<?php
/**
 * CodeFoundry IDE – Custom Code Execution Engine
 *
 * Accepts a POST request with JSON body:
 *   { "language": "python", "code": "...", "stdin": "..." }
 *
 * Executes code in an isolated Docker container and returns results in a
 * format compatible with the former Piston API response shape, so the
 * frontend requires no changes.
 *
 * Requirements:
 *   - Docker CLI must be accessible by the PHP process (e.g. via sudo or
 *     by adding the web-server user to the "docker" group).
 *   - Custom images for TypeScript, Kotlin and Lua must be built once:
 *       cd IDE/docker && bash build.sh
 *     See IDE/docker/README.md for full setup instructions.
 */

declare(strict_types=1);

header('Content-Type: application/json');
require_once __DIR__ . '/runtime_bootstrap.php';

// ---------------------------------------------------------------------------
// Request validation
// ---------------------------------------------------------------------------

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
if ($raw === false || strlen($raw) > 131072) { // 128 KiB hard cap
    http_response_code(413);
    echo json_encode(['error' => 'Request body too large']);
    exit;
}

$input = json_decode($raw, true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON body']);
    exit;
}

$language = $input['language'] ?? '';
$code     = $input['code']     ?? '';
$stdin    = $input['stdin']    ?? '';

// ---------------------------------------------------------------------------
// Language registry
//
// Each entry defines:
//   image    – Docker image to use for execution
//   filename – Source file written into the container workspace
//   compile  – Shell command to compile the source (null = interpreted)
//   run      – Shell command to run the program (null = compile step runs it)
//
// Custom images (codefoundry/*) are built from IDE/docker/.
// All other images are official Docker Hub images.
// ---------------------------------------------------------------------------

const LANG_CONFIG = [
    'python'     => [
        'image'    => 'python:3.12-slim',
        'filename' => 'main.py',
        'compile'  => null,
        'run'      => 'python main.py',
    ],
    'javascript' => [
        'image'    => 'node:20-slim',
        'filename' => 'main.js',
        'compile'  => null,
        'run'      => 'node main.js',
    ],
    'typescript' => [
        'image'    => 'codefoundry/typescript:latest',
        'filename' => 'main.ts',
        'compile'  => null,
        'run'      => 'ts-node main.ts',
    ],
    'java'       => [
        'image'    => 'eclipse-temurin:21-jdk-alpine',
        'filename' => 'Main.java',
        'compile'  => 'javac Main.java',
        'run'      => 'java Main',
    ],
    'c'          => [
        'image'    => 'gcc:13',
        'filename' => 'main.c',
        'compile'  => 'gcc -O2 -o main main.c',
        'run'      => './main',
    ],
    'c++'        => [
        'image'    => 'gcc:13',
        'filename' => 'main.cpp',
        'compile'  => 'g++ -O2 -o main main.cpp',
        'run'      => './main',
    ],
    'csharp'     => [
        'image'    => 'mcr.microsoft.com/dotnet/sdk:8.0',
        'filename' => 'Program.cs',
        'compile'  => 'dotnet build Main.csproj -o out --nologo',
        'run'      => 'dotnet out/Main.dll',
    ],
    'go'         => [
        'image'    => 'golang:1.22-alpine',
        'filename' => 'main.go',
        'compile'  => null,
        'run'      => 'go run main.go',
    ],
    'rust'       => [
        'image'    => 'rust:slim',
        'filename' => 'main.rs',
        'compile'  => 'rustc -O -o main main.rs',
        'run'      => './main',
    ],
    'php'        => [
        'image'    => 'php:8.3-cli',
        'filename' => 'main.php',
        'compile'  => null,
        'run'      => 'php main.php',
    ],
    'ruby'       => [
        'image'    => 'ruby:3.3-slim',
        'filename' => 'main.rb',
        'compile'  => null,
        'run'      => 'ruby main.rb',
    ],
    'swift'      => [
        'image'    => 'swift:5.10-slim',
        'filename' => 'main.swift',
        'compile'  => null,
        'run'      => 'swift main.swift',
    ],
    'kotlin'     => [
        'image'    => 'codefoundry/kotlin:latest',
        'filename' => 'Main.kt',
        'compile'  => 'kotlinc Main.kt -include-runtime -d main.jar',
        'run'      => 'java -jar main.jar',
    ],
    'r'          => [
        'image'    => 'r-base:4.4',
        'filename' => 'main.r',
        'compile'  => null,
        'run'      => 'Rscript main.r',
    ],
    'bash'       => [
        'image'    => 'bash:5.2',
        'filename' => 'main.sh',
        'compile'  => null,
        'run'      => 'bash main.sh',
    ],
    'lua'        => [
        'image'    => 'codefoundry/lua:latest',
        'filename' => 'main.lua',
        'compile'  => null,
        'run'      => 'lua main.lua',
    ],
    'perl'       => [
        'image'    => 'perl:5.38-slim',
        'filename' => 'main.pl',
        'compile'  => null,
        'run'      => 'perl main.pl',
    ],
    'haskell'    => [
        'image'    => 'haskell:9.8',
        'filename' => 'Main.hs',
        'compile'  => 'ghc -O -o main Main.hs',
        'run'      => './main',
    ],
    'scala'      => [
        'image'    => 'virtuslab/scala-cli:latest',
        'filename' => 'main.scala',
        'compile'  => null,
        'run'      => 'scala-cli run main.scala',
    ],

    // ── Mobile Apps ──────────────────────────────────────────────────────────
    'dart'       => [
        'image'    => 'dart:stable',
        'filename' => 'main.dart',
        'compile'  => null,
        'run'      => 'dart run main.dart',
    ],

    // ── Electrical & Engineering ──────────────────────────────────────────────
    'octave'     => [
        'image'    => 'gnuoctave/octave:9.2.0',
        'filename' => 'main.m',
        'compile'  => null,
        'run'      => 'octave --no-gui main.m',
    ],
    'fortran'    => [
        'image'    => 'gcc:13',
        'filename' => 'main.f90',
        'compile'  => 'gfortran -O2 -o main main.f90',
        'run'      => './main',
    ],

    // ── Semiconductor & Electronics ───────────────────────────────────────────
    'verilog'    => [
        'image'    => 'codefoundry/verilog:latest',
        'filename' => 'main.v',
        'compile'  => 'iverilog -o main.out main.v',
        'run'      => 'vvp main.out',
    ],
    'vhdl'       => [
        'image'    => 'codefoundry/vhdl:latest',
        'filename' => 'main.vhd',
        'compile'  => 'ghdl -a --std=08 main.vhd && ghdl -e --std=08 main',
        'run'      => 'ghdl -r --std=08 main',
    ],

    // ── Design Automation / EDA ───────────────────────────────────────────────
    'tcl'        => [
        'image'    => 'codefoundry/tcl:latest',
        'filename' => 'main.tcl',
        'compile'  => null,
        'run'      => 'tclsh main.tcl',
    ],
];

