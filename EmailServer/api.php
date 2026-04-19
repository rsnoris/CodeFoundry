<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/EmailServer.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed.']);
    exit;
}

$configuredKey = trim(cf_load_key('OTP_NOTIFICATION_API_KEY'));
if ($configuredKey === '') {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'Server key is not configured.']);
    exit;
}

$authHeader = (string)($_SERVER['HTTP_AUTHORIZATION'] ?? '');
$providedKey = '';
if (preg_match('/^Bearer\s+(.+)$/i', $authHeader, $m)) {
    $providedKey = trim((string)$m[1]);
}
if ($providedKey === '' || !hash_equals($configuredKey, $providedKey)) {
    AuditStore::log('email_server.unauthorized', '', []);
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized.']);
    exit;
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw ?: '', true);
if (!is_array($payload)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON payload.']);
    exit;
}

$to = trim((string)($payload['to'] ?? ''));
$subject = trim((string)($payload['subject'] ?? ''));
$message = (string)($payload['message'] ?? '');

if ($to === '' || $subject === '' || trim($message) === '') {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'to, subject, and message are required.']);
    exit;
}

$sent = EmailServer::send($to, $subject, $message, [], true);
AuditStore::log(
    $sent ? 'email_server.sent' : 'email_server.send_failed',
    '',
    ['to' => $to, 'subject' => mb_substr($subject, 0, 200)]
);

if (!$sent) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'error' => 'Email delivery failed.']);
    exit;
}

echo json_encode(['ok' => true]);
