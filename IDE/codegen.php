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

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// ── Method guard ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// ── Determine user plan (free vs paid) ────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_sessionUser = $_SESSION['cf_user'] ?? null;
$_userPlan    = $_sessionUser['plan'] ?? 'free';
$_isFreePlan  = ($_userPlan === 'free');

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

if ($providerId !== '' && !array_key_exists($providerId, CF_CODEGEN_PROVIDERS)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown provider.']);
    exit;
}

// ── Validate action ───────────────────────────────────────────────────────
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

if (in_array($action, ['generate', 'improve'], true) && $prompt === '') {
    http_response_code(400);
    echo json_encode(['error' => '"prompt" is required for generate and improve actions.']);
    exit;
}

if (in_array($action, ['improve', 'explain', 'fix'], true) && $currentCode === '') {
    http_response_code(400);
    echo json_encode(['error' => '"currentCode" is required for improve, explain, and fix actions.']);
    exit;
}

// ── Sanitise language label (used in system prompt only) ─────────────────
$langLabel = preg_replace('/[^a-zA-Z0-9 \+\#\-]/', '', $language);

// ── Resolve provider candidates ───────────────────────────────────────────
if ($_isFreePlan && $providerId !== '') {
    $allowedFreeProvider = CodeGenProvider::isFreeTierProvider($providerId)
        || CodeGenProvider::isNoKeyProvider($providerId);
    if (!$allowedFreeProvider) {
        http_response_code(403);
        echo json_encode([
            'error'      => 'Upgrade your plan to access premium AI providers.',
            'error_code' => 'upgrade_required',
        ]);
        exit;
    }
}

$providerCandidates = CodeGenProvider::candidateProviderIds($_isFreePlan, $providerId);
if (empty($providerCandidates)) {
    http_response_code(503);
    echo json_encode([
        'error'      => 'No AI providers are currently available. Please configure an API key or start a local provider.',
        'error_code' => 'subscription_required',
    ]);
    exit;
}

// ── Simple per-IP rate limit (APCu, best-effort) ─────────────────────────
if (function_exists('apcu_fetch')) {
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'cf_codegen_' . md5($ip);
    if (apcu_fetch($cacheKey) !== false) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Please wait a moment and try again.']);
        exit;
    }
    apcu_store($cacheKey, 1, 3); // 3-second window
}

// ── Token limits ──────────────────────────────────────────────────────────
const MAX_TOKENS_EXPLAIN    = 1024;
const MAX_TOKENS_CODE       = 2048;
const MAX_CODE_OUTPUT_LENGTH = 20000;

// ── Build messages ────────────────────────────────────────────────────────
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

// ── Call the provider ─────────────────────────────────────────────────────
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
