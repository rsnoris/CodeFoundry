<?php
declare(strict_types=1);

/**
 * CodeFoundry – CodeGenProvider
 *
 * Provider abstraction used by IDE Code Generation and VIRAL agents.
 */
class CodeGenProvider
{
    // ── Public API ─────────────────────────────────────────────────────────

    /**
     * Return every provider descriptor with an 'available' flag and 'id' key
     * injected. A provider is "available" when it has a configured API key.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function all(): array
    {
        $result = [];
        foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
            $result[$id] = array_merge($cfg, [
                'id'        => $id,
                'available' => self::isAvailable($cfg),
            ]);
        }
        return $result;
    }

    /**
     * Return only available providers.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function available(): array
    {
        return array_filter(self::all(), fn($p) => $p['available']);
    }

    /**
     * Determine whether a provider id is known and available.
     */
    public static function isProviderAvailable(string $providerId): bool
    {
        $providers = CF_CODEGEN_PROVIDERS;
        if (!isset($providers[$providerId])) {
            return false;
        }
        return self::isAvailable($providers[$providerId]);
    }

    /**
     * Validate that a model id belongs to the given provider.
     */
    public static function isValidModel(string $providerId, string $model): bool
    {
        $providers = CF_CODEGEN_PROVIDERS;
        if (!isset($providers[$providerId])) {
            return false;
        }
        foreach ($providers[$providerId]['models'] as $m) {
            if ($m['id'] === $model) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the default provider id.
     * Prefer OpenAI when available, otherwise return the first available provider.
     */
    public static function defaultProviderId(): string
    {
        $providerId = 'openai';
        if (isset(CF_CODEGEN_PROVIDERS[$providerId]) && self::isAvailable(CF_CODEGEN_PROVIDERS[$providerId])) {
            return $providerId;
        }
        foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
            if (self::isAvailable($cfg)) {
                return $id;
            }
        }
        return '';
    }

    /**
     * Backward-compatible alias for the default provider.
     */
    public static function defaultFreeProviderId(): string
    {
        return self::defaultProviderId();
    }

    /**
     * Return true for providers marked as free-tier.
     */
    public static function isFreeTierProvider(string $providerId): bool
    {
        $providers = CF_CODEGEN_PROVIDERS;
        if (!isset($providers[$providerId]) || !is_array($providers[$providerId])) {
            return false;
        }
        return !empty($providers[$providerId]['free_tier']);
    }

    /**
     * Return true for providers that do not require an API key.
     */
    public static function isNoKeyProvider(string $providerId): bool
    {
        $providers = CF_CODEGEN_PROVIDERS;
        if (!isset($providers[$providerId]) || !is_array($providers[$providerId])) {
            return false;
        }
        return !empty($providers[$providerId]['no_api_key']);
    }

    /**
     * Return ordered candidate providers for the current request context.
     *
     * @param string $preferredProviderId Preferred provider id when explicitly requested.
     * @return array<int,string>
     */
    public static function candidateProviderIds(string $preferredProviderId = ''): array
    {
        if ($preferredProviderId !== '' && self::isProviderAvailable($preferredProviderId)) {
            return [$preferredProviderId];
        }
        $defaultProvider = self::defaultProviderId();
        return $defaultProvider !== '' ? [$defaultProvider] : [];
    }

    /**
     * Return the provider's default model id or an empty string.
     *
     * Resolution:
     *  1) `default_model`
     *  2) first `models[*].id`
     *  3) ''
     */
    public static function defaultModelForProvider(string $providerId): string
    {
        if (!isset(CF_CODEGEN_PROVIDERS[$providerId]) || !is_array(CF_CODEGEN_PROVIDERS[$providerId])) {
            return '';
        }
        $cfg = CF_CODEGEN_PROVIDERS[$providerId];
        return (string)($cfg['default_model'] ?? ($cfg['models'][0]['id'] ?? ''));
    }

    /**
     * Try providers in order until one succeeds.
     *
     * @param array<int,string> $providerIds
     * @return array{content:string,tokens:int,provider:string,model:string}
     */
    public static function callWithFallback(
        array $providerIds,
        string $requestedModel,
        array $messages,
        int $maxTokens = 2048,
        float $temperature = 0.2
    ): array {
        $attemptErrors = [];
        foreach ($providerIds as $providerId) {
            if (!self::isProviderAvailable($providerId)) {
                continue;
            }

            // Respect an explicit model choice when it is valid for this provider;
            // otherwise fall back to the provider default to keep generation working.
            $model = '';
            if ($requestedModel !== '' && self::isValidModel($providerId, $requestedModel)) {
                $model = $requestedModel;
            } else {
                $model = self::defaultModelForProvider($providerId);
            }

            if ($model === '') {
                $attemptErrors[] = $providerId . ': No valid model available for provider';
                continue;
            }

            try {
                $result = self::call($providerId, $model, $messages, $maxTokens, $temperature);
                return [
                    'content'  => $result['content'],
                    'tokens'   => $result['tokens'],
                    'provider' => $providerId,
                    'model'    => $model,
                ];
            } catch (\Throwable $e) {
                $attemptErrors[] = $providerId . ': ' . $e->getMessage();
            }
        }

        $suffix = '';
        if (!empty($attemptErrors)) {
            $suffix = ' Attempts: ' . implode(' | ', $attemptErrors);
        }
        throw new \RuntimeException('All ' . count($providerIds) . ' provider attempt(s) failed.' . $suffix);
    }

    /**
     * Call the specified provider/model with a chat-completions request.
     *
     * @param  string  $providerId  One of the keys in CF_CODEGEN_PROVIDERS
     * @param  string  $model       Model id (validated by caller)
     * @param  array   $messages    OpenAI-style messages array
     * @param  int     $maxTokens   Maximum tokens to generate
     * @param  float   $temperature Sampling temperature
     * @return array{content:string, tokens:int}
     * @throws \RuntimeException on network or API error
     * @throws \InvalidArgumentException on unknown provider
     */
    public static function call(
        string $providerId,
        string $model,
        array  $messages,
        int    $maxTokens   = 2048,
        float  $temperature = 0.2
    ): array {
        $cfg = self::resolveConfig($providerId);
        $format = strtolower((string)($cfg['format'] ?? 'openai'));

        // Build the endpoint URL
        $url = $cfg['api_url'];
        if (!empty($cfg['model_in_url'])) {
            // Providers like Gemini can embed model in URL path
            $url = str_replace('{model}', rawurlencode($model), $url);
        }

        // Build request headers
        $headers = ['Content-Type: application/json'];
        $apiKey  = self::resolveApiKey($cfg);
        if (!empty($cfg['auth_in_query']) && $apiKey !== '') {
            $queryKey = (string)$cfg['auth_in_query'];
            $joinChar = str_contains($url, '?') ? '&' : '?';
            $url .= $joinChar . rawurlencode($queryKey) . '=' . rawurlencode($apiKey);
        } elseif ($apiKey !== '') {
            $authHeader = (string)($cfg['auth_header'] ?? 'Authorization');
            $authScheme = (string)($cfg['auth_scheme'] ?? 'Bearer');
            $headers[]  = $authHeader . ': ' . ($authScheme !== '' ? ($authScheme . ' ') : '') . $apiKey;
        }
        if (!empty($cfg['extra_headers'])) {
            foreach ($cfg['extra_headers'] as $h) {
                $headers[] = $h;
            }
        }

        $payload = self::buildPayload($format, $model, $messages, $maxTokens, $temperature);

        $ctx = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => implode("\r\n", $headers),
                'content'       => json_encode($payload),
                'timeout'       => 45,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $ctx);

        if ($response === false) {
            throw new \RuntimeException(
                'Failed to reach ' . $cfg['label'] . ' API. Please try again.'
            );
        }

        $statusCode = 0;
        if (!empty($http_response_header) && is_array($http_response_header)) {
            foreach ($http_response_header as $h) {
                if (preg_match('#^HTTP/\S+\s+(\d{3})#i', $h, $m)) {
                    $statusCode = (int)$m[1];
                    break;
                }
            }
        }

        $result = json_decode($response, true);

        // Guard against non-JSON responses (e.g. HTML error pages from a dead endpoint).
        if ($result === null) {
            throw new \RuntimeException(
                'Could not parse response from ' . $cfg['label'] . ' API. The service may be temporarily unavailable.'
            );
        }

        // Surface API-level error messages across provider formats.
        if (isset($result['error'])) {
            $errMsg = is_array($result['error'])
                ? ($result['error']['message'] ?? 'API error')
                : (string)$result['error'];
            throw new \RuntimeException($cfg['label'] . ' error: ' . $errMsg);
        }
        if ($statusCode >= 400) {
            $statusMsg = $cfg['label'] . ' returned HTTP ' . $statusCode . '.';
            if (isset($result['message']) && is_string($result['message']) && trim($result['message']) !== '') {
                $statusMsg .= ' ' . trim($result['message']);
            }
            throw new \RuntimeException($statusMsg);
        }

        $content = self::extractContent($result, $format);
        if ($content === '') {
            throw new \RuntimeException(
                'No content found in response from ' . $cfg['label'] . ' API.'
            );
        }

        $tokens = self::extractTokens($result);

        return [
            'content' => $content,
            'tokens'  => $tokens,
        ];
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Look up provider config and resolve optional URL override from env.
     *
     * @return array<string, mixed>
     * @throws \InvalidArgumentException
     */
    private static function resolveConfig(string $providerId): array
    {
        $providers = CF_CODEGEN_PROVIDERS;
        if (!isset($providers[$providerId])) {
            throw new \InvalidArgumentException("Unknown CodeGen provider: {$providerId}");
        }
        $cfg = $providers[$providerId];

        // Allow overriding the base URL via key file or env.
        if (!empty($cfg['api_url_env'])) {
            $envUrl = trim((string)cf_load_key((string)$cfg['api_url_env']));
            if ($envUrl !== '') {
                $envUrl = rtrim($envUrl, '/');
                $suffix = (string)($cfg['api_url_suffix'] ?? '');
                $parsedPath = parse_url($envUrl, PHP_URL_PATH);
                $path = ($parsedPath === false || $parsedPath === null) ? '' : (string)$parsedPath;
                if ($suffix !== '' && ($path === '' || $path === '/')) {
                    $cfg['api_url'] = $envUrl . $suffix;
                } else {
                    $cfg['api_url'] = $envUrl;
                }
            }
        }

        return $cfg;
    }

    /** Resolve the API key from the hard-coded value, a key file, or an env var. */
    private static function resolveApiKey(array $cfg): string
    {
        if (!empty($cfg['api_key'])) {
            return (string)$cfg['api_key'];
        }
        if (!empty($cfg['api_key_env'])) {
            $keyName = (string)$cfg['api_key_env'];
            if (!empty($cfg['global_key_only'])) {
                return cf_load_key($keyName);
            }
            $username = '';
            if (session_status() === PHP_SESSION_ACTIVE) {
                $username = $_SESSION['cf_user']['username'] ?? '';
            }
            if (is_string($username) && $username !== '') {
                return cf_load_user_key($username, $keyName);
            }
            return cf_load_key($keyName);
        }
        return '';
    }

    /** Return true when the provider has a usable API key. */
    private static function isAvailable(array $cfg): bool
    {
        if (!empty($cfg['no_api_key'])) {
            return true;
        }
        return self::resolveApiKey($cfg) !== '';
    }

    /**
     * Build provider-specific request payload.
     *
     * @return array<string,mixed>
     */
    private static function buildPayload(
        string $format,
        string $model,
        array $messages,
        int $maxTokens,
        float $temperature
    ): array {
        if ($format === 'anthropic') {
            $normalized = self::normalizeMessagesForAnthropic($messages);
            return [
                'model'       => $model,
                'max_tokens'  => $maxTokens,
                'temperature' => $temperature,
                'system'      => $normalized['system'],
                'messages'    => $normalized['messages'],
            ];
        }

        if ($format === 'gemini') {
            $normalized = self::normalizeMessagesForGemini($messages);
            $payload = [
                'contents' => $normalized['contents'],
                'generationConfig' => [
                    'temperature'     => $temperature,
                    'maxOutputTokens' => $maxTokens,
                ],
            ];
            if ($normalized['system'] !== '') {
                $payload['systemInstruction'] = [
                    'parts' => [
                        ['text' => $normalized['system']],
                    ],
                ];
            }
            return $payload;
        }

        return [
            'model'       => $model,
            'max_tokens'  => $maxTokens,
            'temperature' => $temperature,
            'messages'    => $messages,
        ];
    }

    /**
     * Convert OpenAI-style messages to Anthropic message structure.
     *
     * @return array{system:string,messages:array<int,array<string,mixed>>}
     */
    private static function normalizeMessagesForAnthropic(array $messages): array
    {
        $systemParts = [];
        $out = [];
        foreach ($messages as $msg) {
            if (!is_array($msg)) {
                continue;
            }
            $role = (string)($msg['role'] ?? '');
            $content = trim((string)($msg['content'] ?? ''));
            if ($content === '') {
                continue;
            }
            if ($role === 'system') {
                $systemParts[] = $content;
                continue;
            }
            $anthropicRole = $role === 'assistant' ? 'assistant' : 'user';
            $out[] = [
                'role' => $anthropicRole,
                'content' => [
                    ['type' => 'text', 'text' => $content],
                ],
            ];
        }
        if (empty($out)) {
            throw new \InvalidArgumentException(
                'No valid messages provided for Anthropic request. Messages must include at least one non-empty "user" or "assistant" message.'
            );
        }
        return [
            'system'   => trim(implode("\n\n", $systemParts)),
            'messages' => $out,
        ];
    }

    /**
     * Convert OpenAI-style messages to Gemini message structure.
     *
     * @return array{system:string,contents:array<int,array<string,mixed>>}
     */
    private static function normalizeMessagesForGemini(array $messages): array
    {
        $systemParts = [];
        $contents = [];
        foreach ($messages as $msg) {
            if (!is_array($msg)) {
                continue;
            }
            $role = (string)($msg['role'] ?? '');
            $content = trim((string)($msg['content'] ?? ''));
            if ($content === '') {
                continue;
            }
            if ($role === 'system') {
                $systemParts[] = $content;
                continue;
            }
            $geminiRole = $role === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role' => $geminiRole,
                'parts' => [
                    ['text' => $content],
                ],
            ];
        }
        if (empty($contents)) {
            throw new \InvalidArgumentException(
                'No valid messages provided for Gemini request. Messages must include at least one non-empty "user" or "assistant" message.'
            );
        }
        return [
            'system'   => trim(implode("\n\n", $systemParts)),
            'contents' => $contents,
        ];
    }

