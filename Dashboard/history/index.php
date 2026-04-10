<?php
declare(strict_types=1);
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/UserStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$username     = $user_session['username'];

// Handle project delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['action'] ?? '') === 'delete_project') {
    $project_id = trim($_POST['id'] ?? $_GET['id'] ?? '');
    if ($project_id !== '') {
        UserStore::deleteProject($project_id, $username);
    }
    header('Location: /Dashboard/history/');
    exit;
}

$history  = UserStore::tokenHistoryForUser($username, 100);
$projects = UserStore::projectsForUser($username);

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
  @media (max-width: 700px) {
    .dash-layout { flex-direction: column; padding: 0; }
    .dash-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); padding: 16px 0; display: flex; overflow-x: auto; }
    .dash-sidebar-title { display: none; }
    .dash-main { padding: 24px 16px 48px; }
    .data-table th, .data-table td { padding: 10px 10px; }
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
              </tr>
            </thead>
            <tbody>
              <?php foreach ($history as $entry): ?>
                <tr>
                  <td style="white-space:nowrap"><?= cf_e(date('M j, Y g:i a', strtotime($entry['created_at'] ?? 'now'))) ?></td>
                  <td style="color:var(--text)"><?= cf_e(ucfirst($entry['action'] ?? '')) ?></td>
                  <td><span class="lang-badge"><?= cf_e($entry['language'] ?? '') ?></span></td>
                  <td style="color:var(--text);font-weight:600"><?= number_format((int)($entry['tokens_used'] ?? 0)) ?></td>
                  <td class="prompt-snippet"><?php
                    $snippet = $entry['prompt_snippet'] ?? '';
                    echo cf_e(mb_strlen($snippet) > 60 ? mb_substr($snippet, 0, 60) . '…' : $snippet);
                  ?></td>
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

<?php require_once dirname(dirname(__DIR__)) . '/includes/footer.php'; ?>
