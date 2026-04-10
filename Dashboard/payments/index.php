<?php
declare(strict_types=1);
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/UserStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$user         = UserStore::findUser($user_session['username']) ?? $user_session;
$plan_key     = $user['plan'] ?? 'free';
$plan         = CF_PLANS[$plan_key] ?? CF_PLANS['free'];
$payments     = UserStore::paymentsForUser($user['username']);

$dash_active = 'payments';
$page_title  = 'Payments – CodeFoundry';
$active_page = '';
$page_styles = <<<'CSS'
  .dash-layout {
    display: flex; min-height: calc(100vh - var(--header-height));
    max-width: var(--maxwidth); margin: 0 auto; padding: 0 20px;
  }
  .dash-sidebar {
    width: 240px; flex-shrink: 0; padding: 32px 0;
    border-right: 1px solid var(--border-color);
  }
  .dash-sidebar-title {
    font-size: 11px; font-weight: 700; letter-spacing: .08em;
    text-transform: uppercase; color: var(--text-subtle); padding: 0 20px 12px;
  }
  .dash-nav-item {
    display: flex; align-items: center; gap: 10px; padding: 10px 20px;
    color: var(--text-muted); font-size: 14px; font-weight: 500;
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
    border-radius: var(--card-radius); margin-bottom: 24px;
  }
  .dash-section-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 18px 22px; border-bottom: 1px solid var(--border-color);
  }
  .dash-section-header h2 { font-size: 15px; font-weight: 700; margin: 0; }
  .dash-section-body { padding: 24px 22px; }
  .sub-card {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 20px;
  }
  .sub-plan-name { font-size: 22px; font-weight: 800; margin-bottom: 4px; }
  .sub-plan-price { font-size: 28px; font-weight: 800; color: var(--primary); }
  .sub-plan-price span { font-size: 14px; font-weight: 500; color: var(--text-muted); }
  .sub-plan-note { font-size: 12px; color: var(--text-subtle); margin-top: 6px; }
  .btn-upgrade {
    display: inline-flex; align-items: center; gap: 7px; padding: 11px 28px;
    background: var(--primary); color: var(--navy); font-weight: 700; font-size: 14px;
    border-radius: var(--button-radius); text-decoration: none; transition: background .2s;
    flex-shrink: 0;
  }
  .btn-upgrade:hover { background: var(--primary-hover); }
  .data-table {
    width: 100%; border-collapse: collapse; font-size: 13px;
  }
  .data-table th {
    text-align: left; padding: 12px 16px; color: var(--text-subtle); font-weight: 600;
    font-size: 11px; text-transform: uppercase; letter-spacing: .06em;
    border-bottom: 1px solid var(--border-color); background: var(--navy-3);
  }
  .data-table td {
    padding: 12px 16px; border-bottom: 1px solid rgba(26,41,66,.5);
    color: var(--text-muted); vertical-align: middle;
  }
  .data-table tr:last-child td { border-bottom: none; }
  .data-table tr:hover td { background: rgba(255,255,255,.02); }
  .status-badge {
    display: inline-block; padding: 3px 10px; border-radius: 100px;
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .05em;
  }
  .status-paid    { background: rgba(34,197,94,.12); color: #4ade80; border: 1px solid rgba(34,197,94,.25); }
  .status-pending { background: rgba(245,158,11,.12); color: #fbbf24; border: 1px solid rgba(245,158,11,.25); }
  .status-failed  { background: rgba(255,72,72,.12);  color: #ff7373; border: 1px solid rgba(255,72,72,.25); }
  .empty-state {
    text-align: center; padding: 50px 20px; color: var(--text-subtle);
  }
  .empty-state iconify-icon { font-size: 40px; margin-bottom: 12px; display: block; color: var(--border-color); }
  .empty-state p { margin: 0; font-size: 14px; }
  @media (max-width: 700px) {
    .dash-layout { flex-direction: column; padding: 0; }
    .dash-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); padding: 16px 0; display: flex; overflow-x: auto; }
    .dash-sidebar-title { display: none; }
    .dash-main { padding: 24px 16px 48px; }
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
      <h1>Payments &amp; Subscription</h1>
      <p>Manage your subscription and view payment history.</p>
    </div>

    <!-- Current subscription -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>Current Subscription</h2></div>
      <div class="dash-section-body">
        <div class="sub-card">
          <div>
            <div class="sub-plan-name"><?= cf_e($plan['label']) ?> Plan</div>
            <div class="sub-plan-price">
              <?php if ($plan['price'] === 0): ?>
                Free
              <?php else: ?>
                $<?= $plan['price'] ?><span>/month</span>
              <?php endif; ?>
            </div>
            <?php if ($plan['price'] > 0): ?>
              <div class="sub-plan-note">
                <iconify-icon icon="lucide:refresh-cw" style="vertical-align:middle;margin-right:4px"></iconify-icon>
                Billed monthly
              </div>
            <?php else: ?>
              <div class="sub-plan-note">Upgrade to unlock more tokens and features.</div>
            <?php endif; ?>
          </div>
          <a href="/Pricing/" class="btn-upgrade">
            <iconify-icon icon="lucide:zap"></iconify-icon>
            <?= $plan['price'] > 0 ? 'Change Plan' : 'Upgrade Now' ?>
          </a>
        </div>
      </div>
    </div>

    <!-- Payment history -->
    <div class="dash-section">
      <div class="dash-section-header">
        <h2>Payment History</h2>
        <span style="font-size:13px;color:var(--text-muted)"><?= count($payments) ?> records</span>
      </div>
      <div class="dash-section-body" style="padding:0">
        <?php if (empty($payments)): ?>
          <div class="empty-state">
            <iconify-icon icon="lucide:receipt"></iconify-icon>
            <p>No payment records found. Payments will appear here once you subscribe to a paid plan.</p>
          </div>
        <?php else: ?>
          <table class="data-table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($payments as $payment): ?>
                <?php
                  $status    = strtolower($payment['status'] ?? 'pending');
                  $statusCls = match($status) {
                      'paid'    => 'status-paid',
                      'failed'  => 'status-failed',
                      default   => 'status-pending',
                  };
                ?>
                <tr>
                  <td style="white-space:nowrap"><?= cf_e(date('M j, Y', strtotime($payment['created_at'] ?? 'now'))) ?></td>
                  <td style="color:var(--text)"><?= cf_e($payment['description'] ?? '') ?></td>
                  <td style="color:var(--text);font-weight:600">
                    $<?= number_format((float)($payment['amount'] ?? 0), 2) ?>
                  </td>
                  <td><span class="status-badge <?= $statusCls ?>"><?= cf_e(ucfirst($status)) ?></span></td>
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
