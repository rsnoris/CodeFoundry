<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/CodeGenProvider.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';

// Provider list for the client-side model selector
$_cf_providers_js = [];
foreach (CodeGenProvider::all() as $pid => $pdata) {
    if (!$pdata['available']) continue;
    $models = [];
    foreach ($pdata['models'] as $m) {
        $models[] = ['id' => $m['id'], 'label' => $m['label']];
    }
    $_cf_providers_js[] = [
        'id'            => $pid,
        'label'         => $pdata['label'],
        'opensource'    => $pdata['opensource'],
        'default_model' => $pdata['default_model'],
        'models'        => $models,
    ];
}

// Load recent generations for logged-in users
if (session_status() === PHP_SESSION_NONE) session_start();
$_cf_user      = $_SESSION['cf_user'] ?? null;
$_cf_recents   = [];
if ($_cf_user) {
    $_cf_recents = UserStore::tokenHistoryForUser($_cf_user['username'], 6);
}

$page_title  = 'Generate Code with AI – CodeFoundry';
$active_page = 'ide';
$page_styles = <<<'PAGECSS'
/* ── Layout ──────────────────────────────────────────────── */
.gen-layout {
  display: flex;
  min-height: calc(100vh - var(--header-height));
}

/* ── Sidebar ─────────────────────────────────────────────── */
.gen-sidebar {
  width: 220px;
  flex-shrink: 0;
  border-right: 1px solid var(--border-color);
  padding: 28px 0;
  display: flex;
  flex-direction: column;
  gap: 4px;
  background: var(--navy);
}
.gen-sidebar-section {
  padding: 0 12px;
  margin-bottom: 6px;
}
.gen-sidebar-label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--text-subtle);
  padding: 12px 8px 6px;
}
.gen-sidebar-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 10px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 500;
  color: var(--text-muted);
  transition: background .15s, color .15s;
  cursor: pointer;
}
.gen-sidebar-link:hover {
  background: var(--navy-3);
  color: var(--text);
}
.gen-sidebar-link.active {
  background: rgba(24,179,255,.12);
  color: var(--primary);
}
.gen-sidebar-link iconify-icon {
  font-size: 17px;
  flex-shrink: 0;
}
.gen-sidebar-bottom {
  margin-top: auto;
  padding: 0 12px;
  border-top: 1px solid var(--border-color);
  padding-top: 16px;
}

/* ── Main content ────────────────────────────────────────── */
.gen-main {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 56px 24px 80px;
  overflow-y: auto;
}

/* ── CodeGen brand label ─────────────────────────────────── */
.gen-brand {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  color: var(--primary);
  margin-bottom: 14px;
}
.gen-brand iconify-icon { font-size: 18px; }

/* ── Badge ───────────────────────────────────────────────── */
.gen-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 600;
  color: var(--text-muted);
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  padding: 4px 12px;
  margin-bottom: 20px;
}
.gen-badge iconify-icon { font-size: 14px; color: var(--primary); }

/* ── Heading ─────────────────────────────────────────────── */
.gen-heading {
  font-size: clamp(1.7rem, 3.5vw, 2.6rem);
  font-weight: 900;
  letter-spacing: -.5px;
  margin: 0 0 32px;
  text-align: center;
  line-height: 1.15;
}

/* ── Prompt card ─────────────────────────────────────────── */
.gen-card {
  width: 100%;
  max-width: 720px;
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: 14px;
  padding: 6px 6px 10px;
  margin-bottom: 28px;
  box-shadow: 0 4px 24px rgba(0,0,0,.18);
}
.gen-prompt {
  width: 100%;
  background: transparent;
  color: var(--text);
  border: none;
  padding: 16px 18px 8px;
  font-family: 'Inter', sans-serif;
  font-size: 15px;
  line-height: 1.6;
  resize: none;
  min-height: 100px;
  box-sizing: border-box;
  outline: none;
}
.gen-prompt::placeholder { color: var(--text-subtle); }

.gen-card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 6px 10px 2px;
  gap: 10px;
  flex-wrap: wrap;
}
.gen-card-footer-left {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-wrap: wrap;
}

/* ── Options toggle ──────────────────────────────────────── */
.gen-options-toggle {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 12px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-muted);
  background: transparent;
  border: 1px solid var(--border-color);
  cursor: pointer;
  transition: color .15s, border-color .15s;
}
.gen-options-toggle:hover { color: var(--text); border-color: var(--text-muted); }
.gen-options-toggle iconify-icon { font-size: 15px; }

.gen-options-panel {
  display: none;
  flex-direction: column;
  gap: 14px;
  padding: 14px 18px 8px;
  border-top: 1px solid var(--border-color);
  margin-top: 4px;
}
.gen-options-panel.open { display: flex; }
.gen-options-row {
  display: flex;
  gap: 16px;
  flex-wrap: wrap;
}
.gen-options-field {
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.gen-options-label {
  font-size: 12px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
}

/* ── Shared select styling ───────────────────────────────── */
.gen-select {
  background: var(--navy-2);
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: var(--button-radius);
  padding: 8px 30px 8px 12px;
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  transition: border-color .2s;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2392a3bb' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
}
.gen-select:focus { outline: none; border-color: var(--primary); }
.gen-lang-select { min-width: 180px; }
.gen-model-select { min-width: 240px; }
.gen-model-hint {
  font-size: 11px;
  color: var(--text-subtle);
  display: flex;
  align-items: center;
  gap: 3px;
  margin-top: 2px;
}
.gen-model-hint iconify-icon { font-size: 11px; }

/* ── Generate button ─────────────────────────────────────── */
.gen-submit-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 9px 20px;
  border-radius: 8px;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 700;
  background: var(--primary);
  color: #fff;
  border: none;
  cursor: pointer;
  transition: background .2s;
  white-space: nowrap;
}
.gen-submit-btn:hover:not(:disabled) { background: var(--primary-hover); }
.gen-submit-btn:disabled {
  background: #1e3a4a;
  color: #38bdf866;
  cursor: not-allowed;
}
.gen-submit-btn iconify-icon { font-size: 16px; }

