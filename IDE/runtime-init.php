<?php
declare(strict_types=1);

header('Content-Type: application/json');
require_once __DIR__ . '/runtime_bootstrap.php';

// Read-only status check – setup is triggered exclusively by the host
// system service (IDE/docker/codefoundry-runtime.service), not from
// browser requests.
$dockerReady  = cfDockerCliAvailable() && cfDockerDaemonAvailable();
$setupRunning = cfRuntimeSetupInProgress();

echo json_encode([
    'ready'   => $dockerReady,
    'running' => $setupRunning,
]);
