<?php
$page_title  = 'Troubleshooting - CodeFoundry Documentation';
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
  --green: #7ad9a8;
  --amber: #f4b860;
  --red: #ff7070;
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

/* Category tabs */
.tab-bar {
  display: flex;
  gap: 4px;
  margin-bottom: 0;
  border-bottom: 1px solid var(--border-color);
  flex-wrap: wrap;
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

/* Issue accordion */
.issue-list {
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.issue-item {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  overflow: hidden;
  transition: border-color 0.2s;
}
.issue-item.open { border-color: var(--primary); }
.issue-toggle {
  width: 100%;
  background: none;
  border: none;
  color: var(--text);
  font-family: inherit;
  font-size: 0.95rem;
  font-weight: 700;
  padding: 18px 22px;
  text-align: left;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
}
.issue-toggle:hover { color: var(--primary); }
.issue-toggle .toggle-icon {
  color: var(--primary);
  font-size: 1.1rem;
  flex-shrink: 0;
  transition: transform 0.2s;
}
.issue-item.open .issue-toggle .toggle-icon { transform: rotate(180deg); }
.issue-body {
  display: none;
  padding: 0 22px 22px 22px;
  border-top: 1px solid var(--border-color);
}
.issue-item.open .issue-body { display: block; }
.issue-symptom {
  background: rgba(255,112,112,0.07);
  border: 1px solid rgba(255,112,112,0.2);
  border-radius: 8px;
  padding: 12px 16px;
  margin: 16px 0 16px 0;
  font-size: 0.88rem;
  color: var(--text-muted);
}
.issue-symptom strong { color: var(--red); }
.issue-solution {
  font-size: 0.9rem;
  color: var(--text-muted);
  line-height: 1.6;
  margin: 0 0 14px 0;
}
.solution-steps {
  list-style: none;
  padding: 0;
  margin: 0 0 16px 0;
  display: flex;
  flex-direction: column;
  gap: 8px;
  counter-reset: step-counter;
}
.solution-steps li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 0.88rem;
  color: var(--text-muted);
  line-height: 1.5;
  counter-increment: step-counter;
}
.solution-steps li::before {
  content: counter(step-counter);
  background: rgba(24,179,255,0.15);
  color: var(--primary);
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 800;
  flex-shrink: 0;
  margin-top: 1px;
}

/* Code blocks */
pre, code { font-family: 'JetBrains Mono', 'Fira Code', monospace; }
.code-block {
  background: var(--code-bg);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  overflow: hidden;
  margin: 12px 0 0 0;
}
.code-header {
  background: #0e1520;
  border-bottom: 1px solid var(--border-color);
  padding: 8px 16px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 11px;
  color: var(--text-muted);
  font-weight: 600;
}
.code-lang { display: flex; align-items: center; gap: 6px; color: var(--primary); }
.code-block pre {
  margin: 0;
  padding: 16px 18px;
  overflow-x: auto;
  font-size: 12.5px;
  line-height: 1.65;
  color: #c9d8f0;
}
.code-block pre .cm { color: #627193; font-style: italic; }

/* Error codes table */
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
.error-table tr:hover td { background: rgba(24,179,255,0.04); }
.table-wrapper {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  overflow: hidden;
  overflow-x: auto;
}
.error-code { font-family: monospace; font-weight: 700; color: var(--red); }
.error-muted { color: var(--text-muted); }
.http-code   { font-family: monospace; color: var(--amber); font-weight: 700; }

/* Debug tips */
.tips-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .tips-grid { grid-template-columns: 1fr; } }

.tip-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 26px;
  transition: border-color 0.2s;
}
.tip-card:hover { border-color: var(--primary); }
.tip-card h3 {
  font-size: 1rem;
  font-weight: 800;
  margin: 0 0 10px 0;
  display: flex;
  align-items: center;
  gap: 10px;
}
.tip-card h3 iconify-icon { color: var(--primary); font-size: 1.2rem; }
.tip-card p {
  color: var(--text-muted);
  font-size: 0.88rem;
  line-height: 1.6;
  margin: 0 0 14px 0;
}

/* Support */
.support-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
}
@media (max-width: 768px) { .support-grid { grid-template-columns: 1fr; } }

