<?php
/**
 * CodeFoundry VIRAL – Agent Chat Endpoint
 *
 * POST /VIRAL/chat.php
 * Body (JSON):
 *   role    : string  (required) – agent role slug, e.g. "software-engineer"
 *   message : string  (required) – user message
 *   history : array   (optional) – prior conversation turns [{role,content}]
 *
 * Response:
 *   { "reply": "..." }    on success
 *   { "error": "..." }    on failure
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/CodeGenProvider.php';
require_once __DIR__ . '/config.php';   // VIRAL_AGENTS constant

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

/**
 * Maximum number of prior conversation turns to include in context.
 * Keeps token usage reasonable while preserving enough context for
 * coherent multi-turn conversations.
 */
const VIRAL_MAX_HISTORY_TURNS = 20;

/**
 * Minimum seconds between requests from the same IP.
 * Low enough to allow comfortable back-and-forth chat while
 * still discouraging automated abuse.
 */
const VIRAL_RATE_LIMIT_SECONDS = 5;

// ── Parse body ────────────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);

$role    = isset($body['role'])    ? trim((string)$body['role'])    : '';
$message = isset($body['message']) ? trim((string)$body['message']) : '';
$history = isset($body['history']) && is_array($body['history']) ? $body['history'] : [];

if ($role === '') {
    http_response_code(400);
    echo json_encode(['error' => '"role" is required.']);
    exit;
}

if (!array_key_exists($role, VIRAL_AGENTS)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown agent role.']);
    exit;
}

if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => '"message" is required.']);
    exit;
}

$agentCfg = VIRAL_AGENTS[$role];

// ── Determine provider ────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_sessionUser = $_SESSION['cf_user'] ?? null;
$_userPlan    = $_sessionUser['plan'] ?? 'free';
$_isFreePlan  = ($_userPlan === 'free');

$providerId = $_isFreePlan
    ? CodeGenProvider::defaultFreeProviderId()
    : CodeGenProvider::defaultProviderId();

if ($providerId === '') {
    http_response_code(503);
    echo json_encode(['error' => 'No AI provider available.']);
    exit;
}

$providerCfg = CF_CODEGEN_PROVIDERS[$providerId] ?? [];
$model       = $providerCfg['default_model'] ?? ($providerCfg['models'][0]['id'] ?? '');

// ── Simple per-IP rate limit (APCu, best-effort) ─────────────────────────
if (function_exists('apcu_fetch')) {
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'cf_viral_' . md5($ip);
    if (apcu_fetch($cacheKey) !== false) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Please wait a moment and try again.']);
        exit;
    }
    apcu_store($cacheKey, 1, VIRAL_RATE_LIMIT_SECONDS);
}

// ── Build messages ────────────────────────────────────────────────────────
$messages = [
    ['role' => 'system', 'content' => $agentCfg['system']],
];

// Append validated history (bounded to VIRAL_MAX_HISTORY_TURNS prior turns)
$sanitizedHistory = [];
foreach (array_slice($history, -VIRAL_MAX_HISTORY_TURNS) as $turn) {
    if (!is_array($turn)) continue;
    $r = isset($turn['role']) ? (string)$turn['role'] : '';
    $c = isset($turn['content']) ? (string)$turn['content'] : '';
    if (in_array($r, ['user', 'assistant'], true) && $c !== '') {
        $sanitizedHistory[] = ['role' => $r, 'content' => $c];
    }
}
$messages = array_merge($messages, $sanitizedHistory);
$messages[] = ['role' => 'user', 'content' => $message];

// ── Call the provider ─────────────────────────────────────────────────────
try {
    $result = CodeGenProvider::call($providerId, $model, $messages, 2048);
    $reply  = trim($result['content']);
    $tokens = $result['tokens'];
} catch (\InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
} catch (\RuntimeException $e) {
    http_response_code(502);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// ── Record token usage ────────────────────────────────────────────────────
if ($tokens > 0 && $_sessionUser !== null) {
    require_once dirname(__DIR__) . '/lib/UserStore.php';
    require_once dirname(__DIR__) . '/lib/AuditStore.php';
    UserStore::appendTokenHistory([
        'username'       => $_sessionUser['username'],
        'action'         => 'viral_agent_chat',
        'language'       => $agentCfg['label'],
        'provider'       => $providerId,
        'model'          => $model,
        'prompt_snippet' => mb_substr($message, 0, 80, 'UTF-8'),
        'tokens_used'    => $tokens,
        'code_output'    => '',
        'created_at'     => date('c'),
    ]);
    UserStore::addTokensUsed($_sessionUser['username'], $tokens);
    AuditStore::log('viral.agent_chat', $_sessionUser['username'], [
        'role'     => $role,
        'provider' => $providerId,
        'model'    => $model,
        'tokens'   => $tokens,
    ]);
}

http_response_code(200);
echo json_encode(['reply' => $reply]);
