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
$expandedRoleCount = 80;
$expandedRoleSlugsJson = json_encode(array_slice(array_keys(VIRAL_AGENTS), 0, $expandedRoleCount));

$page_title  = $agentLabel . ' Agent – CodeFoundry VIRAL';
$active_page = 'viral';
$page_styles = <<<PAGECSS
  :root { --accent: {$agent['accent']}; }

  /* ── Layout ──────────────────────────────────────────────────────────── */
  .viral-layout {
    display: flex;
    gap: 0;
    min-height: calc(100vh - 120px);
    max-width: 1400px;
    margin: 0 auto;
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

  /* ── Chat area ───────────────────────────────────────────────────────── */
  .viral-chat-wrap {
    flex: 1;
    min-width: 0;
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
  }
  .chat-messages {
    flex: 1; min-height: 420px; max-height: 560px;
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

  /* ── Mobile ──────────────────────────────────────────────────────────── */
  @media (max-width: 900px) {
    .viral-layout    { flex-direction: column; }
    .prompt-sidebar  { width: 100% !important; border-right: none; border-bottom: 1px solid var(--border-color); max-height: 260px; }
    .prompt-sidebar.collapsed { max-height: 0; overflow: hidden; border-bottom: none; }
    .sidebar-toggle  { display: none; }
    .mobile-sidebar-btn { display: inline-flex !important; }
    .viral-chat-wrap { padding: 16px 14px 80px; }
    .chat-messages   { min-height: 340px; }
    .msg             { max-width: 96%; }
  }
  @media (min-width: 901px) {
    .mobile-sidebar-btn { display: none !important; }
  }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<main>
  <div class="viral-layout">

    <!-- ── Prompt Sidebar ─────────────────────────────────────────────── -->
    <aside class="prompt-sidebar" id="promptSidebar">
      <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle prompt sidebar">
        <iconify-icon icon="lucide:chevron-left" id="sidebarToggleIcon"></iconify-icon>
      </button>
      <div class="sidebar-header">
        <span class="sidebar-title">Prompt Examples</span>
      </div>
      <div class="sidebar-search">
        <input type="text" id="promptSearch" placeholder="Search prompts…" autocomplete="off">
      </div>
      <div class="prompt-list" id="promptList"></div>
      <div class="prompt-count" id="promptCount"></div>
    </aside>

    <!-- ── Chat Area ──────────────────────────────────────────────────── -->
    <div class="viral-chat-wrap">

      <div>
        <button class="chip mobile-sidebar-btn" id="mobileSidebarBtn" style="display:none;margin-bottom:8px;">
          <iconify-icon icon="lucide:list" style="font-size:13px;margin-right:4px;"></iconify-icon>
          Prompt Examples
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

    </div><!-- /.viral-chat-wrap -->
  </div><!-- /.viral-layout -->
</main>

<?php
$page_scripts = <<<PAGEJS
(function () {
  const ROLE       = {$roleJson};
  const AGENT_ICON = {$agentIconJson};
  const EXPANDED_ROLE_SLUGS = {$expandedRoleSlugsJson};
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
  const mobileSidebarBtn = document.getElementById('mobileSidebarBtn');
  const rolePrompts   = buildPromptsForRole(ROLE);
  let history = [];
  let busy    = false;

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
    if (!sidebarToggleIcon || !promptSidebar) return;
    sidebarToggleIcon.setAttribute('icon', promptSidebar.classList.contains('collapsed') ? 'lucide:chevron-right' : 'lucide:chevron-left');
  }

  function renderPromptList(searchTerm) {
    if (!promptList || !promptCount) return;
    const term = String(searchTerm || '').toLowerCase().trim();
    promptList.innerHTML = '';
    let visible = 0;

    rolePrompts.forEach(function (text) {
      if (term && text.toLowerCase().indexOf(term) === -1) return;
      visible += 1;
      const item = document.createElement('button');
      item.type = 'button';
      item.className = 'prompt-item';
      item.textContent = text;
      item.addEventListener('click', function () {
        chatInput.value = text;
        chatInput.dispatchEvent(new Event('input'));
        chatInput.focus();
      });
      promptList.appendChild(item);
    });

    promptCount.textContent = visible + ' of ' + rolePrompts.length + ' prompts';
  }

  renderPromptList('');
  updateSidebarIcon();

  if (promptSearch) {
    promptSearch.addEventListener('input', function () {
      renderPromptList(this.value);
    });
  }

  if (sidebarToggle && promptSidebar) {
    sidebarToggle.addEventListener('click', function () {
      promptSidebar.classList.toggle('collapsed');
      updateSidebarIcon();
    });
  }

  if (mobileSidebarBtn && promptSidebar) {
    mobileSidebarBtn.addEventListener('click', function () {
      promptSidebar.classList.toggle('collapsed');
      updateSidebarIcon();
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
