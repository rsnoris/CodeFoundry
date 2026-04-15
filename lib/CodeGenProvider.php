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
     * Return the default (first) available provider id, or '' if none.
     */
    public static function defaultProviderId(): string
    {
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

        if (empty($result['choices']) || !isset($result['choices'][0]['message']['content'])) {
            throw new \RuntimeException(
                'Unexpected response from ' . $cfg['label'] . ' API.'
            );
        }

        return [
            'content' => $result['choices'][0]['message']['content'],
            'tokens'  => (int)($result['usage']['total_tokens'] ?? 0),
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
}
