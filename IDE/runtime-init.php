<?php
declare(strict_types=1);

header('Content-Type: application/json');
require_once __DIR__ . '/runtime_bootstrap.php';

$dockerReady = cfDockerCliAvailable() && cfDockerDaemonAvailable();
$setupRunning = cfRuntimeSetupInProgress();
$setupStarted = false;

if (!$dockerReady && !$setupRunning) {
    $setupStarted = cfStartRuntimeSetup();
    $setupRunning = $setupStarted || cfRuntimeSetupInProgress();
}

echo json_encode([
    'ready'   => $dockerReady,
    'running' => $setupRunning,
    'started' => $setupStarted,
]);
