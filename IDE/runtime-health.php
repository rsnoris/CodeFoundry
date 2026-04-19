<?php
/**
 * CodeFoundry IDE – Runtime health endpoint
 *
 * GET /IDE/runtime-health.php
 *
 * Returns a JSON object describing the Docker execution engine state so the
 * IDE front-end can give users accurate, up-to-date feedback instead of a
 * static "preparing" message.
 *
 * Response schema
 * ---------------
 *   { "status": "ready" | "warming" | "failed" | "unavailable",
 *     "message": "<human-readable string>",
 *     "log_tail": "<last N lines of setup log, only when not ready>" }
 *
 * HTTP status codes
 * -----------------
 *   200  – always; the "status" field carries the semantic result.
 */

declare(strict_types=1);

header('Content-Type: application/json');
require_once __DIR__ . '/runtime_bootstrap.php';

// ── 1. Docker is fully operational ──────────────────────────────────────────
if (cfDockerCliAvailable() && cfDockerDaemonAvailable()) {
    echo json_encode([
        'status'  => 'ready',
        'message' => 'Docker runtime is ready.',
    ]);
    exit;
}

// ── 2. Setup script is currently running (warming up) ────────────────────────
if (cfRuntimeSetupInProgress()) {
    echo json_encode([
        'status'   => 'warming',
        'message'  => 'Docker runtime is being set up. This usually takes a few minutes on first boot.',
        'log_tail' => cfRuntimeSetupLogTail(30),
    ]);
    exit;
}

// ── 3. Setup has run before but Docker is still not responding → failed ───────
$logTail = cfRuntimeSetupLogTail(30);
if ($logTail !== '') {
    echo json_encode([
        'status'   => 'failed',
        'message'  => 'Docker runtime setup completed but Docker is not responding. Check the setup log or contact an administrator.',
        'log_tail' => $logTail,
    ]);
    exit;
}

// ── 4. Docker was never set up on this host ───────────────────────────────────
echo json_encode([
    'status'  => 'unavailable',
    'message' => 'Docker runtime is not available. The host system service has not been started.',
]);
