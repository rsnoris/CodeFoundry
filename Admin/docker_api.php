<?php
/**
 * CodeFoundry – Admin Docker Monitoring API
 *
 * POST /Admin/docker_api.php
 *
 * JSON endpoint for admin-only Docker execution-engine management.
 * Access is restricted to users with role = 'admin'.
 * Mutating operations require a matching CSRF token.
 *
 * Actions
 * -------
 *   status            – runtime health snapshot (daemon, setup, running containers, exec stats)
 *   list_containers   – docker ps -a (read-only)
 *   list_images       – docker images (read-only)
 *   container_action  – stop | rm on a specific container (mutating, requires CSRF)
 *   init_runtime      – start setup-runtime.sh in background (mutating, requires CSRF)
 *   logs              – setup log tail + execution log records
 */

declare(strict_types=1);

header('Content-Type: application/json');

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/IDE/runtime_bootstrap.php';

// ── Admin-only guard ───────────────────────────────────────────────────────
$_api_user = cf_current_user();
if (($_api_user['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
if ($raw === false || strlen($raw) > 4096) {
    http_response_code(400);
    echo json_encode(['error' => 'Bad request']);
    exit;
}

$input = json_decode($raw, true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$action         = trim((string)($input['action'] ?? ''));
$admin_username = (string)($_api_user['username'] ?? '');

// ── CSRF validation for mutating operations ────────────────────────────────
const DOCKER_MUTATING_ACTIONS = ['init_runtime', 'container_action'];

if (in_array($action, DOCKER_MUTATING_ACTIONS, true)) {
    $submitted_csrf = (string)($input['csrf_token'] ?? '');
    $session_csrf   = (string)($_SESSION['csrf_token'] ?? '');
    if ($submitted_csrf === '' || !hash_equals($session_csrf, $submitted_csrf)) {
        http_response_code(403);
        echo json_encode(['error' => 'CSRF token mismatch']);
        exit;
    }
}

// ── Permitted container operations (allowlist) ─────────────────────────────
const ALLOWED_CONTAINER_OPS = ['stop', 'rm'];

// ── Helpers ────────────────────────────────────────────────────────────────

/**
 * Validate a Docker container name or short ID.
 * Allows alphanumeric characters plus underscore, hyphen, and dot.
 * Length: 1–128 characters. Must start with an alphanumeric character.
 */
function cfValidateContainerRef(string $ref): bool
{
    return $ref !== '' && preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_.\-]{0,127}$/', $ref) === 1;
}

/**
 * Run a Docker CLI command with individually shell-escaped arguments.
 * The docker binary path is resolved via cfDockerBinaryPath().
 *
 * @param  string[] $args  Arguments after 'docker' (each escaped individually).
 * @return array{output:string, code:int}
 */
function cfAdminDockerRun(array $args): array
{
    $binary = cfDockerBinaryPath();
    if ($binary === null) {
        return ['output' => 'Docker binary not found.', 'code' => 127];
    }

    // Build command: binary + each argument is individually shell-escaped.
    $parts = [escapeshellarg($binary)];
    foreach ($args as $arg) {
        $parts[] = escapeshellarg((string)$arg);
    }
    $cmd = implode(' ', $parts) . ' 2>&1';

    exec($cmd, $lines, $exitCode);
    return ['output' => implode("\n", $lines), 'code' => $exitCode];
}

/**
 * Parse Docker --format '{{json .}}' JSONL output into an array of records.
 * Each non-empty line is expected to be a valid JSON object.
 */
function cfParseDockerJsonl(string $output): array
{
    $records = [];
    foreach (explode("\n", trim($output)) as $line) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }
        $obj = json_decode($line, true);
        if (is_array($obj)) {
            $records[] = $obj;
        }
    }
    return $records;
}

/**
 * Read the last $lines entries from the JSONL execution log,
 * returned as an array of decoded objects (newest first).
 */
function cfReadExecLogTail(int $lines): array
{
    if (!is_file(CF_RUNTIME_EXEC_LOG_FILE)) {
        return [];
    }
    $content = @file_get_contents(CF_RUNTIME_EXEC_LOG_FILE);
    if ($content === false || $content === '') {
        return [];
    }
    $all     = explode("\n", rtrim($content));
    $tail    = array_slice($all, -$lines);
    $records = [];
    foreach (array_reverse($tail) as $row) {
        $row = trim($row);
        if ($row === '') {
            continue;
        }
        $obj = json_decode($row, true);
        if (is_array($obj)) {
            $records[] = $obj;
        }
    }
    return $records;
}

// ── Action dispatch ────────────────────────────────────────────────────────

switch ($action) {

    // ── Runtime status snapshot ──────────────────────────────────────────
    case 'status':
        $daemonReady  = cfDockerCliAvailable() && cfDockerDaemonAvailable();
        $setupRunning = cfRuntimeSetupInProgress();
        $setupLogTail = cfRuntimeSetupLogTail(25);

        $runningCount = 0;
        $totalImages  = 0;

        if ($daemonReady) {
            $psResult = cfAdminDockerRun(['ps', '-q']);
            if ($psResult['code'] === 0 && $psResult['output'] !== '') {
                $runningCount = count(array_filter(
                    explode("\n", trim($psResult['output'])),
                    static fn(string $l): bool => $l !== ''
                ));
            }

            $imgResult = cfAdminDockerRun(['images', '-q']);
            if ($imgResult['code'] === 0 && $imgResult['output'] !== '') {
                $totalImages = count(array_filter(
                    explode("\n", trim($imgResult['output'])),
                    static fn(string $l): bool => $l !== ''
                ));
            }
        }

        // Aggregate execution stats from the last 100 entries
        $execEntries = cfReadExecLogTail(100);
        $execTotal   = count($execEntries);
        $execFailed  = count(array_filter(
            $execEntries,
            static fn(array $e): bool => ($e['exit'] ?? 0) !== 0 || ($e['timed_out'] ?? false)
        ));
        $execAvgMs = $execTotal > 0
            ? (int)round(array_sum(array_column($execEntries, 'ms')) / $execTotal)
            : 0;

        echo json_encode([
            'daemon_ready'       => $daemonReady,
            'setup_running'      => $setupRunning,
            'running_containers' => $runningCount,
            'total_images'       => $totalImages,
            'setup_log_tail'     => $setupLogTail,
            'exec_stats'         => [
                'total'  => $execTotal,
                'failed' => $execFailed,
                'avg_ms' => $execAvgMs,
            ],
        ]);
        break;

    // ── List all containers ───────────────────────────────────────────────
    case 'list_containers':
        if (!cfDockerCliAvailable() || !cfDockerDaemonAvailable()) {
            echo json_encode(['containers' => [], 'error' => 'Docker daemon is not available.']);
            break;
        }
        $result     = cfAdminDockerRun(['ps', '-a', '--format', '{{json .}}']);
        $containers = ($result['code'] === 0) ? cfParseDockerJsonl($result['output']) : [];
        echo json_encode(['containers' => $containers]);
        break;

    // ── List all local images ─────────────────────────────────────────────
    case 'list_images':
        if (!cfDockerCliAvailable() || !cfDockerDaemonAvailable()) {
            echo json_encode(['images' => [], 'error' => 'Docker daemon is not available.']);
            break;
        }
        $result = cfAdminDockerRun(['images', '--format', '{{json .}}']);
        $images = ($result['code'] === 0) ? cfParseDockerJsonl($result['output']) : [];
        echo json_encode(['images' => $images]);
        break;

    // ── Container lifecycle action (stop / rm) ────────────────────────────
    case 'container_action':
        $op  = trim((string)($input['op']   ?? ''));
        $ref = trim((string)($input['name'] ?? ''));

        if (!in_array($op, ALLOWED_CONTAINER_OPS, true)) {
            echo json_encode(['error' => 'Operation not allowed. Permitted: ' . implode(', ', ALLOWED_CONTAINER_OPS)]);
            break;
        }
        if (!cfValidateContainerRef($ref)) {
            echo json_encode(['error' => 'Invalid container name or ID.']);
            break;
        }
        if (!cfDockerCliAvailable() || !cfDockerDaemonAvailable()) {
            echo json_encode(['error' => 'Docker daemon is not available.']);
            break;
        }

        // rm uses -f to handle running containers; stop is straightforward.
        $cmdArgs = ($op === 'rm') ? ['rm', '-f', $ref] : ['stop', $ref];
        $result  = cfAdminDockerRun($cmdArgs);

        AuditStore::log('admin.docker_container_' . $op, $admin_username, [
            'container' => $ref,
            'exit_code' => $result['code'],
            'output'    => substr($result['output'], 0, 256),
        ]);

        if ($result['code'] !== 0) {
            echo json_encode(['error' => 'Command failed: ' . $result['output']]);
        } else {
            echo json_encode(['ok' => true, 'output' => $result['output']]);
        }
        break;

    // ── Initialize / prewarm runtime ──────────────────────────────────────
    case 'init_runtime':
        if (cfRuntimeSetupInProgress()) {
            echo json_encode(['ok' => true, 'message' => 'Runtime setup is already running in the background.']);
            break;
        }

        $started = cfStartRuntimeSetup();

        AuditStore::log('admin.docker_runtime_init', $admin_username, ['started' => $started]);

        if ($started) {
            echo json_encode(['ok' => true, 'message' => 'Runtime setup started in the background. Refresh the Setup Log to follow progress.']);
        } else {
            echo json_encode(['error' => 'Failed to start runtime setup. Ensure IDE/docker/setup-runtime.sh exists and is executable by the web-server user.']);
        }
        break;

    // ── Log tails ─────────────────────────────────────────────────────────
    case 'logs':
        $lines = min(200, max(10, (int)($input['lines'] ?? 50)));
        echo json_encode([
            'setup_log' => cfRuntimeSetupLogTail($lines),
            'exec_log'  => cfReadExecLogTail($lines),
        ]);
        break;

    // ── Unknown action ────────────────────────────────────────────────────
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action.']);
        break;
}
