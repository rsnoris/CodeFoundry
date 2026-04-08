<?php
$tutorial_title = 'Cloud & DevOps';
$tutorial_slug  = 'cloud-devops';
$quiz_slug      = '';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>DevOps is a cultural and technical movement that unifies software development (Dev) and IT operations (Ops) to shorten the development lifecycle, increase deployment frequency, and deliver more reliable software. Cloud computing provides the on-demand, elastic infrastructure that makes modern DevOps practices possible. Together, they enable teams to build, test, deploy, and monitor applications at unprecedented speed and scale.</p>',
        'concepts' => [
            'DevOps culture: shared responsibility, continuous improvement, feedback loops',
            'The DevOps infinity loop: Plan, Code, Build, Test, Release, Deploy, Operate, Monitor',
            'CI/CD: Continuous Integration, Continuous Delivery, Continuous Deployment',
            'Cloud models: Public, Private, Hybrid, Multi-cloud',
            'Cloud providers: AWS, Google Cloud Platform (GCP), Microsoft Azure',
            'IaaS, PaaS, SaaS: cloud service models and their tradeoffs',
            'The 12-Factor App methodology for cloud-native applications',
        ],
        'code' => [
            'title'   => 'GitHub Actions CI pipeline',
            'lang'    => 'yaml',
            'content' =>
'name: CI

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - uses: actions/setup-node@v4
        with:
          node-version: "20"
          cache: "npm"

      - run: npm ci
      - run: npm run lint
      - run: npm test -- --coverage

      - uses: codecov/codecov-action@v4
        with:
          token: ${{ secrets.CODECOV_TOKEN }}

  build:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == \'refs/heads/main\'
    steps:
      - uses: actions/checkout@v4
      - run: docker build -t myapp:${{ github.sha }} .
      - run: docker push myapp:${{ github.sha }}',
        ],
        'tips' => [
            'Use actions/cache or setup-node/cache to cache dependencies between runs — it cuts CI time by 50%+.',
            'Pin action versions (@v4 not @latest) — unpinned actions can break your pipeline with upstream changes.',
            'Fail fast: run lint before tests — linting failures are faster to detect and fix.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Infrastructure as Code (IaC) treats infrastructure configuration the same way as application code — versioned, reviewed, and automated. Terraform is the most widely adopted IaC tool, using HCL (HashiCorp Configuration Language) to declare infrastructure resources across any cloud provider. Ansible uses YAML playbooks to configure servers and applications, complementing Terraform for post-provisioning configuration.</p>',
        'concepts' => [
            'Terraform: providers, resources, variables, outputs, state, plan/apply/destroy',
            'Terraform modules: reusable configuration packages; registry.terraform.io',
            'Terraform state: remote backend (S3+DynamoDB), state locking, workspaces',
            'Ansible: inventory, playbooks, tasks, roles, handlers, templates (Jinja2)',
            'Configuration management vs. provisioning tools',
            'GitOps: infrastructure changes via pull requests, automated apply on merge',
            'Secrets management: Vault, AWS Secrets Manager, Azure Key Vault',
        ],
        'code' => [
            'title'   => 'Terraform AWS infrastructure',
            'lang'    => 'hcl',
            'content' =>
'terraform {
  required_providers {
    aws = { source = "hashicorp/aws", version = "~> 5.0" }
  }
  backend "s3" {
    bucket         = "my-terraform-state"
    key            = "prod/main.tfstate"
    region         = "us-east-1"
    dynamodb_table = "terraform-lock"
    encrypt        = true
  }
}

provider "aws" { region = var.region }

variable "region"       { default = "us-east-1" }
variable "instance_type" { default = "t3.micro" }

resource "aws_instance" "api" {
  ami           = data.aws_ami.amazon_linux.id
  instance_type = var.instance_type
  tags          = { Name = "api-server", Environment = "production" }
}

data "aws_ami" "amazon_linux" {
  most_recent = true
  owners      = ["amazon"]
  filter { name = "name"; values = ["al2023-ami-*-x86_64"] }
}

output "instance_ip" { value = aws_instance.api.public_ip }',
        ],
        'tips' => [
            'Always use a remote backend for Terraform state — local state files get lost and cause team conflicts.',
            'Run terraform plan before apply in every pipeline — commit the plan output to the PR for review.',
            'Use Terraform workspaces or separate state files for dev/staging/prod — never share state between environments.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Kubernetes (K8s) is the de facto standard for container orchestration — automatically deploying, scaling, and managing containerised workloads across a cluster. Understanding the core objects — Pods, Deployments, Services, ConfigMaps, Secrets, Ingress — and the control loop pattern (desired state → actual state reconciliation) is fundamental to Kubernetes operations.</p><p>Helm simplifies Kubernetes application deployment with parameterised templates (charts), enabling versioned, repeatable releases that can be upgraded and rolled back atomically.</p>',
        'concepts' => [
            'Kubernetes architecture: control plane (API server, etcd, scheduler, controller manager), nodes, kubelet',
            'Core objects: Pod, Deployment, ReplicaSet, Service (ClusterIP, NodePort, LoadBalancer)',
            'ConfigMap and Secret: externalising configuration and credentials',
            'Ingress and IngressController: HTTP routing to services',
            'kubectl: apply, get, describe, logs, exec, port-forward, rollout',
            'Helm: chart structure, values.yaml, helm install/upgrade/rollback',
            'Namespaces and RBAC for multi-tenant cluster organisation',
        ],
        'code' => [
            'title'   => 'Kubernetes Deployment and Service',
            'lang'    => 'yaml',
            'content' =>
'apiVersion: apps/v1
kind: Deployment
metadata:
  name: api
  labels: { app: api }
spec:
  replicas: 3
  selector:
    matchLabels: { app: api }
  strategy:
    type: RollingUpdate
    rollingUpdate: { maxSurge: 1, maxUnavailable: 0 }
  template:
    metadata:
      labels: { app: api }
    spec:
      containers:
        - name: api
          image: myapp:1.2.3
          ports: [{ containerPort: 3000 }]
          envFrom:
            - configMapRef: { name: api-config }
            - secretRef:    { name: api-secrets }
          resources:
            requests: { cpu: "100m", memory: "128Mi" }
            limits:   { cpu: "500m", memory: "512Mi" }
          readinessProbe:
            httpGet: { path: /health, port: 3000 }
            initialDelaySeconds: 5
---
apiVersion: v1
kind: Service
metadata: { name: api }
spec:
  selector: { app: api }
  ports: [{ port: 80, targetPort: 3000 }]',
        ],
        'tips' => [
            'Always set resource requests and limits — without them, a noisy neighbour can starve your workload.',
            'Use readinessProbe to prevent traffic reaching pods that are not ready — essential for zero-downtime deployments.',
            'Use helm diff (helm plugin) before upgrading to preview the changes a Helm chart update will make.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced DevOps covers observability — the combination of metrics (Prometheus + Grafana), logs (ELK stack or Loki), and traces (Jaeger, Zipkin, OpenTelemetry) that gives teams full visibility into production systems. SLOs (Service Level Objectives) define reliability targets that drive engineering priorities. GitOps with Argo CD or Flux automates Kubernetes deployments through pull-based reconciliation from a Git repository.</p>',
        'concepts' => [
            'Observability: the three pillars — metrics, logs, traces',
            'Prometheus: scraping metrics, PromQL, recording rules, alerting rules',
            'Grafana: dashboards, data sources, alerting, provisioning with code',
            'ELK stack / Loki: log aggregation, parsing, indexing, querying',
            'OpenTelemetry: distributed tracing, spans, context propagation',
            'GitOps: Argo CD or Flux for pull-based Kubernetes deployments',
            'SLO / SLI / Error Budget: defining and tracking reliability targets',
        ],
        'code' => [
            'title'   => 'Prometheus alert rule',
            'lang'    => 'yaml',
            'content' =>
'groups:
  - name: api-slos
    rules:
      # SLI: request success rate (5xx errors)
      - record: job:http_requests:rate5m
        expr: rate(http_requests_total[5m])

      - record: job:http_errors:rate5m
        expr: rate(http_requests_total{status=~"5.."}[5m])

      # Alert: error rate > 1% for 5 minutes
      - alert: HighErrorRate
        expr: |
          (job:http_errors:rate5m / job:http_requests:rate5m) > 0.01
        for: 5m
        labels:
          severity: critical
          team:     backend
        annotations:
          summary:     "High error rate on {{ $labels.job }}"
          description: "Error rate is {{ $value | humanizePercentage }}"
          runbook:     "https://runbooks.internal/high-error-rate"

      # Alert: p99 latency > 500ms
      - alert: HighP99Latency
        expr: |
          histogram_quantile(0.99,
            rate(http_request_duration_seconds_bucket[5m])) > 0.5
        for: 5m
        labels:   { severity: warning }
        annotations:
          summary: "p99 latency above 500ms on {{ $labels.job }}"',
        ],
        'tips' => [
            'Define SLOs before deployment — retroactive SLOs are always too lenient.',
            'Use Argo CD ApplicationSets to deploy the same application to multiple clusters from one definition.',
            'Include a runbook URL in every alert annotation — on-call engineers need context at 3 AM.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Cloud & DevOps involves platform engineering — building internal developer platforms (IDPs) with Backstage, custom Kubernetes operators with the Operator Framework, and FinOps practices for cloud cost optimisation. Chaos engineering (Chaos Monkey, Litmus Chaos) proactively finds weaknesses before they become incidents. Service mesh (Istio, Cilium) provides mTLS, traffic management, and deep observability at the infrastructure layer.</p>',
        'concepts' => [
            'Platform Engineering: Internal Developer Platform (IDP), Backstage, golden paths',
            'Kubernetes Operators: Custom Resource Definitions (CRDs), controller pattern',
            'Service mesh: Istio, Cilium, mTLS, traffic management, observability',
            'FinOps: rightsizing, Reserved Instances vs. Spot, cost allocation tags, Kubecost',
            'Chaos engineering: Chaos Monkey, Gremlin, Litmus Chaos, GameDay exercises',
            'Supply chain security: SLSA levels, SBOM, Sigstore/cosign for image signing',
            'Platform SRE: toil reduction, error budget policy, postmortem culture',
        ],
        'code' => [
            'title'   => 'Kubernetes CRD and operator pattern',
            'lang'    => 'yaml',
            'content' =>
'# Custom Resource Definition (CRD)
apiVersion: apiextensions.k8s.io/v1
kind: CustomResourceDefinition
metadata:
  name: databases.db.example.com
spec:
  group: db.example.com
  versions:
    - name: v1
      served: true
      storage: true
      schema:
        openAPIV3Schema:
          type: object
          properties:
            spec:
              type: object
              required: [engine, version, size]
              properties:
                engine:  { type: string, enum: [postgres, mysql] }
                version: { type: string }
                size:    { type: string, enum: [small, medium, large] }
  scope: Namespaced
  names:
    plural:   databases
    singular: database
    kind:     Database
---
# Custom Resource instance
apiVersion: db.example.com/v1
kind: Database
metadata: { name: user-db, namespace: production }
spec:
  engine:  postgres
  version: "16"
  size:    medium',
        ],
        'tips' => [
            'Build golden paths in your IDP — they should make the right way the easy way for your developers.',
            'Run GameDay chaos exercises on a schedule, not just after incidents — proactive resilience beats reactive fixes.',
            'Follow the CNCF landscape (landscape.cncf.io) to discover new cloud-native tools, but adopt conservatively.',
            'Read the Google SRE book (sre.google) — it is the foundational text for production reliability engineering.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
