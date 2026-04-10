<?php
declare(strict_types=1);
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/UserStore.php';
require_once dirname(dirname(__DIR__)) . '/lib/ChatStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$username     = $user_session['username'];
$unread_chat  = ChatStore::totalUnreadForUser($username);

// Handle project delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'delete_project') {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'])) {
        http_response_code(403);
        exit('Forbidden: invalid CSRF token.');
    }
    $project_id = trim($_POST['id'] ?? '');
    if ($project_id !== '') {
        UserStore::deleteProject($project_id, $username);
    }
    header('Location: /Dashboard/history/');
    exit;
}

$history  = UserStore::tokenHistoryForUser($username, 100);
$projects = UserStore::projectsForUser($username);

// Ensure CSRF token exists (session already started via cf_require_login)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$dash_active = 'history';
$page_title  = 'History – CodeFoundry';
$active_page = '';
$page_styles = <<<'CSS'
  .dash-layout {
    display: flex;
    min-height: calc(100vh - var(--header-height));
    max-width: var(--maxwidth);
    margin: 0 auto;
    padding: 0 20px;
  }
  .dash-sidebar {
    width: 240px;
    flex-shrink: 0;
    padding: 32px 0;
    border-right: 1px solid var(--border-color);
  }
  .dash-sidebar-title {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-subtle);
    padding: 0 20px 12px;
  }
  .dash-nav-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 20px; color: var(--text-muted); font-size: 14px; font-weight: 500;
    border-radius: 8px; margin: 1px 8px; transition: background .15s, color .15s;
  }
  .dash-nav-item:hover { background: var(--navy-3); color: var(--text); }
  .dash-nav-item.active { background: rgba(24,179,255,.12); color: var(--primary); }
  .dash-nav-item iconify-icon { font-size: 17px; flex-shrink: 0; }
  .dash-main { flex: 1; padding: 36px 36px 60px; min-width: 0; }
  .dash-page-header { margin-bottom: 28px; }
  .dash-page-header h1 { font-size: 26px; font-weight: 800; margin: 0 0 4px; }
  .dash-page-header p { color: var(--text-muted); margin: 0; font-size: 14px; }
  .dash-section {
    background: var(--navy); border: 1px solid var(--border-color);
    border-radius: var(--card-radius); margin-bottom: 28px;
  }
  .dash-section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px; border-bottom: 1px solid var(--border-color);
  }
  .dash-section-header h2 { font-size: 15px; font-weight: 700; margin: 0; }
  .dash-section-body { padding: 0; }
  .data-table {
    width: 100%; border-collapse: collapse; font-size: 13px;
  }
  .data-table th {
    text-align: left; padding: 12px 16px;
    color: var(--text-subtle); font-weight: 600; font-size: 11px;
    text-transform: uppercase; letter-spacing: .06em;
    border-bottom: 1px solid var(--border-color);
    background: var(--navy-3);
  }
  .data-table td {
    padding: 12px 16px; border-bottom: 1px solid rgba(26,41,66,.5);
    color: var(--text-muted); vertical-align: middle;
  }
  .data-table tr:last-child td { border-bottom: none; }
  .data-table tr:hover td { background: rgba(255,255,255,.02); }
  .lang-badge {
    display: inline-block; padding: 2px 8px;
    background: rgba(24,179,255,.1); border: 1px solid rgba(24,179,255,.2);
    color: var(--primary); border-radius: 4px; font-size: 11px; font-weight: 600;
  }
  .prompt-snippet { color: var(--text-subtle); font-style: italic; }
  .empty-state {
    text-align: center; padding: 50px 20px; color: var(--text-subtle);
  }
  .empty-state iconify-icon { font-size: 40px; margin-bottom: 12px; display: block; color: var(--border-color); }
  .empty-state p { margin: 0; font-size: 14px; }
  .btn-delete {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; font-size: 12px; font-weight: 600;
    background: rgba(255,72,72,.1); color: #ff7373;
    border: 1px solid rgba(255,72,72,.25); border-radius: 6px;
    cursor: pointer; transition: background .15s;
  }
  .btn-delete:hover { background: rgba(255,72,72,.2); }
  .btn-view {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; font-size: 12px; font-weight: 600;
    background: rgba(24,179,255,.1); color: var(--primary);
    border: 1px solid rgba(24,179,255,.25); border-radius: 6px;
    cursor: pointer; transition: background .15s;
  }
  .btn-view:hover { background: rgba(24,179,255,.2); }
  /* ── Code/Preview modal ────────────────────────────────── */
  .ch-overlay {
    display: none; position: fixed; inset: 0; z-index: 9000;
    background: rgba(0,0,0,.65); backdrop-filter: blur(3px);
    align-items: center; justify-content: center;
  }
  .ch-overlay.open { display: flex; }
  .ch-modal {
    background: var(--navy);
    border: 1px solid var(--border-color);
    border-radius: 14px;
    width: min(960px, 96vw);
    height: min(680px, 90vh);
    display: flex; flex-direction: column;
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(0,0,0,.6);
  }
  .ch-modal-header {
    display: flex; align-items: center; gap: 10px;
    padding: 16px 20px; border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
  }
  .ch-modal-header h3 { margin: 0; font-size: 15px; font-weight: 700; flex: 1; }
  .ch-modal-tabs {
    display: flex; gap: 4px;
  }
  .ch-tab {
    padding: 6px 14px; font-size: 13px; font-weight: 600;
    border-radius: 6px; border: 1px solid var(--border-color);
    background: transparent; color: var(--text-muted);
    cursor: pointer; transition: background .15s, color .15s;
  }
  .ch-tab.active { background: rgba(24,179,255,.15); color: var(--primary); border-color: rgba(24,179,255,.3); }
  .ch-tab:hover:not(.active) { background: var(--navy-3); color: var(--text); }
  .ch-close {
    padding: 6px 8px; background: transparent; border: none;
    color: var(--text-muted); font-size: 20px; cursor: pointer; line-height: 1;
    border-radius: 6px; transition: background .15s;
  }
  .ch-close:hover { background: var(--navy-3); color: var(--text); }
  .ch-modal-body { flex: 1; overflow: hidden; position: relative; }
  .ch-panel { display: none; height: 100%; }
  .ch-panel.active { display: block; }
  /* Code view panel */
  .ch-code-wrap {
    height: 100%; overflow: auto; padding: 20px;
  }
  .ch-code-wrap pre {
    margin: 0; font-family: 'Fira Code', 'Cascadia Code', 'Consolas', monospace;
    font-size: 13px; line-height: 1.6; color: #e2e8f0;
    white-space: pre-wrap; overflow-wrap: break-word; word-break: break-word;
  }
  .ch-no-code {
    display: flex; align-items: center; justify-content: center;
    height: 100%; color: var(--text-subtle); font-size: 14px; font-style: italic;
  }
  /* Preview panel */
  .ch-preview-frame {
    width: 100%; height: 100%; border: none;
    background: #fff;
  }
  .ch-copy-bar {
    display: flex; justify-content: flex-end; padding: 8px 20px 0;
    flex-shrink: 0; border-top: 1px solid var(--border-color);
  }
  .ch-copy-btn {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 13px; font-size: 12px; font-weight: 600;
    background: var(--navy-3); color: var(--text-muted);
    border: 1px solid var(--border-color); border-radius: 6px;
    cursor: pointer; transition: background .15s;
  }
  .ch-copy-btn:hover { background: rgba(24,179,255,.1); color: var(--primary); }
  .nav-badge {
    margin-left: auto;
    background: var(--primary);
    color: var(--navy);
    font-size: 10px;
    font-weight: 800;
    border-radius: 100px;
    padding: 1px 6px;
    min-width: 18px;
    text-align: center;
    line-height: 16px;
  }
  @media (max-width: 700px) {
    .dash-layout { flex-direction: column; padding: 0; }
    .dash-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); padding: 16px 0; display: flex; overflow-x: auto; }
    .dash-sidebar-title { display: none; }
    .dash-main { padding: 24px 16px 48px; }
    .data-table th, .data-table td { padding: 10px 10px; }
    .ch-modal { max-height: 95vh; }
  }
