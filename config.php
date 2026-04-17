<?php
/**
 * CodeFoundry – PHP site configuration.
 *
 * Include this file at the top of any PHP page that needs site-wide settings,
 * or let includes/header.php pull it in automatically.
 */

declare(strict_types=1);

// ---------------------------------------------------------------------------
// Paths
// ---------------------------------------------------------------------------

/** Absolute filesystem path to the repository root (no trailing slash). */
define('CF_ROOT', dirname(__FILE__));

/** Path to the shared navigation data. */
define('CF_NAV_JSON', CF_ROOT . '/data/navigation.json');

/**
 * Absolute filesystem path to the API-key storage folder (no trailing slash).
 *
 * The folder is placed ONE level above the webroot so it is never accessible
 * over HTTP.  Each key is stored as a plain-text file whose name matches the
 * environment-variable name (e.g. "GROQ_API_KEY", "OPENAI_API_KEY").
 *
 * Layout example:
 *   /home/user/Cf-Config-keys/
 *       GROQ_API_KEY
 *       OPENROUTER_API_KEY
 *       HF_API_KEY
 *       TOGETHER_API_KEY
 *       OPENAI_API_KEY
 *       OLLAMA_URL
 *       STRIPE_PUBLISHABLE_KEY
 *       STRIPE_SECRET_KEY
 *       PAYPAL_CLIENT_ID
 *       PAYPAL_CLIENT_SECRET
 *       PAYPAL_MODE
 *       GITHUB_CLIENT_ID
 *       GITHUB_CLIENT_SECRET
 *       GOOGLE_CLIENT_ID
 *       GOOGLE_CLIENT_SECRET
 *       LINKEDIN_CLIENT_ID
 *       LINKEDIN_CLIENT_SECRET
 *
 * To change the location, adjust the path below.
 */
define('CF_KEYS_DIR', CF_ROOT . '/Cf-Config-keys');
define('CF_USERS_STORAGE_DIR', CF_KEYS_DIR . '/Users');

// ---------------------------------------------------------------------------
// Site metadata
// ---------------------------------------------------------------------------

define('CF_SITE_NAME',  'CodeFoundry');
define('CF_SITE_EMAIL', 'hello@codefoundry.cloud');
define('CF_SITE_PHONE', '+1 234 567 890');
define('CF_SITE_ADDR',  '156 Foundry Ave., Suite 502, New York, NY, USA');
define('CF_COPYRIGHT',  '&copy; 2024 CodeFoundry. All rights reserved.');

// ---------------------------------------------------------------------------
// User Accounts
// ---------------------------------------------------------------------------

/**
 * Array of user accounts.  Each entry requires:
 *   username      – login handle (plain text)
 *   password_hash – bcrypt hash produced by password_hash($plain, PASSWORD_BCRYPT)
 *   display       – (optional) name shown in the UI
 *   role          – (optional) 'admin' | 'user'
 *
 * To generate a hash in PHP:
 *   php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT);"
 */
define('CF_USERS', [
    [
        'username'      => 'admin',
        // ⚠  REQUIRED: Replace this placeholder with a real bcrypt hash before deploying.
        // Generate one with: php -r "echo password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT);"
        // Until this is replaced, login will fail for all users.
        'password_hash' => '$2y$12$YourHashHere',
        'display'       => 'Admin',
        'role'          => 'admin',
    ],
    [
        'username'      => 'roshnori',
        'password_hash' => '$2y$10$NxR.XtTCgg.yTwu6hNbaKeh4T/YWaLyxYxVh3giFUi8I8iBudAsxS',
        'display'       => 'roshnori',
        'role'          => 'admin',
    ],
]);

// ---------------------------------------------------------------------------
// Plans
// ---------------------------------------------------------------------------

define('CF_PLANS', [
    'free'       => ['label' => 'Free',       'tokens_limit' => 1000,   'price' => 0,     'price_annual' => 0,   'price_label' => 'Free'],
    'starter'    => ['label' => 'Starter',    'tokens_limit' => 10000,  'price' => 15,    'price_annual' => 12,  'price_label' => '$15/mo'],
    'pro'        => ['label' => 'Pro',        'tokens_limit' => 50000,  'price' => 29.99, 'price_annual' => 25,  'price_label' => '$29.99/mo'],
    'enterprise' => ['label' => 'Enterprise', 'tokens_limit' => 500000, 'price' => 0,     'price_annual' => 0,   'price_label' => 'Contact us'],
]);

