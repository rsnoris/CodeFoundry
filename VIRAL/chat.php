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
require_once __DIR__ . '/config.php';   // VIRAL_AGENTS constant

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

/**
 * Maximum number of prior conversation turns to include in context.
 * Keeps token usage reasonable while preserving enough context for
 * coherent multi-turn conversations.
 */
const VIRAL_MAX_HISTORY_TURNS = 20;

/**
 * Minimum seconds between requests from the same IP.
 * Low enough to allow comfortable back-and-forth chat while
 * still discouraging automated abuse.
 */
const VIRAL_RATE_LIMIT_SECONDS = 5;
const VIRAL_FREE_DAILY_PROMPT_LIMIT = 10;
const VIRAL_FREE_DAILY_TOKEN_LIMIT  = 1000;
const VIRAL_UI_HIFI_THEMES = [
    'Neo Banking Dark',
    'Minimal SaaS Light',
    'Cyber Neon Contrast',
    'Glassmorphism Aurora',
    'Brutalist Mono',
    'Fintech Trust Blue',
    'Healthcare Calm',
    'Ecommerce Warm',
    'Editorial Premium',
    'Playful Gradient',
    'Enterprise Slate',
    'Nature Soft',
];
const VIRAL_UI_COMPONENT_PACK = [
    'button-primary','button-secondary','icon-button','input-text','input-search','textarea','select','checkbox',
    'radio-group','toggle-switch','range-slider','card','stats-card','table','list','avatar','badge','chip',
    'tooltip','alert-banner','toast','modal-dialog','drawer','bottom-sheet','top-navigation','side-navigation',
    'bottom-navigation','breadcrumbs','pagination','tabs','accordion','stepper','progress-bar','skeleton-loader',
    'hero-section','pricing-card','timeline','calendar','chart-container','file-upload','command-palette',
    'kpi-tile','feed-item','comment-thread','empty-state','footer',
];

/**
 * Daily usage for authenticated users, based on token history.
 *
 * @return array{prompts:int,tokens:int}
 */
function viral_daily_usage_for_user(string $username): array
{
    require_once dirname(__DIR__) . '/lib/UserStore.php';
    $history = UserStore::tokenHistoryForUser($username, 2000);
    $todayStart = strtotime('today');
    $prompts = 0;
    $tokens  = 0;
    foreach ($history as $row) {
        if (($row['action'] ?? '') !== 'viral_agent_chat') {
            continue;
        }
        $createdAt = isset($row['created_at']) ? strtotime((string)$row['created_at']) : false;
        if ($createdAt === false) {
            continue;
        }
        if ($createdAt < $todayStart) {
            break; // tokenHistoryForUser() is newest-first; older rows can stop the scan.
        }
        $prompts++;
        $tokens += (int)($row['tokens_used'] ?? 0);
    }
    return ['prompts' => $prompts, 'tokens' => $tokens];
}

/**
 * Daily usage for guests (unauthenticated), persisted in APCu when available,
 * otherwise in session.
 *
 * @return array{prompts:int,tokens:int}
 */
function viral_daily_usage_for_guest(string $ip): array
{
    $dayKey = date('Y-m-d');
    if (function_exists('apcu_fetch')) {
        $cacheKey = 'cf_viral_daily_guest_' . md5($ip . '|' . $dayKey);
        $usage = apcu_fetch($cacheKey);
        if (is_array($usage)) {
            return [
                'prompts' => (int)($usage['prompts'] ?? 0),
                'tokens'  => (int)($usage['tokens'] ?? 0),
            ];
        }
        return ['prompts' => 0, 'tokens' => 0];
    }

    $sessionKey = 'cf_viral_daily_guest_' . $dayKey;
    $usage = $_SESSION[$sessionKey] ?? ['prompts' => 0, 'tokens' => 0];
    return [
        'prompts' => (int)($usage['prompts'] ?? 0),
        'tokens'  => (int)($usage['tokens'] ?? 0),
    ];
}

function viral_store_guest_daily_usage(string $ip, int $prompts, int $tokens): void
{
    $dayKey = date('Y-m-d');
    $usage = ['prompts' => max(0, $prompts), 'tokens' => max(0, $tokens)];
    if (function_exists('apcu_store')) {
        $cacheKey = 'cf_viral_daily_guest_' . md5($ip . '|' . $dayKey);
        apcu_store($cacheKey, $usage, 86400);
        return;
    }
    $sessionKey = 'cf_viral_daily_guest_' . $dayKey;
    $_SESSION[$sessionKey] = $usage;
}

