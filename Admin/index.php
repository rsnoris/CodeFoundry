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

$payment_managed_keys = [
    'STRIPE_PUBLISHABLE_KEY' => ['label' => 'Stripe Publishable Key', 'hint' => 'Used by Checkout UI for Stripe and Apple Pay', 'icon' => 'lucide:credit-card', 'is_secret' => false],
    'STRIPE_SECRET_KEY'      => ['label' => 'Stripe Secret Key',      'hint' => 'Server-side key for creating/verifying Stripe payments', 'icon' => 'lucide:lock-keyhole', 'is_secret' => true],
    'PAYPAL_CLIENT_ID'       => ['label' => 'PayPal Client ID',       'hint' => 'PayPal SDK client identifier', 'icon' => 'lucide:wallet', 'is_secret' => false],
    'PAYPAL_CLIENT_SECRET'   => ['label' => 'PayPal Client Secret',   'hint' => 'Server-side PayPal REST API secret', 'icon' => 'lucide:shield', 'is_secret' => true],
    'PAYPAL_MODE'            => ['label' => 'PayPal Mode',            'hint' => 'Allowed values: sandbox or live', 'icon' => 'lucide:toggle-left', 'is_secret' => false],
    'APPLE_PAY_MERCHANT_ID'  => ['label' => 'Apple Pay Merchant ID',  'hint' => 'Merchant identifier used for Apple Pay setup', 'icon' => 'lucide:smartphone', 'is_secret' => false],
    'APPLE_PAY_DOMAIN'       => ['label' => 'Apple Pay Domain',       'hint' => 'Verified Apple Pay domain (for example: codefoundry.cloud)', 'icon' => 'lucide:globe', 'is_secret' => false],
];
const ADMIN_PAYMENT_KEY_VALUE_MAX_LENGTH = 4096;
const ADMIN_PAYMENT_SECRET_PREFIX_LENGTH = 4;
const ADMIN_PAYMENT_SECRET_MAX_MASK_LENGTH = 20;
const ADMIN_PAYMENT_SECRET_MIN_MASK_LENGTH = 8;

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

