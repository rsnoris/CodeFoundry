<?php
declare(strict_types=1);

const CF_RUNTIME_SETUP_PID_FILE = '/tmp/codefoundry-runtime-setup.pid';
const CF_RUNTIME_SETUP_LOG_FILE = '/tmp/codefoundry-runtime-setup.log';

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
