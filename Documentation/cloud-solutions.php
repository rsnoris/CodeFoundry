<?php
$page_title  = 'Cloud Solutions - CodeFoundry Documentation';
$active_page = '';
$page_styles = <<<'PAGECSS'
:root {
  --navy: #0e1828;
  --navy-2: #121c2b;
  --navy-3: #161f2f;
  --primary: #18b3ff;
  --primary-hover: #009de0;
  --text: #fff;
  --text-muted: #92a3bb;
  --text-subtle: #627193;
  --border-color: #1a2942;
  --button-outline: #ffffff22;
  --button-radius: 8px;
  --maxwidth: 1200px;
  --card-radius: 12px;
  --header-height: 68px;
  --mobile-menu-bg: #0e1828f9;
  --code-bg: #0b1220;
}
html, body {
  background: var(--navy-2);
  color: var(--text);
  font-family: 'Inter', sans-serif;
  margin: 0;
  padding: 0;
}
body { min-height: 100vh; }
a { color: inherit; text-decoration: none; }

main {
  max-width: var(--maxwidth);
  margin: 0 auto;
  padding: 60px 40px;
}
@media (max-width: 768px) { main { padding: 40px 20px; } }

.breadcrumb {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
  color: var(--text-muted);
  margin-bottom: 40px;
  flex-wrap: wrap;
}
.breadcrumb a { color: var(--primary); font-weight: 600; }
.breadcrumb a:hover { color: var(--primary-hover); }
.breadcrumb-sep { color: var(--text-subtle); }

.back-link {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: var(--primary);
  font-weight: 700;
  font-size: 14px;
  margin-bottom: 32px;
}
.back-link:hover { color: var(--primary-hover); }

.page-header { margin-bottom: 60px; }
.page-badge {
  display: inline-block;
  background: rgba(24, 179, 255, 0.15);
  color: var(--primary);
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: 0.5px;
  text-transform: uppercase;
  margin-bottom: 16px;
}
.page-title {
  font-size: 2.8rem;
  font-weight: 900;
  margin: 0 0 16px 0;
  letter-spacing: -1.5px;
  line-height: 1.1;
}
.page-desc {
  font-size: 1.15rem;
  color: var(--text-muted);
  max-width: 680px;
  line-height: 1.6;
}
@media (max-width: 768px) {
  .page-title { font-size: 2rem; }
  .page-desc  { font-size: 1rem; }
}

.section { margin-bottom: 64px; }
.section-title {
  font-size: 1.6rem;
  font-weight: 800;
  margin: 0 0 8px 0;
  letter-spacing: -0.5px;
  display: flex;
  align-items: center;
  gap: 12px;
}
.section-title iconify-icon { color: var(--primary); font-size: 1.4rem; }
.section-subtitle {
  color: var(--text-muted);
  font-size: 1rem;
  margin: 0 0 28px 0;
  line-height: 1.6;
}
.section-divider {
  border: none;
  border-top: 1px solid var(--border-color);
  margin: 0 0 28px 0;
}

/* Architecture pattern cards */
.arch-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 24px;
}
@media (max-width: 768px) { .arch-grid { grid-template-columns: 1fr; } }

.arch-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 30px;
  transition: border-color 0.2s, transform 0.2s;
}
.arch-card:hover {
  border-color: var(--primary);
  transform: translateY(-3px);
}
.arch-icon {
  font-size: 2.4rem;
  color: var(--primary);
  margin-bottom: 16px;
  display: block;
}
.arch-card h3 {
  font-size: 1.15rem;
  font-weight: 800;
  margin: 0 0 10px 0;
}
.arch-card p {
  color: var(--text-muted);
  font-size: 0.9rem;
  line-height: 1.6;
  margin: 0 0 16px 0;
}
.arch-tags { display: flex; flex-wrap: wrap; gap: 6px; }
.arch-tag {
  background: rgba(24,179,255,0.1);
  color: var(--primary);
  border-radius: 6px;
  padding: 3px 10px;
  font-size: 12px;
  font-weight: 700;
}

