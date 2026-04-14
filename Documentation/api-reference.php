<?php
$page_title  = 'API Reference - CodeFoundry Documentation';
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

/* Auth cards */
.auth-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .auth-grid { grid-template-columns: 1fr; } }

.info-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 28px;
}
.info-card h3 {
  font-size: 1.1rem;
  font-weight: 800;
  margin: 0 0 12px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}
.info-card h3 iconify-icon { color: var(--primary); }
.info-card p {
  color: var(--text-muted);
  font-size: 0.9rem;
  line-height: 1.6;
  margin: 0 0 16px 0;
}

/* Code blocks */
pre, code {
  font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace;
}
.code-block {
  background: var(--code-bg);
  border: 1px solid var(--border-color);
  border-radius: 10px;
  overflow: hidden;
  margin: 0;
}
.code-header {
  background: #0e1520;
  border-bottom: 1px solid var(--border-color);
  padding: 10px 18px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  font-size: 12px;
  color: var(--text-muted);
  font-weight: 600;
  letter-spacing: 0.3px;
}
.code-lang {
  display: flex;
  align-items: center;
  gap: 6px;
  color: var(--primary);
}
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
.code-block pre .var { color: #c9d8f0; }

/* Tab switcher */
.tab-bar {
  display: flex;
  gap: 4px;
  margin-bottom: 16px;
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
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* Endpoints table */
.endpoints-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}
.endpoints-table th {
  background: var(--navy-3);
  color: var(--text-muted);
  font-weight: 700;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
}
.endpoints-table td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border-color);
  vertical-align: top;
  line-height: 1.5;
}
.endpoints-table tr:last-child td { border-bottom: none; }
.endpoints-table tr:hover td { background: rgba(24,179,255,0.04); }
.method-badge {
  display: inline-block;
  padding: 2px 10px;
  border-radius: 5px;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.3px;
  min-width: 52px;
  text-align: center;
}
.method-get    { background: rgba(24,179,255,0.18); color: #18b3ff; }
.method-post   { background: rgba(122,217,168,0.18); color: #7ad9a8; }
.method-put    { background: rgba(244,184,96,0.18); color: #f4b860; }
.method-delete { background: rgba(255,100,100,0.18); color: #ff7070; }
.endpoint-path { font-family: monospace; color: var(--primary); font-size: 0.88rem; }
.endpoint-desc { color: var(--text-muted); }
.table-wrapper {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  overflow: hidden;
  overflow-x: auto;
}

/* Rate limits */
.limits-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 16px;
}
@media (max-width: 768px) { .limits-grid { grid-template-columns: 1fr 1fr; } }

.limit-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 22px;
  text-align: center;
}
.limit-value {
  font-size: 2rem;
  font-weight: 900;
  color: var(--primary);
  letter-spacing: -1px;
  margin-bottom: 6px;
}
.limit-label { color: var(--text-muted); font-size: 0.85rem; font-weight: 600; }
.limit-plan  { color: var(--text-subtle); font-size: 0.8rem; margin-top: 4px; }

/* Error codes */
.error-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}
.error-table th {
  background: var(--navy-3);
  color: var(--text-muted);
  font-weight: 700;
  font-size: 12px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  padding: 12px 16px;
  text-align: left;
  border-bottom: 1px solid var(--border-color);
}
.error-table td {
  padding: 12px 16px;
  border-bottom: 1px solid var(--border-color);
  vertical-align: top;
  line-height: 1.5;
}
.error-table tr:last-child td { border-bottom: none; }
.error-code {
  font-family: monospace;
  font-weight: 700;
  color: #ff7070;
}
.error-desc { color: var(--text-muted); }

/* Next steps */
.next-steps-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 16px;
}
@media (max-width: 768px) { .next-steps-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 480px) { .next-steps-grid { grid-template-columns: 1fr; } }

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
    <span>API Reference</span>
  </nav>

  <a href="/Documentation/" class="back-link">
    <iconify-icon icon="lucide:arrow-left"></iconify-icon>
    Back to Documentation
  </a>

  <div class="page-header">
    <span class="page-badge">Developer Reference</span>
    <h1 class="page-title">API Reference</h1>
    <p class="page-desc">
      Complete REST API documentation for CodeFoundry services. Integrate code generation, IDE execution, and project management into your own tooling with our developer-friendly APIs.
    </p>
  </div>

  <!-- Authentication -->
  <section class="section" id="authentication">
    <h2 class="section-title">
      <iconify-icon icon="lucide:key-round"></iconify-icon>
      Authentication
    </h2>
    <p class="section-subtitle">All API requests must be authenticated. CodeFoundry uses API keys transmitted as Bearer tokens in the Authorization header.</p>
    <hr class="section-divider">

    <div class="auth-grid">
      <div class="info-card">
        <h3><iconify-icon icon="lucide:key-round"></iconify-icon>Generating an API Key</h3>
        <p>Navigate to <strong>Dashboard → Settings → API Keys</strong> and click <em>Generate New Key</em>. Give the key a descriptive name and select the required permission scopes. Your key will only be shown once — store it securely in a secrets manager or environment variable.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>Environment variable</span></div>
          <pre>export CODEFOUNDRY_API_KEY="cf_live_xxxxxxxxxxxxxxxxxxxx"</pre>
        </div>
      </div>
      <div class="info-card">
        <h3><iconify-icon icon="lucide:lock"></iconify-icon>Bearer Token Usage</h3>
        <p>Pass your API key in every request using the <code>Authorization</code> header with the <code>Bearer</code> scheme. All API traffic is transmitted over HTTPS — never use your key over plain HTTP.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>HTTP header</span></div>
          <pre>Authorization: Bearer cf_live_xxxxxxxxxxxxxxxxxxxx
