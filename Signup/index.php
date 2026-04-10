<?php
/**
 * CodeFoundry – Sign Up Page
 *
 * Allows new users to create a free account.
 * Credentials are stored in data/users.json via UserStore.
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

$errors  = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $username  = trim($_POST['username']  ?? '');
        $display   = trim($_POST['display']   ?? '');
        $email     = trim($_POST['email']     ?? '');
        $password  = $_POST['password']       ?? '';
        $password2 = $_POST['password2']      ?? '';

        // ── Validate ─────────────────────────────────────────────────────────
        if ($username === '') {
            $errors[] = 'Username is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
            $errors[] = 'Username must be 3–30 characters and contain only letters, numbers, or underscores.';
        } elseif (UserStore::usernameExists($username)) {
            $errors[] = 'That username is already taken.';
        }

        if ($display === '') {
            $errors[] = 'Display name is required.';
        } elseif (mb_strlen($display) > 60) {
            $errors[] = 'Display name must be 60 characters or fewer.';
        }

        if ($email === '') {
            $errors[] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (mb_strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        } elseif ($password !== $password2) {
            $errors[] = 'Passwords do not match.';
        }

        // ── Create account ────────────────────────────────────────────────────
        if (empty($errors)) {
            $hash    = password_hash($password, PASSWORD_BCRYPT);
            $created = UserStore::createUser($username, $display, $email, $hash);
            if (!$created) {
                // Race condition: username taken between validation and write
                $errors[] = 'That username is already taken.';
            } else {
                // Auto-login the new user
                session_regenerate_id(true);
                $_SESSION['cf_user'] = [
                    'username' => $username,
                    'display'  => $display,
                    'role'     => 'user',
                ];
                $raw_redirect = $_GET['redirect'] ?? '';
                $safe_redirect = (
                    is_string($raw_redirect) &&
                    preg_match('#^/[^/\\\\]#', $raw_redirect) &&
                    strpos($raw_redirect, '..') === false
                ) ? $raw_redirect : '/Dashboard/';
                header('Location: ' . $safe_redirect);
                exit;
            }
        }
    }
}

// Generate a fresh CSRF token for the form
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$githubEnabled   = defined('CF_OAUTH_GITHUB_CLIENT_ID')   && CF_OAUTH_GITHUB_CLIENT_ID   !== '';
$googleEnabled   = defined('CF_OAUTH_GOOGLE_CLIENT_ID')   && CF_OAUTH_GOOGLE_CLIENT_ID   !== '';
$linkedinEnabled = defined('CF_OAUTH_LINKEDIN_CLIENT_ID') && CF_OAUTH_LINKEDIN_CLIENT_ID !== '';

$page_title  = 'CodeFoundry - Create Account';
$active_page = 'signup';
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
  }
  .login-error ul {
    margin: 0;
    padding-left: 18px;
  }
  .login-error li {
    margin-top: 4px;
  }
  .login-error li:first-child {
    margin-top: 0;
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
  .form-hint {
    font-size: 12px;
    color: var(--text-subtle);
    margin-top: 5px;
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
    <h1 class="login-title">Create your account</h1>
    <p class="login-subtitle">Join CodeFoundry — it's free</p>

    <?php if (!empty($errors)): ?>
      <div class="login-error" role="alert">
        <?php if (count($errors) === 1): ?>
          <iconify-icon icon="lucide:alert-circle"></iconify-icon>
          <?= htmlspecialchars($errors[0], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        <?php else: ?>
          <ul>
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
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
        Sign up with GitHub
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
        Sign up with Google
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
        Sign up with LinkedIn
      </a>
      <?php endif; ?>
    </div>
    <div class="login-divider">or sign up with email</div>
    <?php endif; ?>

    <form method="POST" action="/Signup/<?php
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
          placeholder="Choose a username"
          value="<?= htmlspecialchars($_POST['username'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
          required
          autocomplete="username"
          maxlength="30"
        />
        <p class="form-hint">3–30 characters; letters, numbers, and underscores only.</p>
      </div>

      <div class="form-group">
        <label for="display" class="form-label">Display Name</label>
        <input
          type="text"
          id="display"
          name="display"
          class="form-input"
          placeholder="Your full name or nickname"
          value="<?= htmlspecialchars($_POST['display'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
          required
          autocomplete="name"
          maxlength="60"
        />
      </div>

      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input
          type="email"
          id="email"
          name="email"
          class="form-input"
          placeholder="you@example.com"
          value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
          required
          autocomplete="email"
        />
      </div>

      <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          class="form-input"
          placeholder="At least 8 characters"
          required
          autocomplete="new-password"
          minlength="8"
        />
      </div>

      <div class="form-group">
        <label for="password2" class="form-label">Confirm Password</label>
        <input
          type="password"
          id="password2"
          name="password2"
          class="form-input"
          placeholder="Repeat your password"
          required
          autocomplete="new-password"
          minlength="8"
        />
      </div>

      <button type="submit" class="btn-login">Create Account</button>
    </form>

    <div class="login-footer">
      Already have an account? <a href="/Login/<?php
        $raw_redir = $_GET['redirect'] ?? '';
        if (is_string($raw_redir) && preg_match('#^/[^/\\\\]#', $raw_redir)) {
            echo '?redirect=' . urlencode($raw_redir);
        }
      ?>">Sign in</a>
    </div>
  </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