// ---------------------------------------------------------------------------
// Data file paths
// ---------------------------------------------------------------------------

define('CF_DATA_USERS',           CF_USERS_STORAGE_DIR . '/users.json');
define('CF_DATA_TOKEN_HISTORY',   CF_USERS_STORAGE_DIR . '/token_history.json');
define('CF_DATA_PROJECTS',        CF_USERS_STORAGE_DIR . '/projects.json');
define('CF_DATA_PAYMENTS',        CF_USERS_STORAGE_DIR . '/payments.json');
define('CF_DATA_AUDIT_LOG',       CF_USERS_STORAGE_DIR . '/audit_log.json');
define('CF_DATA_PAGE_VIEWS',      CF_USERS_STORAGE_DIR . '/page_views.json');
define('CF_DATA_SUPPORT_TICKETS', CF_USERS_STORAGE_DIR . '/support_tickets.json');
define('CF_DATA_CHAT_SESSIONS',   CF_USERS_STORAGE_DIR . '/chat_sessions.json');
define('CF_DATA_CHAT_MESSAGES',   CF_USERS_STORAGE_DIR . '/chat_messages.json');

// ---------------------------------------------------------------------------
// AI / CodeGen
// ---------------------------------------------------------------------------

/** OpenAI API key for the CodeGen feature. Set via key file or environment variable. */
define('CF_OPENAI_KEY', cf_load_key('OPENAI_API_KEY'));

/**
 * AI provider registry for Code Generation + VIRAL agents.
 * Keys:
 *   label             – human-readable provider name shown in the UI
 *   api_url           – full endpoint URL (or base + api_url_suffix when overridden by env)
 *   api_key_env       – (optional) key/env variable name for provider auth
 *   models            – ordered list of {id, label} model descriptors
 *   default_model     – model ID pre-selected in the UI
 *   format            – request/response format handler: openai|anthropic|gemini
 *   api_url_env       – (optional) key/env var to override endpoint URL/base
 *   api_url_suffix    – (optional) appended when api_url_env contains only scheme+host
 *   no_api_key        – (optional) true for providers that do not require API keys
 */
