<?php
/**
 * CodeFoundry VIRAL – Agent Chat Interface
 *
 * URL: /VIRAL/agent.php?role=<slug>
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/CodeGenProvider.php';
require_once __DIR__ . '/config.php';   // VIRAL_AGENTS constant

$role = isset($_GET['role']) ? trim((string)$_GET['role']) : '';

if ($role === '' || !array_key_exists($role, VIRAL_AGENTS)) {
    header('Location: /VIRAL/');
    exit;
}

$agent       = VIRAL_AGENTS[$role];
$agentLabel   = htmlspecialchars($agent['label'], ENT_QUOTES, 'UTF-8');
$agentDesc    = htmlspecialchars($agent['desc'],  ENT_QUOTES, 'UTF-8');
$agentIcon    = htmlspecialchars($agent['icon'],  ENT_QUOTES, 'UTF-8');
$agentAccent  = htmlspecialchars($agent['accent'], ENT_QUOTES, 'UTF-8');
$roleJson     = json_encode($role);
$agentIconJson = json_encode($agent['icon']);
$expandedRoleSlugsJson = json_encode(array_keys(VIRAL_AGENTS));

$_cf_providers_js = [];
foreach (CodeGenProvider::all() as $pid => $pdata) {
    $models = [];
    foreach ($pdata['models'] as $m) {
        $models[] = ['id' => $m['id'], 'label' => $m['label']];
    }
    $_cf_providers_js[] = [
        'id'            => $pid,
        'label'         => $pdata['label'],
        'available'     => (bool)($pdata['available'] ?? false),
        'api_key_env'   => (string)($pdata['api_key_env'] ?? ''),
        'default_model' => $pdata['default_model'],
        'models'        => $models,
    ];
}
$providersJson      = json_encode($_cf_providers_js, JSON_UNESCAPED_UNICODE);
$taskGroupsJson     = json_encode(VIRAL_TASK_CATEGORY_GROUPS, JSON_UNESCAPED_UNICODE);

// Build lightweight agent list for the sidebar navigator
$_cf_nav_agents = [];
foreach (VIRAL_AGENTS as $slug => $a) {
    $_cf_nav_agents[] = [
        'slug'     => $slug,
        'label'    => $a['label'],
        'category' => $a['category'],
        'icon'     => $a['icon'],
    ];
}
$viralNavAgentsJson = json_encode($_cf_nav_agents, JSON_UNESCAPED_UNICODE);

$page_title  = $agentLabel . ' Agent – CodeFoundry VIRAL';
$active_page = 'viral';
$page_styles = <<<PAGECSS
  :root {
    --accent: {$agent['accent']};
    --sidebar-reopen-offset: 14px;
  }

  /* ── Layout ──────────────────────────────────────────────────────────── */
  .viral-layout {
    display: flex;
    gap: 0;
    width: 100%;
    max-width: none;
    margin: 0;
    min-height: calc(100vh - var(--header-height));
    height: calc(100vh - var(--header-height));
  }

  /* ── Prompt Sidebar ──────────────────────────────────────────────────── */
  .prompt-sidebar {
    width: 290px;
    flex-shrink: 0;
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    background: #080f1e;
    position: relative;
    overflow: hidden;
    transition: width .25s ease;
    height: 100%;
  }
  .prompt-sidebar.collapsed { width: 0; border-right: none; }
  .sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px 12px;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
    white-space: nowrap;
  }
  .sidebar-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-muted);
  }
  .sidebar-search {
    padding: 10px 12px;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
  }
  .sidebar-search input {
    width: 100%;
    background: #0d1626;
    border: 1px solid #1e2e48;
    color: var(--text);
    border-radius: 8px;
    padding: 7px 11px;
    font-size: 12px;
    font-family: inherit;
    outline: none;
    transition: border-color .2s;
    box-sizing: border-box;
  }
  .sidebar-search input:focus { border-color: var(--accent); }
  .prompt-list {
    flex: 1;
    overflow-y: auto;
    padding: 6px 0;
  }
  .prompt-list::-webkit-scrollbar { width: 4px; }
  .prompt-list::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 3px; }
  .prompt-item {
    display: block;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    color: #7a8eaa;
    font-size: 12.5px;
    line-height: 1.5;
    padding: 7px 16px;
    cursor: pointer;
    transition: background .15s, color .15s;
    font-family: inherit;
    white-space: normal;
  }
  .prompt-item:hover { background: #0d1626; color: var(--text); }
  .prompt-item.hidden { display: none; }
  .prompt-count {
    padding: 6px 16px 10px;
    font-size: 11px;
    color: var(--text-muted);
    border-top: 1px solid var(--border-color);
    flex-shrink: 0;
    white-space: nowrap;
  }
  .sidebar-toggle {
    position: absolute;
    top: 14px;
    right: -14px;
    z-index: 10;
    width: 28px;
    height: 28px;
    background: #0d1626;
    border: 1px solid var(--border-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--text-muted);
    transition: background .2s, color .2s;
    flex-shrink: 0;
  }
  .sidebar-toggle:hover { background: #111b30; color: var(--accent); }
  .sidebar-reopen {
    position: fixed;
    left: 12px;
    top: calc(var(--header-height) + var(--sidebar-reopen-offset));
    z-index: 1100;
    width: 40px;
    height: 40px;
    border-radius: 999px;
    border: 1px solid var(--border-color);
    background: #0d1626;
    color: var(--text-muted);
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.31);
    transition: background .2s, color .2s, border-color .2s;
  }
  .sidebar-reopen:hover {
    background: #111b30;
    color: var(--accent);
    border-color: var(--accent);
  }
  .sidebar-reopen iconify-icon { font-size: 18px; }
  .prompt-sidebar.collapsed + .sidebar-reopen { display: flex; }

  /* ── Chat area ───────────────────────────────────────────────────────── */
  .viral-chat-wrap {
    flex: 1;
    min-width: 0;
    min-height: 0;
    padding: 28px 28px 80px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .agent-header { display: flex; align-items: center; gap: 16px; }
  .agent-icon-lg {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: var(--accent)22;
    border: 1.5px solid var(--accent)55;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }
  .agent-icon-lg iconify-icon { font-size: 26px; color: var(--accent); }
  .agent-header-info h1 { margin: 0 0 4px; font-size: 22px; font-weight: 800; }
  .agent-header-info p  { margin: 0; color: var(--text-muted); font-size: 14px; }
  .agent-selection {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 12px;
  }
  .agent-selection-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }
  .agent-selection-field label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: var(--text-muted);
  }
  .agent-selection-field select {
    width: 100%;
    background: #0d1626;
    border: 1px solid #1e2e48;
    color: var(--text);
    border-radius: 10px;
    padding: 9px 10px;
    font-size: 13px;
    font-family: inherit;
    outline: none;
    min-height: 40px;
  }
  .agent-selection-field select:focus {
    border-color: var(--accent);
  }
  .back-link {
    display: inline-flex; align-items: center; gap: 6px;
    color: var(--text-muted); font-size: 13px; margin-bottom: 8px;
    transition: color .2s;
  }
  .back-link:hover { color: var(--accent); }
  .chat-window {
    background: #0d1626; border: 1px solid var(--border-color);
    border-radius: 16px; overflow: hidden;
    display: flex; flex-direction: column;
    flex: 1;
    min-height: 0;
  }
  .chat-messages {
    flex: 1; min-height: 0; max-height: none;
    overflow-y: auto; padding: 24px 20px;
    display: flex; flex-direction: column; gap: 16px;
    scroll-behavior: smooth;
  }
  .chat-messages::-webkit-scrollbar { width: 5px; }
  .chat-messages::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 4px; }
  .msg {
    display: flex; gap: 10px; align-items: flex-start;
    max-width: 88%; animation: msgIn .25s ease;
  }
  @keyframes msgIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }
  .msg.user { align-self: flex-end; flex-direction: row-reverse; }
  .msg-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 16px;
  }
  .msg.assistant .msg-avatar { background: var(--accent)22; color: var(--accent); border: 1px solid var(--accent)44; }
  .msg.user .msg-avatar      { background: #1a2942; color: var(--text-muted); }
  .msg-bubble {
    padding: 12px 16px; border-radius: 12px;
    font-size: 14px; line-height: 1.7;
    white-space: pre-wrap; word-break: break-word;
  }
  .msg.assistant .msg-bubble { background: #111b30; border: 1px solid var(--border-color); color: var(--text); }
  .msg.user .msg-bubble      { background: var(--accent)18; border: 1px solid var(--accent)44; color: var(--text); }
  .typing-indicator { display: none; }
  .typing-indicator .dot {
    display: inline-block; width: 7px; height: 7px;
    border-radius: 50%; background: var(--accent);
    opacity: .5; margin: 0 2px; animation: blink 1.2s infinite;
  }
  .typing-indicator .dot:nth-child(2) { animation-delay: .2s; }
  .typing-indicator .dot:nth-child(3) { animation-delay: .4s; }
  @keyframes blink { 0%,80%,100%{opacity:.25} 40%{opacity:1} }
  .chat-input-bar {
    padding: 16px 20px; border-top: 1px solid var(--border-color);
    display: flex; gap: 10px; align-items: flex-end;
    background: #0d1626;
  }
  .chat-input-bar textarea {
    flex: 1; background: #0e1828; border: 1px solid #1e2e48;
    color: var(--text); border-radius: 10px; padding: 10px 14px;
    font-size: 14px; font-family: inherit; resize: none; outline: none;
    line-height: 1.6; min-height: 44px; max-height: 160px;
    overflow-y: auto; transition: border-color .2s;
  }
  .chat-input-bar textarea:focus { border-color: var(--accent); }
  .chat-send-btn {
    background: var(--accent); color: #0a1428;
    border: none; border-radius: 10px; width: 44px; height: 44px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0;
    transition: background .2s, opacity .2s;
  }
  .chat-send-btn:hover:not(:disabled) { background: #009de0; }
  .chat-send-btn:disabled { opacity: .45; cursor: not-allowed; }
  .chat-send-btn iconify-icon { font-size: 18px; }
  .error-toast {
    display: none; background: #ff4d4d22; border: 1px solid #ff4d4d55;
    color: #ff7676; border-radius: 8px; padding: 10px 14px; font-size: 13px;
  }
  .welcome-hint { text-align: center; padding: 48px 20px; color: var(--text-muted); }
  .welcome-hint iconify-icon { font-size: 42px; color: var(--accent); opacity: .6; margin-bottom: 12px; display:block; }
  .welcome-hint h2 { margin: 0 0 8px; font-size: 18px; color: var(--text); }
  .welcome-hint p  { margin: 0; font-size: 14px; line-height: 1.6; }
  .suggestion-chips { display: flex; flex-wrap: wrap; gap: 8px; padding: 0 20px 16px; }
  .chip {
    background: #0e1828; border: 1px solid #1e2e48;
    color: var(--text-muted); border-radius: 20px;
    padding: 6px 14px; font-size: 12px; cursor: pointer;
    transition: border-color .2s, color .2s;
  }
  .chip:hover { border-color: var(--accent); color: var(--accent); }

  /* ── Prompt examples right panel ─────────────────────────────────────── */
  .prompt-examples-panel {
    width: 320px;
    flex-shrink: 0;
    border-left: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    background: #080f1e;
    position: relative;
    overflow: hidden;
    transition: width .25s ease;
    height: 100%;
  }
  .prompt-examples-panel.collapsed { width: 0; border-left: none; }
  .examples-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px 12px;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
    white-space: nowrap;
  }
  .examples-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--text-muted);
  }
  .examples-search {
    padding: 10px 12px;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
  }
  .examples-search input {
    width: 100%;
    background: #0d1626;
    border: 1px solid #1e2e48;
    color: var(--text);
    border-radius: 8px;
    padding: 7px 11px;
    font-size: 12px;
    font-family: inherit;
    outline: none;
    transition: border-color .2s;
    box-sizing: border-box;
  }
  .examples-search input:focus { border-color: var(--accent); }
  .examples-list {
    flex: 1;
    overflow-y: auto;
    padding: 6px 0;
  }
  .examples-list::-webkit-scrollbar { width: 4px; }
  .examples-list::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 3px; }
  .example-item {
    display: block;
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    color: #7a8eaa;
    font-size: 12.5px;
    line-height: 1.5;
    padding: 7px 16px;
    cursor: pointer;
    transition: background .15s, color .15s;
    font-family: inherit;
    white-space: normal;
  }
  .example-item:hover { background: #0d1626; color: var(--text); }
  .example-item.hidden { display: none; }
  .examples-count {
    padding: 6px 16px 10px;
    font-size: 11px;
    color: var(--text-muted);
    border-top: 1px solid var(--border-color);
    flex-shrink: 0;
    white-space: nowrap;
  }
  .examples-toggle {
    position: absolute;
    top: 14px;
    left: -14px;
    z-index: 10;
    width: 28px;
    height: 28px;
    background: #0d1626;
    border: 1px solid var(--border-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: var(--text-muted);
    transition: background .2s, color .2s;
    flex-shrink: 0;
  }
  .examples-toggle:hover { background: #111b30; color: var(--accent); }
  .examples-reopen {
    position: fixed;
    right: 12px;
    top: calc(var(--header-height) + var(--sidebar-reopen-offset));
    z-index: 1100;
    width: 40px;
    height: 40px;
    border-radius: 999px;
    border: 1px solid var(--border-color);
    background: #0d1626;
    color: var(--text-muted);
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.31);
    transition: background .2s, color .2s, border-color .2s;
  }
  .examples-reopen:hover {
    background: #111b30;
    color: var(--accent);
    border-color: var(--accent);
  }
  .examples-reopen iconify-icon { font-size: 18px; }
  .prompt-examples-panel.collapsed + .examples-reopen { display: flex; }

  /* ── Mobile ──────────────────────────────────────────────────────────── */
  /* ── Settings toggle + collapsible dropdowns ─────────────────────────── */
  .settings-wrap { margin-top: 2px; }
  .settings-toggle {
    display: flex; align-items: center; gap: 8px; width: 100%;
    background: #0d1626; border: 1px solid #1e2e48;
    color: var(--text-muted); border-radius: 10px;
    padding: 9px 14px; font-size: 12.5px; font-family: inherit;
    cursor: pointer; transition: border-color .2s, color .2s;
  }
  .settings-toggle:hover  { border-color: var(--accent)66; color: var(--text); }
  .settings-toggle.open   { border-color: var(--accent)66; border-bottom-left-radius: 0; border-bottom-right-radius: 0; }
  .settings-toggle iconify-icon { font-size: 15px; flex-shrink: 0; }
  .settings-caret { margin-left: auto; transition: transform .25s; font-size: 14px !important; }
  .settings-toggle.open .settings-caret { transform: rotate(180deg); }
  .settings-summary-pill {
    font-size: 11px; color: #4a5e7a; white-space: nowrap; overflow: hidden;
    text-overflow: ellipsis; flex: 1; text-align: right; padding-right: 4px;
  }
  .agent-selection {
    border: 1px solid #1e2e48; border-top: none;
    border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;
    padding: 12px;
  }

  /* ── Agent nav sidebar ────────────────────────────────────────────────── */
  .agent-nav-category { border-bottom: 1px solid #111d30; }
  .agent-nav-cat-btn {
    display: flex; align-items: center; justify-content: space-between;
    width: 100%; background: none; border: none;
    padding: 8px 14px; color: #627193;
    font-size: 10.5px; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase; cursor: pointer; font-family: inherit;
    transition: color .15s;
  }
  .agent-nav-cat-btn:hover { color: var(--text); }
  .agent-nav-cat-caret { transition: transform .2s; font-size: 12px !important; }
  .agent-nav-category.open .agent-nav-cat-caret { transform: rotate(180deg); }
  .agent-nav-items { display: none; }
  .agent-nav-category.open .agent-nav-items { display: block; }
  .agent-nav-item {
    display: flex; align-items: center; gap: 7px;
    padding: 5px 14px 5px 22px; font-size: 12px; color: #7a8eaa;
    text-decoration: none; transition: background .12s, color .12s;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  }
  .agent-nav-item:hover { background: #0d1626; color: var(--text); }
  .agent-nav-item.active { color: var(--accent); background: var(--accent)0c; font-weight: 600; }
  .agent-nav-item iconify-icon { font-size: 12px !important; flex-shrink: 0; }

  @media (max-width: 900px) {
    .viral-layout { flex-direction: column; height: auto; min-height: calc(100vh - var(--header-height)); }
    .prompt-sidebar  { width: 100% !important; border-right: none; border-bottom: 1px solid var(--border-color); max-height: 260px; }
    .prompt-sidebar.collapsed { max-height: 0; overflow: hidden; border-bottom: none; }
    .prompt-examples-panel { width: 100% !important; border-left: none; border-top: 1px solid var(--border-color); max-height: 280px; }
    .prompt-examples-panel.collapsed { max-height: 0; overflow: hidden; border-top: none; }
    .sidebar-toggle  { display: none; }
    .sidebar-reopen  { display: none !important; }
    .examples-toggle { display: none; }
    .examples-reopen { display: none !important; }
    .mobile-sidebar-btn { display: inline-flex !important; }
    .viral-chat-wrap { padding: 16px 14px 80px; }
    .chat-messages   { min-height: 340px; }
    .msg             { max-width: 96%; }
    .agent-selection { grid-template-columns: 1fr; }
  }
  @media (min-width: 901px) {
    .mobile-sidebar-btn { display: none !important; }
  }

  /* ── UI Design Agentic Tool – View Mode Tabs ──────────────────────── */
  .dw-view-tabs {
    display: flex;
    gap: 4px;
    margin-bottom: 4px;
  }
  .dw-view-tab {
    background: #0e1828;
    border: 1px solid #1e2e48;
    color: var(--text-muted);
    border-radius: 8px;
    padding: 7px 16px;
    font-size: 13px;
    font-weight: 600;
    font-family: inherit;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .dw-view-tab.active {
    background: var(--accent)22;
    border-color: var(--accent)66;
    color: var(--accent);
  }
  .dw-view-tab iconify-icon { font-size: 14px; }
  .dw-iterate-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: var(--accent)18;
    border: 1px solid var(--accent)44;
    color: var(--accent);
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11.5px;
    font-weight: 600;
    margin-left: 8px;
    vertical-align: middle;
  }
  .dw-iterate-badge iconify-icon { font-size: 12px; }

  /* ── Design Workspace Panel ───────────────────────────────────────── */
  .design-workspace {
    background: #0a1220;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 0;
  }
  .dw-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-bottom: 1px solid var(--border-color);
    background: #060d1a;
    flex-wrap: wrap;
    gap: 8px;
    flex-shrink: 0;
  }
  .dw-toolbar-left  { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
  .dw-toolbar-right { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
  .dw-tab-group { display: flex; gap: 3px; }
  .dw-tab {
    background: none;
    border: 1px solid transparent;
    color: var(--text-muted);
    border-radius: 7px;
    padding: 5px 13px;
    font-size: 12.5px;
    font-family: inherit;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .dw-tab.active, .dw-tab:hover {
    background: var(--accent)18;
    border-color: var(--accent)55;
    color: var(--accent);
  }
  .dw-tab iconify-icon { font-size: 13px; }
  .dw-divider { width: 1px; height: 20px; background: var(--border-color); margin: 0 2px; }
  .dw-bp-group {
    display: flex;
    background: #0e1828;
    border: 1px solid #1e2e48;
    border-radius: 8px;
    padding: 3px;
    gap: 2px;
  }
  .dw-bp {
    background: none;
    border: none;
    color: var(--text-muted);
    border-radius: 5px;
    padding: 4px 11px;
    font-size: 12px;
    font-family: inherit;
    cursor: pointer;
    transition: all .15s;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .dw-bp.active { background: var(--accent)22; color: var(--accent); }
  .dw-bp iconify-icon { font-size: 13px; }
  .dw-action-btn {
    background: #0e1828;
    border: 1px solid #1e2e48;
    color: var(--text-muted);
    border-radius: 7px;
    padding: 5px 13px;
    font-size: 12px;
    font-family: inherit;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .dw-action-btn:hover { border-color: var(--accent)66; color: var(--accent); }
  .dw-action-btn iconify-icon { font-size: 13px; }

  /* ── Workspace Body ───────────────────────────────────────────────── */
  .dw-body {
    display: flex;
    flex: 1;
    min-height: 0;
    overflow: hidden;
  }

  /* ── Canvas Panel ─────────────────────────────────────────────────── */
  .dw-canvas-panel {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .dw-screen-bar {
    display: flex;
    gap: 4px;
    padding: 8px 12px;
    border-bottom: 1px solid var(--border-color);
    overflow-x: auto;
    flex-shrink: 0;
    background: #060d1a;
    align-items: center;
  }
  .dw-screen-bar::-webkit-scrollbar { height: 4px; }
  .dw-screen-bar::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 3px; }
  .dw-screen-tab {
    background: #0e1828;
    border: 1px solid #1e2e48;
    color: var(--text-muted);
    border-radius: 6px;
    padding: 5px 13px;
    font-size: 12px;
    white-space: nowrap;
    cursor: pointer;
    font-family: inherit;
    transition: all .15s;
    flex-shrink: 0;
  }
  .dw-screen-tab.active {
    background: var(--accent)22;
    border-color: var(--accent)66;
    color: var(--accent);
    font-weight: 600;
  }
  .dw-canvas-area {
    flex: 1;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    background: #060d1a;
    padding: 20px;
    overflow: auto;
    position: relative;
  }
  .dw-canvas-area::-webkit-scrollbar { width: 5px; height: 5px; }
  .dw-canvas-area::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 3px; }
  .dw-frame-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
  }
  .dw-frame-label {
    font-size: 11px;
    color: var(--text-muted);
    letter-spacing: .04em;
  }
  .dw-canvas-iframe {
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 32px rgba(0,0,0,.5);
    background: #fff;
    transition: width .3s ease, height .3s ease;
    display: block;
  }
  .dw-canvas-iframe.bp-web    { width: 1280px; height: 720px; }
  .dw-canvas-iframe.bp-mobile { width: 390px;  height: 844px; }
  .dw-empty-canvas {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    color: var(--text-muted);
    text-align: center;
    padding: 40px 20px;
    font-size: 14px;
    flex: 1;
  }
  .dw-empty-canvas iconify-icon { font-size: 48px; opacity: .3; }

  /* ── Right Side Panel ─────────────────────────────────────────────── */
  .dw-side-panel {
    width: 250px;
    flex-shrink: 0;
    border-left: 1px solid var(--border-color);
    background: #080f1e;
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }
  .dw-side-tab-bar {
    display: flex;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
  }
  .dw-side-tab {
    flex: 1;
    background: none;
    border: none;
    border-bottom: 2px solid transparent;
    color: var(--text-muted);
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    padding: 9px 6px;
    cursor: pointer;
    font-family: inherit;
    transition: all .15s;
  }
  .dw-side-tab.active { border-bottom-color: var(--accent); color: var(--accent); }
  .dw-side-content {
    flex: 1;
    overflow-y: auto;
    padding: 10px;
  }
  .dw-side-content::-webkit-scrollbar { width: 4px; }
  .dw-side-content::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 3px; }
  .dw-section-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-muted);
    padding: 4px 2px 6px;
  }
  .dw-comp-item {
    background: #0d1626;
    border: 1px solid #1e2e48;
    border-radius: 8px;
    padding: 8px 10px;
    font-size: 12px;
    color: var(--text-muted);
    cursor: pointer;
    margin-bottom: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all .15s;
    user-select: none;
  }
  .dw-comp-item:hover { border-color: var(--accent)55; color: var(--text); }
  .dw-comp-item.selected { border-color: var(--accent); background: var(--accent)0e; }
  .dw-comp-item iconify-icon { font-size: 14px; flex-shrink: 0; color: var(--accent); }
  .dw-comp-item-name { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
  .dw-comp-item-type {
    font-size: 10px;
    background: var(--accent)18;
    color: var(--accent);
    border-radius: 4px;
    padding: 1px 5px;
    flex-shrink: 0;
  }
  .dw-comp-copy-btn {
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 2px;
    display: flex;
    align-items: center;
    border-radius: 4px;
    transition: color .15s;
    flex-shrink: 0;
    font-size: 12px;
  }
  .dw-comp-copy-btn:hover { color: var(--accent); }
  .dw-comp-preview {
    background: #060d1a;
    border: 1px solid #1e2e48;
    border-radius: 6px;
    margin-bottom: 8px;
    overflow: hidden;
  }
  .dw-comp-preview iframe {
    width: 100%;
    height: 60px;
    border: none;
    display: block;
    pointer-events: none;
  }
  /* Properties panel */
  .dw-prop-row { margin-bottom: 10px; }
  .dw-prop-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: var(--text-muted);
    margin-bottom: 4px;
  }
  .dw-prop-input {
    width: 100%;
    background: #0d1626;
    border: 1px solid #1e2e48;
    color: var(--text);
    border-radius: 6px;
    padding: 6px 8px;
    font-size: 12px;
    font-family: inherit;
    outline: none;
    box-sizing: border-box;
    transition: border-color .15s;
    resize: vertical;
  }
  .dw-prop-input:focus { border-color: var(--accent); }
  .dw-prop-copy-btn {
    width: 100%;
    background: var(--accent)18;
    border: 1px solid var(--accent)44;
    color: var(--accent);
    border-radius: 6px;
    padding: 6px 10px;
    font-size: 12px;
    font-family: inherit;
    cursor: pointer;
    margin-top: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    transition: background .15s;
  }
  .dw-prop-copy-btn:hover { background: var(--accent)2c; }
  .dw-prop-copy-btn iconify-icon { font-size: 13px; }

  /* ── Flow Graph Panel ─────────────────────────────────────────────── */
  .dw-flow-panel {
    flex: 1;
    overflow: auto;
    padding: 20px;
    background: #060d1a;
    position: relative;
  }
  .dw-flow-panel::-webkit-scrollbar { width: 5px; height: 5px; }
  .dw-flow-panel::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 3px; }
  .flow-screen-node { cursor: pointer; }
  .flow-screen-node rect { rx: 8; }
  .flow-screen-node text { font-family: inherit; fill: #92a3bb; font-size: 12px; }
  .flow-screen-node.entry rect { stroke-width: 2; }
  .flow-edge { fill: none; stroke-width: 1.5; }
  .flow-edge.success { stroke: #4ade80; }
  .flow-edge.error   { stroke: #f87171; }
  .flow-edge.branch  { stroke: #fbbf24; }
  .flow-edge.neutral { stroke: #3b5a80; }
  .flow-edge-label   { font-size: 10px; font-family: inherit; }

  /* ── Tokens Panel ─────────────────────────────────────────────────── */
  .dw-tokens-panel {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: #060d1a;
  }
  .dw-tokens-panel::-webkit-scrollbar { width: 5px; }
  .dw-tokens-panel::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 3px; }
  .dw-tok-section { margin-bottom: 24px; }
  .dw-tok-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: var(--text-muted);
    margin-bottom: 10px;
  }
  .dw-color-grid { display: flex; flex-wrap: wrap; gap: 12px 16px; }
  .dw-color-swatch-wrap { display: flex; flex-direction: column; align-items: center; gap: 4px; }
  .dw-color-swatch {
    width: 40px; height: 40px;
    border-radius: 8px;
    border: 1px solid rgba(255,255,255,.1);
    cursor: pointer;
    transition: transform .15s;
  }
  .dw-color-swatch:hover { transform: scale(1.1); }
  .dw-color-swatch-name { font-size: 9.5px; color: var(--text-muted); text-align: center; }
  .dw-tok-table { width: 100%; border-collapse: collapse; }
  .dw-tok-table td {
    padding: 6px 4px;
    border-bottom: 1px solid #0e1828;
    font-size: 12px;
    vertical-align: middle;
  }
  .dw-tok-key { color: var(--text-muted); width: 40%; }
  .dw-tok-val { color: var(--text); font-family: monospace; }

  /* ── Status Toast ─────────────────────────────────────────────────── */
  .dw-status-toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    background: #111b30;
    border: 1px solid var(--border-color);
    color: var(--text);
    border-radius: 8px;
    padding: 10px 18px;
    font-size: 13px;
    z-index: 2000;
    box-shadow: 0 4px 20px rgba(0,0,0,.4);
    display: none;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
  }
  .dw-status-toast.success { border-color: #4ade8066; color: #4ade80; }
  .dw-status-toast.error   { border-color: #f8717166; color: #f87171; }

  /* ── Responsive overrides for workspace ──────────────────────────── */
  @media (max-width: 900px) {
    .dw-side-panel { width: 200px; }
    .dw-canvas-iframe.bp-web { width: 100%; min-width: 320px; height: 480px; }
  }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main>
  <div class="viral-layout">

    <!-- ── Agent Navigator Sidebar ───────────────────────────────────────── -->
    <aside class="prompt-sidebar" id="promptSidebar">
      <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle agent sidebar" aria-expanded="true">
        <iconify-icon icon="lucide:chevron-left" id="sidebarToggleIcon"></iconify-icon>
      </button>
      <div class="sidebar-header">
        <span class="sidebar-title">VIRAL Agents</span>
      </div>
      <div class="sidebar-search">
        <input type="text" id="promptSearch" placeholder="Search agents…" autocomplete="off">
      </div>
      <div class="prompt-list" id="promptList"></div>
      <div class="prompt-count" id="promptCount"></div>
    </aside>
    <button class="sidebar-reopen" id="sidebarReopen" type="button" aria-label="Reopen agent sidebar">
      <iconify-icon icon="lucide:panel-left-open"></iconify-icon>
    </button>

    <!-- ── Chat Area ──────────────────────────────────────────────────── -->
    <div class="viral-chat-wrap">

      <div>
        <button class="chip mobile-sidebar-btn" id="mobileSidebarBtn" style="display:none;margin-bottom:8px;">
          <iconify-icon icon="lucide:layout-grid" style="font-size:13px;margin-right:4px;"></iconify-icon>
          All Agents
        </button>
        <a href="/VIRAL/" class="back-link">
          <iconify-icon icon="lucide:arrow-left"></iconify-icon>
          All VIRAL Agents
        </a>
      </div>

      <div class="agent-header">
        <div class="agent-icon-lg">
          <iconify-icon icon="<?= $agentIcon ?>"></iconify-icon>
        </div>
        <div class="agent-header-info">
          <h1><?= $agentLabel ?></h1>
          <p><?= $agentDesc ?></p>
        </div>
      </div>

      <!-- Collapsible AI settings (model + task selectors) -->
      <div class="settings-wrap">
        <button class="settings-toggle" id="settingsToggle" type="button">
          <iconify-icon icon="lucide:sliders-horizontal"></iconify-icon>
          AI Settings
          <span class="settings-summary-pill" id="settingsSummary"></span>
          <iconify-icon icon="lucide:chevron-down" class="settings-caret"></iconify-icon>
        </button>
        <div class="agent-selection" id="agentSelection" style="display:none;">
          <div class="agent-selection-field" id="viralModelField">
            <label for="viralModelSelect">AI Model</label>
            <select id="viralModelSelect" aria-label="AI model">
              <option value="">Loading models…</option>
            </select>
            <div id="viralModelHelp" style="font-size:11px;color:var(--text-subtle);margin-top:6px;">Configure provider API keys in Dashboard → Account to enable all model families.</div>
          </div>
          <div class="agent-selection-field">
            <label for="viralTaskCategorySelect">Task Category</label>
            <select id="viralTaskCategorySelect" aria-label="Task category">
              <option value="">Any category</option>
            </select>
          </div>
          <div class="agent-selection-field">
            <label for="viralTaskSelect">Task Type</label>
            <select id="viralTaskSelect" aria-label="Task type">
              <option value="">Any task type</option>
            </select>
          </div>
        </div>
      </div>

      <div id="errorToast" class="error-toast"></div>

<?php if ($role === 'ui-design-agentic-tool'): ?>
      <!-- View-mode switcher: Chat ↔ Design Workspace -->
      <div class="dw-view-tabs" id="dwViewTabs">
        <button class="dw-view-tab active" data-view="chat" id="dwViewChat">
          <iconify-icon icon="lucide:message-square"></iconify-icon>
          Chat
        </button>
        <button class="dw-view-tab" data-view="workspace" id="dwViewWorkspace">
          <iconify-icon icon="lucide:layout"></iconify-icon>
          Design Workspace
        </button>
        <span class="dw-iterate-badge" id="dwIterateBadge" style="display:none;">
          <iconify-icon icon="lucide:layers"></iconify-icon>
          <span id="dwIterateName"></span>
        </span>
      </div>
<?php endif; ?>

      <div class="chat-window">
        <div class="chat-messages" id="chatMessages">
          <div class="welcome-hint" id="welcomeHint">
            <iconify-icon icon="<?= $agentIcon ?>"></iconify-icon>
            <h2>Your <?= $agentLabel ?></h2>
            <p><?= $agentDesc ?></p>
          </div>
          <!-- Typing indicator (hidden by default) -->
          <div class="msg assistant typing-indicator" id="typingIndicator">
            <div class="msg-avatar">
              <iconify-icon icon="<?= $agentIcon ?>"></iconify-icon>
            </div>
            <div class="msg-bubble">
              <span class="dot"></span>
              <span class="dot"></span>
              <span class="dot"></span>
            </div>
          </div>
        </div>

        <!-- Suggestion chips (shown before first message) -->
        <div class="suggestion-chips" id="suggestionChips"></div>

        <div class="chat-input-bar">
          <textarea id="chatInput" placeholder="Ask your <?= $agentLabel ?>…" rows="1"></textarea>
          <button class="chat-send-btn" id="sendBtn" aria-label="Send message">
            <iconify-icon icon="lucide:send"></iconify-icon>
          </button>
        </div>
      </div>

    </div><!-- /.viral-chat-wrap -->

<?php if ($role === 'ui-design-agentic-tool'): ?>
    <!-- ── Design Workspace (UI Design Agentic Tool only) ──────────────── -->
    <div class="design-workspace" id="designWorkspace" style="display:none;">

      <!-- Toolbar -->
      <div class="dw-toolbar">
        <div class="dw-toolbar-left">
          <div class="dw-tab-group" id="dwPanelTabs">
            <button class="dw-tab active" data-panel="canvas">
              <iconify-icon icon="lucide:monitor"></iconify-icon> Canvas
            </button>
            <button class="dw-tab" data-panel="flow">
              <iconify-icon icon="lucide:git-branch"></iconify-icon> Flow
            </button>
            <button class="dw-tab" data-panel="tokens">
              <iconify-icon icon="lucide:palette"></iconify-icon> Tokens
            </button>
          </div>
        </div>
        <div class="dw-toolbar-right">
          <div class="dw-bp-group">
            <button class="dw-bp active" id="dwBpWeb" data-bp="web">
              <iconify-icon icon="lucide:monitor"></iconify-icon> Web
            </button>
            <button class="dw-bp" id="dwBpMobile" data-bp="mobile">
              <iconify-icon icon="lucide:smartphone"></iconify-icon> Mobile
            </button>
          </div>
          <div class="dw-divider"></div>
          <button class="dw-action-btn" id="dwExportBtn" title="Export design as JSON">
            <iconify-icon icon="lucide:download"></iconify-icon> Export
          </button>
          <label class="dw-action-btn" style="cursor:pointer;" title="Import design JSON">
            <iconify-icon icon="lucide:upload"></iconify-icon> Import
            <input type="file" id="dwImportInput" accept=".json" style="display:none;">
          </label>
        </div>
      </div>

      <!-- Canvas Panel -->
      <div class="dw-body" id="dwPanelCanvas">
        <div class="dw-canvas-panel">
          <div class="dw-screen-bar" id="dwScreenBar">
            <span style="font-size:11px;color:var(--text-muted);flex-shrink:0;padding-right:6px;">Screens:</span>
          </div>
          <div class="dw-canvas-area" id="dwCanvasArea">
            <div class="dw-empty-canvas" id="dwEmptyCanvas">
              <iconify-icon icon="lucide:figma"></iconify-icon>
              <div>Describe your UI in the chat — your designs will appear here.</div>
            </div>
            <div class="dw-frame-container" id="dwFrameContainer" style="display:none;">
              <div class="dw-frame-label" id="dwFrameLabel">Web · 1280px</div>
              <iframe
                class="dw-canvas-iframe bp-web"
                id="dwCanvasIframe"
                sandbox="allow-scripts allow-same-origin"
                title="Design preview"
              ></iframe>
            </div>
          </div>
        </div>
        <!-- Component Library + Properties side panel -->
        <div class="dw-side-panel">
          <div class="dw-side-tab-bar" id="dwSideTabBar">
            <button class="dw-side-tab active" data-side="components">Components</button>
            <button class="dw-side-tab" data-side="props">Properties</button>
          </div>
          <div class="dw-side-content" id="dwSideContent">
            <div class="dw-section-label">Component Library</div>
            <div id="dwCompList"></div>
          </div>
        </div>
      </div>

      <!-- Flow Panel -->
      <div class="dw-body" id="dwPanelFlow" style="display:none;">
        <div class="dw-flow-panel">
          <svg id="dwFlowSvg" style="width:100%;overflow:visible;" aria-label="User flow diagram">
            <defs>
              <marker id="dwArrowhead" markerWidth="8" markerHeight="6" refX="8" refY="3" orient="auto">
                <polygon points="0 0, 8 3, 0 6" fill="#3b5a80"></polygon>
              </marker>
              <marker id="dwArrowSuccess" markerWidth="8" markerHeight="6" refX="8" refY="3" orient="auto">
                <polygon points="0 0, 8 3, 0 6" fill="#4ade80"></polygon>
              </marker>
              <marker id="dwArrowError" markerWidth="8" markerHeight="6" refX="8" refY="3" orient="auto">
                <polygon points="0 0, 8 3, 0 6" fill="#f87171"></polygon>
              </marker>
              <marker id="dwArrowBranch" markerWidth="8" markerHeight="6" refX="8" refY="3" orient="auto">
                <polygon points="0 0, 8 3, 0 6" fill="#fbbf24"></polygon>
              </marker>
            </defs>
          </svg>
        </div>
      </div>

      <!-- Tokens Panel -->
      <div class="dw-body" id="dwPanelTokens" style="display:none;">
        <div class="dw-tokens-panel" id="dwTokensPanel"></div>
      </div>

    </div><!-- /#designWorkspace -->

    <!-- Status toast for copy/export/import feedback -->
    <div id="dwStatusToast" class="dw-status-toast" role="status" aria-live="polite"></div>
<?php endif; ?>

    <aside class="prompt-examples-panel" id="examplesPanel">
      <button class="examples-toggle" id="examplesToggle" aria-label="Toggle prompt examples" aria-expanded="true">
        <iconify-icon icon="lucide:chevron-right" id="examplesToggleIcon"></iconify-icon>
      </button>
      <div class="examples-header">
        <span class="examples-title">Prompt Examples</span>
      </div>
      <div class="examples-search">
        <input type="text" id="exampleSearch" placeholder="Search examples…" autocomplete="off">
      </div>
      <div class="examples-list" id="exampleList"></div>
      <div class="examples-count" id="exampleCount"></div>
    </aside>
    <button class="examples-reopen" id="examplesReopen" type="button" aria-label="Reopen prompt examples">
      <iconify-icon icon="lucide:panel-right-open"></iconify-icon>
    </button>
  </div><!-- /.viral-layout -->
</main>

<?php
$page_scripts = <<<PAGEJS
(function () {
  const ROLE       = {$roleJson};
  const AGENT_ICON = {$agentIconJson};
  const EXPANDED_ROLE_SLUGS = {$expandedRoleSlugsJson};
  const CF_PROVIDERS = {$providersJson};
  const VIRAL_TASK_GROUPS = {$taskGroupsJson};
  const ALL_AGENTS = {$viralNavAgentsJson};
  const MAX_PROMPTS_PER_EXPANDED_ROLE = 64;
  const SUGGESTION_CHIP_COUNT = 8;

  const BASE_SUGGESTIONS = {
    'software-engineer':    ['Review this code snippet', 'How do I design a REST API?', 'Explain SOLID principles', 'Best practices for async JavaScript', 'Optimize a slow database query', 'Implement JWT authentication', 'Explain the CAP theorem', 'How do I implement caching with Redis?'],
    'product-manager':      ['Write a user story for login', 'How do I prioritize a backlog?', 'Draft a product requirements doc', 'Define success metrics for a feature', 'Write a go-to-market plan', 'Explain the RICE prioritization framework', 'How do I measure product-market fit?', 'Create a product roadmap for a SaaS startup'],
    'data-scientist':       ['How do I handle missing values?', 'Explain gradient boosting', 'Write a Python EDA script', 'Compare classification models', 'Explain the bias-variance tradeoff', 'How do I evaluate a model with imbalanced classes?', 'What is k-fold cross-validation?', 'Build a time series forecasting model'],
    'marketing-manager':    ['Write a product launch email', 'Create a 30-day content plan', 'What is the best funnel strategy?', 'How to A/B test ad copy?', 'Build a customer persona', 'Write a brand positioning statement', 'Plan an email nurture sequence', 'How do I calculate marketing ROI?'],
    'sales-agent':          ['Write a cold outreach email', 'How to handle price objections?', 'Create a follow-up sequence', 'Draft a sales proposal intro', 'Write a discovery call script', 'How do I build rapport quickly?', 'Create a competitor battle card', 'Write a post-demo follow-up email'],
    'customer-support':     ['Reply to an angry customer', 'How to de-escalate a complaint?', 'Write an apology email', 'Handle a refund request politely', 'Create a FAQ response template', 'How do I reduce first-response time?', 'Write a proactive outage notification', 'Improve customer satisfaction scores'],
    'hr-manager':           ['Write a job description for a developer', 'Draft interview questions', 'Create an onboarding checklist', 'How to handle a performance issue?', 'Design a competency framework', 'Write an employee handbook section', 'Create a 90-day onboarding plan', 'How do I conduct a salary benchmarking study?'],
    'financial-analyst':    ['Explain EBITDA vs net income', 'Build a simple revenue model', 'How to forecast cash flow?', 'Review this financial summary', 'Explain discounted cash flow valuation', 'What is WACC?', 'Build a sensitivity analysis in Excel', 'Explain the Rule of 40 for SaaS'],
    'legal-counsel':        ['Review this NDA clause', 'What is GDPR consent requirement?', 'Explain contractor vs employee', 'Draft a software license summary', 'What is a data processing agreement?', 'Explain intellectual property assignment', 'Review this SaaS terms of service', 'What are CCPA requirements?'],
    'ux-designer':          ['How to run a user interview?', 'Create a user journey map', 'Critique this UI design', 'Write a usability test plan', 'Explain the Gestalt principles in UI', 'How do I conduct card sorting?', 'Write a UX research report', 'Design an onboarding flow for a new app'],
    'ui-design-agentic-tool': ['Create an editable Figma wireframe for a mobile banking app onboarding flow', 'Generate a high-fidelity web dashboard design with cards, tables, and filters', 'Turn this plain-English feature brief into a complete user flow with all screens', 'Refine this checkout flow using follow-up changes without rebuilding every screen', 'Design a responsive mobile + desktop version of a pricing page with consistent style tokens', 'Define a reusable component system for buttons, cards, navigation, and form states', 'Produce two distinct visual style directions for the same product requirements', 'Map and design an end-to-end SaaS onboarding journey from signup to first value'],
    'devops-engineer':      ['Set up a CI/CD pipeline', 'Explain Kubernetes vs Docker Swarm', 'How to monitor a Node.js app?', 'Write a Terraform AWS config', 'Configure Nginx load balancer', 'Explain blue-green vs canary deployments', 'Set up Prometheus and Grafana', 'Write a Helm chart'],
    'content-writer':       ['Write a blog intro about AI', 'Create 5 headline options', 'Improve this paragraph', 'Write a LinkedIn post about growth', 'Draft a case study outline', 'Write a product description', 'Create a content brief template', 'Write a compelling call to action'],
    'seo-specialist':       ['Audit this page title & meta', 'Find keywords for SaaS landing page', 'How to improve Core Web Vitals?', 'Write an SEO-friendly H1', 'Build an internal linking strategy', 'Explain E-E-A-T signals', 'Write a meta description for a product page', 'How do I earn featured snippets?'],
    'business-analyst':     ['Write acceptance criteria for login', 'Map this business process', 'Identify gaps in this workflow', 'Create a use-case diagram description', 'Write a business requirements document', 'Conduct a gap analysis', 'Explain MoSCoW prioritization', 'Create a requirements traceability matrix'],
    'project-manager':      ['Create a project kick-off agenda', 'Write a risk register template', 'How to run a sprint retrospective?', 'Draft a project status report', 'Build a project charter', 'Write a RACI matrix', 'Explain earned value management', 'Create a work breakdown structure'],
    'security-expert':      ['Review this authentication flow', 'Explain OWASP Top 10', 'How to secure a REST API?', 'Perform a threat model for login', 'Explain zero-trust architecture', 'Configure security HTTP headers', 'How do I prevent SQL injection?', 'Conduct a STRIDE threat model'],
    'social-media-manager': ['Write 5 tweet ideas about AI', 'Create a week-long content calendar', 'How to grow LinkedIn followers?', 'Draft an Instagram caption', 'Write a YouTube video description', 'Build a hashtag strategy', 'Create a social media crisis plan', 'Measure social media ROI'],
    'qa-engineer':          ['Write test cases for login flow', 'How to set up Selenium tests?', 'Create a bug report template', 'Explain boundary value analysis', 'Write a test plan document', 'Set up contract testing with Pact', 'Explain the testing pyramid', 'Write BDD scenarios with Gherkin'],
    'cto-advisor':          ['How to scale an engineering team?', 'Build vs buy decision framework', 'How to manage tech debt?', 'Advise on choosing a tech stack', 'How to establish engineering culture?', 'Explain platform engineering', 'How do I run architecture reviews?', 'Create an engineering OKR process'],
    'recruiter':            ['Write a LinkedIn outreach message', 'Create a scorecard for a PM role', 'How to screen resumes efficiently?', 'Draft an offer letter intro', 'Write a job ad that attracts top talent', 'Build an interview process for engineers', 'How do I reduce time-to-hire?', 'Write a candidate rejection email'],
    // AI Architecture layer agents
    'infrastructure-layer':         ['Design a scalable API gateway', 'Compare managed vs self-hosted vector DB', 'Set up a message queue for agent tasks', 'Best practices for AI infra cost control', 'Design a multi-tenant AI service', 'Implement GPU auto-scaling', 'Optimize LLM inference latency', 'Build a fault-tolerant AI pipeline'],
    'agent-internet-layer':         ['How do agents share memory?', 'Design a multi-agent coordination flow', 'Compare LangGraph vs CrewAI', 'Build a pub-sub event mesh for agents', 'Implement agent-to-agent authentication', 'Design a task delegation protocol', 'Handle agent failures gracefully', 'Monitor inter-agent communication'],
    'protocol-layer':               ['Explain Model Context Protocol (MCP)', 'Design a tool schema for a search function', 'How to version an agent API?', 'Create an OpenAPI spec for an agent endpoint', 'Implement tool call retries', 'Design a structured output schema', 'Handle streaming tool responses', 'Validate tool input/output contracts'],
    'tooling-enrichment-layer':     ['Set up a RAG pipeline with Pinecone', 'Best chunking strategy for long documents', 'How to integrate web search into an agent?', 'Compare embedding models for retrieval', 'Implement hybrid search (BM25 + vector)', 'Build a document ingestion pipeline', 'Evaluate RAG retrieval quality', 'Add re-ranking to a RAG pipeline'],
    'cognition-reasoning-layer':    ['Explain ReAct vs Chain-of-Thought', 'Design a self-reflection loop for an agent', 'How to decompose a complex multi-step task?', 'Build an error-correction mechanism for agents', 'Implement a planning agent with tools', 'Compare tree-of-thought vs CoT', 'Design a critique-and-revise loop', 'Handle long reasoning chains efficiently'],
    'memory-personalization-layer': ['Design a long-term memory architecture', 'How to store user preferences for an AI?', 'Compare vector memory vs knowledge graph', 'Privacy-preserving agent memory strategies', 'Implement episodic memory for agents', 'Build a user profile from chat history', 'Expire and refresh stale memories', 'Summarize long conversation history'],
    'application-layer':            ['Design a conversational AI onboarding flow', 'Best practices for streaming AI responses', 'How to build user trust in an AI assistant?', 'Design a copilot UI for a developer tool', 'Handle clarification and ambiguity in chat', 'Build a multi-turn conversation UI', 'Display agent reasoning to users', 'Design a feedback loop for AI responses'],
    'operations-governance-layer':  ['Set up AI cost monitoring and alerts', 'Explain the EU AI Act requirements', 'Design safety guardrails for an AI agent', 'How to conduct AI red-teaming?', 'Implement PII detection in AI outputs', 'Build an AI audit logging system', 'Create an AI incident response plan', 'Measure and report AI model bias'],
  };

  const PROMPT_TEMPLATES = [
    'Create a 30-60-90 day plan for a {role}',
    'Draft an SOP checklist a {role} can use weekly',
    'Write a beginner-to-advanced roadmap for becoming a {role}',
    'What KPIs should a {role} track monthly?',
    'Create a decision framework for a {role} handling tradeoffs',
    'Write a stakeholder update email from a {role}',
    'Design a repeatable workflow for a {role}',
    'Create a risk register template for a {role}',
    'Draft an executive summary from a {role} perspective',
    'What are common mistakes new {role}s make and how to avoid them?',
    'Create a quality review checklist for a {role}',
    'Design a dashboard a {role} would use daily',
    'Write interview questions to hire a strong {role}',
    'Build a competency matrix for a {role}',
    'Create a weekly planning template for a {role}',
    'Draft a retrospective template tailored to a {role}',
    'Write a playbook for handling urgent issues as a {role}',
    'Create a communication plan a {role} can use cross-functionally',
    'Define success criteria for a project owned by a {role}',
    'Create a one-page strategy brief from a {role}',
    'List automation opportunities a {role} should prioritize',
    'Write a troubleshooting flowchart for a {role}',
    'Draft a training plan for onboarding a new {role}',
    'Create a scorecard to evaluate {role} outcomes',
    'Build a monthly review template for a {role}',
    'Write a proposal outline from a {role}',
    'Create a handoff checklist used by a {role}',
    'Draft a process-improvement plan for a {role}',
    'Write a meeting agenda template for a {role}',
    'Create a project intake form from a {role} perspective',
    'What tools should every {role} master first?',
    'Create a prioritization rubric for a {role}',
    'Design a governance policy a {role} can enforce',
    'Write a timeline template for work led by a {role}',
    'Draft escalation guidelines for a {role}',
    'Create a status-report template used by a {role}',
    'How should a {role} measure ROI on initiatives?',
    'Write a budgeting framework for a {role}',
    'Create a quarterly planning framework for a {role}',
    'Design a customer/user feedback loop for a {role}',
    'Write a compliance checklist relevant to a {role}',
    'Create a documentation standard for a {role}',
    'Draft a template for presenting recommendations as a {role}',
    'Create an incident postmortem template for a {role}',
    'Write a SWOT analysis template from a {role} viewpoint',
    'Create a change-management plan for a {role}',
    'What metrics indicate high performance for a {role}?',
    'Write a mentorship plan for a junior {role}',
    'Create a cross-team collaboration charter for a {role}',
    'Draft a stakeholder Q&A sheet from a {role}',
    'Create a weekly standup update format for a {role}',
    'Design a pilot program plan led by a {role}',
    'Write acceptance criteria examples a {role} can use',
    'Create a governance cadence for a {role}',
    'Draft a role-specific ethics checklist for a {role}',
    'Build a cost-optimization plan from a {role}',
    'Create a quarterly business review template for a {role}',
    'Write a vendor evaluation checklist for a {role}',
    'Create a lessons-learned template for a {role}',
    'Draft a stakeholder alignment memo from a {role}',
    'Create a risk mitigation matrix for a {role}',
    'Write a hiring scorecard template for selecting a {role}',
    'Create a maturity model relevant to a {role}',
    'Build a service-level expectations template for a {role}',
  ];

  function titleFromSlug(slug) {
    return String(slug || '').split('-').map(function (part) {
      return part ? part.charAt(0).toUpperCase() + part.slice(1) : '';
    }).join(' ');
  }

  function buildPromptsForRole(role) {
    const base = BASE_SUGGESTIONS[role] || [];
    if (!EXPANDED_ROLE_SLUGS.includes(role)) return base;

    const roleTitle = titleFromSlug(role);
    const generated = PROMPT_TEMPLATES.map(function (template) {
      return template.split('{role}').join(roleTitle);
    });
    // Deduplicate overlap between hand-curated base prompts and generated templates.
    return Array.from(new Set(base.concat(generated))).slice(0, MAX_PROMPTS_PER_EXPANDED_ROLE);
  }

  const chatMessages  = document.getElementById('chatMessages');
  const chatInput     = document.getElementById('chatInput');
  const sendBtn       = document.getElementById('sendBtn');
  const typingInd     = document.getElementById('typingIndicator');
  const errorToast    = document.getElementById('errorToast');
  const welcomeHint   = document.getElementById('welcomeHint');
  const suggChips     = document.getElementById('suggestionChips');
  const promptSidebar = document.getElementById('promptSidebar');
  const promptList    = document.getElementById('promptList');
  const promptCount   = document.getElementById('promptCount');
  const promptSearch  = document.getElementById('promptSearch');
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebarToggleIcon = document.getElementById('sidebarToggleIcon');
  const sidebarReopen = document.getElementById('sidebarReopen');
  const mobileSidebarBtn = document.getElementById('mobileSidebarBtn');
  const modelField    = document.getElementById('viralModelField');
  const modelSelect   = document.getElementById('viralModelSelect');
  const modelHelp     = document.getElementById('viralModelHelp');
  const taskCategorySelect = document.getElementById('viralTaskCategorySelect');
  const taskSelect    = document.getElementById('viralTaskSelect');
  const examplesPanel = document.getElementById('examplesPanel');
  const exampleList   = document.getElementById('exampleList');
  const exampleCount  = document.getElementById('exampleCount');
  const exampleSearch = document.getElementById('exampleSearch');
  const examplesToggle = document.getElementById('examplesToggle');
  const examplesToggleIcon = document.getElementById('examplesToggleIcon');
  const examplesReopen = document.getElementById('examplesReopen');
  const settingsToggle = document.getElementById('settingsToggle');
  const agentSelection = document.getElementById('agentSelection');
  const settingsSummary = document.getElementById('settingsSummary');
  const rolePrompts   = buildPromptsForRole(ROLE);
  let history = [];
  let busy    = false;

  function populateModelSelector() {
    if (!modelSelect || !Array.isArray(CF_PROVIDERS) || !CF_PROVIDERS.length) {
      if (modelField) modelField.style.display = 'none';
      return;
    }
    modelSelect.innerHTML = '';
    const missingKeys = [];
    CF_PROVIDERS.forEach(function (p) {
      const group = document.createElement('optgroup');
      group.label = p.available ? p.label : (p.label + ' (setup required)');
      if (!p.available && p.api_key_env) {
        missingKeys.push(p.api_key_env);
      }
      (p.models || []).forEach(function (m) {
        const opt = document.createElement('option');
        opt.value = p.id + ':' + m.id;
        opt.textContent = p.available ? m.label : (m.label + ' (set key in Account)');
        opt.disabled = !p.available;
        group.appendChild(opt);
      });
      modelSelect.appendChild(group);
    });
    const enabledOptions = Array.from(modelSelect.options).filter(function (o) { return !o.disabled; });
    const optionValues = enabledOptions.map(function (o) { return o.value; });
    const saved = localStorage.getItem('cf_viral_ai_model');
    let hasValidSaved = false;
    if (saved) {
      hasValidSaved = optionValues.indexOf(saved) !== -1;
      if (hasValidSaved) modelSelect.value = saved;
    }
    if (!hasValidSaved) {
      const preferredProvider = CF_PROVIDERS[0];
      if (preferredProvider && preferredProvider.default_model) {
        const preferred = preferredProvider.id + ':' + preferredProvider.default_model;
        const preferredExists = optionValues.indexOf(preferred) !== -1;
        if (preferredExists) modelSelect.value = preferred;
      } else if (enabledOptions.length) {
        modelSelect.value = enabledOptions[0].value;
      }
    }
    if (!enabledOptions.length) {
      modelSelect.disabled = true;
      modelSelect.value = '';
    }
    if (modelHelp) {
      if (!enabledOptions.length) {
        modelHelp.textContent = 'No provider keys configured yet. Add keys in Dashboard → Account to enable additional providers and model families.';
      } else if (missingKeys.length) {
        modelHelp.textContent = 'More providers can be enabled by adding keys in Dashboard → Account.';
      }
    }
    modelSelect.addEventListener('change', function () {
      localStorage.setItem('cf_viral_ai_model', modelSelect.value);
      updateSettingsSummary();
    });
  }

  function populateTaskCategories() {
    if (!taskCategorySelect || !taskSelect) return;
    const groups = VIRAL_TASK_GROUPS || {};
    taskCategorySelect.innerHTML = '<option value="">Any category</option>';
    Object.keys(groups).forEach(function (cat) {
      const opt = document.createElement('option');
      opt.value = cat;
      opt.textContent = cat;
      taskCategorySelect.appendChild(opt);
    });
    const savedCategory = localStorage.getItem('cf_viral_task_category') || '';
    if (savedCategory && groups[savedCategory]) {
      taskCategorySelect.value = savedCategory;
    }
    populateTasksForCategory(taskCategorySelect.value);
    const savedTask = localStorage.getItem('cf_viral_task') || '';
    if (savedTask) {
      const taskExists = Array.from(taskSelect.options).some(function (o) { return o.value === savedTask; });
      if (taskExists) taskSelect.value = savedTask;
    }
    taskCategorySelect.addEventListener('change', function () {
      localStorage.setItem('cf_viral_task_category', this.value || '');
      populateTasksForCategory(this.value || '');
      updateSettingsSummary();
    });
    taskSelect.addEventListener('change', function () {
      localStorage.setItem('cf_viral_task', this.value || '');
      updateSettingsSummary();
    });
  }

  function populateTasksForCategory(category) {
    if (!taskSelect) return;
    const groups = VIRAL_TASK_GROUPS || {};
    taskSelect.innerHTML = '<option value="">Any task type</option>';
    const tasks = category && Array.isArray(groups[category]) ? groups[category] : [];
    tasks.forEach(function (task) {
      const opt = document.createElement('option');
      opt.value = task;
      opt.textContent = task;
      taskSelect.appendChild(opt);
    });
  }

  function getAiSelection() {
    const val = modelSelect ? String(modelSelect.value || '') : '';
    if (!val) return {};
    const sep = val.indexOf(':');
    return sep > 0
      ? { provider: val.slice(0, sep), model: val.slice(sep + 1) }
      : {};
  }

  function getTaskSelection() {
    return {
      task_category: taskCategorySelect ? String(taskCategorySelect.value || '') : '',
      task: taskSelect ? String(taskSelect.value || '') : '',
    };
  }

  populateModelSelector();
  populateTaskCategories();
  updateSettingsSummary();

  // Render suggestion chips
  const chips = rolePrompts.slice(0, SUGGESTION_CHIP_COUNT);
  chips.forEach(function (text) {
    const btn = document.createElement('button');
    btn.className = 'chip';
    btn.textContent = text;
    btn.addEventListener('click', function () {
      chatInput.value = text;
      chatInput.dispatchEvent(new Event('input'));
      sendMessage();
    });
    suggChips.appendChild(btn);
  });

  function updateSidebarIcon() {
    if (!sidebarToggleIcon || !promptSidebar || !sidebarToggle) return;
    const collapsed = promptSidebar.classList.contains('collapsed');
    sidebarToggleIcon.setAttribute('icon', collapsed ? 'lucide:chevron-right' : 'lucide:chevron-left');
    sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
    if (sidebarReopen) {
      if (collapsed) {
        sidebarReopen.removeAttribute('aria-hidden');
      } else {
        sidebarReopen.setAttribute('aria-hidden', 'true');
      }
    }
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function updateSettingsSummary() {
    if (!settingsSummary) return;
    var modelLabel = '';
    if (modelSelect && modelSelect.selectedIndex >= 0) {
      var selectedOpt = modelSelect.options[modelSelect.selectedIndex];
      if (selectedOpt) modelLabel = selectedOpt.textContent;
    }
    var catVal = taskCategorySelect ? (taskCategorySelect.value || '') : '';
    var parts = [modelLabel, catVal].filter(Boolean);
    settingsSummary.textContent = parts.length ? parts.join(' · ') : '';
  }

  function renderAgentNav(searchTerm) {
    if (!promptList || !promptCount) return;
    const term = String(searchTerm || '').toLowerCase().trim();
    promptList.innerHTML = '';

    // Group agents by category
    var grouped = {};
    ALL_AGENTS.forEach(function (a) {
      if (!grouped[a.category]) grouped[a.category] = [];
      grouped[a.category].push(a);
    });

    var sortedCats = Object.keys(grouped).sort(function (a, b) { return a.localeCompare(b); });
    var totalVisible = 0;

    sortedCats.forEach(function (cat) {
      var agents = grouped[cat].filter(function (a) {
        if (!term) return true;
        return a.label.toLowerCase().indexOf(term) !== -1 || a.category.toLowerCase().indexOf(term) !== -1;
      });
      if (!agents.length) return;
      totalVisible += agents.length;

      var isCurrentCat = agents.some(function (a) { return a.slug === ROLE; });

      var catDiv = document.createElement('div');
      catDiv.className = 'agent-nav-category' + (isCurrentCat || term ? ' open' : '');

      var catBtn = document.createElement('button');
      catBtn.type = 'button';
      catBtn.className = 'agent-nav-cat-btn';
      catBtn.appendChild(document.createTextNode(cat));
      var catCaret = document.createElement('iconify-icon');
      catCaret.setAttribute('icon', 'lucide:chevron-down');
      catCaret.className = 'agent-nav-cat-caret';
      catBtn.appendChild(catCaret);
      catBtn.addEventListener('click', function () { catDiv.classList.toggle('open'); });

      var itemsDiv = document.createElement('div');
      itemsDiv.className = 'agent-nav-items';

      agents.forEach(function (a) {
        var link = document.createElement('a');
        link.href = '/VIRAL/agent.php?role=' + encodeURIComponent(a.slug);
        link.className = 'agent-nav-item' + (a.slug === ROLE ? ' active' : '');
        var navIcon = document.createElement('iconify-icon');
        navIcon.setAttribute('icon', a.icon);
        link.appendChild(navIcon);
        link.appendChild(document.createTextNode(a.label));
        itemsDiv.appendChild(link);
      });

      catDiv.appendChild(catBtn);
      catDiv.appendChild(itemsDiv);
      promptList.appendChild(catDiv);
    });

    promptCount.textContent = totalVisible + ' agents';
  }

  renderAgentNav('');
  updateSidebarIcon();

  function updateExamplesIcon() {
    if (!examplesToggleIcon || !examplesPanel || !examplesToggle) return;
    const collapsed = examplesPanel.classList.contains('collapsed');
    examplesToggleIcon.setAttribute('icon', collapsed ? 'lucide:chevron-left' : 'lucide:chevron-right');
    examplesToggle.setAttribute('aria-expanded', String(!collapsed));
    if (examplesReopen) {
      if (collapsed) {
        examplesReopen.removeAttribute('aria-hidden');
      } else {
        examplesReopen.setAttribute('aria-hidden', 'true');
      }
    }
  }

  function renderPromptExamples(searchTerm) {
    if (!exampleList || !exampleCount) return;
    const term = String(searchTerm || '').toLowerCase().trim();
    let visible = 0;
    exampleList.innerHTML = '';
    rolePrompts.forEach(function (text) {
      if (term && text.toLowerCase().indexOf(term) === -1) return;
      visible++;
      const btn = document.createElement('button');
      btn.className = 'example-item';
      btn.type = 'button';
      btn.textContent = text;
      btn.addEventListener('click', function () {
        if (!chatInput) return;
        chatInput.value = text;
        chatInput.dispatchEvent(new Event('input'));
        if (typeof sendMessage === 'function') sendMessage();
      });
      exampleList.appendChild(btn);
    });
    exampleCount.textContent = visible + (visible === 1 ? ' example' : ' examples');
  }

  renderPromptExamples('');
  updateExamplesIcon();

  if (promptSearch) {
    promptSearch.addEventListener('input', function () {
      renderAgentNav(this.value);
    });
  }

  if (sidebarToggle && promptSidebar) {
    sidebarToggle.addEventListener('click', function () {
      promptSidebar.classList.toggle('collapsed');
      updateSidebarIcon();
      const expanded = !promptSidebar.classList.contains('collapsed');
      if (expanded && promptSearch) {
        promptSearch.focus();
      }
    });
  }

  if (mobileSidebarBtn && promptSidebar) {
    mobileSidebarBtn.addEventListener('click', function () {
      promptSidebar.classList.toggle('collapsed');
      updateSidebarIcon();
    });
  }

  if (sidebarReopen && promptSidebar) {
    sidebarReopen.addEventListener('click', function () {
      promptSidebar.classList.remove('collapsed');
      updateSidebarIcon();
      if (promptSearch) promptSearch.focus();
    });
  }

  if (settingsToggle && agentSelection) {
    settingsToggle.addEventListener('click', function () {
      var isOpen = settingsToggle.classList.toggle('open');
      agentSelection.style.display = isOpen ? 'grid' : 'none';
    });
  }

  if (exampleSearch) {
    exampleSearch.addEventListener('input', function () {
      renderPromptExamples(this.value);
    });
  }

  if (examplesToggle && examplesPanel) {
    examplesToggle.addEventListener('click', function () {
      examplesPanel.classList.toggle('collapsed');
      updateExamplesIcon();
      const expanded = !examplesPanel.classList.contains('collapsed');
      if (expanded && exampleSearch) {
        exampleSearch.focus();
      }
    });
  }

  if (examplesReopen && examplesPanel) {
    examplesReopen.addEventListener('click', function () {
      examplesPanel.classList.remove('collapsed');
      updateExamplesIcon();
      if (exampleSearch) exampleSearch.focus();
    });
  }

  function scrollDown() {
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function showError(msg) {
    errorToast.textContent = msg;
    errorToast.style.display = 'block';
    setTimeout(function () { errorToast.style.display = 'none'; }, 5000);
  }

  function appendMessage(role, text) {
    const isUser = role === 'user';
    const msgDiv = document.createElement('div');
    msgDiv.className = 'msg ' + role;

    const avatarDiv = document.createElement('div');
    avatarDiv.className = 'msg-avatar';
    if (isUser) {
      avatarDiv.innerHTML = '<iconify-icon icon="lucide:user"></iconify-icon>';
    } else {
      avatarDiv.innerHTML = '<iconify-icon icon="' + AGENT_ICON + '"></iconify-icon>';
    }

    const bubbleDiv = document.createElement('div');
    bubbleDiv.className = 'msg-bubble';
    bubbleDiv.textContent = text;

    msgDiv.appendChild(avatarDiv);
    msgDiv.appendChild(bubbleDiv);
    chatMessages.insertBefore(msgDiv, typingInd);
    scrollDown();
    return msgDiv;
  }

  function sendMessage() {
    const text = chatInput.value.trim();
    if (!text || busy) return;
    busy = true;

    // Hide welcome & chips
    if (welcomeHint) welcomeHint.style.display = 'none';
    suggChips.style.display = 'none';

    appendMessage('user', text);
    history.push({ role: 'user', content: text });
    chatInput.value = '';
    chatInput.style.height = 'auto';
    sendBtn.disabled = true;
    errorToast.style.display = 'none';

    // Show typing
    typingInd.style.display = 'flex';
    scrollDown();

    var payload = Object.assign(
      { role: ROLE, message: text, history: history.slice(0, -1) },
      getAiSelection(),
      getTaskSelection()
    );

    // For the UI Design Agentic Tool, pass the current design state so the
    // model can issue targeted patches instead of regenerating everything.
    if (IS_DESIGN_ROLE && designState) {
      payload.design_context = designState;
    }

    fetch('/VIRAL/chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    })
    .then(function (res) { return res.json(); })
    .then(function (data) {
      typingInd.style.display = 'none';
      if (data.error) {
        showError(data.error);
      } else {
        const reply = data.reply || '';
        appendMessage('assistant', reply);
        history.push({ role: 'assistant', content: reply });

        // Render the design workspace if a structured design payload was returned.
        if (IS_DESIGN_ROLE && data.design) {
          dwRenderWorkspace(data.design);
        }
      }
    })
    .catch(function (err) {
      typingInd.style.display = 'none';
      showError('Network error. Please try again.');
    })
    .finally(function () {
      busy = false;
      sendBtn.disabled = false;
      chatInput.focus();
    });
  }

  sendBtn.addEventListener('click', sendMessage);

  chatInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });

  chatInput.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 160) + 'px';
    sendBtn.disabled = !this.value.trim();
  });

  sendBtn.disabled = true;
  chatInput.focus();

  // ── UI Design Agentic Tool – Design Workspace ─────────────────────────
  const IS_DESIGN_ROLE = ROLE === 'ui-design-agentic-tool';
  let designState  = null;
  let dwActiveBp   = 'web';
  let dwActiveScreen = null;
  let dwActivePanelTab = 'canvas';
  let dwActiveSideTab  = 'components';
  let dwSelectedComp   = null;

  function dwShowToast(msg, type) {
    var toast = document.getElementById('dwStatusToast');
    if (!toast) return;
    toast.textContent = msg;
    toast.className = 'dw-status-toast ' + (type || '');
    toast.style.display = 'flex';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(function () { toast.style.display = 'none'; }, 3000);
  }

  function dwSetBp(bp) {
    dwActiveBp = bp;
    document.querySelectorAll('.dw-bp').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.bp === bp);
    });
    dwRenderCanvas(dwActiveScreen);
  }

  function dwSetPanelTab(tab) {
    dwActivePanelTab = tab;
    document.querySelectorAll('#dwPanelTabs [data-panel]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.panel === tab);
    });
    var panels = {
      canvas: document.getElementById('dwPanelCanvas'),
      flow:   document.getElementById('dwPanelFlow'),
      tokens: document.getElementById('dwPanelTokens'),
    };
    Object.keys(panels).forEach(function (key) {
      if (panels[key]) panels[key].style.display = key === tab ? 'flex' : 'none';
    });
    if (tab === 'flow'   && designState) dwRenderFlow(designState);
    if (tab === 'tokens' && designState) dwRenderTokens(designState.styleTokens);
  }

  function dwSetSideTab(tab) {
    dwActiveSideTab = tab;
    document.querySelectorAll('#dwSideTabBar [data-side]').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.side === tab);
    });
    dwRenderSidePanel();
  }

  function dwBuildIframeDoc(html, css, tokens) {
    var bg = (tokens && tokens.colors && tokens.colors.background) ? tokens.colors.background : '#ffffff';
    var ff = (tokens && tokens.typography && tokens.typography.fontFamily) ? tokens.typography.fontFamily : 'system-ui, sans-serif';
    return '<!DOCTYPE html><html><head>'
      + '<meta charset="utf-8">'
      + '<meta name="viewport" content="width=device-width,initial-scale=1">'
      + '<style>'
      + '*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}'
      + 'html,body{width:100%;min-height:100%;overflow-x:hidden}'
      + 'body{font-family:' + String(ff).replace(/"/g, "'") + ';background:' + bg + ';}'
      + String(css || '')
      + '</style></head><body>'
      + String(html || '')
      + '</body></html>';
  }

  function dwRenderCanvas(screenId) {
    if (!designState || !Array.isArray(designState.screens) || !designState.screens.length) return;
    var screen = designState.screens.find(function (s) { return s.id === screenId; });
    if (!screen) screen = designState.screens[0];
    dwActiveScreen = screen.id;

    var iframe        = document.getElementById('dwCanvasIframe');
    var emptyCanvas   = document.getElementById('dwEmptyCanvas');
    var frameContainer = document.getElementById('dwFrameContainer');
    var frameLabel    = document.getElementById('dwFrameLabel');
    if (!iframe) return;

    emptyCanvas.style.display    = 'none';
    frameContainer.style.display = 'flex';

    var isMobile = dwActiveBp === 'mobile';
    iframe.className = 'dw-canvas-iframe ' + (isMobile ? 'bp-mobile' : 'bp-web');
    if (frameLabel) frameLabel.textContent = isMobile ? 'Mobile · 390px' : 'Web · 1280px';

    var html = isMobile ? (screen.mobileHtml || screen.html || '') : (screen.html || '');
    var css  = isMobile
      ? (String(screen.css || '') + '\n' + String(screen.mobileCss || ''))
      : String(screen.css || '');

    iframe.srcdoc = dwBuildIframeDoc(html, css, designState.styleTokens);

    document.querySelectorAll('.dw-screen-tab').forEach(function (tab) {
      tab.classList.toggle('active', tab.dataset.screenId === screen.id);
    });
  }

  function dwRenderScreenBar(screens) {
    var bar = document.getElementById('dwScreenBar');
    if (!bar) return;
    Array.from(bar.querySelectorAll('.dw-screen-tab')).forEach(function (t) { t.remove(); });
    screens.forEach(function (screen) {
      var btn = document.createElement('button');
      btn.type = 'button';
      btn.className = 'dw-screen-tab';
      btn.dataset.screenId = screen.id;
      btn.textContent = screen.name;
      btn.addEventListener('click', function () { dwRenderCanvas(screen.id); });
      bar.appendChild(btn);
    });
  }

  function dwRenderComponents(components) {
    var list = document.getElementById('dwCompList');
    if (!list) return;
    list.innerHTML = '';
    if (!Array.isArray(components) || !components.length) {
      list.innerHTML = '<div style="color:var(--text-muted);font-size:12px;padding:8px 2px;">No components defined.</div>';
      return;
    }
    var COMP_ICONS = {
      button: 'lucide:square', card: 'lucide:credit-card', nav: 'lucide:navigation',
      input: 'lucide:square-pen', form: 'lucide:clipboard-list', table: 'lucide:table',
      modal: 'lucide:panel-top', tab: 'lucide:layout-template', badge: 'lucide:tag',
      hero: 'lucide:layout', select: 'lucide:list', checkbox: 'lucide:check-square',
      radio: 'lucide:circle-dot', toggle: 'lucide:toggle-left', avatar: 'lucide:user-circle',
      alert: 'lucide:alert-circle', tooltip: 'lucide:info',
    };
    components.forEach(function (comp) {
      var item = document.createElement('div');
      item.className = 'dw-comp-item';
      item.dataset.compId = comp.id;

      var icon = document.createElement('iconify-icon');
      icon.setAttribute('icon', COMP_ICONS[String(comp.type || '').toLowerCase()] || 'lucide:box');

      var nameSpan = document.createElement('span');
      nameSpan.className = 'dw-comp-item-name';
      nameSpan.textContent = comp.name || comp.type || comp.id;

      var typeSpan = document.createElement('span');
      typeSpan.className = 'dw-comp-item-type';
      typeSpan.textContent = String(comp.type || '').toLowerCase().slice(0, 8);

      var copyBtn = document.createElement('button');
      copyBtn.type = 'button';
      copyBtn.className = 'dw-comp-copy-btn';
      copyBtn.title = 'Copy HTML';
      copyBtn.innerHTML = '<iconify-icon icon="lucide:copy"></iconify-icon>';
      copyBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        dwCopyToClipboard(comp.html || '', 'Component HTML copied');
      });

      item.appendChild(icon);
      item.appendChild(nameSpan);
      item.appendChild(typeSpan);
      item.appendChild(copyBtn);
      item.addEventListener('click', function () {
        dwSelectedComp = comp;
        document.querySelectorAll('.dw-comp-item').forEach(function (el) {
          el.classList.toggle('selected', el.dataset.compId === comp.id);
        });
        dwSetSideTab('props');
      });
      list.appendChild(item);
    });
  }

  function dwRenderSidePanel() {
    var content = document.getElementById('dwSideContent');
    if (!content || !designState) return;
    if (dwActiveSideTab === 'components') {
      content.innerHTML = '<div class="dw-section-label">Component Library</div><div id="dwCompList"></div>';
      dwRenderComponents(designState.components);
    } else if (dwActiveSideTab === 'props') {
      content.innerHTML = '';
      if (!dwSelectedComp) {
        content.innerHTML = '<div style="color:var(--text-muted);font-size:12px;padding:8px 2px;">Click a component to view its properties.</div>';
        return;
      }
      var comp = dwSelectedComp;
      var heading = document.createElement('div');
      heading.className = 'dw-section-label';
      heading.textContent = comp.name || comp.type;
      content.appendChild(heading);

      if (Array.isArray(comp.props) && comp.props.length) {
        comp.props.forEach(function (prop) {
          var row = document.createElement('div');
          row.className = 'dw-prop-row';
          var label = document.createElement('div');
          label.className = 'dw-prop-label';
          label.textContent = prop.name;
          var input = document.createElement('input');
          input.className = 'dw-prop-input';
          input.type = 'text';
          input.value = prop.default !== undefined ? String(prop.default) : '';
          input.placeholder = prop.name;
          row.appendChild(label);
          row.appendChild(input);
          content.appendChild(row);
        });
      }

      if (comp.html) {
        var previewRow = document.createElement('div');
        previewRow.className = 'dw-prop-row';
        var previewLabel = document.createElement('div');
        previewLabel.className = 'dw-prop-label';
        previewLabel.textContent = 'Preview';
        var previewWrap = document.createElement('div');
        previewWrap.className = 'dw-comp-preview';
        var previewFrame = document.createElement('iframe');
        previewFrame.sandbox = 'allow-scripts allow-same-origin';
        previewFrame.srcdoc = dwBuildIframeDoc(comp.html, comp.css || '', designState ? designState.styleTokens : null);
        previewWrap.appendChild(previewFrame);
        previewRow.appendChild(previewLabel);
        previewRow.appendChild(previewWrap);
        content.appendChild(previewRow);

        var copyRow = document.createElement('div');
        copyRow.className = 'dw-prop-row';
        var copyBtn = document.createElement('button');
        copyBtn.type = 'button';
        copyBtn.className = 'dw-prop-copy-btn';
        copyBtn.innerHTML = '<iconify-icon icon="lucide:copy"></iconify-icon> Copy HTML';
        copyBtn.addEventListener('click', function () {
          dwCopyToClipboard(comp.html, 'Component HTML copied');
        });
        copyRow.appendChild(copyBtn);
        content.appendChild(copyRow);
      }
    }
  }

  function dwRenderFlow(design) {
    var svg = document.getElementById('dwFlowSvg');
    if (!svg) return;
    // Remove children after <defs>
    while (svg.childNodes.length > 1) svg.removeChild(svg.lastChild);

    var screens = Array.isArray(design.screens) ? design.screens : [];
    var flow    = design.flow || {};
    var transitions = Array.isArray(flow.transitions) ? flow.transitions : [];

    if (!screens.length) {
      svg.setAttribute('height', '80');
      var emptyTxt = document.createElementNS('http://www.w3.org/2000/svg', 'text');
      emptyTxt.setAttribute('x', '20');
      emptyTxt.setAttribute('y', '44');
      emptyTxt.setAttribute('fill', '#627193');
      emptyTxt.setAttribute('font-size', '13');
      emptyTxt.textContent = 'No screens defined yet.';
      svg.appendChild(emptyTxt);
      return;
    }

    var COLS = Math.min(screens.length, 4);
    var BOX_W = 160, BOX_H = 80, PAD_X = 210, PAD_Y = 140, OX = 30, OY = 40;
    var positions = {};
    screens.forEach(function (screen, i) {
      positions[screen.id] = {
        x: OX + (i % COLS) * PAD_X,
        y: OY + Math.floor(i / COLS) * PAD_Y,
      };
    });
    var totalW = OX * 2 + Math.min(screens.length, COLS) * PAD_X;
    var totalH = OY * 2 + Math.ceil(screens.length / COLS) * PAD_Y;
    svg.setAttribute('width', String(totalW));
    svg.setAttribute('height', String(totalH));
    svg.style.minHeight = totalH + 'px';

    var MARKERS = { success:'url(#dwArrowSuccess)', error:'url(#dwArrowError)', branch:'url(#dwArrowBranch)', neutral:'url(#dwArrowhead)' };
    var COLORS  = { success:'#4ade80', error:'#f87171', branch:'#fbbf24', neutral:'#3b5a80' };

    // Edges
    transitions.forEach(function (t) {
      var from = positions[t.from];
      var to   = positions[t.to];
      if (!from || !to) return;
      var type = (t.type || 'neutral').toLowerCase();
      var color = COLORS[type] || COLORS.neutral;
      var x1 = from.x + BOX_W / 2, y1 = from.y + BOX_H;
      var x2 = to.x + BOX_W / 2,   y2 = to.y;
      var cy1 = y1 + (y2 - y1) * 0.45, cy2 = y2 - (y2 - y1) * 0.45;
      var d = 'M ' + x1 + ' ' + y1 + ' C ' + x1 + ' ' + cy1 + ', ' + x2 + ' ' + cy2 + ', ' + x2 + ' ' + y2;
      var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.setAttribute('d', d);
      path.setAttribute('class', 'flow-edge ' + type);
      path.setAttribute('stroke', color);
      path.setAttribute('marker-end', MARKERS[type] || MARKERS.neutral);
      svg.appendChild(path);
      if (t.label) {
        var lx = (x1 + x2) / 2, ly = (y1 + y2) / 2 - 4;
        var lt = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        lt.setAttribute('x', String(lx));
        lt.setAttribute('y', String(ly));
        lt.setAttribute('text-anchor', 'middle');
        lt.setAttribute('fill', '#92a3bb');
        lt.setAttribute('font-size', '10');
        lt.setAttribute('class', 'flow-edge-label');
        lt.textContent = t.label;
        svg.appendChild(lt);
      }
    });

    // Screen nodes
    screens.forEach(function (screen) {
      var pos = positions[screen.id];
      if (!pos) return;
      var isEntry = flow.entryScreen === screen.id;
      var g = document.createElementNS('http://www.w3.org/2000/svg', 'g');
      g.setAttribute('class', 'flow-screen-node' + (isEntry ? ' entry' : ''));
      g.setAttribute('transform', 'translate(' + pos.x + ',' + pos.y + ')');
      g.setAttribute('tabindex', '0');
      g.setAttribute('role', 'button');
      g.setAttribute('aria-label', 'Screen: ' + screen.name);

      var rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
      rect.setAttribute('width', String(BOX_W));
      rect.setAttribute('height', String(BOX_H));
      rect.setAttribute('rx', '8');
      rect.setAttribute('fill', '#0d1626');
      rect.setAttribute('stroke', isEntry ? 'var(--accent, #ec4899)' : '#1e2e48');
      rect.setAttribute('stroke-width', isEntry ? '2' : '1.5');
      g.appendChild(rect);

      var titleTxt = document.createElementNS('http://www.w3.org/2000/svg', 'text');
      titleTxt.setAttribute('x', String(BOX_W / 2));
      titleTxt.setAttribute('y', '26');
      titleTxt.setAttribute('text-anchor', 'middle');
      titleTxt.setAttribute('fill', '#dce7f8');
      titleTxt.setAttribute('font-size', '13');
      titleTxt.setAttribute('font-weight', '700');
      titleTxt.textContent = screen.name;
      g.appendChild(titleTxt);

      if (screen.description) {
        var desc = String(screen.description);
        var descTxt = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        descTxt.setAttribute('x', String(BOX_W / 2));
        descTxt.setAttribute('y', '44');
        descTxt.setAttribute('text-anchor', 'middle');
        descTxt.setAttribute('fill', '#627193');
        descTxt.setAttribute('font-size', '10');
        descTxt.textContent = desc.length > 30 ? desc.slice(0, 30) + '\u2026' : desc;
        g.appendChild(descTxt);
      }

      if (isEntry) {
        var entryLbl = document.createElementNS('http://www.w3.org/2000/svg', 'text');
        entryLbl.setAttribute('x', String(BOX_W / 2));
        entryLbl.setAttribute('y', String(BOX_H - 8));
        entryLbl.setAttribute('text-anchor', 'middle');
        entryLbl.setAttribute('fill', 'var(--accent, #ec4899)');
        entryLbl.setAttribute('font-size', '9');
        entryLbl.setAttribute('font-weight', '700');
        entryLbl.textContent = 'ENTRY';
        g.appendChild(entryLbl);
      }

      g.addEventListener('click', function () {
        dwSetPanelTab('canvas');
        dwRenderCanvas(screen.id);
      });
      g.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          dwSetPanelTab('canvas');
          dwRenderCanvas(screen.id);
        }
      });
      svg.appendChild(g);
    });
  }

  function dwRenderTokens(tokens) {
    var panel = document.getElementById('dwTokensPanel');
    if (!panel || !tokens) return;
    panel.innerHTML = '';

    if (tokens.colors && typeof tokens.colors === 'object') {
      var colorSec = document.createElement('div');
      colorSec.className = 'dw-tok-section';
      var colorTitle = document.createElement('div');
      colorTitle.className = 'dw-tok-title';
      colorTitle.textContent = 'Colors';
      colorSec.appendChild(colorTitle);
      var grid = document.createElement('div');
      grid.className = 'dw-color-grid';
      Object.keys(tokens.colors).forEach(function (key) {
        var val = tokens.colors[key];
        var wrap = document.createElement('div');
        wrap.className = 'dw-color-swatch-wrap';
        var swatch = document.createElement('div');
        swatch.className = 'dw-color-swatch';
        swatch.style.background = String(val);
        swatch.title = key + ': ' + val;
        swatch.addEventListener('click', function () { dwCopyToClipboard(String(val), 'Copied ' + val); });
        var nameEl = document.createElement('div');
        nameEl.className = 'dw-color-swatch-name';
        nameEl.textContent = key;
        wrap.appendChild(swatch);
        wrap.appendChild(nameEl);
        grid.appendChild(wrap);
      });
      colorSec.appendChild(grid);
      panel.appendChild(colorSec);
    }

    if (tokens.typography) {
      var typSec = document.createElement('div');
      typSec.className = 'dw-tok-section';
      var typTitle = document.createElement('div');
      typTitle.className = 'dw-tok-title';
      typTitle.textContent = 'Typography';
      typSec.appendChild(typTitle);
      var tbl = document.createElement('table');
      tbl.className = 'dw-tok-table';
      var addRow = function (k, v) {
        var tr = document.createElement('tr');
        var td1 = document.createElement('td');
        td1.className = 'dw-tok-key';
        td1.textContent = k;
        var td2 = document.createElement('td');
        td2.className = 'dw-tok-val';
        td2.textContent = String(v);
        tr.appendChild(td1);
        tr.appendChild(td2);
        tbl.appendChild(tr);
      };
      if (tokens.typography.fontFamily) addRow('Font Family', tokens.typography.fontFamily);
      if (tokens.typography.scale && typeof tokens.typography.scale === 'object') {
        Object.keys(tokens.typography.scale).forEach(function (k) {
          addRow('Scale / ' + k, tokens.typography.scale[k]);
        });
      }
      typSec.appendChild(tbl);
      panel.appendChild(typSec);
    }

    var extraSections = [
      { key: 'spacing',   title: 'Spacing' },
      { key: 'radius',    title: 'Border Radius' },
      { key: 'elevation', title: 'Elevation' },
    ];
    extraSections.forEach(function (s) {
      var obj = tokens[s.key];
      if (!obj || typeof obj !== 'object') return;
      var sec = document.createElement('div');
      sec.className = 'dw-tok-section';
      var secTitle = document.createElement('div');
      secTitle.className = 'dw-tok-title';
      secTitle.textContent = s.title;
      sec.appendChild(secTitle);
      var t2 = document.createElement('table');
      t2.className = 'dw-tok-table';
      Object.keys(obj).forEach(function (k) {
        var tr = document.createElement('tr');
        var td1 = document.createElement('td');
        td1.className = 'dw-tok-key';
        td1.textContent = k;
        var td2 = document.createElement('td');
        td2.className = 'dw-tok-val';
        td2.textContent = String(obj[k]);
        tr.appendChild(td1);
        tr.appendChild(td2);
        t2.appendChild(tr);
      });
      sec.appendChild(t2);
      panel.appendChild(sec);
    });
  }

  function dwApplyPatch(patchList) {
    if (!designState || !Array.isArray(patchList)) return;
    patchList.forEach(function (patch) {
      var idx = designState.screens.findIndex(function (s) { return s.id === patch.screenId; });
      if (idx === -1) return;
      if (patch.html        !== undefined) designState.screens[idx].html        = patch.html;
      if (patch.css         !== undefined) designState.screens[idx].css         = patch.css;
      if (patch.mobileHtml  !== undefined) designState.screens[idx].mobileHtml  = patch.mobileHtml;
      if (patch.mobileCss   !== undefined) designState.screens[idx].mobileCss   = patch.mobileCss;
      if (patch.name        !== undefined) designState.screens[idx].name        = patch.name;
      if (patch.description !== undefined) designState.screens[idx].description = patch.description;
    });
  }

  function dwRenderWorkspace(design) {
    var workspace = document.getElementById('designWorkspace');
    if (!workspace) return;

    var isCreate = !designState || design.op === 'create';

    if (isCreate) {
      designState = {
        schema:      'cf-ui-design/1',
        op:          'create',
        project:     design.project     || {},
        styleTokens: design.styleTokens || {},
        components:  design.components  || [],
        screens:     design.screens     || [],
        flow:        design.flow        || { transitions: [] },
        patches:     [],
      };
      // Auto-switch to workspace on first design
      dwSwitchView('workspace');
    } else {
      // Patch mode: merge updates into existing state
      if (design.project)     designState.project     = design.project;
      if (design.styleTokens) designState.styleTokens = design.styleTokens;
      if (design.components)  designState.components  = design.components;
      if (design.flow)        designState.flow        = design.flow;
      if (Array.isArray(design.patches) && design.patches.length) {
        dwApplyPatch(design.patches);
      }
      // Merge new screens into existing state: add new ones, merge fields of existing ones
      // to preserve any fields not provided by the patch.
      if (Array.isArray(design.screens)) {
        design.screens.forEach(function (ns) {
          var idx = designState.screens.findIndex(function (s) { return s.id === ns.id; });
          if (idx === -1) {
            designState.screens.push(ns);
          } else {
            // Merge: copy all provided fields without dropping existing ones
            Object.keys(ns).forEach(function (k) {
              designState.screens[idx][k] = ns[k];
            });
          }
        });
      }
    }

    workspace.style.display = 'flex';

    // Update iterate badge
    var badge     = document.getElementById('dwIterateBadge');
    var badgeName = document.getElementById('dwIterateName');
    if (badge && badgeName) {
      var projectName = (designState.project && designState.project.name) ? designState.project.name : '';
      if (projectName) {
        badgeName.textContent  = projectName;
        badge.style.display    = 'inline-flex';
      }
    }

    dwRenderScreenBar(designState.screens);
    var firstScreen = designState.screens.length ? designState.screens[0].id : null;
    if (firstScreen) {
      dwActiveScreen = firstScreen;
      dwRenderCanvas(firstScreen);
    }
    dwRenderSidePanel();
    if (dwActivePanelTab === 'flow')   dwRenderFlow(designState);
    if (dwActivePanelTab === 'tokens') dwRenderTokens(designState.styleTokens);
  }

  function dwSwitchView(view) {
    var chatWindow  = document.querySelector('.chat-window');
    var workspace   = document.getElementById('designWorkspace');
    var viewChatBtn = document.getElementById('dwViewChat');
    var viewWsBtn   = document.getElementById('dwViewWorkspace');
    if (view === 'workspace') {
      if (chatWindow)  chatWindow.style.display  = 'none';
      if (workspace)   workspace.style.display   = 'flex';
      if (viewChatBtn) viewChatBtn.classList.remove('active');
      if (viewWsBtn)   viewWsBtn.classList.add('active');
    } else {
      if (chatWindow)  chatWindow.style.display  = 'flex';
      if (workspace)   workspace.style.display   = 'none';
      if (viewChatBtn) viewChatBtn.classList.add('active');
      if (viewWsBtn)   viewWsBtn.classList.remove('active');
    }
  }

  function dwCopyToClipboard(text, successMsg) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text)
        .then(function () { dwShowToast(successMsg || 'Copied!', 'success'); })
        .catch(function () { dwShowToast('Copy failed.', 'error'); });
    } else {
      var ta = document.createElement('textarea');
      ta.value = text;
      ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0;';
      document.body.appendChild(ta);
      ta.select();
      try {
        document.execCommand('copy');
        dwShowToast(successMsg || 'Copied!', 'success');
      } catch (e) {
        dwShowToast('Copy not supported in this browser.', 'error');
      }
      document.body.removeChild(ta);
    }
  }

  function dwInitViewTabs() {
    var viewChatBtn = document.getElementById('dwViewChat');
    var viewWsBtn   = document.getElementById('dwViewWorkspace');
    if (viewChatBtn) viewChatBtn.addEventListener('click', function () { dwSwitchView('chat'); });
    if (viewWsBtn)   viewWsBtn.addEventListener('click',   function () { dwSwitchView('workspace'); });
  }

  function dwInitToolbar() {
    // Panel tabs (Canvas / Flow / Tokens)
    var panelTabGroup = document.getElementById('dwPanelTabs');
    if (panelTabGroup) {
      panelTabGroup.querySelectorAll('[data-panel]').forEach(function (btn) {
        btn.addEventListener('click', function () { dwSetPanelTab(btn.dataset.panel); });
      });
    }

    // Breakpoint switcher
    var bpWeb    = document.getElementById('dwBpWeb');
    var bpMobile = document.getElementById('dwBpMobile');
    if (bpWeb)    bpWeb.addEventListener('click',    function () { dwSetBp('web'); });
    if (bpMobile) bpMobile.addEventListener('click', function () { dwSetBp('mobile'); });

    // Side panel tabs (Components / Properties)
    var sideTabBar = document.getElementById('dwSideTabBar');
    if (sideTabBar) {
      sideTabBar.querySelectorAll('[data-side]').forEach(function (btn) {
        btn.addEventListener('click', function () { dwSetSideTab(btn.dataset.side); });
      });
    }

    // Export button
    var exportBtn = document.getElementById('dwExportBtn');
    if (exportBtn) {
      exportBtn.addEventListener('click', function () {
        if (!designState) { dwShowToast('No design to export yet.', ''); return; }
        var json = JSON.stringify(designState, null, 2);
        var blob = new Blob([json], { type: 'application/json' });
        var url  = URL.createObjectURL(blob);
        var a    = document.createElement('a');
        var safeName = (designState.project && designState.project.name)
          ? String(designState.project.name).toLowerCase().replace(/[^a-z0-9]+/g, '-')
          : 'design';
        a.href     = url;
        a.download = safeName + '.fig.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        dwShowToast('Exported ' + a.download, 'success');
      });
    }

    // Import file input
    var importInput = document.getElementById('dwImportInput');
    if (importInput) {
      importInput.addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (evt) {
          try {
            var parsed = JSON.parse(evt.target.result);
            if (!parsed || typeof parsed !== 'object' || parsed.schema !== 'cf-ui-design/1') {
              dwShowToast('Invalid design file: expected cf-ui-design/1 schema.', 'error');
              return;
            }
            designState = null;
            dwRenderWorkspace(parsed);
            dwSwitchView('workspace');
            dwShowToast('Design imported successfully.', 'success');
          } catch (err) {
            dwShowToast('Import failed: ' + String(err.message), 'error');
          }
        };
        reader.readAsText(file);
        importInput.value = '';
      });
    }
  }

  if (IS_DESIGN_ROLE) {
    dwInitViewTabs();
    dwInitToolbar();
  }

}());
PAGEJS;

require_once dirname(__DIR__) . '/includes/footer.php';
