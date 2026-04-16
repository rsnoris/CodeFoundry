<?php
declare(strict_types=1);

/**
 * CodeFoundry – CodeGenProvider
 *
 * Unified abstraction over all supported LLM inference backends.
 * Every provider uses the OpenAI Chat Completions wire format
 * (POST /v1/chat/completions with the same JSON shape), so the
 * only differences are the endpoint URL, the auth header, and
 * optional extra headers (e.g. OpenRouter's HTTP-Referer).
 *
 * HuggingFace is the one exception: the model id is embedded in
 * the URL path instead of the JSON body (model_in_url = true).
 */
class CodeGenProvider
{
    private const PREFERRED_DEFAULT_PROVIDER_ID = 'openrouter';

    // ── Public API ─────────────────────────────────────────────────────────

    /**
     * Return every provider descriptor with an 'available' flag and 'id' key
     * injected.  A provider is "available" when it has a configured API key
     * or is marked as local (Ollama).
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
     * Return only available providers (key set or local).
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
     * Returns true unconditionally for the 'ollama' provider so that
     * locally-pulled models not listed in the config still work.
     */
    public static function isValidModel(string $providerId, string $model): bool
    {
        if ($providerId === 'ollama') {
            return $model !== '';
        }
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
     * Prefer OpenRouter when available, otherwise return the first available provider.
     */
    public static function defaultProviderId(): string
    {
        $preferredId = self::PREFERRED_DEFAULT_PROVIDER_ID;
        if (isset(CF_CODEGEN_PROVIDERS[$preferredId]) && self::isAvailable(CF_CODEGEN_PROVIDERS[$preferredId])) {
            return $preferredId;
        }
        foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
            if (self::isAvailable($cfg)) {
                return $id;
            }
        }
        return '';
    }

    /**
     * Return the default free-tier provider id (first provider with free_tier = true),
     * or '' if none is configured.
     */
    public static function defaultFreeProviderId(): string
    {
        foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
            if (!empty($cfg['free_tier']) && self::isAvailable($cfg)) {
                return $id;
            }
        }
        return '';
    }

    /**
     * Return true when the given provider is marked as free_tier.
     */
    public static function isFreeTierProvider(string $providerId): bool
    {
        $providers = CF_CODEGEN_PROVIDERS;
        return !empty($providers[$providerId]['free_tier']);
    }

    /**
     * Return true when the provider does not require a user API key.
     *
     * A provider qualifies when marked as `no_key_required` or `local`.
     * Used to determine free-plan-safe provider choices.
     */
    public static function isNoKeyProvider(string $providerId): bool
    {
        $providers = CF_CODEGEN_PROVIDERS;
        if (!isset($providers[$providerId])) {
            return false;
        }
        return !empty($providers[$providerId]['no_key_required']) || !empty($providers[$providerId]['local']);
    }

    /**
     * Return ordered candidate providers for the current request context.
     *
     * Ordering:
     *  - preferred provider first (if provided and available)
     *  - free plan: free-tier, then no-key/local, then other available providers
     *  - paid plan: default provider first, then all available providers
     *
     * @return array<int,string>
     */
    public static function candidateProviderIds(bool $freePlan, string $preferredProviderId = ''): array
    {
        $ordered = [];
        $push = static function (string $id) use (&$ordered): void {
            if ($id === '') {
                return;
            }
            if (!self::isProviderAvailable($id)) {
                return;
            }
            if (!in_array($id, $ordered, true)) {
                $ordered[] = $id;
            }
        };

        if ($preferredProviderId !== '') {
            $push($preferredProviderId);
        }

        if ($freePlan) {
            foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
                if (!empty($cfg['free_tier'])) {
                    $push((string)$id);
                }
            }
            foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
                if (!empty($cfg['no_key_required']) || !empty($cfg['local'])) {
                    $push((string)$id);
                }
            }
            foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
                $push((string)$id);
            }
        } else {
            $push(self::defaultProviderId());
            foreach (CF_CODEGEN_PROVIDERS as $id => $cfg) {
                $push((string)$id);
            }
        }

        return $ordered;
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

        // Build the endpoint URL
        $url = $cfg['api_url'];
        if (!empty($cfg['model_in_url'])) {
            // HuggingFace: model is part of the URL path
            $url = str_replace('{model}', rawurlencode($model), $url);
        }

        // Build request headers
        $headers = ['Content-Type: application/json'];
        $apiKey  = self::resolveApiKey($cfg);
        if ($apiKey !== '') {
            $headers[] = 'Authorization: Bearer ' . $apiKey;
        }
        if (!empty($cfg['extra_headers'])) {
            foreach ($cfg['extra_headers'] as $h) {
                $headers[] = $h;
            }
        }

        // Build payload (OpenAI-compatible format)
        $payload = [
            'model'       => $model,
            'max_tokens'  => $maxTokens,
            'temperature' => $temperature,
            'messages'    => $messages,
        ];

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

        // Surface API-level error messages (handles both OpenAI and OpenRouter formats).
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

        $content = self::extractContent($result);
        if ($content === '') {
            throw new \RuntimeException(
                'Unexpected response from ' . $cfg['label'] . ' API.'
            );
        }

        $usage = $result['usage'] ?? [];
        $tokens = 0;
        if (is_array($usage)) {
            if (isset($usage['total_tokens'])) {
                $tokens = (int)$usage['total_tokens'];
            } elseif (isset($usage['prompt_tokens']) || isset($usage['completion_tokens'])) {
                $tokens = (int)($usage['prompt_tokens'] ?? 0) + (int)($usage['completion_tokens'] ?? 0);
            } elseif (isset($usage['input_tokens']) || isset($usage['output_tokens'])) {
                $tokens = (int)($usage['input_tokens'] ?? 0) + (int)($usage['output_tokens'] ?? 0);
            }
        }

        return [
            'content' => $content,
            'tokens'  => $tokens,
        ];
    }

    // ── Private helpers ────────────────────────────────────────────────────

    /**
     * Look up provider config and resolve the Ollama URL override from env.
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

        // Allow overriding the base URL via key file or env (useful for Ollama pointing at a remote host)
        if (!empty($cfg['api_url_env'])) {
            $envUrl = cf_load_key($cfg['api_url_env']);
            if ($envUrl !== '') {
                $cfg['api_url'] = rtrim($envUrl, '/');
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

    /** Return true when the provider has a usable API key, is local, or requires no key. */
    private static function isAvailable(array $cfg): bool
    {
        if (!empty($cfg['local']) || !empty($cfg['no_key_required'])) {
            return true;
        }
        return self::resolveApiKey($cfg) !== '';
    }

    /**
     * Extract assistant text from multiple API response shapes.
     *
     * Supported sources:
     *  - choices[0].message.content (string)
     *  - choices[0].message.content (array of {type:'text', text:'...'})
     *  - choices[0].text
     *  - output_text
     */
    private static function extractContent(array $result): string
    {
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
}