define('CF_CODEGEN_PROVIDERS', [
    'openai' => [
        'label'         => 'OpenAI (ChatGPT)',
        'api_url'       => 'https://api.openai.com/v1/chat/completions',
        'api_key_env'   => 'OPENAI_API_KEY',
        'format'        => 'openai',
        'models'        => [
            ['id' => 'gpt-4o-mini',   'label' => 'GPT-4o Mini'],
            ['id' => 'gpt-4o',        'label' => 'GPT-4o'],
            ['id' => 'gpt-4-turbo',   'label' => 'GPT-4 Turbo'],
            ['id' => 'gpt-3.5-turbo', 'label' => 'GPT-3.5 Turbo'],
            ['id' => 'o1-mini',       'label' => 'o1 Mini'],
            ['id' => 'o3-mini',       'label' => 'o3 Mini'],
        ],
        'default_model' => 'gpt-4o-mini',
    ],
    'anthropic' => [
        'label'         => 'Anthropic (Claude)',
        'api_url'       => 'https://api.anthropic.com/v1/messages',
        'api_key_env'   => 'ANTHROPIC_API_KEY',
        'format'        => 'anthropic',
        'auth_header'   => 'x-api-key',
        'auth_scheme'   => '',
        'extra_headers' => [
            'anthropic-version: 2023-06-01',
        ],
        'models'        => [
            ['id' => 'claude-3-5-haiku-latest',  'label' => 'Claude 3.5 Haiku'],
            ['id' => 'claude-3-5-sonnet-latest', 'label' => 'Claude 3.5 Sonnet'],
            ['id' => 'claude-3-opus-latest',     'label' => 'Claude 3 Opus'],
        ],
        'default_model' => 'claude-3-5-haiku-latest',
    ],
    'gemini' => [
        'label'         => 'Google Gemini',
        'api_url'       => 'https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent',
        'api_key_env'   => 'GEMINI_API_KEY',
        'format'        => 'gemini',
        'model_in_url'  => true,
        'auth_in_query' => 'key',
        'api_url_env'   => 'GEMINI_BASE_URL',
        'api_url_suffix'=> '/v1beta/models/{model}:generateContent',
        'models'        => [
            ['id' => 'gemini-1.5-flash', 'label' => 'Gemini 1.5 Flash'],
            ['id' => 'gemini-1.5-pro',   'label' => 'Gemini 1.5 Pro'],
            ['id' => 'gemini-2.0-flash', 'label' => 'Gemini 2.0 Flash'],
        ],
        'default_model' => 'gemini-1.5-flash',
    ],
    'groq' => [
        'label'         => 'Groq',
        'api_url'       => 'https://api.groq.com/openai/v1/chat/completions',
        'api_key_env'   => 'GROQ_API_KEY',
        'format'        => 'openai',
        'models'        => [
            ['id' => 'llama-3.1-8b-instant',     'label' => 'Llama 3.1 8B Instant'],
            ['id' => 'llama-3.3-70b-versatile',  'label' => 'Llama 3.3 70B Versatile'],
            ['id' => 'mixtral-8x7b-32768',       'label' => 'Mixtral 8x7B'],
            ['id' => 'gemma2-9b-it',             'label' => 'Gemma 2 9B'],
        ],
        'default_model' => 'llama-3.1-8b-instant',
    ],
    'openrouter' => [
        'label'         => 'OpenRouter',
        'api_url'       => 'https://openrouter.ai/api/v1/chat/completions',
        'api_key_env'   => 'OPENROUTER_API_KEY',
        'format'        => 'openai',
        'extra_headers' => [
            'HTTP-Referer: https://codefoundry.cloud',
            'X-Title: CodeFoundry',
        ],
        'models'        => [
            ['id' => 'openai/gpt-4o-mini',            'label' => 'OpenAI GPT-4o Mini (via OpenRouter)'],
            ['id' => 'anthropic/claude-3.5-haiku',    'label' => 'Claude 3.5 Haiku (via OpenRouter)'],
            ['id' => 'meta-llama/llama-3.1-8b-instruct:free', 'label' => 'Llama 3.1 8B Instruct (Free)'],
            ['id' => 'google/gemini-flash-1.5',       'label' => 'Gemini Flash 1.5 (via OpenRouter)'],
        ],
        'default_model' => 'openai/gpt-4o-mini',
    ],
    'huggingface' => [
        'label'         => 'Hugging Face',
        'api_url'       => 'https://router.huggingface.co/v1/chat/completions',
        'api_key_env'   => 'HF_API_KEY',
        'format'        => 'openai',
        'models'        => [
            ['id' => 'meta-llama/Meta-Llama-3.1-8B-Instruct',  'label' => 'Llama 3.1 8B Instruct'],
            ['id' => 'mistralai/Mistral-7B-Instruct-v0.3',     'label' => 'Mistral 7B Instruct v0.3'],
            ['id' => 'Qwen/Qwen2.5-Coder-32B-Instruct',        'label' => 'Qwen2.5 Coder 32B'],
        ],
        'default_model' => 'meta-llama/Meta-Llama-3.1-8B-Instruct',
    ],
    'together' => [
        'label'         => 'Together AI',
        'api_url'       => 'https://api.together.xyz/v1/chat/completions',
        'api_key_env'   => 'TOGETHER_API_KEY',
        'format'        => 'openai',
        'models'        => [
            ['id' => 'meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo', 'label' => 'Llama 3.1 8B Instruct Turbo'],
            ['id' => 'mistralai/Mixtral-8x7B-Instruct-v0.1',        'label' => 'Mixtral 8x7B Instruct'],
            ['id' => 'Qwen/Qwen2.5-Coder-32B-Instruct',             'label' => 'Qwen2.5 Coder 32B'],
        ],
        'default_model' => 'meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo',
    ],
    'ollama' => [
        'label'          => 'Ollama (Local)',
        'api_url'        => 'http://localhost:11434/v1/chat/completions',
        'api_url_env'    => 'OLLAMA_URL',
        'api_url_suffix' => '/v1/chat/completions',
        'format'         => 'openai',
        'no_api_key'     => true,
        'models'         => [
            ['id' => 'llama3.2', 'label' => 'Llama 3.2'],
            ['id' => 'mistral',  'label' => 'Mistral'],
            ['id' => 'qwen2.5-coder', 'label' => 'Qwen2.5 Coder'],
            ['id' => 'phi3',     'label' => 'Phi-3'],
        ],
        'default_model'  => 'llama3.2',
    ],
]);

