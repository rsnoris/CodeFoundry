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
