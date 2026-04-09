<?php
/**
 * CodeFoundry IDE – CodeGen endpoint
 *
 * POST /IDE/codegen.php
 * Body (JSON): { "prompt": "...", "language": "python" }
 * Response:    { "code": "..." }  or  { "error": "..." }
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

$prompt   = isset($body['prompt'])   ? trim((string)$body['prompt'])   : '';
$language = isset($body['language']) ? trim((string)$body['language']) : '';

if ($prompt === '' || $language === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Both "prompt" and "language" are required.']);
    exit;
}

// ── Sanitise language label (used in system prompt only) ─────────────────
$langLabel = preg_replace('/[^a-zA-Z0-9 \+\#\-]/', '', $language);

// ── API key guard ─────────────────────────────────────────────────────────
$apiKey = CF_OPENAI_KEY;
if ($apiKey === '') {
    http_response_code(503);
    echo json_encode(['error' => 'CodeGen is not configured. Set the OPENAI_API_KEY environment variable.']);
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

// ── Call OpenAI Chat Completions ──────────────────────────────────────────
$payload = json_encode([
    'model'      => 'gpt-4o-mini',
    'max_tokens' => 2048,
    'temperature'=> 0.2,
    'messages'   => [
        [
            'role'    => 'system',
            'content' => "You are an expert {$langLabel} programmer. When asked to generate code, respond with ONLY the source code and nothing else — no markdown fences, no commentary, no explanation.",
        ],
        [
            'role'    => 'user',
            'content' => $prompt,
        ],
    ],
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

$code = $result['choices'][0]['message']['content'];

// Strip accidental markdown fences if the model added them anyway
$code = preg_replace('/^```[a-zA-Z]*\n?/', '', $code);
$code = preg_replace('/\n?```$/', '', $code);
$code = trim($code);

http_response_code(200);
echo json_encode(['code' => $code]);