/* Provider tabs */
.tab-bar {
  display: flex;
  gap: 4px;
  margin-bottom: 0;
  border-bottom: 1px solid var(--border-color);
  padding-bottom: 0;
}
.tab-btn {
  background: none;
  border: none;
  border-bottom: 2px solid transparent;
  color: var(--text-muted);
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  padding: 10px 18px;
  cursor: pointer;
  margin-bottom: -1px;
  transition: color 0.15s, border-color 0.15s;
}
.tab-btn:hover { color: var(--text); }
.tab-btn.active {
  color: var(--primary);
  border-bottom-color: var(--primary);
}
.tab-panel { display: none; padding-top: 24px; }
.tab-panel.active { display: block; }

/* Code blocks */
pre, code {
  font-family: 'JetBrains Mono', 'Fira Code', monospace;
}
.code-block {
  background: var(--code-bg);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  overflow: hidden;
  margin: 0 0 20px 0;
}
.code-header {
  background: #0e1520;
  border-bottom: 1px solid var(--border-color);
  padding: 10px 18px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 12px;
  color: var(--text-muted);
  font-weight: 600;
  letter-spacing: 0.3px;
}
.code-lang { display: flex; align-items: center; gap: 6px; color: var(--primary); }
.code-block pre {
  margin: 0;
  padding: 20px 22px;
  overflow-x: auto;
  font-size: 13px;
  line-height: 1.7;
  color: #c9d8f0;
}
.code-block pre .kw  { color: #18b3ff; }
.code-block pre .str { color: #7ad9a8; }
.code-block pre .cm  { color: #627193; font-style: italic; }
.code-block pre .fn  { color: #f4b860; }
.code-block pre .num { color: #ff9e6e; }

/* IaC steps */
.iac-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}
@media (max-width: 860px) { .iac-grid { grid-template-columns: 1fr; } }

.iac-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 28px;
}
.iac-card h3 {
  font-size: 1.1rem;
  font-weight: 800;
  margin: 0 0 8px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}
.iac-card h3 iconify-icon { color: var(--primary); }
.iac-card p {
  color: var(--text-muted);
  font-size: 0.9rem;
  line-height: 1.6;
  margin: 0 0 16px 0;
}

/* Best practices */
.practices-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .practices-grid { grid-template-columns: 1fr; } }

.practice-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 26px;
  transition: border-color 0.2s;
}
.practice-card:hover { border-color: var(--primary); }
.practice-card h3 {
  font-size: 1rem;
  font-weight: 800;
  margin: 0 0 10px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}
.practice-card h3 iconify-icon { color: var(--primary); font-size: 1.2rem; }
.practice-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
}
.practice-list li {
  display: flex;
  align-items: flex-start;
  gap: 8px;
  color: var(--text-muted);
  font-size: 0.88rem;
  line-height: 1.5;
}
.practice-list li::before {
  content: '▸';
  color: var(--primary);
  flex-shrink: 0;
  margin-top: 1px;
}

/* Next steps */
.next-steps-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
}
.next-step-link {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 22px 24px;
  display: flex;
  align-items: center;
  gap: 14px;
  font-weight: 700;
  font-size: 0.95rem;
  transition: border-color 0.2s, background 0.2s;
}
.next-step-link:hover {
  border-color: var(--primary);
  background: rgba(24,179,255,0.07);
  color: var(--primary);
}
.next-step-link iconify-icon { font-size: 1.4rem; color: var(--primary); flex-shrink: 0; }

.note-box {
  background: rgba(24,179,255,0.08);
  border: 1px solid rgba(24,179,255,0.25);
  border-radius: 10px;
  padding: 16px 20px;
  font-size: 0.9rem;
  color: var(--text-muted);
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 24px;
}
.note-box iconify-icon { color: var(--primary); font-size: 1.2rem; flex-shrink: 0; margin-top: 1px; }
PAGECSS;
$page_scripts = <<<'PAGEJS'
const menuBtn = document.getElementById('mobileMenuBtn');
const mobileNav = document.getElementById('mobileNav');
const closeBtn = document.getElementById('closeMobileNav');
function closeMobileNav() { mobileNav.classList.remove('open'); }
if (menuBtn) menuBtn.onclick = () => mobileNav.classList.add('open');
if (closeBtn) closeBtn.onclick = closeMobileNav;
if (mobileNav) mobileNav.onclick = (e) => { if (e.target === mobileNav) closeMobileNav(); };

