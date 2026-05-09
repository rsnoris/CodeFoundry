<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';
require_once dirname(__DIR__) . '/lib/IdeWorkspace.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (!CF_IDE_VSCODE_ENABLED) {
    http_response_code(503);
    echo json_encode(['error' => 'Hosted VS Code mode is disabled.']);
    exit;
}

if (CF_IDE_VSCODE_BASE_URL === '') {
    http_response_code(503);
    echo json_encode(['error' => 'Hosted VS Code URL is not configured.']);
    exit;
}

cf_require_login();
$user = cf_current_user();
if ($user === null || empty($user['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Authentication required']);
    exit;
}

try {
    $workspace = IdeWorkspace::ensureForUser((string)$user['username']);
    if (!is_dir($workspace['workspace_path']) || !is_writable($workspace['workspace_path'])) {
        throw new \RuntimeException('Workspace directory is unavailable or not writable.');
    }
    $launchUrl = IdeWorkspace::buildHostedUrl($workspace['workspace_path']);
    if ($launchUrl === '') {
        throw new \RuntimeException('Failed to build hosted IDE URL.');
    }
} catch (\RuntimeException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

AuditStore::log('ide.hosted.bootstrap', (string)$user['username'], [
    'workspace_id' => $workspace['workspace_id'],
    'workspace'    => $workspace['workspace_name'],
]);

echo json_encode([
    'ok'          => true,
    'launch_url'  => $launchUrl,
    'workspace'   => [
        'id'   => $workspace['workspace_id'],
        'name' => $workspace['workspace_name'],
    ],
    'capabilities' => CF_IDE_CAPABILITIES,
]);
