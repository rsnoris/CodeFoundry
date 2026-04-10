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

define('CF_DATA_USERS',         CF_ROOT . '/UserAccountData/users.json');
define('CF_DATA_TOKEN_HISTORY', CF_ROOT . '/UserAccountData/token_history.json');
define('CF_DATA_PROJECTS',      CF_ROOT . '/UserAccountData/projects.json');
define('CF_DATA_PAYMENTS',      CF_ROOT . '/UserAccountData/payments.json');

// ---------------------------------------------------------------------------
// AI / CodeGen
// ---------------------------------------------------------------------------

/** OpenAI API key for the CodeGen feature. Set via environment or replace the empty string. */
define('CF_OPENAI_KEY', getenv('OPENAI_API_KEY') ?: '');

/**
 * CodeGen provider registry.
 *
 * Each entry describes one inference backend.  Keys:
 *   label          – human-readable provider name shown in the UI
 *   api_url        – full endpoint URL; use {model} placeholder when model_in_url is true
 *   api_key_env    – (optional) env-var name that holds the API key
 *   api_key        – (optional) hard-coded key (used by Ollama which accepts any string)
 *   api_url_env    – (optional) env-var to override api_url (used by Ollama so the URL is configurable)
 *   extra_headers  – (optional) additional raw HTTP headers to send
 *   model_in_url   – (optional) true when the model ID is embedded in the URL (HuggingFace)
 *   models         – ordered list of {id, label} model descriptors
 *   default_model  – model ID pre-selected in the UI
 *   opensource     – true for fully open-source / free-tier models
 *   local          – true for local inference (Ollama); these are always "available"
 */
define('CF_CODEGEN_PROVIDERS', [

    'groq' => [
        'label'         => 'Groq',
        'api_url'       => 'https://api.groq.com/openai/v1/chat/completions',
        'api_key_env'   => 'GROQ_API_KEY',
        'models'        => [
            ['id' => 'llama-3.1-70b-versatile',          'label' => 'Llama 3.1 70B'],
            ['id' => 'llama-3.1-8b-instant',             'label' => 'Llama 3.1 8B'],
            ['id' => 'mixtral-8x7b-32768',               'label' => 'Mixtral 8×7B'],
            ['id' => 'gemma2-9b-it',                     'label' => 'Gemma 2 9B'],
            ['id' => 'deepseek-r1-distill-llama-70b',    'label' => 'DeepSeek R1 70B'],
        ],
        'default_model' => 'llama-3.1-70b-versatile',
        'opensource'    => true,
        'local'         => false,
    ],

    'openrouter' => [
        'label'         => 'OpenRouter',
        'api_url'       => 'https://openrouter.ai/api/v1/chat/completions',
        'api_key_env'   => 'OPENROUTER_API_KEY',
        'extra_headers' => [
            'HTTP-Referer: https://codefoundry.cloud',
            'X-Title: CodeFoundry',
        ],
        'models'        => [
            ['id' => 'meta-llama/llama-3.1-8b-instruct:free',  'label' => 'Llama 3.1 8B (Free)'],
            ['id' => 'mistralai/mistral-7b-instruct:free',      'label' => 'Mistral 7B (Free)'],
            ['id' => 'google/gemma-2-9b-it:free',               'label' => 'Gemma 2 9B (Free)'],
            ['id' => 'qwen/qwen-2.5-coder-7b-instruct:free',   'label' => 'Qwen 2.5 Coder 7B (Free)'],
            ['id' => 'deepseek/deepseek-r1:free',               'label' => 'DeepSeek R1 (Free)'],
        ],
        'default_model' => 'meta-llama/llama-3.1-8b-instruct:free',
        'opensource'    => true,
        'local'         => false,
    ],

    'ollama' => [
        'label'         => 'Ollama (Local)',
        'api_url'       => 'http://localhost:11434/v1/chat/completions',
        'api_url_env'   => 'OLLAMA_URL',
        'api_key'       => 'ollama',          // Ollama accepts any non-empty string
        'models'        => [
            ['id' => 'codellama',          'label' => 'CodeLlama'],
            ['id' => 'qwen2.5-coder',      'label' => 'Qwen 2.5 Coder'],
            ['id' => 'deepseek-coder-v2',  'label' => 'DeepSeek Coder V2'],
            ['id' => 'llama3.2',           'label' => 'Llama 3.2'],
            ['id' => 'phi4',               'label' => 'Phi-4'],
            ['id' => 'mistral',            'label' => 'Mistral'],
        ],
        'default_model' => 'codellama',
        'opensource'    => true,
        'local'         => true,              // always treated as available
    ],

    'huggingface' => [
        'label'         => 'HuggingFace',
        // HuggingFace Inference API embeds the model id in the path
        'api_url'       => 'https://api-inference.huggingface.co/models/{model}/v1/chat/completions',
        'api_key_env'   => 'HF_API_KEY',
        'model_in_url'  => true,
        'models'        => [
            ['id' => 'Qwen/Qwen2.5-Coder-32B-Instruct',        'label' => 'Qwen 2.5 Coder 32B'],
            ['id' => 'meta-llama/Llama-3.1-8B-Instruct',       'label' => 'Llama 3.1 8B'],
            ['id' => 'mistralai/Mistral-7B-Instruct-v0.3',     'label' => 'Mistral 7B'],
            ['id' => 'codellama/CodeLlama-34b-Instruct-hf',    'label' => 'CodeLlama 34B'],
        ],
        'default_model' => 'Qwen/Qwen2.5-Coder-32B-Instruct',
        'opensource'    => true,
        'local'         => false,
    ],

    'together' => [
        'label'         => 'Together AI',
        'api_url'       => 'https://api.together.xyz/v1/chat/completions',
        'api_key_env'   => 'TOGETHER_API_KEY',
        'models'        => [
            ['id' => 'meta-llama/Meta-Llama-3.1-70B-Instruct-Turbo', 'label' => 'Llama 3.1 70B'],
            ['id' => 'meta-llama/Meta-Llama-3.1-8B-Instruct-Turbo',  'label' => 'Llama 3.1 8B'],
            ['id' => 'Qwen/Qwen2.5-Coder-32B-Instruct',              'label' => 'Qwen 2.5 Coder 32B'],
            ['id' => 'mistralai/Mixtral-8x7B-Instruct-v0.1',         'label' => 'Mixtral 8×7B'],
            ['id' => 'codellama/CodeLlama-34b-Instruct-hf',          'label' => 'CodeLlama 34B'],
        ],
        'default_model' => 'meta-llama/Meta-Llama-3.1-70B-Instruct-Turbo',
        'opensource'    => true,
        'local'         => false,
    ],

    'openai' => [
        'label'         => 'OpenAI',
        'api_url'       => 'https://api.openai.com/v1/chat/completions',
        'api_key_env'   => 'OPENAI_API_KEY',
        'models'        => [
            ['id' => 'gpt-4o-mini',   'label' => 'GPT-4o Mini'],
            ['id' => 'gpt-4o',        'label' => 'GPT-4o'],
            ['id' => 'gpt-3.5-turbo', 'label' => 'GPT-3.5 Turbo'],
        ],
        'default_model' => 'gpt-4o-mini',
        'opensource'    => false,
        'local'         => false,
    ],

]);

