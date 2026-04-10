<?php
/**
 * CodeFoundry – Admin Support Chat AJAX API
 *
 * POST /Admin/chat_api.php
 * JSON body: { "action": "...", ... }
 *
 * Actions:
 *   send          – { session_id, message }               → { message_id }
 *   poll          – { session_id, after_id? }             → { messages: [...], session: {...} }
 *   sessions      – {}                                    → { sessions: [...], unread_total }
 *   update_status – { session_id, status }                → { ok: true }
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/ChatStore.php';
require_once dirname(__DIR__) . '/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

// Must be logged in as admin
$cf_user = cf_current_user();
if ($cf_user === null) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated.']);
    exit;
}
if (($cf_user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Admin access required.']);
    exit;
}
$admin_name = $cf_user['username'];

// Parse JSON body
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '{}', true) ?? [];

$action = $body['action'] ?? '';

switch ($action) {

    case 'send': {
        $session_id = trim($body['session_id'] ?? '');
        $message    = trim($body['message'] ?? '');

        if ($session_id === '' || $message === '') {
            http_response_code(400);
            echo json_encode(['error' => 'session_id and message are required.']);
            exit;
        }

        $session = ChatStore::getSession($session_id);
        if ($session === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Session not found.']);
            exit;
        }
        if (($session['status'] ?? '') === 'closed') {
            http_response_code(400);
            echo json_encode(['error' => 'This chat session is closed.']);
            exit;
        }

        $msg_id = ChatStore::addMessage($session_id, $admin_name, 'admin', $message);
        echo json_encode(['message_id' => $msg_id]);
        break;
    }

    case 'poll': {
        $session_id = trim($body['session_id'] ?? '');
        $after_id   = trim($body['after_id'] ?? '');

        if ($session_id === '') {
            http_response_code(400);
            echo json_encode(['error' => 'session_id is required.']);
            exit;
        }

        $session = ChatStore::getSession($session_id);
        if ($session === null) {
            http_response_code(404);
            echo json_encode(['error' => 'Session not found.']);
            exit;
        }

        // Mark unread messages as read by admin
        if ((int)($session['unread_admin'] ?? 0) > 0) {
            ChatStore::markReadByAdmin($session_id);
            $session['unread_admin'] = 0;
        }

        $messages = ChatStore::messagesForSession($session_id, $after_id);
        echo json_encode(['messages' => $messages, 'session' => $session]);
        break;
    }

    case 'sessions': {
        $sessions     = ChatStore::allSessions();
        $unread_total = ChatStore::totalUnreadForAdmin();
        echo json_encode(['sessions' => $sessions, 'unread_total' => $unread_total]);
        break;
    }

    case 'update_status': {
        $session_id = trim($body['session_id'] ?? '');
        $status     = trim($body['status'] ?? '');

        if ($session_id === '' || !in_array($status, ['open', 'closed'], true)) {
            http_response_code(400);
            echo json_encode(['error' => 'session_id and valid status are required.']);
            exit;
        }

        $ok = ChatStore::updateSessionStatus($session_id, $status);
        if (!$ok) {
            http_response_code(404);
            echo json_encode(['error' => 'Session not found.']);
            exit;
        }
        echo json_encode(['ok' => true]);
        break;
    }

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action.']);
        break;
}