if (!is_string($language) || !array_key_exists($language, LANG_CONFIG)) {
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

// ---------------------------------------------------------------------------
// Execution sandbox constants
// ---------------------------------------------------------------------------

const MEMORY_LIMIT              = '256m'; // per-container memory cap
const CPU_LIMIT                 = '0.5';  // fraction of one CPU core
const PIDS_LIMIT                = '64';   // prevents fork-bomb escalation
const RUN_TIMEOUT               = 10;     // seconds: maximum run time
const COMPILE_TIMEOUT           = 30;     // seconds: maximum compile time
const MAX_OUTPUT_BYTES          = 524288; // 512 KB per stream (stdout / stderr)
const PIPE_READ_BUFFER_SIZE     = 8192;   // bytes read per fread() call
const STREAM_SELECT_TIMEOUT_USEC = 100000; // 100 ms poll interval for stream_select
const STREAM_DRAIN_GRACE_PERIOD = 4;      // extra seconds to drain pipes after container timeout
const DOCKER_SETUP_HINT         = 'Docker runtime is not available yet. Run: bash IDE/docker/setup-runtime.sh';
const DOCKER_BOOTSTRAP_MESSAGE  = 'Docker runtime is initializing in the background. Please retry in about a minute.';

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

/**
 * Execute a command inside a sandboxed Docker container and return its output.
 *
 * Security measures applied to every container:
 *   --network=none          no outbound or inbound network access
 *   --memory / --cpus       resource caps to prevent resource exhaustion
 *   --pids-limit            prevents fork bombs
 *   --cap-drop=ALL          drops all Linux capabilities
 *   --security-opt=no-new-privileges  blocks privilege escalation via setuid
 *   --stop-timeout=0        SIGKILL sent immediately when the container stops
 *
 * The container is given a unique name so it can be force-removed after a
 * timeout even if the docker-client process was already killed.
 *
 * @return array{stdout:string, stderr:string, code:int, timed_out:bool}
 */
function dockerRun(
    string $image,
    string $workDir,
    string $stdin,
    string $command,
    int    $timeout
): array {
    $containerName = 'cf_exec_' . bin2hex(random_bytes(8));

    // Build the docker CLI argument list; each arg is escaped individually.
    $args = [
        'docker', 'run', '--rm',
        '--name',              $containerName,
        '--network=none',
        '--memory='          . MEMORY_LIMIT,
        '--memory-swap='     . MEMORY_LIMIT,
        '--cpus='            . CPU_LIMIT,
        '--pids-limit='      . PIDS_LIMIT,
        '--cap-drop=ALL',
        '--security-opt=no-new-privileges',
        '--stop-timeout=0',
        '-v', $workDir . ':/sandbox',
        '-w', '/sandbox',
        '-i',            // keep stdin open so we can pipe data in
        $image,
        'sh', '-c', $command,
    ];

    $dockerCmd = implode(' ', array_map('escapeshellarg', $args));

    // Wrap the docker invocation with the system timeout command.
    // --kill-after=2 sends SIGKILL to the docker client 2 s after the
    // initial SIGTERM, ensuring the client process cannot hang.
    $fullCmd = 'timeout --kill-after=2 ' . (int)$timeout . ' ' . $dockerCmd;

    $descriptors = [
        0 => ['pipe', 'r'],
        1 => ['pipe', 'w'],
        2 => ['pipe', 'w'],
    ];

    $process = proc_open($fullCmd, $descriptors, $pipes);

    if (!is_resource($process)) {
        return [
            'stdout'    => '',
            'stderr'    => 'Failed to start execution container.',
            'code'      => -1,
            'timed_out' => false,
        ];
    }

    // Write stdin and close immediately so the process is not blocked waiting.
    if ($stdin !== '') {
        fwrite($pipes[0], $stdin);
    }
    fclose($pipes[0]);

    // Switch both output pipes to non-blocking mode and drain them
    // concurrently with stream_select to avoid a deadlock that would occur
    // if we read stdout then stderr sequentially while the process fills the
    // opposite OS pipe buffer.
    stream_set_blocking($pipes[1], false);
    stream_set_blocking($pipes[2], false);

    $stdout   = '';
    $stderr   = '';
    $deadline = microtime(true) + $timeout + STREAM_DRAIN_GRACE_PERIOD;

    while (microtime(true) < $deadline) {
        $read   = [];
        if (!feof($pipes[1])) {
            $read[] = $pipes[1];
        }
        if (!feof($pipes[2])) {
            $read[] = $pipes[2];
        }
        if (empty($read)) {
            break;
        }

        $write  = null;
        $except = null;
        $n      = stream_select($read, $write, $except, 0, STREAM_SELECT_TIMEOUT_USEC);

        if ($n === false) {
            break;
        }

        foreach ($read as $pipe) {
            $chunk = fread($pipe, PIPE_READ_BUFFER_SIZE);
            if ($chunk === false || $chunk === '') {
                continue;
            }
            if ($pipe === $pipes[1]) {
                if (strlen($stdout) < MAX_OUTPUT_BYTES) {
                    $stdout .= substr($chunk, 0, MAX_OUTPUT_BYTES - strlen($stdout));
                }
            } else {
                if (strlen($stderr) < MAX_OUTPUT_BYTES) {
                    $stderr .= substr($chunk, 0, MAX_OUTPUT_BYTES - strlen($stderr));
                }
            }
        }
    }

    fclose($pipes[1]);
    fclose($pipes[2]);
    $code = proc_close($process);

    // Force-remove the container in case timeout killed the docker client
    // before the container exited on its own (--rm only fires on clean exit).
    exec('docker rm -f ' . escapeshellarg($containerName) . ' 2>/dev/null');

    return [
        'stdout'    => $stdout,
        'stderr'    => $stderr,
        'code'      => $code,
        'timed_out' => ($code === 124), // GNU timeout exits 124 on expiry
    ];
}

/**
 * Recursively delete a directory and all its contents.
 */
function removeDir(string $dir): void
{
    if (!is_dir($dir)) {
        return;
    }
    $items = glob($dir . '/*');
    foreach (($items !== false ? $items : []) as $item) {
        is_dir($item) ? removeDir($item) : unlink($item);
    }
    rmdir($dir);
}

// ---------------------------------------------------------------------------
// Prepare execution sandbox directory
// ---------------------------------------------------------------------------

$config  = LANG_CONFIG[$language];
$execDir = sys_get_temp_dir() . '/cf_exec_' . bin2hex(random_bytes(8));

if (!cfDockerCliAvailable() || !cfDockerDaemonAvailable()) {
    $setupTriggered = false;
    if (!cfRuntimeSetupInProgress()) {
        $setupTriggered = cfStartRuntimeSetup();
    }

    $runtimeMsg = ($setupTriggered || cfRuntimeSetupInProgress())
        ? DOCKER_BOOTSTRAP_MESSAGE
        : DOCKER_SETUP_HINT;

    echo json_encode([
        'language' => $language,
        'run'      => [
            'stdout' => '',
            'stderr' => $runtimeMsg,
            'code'   => 1,
            'signal' => null,
            'output' => $runtimeMsg,
        ],
    ]);
    exit;
}

if (!mkdir($execDir, 0755, true)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to create execution sandbox.']);
    exit;
}

// Write the source file.
if (file_put_contents($execDir . '/' . $config['filename'], $code) === false) {
    removeDir($execDir);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to write source file.']);
    exit;
}

// C# requires a minimal project file alongside the source.
if ($language === 'csharp') {
    $csproj = <<<'XML'
<Project Sdk="Microsoft.NET.Sdk">
  <PropertyGroup>
    <OutputType>Exe</OutputType>
    <TargetFramework>net8.0</TargetFramework>
    <Nullable>enable</Nullable>
    <ImplicitUsings>enable</ImplicitUsings>
  </PropertyGroup>
</Project>
XML;
    file_put_contents($execDir . '/Main.csproj', $csproj);
}

// ---------------------------------------------------------------------------
// Execution
// ---------------------------------------------------------------------------

$compileResult = null;

// Compile phase (compiled languages only).
if ($config['compile'] !== null) {
    $compileResult = dockerRun(
        $config['image'],
        $execDir,
        '',                     // no stdin during compilation
        $config['compile'],
        COMPILE_TIMEOUT
    );

    if ($compileResult['code'] !== 0) {
        removeDir($execDir);

        $stderrMsg = $compileResult['timed_out']
            ? 'Compilation timed out after ' . COMPILE_TIMEOUT . ' seconds.'
            : $compileResult['stderr'];

        echo json_encode([
            'language' => $language,
            'compile'  => [
                'stdout' => $compileResult['stdout'],
                'stderr' => $stderrMsg,
                'code'   => $compileResult['code'],
                'output' => $compileResult['stdout'] . $stderrMsg,
            ],
            'run'      => [
                'stdout' => '',
                'stderr' => '',
                'code'   => 1,
                'signal' => null,
                'output' => '',
            ],
        ]);
        exit;
    }
}

// Run phase.
$runCmd = $config['run'];

if ($runCmd !== null) {
    $runResult = dockerRun(
        $config['image'],
        $execDir,
        $stdin,
        $runCmd,
        RUN_TIMEOUT
    );
} else {
    // Language uses only the compile command to produce output (no separate
    // run step).  Promote the compile result to the run slot.
    $runResult     = $compileResult;
    $compileResult = null;
}

removeDir($execDir);

if ($runResult['timed_out']) {
    $runResult['stderr'] .= "\nExecution timed out after " . RUN_TIMEOUT . ' seconds.';
    $runResult['code']    = 1;
}

// ---------------------------------------------------------------------------
// Response – Piston-compatible shape so the frontend needs no changes
// ---------------------------------------------------------------------------

$response = [
    'language' => $language,
    'run'      => [
        'stdout' => $runResult['stdout'],
        'stderr' => $runResult['stderr'],
        'code'   => $runResult['code'],
        'signal' => null,
        'output' => $runResult['stdout'] . $runResult['stderr'],
    ],
];

if ($compileResult !== null) {
    $response['compile'] = [
        'stdout' => $compileResult['stdout'],
        'stderr' => $compileResult['stderr'],
        'code'   => $compileResult['code'],
        'output' => $compileResult['stdout'] . $compileResult['stderr'],
    ];
}

echo json_encode($response);
