<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';
require_once dirname(__DIR__) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$user         = UserStore::findUser($user_session['username']) ?? $user_session;
$plan_key     = $user['plan'] ?? 'free';
$plan         = CF_PLANS[$plan_key] ?? CF_PLANS['free'];
$tokens_used  = (int)($user['tokens_used'] ?? 0);
$tokens_limit = (int)$plan['tokens_limit'];
$token_pct    = $tokens_limit > 0 ? min(100, round($tokens_used / $tokens_limit * 100, 1)) : 0;

$history      = UserStore::tokenHistoryForUser($user['username'], 5);
$projects     = UserStore::projectsForUser($user['username']);
$project_count = count(UserStore::projectsForUser($user['username']));

$dash_active  = 'dashboard';
$page_title   = 'Dashboard – CodeFoundry';
$active_page  = '';
$page_styles  = <<<'CSS'
  .dash-layout {
    display: flex;
    min-height: calc(100vh - var(--header-height));
    max-width: var(--maxwidth);
    margin: 0 auto;
    padding: 0 20px;
    gap: 0;
  }
  .dash-sidebar {
    width: 240px;
    flex-shrink: 0;
    padding: 32px 0 32px;
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
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    margin: 1px 8px;
    transition: background .15s, color .15s;
  }
  .dash-nav-item:hover {
    background: var(--navy-3);
    color: var(--text);
  }
  .dash-nav-item.active {
    background: rgba(24,179,255,.12);
    color: var(--primary);
  }
  .dash-nav-item iconify-icon {
    font-size: 17px;
    flex-shrink: 0;
  }
  .dash-main {
    flex: 1;
    padding: 36px 36px 60px;
    min-width: 0;
  }
  .dash-page-header {
    margin-bottom: 28px;
  }
  .dash-page-header h1 {
    font-size: 26px;
    font-weight: 800;
    margin: 0 0 4px;
  }
  .dash-page-header p {
    color: var(--text-muted);
    margin: 0;
    font-size: 14px;
  }
  .stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 28px;
  }
  .stat-card {
    background: var(--navy);
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    padding: 20px 22px;
  }
  .stat-card-label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .06em;
    margin-bottom: 10px;
  }
  .stat-card-value {
    font-size: 28px;
    font-weight: 800;
    color: var(--text);
    line-height: 1;
    margin-bottom: 4px;
  }
  .stat-card-sub {
    font-size: 12px;
    color: var(--text-subtle);
  }
  .dash-section {
    background: var(--navy);
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    margin-bottom: 24px;
  }
  .dash-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px;
    border-bottom: 1px solid var(--border-color);
  }
  .dash-section-header h2 {
    font-size: 15px;
    font-weight: 700;
    margin: 0;
  }
  .dash-section-body {
    padding: 20px 22px;
  }
  .progress-bar-wrap {
    background: var(--navy-3);
    border-radius: 100px;
    height: 10px;
    overflow: hidden;
    margin: 10px 0 8px;
  }
  .progress-bar-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, var(--primary), #0076b8);
    transition: width .4s ease;
  }
  .progress-labels {
    display: flex;
    justify-content: space-between;
    font-size: 12px;
    color: var(--text-muted);
  }
  .activity-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
  }
  .activity-table th {
    text-align: left;
    padding: 0 12px 10px 0;
    color: var(--text-subtle);
    font-weight: 600;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .06em;
    border-bottom: 1px solid var(--border-color);
  }
  .activity-table td {
    padding: 10px 12px 10px 0;
    border-bottom: 1px solid rgba(26,41,66,.5);
    color: var(--text-muted);
    vertical-align: middle;
  }
  .activity-table tr:last-child td { border-bottom: none; }
  .lang-badge {
    display: inline-block;
    padding: 2px 8px;
    background: rgba(24,179,255,.1);
    border: 1px solid rgba(24,179,255,.2);
    color: var(--primary);
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
  }
  .quick-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }
  .btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    background: var(--primary);
    color: var(--navy);
    font-weight: 700;
    font-size: 14px;
    border-radius: var(--button-radius);
    border: none;
    cursor: pointer;
    transition: background .2s;
    text-decoration: none;
  }
  .btn-primary:hover { background: var(--primary-hover); }
  .btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 10px 20px;
    background: transparent;
    color: var(--text);
    font-weight: 600;
    font-size: 14px;
    border-radius: var(--button-radius);
    border: 1px solid var(--border-color);
    cursor: pointer;
    transition: border-color .2s, background .2s;
    text-decoration: none;
  }
  .btn-secondary:hover {
    border-color: var(--primary);
    background: rgba(24,179,255,.07);
  }
  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-subtle);
  }
  .empty-state iconify-icon { font-size: 36px; margin-bottom: 10px; display: block; }
  @media (max-width: 900px) {
    .stat-grid { grid-template-columns: repeat(2,1fr); }
  }
  @media (max-width: 700px) {
    .dash-layout { flex-direction: column; padding: 0; }
    .dash-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); padding: 16px 0; display: flex; overflow-x: auto; }
    .dash-sidebar-title { display: none; }
    .dash-main { padding: 24px 16px 48px; }
    .stat-grid { grid-template-columns: repeat(2,1fr); }
  }
CSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="dash-layout">
  <!-- Sidebar -->
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

  <!-- Main content -->
  <main class="dash-main">
    <div class="dash-page-header">
      <h1>Welcome back, <?= cf_e($user['display'] ?? $user['username']) ?></h1>
      <p>Here's an overview of your CodeFoundry account.</p>
    </div>

    <!-- Stat cards -->
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-card-label">Tokens Used</div>
        <div class="stat-card-value"><?= number_format($tokens_used) ?></div>
        <div class="stat-card-sub">this month</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Token Limit</div>
        <div class="stat-card-value"><?= number_format($tokens_limit) ?></div>
        <div class="stat-card-sub"><?= cf_e($plan['label']) ?> plan</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Projects</div>
        <div class="stat-card-value"><?= $project_count ?></div>
        <div class="stat-card-sub">saved projects</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Plan</div>
        <div class="stat-card-value" style="font-size:22px"><?= cf_e($plan['label']) ?></div>
        <div class="stat-card-sub"><?= cf_e($plan['price_label']) ?></div>
      </div>
    </div>

    <!-- Token usage -->
    <div class="dash-section">
      <div class="dash-section-header">
        <h2>Token Usage</h2>
        <span style="font-size:13px;color:var(--text-muted)"><?= $token_pct ?>% used</span>
      </div>
      <div class="dash-section-body">
        <div class="progress-bar-wrap">
          <div class="progress-bar-fill" style="width:<?= $token_pct ?>%"></div>
        </div>
        <div class="progress-labels">
          <span><?= number_format($tokens_used) ?> used</span>
          <span><?= number_format($tokens_limit) ?> total</span>
        </div>
        <?php if ($token_pct >= 80): ?>
          <p style="margin:14px 0 0;font-size:13px;color:#f59e0b">
            <iconify-icon icon="lucide:alert-triangle" style="vertical-align:middle"></iconify-icon>
            You've used <?= $token_pct ?>% of your token allowance. <a href="/Dashboard/resources/" style="color:var(--primary)">Upgrade your plan</a> for more.
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="dash-section">
      <div class="dash-section-header">
        <h2>Recent Activity</h2>
        <a href="/Dashboard/history/" style="font-size:13px;color:var(--primary)">View all</a>
      </div>
      <div class="dash-section-body" style="padding-top:8px">
        <?php if (empty($history)): ?>
          <div class="empty-state">
            <iconify-icon icon="lucide:terminal"></iconify-icon>
            <p style="margin:0">No CodeGen activity yet. <a href="/IDE/" style="color:var(--primary)">Open the IDE</a> to get started.</p>
          </div>
        <?php else: ?>
          <table class="activity-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Action</th>
                <th>Language</th>
                <th>Tokens</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($history as $entry): ?>
                <tr>
                  <td><?= cf_e(date('M j, Y g:i a', strtotime($entry['created_at'] ?? 'now'))) ?></td>
                  <td style="color:var(--text)"><?= cf_e(ucfirst($entry['action'] ?? '')) ?></td>
                  <td><span class="lang-badge"><?= cf_e($entry['language'] ?? '') ?></span></td>
                  <td style="color:var(--text)"><?= number_format((int)($entry['tokens_used'] ?? 0)) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>Quick Actions</h2></div>
      <div class="dash-section-body">
        <div class="quick-actions">
          <a href="/IDE/" class="btn-primary">
            <iconify-icon icon="lucide:code-2"></iconify-icon> Open IDE
          </a>
          <a href="/Generate/" class="btn-secondary">
            <iconify-icon icon="lucide:sparkles"></iconify-icon> Generate Code
          </a>
          <a href="/Dashboard/history/" class="btn-secondary">
            <iconify-icon icon="lucide:history"></iconify-icon> View History
          </a>
        </div>
      </div>
    </div>
  </main>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