/* ── Quick-action chips ──────────────────────────────────── */
.gen-chips {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 52px;
}
.gen-chip {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  cursor: pointer;
  transition: transform .15s;
}
.gen-chip:hover { transform: translateY(-2px); }
.gen-chip-icon {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  transition: border-color .15s, background .15s;
}
.gen-chip:hover .gen-chip-icon {
  border-color: var(--primary);
  background: rgba(24,179,255,.08);
}
.gen-chip-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-muted);
  white-space: nowrap;
}

/* ── Recents section ─────────────────────────────────────── */
.gen-recents {
  width: 100%;
  max-width: 900px;
}
.gen-recents-heading {
  font-size: 1.2rem;
  font-weight: 900;
  margin: 0 0 18px;
  letter-spacing: -.3px;
}
.gen-recents-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 14px;
}
.gen-recent-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 16px 18px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  transition: border-color .2s, transform .15s;
  cursor: pointer;
}
.gen-recent-card:hover {
  border-color: var(--primary);
  transform: translateY(-2px);
}
.gen-recent-card-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 6px;
}
.gen-recent-lang-badge {
  font-size: 11px;
  font-weight: 700;
  background: rgba(24,179,255,.1);
  color: var(--primary);
  border-radius: 20px;
  padding: 2px 10px;
}
.gen-recent-time {
  font-size: 11px;
  color: var(--text-subtle);
}
.gen-recent-snippet {
  font-family: 'Courier New', Courier, monospace;
  font-size: 12px;
  color: var(--text-muted);
  background: #0d1117;
  border-radius: 6px;
  padding: 8px 10px;
  white-space: pre-wrap;
  overflow: hidden;
  max-height: 56px;
  line-height: 1.5;
  word-break: break-all;
}
.gen-recent-prompt {
  font-size: 13px;
  color: var(--text-muted);
  line-height: 1.45;
  overflow: hidden;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

/* ── Result section ─────────────────────────────────────── */
.gen-result {
  display: none;
  flex-direction: column;
  gap: 12px;
  width: 100%;
  max-width: 720px;
  margin-top: 8px;
}
.gen-result.visible { display: flex; }
.gen-result-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
}
.gen-result-title {
  font-size: 13px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
}
.gen-result-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.gen-code-output {
  background: #0d1117;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 16px;
  font-family: 'Courier New', Courier, monospace;
  font-size: 13px;
  line-height: 1.6;
  color: #e2e8f0;
  white-space: pre;
  overflow-x: auto;
  max-height: 420px;
  overflow-y: auto;
  tab-size: 2;
}

