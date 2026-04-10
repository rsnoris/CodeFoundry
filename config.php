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
    'free'       => ['label' => 'Free',       'tokens_limit' => 1000,   'price' => 0,   'price_label' => 'Free'],
    'starter'    => ['label' => 'Starter',    'tokens_limit' => 10000,  'price' => 19,  'price_label' => '$19/mo'],
    'pro'        => ['label' => 'Pro',        'tokens_limit' => 50000,  'price' => 49,  'price_label' => '$49/mo'],
    'enterprise' => ['label' => 'Enterprise', 'tokens_limit' => 500000, 'price' => 199, 'price_label' => '$199/mo'],
]);

// ---------------------------------------------------------------------------
// Data file paths
// ---------------------------------------------------------------------------

define('CF_DATA_USERS',         CF_ROOT . '/data/users.json');
define('CF_DATA_TOKEN_HISTORY', CF_ROOT . '/data/token_history.json');
define('CF_DATA_PROJECTS',      CF_ROOT . '/data/projects.json');
define('CF_DATA_PAYMENTS',      CF_ROOT . '/data/payments.json');

// ---------------------------------------------------------------------------
// AI / CodeGen
// ---------------------------------------------------------------------------

/** OpenAI API key for the CodeGen feature. Set via environment or replace the empty string. */
define('CF_OPENAI_KEY', getenv('OPENAI_API_KEY') ?: '');

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
