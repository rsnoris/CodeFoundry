<?php
/**
 * CodeFoundry – Page View Tracker
 *
 * Lightweight POST endpoint called from client-side JavaScript to record
 * page-navigation data for analytics.
 *
 * POST body (JSON):
 *   page        : string  – current page path, e.g. "/IDE/"
 *   referrer    : string  – previous page path (optional)
 *   time_on_page: int     – seconds spent on the referrer page (optional)
 */
declare(strict_types=1);

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false]);
    exit;
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib/AuditStore.php';

// Session is needed only to read the current user – start if already exists.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$username = $_SESSION['cf_user']['username'] ?? '';

$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

$page        = substr(trim((string)($body['page']         ?? '')), 0, 500);
$referrer    = substr(trim((string)($body['referrer']     ?? '')), 0, 500);
$timeOnPage  = max(0, (int)($body['time_on_page'] ?? 0));

if ($page === '') {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

// Only record paths that start with /  (no external URLs)
if (!str_starts_with($page, '/')) {
    http_response_code(400);
    echo json_encode(['ok' => false]);
    exit;
}

AuditStore::logPageView($username, $page, $timeOnPage, $referrer);

http_response_code(200);
echo json_encode(['ok' => true]);