// ---------------------------------------------------------------------------
// Stripe
// ---------------------------------------------------------------------------

/**
 * Stripe API keys.
 * Register at: https://dashboard.stripe.com/apikeys
 * Store keys in Cf-Config-keys/STRIPE_PUBLISHABLE_KEY and Cf-Config-keys/STRIPE_SECRET_KEY,
 * or set STRIPE_PUBLISHABLE_KEY and STRIPE_SECRET_KEY as environment variables.
 */
define('CF_STRIPE_PUBLISHABLE_KEY', cf_load_key('STRIPE_PUBLISHABLE_KEY'));
define('CF_STRIPE_SECRET_KEY',      cf_load_key('STRIPE_SECRET_KEY'));

// ---------------------------------------------------------------------------
// PayPal
// ---------------------------------------------------------------------------

/**
 * PayPal REST API credentials.
 * Register at: https://developer.paypal.com/dashboard/
 * Store keys in Cf-Config-keys/PAYPAL_CLIENT_ID, Cf-Config-keys/PAYPAL_CLIENT_SECRET,
 * and Cf-Config-keys/PAYPAL_MODE, or set them as environment variables.
 * PAYPAL_MODE defaults to 'sandbox' if not set.
 */
define('CF_PAYPAL_CLIENT_ID',     cf_load_key('PAYPAL_CLIENT_ID'));
define('CF_PAYPAL_CLIENT_SECRET', cf_load_key('PAYPAL_CLIENT_SECRET'));
define('CF_PAYPAL_MODE',          cf_load_key('PAYPAL_MODE', 'sandbox'));

// ---------------------------------------------------------------------------
// OAuth / Social Login
// ---------------------------------------------------------------------------

/**
 * GitHub OAuth app credentials.
 * Register at: https://github.com/settings/developers
 * Set the Authorization callback URL to: https://yourdomain.com/Login/oauth_callback.php
 * Store keys in Cf-Config-keys/GITHUB_CLIENT_ID and Cf-Config-keys/GITHUB_CLIENT_SECRET,
 * or set them as environment variables.
 */
define('CF_OAUTH_GITHUB_CLIENT_ID',     cf_load_key('GITHUB_CLIENT_ID'));
define('CF_OAUTH_GITHUB_CLIENT_SECRET', cf_load_key('GITHUB_CLIENT_SECRET'));

/**
 * Google OAuth app credentials.
 * Register at: https://console.cloud.google.com/apis/credentials
 * Set the Authorized redirect URI to: https://yourdomain.com/Login/oauth_callback.php
 * Store keys in Cf-Config-keys/GOOGLE_CLIENT_ID and Cf-Config-keys/GOOGLE_CLIENT_SECRET,
 * or set them as environment variables.
 */
define('CF_OAUTH_GOOGLE_CLIENT_ID',     cf_load_key('GOOGLE_CLIENT_ID'));
define('CF_OAUTH_GOOGLE_CLIENT_SECRET', cf_load_key('GOOGLE_CLIENT_SECRET'));

/**
 * LinkedIn OAuth 2.0 / OpenID Connect credentials.
 * Register at: https://www.linkedin.com/developers/apps
 * Set the Authorized redirect URL to: https://yourdomain.com/Login/oauth_callback.php
 * Required scopes: openid, profile, email
 * Store keys in Cf-Config-keys/LINKEDIN_CLIENT_ID and Cf-Config-keys/LINKEDIN_CLIENT_SECRET,
 * or set them as environment variables.
 */
define('CF_OAUTH_LINKEDIN_CLIENT_ID',     cf_load_key('LINKEDIN_CLIENT_ID'));
define('CF_OAUTH_LINKEDIN_CLIENT_SECRET', cf_load_key('LINKEDIN_CLIENT_SECRET'));

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

