<?php
/**
 * CodeFoundry – GitHub Repository API Endpoint
 *
 * JSON API used by the "Push to GitHub" modal in /Generate/.
 * Requires the user to be logged in and to have connected a GitHub token
 * (via /Generate/github_oauth.php).
 *
 * Actions (POST JSON body: {"action": "..."}):
 *   status  – return connection status and GitHub username
 *   repos   – list the authenticated user's repositories (page/per_page supported)
 *   create  – create a new private repository  { name, description? }
 *   push    – push a single file to a repo     { repo, path, content, message? }
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';

session_start();

header('Content-Type: application/json');

/** Send a JSON error response and exit. */
function gh_api_error(string $msg, int $code = 400): never
{
    http_response_code($code);
    echo json_encode(['error' => $msg]);
    exit;
}

/** Perform a GitHub API request. Returns decoded JSON or throws on failure. */
function gh_api_request(string $method, string $url, string $token, array $body = []): array
{
    $headers = [
        "Authorization: Bearer {$token}",
        'Accept: application/vnd.github+json',
        'User-Agent: CodeFoundry',
        'X-GitHub-Api-Version: 2022-11-28',
    ];
    $opts = ['http' => [
        'method'        => $method,
        'ignore_errors' => true,
        'timeout'       => 15,
        'header'        => implode("\r\n", $headers),
    ]];
    if (!empty($body)) {
        $opts['http']['content'] = json_encode($body);
        $opts['http']['header'] .= "\r\nContent-Type: application/json";
    }
    $resp = @file_get_contents($url, false, stream_context_create($opts));
    if ($resp === false) {
        return ['_error' => 'Network error contacting GitHub API.'];
    }
    $decoded = json_decode($resp, true);
    return is_array($decoded) ? $decoded : ['_raw' => $resp];
}

// ── Auth guard ──────────────────────────────────────────────────────────────

if (empty($_SESSION['cf_user'])) {
    gh_api_error('Not logged in.', 401);
}

$username = $_SESSION['cf_user']['username'];
$user     = UserStore::findUser($username);
if ($user === null) {
    gh_api_error('User not found.', 401);
}

// ── Parse request ───────────────────────────────────────────────────────────

$raw    = (string) file_get_contents('php://input');
$input  = json_decode($raw, true) ?? [];
$action = trim((string)($input['action'] ?? ''));

// ── Actions that do NOT require a GitHub token ──────────────────────────────

if ($action === 'status') {
    $token     = $user['github_token']    ?? '';
    $ghUser    = $user['github_username'] ?? '';
    $connected = ($token !== '');
    echo json_encode(['connected' => $connected, 'github_user' => $connected ? $ghUser : null]);
    exit;
}

// ── All remaining actions require a stored GitHub token ─────────────────────

$token = $user['github_token'] ?? '';
if ($token === '') {
    gh_api_error('GitHub account not connected. Please connect your GitHub account first.', 403);
}

// ── repos ────────────────────────────────────────────────────────────────────

if ($action === 'repos') {
    $page    = max(1, (int)($input['page'] ?? 1));
    $perPage = min(100, max(1, (int)($input['per_page'] ?? 30)));
    $url     = "https://api.github.com/user/repos?per_page={$perPage}&page={$page}&sort=updated&affiliation=owner";
    $data    = gh_api_request('GET', $url, $token);
    if (isset($data['_error'])) {
        gh_api_error($data['_error'], 502);
    }
    if (isset($data['message'])) {
        // GitHub returned an error object
        gh_api_error($data['message'], 502);
    }
    // Return minimal repo list
    $repos = [];
    foreach ($data as $r) {
        if (!is_array($r)) continue;
        $repos[] = [
            'full_name'   => $r['full_name']   ?? '',
            'name'        => $r['name']         ?? '',
            'private'     => $r['private']      ?? false,
            'description' => $r['description']  ?? '',
            'html_url'    => $r['html_url']      ?? '',
        ];
    }
    echo json_encode(['repos' => $repos]);
    exit;
}

// ── create ───────────────────────────────────────────────────────────────────

if ($action === 'create') {
    $name = trim((string)($input['name'] ?? ''));
    if ($name === '') {
        gh_api_error('Repository name is required.');
    }
    if (!preg_match('/^[a-zA-Z0-9_.-]{1,100}$/', $name)) {
        gh_api_error('Repository name may only contain letters, numbers, hyphens, underscores, and dots (max 100 chars).');
    }
    $body = [
        'name'        => $name,
        'description' => (string)($input['description'] ?? 'Generated with CodeFoundry'),
        'private'     => true,
        'auto_init'   => true,
    ];
    $data = gh_api_request('POST', 'https://api.github.com/user/repos', $token, $body);
    if (isset($data['_error'])) {
        gh_api_error($data['_error'], 502);
    }
    if (isset($data['errors'])) {
        $msg = $data['message'] ?? 'Failed to create repository.';
        gh_api_error($msg);
    }
    if (empty($data['full_name'])) {
        gh_api_error($data['message'] ?? 'Failed to create repository.');
    }
    echo json_encode([
        'full_name' => $data['full_name'],
        'html_url'  => $data['html_url'] ?? '',
    ]);
    exit;
}

// ── push ─────────────────────────────────────────────────────────────────────

if ($action === 'push') {
    $fullName = trim((string)($input['repo'] ?? ''));
    $filePath = trim((string)($input['path'] ?? ''), '/ ');
    $content  = (string)($input['content'] ?? '');
    $message  = trim((string)($input['message'] ?? '')) ?: 'Add code generated by CodeFoundry';

    if ($fullName === '') {
        gh_api_error('Repository name is required.');
    }
    if ($filePath === '') {
        gh_api_error('File path is required.');
    }
    if ($content === '') {
        gh_api_error('File content is required.');
    }
    // Sanitise path: no traversal, only safe characters
    if (strpos($filePath, '..') !== false || !preg_match('#^[a-zA-Z0-9_./ -]{1,255}$#', $filePath)) {
        gh_api_error('Invalid file path.');
    }

    $apiUrl = "https://api.github.com/repos/{$fullName}/contents/{$filePath}";

    // Check if the file already exists so we can supply the sha for an update
    $existing = gh_api_request('GET', $apiUrl, $token);
    $sha      = $existing['sha'] ?? null;

    $body = [
        'message' => $message,
        'content' => base64_encode($content),
    ];
    if ($sha !== null) {
        $body['sha'] = $sha;
    }

    $data = gh_api_request('PUT', $apiUrl, $token, $body);
    if (isset($data['_error'])) {
        gh_api_error($data['_error'], 502);
    }
    if (isset($data['message']) && empty($data['content'])) {
        gh_api_error($data['message']);
    }

    $fileUrl = $data['content']['html_url'] ?? "https://github.com/{$fullName}/blob/main/{$filePath}";
    echo json_encode(['url' => $fileUrl, 'html_url' => "https://github.com/{$fullName}"]);
    exit;
}

// ── disconnect ────────────────────────────────────────────────────────────────

if ($action === 'disconnect') {
    UserStore::updateUser($username, ['github_token' => '', 'github_username' => '']);
    echo json_encode(['ok' => true]);
    exit;
}

gh_api_error('Unknown action.');
