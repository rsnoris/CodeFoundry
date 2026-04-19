<?php
declare(strict_types=1);

const CF_RUNTIME_SETUP_PID_FILE  = '/tmp/codefoundry-runtime-setup.pid';
const CF_RUNTIME_SETUP_LOG_FILE  = '/tmp/codefoundry-runtime-setup.log';
const CF_RUNTIME_EXEC_LOG_FILE   = '/tmp/codefoundry-exec.log';

// ---------------------------------------------------------------------------
// Rate-limit constants (per IP, sliding window)
// ---------------------------------------------------------------------------
const CF_RATE_LIMIT_DIR      = '/tmp/cf_rate';
const CF_RATE_LIMIT_WINDOW   = 60;   // seconds
const CF_RATE_LIMIT_MAX_REQS = 20;   // requests per window per IP

// ---------------------------------------------------------------------------
// Concurrent-execution slot constants
// ---------------------------------------------------------------------------
const CF_CONCURRENT_SLOT_DIR   = '/tmp/cf_concurrent';
const CF_MAX_CONCURRENT_RUNS   = 10;   // max simultaneous executions across all users
const CF_CONCURRENT_STALE_SECS = 120;  // release slots whose holder died > N seconds ago

/**
 * Resolve an absolute docker binary path when possible.
 */
function cfDockerBinaryPath(): ?string
{
    $path = trim((string)shell_exec('command -v docker 2>/dev/null'));
    if ($path !== '') {
        return $path;
    }

    $candidates = ['/usr/bin/docker', '/usr/local/bin/docker', '/snap/bin/docker'];
    foreach ($candidates as $candidate) {
        if (is_file($candidate) && is_executable($candidate)) {
            return $candidate;
        }
    }

    return null;
}

function cfDockerCliAvailable(): bool
{
    return cfDockerBinaryPath() !== null;
}

function cfDockerDaemonAvailable(): bool
{
    $docker = cfDockerBinaryPath();
    if ($docker === null) {
        return false;
    }

    exec(escapeshellarg($docker) . ' info >/dev/null 2>&1', $output, $code);
    return $code === 0;
}

function cfRuntimeSetupScriptPath(): string
{
    return __DIR__ . '/docker/setup-runtime.sh';
}

function cfIsProcessRunning(int $pid): bool
{
    if ($pid <= 0) {
        return false;
    }
    exec('ps -p ' . escapeshellarg((string)$pid) . ' >/dev/null 2>&1', $output, $code);
    return $code === 0;
}

function cfRuntimeSetupInProgress(): bool
{
    if (!is_file(CF_RUNTIME_SETUP_PID_FILE)) {
        return false;
    }

    $pid = (int)trim((string)@file_get_contents(CF_RUNTIME_SETUP_PID_FILE));
    if (cfIsProcessRunning($pid)) {
        return true;
    }

    @unlink(CF_RUNTIME_SETUP_PID_FILE);
    return false;
}

function cfStartRuntimeSetup(): bool
{
    if (cfRuntimeSetupInProgress()) {
        return true;
    }

    $scriptPath = cfRuntimeSetupScriptPath();
    if (!is_file($scriptPath)) {
        return false;
    }

    $cmd = 'nohup bash ' . escapeshellarg($scriptPath)
        . ' >' . escapeshellarg(CF_RUNTIME_SETUP_LOG_FILE)
        . ' 2>&1 & echo $!';
    $pid = trim((string)shell_exec('sh -c ' . escapeshellarg($cmd)));

    if (!ctype_digit($pid)) {
        return false;
    }

    $pidInt = (int)$pid;
    if (!cfIsProcessRunning($pidInt)) {
        return false;
    }

    return @file_put_contents(CF_RUNTIME_SETUP_PID_FILE, (string)$pidInt) !== false;
}

// ---------------------------------------------------------------------------
// Setup-log tail
// ---------------------------------------------------------------------------

/**
 * Return the last $lines lines of the runtime-setup log, or '' if none.
 */
function cfRuntimeSetupLogTail(int $lines = 30): string
{
    if (!is_file(CF_RUNTIME_SETUP_LOG_FILE)) {
        return '';
    }
    $content = @file_get_contents(CF_RUNTIME_SETUP_LOG_FILE);
    if ($content === false || $content === '') {
        return '';
    }
    $all  = explode("\n", rtrim($content));
    $tail = array_slice($all, -$lines);
    return implode("\n", $tail);
}

// ---------------------------------------------------------------------------
// Execution logging
// ---------------------------------------------------------------------------