CSS;

require_once dirname(dirname(__DIR__)) . '/includes/header.php';
?>

<div class="dash-layout">
  <aside class="dash-sidebar">
    <div class="dash-sidebar-title">Navigation</div>
    <a href="/Dashboard/" class="dash-nav-item <?= $dash_active === 'dashboard' ? 'active' : '' ?>">
      <iconify-icon icon="lucide:layout-dashboard"></iconify-icon> Dashboard
    </a>
    <a href="/Dashboard/resources/" class="dash-nav-item <?= $dash_active === 'resources' ? 'active' : '' ?>">
      <iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Resources
    </a>
    <a href="/Dashboard/history/" class="dash-nav-item <?= $dash_active === 'history' ? 'active' : '' ?>">
      <iconify-icon icon="lucide:history"></iconify-icon> History
    </a>
    <a href="/Dashboard/account/" class="dash-nav-item <?= $dash_active === 'account' ? 'active' : '' ?>">
      <iconify-icon icon="lucide:user-cog"></iconify-icon> Account
    </a>
    <a href="/Dashboard/payments/" class="dash-nav-item <?= $dash_active === 'payments' ? 'active' : '' ?>">
      <iconify-icon icon="lucide:credit-card"></iconify-icon> Payments
    </a>
    <a href="/Dashboard/chat/" class="dash-nav-item <?= $dash_active === 'chat' ? 'active' : '' ?>" id="sidebarChatLink">
      <iconify-icon icon="lucide:message-circle"></iconify-icon>
      Support Chat
      <?php if ($unread_chat > 0): ?>
        <span class="nav-badge" id="sidebarBadge"><?= (int)$unread_chat ?></span>
      <?php else: ?>
        <span class="nav-badge" id="sidebarBadge" style="display:none">0</span>
      <?php endif; ?>
    </a>
  </aside>

  <main class="dash-main">
    <div class="dash-page-header">
      <h1>History</h1>
      <p>Your CodeGen activity and saved projects.</p>
    </div>

    <!-- CodeGen History -->
    <div class="dash-section">
      <div class="dash-section-header">
        <h2>CodeGen History</h2>
        <span style="font-size:13px;color:var(--text-muted)"><?= count($history) ?> entries</span>
      </div>
      <div class="dash-section-body">
        <?php if (empty($history)): ?>
          <div class="empty-state">
            <iconify-icon icon="lucide:terminal"></iconify-icon>
            <p>No CodeGen history yet. <a href="/IDE/" style="color:var(--primary)">Open the IDE</a> to start generating code.</p>
          </div>
        <?php else: ?>
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Action</th>
                <th>Language</th>
                <th>Tokens</th>
                <th>Prompt</th>
                <th>View</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($history as $idx => $entry): ?>
                <?php
                  $lang      = $entry['language'] ?? '';
                  $code      = $entry['code_output'] ?? '';
                  $isWeb     = in_array(strtolower($lang), ['html', 'css', 'javascript', 'js'], true);
                  $hasCode   = $code !== '';
                ?>
                <tr>
                  <td style="white-space:nowrap"><?= cf_e(date('M j, Y g:i a', strtotime($entry['created_at'] ?? 'now'))) ?></td>
                  <td style="color:var(--text)"><?= cf_e(ucfirst($entry['action'] ?? '')) ?></td>
                  <td><span class="lang-badge"><?= cf_e($lang) ?></span></td>
                  <td style="color:var(--text);font-weight:600"><?= number_format((int)($entry['tokens_used'] ?? 0)) ?></td>
                  <td class="prompt-snippet"><?php
                    $snippet = $entry['prompt_snippet'] ?? '';
                    echo cf_e(mb_strlen($snippet) > 60 ? mb_substr($snippet, 0, 60) . '…' : $snippet);
                  ?></td>
                  <td>
                    <?php if ($hasCode): ?>
                      <button class="btn-view ch-view-btn"
                              data-lang="<?= cf_e($lang) ?>"
                              data-isweb="<?= $isWeb ? '1' : '0' ?>"
                              data-code="<?= htmlspecialchars($code, ENT_QUOTES, 'UTF-8') ?>">
                        <iconify-icon icon="lucide:eye"></iconify-icon> View
                      </button>
                    <?php else: ?>
                      <span style="color:var(--text-subtle);font-size:12px">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <!-- Projects -->
    <div class="dash-section">
      <div class="dash-section-header">
        <h2>Projects</h2>
        <span style="font-size:13px;color:var(--text-muted)"><?= count($projects) ?> projects</span>
      </div>
      <div class="dash-section-body">
        <?php if (empty($projects)): ?>
          <div class="empty-state">
            <iconify-icon icon="lucide:folder-open"></iconify-icon>
            <p>No projects saved yet. Save a project from the IDE to see it here.</p>
          </div>
        <?php else: ?>
          <table class="data-table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Language</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($projects as $project): ?>
                <tr>
                  <td style="color:var(--text);font-weight:500"><?= cf_e($project['title'] ?? 'Untitled') ?></td>
                  <td><span class="lang-badge"><?= cf_e($project['language'] ?? '') ?></span></td>
                  <td style="white-space:nowrap"><?= cf_e(date('M j, Y', strtotime($project['created_at'] ?? 'now'))) ?></td>
                  <td>
                    <form method="POST" action="/Dashboard/history/?action=delete_project" style="display:inline" onsubmit="return confirm('Delete this project?')">
                      <input type="hidden" name="csrf_token" value="<?= cf_e($csrf_token) ?>">
                      <input type="hidden" name="id" value="<?= cf_e($project['id'] ?? '') ?>">
                      <button type="submit" class="btn-delete">
                        <iconify-icon icon="lucide:trash-2"></iconify-icon> Delete
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>
  </main>