document.querySelectorAll('.tab-bar').forEach(function(bar) {
  bar.querySelectorAll('.tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const group = btn.closest('.tab-group');
      group.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      group.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
      btn.classList.add('active');
      const panel = group.querySelector('#' + btn.dataset.tab);
      if (panel) panel.classList.add('active');
    });
  });
});
PAGEJS;
require_once __DIR__ . '/../includes/header.php';
?>

<main>
  <nav class="breadcrumb" aria-label="Breadcrumb">
    <a href="/Documentation/">Documentation Hub</a>
    <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
    <span>Cloud Solutions</span>
  </nav>

  <a href="/Documentation/" class="back-link">
    <iconify-icon icon="lucide:arrow-left"></iconify-icon>
    Back to Documentation
  </a>

  <div class="page-header">
    <span class="page-badge">Infrastructure Guide</span>
    <h1 class="page-title">Cloud Solutions</h1>
    <p class="page-desc">
      Architecture patterns, multi-cloud deployment guides, Infrastructure as Code templates, and best practices for building scalable, resilient cloud systems with CodeFoundry.
    </p>
  </div>

  <!-- Architecture Patterns -->
  <section class="section" id="architecture-patterns">
    <h2 class="section-title">
      <iconify-icon icon="lucide:network"></iconify-icon>
      Architecture Patterns
    </h2>
    <p class="section-subtitle">Choose the architecture pattern that best fits your workload's scale, team size, and operational requirements.</p>
    <hr class="section-divider">
    <div class="arch-grid">
      <div class="arch-card">
        <iconify-icon icon="lucide:boxes" class="arch-icon"></iconify-icon>
        <h3>Microservices</h3>
        <p>Decompose your application into small, independently deployable services. Each service owns its data, exposes an API, and can be scaled, deployed, and updated independently of others.</p>
        <div class="arch-tags">
          <span class="arch-tag">Docker</span>
          <span class="arch-tag">Kubernetes</span>
          <span class="arch-tag">Service Mesh</span>
          <span class="arch-tag">API Gateway</span>
        </div>
      </div>
      <div class="arch-card">
        <iconify-icon icon="lucide:zap" class="arch-icon"></iconify-icon>
        <h3>Serverless</h3>
        <p>Run functions on demand without managing servers. Pay only for compute consumed and scale automatically to zero. Ideal for event-driven workloads, API backends, and scheduled tasks.</p>
        <div class="arch-tags">
          <span class="arch-tag">AWS Lambda</span>
          <span class="arch-tag">Azure Functions</span>
          <span class="arch-tag">Cloud Functions</span>
          <span class="arch-tag">Faas</span>
        </div>
      </div>
      <div class="arch-card">
        <iconify-icon icon="lucide:git-branch" class="arch-icon"></iconify-icon>
        <h3>Event-Driven</h3>
        <p>Decouple services using events and message queues. Producers emit events without knowledge of consumers. Enables loose coupling, high resilience, and natural horizontal scaling.</p>
        <div class="arch-tags">
          <span class="arch-tag">Kafka</span>
          <span class="arch-tag">SQS / SNS</span>
          <span class="arch-tag">Pub/Sub</span>
          <span class="arch-tag">Event Bus</span>
        </div>
      </div>
      <div class="arch-card">
        <iconify-icon icon="lucide:layers-2" class="arch-icon"></iconify-icon>
        <h3>Monolithic Modular</h3>
        <p>A single deployable unit structured around clear internal module boundaries. Lower operational complexity than microservices while still maintaining separation of concerns. A great starting point for most teams.</p>
        <div class="arch-tags">
          <span class="arch-tag">Modular Design</span>
          <span class="arch-tag">Single Deploy</span>
          <span class="arch-tag">Shared DB</span>
        </div>
      </div>
      <div class="arch-card">
        <iconify-icon icon="lucide:layout-template" class="arch-icon"></iconify-icon>
        <h3>Jamstack / Edge</h3>
        <p>Pre-render content at build time and serve it from a global CDN. Dynamic functionality is powered by serverless APIs. Results in extremely fast load times and minimal infrastructure overhead.</p>
        <div class="arch-tags">
          <span class="arch-tag">CDN</span>
          <span class="arch-tag">Static Site</span>
          <span class="arch-tag">Edge Functions</span>
          <span class="arch-tag">Headless CMS</span>
        </div>
      </div>
      <div class="arch-card">
        <iconify-icon icon="lucide:database" class="arch-icon"></iconify-icon>
        <h3>Data-Intensive (Lambda)</h3>
        <p>Handle large-scale data processing using the Lambda architecture: a batch layer for accuracy, a speed layer for low-latency processing, and a serving layer for query results.</p>
        <div class="arch-tags">
          <span class="arch-tag">Spark</span>
          <span class="arch-tag">Flink</span>
          <span class="arch-tag">Data Lake</span>
          <span class="arch-tag">Streaming</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Deployment Guides -->
  <section class="section" id="deployment-guides">
    <h2 class="section-title">
      <iconify-icon icon="lucide:rocket"></iconify-icon>
      Multi-Cloud Deployment Guides
    </h2>
    <p class="section-subtitle">Step-by-step deployment instructions for AWS, Azure, and Google Cloud Platform. CodeFoundry's control plane abstracts provider-specific APIs so you can switch or span clouds with minimal changes.</p>
    <hr class="section-divider">

    <div class="tab-group">
      <div class="tab-bar">
        <button class="tab-btn active" data-tab="tab-aws">AWS</button>
        <button class="tab-btn" data-tab="tab-azure">Azure</button>
        <button class="tab-btn" data-tab="tab-gcp">GCP</button>
      </div>

      <div class="tab-panel active" id="tab-aws">
        <div class="note-box">
          <iconify-icon icon="lucide:info"></iconify-icon>
          <span>Requires an AWS account with appropriate IAM permissions. CodeFoundry needs <code>ec2:*</code>, <code>ecs:*</code>, <code>rds:*</code>, <code>s3:*</code>, <code>cloudformation:*</code>, and <code>iam:PassRole</code>.</span>
        </div>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>AWS – Configure credentials &amp; deploy</span></div>
          <pre><span class="cm"># 1. Configure AWS credentials</span>
