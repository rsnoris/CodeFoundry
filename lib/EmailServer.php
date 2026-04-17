<?php
declare(strict_types=1);

/**
 * CodeFoundry – EmailServer
 *
 * Provides a single place for transactional email delivery used by OTP flows.
 * Delivery strategy:
 *   1) Configured OTP notification API (if URL + key are set)
 *   2) PHP mail() fallback
 */
class EmailServer
{
    private const API_TIMEOUT_SECONDS_DEFAULT = 12;
    private const API_CONNECT_TIMEOUT_SECONDS_DEFAULT = 6;

    public static function send(
        string $to,
        string $subject,
        string $message,
        array $metadata = [],
        bool $skipConfiguredApi = false
    ): bool {
        $to = trim($to);
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $subject = self::sanitizeHeaderValue($subject);
        if ($subject === '') {
            return false;
        }

        if (!$skipConfiguredApi && self::sendViaConfiguredApi($to, $subject, $message, $metadata)) {
            return true;
        }

        return self::sendViaPhpMail($to, $subject, $message);
    }

    public static function sendPasswordResetOtp(string $email, string $displayName, string $otp): bool
    {
        $safeName = trim($displayName) !== '' ? trim($displayName) : 'there';
        $subject = 'Your CodeFoundry password reset code';
        $body = "Hi {$safeName},\n\n"
            . "Your CodeFoundry password reset OTP is: {$otp}\n\n"
            . "This code expires in 10 minutes.\n"
            . "If you did not request a password reset, you can ignore this email.\n\n"
            . "— CodeFoundry";

        return self::send($email, $subject, $body, [
            'otp'      => $otp,
            'template' => 'password_reset_otp',
        ]);
    }

    private static function sendViaConfiguredApi(string $to, string $subject, string $message, array $metadata): bool
    {
        $apiUrl = trim(cf_load_key('OTP_NOTIFICATION_API_URL'));
        $apiKey = trim(cf_load_key('OTP_NOTIFICATION_API_KEY'));
        if ($apiUrl === '' || $apiKey === '') {
            return false;
        }
        if (!filter_var($apiUrl, FILTER_VALIDATE_URL)) {
            return false;
        }
        if (!function_exists('curl_init')) {
            return false;
        }

        $payload = json_encode([
            'to'      => $to,
            'subject' => $subject,
            'message' => $message,
            'meta'    => $metadata,
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
        $status   = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $curlErr === 0 && $response !== false && $status >= 200 && $status < 300;
    }

    private static function sendViaPhpMail(string $to, string $subject, string $message): bool
    {
        $from = defined('CF_SITE_EMAIL') ? trim((string)CF_SITE_EMAIL) : '';
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $headers = [
            'From: ' . self::sanitizeHeaderValue($from),
            'Reply-To: ' . self::sanitizeHeaderValue($from),
            'Content-Type: text/plain; charset=UTF-8',
        ];

        return mail($to, $subject, $message, implode("\r\n", $headers));
    }

    private static function sanitizeHeaderValue(string $value): string
    {
        return trim(str_replace(["\r", "\n"], '', $value));
    }
}