/* ── Shared button styles ────────────────────────────────── */
.gen-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 8px 16px;
  border-radius: var(--button-radius);
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: background .2s, color .2s, border-color .2s;
  white-space: nowrap;
}
.gen-btn.ghost {
  background: transparent;
  color: var(--text-muted);
  border: 1px solid var(--border-color);
}
.gen-btn.ghost:hover { color: var(--text); border-color: var(--text-muted); }
.gen-btn.success { background: #22c55e; color: #fff; }
.gen-btn.success:hover { background: #16a34a; }

/* ── GitHub Push modal ───────────────────────────────────── */
.gh-modal-backdrop {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.65);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}
.gh-modal-backdrop.open { display: flex; }
.gh-modal {
  background: var(--navy);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 32px 28px 24px;
  width: 100%;
  max-width: 480px;
  position: relative;
}
.gh-modal-title {
  font-size: 1.1rem;
  font-weight: 800;
  margin: 0 0 4px;
}
.gh-modal-subtitle {
  font-size: 13px;
  color: var(--text-muted);
  margin: 0 0 22px;
}
.gh-modal-close {
  position: absolute;
  top: 14px;
  right: 16px;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--text-muted);
  font-size: 20px;
  line-height: 1;
  padding: 4px;
}
.gh-modal-close:hover { color: var(--text); }
.gh-connect-section { text-align: center; padding: 8px 0 4px; }
.gh-connect-section p {
  color: var(--text-muted);
  font-size: 13px;
  margin: 0 0 16px;
  line-height: 1.5;
}
.gh-form-group {
  margin-bottom: 14px;
}
.gh-form-label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
  margin-bottom: 6px;
}
.gh-form-input {
  width: 100%;
  padding: 9px 12px;
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: var(--button-radius);
  color: var(--text);
  font-size: 14px;
  font-family: inherit;
  outline: none;
  transition: border-color .2s;
  box-sizing: border-box;
}
.gh-form-input:focus { border-color: var(--primary); }
.gh-radio-group {
  display: flex;
  gap: 16px;
  margin-bottom: 16px;
}
.gh-radio-label {
  display: flex;
  align-items: center;
  gap: 7px;
  font-size: 14px;
  font-weight: 600;
  color: var(--text-muted);
  cursor: pointer;
}
.gh-radio-label input { accent-color: var(--primary); cursor: pointer; }
.gh-connected-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 700;
  color: #22c55e;
  background: rgba(34,197,94,.1);
  border-radius: 20px;
  padding: 3px 10px;
  margin-bottom: 18px;
}
.gh-modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
  padding-top: 16px;
  border-top: 1px solid var(--border-color);
}
.gh-success-msg {
  display: none;
  align-items: center;
  gap: 8px;
  background: rgba(34,197,94,.1);
  border: 1px solid rgba(34,197,94,.3);
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 13px;
  color: #4ade80;
  margin-top: 12px;
}
.gh-success-msg.visible { display: flex; }
.gh-success-msg a { color: #4ade80; text-decoration: underline; }
.gh-push-error {
  display: none;
  align-items: center;
  gap: 8px;
  background: #2d1515;
  border: 1px solid #7f1d1d;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 13px;
  color: #fca5a5;
  margin-top: 12px;
}
.gh-push-error.visible { display: flex; }

/* ── Error state ─────────────────────────────────────────── */
.gen-error {
  display: none;
  align-items: center;
  gap: 8px;
  background: #2d1515;
  border: 1px solid #7f1d1d;
  border-radius: 8px;
  padding: 12px 14px;
  font-size: 13px;
  color: #fca5a5;
  width: 100%;
  max-width: 720px;
  margin-top: 4px;
}
.gen-error.visible { display: flex; }

/* ── Spinner ─────────────────────────────────────────────── */
.spinner {
  display: inline-block;
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255,255,255,.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin .7s linear infinite;
  vertical-align: middle;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 768px) {
  .gen-sidebar { display: none; }
  .gen-main { padding: 36px 16px 60px; }
  .gen-chips { gap: 8px; }
  .gen-chip-icon { width: 44px; height: 44px; font-size: 18px; }
}
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="gen-layout">

  <!-- ── Sidebar ─────────────────────────────────────────── -->
  <aside class="gen-sidebar" aria-label="Generate navigation">
    <div class="gen-sidebar-section">
      <a href="/Generate/" class="gen-sidebar-link active">
        <iconify-icon icon="lucide:sparkles"></iconify-icon>
        Generate
      </a>
      <a href="/IDE/" class="gen-sidebar-link">
        <iconify-icon icon="lucide:code-2"></iconify-icon>
        Open IDE
      </a>
    </div>

    <div class="gen-sidebar-section">
      <div class="gen-sidebar-label">Templates</div>
      <a href="#chip-python" class="gen-sidebar-link" id="sbPython">
        <iconify-icon icon="lucide:file-code"></iconify-icon>
        Python Script
      </a>
      <a href="#chip-restapi" class="gen-sidebar-link" id="sbRestApi">
        <iconify-icon icon="lucide:globe"></iconify-icon>
        REST API
      </a>
      <a href="#chip-landing" class="gen-sidebar-link" id="sbLanding">
        <iconify-icon icon="lucide:layout"></iconify-icon>
        Landing Page
      </a>
      <a href="#chip-mobile" class="gen-sidebar-link" id="sbMobile">
        <iconify-icon icon="lucide:smartphone"></iconify-icon>
        Mobile App
      </a>
      <a href="#chip-bash" class="gen-sidebar-link" id="sbBash">
        <iconify-icon icon="lucide:terminal"></iconify-icon>
        Bash Script
      </a>
    </div>

    <div class="gen-sidebar-section">
      <div class="gen-sidebar-label">Account</div>
      <?php if ($_cf_user): ?>
      <a href="/Dashboard/" class="gen-sidebar-link">
        <iconify-icon icon="lucide:layout-dashboard"></iconify-icon>
        Dashboard
      </a>
      <?php else: ?>
      <a href="/Pricing/" class="gen-sidebar-link">
        <iconify-icon icon="lucide:zap"></iconify-icon>
        Upgrade
      </a>
      <a href="/Login/" class="gen-sidebar-link">
        <iconify-icon icon="lucide:log-in"></iconify-icon>
        Login
      </a>
      <?php endif; ?>
    </div>

    <div class="gen-sidebar-bottom">
      <a href="/Pricing/" class="gen-sidebar-link">
        <iconify-icon icon="lucide:info"></iconify-icon>
        Pricing
      </a>
    </div>
  </aside>

  <!-- ── Main ────────────────────────────────────────────── -->
  <main class="gen-main">

    <!-- CodeGen branding -->
    <div class="gen-brand">
      <iconify-icon icon="lucide:sparkles" width="22"></iconify-icon>
      CodeGen
    </div>

    <!-- Badge -->
    <div class="gen-badge">
      <iconify-icon icon="lucide:gift"></iconify-icon>
      Free credits available
    </div>

    <!-- Heading -->
    <h1 class="gen-heading">What should we generate?</h1>

    <!-- Prompt card -->
    <div class="gen-card" id="genCard">
      <textarea id="genPrompt" class="gen-prompt" rows="4"
        placeholder="Describe the code you need…&#10;e.g. Write a REST API in Python with user authentication and CRUD endpoints."></textarea>

      <!-- Options panel (hidden by default) -->
      <div class="gen-options-panel" id="genOptionsPanel">
        <div class="gen-options-row">
          <div class="gen-options-field">
            <label class="gen-options-label" for="genLangSelect">Language</label>
            <select id="genLangSelect" class="gen-select gen-lang-select" aria-label="Programming language">
              <optgroup label="General Purpose">
                <option value="python">Python</option>
                <option value="javascript">JavaScript</option>
                <option value="typescript">TypeScript</option>
                <option value="java">Java</option>
                <option value="c">C</option>
                <option value="c++">C++</option>
                <option value="csharp">C#</option>
                <option value="go">Go</option>
                <option value="rust">Rust</option>
                <option value="php">PHP</option>
                <option value="ruby">Ruby</option>
                <option value="bash">Bash</option>
                <option value="lua">Lua</option>
                <option value="perl">Perl</option>
                <option value="haskell">Haskell</option>
                <option value="scala">Scala</option>
                <option value="r">R</option>
              </optgroup>
              <optgroup label="Mobile Apps">
                <option value="swift">Swift</option>
                <option value="kotlin">Kotlin</option>
                <option value="dart">Dart</option>
              </optgroup>
              <optgroup label="Electrical &amp; Engineering">
                <option value="octave">Octave / MATLAB</option>
                <option value="fortran">Fortran</option>
              </optgroup>
              <optgroup label="Semiconductor &amp; Electronics">
                <option value="verilog">Verilog</option>
                <option value="vhdl">VHDL</option>
              </optgroup>
              <optgroup label="Design Automation / EDA">
                <option value="tcl">Tcl</option>
              </optgroup>
            </select>
          </div>
          <div class="gen-options-field" id="genModelField">
            <label class="gen-options-label" for="genModelSelect">AI Model</label>
            <select id="genModelSelect" class="gen-select gen-model-select" aria-label="AI model">
              <option value="">No AI providers configured</option>
            </select>
            <span class="gen-model-hint">
              <iconify-icon icon="lucide:info"></iconify-icon>
              ✦ = open-source / free tier
            </span>
          </div>
        </div>
      </div>

      <!-- Card footer -->
      <div class="gen-card-footer">
        <div class="gen-card-footer-left">
          <button class="gen-options-toggle" id="genOptionsToggle" type="button">
            <iconify-icon icon="lucide:settings-2"></iconify-icon>
            Options
          </button>
        </div>
        <button id="genSubmitBtn" class="gen-submit-btn" type="button">
          <iconify-icon icon="lucide:sparkles"></iconify-icon>
          Generate
        </button>
      </div>
    </div><!-- /gen-card -->

    <!-- Error -->
    <div class="gen-error" id="genError" role="alert">
      <iconify-icon icon="lucide:alert-circle"></iconify-icon>
      <span id="genErrorText"></span>
    </div>

    <!-- Result -->
    <div class="gen-result" id="genResult">
      <div class="gen-result-header">
        <span class="gen-result-title">Generated Code</span>
        <div class="gen-result-actions">
          <button id="genCopyBtn" class="gen-btn ghost">
            <iconify-icon icon="lucide:copy"></iconify-icon>
            Copy
          </button>
          <?php if ($_cf_user): ?>
          <button id="genGithubBtn" class="gen-btn ghost">
            <iconify-icon icon="mdi:github"></iconify-icon>
            Push to GitHub
          </button>
          <?php endif; ?>
          <button id="genOpenIdeBtn" class="gen-btn success">
            <iconify-icon icon="lucide:code-2"></iconify-icon>
            Open in IDE
          </button>
        </div>
      </div>
      <pre class="gen-code-output" id="genCodeOutput"></pre>
    </div>

    <!-- Quick-action chips -->
    <div class="gen-chips" role="list" aria-label="Quick-start templates">

      <div class="gen-chip" id="chip-python" role="listitem" tabindex="0"
           data-lang="python"
           data-prompt="Write a Python script that reads a CSV file and returns a list of dictionaries, one per row, using the header row as keys. Handle file-not-found errors gracefully.">
        <div class="gen-chip-icon">🐍</div>
        <span class="gen-chip-label">Python Script</span>
      </div>

      <div class="gen-chip" id="chip-restapi" role="listitem" tabindex="0"
           data-lang="javascript"
           data-prompt="Write an Express.js REST API server with GET, POST, PUT, and DELETE endpoints for a to-do list stored in memory. Include input validation and error handling.">
        <div class="gen-chip-icon">🌐</div>
        <span class="gen-chip-label">REST API</span>
      </div>

      <div class="gen-chip" id="chip-landing" role="listitem" tabindex="0"
           data-lang="javascript"
           data-prompt="Generate a modern landing page in HTML, CSS, and JavaScript with a hero section, features grid, pricing table, and contact form. Use a clean, professional design.">
        <div class="gen-chip-icon">🖥️</div>
        <span class="gen-chip-label">Landing Page</span>
      </div>

      <div class="gen-chip" id="chip-mobile" role="listitem" tabindex="0"
           data-lang="dart"
           data-prompt="Write a Flutter mobile app with a login screen, home dashboard, and a settings page. Use a Material Design theme with a bottom navigation bar.">
        <div class="gen-chip-icon">📱</div>
        <span class="gen-chip-label">Mobile App</span>
      </div>

      <div class="gen-chip" id="chip-bash" role="listitem" tabindex="0"
           data-lang="bash"
           data-prompt="Write a Bash script that monitors a directory for new files, logs each new file name with a timestamp to a log file, and sends an alert if the directory grows beyond 100 files.">
        <div class="gen-chip-icon">⚡</div>
        <span class="gen-chip-label">Bash Script</span>
      </div>

      <div class="gen-chip" role="listitem" tabindex="0"
           data-lang="rust"
           data-prompt="Write a Rust program that reads a text file, counts word frequencies case-insensitively, and prints the top 10 most frequent words with their counts.">
        <div class="gen-chip-icon">🦀</div>
        <span class="gen-chip-label">Rust Script</span>
      </div>

      <div class="gen-chip" role="listitem" tabindex="0"
           data-lang="typescript"
           data-prompt="Write a TypeScript generic Stack class with push, pop, peek, isEmpty, and size methods. Include proper type annotations and JSDoc comments.">
        <div class="gen-chip-icon">📦</div>
        <span class="gen-chip-label">TypeScript</span>
      </div>

    </div>

    <!-- Recents -->
    <?php if (!empty($_cf_recents)): ?>
    <div class="gen-recents">
      <div class="gen-recents-heading">Recents</div>
      <div class="gen-recents-grid">
        <?php foreach ($_cf_recents as $r): ?>
        <?php
          $lang    = htmlspecialchars($r['language'] ?? 'code', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
          $snippet = htmlspecialchars($r['prompt_snippet'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
          $ts      = isset($r['ts']) ? date('M j, g:iA', (int)$r['ts']) : '';
        ?>
        <div class="gen-recent-card"
             data-lang="<?= htmlspecialchars($r['language'] ?? 'python', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
             data-prompt="<?= htmlspecialchars($r['prompt_snippet'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
          <div class="gen-recent-card-top">
            <span class="gen-recent-lang-badge"><?= $lang ?></span>
            <?php if ($ts): ?><span class="gen-recent-time"><?= $ts ?></span><?php endif; ?>
          </div>
          <div class="gen-recent-snippet"><?= $snippet ?></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </main>

</div><!-- /gen-layout -->

<?php if ($_cf_user): ?>
<!-- ── Push to GitHub modal ────────────────────────────────── -->
<div class="gh-modal-backdrop" id="ghModalBackdrop" role="dialog" aria-modal="true" aria-labelledby="ghModalTitle">
  <div class="gh-modal">
    <button class="gh-modal-close" id="ghModalClose" aria-label="Close">
      <iconify-icon icon="lucide:x"></iconify-icon>
    </button>
    <h2 class="gh-modal-title" id="ghModalTitle">
      <iconify-icon icon="mdi:github" style="vertical-align:middle;margin-right:6px;"></iconify-icon>Push to GitHub
    </h2>
    <p class="gh-modal-subtitle">Save your generated code to a private GitHub repository.</p>

    <!-- Step 1: Connect GitHub -->
    <div id="ghStepConnect">
      <div class="gh-connect-section">
        <p>Connect your GitHub account to create private repositories and push your generated code directly from CodeFoundry.</p>
        <button id="ghConnectBtn" class="gen-btn success" style="margin:0 auto;">
          <iconify-icon icon="mdi:github"></iconify-icon>
          Connect GitHub Account
        </button>
      </div>
    </div>

    <!-- Step 2: Choose repo and push -->
    <div id="ghStepPush" style="display:none;">
      <div id="ghConnectedBadge" class="gh-connected-badge">
        <iconify-icon icon="lucide:check-circle-2"></iconify-icon>
        <span id="ghConnectedUser">Connected</span>
      </div>

      <div class="gh-radio-group">
        <label class="gh-radio-label">
          <input type="radio" name="ghRepoMode" value="existing" id="ghModeExisting" checked>
          Existing repository
        </label>
        <label class="gh-radio-label">
          <input type="radio" name="ghRepoMode" value="new" id="ghModeNew">
          New repository
        </label>
      </div>

      <!-- Existing repo -->
      <div id="ghExistingGroup" class="gh-form-group">
        <label class="gh-form-label" for="ghRepoSelect">Repository</label>
        <select id="ghRepoSelect" class="gh-form-input">
          <option value="">Loading repositories…</option>
        </select>
      </div>

      <!-- New repo -->
      <div id="ghNewGroup" style="display:none;">
        <div class="gh-form-group">
          <label class="gh-form-label" for="ghNewRepoName">New repository name</label>
          <input type="text" id="ghNewRepoName" class="gh-form-input" placeholder="my-project" maxlength="100">
        </div>
      </div>

      <!-- File path -->
      <div class="gh-form-group">
        <label class="gh-form-label" for="ghFilePath">File path in repository</label>
        <input type="text" id="ghFilePath" class="gh-form-input" placeholder="main.py" maxlength="255">
      </div>

      <div id="ghPushError" class="gh-push-error" role="alert">
        <iconify-icon icon="lucide:alert-circle"></iconify-icon>
        <span id="ghPushErrorText"></span>
      </div>
      <div id="ghPushSuccess" class="gh-success-msg">
        <iconify-icon icon="lucide:check-circle-2"></iconify-icon>
        <span id="ghPushSuccessText">Code pushed successfully! <a id="ghPushLink" href="#" target="_blank" rel="noopener">View on GitHub →</a></span>
      </div>

      <div class="gh-modal-actions">
        <button id="ghDisconnectBtn" class="gen-btn ghost" style="margin-right:auto;">
          <iconify-icon icon="lucide:unlink"></iconify-icon>
          Disconnect
        </button>
        <button id="ghCancelBtn" class="gen-btn ghost">Cancel</button>
        <button id="ghPushBtn" class="gen-btn success">
          <iconify-icon icon="lucide:upload-cloud"></iconify-icon>
          Push Code
        </button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php
$page_scripts = <<<'PAGEJS'
(function () {
  'use strict';

  /* ── Available AI providers ───────────────────────────── */
PAGEJS;
$page_scripts .= 'const CF_PROVIDERS = ' . json_encode($_cf_providers_js, JSON_UNESCAPED_UNICODE) . ";\n";
$page_scripts .= <<<'PAGEJS'

  /* ── Populate AI model selector ──────────────────────── */
  (function () {
    const sel = document.getElementById('genModelSelect');
    if (!sel || !CF_PROVIDERS.length) {
      const field = document.getElementById('genModelField');
      if (field) field.style.display = 'none';
      return;
    }
    sel.innerHTML = '';
    CF_PROVIDERS.forEach(function (p) {
      const group = document.createElement('optgroup');
      group.label = p.label + (p.opensource ? ' ✦' : '');
      p.models.forEach(function (m) {
        const opt = document.createElement('option');
        opt.value       = p.id + ':' + m.id;
        opt.textContent = m.label;
        group.appendChild(opt);
      });
      sel.appendChild(group);
    });
    const saved = localStorage.getItem('cf_ai_model');
    if (saved) {
      const exists = Array.from(sel.options).some(function (o) { return o.value === saved; });
      if (exists) sel.value = saved;
    }
    sel.addEventListener('change', function () {
      localStorage.setItem('cf_ai_model', sel.value);
    });
  })();

  function getAiSelection() {
    const sel = document.getElementById('genModelSelect');
    const val = sel ? sel.value : '';
    if (!val) return {};
    const sep = val.indexOf(':');
    return sep > 0
      ? { provider: val.slice(0, sep), model: val.slice(sep + 1) }
      : {};
  }

  const langSelect  = document.getElementById('genLangSelect');
  const promptTA    = document.getElementById('genPrompt');
  const submitBtn   = document.getElementById('genSubmitBtn');
  const resultEl    = document.getElementById('genResult');
  const codeOutput  = document.getElementById('genCodeOutput');
  const copyBtn     = document.getElementById('genCopyBtn');
  const openIdeBtn  = document.getElementById('genOpenIdeBtn');
  const errorEl     = document.getElementById('genError');
  const errorTextEl = document.getElementById('genErrorText');

  let lastGeneratedCode = '';

  /* ── Error helper ─────────────────────────────────────── */
  function showError(msg) {
    errorTextEl.textContent = msg;
    errorEl.classList.add('visible');
    resultEl.classList.remove('visible');
  }
  function clearError() {
    errorEl.classList.remove('visible');
  }

  /* ── Generate ─────────────────────────────────────────── */
  async function generate() {
    const prompt = promptTA.value.trim();
    if (!prompt) { promptTA.focus(); return; }

    clearError();
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Generating…';
    resultEl.classList.remove('visible');

    try {
      const res  = await fetch('/IDE/codegen.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(Object.assign({
          action:   'generate',
          prompt:   prompt,
          language: langSelect.value,
        }, getAiSelection())),
      });
      const data = await res.json();

      if (!res.ok) {
        if (data.error_code === 'subscription_required') {
          window.location.href = '/Pricing/';
          return;
        }
        showError(data.error || 'An error occurred. Please try again.');
        return;
      }

      lastGeneratedCode = data.code || '';
      codeOutput.textContent = lastGeneratedCode;
      resultEl.classList.add('visible');
      resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } catch (err) {
      showError('Network error: ' + err.message);
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon> Generate';
    }
  }

  submitBtn.addEventListener('click', generate);

  promptTA.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
      e.preventDefault();
      generate();
    }
  });

  /* ── Copy button ──────────────────────────────────────── */
  copyBtn.addEventListener('click', function () {
    if (!lastGeneratedCode) return;
    navigator.clipboard.writeText(lastGeneratedCode).then(function () {
      const orig = copyBtn.innerHTML;
      copyBtn.innerHTML = '<iconify-icon icon="lucide:check" aria-hidden="true"></iconify-icon> Copied!';
      setTimeout(function () { copyBtn.innerHTML = orig; }, 1800);
    }).catch(function () {
      // Fallback for older browsers
      const ta = document.createElement('textarea');
      ta.value = lastGeneratedCode;
      ta.style.position = 'fixed';
      ta.style.opacity  = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    });
  });

  /* ── Open in IDE ──────────────────────────────────────── */
  openIdeBtn.addEventListener('click', function () {
    if (!lastGeneratedCode) return;
    sessionStorage.setItem('cf_generated_code',     lastGeneratedCode);
    sessionStorage.setItem('cf_generated_language', langSelect.value);
    window.location.href = '/IDE/';
  });

  /* ── Options toggle ───────────────────────────────────── */
  var optionsToggle = document.getElementById('genOptionsToggle');
  var optionsPanel  = document.getElementById('genOptionsPanel');
  if (optionsToggle && optionsPanel) {
    optionsToggle.addEventListener('click', function () {
      var open = optionsPanel.classList.toggle('open');
      optionsToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  /* ── Chip / recent card click helper ──────────────────── */
  function applyChip(el) {
    var lang   = el.dataset.lang;
    var prompt = el.dataset.prompt;
    if (lang) langSelect.value = lang;
    if (prompt) {
      promptTA.value = prompt;
      promptTA.focus();
      promptTA.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  }

  /* ── Quick-action chips ────────────────────────────────── */
  document.querySelectorAll('.gen-chip').forEach(function (chip) {
    chip.addEventListener('click', function () { applyChip(chip); });
    chip.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); applyChip(chip); }
    });
  });

  /* ── Recent cards ─────────────────────────────────────── */
  document.querySelectorAll('.gen-recent-card').forEach(function (card) {
    card.addEventListener('click', function () { applyChip(card); });
  });

  /* ── Sidebar template shortcuts ───────────────────────── */
  var sbMap = {
    sbPython:  'chip-python',
    sbRestApi: 'chip-restapi',
    sbLanding: 'chip-landing',
    sbMobile:  'chip-mobile',
    sbBash:    'chip-bash',
  };
  Object.keys(sbMap).forEach(function (sbId) {
    var sbEl   = document.getElementById(sbId);
    var chipEl = document.getElementById(sbMap[sbId]);
    if (sbEl && chipEl) {
      sbEl.addEventListener('click', function (e) {
        e.preventDefault();
        applyChip(chipEl);
      });
    }
  });
})();
PAGEJS;