aws configure
<span class="cm"># AWS Access Key ID: [your-key]</span>
<span class="cm"># AWS Secret Access Key: [your-secret]</span>
<span class="cm"># Default region: us-east-1</span>

<span class="cm"># 2. Link your AWS account in CodeFoundry</span>
cf cloud connect aws \
  --account-id 123456789012 \
  --region us-east-1 \
  --role arn:aws:iam::123456789012:role/CodeFoundryRole

<span class="cm"># 3. Deploy a containerised service to ECS Fargate</span>
cf deploy \
  --provider aws \
  --service my-api \
  --image ghcr.io/my-org/my-api:latest \
  --cpu 512 --memory 1024 \
  --port 8080</pre>
        </div>
      </div>

      <div class="tab-panel" id="tab-azure">
        <div class="note-box">
          <iconify-icon icon="lucide:info"></iconify-icon>
          <span>Requires an Azure subscription and a service principal with <strong>Contributor</strong> role on the target resource group.</span>
        </div>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>Azure – Configure credentials &amp; deploy</span></div>
          <pre><span class="cm"># 1. Create a service principal</span>
az ad sp create-for-rbac \
  --name CodeFoundrySP \
  --role Contributor \
  --scopes /subscriptions/&lt;SUBSCRIPTION_ID&gt;/resourceGroups/my-rg

<span class="cm"># 2. Link your Azure account in CodeFoundry</span>
cf cloud connect azure \
  --subscription-id &lt;SUBSCRIPTION_ID&gt; \
  --tenant-id &lt;TENANT_ID&gt; \
  --client-id &lt;CLIENT_ID&gt; \
  --client-secret &lt;CLIENT_SECRET&gt;

