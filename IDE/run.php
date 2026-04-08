<?php
/**
 * CodeFoundry IDE – Code Execution Proxy
 *
 * Accepts a POST request with JSON body:
 *   { "language": "python", "code": "...", "stdin": "..." }
 *
 * Proxies to the Piston code-execution API and returns its response.
 */

declare(strict_types=1);

header('Content-Type: application/json');

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Read and size-limit the raw request body
$raw = file_get_contents('php://input');
if ($raw === false || strlen($raw) > 131072) { // 128 KiB hard cap
    http_response_code(413);
    echo json_encode(['error' => 'Request body too large']);
    exit;
}

// Parse JSON body
$input = json_decode($raw, true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

$language = $input['language'] ?? '';
$code     = $input['code']     ?? '';
$stdin    = $input['stdin']    ?? '';

// Whitelist of Piston language identifiers that the IDE exposes
$allowed = [
    'python', 'javascript', 'typescript', 'java',
    'c', 'c++', 'csharp', 'go', 'rust', 'php',
    'ruby', 'swift', 'kotlin', 'r', 'bash',
    'lua', 'perl', 'haskell', 'scala',
];

if (!is_string($language) || !in_array($language, $allowed, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unsupported or missing language']);
    exit;
}

if (!is_string($code) || strlen($code) > 65536) { // 64 KB code cap
    http_response_code(400);
    echo json_encode(['error' => 'Code too large or invalid']);
    exit;
}

if (!is_string($stdin) || strlen($stdin) > 10240) { // 10 KB stdin cap
    http_response_code(400);
    echo json_encode(['error' => 'Stdin input too large or invalid']);
    exit;
}

// Build the Piston request payload
$payload = json_encode([
    'language'         => $language,
    'version'          => '*',
    'files'            => [['name' => 'main', 'content' => $code]],
    'stdin'            => $stdin,
    'run_timeout'      => 10000,
    'compile_timeout'  => 10000,
]);

// Proxy to Piston API
$ch = curl_init('https://emkc.org/api/v2/piston/execute');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Accept: application/json',
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 20,
    CURLOPT_SSL_VERIFYPEER => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

if ($response === false) {
    http_response_code(502);
    echo json_encode(['error' => 'Execution service unavailable: ' . $curlErr]);
    exit;
}

http_response_code($httpCode);
echo $response;
