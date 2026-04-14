<?php
/**
 * CodeFoundry – OAuth Callback Handler
 *
 * Handles the Authorization Code callback from GitHub or Google.
 * Exchanges the code for an access token, retrieves the user's profile,
 * and either logs in the existing linked account or creates a new one.
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';

session_start();

// ── Helpers ─────────────────────────────────────────────────────────────────

/** Perform a simple HTTP POST or GET using file_get_contents + stream context. */
function cf_http_request(string $method, string $url, array $headers, string $body = ''): string|false
{
    $opts = [
        'http' => [
            'method'        => $method,
            'header'        => implode("\r\n", $headers),
            'content'       => $body,
            'ignore_errors' => true,
            'timeout'       => 10,
        ],
    ];
    return @file_get_contents($url, false, stream_context_create($opts));
}

/** Abort with a plain-text error message and an HTTP error status. */
function cf_oauth_fail(string $message, int $status = 400): never
{
    http_response_code($status);
    // Redirect to login with a generic error – avoids leaking OAuth details to users
    header('Location: /Login/?error=oauth');
    exit;
}

// ── Validate state (CSRF) ────────────────────────────────────────────────────

$provider     = $_SESSION['oauth_provider'] ?? '';
$savedState   = $_SESSION['oauth_state']    ?? '';
$safeRedirect = $_SESSION['oauth_redirect'] ?? '/Generate/';

// Clean up session state immediately (replay-protection)
unset($_SESSION['oauth_state'], $_SESSION['oauth_provider'], $_SESSION['oauth_redirect']);

$returnedState = $_GET['state'] ?? '';
$code          = $_GET['code']  ?? '';

if (
    $savedState === '' ||
    !hash_equals($savedState, $returnedState) ||
    $code === ''
) {
    cf_oauth_fail('Invalid OAuth state or missing code.');
}

// ── Exchange code for access token ──────────────────────────────────────────

