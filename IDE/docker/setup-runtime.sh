#!/usr/bin/env bash
# IDE/docker/setup-runtime.sh
#
# Automated runtime bootstrap for the CodeFoundry IDE execution engine.
# - Installs Docker Engine when missing (Debian/Ubuntu, RHEL/Fedora/Amazon Linux)
# - Starts/enables Docker daemon
# - Pulls official language runtime images
# - Builds custom CodeFoundry language images

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

OFFICIAL_IMAGES=(
  "python:3.12-slim"
  "node:20-slim"
  "eclipse-temurin:21-jdk-alpine"
  "gcc:13"
  "mcr.microsoft.com/dotnet/sdk:8.0"
  "golang:1.22-alpine"
  "rust:slim"
  "php:8.3-cli"
  "ruby:3.3-slim"
  "swift:5.10-slim"
  "r-base:4.4"
  "bash:5.2"
  "perl:5.38-slim"
  "haskell:9.8"
  "virtuslab/scala-cli:latest"
  "dart:stable"
  "gnuoctave/octave:9.2.0"
)

SKIP_INSTALL=0
SKIP_PULL=0
SKIP_BUILD=0

usage() {
  cat <<'EOF'
Usage: bash IDE/docker/setup-runtime.sh [--skip-install] [--skip-pull] [--skip-build]

Options:
  --skip-install   Do not install Docker if missing
  --skip-pull      Skip pulling official runtime images
  --skip-build     Skip building custom CodeFoundry images
  -h, --help       Show help
EOF
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --skip-install) SKIP_INSTALL=1 ;;
    --skip-pull) SKIP_PULL=1 ;;
    --skip-build) SKIP_BUILD=1 ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown option: $1" >&2
      usage
      exit 1
      ;;
  esac
  shift
done

SUDO=""
if [[ "$(id -u)" -ne 0 ]]; then
  SUDO="sudo"
fi

ensure_docker_cli() {
  if command -v docker >/dev/null 2>&1; then
    return 0
  fi

  if [[ "$SKIP_INSTALL" -eq 1 ]]; then
    echo "Docker CLI is missing and --skip-install was provided." >&2
    exit 1
  fi

  echo "Docker CLI not found. Installing Docker Engine..."

  if command -v apt-get >/dev/null 2>&1; then
    $SUDO apt-get update
    $SUDO apt-get install -y docker.io
  elif command -v dnf >/dev/null 2>&1; then
    $SUDO dnf install -y docker
  elif command -v yum >/dev/null 2>&1; then
    $SUDO yum install -y docker
  else
    echo "Unsupported package manager. Install Docker manually first." >&2
    exit 1
  fi
}

start_docker_service() {
  echo "Ensuring Docker daemon is running..."

  if command -v systemctl >/dev/null 2>&1; then
    $SUDO systemctl enable --now docker
  else
    $SUDO service docker start
  fi

  if ! $SUDO docker info >/dev/null 2>&1; then
    echo "Docker daemon is not reachable after startup." >&2
    exit 1
  fi
}

add_current_user_to_docker_group() {
  if [[ "$(id -u)" -eq 0 ]]; then
    return 0
  fi

  if id -nG "$USER" | grep -qw docker; then
    return 0
  fi

  echo "Adding ${USER} to docker group..."
  $SUDO usermod -aG docker "$USER" || true
  echo "Note: group membership may require re-login before docker can run without sudo."
}

pull_official_images() {
  if [[ "$SKIP_PULL" -eq 1 ]]; then
    return 0
  fi

  echo "Pulling official runtime images..."
  for image in "${OFFICIAL_IMAGES[@]}"; do
    echo "  -> ${image}"
    $SUDO docker pull "$image"
  done
}

build_custom_images() {
  if [[ "$SKIP_BUILD" -eq 1 ]]; then
    return 0
  fi

  echo "Building custom CodeFoundry runtime images..."
  bash "${SCRIPT_DIR}/build.sh"
}

main() {
  ensure_docker_cli
  start_docker_service
  add_current_user_to_docker_group
  pull_official_images
  build_custom_images
  echo ""
  echo "CodeFoundry IDE runtime setup complete."
}

main "$@"