// ── Handle user management actions ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $admin_user = cf_current_user();
    if (
        ($admin_user['role'] ?? '') === 'admin' &&
        !empty($_POST['csrf_token']) &&
        $_POST['csrf_token'] === ($_SESSION['csrf_token'] ?? '')
    ) {
        $action = $_POST['action'];

        if ($action === 'add_user') {
            $new_username = trim($_POST['new_username'] ?? '');
            $new_display  = trim($_POST['new_display']  ?? '');
            $new_email    = trim($_POST['new_email']    ?? '');
            $new_password = $_POST['new_password'] ?? '';
            $new_role     = in_array($_POST['new_role'] ?? '', ['user', 'admin'], true) ? $_POST['new_role'] : 'user';
            $new_plan     = in_array($_POST['new_plan'] ?? '', array_keys(CF_PLANS), true) ? $_POST['new_plan'] : 'free';
            if ($new_username !== '' && $new_password !== '') {
                $hash = password_hash($new_password, PASSWORD_DEFAULT);
                $ok   = UserStore::createUser($new_username, $new_display ?: $new_username, $new_email, $hash, $new_role, $new_plan);
                if ($ok) {
                    AuditStore::log('admin.user_added', $admin_user['username'], ['target' => $new_username]);
                }
            }
            header('Location: /Admin/?tab=users');
            exit;
        }

        if ($action === 'update_user') {
            $target = trim($_POST['target_username'] ?? '');
            if ($target !== '') {
                $fields = [];
                if (isset($_POST['upd_display']))  $fields['display'] = trim($_POST['upd_display']);
                if (isset($_POST['upd_email']))     $fields['email']   = trim($_POST['upd_email']);
                $upd_plan = $_POST['upd_plan'] ?? '';
                if (in_array($upd_plan, array_keys(CF_PLANS), true)) {
                    $fields['plan'] = $upd_plan;
                }
                $upd_role = $_POST['upd_role'] ?? '';
                if (in_array($upd_role, ['user', 'admin'], true)) {
                    $fields['role'] = $upd_role;
                }
                if (!empty($_POST['upd_password'])) {
                    $fields['password_hash'] = password_hash($_POST['upd_password'], PASSWORD_DEFAULT);
                }
                if (!empty($fields)) {
                    UserStore::updateUser($target, $fields);
                    AuditStore::log('admin.user_updated', $admin_user['username'], ['target' => $target, 'fields' => array_keys($fields)]);
                }
            }
            header('Location: /Admin/?tab=user_detail&u=' . urlencode($target));
            exit;
        }

        if ($action === 'freeze_user') {
            $target = trim($_POST['target_username'] ?? '');
            if ($target !== '') {
                UserStore::updateUser($target, ['frozen' => true]);
                AuditStore::log('admin.user_frozen', $admin_user['username'], ['target' => $target]);
            }
            $redirect_tab = trim($_POST['redirect_tab'] ?? 'users');
            $safe_tab = in_array($redirect_tab, ['users', 'user_detail'], true) ? $redirect_tab : 'users';
            header('Location: /Admin/?tab=' . $safe_tab . ($safe_tab === 'user_detail' ? '&u=' . urlencode($target) : ''));
            exit;
        }

        if ($action === 'unfreeze_user') {
            $target = trim($_POST['target_username'] ?? '');
            if ($target !== '') {
                UserStore::updateUser($target, ['frozen' => false, 'failed_login_attempts' => 0]);
                AuditStore::log('admin.user_unfrozen', $admin_user['username'], ['target' => $target]);
            }
            $redirect_tab = trim($_POST['redirect_tab'] ?? 'users');
            $safe_tab = in_array($redirect_tab, ['users', 'user_detail'], true) ? $redirect_tab : 'users';
            header('Location: /Admin/?tab=' . $safe_tab . ($safe_tab === 'user_detail' ? '&u=' . urlencode($target) : ''));
            exit;
        }

        if ($action === 'save_api_key') {
            $key_name  = trim($_POST['key_name'] ?? '');
            $key_value = trim($_POST['key_value'] ?? '');
            if ($key_name !== '' && preg_match('/^[A-Za-z0-9_]+$/', $key_name)) {
                cf_save_key($key_name, $key_value);
                AuditStore::log('admin.api_key_saved', $admin_user['username'], ['key' => $key_name]);
            }
            header('Location: /Admin/?tab=api_keys&saved=' . urlencode($key_name));
            exit;
        }

        if ($action === 'clear_api_key') {
            $key_name = trim($_POST['key_name'] ?? '');
            if ($key_name !== '' && preg_match('/^[A-Za-z0-9_]+$/', $key_name)) {
                cf_save_key($key_name, '');
                AuditStore::log('admin.api_key_cleared', $admin_user['username'], ['key' => $key_name]);
            }
            header('Location: /Admin/?tab=api_keys&cleared=' . urlencode($key_name));
            exit;
        }

        if ($action === 'save_payment_api_key') {
            $key_name       = trim((string)($_POST['key_name'] ?? ''));
            $key_value      = trim((string)($_POST['key_value'] ?? ''));
            $admin_password = (string)($_POST['admin_password'] ?? '');
            $redirect_base  = '/Admin/?tab=payment_api_keys';

            if (!isset($payment_managed_keys[$key_name])) {
                header('Location: ' . $redirect_base . '&error=invalid_key');
                exit;
            }
            if ($key_value === '') {
                header('Location: ' . $redirect_base . '&error=empty_value&key=' . urlencode($key_name));
                exit;
            }
            if (strlen($key_value) > ADMIN_PAYMENT_KEY_VALUE_MAX_LENGTH) {
                header('Location: ' . $redirect_base . '&error=value_too_long&key=' . urlencode($key_name));
                exit;
            }
            if ($key_name === 'PAYPAL_MODE' && !in_array(strtolower($key_value), ['sandbox', 'live'], true)) {
                header('Location: ' . $redirect_base . '&error=invalid_paypal_mode&key=' . urlencode($key_name));
                exit;
            }

            $had_existing = cf_load_key($key_name) !== '';
            if ($had_existing) {
                if ($admin_password === '') {
                    header('Location: ' . $redirect_base . '&error=reauth_required&key=' . urlencode($key_name));
                    exit;
                }
                $admin_record = UserStore::findUser((string)($admin_user['username'] ?? ''));
                $password_hash = (string)($admin_record['password_hash'] ?? '');
                if ($password_hash === '' || !password_verify($admin_password, $password_hash)) {
                    AuditStore::log('admin.payment_api_key_reauth_failed', $admin_user['username'], ['key' => $key_name, 'action' => 'save']);
                    header('Location: ' . $redirect_base . '&error=reauth_failed&key=' . urlencode($key_name));
                    exit;
                }
            }

            cf_save_key($key_name, $key_value);
            AuditStore::log('admin.payment_api_key_saved', $admin_user['username'], [
                'key'    => $key_name,
                'action' => $had_existing ? 'rotated' : 'saved',
            ]);
            header('Location: ' . $redirect_base . '&saved=' . urlencode($key_name) . '&mode=' . ($had_existing ? 'rotated' : 'saved'));
            exit;
        }

        if ($action === 'delete_payment_api_key') {
            $key_name       = trim((string)($_POST['key_name'] ?? ''));
            $admin_password = (string)($_POST['admin_password'] ?? '');
            $redirect_base  = '/Admin/?tab=payment_api_keys';

            if (!isset($payment_managed_keys[$key_name])) {
                header('Location: ' . $redirect_base . '&error=invalid_key');
                exit;
            }
            if ($admin_password === '') {
                header('Location: ' . $redirect_base . '&error=reauth_required&key=' . urlencode($key_name));
                exit;
            }

            $admin_record = UserStore::findUser((string)($admin_user['username'] ?? ''));
            $password_hash = (string)($admin_record['password_hash'] ?? '');
            if ($password_hash === '' || !password_verify($admin_password, $password_hash)) {
                AuditStore::log('admin.payment_api_key_reauth_failed', $admin_user['username'], ['key' => $key_name, 'action' => 'delete']);
                header('Location: ' . $redirect_base . '&error=reauth_failed&key=' . urlencode($key_name));
                exit;
            }

            cf_save_key($key_name, '');
            AuditStore::log('admin.payment_api_key_deleted', $admin_user['username'], ['key' => $key_name]);
            header('Location: ' . $redirect_base . '&deleted=' . urlencode($key_name));
            exit;
        }
    }
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
$all_users     = UserStore::allUsers();
$all_events    = AuditStore::allEvents();
$all_pviews    = AuditStore::allPageViews(500);
$all_tickets   = AuditStore::allSupportTickets();
$pview_counts  = AuditStore::pageViewCounts();
$login_events  = AuditStore::loginEvents();

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
$frozen_users   = count(array_filter($all_users, fn($u) => !empty($u['frozen'])));

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
  .badge-orange { background:rgba(249,115,22,.1); color:#fb923c; border:1px solid rgba(249,115,22,.2); }
  .btn-danger { background:rgba(239,68,68,.12); border-color:rgba(239,68,68,.3); color:#f87171; }
  .btn-danger:hover { border-color:#f87171; color:#fca5a5; }
  .btn-success { background:rgba(34,197,94,.1); border-color:rgba(34,197,94,.25); color:#4ade80; }
  .btn-success:hover { border-color:#4ade80; color:#86efac; }
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
  /* ── API Keys styles ─── */
  .key-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:16px; }
  .key-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:18px 20px; display:flex; flex-direction:column; gap:12px; }
  .key-card-header { display:flex; align-items:center; gap:10px; }
  .key-card-icon { width:34px; height:34px; background:rgba(24,179,255,.1); border:1px solid rgba(24,179,255,.2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; color:var(--primary); flex-shrink:0; }
  .key-card-title { font-size:13px; font-weight:700; color:var(--text); }
  .key-card-hint { font-size:11px; color:var(--text-subtle); margin-top:1px; }
  .key-status-set { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; color:#4ade80; }
  .key-status-unset { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; color:var(--text-subtle); }
  .key-input-row { display:flex; gap:6px; align-items:center; }
  .key-input { flex:1; background:var(--navy); border:1px solid var(--border-color); border-radius:6px; padding:7px 10px; color:var(--text); font-size:12px; outline:none; font-family:monospace; }
  .key-input:focus { border-color:var(--primary); }
  .key-masked { font-size:11px; color:var(--text-subtle); font-family:monospace; letter-spacing:.05em; margin-top:2px; }
  .key-actions { display:flex; gap:6px; flex-wrap:wrap; }
  .btn-key-save { display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:6px; font-size:11px; font-weight:700; cursor:pointer; border:1px solid rgba(24,179,255,.3); background:rgba(24,179,255,.12); color:var(--primary); transition:border-color .15s,background .15s; }
  .btn-key-save:hover { border-color:var(--primary); background:rgba(24,179,255,.2); }
  .btn-key-clear { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1px solid rgba(239,68,68,.25); background:rgba(239,68,68,.07); color:#f87171; transition:border-color .15s; }
  .btn-key-clear:hover { border-color:#f87171; }
  @media(max-width:900px){ .stat-grid{grid-template-columns:repeat(2,1fr);} .arch-grid{grid-template-columns:1fr;} }
  @media(max-width:700px){ .adm-layout{flex-direction:column;padding:0;} .adm-sidebar{width:100%;border-right:none;border-bottom:1px solid var(--border-color);padding:12px 0;display:flex;overflow-x:auto;} .adm-sidebar-title{display:none;} .adm-main{padding:20px 14px 48px;} .stat-grid{grid-template-columns:repeat(2,1fr);} }
  /* ── Docker Instances tab ─── */
  .docker-banner { display:flex; align-items:center; gap:10px; padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:20px; }
  .docker-banner-warn { background:rgba(251,191,36,.08); border:1px solid rgba(251,191,36,.25); color:#fbbf24; }
  .docker-banner-err  { background:rgba(239,68,68,.08);  border:1px solid rgba(239,68,68,.25);  color:#f87171; }
  .docker-banner iconify-icon { font-size:18px; flex-shrink:0; }
  .docker-log-pre { background:var(--navy-3); border:1px solid var(--border-color); border-radius:8px; padding:14px 16px; font-family:monospace; font-size:11px; color:var(--text-muted); white-space:pre-wrap; word-break:break-all; max-height:320px; overflow-y:auto; margin:0; line-height:1.6; }
  .docker-action-bar { display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
  .btn-docker-init { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; border:1px solid rgba(24,179,255,.35); background:rgba(24,179,255,.12); color:var(--primary); transition:background .2s,border-color .2s; }
  .btn-docker-init:hover:not(:disabled) { background:rgba(24,179,255,.22); border-color:var(--primary); }
  .btn-docker-init:disabled { opacity:.5; cursor:not-allowed; }
  .btn-docker-refresh { display:inline-flex; align-items:center; gap:6px; padding:8px 14px; border-radius:8px; font-size:12px; font-weight:600; cursor:pointer; border:1px solid var(--border-color); background:var(--navy-3); color:var(--text-muted); transition:border-color .15s,color .15s; }
  .btn-docker-refresh:hover { border-color:var(--primary); color:var(--primary); }
CSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="adm-layout">
  <!-- Sidebar -->
  <aside class="adm-sidebar">
    <div class="adm-sidebar-title">Control Panel</div>
    <?php
    $tabs = [
        'overview'       => ['icon' => 'lucide:layout-dashboard', 'label' => 'Overview'],
        'audit'          => ['icon' => 'lucide:shield-check',     'label' => 'Audit Trail'],
        'login_history'  => ['icon' => 'lucide:log-in',           'label' => 'Login History'],
        'users'          => ['icon' => 'lucide:users',             'label' => 'Users'],
        'support'        => ['icon' => 'lucide:life-buoy',         'label' => 'Support'],
        'live_chat'      => ['icon' => 'lucide:message-circle',    'label' => 'Live Chat'],
        'analytics'      => ['icon' => 'lucide:bar-chart-2',       'label' => 'Analytics'],
        'api_keys'         => ['icon' => 'lucide:key-round',         'label' => 'API Keys'],
        'payment_api_keys' => ['icon' => 'lucide:credit-card',       'label' => 'Payment API Keys'],
        'docker_instances' => ['icon' => 'lucide:container',        'label' => 'Docker'],
        'architecture'     => ['icon' => 'lucide:layout',            'label' => 'Architecture'],
        'workflows'        => ['icon' => 'lucide:git-pull-request',  'label' => 'Workflows'],
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
            <option value="admin.user_added">admin.user_added</option>
            <option value="admin.user_updated">admin.user_updated</option>
            <option value="admin.user_frozen">admin.user_frozen</option>
            <option value="admin.user_unfrozen">admin.user_unfrozen</option>
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

    <?php elseif ($active_tab === 'login_history'): ?>
    <!-- ═══ LOGIN HISTORY ══════════════════════════════════════════════════ -->
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:log-in" style="vertical-align:middle;margin-right:8px"></iconify-icon>Login History</h1>
      <p>All user login, failed-login, and logout events including IP address, location, and session duration.</p>
    </div>

    <!-- Summary stats -->
    <?php
      $lh_logins   = array_filter($login_events, fn($e) => ($e['event'] ?? '') === 'user.login');
      $lh_failures = array_filter($login_events, fn($e) => ($e['event'] ?? '') === 'user.login_failed');
      $lh_logouts  = array_filter($login_events, fn($e) => ($e['event'] ?? '') === 'user.logout');
      $lh_recent   = array_filter($login_events, fn($e) =>
          ($e['event'] ?? '') === 'user.login' &&
          isset($e['created_at']) && strtotime($e['created_at']) >= strtotime('-24 hours')
      );
    ?>
    <div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:20px">
      <div class="stat-card">
        <div class="stat-card-label">Total Logins</div>
        <div class="stat-card-value"><?= number_format(count($lh_logins)) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Failed Logins</div>
        <div class="stat-card-value" style="<?= count($lh_failures) > 0 ? 'color:#f87171' : '' ?>"><?= number_format(count($lh_failures)) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Logouts</div>
        <div class="stat-card-value"><?= number_format(count($lh_logouts)) ?></div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Logins (24h)</div>
        <div class="stat-card-value"><?= number_format(count($lh_recent)) ?></div>
      </div>
    </div>

    <div class="adm-section">
      <div class="adm-section-header"><h2>All Login Events (<?= number_format(count($login_events)) ?>)</h2></div>
      <div class="adm-section-body">
        <div class="filter-bar">
          <input type="text" class="filter-input" id="lhSearch" placeholder="Search user, IP, location…" oninput="filterTable('lhTbl',this.value)">
          <select class="filter-select" onchange="filterTable('lhTbl',document.getElementById('lhSearch').value,this.value,'event')">
            <option value="">All event types</option>
            <option value="user.login">user.login</option>
            <option value="user.login_failed">user.login_failed</option>
            <option value="user.logout">user.logout</option>
          </select>
        </div>
        <?php if (empty($login_events)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No login events recorded yet.</div>
        <?php else: ?>
          <table class="adm-table" id="lhTbl">
            <thead>
              <tr>
                <th>Time</th>
                <th>Event</th>
                <th>User</th>
                <th>IP Address</th>
                <th>Location</th>
                <th>Method</th>
                <th>Session Duration</th>
                <th>Browser / UA</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($login_events as $ev):
                $ev_data     = $ev['data'] ?? [];
                $ev_location = $ev_data['location'] ?? '';
                $ev_method   = isset($ev_data['provider'])
                    ? 'oauth:' . $ev_data['provider']
                    : ($ev_data['method'] ?? '');
                $ev_ua       = $ev_data['user_agent'] ?? ($ev['user_agent'] ?? '');
                $ev_duration = isset($ev_data['session_duration_seconds'])
                    ? cf_format_duration((int)$ev_data['session_duration_seconds'])
                    : '–';
                $badge_class = match($ev['event'] ?? '') {
                    'user.login'        => 'badge-green',
                    'user.login_failed' => 'badge-red',
                    'user.logout'       => 'badge-gray',
                    default             => 'badge-blue',
                };
              ?>
              <tr>
                <td style="white-space:nowrap"><?= cf_e(date('Y-m-d H:i:s', strtotime($ev['created_at'] ?? 'now'))) ?></td>
                <td><span class="badge <?= $badge_class ?>"><?= cf_e($ev['event'] ?? '') ?></span></td>
                <td><?= $ev['username'] ? '<a href="/Admin/?tab=user_detail&u='.urlencode($ev['username']).'">'.cf_e($ev['username']).'</a>' : '–' ?></td>
                <td style="font-family:monospace;font-size:11px"><?= cf_e($ev['ip'] ?? '') ?></td>
                <td style="font-size:12px"><?= cf_e($ev_location) ?></td>
                <td style="font-size:12px"><?= cf_e($ev_method) ?></td>
                <td style="font-size:12px"><?= cf_e($ev_duration) ?></td>
                <td style="font-size:11px;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text-subtle)" title="<?= cf_e($ev_ua) ?>"><?= cf_e($ev_ua) ?></td>
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
      <p>All registered users – click a username for full details.<?php if ($frozen_users > 0): ?> <span class="badge badge-orange"><?= $frozen_users ?> frozen</span><?php endif; ?></p>
    </div>

    <!-- Add User form -->
    <div class="adm-section" style="margin-bottom:20px">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:user-plus" style="vertical-align:middle;margin-right:6px"></iconify-icon>Add New User</h2>
      </div>
      <div class="adm-section-body">
        <form method="POST" action="/Admin/?tab=users" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;align-items:end">
          <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
          <input type="hidden" name="action" value="add_user">
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Username *</label>
            <input type="text" name="new_username" class="filter-input" placeholder="username" required style="width:100%;box-sizing:border-box">
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Display Name</label>
            <input type="text" name="new_display" class="filter-input" placeholder="Display Name" style="width:100%;box-sizing:border-box">
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Email</label>
            <input type="email" name="new_email" class="filter-input" placeholder="email@example.com" style="width:100%;box-sizing:border-box">
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Password *</label>
            <input type="password" name="new_password" class="filter-input" placeholder="Password" required style="width:100%;box-sizing:border-box">
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Role</label>
            <select name="new_role" class="filter-select" style="width:100%;box-sizing:border-box">
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Plan</label>
            <select name="new_plan" class="filter-select" style="width:100%;box-sizing:border-box">
              <?php foreach (array_keys(CF_PLANS) as $pk): ?>
                <option value="<?= cf_e($pk) ?>"><?= cf_e(ucfirst($pk)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <button type="submit" class="btn-sm btn-sm-primary" style="padding:8px 16px;font-size:12px;width:100%;justify-content:center">
              <iconify-icon icon="lucide:plus"></iconify-icon> Add User
            </button>
          </div>
        </form>
      </div>
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
                <th>Role</th><th>Plan</th><th>Status</th><th>Tokens Used</th>
                <th>Joined</th><th>Action</th>
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
                <td>
                  <?php if (!empty($u['frozen'])): ?>
                    <span class="badge badge-red"><iconify-icon icon="lucide:lock" style="vertical-align:middle"></iconify-icon> Frozen</span>
                  <?php else: ?>
                    <span class="badge badge-green">Active</span>
                  <?php endif; ?>
                </td>
                <td><?= number_format((int)($u['tokens_used'] ?? 0)) ?></td>
                <td style="white-space:nowrap"><?= cf_e(!empty($u['created_at']) ? date('M j, Y', strtotime($u['created_at'])) : '–') ?></td>
                <td style="white-space:nowrap">
                  <?php if (!empty($u['frozen'])): ?>
                    <form method="POST" action="/Admin/?tab=users" style="display:inline">
                      <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
                      <input type="hidden" name="action" value="unfreeze_user">
                      <input type="hidden" name="target_username" value="<?= cf_e($u['username']) ?>">
                      <input type="hidden" name="redirect_tab" value="users">
                      <button type="submit" class="btn-sm btn-success" title="Unfreeze account">
                        <iconify-icon icon="lucide:unlock"></iconify-icon> Unfreeze
                      </button>
                    </form>
                  <?php else: ?>
                    <form method="POST" action="/Admin/?tab=users" style="display:inline">
                      <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
                      <input type="hidden" name="action" value="freeze_user">
                      <input type="hidden" name="target_username" value="<?= cf_e($u['username']) ?>">
                      <input type="hidden" name="redirect_tab" value="users">
                      <button type="submit" class="btn-sm btn-danger" title="Freeze account">
                        <iconify-icon icon="lucide:lock"></iconify-icon> Freeze
                      </button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    </div>

    <?php elseif ($active_tab === 'user_detail' && $detail_user !== null): ?>
    <!-- ═══ USER DETAIL ════════════════════════════════════════════════════ -->
    <div style="margin-bottom:16px;display:flex;align-items:center;gap:8px">
      <a href="/Admin/?tab=users" class="btn-sm"><iconify-icon icon="lucide:arrow-left"></iconify-icon> All Users</a>
    </div>
    <div class="user-detail-header">
      <div class="user-avatar"><iconify-icon icon="lucide:user-circle-2"></iconify-icon></div>
      <div style="flex:1">
        <div class="user-detail-name"><?= cf_e($detail_user['display'] ?? $detail_user['username']) ?></div>
        <div class="user-detail-meta">
          @<?= cf_e($detail_user['username']) ?>
          <?php if (!empty($detail_user['email'])): ?> · <?= cf_e($detail_user['email']) ?><?php endif; ?>
          · <span class="badge <?= ($detail_user['role'] ?? '') === 'admin' ? 'badge-yellow' : 'badge-gray' ?>"><?= cf_e(ucfirst($detail_user['role'] ?? 'user')) ?></span>
          · <span class="badge badge-blue"><?= cf_e(ucfirst($detail_user['plan'] ?? 'free')) ?></span>
          <?php if (!empty($detail_user['frozen'])): ?>
            · <span class="badge badge-red"><iconify-icon icon="lucide:lock" style="vertical-align:middle"></iconify-icon> Frozen</span>
          <?php else: ?>
            · <span class="badge badge-green">Active</span>
          <?php endif; ?>
        </div>
      </div>
      <!-- Freeze / Unfreeze button -->
      <?php if (!empty($detail_user['frozen'])): ?>
        <form method="POST" action="/Admin/?tab=user_detail&u=<?= urlencode($detail_user['username']) ?>">
          <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
          <input type="hidden" name="action" value="unfreeze_user">
          <input type="hidden" name="target_username" value="<?= cf_e($detail_user['username']) ?>">
          <input type="hidden" name="redirect_tab" value="user_detail">
          <button type="submit" class="btn-sm btn-success"><iconify-icon icon="lucide:unlock"></iconify-icon> Unfreeze Account</button>
        </form>
      <?php else: ?>
        <form method="POST" action="/Admin/?tab=user_detail&u=<?= urlencode($detail_user['username']) ?>">
          <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
          <input type="hidden" name="action" value="freeze_user">
          <input type="hidden" name="target_username" value="<?= cf_e($detail_user['username']) ?>">
          <input type="hidden" name="redirect_tab" value="user_detail">
          <button type="submit" class="btn-sm btn-danger"><iconify-icon icon="lucide:lock"></iconify-icon> Freeze Account</button>
        </form>
      <?php endif; ?>
    </div>

    <!-- Edit user form -->
    <div class="adm-section" style="margin-bottom:20px">
      <div class="adm-section-header"><h2><iconify-icon icon="lucide:user-cog" style="vertical-align:middle;margin-right:6px"></iconify-icon>Edit User</h2></div>
      <div class="adm-section-body">
        <form method="POST" action="/Admin/?tab=user_detail&u=<?= urlencode($detail_user['username']) ?>" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:10px;align-items:end">
          <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
          <input type="hidden" name="action" value="update_user">
          <input type="hidden" name="target_username" value="<?= cf_e($detail_user['username']) ?>">
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Display Name</label>
            <input type="text" name="upd_display" class="filter-input" value="<?= cf_e($detail_user['display'] ?? '') ?>" style="width:100%;box-sizing:border-box">
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Email</label>
            <input type="email" name="upd_email" class="filter-input" value="<?= cf_e($detail_user['email'] ?? '') ?>" style="width:100%;box-sizing:border-box">
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Role</label>
            <select name="upd_role" class="filter-select" style="width:100%;box-sizing:border-box">
              <option value="user" <?= ($detail_user['role'] ?? 'user') === 'user' ? 'selected' : '' ?>>User</option>
              <option value="admin" <?= ($detail_user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">Plan</label>
            <select name="upd_plan" class="filter-select" style="width:100%;box-sizing:border-box">
              <?php foreach (array_keys(CF_PLANS) as $pk): ?>
                <option value="<?= cf_e($pk) ?>" <?= ($detail_user['plan'] ?? 'free') === $pk ? 'selected' : '' ?>><?= cf_e(ucfirst($pk)) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label style="font-size:11px;font-weight:600;color:var(--text-muted);display:block;margin-bottom:4px">New Password</label>
            <input type="password" name="upd_password" class="filter-input" placeholder="Leave blank to keep" style="width:100%;box-sizing:border-box">
          </div>
          <div>
            <button type="submit" class="btn-sm btn-sm-primary" style="padding:8px 16px;font-size:12px;width:100%;justify-content:center">
              <iconify-icon icon="lucide:save"></iconify-icon> Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

    <?php
      $du = $detail_user['username'];
      $du_events        = AuditStore::eventsForUser($du);
      $du_login_events  = AuditStore::loginEventsForUser($du);
      $du_pviews        = AuditStore::pageViewsForUser($du);
      $du_tickets       = AuditStore::supportTicketsForUser($du);
      $du_tokens        = UserStore::tokenHistoryForUser($du, 50);
      $du_payments      = UserStore::paymentsForUser($du);

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
      <div class="stat-card">
        <div class="stat-card-label">Failed Login Attempts</div>
        <div class="stat-card-value" style="<?= ((int)($detail_user['failed_login_attempts'] ?? 0)) >= 3 ? 'color:#f87171' : '' ?>"><?= (int)($detail_user['failed_login_attempts'] ?? 0) ?></div>
        <div class="stat-card-sub"><?= !empty($detail_user['frozen']) ? 'Account frozen' : 'of 3 before freeze' ?></div>
      </div>
    </div>

    <!-- Login history for this user -->
    <div class="adm-section">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:log-in" style="vertical-align:middle;margin-right:6px"></iconify-icon>Login History</h2>
        <a href="/Admin/?tab=login_history" class="btn-sm">View all users</a>
      </div>
      <div class="adm-section-body">
        <?php if (empty($du_login_events)): ?>
          <div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No login events recorded.</div>
        <?php else: ?>
          <table class="adm-table">
            <thead>
              <tr>
                <th>Time</th>
                <th>Event</th>
                <th>IP Address</th>
                <th>Location</th>
                <th>Method</th>
                <th>Session Duration</th>
                <th>Browser / UA</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach (array_slice($du_login_events, 0, 50) as $le):
                $le_data     = $le['data'] ?? [];
                $le_location = $le_data['location'] ?? '';
                $le_method   = isset($le_data['provider'])
                    ? 'oauth:' . $le_data['provider']
                    : ($le_data['method'] ?? '');
                $le_ua       = $le_data['user_agent'] ?? ($le['user_agent'] ?? '');
                $le_duration = isset($le_data['session_duration_seconds'])
                    ? cf_format_duration((int)$le_data['session_duration_seconds'])
                    : '–';
                $le_badge = match($le['event'] ?? '') {
                    'user.login'        => 'badge-green',
                    'user.login_failed' => 'badge-red',
                    'user.logout'       => 'badge-gray',
                    default             => 'badge-blue',
                };
              ?>
              <tr>
                <td style="white-space:nowrap"><?= cf_e(date('M j, Y H:i', strtotime($le['created_at'] ?? 'now'))) ?></td>
                <td><span class="badge <?= $le_badge ?>"><?= cf_e($le['event'] ?? '') ?></span></td>
                <td style="font-family:monospace;font-size:11px"><?= cf_e($le['ip'] ?? '') ?></td>
                <td style="font-size:12px"><?= cf_e($le_location) ?></td>
                <td style="font-size:12px"><?= cf_e($le_method) ?></td>
                <td style="font-size:12px"><?= cf_e($le_duration) ?></td>
                <td style="font-size:11px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:var(--text-subtle)" title="<?= cf_e($le_ua) ?>"><?= cf_e($le_ua) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
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
            <p>Multi-provider AI code generation abstracted by <code>lib/CodeGenProvider.php</code>. Free-tier users are restricted to OpenRouter free models.</p>
            <ul>
              <li>OpenRouter (free models: Llama 3.1 8B, Mistral 7B, Gemma 2 9B, etc.)</li>
              <li>Groq, Together AI</li>
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
          <div class="wf-step"><div class="wf-step-num">1</div><div class="wf-step-body"><h4>Open /IDE/</h4><p>User selects language, provider and model. Free-plan users see only OpenRouter free models (Llama 3.1 8B, Mistral 7B, etc.).</p></div></div>
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

    <?php elseif ($active_tab === 'api_keys'): ?>
    <!-- ═══ API KEYS ════════════════════════════════════════════════════════ -->
    <?php
    // Canonical list of all managed API keys with metadata
    $admin_managed_keys = [
        'OPENAI_API_KEY'     => ['label' => 'OpenAI',             'hint' => 'GPT-4o, GPT-4 Turbo, o1, o3 models',                          'icon' => 'lucide:zap'],
        'GROQ_API_KEY'       => ['label' => 'Groq',               'hint' => 'Ultra-fast inference for Llama, Mixtral, Gemma',               'icon' => 'lucide:cpu'],
        'OPENROUTER_API_KEY' => ['label' => 'OpenRouter',         'hint' => 'Multi-model routing — free & paid models',                    'icon' => 'lucide:route'],
        'HF_API_KEY'         => ['label' => 'Hugging Face',       'hint' => 'Inference API for open-source models',                        'icon' => 'lucide:box'],
        'TOGETHER_API_KEY'   => ['label' => 'Together AI',        'hint' => 'Scalable hosted open-source model inference',                 'icon' => 'lucide:layers'],
        'ANTHROPIC_API_KEY'  => ['label' => 'Anthropic (Claude)', 'hint' => 'Claude 3 Haiku, Sonnet, Opus',                               'icon' => 'lucide:brain'],
        'GEMINI_API_KEY'     => ['label' => 'Google Gemini',      'hint' => 'Gemini Pro, Gemini Flash models',                             'icon' => 'lucide:sparkles'],
        'OLLAMA_URL'         => ['label' => 'Ollama (URL)',        'hint' => 'Self-hosted Ollama server URL, e.g. http://localhost:11434',  'icon' => 'lucide:server', 'is_url' => true],
        'OTP_NOTIFICATION_API_URL' => ['label' => 'OTP Notification API URL', 'hint' => 'Provider endpoint used to send password-reset OTP emails', 'icon' => 'lucide:mail', 'is_url' => true],
        'OTP_NOTIFICATION_API_KEY' => ['label' => 'OTP Notification API Key', 'hint' => 'Provider key/token used with the OTP notification API', 'icon' => 'lucide:key-round'],
        'AUTH_VALIDATION_SERVER_API_KEY' => ['label' => 'Auth Validation Server API Key', 'hint' => 'Bearer key required by /ValidationServer/api.php', 'icon' => 'lucide:shield-check'],
    ];

    $saved_key  = $_GET['saved']   ?? '';
    $cleared_key = $_GET['cleared'] ?? '';
    ?>
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:key-round" style="vertical-align:middle;margin-right:8px"></iconify-icon>API Keys</h1>
      <p>Rotate or update global provider and notification API keys. Keys are stored in <code><?= cf_e(CF_KEYS_DIR) ?></code> and never committed to source control.</p>
    </div>

    <?php if ($saved_key !== ''): ?>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;padding:12px 16px;background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:8px;font-size:13px;color:#4ade80">
        <iconify-icon icon="lucide:check-circle"></iconify-icon>
        Key <strong><?= cf_e($saved_key) ?></strong> saved successfully.
      </div>
    <?php elseif ($cleared_key !== ''): ?>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;padding:12px 16px;background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);border-radius:8px;font-size:13px;color:#fbbf24">
        <iconify-icon icon="lucide:info"></iconify-icon>
        Key <strong><?= cf_e($cleared_key) ?></strong> cleared. The system will fall back to environment variables if set.
      </div>
    <?php endif; ?>

    <div class="adm-section">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:shield-check" style="vertical-align:middle;margin-right:6px"></iconify-icon>Provider & Notification Keys</h2>
        <span style="font-size:11px;color:var(--text-subtle)">Each key is stored as a plain-text file — never in code.</span>
      </div>
      <div class="adm-section-body">
        <div class="key-grid">
          <?php foreach ($admin_managed_keys as $key_name => $key_meta):
            $current_val   = cf_load_key($key_name);
            $is_set        = $current_val !== '';
            $masked        = $is_set
                ? substr($current_val, 0, 6) . str_repeat('•', min(20, max(8, strlen($current_val) - 6)))
                : '';
            $is_url        = !empty($key_meta['is_url']);
          ?>
          <div class="key-card">
            <div class="key-card-header">
              <div class="key-card-icon"><iconify-icon icon="<?= cf_e($key_meta['icon']) ?>"></iconify-icon></div>
              <div>
                <div class="key-card-title"><?= cf_e($key_meta['label']) ?></div>
                <div class="key-card-hint"><?= cf_e($key_meta['hint']) ?></div>
              </div>
            </div>
            <div>
              <?php if ($is_set): ?>
                <span class="key-status-set"><iconify-icon icon="lucide:check-circle-2"></iconify-icon> Key set</span>
                <div class="key-masked"><?= cf_e($masked) ?></div>
              <?php else: ?>
                <span class="key-status-unset"><iconify-icon icon="lucide:circle-dashed"></iconify-icon> Not configured</span>
              <?php endif; ?>
            </div>
            <!-- Save / Rotate form -->
            <form method="POST" action="/Admin/?tab=api_keys" autocomplete="off">
              <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
              <input type="hidden" name="action" value="save_api_key">
              <input type="hidden" name="key_name" value="<?= cf_e($key_name) ?>">
              <div class="key-input-row">
                <input
                  type="<?= $is_url ? 'text' : 'password' ?>"
                  name="key_value"
                  class="key-input"
                  placeholder="<?= $is_set ? 'Enter new value to rotate…' : 'Paste ' . cf_e($key_meta['label']) . ' key…' ?>"
                  autocomplete="new-password"
                  required
                >
              </div>
              <div class="key-actions" style="margin-top:8px">
                <button type="submit" class="btn-key-save">
                  <iconify-icon icon="lucide:rotate-ccw"></iconify-icon>
                  <?= $is_set ? 'Rotate Key' : 'Save Key' ?>
                </button>
              </div>
            </form>
            <!-- Clear form (separate — nested forms are invalid HTML) -->
            <?php if ($is_set): ?>
            <form method="POST" action="/Admin/?tab=api_keys">
              <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
              <input type="hidden" name="action" value="clear_api_key">
              <input type="hidden" name="key_name" value="<?= cf_e($key_name) ?>">
              <button type="submit" class="btn-key-clear"
                data-confirm="Clear <?= cf_e($key_meta['label']) ?> key?">
                <iconify-icon icon="lucide:trash-2"></iconify-icon> Clear Key
              </button>
            </form>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="adm-section">
      <div class="adm-section-header"><h2><iconify-icon icon="lucide:info" style="vertical-align:middle;margin-right:6px"></iconify-icon>How Key Rotation Works</h2></div>
      <div class="adm-section-body" style="font-size:13px;color:var(--text-muted);line-height:1.7">
        <ol style="margin:0;padding-left:20px">
          <li>Enter the new API key in the field above and click <strong>Rotate Key</strong>. The old key file is overwritten immediately.</li>
          <li>The application picks up the new key on the next request — no restart required.</li>
          <li><strong>Clear</strong> removes the stored file value; the system then falls back to environment variables (<code>getenv()</code>).</li>
          <li>Keys are stored as plain-text files in <code><?= cf_e(CF_KEYS_DIR) ?>/</code>, which is outside the webroot and never returned over HTTP.</li>
          <li>All save and clear actions are recorded in the Audit Trail with the admin's username.</li>
        </ol>
      </div>
    </div>

    <?php elseif ($active_tab === 'payment_api_keys'): ?>
    <!-- ═══ PAYMENT API KEYS ════════════════════════════════════════════════ -->
    <?php
      $saved_payment_key   = trim((string)($_GET['saved'] ?? ''));
      $deleted_payment_key = trim((string)($_GET['deleted'] ?? ''));
      $payment_error       = trim((string)($_GET['error'] ?? ''));
      $payment_error_key   = trim((string)($_GET['key'] ?? ''));
      $payment_mode        = trim((string)($_GET['mode'] ?? ''));

      $payment_error_messages = [
        'invalid_key'         => 'Unsupported payment API key.',
        'empty_value'         => 'Key value cannot be empty. Use Delete Key to remove a stored key.',
        'value_too_long'      => 'Key value is too long.',
        'reauth_required'     => 'Re-authentication required. Enter your admin password to continue.',
        'reauth_failed'       => 'Re-authentication failed. Please verify your admin password and try again.',
        'invalid_paypal_mode' => 'PayPal Mode must be either "sandbox" or "live".',
      ];
      $payment_key_label = $payment_error_key !== '' && isset($payment_managed_keys[$payment_error_key])
        ? $payment_managed_keys[$payment_error_key]['label']
        : $payment_error_key;
    ?>
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:credit-card" style="vertical-align:middle;margin-right:8px"></iconify-icon>Payment API Keys</h1>
      <p>Enter, save, update, rotate, and delete payment integration keys. Updates/rotations/deletions require admin re-authentication.</p>
    </div>

    <?php if ($saved_payment_key !== ''):
      $saved_label = $payment_managed_keys[$saved_payment_key]['label'] ?? $saved_payment_key;
      $saved_action = $payment_mode === 'rotated' ? 'rotated' : 'saved';
    ?>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;padding:12px 16px;background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-radius:8px;font-size:13px;color:#4ade80">
        <iconify-icon icon="lucide:check-circle"></iconify-icon>
        <strong><?= cf_e($saved_label) ?></strong> <?= cf_e($saved_action) ?> successfully.
      </div>
    <?php elseif ($deleted_payment_key !== ''):
      $deleted_label = $payment_managed_keys[$deleted_payment_key]['label'] ?? $deleted_payment_key;
    ?>
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:18px;padding:12px 16px;background:rgba(251,191,36,.08);border:1px solid rgba(251,191,36,.2);border-radius:8px;font-size:13px;color:#fbbf24">
        <iconify-icon icon="lucide:info"></iconify-icon>
        <strong><?= cf_e($deleted_label) ?></strong> deleted successfully.
      </div>
    <?php elseif ($payment_error !== '' && isset($payment_error_messages[$payment_error])): ?>
      <div style="display:flex;align-items:flex-start;gap:8px;margin-bottom:18px;padding:12px 16px;background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);border-radius:8px;font-size:13px;color:#f87171">
        <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
        <div>
          <?= cf_e($payment_error_messages[$payment_error]) ?>
          <?php if ($payment_key_label !== ''): ?>
            <div style="font-size:11px;color:#fca5a5;margin-top:3px">Key: <?= cf_e($payment_key_label) ?></div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="adm-section">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:wallet-cards" style="vertical-align:middle;margin-right:6px"></iconify-icon>Payment Provider Keys</h2>
        <span style="font-size:11px;color:var(--text-subtle)">Stored in <code><?= cf_e(CF_KEYS_DIR) ?></code></span>
      </div>
      <div class="adm-section-body">
        <div class="key-grid">
          <?php foreach ($payment_managed_keys as $key_name => $key_meta):
            $current_val = cf_load_key($key_name);
            $is_set      = $current_val !== '';
            $is_secret   = !empty($key_meta['is_secret']);
            $display_val = '';
            if ($is_set) {
              if ($is_secret) {
                $display_val = substr($current_val, 0, ADMIN_PAYMENT_SECRET_PREFIX_LENGTH) . str_repeat(
                  '•',
                  min(
                    ADMIN_PAYMENT_SECRET_MAX_MASK_LENGTH,
                    max(ADMIN_PAYMENT_SECRET_MIN_MASK_LENGTH, strlen($current_val) - ADMIN_PAYMENT_SECRET_PREFIX_LENGTH)
                  )
                );
              } else {
                $display_val = strlen($current_val) > 60 ? (substr($current_val, 0, 57) . '...') : $current_val;
              }
            }
            $save_btn_label = $is_set ? ($is_secret ? 'Rotate Key' : 'Update Value') : 'Save Key';
          ?>
          <div class="key-card">
            <div class="key-card-header">
              <div class="key-card-icon"><iconify-icon icon="<?= cf_e($key_meta['icon']) ?>"></iconify-icon></div>
              <div>
                <div class="key-card-title"><?= cf_e($key_meta['label']) ?></div>
                <div class="key-card-hint"><?= cf_e($key_meta['hint']) ?></div>
              </div>
            </div>

            <div>
              <?php if ($is_set): ?>
                <span class="key-status-set"><iconify-icon icon="lucide:check-circle-2"></iconify-icon> Key set</span>
                <div class="key-masked"><?= cf_e($display_val) ?></div>
              <?php else: ?>
                <span class="key-status-unset"><iconify-icon icon="lucide:circle-dashed"></iconify-icon> Not configured</span>
              <?php endif; ?>
            </div>

            <form method="POST" action="/Admin/?tab=payment_api_keys" autocomplete="off">
              <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
              <input type="hidden" name="action" value="save_payment_api_key">
              <input type="hidden" name="key_name" value="<?= cf_e($key_name) ?>">
              <div class="key-input-row">
                <input
                  type="<?= $is_secret ? 'password' : 'text' ?>"
                  name="key_value"
                  class="key-input"
                  placeholder="<?= $is_set ? 'Enter replacement value…' : 'Enter value…' ?>"
                  autocomplete="new-password"
                  maxlength="<?= (string)ADMIN_PAYMENT_KEY_VALUE_MAX_LENGTH ?>"
                  required
                >
              </div>
              <div class="key-input-row">
                <input
                  type="password"
                  name="admin_password"
                  class="key-input"
                  placeholder="<?= $is_set ? 'Admin password (required to update/rotate)' : 'Admin password (required once key is set)' ?>"
                  autocomplete="current-password"
                  <?= $is_set ? 'required' : '' ?>
                >
              </div>
              <div class="key-actions" style="margin-top:8px">
                <button type="submit" class="btn-key-save">
                  <iconify-icon icon="lucide:save"></iconify-icon>
                  <?= cf_e($save_btn_label) ?>
                </button>
              </div>
            </form>

            <?php if ($is_set): ?>
              <form method="POST" action="/Admin/?tab=payment_api_keys" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= cf_e($csrf) ?>">
                <input type="hidden" name="action" value="delete_payment_api_key">
                <input type="hidden" name="key_name" value="<?= cf_e($key_name) ?>">
                <div class="key-input-row">
                  <input
                    type="password"
                    name="admin_password"
                    class="key-input"
                    placeholder="Admin password (required to delete)"
                    autocomplete="current-password"
                    required
                  >
                </div>
                <button type="submit" class="btn-key-clear"
                  data-confirm="Delete <?= cf_e($key_meta['label']) ?>?">
                  <iconify-icon icon="lucide:trash-2"></iconify-icon> Delete Key
                </button>
              </form>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="adm-section">
      <div class="adm-section-header"><h2><iconify-icon icon="lucide:shield-check" style="vertical-align:middle;margin-right:6px"></iconify-icon>Re-authentication Policy</h2></div>
      <div class="adm-section-body" style="font-size:13px;color:var(--text-muted);line-height:1.7">
        <ol style="margin:0;padding-left:20px">
          <li>Initial key save is allowed without re-authentication when no value exists yet.</li>
          <li>Updating or rotating an existing payment key requires the admin's current password.</li>
          <li>Deleting any existing payment key requires the admin's current password.</li>
          <li>All save/rotate/delete actions are written to the Audit Trail.</li>
        </ol>
      </div>
    </div>

    <?php elseif ($active_tab === 'docker_instances'): ?>
    <!-- ═══ DOCKER INSTANCES ════════════════════════════════════════════════ -->
    <div id="dockerMonitorPanel">
    <div class="adm-header">
      <h1><iconify-icon icon="lucide:container" style="vertical-align:middle;margin-right:8px"></iconify-icon>Docker Instances</h1>
      <p>Monitor and manage the Docker execution engine — initiate, prewarm, inspect, and stop containers and images.</p>
    </div>

    <!-- Runtime status banner (populated by JS) -->
    <div id="dockerStatusBanner" class="docker-banner" style="display:none">
      <iconify-icon icon="lucide:loader-2"></iconify-icon>
      <span id="dockerStatusBannerText"></span>
    </div>

    <!-- Status cards -->
    <div class="stat-grid" style="grid-template-columns:repeat(4,1fr)">
      <div class="stat-card">
        <div class="stat-card-label">Daemon Status</div>
        <div class="stat-card-value" id="dkDaemonStatus">–</div>
        <div class="stat-card-sub" id="dkDaemonSub">checking…</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Running Containers</div>
        <div class="stat-card-value" id="dkRunningContainers">–</div>
        <div class="stat-card-sub">active right now</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Cached Images</div>
        <div class="stat-card-value" id="dkTotalImages">–</div>
        <div class="stat-card-sub">available locally</div>
      </div>
      <div class="stat-card">
        <div class="stat-card-label">Recent Executions</div>
        <div class="stat-card-value" id="dkExecStats">–</div>
        <div class="stat-card-sub" id="dkExecStatsSub">last 100 records</div>
      </div>
    </div>

    <!-- Action bar -->
    <div class="docker-action-bar">
      <button class="btn-docker-init" id="dockerInitBtn" onclick="dockerInitRuntime()">
        <iconify-icon icon="lucide:play-circle"></iconify-icon>
        Initialize / Prewarm Runtime
      </button>
      <button class="btn-docker-refresh" onclick="dockerRefreshAll()">
        <iconify-icon icon="lucide:refresh-cw"></iconify-icon>
        Refresh All
      </button>
      <span id="dockerLastRefreshed" style="font-size:11px;color:var(--text-subtle);margin-left:4px"></span>
    </div>

    <!-- Active Containers -->
    <div class="adm-section" style="margin-bottom:20px">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:box" style="vertical-align:middle;margin-right:6px"></iconify-icon>Containers</h2>
        <button class="btn-sm" onclick="dockerLoadContainers()"><iconify-icon icon="lucide:refresh-cw"></iconify-icon> Refresh</button>
      </div>
      <div class="adm-section-body">
        <div id="dockerContainersContent">
          <div class="empty-state"><iconify-icon icon="lucide:loader-2"></iconify-icon>Loading…</div>
        </div>
      </div>
    </div>

    <!-- Cached Images -->
    <div class="adm-section" style="margin-bottom:20px">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:layers" style="vertical-align:middle;margin-right:6px"></iconify-icon>Cached Images</h2>
        <button class="btn-sm" onclick="dockerLoadImages()"><iconify-icon icon="lucide:refresh-cw"></iconify-icon> Refresh</button>
      </div>
      <div class="adm-section-body">
        <div id="dockerImagesContent">
          <div class="empty-state"><iconify-icon icon="lucide:loader-2"></iconify-icon>Loading…</div>
        </div>
      </div>
    </div>

    <!-- Recent Executions -->
    <div class="adm-section" style="margin-bottom:20px">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:terminal" style="vertical-align:middle;margin-right:6px"></iconify-icon>Recent Executions</h2>
        <button class="btn-sm" onclick="dockerLoadExecLog()"><iconify-icon icon="lucide:refresh-cw"></iconify-icon> Refresh</button>
      </div>
      <div class="adm-section-body">
        <div id="dockerExecLogContent">
          <div class="empty-state"><iconify-icon icon="lucide:loader-2"></iconify-icon>Loading…</div>
        </div>
      </div>
    </div>

    <!-- Runtime Setup Log -->
    <div class="adm-section">
      <div class="adm-section-header">
        <h2><iconify-icon icon="lucide:file-text" style="vertical-align:middle;margin-right:6px"></iconify-icon>Runtime Setup Log</h2>
        <button class="btn-sm" onclick="dockerLoadSetupLog()"><iconify-icon icon="lucide:refresh-cw"></iconify-icon> Refresh</button>
      </div>
      <div class="adm-section-body">
        <pre class="docker-log-pre" id="dockerSetupLogContent">(No setup log available — click Initialize / Prewarm Runtime to start.)</pre>
      </div>
    </div>

    </div><!-- #dockerMonitorPanel -->

    <?php endif; ?>
</div>

<script>
var DOCKER_ADMIN_CSRF = <?= json_encode($csrf) ?>;
// data-confirm handler for clear-key buttons
document.addEventListener('click', function(e) {
  var btn = e.target.closest('[data-confirm]');
  if (!btn) return;
  var msg = btn.getAttribute('data-confirm');
  if (msg && !window.confirm(msg)) {
    e.preventDefault();
  }
});
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

<script src="/assets/js/site.js"></script>

<script>
// ── Docker Instances monitoring ────────────────────────────────────────────
(function () {
  // Only run on the docker_instances tab
  if (!document.getElementById('dockerMonitorPanel')) return;

  var AUTO_REFRESH_MS = 15000;
  var autoRefreshTimer = null;

  // ── API wrapper ────────────────────────────────────────────────────────
  function dockerApi(action, extra) {
    var body = Object.assign({ action: action }, extra || {});
    if (action === 'init_runtime' || action === 'container_action') {
      body.csrf_token = DOCKER_ADMIN_CSRF;
    }
    return fetch('/Admin/docker_api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    }).then(function (r) { return r.json(); });
  }

  function escHtml(s) {
    return String(s)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;')
      .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
  }

  // ── Status cards ────────────────────────────────────────────────────────
  window.dockerLoadStatus = function () {
    dockerApi('status').then(function (data) {
      if (!data) return;

      var daemonEl     = document.getElementById('dkDaemonStatus');
      var daemonSubEl  = document.getElementById('dkDaemonSub');
      var containersEl = document.getElementById('dkRunningContainers');
      var imagesEl     = document.getElementById('dkTotalImages');
      var execEl       = document.getElementById('dkExecStats');
      var execSubEl    = document.getElementById('dkExecStatsSub');

      if (daemonEl) {
        if (data.daemon_ready) {
          daemonEl.textContent = 'Ready';
          daemonEl.style.color = '#4ade80';
        } else if (data.setup_running) {
          daemonEl.textContent = 'Warming up';
          daemonEl.style.color = '#fbbf24';
        } else {
          daemonEl.textContent = 'Unavailable';
          daemonEl.style.color = '#f87171';
        }
      }
      if (daemonSubEl) {
        daemonSubEl.textContent = data.setup_running ? 'setup in progress' : (data.daemon_ready ? 'Docker daemon running' : 'daemon not reachable');
      }
      if (containersEl) {
        containersEl.textContent = data.running_containers || 0;
        containersEl.style.color = (data.running_containers > 0) ? '#fbbf24' : '';
      }
      if (imagesEl) {
        imagesEl.textContent = data.total_images || 0;
        imagesEl.style.color = (data.total_images > 0) ? '#4ade80' : '#fbbf24';
      }
      if (execEl) {
        var stats = data.exec_stats || {};
        execEl.textContent = (stats.total || 0) + ' total';
      }
      if (execSubEl) {
        var stats = data.exec_stats || {};
        execSubEl.textContent = (stats.failed || 0) + ' failed · avg ' + (stats.avg_ms || 0) + 'ms';
      }

      // Runtime banner
      var banner   = document.getElementById('dockerStatusBanner');
      var bannerTx = document.getElementById('dockerStatusBannerText');
      if (banner && bannerTx) {
        if (!data.daemon_ready && data.setup_running) {
          banner.style.display = 'flex';
          banner.className = 'docker-banner docker-banner-warn';
          bannerTx.textContent = 'Docker runtime is warming up — setup is running in the background. This may take a few minutes.';
        } else if (!data.daemon_ready) {
          banner.style.display = 'flex';
          banner.className = 'docker-banner docker-banner-err';
          bannerTx.innerHTML = 'Docker daemon is not available. Click <strong>Initialize / Prewarm Runtime</strong> to pull language images and start the engine.';
        } else {
          banner.style.display = 'none';
        }
      }

      // Init button state
      var initBtn = document.getElementById('dockerInitBtn');
      if (initBtn) {
        initBtn.disabled    = !!data.setup_running;
        initBtn.textContent = data.setup_running ? 'Setup Running…' : 'Initialize / Prewarm Runtime';
        if (!data.setup_running) {
          var icon = document.createElement('iconify-icon');
          icon.setAttribute('icon', 'lucide:play-circle');
          initBtn.prepend(icon);
        }
      }

      // Refresh setup log automatically when warming
      if (!data.daemon_ready && data.setup_log_tail) {
        var logEl = document.getElementById('dockerSetupLogContent');
        if (logEl) logEl.textContent = data.setup_log_tail;
      }

      var ts = document.getElementById('dockerLastRefreshed');
      if (ts) ts.textContent = 'Updated ' + new Date().toLocaleTimeString();

    }).catch(function () {});
  };

  // ── Container list ──────────────────────────────────────────────────────
  window.dockerLoadContainers = function () {
    var el = document.getElementById('dockerContainersContent');
    if (!el) return;
    el.innerHTML = '<div class="empty-state" style="padding:20px"><iconify-icon icon="lucide:loader-2"></iconify-icon> Loading…</div>';

    dockerApi('list_containers').then(function (data) {
      if (data.error && !data.containers) {
        el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:alert-triangle"></iconify-icon>' + escHtml(data.error) + '</div>';
        return;
      }
      var containers = data.containers || [];
      if (!containers.length) {
        el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No containers found on this host.</div>';
        return;
      }

      var html = '<table class="adm-table" id="dockerContainersTbl">'
        + '<thead><tr><th>Name</th><th>Image</th><th>State</th><th>Status</th><th>Running For</th><th>Actions</th></tr></thead>'
        + '<tbody>';

      containers.forEach(function (c) {
        var name    = c.Names || c.ID || '';
        var state   = (c.State || '').toLowerCase();
        var bcls    = state === 'running' ? 'badge-green'
                    : (state === 'exited' || state === 'dead') ? 'badge-red'
                    : 'badge-gray';
        var actions = '';
        if (state === 'running') {
          actions += '<button class="btn-sm btn-danger" style="margin-right:4px" '
            + 'onclick="dockerContainerAction(\'stop\',\'' + escHtml(name) + '\',this)">Stop</button>';
        }
        actions += '<button class="btn-sm" '
          + 'data-confirm="Remove container \'' + escHtml(name) + '\'?" '
          + 'onclick="dockerContainerAction(\'rm\',\'' + escHtml(name) + '\',this)">Remove</button>';

        html += '<tr>'
          + '<td style="font-family:monospace;font-size:11px;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">' + escHtml(name) + '</td>'
          + '<td style="font-size:11px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">' + escHtml(c.Image || '') + '</td>'
          + '<td><span class="badge ' + bcls + '">' + escHtml(c.State || '') + '</span></td>'
          + '<td style="font-size:11px;color:var(--text-subtle)">' + escHtml(c.Status || '') + '</td>'
          + '<td style="font-size:11px;color:var(--text-subtle)">' + escHtml(c.RunningFor || '') + '</td>'
          + '<td style="white-space:nowrap">' + actions + '</td>'
          + '</tr>';
      });
      html += '</tbody></table>';
      html += '<div class="filter-bar" style="margin-top:10px">'
        + '<input type="text" class="filter-input" placeholder="Filter containers…" '
        + 'oninput="filterTable(\'dockerContainersTbl\',this.value)">'
        + '</div>';
      el.innerHTML = html;
    }).catch(function () {
      el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:wifi-off"></iconify-icon>Request failed.</div>';
    });
  };

  // ── Image list ──────────────────────────────────────────────────────────
  window.dockerLoadImages = function () {
    var el = document.getElementById('dockerImagesContent');
    if (!el) return;
    el.innerHTML = '<div class="empty-state" style="padding:20px"><iconify-icon icon="lucide:loader-2"></iconify-icon> Loading…</div>';

    dockerApi('list_images').then(function (data) {
      if (data.error && !data.images) {
        el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:alert-triangle"></iconify-icon>' + escHtml(data.error) + '</div>';
        return;
      }
      var images = data.images || [];
      if (!images.length) {
        el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>'
          + 'No images found. Click <strong>Initialize / Prewarm Runtime</strong> to pull language images.</div>';
        return;
      }

      var html = '<table class="adm-table" id="dockerImagesTbl">'
        + '<thead><tr><th>Repository</th><th>Tag</th><th>Size</th><th>Created</th></tr></thead>'
        + '<tbody>';
      images.forEach(function (img) {
        html += '<tr>'
          + '<td style="font-family:monospace;font-size:11px">' + escHtml(img.Repository || img.repository || '') + '</td>'
          + '<td><span class="badge badge-blue">' + escHtml(img.Tag || img.tag || '') + '</span></td>'
          + '<td style="font-size:11px;color:var(--text-subtle)">' + escHtml(img.Size || img.VirtualSize || '') + '</td>'
          + '<td style="font-size:11px;color:var(--text-subtle)">' + escHtml(img.CreatedSince || img.CreatedAt || '') + '</td>'
          + '</tr>';
      });
      html += '</tbody></table>';
      html += '<div class="filter-bar" style="margin-top:10px">'
        + '<input type="text" class="filter-input" placeholder="Filter images…" '
        + 'oninput="filterTable(\'dockerImagesTbl\',this.value)">'
        + '</div>';
      el.innerHTML = html;
    }).catch(function () {
      el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:wifi-off"></iconify-icon>Request failed.</div>';
    });
  };

  // ── Execution log ───────────────────────────────────────────────────────
  window.dockerLoadExecLog = function () {
    var el = document.getElementById('dockerExecLogContent');
    if (!el) return;
    el.innerHTML = '<div class="empty-state" style="padding:20px"><iconify-icon icon="lucide:loader-2"></iconify-icon> Loading…</div>';

    dockerApi('logs', { lines: 50 }).then(function (data) {
      var entries = data.exec_log || [];
      if (!entries.length) {
        el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:inbox"></iconify-icon>No execution records yet.</div>';
        return;
      }
      var html = '<table class="adm-table"><thead><tr>'
        + '<th>Time</th><th>Language</th><th>Exit</th><th>Duration</th><th>Timed Out</th><th>Container</th><th>IP</th>'
        + '</tr></thead><tbody>';
      entries.forEach(function (e) {
        var exitOk = (e.exit === 0 || e.exit === '0') && !e.timed_out;
        html += '<tr>'
          + '<td style="white-space:nowrap;font-size:11px">' + escHtml(e.ts || '') + '</td>'
          + '<td><span class="badge badge-blue">' + escHtml(e.lang || '') + '</span></td>'
          + '<td><span class="badge ' + (exitOk ? 'badge-green' : 'badge-red') + '">' + escHtml(String(e.exit !== undefined ? e.exit : '')) + '</span></td>'
          + '<td style="font-size:11px">' + escHtml(String(e.ms || 0)) + 'ms</td>'
          + '<td>' + (e.timed_out ? '<span class="badge badge-red">Yes</span>' : '<span style="color:var(--text-subtle);font-size:11px">–</span>') + '</td>'
          + '<td style="font-family:monospace;font-size:10px;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="' + escHtml(e.container || '') + '">' + escHtml(e.container || '') + '</td>'
          + '<td style="font-family:monospace;font-size:11px">' + escHtml(e.ip || '') + '</td>'
          + '</tr>';
      });
      html += '</tbody></table>';
      el.innerHTML = html;
    }).catch(function () {
      el.innerHTML = '<div class="empty-state"><iconify-icon icon="lucide:wifi-off"></iconify-icon>Request failed.</div>';
    });
  };

  // ── Setup log ───────────────────────────────────────────────────────────
  window.dockerLoadSetupLog = function () {
    dockerApi('logs', { lines: 80 }).then(function (data) {
      var logEl = document.getElementById('dockerSetupLogContent');
      if (!logEl) return;
      var txt = (data.setup_log || '').trim();
      logEl.textContent = txt || '(No setup log available — click Initialize / Prewarm Runtime to start.)';
      // Auto-scroll to bottom to show latest output
      logEl.scrollTop = logEl.scrollHeight;
    }).catch(function () {});
  };

  // ── Container action (stop / rm) ────────────────────────────────────────
  window.dockerContainerAction = function (op, name, btn) {
    if (btn && btn.getAttribute('data-confirm')) {
      if (!window.confirm(btn.getAttribute('data-confirm'))) return;
    }
    if (btn) {
      btn.disabled    = true;
      btn.textContent = op === 'stop' ? 'Stopping…' : 'Removing…';
    }

    dockerApi('container_action', { op: op, name: name }).then(function (data) {
      if (data.error) {
        window.alert('Error: ' + data.error);
        if (btn) { btn.disabled = false; btn.textContent = op === 'stop' ? 'Stop' : 'Remove'; }
      } else {
        window.dockerLoadContainers();
        window.dockerLoadStatus();
      }
    }).catch(function () {
      if (btn) { btn.disabled = false; btn.textContent = op === 'stop' ? 'Stop' : 'Remove'; }
    });
  };

  // ── Initialize / prewarm runtime ────────────────────────────────────────
  window.dockerInitRuntime = function () {
    var btn = document.getElementById('dockerInitBtn');
    if (btn) { btn.disabled = true; btn.textContent = 'Starting…'; }

    dockerApi('init_runtime').then(function (data) {
      if (data.error) {
        window.alert('Error: ' + data.error);
        if (btn) { btn.disabled = false; btn.textContent = 'Initialize / Prewarm Runtime'; }
      } else {
        // Poll status and log after a short delay to let the process start
        setTimeout(window.dockerLoadStatus,   2000);
        setTimeout(window.dockerLoadSetupLog, 3000);
      }
    }).catch(function () {
      if (btn) { btn.disabled = false; btn.textContent = 'Initialize / Prewarm Runtime'; }
    });
  };

  // ── Refresh all sections ────────────────────────────────────────────────
  window.dockerRefreshAll = function () {
    window.dockerLoadStatus();
    window.dockerLoadContainers();
    window.dockerLoadImages();
    window.dockerLoadExecLog();
    window.dockerLoadSetupLog();
  };

  // Initial load
  window.dockerRefreshAll();

  // Auto-refresh status card every 15 s while on this tab
  autoRefreshTimer = setInterval(window.dockerLoadStatus, AUTO_REFRESH_MS);
}());
</script>
