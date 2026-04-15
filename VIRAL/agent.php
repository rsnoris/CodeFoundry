<?php
/**
 * CodeFoundry VIRAL – Agent Chat Interface
 *
 * URL: /VIRAL/agent.php?role=<slug>
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
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

$page_title  = $agentLabel . ' Agent – CodeFoundry VIRAL';
$active_page = 'viral';
$page_styles = <<<PAGECSS
  :root { --accent: {$agent['accent']}; }
  .viral-chat-wrap {
    max-width: 860px;
    margin: 0 auto;
    padding: 40px 24px 100px;
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .agent-header {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  .agent-icon-lg {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: var(--accent)22;
    border: 1.5px solid var(--accent)55;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }
  .agent-icon-lg iconify-icon {
    font-size: 26px;
    color: var(--accent);
  }
  .agent-header-info h1 {
    margin: 0 0 4px;
    font-size: 22px;
    font-weight: 800;
  }
  .agent-header-info p {
    margin: 0;
    color: var(--text-muted);
    font-size: 14px;
  }
  .back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--text-muted);
    font-size: 13px;
    margin-bottom: 8px;
    transition: color .2s;
  }
  .back-link:hover { color: var(--accent); }
  /* Chat window */
  .chat-window {
    background: #0d1626;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }
  .chat-messages {
    flex: 1;
    min-height: 420px;
    max-height: 560px;
    overflow-y: auto;
    padding: 24px 20px;
    display: flex;
    flex-direction: column;
    gap: 16px;
    scroll-behavior: smooth;
  }
  .chat-messages::-webkit-scrollbar { width: 5px; }
  .chat-messages::-webkit-scrollbar-thumb { background: #1e2e48; border-radius: 4px; }
  /* Message bubbles */
  .msg {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    max-width: 88%;
    animation: msgIn .25s ease;
  }
  @keyframes msgIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }
  .msg.user { align-self: flex-end; flex-direction: row-reverse; }
  .msg-avatar {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 16px;
  }
  .msg.assistant .msg-avatar { background: var(--accent)22; color: var(--accent); border: 1px solid var(--accent)44; }
  .msg.user .msg-avatar      { background: #1a2942; color: var(--text-muted); }
  .msg-bubble {
    padding: 12px 16px;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.7;
    white-space: pre-wrap;
    word-break: break-word;
  }
  .msg.assistant .msg-bubble {
    background: #111b30;
    border: 1px solid var(--border-color);
    color: var(--text);
  }
  .msg.user .msg-bubble {
    background: var(--accent)18;
    border: 1px solid var(--accent)44;
    color: var(--text);
  }
  /* Typing indicator */
  .typing-indicator { display: none; }
  .typing-indicator .dot {
    display: inline-block;
    width: 7px; height: 7px;
    border-radius: 50%;
    background: var(--accent);
    opacity: .5;
    margin: 0 2px;
    animation: blink 1.2s infinite;
  }
  .typing-indicator .dot:nth-child(2) { animation-delay: .2s; }
  .typing-indicator .dot:nth-child(3) { animation-delay: .4s; }
  @keyframes blink { 0%,80%,100%{opacity:.25} 40%{opacity:1} }
  /* Input bar */
  .chat-input-bar {
    padding: 16px 20px;
    border-top: 1px solid var(--border-color);
    display: flex;
    gap: 10px;
    align-items: flex-end;
    background: #0d1626;
  }
  .chat-input-bar textarea {
    flex: 1;
    background: #0e1828;
    border: 1px solid #1e2e48;
    color: var(--text);
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    font-family: inherit;
    resize: none;
    outline: none;
    line-height: 1.6;
    min-height: 44px;
    max-height: 160px;
    overflow-y: auto;
    transition: border-color .2s;
  }
  .chat-input-bar textarea:focus { border-color: var(--accent); }
  .chat-send-btn {
    background: var(--accent);
    color: #0a1428;
    border: none;
    border-radius: 10px;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background .2s, opacity .2s;
    font-size: 18px;
  }
  .chat-send-btn:hover:not(:disabled) { background: #009de0; }
  .chat-send-btn:disabled { opacity: .45; cursor: not-allowed; }
  .chat-send-btn iconify-icon { font-size: 18px; }
  .error-toast {
    display: none;
    background: #ff4d4d22;
    border: 1px solid #ff4d4d55;
    color: #ff7676;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
  }
  /* Welcome message */
  .welcome-hint {
    text-align: center;
    padding: 48px 20px;
    color: var(--text-muted);
  }
  .welcome-hint iconify-icon { font-size: 42px; color: var(--accent); opacity: .6; margin-bottom: 12px; display:block; }
  .welcome-hint h2 { margin: 0 0 8px; font-size: 18px; color: var(--text); }
  .welcome-hint p { margin: 0; font-size: 14px; line-height: 1.6; }
  /* Suggestion chips */
  .suggestion-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 0 20px 16px;
  }
  .chip {
    background: #0e1828;
    border: 1px solid #1e2e48;
    color: var(--text-muted);
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 12px;
    cursor: pointer;
    transition: border-color .2s, color .2s;
  }
  .chip:hover { border-color: var(--accent); color: var(--accent); }
  @media (max-width: 600px) {
    .viral-chat-wrap { padding: 20px 14px 80px; }
    .chat-messages { min-height: 340px; }
    .msg { max-width: 96%; }
  }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main>
  <div class="viral-chat-wrap">

    <div>
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

    <div id="errorToast" class="error-toast"></div>

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

  </div>
</main>

<?php
$page_scripts = <<<PAGEJS
(function () {
  const ROLE       = {$roleJson};
  const AGENT_ICON = {$agentIconJson};

  // Suggestion prompts keyed by role
  const SUGGESTIONS = {
    'software-engineer':    ['Review this code snippet', 'How do I design a REST API?', 'Explain SOLID principles', 'Best practices for async JavaScript'],
    'product-manager':      ['Write a user story for login', 'How do I prioritize a backlog?', 'Draft a product requirements doc', 'Define success metrics for a feature'],
    'data-scientist':       ['How do I handle missing values?', 'Explain gradient boosting', 'Write a Python EDA script', 'Compare classification models'],
    'marketing-manager':    ['Write a product launch email', 'Create a 30-day content plan', 'What is the best funnel strategy?', 'How to A/B test ad copy?'],
    'sales-agent':          ['Write a cold outreach email', 'How to handle price objections?', 'Create a follow-up sequence', 'Draft a sales proposal intro'],
    'customer-support':     ['Reply to an angry customer', 'How to de-escalate a complaint?', 'Write an apology email', 'Handle a refund request politely'],
    'hr-manager':           ['Write a job description for a developer', 'Draft interview questions', 'Create an onboarding checklist', 'How to handle a performance issue?'],
    'financial-analyst':    ['Explain EBITDA vs net income', 'Build a simple revenue model', 'How to forecast cash flow?', 'Review this financial summary'],
    'legal-counsel':        ['Review this NDA clause', 'What is GDPR consent requirement?', 'Explain contractor vs employee', 'Draft a software license summary'],
    'ux-designer':          ['How to run a user interview?', 'Create a user journey map', 'Critique this UI design', 'Write a usability test plan'],
    'devops-engineer':      ['Set up a CI/CD pipeline', 'Explain Kubernetes vs Docker Swarm', 'How to monitor a Node.js app?', 'Write a Terraform AWS config'],
    'content-writer':       ['Write a blog intro about AI', 'Create 5 headline options', 'Improve this paragraph', 'Write a LinkedIn post about growth'],
    'seo-specialist':       ['Audit this page title & meta', 'Find keywords for SaaS landing page', 'How to improve Core Web Vitals?', 'Write an SEO-friendly H1'],
    'business-analyst':     ['Write acceptance criteria for login', 'Map this business process', 'Identify gaps in this workflow', 'Create a use-case diagram description'],
    'project-manager':      ['Create a project kick-off agenda', 'Write a risk register template', 'How to run a sprint retrospective?', 'Draft a project status report'],
    'security-expert':      ['Review this authentication flow', 'Explain OWASP Top 10', 'How to secure a REST API?', 'Perform a threat model for login'],
    'social-media-manager': ['Write 5 tweet ideas about AI', 'Create a week-long content calendar', 'How to grow LinkedIn followers?', 'Draft an Instagram caption'],
    'qa-engineer':          ['Write test cases for login flow', 'How to set up Selenium tests?', 'Create a bug report template', 'Explain boundary value analysis'],
    'cto-advisor':          ['How to scale an engineering team?', 'Build vs buy decision framework', 'How to manage tech debt?', 'Advise on choosing a tech stack'],
    'recruiter':            ['Write a LinkedIn outreach message', 'Create a scorecard for a PM role', 'How to screen resumes efficiently?', 'Draft an offer letter intro'],
    // AI Architecture layer agents
    'infrastructure-layer':         ['Design a scalable API gateway', 'Compare managed vs self-hosted vector DB', 'Set up a message queue for agent tasks', 'Best practices for AI infra cost control'],
    'agent-internet-layer':         ['How do agents share memory?', 'Design a multi-agent coordination flow', 'Compare LangGraph vs CrewAI', 'Build a pub-sub event mesh for agents'],
    'protocol-layer':               ['Explain Model Context Protocol (MCP)', 'Design a tool schema for a search function', 'How to version an agent API?', 'Create an OpenAPI spec for an agent endpoint'],
    'tooling-enrichment-layer':     ['Set up a RAG pipeline with Pinecone', 'Best chunking strategy for long documents', 'How to integrate web search into an agent?', 'Compare embedding models for retrieval'],
    'cognition-reasoning-layer':    ['Explain ReAct vs Chain-of-Thought', 'Design a self-reflection loop for an agent', 'How to decompose a complex multi-step task?', 'Build an error-correction mechanism for agents'],
    'memory-personalization-layer': ['Design a long-term memory architecture', 'How to store user preferences for an AI?', 'Compare vector memory vs knowledge graph', 'Privacy-preserving agent memory strategies'],
    'application-layer':            ['Design a conversational AI onboarding flow', 'Best practices for streaming AI responses', 'How to build user trust in an AI assistant?', 'Design a copilot UI for a developer tool'],
    'operations-governance-layer':  ['Set up AI cost monitoring and alerts', 'Explain the EU AI Act requirements', 'Design safety guardrails for an AI agent', 'How to conduct AI red-teaming?'],
  };

  const chatMessages  = document.getElementById('chatMessages');
  const chatInput     = document.getElementById('chatInput');
  const sendBtn       = document.getElementById('sendBtn');
  const typingInd     = document.getElementById('typingIndicator');
  const errorToast    = document.getElementById('errorToast');
  const welcomeHint   = document.getElementById('welcomeHint');
  const suggChips     = document.getElementById('suggestionChips');
  let history = [];
  let busy    = false;

  // Render suggestion chips
  const chips = SUGGESTIONS[ROLE] || [];
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

    fetch('/VIRAL/chat.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ role: ROLE, message: text, history: history.slice(0, -1) }),
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
}());
PAGEJS;

require_once dirname(__DIR__) . '/includes/footer.php';
