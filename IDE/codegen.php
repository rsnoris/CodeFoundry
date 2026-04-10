<?php
/**
 * CodeFoundry IDE – CodeGen endpoint
 *
 * POST /IDE/codegen.php
 * Body (JSON):
 *   action      : "generate" | "improve" | "explain" | "fix"  (default: "generate")
 *   prompt      : string  (required for generate/improve)
 *   language    : string  (required)
 *   currentCode : string  (required for improve/explain/fix)
 *   errorOutput : string  (optional, used for fix to include stderr context)
 *
 * Response:
 *   { "code": "..." }        for generate / improve / fix
 *   { "explanation": "..." } for explain
 *   { "error": "..." }       on failure
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// ── Method guard ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// ── Parse body ────────────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);

$action      = isset($body['action'])      ? trim((string)$body['action'])      : 'generate';
$prompt      = isset($body['prompt'])      ? trim((string)$body['prompt'])      : '';
$language    = isset($body['language'])    ? trim((string)$body['language'])    : '';
$currentCode = isset($body['currentCode']) ? trim((string)$body['currentCode']) : '';
$errorOutput = isset($body['errorOutput']) ? trim((string)$body['errorOutput']) : '';

$validActions = ['generate', 'improve', 'explain', 'fix'];
if (!in_array($action, $validActions, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action.']);
    exit;
}

if ($language === '') {
    http_response_code(400);
    echo json_encode(['error' => '"language" is required.']);
    exit;
}

// generate/improve require a prompt; explain/fix do not
if (in_array($action, ['generate', 'improve'], true) && $prompt === '') {
    http_response_code(400);
    echo json_encode(['error' => '"prompt" is required for generate and improve actions.']);
    exit;
}

// improve/explain/fix require existing code
if (in_array($action, ['improve', 'explain', 'fix'], true) && $currentCode === '') {
    http_response_code(400);
    echo json_encode(['error' => '"currentCode" is required for improve, explain, and fix actions.']);
    exit;
}

// ── Sanitise language label (used in system prompt only) ─────────────────
$langLabel = preg_replace('/[^a-zA-Z0-9 \+\#\-]/', '', $language);

// ── API key guard ─────────────────────────────────────────────────────────
$apiKey = CF_OPENAI_KEY;
if ($apiKey === '') {
    http_response_code(503);
    echo json_encode([
        'error'      => 'AI features require an active subscription.',
        'error_code' => 'subscription_required',
    ]);
    exit;
}

// ── Simple per-IP rate limit (APCu, best-effort) ─────────────────────────
if (function_exists('apcu_fetch')) {
    $ip      = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'cf_codegen_' . md5($ip);
    if (apcu_fetch($cacheKey) !== false) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Please wait a moment and try again.']);
        exit;
    }
    apcu_store($cacheKey, 1, 3); // 3-second window
}

// ── Token limits ──────────────────────────────────────────────────────────
const MAX_TOKENS_EXPLAIN = 1024;
const MAX_TOKENS_CODE    = 2048;

// ── Call OpenAI Chat Completions ──────────────────────────────────────────

// Build messages based on action
$messages = [];

if ($action === 'generate') {
    $messages = [
        [
            'role'    => 'system',
            'content' => "You are an expert {$langLabel} programmer. When asked to generate code, respond with ONLY the source code and nothing else — no markdown fences, no commentary, no explanation.",
        ],
        [
            'role'    => 'user',
            'content' => $prompt,
        ],
    ];
} elseif ($action === 'improve') {
    $messages = [
        [
            'role'    => 'system',
            'content' => "You are an expert {$langLabel} programmer. When asked to improve or refine code, respond with ONLY the updated source code and nothing else — no markdown fences, no commentary, no explanation.",
        ],
        [
            'role'    => 'user',
            'content' => "Here is my existing {$langLabel} code:\n\n{$currentCode}\n\nPlease improve it as follows: {$prompt}",
        ],
    ];
} elseif ($action === 'explain') {
    $messages = [
        [
            'role'    => 'system',
            'content' => "You are an expert {$langLabel} programmer and teacher. When asked to explain code, provide a clear, concise explanation in plain English. Use bullet points or numbered steps where helpful. Do NOT include code blocks unless illustrating a specific concept.",
        ],
        [
            'role'    => 'user',
            'content' => "Please explain the following {$langLabel} code:\n\n{$currentCode}",
        ],
    ];
} elseif ($action === 'fix') {
    $errorContext = $errorOutput !== ''
        ? "\n\nThe following error output was produced when running the code:\n{$errorOutput}"
        : '';
    $messages = [
        [
            'role'    => 'system',
            'content' => "You are an expert {$langLabel} programmer. When asked to fix code, respond with ONLY the corrected source code and nothing else — no markdown fences, no commentary, no explanation.",
        ],
        [
            'role'    => 'user',
            'content' => "Please fix the following {$langLabel} code:{$errorContext}\n\nCode:\n{$currentCode}",
        ],
    ];
}

$maxTokens = ($action === 'explain') ? MAX_TOKENS_EXPLAIN : MAX_TOKENS_CODE;

$payload = json_encode([
    'model'       => 'gpt-4o-mini',
    'max_tokens'  => $maxTokens,
    'temperature' => 0.2,
    'messages'    => $messages,
]);

$ctx = stream_context_create([
    'http' => [
        'method'        => 'POST',
        'header'        => implode("\r\n", [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ]),
        'content'       => $payload,
        'timeout'       => 30,
        'ignore_errors' => true,
    ],
]);

$response = @file_get_contents('https://api.openai.com/v1/chat/completions', false, $ctx);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'Failed to reach the OpenAI API. Please try again.']);
    exit;
}

$result = json_decode($response, true);

// Check for OpenAI-level errors
if (isset($result['error'])) {
    http_response_code(502);
    echo json_encode(['error' => $result['error']['message'] ?? 'OpenAI API error.']);
    exit;
}

if (empty($result['choices']) || !isset($result['choices'][0]['message']['content'])) {
    http_response_code(502);
    echo json_encode(['error' => 'Unexpected response from OpenAI API.']);
    exit;
}

$content = $result['choices'][0]['message']['content'];

// ── Record token usage ────────────────────────────────────────────────────
$tokensUsed = (int)($result['usage']['total_tokens'] ?? 0);
if ($tokensUsed > 0) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $sessionUser = $_SESSION['cf_user'] ?? null;
    if ($sessionUser !== null) {
        require_once dirname(__DIR__) . '/lib/UserStore.php';
        $promptSnippet = mb_substr($prompt ?: $currentCode, 0, 80, 'UTF-8');
        UserStore::appendTokenHistory([
            'username'       => $sessionUser['username'],
            'action'         => $action,
            'language'       => $langLabel,
            'prompt_snippet' => $promptSnippet,
            'tokens_used'    => $tokensUsed,
            'created_at'     => date('c'),
        ]);
        UserStore::addTokensUsed($sessionUser['username'], $tokensUsed);
    }
}

if ($action === 'explain') {
    // Return the explanation as plain text
    http_response_code(200);
    echo json_encode(['explanation' => trim($content)]);
} else {
    // Strip accidental markdown fences if the model added them anyway
    $code = preg_replace('/^```[a-zA-Z]*\n?/', '', $content);
    $code = preg_replace('/\n?```$/', '', $code);
    $code = trim($code);

    http_response_code(200);
    echo json_encode(['code' => $code]);
}
