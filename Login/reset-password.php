<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/UserStore.php';
require_once dirname(__DIR__) . '/lib/AuditStore.php';

session_start();

$error = '';
$flash = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', (string)$_POST['csrf_token'])) {
        $error = 'Invalid request. Please refresh and try again.';
    } else {
        $identifier = trim((string)($_POST['identifier'] ?? ''));
        $otp        = trim((string)($_POST['otp'] ?? ''));
        $newPw      = (string)($_POST['new_password'] ?? '');
        $confirmPw  = (string)($_POST['confirm_password'] ?? '');

        if ($identifier === '' || $otp === '' || $newPw === '' || $confirmPw === '') {
            $error = 'All fields are required.';
        } elseif (strlen($newPw) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($newPw !== $confirmPw) {
            $error = 'Passwords do not match.';
        } else {
            $user = filter_var($identifier, FILTER_VALIDATE_EMAIL)
                ? UserStore::findUserByEmail($identifier)
                : UserStore::findUser($identifier);

            $isValid = false;
            if ($user !== null) {
                $username = (string)($user['username'] ?? '');
                $otpHash  = (string)($user['password_reset_otp_hash'] ?? '');
                $expires = false;
                $expiresRaw = (string)($user['password_reset_expires_at'] ?? '');
                if ($expiresRaw !== '') {
                    $expiresDt = date_create_immutable($expiresRaw);
                    if ($expiresDt !== false) {
                        $expires = $expiresDt->getTimestamp();
                    }
                }
                $attempts = (int)($user['password_reset_attempts'] ?? 0);

                if ($username !== '' && $otpHash !== '' && $expires !== false && $expires >= time() && $attempts < 5) {
                    if (password_verify($otp, $otpHash)) {
                        UserStore::updateUser($username, [
                            'password_hash'              => password_hash($newPw, PASSWORD_BCRYPT),
                            'password_reset_otp_hash'    => '',
                            'password_reset_expires_at'  => '',
                            'password_reset_attempts'    => 0,
                            'password_reset_requested_at'=> '',
                        ]);
                        UserStore::resetFailedLogin($username);
                        AuditStore::log('user.password_reset_completed', $username, []);
                        $isValid = true;
                    } else {
                        $attempts++;
                        UserStore::updateUser($username, [
                            'password_reset_attempts' => $attempts,
                        ]);
                        if ($attempts >= 5) {
                            UserStore::updateUser($username, [
                                'password_reset_otp_hash'    => '',
                                'password_reset_expires_at'  => '',
                                'password_reset_attempts'    => 0,
                                'password_reset_requested_at'=> '',
                            ]);
                        }
                        AuditStore::log('user.password_reset_otp_failed', $username, ['attempts' => $attempts]);
                    }
                }
            }

            if ($isValid) {
                $flash = 'Password reset successful. You can now sign in.';
            } else {
                $error = 'Invalid or expired OTP. Request a new one and try again.';
            }
        }
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$page_title  = 'CodeFoundry - Reset Password';
$active_page = 'login';
$page_styles = <<<'PAGECSS'
  .login-wrap { min-height: calc(100vh - var(--header-height) - 200px); display:flex; align-items:center; justify-content:center; padding:60px 16px; }
  .login-card { background:var(--navy); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:44px 40px 40px; width:100%; max-width:440px; }
  .login-title { font-size:26px; font-weight:800; margin:0 0 6px; }
  .login-subtitle { color:var(--text-muted); font-size:15px; margin:0 0 24px; }
  .flash-success { background:rgba(34,197,94,.1); border:1px solid rgba(34,197,94,.25); color:#4ade80; border-radius:8px; padding:11px 14px; font-size:14px; margin-bottom:16px; }
  .flash-error { background:rgba(255,72,72,.12); border:1px solid rgba(255,72,72,.35); color:#ff7373; border-radius:8px; padding:11px 14px; font-size:14px; margin-bottom:16px; }
  .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
  .form-group { margin-bottom:14px; }
  .form-group.full { grid-column:1 / -1; }
  .form-label { display:block; font-size:14px; font-weight:600; color:var(--text-muted); margin-bottom:7px; }
  .form-input { width:100%; padding:11px 14px; background:var(--navy-2); border:1px solid var(--border-color); border-radius:var(--button-radius); color:var(--text); font-size:15px; font-family:inherit; outline:none; box-sizing:border-box; }
  .form-input:focus { border-color:var(--primary); }
  .btn-login { width:100%; padding:13px; background:var(--primary); color:var(--navy); font-weight:800; font-size:16px; border:none; border-radius:var(--button-radius); cursor:pointer; font-family:inherit; margin-top:4px; }
  .login-footer { margin-top:20px; text-align:center; font-size:14px; color:var(--text-muted); }
  .login-footer a { color:var(--primary); font-weight:600; text-decoration:none; }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main class="login-wrap">
  <div class="login-card">
    <h1 class="login-title">Reset password</h1>
    <p class="login-subtitle">Enter your OTP and set a new password.</p>

    <?php if ($flash !== ''): ?>
      <div class="flash-success"><?= cf_e($flash) ?></div>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <div class="flash-error"><?= cf_e($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/Login/reset-password.php" novalidate>
      <input type="hidden" name="csrf_token" value="<?= cf_e($_SESSION['csrf_token']) ?>">
      <div class="form-grid">
        <div class="form-group full">
          <label for="identifier" class="form-label">Username or Email</label>
          <input type="text" id="identifier" name="identifier" class="form-input" required autocomplete="username email">
        </div>
        <div class="form-group full">
          <label for="otp" class="form-label">OTP</label>
          <input type="text" id="otp" name="otp" class="form-input" required maxlength="8" inputmode="numeric" autocomplete="one-time-code">
        </div>
        <div class="form-group">
          <label for="new_password" class="form-label">New Password</label>
          <input type="password" id="new_password" name="new_password" class="form-input" required minlength="8" autocomplete="new-password">
        </div>
        <div class="form-group">
          <label for="confirm_password" class="form-label">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-input" required minlength="8" autocomplete="new-password">
        </div>
      </div>
      <button type="submit" class="btn-login">Reset Password</button>
    </form>

    <div class="login-footer">
      Need a code? <a href="/Login/forgot-password.php">Request OTP</a><br>
      Back to <a href="/Login/">Sign In</a>
    </div>
  </div>
</main>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