.support-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 28px;
  transition: border-color 0.2s, transform 0.2s;
}
.support-card:hover {
  border-color: var(--primary);
  transform: translateY(-3px);
}
.support-icon {
  font-size: 2.2rem;
  color: var(--primary);
  margin-bottom: 14px;
  display: block;
}
.support-card h3 { font-size: 1.05rem; font-weight: 800; margin: 0 0 8px 0; }
.support-card p {
  color: var(--text-muted);
  font-size: 0.88rem;
  line-height: 1.6;
  margin: 0 0 16px 0;
}
.support-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: var(--primary);
  font-weight: 700;
  font-size: 14px;
}
.support-link:hover { color: var(--primary-hover); }
.support-tag {
  display: inline-block;
  background: rgba(122,217,168,0.12);
  color: var(--green);
  border-radius: 6px;
  padding: 3px 10px;
  font-size: 11px;
  font-weight: 700;
  margin-bottom: 10px;
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

// Issue accordion
document.querySelectorAll('.issue-toggle').forEach(function(btn) {
  btn.addEventListener('click', function() {
    const item = btn.closest('.issue-item');
    item.classList.toggle('open');
  });
});

// Category tabs
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
    <span>Troubleshooting</span>
  </nav>

  <a href="/Documentation/" class="back-link">
    <iconify-icon icon="lucide:arrow-left"></iconify-icon>
    Back to Documentation
  </a>

  <div class="page-header">
    <span class="page-badge">Support Guide</span>
    <h1 class="page-title">Troubleshooting</h1>
    <p class="page-desc">
      Find solutions to common issues organised by category. If you can't resolve your problem here, check the error codes reference or contact our support team.
    </p>
  </div>

  <!-- Common Issues -->
  <section class="section" id="common-issues">
    <h2 class="section-title">
      <iconify-icon icon="lucide:list-checks"></iconify-icon>
      Common Issues &amp; Solutions
    </h2>
    <p class="section-subtitle">Select a category to browse known issues and step-by-step resolutions.</p>
    <hr class="section-divider">

    <div class="tab-group">
      <div class="tab-bar">
        <button class="tab-btn active" data-tab="tab-ide">IDE</button>
        <button class="tab-btn" data-tab="tab-codegen">Code Generation</button>
        <button class="tab-btn" data-tab="tab-auth">Authentication</button>
        <button class="tab-btn" data-tab="tab-billing">Billing</button>
      </div>

      <!-- IDE issues -->
      <div class="tab-panel active" id="tab-ide">
        <div class="issue-list">

          <div class="issue-item open">
            <button class="issue-toggle" aria-expanded="true">
              IDE fails to load or shows a blank screen
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> The browser-based IDE loads a blank white or dark screen and never completes initialisation.</div>
              <p class="issue-solution">This is usually caused by a cached service worker conflict, an outdated browser, or a network proxy blocking WebSocket connections.</p>
              <ol class="solution-steps">
                <li>Open DevTools (F12), navigate to <strong>Application → Service Workers</strong>, and click <em>Unregister</em> for all codefoundry.io workers. Reload.</li>
                <li>Clear browser cache: <strong>Settings → Privacy → Clear browsing data → Cached images and files</strong>.</li>
                <li>Confirm your browser is Chromium-based (Chrome 110+, Edge 110+) or Firefox 115+. Safari is not currently supported.</li>
                <li>Check that WebSockets are not blocked by a corporate proxy or browser extension by visiting <code>wss://ide.codefoundry.io/health</code> in the browser console.</li>
                <li>Try an incognito/private window to rule out extension interference.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              Terminal not responding or stuck at "Connecting…"
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> The integrated terminal panel shows "Connecting to container…" indefinitely.</div>
              <p class="issue-solution">The container backing your IDE session may have crashed or been reclaimed after an idle period.</p>
              <ol class="solution-steps">
                <li>Click the <strong>Restart Session</strong> button in the bottom-right status bar.</li>
                <li>If the session does not restart within 60 seconds, close the tab and re-open your project from the Dashboard.</li>
                <li>Check your plan's concurrent session limit — Starter accounts support 1 active session at a time.</li>
                <li>If the issue persists after restart, run a diagnostic from your account settings: <strong>Dashboard → Settings → Run Diagnostics</strong>.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              File changes not saving / "Read-only filesystem" error
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> File saves appear to succeed but revert on reload, or the terminal shows <code>Read-only file system</code>.</div>
              <ol class="solution-steps">
                <li>Check available storage: run <code>df -h /workspace</code> in the terminal. If usage is at 100%, delete unused files or upgrade your plan.</li>
                <li>Verify you have write access: run <code>ls -la /workspace</code> and confirm your user owns the directory.</li>
                <li>If using a Git-backed project, check that the remote is reachable: <code>git remote -v &amp;&amp; git fetch</code>.</li>
                <li>Force-remount the workspace by restarting the session and checking the container health badge in the status bar.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              Code execution is very slow or times out
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> Running code takes significantly longer than expected, or the runner returns a <code>TIMEOUT</code> error.</div>
              <ol class="solution-steps">
                <li>Check container resource usage: run <code>top</code> or <code>htop</code> in the terminal to identify CPU/memory hotspots.</li>
                <li>Starter plan containers are capped at 0.5 vCPU and 512 MB RAM. Upgrade to Professional for 2 vCPU / 4 GB.</li>
                <li>Avoid importing large datasets in the IDE runner. Use a separate data pipeline for files over 100 MB.</li>
                <li>If the execution timeout is too short for your use case, configure it in <strong>Project Settings → Runner → Timeout (max 300s on Business)</strong>.</li>
              </ol>
            </div>
          </div>

        </div>
      </div>

      <!-- Code Generation issues -->
      <div class="tab-panel" id="tab-codegen">
        <div class="issue-list">

          <div class="issue-item open">
            <button class="issue-toggle" aria-expanded="true">
              Generated code contains placeholder comments or TODO stubs
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> The AI returns code with <code>// TODO: implement</code> stubs or placeholder functions instead of real implementations.</div>
              <p class="issue-solution">This usually means the prompt is too vague or the requested logic is too complex for a single generation call.</p>
              <ol class="solution-steps">
                <li>Make your prompt more specific: include the expected input types, output format, error handling requirements, and any constraints.</li>
                <li>Break complex prompts into smaller, focused requests — generate each function or module separately.</li>
                <li>Use the <code>detail: "high"</code> parameter in the API request body to request more complete output.</li>
                <li>If a stub is returned, follow up with a continuation prompt: <em>"Complete the implementation of [function name] with the following behaviour: …"</em></li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              API returns 422 "Unsupported language" error
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> The <code>/generate/code</code> endpoint returns <code>HTTP 422 UNPROCESSABLE</code> with message "Unsupported language".</div>
              <ol class="solution-steps">
                <li>Check the supported languages list at <code>GET /v1/meta/languages</code>.</li>
                <li>Ensure the <code>language</code> field uses the lowercase identifier (e.g., <code>"python"</code> not <code>"Python 3"</code>).</li>
                <li>If your language is not in the list, use <code>"language": "generic"</code> and specify the target language in the prompt itself.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              Generation quota exhausted mid-sprint
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> The generator returns <code>HTTP 429</code> with message "Monthly generation quota exceeded".</div>
              <ol class="solution-steps">
                <li>Check your current usage in <strong>Dashboard → Usage → Code Generation</strong>.</li>
                <li>Enable quota alerts at 70% and 90% usage in <strong>Dashboard → Settings → Notifications</strong> to avoid surprise interruptions.</li>
                <li>Upgrade your plan for a higher monthly quota, or purchase a quota top-up from the Billing page.</li>
                <li>Until the quota resets (1st of each month), use the IDE's built-in Copilot-style inline suggestions as a fallback.</li>
              </ol>
            </div>
          </div>

        </div>
      </div>

      <!-- Auth issues -->
      <div class="tab-panel" id="tab-auth">
        <div class="issue-list">

          <div class="issue-item open">
            <button class="issue-toggle" aria-expanded="true">
              "Invalid or expired token" error on API calls
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> API requests return <code>HTTP 401 UNAUTHORIZED</code> with message "Invalid or expired token".</div>
              <ol class="solution-steps">
                <li>Confirm you are using the correct key. API keys are prefixed <code>cf_live_</code> (production) or <code>cf_test_</code> (sandbox).</li>
                <li>Check that the key has not been rotated: view active keys at <strong>Dashboard → Settings → API Keys</strong>.</li>
                <li>Ensure the <code>Authorization</code> header format is exactly <code>Bearer &lt;key&gt;</code> with a single space — no line breaks or extra characters.</li>
                <li>If using JWTs from the OAuth flow, check the <code>exp</code> claim. Access tokens expire after 60 minutes; use the refresh token to obtain a new one.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              MFA prompt appears on every login despite "Remember this device"
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> You are prompted for MFA on every login even after ticking "Remember this device for 30 days".</div>
              <ol class="solution-steps">
                <li>The "Remember device" feature requires cookies. Confirm third-party cookies are enabled for <code>auth.codefoundry.io</code> in your browser settings.</li>
                <li>Check that a browser extension (uBlock Origin, Privacy Badger) is not blocking the <code>cf_device</code> cookie.</li>
                <li>If you clear cookies or use incognito mode, the device trust is reset and you must re-verify.</li>
                <li>Corporate SSO integrations may override the device-trust cookie — contact your IT administrator.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              SSO login redirects back to login page with no error
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> Clicking "Sign in with SSO" redirects to your identity provider and back, but lands on the login page again silently.</div>
              <ol class="solution-steps">
                <li>Open DevTools → Network, filter by <code>auth</code>, and check the callback URL response for a <code>error</code> query parameter.</li>
                <li>Verify the Assertion Consumer Service (ACS) URL configured in your IdP matches exactly: <code>https://auth.codefoundry.io/saml/acs</code>.</li>
                <li>Check the SAML attribute mapping — CodeFoundry requires <code>email</code> and optionally <code>firstName</code>, <code>lastName</code>, and <code>groups</code>.</li>
                <li>Review the SSO audit log at <strong>Admin → SSO → Audit Log</strong> for a detailed error message from the IdP.</li>
              </ol>
            </div>
          </div>

        </div>
      </div>

      <!-- Billing issues -->
      <div class="tab-panel" id="tab-billing">
        <div class="issue-list">

          <div class="issue-item open">
            <button class="issue-toggle" aria-expanded="true">
              Payment declined / card not accepted
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> Attempting to add a credit/debit card or complete a purchase returns "Your card was declined".</div>
              <ol class="solution-steps">
                <li>Confirm your bank has not blocked the charge. CodeFoundry charges appear as "CODEFOUNDRY.IO" on statements. Call your bank to whitelist the merchant if needed.</li>
                <li>Ensure the billing address entered matches your card's registered address exactly.</li>
                <li>Try a different card or a PayPal account as an alternative payment method.</li>
                <li>If paying in a non-USD currency, check that your card supports international transactions.</li>
                <li>Contact <a href="/Support/" style="color:var(--primary)">Support</a> with your account email — we can attempt manual card validation.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              Unexpected charge or invoice discrepancy
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> A charge on your bank statement doesn't match your expected plan cost.</div>
              <ol class="solution-steps">
                <li>View itemised invoices at <strong>Dashboard → Billing → Invoices</strong>. Each line item shows the source (subscription, overage, add-on).</li>
                <li>Check for usage overages: Cloud compute and storage beyond plan limits are billed at the per-unit rate listed on the Pricing page.</li>
                <li>If you upgraded or downgraded mid-cycle, a prorated charge or credit will appear on your next invoice.</li>
                <li>If you believe there is a genuine billing error, open a support ticket with the invoice number and we will investigate within 2 business days.</li>
              </ol>
            </div>
          </div>

          <div class="issue-item">
            <button class="issue-toggle" aria-expanded="false">
              Cannot cancel subscription / cancel button missing
              <iconify-icon icon="lucide:chevron-down" class="toggle-icon"></iconify-icon>
            </button>
            <div class="issue-body">
              <div class="issue-symptom"><strong>Symptom:</strong> The "Cancel Plan" option is not visible in Billing settings.</div>
              <ol class="solution-steps">
                <li>Only the account Owner role can cancel a subscription. Confirm your role at <strong>Dashboard → Settings → Team</strong>.</li>
                <li>Annual subscriptions cannot be cancelled mid-term via the UI — contact support to discuss early termination options.</li>
                <li>If you are on an Enterprise plan managed by a reseller, contact your reseller directly to cancel.</li>
                <li>To downgrade rather than cancel, click <strong>Change Plan</strong> and select the Starter (free) tier. Your paid features remain active until the end of the current billing period.</li>
              </ol>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>

  <!-- Error Codes Reference -->
  <section class="section" id="error-codes">
    <h2 class="section-title">
      <iconify-icon icon="lucide:alert-circle"></iconify-icon>
      Error Codes Reference
    </h2>
    <p class="section-subtitle">Platform-level error codes returned in error response bodies and displayed in the IDE status bar.</p>
    <hr class="section-divider">
    <div class="table-wrapper">
      <table class="error-table">
        <thead>
          <tr>
            <th>HTTP</th>
            <th>Code</th>
            <th>Meaning</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="http-code">400</td>
            <td class="error-code">INVALID_REQUEST</td>
            <td class="error-muted">Malformed JSON or missing required fields.</td>
            <td class="error-muted">Validate your request body against the API schema.</td>
          </tr>
          <tr>
            <td class="http-code">401</td>
            <td class="error-code">UNAUTHORIZED</td>
            <td class="error-muted">API key missing or invalid.</td>
            <td class="error-muted">Regenerate or verify your API key in Dashboard settings.</td>
          </tr>
          <tr>
            <td class="http-code">401</td>
            <td class="error-code">TOKEN_EXPIRED</td>
            <td class="error-muted">JWT access token has expired.</td>
            <td class="error-muted">Use the refresh token endpoint to obtain a new access token.</td>
          </tr>
          <tr>
            <td class="http-code">403</td>
            <td class="error-code">FORBIDDEN</td>
            <td class="error-muted">Valid credentials but insufficient permissions.</td>
            <td class="error-muted">Check your API key scopes or user role assignments.</td>
          </tr>
          <tr>
            <td class="http-code">404</td>
            <td class="error-code">NOT_FOUND</td>
            <td class="error-muted">Resource does not exist.</td>
            <td class="error-muted">Verify the resource ID and that it belongs to your account.</td>
          </tr>
          <tr>
            <td class="http-code">409</td>
            <td class="error-code">CONFLICT</td>
            <td class="error-muted">Resource already exists with this identifier.</td>
            <td class="error-muted">Use a unique name/ID or update the existing resource instead.</td>
          </tr>
          <tr>
            <td class="http-code">422</td>
            <td class="error-code">UNPROCESSABLE</td>
            <td class="error-muted">Valid structure but invalid business logic (e.g. unsupported language).</td>
            <td class="error-muted">Check the <code>details</code> field in the response body.</td>
          </tr>
          <tr>
            <td class="http-code">429</td>
            <td class="error-code">RATE_LIMITED</td>
            <td class="error-muted">Request rate or quota exceeded.</td>
            <td class="error-muted">Respect the <code>Retry-After</code> header; consider upgrading your plan.</td>
          </tr>
          <tr>
            <td class="http-code">500</td>
            <td class="error-code">INTERNAL_ERROR</td>
            <td class="error-muted">Unexpected server-side error.</td>
            <td class="error-muted">Retry with exponential backoff. Open a support ticket if persistent.</td>
          </tr>
          <tr>
            <td class="http-code">503</td>
            <td class="error-code">SERVICE_UNAVAILABLE</td>
            <td class="error-muted">Temporary service disruption.</td>
            <td class="error-muted">Check <code>status.codefoundry.io</code> and retry after the stated incident window.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

  <!-- Debug Tips -->
  <section class="section" id="debug-tips">
    <h2 class="section-title">
      <iconify-icon icon="lucide:microscope"></iconify-icon>
      Debug Tips &amp; Diagnostic Commands
    </h2>
    <p class="section-subtitle">Use these commands and techniques to gather information before contacting support. Include the output when opening a ticket.</p>
    <hr class="section-divider">
    <div class="tips-grid">
      <div class="tip-card">
        <h3><iconify-icon icon="lucide:terminal"></iconify-icon>Container Health Check</h3>
        <p>Run these commands in the IDE terminal to capture environment and resource diagnostics.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>Shell</span></div>
          <pre><span class="cm"># System info</span>
uname -a &amp;&amp; cat /etc/os-release
<span class="cm"># Resource usage</span>
df -h /workspace &amp;&amp; free -m
<span class="cm"># Network connectivity</span>
curl -s https://api.codefoundry.io/v1/health
<span class="cm"># Running processes</span>
ps aux --sort=-%cpu | head -20</pre>
        </div>
      </div>
      <div class="tip-card">
        <h3><iconify-icon icon="lucide:bug"></iconify-icon>API Request Debugging</h3>
        <p>Add verbose flags to cURL calls to capture full request/response headers for API issues.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>cURL with verbose output</span></div>
          <pre><span class="cm"># -v shows headers, -w shows timing</span>
curl -v -w "\n\nTime: %{time_total}s\n" \
  -X GET https://api.codefoundry.io/v1/projects \
  -H "Authorization: Bearer $CF_API_KEY" \
  2&gt;&amp;1 | tee api-debug.log</pre>
        </div>
      </div>
      <div class="tip-card">
        <h3><iconify-icon icon="lucide:network"></iconify-icon>Network Diagnostics</h3>
        <p>Verify connectivity and DNS resolution to CodeFoundry services from your environment.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>Network checks</span></div>
          <pre><span class="cm"># DNS resolution</span>
nslookup api.codefoundry.io
<span class="cm"># Latency to API</span>
ping -c 4 api.codefoundry.io
<span class="cm"># TLS certificate check</span>
openssl s_client -connect \
  api.codefoundry.io:443 &lt;/dev/null \
  | openssl x509 -noout -dates</pre>
        </div>
      </div>
      <div class="tip-card">
        <h3><iconify-icon icon="lucide:file-text"></iconify-icon>Log Collection</h3>
        <p>Collect application and platform logs to share with support. Logs are retained for 30 days.</p>
        <div class="code-block">
          <div class="code-header"><span class="code-lang"><iconify-icon icon="lucide:terminal"></iconify-icon>Fetch logs via CLI</span></div>
          <pre><span class="cm"># Install CodeFoundry CLI</span>
npm install -g @codefoundry/cli
<span class="cm"># Authenticate</span>
cf auth login
<span class="cm"># Export last 500 lines of project logs</span>
cf logs --project my-project \
  --tail 500 --format json \
  &gt; project-logs.json</pre>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Support -->
  <section class="section" id="contact-support">
    <h2 class="section-title">
      <iconify-icon icon="lucide:headphones"></iconify-icon>
      Contact Support
    </h2>
    <p class="section-subtitle">Still stuck? Our support team is here to help. Choose the channel that best matches your urgency and plan tier.</p>
    <hr class="section-divider">
    <div class="note-box">
      <iconify-icon icon="lucide:info"></iconify-icon>
      <span>When contacting support, please include: your account email, a description of the issue, steps to reproduce, and any error codes or log output. This helps us resolve your ticket faster.</span>
    </div>
    <div class="support-grid">
      <div class="support-card">
        <iconify-icon icon="lucide:life-buoy" class="support-icon"></iconify-icon>
        <span class="support-tag">All Plans</span>
        <h3>Support Centre</h3>
        <p>Browse our knowledge base, submit a ticket, and track the status of open requests through the Support portal.</p>
        <a href="/Support/" class="support-link">
          Open Support Portal <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
      <div class="support-card">
        <iconify-icon icon="lucide:message-square" class="support-icon"></iconify-icon>
        <span class="support-tag">Professional+</span>
        <h3>Live Chat</h3>
        <p>Chat with a support engineer in real time during business hours (Mon–Fri 09:00–18:00 UTC). Average response time: under 5 minutes.</p>
        <a href="/Support/#chat" class="support-link">
          Start Chat <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
      <div class="support-card">
        <iconify-icon icon="lucide:phone" class="support-icon"></iconify-icon>
        <span class="support-tag">Enterprise</span>
        <h3>Dedicated Support Line</h3>
        <p>Enterprise customers have a named Customer Success Manager and access to a 24/7 emergency hotline with a 1-hour response SLA for critical incidents.</p>
        <a href="/Support/#enterprise" class="support-link">
          Contact CSM <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
      </div>
      <div class="support-card">
        <iconify-icon icon="lucide:github" class="support-icon"></iconify-icon>
        <span class="support-tag">Community</span>
        <h3>GitHub Discussions</h3>
        <p>Search existing community questions, report non-security bugs, and engage with the CodeFoundry developer community on GitHub.</p>
        <a href="#" class="support-link">
          Visit Discussions <iconify-icon icon="lucide:arrow-right"></iconify-icon>
        </a>
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
      <a href="/Documentation/cloud-solutions.php" class="next-step-link">
        <iconify-icon icon="lucide:cloud"></iconify-icon>
        Cloud Solutions
      </a>
      <a href="/Documentation/security-compliance.php" class="next-step-link">
        <iconify-icon icon="lucide:shield-check"></iconify-icon>
        Security &amp; Compliance
      </a>
    </div>
  </section>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
