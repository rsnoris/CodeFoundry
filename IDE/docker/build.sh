#!/usr/bin/env bash
# IDE/docker/build.sh
#
# Build the three custom Docker images required by the CodeFoundry execution
# engine.  Run this script once on the host before starting the web server.
#
# Usage:
#   cd /path/to/CodeFoundry
#   bash IDE/docker/build.sh
#
# To rebuild a single image pass its name as the first argument:
#   bash IDE/docker/build.sh typescript

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

build() {
    local name="$1"
    local context="${SCRIPT_DIR}/${name}"
    echo "==> Building codefoundry/${name}:latest ..."
    docker build --pull -t "codefoundry/${name}:latest" "$context"
    echo "    Done."
}

if [[ $# -gt 0 ]]; then
    build "$1"
else
    build typescript
    build kotlin
    build lua
fi

echo ""
echo "All custom images built successfully."
echo "You can verify with: docker images | grep codefoundry"