// ── Parse body ────────────────────────────────────────────────────────────
$raw  = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);

$role    = isset($body['role'])    ? trim((string)$body['role'])    : '';
$message = isset($body['message']) ? trim((string)$body['message']) : '';
$history = isset($body['history']) && is_array($body['history']) ? $body['history'] : [];
$providerIdRequested = isset($body['provider']) ? trim((string)$body['provider']) : '';
$modelRequested      = isset($body['model']) ? trim((string)$body['model']) : '';
$taskCategory        = isset($body['task_category']) ? trim((string)$body['task_category']) : '';
$taskType            = isset($body['task']) ? trim((string)$body['task']) : '';
$designContext       = isset($body['design_context']) && is_array($body['design_context']) ? $body['design_context'] : null;

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

if ($providerIdRequested !== '' && !array_key_exists($providerIdRequested, CF_CODEGEN_PROVIDERS)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown provider: ' . $providerIdRequested]);
    exit;
}

if ($taskCategory !== '' && !array_key_exists($taskCategory, VIRAL_TASK_CATEGORY_GROUPS)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unknown task category.']);
    exit;
}

if ($taskType !== '') {
    if ($taskCategory === '') {
        foreach (VIRAL_TASK_CATEGORY_GROUPS as $groupName => $tasks) {
            if (in_array($taskType, $tasks, true)) {
                $taskCategory = (string)$groupName;
                break;
            }
        }
    }
    if ($taskCategory === '' || !in_array($taskType, VIRAL_TASK_CATEGORY_GROUPS[$taskCategory] ?? [], true)) {
        http_response_code(400);
        echo json_encode(['error' => 'Unknown task type for selected category.']);
        exit;
    }
}

$agentCfg = VIRAL_AGENTS[$role];

// ── Determine provider candidates ─────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$_sessionUser = $_SESSION['cf_user'] ?? null;
$_userPlan    = $_sessionUser['plan'] ?? 'free';
$_isFreePlan  = ($_userPlan === 'free');

$providerCandidates = CodeGenProvider::candidateProviderIds($providerIdRequested);
if (empty($providerCandidates)) {
    http_response_code(503);
    echo json_encode(['error' => 'No AI providers are configured. Please add at least one provider key in account/admin settings and try again.']);
    exit;
}
$providerId = '';
$model      = '';

// ── Daily free-plan limits ──────────────────────────────────────────────────
$dailyUsage = ['prompts' => 0, 'tokens' => 0];
$guestIp = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if ($_isFreePlan) {
    if ($_sessionUser !== null && !empty($_sessionUser['username'])) {
        $dailyUsage = viral_daily_usage_for_user((string)$_sessionUser['username']);
    } else {
        $dailyUsage = viral_daily_usage_for_guest($guestIp);
    }

    if ($dailyUsage['prompts'] >= VIRAL_FREE_DAILY_PROMPT_LIMIT) {
        http_response_code(429);
        echo json_encode([
            'error' => 'Daily free limit reached (' . VIRAL_FREE_DAILY_PROMPT_LIMIT . ' prompts/day). Please try again tomorrow or upgrade your plan.',
        ]);
        exit;
    }
    if ($dailyUsage['tokens'] >= VIRAL_FREE_DAILY_TOKEN_LIMIT) {
        http_response_code(429);
        echo json_encode([
            'error' => 'Daily free token limit reached. Please try again tomorrow or upgrade your plan.',
        ]);
        exit;
    }
}

// ── Simple per-IP rate limit (APCu, best-effort) ─────────────────────────
if (function_exists('apcu_fetch')) {
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $cacheKey = 'cf_viral_' . md5($ip);
    if (apcu_fetch($cacheKey) !== false) {
        http_response_code(429);
        echo json_encode(['error' => 'Too many requests. Please wait a moment and try again.']);
        exit;
    }
    apcu_store($cacheKey, 1, VIRAL_RATE_LIMIT_SECONDS);
}

