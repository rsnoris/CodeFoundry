<?php
$page_title  = 'CI/CD Pipeline Setup – CodeFoundry Training';
$active_page = 'training';
$page_styles = <<<'PAGECSS'
:root {
  --navy:         #0e1828;
  --navy-2:       #121c2b;
  --navy-3:       #161f2f;
  --primary:      #18b3ff;
  --primary-hover:#009de0;
  --text:         #fff;
  --text-muted:   #92a3bb;
  --text-subtle:  #627193;
  --border-color: #1a2942;
  --button-radius:8px;
  --maxwidth:     1200px;
  --card-radius:  12px;
  --header-height:68px;
}
html, body { background:var(--navy-2); color:var(--text); font-family:'Inter',sans-serif; margin:0; padding:0; }
body { min-height:100vh; }
a { color:inherit; text-decoration:none; }
.breadcrumb { max-width:var(--maxwidth); margin:0 auto; padding:20px 40px 0; display:flex; align-items:center; gap:8px; font-size:.85rem; color:var(--text-muted); flex-wrap:wrap; }
.breadcrumb a { color:var(--text-muted); transition:color .2s; }
.breadcrumb a:hover { color:var(--primary); }
.breadcrumb-sep { color:var(--text-subtle); }
.breadcrumb-current { color:var(--text); font-weight:600; }
.guide-hero { background:linear-gradient(135deg,var(--navy) 0%,#0d1e36 60%,#0a1826 100%); border-bottom:1px solid var(--border-color); padding:60px 40px 56px; position:relative; overflow:hidden; }
.guide-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 900px 500px at 50% -80px,rgba(24,179,255,.12) 0%,transparent 70%); pointer-events:none; }
.guide-hero-inner { max-width:var(--maxwidth); margin:0 auto; position:relative; }
.guide-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(24,179,255,.1); border:1px solid rgba(24,179,255,.25); color:var(--primary); font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; padding:5px 14px; border-radius:100px; margin-bottom:20px; }
.guide-hero h1 { font-size:clamp(1.8rem,4vw,2.8rem); font-weight:900; line-height:1.15; margin:0 0 16px; background:linear-gradient(135deg,#fff 40%,var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.guide-hero-desc { max-width:680px; color:var(--text-muted); font-size:1.05rem; line-height:1.7; margin:0 0 28px; }
.guide-meta { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
.guide-meta-item { display:flex; align-items:center; gap:6px; color:var(--text-muted); font-size:.85rem; }
.guide-meta-item iconify-icon { color:var(--primary); font-size:1rem; }
.topic-tags { display:flex; gap:8px; flex-wrap:wrap; margin-top:16px; }
.topic-tag { background:rgba(24,179,255,.08); border:1px solid rgba(24,179,255,.2); color:var(--primary); font-size:.75rem; font-weight:600; padding:4px 12px; border-radius:100px; }
.guide-layout { max-width:var(--maxwidth); margin:0 auto; padding:48px 40px; display:grid; grid-template-columns:1fr 280px; gap:48px; align-items:start; }
@media(max-width:900px){ .guide-layout{ grid-template-columns:1fr; padding:32px 20px; } }
.toc-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:24px; position:sticky; top:calc(var(--header-height) + 20px); }
.toc-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--text-muted); margin:0 0 16px; }
.toc-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:4px; }
.toc-list a { display:flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:6px 10px; border-radius:6px; transition:all .2s; }
.toc-list a:hover { color:var(--primary); background:rgba(24,179,255,.06); }
.toc-num { font-size:.7rem; font-weight:700; color:var(--primary); min-width:18px; }
.back-link { display:inline-flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px; margin-top:20px; width:100%; box-sizing:border-box; justify-content:center; transition:all .2s; }
.back-link:hover { color:var(--primary); border-color:rgba(24,179,255,.4); background:rgba(24,179,255,.05); }
.guide-content { min-width:0; }
.guide-section { margin-bottom:56px; }
.guide-section:last-child { margin-bottom:0; }
.section-header { display:flex; align-items:center; gap:14px; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border-color); }
.section-num { display:flex; align-items:center; justify-content:center; width:36px; height:36px; background:rgba(24,179,255,.12); border:1px solid rgba(24,179,255,.25); border-radius:8px; color:var(--primary); font-weight:800; font-size:.9rem; flex-shrink:0; }
.section-header h2 { font-size:1.35rem; font-weight:800; margin:0; color:var(--text); }
.guide-section p { color:var(--text-muted); line-height:1.75; margin:0 0 16px; }
.guide-section ul, .guide-section ol { color:var(--text-muted); line-height:1.75; padding-left:24px; margin:0 0 16px; }
.guide-section li { margin-bottom:6px; }
.code-block { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; overflow:hidden; margin:20px 0; }
.code-block-header { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-bottom:1px solid var(--border-color); background:rgba(255,255,255,.02); }
.code-lang { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--primary); }
.code-filename { font-size:.78rem; color:var(--text-muted); font-family:'Fira Mono','Consolas',monospace; }
.code-block pre { margin:0; padding:20px; overflow-x:auto; }
.code-block code { font-family:'Fira Mono','Consolas','Courier New',monospace; font-size:.82rem; line-height:1.65; color:#c9d1d9; white-space:pre; }
.callout { display:flex; gap:14px; padding:18px 20px; border-radius:10px; margin:20px 0; }
.callout-tip  { background:rgba(24,179,255,.07); border:1px solid rgba(24,179,255,.2); }
.callout-warn { background:rgba(255,179,28,.07); border:1px solid rgba(255,179,28,.2); }
.callout-info { background:rgba(139,92,246,.07); border:1px solid rgba(139,92,246,.2); }
.callout-icon { font-size:1.3rem; flex-shrink:0; margin-top:1px; }
.callout-tip  .callout-icon { color:#18b3ff; }
.callout-warn .callout-icon { color:#ffb31c; }
.callout-info .callout-icon { color:#8b5cf6; }
.callout-body { flex:1; }
.callout-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin:0 0 6px; }
.callout-tip  .callout-title { color:#18b3ff; }
.callout-warn .callout-title { color:#ffb31c; }
.callout-info .callout-title { color:#8b5cf6; }
.callout-body p { color:var(--text-muted); font-size:.88rem; line-height:1.65; margin:0; }
.callout-body ul { color:var(--text-muted); font-size:.88rem; line-height:1.65; margin:8px 0 0; padding-left:20px; }
.pipeline-stages { display:flex; gap:0; margin:24px 0; overflow-x:auto; padding-bottom:8px; }
.pipeline-stage { background:var(--navy-3); border:1px solid var(--border-color); border-right:none; padding:16px 20px; min-width:130px; text-align:center; position:relative; }
.pipeline-stage:first-child { border-radius:10px 0 0 10px; }
.pipeline-stage:last-child { border-right:1px solid var(--border-color); border-radius:0 10px 10px 0; }
.pipeline-stage.active { background:rgba(24,179,255,.08); border-color:rgba(24,179,255,.3); }
.pipeline-stage-icon { font-size:1.4rem; color:var(--primary); display:block; margin-bottom:6px; }
.pipeline-stage-name { font-size:.75rem; font-weight:700; color:var(--text); }
.pipeline-stage-time { font-size:.68rem; color:var(--text-muted); margin-top:4px; }
.related-section { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:32px; margin-top:48px; }
.related-title { font-size:1.1rem; font-weight:800; margin:0 0 20px; display:flex; align-items:center; gap:10px; }
.related-title iconify-icon { color:var(--primary); }
.related-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:12px; }
.related-card { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; padding:16px; transition:border-color .2s,transform .2s; }
.related-card:hover { border-color:rgba(24,179,255,.4); transform:translateY(-2px); }
.related-card-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--primary); margin-bottom:6px; }
.related-card-title { font-size:.9rem; font-weight:600; color:var(--text); }
PAGECSS;
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="breadcrumb">
  <a href="/Training/">Training</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <a href="/Training/Guides/">Implementation Guides</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <span class="breadcrumb-current">CI/CD Pipeline Setup</span>
</div>

<section class="guide-hero">
  <div class="guide-hero-inner">
    <div class="guide-badge"><iconify-icon icon="lucide:git-merge"></iconify-icon> Implementation Guide</div>
    <h1>CI/CD Pipeline Setup</h1>
    <p class="guide-hero-desc">Build a robust, fully automated pipeline from code commit to production deployment — covering testing, code quality gates, Docker builds, multi-environment promotion, and rollback strategies.</p>
    <div class="guide-meta">
      <div class="guide-meta-item"><iconify-icon icon="lucide:clock"></iconify-icon> 35 min read</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Intermediate</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:calendar"></iconify-icon> Updated 2025</div>
    </div>
    <div class="topic-tags">
      <span class="topic-tag">CI/CD</span>
      <span class="topic-tag">GitHub Actions</span>
      <span class="topic-tag">Testing</span>
      <span class="topic-tag">Automation</span>
    </div>
  </div>
</section>

<div class="guide-layout">
  <main class="guide-content">

    <!-- Section 1 -->
    <div class="guide-section" id="s1">
      <div class="section-header">
        <div class="section-num">1</div>
        <h2>CI/CD Fundamentals</h2>
      </div>
      <p>Continuous Integration, Delivery, and Deployment are distinct but related practices that together automate the path from developer commit to running software:</p>
      <ul>
        <li><strong>Continuous Integration (CI)</strong>: Every commit triggers an automated build and test run. Fast feedback on broken code before it reaches main.</li>
        <li><strong>Continuous Delivery (CD)</strong>: The build artefact is automatically deployed to a staging environment and is always in a releasable state. Deployment to production is a one-click operation.</li>
        <li><strong>Continuous Deployment</strong>: Every passing commit automatically deploys to production with no human approval. Requires high test coverage and robust monitoring.</li>
      </ul>
      <div class="pipeline-stages">
        <div class="pipeline-stage active">
          <iconify-icon class="pipeline-stage-icon" icon="lucide:git-commit"></iconify-icon>
          <div class="pipeline-stage-name">Commit</div>
          <div class="pipeline-stage-time">0s</div>
        </div>
        <div class="pipeline-stage active">
          <iconify-icon class="pipeline-stage-icon" icon="lucide:flask-conical"></iconify-icon>
          <div class="pipeline-stage-name">Test</div>
          <div class="pipeline-stage-time">~2 min</div>
        </div>
        <div class="pipeline-stage active">
          <iconify-icon class="pipeline-stage-icon" icon="lucide:scan-line"></iconify-icon>
          <div class="pipeline-stage-name">Quality</div>
          <div class="pipeline-stage-time">~1 min</div>
        </div>
        <div class="pipeline-stage active">
          <iconify-icon class="pipeline-stage-icon" icon="lucide:package"></iconify-icon>
          <div class="pipeline-stage-name">Build</div>
          <div class="pipeline-stage-time">~3 min</div>
        </div>
        <div class="pipeline-stage active">
          <iconify-icon class="pipeline-stage-icon" icon="lucide:rocket"></iconify-icon>
          <div class="pipeline-stage-name">Deploy</div>
          <div class="pipeline-stage-time">~2 min</div>
        </div>
      </div>
      <div class="callout callout-info">
        <div class="callout-icon"><iconify-icon icon="lucide:info"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">DORA Metrics</div>
          <p>The four DORA metrics measure CI/CD effectiveness: <strong>Deployment Frequency</strong>, <strong>Lead Time for Changes</strong>, <strong>Change Failure Rate</strong>, and <strong>Time to Restore Service</strong>. Elite performers deploy multiple times per day with less than 15-minute lead times.</p>
        </div>
      </div>
    </div>

    <!-- Section 2 -->
    <div class="guide-section" id="s2">
      <div class="section-header">
        <div class="section-num">2</div>
        <h2>GitHub Actions Setup</h2>
      </div>
      <p>GitHub Actions uses YAML workflow files in <code>.github/workflows/</code>. A workflow is triggered by events (push, pull_request, schedule) and runs one or more jobs on GitHub-hosted or self-hosted runners.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">.github/workflows/ci.yml</span></div>
        <pre><code>name: CI

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

# Cancel in-progress runs on new commits to same PR
concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

env:
  NODE_VERSION: '20'
  REGISTRY: ghcr.io
  IMAGE_NAME: ${{ github.repository }}

jobs:
  test:
    name: Test
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:16-alpine
        env:
          POSTGRES_DB: testdb
          POSTGRES_PASSWORD: testpass
        ports: ['5432:5432']
        options: >-
          --health-cmd pg_isready
          --health-interval 10s
          --health-timeout 5s
          --health-retries 5
    steps:
      - uses: actions/checkout@v4

      - name: Setup Node.js
        uses: actions/setup-node@v4
        with:
          node-version: ${{ env.NODE_VERSION }}
          cache: npm

      - name: Install dependencies
        run: npm ci

      - name: Run tests
        run: npm test -- --coverage
        env:
          DATABASE_URL: postgresql://postgres:testpass@localhost:5432/testdb

      - name: Upload coverage
        uses: codecov/codecov-action@v4
        with:
          token: ${{ secrets.CODECOV_TOKEN }}</code></pre>
      </div>
    </div>

    <!-- Section 3 -->
    <div class="guide-section" id="s3">
      <div class="section-header">
        <div class="section-num">3</div>
        <h2>Automated Testing Stage</h2>
      </div>
      <p>Structure your test suite in three layers — each with a distinct scope and speed. Faster tests run first; expensive integration tests run only on the main branch to keep PR feedback fast.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">.github/workflows/ci.yml (test matrix)</span></div>
        <pre><code>  test-matrix:
    name: Test (${{ matrix.type }})
    runs-on: ubuntu-latest
    strategy:
      matrix:
        type: [unit, integration, e2e]
      fail-fast: false   # run all types even if one fails
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with: { node-version: '20', cache: npm }
      - run: npm ci

      - name: Run unit tests
        if: matrix.type == 'unit'
        run: npm run test:unit -- --coverage --coverageThreshold='{"global":{"lines":80}}'

      - name: Run integration tests
        if: matrix.type == 'integration'
        run: npm run test:integration
        env:
          DATABASE_URL: ${{ secrets.TEST_DATABASE_URL }}

      - name: Run E2E tests
        if: matrix.type == 'e2e'
        run: |
          npx playwright install --with-deps chromium
          npm run test:e2e
      
      - name: Upload test results
        if: always()
        uses: actions/upload-artifact@v4
        with:
          name: test-results-${{ matrix.type }}
          path: test-results/</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Testing Best Practices</div>
          <ul>
            <li>Keep unit tests under 100ms each — use mocks for database and network calls.</li>
            <li>Enforce a minimum line coverage threshold in CI to prevent regressions.</li>
            <li>Use <code>--bail</code> in unit tests to stop on first failure for faster feedback in development.</li>
            <li>Parallelise E2E tests across shards: <code>--shard=1/4</code> with Playwright.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 4 -->
    <div class="guide-section" id="s4">
      <div class="section-header">
        <div class="section-num">4</div>
        <h2>Code Quality Checks</h2>
      </div>
      <p>Quality gates run in parallel with tests. They enforce code style, catch bugs statically, and flag security vulnerabilities — all before a merge.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">.github/workflows/quality.yml</span></div>
        <pre><code>name: Code Quality

on: [push, pull_request]

jobs:
  lint:
    name: Lint & Format
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with: { node-version: '20', cache: npm }
      - run: npm ci
      - name: ESLint
        run: npm run lint -- --max-warnings=0
      - name: Prettier check
        run: npm run format:check

  security:
    name: Security Scan
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Dependency audit
        run: npm audit --audit-level=high
      - name: SAST with CodeQL
        uses: github/codeql-action/analyze@v3
        with:
          languages: javascript

  sonarqube:
    name: SonarQube Analysis
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    steps:
      - uses: actions/checkout@v4
        with: { fetch-depth: 0 }
      - uses: SonarSource/sonarcloud-github-action@master
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
          SONAR_TOKEN: ${{ secrets.SONAR_TOKEN }}</code></pre>
      </div>
    </div>

    <!-- Section 5 -->
    <div class="guide-section" id="s5">
      <div class="section-header">
        <div class="section-num">5</div>
        <h2>Build and Package</h2>
      </div>
      <p>Build Docker images only after all tests and quality checks pass. Use GitHub Container Registry (GHCR) for storage and tag images with both the Git SHA (immutable) and branch name (floating).</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">.github/workflows/build.yml</span></div>
        <pre><code>  build-image:
    name: Build & Push Docker Image
    runs-on: ubuntu-latest
    needs: [test-matrix, lint, security]   # only build if all checks pass
    permissions:
      contents: read
      packages: write

    steps:
      - uses: actions/checkout@v4

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v3

      - name: Log in to GHCR
        uses: docker/login-action@v3
        with:
          registry: ghcr.io
          username: ${{ github.actor }}
          password: ${{ secrets.GITHUB_TOKEN }}

      - name: Extract metadata
        id: meta
        uses: docker/metadata-action@v5
        with:
          images: ghcr.io/${{ github.repository }}
          tags: |
            type=sha,format=long
            type=ref,event=branch
            type=semver,pattern={{version}}

      - name: Build and push
        uses: docker/build-push-action@v5
        with:
          context: .
          push: true
          tags: ${{ steps.meta.outputs.tags }}
          labels: ${{ steps.meta.outputs.labels }}
          cache-from: type=gha
          cache-to: type=gha,mode=max</code></pre>
      </div>
    </div>

    <!-- Section 6 -->
    <div class="guide-section" id="s6">
      <div class="section-header">
        <div class="section-num">6</div>
        <h2>Multi-Environment Deployment</h2>
      </div>
      <p>Promote artefacts through environments: develop branch → staging, main branch with manual approval → production. Use GitHub Environments with protection rules for approvals and secrets scoping.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">.github/workflows/deploy.yml</span></div>
        <pre><code>name: Deploy

on:
  workflow_run:
    workflows: [CI]
    types: [completed]
    branches: [main, develop]

jobs:
  deploy-staging:
    name: Deploy to Staging
    if: github.ref == 'refs/heads/develop' && github.event.workflow_run.conclusion == 'success'
    runs-on: ubuntu-latest
    environment:
      name: staging
      url: https://staging.myapp.com
    steps:
      - name: Deploy to Staging
        uses: appleboy/ssh-action@v1
        with:
          host: ${{ secrets.STAGING_HOST }}
          username: deploy
          key: ${{ secrets.STAGING_SSH_KEY }}
          script: |
            export IMAGE_TAG=${{ github.sha }}
            cd /opt/app
            docker compose pull app
            docker compose up -d app
            docker system prune -f

  deploy-production:
    name: Deploy to Production
    if: github.ref == 'refs/heads/main' && github.event.workflow_run.conclusion == 'success'
    runs-on: ubuntu-latest
    environment:
      name: production
      url: https://myapp.com
    # GitHub Environment protection rules require manual approval
    steps:
      - name: Deploy to Production
        uses: appleboy/ssh-action@v1
        with:
          host: ${{ secrets.PROD_HOST }}
          username: deploy
          key: ${{ secrets.PROD_SSH_KEY }}
          script: |
            export IMAGE_TAG=${{ github.sha }}
            cd /opt/app
            docker compose pull app
            docker compose up -d --no-deps app
            sleep 30
            # Smoke test
            curl -f https://myapp.com/health || (docker compose rollback && exit 1)</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Secrets Management</div>
          <ul>
            <li>Store secrets in GitHub Environment secrets, not repository secrets, so they're scoped to specific environments.</li>
            <li>Rotate secrets regularly — use <code>gh secret set</code> in your rotation script to automate updates.</li>
            <li>Never print secrets in logs — GitHub Actions auto-masks known secrets, but be careful with encoded variants.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 7 -->
    <div class="guide-section" id="s7">
      <div class="section-header">
        <div class="section-num">7</div>
        <h2>Rollback Strategies</h2>
      </div>
      <p>Even with strong automated testing, bad deployments happen. Design rollback into your pipeline from day one — not as an afterthought.</p>
      <p><strong>Blue/Green Deployment</strong>: Run two identical production environments (blue = current, green = new). Switch traffic from blue to green atomically. Rollback is instant — switch back.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">Blue/Green with AWS ECS</span></div>
        <pre><code>  deploy-bluegreen:
    steps:
      - name: Deploy new task definition (green)
        run: |
          aws ecs register-task-definition \
            --family my-app \
            --container-definitions "[{
              \"name\": \"app\",
              \"image\": \"ghcr.io/myorg/app:${{ github.sha }}\",
              \"portMappings\": [{\"containerPort\": 8080}]
            }]"

      - name: Update service with CodeDeploy (blue/green)
        run: |
          aws ecs update-service \
            --cluster production \
            --service my-app \
            --task-definition my-app \
            --deployment-configuration \
              "deploymentCircuitBreaker={enable=true,rollback=true}"</code></pre>
      </div>
      <p><strong>Canary Releases</strong>: Route 5% of traffic to the new version while 95% goes to stable. Monitor error rates and latency. Gradually increase traffic if metrics stay healthy; auto-rollback if they don't.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">Canary with NGINX split traffic</span></div>
        <pre><code>upstream stable  { server app-stable:8080; }
upstream canary  { server app-canary:8080; }

split_clients "${remote_addr}${http_user_agent}" $backend {
  5%    canary;
  *     stable;
}

server {
  location / {
    proxy_pass http://$backend;
  }
}</code></pre>
      </div>
    </div>

    <!-- Section 8 -->
    <div class="guide-section" id="s8">
      <div class="section-header">
        <div class="section-num">8</div>
        <h2>Monitoring Pipeline Performance</h2>
      </div>
      <p>Track pipeline performance to identify bottlenecks, flaky tests, and DORA metric trends over time. GitHub Actions provides built-in job duration data; export it to your metrics platform.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">Failure notification to Slack</span></div>
        <pre><code>      - name: Notify Slack on failure
        if: failure()
        uses: slackapi/slack-github-action@v1
        with:
          channel-id: ${{ secrets.SLACK_CHANNEL_ID }}
          payload: |
            {
              "text": "❌ Pipeline failed on `${{ github.ref_name }}`",
              "attachments": [{
                "color": "danger",
                "fields": [
                  {"title": "Repository", "value": "${{ github.repository }}", "short": true},
                  {"title": "Commit",     "value": "${{ github.sha }}", "short": true},
                  {"title": "Run URL",    "value": "${{ github.server_url }}/${{ github.repository }}/actions/runs/${{ github.run_id }}"}
                ]
              }]
            }
        env:
          SLACK_BOT_TOKEN: ${{ secrets.SLACK_BOT_TOKEN }}</code></pre>
      </div>
      <div class="callout callout-info">
        <div class="callout-icon"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">DORA Metric Targets (Elite Performers)</div>
          <ul>
            <li><strong>Deployment Frequency</strong>: Multiple times per day</li>
            <li><strong>Lead Time for Changes</strong>: Less than one hour</li>
            <li><strong>Change Failure Rate</strong>: 0–15%</li>
            <li><strong>Time to Restore Service</strong>: Less than one hour</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="related-section">
      <div class="related-title"><iconify-icon icon="lucide:link"></iconify-icon> Related Resources</div>
      <div class="related-grid">
        <a href="/Training/Guides/fullstack-web-app.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Full-Stack Web App</div>
        </a>
        <a href="/Training/Guides/microservices-architecture.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Microservices Architecture</div>
        </a>
        <a href="/Training/Tutorials/docker.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">Docker Fundamentals</div>
        </a>
        <a href="/Training/Guides/cloud-native-development.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Cloud-Native Development</div>
        </a>
      </div>
    </div>

  </main>

  <aside>
    <div class="toc-card">
      <div class="toc-title">Contents</div>
      <ul class="toc-list">
        <li><a href="#s1"><span class="toc-num">1</span> CI/CD Fundamentals</a></li>
        <li><a href="#s2"><span class="toc-num">2</span> GitHub Actions Setup</a></li>
        <li><a href="#s3"><span class="toc-num">3</span> Automated Testing</a></li>
        <li><a href="#s4"><span class="toc-num">4</span> Quality Checks</a></li>
        <li><a href="#s5"><span class="toc-num">5</span> Build & Package</a></li>
        <li><a href="#s6"><span class="toc-num">6</span> Multi-Env Deploy</a></li>
        <li><a href="#s7"><span class="toc-num">7</span> Rollback Strategies</a></li>
        <li><a href="#s8"><span class="toc-num">8</span> Pipeline Monitoring</a></li>
      </ul>
      <a href="/Training/Guides/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Guides</a>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
