<?php
$tutorial_title = 'Bash';
$tutorial_slug  = 'bash';
$quiz_slug      = 'bash';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Bash (Bourne Again Shell) is the default command interpreter on most Linux and macOS systems. It is simultaneously an interactive command-line interface and a full scripting language. Bash scripts automate repetitive tasks — file management, deployment pipelines, system administration, and text processing — that would take far more code in a general-purpose language. Every developer who works on Linux or macOS benefits from shell fluency.</p>',
        'concepts' => [
            'Shebang: #!/usr/bin/env bash and script permissions (chmod +x)',
            'Variables: NAME=value (no spaces!), ${NAME}, quoting rules',
            'Command substitution: $(command) and backtick alternatives',
            'Arithmetic: $((expr)) and let',
            'Control flow: if/elif/else/fi, test [ ] and [[ ]]',
            'Loops: for item in list; do ... done; while condition; do ... done',
            'Positional parameters: $1, $2, $@, $#, $0',
        ],
        'code' => [
            'title'   => 'Bash script with argument validation',
            'lang'    => 'bash',
            'content' =>
'#!/usr/bin/env bash
set -euo pipefail  # exit on error, undefined var, pipe failure
IFS=$\'\n\t\'       # safer word splitting

usage() {
  echo "Usage: $0 <environment> <version>"
  echo "  environment: staging | production"
  echo "  version:     semver (e.g. 1.2.3)"
  exit 1
}

[[ $# -ne 2 ]] && usage

ENV=$1
VERSION=$2

if [[ ! "$ENV" =~ ^(staging|production)$ ]]; then
  echo "Error: environment must be staging or production" >&2
  exit 1
fi

if [[ ! "$VERSION" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "Error: version must be semver (e.g. 1.2.3)" >&2
  exit 1
fi

echo "Deploying version $VERSION to $ENV..."',
        ],
        'tips' => [
            'Always start scripts with set -euo pipefail — it catches most silent failure modes.',
            'Quote all variable expansions: "$var", not $var — unquoted variables split on spaces.',
            'Use [[ ]] (double brackets) instead of [ ] — it is safer and supports regex with =~.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Bash functions, arrays, and associative arrays (dictionaries) allow scripts to grow from one-liners into maintainable programs. The Unix pipeline — chaining commands with |, >, >>, and < — is Bash\'s superpower: dozens of powerful text-processing tools (grep, sed, awk, sort, uniq, cut, wc) compose through pipes into elegant data transformation workflows.</p>',
        'concepts' => [
            'Functions: name() { ... }, local variables, return values via exit codes',
            'Arrays: arr=(a b c), ${arr[0]}, ${arr[@]}, ${#arr[@]}',
            'Associative arrays: declare -A map; map[key]=val; ${map[key]}',
            'Pipelines: cmd1 | cmd2 | cmd3; stderr redirection 2>&1',
            'Text tools: grep (regex search), sed (stream editor), awk (field processing)',
            'sort, uniq, cut, wc, head, tail for data manipulation',
            'xargs for parallel and bulk command execution',
        ],
        'code' => [
            'title'   => 'Log analysis pipeline',
            'lang'    => 'bash',
            'content' =>
'#!/usr/bin/env bash
set -euo pipefail

LOGFILE="${1:-/var/log/nginx/access.log}"

echo "=== Top 10 IP addresses ==="
awk \'{print $1}\' "$LOGFILE" \
  | sort | uniq -c | sort -rn \
  | head -10

echo ""
echo "=== HTTP status code summary ==="
awk \'{print $9}\' "$LOGFILE" \
  | sort | uniq -c | sort -rn

echo ""
echo "=== 5xx errors in last hour ==="
HOUR_AGO=$(date -d "1 hour ago" "+%d/%b/%Y:%H" 2>/dev/null \
        || date -v-1H "+%d/%b/%Y:%H")  # GNU vs. BSD date

grep " 5[0-9][0-9] " "$LOGFILE" \
  | awk -v hour="$HOUR_AGO" \
      \'$4 ~ hour {count++} END {print count " 5xx errors"}\' ',
        ],
        'tips' => [
            'Learn awk\'s field separator: awk -F: \'{print $1}\' /etc/passwd splits on colons.',
            'Use tee to split a pipeline: cmd | tee output.log | next-cmd.',
            'Prefer printf over echo for portability — echo behaviour differs between shells.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Robust Bash scripting requires error handling strategies beyond set -e: trapping signals (SIGTERM, SIGINT) and EXIT for cleanup, error functions that print context before exiting, and temporary file management with mktemp to avoid collisions. File operations — finding files with find, synchronising with rsync, and archiving with tar — are the backbone of backup and deployment scripts.</p>',
        'concepts' => [
            'trap: trap "cleanup" EXIT SIGTERM SIGINT for reliable cleanup',
            'mktemp for safe temporary files and directories',
            'find: -name, -type, -mtime, -exec, -delete, -print0 with xargs -0',
            'rsync: -av --delete, --exclude, dry run with --dry-run, over SSH',
            'tar: create (-czf), extract (-xzf), list (-tzf)',
            'Process substitution: diff <(cmd1) <(cmd2)',
            'Here documents (heredoc): cat << EOF ... EOF for multi-line strings',
        ],
        'code' => [
            'title'   => 'Backup script with trap and mktemp',
            'lang'    => 'bash',
            'content' =>
'#!/usr/bin/env bash
set -euo pipefail

BACKUP_DIR="/backups"
SOURCE_DIR="${1:?Usage: $0 <source-dir>}"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
TMPDIR=$(mktemp -d)

cleanup() {
  local exit_code=$?
  rm -rf "$TMPDIR"
  [[ $exit_code -ne 0 ]] && echo "Script failed with exit code $exit_code" >&2
}
trap cleanup EXIT

log() { echo "[$(date +%T)] $*"; }

log "Creating backup of $SOURCE_DIR"
ARCHIVE="$BACKUP_DIR/backup_${TIMESTAMP}.tar.gz"

tar -czf "$TMPDIR/backup.tar.gz" -C "$(dirname "$SOURCE_DIR")" \
    "$(basename "$SOURCE_DIR")"

mv "$TMPDIR/backup.tar.gz" "$ARCHIVE"
log "Backup saved to $ARCHIVE ($(du -sh "$ARCHIVE" | cut -f1))"

# Keep only last 7 backups
find "$BACKUP_DIR" -name "backup_*.tar.gz" -printf "%T@ %p\n" \
  | sort -n | head -n -7 | awk \'{print $2}\' | xargs -r rm -v',
        ],
        'tips' => [
            'Always trap EXIT for cleanup — it runs whether the script succeeds, fails, or is interrupted.',
            'Use ${VAR:?error message} for mandatory parameters — it exits with a descriptive error if unset.',
            'Add --dry-run to rsync and check the output before running destructive sync operations.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced Bash covers concurrent execution — running background jobs with &, waiting with wait, and managing job arrays for parallel processing. String manipulation using parameter expansions (${var#prefix}, ${var%suffix}, ${var/search/replace}) avoids spawning subshells for simple text operations. Writing reusable Bash libraries with sourcing and namespacing enables shared utilities across scripts.</p>',
        'concepts' => [
            'Background jobs: &, wait, $!, PPID, jobs, kill',
            'Parallel execution: for item; do ... & done; wait',
            'Parameter expansion: ${#var}, ${var:offset:length}, ${var/pattern/replace}',
            'Case conversion: ${var^^}, ${var,,}, ${var~}',
            'Nameref variables: declare -n ref=target for indirect references',
            'Bash options: shopt -s globstar, nullglob, extglob',
            'Profiling Bash: PS4="+" with set -x, or bash --debugger with bashdb',
        ],
        'code' => [
            'title'   => 'Parallel job runner with error tracking',
            'lang'    => 'bash',
            'content' =>
'#!/usr/bin/env bash
set -euo pipefail

JOBS=4  # max parallel jobs
PIDS=()
FAILED=()

run_job() {
  local name=$1
  shift
  echo "Starting: $name"
  "$@" && echo "Done: $name" || { echo "FAILED: $name" >&2; return 1; }
}

wait_for_jobs() {
  local pid status
  for pid in "${PIDS[@]}"; do
    if ! wait "$pid"; then
      FAILED+=("$pid")
    fi
  done
  PIDS=()
}

ITEMS=(service1 service2 service3 service4 service5 service6)

for item in "${ITEMS[@]}"; do
  run_job "$item" sleep 1 &
  PIDS+=($!)
  (( ${#PIDS[@]} >= JOBS )) && wait_for_jobs
done
wait_for_jobs

(( ${#FAILED[@]} == 0 )) || { echo "Failed jobs: ${FAILED[*]}"; exit 1; }
echo "All jobs completed successfully"',
        ],
        'tips' => [
            'Use ${arr[@]} to iterate arrays and ${#arr[@]} for length — both are required for correct expansion.',
            'shopt -s globstar enables ** for recursive globbing: for f in **/*.log; do ... done.',
            'Limit parallel jobs explicitly — unrestricted & jobs can exhaust memory on large input sets.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Bash mastery involves understanding bash internals — the execution environment, subshell inheritance, file descriptor management, and the coprocess facility — alongside writing shell code that is robust across bash versions (3.x to 5.x) and POSIX sh for maximum portability. ShellCheck, the static analyser for shell scripts, catches hundreds of common mistakes and is an essential CI step for any serious Bash project.</p>',
        'concepts' => [
            'Coprocesses: coproc and bidirectional pipes',
            'File descriptors: exec 3<>file, custom FD redirection, /dev/fd',
            'Subshell inheritance: what is and isn\'t inherited (vars, traps, functions)',
            'POSIX sh portability: avoiding bashisms when scripts must run on /bin/sh',
            'ShellCheck: SC codes, disabling specific warnings, CI integration',
            'Bash completion: complete, compgen, COMPREPLY for custom tab completion',
            'Writing shell libraries: namespacing with prefixes, autoloading with BASH_SOURCE',
        ],
        'code' => [
            'title'   => 'ShellCheck-clean script skeleton',
            'lang'    => 'bash',
            'content' =>
'#!/usr/bin/env bash
# shellcheck shell=bash
# shellcheck enable=all
set -euo pipefail

readonly SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
readonly SCRIPT_NAME="$(basename "$0")"

log::info()  { echo "[INFO]  $(date -Iseconds) $*"; }
log::error() { echo "[ERROR] $(date -Iseconds) $*" >&2; }
log::die()   { log::error "$*"; exit 1; }

main() {
  [[ $# -gt 0 ]] || log::die "Usage: $SCRIPT_NAME <arg>"

  local arg="$1"
  log::info "Processing: $arg"

  # Always use local in functions; always quote expansions
  local result
  result=$(process "$arg") || log::die "process failed for $arg"
  log::info "Result: $result"
}

process() {
  local input="$1"
  printf "%s" "${input^^}"  # uppercase without subshell
}

main "$@"',
        ],
        'tips' => [
            'Run shellcheck ./*.sh in CI — it catches quoting bugs, bad conditionals, and portability issues.',
            'Use readonly for constants — it prevents accidental modification and documents intent.',
            'Prefix all library functions with a namespace (log::, util::) to avoid collision with other sources.',
            'Read the Bash manual (man bash) completely — there are features most developers never discover.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
