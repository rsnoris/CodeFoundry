<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/auth.php';

if (!CF_IDE_VSCODE_ENABLED || CF_IDE_VSCODE_BASE_URL === '') {
    header('Location: /IDE/?mode=classic');
    exit;
}

cf_require_login();

$page_title  = 'CodeFoundry IDE – Hosted VS Code';
$active_page = 'ide';
$page_styles = <<<'PAGECSS'
.vscode-shell {
  height: calc(100vh - var(--header-height));
  display: flex;
  flex-direction: column;
  background: #0b1220;
}
.vscode-topbar {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 14px;
  border-bottom: 1px solid rgba(255,255,255,.12);
  color: #dbe6ff;
  background: #101b30;
}
.vscode-title { font-weight: 800; font-size: 13px; letter-spacing: .03em; text-transform: uppercase; }
.vscode-pill {
  font-size: 11px;
  border: 1px solid rgba(255,255,255,.2);
  color: #9fc4ff;
  padding: 2px 8px;
  border-radius: 999px;
}
.vscode-actions { margin-left: auto; display: flex; gap: 8px; }
.vscode-btn {
  display: inline-flex; align-items: center; gap: 6px;
  border: 1px solid rgba(255,255,255,.2); color: #dbe6ff;
  border-radius: 8px; padding: 6px 10px; text-decoration: none; font-size: 12px;
}
.vscode-btn:hover { border-color: #60a5fa; color: #fff; }
.vscode-frame-wrap { position: relative; flex: 1; min-height: 0; }
.vscode-frame {
  width: 100%; height: 100%; border: none; background: #0d1117;
}
.vscode-loading,
.vscode-error {
  position: absolute; inset: 0;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 12px; color: #dbe6ff; text-align: center; padding: 24px;
}
.vscode-error { background: #1f2937; }
.cap-list {
  margin: 0; padding: 0; list-style: none; width: min(860px, 100%);
  max-height: 210px; overflow: auto; border: 1px solid rgba(255,255,255,.14); border-radius: 12px;
}
.cap-list li {
  display: flex; justify-content: space-between; gap: 12px;
  padding: 9px 12px; border-bottom: 1px solid rgba(255,255,255,.08);
  font-size: 13px;
}
.cap-list li:last-child { border-bottom: none; }
.cap-status { text-transform: uppercase; font-size: 10px; letter-spacing: .08em; color: #8fb8ff; }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>
<link rel="manifest" href="/IDE/manifest.webmanifest">
<meta name="theme-color" content="#101b30">

<div class="vscode-shell">
  <div class="vscode-topbar">
    <div class="vscode-title">Hosted VS Code</div>
    <span class="vscode-pill" id="workspaceBadge">Workspace</span>
    <div class="vscode-actions">
      <a class="vscode-btn" href="/IDE/?mode=classic">Open Classic IDE</a>
      <button class="vscode-btn" id="reloadBtn" type="button">Reload</button>
    </div>
  </div>
  <div class="vscode-frame-wrap">
    <div class="vscode-loading" id="loadingState">
      <div>Launching your persistent IDE workspace…</div>
    </div>
    <div class="vscode-error" id="errorState" style="display:none;">
      <h3 style="margin:0;">Unable to launch hosted IDE</h3>
      <p id="errorText" style="margin:0; max-width:760px;"></p>
      <ul class="cap-list" id="capList"></ul>
      <div><a class="vscode-btn" href="/IDE/?mode=classic">Continue in Classic IDE</a></div>
    </div>
    <iframe id="vscodeFrame" class="vscode-frame" title="Hosted VS Code IDE" loading="eager" style="display:none;"></iframe>
  </div>
</div>

<script>
'use strict';

(function registerSw() {
  if (!('serviceWorker' in navigator)) return;
  navigator.serviceWorker.register('/IDE/sw.js', { scope: '/IDE/' }).catch(function (err) {
    console.warn('Service worker registration failed:', err);
  });
})();

const frame = document.getElementById('vscodeFrame');
const loadingState = document.getElementById('loadingState');
const errorState = document.getElementById('errorState');
const errorText = document.getElementById('errorText');
const capList = document.getElementById('capList');
const workspaceBadge = document.getElementById('workspaceBadge');
const reloadBtn = document.getElementById('reloadBtn');

reloadBtn.addEventListener('click', function () {
  window.location.reload();
});

function showError(message, capabilities) {
  loadingState.style.display = 'none';
  frame.style.display = 'none';
  errorState.style.display = 'flex';
  errorText.textContent = message || 'Unknown IDE bootstrap error.';
  capList.innerHTML = '';
  (capabilities || []).forEach(function (cap) {
    const li = document.createElement('li');
    const label = document.createElement('span');
    const status = document.createElement('span');
    label.textContent = cap.label || cap.id || 'Capability';
    status.className = 'cap-status';
    status.textContent = (cap.status || 'planned');
    li.appendChild(label);
    li.appendChild(status);
    capList.appendChild(li);
  });
}

fetch('/IDE/vscode-bootstrap.php', { credentials: 'same-origin' })
  .then(function (r) {
    return r.json().then(function (body) {
      return { ok: r.ok, body: body };
    });
  })
  .then(function (res) {
    if (!res.ok || !res.body || !res.body.launch_url) {
      const msg = (res.body && res.body.error) ? res.body.error : 'Bootstrap endpoint did not return a launch URL.';
      showError(msg, (res.body && res.body.capabilities) || []);
      return;
    }
    workspaceBadge.textContent = 'Workspace: ' + ((res.body.workspace && res.body.workspace.name) || 'default');
    frame.src = res.body.launch_url;
    frame.style.display = 'block';
    loadingState.style.display = 'none';
  })
  .catch(function () {
    showError('Network error while contacting IDE bootstrap endpoint.', []);
  });
</script>
<?php
$page_scripts = '';
require_once dirname(__DIR__) . '/includes/footer.php';
?>
