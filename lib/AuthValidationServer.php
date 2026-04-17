<?php
declare(strict_types=1);

/**
 * CodeFoundry – AuthValidationServer
 *
 * Shared authentication and validation helpers used by login/password-reset
 * flows and by ValidationServer API endpoints.
 */
class AuthValidationServer
{
    private const RATE_LIMIT_FILE = CF_USERS_STORAGE_DIR . '/auth_validation_rate_limits.json';

    public static function consumeLoginAttempt(string $username, string $ip): bool
    {
        return self::consumeRateLimit('login_attempt', $username . '|' . $ip, 12, 300);
    }

    public static function consumeOtpRequest(string $identifier, string $ip): bool
    {
        return self::consumeRateLimit('otp_request', $identifier . '|' . $ip, 4, 300);
    }

    public static function consumeOtpVerifyAttempt(string $identifier, string $ip): bool
    {
        return self::consumeRateLimit('otp_verify', $identifier . '|' . $ip, 8, 300);
    }

    public static function consumeRateLimit(string $action, string $scope, int $maxAttempts, int $windowSeconds): bool
    {
        $action = trim($action);
        $scope = mb_strtolower(trim($scope));
        if ($action === '' || $scope === '' || $maxAttempts < 1 || $windowSeconds < 1) {
            return false;
        }

        $all = self::pruneRateLimitStore(self::readRateLimits());
        $key = $action . '|' . $scope;
        $now = time();
        $windowStart = $now - $windowSeconds;

        $bucket = $all[$key] ?? [];
        if (!is_array($bucket)) {
            $bucket = [];
        }

        $bucket = array_values(array_filter($bucket, static fn($t) => (int)$t >= $windowStart));
        if (count($bucket) >= $maxAttempts) {
            $all[$key] = $bucket;
            self::writeRateLimits($all);
            return false;
        }

        $bucket[] = $now;
        $all[$key] = $bucket;
        self::writeRateLimits($all);
        return true;
    }

    public static function isSessionValid(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $username = trim((string)($_SESSION['cf_user']['username'] ?? ''));
        if ($username === '') {
            return false;
        }

        return UserStore::findUser($username) !== null;
    }

    public static function validateLoginCredentials(string $username, string $password): array
    {
        $username = trim($username);
        if ($username === '' || $password === '') {
            return ['ok' => false, 'reason' => 'missing_fields', 'user' => null];
        }

        $userRecord = UserStore::findUser($username);
        if ($userRecord !== null && !empty($userRecord['frozen'])) {
            return ['ok' => false, 'reason' => 'frozen', 'user' => null];
        }

        $matchedUser = null;
        foreach (CF_USERS as $user) {
            if (($user['username'] ?? '') !== $username) {
                continue;
            }
            $effectiveHash = (string)($user['password_hash'] ?? '');
            if ($userRecord !== null && !empty($userRecord['password_hash'])) {
                $effectiveHash = (string)$userRecord['password_hash'];
            }
            if ($effectiveHash !== '' && password_verify($password, $effectiveHash)) {
                $matchedUser = [
                    'username' => (string)$user['username'],
                    'display'  => (string)($user['display'] ?? $user['username']),
                    'role'     => (string)($user['role'] ?? 'user'),
                ];
            }
            break;
        }

        if ($matchedUser !== null) {
            return ['ok' => true, 'reason' => 'valid', 'user' => $matchedUser];
        }

        if (
            $userRecord !== null &&
            !empty($userRecord['self_registered']) &&
            !empty($userRecord['password_hash']) &&
            password_verify($password, (string)$userRecord['password_hash'])
        ) {
            return [
                'ok' => true,
                'reason' => 'valid',
                'user' => [
                    'username' => (string)$userRecord['username'],
                    'display'  => (string)($userRecord['display'] ?? $userRecord['username']),
                    'role'     => (string)($userRecord['role'] ?? 'user'),
                ],
            ];
        }

        return ['ok' => false, 'reason' => 'invalid_credentials', 'user' => null];
    }

    public static function validatePasswordResetOtp(string $identifier, string $otp, int $maxAttempts = 5): array
    {
        $identifier = trim($identifier);
        $otp = trim($otp);
        if ($identifier === '' || $otp === '') {
            return ['ok' => false, 'reason' => 'missing_fields', 'user' => null];
        }

        $user = filter_var($identifier, FILTER_VALIDATE_EMAIL)
            ? UserStore::findUserByEmail($identifier)
            : UserStore::findUser($identifier);
        if ($user === null) {
            return ['ok' => false, 'reason' => 'not_found', 'user' => null];
        }

        $otpHash = (string)($user['password_reset_otp_hash'] ?? '');
        if ($otpHash === '') {
            return ['ok' => false, 'reason' => 'missing_otp', 'user' => $user];
        }

        $expiresRaw = (string)($user['password_reset_expires_at'] ?? '');
        $expiresTimestamp = false;
        if ($expiresRaw !== '') {
            $expiresDt = date_create_immutable($expiresRaw);
            if ($expiresDt !== false) {
                $expiresTimestamp = $expiresDt->getTimestamp();
            }
        }
        if ($expiresTimestamp === false || $expiresTimestamp < time()) {
            return ['ok' => false, 'reason' => 'expired', 'user' => $user];
        }

        $attempts = (int)($user['password_reset_attempts'] ?? 0);
        if ($attempts >= $maxAttempts) {
            return ['ok' => false, 'reason' => 'max_attempts', 'user' => $user];
        }

        if (!password_verify($otp, $otpHash)) {
            return ['ok' => false, 'reason' => 'invalid_otp', 'user' => $user];
        }

        return ['ok' => true, 'reason' => 'valid', 'user' => $user];
    }

    private static function readRateLimits(): array
    {
        $file = self::RATE_LIMIT_FILE;
        if (!is_file($file)) {
            return [];
        }

        $fp = fopen($file, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH);
        $content = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($content === false || trim($content) === '') {
            return [];
        }
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    private static function writeRateLimits(array $data): void
    {
        $dir = dirname(self::RATE_LIMIT_FILE);
        if (!is_dir($dir)) {
            @mkdir($dir, 0700, true);
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return;
        }

        $tmpPath = tempnam($dir, 'cf_rate_limits_');
        if ($tmpPath === false) {
            return;
        }

        $fp = fopen($tmpPath, 'w');
        if ($fp === false) {
            @unlink($tmpPath);
            return;
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if (!@rename($tmpPath, self::RATE_LIMIT_FILE)) {
            @unlink($tmpPath);
        }
    }

    private static function pruneRateLimitStore(array $all): array
    {
        $oldestAllowed = time() - 86400;
        $clean = [];
        foreach ($all as $key => $bucket) {
            if (!is_string($key) || !is_array($bucket)) {
                continue;
            }
            $filtered = array_values(array_filter($bucket, static fn($t) => (int)$t >= $oldestAllowed));
            if (!empty($filtered)) {
                $clean[$key] = $filtered;
            }
        }
        return $clean;
    }
}
