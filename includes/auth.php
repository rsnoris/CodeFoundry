<?php
declare(strict_types=1);

/**
 * CodeFoundry – Authentication helpers.
 *
 * Include this file at the top of any protected page.
 */

/**
 * Require the user to be logged in.
 * If not, redirect to /Login/ with the current URL as ?redirect=
 */
function cf_require_login(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['cf_user'])) {
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/');
        header('Location: /Login/?redirect=' . $redirect);
        exit;
    }

    $loginAt = (int)($_SESSION['cf_login_at'] ?? 0);
    if ($loginAt <= 0) {
        $_SESSION['cf_login_at'] = time();
        return;
    }

    if (CF_SOCIAL_AUTH_SESSION_LIMIT_SECONDS > 0 && (time() - $loginAt) > CF_SOCIAL_AUTH_SESSION_LIMIT_SECONDS) {
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
        $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/');
        header('Location: /Login/?expired=1&redirect=' . $redirect);
        exit;
    }
}

/**
 * Return the current logged-in user array or null.
 */
function cf_current_user(): ?array
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return $_SESSION['cf_user'] ?? null;
}