/**
 * Append one JSON-lines execution record to the execution log.
 */
function cfLogExecution(
    string $lang,
    int    $exitCode,
    float  $durationMs,
    bool   $timedOut,
    string $containerName,
    string $clientIp
): void {
    $entry = json_encode([
        'ts'        => date('c'),
        'lang'      => $lang,
        'exit'      => $exitCode,
        'ms'        => (int)round($durationMs),
        'timed_out' => $timedOut,
        'container' => $containerName,
        'ip'        => $clientIp,
    ]) . "\n";
    @file_put_contents(CF_RUNTIME_EXEC_LOG_FILE, $entry, FILE_APPEND | LOCK_EX);
}

// ---------------------------------------------------------------------------
// Per-IP rate limiting  (sliding window, file-based)
// ---------------------------------------------------------------------------

/**
 * Check whether the given IP is within its rate-limit budget.
 *
 * Returns true  → request is allowed (counter already incremented).
 * Returns false → rate limit exceeded.
 */
function cfCheckRateLimit(string $ip): bool
{
    // No client IP could be determined – assign a shared fallback bucket so
    // rate limiting still applies rather than failing open to unlimited runs.
    if ($ip === '') {
        $ip = '0.0.0.0';
    }
    if (!is_dir(CF_RATE_LIMIT_DIR)) {
        @mkdir(CF_RATE_LIMIT_DIR, 0700, true);
    }

    $keyFile = CF_RATE_LIMIT_DIR . '/' . hash('sha256', $ip);
    $fp      = @fopen($keyFile, 'c+');
    if ($fp === false) {
        // Filesystem unavailable – fail closed to prevent unbounded executions
        // during storage problems.
        return false;
    }

    flock($fp, LOCK_EX);

    $now        = time();
    $raw        = fread($fp, 8192);
    $timestamps = ($raw !== false && $raw !== '')
        ? (json_decode($raw, true) ?? [])
        : [];

    if (!is_array($timestamps)) {
        $timestamps = [];
    }

    // Slide the window: drop entries older than the allowed period.
    $timestamps = array_values(array_filter(
        $timestamps,
        static fn($t): bool => is_numeric($t) && ($now - (int)$t) < CF_RATE_LIMIT_WINDOW
    ));

    $allowed = count($timestamps) < CF_RATE_LIMIT_MAX_REQS;
    if ($allowed) {
        $timestamps[] = $now;
    }

    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($timestamps));
    flock($fp, LOCK_UN);
    fclose($fp);

    return $allowed;
}

// ---------------------------------------------------------------------------
// Concurrent-execution slot manager  (file-based counting semaphore)
// ---------------------------------------------------------------------------

/**
 * Attempt to acquire a concurrent-execution slot.
 *
 * Uses an exclusive lock on a shared counter file to prevent races.
 *
 * Returns the slot-file path on success, or null when the server is at
 * maximum concurrent-run capacity.
 */
function cfAcquireConcurrentSlot(): ?string
{
    if (!is_dir(CF_CONCURRENT_SLOT_DIR)) {
        @mkdir(CF_CONCURRENT_SLOT_DIR, 0700, true);
    }

    $lockFile = CF_CONCURRENT_SLOT_DIR . '/.lock';
    $lock     = @fopen($lockFile, 'c');
    if ($lock === false) {
        return null; // filesystem unavailable; refuse to allow unbounded runs
    }

    flock($lock, LOCK_EX);

    try {
        // Remove stale slot files (process died without cleanup).
        $staleThreshold = time() - CF_CONCURRENT_STALE_SECS;
        $files          = glob(CF_CONCURRENT_SLOT_DIR . '/slot_*') ?: [];
        $active         = 0;
        foreach ($files as $f) {
            if (@filemtime($f) < $staleThreshold) {
                @unlink($f);
            } else {
                $active++;
            }
        }

        if ($active >= CF_MAX_CONCURRENT_RUNS) {
            return null;
        }

        $slotPath = CF_CONCURRENT_SLOT_DIR . '/slot_' . bin2hex(random_bytes(8));
        @file_put_contents($slotPath, (string)getmypid());
        return $slotPath;
    } finally {
        flock($lock, LOCK_UN);
        fclose($lock);
    }
}

/**
 * Release a previously acquired concurrent-execution slot.
 */
function cfReleaseConcurrentSlot(?string $slotPath): void
{
    if ($slotPath !== null) {
        @unlink($slotPath);
    }
}
