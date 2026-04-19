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
 *   provider    : string  (optional, provider id from CF_CODEGEN_PROVIDERS; defaults to first available)
 *   model       : string  (optional, model id within the chosen provider; defaults to provider default)
 *
 * Response:
 *   { "code": "..." }        for generate / improve / fix
 *   { "explanation": "..." } for explain
 *   { "error": "..." }       on failure
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/CodeGenProvider.php';

header('X-Content-Type-Options: nosniff');

// ── Method guard ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Content-Type: application/json');
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// ── Session context ────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_sessionUser = $_SESSION['cf_user'] ?? null;

// ── Parse body ────────────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);

$action      = isset($body['action'])      ? trim((string)$body['action'])      : 'generate';
$prompt      = isset($body['prompt'])      ? trim((string)$body['prompt'])      : '';
$language    = isset($body['language'])    ? trim((string)$body['language'])    : '';
$currentCode = isset($body['currentCode']) ? trim((string)$body['currentCode']) : '';
$errorOutput = isset($body['errorOutput']) ? trim((string)$body['errorOutput']) : '';
$providerId  = isset($body['provider'])    ? trim((string)$body['provider'])    : '';
$model       = isset($body['model'])       ? trim((string)$body['model'])       : '';
$stream      = !empty($body['stream']);
// Alias: multi-turn messages array (for chat-style continuity)
$msgHistory  = (!empty($body['messages']) && is_array($body['messages'])) ? $body['messages'] : [];

if ($providerId !== '' && !array_key_exists($providerId, CF_CODEGEN_PROVIDERS)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Unknown provider: ' . $providerId]);
    exit;
}

// ── Validate action ───────────────────────────────────────────────────────
$validActions = ['generate', 'improve', 'explain', 'fix'];
if (!in_array($action, $validActions, true)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => 'Invalid action.']);
    exit;
}

if ($language === '') {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => '"language" is required.']);
    exit;
}

if (in_array($action, ['generate', 'improve'], true) && $prompt === '' && empty($msgHistory)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => '"prompt" is required for generate and improve actions.']);
    exit;
}

if (in_array($action, ['improve', 'explain', 'fix'], true) && $currentCode === '' && empty($msgHistory)) {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(['error' => '"currentCode" is required for improve, explain, and fix actions.']);
    exit;
}

// ── Sanitise language label (used in system prompt only) ─────────────────
$langLabel = preg_replace('/[^a-zA-Z0-9 \+\#\-]/', '', $language);

// ── Resolve provider candidates ────────────────────────────────────────────
$providerCandidates = CodeGenProvider::candidateProviderIds($providerId);
if (empty($providerCandidates)) {
    header('Content-Type: application/json');
    http_response_code(503);
    echo json_encode([
        'error'      => 'No AI providers are configured. Please add at least one provider key in account/admin settings and try again.',
        'error_code' => 'provider_not_configured',
    ]);
    exit;
}

// ── Simple per-IP rate limit (APCu, best-effort) ─────────────────────────
if (function_exists('apcu_fetch')) {
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'cf_codegen_' . md5($ip);
    if (apcu_fetch($cacheKey) !== false) {
        header('Content-Type: application/json');
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Please wait a moment and try again.']);
        exit;
    }
    apcu_store($cacheKey, 1, 3); // 3-second window
}

// ── Token limits ──────────────────────────────────────────────────────────
const MAX_TOKENS_EXPLAIN    = 1024;
const MAX_TOKENS_CODE       = 4096;
const MAX_CODE_OUTPUT_LENGTH = 20000;

// ── Build messages ────────────────────────────────────────────────────────
$messages = [];

// Multi-turn: if the client supplied a full history array, use it directly
// (client ensures system message is first; we just validate the array).
if (!empty($msgHistory)) {
    $messages = array_values(array_filter($msgHistory, function ($m) {
        return isset($m['role'], $m['content'])
            && in_array($m['role'], ['system', 'user', 'assistant'], true)
            && is_string($m['content'])
            && trim($m['content']) !== '';
    }));
    // Cap to avoid token overflow
    if (count($messages) > 40) {
        $messages = array_slice($messages, -40);
    }
} elseif ($action === 'generate') {
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

// ── Streaming response (SSE forwarded from OpenAI-compatible provider) ────
if ($stream) {
    // Resolve the primary provider and API key for streaming
    $streamProvider = CodeGenProvider::resolveForStream($providerCandidates, $model);
    if ($streamProvider === null) {
        header('Content-Type: application/json');
        http_response_code(503);
        echo json_encode(['error' => 'No provider available for streaming. Configure an OpenAI-compatible provider.']);
        exit;
    }

    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');

    if (!function_exists('curl_init')) {
        echo "data: " . json_encode(['error' => 'Streaming requires the cURL PHP extension.']) . "\n\n";
        flush();
        exit;
    }

    $streamPayload = [
        'model'      => $streamProvider['model'],
        'max_tokens' => $maxTokens,
        'messages'   => $messages,
        'stream'     => true,
    ];

    $ch = curl_init($streamProvider['endpoint']);
    curl_setopt_array($ch, [
        CURLOPT_POST          => true,
        CURLOPT_HTTPHEADER    => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $streamProvider['api_key'],
        ],
        CURLOPT_POSTFIELDS    => json_encode($streamPayload),
        CURLOPT_TIMEOUT       => 90,
        CURLOPT_WRITEFUNCTION => static function ($ch, $data) {
            echo $data;
            if (ob_get_level() > 0) ob_flush();
            flush();
            return strlen($data);
        },
    ]);
    curl_exec($ch);
    if (curl_errno($ch) !== 0) {
        echo "data: " . json_encode(['error' => 'Streaming request failed.']) . "\n\n";
        flush();
    }
    curl_close($ch);
    exit;
}

// ── Non-streaming: call the provider ─────────────────────────────────────
header('Content-Type: application/json');
try {
    $result     = CodeGenProvider::callWithFallback($providerCandidates, $model, $messages, $maxTokens);
    $content    = $result['content'];
    $tokens     = $result['tokens'];
    $providerId = $result['provider'];
    $model      = $result['model'];
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
    $promptSnippet = mb_substr($prompt ?: $currentCode, 0, 80, 'UTF-8');
    $codeOutput    = ($action !== 'explain') ? mb_substr(trim($content), 0, MAX_CODE_OUTPUT_LENGTH, 'UTF-8') : '';
    UserStore::appendTokenHistory([
        'username'       => $_sessionUser['username'],
        'action'         => $action,
        'language'       => $langLabel,
        'provider'       => $providerId,
        'model'          => $model,
        'prompt_snippet' => $promptSnippet,
        'tokens_used'    => $tokens,
        'code_output'    => $codeOutput,
        'created_at'     => date('c'),
    ]);
    UserStore::addTokensUsed($_sessionUser['username'], $tokens);
    AuditStore::log('codegen.request', $_sessionUser['username'], [
        'action'   => $action,
        'language' => $langLabel,
        'provider' => $providerId,
        'model'    => $model,
        'tokens'   => $tokens,
    ]);
}

// ── Return result ─────────────────────────────────────────────────────────
if ($action === 'explain') {
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
