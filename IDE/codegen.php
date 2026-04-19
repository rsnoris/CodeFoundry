<?php
/**
 * CodeFoundry IDE – CodeGen endpoint
 *
 * POST /IDE/codegen.php
 * Body (JSON):
 *   {
 *     "messages":     [...],       // full conversation history (multi-turn)
 *     "prompt":       "...",       // single-turn shorthand (ignored when messages given)
 *     "language":     "python",    // required
 *     "mode":         "generate",  // generate | edit | fix | explain
 *     "selection":    "...",       // for "edit" mode: the selected code snippet
 *     "error_output": "...",       // for "fix" mode: runtime error from output panel
 *     "pro":          false,       // true = gpt-4o, false = gpt-4o-mini
 *     "stream":       false        // true = SSE streaming response
 *   }
 *
 * Non-streaming response:  { "code": "..." } | { "text": "..." } | { "error": "..." }
 * Streaming response:      text/event-stream forwarded directly from OpenAI
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';

// ── Method guard ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// ── Parse body ────────────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);

$language    = isset($body['language'])     ? trim((string)$body['language'])     : '';
$mode        = isset($body['mode'])         ? trim((string)$body['mode'])         : 'generate';
$selection   = isset($body['selection'])    ? trim((string)$body['selection'])    : '';
$errorOutput = isset($body['error_output']) ? trim((string)$body['error_output']) : '';
$pro         = !empty($body['pro']);
$stream      = !empty($body['stream']);

if (!in_array($mode, ['generate', 'edit', 'fix', 'explain'], true)) {
    $mode = 'generate';
}

if ($language === '') {
    http_response_code(400);
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    echo json_encode(['error' => '"language" is required.']);
    exit;
}

// ── Sanitise language label (used in system prompt only) ─────────────────
$langLabel = preg_replace('/[^a-zA-Z0-9 \+\#\-]/', '', $language);

// ── API key guard ─────────────────────────────────────────────────────────
$apiKey = CF_OPENAI_KEY;
if ($apiKey === '') {
    http_response_code(503);
    header('Content-Type: application/json');
    header('X-Content-Type-Options: nosniff');
    echo json_encode(['error' => 'CodeGen is not configured. Set the OPENAI_API_KEY environment variable.']);
    exit;
}

// ── Simple per-IP rate limit (APCu, best-effort) ─────────────────────────
if (function_exists('apcu_fetch')) {
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'cf_codegen_' . md5($ip);
    if (apcu_fetch($cacheKey) !== false) {
        http_response_code(429);
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        echo json_encode(['error' => 'Too many requests. Please wait a moment and try again.']);
        exit;
    }
    apcu_store($cacheKey, 1, 3); // 3-second window
}

// ── Build system prompt ───────────────────────────────────────────────────
$isExplain = ($mode === 'explain');

if ($isExplain) {
    $systemContent = "You are an expert {$langLabel} programmer and teacher. "
        . "Explain the provided code clearly in plain English. "
        . "Be concise but thorough. Use bullet points or short paragraphs. "
        . "Do NOT include code in your response unless showing a direct correction.";
} else {
    $systemContent = "You are an expert {$langLabel} programmer. "
        . "When asked to generate or modify code, respond with ONLY the source code "
        . "and nothing else — no markdown fences, no commentary, no explanation.";
}

// ── Build messages array ──────────────────────────────────────────────────
if (!empty($body['messages']) && is_array($body['messages'])) {
    // Multi-turn: client supplies full history
    $messages = array_values(array_filter($body['messages'], function ($m) {
        return isset($m['role'], $m['content'])
            && in_array($m['role'], ['system', 'user', 'assistant'], true)
            && is_string($m['content'])
            && trim($m['content']) !== '';
    }));
    // Cap history to avoid token overflow
    if (count($messages) > 40) {
        $messages = array_slice($messages, -40);
    }
    // Ensure first message is a system message and uses current system prompt
    if (empty($messages) || $messages[0]['role'] !== 'system') {
        array_unshift($messages, ['role' => 'system', 'content' => $systemContent]);
    } else {
        $messages[0]['content'] = $systemContent;
    }
} else {
    // Single-turn: build from individual fields
    $prompt = isset($body['prompt']) ? trim((string)$body['prompt']) : '';

    $messages = [['role' => 'system', 'content' => $systemContent]];

    if ($mode === 'edit' && $selection !== '') {
        $instruction = $prompt !== '' ? $prompt : 'Improve this code.';
        $messages[]  = [
            'role'    => 'user',
            'content' => "Here is the code selection to modify:\n```\n{$selection}\n```\n\n{$instruction}",
        ];
    } elseif ($mode === 'fix') {
        $codeContext = $selection !== '' ? $selection : $prompt;
        if ($codeContext === '') {
            http_response_code(400);
            header('Content-Type: application/json');
            header('X-Content-Type-Options: nosniff');
            echo json_encode(['error' => '"selection" or "prompt" with the code to fix is required.']);
            exit;
        }
        $messages[] = [
            'role'    => 'user',
            'content' => "The following {$langLabel} code has a runtime error. "
                . "Fix it and return ONLY the corrected complete source code.\n\n"
                . "Code:\n```\n{$codeContext}\n```"
                . ($errorOutput !== '' ? "\n\nError output:\n{$errorOutput}" : ''),
        ];
    } elseif ($mode === 'explain') {
        $codeContext = $selection !== '' ? $selection : $prompt;
        if ($codeContext === '') {
            http_response_code(400);
            header('Content-Type: application/json');
            header('X-Content-Type-Options: nosniff');
            echo json_encode(['error' => '"selection" or "prompt" with the code to explain is required.']);
            exit;
        }
        $messages[] = [
            'role'    => 'user',
            'content' => "Explain this {$langLabel} code:\n```\n{$codeContext}\n```",
        ];
    } else {
        // generate
        if ($prompt === '') {
            http_response_code(400);
            header('Content-Type: application/json');
            header('X-Content-Type-Options: nosniff');
            echo json_encode(['error' => 'A "prompt" or "messages" array is required.']);
            exit;
        }
        $messages[] = ['role' => 'user', 'content' => $prompt];
    }
}

// ── Choose model and build payload ────────────────────────────────────────
$model      = $pro ? 'gpt-4o' : 'gpt-4o-mini';
$apiPayload = [
    'model'       => $model,
    'max_tokens'  => 4096,
    'temperature' => $isExplain ? 0.4 : 0.2,
    'messages'    => $messages,
    'stream'      => $stream,
];

// ── Streaming response (SSE forwarded from OpenAI) ────────────────────────
if ($stream) {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');
    header('X-Accel-Buffering: no');
    header('X-Content-Type-Options: nosniff');

    if (!function_exists('curl_init')) {
        echo "data: " . json_encode(['error' => 'Streaming requires the cURL PHP extension.']) . "\n\n";
        flush();
        exit;
    }

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_POST          => true,
        CURLOPT_HTTPHEADER    => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS    => json_encode($apiPayload),
        CURLOPT_TIMEOUT       => 90,
        CURLOPT_WRITEFUNCTION => static function ($ch, $data) {
            echo $data;
            if (ob_get_level() > 0) {
                ob_flush();
            }
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

// ── Non-streaming response ────────────────────────────────────────────────
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if (function_exists('curl_init')) {
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ],
        CURLOPT_POSTFIELDS     => json_encode($apiPayload),
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_RETURNTRANSFER => true,
    ]);
    $response = curl_exec($ch);
    if ($response === false) {
        $response = false;
    }
    curl_close($ch);
} else {
    $ctx = stream_context_create([
        'http' => [
            'method'        => 'POST',
            'header'        => implode("\r\n", [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ]),
            'content'       => json_encode($apiPayload),
            'timeout'       => 60,
            'ignore_errors' => true,
        ],
    ]);
    $response = @file_get_contents('https://api.openai.com/v1/chat/completions', false, $ctx);
}

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'Failed to reach the OpenAI API. Please try again.']);
    exit;
}

$result = json_decode($response, true);

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

if ($isExplain) {
    echo json_encode(['text' => $content]);
} else {
    // Strip accidental markdown fences if the model added them anyway
    $content = preg_replace('/^```[a-zA-Z]*\n?/', '', $content);
    $content = preg_replace('/\n?```$/', '', $content);
    $content = trim($content);
    echo json_encode(['code' => $content]);
}