<span class="cm"># 3. Deploy to Azure Container Apps</span>
cf deploy \
  --provider azure \
  --resource-group my-rg \
  --service my-api \
  --image ghcr.io/my-org/my-api:latest \
  --port 8080</pre>
        </div>
      </div>

      <div class="tab-panel" id="tab-gcp">
        <div class="note-box">
          <iconify-icon icon="lucide:info"></iconify-icon>
          <span>Requires a GCP project with the Cloud Run, Cloud Build, and Artifact Registry APIs enabled. The service account needs <strong>roles/run.admin</strong> and <strong>roles/iam.serviceAccountUser</strong>.</span>
        </div>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>GCP – Configure credentials &amp; deploy</span></div>
          <pre><span class="cm"># 1. Authenticate with GCP</span>
gcloud auth application-default login
gcloud config set project my-gcp-project

<span class="cm"># 2. Create a service account and key</span>
gcloud iam service-accounts create codefoundry-sa \
  --display-name "CodeFoundry Service Account"
gcloud projects add-iam-policy-binding my-gcp-project \
  --member serviceAccount:codefoundry-sa@my-gcp-project.iam.gserviceaccount.com \
  --role roles/run.admin

<span class="cm"># 3. Link and deploy via CodeFoundry</span>
cf cloud connect gcp \
  --project my-gcp-project \
  --service-account-key /path/to/key.json

cf deploy \
  --provider gcp \
  --region us-central1 \
  --service my-api \
  --image gcr.io/my-gcp-project/my-api:latest</pre>
        </div>
      </div>
    </div>
  </section>

  <!-- IaC -->
  <section class="section" id="infrastructure-as-code">
    <h2 class="section-title">
      <iconify-icon icon="lucide:file-code-2"></iconify-icon>
      Infrastructure as Code
    </h2>
    <p class="section-subtitle">Manage infrastructure declaratively using Terraform or CloudFormation. CodeFoundry provides ready-to-use modules and templates to accelerate provisioning.</p>
    <hr class="section-divider">
    <div class="iac-grid">
      <div class="iac-card">
        <h3><iconify-icon icon="simple-icons:terraform"></iconify-icon>Terraform Module</h3>
        <p>Use the official CodeFoundry Terraform module to provision a full ECS Fargate stack including VPC, ALB, ECR, and auto-scaling policies in one block.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:file-code-2"></iconify-icon>main.tf</span></div>
          <pre><span class="kw">module</span> <span class="str">"codefoundry_service"</span> {
  source  = <span class="str">"codefoundry/ecs-fargate/aws"</span>
  version = <span class="str">"~> 2.0"</span>

  service_name   = <span class="str">"my-api"</span>
  container_image = <span class="str">"ghcr.io/my-org/my-api:latest"</span>
  container_port  = <span class="num">8080</span>
  cpu             = <span class="num">512</span>
  memory          = <span class="num">1024</span>
  desired_count   = <span class="num">2</span>

  environment = {
    NODE_ENV    = <span class="str">"production"</span>
    DB_HOST     = <span class="kw">var</span>.db_host
  }
}</pre>
        </div>
      </div>
      <div class="iac-card">
        <h3><iconify-icon icon="simple-icons:amazonaws"></iconify-icon>CloudFormation Template</h3>
        <p>Deploy a serverless API backed by Lambda, API Gateway, and DynamoDB using the provided CloudFormation YAML template. Parameterise it to support multiple environments.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:file-code-2"></iconify-icon>serverless-api.yaml</span></div>
          <pre><span class="kw">AWSTemplateFormatVersion:</span> <span class="str">'2010-09-09'</span>
<span class="kw">Transform:</span> AWS::Serverless-2016-10-31
<span class="kw">Parameters:</span>
  <span class="kw">Stage:</span>
    <span class="kw">Type:</span> String
    <span class="kw">Default:</span> prod