if ($provider === 'github') {

    if (!defined('CF_OAUTH_GITHUB_CLIENT_ID') || CF_OAUTH_GITHUB_CLIENT_ID === '') {
        cf_oauth_fail('GitHub OAuth is not configured.', 503);
    }

    $tokenBody = http_build_query([
        'client_id'     => CF_OAUTH_GITHUB_CLIENT_ID,
        'client_secret' => CF_OAUTH_GITHUB_CLIENT_SECRET,
        'code'          => $code,
        'redirect_uri'  => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . '/Login/oauth_callback.php',
    ]);
    $tokenResp = cf_http_request('POST', 'https://github.com/login/oauth/access_token', [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json',
        'User-Agent: CodeFoundry',
    ], $tokenBody);

    if ($tokenResp === false) {
        cf_oauth_fail('Token exchange failed.', 502);
    }
    $tokenData   = json_decode($tokenResp, true);
    $accessToken = $tokenData['access_token'] ?? '';
    if ($accessToken === '') {
        cf_oauth_fail('No access token returned by GitHub.', 502);
    }

    // Fetch user profile
    $profileResp = cf_http_request('GET', 'https://api.github.com/user', [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/vnd.github+json',
        'User-Agent: CodeFoundry',
    ]);
    if ($profileResp === false) {
        cf_oauth_fail('Failed to fetch GitHub user profile.', 502);
    }
    $profile = json_decode($profileResp, true);

    // Fetch verified primary email if not public
    $email = $profile['email'] ?? '';
    if ($email === '') {
        $emailsResp = cf_http_request('GET', 'https://api.github.com/user/emails', [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/vnd.github+json',
            'User-Agent: CodeFoundry',
        ]);
        if ($emailsResp !== false) {
            $emails = json_decode($emailsResp, true) ?? [];
            foreach ($emails as $e) {
                if (!empty($e['primary']) && !empty($e['verified'])) {
                    $email = $e['email'];
                    break;
                }
            }
        }
    }

    $providerId = (string)($profile['id']   ?? '');
    $display    = $profile['name']  ?? $profile['login'] ?? '';

} elseif ($provider === 'google') {

    if (!defined('CF_OAUTH_GOOGLE_CLIENT_ID') || CF_OAUTH_GOOGLE_CLIENT_ID === '') {
        cf_oauth_fail('Google OAuth is not configured.', 503);
    }

    $tokenBody = http_build_query([
        'client_id'     => CF_OAUTH_GOOGLE_CLIENT_ID,
        'client_secret' => CF_OAUTH_GOOGLE_CLIENT_SECRET,
        'code'          => $code,
        'grant_type'    => 'authorization_code',
        'redirect_uri'  => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . '/Login/oauth_callback.php',
    ]);
    $tokenResp = cf_http_request('POST', 'https://oauth2.googleapis.com/token', [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json',
    ], $tokenBody);

    if ($tokenResp === false) {
        cf_oauth_fail('Token exchange failed.', 502);
    }
    $tokenData   = json_decode($tokenResp, true);
    $accessToken = $tokenData['access_token'] ?? '';
    if ($accessToken === '') {
        cf_oauth_fail('No access token returned by Google.', 502);
    }

    // Fetch user info via Google's UserInfo endpoint
    $profileResp = cf_http_request('GET', 'https://www.googleapis.com/oauth2/v3/userinfo', [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json',
    ]);
    if ($profileResp === false) {
        cf_oauth_fail('Failed to fetch Google user profile.', 502);
    }
    $profile = json_decode($profileResp, true);

    $providerId = $profile['sub']   ?? '';
    $email      = $profile['email'] ?? '';
    $display    = $profile['name']  ?? '';

} elseif ($provider === 'linkedin') {

    if (!defined('CF_OAUTH_LINKEDIN_CLIENT_ID') || CF_OAUTH_LINKEDIN_CLIENT_ID === '') {
        cf_oauth_fail('LinkedIn OAuth is not configured.', 503);
    }

    $tokenBody = http_build_query([
        'grant_type'    => 'authorization_code',
        'code'          => $code,
        'client_id'     => CF_OAUTH_LINKEDIN_CLIENT_ID,
        'client_secret' => CF_OAUTH_LINKEDIN_CLIENT_SECRET,
        'redirect_uri'  => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
            . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
            . '/Login/oauth_callback.php',
    ]);
    $tokenResp = cf_http_request('POST', 'https://www.linkedin.com/oauth/v2/accessToken', [
        'Content-Type: application/x-www-form-urlencoded',
        'Accept: application/json',
    ], $tokenBody);

    if ($tokenResp === false) {
        cf_oauth_fail('Token exchange failed.', 502);
    }
    $tokenData   = json_decode($tokenResp, true);
    $accessToken = $tokenData['access_token'] ?? '';
    if ($accessToken === '') {
        cf_oauth_fail('No access token returned by LinkedIn.', 502);
    }

    // Fetch user info via LinkedIn's OpenID Connect UserInfo endpoint
    $profileResp = cf_http_request('GET', 'https://api.linkedin.com/v2/userinfo', [
        'Authorization: Bearer ' . $accessToken,
        'Accept: application/json',
    ]);
    if ($profileResp === false) {
        cf_oauth_fail('Failed to fetch LinkedIn user profile.', 502);
    }
    $profile = json_decode($profileResp, true);

    $providerId = $profile['sub']   ?? '';
    $email      = $profile['email'] ?? '';
    $fullName   = trim(($profile['given_name'] ?? '') . ' ' . ($profile['family_name'] ?? ''));
    $display    = $profile['name'] ?? ($fullName !== '' ? $fullName : ($email !== '' ? explode('@', $email)[0] : 'LinkedIn User'));

} else {
    cf_oauth_fail('Unknown OAuth provider.');
}

// ── Sanity-check the extracted fields ────────────────────────────────────────

if ($providerId === '') {
    cf_oauth_fail('Could not determine provider user ID.', 502);
}

// ── Find or create the local user account ───────────────────────────────────

$user = UserStore::findUserByOAuth($provider, $providerId);
if ($user === null) {
    $user = UserStore::createOAuthUser($provider, $providerId, $display, $email);
}

// ── Log the user in ──────────────────────────────────────────────────────────

session_regenerate_id(true);
$_SESSION['cf_login_at'] = time();
$loginIp  = AuditStore::getClientIp();
$loginGeo = AuditStore::geoLocate($loginIp);
AuditStore::log('user.login', $user['username'], array_filter([
    'method'     => 'oauth',
    'provider'   => $provider,
    'location'   => cf_format_location($loginGeo),
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
]));
$_SESSION['cf_user'] = [
    'username' => $user['username'],
    'display'  => $user['display'] ?? $user['username'],
    'role'     => $user['role']    ?? 'user',
];

header('Location: ' . $safeRedirect);
exit;