// ---------------------------------------------------------------------------
// Stripe
// ---------------------------------------------------------------------------

/**
 * Stripe API keys.
 * Register at: https://dashboard.stripe.com/apikeys
 * Set STRIPE_PUBLISHABLE_KEY and STRIPE_SECRET_KEY as environment variables.
 */
define('CF_STRIPE_PUBLISHABLE_KEY', getenv('STRIPE_PUBLISHABLE_KEY') ?: '');
define('CF_STRIPE_SECRET_KEY',      getenv('STRIPE_SECRET_KEY')      ?: '');

// ---------------------------------------------------------------------------
// PayPal
// ---------------------------------------------------------------------------

/**
 * PayPal REST API credentials.
 * Register at: https://developer.paypal.com/dashboard/
 * Set PAYPAL_CLIENT_ID and PAYPAL_CLIENT_SECRET as environment variables.
 * Set PAYPAL_MODE to 'live' in production (default: 'sandbox').
 */
define('CF_PAYPAL_CLIENT_ID',     getenv('PAYPAL_CLIENT_ID')     ?: '');
define('CF_PAYPAL_CLIENT_SECRET', getenv('PAYPAL_CLIENT_SECRET') ?: '');
define('CF_PAYPAL_MODE',          getenv('PAYPAL_MODE')          ?: 'sandbox');

// ---------------------------------------------------------------------------
// OAuth / Social Login
// ---------------------------------------------------------------------------

/**
 * GitHub OAuth app credentials.
 * Register at: https://github.com/settings/developers
 * Set the Authorization callback URL to: https://yourdomain.com/Login/oauth_callback.php
 */
define('CF_OAUTH_GITHUB_CLIENT_ID',     getenv('GITHUB_CLIENT_ID')     ?: '');
define('CF_OAUTH_GITHUB_CLIENT_SECRET', getenv('GITHUB_CLIENT_SECRET') ?: '');

/**
 * Google OAuth app credentials.
 * Register at: https://console.cloud.google.com/apis/credentials
 * Set the Authorized redirect URI to: https://yourdomain.com/Login/oauth_callback.php
 */
define('CF_OAUTH_GOOGLE_CLIENT_ID',     getenv('GOOGLE_CLIENT_ID')     ?: '');
define('CF_OAUTH_GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');

/**
 * LinkedIn OAuth 2.0 / OpenID Connect credentials.
 * Register at: https://www.linkedin.com/developers/apps
 * Set the Authorized redirect URL to: https://yourdomain.com/Login/oauth_callback.php
 * Required scopes: openid, profile, email
 */
define('CF_OAUTH_LINKEDIN_CLIENT_ID',     getenv('LINKEDIN_CLIENT_ID')     ?: '');
define('CF_OAUTH_LINKEDIN_CLIENT_SECRET', getenv('LINKEDIN_CLIENT_SECRET') ?: '');

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

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
