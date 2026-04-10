<?php
/**
 * CodeFoundry – User Support Chat AJAX API
 *
 * POST /Dashboard/chat/api.php
 * JSON body: { "action": "...", ... }
 *
 * Actions:
 *   new_session   – { subject }                              → { session_id }
 *   send          – { session_id, message }                  → { message_id }
 *   poll          – { session_id, after_id? }                → { messages: [...], session: {...} }
 *   sessions      – {}                                       → { sessions: [...], unread_total }
 *   close_session – { session_id }                           → { ok: true }
 */
declare(strict_types=1);

require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/ChatStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

// Must be logged in
$cf_user = cf_current_user();
if ($cf_user === null) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated.']);
    exit;
}
$username = $cf_user['username'];

// Parse JSON body
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '{}', true) ?? [];

$action = $body['action'] ?? '';

switch ($action) {

    case 'new_session': {
        $subject = mb_substr(trim($body['subject'] ?? 'Support Request'), 0, 200);
        if ($subject === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Subject is required.']);
            exit;
        }
        $session_id = ChatStore::createSession($username, $subject);
        echo json_encode(['session_id' => $session_id]);
        break;
    }

    case 'send': {
        $session_id = trim($body['session_id'] ?? '');
        $message    = trim($body['message'] ?? '');

        if ($session_id === '' || $message === '') {
            http_response_code(400);
            echo json_encode(['error' => 'session_id and message are required.']);
            exit;
        }

        $session = ChatStore::getSession($session_id);
        if ($session === null || ($session['username'] ?? '') !== $username) {
            http_response_code(403);
            echo json_encode(['error' => 'Session not found or access denied.']);
            exit;
        }
        if (($session['status'] ?? '') === 'closed') {
            http_response_code(400);
            echo json_encode(['error' => 'This chat session is closed.']);
            exit;
        }

        $msg_id = ChatStore::addMessage($session_id, $username, 'user', $message);
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
        if ($session === null || ($session['username'] ?? '') !== $username) {
            http_response_code(403);
            echo json_encode(['error' => 'Session not found or access denied.']);
            exit;
        }

        // Mark unread messages as read by user
        if ((int)($session['unread_user'] ?? 0) > 0) {
            ChatStore::markReadByUser($session_id);
            $session['unread_user'] = 0;
        }

        $messages = ChatStore::messagesForSession($session_id, $after_id);
        echo json_encode(['messages' => $messages, 'session' => $session]);
        break;
    }

    case 'sessions': {
        $sessions    = ChatStore::sessionsForUser($username);
        $unread_total = ChatStore::totalUnreadForUser($username);
        echo json_encode(['sessions' => $sessions, 'unread_total' => $unread_total]);
        break;
    }

    case 'close_session': {
        $session_id = trim($body['session_id'] ?? '');
        if ($session_id === '') {
            http_response_code(400);
            echo json_encode(['error' => 'session_id is required.']);
            exit;
        }
        $session = ChatStore::getSession($session_id);
        if ($session === null || ($session['username'] ?? '') !== $username) {
            http_response_code(403);
            echo json_encode(['error' => 'Session not found or access denied.']);
            exit;
        }
        ChatStore::updateSessionStatus($session_id, 'closed');
        echo json_encode(['ok' => true]);
        break;
    }

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action.']);
        break;
}
