<?php
/**
 * CodeFoundry – OAuth Redirect Handler
 *
 * Initiates the OAuth 2.0 Authorization Code flow for GitHub or Google.
 * Generates a CSRF state token, stores it in the session, and redirects
 * the browser to the provider's authorization endpoint.
 *
 * Usage: /Login/oauth.php?provider=github   (or ?provider=google)
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';

session_start();

// Redirect already-logged-in users
if (!empty($_SESSION['cf_user'])) {
    header('Location: /');
    exit;
}

$provider = $_GET['provider'] ?? '';

// Validate redirect target
$raw_redirect = $_GET['redirect'] ?? '';
$safe_redirect = (
    is_string($raw_redirect) &&
    preg_match('#^/[^/\\\\]#', $raw_redirect) &&
    strpos($raw_redirect, '..') === false
) ? $raw_redirect : '/Dashboard/';

// Generate and store an opaque state token (CSRF protection for OAuth)
$state = bin2hex(random_bytes(24));
$_SESSION['oauth_state']    = $state;
$_SESSION['oauth_provider'] = $provider;
$_SESSION['oauth_redirect'] = $safe_redirect;

if ($provider === 'github') {
    if (!defined('CF_OAUTH_GITHUB_CLIENT_ID') || CF_OAUTH_GITHUB_CLIENT_ID === '') {
        http_response_code(503);
        echo 'GitHub OAuth is not configured on this server.';
        exit;
    }
    $params = http_build_query([
        'client_id' => CF_OAUTH_GITHUB_CLIENT_ID,
        'redirect_uri' => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . '/Login/oauth_callback.php',
        'scope' => 'read:user user:email',
        'state' => $state,
    ]);
    header('Location: https://github.com/login/oauth/authorize?' . $params);
    exit;
}

if ($provider === 'google') {
    if (!defined('CF_OAUTH_GOOGLE_CLIENT_ID') || CF_OAUTH_GOOGLE_CLIENT_ID === '') {
        http_response_code(503);
        echo 'Google OAuth is not configured on this server.';
        exit;
    }
    $params = http_build_query([
        'client_id'     => CF_OAUTH_GOOGLE_CLIENT_ID,
        'redirect_uri'  => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . '/Login/oauth_callback.php',
        'response_type' => 'code',
        'scope'         => 'openid email profile',
        'state'         => $state,
        'access_type'   => 'online',
    ]);
    header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
    exit;
}

if ($provider === 'linkedin') {
    if (!defined('CF_OAUTH_LINKEDIN_CLIENT_ID') || CF_OAUTH_LINKEDIN_CLIENT_ID === '') {
        http_response_code(503);
        echo 'LinkedIn OAuth is not configured on this server.';
        exit;
    }
    $params = http_build_query([
        'response_type' => 'code',
        'client_id'     => CF_OAUTH_LINKEDIN_CLIENT_ID,
        'redirect_uri'  => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . '/Login/oauth_callback.php',
        'scope'         => 'openid profile email',
        'state'         => $state,
    ]);
    header('Location: https://www.linkedin.com/oauth/v2/authorization?' . $params);
    exit;
}

// Unknown provider
http_response_code(400);
echo 'Unknown OAuth provider.';
