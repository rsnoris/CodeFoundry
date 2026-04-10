<?php
/**
 * CodeFoundry – User Support Chat
 *
 * Allows logged-in users to start and participate in real-time support
 * chat conversations with the support team.
 */
declare(strict_types=1);
require_once dirname(dirname(__DIR__)) . '/config.php';
require_once dirname(dirname(__DIR__)) . '/lib/UserStore.php';
require_once dirname(dirname(__DIR__)) . '/lib/ChatStore.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';

cf_require_login();

$user_session = cf_current_user();
$username     = $user_session['username'];
$unread_chat  = ChatStore::totalUnreadForUser($username);

$dash_active = 'chat';
$page_title  = 'Support Chat – CodeFoundry';
$active_page = '';
$page_styles = <<<'CSS'
  .dash-layout {
    display: flex;
    min-height: calc(100vh - var(--header-height));
    max-width: var(--maxwidth);
    margin: 0 auto;
    padding: 0 20px;
    gap: 0;
  }
  .dash-sidebar {
    width: 240px;
    flex-shrink: 0;
    padding: 32px 0 32px;
    border-right: 1px solid var(--border-color);
  }
  .dash-sidebar-title {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--text-subtle);
    padding: 0 20px 12px;
  }
  .dash-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    margin: 1px 8px;
    transition: background .15s, color .15s;
    text-decoration: none;
  }
  .dash-nav-item:hover { background: var(--navy-3); color: var(--text); }
  .dash-nav-item.active { background: rgba(24,179,255,.12); color: var(--primary); }
  .dash-nav-item iconify-icon { font-size: 17px; flex-shrink: 0; }
  .nav-badge {
    margin-left: auto;
    background: var(--primary);
    color: var(--navy);
    font-size: 10px;
    font-weight: 800;
    border-radius: 100px;
    padding: 1px 6px;
    min-width: 18px;
    text-align: center;
    line-height: 16px;
  }
  .dash-main {
    flex: 1;
    padding: 36px 36px 60px;
    min-width: 0;
  }
  .chat-layout {
    display: flex;
    gap: 0;
    height: calc(100vh - var(--header-height) - 120px);
    min-height: 500px;
    background: var(--navy);
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    overflow: hidden;
  }
  .chat-sessions-panel {
    width: 280px;
    flex-shrink: 0;
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
  }
  .chat-sessions-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-color);
    flex-shrink: 0;
  }
  .chat-sessions-header h3 {
    font-size: 13px;
    font-weight: 700;
    margin: 0;
    color: var(--text);
  }
  .chat-sessions-list { flex: 1; overflow-y: auto; }
  .chat-session-item {
    display: flex;
    flex-direction: column;
    gap: 3px;
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid rgba(26,41,66,.5);
    transition: background .15s;
  }
  .chat-session-item:hover { background: var(--navy-3); }
  .chat-session-item.active { background: rgba(24,179,255,.08); border-left: 2px solid var(--primary); }
  .chat-session-subject {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .chat-session-meta {
    font-size: 11px;
    color: var(--text-subtle);
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .chat-session-unread {
    background: var(--primary);
    color: var(--navy);
    font-size: 10px;
    font-weight: 800;
    border-radius: 100px;
    padding: 1px 6px;
    min-width: 18px;
    text-align: center;
  }
  .chat-main-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
  }
  .chat-header {
    padding: 14px 18px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
  }
  .chat-header-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    margin: 0;
  }
  .chat-header-sub {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 2px;
  }
  .chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }
  .chat-bubble-wrap {
    display: flex;
    flex-direction: column;
    max-width: 75%;
  }
  .chat-bubble-wrap.from-me { align-self: flex-end; align-items: flex-end; }
  .chat-bubble-wrap.from-them { align-self: flex-start; align-items: flex-start; }
  .chat-bubble {
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 14px;
    line-height: 1.5;
    word-break: break-word;
  }
  .chat-bubble.from-me {
    background: var(--primary);
    color: var(--navy);
    border-bottom-right-radius: 4px;
  }
  .chat-bubble.from-them {
    background: var(--navy-3);
    color: var(--text);
    border: 1px solid var(--border-color);
    border-bottom-left-radius: 4px;
  }
  .chat-bubble-meta {
    font-size: 10px;
    color: var(--text-subtle);
    margin-top: 3px;
    padding: 0 4px;
  }
  .chat-input-area {
    padding: 14px 18px;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 10px;
    align-items: flex-end;
    flex-shrink: 0;
  }
  .chat-input {
    flex: 1;
    background: var(--navy-3);
    border: 1px solid var(--border-color);
    border-radius: 10px;
    padding: 10px 14px;
    color: var(--text);
    font-size: 14px;
    resize: none;
    outline: none;
    min-height: 42px;
    max-height: 120px;
    font-family: inherit;
    line-height: 1.4;
    transition: border-color .15s;
  }
  .chat-input:focus { border-color: var(--primary); }
  .chat-send-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 18px;
    background: var(--primary);
    color: var(--navy);
    font-weight: 700;
    font-size: 14px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: background .2s;
    white-space: nowrap;
    flex-shrink: 0;
  }
  .chat-send-btn:hover { background: var(--primary-hover); }
  .chat-send-btn:disabled { opacity: .5; cursor: not-allowed; }
  .chat-empty-state {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-subtle);
    gap: 10px;
  }
  .chat-empty-state iconify-icon { font-size: 36px; }
  .chat-empty-state p { margin: 0; font-size: 14px; }
  .badge-status-open { color: #fbbf24; }
  .badge-status-closed { color: var(--text-subtle); }
  .btn-new-chat {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: var(--primary);
    color: var(--navy);
    font-weight: 700;
    font-size: 12px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background .2s;
  }
  .btn-new-chat:hover { background: var(--primary-hover); }
  .btn-close-chat {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 10px;
    background: transparent;
    color: var(--text-muted);
    font-size: 12px;
    font-weight: 600;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    cursor: pointer;
    transition: border-color .15s, color .15s;
  }
  .btn-close-chat:hover { border-color: #f87171; color: #f87171; }
  .modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    z-index: 200;
    align-items: center;
    justify-content: center;
  }
  .modal-overlay.open { display: flex; }
  .modal-box {
    background: var(--navy);
    border: 1px solid var(--border-color);
    border-radius: var(--card-radius);
    padding: 28px 32px;
    width: 100%;
    max-width: 440px;
    box-shadow: 0 8px 40px rgba(0,0,0,.5);
  }
  .modal-box h3 { font-size: 18px; font-weight: 800; margin: 0 0 6px; }
  .modal-box p { font-size: 13px; color: var(--text-muted); margin: 0 0 20px; }
  .form-group { margin-bottom: 16px; }
  .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .06em; }
  .form-input {
    width: 100%;
    background: var(--navy-3);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 10px 14px;
    color: var(--text);
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
    transition: border-color .15s;
  }
  .form-input:focus { border-color: var(--primary); }
  .modal-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 8px; }
  .btn-cancel {
    padding: 9px 18px;
    background: transparent;
    color: var(--text-muted);
    font-size: 14px;
    font-weight: 600;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    cursor: pointer;
  }
  .btn-cancel:hover { border-color: var(--primary); color: var(--primary); }
  .btn-submit {
    padding: 9px 18px;
    background: var(--primary);
    color: var(--navy);
    font-size: 14px;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background .2s;
  }
  .btn-submit:hover { background: var(--primary-hover); }
  @media (max-width: 900px) {
    .chat-sessions-panel { width: 220px; }
  }
  @media (max-width: 700px) {
    .dash-layout { flex-direction: column; padding: 0; }
    .dash-sidebar { width: 100%; border-right: none; border-bottom: 1px solid var(--border-color); padding: 16px 0; display: flex; overflow-x: auto; }
    .dash-sidebar-title { display: none; }
    .dash-main { padding: 16px 12px 40px; }
    .chat-layout { flex-direction: column; height: auto; min-height: 600px; }
    .chat-sessions-panel { width: 100%; height: 180px; border-right: none; border-bottom: 1px solid var(--border-color); }
    .chat-main-panel { height: 420px; }
  }
