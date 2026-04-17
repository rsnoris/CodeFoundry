<?php
declare(strict_types=1);

/**
 * Sends password-reset OTP notifications.
 *
 * If OTP_NOTIFICATION_API_URL and OTP_NOTIFICATION_API_KEY are configured,
 * a JSON POST is sent to that provider. Otherwise, falls back to PHP mail().
 */
class OtpNotification
{
    private const API_TIMEOUT_SECONDS_DEFAULT = 12;
    private const API_CONNECT_TIMEOUT_SECONDS_DEFAULT = 6;

    public static function generateOtp(int $length = 6): string
    {
        $length = max(4, min(8, $length));
        return str_pad((string)random_int(0, (10 ** $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    public static function sendPasswordResetOtp(string $email, string $displayName, string $otp): bool
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $subject = 'Your CodeFoundry password reset code';
        $safeName = trim($displayName) !== '' ? trim($displayName) : 'there';
        $body = "Hi {$safeName},\n\n"
            . "Your CodeFoundry password reset OTP is: {$otp}\n\n"
            . "This code expires in 10 minutes.\n"
            . "If you did not request a password reset, you can ignore this email.\n\n"
            . "— CodeFoundry";

        if (self::sendViaConfiguredApi($email, $subject, $body, $otp)) {
            return true;
        }

        return self::sendViaPhpMail($email, $subject, $body);
    }

    private static function sendViaConfiguredApi(string $email, string $subject, string $body, string $otp): bool
    {
        $apiUrl = trim(cf_load_key('OTP_NOTIFICATION_API_URL'));
        $apiKey = trim(cf_load_key('OTP_NOTIFICATION_API_KEY'));
        if ($apiUrl === '' || $apiKey === '') {
            return false;
        }

        if (!function_exists('curl_init')) {
            return false;
        }

        $payload = json_encode([
            'to'       => $email,
            'subject'  => $subject,
            'message'  => $body,
            'otp'      => $otp,
            'template' => 'password_reset_otp',
        ]);
        if ($payload === false) {
            return false;
        }

        $ch = curl_init($apiUrl);
        if ($ch === false) {
            return false;
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            CURLOPT_TIMEOUT => self::API_TIMEOUT_SECONDS_DEFAULT,
            CURLOPT_CONNECTTIMEOUT => self::API_CONNECT_TIMEOUT_SECONDS_DEFAULT,
        ]);

        $response = curl_exec($ch);
        $curlErr  = curl_errno($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $curlErr === 0 && $response !== false && $status >= 200 && $status < 300;
    }

    private static function sendViaPhpMail(string $email, string $subject, string $body): bool
    {
        $from = defined('CF_SITE_EMAIL') ? trim((string)CF_SITE_EMAIL) : '';
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        $headers = [
            'From: ' . $from,
            'Reply-To: ' . $from,
            'Content-Type: text/plain; charset=UTF-8',
        ];

        return mail($email, $subject, $body, implode("\r\n", $headers));
    }
}
