<?php
/**
 * CodeFoundry – Logout Endpoint
 *
 * Destroys the current session and redirects to the home page.
 */
declare(strict_types=1);

session_start();

// Capture user before destroying the session
$_logoutUser = $_SESSION['cf_user']['username'] ?? '';

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

session_destroy();

// Log the logout event after session destruction (uses its own file write, no session needed)
if ($_logoutUser !== '') {
    require_once dirname(__DIR__) . '/config.php';
    require_once dirname(__DIR__) . '/lib/AuditStore.php';
    AuditStore::log('user.logout', $_logoutUser, []);
}

header('Location: /');
exit;