Content-Type: application/json</pre>
        </div>
      </div>
    </div>
  </section>

  <!-- Base URL -->
  <section class="section" id="base-url">
    <h2 class="section-title">
      <iconify-icon icon="lucide:globe"></iconify-icon>
      Base URL &amp; Versioning
    </h2>
    <p class="section-subtitle">All API endpoints are versioned. The current stable version is <strong>v1</strong>. Include the version in every request path.</p>
    <hr class="section-divider">
    <div class="note-box">
      <iconify-icon icon="lucide:info"></iconify-icon>
      <span>Requests made without a version prefix will be redirected to the latest stable version. To avoid breaking changes, always specify the version explicitly.</span>
    </div>
    <div class="code-block">
      <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:link"></iconify-icon>Base URL</span></div>
      <pre>https://api.codefoundry.io/v1</pre>
    </div>
  </section>

  <!-- Endpoints -->
  <section class="section" id="endpoints">
    <h2 class="section-title">
      <iconify-icon icon="lucide:layers"></iconify-icon>
      Endpoints
    </h2>
    <p class="section-subtitle">The CodeFoundry REST API covers three core service areas: Code Generation, IDE Execution, and Project Management.</p>
    <hr class="section-divider">

    <div class="table-wrapper" style="margin-bottom:32px;">
      <table class="endpoints-table">
        <thead>
          <tr>
            <th>Method</th>
            <th>Endpoint</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="method-badge method-post">POST</span></td>
            <td class="endpoint-path">/generate/code</td>
            <td class="endpoint-desc">Generate code from a natural-language prompt. Returns source code, language metadata, and confidence score.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-post">POST</span></td>
            <td class="endpoint-path">/generate/tests</td>
            <td class="endpoint-desc">Generate unit tests for a provided code snippet. Supports Jest, pytest, JUnit, and more.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-post">POST</span></td>
            <td class="endpoint-path">/ide/execute</td>
            <td class="endpoint-desc">Execute a code snippet in a sandboxed container. Returns stdout, stderr, and exit code.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-get">GET</span></td>
            <td class="endpoint-path">/ide/execute/{jobId}</td>
            <td class="endpoint-desc">Poll the result of an async execution job by its ID.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-get">GET</span></td>
            <td class="endpoint-path">/projects</td>
            <td class="endpoint-desc">List all projects associated with your account. Supports pagination and filtering.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-post">POST</span></td>
            <td class="endpoint-path">/projects</td>
            <td class="endpoint-desc">Create a new project. Optionally seed it from a Git URL or a template identifier.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-get">GET</span></td>
            <td class="endpoint-path">/projects/{projectId}</td>
            <td class="endpoint-desc">Retrieve detailed metadata for a single project including files, collaborators, and deployment status.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-put">PUT</span></td>
            <td class="endpoint-path">/projects/{projectId}</td>
            <td class="endpoint-desc">Update project settings such as name, visibility, and runtime configuration.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-delete">DELETE</span></td>
            <td class="endpoint-path">/projects/{projectId}</td>
            <td class="endpoint-desc">Permanently delete a project and all associated resources. This action is irreversible.</td>
          </tr>
          <tr>
            <td><span class="method-badge method-post">POST</span></td>
            <td class="endpoint-path">/projects/{projectId}/deploy</td>
            <td class="endpoint-desc">Trigger a deployment of the specified project to the configured cloud environment.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Code Examples -->
  <section class="section" id="examples">
    <h2 class="section-title">
      <iconify-icon icon="lucide:code-2"></iconify-icon>
      Code Examples
    </h2>
    <p class="section-subtitle">The following examples demonstrate how to call the code generation endpoint using cURL, JavaScript (Fetch), and Python (requests).</p>
    <hr class="section-divider">

    <div class="tab-group">
      <div class="tab-bar">
        <button class="tab-btn active" data-tab="tab-curl">cURL</button>
        <button class="tab-btn" data-tab="tab-js">JavaScript</button>
        <button class="tab-btn" data-tab="tab-python">Python</button>
      </div>

      <div class="tab-panel active" id="tab-curl">
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>cURL – Generate Code</span></div>
          <pre><span class="cm"># Generate a Python function from a natural-language prompt</span>
