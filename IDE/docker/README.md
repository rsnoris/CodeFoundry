# CodeFoundry – Docker Execution Engine

## Overview

`IDE/run.php` executes submitted code locally inside **sandboxed Docker
containers** instead of proxying to the external Piston API.  Each execution
gets its own short-lived container that is destroyed immediately after the
program finishes.

### Security controls applied to every container

| Flag | Effect |
|------|--------|
| `--network=none` | No outbound or inbound network access |
| `--memory=256m --memory-swap=256m` | Hard memory cap |
| `--cpus=0.5` | Bounded to half a CPU core |
| `--pids-limit=64` | Prevents fork-bomb escalation |
| `--cap-drop=ALL` | Drops all Linux capabilities |
| `--security-opt=no-new-privileges` | Blocks setuid / privilege escalation |
| `--stop-timeout=0` | SIGKILL sent instantly on container stop |
| `--tmpfs /tmp:rw,nosuid,size=128m` | Writable scratch space in the container; never persists to the host |
| Named container + `docker rm -f` | Force-removes the container after a timeout even if the docker client was killed |

### Per-request guards (in `IDE/run.php`)

| Guard | Limit | Response |
|-------|-------|----------|
| Per-IP rate limit | 20 requests / 60 seconds | HTTP 429 + `Retry-After: 60` |
| Concurrent-run cap | 10 simultaneous executions (all users) | HTTP 429 |

---

## Prerequisites

- Docker Engine 20.10+ installed on the host, **or** installable by the runtime
  bootstrap script (`apt-get`, `apt`, `dnf`, `microdnf`, `yum`, `apk`, `zypper`,
  `pacman`)
- The web-server user (e.g. `www-data`) must be able to run `docker` commands:
  ```bash
  sudo usermod -aG docker www-data
  # then restart the web server / PHP-FPM
  ```

---

## Admin Docker Monitoring tab

Administrators can manage the execution engine entirely from the Admin Control
Panel without touching a terminal.  Navigate to:

```
/Admin/?tab=docker_instances
```

### Requirements

The web-server process (`www-data` or equivalent) must be able to run `docker`
commands without a password prompt:

```bash
sudo usermod -aG docker www-data
# then restart the web server / PHP-FPM
```

If Docker is not installed, **Initialize / Prewarm Runtime** will attempt to
install it automatically using the supported package managers above.

### Features

| Feature | Description |
|---------|-------------|
| **Status cards** | Real-time: daemon ready, running containers, cached images, recent execution stats |
| **Initialize / Prewarm Runtime** | Runs `IDE/docker/setup-runtime.sh` in the background — pulls official images and builds custom `codefoundry/*` images |
| **Containers table** | Lists all containers (`docker ps -a`) with Stop and Remove actions |
| **Images table** | Lists all locally cached images (`docker images`) |
| **Recent Executions** | Last 50 entries from `/tmp/codefoundry-exec.log` (language, exit code, duration, timed-out, container name, IP) |
| **Runtime Setup Log** | Last 80 lines of `/tmp/codefoundry-runtime-setup.log` — auto-updated while setup is running |
| **Auto-refresh** | Status cards refresh every 15 seconds while the tab is open |

### Security controls

* Access requires `role = admin` — a 403 is returned to any other user.
* All write operations (Init Runtime, Stop, Remove) require a matching
  session CSRF token.
* Container names are validated against a strict allowlist regex before being
  passed to the Docker CLI.
* Only `stop` and `rm` container operations are permitted.
* All admin Docker actions are recorded in the Audit Trail
  (`admin.docker_container_stop`, `admin.docker_container_rm`,
  `admin.docker_runtime_init`).

---

## Persistent runtime management (systemd)

The execution engine must be operational **before** users arrive, not
triggered by the first page load.  A systemd one-shot service is provided:

```
IDE/docker/codefoundry-runtime.service
```

### Install

1. Edit the `ExecStart=` path inside the unit file to match your deployment
   root (default: `/opt/CodeFoundry`).
2. Copy (or symlink) the unit to systemd's unit directory:
   ```bash
   sudo cp IDE/docker/codefoundry-runtime.service /etc/systemd/system/
   sudo systemctl daemon-reload
   sudo systemctl enable --now codefoundry-runtime
   ```
3. Check status at any time:
   ```bash
   sudo systemctl status codefoundry-runtime
   journalctl -u codefoundry-runtime -f
   ```

The service runs `setup-runtime.sh --skip-install` on every boot, which:
- Ensures Docker daemon is started
- Pulls all official language runtime images
- Builds the six custom `codefoundry/*` images

---

## Runtime health endpoint

`GET /IDE/runtime-health.php` returns a JSON status object:

```json
{ "status": "ready" | "warming" | "failed" | "unavailable",
  "message": "<human-readable string>",
  "log_tail": "<last 30 lines of setup log (warming/failed only)>" }
```

