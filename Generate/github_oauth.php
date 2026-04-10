<?php
/**
 * CodeFoundry – GitHub Repository OAuth Initiation
 *
 * Starts a GitHub OAuth flow requesting the `repo` scope so the user can
 * push generated code to their own GitHub repositories.  The user must be
 * logged in.  After authorisation the browser is sent to github_callback.php.
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';

session_start();

if (empty($_SESSION['cf_user'])) {
    header('Location: /Login/?redirect=' . urlencode('/Generate/'));
    exit;
}

if (!defined('CF_OAUTH_GITHUB_CLIENT_ID') || CF_OAUTH_GITHUB_CLIENT_ID === '') {
    http_response_code(503);
    echo 'GitHub OAuth is not configured on this server.';
    exit;
}

$state = bin2hex(random_bytes(24));
$_SESSION['gh_repo_state'] = $state;

$params = http_build_query([
    'client_id'    => CF_OAUTH_GITHUB_CLIENT_ID,
    'redirect_uri' => (isset($_SERVER['HTTPS']) ? 'https' : 'http')
        . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
        . '/Generate/github_callback.php',
    'scope'        => 'repo read:user',
    'state'        => $state,
]);

header('Location: https://github.com/login/oauth/authorize?' . $params);
exit;
