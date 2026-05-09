<?php
declare(strict_types=1);

/**
 * CodeFoundry – Hosted IDE workspace helpers.
 */
final class IdeWorkspace
{
    /**
     * Ensure a persistent workspace directory exists for a user.
     *
     * @return array{workspace_id:string,workspace_name:string,workspace_path:string}
     */
    public static function ensureForUser(string $username): array
    {
        $raw  = trim($username);
        $safe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $raw);
        if ($safe === null || $safe === '') {
            $safe = 'user';
        }
        $hash        = substr(hash('sha256', $raw), 0, 16);
        $workspaceId = $safe . '_' . $hash;
        $path        = rtrim(CF_IDE_WORKSPACES_DIR, '/') . '/' . $workspaceId;

        self::ensureDir($path);
        self::ensureDir($path . '/.vscode');
        self::ensureDir($path . '/.codefoundry');
        self::writeIfMissing(
            $path . '/.codefoundry/README.txt',
            "This directory is your persistent CodeFoundry IDE workspace.\n"
        );
        self::writeIfMissing(
            $path . '/.vscode/settings.json',
            "{\n  \"files.autoSave\": \"off\",\n  \"editor.formatOnSave\": false\n}\n"
        );

        return [
            'workspace_id'   => $workspaceId,
            'workspace_name' => $safe,
            'workspace_path' => $path,
        ];
    }

    /**
     * Build hosted VS Code URL with folder + optional token.
     */
    public static function buildHostedUrl(string $workspacePath): string
    {
        $base = CF_IDE_VSCODE_BASE_URL;
        if ($base === '') {
            return '';
        }

        $parts = parse_url($base);
        if ($parts === false || !isset($parts['scheme'], $parts['host'])) {
            return '';
        }

        $existingQuery = [];
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $existingQuery);
        }

        $query = array_merge($existingQuery, ['folder' => $workspacePath]);
        if (CF_IDE_VSCODE_TOKEN !== '') {
            $query['tkn'] = CF_IDE_VSCODE_TOKEN;
        }

        $path = $parts['path'] ?? '';
        if ($path === '') {
            $path = '/';
        }

        $url = $parts['scheme'] . '://' . $parts['host'];
        if (isset($parts['port'])) {
            $url .= ':' . (int)$parts['port'];
        }
        $url .= $path . '?' . http_build_query($query);

        return $url;
    }

    private static function ensureDir(string $path): void
    {
        if (!is_dir($path) && !@mkdir($path, 0750, true) && !is_dir($path)) {
            throw new \RuntimeException('Failed to create IDE workspace directory.');
        }
    }

    private static function writeIfMissing(string $path, string $content): void
    {
        if (!is_file($path)) {
            $written = @file_put_contents($path, $content, LOCK_EX);
            if ($written === false && !is_file($path)) {
                throw new \RuntimeException('Failed to initialize IDE workspace file.');
            }
        }
        if (!@chmod($path, 0640) && !is_readable($path)) {
            throw new \RuntimeException('Failed to set IDE workspace file permissions.');
        }
    }
}
