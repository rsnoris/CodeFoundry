# CodeFoundry IDE Modes

The `/IDE/` route now supports two modes:

1. **Hosted VS Code mode** (new foundation)
2. **Classic Monaco mode** (existing IDE, fallback)

## Hosted VS Code mode

Enable hosted mode by configuring these keys in `Cf-Config-keys/` (or env):

- `IDE_VSCODE_ENABLED=1`
- `IDE_VSCODE_BASE_URL=https://<your-hosted-vscode-endpoint>`
- `IDE_VSCODE_TOKEN=<optional-static-token>`

When enabled, `/IDE/` redirects authenticated users to `/IDE/vscode.php`.

`/IDE/vscode-bootstrap.php` creates/loads a per-user persistent workspace under:

`Cf-Config-keys/Users/ide_workspaces/<sanitized_username>_<hash>/`

## Classic Monaco fallback

Use `/IDE/?mode=classic` to force the previous Monaco IDE.

This fallback remains available during the hosted IDE migration.