<span class="kw">Resources:</span>
  <span class="kw">ApiFunction:</span>
    <span class="kw">Type:</span> AWS::Serverless::Function
    <span class="kw">Properties:</span>
      <span class="kw">Handler:</span> src/index.handler
      <span class="kw">Runtime:</span> nodejs20.x
      <span class="kw">MemorySize:</span> <span class="num">512</span>
      <span class="kw">Timeout:</span> <span class="num">30</span>
      <span class="kw">Events:</span>
        <span class="kw">Api:</span>
          <span class="kw">Type:</span> Api
          <span class="kw">Properties:</span>
            <span class="kw">Path:</span> <span class="str">/{proxy+}</span>
            <span class="kw">Method:</span> ANY</pre>
        </div>
      </div>
    </div>
  </section>

  <!-- Scaling & Monitoring -->
  <section class="section" id="scaling-monitoring">
    <h2 class="section-title">
      <iconify-icon icon="lucide:trending-up"></iconify-icon>
      Scaling &amp; Monitoring Best Practices
    </h2>
    <p class="section-subtitle">Building a cloud system that performs reliably under load requires thoughtful scaling strategies and comprehensive observability from day one.</p>
    <hr class="section-divider">
    <div class="practices-grid">
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:arrow-up-down"></iconify-icon>Auto Scaling</h3>
        <ul class="practice-list">
          <li>Use target-tracking scaling policies keyed on CPU and custom business metrics.</li>
          <li>Set minimum capacity to at least 2 instances across multiple Availability Zones.</li>
          <li>Configure scale-in cooldown periods to avoid premature downscaling during traffic spikes.</li>
          <li>Test scaling policies with synthetic load before going to production.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:activity"></iconify-icon>Observability</h3>
        <ul class="practice-list">
          <li>Emit structured JSON logs with trace IDs for correlation across services.</li>
          <li>Instrument code with OpenTelemetry for distributed tracing.</li>
          <li>Define SLOs for latency (p99 &lt; 500 ms), error rate (&lt; 0.1%), and availability (99.9%).</li>
          <li>Set up anomaly-detection alerts, not just static thresholds.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:database"></iconify-icon>Database Scaling</h3>
        <ul class="practice-list">
          <li>Use read replicas to offload analytical queries from the primary instance.</li>
          <li>Enable connection pooling (PgBouncer, RDS Proxy) to handle bursts.</li>
          <li>Shard by tenant or user ID when single-node vertical scaling is exhausted.</li>
          <li>Implement CQRS if read and write scaling requirements diverge significantly.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:shield"></iconify-icon>Resilience &amp; HA</h3>
        <ul class="practice-list">
          <li>Deploy services across at least 2 AZs with health-check-based failover.</li>
          <li>Implement circuit breakers to prevent cascading failures between services.</li>
          <li>Define and test RTO and RPO targets for all critical data stores.</li>
          <li>Run quarterly chaos engineering exercises to validate failure modes.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:dollar-sign"></iconify-icon>Cost Optimisation</h3>
        <ul class="practice-list">
          <li>Right-size instances based on observed memory and CPU utilisation, not guesswork.</li>
          <li>Use Savings Plans or Reserved Instances for predictable baseline workloads.</li>
          <li>Tag all resources and set up per-team cost allocation dashboards.</li>
          <li>Schedule non-production environments to shut down outside working hours.</li>
        </ul>
      </div>
      <div class="practice-card">
        <h3><iconify-icon icon="lucide:git-merge"></iconify-icon>CI/CD Pipeline</h3>
        <ul class="practice-list">
          <li>Gate deployments on automated tests, SAST scans, and container image signing.</li>
          <li>Use blue/green or canary releases for zero-downtime production deployments.</li>
          <li>Store secrets in a secrets manager — never in source code or environment files.</li>
          <li>Automate rollback triggers based on error-rate metrics post-deployment.</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Next steps -->
  <section class="section" id="next-steps">
    <h2 class="section-title">
      <iconify-icon icon="lucide:arrow-right-circle"></iconify-icon>
      Related Documentation
    </h2>
    <hr class="section-divider">
    <div class="next-steps-grid">
      <a href="/Documentation/getting-started.php" class="next-step-link">
        <iconify-icon icon="lucide:book-open"></iconify-icon>
        Getting Started
      </a>
      <a href="/Documentation/api-reference.php" class="next-step-link">
        <iconify-icon icon="lucide:code-2"></iconify-icon>
        API Reference
      </a>
      <a href="/Documentation/security-compliance.php" class="next-step-link">
        <iconify-icon icon="lucide:shield-check"></iconify-icon>
        Security &amp; Compliance
      </a>
      <a href="/Documentation/troubleshooting.php" class="next-step-link">
        <iconify-icon icon="lucide:wrench"></iconify-icon>
        Troubleshooting
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
