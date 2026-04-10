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
