<?php
declare(strict_types=1);
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/UserStore.php';
require_once dirname(dirname(__DIR__)) . '/lib/ChatStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$username     = $user_session['username'];
$user         = UserStore::findUser($username) ?? $user_session;
$unread_chat  = ChatStore::totalUnreadForUser($username);
$token_history_count = count(UserStore::tokenHistoryForUser($username, 1000));
$payment_history_count = count(UserStore::paymentsForUser($username));
$session_history_count = count(ChatStore::sessionsForUser($username));
$openrouter_key_current = UserStore::getUserApiKey($username, 'OPENROUTER_API_KEY', '');
$openrouter_key_masked = $openrouter_key_current !== ''
    ? str_repeat('•', max(8, min(24, strlen($openrouter_key_current))))
    : '';

// Generate CSRF token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
const OPENROUTER_MAX_KEY_LENGTH = 500;

$flash_profile  = '';
$flash_password = '';
$flash_api      = '';
$error_profile  = '';
$error_password = '';
$error_api      = '';

// ── Handle profile update ─────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'profile') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_profile = 'Invalid request. Please refresh and try again.';
    } else {
        $display = trim($_POST['display'] ?? '');
        $email   = trim($_POST['email'] ?? '');

        if ($display === '') {
            $error_profile = 'Display name cannot be empty.';
        } elseif ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_profile = 'Please enter a valid email address.';
        } else {
            UserStore::updateUser($username, ['display' => $display, 'email' => $email]);
            // Update session
            $_SESSION['cf_user']['display'] = $display;
            $user['display'] = $display;
            $user['email']   = $email;
            $flash_profile = 'Profile updated successfully.';
        }
    }
}

// ── Handle password update ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'password') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_password = 'Invalid request. Please refresh and try again.';
    } else {
        $current_pw  = $_POST['current_password'] ?? '';
        $new_pw      = $_POST['new_password'] ?? '';
        $confirm_pw  = $_POST['confirm_password'] ?? '';

        // Determine current hash: data/users.json override, then CF_USERS
        $current_hash = $user['password_hash'] ?? '';

        if ($current_pw === '' || $new_pw === '' || $confirm_pw === '') {
            $error_password = 'All password fields are required.';
        } elseif (!password_verify($current_pw, $current_hash)) {
            $error_password = 'Current password is incorrect.';
        } elseif (strlen($new_pw) < 8) {
            $error_password = 'New password must be at least 8 characters.';
        } elseif ($new_pw !== $confirm_pw) {
            $error_password = 'New passwords do not match.';
        } else {
            $new_hash = password_hash($new_pw, PASSWORD_BCRYPT);
            UserStore::updateUser($username, ['password_hash' => $new_hash]);
            $flash_password = 'Password updated successfully.';
        }
    }
}

// ── Handle OpenRouter API key update ───────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'openrouter_key') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_api = 'Invalid request. Please refresh and try again.';
    } else {
        $openrouter_key = trim((string)($_POST['openrouter_api_key'] ?? ''));
        if ($openrouter_key !== '' && strlen($openrouter_key) > OPENROUTER_MAX_KEY_LENGTH) {
            $error_api = 'API key value is too long.';
        } else {
            UserStore::saveUserApiKey($username, 'OPENROUTER_API_KEY', $openrouter_key);
            $openrouter_key_current = UserStore::getUserApiKey($username, 'OPENROUTER_API_KEY', '');
            $openrouter_key_masked = $openrouter_key_current !== ''
                ? str_repeat('•', max(8, min(24, strlen($openrouter_key_current))))
                : '';
            $flash_api = $openrouter_key === ''
                ? 'OpenRouter API key cleared for this account.'
                : 'OpenRouter API key saved for this account.';
        }
    }
}

// Reload fresh user data
$user = UserStore::findUser($username) ?? $user;