// ── Build messages ────────────────────────────────────────────────────────
$messages = [
    ['role' => 'system', 'content' => $agentCfg['system']],
];
if ($taskCategory !== '' || $taskType !== '') {
    $selectionParts = [];
    if ($taskCategory !== '') {
        $selectionParts[] = 'Selected task category: ' . $taskCategory;
    }
    if ($taskType !== '') {
        $selectionParts[] = 'Selected task type: ' . $taskType;
    }
    $messages[] = [
        'role' => 'system',
        'content' => implode('. ', $selectionParts) . '. Tailor the response to this selected AI task context while preserving the role persona.',
    ];
}

// Append validated history (bounded to VIRAL_MAX_HISTORY_TURNS prior turns)
$sanitizedHistory = [];
foreach (array_slice($history, -VIRAL_MAX_HISTORY_TURNS) as $turn) {
    if (!is_array($turn)) continue;
    $r = isset($turn['role']) ? (string)$turn['role'] : '';
    $c = isset($turn['content']) ? (string)$turn['content'] : '';
    if (in_array($r, ['user', 'assistant'], true) && $c !== '') {
        $sanitizedHistory[] = ['role' => $r, 'content' => $c];
    }
}
$messages = array_merge($messages, $sanitizedHistory);

// For the UI Design Agentic Tool: inject the current design state so the model
// can apply targeted patches rather than regenerating from scratch.
if ($role === 'ui-design-agentic-tool' && $designContext !== null) {
    $contextJson = json_encode($designContext, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($contextJson !== false) {
        $messages[] = [
            'role'    => 'system',
            'content' => 'Current design state (do NOT regenerate screens that are not changing; '
                . 'use op:"patch" and only include updated screens in "patches"): ' . $contextJson,
        ];
    }
}
if ($role === 'ui-design-agentic-tool') {
    $messages[] = [
        'role' => 'system',
        'content' => 'Hi-fi theme templates available for reuse/customization: '
            . implode(', ', VIRAL_UI_HIFI_THEMES)
            . '. Pick the best matching template for each request and adapt style tokens/components while preserving consistency across screens.',
    ];
    $messages[] = [
        'role' => 'system',
        'content' => 'Component library full pack to include in design outputs (especially on op:"create"): '
            . implode(', ', VIRAL_UI_COMPONENT_PACK)
            . '. Keep component IDs/types stable across follow-up patches.',
    ];
}

$messages[] = ['role' => 'user', 'content' => $message];

// ── Call the provider ─────────────────────────────────────────────────────
try {
    $result = CodeGenProvider::callWithFallback($providerCandidates, $modelRequested, $messages, 2048);
    $reply  = trim($result['content']);
    $tokens = $result['tokens'];
    $providerId = $result['provider'];
    $model = $result['model'];
} catch (\InvalidArgumentException $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
} catch (\RuntimeException $e) {
    http_response_code(502);
    echo json_encode(['error' => $e->getMessage()]);
    exit;
}

// ── Extract structured design payload for UI Design Agentic Tool ─────────
$designPayload = null;
if ($role === 'ui-design-agentic-tool') {
    // Look for a ```json ... ``` block that contains a cf-ui-design/1 schema.
    // Use a greedy quantifier so nested JSON objects are captured in full.
    if (preg_match('/```json\s*(\{.*\})\s*```/s', $reply, $jsonMatch)) {
        $candidate = json_decode($jsonMatch[1], true);
        if (is_array($candidate) && ($candidate['schema'] ?? '') === 'cf-ui-design/1') {
            $designPayload = $candidate;
            // Remove the JSON block from the narrative reply so the chat bubble
            // only shows the human-readable text.
            $reply = trim(str_replace($jsonMatch[0], '', $reply));
        }
    }
}

// ── Record token usage ────────────────────────────────────────────────────
if ($_sessionUser !== null) {
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
    if ($tokens > 0) {
        UserStore::addTokensUsed($_sessionUser['username'], $tokens);
    }
    AuditStore::log('viral.agent_chat', $_sessionUser['username'], [
        'role'     => $role,
        'provider' => $providerId,
        'model'    => $model,
        'tokens'   => $tokens,
    ]);
} elseif ($_isFreePlan) {
    viral_store_guest_daily_usage($guestIp, $dailyUsage['prompts'] + 1, $dailyUsage['tokens'] + max(0, $tokens));
}

http_response_code(200);
$responseData = ['reply' => $reply];
if ($designPayload !== null) {
    $responseData['design'] = $designPayload;
}
echo json_encode($responseData);
