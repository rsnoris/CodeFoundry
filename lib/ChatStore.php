<?php
declare(strict_types=1);

/**
 * CodeFoundry – ChatStore
 *
 * Flat-file (JSON) storage for real-time support chat:
 *   • chat_sessions.json – one record per conversation thread
 *   • chat_messages.json – individual messages belonging to sessions
 *
 * All writes use atomic file replacement (temp file + rename).
 */
class ChatStore
{
    // ── Sessions ───────────────────────────────────────────────────────────

    /**
     * Create a new chat session and return its ID.
     */
    public static function createSession(string $username, string $subject): string
    {
        $id       = self::uuid();
        $sessions = self::readJson(CF_DATA_CHAT_SESSIONS);
        $sessions[] = [
            'id'           => $id,
            'username'     => $username,
            'subject'      => $subject,
            'status'       => 'open',
            'unread_user'  => 0,
            'unread_admin' => 0,
            'created_at'   => date('c'),
            'updated_at'   => date('c'),
        ];
        self::writeJson(CF_DATA_CHAT_SESSIONS, $sessions);
        return $id;
    }

    /** Return all sessions, newest first. */
    public static function allSessions(int $limit = 0): array
    {
        $all = array_reverse(self::readJson(CF_DATA_CHAT_SESSIONS));
        return $limit > 0 ? array_slice($all, 0, $limit) : $all;
    }

    /** Return sessions belonging to a specific user, newest first. */
    public static function sessionsForUser(string $username): array
    {
        $all = array_reverse(self::readJson(CF_DATA_CHAT_SESSIONS));
        return array_values(array_filter(
            $all,
            fn($s) => ($s['username'] ?? '') === $username
        ));
    }

    /** Return a single session by ID or null. */
    public static function getSession(string $id): ?array
    {
        foreach (self::readJson(CF_DATA_CHAT_SESSIONS) as $s) {
            if (($s['id'] ?? '') === $id) {
                return $s;
            }
        }
        return null;
    }

    /**
     * Update session status ('open' | 'closed').
     */
    public static function updateSessionStatus(string $id, string $status): bool
    {
        $allowed = ['open', 'closed'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }
        return self::patchSession($id, ['status' => $status]);
    }

    /**
     * Mark all messages in a session as read by the user (set unread_user = 0).
     */
    public static function markReadByUser(string $id): void
    {
        self::patchSession($id, ['unread_user' => 0]);
    }

    /**
     * Mark all messages in a session as read by admin (set unread_admin = 0).
     */
    public static function markReadByAdmin(string $id): void
    {
        self::patchSession($id, ['unread_admin' => 0]);
    }

    /**
     * Return the total number of unread messages across all of a user's sessions.
     */
    public static function totalUnreadForUser(string $username): int
    {
        $total = 0;
        foreach (self::readJson(CF_DATA_CHAT_SESSIONS) as $s) {
            if (($s['username'] ?? '') === $username) {
                $total += (int)($s['unread_user'] ?? 0);
            }
        }
        return $total;
    }

    /**
     * Return the total number of sessions with unread messages waiting for admin.
     */
    public static function totalUnreadForAdmin(): int
    {
        $total = 0;
        foreach (self::readJson(CF_DATA_CHAT_SESSIONS) as $s) {
            if ((int)($s['unread_admin'] ?? 0) > 0) {
                $total++;
            }
        }
        return $total;
    }

    // ── Messages ───────────────────────────────────────────────────────────

    /**
     * Append a message to a session.
     *
     * @param string $sessionId   Session UUID.
     * @param string $sender      Username of the sender.
     * @param string $senderRole  'user' or 'admin'.
     * @param string $message     Message text (max 4000 chars).
     * @return string  The new message UUID.
     */
    public static function addMessage(
        string $sessionId,
        string $sender,
        string $senderRole,
        string $message
    ): string {
        $id  = self::uuid();
        $msg = [
            'id'          => $id,
            'session_id'  => $sessionId,
            'sender'      => $sender,
            'sender_role' => $senderRole,
            'message'     => mb_substr(trim($message), 0, 4000),
            'created_at'  => date('c'),
        ];
        $all   = self::readJson(CF_DATA_CHAT_MESSAGES);
        $all[] = $msg;
        self::writeJson(CF_DATA_CHAT_MESSAGES, $all);

        // Update session: bump unread counter for the *other* party and refresh updated_at
        if ($senderRole === 'user') {
            self::patchSession($sessionId, [
                'unread_admin' => self::incrementUnread($sessionId, 'unread_admin'),
                'updated_at'   => date('c'),
            ]);
        } else {
            self::patchSession($sessionId, [
                'unread_user' => self::incrementUnread($sessionId, 'unread_user'),
                'updated_at'  => date('c'),
            ]);
        }

        return $id;
    }

    /**
     * Return all messages for a session, oldest first.
     * If $afterId is provided, return only messages that appear after that ID
     * in the file (i.e., newer messages for polling).
     */
    public static function messagesForSession(string $sessionId, string $afterId = ''): array
    {
        $all = array_filter(
            self::readJson(CF_DATA_CHAT_MESSAGES),
            fn($m) => ($m['session_id'] ?? '') === $sessionId
        );
        $all = array_values($all); // oldest first (insertion order)

        if ($afterId === '') {
            return $all;
        }

        // Find the position of afterId and return everything after it
        $found = false;
        $result = [];
        foreach ($all as $m) {
            if ($found) {
                $result[] = $m;
            }
            if (($m['id'] ?? '') === $afterId) {
                $found = true;
            }
        }
        return $result;
    }

    // ── Internal helpers ───────────────────────────────────────────────────

    /** Read and increment the unread counter for the given field without a full session reload. */
    private static function incrementUnread(string $sessionId, string $field): int
    {
        foreach (self::readJson(CF_DATA_CHAT_SESSIONS) as $s) {
            if (($s['id'] ?? '') === $sessionId) {
                return (int)($s[$field] ?? 0) + 1;
            }
        }
        return 1;
    }

    /** Apply $fields to a single session record identified by $id. */
    private static function patchSession(string $id, array $fields): bool
    {
        $sessions = self::readJson(CF_DATA_CHAT_SESSIONS);
        $patched  = false;
        foreach ($sessions as &$s) {
            if (($s['id'] ?? '') === $id) {
                $s       = array_merge($s, $fields);
                $patched = true;
                break;
            }
        }
        unset($s);
        if ($patched) {
            self::writeJson(CF_DATA_CHAT_SESSIONS, $sessions);
        }
        return $patched;
    }

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

    private static function writeJson(string $path, array $data): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            mkdir($dir, 0700, true);
        }

        $json    = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $tmpPath = tempnam(sys_get_temp_dir(), 'cf_chat_');
        if ($tmpPath === false) {
            throw new \RuntimeException('ChatStore: unable to create temporary file.');
        }

        $fp = fopen($tmpPath, 'w');
        if ($fp === false) {
            @unlink($tmpPath);
            throw new \RuntimeException("ChatStore: cannot open temp file for writing.");
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        if (!rename($tmpPath, $path)) {
            @unlink($tmpPath);
            throw new \RuntimeException("ChatStore: failed to atomically replace {$path}.");
        }
    }

    private static function uuid(): string
    {
        $bytes    = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40);
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
