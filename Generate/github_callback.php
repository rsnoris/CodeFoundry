<?php
/**
 * CodeFoundry – GitHub Repository OAuth Callback
 *
 * Exchanges the GitHub authorization code for an access token (with the
 * `repo` scope) and stores it on the current user's account so the Generate
 * page can push code to GitHub.
 *
 * After a successful connection the window posts a message to its opener
 * (for popup flow) or redirects back to /Generate/ (for direct flow).
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';

session_start();

/** Terminate with an error page the popup can detect. */
function gh_cb_fail(string $msg): never
{
    echo '<!doctype html><html><body><script>
if (window.opener) {
    window.opener.postMessage({type:"gh_connect",ok:false,error:' . json_encode($msg) . '}, "*");
    window.close();
} else {
    window.location.href = "/Generate/?gh_error=" + encodeURIComponent(' . json_encode($msg) . ');
}
</script></body></html>';
    exit;
}

if (empty($_SESSION['cf_user'])) {
    gh_cb_fail('Not logged in.');
}

$savedState    = $_SESSION['gh_repo_state'] ?? '';
$returnedState = $_GET['state'] ?? '';
$code          = $_GET['code']  ?? '';

unset($_SESSION['gh_repo_state']);

if ($savedState === '') {
    gh_cb_fail('Missing OAuth state token.');
}
if (!hash_equals($savedState, $returnedState) || $code === '') {
    gh_cb_fail('Invalid OAuth state or missing code.');
}

if (!defined('CF_OAUTH_GITHUB_CLIENT_ID') || CF_OAUTH_GITHUB_CLIENT_ID === '') {
    gh_cb_fail('GitHub OAuth is not configured.');
}

// Exchange code for access token
$tokenBody = http_build_query([
    'client_id'     => CF_OAUTH_GITHUB_CLIENT_ID,
    'client_secret' => CF_OAUTH_GITHUB_CLIENT_SECRET,
    'code'          => $code,
    'redirect_uri'  => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
        . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
        . '/Generate/github_callback.php',
]);
$opts = ['http' => [
    'method'        => 'POST',
    'header'        => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\nUser-Agent: CodeFoundry",
    'content'       => $tokenBody,
    'ignore_errors' => true,
    'timeout'       => 10,
]];
$tokenResp = @file_get_contents('https://github.com/login/oauth/access_token', false, stream_context_create($opts));
if ($tokenResp === false) {
    gh_cb_fail('Token exchange failed.');
}
$tokenData   = json_decode($tokenResp, true);
$accessToken = $tokenData['access_token'] ?? '';
if ($accessToken === '') {
    gh_cb_fail('No access token returned by GitHub.');
}

// Fetch GitHub username
$profileOpts = ['http' => [
    'method'        => 'GET',
    'header'        => "Authorization: Bearer {$accessToken}\r\nAccept: application/vnd.github+json\r\nUser-Agent: CodeFoundry",
    'ignore_errors' => true,
    'timeout'       => 10,
]];
$profileResp = @file_get_contents('https://api.github.com/user', false, stream_context_create($profileOpts));
$ghUsername  = '';
if ($profileResp !== false) {
    $profile    = json_decode($profileResp, true);
    $ghUsername = $profile['login'] ?? '';
}

// Persist token on the user account
UserStore::updateUser($_SESSION['cf_user']['username'], [
    'github_token'    => $accessToken,
    'github_username' => $ghUsername,
]);

echo '<!doctype html><html><body><script>
if (window.opener) {
    window.opener.postMessage({type:"gh_connect",ok:true,ghUser:' . json_encode($ghUsername) . '}, "*");
    window.close();
} else {
    window.location.href = "/Generate/?gh_connected=1";
}
</script></body></html>';
