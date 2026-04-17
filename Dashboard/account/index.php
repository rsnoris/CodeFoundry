<?php
declare(strict_types=1);
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/UserStore.php';
require_once dirname(dirname(__DIR__)) . '/lib/ChatStore.php';
require_once dirname(dirname(__DIR__)) . '/lib/AuditStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$username     = $user_session['username'];
$user         = UserStore::findUser($username) ?? $user_session;
$unread_chat  = ChatStore::totalUnreadForUser($username);
$token_history_count = count(UserStore::tokenHistoryForUser($username, 1000));
$payment_history_count = count(UserStore::paymentsForUser($username));
$session_history_count = count(ChatStore::sessionsForUser($username));
$user_managed_keys = [
    'OPENAI_API_KEY'     => ['label' => 'OpenAI',             'hint' => 'GPT-4o, GPT-4 Turbo, o1, o3 models',                                      'icon' => 'lucide:zap',      'docs_url' => 'https://platform.openai.com/api-keys'],
    'GROQ_API_KEY'       => ['label' => 'Groq',               'hint' => 'Ultra-fast inference for Llama, Mixtral, Gemma',                           'icon' => 'lucide:cpu',      'docs_url' => 'https://console.groq.com/keys'],
    'OPENROUTER_API_KEY' => ['label' => 'OpenRouter',         'hint' => 'Multi-model routing with one key (alternative to direct provider keys)',     'icon' => 'lucide:route',    'docs_url' => 'https://openrouter.ai/keys'],
    'HF_API_KEY'         => ['label' => 'Hugging Face',       'hint' => 'Inference API for open-source models',                                    'icon' => 'lucide:box',      'docs_url' => 'https://huggingface.co/settings/tokens'],
    'TOGETHER_API_KEY'   => ['label' => 'Together AI',        'hint' => 'Hosted open models (Llama, Qwen, Mixtral, and more)',                     'icon' => 'lucide:layers',   'docs_url' => 'https://api.together.xyz/settings/api-keys'],
    'ANTHROPIC_API_KEY'  => ['label' => 'Anthropic (Claude)', 'hint' => 'Claude 3 Haiku, Sonnet, Opus',                                             'icon' => 'lucide:brain',    'docs_url' => 'https://console.anthropic.com/settings/keys'],
    'GEMINI_API_KEY'     => ['label' => 'Google Gemini',      'hint' => 'Gemini Pro, Gemini Flash models',                                         'icon' => 'lucide:sparkles', 'docs_url' => 'https://aistudio.google.com/app/apikey'],
    'OLLAMA_URL'         => ['label' => 'Ollama (URL)',       'hint' => 'Self-hosted Ollama server URL, e.g. http://localhost:11434',              'icon' => 'lucide:server',   'is_url' => true, 'docs_url' => 'https://ollama.com/download'],
];

// Generate CSRF token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
const USER_API_KEY_VALUE_MAX_LENGTH = 2048;

/**
 * Mask a key value for safe UI display.
 */
function cf_mask_account_key(string $value): string
{
    if ($value === '') {
        return '';
    }
    return str_repeat('•', max(8, min(24, strlen($value))));
}

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

// ── Handle user API key update / clear ─────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form'] ?? '') === 'api_key') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $error_api = 'Invalid request. Please refresh and try again.';
    } else {
        $action   = trim((string)($_POST['action'] ?? ''));
        $key_name = trim((string)($_POST['key_name'] ?? ''));
        if (!isset($user_managed_keys[$key_name])) {
            $error_api = 'Unsupported key name.';
        } else {
            if ($action === 'save_api_key') {
                $key_value = trim((string)($_POST['key_value'] ?? ''));
                if ($key_value === '') {
                    if (!UserStore::clearUserApiKey($username, $key_name)) {
                        $error_api = 'Unable to clear key at this time. Please try again.';
                    } else {
                        AuditStore::log('user.api_key_cleared', $username, ['key' => $key_name]);
                        $flash_api = $user_managed_keys[$key_name]['label'] . ' key cleared for this account.';
                    }
                } elseif (strlen($key_value) > USER_API_KEY_VALUE_MAX_LENGTH) {
                    $error_api = 'API key value is too long.';
                } else {
                    $had_value = UserStore::getUserApiKeyOverride($username, $key_name) !== '';
                    UserStore::saveUserApiKey($username, $key_name, $key_value);
                    AuditStore::log('user.api_key_saved', $username, [
                        'key'    => $key_name,
                        'action' => $had_value ? 'rotated' : 'saved',
                    ]);
                    $flash_api = $user_managed_keys[$key_name]['label'] . ' key ' . ($had_value ? 'rotated' : 'saved') . ' for this account.';
                }
            } elseif ($action === 'clear_api_key') {
                if (!UserStore::clearUserApiKey($username, $key_name)) {
                    $error_api = 'Unable to clear key at this time. Please try again.';
                } else {
                    AuditStore::log('user.api_key_cleared', $username, ['key' => $key_name]);
                    $flash_api = $user_managed_keys[$key_name]['label'] . ' key cleared for this account.';
                }
            } else {
                $error_api = 'Unsupported API key action.';
            }
        }
    }
}

