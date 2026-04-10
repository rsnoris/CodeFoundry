<?php
/**
 * CodeFoundry – Login Page
 *
 * Authenticates the user against the credentials defined in config.php
 * and stores the result in a PHP session.
 */
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';

session_start();

// Redirect already-logged-in users to the home page
if (!empty($_SESSION['cf_user'])) {
    header('Location: /');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } elseif ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $matched = false;

        // ── 1. Check CF_USERS (hardcoded accounts) ──────────────────────────
        $cfUsers = defined('CF_USERS') ? CF_USERS : [];
        foreach ($cfUsers as $user) {
            if ($user['username'] !== $username) {
                continue;
            }
            // Check data/users.json for a password_hash override
            $effectiveHash = $user['password_hash'];
            if (defined('CF_DATA_USERS') && file_exists(CF_DATA_USERS)) {
                $storedJson = @file_get_contents(CF_DATA_USERS);
                if ($storedJson !== false) {
                    $storedUsers = json_decode($storedJson, true) ?? [];
                    foreach ($storedUsers as $row) {
                        if (($row['username'] ?? '') === $username && !empty($row['password_hash'])) {
                            $effectiveHash = $row['password_hash'];
                            break;
                        }
                    }
                }
            }
            if (password_verify($password, $effectiveHash)) {
                $matched = true;
                $_SESSION['cf_user'] = [
                    'username' => $user['username'],
                    'display'  => $user['display'] ?? $user['username'],
                    'role'     => $user['role']    ?? 'user',
                ];
            }
            break; // username matched – stop regardless of password result
        }

        // ── 2. Fall back to self-registered users in data/users.json ────────
        if (!$matched) {
            $selfUser = UserStore::findUser($username);
            if (
                $selfUser !== null &&
                !empty($selfUser['self_registered']) &&
                !empty($selfUser['password_hash']) &&
                password_verify($password, $selfUser['password_hash'])
            ) {
                $matched = true;
                $_SESSION['cf_user'] = [
                    'username' => $selfUser['username'],
                    'display'  => $selfUser['display'] ?? $selfUser['username'],
                    'role'     => $selfUser['role']    ?? 'user',
                ];
            }
        }

        if ($matched) {
            session_regenerate_id(true);
            $raw_redirect = $_GET['redirect'] ?? '';
            // Only allow relative paths: single leading slash, no double-slash, no path traversal
            $safe_redirect = (
                is_string($raw_redirect) &&
                preg_match('#^/[^/\\\\]#', $raw_redirect) &&
                strpos($raw_redirect, '..') === false
            ) ? $raw_redirect : '/';
            header('Location: ' . $safe_redirect);
            exit;
        }

        // Short delay to slow brute-force attempts
        sleep(1);
        $error = 'Invalid username or password.';
    }
}

// Generate a fresh CSRF token for the form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$githubEnabled   = defined('CF_OAUTH_GITHUB_CLIENT_ID')   && CF_OAUTH_GITHUB_CLIENT_ID   !== '';
$googleEnabled   = defined('CF_OAUTH_GOOGLE_CLIENT_ID')   && CF_OAUTH_GOOGLE_CLIENT_ID   !== '';
$linkedinEnabled = defined('CF_OAUTH_LINKEDIN_CLIENT_ID') && CF_OAUTH_LINKEDIN_CLIENT_ID !== '';

