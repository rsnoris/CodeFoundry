<?php
declare(strict_types=1);

/**
 * CodeFoundry – AuditStore
 *
 * Flat-file (JSON) storage for:
 *   • Audit log   – immutable event trail for every significant action.
 *   • Page views  – lightweight page-navigation tracking.
 *   • Support tickets – user-submitted support requests.
 *
 * All writes use atomic file replacement (temp file + rename).
 */
class AuditStore
{
    // ── Audit log ──────────────────────────────────────────────────────────

    /**
     * Append one audit-log entry.
     *
     * @param string      $event    Dot-namespaced event type, e.g. "user.login".
     * @param string      $username Username of the actor (empty string for anonymous).
     * @param array       $data     Additional context (never store raw passwords).
     */
    public static function log(string $event, string $username, array $data = []): void
    {
        $entry = [
            'id'         => self::uuid(),
            'event'      => $event,
            'username'   => $username,
            'ip'         => self::clientIp(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('c'),
            'data'       => $data,
        ];
        $all   = self::readJson(CF_DATA_AUDIT_LOG);
        $all[] = $entry;
        self::writeJson(CF_DATA_AUDIT_LOG, $all);
    }

    /**
     * Return all audit-log entries, newest first.
     *
     * @param int    $limit  Max number of entries (0 = all).
     * @param string $filter Optional event-type prefix filter, e.g. "user".
     */
    public static function allEvents(int $limit = 0, string $filter = ''): array
    {
        $all = self::readJson(CF_DATA_AUDIT_LOG);
        $all = array_reverse($all);

        if ($filter !== '') {
            $all = array_values(array_filter(
                $all,
                fn($e) => str_starts_with($e['event'] ?? '', $filter)
            ));
        }

        if ($limit > 0) {
            $all = array_slice($all, 0, $limit);
        }

        return $all;
    }

    /**
     * Return audit-log entries for a specific user, newest first.
     */
    public static function eventsForUser(string $username, int $limit = 0): array
    {
        $all = self::readJson(CF_DATA_AUDIT_LOG);
        $all = array_reverse($all);
        $all = array_values(array_filter(
            $all,
            fn($e) => ($e['username'] ?? '') === $username
        ));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    // ── Page views ─────────────────────────────────────────────────────────

    /**
     * Record a page view.
     *
     * @param string $username   Logged-in username or empty string.
     * @param string $page       The page path, e.g. "/IDE/".
     * @param int    $timeOnPage Seconds the user spent on the previous page (0 if unknown).
     * @param string $referrer   Previous page path.
     */
    public static function logPageView(
        string $username,
        string $page,
        int    $timeOnPage = 0,
        string $referrer   = ''
    ): void {
        $entry = [
            'id'           => self::uuid(),
            'username'     => $username,
            'page'         => $page,
            'time_on_page' => $timeOnPage,
            'referrer'     => $referrer,
            'ip'           => self::clientIp(),
            'user_agent'   => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at'   => date('c'),
        ];
        $all   = self::readJson(CF_DATA_PAGE_VIEWS);
        $all[] = $entry;
        self::writeJson(CF_DATA_PAGE_VIEWS, $all);
    }

    /** Return all page-view records, newest first. */
    public static function allPageViews(int $limit = 0): array
    {
        $all = array_reverse(self::readJson(CF_DATA_PAGE_VIEWS));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    /** Return page-view records for a specific user. */
    public static function pageViewsForUser(string $username, int $limit = 0): array
    {
        $all = array_reverse(self::readJson(CF_DATA_PAGE_VIEWS));
        $all = array_values(array_filter(
            $all,
            fn($v) => ($v['username'] ?? '') === $username
        ));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    /**
     * Aggregate page-view counts keyed by page path.
     *
     * @return array<string, int>
     */
    public static function pageViewCounts(): array
    {
        $counts = [];
        foreach (self::readJson(CF_DATA_PAGE_VIEWS) as $v) {
            $page            = $v['page'] ?? 'unknown';
            $counts[$page]   = ($counts[$page] ?? 0) + 1;
        }
        arsort($counts);
        return $counts;
    }

    // ── Support tickets ────────────────────────────────────────────────────

    /**
     * Save a new support ticket.
     *
     * @return string  The generated ticket ID.
     */
    public static function createSupportTicket(
        string $username,
        string $name,
        string $email,
        string $subject,
        string $message
    ): string {
        $id      = self::uuid();
        $ticket  = [
            'id'         => $id,
            'username'   => $username,
            'name'       => $name,
            'email'      => $email,
            'subject'    => $subject,
            'message'    => $message,
            'status'     => 'open',
            'ip'         => self::clientIp(),
            'created_at' => date('c'),
        ];
        $all     = self::readJson(CF_DATA_SUPPORT_TICKETS);
        $all[]   = $ticket;
        self::writeJson(CF_DATA_SUPPORT_TICKETS, $all);

        // Also write to the audit log
        self::log('support.ticket_created', $username, [
            'ticket_id' => $id,
            'subject'   => $subject,
        ]);

        return $id;
    }

    /** Return all support tickets, newest first. */
    public static function allSupportTickets(int $limit = 0): array
    {
        $all = array_reverse(self::readJson(CF_DATA_SUPPORT_TICKETS));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    /** Return support tickets for a specific user, newest first. */
    public static function supportTicketsForUser(string $username, int $limit = 0): array
    {
        $all = array_reverse(self::readJson(CF_DATA_SUPPORT_TICKETS));
        $all = array_values(array_filter(
            $all,
            fn($t) => ($t['username'] ?? '') === $username
        ));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    /**
     * Update a support ticket's status.
     *
     * @param string $id      Ticket UUID.
     * @param string $status  'open' | 'in_progress' | 'resolved' | 'closed'.
     */
    public static function updateTicketStatus(string $id, string $status): bool
    {
        $all     = self::readJson(CF_DATA_SUPPORT_TICKETS);
        $updated = false;
        foreach ($all as &$t) {
            if (($t['id'] ?? '') === $id) {
                $t['status']     = $status;
                $t['updated_at'] = date('c');
                $updated         = true;
                break;
            }
        }
        unset($t);
        if ($updated) {
            self::writeJson(CF_DATA_SUPPORT_TICKETS, $all);
        }
        return $updated;
    }

    // ── Login-history helpers ──────────────────────────────────────────────

    /**
     * Return all login-related audit events (user.login, user.login_failed,
     * user.logout), newest first.
     */
    public static function loginEvents(int $limit = 0): array
    {
        $all = self::readJson(CF_DATA_AUDIT_LOG);
        $all = array_reverse($all);
        $all = array_values(array_filter(
            $all,
            fn($e) => in_array($e['event'] ?? '', [
                'user.login',
                'user.login_failed',
                'user.logout',
            ], true)
        ));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    /**
     * Return login-related events for a specific user, newest first.
     */
    public static function loginEventsForUser(string $username, int $limit = 0): array
    {
        $all = self::loginEvents();
        $all = array_values(array_filter(
            $all,
            fn($e) => ($e['username'] ?? '') === $username
        ));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    /**
     * Best-effort geo-location lookup for an IP address.
     * Uses the ip-api.com free JSON endpoint (no API key required).
     * Returns an array with city, region, country, and country_code keys,
     * or an empty array on failure (private IPs, network errors, etc.).
     *
     * @param string $ip  IPv4 or IPv6 address.
     * @return array{city:string,region:string,country:string,country_code:string}|array{}
     */
    public static function geoLocate(string $ip): array
    {
        // Skip private/loopback/empty addresses
        if ($ip === '' || !filter_var($ip, FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [];
        }

        $url = 'http://ip-api.com/json/' . rawurlencode($ip)
             . '?fields=status,city,regionName,country,countryCode';

        $ctx = stream_context_create([
            'http' => [
                'method'        => 'GET',
                'timeout'       => 3,
                'ignore_errors' => true,
            ],
        ]);

        $raw = @file_get_contents($url, false, $ctx);
        if ($raw === false) {
            return [];
        }

        $data = json_decode($raw, true);
        if (!is_array($data) || ($data['status'] ?? '') !== 'success') {
            return [];
        }

        return [
            'city'         => $data['city']        ?? '',
            'region'       => $data['regionName']  ?? '',
            'country'      => $data['country']     ?? '',
            'country_code' => $data['countryCode'] ?? '',
        ];
    }

    // ── Internal helpers ───────────────────────────────────────────────────

    private static function readJson(string $path): array
    {
        if (!file_exists($path)) {
            return [];
        }
        $fp = fopen($path, 'r');
        if ($fp === false) {
            return [];
        }
        flock($fp, LOCK_SH);
        $content = stream_get_contents($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if ($content === false || $content === '') {
            return [];
        }
        $decoded = json_decode($content, true);
        return is_array($decoded) ? $decoded : [];
    }

    /** Atomic write using temp file + rename. */
    private static function writeJson(string $path, array $data): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }

        $json    = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $tmpPath = tempnam(sys_get_temp_dir(), 'cf_audit_');
        if ($tmpPath === false) {
            throw new \RuntimeException('AuditStore: unable to create temporary file.');
        }

        $fp = fopen($tmpPath, 'w');
        if ($fp === false) {
            @unlink($tmpPath);
            throw new \RuntimeException("AuditStore: cannot open temp file for writing.");
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if (!rename($tmpPath, $path)) {
            @unlink($tmpPath);
            throw new \RuntimeException("AuditStore: failed to atomically replace {$path}.");
        }
    }

    /** Return a UUID v4 formatted string. */
    private static function uuid(): string
    {
        $bytes    = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }

    /** Best-effort client IP extraction (public alias for use outside AuditStore). */
    public static function clientIpPublic(): string
    {
        return self::clientIp();
    }

    /** Best-effort client IP extraction. */
    private static function clientIp(): string
    {
        foreach (['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'] as $key) {
            $val = $_SERVER[$key] ?? '';
            if ($val !== '') {
                // Use only the first IP in a forwarded list; trim whitespace
                return trim(explode(',', $val)[0]);
            }
        }
        return '';
    }
}
