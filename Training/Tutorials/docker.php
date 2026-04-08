<?php
$tutorial_title = 'Docker';
$tutorial_slug  = 'docker';
$quiz_slug      = 'docker';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Docker is a platform for developing, shipping, and running applications in containers. Containers package an application with all its dependencies — runtime, libraries, configuration — into a portable, isolated unit that runs consistently on any machine with Docker installed. Created in 2013 by Solomon Hykes, Docker transformed how software is built and deployed by solving the "it works on my machine" problem once and for all.</p>',
        'concepts' => [
            'Containers vs. VMs: shared OS kernel, lightweight, fast startup',
            'Docker architecture: daemon, client, registry (Docker Hub)',
            'Images vs. containers: immutable image → running container',
            'docker pull, docker run, docker ps, docker stop, docker rm',
            'Port mapping: -p host:container',
            'Environment variables: -e KEY=VALUE',
            'Volumes: -v host:container for persistent data',
        ],
        'code' => [
            'title'   => 'Docker run essentials',
            'lang'    => 'bash',
            'content' =>
'# Pull and run a PostgreSQL container
docker run -d \
  --name postgres \
  -e POSTGRES_PASSWORD=secret \
  -e POSTGRES_DB=myapp \
  -p 5432:5432 \
  -v postgres_data:/var/lib/postgresql/data \
  postgres:16-alpine

# Inspect running containers
docker ps
docker logs postgres --tail 50 --follow
docker stats postgres        # live CPU/memory usage

# Execute commands inside a running container
docker exec -it postgres psql -U postgres -d myapp

# Lifecycle
docker stop postgres
docker start postgres
docker rm postgres           # remove container (not image)
docker rmi postgres:16-alpine # remove image

# List images and volumes
docker images
docker volume ls',
        ],
        'tips' => [
            'Use docker run -d to detach; -it for interactive terminal; --rm to auto-remove on exit.',
            'Named volumes (docker volume create) persist data across container restarts; bind mounts (-v /host:/container) for dev.',
            'Always pin image tags (postgres:16-alpine not postgres:latest) for reproducible deployments.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>A Dockerfile is the recipe for building a Docker image — a series of instructions that layer filesystem changes on top of a base image. Multi-stage builds use multiple FROM instructions to create a lean final image, discarding build tools and intermediate artefacts that should not ship to production. Understanding layer caching — how Docker reuses layers when instructions haven\'t changed — is critical for fast image builds.</p>',
        'concepts' => [
            'Dockerfile instructions: FROM, RUN, COPY, ADD, WORKDIR, ENV, EXPOSE, CMD, ENTRYPOINT',
            'Layer caching: instruction order matters for cache efficiency',
            'Multi-stage builds: builder stage + minimal production stage',
            'docker build: -t name:tag, --build-arg, --no-cache, --platform',
            '.dockerignore: exclude node_modules, .git, secrets from build context',
            'Base image selection: alpine, slim, distroless for minimal attack surface',
            'CMD vs. ENTRYPOINT: default command vs. fixed executable',
        ],
        'code' => [
            'title'   => 'Multi-stage Dockerfile for Node.js',
            'lang'    => 'dockerfile',
            'content' =>
'# ---- Build stage ----
FROM node:20-alpine AS builder
WORKDIR /app

# Copy dependency files first (cache layer)
COPY package*.json ./
RUN npm ci --only=production --frozen-lockfile

# ---- Production stage ----
FROM node:20-alpine AS production

# Create non-root user for security
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

WORKDIR /app

# Copy only the built artefacts
COPY --from=builder /app/node_modules ./node_modules
COPY --chown=appuser:appgroup src ./src
COPY --chown=appuser:appgroup package.json .

USER appuser

EXPOSE 3000
ENV NODE_ENV=production

HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD wget -qO- http://localhost:3000/health || exit 1

CMD ["node", "src/index.js"]',
        ],
        'tips' => [
            'Copy package.json before your source code — unchanged dependencies hit the layer cache every build.',
            'Run containers as a non-root user — add RUN adduser and USER instructions to every production Dockerfile.',
            'Use HEALTHCHECK so Docker (and Kubernetes) knows when a container is actually ready to serve traffic.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Docker Compose defines and runs multi-container applications using a YAML file. Instead of running multiple docker run commands and remembering all the flags, Compose encodes the entire application stack — services, networks, volumes, environment variables — as code. A single <code>docker compose up</code> starts everything; <code>down</code> tears it all down.</p>',
        'concepts' => [
            'docker-compose.yml: services, networks, volumes, depends_on',
            'Service configuration: image, build, ports, environment, volumes, restart',
            'Docker networks: bridge (default), host, overlay (Swarm); service DNS',
            'Health checks in Compose: healthcheck, condition: service_healthy',
            'Profiles: selective service startup with --profile flag',
            'Override files: docker-compose.override.yml for local dev overrides',
            'docker compose exec, logs, ps, down -v',
        ],
        'code' => [
            'title'   => 'docker-compose.yml for full-stack app',
            'lang'    => 'yaml',
            'content' =>
'services:
  api:
    build: ./api
    ports: ["3000:3000"]
    environment:
      DATABASE_URL: postgresql://postgres:secret@db:5432/myapp
      REDIS_URL:    redis://redis:6379
    depends_on:
      db:
        condition: service_healthy
      redis:
        condition: service_started
    restart: unless-stopped

  db:
    image: postgres:16-alpine
    environment:
      POSTGRES_PASSWORD: secret
      POSTGRES_DB:       myapp
    volumes:
      - postgres_data:/var/lib/postgresql/data
    healthcheck:
      test:     ["CMD-SHELL", "pg_isready -U postgres"]
      interval: 10s
      timeout:  5s
      retries:  5

  redis:
    image: redis:7-alpine
    volumes: [redis_data:/data]

volumes:
  postgres_data:
  redis_data:',
        ],
        'tips' => [
            'Use service_healthy depends_on conditions — service_started does not wait for the service to be ready.',
            'Store secrets in a .env file and add it to .gitignore — reference with ${VAR} in the compose file.',
            'Use docker compose --profile dev up to start additional dev-only services (pgAdmin, Redis Commander).',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Container security goes beyond running as non-root. Distroless or scratch base images eliminate the shell and package manager, reducing the attack surface to nearly zero. Read-only filesystems, dropped Linux capabilities, seccomp profiles, and runtime scanning with Trivy or Grype complete a defence-in-depth container security posture. Docker Content Trust (Notary) ensures image integrity with cryptographic signatures.</p><p>Docker BuildKit enables advanced build features: BuildKit cache mounts (persistent build caches), SSH forwarding for private dependencies, and concurrent multi-platform builds with QEMU emulation.</p>',
        'concepts' => [
            'Container security: non-root user, read-only filesystem, dropped capabilities',
            'Distroless images: gcr.io/distroless/nodejs, minimal attack surface',
            'Image scanning: docker scout, Trivy, Grype for CVE detection',
            'Docker Content Trust: image signing and verification',
            'BuildKit: DOCKER_BUILDKIT=1, RUN --mount=type=cache for build caches',
            'Multi-platform builds: docker buildx, QEMU, --platform linux/amd64,linux/arm64',
            'BuildKit secrets: RUN --mount=type=secret for secure build-time credentials',
        ],
        'code' => [
            'title'   => 'BuildKit with cache mounts',
            'lang'    => 'dockerfile',
            'content' =>
'# syntax=docker/dockerfile:1
FROM python:3.12-slim AS builder

WORKDIR /app

# BuildKit cache mount: pip cache persists between builds
RUN --mount=type=cache,target=/root/.cache/pip \
    --mount=type=bind,source=requirements.txt,target=requirements.txt \
    pip install --user -r requirements.txt

# ---- Production ----
FROM gcr.io/distroless/python3-debian12

COPY --from=builder /root/.local /root/.local
COPY src/ /app/src/

WORKDIR /app
ENV PATH=/root/.local/bin:$PATH \
    PYTHONUNBUFFERED=1

USER nonroot:nonroot

ENTRYPOINT ["python", "-m", "src.main"]',
        ],
        'tips' => [
            'BuildKit cache mounts (--mount=type=cache) can reduce rebuild time from minutes to seconds.',
            'Use docker scout cves <image> to check for vulnerabilities before pushing to production.',
            'Build multi-platform images with docker buildx bake — ship ARM64 images for Apple Silicon and AWS Graviton.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Docker involves understanding the container runtime (runc, containerd), the OCI (Open Container Initiative) specifications, and the overlayfs storage driver that makes layer stacking efficient. Rootless Docker (running the daemon without root) and user namespaces further harden the security boundary between containers and the host. At scale, Docker Swarm or Kubernetes replace single-host Docker for orchestration.</p>',
        'concepts' => [
            'Container runtimes: Docker → containerd → runc → OCI runtime spec',
            'OCI Image Specification: media types, manifest, layers, config',
            'Storage drivers: overlay2, fuse-overlayfs, devicemapper',
            'Rootless Docker: running dockerd as a non-root user',
            'User namespaces: mapping container UIDs to unprivileged host UIDs',
            'cgroups v2: resource limits (CPU, memory, I/O, pids)',
            'Docker Swarm: services, stacks, secrets, configs, rolling updates',
        ],
        'code' => [
            'title'   => 'Docker cgroups resource limits',
            'lang'    => 'bash',
            'content' =>
'# Limit a container to 2 CPU cores and 512 MB memory
docker run -d \
  --name api \
  --cpus="2.0" \
  --memory="512m" \
  --memory-swap="512m" \     # swap = memory limit means no swap
  --pids-limit=100 \         # prevent fork bombs
  --read-only \              # read-only root filesystem
  --tmpfs /tmp:rw,size=100m \ # writable tmpfs for /tmp only
  --cap-drop ALL \           # drop all Linux capabilities
  --cap-add NET_BIND_SERVICE \ # re-add only what is needed
  --security-opt no-new-privileges \
  --security-opt seccomp=/etc/docker/seccomp/default.json \
  my-api:latest

# Inspect resource usage
docker stats api

# Export an image as a tar archive (for air-gapped environments)
docker save my-api:latest | gzip > my-api.tar.gz
docker load < my-api.tar.gz',
        ],
        'tips' => [
            'Set --memory-swap equal to --memory to disable swap — unbounded swap causes OOM behaviour, not fast OOM.',
            'Drop ALL capabilities and add back only what the application needs — principle of least privilege.',
            'Read the OCI image spec and runtime spec — understanding them makes Kubernetes image pulls and pod specs intuitive.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
