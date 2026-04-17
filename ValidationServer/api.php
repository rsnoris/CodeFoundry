<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';
require_once dirname(__DIR__) . '/lib/AuthValidationServer.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if ($method === 'GET' && (($_GET['action'] ?? '') === 'health' || !isset($_GET['action']))) {
    echo json_encode(['ok' => true, 'service' => 'validation_server', 'time' => date('c')]);
    exit;
}
if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed.']);
    exit;
}

$configuredKey = trim(cf_load_key('AUTH_VALIDATION_SERVER_API_KEY'));
if ($configuredKey === '') {
    http_response_code(503);
    echo json_encode(['ok' => false, 'error' => 'Validation server key is not configured.']);
    exit;
}

$authHeader = (string)($_SERVER['HTTP_AUTHORIZATION'] ?? '');
$providedKey = '';
if (preg_match('/^Bearer\s+(.+)$/i', $authHeader, $m)) {
    $providedKey = trim((string)$m[1]);
}
if ($providedKey === '' || !hash_equals($configuredKey, $providedKey)) {
    AuditStore::log('validation_server.unauthorized', '', []);
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Unauthorized.']);
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode($raw ?: '{}', true);
if (!is_array($body)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON payload.']);
    exit;
}

$action = trim((string)($body['action'] ?? ''));
$ip = trim((string)($body['ip'] ?? AuditStore::getClientIp()));

switch ($action) {
    case 'validate_session': {
        echo json_encode(['ok' => true, 'valid' => AuthValidationServer::isSessionValid()]);
        break;
    }

    case 'validate_login': {
        $username = trim((string)($body['username'] ?? ''));
        $password = (string)($body['password'] ?? '');
        $rateAllowed = AuthValidationServer::consumeLoginAttempt($username, $ip);
        if (!$rateAllowed) {
            http_response_code(429);
            echo json_encode(['ok' => false, 'error' => 'Too many login attempts.']);
            break;
        }
        $result = AuthValidationServer::validateLoginCredentials($username, $password);
        echo json_encode(['ok' => true] + $result);
        break;
    }

    case 'can_request_otp': {
        $identifier = trim((string)($body['identifier'] ?? ''));
        if ($identifier === '') {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'identifier is required.']);
            break;
        }
        $allowed = AuthValidationServer::consumeOtpRequest($identifier, $ip);
        if (!$allowed) {
            http_response_code(429);
            echo json_encode(['ok' => false, 'error' => 'Too many OTP requests.']);
            break;
        }
        echo json_encode(['ok' => true, 'allowed' => true]);
        break;
    }

    case 'validate_password_reset_otp': {
        $identifier = trim((string)($body['identifier'] ?? ''));
        $otp = trim((string)($body['otp'] ?? ''));
        $rateAllowed = AuthValidationServer::consumeOtpVerifyAttempt($identifier, $ip);
        if (!$rateAllowed) {
            http_response_code(429);
            echo json_encode(['ok' => false, 'error' => 'Too many OTP validation attempts.']);
            break;
        }
        $result = AuthValidationServer::validatePasswordResetOtp($identifier, $otp);
        echo json_encode(['ok' => true] + $result);
        break;
    }

    default:
        http_response_code(400);
        echo json_encode(['ok' => false, 'error' => 'Unknown action.']);
        break;
}