curl -X POST https://api.codefoundry.io/v1/generate/code \
  -H <span class="str">"Authorization: Bearer $CODEFOUNDRY_API_KEY"</span> \
  -H <span class="str">"Content-Type: application/json"</span> \
  -d <span class="str">'{
    "prompt": "Write a Python function that validates an email address using regex",
    "language": "python",
    "style": "pep8",
    "include_tests": true
  }'</span></pre>
        </div>
        <div class="code-block" style="margin-top:16px;">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>cURL – Execute Code</span></div>
          <pre>curl -X POST https://api.codefoundry.io/v1/ide/execute \
  -H <span class="str">"Authorization: Bearer $CODEFOUNDRY_API_KEY"</span> \
  -H <span class="str">"Content-Type: application/json"</span> \
  -d <span class="str">'{
    "language": "python",
    "code": "print(\"Hello, CodeFoundry!\")",
    "timeout": 10
  }'</span></pre>
        </div>
      </div>

      <div class="tab-panel" id="tab-js">
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="simple-icons:javascript"></iconify-icon>JavaScript (Fetch) – Generate Code</span></div>
          <pre><span class="kw">const</span> <span class="var">response</span> = <span class="kw">await</span> <span class="fn">fetch</span>(<span class="str">'https://api.codefoundry.io/v1/generate/code'</span>, {
  <span class="var">method</span>: <span class="str">'POST'</span>,
  <span class="var">headers</span>: {
    <span class="str">'Authorization'</span>: <span class="str">`Bearer ${process.env.CODEFOUNDRY_API_KEY}`</span>,
    <span class="str">'Content-Type'</span>: <span class="str">'application/json'</span>,
  },
  <span class="var">body</span>: <span class="fn">JSON.stringify</span>({
    <span class="var">prompt</span>: <span class="str">'Create a REST endpoint in Node.js/Express for user registration'</span>,
    <span class="var">language</span>: <span class="str">'javascript'</span>,
    <span class="var">include_tests</span>: <span class="kw">true</span>,
  }),
});

<span class="kw">const</span> <span class="var">data</span> = <span class="kw">await</span> <span class="var">response</span>.<span class="fn">json</span>();
<span class="var">console</span>.<span class="fn">log</span>(<span class="var">data</span>.<span class="var">code</span>);  <span class="cm">// Generated source code</span>
<span class="var">console</span>.<span class="fn">log</span>(<span class="var">data</span>.<span class="var">language</span>);  <span class="cm">// 'javascript'</span>
<span class="var">console</span>.<span class="fn">log</span>(<span class="var">data</span>.<span class="var">confidence</span>);  <span class="cm">// 0.97</span></pre>
        </div>
      </div>

      <div class="tab-panel" id="tab-python">
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="simple-icons:python"></iconify-icon>Python (requests) – Generate &amp; Execute</span></div>
          <pre><span class="kw">import</span> <span class="var">os</span>
<span class="kw">import</span> <span class="var">requests</span>

<span class="var">API_BASE</span> = <span class="str">"https://api.codefoundry.io/v1"</span>
<span class="var">headers</span> = {
    <span class="str">"Authorization"</span>: <span class="fn">f</span><span class="str">"Bearer {os.environ['CODEFOUNDRY_API_KEY']}"</span>,
    <span class="str">"Content-Type"</span>: <span class="str">"application/json"</span>,
}

<span class="cm"># Step 1: Generate code</span>
<span class="var">gen_resp</span> = <span class="var">requests</span>.<span class="fn">post</span>(
    <span class="fn">f</span><span class="str">"{API_BASE}/generate/code"</span>,
    <span class="var">headers</span>=<span class="var">headers</span>,
    <span class="var">json</span>={
        <span class="str">"prompt"</span>: <span class="str">"Binary search algorithm in Python"</span>,
        <span class="str">"language"</span>: <span class="str">"python"</span>,
        <span class="str">"include_tests"</span>: <span class="kw">True</span>,
    },
)
<span class="var">generated_code</span> = <span class="var">gen_resp</span>.<span class="fn">json</span>()[<span class="str">"code"</span>]