</div>

<!-- ── Code / Preview modal ───────────────────────────────────────────── -->
<div id="chOverlay" class="ch-overlay" role="dialog" aria-modal="true" aria-labelledby="chModalTitle">
  <div class="ch-modal">
    <div class="ch-modal-header">
      <h3 id="chModalTitle">Code Output</h3>
      <div class="ch-modal-tabs" id="chTabs"></div>
      <button class="ch-close" onclick="closeCodeModal()" aria-label="Close">&times;</button>
    </div>
    <div class="ch-modal-body" id="chBody">
      <!-- panels injected by JS -->
    </div>
    <div class="ch-copy-bar">
      <button class="ch-copy-btn" id="chCopyBtn" onclick="copyModalCode()">
        <iconify-icon icon="lucide:copy"></iconify-icon> Copy code
      </button>
    </div>
  </div>
</div>

<script>
(function () {
  'use strict';

  /* Languages whose output can be previewed live in an iframe */
  const WEB_LANGS = new Set(['html', 'css', 'javascript', 'js']);

  let _currentCode = '';

  function wrapForPreview(lang, code) {
    lang = (lang || '').toLowerCase();
    if (lang === 'css') {
      return `<!DOCTYPE html><html><head><style>${code}</style></head><body>
        <p style="font-family:sans-serif;color:#666;font-size:13px;padding:16px">
          (CSS preview — no HTML body provided)</p></body></html>`;
    }
    if (lang === 'javascript' || lang === 'js') {
      return `<!DOCTYPE html><html><head></head><body>
        <script>${code}<\/script></body></html>`;
    }
    /* html or default */
    return code;
  }

  window.openCodeModal = function (entry) {
    _currentCode = entry.code || '';
    const overlay = document.getElementById('chOverlay');
    const title   = document.getElementById('chModalTitle');
    const body    = document.getElementById('chBody');
    const tabs    = document.getElementById('chTabs');

    title.textContent = (entry.lang ? entry.lang.toUpperCase() + ' ' : '') + 'Code Output';

    /* Build tab buttons */
    tabs.innerHTML = '';
    const codeTab = document.createElement('button');
    codeTab.className = 'ch-tab active';
    codeTab.textContent = 'Code';
    codeTab.setAttribute('data-panel', 'code');
    tabs.appendChild(codeTab);

    if (entry.isWeb && _currentCode) {
      const prevTab = document.createElement('button');
      prevTab.className = 'ch-tab';
      prevTab.textContent = '▶ Preview';
      prevTab.setAttribute('data-panel', 'preview');
      tabs.appendChild(prevTab);
    }

    tabs.addEventListener('click', function (e) {
      const btn = e.target.closest('.ch-tab');
      if (!btn) return;
      Array.from(tabs.querySelectorAll('.ch-tab')).forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      showPanel(btn.getAttribute('data-panel'));
    });

    /* Build panels */
    body.innerHTML = '';

    /* Code panel */
    const codePanel = document.createElement('div');
    codePanel.className = 'ch-panel active';
    codePanel.id = 'chPanelCode';
    const wrap = document.createElement('div');
    wrap.className = 'ch-code-wrap';
    if (_currentCode) {
      const pre = document.createElement('pre');
      pre.textContent = _currentCode;
      wrap.appendChild(pre);
    } else {
      const msg = document.createElement('div');
      msg.className = 'ch-no-code';
      msg.textContent = 'No code output was saved for this entry.';
      wrap.appendChild(msg);
    }
    codePanel.appendChild(wrap);
    body.appendChild(codePanel);

    /* Preview panel (web only) */
    if (entry.isWeb && _currentCode) {
      const prevPanel = document.createElement('div');
      prevPanel.className = 'ch-panel';
      prevPanel.id = 'chPanelPreview';
      prevPanel.style.height = '100%';

      const frame = document.createElement('iframe');
      frame.className = 'ch-preview-frame';
      frame.setAttribute('sandbox', 'allow-scripts');
      frame.setAttribute('title', 'UI Preview');
      frame.srcdoc = wrapForPreview(entry.lang, _currentCode);
      prevPanel.appendChild(frame);
      body.appendChild(prevPanel);
    }

    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  };

  function showPanel(name) {
    const code = document.getElementById('chPanelCode');
    const prev = document.getElementById('chPanelPreview');
    if (code) code.classList.toggle('active', name === 'code');
    if (prev) prev.classList.toggle('active', name === 'preview');
  }

  window.closeCodeModal = function () {
    document.getElementById('chOverlay').classList.remove('open');
    document.body.style.overflow = '';
    /* clear iframe src to stop any running scripts */
    const frame = document.querySelector('#chPanelPreview iframe');
    if (frame) { frame.srcdoc = ''; }
  };

  window.copyModalCode = function () {
    if (!_currentCode) return;
    navigator.clipboard.writeText(_currentCode).then(function () {
      const btn = document.getElementById('chCopyBtn');
      const orig = btn.innerHTML;
      btn.innerHTML = '<iconify-icon icon="lucide:check"></iconify-icon> Copied!';
      setTimeout(function () { btn.innerHTML = orig; }, 1800);
    });
  };

  /* Wire up all View buttons via event delegation */
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.ch-view-btn');
    if (!btn) return;
    window.openCodeModal({
      lang:  btn.dataset.lang  || '',
      code:  btn.dataset.code  || '',
      isWeb: btn.dataset.isweb === '1',
    });
  });

  /* Close on backdrop click */
  document.getElementById('chOverlay').addEventListener('click', function (e) {
    if (e.target === this) { window.closeCodeModal(); }
  });

  /* Close on Escape */
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') { window.closeCodeModal(); }
  });
}());
</script>

<?php require_once dirname(dirname(__DIR__)) . '/includes/footer.php'; ?>
