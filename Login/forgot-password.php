<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';
require_once dirname(__DIR__) . '/lib/OtpNotification.php';

session_start();

$error = '';
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', (string)$_POST['csrf_token'])) {
        $error = 'Invalid request. Please refresh and try again.';
    } else {
        $identifier = trim((string)($_POST['identifier'] ?? ''));
        if ($identifier === '') {
            $error = 'Please enter your username or email address.';
        } else {
            $user = filter_var($identifier, FILTER_VALIDATE_EMAIL)
                ? UserStore::findUserByEmail($identifier)
                : UserStore::findUser($identifier);

            if ($user !== null) {
                $username = (string)($user['username'] ?? '');
                $email    = trim((string)($user['email'] ?? ''));
                $lastSent = strtotime((string)($user['password_reset_requested_at'] ?? ''));

                if (
                    $username !== '' &&
                    filter_var($email, FILTER_VALIDATE_EMAIL) &&
                    ($lastSent === false || $lastSent < (time() - 60))
                ) {
                    $otp = OtpNotification::generateOtp(6);
                    UserStore::updateUser($username, [
                        'password_reset_otp_hash'     => password_hash($otp, PASSWORD_DEFAULT),
                        'password_reset_expires_at'   => date('c', time() + 600),
                        'password_reset_attempts'     => 0,
                        'password_reset_requested_at' => date('c'),
                    ]);

                    $sent = OtpNotification::sendPasswordResetOtp($email, (string)($user['display'] ?? $username), $otp);
                    AuditStore::log(
                        $sent ? 'user.password_reset_otp_sent' : 'user.password_reset_otp_send_failed',
                        $username,
                        ['email' => $email]
                    );

                    if (!$sent) {
                        UserStore::updateUser($username, [
                            'password_reset_otp_hash'     => '',
                            'password_reset_expires_at'   => '',
                            'password_reset_attempts'     => 0,
                            'password_reset_requested_at' => '',
                        ]);
                    }
                }
            }

            $flash = 'If an account exists with that username/email, a one-time password has been sent.';
        }
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page_title  = 'CodeFoundry - Forgot Password';
$active_page = 'login';
$page_styles = <<<'PAGECSS'
  .login-wrap { min-height: calc(100vh - var(--header-height) - 200px); display:flex; align-items:center; justify-content:center; padding:60px 16px; }
  .login-card { background:var(--navy); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:44px 40px 40px; width:100%; max-width:420px; }
  .login-title { font-size:26px; font-weight:800; margin:0 0 6px; }
  .login-subtitle { color:var(--text-muted); font-size:15px; margin:0 0 24px; }
  .flash-success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.25); color:#4ade80; border-radius:8px; padding:11px 14px; font-size:14px; margin-bottom:16px; }
  .flash-error { background:rgba(255,72,72,.12); border:1px solid rgba(255,72,72,.35); color:#ff7373; border-radius:8px; padding:11px 14px; font-size:14px; margin-bottom:16px; }
  .form-group { margin-bottom:16px; }
  .form-label { display:block; font-size:14px; font-weight:600; color:var(--text-muted); margin-bottom:7px; }
  .form-input { width:100%; padding:11px 14px; background:var(--navy-2); border:1px solid var(--border-color); border-radius:var(--button-radius); color:var(--text); font-size:15px; font-family:inherit; outline:none; box-sizing:border-box; }
  .form-input:focus { border-color:var(--primary); }
  .btn-login { width:100%; padding:13px; background:var(--primary); color:var(--navy); font-weight:800; font-size:16px; border:none; border-radius:var(--button-radius); cursor:pointer; font-family:inherit; }
  .login-footer { margin-top:20px; text-align:center; font-size:14px; color:var(--text-muted); }
  .login-footer a { color:var(--primary); font-weight:600; text-decoration:none; }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main class="login-wrap">
  <div class="login-card">
    <h1 class="login-title">Forgot password?</h1>
    <p class="login-subtitle">Enter your username or email and we’ll send a reset OTP.</p>

    <?php if ($flash !== ''): ?>
      <div class="flash-success"><?= cf_e($flash) ?></div>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <div class="flash-error"><?= cf_e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/Login/forgot-password.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
      <div class="form-group">
        <label for="identifier" class="form-label">Username or Email</label>
        <input type="text" id="identifier" name="identifier" class="form-input" required autocomplete="username email">
      </div>
      <button type="submit" class="btn-login">Send OTP</button>
    </form>

    <div class="login-footer">
      Have your OTP? <a href="/Login/reset-password.php">Reset your password</a><br>
      Back to <a href="/Login/">Sign In</a>
    </div>
  </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>

