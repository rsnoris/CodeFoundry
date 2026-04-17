<?php
declare(strict_types=1);
require_once __DIR__ . '/EmailServer.php';

/**
 * Sends password-reset OTP notifications.
 *
 * If OTP_NOTIFICATION_API_URL and OTP_NOTIFICATION_API_KEY are configured,
 * a JSON POST is sent to that provider. Otherwise, falls back to PHP mail().
 */
class OtpNotification
{
    public static function generateOtp(int $length = 6): string
    {
        $length = max(4, min(8, $length));
        return str_pad((string)random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    public static function sendPasswordResetOtp(string $email, string $displayName, string $otp): bool
    {
        return EmailServer::sendPasswordResetOtp($email, $displayName, $otp);
    }
}