    /**
     * Extract assistant text from multiple API response shapes.
     *
     * Supported sources:
     *  - choices[0].message.content (string)
     *  - choices[0].message.content (array of {type:'text', text:'...'})
     *  - choices[0].text
     *  - output_text
     *  - Anthropic: content[*].text
     *  - Gemini: candidates[0].content.parts[*].text
     */
    private static function extractContent(array $result, string $format = 'openai'): string
    {
        if ($format === 'anthropic' && isset($result['content']) && is_array($result['content'])) {
            $parts = [];
            foreach ($result['content'] as $part) {
                if (!is_array($part)) {
                    continue;
                }
                if (($part['type'] ?? '') === 'text' && isset($part['text']) && is_string($part['text'])) {
                    $parts[] = $part['text'];
                }
            }
            $joined = trim(implode("\n", $parts));
            if ($joined !== '') {
                return $joined;
            }
        }

        if ($format === 'gemini') {
            $parts = $result['candidates'][0]['content']['parts'] ?? null;
            if (is_array($parts)) {
                $txt = [];
                foreach ($parts as $part) {
                    if (is_array($part) && isset($part['text']) && is_string($part['text'])) {
                        $txt[] = $part['text'];
                    }
                }
                $joined = trim(implode("\n", $txt));
                if ($joined !== '') {
                    return $joined;
                }
            }
        }

        if (!isset($result['choices']) || !is_array($result['choices']) || !isset($result['choices'][0]) || !is_array($result['choices'][0])) {
            return '';
        }
        $messageContent = $result['choices'][0]['message']['content'] ?? null;
        if (is_string($messageContent)) {
            return $messageContent;
        }
        if (is_array($messageContent)) {
            $parts = [];
            foreach ($messageContent as $part) {
                if (!is_array($part)) {
                    continue;
                }
                if (($part['type'] ?? '') === 'text' && isset($part['text']) && is_string($part['text'])) {
                    $parts[] = $part['text'];
                }
            }
            $joined = trim(implode("\n", $parts));
            if ($joined !== '') {
                return $joined;
            }
        }

        $choiceText = $result['choices'][0]['text'] ?? null;
        if (is_string($choiceText) && trim($choiceText) !== '') {
            return $choiceText;
        }

        $outputText = $result['output_text'] ?? null;
        if (is_string($outputText) && trim($outputText) !== '') {
            return $outputText;
        }

        return '';
    }

    /** Extract total token usage from known provider response shapes. */
    private static function extractTokens(array $result): int
    {
        $usage = $result['usage'] ?? [];
        if (is_array($usage)) {
            if (isset($usage['total_tokens'])) {
                return (int)$usage['total_tokens'];
            }
            if (isset($usage['prompt_tokens']) || isset($usage['completion_tokens'])) {
                return (int)($usage['prompt_tokens'] ?? 0) + (int)($usage['completion_tokens'] ?? 0);
            }
            if (isset($usage['input_tokens']) || isset($usage['output_tokens'])) {
                return (int)($usage['input_tokens'] ?? 0) + (int)($usage['output_tokens'] ?? 0);
            }
        }

        $usageMeta = $result['usageMetadata'] ?? null;
        if (is_array($usageMeta)) {
            if (isset($usageMeta['totalTokenCount'])) {
                return (int)$usageMeta['totalTokenCount'];
            }
            if (isset($usageMeta['promptTokenCount']) || isset($usageMeta['candidatesTokenCount'])) {
                return (int)($usageMeta['promptTokenCount'] ?? 0) + (int)($usageMeta['candidatesTokenCount'] ?? 0);
            }
        }

        return 0;
    }
}