The IDE front-end polls this endpoint every 5 seconds and updates the output
panel banner automatically—no more permanent "Preparing Docker runtime…"
messages.

---

## One-time image setup

Most languages use **official Docker Hub images** that are pulled
automatically by the systemd service on first boot.  Six languages require
custom images:

| Language   | Image                          | Dockerfile |
|------------|--------------------------------|------------|
| TypeScript | `codefoundry/typescript:latest`| `typescript/Dockerfile` |
| Kotlin     | `codefoundry/kotlin:latest`    | `kotlin/Dockerfile` |
| Lua        | `codefoundry/lua:latest`       | `lua/Dockerfile` |
| Verilog    | `codefoundry/verilog:latest`   | `verilog/Dockerfile` |
| VHDL       | `codefoundry/vhdl:latest`      | `vhdl/Dockerfile` |
| Tcl        | `codefoundry/tcl:latest`       | `tcl/Dockerfile` |

Build all six at once:

```bash
cd /path/to/CodeFoundry
bash IDE/docker/build.sh
```

Or rebuild a single image:

```bash
bash IDE/docker/build.sh typescript
```

### Manual bootstrap (without systemd)

```bash
cd /path/to/CodeFoundry
bash IDE/docker/setup-runtime.sh
```

---

## Language → Docker image mapping

| Language   | Image | Execution model | Domain |
|------------|-------|-----------------|--------|
| Python     | `python:3.12-slim` | Interpreted | General Purpose |
| JavaScript | `node:20-slim` | Interpreted | General Purpose |
| TypeScript | `codefoundry/typescript:latest` | Interpreted via ts-node | General Purpose |
| Java       | `eclipse-temurin:21-jdk-alpine` | Compile (`javac`) then run (`java`) | General Purpose |
| C          | `gcc:13` | Compile (`gcc -O2`) then run | General Purpose |
| C++        | `gcc:13` | Compile (`g++ -O2`) then run | General Purpose |
| C#         | `mcr.microsoft.com/dotnet/sdk:8.0` | Compile (`dotnet build`) then run | General Purpose |
| Go         | `golang:1.22-alpine` | Interpreted via `go run` | General Purpose |
| Rust       | `rust:slim` | Compile (`rustc -O`) then run | General Purpose |
| PHP        | `php:8.3-cli` | Interpreted | General Purpose |
| Ruby       | `ruby:3.3-slim` | Interpreted | General Purpose |
| Bash       | `bash:5.2` | Interpreted | General Purpose |
| Lua        | `codefoundry/lua:latest` | Interpreted | General Purpose |
| Perl       | `perl:5.38-slim` | Interpreted | General Purpose |
| Haskell    | `haskell:9.8` | Compile (`ghc -O`) then run | General Purpose |
| Scala      | `virtuslab/scala-cli:latest` | Interpreted via `scala-cli run` | General Purpose |
| R          | `r-base:4.4` | Interpreted via `Rscript` | General Purpose |
| Swift      | `swift:5.10-slim` | Interpreted via `swift` runner | Mobile Apps |
| Kotlin     | `codefoundry/kotlin:latest` | Compile (`kotlinc`) then run (`java -jar`) | Mobile Apps |
| Dart       | `dart:stable` | Interpreted via `dart run` | Mobile Apps |
| Octave     | `gnuoctave/octave:9.2.0` | Interpreted via `octave` | Electrical & Engineering |
| Fortran    | `gcc:13` | Compile (`gfortran -O2`) then run | Electrical & Engineering |
| Verilog    | `codefoundry/verilog:latest` | Compile (`iverilog`) then simulate (`vvp`) | Semiconductor & Electronics |
| VHDL       | `codefoundry/vhdl:latest` | Compile+elaborate (`ghdl`) then simulate | Semiconductor & Electronics |
| Tcl        | `codefoundry/tcl:latest` | Interpreted via `tclsh` | Design Automation / EDA |

---

## Resource limits (configurable in `IDE/run.php`)

| Constant | Default | Description |
|----------|---------|-------------|
| `MEMORY_LIMIT` | `256m` | Per-container memory cap |
| `CPU_LIMIT` | `0.5` | Fraction of one CPU core |
| `PIDS_LIMIT` | `64` | Maximum number of processes |
| `RUN_TIMEOUT` | `10` s | Maximum execution time |
| `COMPILE_TIMEOUT` | `30` s | Maximum compile time |
| `MAX_OUTPUT_BYTES` | `524288` | 512 KB cap per output stream |

---

## Operational log files

| File | Contents |
|------|----------|
| `/tmp/codefoundry-runtime-setup.log` | Output of `setup-runtime.sh` |
| `/tmp/codefoundry-exec.log` | JSONL execution records (lang, exit code, duration, IP) |

Tail the execution log in real time:

```bash
tail -f /tmp/codefoundry-exec.log | python3 -m json.tool
```