CSS;

require_once dirname(dirname(__DIR__)) . '/includes/header.php';
?>

<!-- New Chat Modal -->
<div class="modal-overlay" id="newChatModal">
  <div class="modal-box">
    <h3>Start a Support Chat</h3>
    <p>Describe your issue briefly and a support team member will respond shortly.</p>
    <div class="form-group">
      <label class="form-label" for="newChatSubject">Subject</label>
      <input type="text" id="newChatSubject" class="form-input"
             placeholder="e.g. IDE not loading my project"
             maxlength="200" autocomplete="off">
    </div>
    <div class="modal-actions">
      <button class="btn-cancel" onclick="closeNewChatModal()">Cancel</button>
      <button class="btn-submit" id="newChatSubmitBtn" onclick="submitNewChat()">
        <iconify-icon icon="lucide:message-circle" style="vertical-align:middle;margin-right:4px"></iconify-icon>
        Start Chat
      </button>
    </div>
  </div>
</div>

<div class="dash-layout">
  <!-- Sidebar -->
  <aside class="dash-sidebar">
    <div class="dash-sidebar-title">Navigation</div>
    <a href="/Dashboard/" class="dash-nav-item">
      <iconify-icon icon="lucide:layout-dashboard"></iconify-icon> Dashboard
    </a>
    <a href="/Dashboard/resources/" class="dash-nav-item">
      <iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Resources
    </a>
    <a href="/Dashboard/history/" class="dash-nav-item">
      <iconify-icon icon="lucide:history"></iconify-icon> History
    </a>
    <a href="/Dashboard/account/" class="dash-nav-item">
      <iconify-icon icon="lucide:user-cog"></iconify-icon> Account
    </a>
    <a href="/Dashboard/payments/" class="dash-nav-item">
      <iconify-icon icon="lucide:credit-card"></iconify-icon> Payments
    </a>
    <a href="/Dashboard/chat/" class="dash-nav-item active" id="sidebarChatLink">
      <iconify-icon icon="lucide:message-circle"></iconify-icon>
      Support Chat
      <?php if ($unread_chat > 0): ?>
        <span class="nav-badge" id="sidebarBadge"><?= (int)$unread_chat ?></span>
      <?php else: ?>
        <span class="nav-badge" id="sidebarBadge" style="display:none">0</span>
      <?php endif; ?>
    </a>
  </aside>

  <!-- Main content -->
  <main class="dash-main">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
      <div>
        <h1 style="font-size:22px;font-weight:800;margin:0 0 4px">Support Chat</h1>
        <p style="color:var(--text-muted);margin:0;font-size:13px">Chat live with our support team. We typically respond within a few minutes.</p>
      </div>
      <button class="btn-new-chat" onclick="openNewChatModal()">
        <iconify-icon icon="lucide:plus"></iconify-icon> New Chat
      </button>
    </div>

    <div class="chat-layout">
      <!-- Sessions list -->
      <div class="chat-sessions-panel">
        <div class="chat-sessions-header">
          <h3>Conversations</h3>
        </div>
        <div class="chat-sessions-list" id="sessionsList">
          <div style="padding:20px;text-align:center;color:var(--text-subtle);font-size:12px">Loading…</div>
        </div>
      </div>

      <!-- Messages area -->
      <div class="chat-main-panel" id="chatMainPanel">
        <div class="chat-empty-state" id="chatEmptyState">
          <iconify-icon icon="lucide:message-circle"></iconify-icon>
          <p>Select a conversation or start a new chat.</p>
        </div>
      </div>
    </div>
  </main>
