<?php
declare(strict_types=1);
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/UserStore.php';
require_once dirname(dirname(__DIR__)) . '/lib/ChatStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$user         = UserStore::findUser($user_session['username']) ?? $user_session;
$plan_key     = $user['plan'] ?? 'free';
$plan         = CF_PLANS[$plan_key] ?? CF_PLANS['free'];
$tokens_used  = (int)($user['tokens_used'] ?? 0);
$tokens_limit = (int)$plan['tokens_limit'];
$token_pct    = $tokens_limit > 0 ? min(100, round($tokens_used / $tokens_limit * 100, 1)) : 0;
$unread_chat  = ChatStore::totalUnreadForUser($user['username']);

$dash_active = 'resources';
$page_title  = 'Resources – CodeFoundry';
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
  .dash-nav-item:hover { background: var(--navy-3); color: var(--text); }
  .dash-nav-item.active { background: rgba(24,179,255,.12); color: var(--primary); }
  .dash-nav-item iconify-icon { font-size: 17px; flex-shrink: 0; }
  .dash-main { flex: 1; padding: 36px 36px 60px; min-width: 0; }
  .dash-page-header { margin-bottom: 28px; }
  .dash-page-header h1 { font-size: 26px; font-weight: 800; margin: 0 0 4px; }
  .dash-page-header p { color: var(--text-muted); margin: 0; font-size: 14px; }
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
  .dash-section-header h2 { font-size: 15px; font-weight: 700; margin: 0; }
  .dash-section-body { padding: 22px; }
  .progress-bar-wrap {
    background: var(--navy-3);
    border-radius: 100px;
    height: 12px;
    overflow: hidden;
    margin: 14px 0 10px;
  }
  .progress-bar-fill {
    height: 100%;
    border-radius: 100px;
    background: linear-gradient(90deg, var(--primary), #0076b8);
    transition: width .4s ease;
  }
  .progress-labels { display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted); }
  .plan-current-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
  }
  .plan-current-name { font-size: 22px; font-weight: 800; }
  .plan-current-meta { font-size: 14px; color: var(--text-muted); margin-top: 4px; }
  .plan-badge {
    display: inline-block;
    padding: 4px 12px;
    background: rgba(24,179,255,.15);
    color: var(--primary);
    border: 1px solid rgba(24,179,255,.3);
    border-radius: 100px;
    font-size: 13px;
    font-weight: 700;
  }
  .plans-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
  }
  .plan-card {
    background: var(--navy-2);
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    padding: 24px 20px;
    display: flex;
    flex-direction: column;
    gap: 14px;
    transition: border-color .2s;
  }
  .plan-card.current { border-color: var(--primary); }
  .plan-card:hover { border-color: rgba(24,179,255,.4); }
  .plan-card-name { font-size: 16px; font-weight: 800; }
  .plan-card-price { font-size: 28px; font-weight: 800; color: var(--primary); }
  .plan-card-price span { font-size: 14px; font-weight: 500; color: var(--text-muted); }
  .plan-card-tokens { font-size: 13px; color: var(--text-muted); }
  .plan-card-tokens strong { color: var(--text); }
  .btn-plan-current {
    display: block; text-align: center;
    padding: 9px 16px;
    background: rgba(24,179,255,.1);
    color: var(--primary);
    border: 1px solid rgba(24,179,255,.3);
    border-radius: var(--button-radius);
    font-weight: 700; font-size: 13px;
    cursor: default;
  }
  .btn-plan-upgrade {
    display: block; text-align: center;
    padding: 9px 16px;
    background: var(--primary);
    color: var(--navy);
    border-radius: var(--button-radius);
    font-weight: 700; font-size: 13px;
    transition: background .2s;
    text-decoration: none;
  }
  .btn-plan-upgrade:hover { background: var(--primary-hover); }
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
  @media (max-width: 900px) { .plans-grid { grid-template-columns: repeat(2,1fr); } }
  @media (max-width: 700px) {
    .dash-layout { flex-direction: column; padding: 0; }
    .dash-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); padding: 16px 0; display: flex; overflow-x: auto; }
    .dash-sidebar-title { display: none; }
    .dash-main { padding: 24px 16px 48px; }
    .plans-grid { grid-template-columns: 1fr 1fr; }
  }
  @media (max-width: 480px) { .plans-grid { grid-template-columns: 1fr; } }
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
      <h1>Resources</h1>
      <p>Monitor your token usage and manage your subscription plan.</p>
    </div>

    <!-- Current plan -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>Current Plan</h2></div>
      <div class="dash-section-body">
        <div class="plan-current-card">
          <div>
            <div class="plan-current-name"><?= cf_e($plan['label']) ?> <span class="plan-badge"><?= cf_e($plan['price_label']) ?></span></div>
            <div class="plan-current-meta"><?= number_format($tokens_limit) ?> tokens/month</div>
          </div>
          <?php if ($plan['price'] > 0): ?>
            <a href="/Pricing/" class="btn-plan-upgrade" style="padding:11px 28px">Change Plan</a>
          <?php else: ?>
            <a href="/Pricing/" class="btn-plan-upgrade" style="padding:11px 28px">Upgrade</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Token usage -->
    <div class="dash-section">
      <div class="dash-section-header">
        <h2>Token Usage</h2>
        <span style="font-size:13px;color:var(--text-muted)"><?= $token_pct ?>%</span>
      </div>
      <div class="dash-section-body">
        <div style="font-size:32px;font-weight:800;margin-bottom:4px"><?= number_format($tokens_used) ?></div>
        <div style="font-size:13px;color:var(--text-muted);margin-bottom:2px">tokens used of <?= number_format($tokens_limit) ?> total</div>
        <div class="progress-bar-wrap">
          <div class="progress-bar-fill" style="width:<?= $token_pct ?>%"></div>
        </div>
        <div class="progress-labels">
          <span><?= number_format($tokens_used) ?> used</span>
          <span><?= number_format($tokens_limit - $tokens_used) ?> remaining</span>
        </div>
        <?php if ($token_pct >= 80): ?>
          <p style="margin:16px 0 0;font-size:13px;color:#f59e0b;background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.2);padding:12px 16px;border-radius:8px">
            <iconify-icon icon="lucide:alert-triangle" style="vertical-align:middle;margin-right:6px"></iconify-icon>
            You've used <?= $token_pct ?>% of your token allowance. Consider upgrading to avoid interruptions.
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Plan comparison -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>Available Plans</h2></div>
      <div class="dash-section-body">
        <div class="plans-grid">
          <?php foreach (CF_PLANS as $pk => $p): ?>
            <div class="plan-card <?= $pk === $plan_key ? 'current' : '' ?>">
              <div class="plan-card-name"><?= cf_e($p['label']) ?></div>
              <div class="plan-card-price">
                <?php if ($p['price'] === 0): ?>
                  Free
                <?php else: ?>
                  $<?= $p['price'] ?><span>/mo</span>
                <?php endif; ?>
              </div>
              <div class="plan-card-tokens">
                <strong><?= number_format($p['tokens_limit']) ?></strong> tokens/month
              </div>
              <?php if ($pk === $plan_key): ?>
                <div class="btn-plan-current">Current Plan</div>
              <?php elseif ($p['price'] > 0): ?>
                <a href="/Checkout/?plan=<?= cf_e($pk) ?>" class="btn-plan-upgrade">Upgrade</a>
              <?php else: ?>
                <a href="/Support/" class="btn-plan-upgrade">Contact Sales</a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </main>
</div>

<?php require_once dirname(dirname(__DIR__)) . '/includes/footer.php'; ?>