<span class="cm"># Step 2: Execute generated code</span>
<span class="var">exec_resp</span> = <span class="var">requests</span>.<span class="fn">post</span>(
    <span class="fn">f</span><span class="str">"{API_BASE}/ide/execute"</span>,
    <span class="var">headers</span>=<span class="var">headers</span>,
    <span class="var">json</span>={<span class="str">"language"</span>: <span class="str">"python"</span>, <span class="str">"code"</span>: <span class="var">generated_code</span>, <span class="str">"timeout"</span>: <span class="num">15</span>},
)
<span class="var">result</span> = <span class="var">exec_resp</span>.<span class="fn">json</span>()
<span class="fn">print</span>(<span class="var">result</span>[<span class="str">"stdout"</span>])
<span class="fn">print</span>(<span class="str">"Exit code:"</span>, <span class="var">result</span>[<span class="str">"exit_code"</span>])</pre>
        </div>
      </div>
    </div>
  </section>

  <!-- Rate Limits -->
  <section class="section" id="rate-limits">
    <h2 class="section-title">
      <iconify-icon icon="lucide:gauge"></iconify-icon>
      Rate Limits
    </h2>
    <p class="section-subtitle">Rate limits are applied per API key, per minute. If you exceed your limit, the API returns a <code>429 Too Many Requests</code> response. Retry after the duration specified in the <code>Retry-After</code> header.</p>
    <hr class="section-divider">
    <div class="limits-grid">
      <div class="limit-card">
        <div class="limit-value">60</div>
        <div class="limit-label">Requests / min</div>
        <div class="limit-plan">Starter Plan</div>
      </div>
      <div class="limit-card">
        <div class="limit-value">300</div>
        <div class="limit-label">Requests / min</div>
        <div class="limit-plan">Professional Plan</div>
      </div>
      <div class="limit-card">
        <div class="limit-value">1,000</div>
        <div class="limit-label">Requests / min</div>
        <div class="limit-plan">Business Plan</div>
      </div>
      <div class="limit-card">
        <div class="limit-value">Custom</div>
        <div class="limit-label">Requests / min</div>
        <div class="limit-plan">Enterprise Plan</div>
      </div>
    </div>
  </section>

  <!-- Error Codes -->
  <section class="section" id="error-codes">
    <h2 class="section-title">
      <iconify-icon icon="lucide:alert-circle"></iconify-icon>
      Error Codes Reference
    </h2>
    <p class="section-subtitle">The API uses standard HTTP status codes. Every error response body contains a <code>code</code> string and a human-readable <code>message</code>.</p>
    <hr class="section-divider">
    <div class="table-wrapper">
      <table class="error-table">
        <thead>
          <tr>
            <th>HTTP Status</th>
            <th>Error Code</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>400</td>
            <td class="error-code">INVALID_REQUEST</td>
            <td class="error-desc">The request body is malformed or missing required fields.</td>
          </tr>
          <tr>
            <td>401</td>
            <td class="error-code">UNAUTHORIZED</td>
            <td class="error-desc">API key is missing, expired, or invalid.</td>
          </tr>
          <tr>
            <td>403</td>
            <td class="error-code">FORBIDDEN</td>
            <td class="error-desc">The API key does not have permission to access this resource.</td>
          </tr>
          <tr>
            <td>404</td>
            <td class="error-code">NOT_FOUND</td>
            <td class="error-desc">The requested resource (project, job, etc.) does not exist.</td>
          </tr>
          <tr>
            <td>422</td>
            <td class="error-code">UNPROCESSABLE</td>
            <td class="error-desc">The request is structurally valid but cannot be processed — e.g., unsupported language.</td>
          </tr>
          <tr>
            <td>429</td>
            <td class="error-code">RATE_LIMITED</td>
            <td class="error-desc">Too many requests. Check the <code>Retry-After</code> header for backoff duration.</td>
          </tr>
          <tr>
            <td>500</td>
            <td class="error-code">INTERNAL_ERROR</td>
            <td class="error-desc">An unexpected server error occurred. Please retry and contact support if the issue persists.</td>
          </tr>
          <tr>
            <td>503</td>
            <td class="error-code">SERVICE_UNAVAILABLE</td>
            <td class="error-desc">The service is temporarily unavailable. Check the status page at status.codefoundry.io.</td>
          </tr>
        </tbody>
      </table>
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
      <a href="/Documentation/cloud-solutions.php" class="next-step-link">
        <iconify-icon icon="lucide:cloud"></iconify-icon>
        Cloud Solutions
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
