<?php
/**
 * CodeFoundry – Admin Control Panel
 *
 * Provides a full administrative view of:
 *   • System overview / key metrics
 *   • Audit trail (all events)
 *   • Full architecture diagram & description
 *   • End-to-end workflows
 *   • User management (list, details, signups, navigation, time-spent, support tickets)
 *   • Analytics (page views, codegen usage, payments, top pages)
 *
 * Access is restricted to users with role = 'admin'.
 */
declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';
require_once dirname(__DIR__) . '/lib/ChatStore.php';
require_once dirname(__DIR__) . '/includes/auth.php';

cf_require_login();

// Admin guard
$_admin_session = cf_current_user();
if (($_admin_session['role'] ?? '') !== 'admin') {
    http_response_code(403);
    echo '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body style="font-family:sans-serif;padding:40px"><h1>403 – Forbidden</h1><p>You do not have permission to access this page.</p><a href="/">Home</a></body></html>';
    exit;
}

// ── Handle ticket status update ───────────────────────────────────────────
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['action'], $_POST['ticket_id']) &&
    $_POST['action'] === 'update_ticket'
) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!empty($_POST['csrf_token']) && $_POST['csrf_token'] === ($_SESSION['csrf_token'] ?? '')) {
        $allowed_statuses = ['open', 'in_progress', 'resolved', 'closed'];
        $new_status = $_POST['status'] ?? 'open';
        if (in_array($new_status, $allowed_statuses, true)) {
            AuditStore::updateTicketStatus(trim($_POST['ticket_id']), $new_status);
        }
    }
    header('Location: /Admin/?tab=support');
    exit;
}

// Ensure session is started for CSRF token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

// ── Load all data ─────────────────────────────────────────────────────────
$all_users    = UserStore::allUsers();
$all_events   = AuditStore::allEvents();
$all_pviews   = AuditStore::allPageViews(500);
$all_tickets  = AuditStore::allSupportTickets();
$pview_counts = AuditStore::pageViewCounts();

// Merge CF_USERS into allUsers for display (admin account from config not in users.json)
$cf_usernames = array_column(CF_USERS, 'username');
$stored_usernames = array_column($all_users, 'username');
foreach (CF_USERS as $cu) {
    if (!in_array($cu['username'], $stored_usernames, true)) {
        $all_users[] = [
            'username'   => $cu['username'],
            'display'    => $cu['display'] ?? $cu['username'],
            'email'      => $cu['email'] ?? '',
            'role'       => $cu['role'] ?? 'user',
            'plan'       => 'free',
            'tokens_used' => 0,
            'created_at' => '',
        ];
    }
}

// ── Stats ─────────────────────────────────────────────────────────────────
$total_users    = count($all_users);
$total_events   = count($all_events);
$total_pviews   = count(AuditStore::allPageViews());
$total_tickets  = count($all_tickets);
$open_tickets   = count(array_filter($all_tickets, fn($t) => ($t['status'] ?? 'open') === 'open'));

// Signups in last 30 days
$signup_events = array_filter($all_events, fn($e) => ($e['event'] ?? '') === 'user.signup');
$signups_30d = count(array_filter($signup_events, function ($e) {
    return isset($e['created_at']) && strtotime($e['created_at']) >= strtotime('-30 days');
}));

// Total tokens across all users
$total_tokens = array_sum(array_column($all_users, 'tokens_used'));

// Payment total
$all_payments_raw = [];
foreach ($all_users as $u) {
    $pms = UserStore::paymentsForUser($u['username']);
    foreach ($pms as $pm) {
        $all_payments_raw[] = $pm;
    }
}
$total_revenue = array_sum(array_column($all_payments_raw, 'amount'));

// Chat
$unread_admin_chat = ChatStore::totalUnreadForAdmin();

// Active tab
$active_tab = $_GET['tab'] ?? 'overview';

// User detail
$detail_user = null;
if ($active_tab === 'user_detail' && isset($_GET['u'])) {
    $detail_user = UserStore::findUser(trim($_GET['u']));
}