</div>

<?php
$page_scripts = <<<'JS'
(function () {
  var currentSessionId = null;
  var lastMessageId    = '';
  var pollTimer        = null;
  var sessions         = [];
  var POLL_INTERVAL    = 4000; // ms

  // ── Helpers ───────────────────────────────────────────────────────────────

  function api(action, extra, cb) {
    fetch('/Dashboard/chat/api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(Object.assign({ action: action }, extra))
    })
    .then(function (r) { return r.json(); })
    .then(cb)
    .catch(function () {});
  }

  function fmtTime(iso) {
    if (!iso) return '';
    var d = new Date(iso);
    var h = d.getHours(), m = d.getMinutes();
    var ampm = h >= 12 ? 'pm' : 'am';
    h = h % 12 || 12;
    return h + ':' + (m < 10 ? '0' : '') + m + ' ' + ampm;
  }

  function fmtDate(iso) {
    if (!iso) return '';
    var d = new Date(iso);
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    return months[d.getMonth()] + ' ' + d.getDate();
  }

  function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  // ── Session list ──────────────────────────────────────────────────────────

  function loadSessions() {
    api('sessions', {}, function (data) {
      sessions = data.sessions || [];
      renderSessionsList();
      updateSidebarBadge(data.unread_total || 0);
    });
  }

  function renderSessionsList() {
    var el = document.getElementById('sessionsList');
    if (!sessions.length) {
      el.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-subtle);font-size:12px">No conversations yet.<br>Click <b>New Chat</b> to start.</div>';
      return;
    }
    var html = '';
    sessions.forEach(function (s) {
      var active = s.id === currentSessionId ? ' active' : '';
      var statusIcon = s.status === 'closed'
        ? '<span class="badge-status-closed">Closed</span>'
        : '<span class="badge-status-open">Open</span>';
      var unreadBadge = (s.unread_user > 0)
        ? '<span class="chat-session-unread">' + s.unread_user + '</span>'
        : '';
      html += '<div class="chat-session-item' + active + '" onclick="selectSession(\'' + escHtml(s.id) + '\')">'
            + '<div class="chat-session-subject">' + escHtml(s.subject) + '</div>'
            + '<div class="chat-session-meta">'
            + '<span>' + statusIcon + ' · ' + fmtDate(s.updated_at || s.created_at) + '</span>'
            + unreadBadge
            + '</div>'
            + '</div>';
    });
    el.innerHTML = html;
  }

  function updateSidebarBadge(count) {
    var badge = document.getElementById('sidebarBadge');
    if (!badge) return;
    if (count > 0) {
      badge.textContent = count;
      badge.style.display = '';
    } else {
      badge.style.display = 'none';
    }
  }

  // ── Select / open a session ───────────────────────────────────────────────

  window.selectSession = function (sessionId) {
    currentSessionId = sessionId;
    lastMessageId    = '';
    clearInterval(pollTimer);

    // Update active class
    var items = document.querySelectorAll('.chat-session-item');
    items.forEach(function (item) {
      item.classList.remove('active');
    });
    var sel = document.querySelector('.chat-session-item[onclick*="' + sessionId + '"]');
    if (sel) sel.classList.add('active');

    // Find session meta
    var session = null;
    for (var i = 0; i < sessions.length; i++) {
      if (sessions[i].id === sessionId) { session = sessions[i]; break; }
    }

    renderChatPanel(session);
    pollMessages();
    pollTimer = setInterval(pollMessages, POLL_INTERVAL);
  };

  function renderChatPanel(session) {
    var panel = document.getElementById('chatMainPanel');
    var isClosed = session && session.status === 'closed';
    var headerHtml = session
      ? '<div class="chat-header">'
        + '<div><div class="chat-header-title">' + escHtml(session.subject) + '</div>'
        + '<div class="chat-header-sub">Session started ' + fmtDate(session.created_at) + ' · '
        + (isClosed ? '<span class="badge-status-closed">Closed</span>' : '<span class="badge-status-open">Open</span>')
        + '</div></div>'
        + (!isClosed ? '<button class="btn-close-chat" onclick="closeSession()"><iconify-icon icon="lucide:x-circle" style="vertical-align:middle"></iconify-icon> Close chat</button>' : '')
        + '</div>'
      : '';

    var inputHtml = !isClosed
      ? '<div class="chat-input-area">'
        + '<textarea id="chatInput" class="chat-input" rows="1" placeholder="Type a message…" maxlength="4000" onkeydown="handleChatKey(event)" oninput="autoResize(this)"></textarea>'
        + '<button class="chat-send-btn" id="chatSendBtn" onclick="sendMessage()">'
        + '<iconify-icon icon="lucide:send"></iconify-icon></button>'
        + '</div>'
      : '<div style="padding:12px 18px;text-align:center;font-size:12px;color:var(--text-subtle);border-top:1px solid var(--border-color)">This conversation is closed.</div>';

    panel.innerHTML = headerHtml
      + '<div class="chat-messages" id="chatMessages"><div style="text-align:center;color:var(--text-subtle);font-size:12px;padding:20px">Loading…</div></div>'
      + inputHtml;
  }

  // ── Poll for new messages ─────────────────────────────────────────────────

  function pollMessages() {
    if (!currentSessionId) return;
    api('poll', { session_id: currentSessionId, after_id: lastMessageId }, function (data) {
      if (!data || data.error) return;

      var msgs = data.messages || [];
      if (msgs.length > 0) {
        appendMessages(msgs);
        lastMessageId = msgs[msgs.length - 1].id;
      }

      // Refresh session meta (status, unread)
      if (data.session) {
        for (var i = 0; i < sessions.length; i++) {
          if (sessions[i].id === data.session.id) {
            sessions[i] = data.session;
            break;
          }
        }
        renderSessionsList();
        updateSidebarBadge(0); // We just read them
      }
    });
  }

  function appendMessages(msgs) {
    var box = document.getElementById('chatMessages');
    if (!box) return;

    // On first load (no lastMessageId), replace loading indicator
    if (lastMessageId === '' && msgs.length > 0) {
      box.innerHTML = '';
    } else if (lastMessageId === '' && msgs.length === 0) {
      box.innerHTML = '<div style="text-align:center;color:var(--text-subtle);font-size:12px;padding:20px">No messages yet. Say hello!</div>';
      return;
    }

    msgs.forEach(function (m) {
      var isMe = m.sender_role === 'user';
      var wrap = document.createElement('div');
      wrap.className = 'chat-bubble-wrap ' + (isMe ? 'from-me' : 'from-them');
      wrap.innerHTML = '<div class="chat-bubble ' + (isMe ? 'from-me' : 'from-them') + '">' + escHtml(m.message).replace(/\n/g,'<br>') + '</div>'
        + '<div class="chat-bubble-meta">' + (isMe ? 'You' : '<iconify-icon icon="lucide:headset" style="vertical-align:middle;font-size:11px"></iconify-icon> Support') + ' · ' + fmtTime(m.created_at) + '</div>';
      box.appendChild(wrap);
    });

    box.scrollTop = box.scrollHeight;
  }

  // ── Send message ──────────────────────────────────────────────────────────

  window.sendMessage = function () {
    var input = document.getElementById('chatInput');
    if (!input) return;
    var text = input.value.trim();
    if (!text || !currentSessionId) return;

    var btn = document.getElementById('chatSendBtn');
    if (btn) btn.disabled = true;
    input.disabled = true;

    api('send', { session_id: currentSessionId, message: text }, function (data) {
      if (btn) btn.disabled = false;
      if (input) { input.disabled = false; input.style.height = ''; }
      if (!data || data.error) {
        alert(data ? data.error : 'Failed to send message.');
        return;
      }
      input.value = '';
      // Poll immediately for new messages (keeps lastMessageId so only new ones are fetched)
      pollMessages();
    });
  };

  window.handleChatKey = function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      window.sendMessage();
    }
  };

  window.autoResize = function (el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
  };

  // ── Close session ─────────────────────────────────────────────────────────

  window.closeSession = function () {
    if (!currentSessionId) return;
    if (!confirm('Are you sure you want to close this chat? You can still view the history.')) return;
    api('close_session', { session_id: currentSessionId }, function (data) {
      if (!data || data.error) { alert('Failed to close session.'); return; }
      clearInterval(pollTimer);
      currentSessionId = null;
      loadSessions();
      // Show empty state
      document.getElementById('chatMainPanel').innerHTML =
        '<div class="chat-empty-state"><iconify-icon icon="lucide:check-circle" style="color:#4ade80"></iconify-icon><p>Chat closed. Select another conversation or start a new one.</p></div>';
    });
  };

  // ── New chat modal ────────────────────────────────────────────────────────

  window.openNewChatModal = function () {
    document.getElementById('newChatModal').classList.add('open');
    document.getElementById('newChatSubject').value = '';
    document.getElementById('newChatSubject').focus();
  };

  window.closeNewChatModal = function () {
    document.getElementById('newChatModal').classList.remove('open');
  };

  window.submitNewChat = function () {
    var subject = document.getElementById('newChatSubject').value.trim();
    if (!subject) { document.getElementById('newChatSubject').focus(); return; }

    var btn = document.getElementById('newChatSubmitBtn');
    btn.disabled = true;

    api('new_session', { subject: subject }, function (data) {
      btn.disabled = false;
      if (!data || data.error) { alert(data ? data.error : 'Failed to start chat.'); return; }
      window.closeNewChatModal();
      loadSessions();
      // Auto-select the new session after a short delay
      setTimeout(function () {
        window.selectSession(data.session_id);
        loadSessions();
      }, 300);
    });
  };

  // Allow Enter key in subject input to submit
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && document.getElementById('newChatModal').classList.contains('open')) {
      window.submitNewChat();
    }
    if (e.key === 'Escape') {
      window.closeNewChatModal();
    }
  });

  // ── Background session polling (for unread badge) ─────────────────────────

  function backgroundPoll() {
    // Skip if a session is actively being polled (avoids redundant requests)
    if (currentSessionId) return;
    api('sessions', {}, function (data) {
      sessions = data.sessions || [];
      renderSessionsList();
      updateSidebarBadge(data.unread_total || 0);
    });
  }

  // ── Boot ──────────────────────────────────────────────────────────────────

  loadSessions();
  setInterval(backgroundPoll, 10000);

}());
JS;

require_once dirname(dirname(__DIR__)) . '/includes/footer.php';
?>