if ($_cf_user):
$page_scripts .= <<<'GHJS'

/* ── Push to GitHub ────────────────────────────────────────── */
(function () {
  'use strict';

  var backdrop      = document.getElementById('ghModalBackdrop');
  var stepConnect   = document.getElementById('ghStepConnect');
  var stepPush      = document.getElementById('ghStepPush');
  var ghConnectBtn  = document.getElementById('ghConnectBtn');
  var ghCancelBtn   = document.getElementById('ghCancelBtn');
  var ghCloseBtn    = document.getElementById('ghModalClose');
  var ghPushBtn     = document.getElementById('ghPushBtn');
  var ghDisconnect  = document.getElementById('ghDisconnectBtn');
  var ghConnBadge   = document.getElementById('ghConnectedBadge');
  var ghConnUser    = document.getElementById('ghConnectedUser');
  var ghRepoSelect  = document.getElementById('ghRepoSelect');
  var ghNewGroup    = document.getElementById('ghNewGroup');
  var ghExistGroup  = document.getElementById('ghExistingGroup');
  var ghNewName     = document.getElementById('ghNewRepoName');
  var ghFilePath    = document.getElementById('ghFilePath');
  var ghPushError   = document.getElementById('ghPushError');
  var ghPushErrTxt  = document.getElementById('ghPushErrorText');
  var ghPushSuccess = document.getElementById('ghPushSuccess');
  var ghPushLink    = document.getElementById('ghPushLink');
  var ghGithubBtn   = document.getElementById('genGithubBtn');

  if (!backdrop || !ghGithubBtn) return;

  var ghConnected = false;

  /* ── helpers ─────────────────────────────────────────────── */
  function ghApi(payload) {
    return fetch('/Generate/github_push.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(payload),
    }).then(function (r) { return r.json(); });
  }

  function showPushError(msg) {
    ghPushErrTxt.textContent = msg;
    ghPushError.classList.add('visible');
    ghPushSuccess.classList.remove('visible');
  }
  function clearPushMessages() {
    ghPushError.classList.remove('visible');
    ghPushSuccess.classList.remove('visible');
  }

  function inferFilename() {
    var lang = document.getElementById('genLangSelect');
    if (!lang) return 'generated_code.txt';
    var ext = {
      python:'py', javascript:'js', typescript:'ts', java:'java', c:'c',
      'c++':'cpp', csharp:'cs', go:'go', rust:'rs', php:'php', ruby:'rb',
      bash:'sh', lua:'lua', perl:'pl', haskell:'hs', scala:'scala', r:'r',
      swift:'swift', kotlin:'kt', dart:'dart', octave:'m', fortran:'f90',
      verilog:'v', vhdl:'vhd', tcl:'tcl',
    };
    return 'generated_code.' + (ext[lang.value] || 'txt');
  }

  /* ── Load repos into select ──────────────────────────────── */
  function loadRepos() {
    ghRepoSelect.innerHTML = '<option value="">Loading…</option>';
    ghApi({ action: 'repos', per_page: 100 }).then(function (data) {
      if (data.error) {
        ghRepoSelect.innerHTML = '<option value="">Error loading repos</option>';
        return;
      }
      var repos = data.repos || [];
      ghRepoSelect.innerHTML = '';
      if (repos.length === 0) {
        var opt = document.createElement('option');
        opt.value = '';
        opt.textContent = 'No repositories found – create a new one';
        ghRepoSelect.appendChild(opt);
        return;
      }
      repos.forEach(function (r) {
        var opt = document.createElement('option');
        opt.value       = r.full_name;
        opt.textContent = r.full_name + (r.private ? ' 🔒' : '');
        ghRepoSelect.appendChild(opt);
      });
    }).catch(function () {
      ghRepoSelect.innerHTML = '<option value="">Error loading repos</option>';
    });
  }

  /* ── Open modal ──────────────────────────────────────────── */
  function openModal() {
    clearPushMessages();
    ghFilePath.value = inferFilename();
    backdrop.classList.add('open');

    // Check connection status
    ghApi({ action: 'status' }).then(function (data) {
      ghConnected = !!data.connected;
      if (ghConnected) {
        stepConnect.style.display = 'none';
        stepPush.style.display    = '';
        ghConnUser.textContent    = 'Connected as ' + (data.github_user || 'GitHub');
        loadRepos();
      } else {
        stepConnect.style.display = '';
        stepPush.style.display    = 'none';
      }
    });
  }

  function closeModal() {
    backdrop.classList.remove('open');
  }

  ghGithubBtn.addEventListener('click', openModal);
  ghCloseBtn.addEventListener('click', closeModal);
  ghCancelBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', function (e) {
    if (e.target === backdrop) closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && backdrop.classList.contains('open')) closeModal();
  });

  /* ── Connect GitHub (popup) ──────────────────────────────── */
  ghConnectBtn.addEventListener('click', function () {
    var w = window.open('/Generate/github_oauth.php', 'gh_connect',
      'width=700,height=600,scrollbars=yes');
    var handler = function (evt) {
      if (evt.data && evt.data.type === 'gh_connect') {
        window.removeEventListener('message', handler);
        if (evt.data.ok) {
          ghConnected = true;
          stepConnect.style.display = 'none';
          stepPush.style.display    = '';
          ghConnUser.textContent    = 'Connected as ' + (evt.data.ghUser || 'GitHub');
          clearPushMessages();
          loadRepos();
        } else {
          alert('GitHub connection failed: ' + (evt.data.error || 'Unknown error'));
        }
        if (w && !w.closed) w.close();
      }
    };
    window.addEventListener('message', handler);
  });

  /* ── Disconnect ──────────────────────────────────────────── */
  ghDisconnect.addEventListener('click', function () {
    if (!confirm('Disconnect your GitHub account from CodeFoundry?')) return;
    ghApi({ action: 'disconnect' }).finally(function () {
      ghConnected = false;
      stepPush.style.display    = 'none';
      stepConnect.style.display = '';
      clearPushMessages();
    });
  });

  /* ── Repo mode toggle ────────────────────────────────────── */
  document.querySelectorAll('input[name="ghRepoMode"]').forEach(function (radio) {
    radio.addEventListener('change', function () {
      var isNew = document.getElementById('ghModeNew').checked;
      ghNewGroup.style.display   = isNew ? '' : 'none';
      ghExistGroup.style.display = isNew ? 'none' : '';
      clearPushMessages();
    });
  });

  /* ── Push ────────────────────────────────────────────────── */
  ghPushBtn.addEventListener('click', function () {
    clearPushMessages();
    var code = (document.getElementById('genCodeOutput') || {}).textContent || '';
    if (!code) { showPushError('No generated code to push.'); return; }

    var filePath = ghFilePath.value.trim();
    if (!filePath) { showPushError('Please enter a file path.'); ghFilePath.focus(); return; }

    var isNew = document.getElementById('ghModeNew').checked;

    function doPush(repoFullName) {
      ghPushBtn.disabled = true;
      ghPushBtn.innerHTML = '<span class="spinner"></span> Pushing…';
      ghApi({
        action:  'push',
        repo:    repoFullName,
        path:    filePath,
        content: code,
      }).then(function (data) {
        if (data.error) { showPushError(data.error); return; }
        ghPushLink.href = data.html_url || data.url || '#';
        ghPushSuccess.classList.add('visible');
      }).catch(function (err) {
        showPushError('Network error: ' + err.message);
      }).finally(function () {
        ghPushBtn.disabled = false;
        ghPushBtn.innerHTML = '<iconify-icon icon="lucide:upload-cloud"></iconify-icon> Push Code';
      });
    }

    if (isNew) {
      var newName = ghNewName.value.trim();
      if (!newName) { showPushError('Please enter a repository name.'); ghNewName.focus(); return; }
      ghPushBtn.disabled = true;
      ghPushBtn.innerHTML = '<span class="spinner"></span> Creating repo…';
      ghApi({ action: 'create', name: newName }).then(function (data) {
        if (data.error) { showPushError(data.error); ghPushBtn.disabled = false; ghPushBtn.innerHTML = '<iconify-icon icon="lucide:upload-cloud"></iconify-icon> Push Code'; return; }
        doPush(data.full_name);
      }).catch(function (err) {
        showPushError('Network error: ' + err.message);
        ghPushBtn.disabled = false;
        ghPushBtn.innerHTML = '<iconify-icon icon="lucide:upload-cloud"></iconify-icon> Push Code';
      });
    } else {
      var selected = ghRepoSelect.value;
      if (!selected) { showPushError('Please select a repository.'); return; }
      doPush(selected);
    }
  });
})();
GHJS;
endif;

require_once dirname(__DIR__) . '/includes/footer.php';
?>