/**
 * Load a configuration key / secret by name.
 *
 * Resolution order:
 *   1. File:    CF_KEYS_DIR . '/' . $name  (plain-text, whitespace stripped)
 *   2. Env var: getenv($name)
 *   3. $default (empty string if omitted)
 *
 * This lets you store all API keys in the Cf-Config-keys folder on the server
 * without ever putting them in source code or setting server-wide env vars.
 *
 * @param  string $name    Filename / env-var name (e.g. 'GROQ_API_KEY')
 * @param  string $default Returned when neither source has a value
 * @return string
 */
function cf_load_key(string $name, string $default = ''): string
{
    $safeName = cf_normalize_key_name($name);
    if ($safeName === '') {
        return $default;
    }

    // 1. Try the key file
    $file = CF_KEYS_DIR . '/' . $safeName;
    if (is_file($file) && is_readable($file)) {
        $value = trim((string)file_get_contents($file));
        if ($value !== '') {
            return $value;
        }
    }

    // 2. Try OpenrouterConfig.env inside Cf-Config-keys
    $envFileValues = cf_load_env_file(CF_KEYS_DIR . '/OpenrouterConfig.env');
    if (isset($envFileValues[$safeName]) && trim((string)$envFileValues[$safeName]) !== '') {
        return trim((string)$envFileValues[$safeName]);
    }

    // 3. Fall back to environment variable
    $env = getenv($safeName);
    if ($env !== false && $env !== '') {
        return $env;
    }

    return $default;
}

/**
 * Load user-specific key/secret by name from Cf-Config-keys/Users/<username>/.
 *
 * Resolution order:
 *   1) File: <user_dir>/<name>
 *   2) Env file: <user_dir>/OpenrouterConfig.env
 *   3) Global cf_load_key($name)
 */
function cf_load_user_key(string $username, string $name, string $default = ''): string
{
    $safeName = cf_normalize_key_name($name);
    if ($safeName === '') {
        return $default;
    }

    $userDir = cf_user_config_dir($username);
    $file    = $userDir . '/' . $safeName;
    if (is_file($file) && is_readable($file)) {
        $value = trim((string)file_get_contents($file));
        if ($value !== '') {
            return $value;
        }
    }

    $envFileValues = cf_load_env_file($userDir . '/OpenrouterConfig.env');
    if (isset($envFileValues[$safeName]) && trim((string)$envFileValues[$safeName]) !== '') {
        return trim((string)$envFileValues[$safeName]);
    }

    return cf_load_key($safeName, $default);
}

/**
 * Persist a global key file under CF_KEYS_DIR/<name>.
 * Pass an empty string to clear the key (the file is written but empty,
 * causing cf_load_key() to fall through to the next resolution source).
 *
 * @throws \InvalidArgumentException on an invalid key name
 */
function cf_save_key(string $name, string $value): void
{
    $safeName = cf_normalize_key_name($name);
    if ($safeName === '') {
        throw new \InvalidArgumentException('Invalid key name.');
    }
    if (!is_dir(CF_KEYS_DIR)) {
        @mkdir(CF_KEYS_DIR, 0700, true);
    }
    file_put_contents(CF_KEYS_DIR . '/' . $safeName, trim($value), LOCK_EX);
    @chmod(CF_KEYS_DIR . '/' . $safeName, 0600);
}

/** Persist a user-specific key file under Cf-Config-keys/Users/<username>/. */
function cf_save_user_key(string $username, string $name, string $value): void
{
    $safeName = cf_normalize_key_name($name);
    if ($safeName === '') {
        throw new \InvalidArgumentException('Invalid key name.');
    }
    $userDir = cf_user_config_dir($username);
    if (!is_dir($userDir)) {
        @mkdir($userDir, 0700, true);
    }
    file_put_contents($userDir . '/' . $safeName, trim($value));
}

/** Return the absolute config directory for a username. */
function cf_user_config_dir(string $username): string
{
    $raw   = trim($username);
    $safe  = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $raw);
    if ($safe === null || $safe === '') {
        $safe = 'unknown_user';
    }
    $hash = substr(hash('sha256', $raw), 0, 12);
    return CF_USERS_STORAGE_DIR . '/' . $safe . '_' . $hash;
}