$page_title  = 'CodeFoundry - Login';
$active_page = 'login';
$page_styles = <<<'PAGECSS'
  .login-wrap {
    min-height: calc(100vh - var(--header-height) - 200px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 16px;
  }
  .login-card {
    background: var(--navy);
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    padding: 44px 40px 40px;
    width: 100%;
    max-width: 420px;
  }
  .login-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 800;
    font-size: 20px;
    color: #fff;
    margin-bottom: 28px;
  }
  .login-logo svg {
    width: 28px;
    height: 28px;
    background: var(--primary);
    border-radius: 6px;
    color: #092340;
    padding: 4px;
  }
  .login-title {
    font-size: 26px;
    font-weight: 800;
    margin: 0 0 6px;
  }
  .login-subtitle {
    color: var(--text-muted);
    font-size: 15px;
    margin: 0 0 28px;
  }
  .login-error {
    background: rgba(255, 72, 72, .12);
    border: 1px solid rgba(255, 72, 72, .35);
    color: #ff7373;
    border-radius: 8px;
    padding: 11px 14px;
    font-size: 14px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .form-group {
    margin-bottom: 18px;
  }
  .form-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    margin-bottom: 7px;
  }
  .form-input {
    width: 100%;
    padding: 11px 14px;
    background: var(--navy-2);
    border: 1px solid var(--border-color);
    border-radius: var(--button-radius);
    color: var(--text);
    font-size: 15px;
    font-family: inherit;
    outline: none;
    transition: border-color .2s;
    box-sizing: border-box;
  }
  .form-input:focus {
    border-color: var(--primary);
  }
  .form-input::placeholder {
    color: var(--text-subtle);
  }
  .btn-login {
    width: 100%;
    padding: 13px;
    background: var(--primary);
    color: var(--navy);
    font-weight: 800;
    font-size: 16px;
    border: none;
    border-radius: var(--button-radius);
    cursor: pointer;
    font-family: inherit;
    transition: background .2s;
    margin-top: 6px;
  }
  .btn-login:hover {
    background: var(--primary-hover);
  }
  .login-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: var(--text-subtle);
    font-size: 13px;
  }
  .login-divider::before,
  .login-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border-color);
  }
  .social-btns {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 4px;
  }
  .btn-social {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 11px 14px;
    background: var(--navy-2);
    border: 1px solid var(--border-color);
    border-radius: var(--button-radius);
    color: var(--text);
    font-size: 15px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    text-decoration: none;
    transition: border-color .2s, background .2s;
  }
  .btn-social:hover {
    border-color: var(--primary);
    background: rgba(0,200,255,.06);
  }
  .btn-social iconify-icon {
    font-size: 18px;
  }
  .btn-social-linkedin iconify-icon {
    color: #0a66c2;
  }
  .login-footer {
    margin-top: 24px;
    text-align: center;
    font-size: 14px;
    color: var(--text-muted);
  }
  .login-footer a {
    color: var(--primary);
    font-weight: 600;
    text-decoration: none;
  }
  .login-footer a:hover {
    text-decoration: underline;
  }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main class="login-wrap">
  <div class="login-card">
    <div class="login-logo">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 16l4-4l-4-4M6 8l-4 4l4 4m8.5-12l-5 16"/></svg>
      CodeFoundry
    </div>
    <h1 class="login-title">Welcome back</h1>
    <p class="login-subtitle">Sign in to your CodeFoundry account</p>

    <?php if ($error !== ''): ?>
      <div class="login-error" role="alert">
        <iconify-icon icon="lucide:alert-circle"></iconify-icon>
        <?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
      </div>
    <?php endif; ?>

    <?php if ($githubEnabled || $googleEnabled || $linkedinEnabled): ?>
    <div class="social-btns">
      <?php if ($githubEnabled): ?>
      <a href="/Login/oauth.php?provider=github<?php
        $raw_redir = $_GET['redirect'] ?? '';
        if (is_string($raw_redir) && preg_match('#^/[^/\\\\]#', $raw_redir)) {
            echo '&redirect=' . urlencode($raw_redir);
        }
      ?>" class="btn-social">
        <iconify-icon icon="mdi:github"></iconify-icon>
        Continue with GitHub
      </a>
      <?php endif; ?>
      <?php if ($googleEnabled): ?>
      <a href="/Login/oauth.php?provider=google<?php
        $raw_redir = $_GET['redirect'] ?? '';
        if (is_string($raw_redir) && preg_match('#^/[^/\\\\]#', $raw_redir)) {
            echo '&redirect=' . urlencode($raw_redir);
        }
      ?>" class="btn-social">
        <iconify-icon icon="flat-color-icons:google"></iconify-icon>
        Continue with Google
      </a>
      <?php endif; ?>
      <?php if ($linkedinEnabled): ?>
      <a href="/Login/oauth.php?provider=linkedin<?php
        $raw_redir = $_GET['redirect'] ?? '';
        if (is_string($raw_redir) && preg_match('#^/[^/\\\\]#', $raw_redir)) {
            echo '&redirect=' . urlencode($raw_redir);
        }
      ?>" class="btn-social btn-social-linkedin">
        <iconify-icon icon="mdi:linkedin"></iconify-icon>
        Continue with LinkedIn
      </a>
      <?php endif; ?>
    </div>
    <div class="login-divider">or sign in with email</div>
    <?php endif; ?>

    <form method="POST" action="/Login/<?php
      $raw_redir = $_GET['redirect'] ?? '';
      if (is_string($raw_redir) && preg_match('#^/[^/\\\\]#', $raw_redir)) {
          echo '?redirect=' . urlencode($raw_redir);
      }
    ?>" novalidate>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

      <div class="form-group">
        <label for="username" class="form-label">Username</label>
        <input
          type="text"
          id="username"
          name="username"
          class="form-input"
          placeholder="Enter your username"
          value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
          required
          autocomplete="username"
        />
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-input"
          placeholder="Enter your password"
          required
          autocomplete="current-password"
        />
      </div>

      <button type="submit" class="btn-login">Sign In</button>
    </form>

    <div class="login-footer">
      Don't have an account? <a href="/Signup/<?php
        $raw_redir = $_GET['redirect'] ?? '';
        if (is_string($raw_redir) && preg_match('#^/[^/\\\\]#', $raw_redir)) {
            echo '?redirect=' . urlencode($raw_redir);
        }
      ?>">Create one for free</a>
    </div>
  </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