$page_title  = 'Control Panel – CodeFoundry';
$active_page = '';
$page_styles = <<<'CSS'
  :root {
    --navy: #0e1828; --navy-2: #121c2b; --navy-3: #161f2f;
    --primary: #18b3ff; --primary-hover: #009de0;
    --text: #fff; --text-muted: #92a3bb; --text-subtle: #627193;
    --border-color: #1a2942; --button-radius: 8px;
    --maxwidth: 1400px; --card-radius: 12px; --header-height: 68px;
  }
  .adm-layout { display:flex; min-height:calc(100vh - var(--header-height)); max-width:var(--maxwidth); margin:0 auto; padding:0 20px; gap:0; }
  .adm-sidebar { width:220px; flex-shrink:0; padding:28px 0; border-right:1px solid var(--border-color); }
  .adm-sidebar-title { font-size:11px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; color:var(--text-subtle); padding:0 16px 10px; }
  .adm-nav-item { display:flex; align-items:center; gap:10px; padding:9px 16px; color:var(--text-muted); font-size:13px; font-weight:500; border-radius:8px; margin:1px 6px; transition:background .15s,color .15s; text-decoration:none; }
  .adm-nav-item:hover { background:var(--navy-3); color:var(--text); }
  .adm-nav-item.active { background:rgba(24,179,255,.12); color:var(--primary); }
  .adm-nav-item iconify-icon { font-size:16px; flex-shrink:0; }
  .adm-main { flex:1; padding:32px 32px 60px; min-width:0; }
  .adm-header { margin-bottom:24px; }
  .adm-header h1 { font-size:24px; font-weight:800; margin:0 0 4px; }
  .adm-header p { color:var(--text-muted); margin:0; font-size:13px; }
  .stat-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:24px; }
  .stat-card { background:var(--navy); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:18px 20px; }
  .stat-card-label { font-size:11px; color:var(--text-muted); font-weight:700; text-transform:uppercase; letter-spacing:.06em; margin-bottom:8px; }
  .stat-card-value { font-size:26px; font-weight:800; color:var(--text); line-height:1; margin-bottom:3px; }
  .stat-card-sub { font-size:11px; color:var(--text-subtle); }
  .adm-section { background:var(--navy); border:1px solid var(--border-color); border-radius:var(--card-radius); margin-bottom:20px; }
  .adm-section-header { display:flex; align-items:center; justify-content:space-between; padding:16px 20px; border-bottom:1px solid var(--border-color); }
  .adm-section-header h2 { font-size:14px; font-weight:700; margin:0; }
  .adm-section-body { padding:18px 20px; overflow-x:auto; }
  .adm-table { width:100%; border-collapse:collapse; font-size:12px; }
  .adm-table th { text-align:left; padding:0 10px 8px 0; color:var(--text-subtle); font-weight:700; font-size:11px; text-transform:uppercase; letter-spacing:.06em; border-bottom:1px solid var(--border-color); white-space:nowrap; }
  .adm-table td { padding:9px 10px 9px 0; border-bottom:1px solid rgba(26,41,66,.5); color:var(--text-muted); vertical-align:middle; }
  .adm-table tr:last-child td { border-bottom:none; }
  .adm-table td a { color:var(--primary); text-decoration:none; }
  .adm-table td a:hover { text-decoration:underline; }
  .badge { display:inline-block; padding:2px 7px; border-radius:4px; font-size:11px; font-weight:600; }
  .badge-blue { background:rgba(24,179,255,.12); color:var(--primary); border:1px solid rgba(24,179,255,.2); }
  .badge-green { background:rgba(34,197,94,.1); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
  .badge-yellow { background:rgba(245,158,11,.1); color:#fbbf24; border:1px solid rgba(245,158,11,.2); }
  .badge-red { background:rgba(239,68,68,.1); color:#f87171; border:1px solid rgba(239,68,68,.2); }
  .badge-gray { background:rgba(148,163,184,.1); color:var(--text-subtle); border:1px solid var(--border-color); }
  .arch-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; }
  .arch-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:10px; padding:16px 18px; }
  .arch-card-title { font-size:13px; font-weight:700; color:var(--text); margin-bottom:8px; display:flex; align-items:center; gap:8px; }
  .arch-card-title iconify-icon { color:var(--primary); font-size:16px; }
  .arch-card p { font-size:12px; color:var(--text-muted); margin:0 0 6px; line-height:1.6; }
  .arch-card ul { font-size:12px; color:var(--text-muted); margin:6px 0 0; padding-left:16px; }
  .arch-card li { margin-bottom:3px; }
  .wf-step { display:flex; gap:14px; margin-bottom:16px; }
  .wf-step-num { width:28px; height:28px; background:rgba(24,179,255,.15); border:1px solid rgba(24,179,255,.3); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:var(--primary); flex-shrink:0; margin-top:1px; }
  .wf-step-body h4 { font-size:13px; font-weight:700; margin:0 0 3px; color:var(--text); }
  .wf-step-body p { font-size:12px; color:var(--text-muted); margin:0; line-height:1.6; }
  .wf-title { font-size:15px; font-weight:700; margin:0 0 16px; color:var(--text); padding-bottom:8px; border-bottom:1px solid var(--border-color); }
  .wf-section { margin-bottom:28px; }
  .filter-bar { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:14px; }
  .filter-input { background:var(--navy-3); border:1px solid var(--border-color); border-radius:6px; padding:7px 12px; color:var(--text); font-size:12px; outline:none; min-width:180px; }
  .filter-input:focus { border-color:var(--primary); }
  .btn-sm { display:inline-flex; align-items:center; gap:5px; padding:5px 12px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1px solid var(--border-color); background:var(--navy-3); color:var(--text-muted); text-decoration:none; transition:border-color .15s,color .15s; }
  .btn-sm:hover { border-color:var(--primary); color:var(--primary); }
  .btn-sm-primary { background:rgba(24,179,255,.12); border-color:rgba(24,179,255,.25); color:var(--primary); }
  .user-detail-header { display:flex; align-items:center; gap:16px; margin-bottom:24px; }
  .user-avatar { width:52px; height:52px; background:rgba(24,179,255,.12); border:1px solid rgba(24,179,255,.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px; color:var(--primary); flex-shrink:0; }
  .user-detail-name { font-size:20px; font-weight:800; margin:0 0 2px; }
  .user-detail-meta { font-size:12px; color:var(--text-muted); }
  .empty-state { text-align:center; padding:32px 16px; color:var(--text-subtle); font-size:13px; }
  .empty-state iconify-icon { font-size:28px; display:block; margin-bottom:8px; }
  .top-pages-bar { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
  .top-pages-bar-label { font-size:12px; color:var(--text-muted); width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .top-pages-bar-track { flex:1; background:var(--navy-3); border-radius:100px; height:8px; overflow:hidden; }
  .top-pages-bar-fill { height:100%; background:linear-gradient(90deg,var(--primary),#0076b8); border-radius:100px; }
  .top-pages-bar-count { font-size:11px; color:var(--text-subtle); width:36px; text-align:right; }
  select.filter-select { background:var(--navy-3); border:1px solid var(--border-color); border-radius:6px; padding:7px 12px; color:var(--text); font-size:12px; outline:none; cursor:pointer; }
  select.filter-select:focus { border-color:var(--primary); }
  .nav-badge { margin-left:auto; background:var(--primary); color:var(--navy); font-size:10px; font-weight:800; border-radius:100px; padding:1px 6px; min-width:18px; text-align:center; line-height:16px; }
  /* ── Chat styles ─── */
  .chat-admin-layout { display:flex; gap:0; height:calc(100vh - var(--header-height) - 140px); min-height:460px; background:var(--navy); border:1px solid var(--border-color); border-radius:var(--card-radius); overflow:hidden; }
  .chat-sessions-panel { width:260px; flex-shrink:0; border-right:1px solid var(--border-color); display:flex; flex-direction:column; }
  .chat-sessions-header { padding:12px 14px; border-bottom:1px solid var(--border-color); flex-shrink:0; }
  .chat-sessions-header h3 { font-size:12px; font-weight:700; margin:0; color:var(--text); }
  .chat-sessions-list { flex:1; overflow-y:auto; }
  .chat-session-item { display:flex; flex-direction:column; gap:3px; padding:10px 14px; cursor:pointer; border-bottom:1px solid rgba(26,41,66,.5); transition:background .15s; }
  .chat-session-item:hover { background:var(--navy-3); }
  .chat-session-item.active { background:rgba(24,179,255,.08); border-left:2px solid var(--primary); }
  .chat-session-subject { font-size:12px; font-weight:600; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
  .chat-session-meta { font-size:10px; color:var(--text-subtle); display:flex; align-items:center; justify-content:space-between; }
  .chat-session-unread { background:var(--primary); color:var(--navy); font-size:9px; font-weight:800; border-radius:100px; padding:1px 5px; }
  .chat-main-panel { flex:1; display:flex; flex-direction:column; min-width:0; }
  .chat-header { padding:12px 16px; border-bottom:1px solid var(--border-color); display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
  .chat-header-title { font-size:13px; font-weight:700; color:var(--text); margin:0; }
  .chat-header-sub { font-size:10px; color:var(--text-muted); margin-top:2px; }
  .chat-messages { flex:1; overflow-y:auto; padding:14px 16px; display:flex; flex-direction:column; gap:8px; }
  .chat-bubble-wrap { display:flex; flex-direction:column; max-width:75%; }
  .chat-bubble-wrap.from-me { align-self:flex-end; align-items:flex-end; }
  .chat-bubble-wrap.from-them { align-self:flex-start; align-items:flex-start; }
  .chat-bubble { padding:8px 12px; border-radius:14px; font-size:13px; line-height:1.5; word-break:break-word; }
  .chat-bubble.from-me { background:rgba(24,179,255,.15); color:var(--text); border:1px solid rgba(24,179,255,.25); border-bottom-right-radius:3px; }
  .chat-bubble.from-them { background:var(--navy-3); color:var(--text); border:1px solid var(--border-color); border-bottom-left-radius:3px; }
  .chat-bubble-meta { font-size:9px; color:var(--text-subtle); margin-top:2px; padding:0 4px; }
  .chat-input-area { padding:12px 16px; border-top:1px solid var(--border-color); display:flex; gap:8px; align-items:flex-end; flex-shrink:0; }
  .chat-input { flex:1; background:var(--navy-3); border:1px solid var(--border-color); border-radius:8px; padding:8px 12px; color:var(--text); font-size:13px; resize:none; outline:none; min-height:36px; max-height:100px; font-family:inherit; line-height:1.4; transition:border-color .15s; }
  .chat-input:focus { border-color:var(--primary); }
  .chat-send-btn { display:flex; align-items:center; justify-content:center; gap:5px; padding:8px 14px; background:rgba(24,179,255,.15); color:var(--primary); font-weight:700; font-size:12px; border:1px solid rgba(24,179,255,.3); border-radius:8px; cursor:pointer; transition:background .2s; white-space:nowrap; flex-shrink:0; }
  .chat-send-btn:hover { background:rgba(24,179,255,.25); }
  .chat-send-btn:disabled { opacity:.5; cursor:not-allowed; }
  .chat-empty-state { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; color:var(--text-subtle); gap:8px; }
  .chat-empty-state iconify-icon { font-size:28px; }
  .chat-empty-state p { margin:0; font-size:13px; }
  .badge-status-open { color:#fbbf24; }
  .badge-status-closed { color:var(--text-subtle); }
  @media(max-width:900px){ .stat-grid{grid-template-columns:repeat(2,1fr);} .arch-grid{grid-template-columns:1fr;} }
  @media(max-width:700px){ .adm-layout{flex-direction:column;padding:0;} .adm-sidebar{width:100%;border-right:none;border-bottom:1px solid var(--border-color);padding:12px 0;display:flex;overflow-x:auto;} .adm-sidebar-title{display:none;} .adm-main{padding:20px 14px 48px;} .stat-grid{grid-template-columns:repeat(2,1fr);} }
CSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="adm-layout">
  <!-- Sidebar -->
  <aside class="adm-sidebar">
    <div class="adm-sidebar-title">Control Panel</div>
    <?php
    $tabs = [
        'overview'     => ['icon' => 'lucide:layout-dashboard', 'label' => 'Overview'],
        'audit'        => ['icon' => 'lucide:shield-check',     'label' => 'Audit Trail'],
        'users'        => ['icon' => 'lucide:users',             'label' => 'Users'],
        'support'      => ['icon' => 'lucide:life-buoy',         'label' => 'Support'],
        'live_chat'    => ['icon' => 'lucide:message-circle',    'label' => 'Live Chat'],
        'analytics'    => ['icon' => 'lucide:bar-chart-2',       'label' => 'Analytics'],
        'architecture' => ['icon' => 'lucide:layout',            'label' => 'Architecture'],
        'workflows'    => ['icon' => 'lucide:git-pull-request',  'label' => 'Workflows'],
    ];
    foreach ($tabs as $key => $meta): ?>
    <a href="/Admin/?tab=<?= cf_e($key) ?>"
       class="adm-nav-item <?= $active_tab === $key ? 'active' : '' ?>">
      <iconify-icon icon="<?= cf_e($meta['icon']) ?>"></iconify-icon>
      <?= cf_e($meta['label']) ?>
      <?php if ($key === 'live_chat' && $unread_admin_chat > 0): ?>
        <span class="nav-badge" id="adminChatBadge"><?= (int)$unread_admin_chat ?></span>
      <?php elseif ($key === 'live_chat'): ?>
        <span class="nav-badge" id="adminChatBadge" style="display:none">0</span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
    <div style="margin-top:auto;padding:20px 16px 0;border-top:1px solid var(--border-color);margin-top:20px">
      <a href="/Dashboard/" class="adm-nav-item">
        <iconify-icon icon="lucide:arrow-left"></iconify-icon> Dashboard
      </a>
    </div>
  </aside>

  <!-- Main content -->
  <main class="adm-main">

    <?php if ($active_tab === 'overview'): ?>
    <!-- ═══ OVERVIEW ══════════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:layout-dashboard" style="vertical-align:middle;margin-right:8px"></iconify-icon>Overview</h1>
      <p>System health and key metrics at a glance.</p>
    </div>
    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-card-label">Total Users</div>
        <div class="stat-card-value"><?= number_format($total_users) ?></div>
        <div class="stat-card-sub"><?= $signups_30d ?> signups in last 30 days</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Audit Events</div>
        <div class="stat-card-value"><?= number_format($total_events) ?></div>
        <div class="stat-card-sub">all time</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Page Views</div>
        <div class="stat-card-value"><?= number_format($total_pviews) ?></div>
        <div class="stat-card-sub">tracked sessions</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Revenue</div>
        <div class="stat-card-value">$<?= number_format($total_revenue, 2) ?></div>
        <div class="stat-card-sub">all payments</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Tokens Used</div>
        <div class="stat-card-value"><?= number_format($total_tokens) ?></div>
        <div class="stat-card-sub">across all users</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Support Tickets</div>
        <div class="stat-card-value"><?= number_format($total_tickets) ?></div>
        <div class="stat-card-sub"><?= $open_tickets ?> open</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Payments</div>
        <div class="stat-card-value"><?= number_format(count($all_payments_raw)) ?></div>
        <div class="stat-card-sub">total transactions</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Providers</div>
        <div class="stat-card-value"><?= count(CF_CODEGEN_PROVIDERS) ?></div>
        <div class="stat-card-sub">AI codegen backends</div>
      </div>
    </div>

    <!-- Recent events -->
    <div class="adm-section">
      <div class="adm-section-header">
        <h2>Recent Audit Events</h2>
        <a href="/Admin/?tab=audit" class="btn-sm">View all</a>
      </div>
      <div class="adm-section-body" style="padding-top:10px">
        <?php $recent = AuditStore::allEvents(15); if (empty($recent)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No events recorded yet.</div>
        <?php else: ?>
          <table class="adm-table">
            <thead><tr><th>Time</th><th>Event</th><th>User</th><th>IP</th></tr></thead>
            <tbody>
              <?php foreach ($recent as $ev): ?>
              <tr>
                <td style="white-space:nowrap"><?= cf_e(date('M j, g:i a', strtotime($ev['created_at'] ?? 'now'))) ?></td>
                <td><span class="badge badge-blue"><?= cf_e($ev['event'] ?? '') ?></span></td>
                <td><?= cf_e($ev['username'] ?: '–') ?></td>
                <td style="font-family:monospace"><?= cf_e($ev['ip'] ?? '') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <!-- Recent signups -->
    <div class="adm-section">
      <div class="adm-section-header">
        <h2>Recent Signups</h2>
        <a href="/Admin/?tab=users" class="btn-sm">All users</a>
      </div>
      <div class="adm-section-body" style="padding-top:10px">
        <?php
        $recent_signups = array_slice(array_reverse(array_filter(
            $all_users,
            fn($u) => !empty($u['created_at'])
        )), 0, 10);
        if (empty($recent_signups)):
        ?>
          <div class="empty-state"><iconify-icon icon="lucide:user-x"></iconify-icon>No signups recorded yet.</div>
        <?php else: ?>
          <table class="adm-table">
            <thead><tr><th>Joined</th><th>Username</th><th>Email</th><th>Plan</th></tr></thead>
            <tbody>
              <?php foreach ($recent_signups as $u): ?>
              <tr>
                <td style="white-space:nowrap"><?= cf_e(!empty($u['created_at']) ? date('M j, Y', strtotime($u['created_at'])) : '–') ?></td>
                <td><a href="/Admin/?tab=user_detail&u=<?= urlencode($u['username']) ?>"><?= cf_e($u['username']) ?></a></td>
                <td><?= cf_e($u['email'] ?? '') ?></td>
                <td><span class="badge badge-gray"><?= cf_e(ucfirst($u['plan'] ?? 'free')) ?></span></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <?php elseif ($active_tab === 'audit'): ?>
    <!-- ═══ AUDIT TRAIL ═══════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:shield-check" style="vertical-align:middle;margin-right:8px"></iconify-icon>Audit Trail</h1>
      <p>Immutable record of all significant system events.</p>
    </div>
    <div class="adm-section">
      <div class="adm-section-header"><h2>All Events (<?= number_format($total_events) ?>)</h2></div>
      <div class="adm-section-body">
        <div class="filter-bar">
          <input type="text" class="filter-input" id="auditSearch" placeholder="Search event, user, IP…" oninput="filterTable('auditTbl',this.value)">
          <select class="filter-select" onchange="filterTable('auditTbl',document.getElementById('auditSearch').value,this.value,'event')">
            <option value="">All event types</option>
            <option value="user.login">user.login</option>
            <option value="user.login_failed">user.login_failed</option>
            <option value="user.signup">user.signup</option>
            <option value="user.logout">user.logout</option>
            <option value="codegen.request">codegen.request</option>
            <option value="payment.completed">payment.completed</option>
            <option value="support.ticket_created">support.ticket_created</option>
          </select>
        </div>
        <?php if (empty($all_events)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No audit events recorded yet.</div>
        <?php else: ?>
          <table class="adm-table" id="auditTbl">
            <thead><tr><th>Time</th><th>Event</th><th>User</th><th>IP</th><th>Details</th></tr></thead>
            <tbody>
              <?php foreach ($all_events as $ev): ?>
              <tr>
                <td style="white-space:nowrap"><?= cf_e(date('Y-m-d H:i:s', strtotime($ev['created_at'] ?? 'now'))) ?></td>
                <td><span class="badge <?= match(true) {
                    str_starts_with($ev['event'] ?? '', 'user.login') => 'badge-blue',
                    str_starts_with($ev['event'] ?? '', 'user.signup') => 'badge-green',
                    str_starts_with($ev['event'] ?? '', 'payment') => 'badge-yellow',
                    str_starts_with($ev['event'] ?? '', 'codegen') => 'badge-blue',
                    str_starts_with($ev['event'] ?? '', 'support') => 'badge-gray',
                    str_contains($ev['event'] ?? '', 'failed') => 'badge-red',
                    default => 'badge-gray',
                } ?>"><?= cf_e($ev['event'] ?? '') ?></span></td>
                <td><?= $ev['username'] ? '<a href="/Admin/?tab=user_detail&u='.urlencode($ev['username']).'">'.cf_e($ev['username']).'</a>' : '–' ?></td>
                <td style="font-family:monospace;font-size:11px"><?= cf_e($ev['ip'] ?? '') ?></td>
                <td style="max-width:300px;word-break:break-all;font-size:11px;color:var(--text-subtle)"><?php
                  $d = $ev['data'] ?? [];
                  unset($d['code_output']);
                  echo cf_e(json_encode($d, JSON_UNESCAPED_SLASHES));
                ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <?php elseif ($active_tab === 'users'): ?>
    <!-- ═══ USERS ══════════════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:users" style="vertical-align:middle;margin-right:8px"></iconify-icon>User Management</h1>
      <p>All registered users – click a username for full details.</p>
    </div>
    <div class="adm-section">
      <div class="adm-section-header"><h2>All Users (<?= number_format($total_users) ?>)</h2></div>
      <div class="adm-section-body">
        <div class="filter-bar">
          <input type="text" class="filter-input" placeholder="Search username, email…" oninput="filterTable('usersTbl',this.value)">
        </div>
        <?php if (empty($all_users)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:user-x"></iconify-icon>No users found.</div>
        <?php else: ?>
          <table class="adm-table" id="usersTbl">
            <thead>
              <tr>
                <th>Username</th><th>Display</th><th>Email</th>
                <th>Role</th><th>Plan</th><th>Tokens Used</th>
                <th>Joined</th><th>OAuth</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($all_users as $u): ?>
              <tr>
                <td><a href="/Admin/?tab=user_detail&u=<?= urlencode($u['username']) ?>"><?= cf_e($u['username']) ?></a></td>
                <td><?= cf_e($u['display'] ?? '') ?></td>
                <td><?= cf_e($u['email'] ?? '') ?></td>
                <td><span class="badge <?= ($u['role'] ?? '') === 'admin' ? 'badge-yellow' : 'badge-gray' ?>"><?= cf_e(ucfirst($u['role'] ?? 'user')) ?></span></td>
                <td><span class="badge badge-blue"><?= cf_e(ucfirst($u['plan'] ?? 'free')) ?></span></td>
                <td><?= number_format((int)($u['tokens_used'] ?? 0)) ?></td>
                <td style="white-space:nowrap"><?= cf_e(!empty($u['created_at']) ? date('M j, Y', strtotime($u['created_at'])) : '–') ?></td>
                <td><?= cf_e($u['oauth_provider'] ?? '') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <?php elseif ($active_tab === 'user_detail' && $detail_user !== null): ?>
    <!-- ═══ USER DETAIL ════════════════════════════════════════════════════ -->
    <div style="margin-bottom:16px">
      <a href="/Admin/?tab=users" class="btn-sm"><iconify-icon icon="lucide:arrow-left"></iconify-icon> All Users</a>
    </div>
    <div class="user-detail-header">
      <div class="user-avatar"><iconify-icon icon="lucide:user-circle-2"></iconify-icon></div>
      <div>
        <div class="user-detail-name"><?= cf_e($detail_user['display'] ?? $detail_user['username']) ?></div>
        <div class="user-detail-meta">
          @<?= cf_e($detail_user['username']) ?>
          <?php if (!empty($detail_user['email'])): ?> · <?= cf_e($detail_user['email']) ?><?php endif; ?>
          · <span class="badge <?= ($detail_user['role'] ?? '') === 'admin' ? 'badge-yellow' : 'badge-gray' ?>"><?= cf_e(ucfirst($detail_user['role'] ?? 'user')) ?></span>
          · <span class="badge badge-blue"><?= cf_e(ucfirst($detail_user['plan'] ?? 'free')) ?></span>
        </div>
      </div>
    </div>

    <?php
      $du = $detail_user['username'];
      $du_events   = AuditStore::eventsForUser($du);
      $du_pviews   = AuditStore::pageViewsForUser($du);
      $du_tickets  = AuditStore::supportTicketsForUser($du);
      $du_tokens   = UserStore::tokenHistoryForUser($du, 50);
      $du_payments = UserStore::paymentsForUser($du);

      // Time on pages
      $du_total_time = array_sum(array_column($du_pviews, 'time_on_page'));
      // Page view counts per page for this user
      $du_page_counts = [];
      foreach ($du_pviews as $pv) {
        $pg = $pv['page'] ?? 'unknown';
        $du_page_counts[$pg] = ($du_page_counts[$pg] ?? 0) + 1;
      }
      arsort($du_page_counts);
    ?>

    <div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">
      <div class="stat-card">
        <div class="stat-card-label">Tokens Used</div>
        <div class="stat-card-value"><?= number_format((int)($detail_user['tokens_used'] ?? 0)) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Page Views</div>
        <div class="stat-card-value"><?= number_format(count($du_pviews)) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Time on Site</div>
        <div class="stat-card-value"><?= floor($du_total_time / 60) ?>m</div>
        <div class="stat-card-sub"><?= $du_total_time ?>s total</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Support Tickets</div>
        <div class="stat-card-value"><?= count($du_tickets) ?></div>
      </div>
    </div>

    <!-- Pages navigated -->
    <div class="adm-section">
      <div class="adm-section-header"><h2>Pages Navigated</h2></div>
      <div class="adm-section-body">
        <?php if (empty($du_pviews)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:compass"></iconify-icon>No page views recorded.</div>
        <?php else: ?>
          <?php $max_pv = max($du_page_counts); foreach ($du_page_counts as $pg => $cnt): ?>
          <div class="top-pages-bar">
            <div class="top-pages-bar-label"><?= cf_e($pg) ?></div>
            <div class="top-pages-bar-track"><div class="top-pages-bar-fill" style="width:<?= round($cnt/$max_pv*100) ?>%"></div></div>
            <div class="top-pages-bar-count"><?= $cnt ?></div>
          </div>
          <?php endforeach; ?>
          <div style="margin-top:16px">
            <table class="adm-table">
              <thead><tr><th>Time</th><th>Page</th><th>Time Spent (s)</th><th>Referrer</th></tr></thead>
              <tbody>
                <?php foreach (array_slice($du_pviews, 0, 50) as $pv): ?>
                <tr>
                  <td style="white-space:nowrap"><?= cf_e(date('M j, H:i', strtotime($pv['created_at'] ?? 'now'))) ?></td>
                  <td><?= cf_e($pv['page'] ?? '') ?></td>
                  <td><?= (int)($pv['time_on_page'] ?? 0) ?></td>
                  <td style="color:var(--text-subtle)"><?= cf_e($pv['referrer'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Audit events -->
    <div class="adm-section">
      <div class="adm-section-header"><h2>Audit Events</h2></div>
      <div class="adm-section-body">
        <?php if (empty($du_events)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No events.</div>
        <?php else: ?>
          <table class="adm-table">
            <thead><tr><th>Time</th><th>Event</th><th>IP</th><th>Details</th></tr></thead>
            <tbody>
              <?php foreach (array_slice($du_events, 0, 100) as $ev): ?>
              <tr>
                <td style="white-space:nowrap"><?= cf_e(date('M j, Y H:i', strtotime($ev['created_at'] ?? 'now'))) ?></td>
                <td><span class="badge badge-blue"><?= cf_e($ev['event'] ?? '') ?></span></td>
                <td style="font-family:monospace;font-size:11px"><?= cf_e($ev['ip'] ?? '') ?></td>
                <td style="font-size:11px;color:var(--text-subtle)"><?php
                  $d = $ev['data'] ?? []; unset($d['code_output']);
                  echo cf_e(json_encode($d, JSON_UNESCAPED_SLASHES));
                ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <!-- CodeGen history -->
    <?php if (!empty($du_tokens)): ?>
    <div class="adm-section">
      <div class="adm-section-header"><h2>CodeGen History</h2></div>
      <div class="adm-section-body">
        <table class="adm-table">
          <thead><tr><th>Date</th><th>Action</th><th>Language</th><th>Provider</th><th>Tokens</th></tr></thead>
          <tbody>
            <?php foreach ($du_tokens as $tk): ?>
            <tr>
              <td><?= cf_e(date('M j, Y H:i', strtotime($tk['created_at'] ?? 'now'))) ?></td>
              <td><?= cf_e(ucfirst($tk['action'] ?? '')) ?></td>
              <td><span class="badge badge-blue"><?= cf_e($tk['language'] ?? '') ?></span></td>
              <td><?= cf_e($tk['provider'] ?? '') ?></td>
              <td><?= number_format((int)($tk['tokens_used'] ?? 0)) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- Support tickets -->
    <?php if (!empty($du_tickets)): ?>
    <div class="adm-section">
      <div class="adm-section-header"><h2>Support Tickets</h2></div>
      <div class="adm-section-body">
        <table class="adm-table">
          <thead><tr><th>Date</th><th>Subject</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach ($du_tickets as $tk): ?>
            <tr>
              <td><?= cf_e(date('M j, Y', strtotime($tk['created_at'] ?? 'now'))) ?></td>
              <td><?= cf_e($tk['subject'] ?? '') ?></td>
              <td><span class="badge <?= match($tk['status'] ?? 'open') {
                'open' => 'badge-yellow',
                'in_progress' => 'badge-blue',
                'resolved', 'closed' => 'badge-green',
                default => 'badge-gray',
              } ?>"><?= cf_e(ucfirst(str_replace('_', ' ', $tk['status'] ?? 'open'))) ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <!-- Payments -->
    <?php if (!empty($du_payments)): ?>
    <div class="adm-section">
      <div class="adm-section-header"><h2>Payments</h2></div>
      <div class="adm-section-body">
        <table class="adm-table">
          <thead><tr><th>Date</th><th>Plan</th><th>Amount</th><th>Method</th><th>Txn ID</th></tr></thead>
          <tbody>
            <?php foreach ($du_payments as $pm): ?>
            <tr>
              <td><?= cf_e(date('M j, Y', strtotime($pm['created_at'] ?? 'now'))) ?></td>
              <td><?= cf_e(ucfirst($pm['plan'] ?? '')) ?></td>
              <td>$<?= number_format((float)($pm['amount'] ?? 0), 2) ?></td>
              <td><?= cf_e(ucfirst($pm['method'] ?? '')) ?></td>
              <td style="font-family:monospace;font-size:11px"><?= cf_e($pm['txn_id'] ?? '') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <?php elseif ($active_tab === 'user_detail'): ?>
      <!-- user not found -->
      <div class="adm-header"><h1>User not found</h1></div>
      <a href="/Admin/?tab=users" class="btn-sm"><iconify-icon icon="lucide:arrow-left"></iconify-icon> All Users</a>

    <?php elseif ($active_tab === 'support'): ?>
    <!-- ═══ SUPPORT TICKETS ════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:life-buoy" style="vertical-align:middle;margin-right:8px"></iconify-icon>Support Tickets</h1>
      <p>User-submitted support requests. <?= $open_tickets ?> open.</p>
    </div>
    <div class="adm-section">
      <div class="adm-section-header"><h2>All Tickets (<?= number_format($total_tickets) ?>)</h2></div>
      <div class="adm-section-body">
        <div class="filter-bar">
          <input type="text" class="filter-input" placeholder="Search subject, user, email…" oninput="filterTable('ticketsTbl',this.value)">
          <select class="filter-select" onchange="filterTable('ticketsTbl',document.getElementById('ticketsTbl').dataset.q||'',this.value,'status')">
            <option value="">All statuses</option>
            <option value="open">Open</option>
            <option value="in_progress">In Progress</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
          </select>
        </div>
        <?php if (empty($all_tickets)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No support tickets yet.</div>
        <?php else: ?>
          <table class="adm-table" id="ticketsTbl">
            <thead><tr><th>Date</th><th>User</th><th>Email</th><th>Subject</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              <?php foreach ($all_tickets as $tk): ?>
              <tr>
                <td style="white-space:nowrap"><?= cf_e(date('M j, Y', strtotime($tk['created_at'] ?? 'now'))) ?></td>
                <td><?= $tk['username'] ? '<a href="/Admin/?tab=user_detail&u='.urlencode($tk['username']).'">'.cf_e($tk['username']).'</a>' : cf_e($tk['name'] ?? '–') ?></td>
                <td><?= cf_e($tk['email'] ?? '') ?></td>
                <td style="max-width:220px"><?= cf_e($tk['subject'] ?? '') ?></td>
                <td class="ticket-status-cell"><span class="badge <?= match($tk['status'] ?? 'open') {
                  'open' => 'badge-yellow',
                  'in_progress' => 'badge-blue',
                  'resolved', 'closed' => 'badge-green',
                  default => 'badge-gray',
                } ?>"><?= cf_e(ucfirst(str_replace('_', ' ', $tk['status'] ?? 'open'))) ?></span></td>
                <td>
                  <form method="post" action="/Admin/?tab=support" style="display:inline-flex;gap:4px;align-items:center">
                    <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
                    <input type="hidden" name="action" value="update_ticket">
                    <input type="hidden" name="ticket_id" value="<?= cf_e($tk['id'] ?? '') ?>">
                    <select name="status" class="filter-select" style="padding:4px 8px;font-size:11px">
                      <?php foreach (['open','in_progress','resolved','closed'] as $st): ?>
                        <option value="<?= cf_e($st) ?>" <?= ($tk['status'] ?? 'open') === $st ? 'selected' : '' ?>><?= cf_e(ucfirst(str_replace('_',' ',$st))) ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn-sm btn-sm-primary">Save</button>
                  </form>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <?php elseif ($active_tab === 'live_chat'): ?>
    <!-- ═══ LIVE CHAT ══════════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:message-circle" style="vertical-align:middle;margin-right:8px"></iconify-icon>Live Support Chat</h1>
      <p>Real-time conversations with users. Respond to open chats below.</p>
    </div>

    <div class="chat-admin-layout">
      <!-- Sessions list -->
      <div class="chat-sessions-panel">
        <div class="chat-sessions-header">
          <h3>All Conversations <span id="adminSessionCount" style="color:var(--text-subtle);font-weight:400"></span></h3>
        </div>
        <div class="chat-sessions-list" id="adminSessionsList">
          <div style="padding:16px;text-align:center;color:var(--text-subtle);font-size:12px">Loading…</div>
        </div>
      </div>

      <!-- Messages panel -->
      <div class="chat-main-panel" id="adminChatMainPanel">
        <div class="chat-empty-state" id="adminChatEmptyState">
          <iconify-icon icon="lucide:message-circle"></iconify-icon>
          <p>Select a conversation to view messages and reply.</p>
        </div>
      </div>
    </div>

    <?php elseif ($active_tab === 'analytics'): ?>
    <!-- ═══ ANALYTICS ══════════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:bar-chart-2" style="vertical-align:middle;margin-right:8px"></iconify-icon>Analytics</h1>
      <p>Aggregated usage statistics and traffic data.</p>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-card-label">Total Page Views</div>
        <div class="stat-card-value"><?= number_format($total_pviews) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Unique Pages</div>
        <div class="stat-card-value"><?= number_format(count($pview_counts)) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">CodeGen Requests</div>
        <div class="stat-card-value"><?= number_format(count(array_filter($all_events, fn($e) => ($e['event'] ?? '') === 'codegen.request'))) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Total Revenue</div>
        <div class="stat-card-value">$<?= number_format($total_revenue, 2) ?></div>
      </div>
    </div>

    <!-- Top pages -->
    <div class="adm-section">
      <div class="adm-section-header"><h2>Top Pages</h2></div>
      <div class="adm-section-body">
        <?php if (empty($pview_counts)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:bar-chart"></iconify-icon>No page view data yet.</div>
        <?php else:
          $max_pvc = max($pview_counts);
          foreach (array_slice($pview_counts, 0, 20, true) as $pg => $cnt): ?>
          <div class="top-pages-bar">
            <div class="top-pages-bar-label"><?= cf_e($pg) ?></div>
            <div class="top-pages-bar-track"><div class="top-pages-bar-fill" style="width:<?= round($cnt/$max_pvc*100) ?>%"></div></div>
            <div class="top-pages-bar-count"><?= $cnt ?></div>
          </div>
          <?php endforeach; endif; ?>
      </div>
    </div>

    <!-- CodeGen usage by language -->
    <?php
    $cg_by_lang = [];
    foreach (array_filter($all_events, fn($e) => ($e['event'] ?? '') === 'codegen.request') as $ev) {
        $lang = $ev['data']['language'] ?? 'unknown';
        $cg_by_lang[$lang] = ($cg_by_lang[$lang] ?? 0) + 1;
    }
    arsort($cg_by_lang);
    if (!empty($cg_by_lang)):
    ?>
    <div class="adm-section">
      <div class="adm-section-header"><h2>CodeGen Requests by Language</h2></div>
      <div class="adm-section-body">
        <?php $max_lang = max($cg_by_lang);
        foreach ($cg_by_lang as $lang => $cnt): ?>
          <div class="top-pages-bar">
            <div class="top-pages-bar-label"><?= cf_e($lang) ?></div>
            <div class="top-pages-bar-track"><div class="top-pages-bar-fill" style="width:<?= round($cnt/$max_lang*100) ?>%"></div></div>
            <div class="top-pages-bar-count"><?= $cnt ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Provider usage -->
    <?php
    $cg_by_provider = [];
    foreach (array_filter($all_events, fn($e) => ($e['event'] ?? '') === 'codegen.request') as $ev) {
        $prov = $ev['data']['provider'] ?? 'unknown';
        $cg_by_provider[$prov] = ($cg_by_provider[$prov] ?? 0) + 1;
    }
    arsort($cg_by_provider);
    if (!empty($cg_by_provider)):
    ?>
    <div class="adm-section">
      <div class="adm-section-header"><h2>CodeGen Provider Usage</h2></div>
      <div class="adm-section-body">
        <?php $max_prov = max($cg_by_provider);
        foreach ($cg_by_provider as $prov => $cnt): ?>
          <div class="top-pages-bar">
            <div class="top-pages-bar-label"><?= cf_e($prov) ?></div>
            <div class="top-pages-bar-track"><div class="top-pages-bar-fill" style="width:<?= round($cnt/$max_prov*100) ?>%"></div></div>
            <div class="top-pages-bar-count"><?= $cnt ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Signup events by day (last 14 days) -->
    <div class="adm-section">
      <div class="adm-section-header"><h2>Signups – Last 14 Days</h2></div>
      <div class="adm-section-body">
        <?php
        $signup_by_day = [];
        for ($i = 13; $i >= 0; $i--) {
            $signup_by_day[date('Y-m-d', strtotime("-$i days"))] = 0;
        }
        foreach (array_filter($all_events, fn($e) => ($e['event'] ?? '') === 'user.signup') as $ev) {
            $day = date('Y-m-d', strtotime($ev['created_at'] ?? 'now'));
            if (isset($signup_by_day[$day])) {
                $signup_by_day[$day]++;
            }
        }
        $max_sd = max($signup_by_day) ?: 1;
        foreach ($signup_by_day as $day => $cnt):
        ?>
          <div class="top-pages-bar">
            <div class="top-pages-bar-label"><?= cf_e(date('M j', strtotime($day))) ?></div>
            <div class="top-pages-bar-track"><div class="top-pages-bar-fill" style="width:<?= round($cnt/$max_sd*100) ?>%"></div></div>
            <div class="top-pages-bar-count"><?= $cnt ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Payment history -->
    <?php if (!empty($all_payments_raw)): ?>
    <div class="adm-section">
      <div class="adm-section-header"><h2>Payment History</h2></div>
      <div class="adm-section-body">
        <table class="adm-table">
          <thead><tr><th>Date</th><th>User</th><th>Plan</th><th>Amount</th><th>Method</th><th>Txn ID</th></tr></thead>
          <tbody>
            <?php foreach (array_reverse($all_payments_raw) as $pm): ?>
            <tr>
              <td><?= cf_e(date('M j, Y', strtotime($pm['created_at'] ?? 'now'))) ?></td>
              <td><?= cf_e($pm['username'] ?? '') ?></td>
              <td><?= cf_e(ucfirst($pm['plan'] ?? '')) ?></td>
              <td>$<?= number_format((float)($pm['amount'] ?? 0), 2) ?></td>
              <td><?= cf_e(ucfirst($pm['method'] ?? '')) ?></td>
              <td style="font-family:monospace;font-size:11px"><?= cf_e($pm['txn_id'] ?? '') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>

    <?php elseif ($active_tab === 'architecture'): ?>
    <!-- ═══ ARCHITECTURE ═══════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:layout" style="vertical-align:middle;margin-right:8px"></iconify-icon>System Architecture</h1>
      <p>Full overview of CodeFoundry components and their relationships.</p>
    </div>

    <div class="adm-section">
      <div class="adm-section-header"><h2>Technology Stack</h2></div>
      <div class="adm-section-body">
        <div class="arch-grid">
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:server"></iconify-icon>Backend / Runtime</div>
            <p>PHP (strict types) serves all pages and API endpoints. No framework — pure PHP with a small set of shared includes.</p>
            <ul>
              <li>PHP 8.x, strict types</li>
              <li>Apache / Nginx via document root</li>
              <li>Session-based auth (PHP sessions)</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:database"></iconify-icon>Data Storage</div>
            <p>All persistent data is stored as flat JSON files under <code>UserAccountData/</code>. Writes use atomic temp-file + rename for safety.</p>
            <ul>
              <li>users.json – accounts &amp; profiles</li>
              <li>token_history.json – AI usage log</li>
              <li>projects.json – saved IDE projects</li>
              <li>payments.json – transaction records</li>
              <li>audit_log.json – immutable event trail</li>
              <li>page_views.json – navigation tracking</li>
              <li>support_tickets.json – support requests</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:globe"></iconify-icon>Frontend</div>
            <p>Vanilla HTML + CSS + minimal JavaScript. Iconify icons loaded from CDN. Inter font via Google Fonts.</p>
            <ul>
              <li>assets/css/site.css – global styles</li>
              <li>assets/js/site.js – global interactions</li>
              <li>Iconify CDN – icon rendering</li>
              <li>Page-view tracker (footer.php)</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:brain-circuit"></iconify-icon>AI / CodeGen</div>
            <p>Multi-provider AI code generation abstracted by <code>lib/CodeGenProvider.php</code>. Free-tier users are restricted to Pollinations.</p>
            <ul>
              <li>Pollinations AI (free, no key)</li>
              <li>Groq, OpenRouter, Together AI</li>
              <li>HuggingFace Inference API</li>
              <li>Ollama (local)</li>
              <li>OpenAI</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:terminal"></iconify-icon>Code Execution (IDE)</div>
            <p>User code is executed in sandboxed Docker containers via <code>IDE/run.php</code> with a Piston-compatible response shape.</p>
            <ul>
              <li>--network=none, --cap-drop=ALL</li>
              <li>Resource limits (memory, CPU, time)</li>
              <li>Custom images: TypeScript, Kotlin, Lua</li>
              <li>Standard images for Python, Go, etc.</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:credit-card"></iconify-icon>Payments</div>
            <p>Stripe and PayPal REST integrations. Stripe uses Payment Intents; PayPal uses Orders API. Both write to payments.json.</p>
            <ul>
              <li>Stripe Payment Intents</li>
              <li>PayPal Orders API (sandbox/live)</li>
              <li>Plan upgrade on payment success</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:lock"></iconify-icon>Authentication</div>
            <p>Session-based auth. Passwords stored as bcrypt hashes. OAuth via GitHub, Google, and LinkedIn.</p>
            <ul>
              <li>Password login (CF_USERS + users.json)</li>
              <li>GitHub / Google / LinkedIn OAuth 2.0</li>
              <li>CSRF tokens on all POST forms</li>
              <li>Brute-force delay on failed logins</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:layout-list"></iconify-icon>Shared Libraries</div>
            <p>Thin PHP classes under <code>lib/</code> encapsulate all data access.</p>
            <ul>
              <li>lib/UserStore.php – users, tokens, projects, payments</li>
              <li>lib/AuditStore.php – audit log, page views, tickets</li>
              <li>lib/CodeGenProvider.php – AI provider registry</li>
              <li>includes/auth.php – login guard</li>
              <li>includes/header.php / footer.php – page chrome</li>
            </ul>
          </div>
          <div class="arch-card">
            <div class="arch-card-title"><iconify-icon icon="lucide:folder-open"></iconify-icon>Page Structure</div>
            <p>Each top-level directory is a standalone page/feature with its own <code>index.php</code>.</p>
            <ul>
              <li>/ – marketing homepage</li>
              <li>/IDE/ – online code editor</li>
              <li>/Dashboard/ – user dashboard</li>
              <li>/Admin/ – admin control panel</li>
              <li>/Checkout/ – payment flow</li>
              <li>/Signup/, /Login/ – auth pages</li>
              <li>/Support/ – support + ticket form</li>
              <li>/Generate/ – AI code generator</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Config constants reference -->
    <div class="adm-section">
      <div class="adm-section-header"><h2>Configuration Constants</h2></div>
      <div class="adm-section-body">
        <table class="adm-table">
          <thead><tr><th>Constant</th><th>Description</th><th>Set via</th></tr></thead>
          <tbody>
            <tr><td><code>CF_ROOT</code></td><td>Absolute path to repository root</td><td>config.php</td></tr>
            <tr><td><code>CF_USERS</code></td><td>Hard-coded admin accounts (bcrypt hashes)</td><td>config.php</td></tr>
            <tr><td><code>CF_PLANS</code></td><td>Plan definitions (tokens, price)</td><td>config.php</td></tr>
            <tr><td><code>CF_CODEGEN_PROVIDERS</code></td><td>AI provider registry</td><td>config.php</td></tr>
            <tr><td><code>CF_STRIPE_*</code></td><td>Stripe API keys</td><td>env vars</td></tr>
            <tr><td><code>CF_PAYPAL_*</code></td><td>PayPal API credentials + mode</td><td>env vars</td></tr>
            <tr><td><code>CF_OAUTH_*</code></td><td>GitHub / Google / LinkedIn OAuth creds</td><td>env vars</td></tr>
            <tr><td><code>CF_DATA_*</code></td><td>Paths to JSON data files</td><td>config.php</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <?php elseif ($active_tab === 'workflows'): ?>
    <!-- ═══ WORKFLOWS ══════════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:git-pull-request" style="vertical-align:middle;margin-right:8px"></iconify-icon>End-to-End Workflows</h1>
      <p>Key user and system journeys across the entire platform.</p>
    </div>

    <div class="adm-section">
      <div class="adm-section-header"><h2>Workflow Diagrams</h2></div>
      <div class="adm-section-body">

        <div class="wf-section">
          <div class="wf-title">1. User Registration &amp; Onboarding</div>
          <div class="wf-step"><div class="wf-step-num">1</div><div class="wf-step-body"><h4>Visit /Signup/</h4><p>User fills out username, display name, email and password. CSRF token validated on submit.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">2</div><div class="wf-step-body"><h4>Validation</h4><p>Server validates uniqueness, format, password strength. Returns errors inline or proceeds.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">3</div><div class="wf-step-body"><h4>Account Created</h4><p>UserStore::createUser() writes to users.json. AuditStore logs user.signup event.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">4</div><div class="wf-step-body"><h4>Auto-login</h4><p>Session is regenerated and cf_user set. User redirected to /Dashboard/.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">5</div><div class="wf-step-body"><h4>Alternative: OAuth</h4><p>/Login/oauth.php initiates OAuth flow (GitHub/Google/LinkedIn). Callback at /Login/oauth_callback.php creates or links account, then logs user in.</p></div></div>
        </div>

        <div class="wf-section">
          <div class="wf-title">2. Login &amp; Session Management</div>
          <div class="wf-step"><div class="wf-step-num">1</div><div class="wf-step-body"><h4>POST /Login/</h4><p>Credentials checked against CF_USERS (hardcoded) then users.json (self-registered). Password verified with password_verify().</p></div></div>
          <div class="wf-step"><div class="wf-step-num">2</div><div class="wf-step-body"><h4>Success</h4><p>session_regenerate_id() called. $_SESSION['cf_user'] set with username, display, role. AuditStore logs user.login.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">3</div><div class="wf-step-body"><h4>Failure</h4><p>1-second sleep (brute-force mitigation). AuditStore logs user.login_failed. Error shown to user.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">4</div><div class="wf-step-body"><h4>Logout</h4><p>GET /Login/logout.php destroys session, clears cookie, AuditStore logs user.logout.</p></div></div>
        </div>

        <div class="wf-section">
          <div class="wf-title">3. AI Code Generation (IDE)</div>
          <div class="wf-step"><div class="wf-step-num">1</div><div class="wf-step-body"><h4>Open /IDE/</h4><p>User selects language, provider and model. Free-plan users see only Pollinations AI.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">2</div><div class="wf-step-body"><h4>POST /IDE/codegen.php</h4><p>JSON body with action, prompt, language, provider, model. Plan check: free users restricted to free_tier providers.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">3</div><div class="wf-step-body"><h4>Provider Dispatch</h4><p>CodeGenProvider resolves provider config, builds OpenAI-compatible chat request, streams response.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">4</div><div class="wf-step-body"><h4>Token Accounting</h4><p>Response token count written to token_history.json and user tokens_used incremented. AuditStore logs codegen.request.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">5</div><div class="wf-step-body"><h4>Code Execution</h4><p>User can run code via POST /IDE/run.php which spawns a sandboxed Docker container. Stdout/stderr returned as Piston-compatible JSON.</p></div></div>
        </div>

        <div class="wf-section">
          <div class="wf-title">4. Plan Upgrade &amp; Payment</div>
          <div class="wf-step"><div class="wf-step-num">1</div><div class="wf-step-body"><h4>/Pricing/ or /Dashboard/resources/</h4><p>User selects a plan and billing frequency, clicks "Upgrade".</p></div></div>
          <div class="wf-step"><div class="wf-step-num">2</div><div class="wf-step-body"><h4>/Checkout/</h4><p>Stripe: POST /Checkout/create_intent.php creates a Payment Intent. PayPal: POST /Checkout/paypal_order.php creates a PayPal Order.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">3</div><div class="wf-step-body"><h4>Payment Confirmation</h4><p>Stripe redirects to /Checkout/complete.php with payment_intent param. PayPal calls /Checkout/capture_paypal.php then redirects.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">4</div><div class="wf-step-body"><h4>Plan Upgrade</h4><p>complete.php verifies payment server-side. UserStore::savePayment() records transaction and upgrades user plan. AuditStore logs payment.completed.</p></div></div>
        </div>

        <div class="wf-section">
          <div class="wf-title">5. Support Request Submission</div>
          <div class="wf-step"><div class="wf-step-num">1</div><div class="wf-step-body"><h4>/Support/</h4><p>User fills out the support form (name, email, subject, message). CSRF token validated.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">2</div><div class="wf-step-body"><h4>Ticket Created</h4><p>AuditStore::createSupportTicket() writes to support_tickets.json and also logs support.ticket_created in the audit log.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">3</div><div class="wf-step-body"><h4>Admin Review</h4><p>Admin visits /Admin/?tab=support to view all tickets, update their status (open / in_progress / resolved / closed).</p></div></div>
        </div>

        <div class="wf-section">
          <div class="wf-title">6. Page View Tracking</div>
          <div class="wf-step"><div class="wf-step-num">1</div><div class="wf-step-body"><h4>Page Load</h4><p>JavaScript in footer.php fires a POST to /track.php with the current page path and document.referrer.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">2</div><div class="wf-step-body"><h4>Unload / Tab Hidden</h4><p>visibilitychange event sends a second POST with time_on_page (seconds since load). Uses navigator.sendBeacon for reliability.</p></div></div>
          <div class="wf-step"><div class="wf-step-num">3</div><div class="wf-step-body"><h4>Storage</h4><p>track.php calls AuditStore::logPageView() which writes to page_views.json. Username is read from the session (empty for guests).</p></div></div>
          <div class="wf-step"><div class="wf-step-num">4</div><div class="wf-step-body"><h4>Admin Analytics</h4><p>Aggregated counts and per-user navigation visible in /Admin/?tab=analytics and /Admin/?tab=user_detail.</p></div></div>
        </div>

      </div>
    </div>

    <?php endif; ?>

  </main>
</div>

<script>
// Table client-side filtering
function filterTable(tblId, query, colFilter, colKey) {
  var tbl = document.getElementById(tblId);
  if (!tbl) return;
  tbl.dataset.q = query;
  var q = query.toLowerCase();
  var rows = tbl.tBodies[0].rows;
  for (var i = 0; i < rows.length; i++) {
    var text = rows[i].innerText.toLowerCase();
    var visible = !q || text.includes(q);
    if (visible && colFilter) {
      visible = !colFilter || text.includes(colFilter.toLowerCase());
    }
    rows[i].style.display = visible ? '' : 'none';
  }
}

// ── Admin Live Chat ────────────────────────────────────────────────────────
(function () {
  // Only run on the live_chat tab
  if (!document.getElementById('adminSessionsList')) return;

  var currentSessionId = null;
  var lastMessageId    = '';
  var pollTimer        = null;
  var sessions         = [];
  var POLL_INTERVAL    = 4000;

  function api(action, extra, cb) {
    fetch('/Admin/chat_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(Object.assign({ action: action }, extra))
    })
    .then(function (r) { return r.json(); })
    .then(cb)
    .catch(function () {});
  }

  function fmtTime(iso) {
    if (!iso) return '';
    var d = new Date(iso);
    var h = d.getHours(), m = d.getMinutes();
    var ampm = h >= 12 ? 'pm' : 'am';
    h = h % 12 || 12;
    return h + ':' + (m < 10 ? '0' : '') + m + ' ' + ampm;
  }

  function fmtDate(iso) {
    if (!iso) return '';
    var d = new Date(iso);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return months[d.getMonth()] + ' ' + d.getDate();
  }

  function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function loadSessions() {
    api('sessions', {}, function (data) {
      sessions = data.sessions || [];
      renderSessionsList();
      updateAdminBadge(data.unread_total || 0);
    });
  }

  function renderSessionsList() {
    var el = document.getElementById('adminSessionsList');
    var countEl = document.getElementById('adminSessionCount');
    if (countEl) countEl.textContent = '(' + sessions.length + ')';
    if (!sessions.length) {
      el.innerHTML = '<div style="padding:16px;text-align:center;color:var(--text-subtle);font-size:11px">No conversations yet.</div>';
      return;
    }
    var html = '';
    sessions.forEach(function (s) {
      var active = s.id === currentSessionId ? ' active' : '';
      var statusIcon = s.status === 'closed'
        ? '<span class="badge-status-closed">Closed</span>'
        : '<span class="badge-status-open">Open</span>';
      var unreadBadge = (s.unread_admin > 0)
        ? '<span class="chat-session-unread">' + s.unread_admin + '</span>'
        : '';
      html += '<div class="chat-session-item' + active + '" onclick="adminSelectSession(\'' + escHtml(s.id) + '\')">'
            + '<div class="chat-session-subject">' + escHtml(s.subject) + '</div>'
            + '<div class="chat-session-meta">'
            + '<span>' + escHtml(s.username) + ' · ' + statusIcon + '</span>'
            + unreadBadge
            + '</div>'
            + '<div style="font-size:9px;color:var(--text-subtle);margin-top:1px">' + fmtDate(s.updated_at || s.created_at) + '</div>'
            + '</div>';
    });
    el.innerHTML = html;
  }

  function updateAdminBadge(count) {
    var badge = document.getElementById('adminChatBadge');
    if (!badge) return;
    if (count > 0) {
      badge.textContent = count;
      badge.style.display = '';
    } else {
      badge.style.display = 'none';
    }
  }

  window.adminSelectSession = function (sessionId) {
    currentSessionId = sessionId;
    lastMessageId    = '';
    clearInterval(pollTimer);

    var session = null;
    for (var i = 0; i < sessions.length; i++) {
      if (sessions[i].id === sessionId) { session = sessions[i]; break; }
    }

    renderAdminChatPanel(session);
    adminPollMessages();
    pollTimer = setInterval(adminPollMessages, POLL_INTERVAL);
    renderSessionsList();
  };

  function renderAdminChatPanel(session) {
    var panel = document.getElementById('adminChatMainPanel');
    var isClosed = session && session.status === 'closed';

    var headerHtml = session
      ? '<div class="chat-header">'
        + '<div><div class="chat-header-title">' + escHtml(session.subject) + '</div>'
        + '<div class="chat-header-sub">User: ' + escHtml(session.username) + ' · Started ' + fmtDate(session.created_at)
        + ' · ' + (isClosed ? '<span class="badge-status-closed">Closed</span>' : '<span class="badge-status-open">Open</span>')
        + '</div></div>'
        + (isClosed
          ? '<button class="btn-sm" onclick="adminToggleSession(\'open\')" style="font-size:11px">Reopen</button>'
          : '<button class="btn-sm" onclick="adminToggleSession(\'closed\')" style="font-size:11px">Close chat</button>')
        + '</div>'
      : '';

    var inputHtml = !isClosed
      ? '<div class="chat-input-area">'
        + '<textarea id="adminChatInput" class="chat-input" rows="1" placeholder="Type a reply…" maxlength="4000" onkeydown="adminHandleKey(event)" oninput="adminAutoResize(this)"></textarea>'
        + '<button class="chat-send-btn" id="adminSendBtn" onclick="adminSendMessage()">'
        + '<iconify-icon icon="lucide:send" style="vertical-align:middle"></iconify-icon> Send</button>'
        + '</div>'
      : '<div style="padding:10px 16px;text-align:center;font-size:11px;color:var(--text-subtle);border-top:1px solid var(--border-color)">This conversation is closed.</div>';

    panel.innerHTML = headerHtml
      + '<div class="chat-messages" id="adminChatMessages"><div style="text-align:center;color:var(--text-subtle);font-size:12px;padding:16px">Loading…</div></div>'
      + inputHtml;
  }

  function adminPollMessages() {
    if (!currentSessionId) return;
    api('poll', { session_id: currentSessionId, after_id: lastMessageId }, function (data) {
      if (!data || data.error) return;

      var msgs = data.messages || [];
      if (msgs.length > 0) {
        appendAdminMessages(msgs);
        lastMessageId = msgs[msgs.length - 1].id;
      }

      if (data.session) {
        for (var i = 0; i < sessions.length; i++) {
          if (sessions[i].id === data.session.id) { sessions[i] = data.session; break; }
        }
        renderSessionsList();
        updateAdminBadge(0);
      }
    });
  }

  function appendAdminMessages(msgs) {
    var box = document.getElementById('adminChatMessages');
    if (!box) return;

    if (lastMessageId === '' && msgs.length > 0) {
      box.innerHTML = '';
    } else if (lastMessageId === '' && msgs.length === 0) {
      box.innerHTML = '<div style="text-align:center;color:var(--text-subtle);font-size:12px;padding:16px">No messages yet.</div>';
      return;
    }

    msgs.forEach(function (m) {
      var isAdmin = m.sender_role === 'admin';
      var wrap = document.createElement('div');
      wrap.className = 'chat-bubble-wrap ' + (isAdmin ? 'from-me' : 'from-them');
      wrap.innerHTML = '<div class="chat-bubble ' + (isAdmin ? 'from-me' : 'from-them') + '">' + escHtml(m.message).replace(/\n/g,'<br>') + '</div>'
        + '<div class="chat-bubble-meta">' + (isAdmin ? 'Support · ' : escHtml(m.sender) + ' · ') + fmtTime(m.created_at) + '</div>';
      box.appendChild(wrap);
    });

    box.scrollTop = box.scrollHeight;
  }

  window.adminSendMessage = function () {
    var input = document.getElementById('adminChatInput');
    if (!input) return;
    var text = input.value.trim();
    if (!text || !currentSessionId) return;

    var btn = document.getElementById('adminSendBtn');
    if (btn) btn.disabled = true;
    input.disabled = true;

    api('send', { session_id: currentSessionId, message: text }, function (data) {
      if (btn) btn.disabled = false;
      if (input) { input.disabled = false; input.style.height = ''; }
      if (!data || data.error) { alert(data ? data.error : 'Failed to send.'); return; }
      input.value = '';
      adminPollMessages();
    });
  };

  window.adminHandleKey = function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      window.adminSendMessage();
    }
  };

  window.adminAutoResize = function (el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 100) + 'px';
  };

  window.adminToggleSession = function (status) {
    if (!currentSessionId) return;
    api('update_status', { session_id: currentSessionId, status: status }, function (data) {
      if (!data || data.error) { alert('Failed to update session.'); return; }
      clearInterval(pollTimer);
      currentSessionId = null;
      loadSessions();
      document.getElementById('adminChatMainPanel').innerHTML =
        '<div class="chat-empty-state"><iconify-icon icon="lucide:check-circle" style="color:#4ade80"></iconify-icon><p>Session ' + status + '. Select another conversation.</p></div>';
    });
  };

  loadSessions();
  // Background session list refresh – skip when a session is actively being polled
  setInterval(function () { if (!currentSessionId) loadSessions(); }, 10000);
}());

// Background badge refresh (runs on all admin tabs)
(function () {
  var badge = document.getElementById('adminChatBadge');
  if (!badge) return;
  // Only poll if we're NOT on the live_chat tab (the chat IIFE handles that case)
  if (document.getElementById('adminSessionsList')) return;
  function refreshBadge() {
    fetch('/Admin/chat_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ action: 'sessions' })
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
      var count = data.unread_total || 0;
      if (count > 0) { badge.textContent = count; badge.style.display = ''; }
      else { badge.style.display = 'none'; }
    })
    .catch(function () {});
  }
  setInterval(refreshBadge, 15000);
}());
</script>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