$user_key_overrides = [];
foreach ($user_managed_keys as $key_name => $_meta) {
    $user_key_overrides[$key_name] = UserStore::getUserApiKeyOverride($username, $key_name);
}
$api_key_events = AuditStore::eventsForUser($username, 25, ['user.api_key_saved', 'user.api_key_cleared']);

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
  .key-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:16px; }
  .key-card { background:var(--navy-2); border:1px solid var(--border-color); border-radius:12px; padding:18px 20px; display:flex; flex-direction:column; gap:12px; }
  .key-card-header { display:flex; align-items:center; gap:10px; }
  .key-card-icon { width:34px; height:34px; background:rgba(24,179,255,.1); border:1px solid rgba(24,179,255,.2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px; color:var(--primary); flex-shrink:0; }
  .key-card-title { font-size:13px; font-weight:700; color:var(--text); }
  .key-card-hint { font-size:11px; color:var(--text-subtle); margin-top:1px; }
  .key-card-link { font-size:11px; color:var(--primary); text-decoration:none; }
  .key-card-link:hover { text-decoration:underline; }
  .key-status-set { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; color:#4ade80; }
  .key-status-unset { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:600; color:var(--text-subtle); }
  .key-input { width:100%; background:var(--navy); border:1px solid var(--border-color); border-radius:6px; padding:8px 10px; color:var(--text); font-size:12px; outline:none; font-family:monospace; }
  .key-input:focus { border-color:var(--primary); }
  .key-masked { font-size:11px; color:var(--text-subtle); font-family:monospace; letter-spacing:.05em; margin-top:2px; }
  .key-actions { display:flex; gap:6px; flex-wrap:wrap; margin-top:8px; }
  .btn-key-save { display:inline-flex; align-items:center; gap:5px; padding:6px 14px; border-radius:6px; font-size:11px; font-weight:700; cursor:pointer; border:1px solid rgba(24,179,255,.3); background:rgba(24,179,255,.12); color:var(--primary); transition:border-color .15s,background .15s; }
  .btn-key-save:hover { border-color:var(--primary); background:rgba(24,179,255,.2); }
  .btn-key-clear { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:6px; font-size:11px; font-weight:600; cursor:pointer; border:1px solid rgba(239,68,68,.25); background:rgba(239,68,68,.07); color:#f87171; transition:border-color .15s; }
  .btn-key-clear:hover { border-color:#f87171; }
  .table-wrap { overflow-x:auto; }
  .data-table { width: 100%; border-collapse: collapse; font-size: 13px; }
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
  .event-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 8px; border-radius:999px; font-size:11px; font-weight:700; }
  .event-badge.saved { background:rgba(34,197,94,.12); color:#4ade80; border:1px solid rgba(34,197,94,.25); }
  .event-badge.cleared { background:rgba(251,191,36,.12); color:#fbbf24; border:1px solid rgba(251,191,36,.25); }
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
        <div class="key-grid">
          <?php foreach ($user_managed_keys as $key_name => $key_meta):
            $current_val = (string)($user_key_overrides[$key_name] ?? '');
            $is_set      = $current_val !== '';
            $masked      = $is_set ? cf_mask_account_key($current_val) : '';
            $is_url      = !empty($key_meta['is_url']);
            $placeholder = $is_set ? 'Enter new value to rotate…' : 'Paste ' . $key_meta['label'] . ' value…';
          ?>
          <div class="key-card">
            <div class="key-card-header">
              <div class="key-card-icon"><iconify-icon icon="<?= cf_e($key_meta['icon']) ?>"></iconify-icon></div>
              <div>
                <div class="key-card-title"><?= cf_e($key_meta['label']) ?></div>
                <div class="key-card-hint"><?= cf_e($key_meta['hint']) ?></div>
                <?php if (!empty($key_meta['docs_url'])): ?>
                  <a class="key-card-link" href="<?= cf_e((string)$key_meta['docs_url']) ?>" target="_blank" rel="noopener noreferrer">
                    <?= $is_url ? 'Setup guide' : 'Get API key' ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div>
              <?php if ($is_set): ?>
                <span class="key-status-set"><iconify-icon icon="lucide:check-circle-2"></iconify-icon> Key set for your account</span>
                <div class="key-masked"><?= cf_e($masked) ?></div>
              <?php else: ?>
                <span class="key-status-unset"><iconify-icon icon="lucide:circle-dashed"></iconify-icon> No account-level key set</span>
              <?php endif; ?>
            </div>
            <form method="POST" autocomplete="off">
              <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
              <input type="hidden" name="form" value="api_key">
              <input type="hidden" name="action" value="save_api_key">
              <input type="hidden" name="key_name" value="<?= cf_e($key_name) ?>">
              <input
                type="<?= $is_url ? 'text' : 'password' ?>"
                name="key_value"
                class="key-input"
                placeholder="<?= cf_e($placeholder) ?>"
                autocomplete="new-password"
              >
              <div class="key-actions">
                <button type="submit" class="btn-key-save">
                  <iconify-icon icon="lucide:rotate-ccw"></iconify-icon>
                  <?= $is_set ? 'Rotate Key' : 'Save Key' ?>
                </button>
              </div>
            </form>
            <?php if ($is_set): ?>
            <form method="POST">
              <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
              <input type="hidden" name="form" value="api_key">
              <input type="hidden" name="action" value="clear_api_key">
              <input type="hidden" name="key_name" value="<?= cf_e($key_name) ?>">
              <button type="submit" class="btn-key-clear" data-confirm="Clear <?= cf_e($key_meta['label']) ?> key for your account?">
                <iconify-icon icon="lucide:trash-2"></iconify-icon> Clear Key
              </button>
            </form>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="dash-section">
      <div class="dash-section-header"><h2>API Key Change History</h2></div>
      <div class="dash-section-body" style="padding:0">
        <?php if (empty($api_key_events)): ?>
          <div style="padding:22px;color:var(--text-subtle);font-size:13px">
            No API key changes recorded yet for this account.
          </div>
        <?php else: ?>
          <div class="table-wrap">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Action</th>
                  <th>Key</th>
                  <th>IP</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($api_key_events as $entry):
                  $event = (string)($entry['event'] ?? '');
                  $is_saved = $event === 'user.api_key_saved';
                  $badge_class = $is_saved ? 'saved' : 'cleared';
                  $badge_text = $is_saved ? 'Saved / Rotated' : 'Cleared';
                  $key_name = (string)($entry['data']['key'] ?? 'Key name not recorded');
                  $created_raw = (string)($entry['created_at'] ?? '');
                  $created_ts = $created_raw !== '' ? strtotime($created_raw) : false;
                  $created_label = $created_ts !== false ? date('M j, Y g:i A', $created_ts) : 'Unknown';
                ?>
                <tr>
                  <td style="white-space:nowrap"><?= cf_e($created_label) ?></td>
                  <td><span class="event-badge <?= cf_e($badge_class) ?>"><?= cf_e($badge_text) ?></span></td>
                  <td><code><?= cf_e($key_name) ?></code></td>
                  <td><?= cf_e((string)($entry['ip'] ?? '')) ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
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

<script>
document.addEventListener('click', function (e) {
  const btn = e.target.closest('[data-confirm]');
  if (!btn) return;
  const msg = btn.getAttribute('data-confirm');
  if (msg && !window.confirm(msg)) {
    e.preventDefault();
  }
});
</script>

<?php require_once dirname(dirname(__DIR__)) . '/includes/footer.php'; ?>
