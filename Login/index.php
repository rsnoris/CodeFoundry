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
require_once dirname(__DIR__) . '/lib/AuditStore.php';
require_once dirname(__DIR__) . '/lib/AuthValidationServer.php';

session_start();

// Redirect already-logged-in users to the Generate page
if (!empty($_SESSION['cf_user'])) {
    header('Location: /Generate/');
    exit;
}

$error = '';
$notice = '';

if (($_GET['error'] ?? '') === 'oauth' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = 'Social sign-in failed. Please try again.';
}
if (($_GET['expired'] ?? '') === '1' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $notice = 'Your session expired. Please sign in again.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== ($_SESSION['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } elseif ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        if (!AuthValidationServer::consumeLoginAttempt($username, AuditStore::getClientIp())) {
            AuditStore::log('user.login_rate_limited', $username, []);
            $error = 'Too many login attempts. Please wait a few minutes and try again.';
        } else {
            $validation = AuthValidationServer::validateLoginCredentials($username, $password);
            $matched = !empty($validation['ok']) && is_array($validation['user'] ?? null);
            if ($matched) {
                $validatedUser = (array)$validation['user'];
                $_SESSION['cf_user'] = [
                    'username' => (string)($validatedUser['username'] ?? ''),
                    'display'  => (string)($validatedUser['display'] ?? $username),
                    'role'     => (string)($validatedUser['role'] ?? 'user'),
                ];
            }

            if ($matched) {
                session_regenerate_id(true);
                $_SESSION['cf_login_at'] = time();
                UserStore::resetFailedLogin($username);
                $loginIp  = AuditStore::getClientIp();
                $loginGeo = AuditStore::geoLocate($loginIp);
                AuditStore::log('user.login', $username, array_filter([
                    'method'     => 'password',
                    'location'   => cf_format_location($loginGeo),
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                ]));
                $raw_redirect = $_GET['redirect'] ?? '';
                // Only allow relative paths: single leading slash, no double-slash, no path traversal
                $safe_redirect = (
                    is_string($raw_redirect) &&
                    preg_match('#^/[^/\\\\]#', $raw_redirect) &&
                    strpos($raw_redirect, '..') === false
                ) ? $raw_redirect : CF_SOCIAL_AUTH_LANDING_PATH;
                header('Location: ' . $safe_redirect);
                exit;
            }

            $reason = (string)($validation['reason'] ?? 'invalid');
            if ($reason !== 'frozen') {
                // Short delay to slow brute-force attempts
                sleep(1);
                $loginUserRecord = UserStore::findUser($username);
                if ($loginUserRecord !== null) {
                    UserStore::incrementFailedLogin($username);
                }
            }
            AuditStore::log('user.login_failed', $username, ['reason' => $reason]);
            $error = $reason === 'frozen'
                ? 'Your account has been frozen. Please contact support.'
                : 'Invalid username or password.';
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
$socialEnabled   = $githubEnabled || $googleEnabled || $linkedinEnabled;
$rawRedir        = $_GET['redirect'] ?? '';
$safeRedirectParam = (
    is_string($rawRedir) &&
    preg_match('#^/[^/\\\\]#', $rawRedir) &&
    strpos($rawRedir, '..') === false
) ? urlencode($rawRedir) : '';

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
  .login-layout {
    width: 100%;
    max-width: 980px;
    display: grid;
    grid-template-columns: 1.1fr .9fr;
    gap: 22px;
  }
  .login-showcase {
    background: linear-gradient(145deg, rgba(0,200,255,.14), rgba(14,24,40,.9));
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    padding: 34px 30px;
  }
  .login-showcase h1 {
    margin: 0 0 12px;
    font-size: 32px;
    line-height: 1.2;
    font-weight: 900;
  }
  .login-showcase p {
    margin: 0;
    color: var(--text-muted);
    line-height: 1.7;
    font-size: 15px;
  }
  .showcase-points {
    margin: 24px 0 0;
    padding: 0;
    list-style: none;
    display: grid;
    gap: 10px;
    font-size: 14px;
    color: var(--text);
  }
  .showcase-points li {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .showcase-points iconify-icon {
    color: var(--primary);
    font-size: 16px;
  }
  .login-card {
    background: var(--navy);
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    padding: 34px 30px 30px;
    width: 100%;
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
    font-size: 24px;
    font-weight: 800;
    margin: 0 0 6px;
  }
  .login-subtitle {
    color: var(--text-muted);
    font-size: 15px;
    margin: 0 0 20px;
  }
  .login-notice {
    background: rgba(24,179,255,.1);
    border: 1px solid rgba(24,179,255,.28);
    color: #98dfff;
    border-radius: 8px;
    padding: 11px 14px;
    font-size: 14px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .login-mode {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--primary);
    font-weight: 800;
    margin-bottom: 10px;
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
    margin-bottom: 8px;
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
  .login-footer-note {
    margin-top: 8px;
    color: var(--text-subtle);
    font-size: 12px;
  }
  @media (max-width: 900px) {
    .login-layout {
      grid-template-columns: 1fr;
      max-width: 460px;
    }
    .login-showcase {
      display: none;
    }
  }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main class="login-wrap">
  <div class="login-layout">
    <section class="login-showcase" aria-hidden="true">
      <h1>One account.<br>Social login + signup ready.</h1>
      <p>Use GitHub, Google, or LinkedIn to instantly sign in or create your account. Prefer password auth? Continue with your username and password below.</p>
      <ul class="showcase-points">
        <li><iconify-icon icon="lucide:shield-check"></iconify-icon> Secure OAuth 2.0 authentication</li>
        <li><iconify-icon icon="lucide:user-plus"></iconify-icon> Auto signup on first social login</li>
        <li><iconify-icon icon="lucide:move-right"></iconify-icon> Redirects to your configured landing flow</li>
      </ul>
    </section>

    <div class="login-card">
      <div class="login-logo">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m18 16l4-4l-4-4M6 8l-4 4l4 4m8.5-12l-5 16"/></svg>
        CodeFoundry
      </div>
      <div class="login-mode"><iconify-icon icon="lucide:user-round-check"></iconify-icon> Sign in or sign up</div>
      <h1 class="login-title">Access your workspace</h1>
      <p class="login-subtitle">Continue with social auth or your CodeFoundry credentials.</p>

      <?php if ($notice !== ''): ?>
        <div class="login-notice" role="status">
          <iconify-icon icon="lucide:clock-3"></iconify-icon>
          <?= htmlspecialchars($notice, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <?php if ($error !== ''): ?>
        <div class="login-error" role="alert">
          <iconify-icon icon="lucide:alert-circle"></iconify-icon>
          <?= htmlspecialchars($error, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <?php if ($socialEnabled): ?>
      <div class="social-btns">
        <?php if ($githubEnabled): ?>
        <a href="/Login/oauth.php?provider=github<?= $safeRedirectParam !== '' ? '&redirect=' . $safeRedirectParam : '' ?>" class="btn-social">
          <iconify-icon icon="mdi:github"></iconify-icon>
          Continue with GitHub
        </a>
        <?php endif; ?>
        <?php if ($googleEnabled): ?>
        <a href="/Login/oauth.php?provider=google<?= $safeRedirectParam !== '' ? '&redirect=' . $safeRedirectParam : '' ?>" class="btn-social">
          <iconify-icon icon="flat-color-icons:google"></iconify-icon>
          Continue with Google
        </a>
        <?php endif; ?>
        <?php if ($linkedinEnabled): ?>
        <a href="/Login/oauth.php?provider=linkedin<?= $safeRedirectParam !== '' ? '&redirect=' . $safeRedirectParam : '' ?>" class="btn-social btn-social-linkedin">
          <iconify-icon icon="mdi:linkedin"></iconify-icon>
          Continue with LinkedIn
        </a>
        <?php endif; ?>
      </div>
      <div class="login-divider">or continue with password</div>
      <?php endif; ?>

      <form method="POST" action="/Login/<?= $safeRedirectParam !== '' ? '?redirect=' . $safeRedirectParam : '' ?>" novalidate>
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
        <a href="/Login/forgot-password.php">Forgot password?</a><br>
        Don't have an account? <a href="/Signup/<?= $safeRedirectParam !== '' ? '?redirect=' . $safeRedirectParam : '' ?>">Create one for free</a>
        <?php if ($socialEnabled): ?>
        <div class="login-footer-note">Tip: You can also create your account instantly using any social provider above.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