$dash_active = 'account';
$page_title  = 'Account – CodeFoundry';
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
    font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: var(--text-subtle); padding: 0 20px 12px;
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
  .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
  .form-group { display: flex; flex-direction: column; gap: 7px; }
  .form-group.full { grid-column: 1 / -1; }
  .form-label { font-size: 13px; font-weight: 600; color: var(--text-muted); }
  .form-input {
    padding: 11px 14px; background: var(--navy-2); border: 1px solid var(--border-color);
    border-radius: var(--button-radius); color: var(--text); font-size: 14px;
    font-family: inherit; outline: none; transition: border-color .2s;
  }
  .form-input:focus { border-color: var(--primary); }
  .form-input::placeholder { color: var(--text-subtle); }
  .form-hint { font-size: 12px; color: var(--text-subtle); }
  .btn-save {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 10px 24px; background: var(--primary); color: var(--navy);
    font-weight: 700; font-size: 14px; border: none; border-radius: var(--button-radius);
    cursor: pointer; font-family: inherit; transition: background .2s; margin-top: 8px;
  }
  .btn-save:hover { background: var(--primary-hover); }
  .flash-success {
    background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25); color: #4ade80;
    border-radius: 8px; padding: 11px 16px; font-size: 13px; margin-bottom: 18px;
    display: flex; align-items: center; gap: 8px;
  }
  .flash-error {
    background: rgba(255,72,72,.1); border: 1px solid rgba(255,72,72,.25); color: #ff7373;
    border-radius: 8px; padding: 11px 16px; font-size: 13px; margin-bottom: 18px;
    display: flex; align-items: center; gap: 8px;
  }
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
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 14px;
  }
  .stat-card {
    background: var(--navy-2);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 14px;
  }
  .stat-label { font-size: 12px; color: var(--text-subtle); margin-bottom: 4px; }
  .stat-value { font-size: 22px; font-weight: 800; color: var(--text); }
  .data-path {
    margin-top: 12px;
    font-size: 12px;
    color: var(--text-subtle);
    word-break: break-all;
  }
  @media (max-width: 700px) {
    .dash-layout { flex-direction: column; padding: 0; }
    .dash-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); padding: 16px 0; display: flex; overflow-x: auto; }
    .dash-sidebar-title { display: none; }
    .dash-main { padding: 24px 16px 48px; }
    .form-grid { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: 1fr; }
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
      <h1>Account Settings</h1>
      <p>Manage your profile and security settings.</p>
    </div>

    <!-- Profile form -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>Profile Information</h2></div>
      <div class="dash-section-body">
        <?php if ($flash_profile !== ''): ?>
          <div class="flash-success"><iconify-icon icon="lucide:check-circle"></iconify-icon><?= cf_e($flash_profile) ?></div>
        <?php endif; ?>
        <?php if ($error_profile !== ''): ?>
          <div class="flash-error"><iconify-icon icon="lucide:alert-circle"></iconify-icon><?= cf_e($error_profile) ?></div>
        <?php endif; ?>
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
          <input type="hidden" name="form" value="profile">
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label" for="display">Display Name</label>
              <input type="text" id="display" name="display" class="form-input"
                     value="<?= cf_e($user['display'] ?? '') ?>" required maxlength="80" placeholder="Your name">
            </div>
            <div class="form-group">
              <label class="form-label" for="email">Email Address</label>
              <input type="email" id="email" name="email" class="form-input"
                     value="<?= cf_e($user['email'] ?? '') ?>" placeholder="your@email.com" maxlength="200">
              <span class="form-hint">Used for account notifications (optional).</span>
            </div>
            <div class="form-group full">
              <label class="form-label">Username</label>
              <input type="text" class="form-input" value="<?= cf_e($username) ?>" disabled
                     style="opacity:.5;cursor:not-allowed">
              <span class="form-hint">Username cannot be changed.</span>
            </div>
          </div>
          <button type="submit" class="btn-save">
            <iconify-icon icon="lucide:save"></iconify-icon> Save Profile
          </button>
        </form>
      </div>
    </div>

    <!-- Password form -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>Change Password</h2></div>
      <div class="dash-section-body">
        <?php if ($flash_password !== ''): ?>
          <div class="flash-success"><iconify-icon icon="lucide:check-circle"></iconify-icon><?= cf_e($flash_password) ?></div>
        <?php endif; ?>
        <?php if ($error_password !== ''): ?>
          <div class="flash-error"><iconify-icon icon="lucide:alert-circle"></iconify-icon><?= cf_e($error_password) ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
          <input type="hidden" name="form" value="password">
          <div class="form-grid">
            <div class="form-group full">
              <label class="form-label" for="current_password">Current Password</label>
              <input type="password" id="current_password" name="current_password" class="form-input"
                     placeholder="Enter current password" autocomplete="current-password">
            </div>
            <div class="form-group">
              <label class="form-label" for="new_password">New Password</label>
              <input type="password" id="new_password" name="new_password" class="form-input"
                     placeholder="At least 8 characters" autocomplete="new-password" minlength="8">
            </div>
            <div class="form-group">
              <label class="form-label" for="confirm_password">Confirm New Password</label>
              <input type="password" id="confirm_password" name="confirm_password" class="form-input"
                     placeholder="Repeat new password" autocomplete="new-password">
            </div>
          </div>
          <button type="submit" class="btn-save">
            <iconify-icon icon="lucide:lock"></iconify-icon> Update Password
          </button>
        </form>
      </div>
    </div>

    <!-- API Keys -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>API Keys (Per Account)</h2></div>
      <div class="dash-section-body">
        <?php if ($flash_api !== ''): ?>
          <div class="flash-success"><iconify-icon icon="lucide:check-circle"></iconify-icon><?= cf_e($flash_api) ?></div>
        <?php endif; ?>
        <?php if ($error_api !== ''): ?>
          <div class="flash-error"><iconify-icon icon="lucide:alert-circle"></iconify-icon><?= cf_e($error_api) ?></div>
        <?php endif; ?>
        <form method="POST" autocomplete="off">
          <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
          <input type="hidden" name="form" value="openrouter_key">
          <div class="form-grid">
            <div class="form-group full">
              <label class="form-label" for="openrouter_api_key">OpenRouter API Key</label>
              <input type="password" id="openrouter_api_key" name="openrouter_api_key" class="form-input"
                     placeholder="Paste your OpenRouter API key (stored in your account config folder)">
              <?php if ($openrouter_key_masked !== ''): ?>
                <span class="form-hint">Current saved value: <?= cf_e($openrouter_key_masked) ?></span>
              <?php else: ?>
                <span class="form-hint">No key stored for this account yet.</span>
              <?php endif; ?>
            </div>
          </div>
          <button type="submit" class="btn-save">
            <iconify-icon icon="lucide:key-round"></iconify-icon> Save OpenRouter Key
          </button>
        </form>
      </div>
    </div>

    <!-- Usage / billing summaries -->
    <div class="dash-section">
      <div class="dash-section-header"><h2>Usage, History & Billing</h2></div>
      <div class="dash-section-body">
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-label">Prompts / Token History</div>
            <div class="stat-value"><?= (int)$token_history_count ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Session History</div>
            <div class="stat-value"><?= (int)$session_history_count ?></div>
          </div>
          <div class="stat-card">
            <div class="stat-label">Payment History</div>
            <div class="stat-value"><?= (int)$payment_history_count ?></div>
          </div>
        </div>
        <div class="data-path">
          Account data and key config location: <strong><?= cf_e(CF_USERS_STORAGE_DIR) ?></strong><br>
          Your per-account key folder: <strong><?= cf_e(cf_user_config_dir($username)) ?></strong>
        </div>
      </div>
    </div>
  </main>
</div>

<?php require_once dirname(dirname(__DIR__)) . '/includes/footer.php'; ?>
