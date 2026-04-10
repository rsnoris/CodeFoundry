<?php
declare(strict_types=1);

/**
 * CodeFoundry – UserStore
 *
 * Simple flat-file (JSON) data access layer for user profiles, token history,
 * projects and payments. All writes use atomic file replacement via a temp file.
 */
class UserStore
{
    // ── Users ──────────────────────────────────────────────────────────────

    /** Return all user records from data/users.json. */
    public static function allUsers(): array
    {
        return self::readJson(CF_DATA_USERS);
    }

    /**
     * Return a single user by username.
     * Merges CF_USERS (authoritative for credentials/role) with any extra
     * fields stored in data/users.json (plan, tokens_used, email, etc.).
     */
    public static function findUser(string $username): ?array
    {
        // Look up in CF_USERS first
        $base         = null;
        $basePassword = null;
        foreach (CF_USERS as $u) {
            if (isset($u['username']) && $u['username'] === $username) {
                $base         = $u;
                $basePassword = $u['password_hash'];
                break;
            }
        }
        if ($base === null) {
            return null;
        }

        // Merge in any runtime state from data/users.json
        $stored = self::allUsers();
        foreach ($stored as $row) {
            if (($row['username'] ?? '') === $username) {
                // runtime fields override defaults; credentials come from CF_USERS unless overridden
                $base = array_merge($base, $row);
                // Prefer data/users.json password_hash if present, otherwise keep CF_USERS hash
                if (empty($row['password_hash'])) {
                    $base['password_hash'] = $basePassword;
                }
                break;
            }
        }

        // Apply defaults for plan, tokens_used, email
        $base['plan']        = $base['plan']        ?? 'free';
        $base['tokens_used'] = $base['tokens_used'] ?? 0;
        $base['email']       = $base['email']       ?? '';

        return $base;
    }

    /** Persist all user records to data/users.json. */
    public static function saveUsers(array $users): void
    {
        self::writeJson(CF_DATA_USERS, $users);
    }

    /**
     * Update mutable fields of a single user in data/users.json.
     * Only allows: display, email, plan, tokens_used, password_hash.
     */
    public static function updateUser(string $username, array $fields): bool
    {
        $allowed = ['display', 'email', 'plan', 'tokens_used', 'password_hash'];
        $clean   = [];
        foreach ($allowed as $key) {
            if (array_key_exists($key, $fields)) {
                $clean[$key] = $fields[$key];
            }
        }
        if (empty($clean)) {
            return false;
        }

        $users   = self::allUsers();
        $updated = false;
        foreach ($users as &$row) {
            if (($row['username'] ?? '') === $username) {
                $row    = array_merge($row, $clean);
                $updated = true;
                break;
            }
        }
        unset($row);

        if (!$updated) {
            // User not yet in data/users.json – create a minimal record
            $users[] = array_merge(['username' => $username], $clean);
        }

        self::saveUsers($users);
        return true;
    }

    /** Add $tokens to a user's running total in data/users.json. */
    public static function addTokensUsed(string $username, int $tokens): void
    {
        $users   = self::allUsers();
        $found   = false;
        foreach ($users as &$row) {
            if (($row['username'] ?? '') === $username) {
                $row['tokens_used'] = ((int)($row['tokens_used'] ?? 0)) + $tokens;
                $found = true;
                break;
            }
        }
        unset($row);

        if (!$found) {
            $users[] = ['username' => $username, 'tokens_used' => $tokens];
        }

        self::saveUsers($users);
    }

    // ── Token history ──────────────────────────────────────────────────────

    /** Append one CodeGen usage record to data/token_history.json. */
    public static function appendTokenHistory(array $record): void
    {
        $all   = self::readJson(CF_DATA_TOKEN_HISTORY);
        $all[] = $record;
        self::writeJson(CF_DATA_TOKEN_HISTORY, $all);
    }

    /**
     * Return token history for a user (newest first), limited to $limit entries.
     */
    public static function tokenHistoryForUser(string $username, int $limit = 100): array
    {
        $all      = self::readJson(CF_DATA_TOKEN_HISTORY);
        $filtered = array_filter($all, fn($r) => ($r['username'] ?? '') === $username);
        $filtered = array_reverse(array_values($filtered));
        return array_slice($filtered, 0, $limit);
    }

    // ── Projects ───────────────────────────────────────────────────────────

    /** Return projects for a user (newest first). */
    public static function projectsForUser(string $username): array
    {
        $all      = self::readJson(CF_DATA_PROJECTS);
        $filtered = array_filter($all, fn($p) => ($p['username'] ?? '') === $username);
        $filtered = array_reverse(array_values($filtered));
        return array_values($filtered);
    }

    /**
     * Save a new project record to data/projects.json.
     * Returns the generated UUID.
     */
    public static function saveProject(array $project): string
    {
        $id             = self::uuid();
        $project['id']  = $id;
        if (!isset($project['created_at'])) {
            $project['created_at'] = date('c');
        }
        $all   = self::readJson(CF_DATA_PROJECTS);
        $all[] = $project;
        self::writeJson(CF_DATA_PROJECTS, $all);
        return $id;
    }

    /**
     * Delete a project by id and owner username.
     * Returns true if a record was removed.
     */
    public static function deleteProject(string $id, string $username): bool
    {
        $all     = self::readJson(CF_DATA_PROJECTS);
        $before  = count($all);
        $all     = array_values(array_filter(
            $all,
            fn($p) => !($p['id'] === $id && ($p['username'] ?? '') === $username)
        ));
        if (count($all) === $before) {
            return false;
        }
        self::writeJson(CF_DATA_PROJECTS, $all);
        return true;
    }

    // ── Payments ───────────────────────────────────────────────────────────

    /** Return payments for a user (newest first). */
    public static function paymentsForUser(string $username): array
    {
        $all      = self::readJson(CF_DATA_PAYMENTS);
        $filtered = array_filter($all, fn($p) => ($p['username'] ?? '') === $username);
        $filtered = array_reverse(array_values($filtered));
        return array_values($filtered);
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

    /** Atomic write using a sibling temp file + rename. */
    private static function writeJson(string $path, array $data): void
    {
        $dir     = dirname($path);
        $tmpPath = $dir . '/' . basename($path) . '.' . bin2hex(random_bytes(6)) . '.tmp';
        $json    = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $fp = fopen($tmpPath, 'w');
        if ($fp === false) {
            throw new \RuntimeException("UserStore: cannot open temp file for writing: {$tmpPath}");
        }
        flock($fp, LOCK_EX);
        fwrite($fp, $json);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        rename($tmpPath, $path);
    }

    /** Return a UUID v4 formatted string. */
    private static function uuid(): string
    {
        $bytes = random_bytes(16);
        $bytes[6] = chr((ord($bytes[6]) & 0x0f) | 0x40); // version 4
        $bytes[8] = chr((ord($bytes[8]) & 0x3f) | 0x80); // variant RFC 4122
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($bytes), 4));
    }
}