/** Parse a simple .env file into an associative array. */
function cf_load_env_file(string $path): array
{
    static $cache = [];
    if (array_key_exists($path, $cache)) {
        return $cache[$path];
    }
    if (!is_file($path) || !is_readable($path)) {
        $cache[$path] = [];
        return [];
    }

    $values = [];
    $lines  = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
    foreach ($lines as $line) {
        $line = trim((string)$line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_starts_with($line, 'export ')) {
            $line = trim(substr($line, 7));
        }
        $parts = explode('=', $line, 2);
        if (count($parts) !== 2) {
            continue;
        }
        $k = trim($parts[0]);
        $v = trim($parts[1]);
        if (strlen($v) >= 2 && ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'")))) {
            $v = substr($v, 1, -1);
        } elseif (str_contains($v, ' #')) {
            $v = rtrim((string)explode(' #', $v, 2)[0]);
        }
        if ($k !== '') {
            $values[$k] = $v;
        }
    }
    $cache[$path] = $values;
    return $values;
}

/** Ensure key/data storage directories and JSON files exist. */
function cf_ensure_storage_layout(): void
{
    $dirs = [CF_KEYS_DIR, CF_USERS_STORAGE_DIR];
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0700, true);
        }
    }

    $jsonFiles = [
        CF_DATA_USERS,
        CF_DATA_TOKEN_HISTORY,
        CF_DATA_PROJECTS,
        CF_DATA_PAYMENTS,
        CF_DATA_AUDIT_LOG,
        CF_DATA_PAGE_VIEWS,
        CF_DATA_SUPPORT_TICKETS,
        CF_DATA_CHAT_SESSIONS,
        CF_DATA_CHAT_MESSAGES,
    ];

    foreach ($jsonFiles as $file) {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            @mkdir($dir, 0700, true);
        }
        if (!is_file($file)) {
            @file_put_contents($file, "[]\n");
        }
    }
}

cf_ensure_storage_layout();

/** Validate and normalize key-file names to prevent path traversal. */
function cf_normalize_key_name(string $name): string
{
    $trimmed = trim($name);
    if ($trimmed === '') {
        return '';
    }
    if (!preg_match('/^[A-Za-z0-9_]+$/', $trimmed)) {
        return '';
    }
    return $trimmed;
}

/**
 * Return the decoded navigation JSON as an associative array.
 * Result is cached in a static variable so the file is read only once.
 *
 * @return array<string, mixed>
 */
function cf_nav_data(): array {
    static $data = null;
    if ($data === null) {
        $json = file_get_contents(CF_NAV_JSON);
        if ($json === false) {
            $data = [];
        } else {
            $data = json_decode($json, true) ?? [];
        }
    }
    return $data;
}

/**
 * Return the primary navigation links array.
 *
 * Each element has keys: label, href, id.
 *
 * @return array<int, array<string, string>>
 */
function cf_nav_links(): array {
    return cf_nav_data()['nav'] ?? [];
}

/**
 * Safely escape a string for HTML output.
 */
function cf_e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Format a geo-location array returned by AuditStore::geoLocate() into a
 * human-readable string such as "New York, NY, US".
 * Returns an empty string when no location data is available.
 *
 * @param  array $geoData  Array with optional keys: city, region, country_code.
 * @return string          Formatted location or empty string.
 */
function cf_format_location(array $geoData): string {
    if (empty($geoData)) {
        return '';
    }
    return implode(', ', array_filter([
        $geoData['city']         ?? '',
        $geoData['region']       ?? '',
        $geoData['country_code'] ?? '',
    ]));
}

/**
 * Format a duration in seconds as a human-readable string.
 * e.g. 3661 → "1h 1m 1s"
 *
 * @param  int    $seconds Non-negative duration in seconds.
 * @return string          Formatted duration string.
 */
function cf_format_duration(int $seconds): string {
    if ($seconds < 0) {
        $seconds = 0;
    }
    $h   = intdiv($seconds, 3600);
    $m   = intdiv($seconds % 3600, 60);
    $sec = $seconds % 60;
    if ($h > 0) {
        return "{$h}h {$m}m {$sec}s";
    }
    if ($m > 0) {
        return "{$m}m {$sec}s";
    }
    return "{$sec}s";
}
