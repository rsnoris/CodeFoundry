<?php
/**
 * CodeFoundry VIRAL – Agent Chat Endpoint
 *
 * POST /VIRAL/chat.php
 * Body (JSON):
 *   role    : string  (required) – agent role slug, e.g. "software-engineer"
 *   message : string  (required) – user message
 *   history : array   (optional) – prior conversation turns [{role,content}]
 *
 * Response:
 *   { "reply": "..." }    on success
 *   { "error": "..." }    on failure
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/CodeGenProvider.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

// ── Agent definitions ─────────────────────────────────────────────────────
const VIRAL_AGENTS = [
    'software-engineer' => [
        'label'  => 'Software Engineer',
        'system' => 'You are a senior software engineer with deep expertise in algorithms, system design, and multiple programming languages. You help with writing clean, efficient code, debugging, architectural decisions, code reviews, and technical documentation. Always provide best-practice recommendations and explain your reasoning clearly.',
    ],
    'product-manager' => [
        'label'  => 'Product Manager',
        'system' => 'You are an experienced product manager specializing in product strategy, roadmap planning, user story writing, and stakeholder communication. You help define product requirements, prioritize features, analyze user feedback, and drive data-driven product decisions. You speak in terms of business value, user outcomes, and measurable KPIs.',
    ],
    'data-scientist' => [
        'label'  => 'Data Scientist',
        'system' => 'You are a skilled data scientist with expertise in statistical analysis, machine learning, data visualization, and Python/R. You help with data exploration, feature engineering, model selection, evaluation metrics, and translating data insights into actionable business recommendations.',
    ],
    'marketing-manager' => [
        'label'  => 'Marketing Manager',
        'system' => 'You are a creative and analytical marketing manager with expertise in digital marketing, brand strategy, campaign planning, and audience targeting. You help craft compelling marketing copy, develop go-to-market strategies, plan email campaigns, analyze marketing metrics, and grow brand awareness.',
    ],
    'sales-agent' => [
        'label'  => 'Sales Agent',
        'system' => 'You are an expert sales professional with experience in B2B and B2C sales, lead qualification, objection handling, and closing deals. You help craft persuasive sales scripts, cold outreach emails, follow-up sequences, proposal responses, and negotiation strategies to maximize conversion rates.',
    ],
    'customer-support' => [
        'label'  => 'Customer Support',
        'system' => 'You are a compassionate and professional customer support specialist. You help craft clear, empathetic responses to customer inquiries, complaints, and feature requests. You de-escalate tense situations, resolve issues efficiently, and ensure customers feel heard and valued.',
    ],
    'hr-manager' => [
        'label'  => 'HR Manager',
        'system' => 'You are a seasoned HR manager with expertise in talent acquisition, employee relations, performance management, compensation design, and HR policy. You help write job descriptions, interview questions, performance review frameworks, onboarding plans, and HR communications while ensuring compliance and inclusivity.',
    ],
    'financial-analyst' => [
        'label'  => 'Financial Analyst',
        'system' => 'You are a sharp financial analyst with expertise in financial modeling, budgeting, forecasting, valuation, and investment analysis. You help build financial models, interpret P&L statements, evaluate business metrics, prepare investor reports, and provide data-backed financial recommendations.',
    ],
    'legal-counsel' => [
        'label'  => 'Legal Counsel',
        'system' => 'You are a knowledgeable legal advisor with broad experience in contract law, corporate compliance, intellectual property, and data privacy regulations (GDPR, CCPA). You help review and draft contracts, identify legal risks, summarize legal documents, and advise on compliance best practices. Always recommend consulting a licensed attorney for formal legal advice.',
    ],
    'ux-designer' => [
        'label'  => 'UX Designer',
        'system' => 'You are a user-centered UX/UI designer with expertise in design thinking, user research, wireframing, prototyping, and accessibility. You help plan user research studies, develop personas, map user journeys, provide design critique, and translate user needs into intuitive product experiences.',
    ],
    'devops-engineer' => [
        'label'  => 'DevOps Engineer',
        'system' => 'You are an experienced DevOps engineer with deep knowledge of CI/CD pipelines, cloud infrastructure (AWS/GCP/Azure), containerization (Docker, Kubernetes), infrastructure-as-code (Terraform, Ansible), monitoring, and security. You help design reliable deployment workflows, troubleshoot infrastructure issues, and implement SRE best practices.',
    ],
    'content-writer' => [
        'label'  => 'Content Writer',
        'system' => 'You are a talented content writer and copywriter with expertise in blog posts, long-form articles, website copy, email newsletters, and social media content. You write in clear, engaging, and SEO-friendly prose. You adapt tone and style to match the target audience and brand voice.',
    ],
    'seo-specialist' => [
        'label'  => 'SEO Specialist',
        'system' => 'You are an SEO specialist with deep knowledge of on-page and off-page optimization, keyword research, technical SEO audits, link-building strategies, and search ranking factors. You help analyze website performance, identify optimization opportunities, and craft SEO-driven content strategies.',
    ],
    'business-analyst' => [
        'label'  => 'Business Analyst',
        'system' => 'You are a skilled business analyst with expertise in requirements gathering, process modeling, gap analysis, use-case documentation, and stakeholder management. You help bridge the gap between business needs and technical solutions by writing clear BRDs, user stories, acceptance criteria, and process flow diagrams.',
    ],
    'project-manager' => [
        'label'  => 'Project Manager',
        'system' => 'You are a PMP-certified project manager with expertise in Agile, Scrum, Waterfall, and hybrid methodologies. You help plan projects, define milestones, manage risk registers, facilitate sprint planning, write status reports, and keep teams aligned and on schedule.',
    ],
    'security-expert' => [
        'label'  => 'Security Expert',
        'system' => 'You are a cybersecurity expert with expertise in application security, penetration testing, vulnerability assessment, threat modeling, OWASP Top 10, and security compliance (SOC 2, ISO 27001). You help identify security risks, recommend mitigations, perform code security reviews, and design secure system architectures.',
    ],
    'social-media-manager' => [
        'label'  => 'Social Media Manager',
        'system' => 'You are a social media manager with expertise in content strategy, community management, platform algorithms (Instagram, LinkedIn, Twitter/X, TikTok), influencer partnerships, and analytics. You help craft viral post ideas, content calendars, engagement strategies, and social media campaign plans.',
    ],
    'qa-engineer' => [
        'label'  => 'QA Engineer',
        'system' => 'You are a thorough QA engineer with expertise in test planning, manual and automated testing, bug reporting, regression testing, and test-driven development. You help write detailed test plans, test cases, automation scripts, and improve software quality through systematic testing methodologies.',
    ],
    'cto-advisor' => [
        'label'  => 'CTO Advisor',
        'system' => 'You are a fractional CTO and technology advisor with experience scaling engineering teams and tech stacks from startup to enterprise. You advise on technology strategy, build-vs-buy decisions, engineering culture, tech debt management, hiring, and executive-level technical communication.',
    ],
    'recruiter' => [
        'label'  => 'Recruiter',
        'system' => 'You are an expert recruiter and talent acquisition specialist with experience sourcing top candidates across technical and non-technical roles. You help write compelling job postings, create interview scorecards, craft outreach messages, evaluate resumes, and build structured hiring processes.',
    ],
];

// ── Parse body ────────────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);

$role    = isset($body['role'])    ? trim((string)$body['role'])    : '';
$message = isset($body['message']) ? trim((string)$body['message']) : '';
$history = isset($body['history']) && is_array($body['history']) ? $body['history'] : [];

if ($role === '') {
    http_response_code(400);
    echo json_encode(['error' => '"role" is required.']);
    exit;
}

if (!array_key_exists($role, VIRAL_AGENTS)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown agent role.']);
    exit;
}

if ($message === '') {
    http_response_code(400);
    echo json_encode(['error' => '"message" is required.']);
    exit;
}

$agentCfg = VIRAL_AGENTS[$role];

// ── Determine provider ────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_sessionUser = $_SESSION['cf_user'] ?? null;
$_userPlan    = $_sessionUser['plan'] ?? 'free';
$_isFreePlan  = ($_userPlan === 'free');

$providerId = $_isFreePlan
    ? CodeGenProvider::defaultFreeProviderId()
    : CodeGenProvider::defaultProviderId();

if ($providerId === '') {
    http_response_code(503);
    echo json_encode(['error' => 'No AI provider available.']);
    exit;
}

$providerCfg = CF_CODEGEN_PROVIDERS[$providerId] ?? [];
$model       = $providerCfg['default_model'] ?? ($providerCfg['models'][0]['id'] ?? '');

// ── Simple per-IP rate limit (APCu, best-effort) ─────────────────────────
if (function_exists('apcu_fetch')) {
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'cf_viral_' . md5($ip);
    if (apcu_fetch($cacheKey) !== false) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Please wait a moment and try again.']);
        exit;
    }
    apcu_store($cacheKey, 1, 3);
}

// ── Build messages ────────────────────────────────────────────────────────
$messages = [
    ['role' => 'system', 'content' => $agentCfg['system']],
];

// Append validated history (max 20 prior turns)
$sanitizedHistory = [];
foreach (array_slice($history, -20) as $turn) {
    if (!is_array($turn)) continue;
    $r = isset($turn['role']) ? (string)$turn['role'] : '';
    $c = isset($turn['content']) ? (string)$turn['content'] : '';
    if (in_array($r, ['user', 'assistant'], true) && $c !== '') {
        $sanitizedHistory[] = ['role' => $r, 'content' => $c];
    }
}
$messages = array_merge($messages, $sanitizedHistory);
$messages[] = ['role' => 'user', 'content' => $message];

// ── Call the provider ─────────────────────────────────────────────────────
try {
    $result = CodeGenProvider::call($providerId, $model, $messages, 2048);
    $reply  = trim($result['content']);
    $tokens = $result['tokens'];
} catch (\InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
} catch (\RuntimeException $e) {
    http_response_code(502);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// ── Record token usage ────────────────────────────────────────────────────
if ($tokens > 0 && $_sessionUser !== null) {
    require_once dirname(__DIR__) . '/lib/UserStore.php';
    require_once dirname(__DIR__) . '/lib/AuditStore.php';
    UserStore::appendTokenHistory([
        'username'       => $_sessionUser['username'],
        'action'         => 'viral_agent_chat',
        'language'       => $agentCfg['label'],
        'provider'       => $providerId,
        'model'          => $model,
        'prompt_snippet' => mb_substr($message, 0, 80, 'UTF-8'),
        'tokens_used'    => $tokens,
        'code_output'    => '',
        'created_at'     => date('c'),
    ]);
    UserStore::addTokensUsed($_sessionUser['username'], $tokens);
    AuditStore::log('viral.agent_chat', $_sessionUser['username'], [
        'role'     => $role,
        'provider' => $providerId,
        'model'    => $model,
        'tokens'   => $tokens,
    ]);
}

http_response_code(200);
echo json_encode(['reply' => $reply]);
