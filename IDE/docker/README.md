# CodeFoundry – Docker Execution Engine

## Overview

`IDE/run.php` now executes submitted code locally inside **sandboxed Docker
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
| Named container + `docker rm -f` | Force-removes the container after a timeout even if the docker client was killed |

---

## Prerequisites

- Docker Engine 20.10+ installed on the host
- The web-server user (e.g. `www-data`) must be able to run `docker` commands:
  ```bash
  sudo usermod -aG docker www-data
  # then restart the web server / PHP-FPM
  ```

### Recommended one-command bootstrap

Run this once on a fresh host to install/start Docker (when supported), pre-pull
official runtime images, and build custom CodeFoundry images:

```bash
cd /path/to/CodeFoundry
bash IDE/docker/setup-runtime.sh
```

---

## One-time image setup

Most languages use **official Docker Hub images** that are pulled automatically
on first use.  Six languages require custom images that must be built once:

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

### Pre-pull official images (optional but recommended)

To avoid latency on first execution, pre-pull the images your users are likely
to use:

```bash
docker pull python:3.12-slim
docker pull node:20-slim
docker pull eclipse-temurin:21-jdk-alpine
docker pull gcc:13
docker pull mcr.microsoft.com/dotnet/sdk:8.0
docker pull golang:1.22-alpine
docker pull rust:slim
docker pull php:8.3-cli
docker pull ruby:3.3-slim
docker pull swift:5.10-slim
docker pull r-base:4.4
docker pull bash:5.2
docker pull perl:5.38-slim
docker pull haskell:9.8
docker pull virtuslab/scala-cli:latest
docker pull dart:stable
docker pull gnuoctave/octave:9.2.0
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
