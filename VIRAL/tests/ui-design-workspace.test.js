/**
 * UI Design Agentic Tool – focused unit tests
 *
 * Tests the core logic extracted from agent.php / chat.php:
 *   1. Payload parsing    – cf-ui-design/1 schema validation
 *   2. Iterative patching – dwApplyPatch merges changes without overwriting intact data
 *   3. Responsive variants – screen/iframe content selection per breakpoint
 *   4. Flow completeness  – all transition endpoints reference real screen IDs
 *
 * Run with:  node VIRAL/tests/ui-design-workspace.test.js
 * No external dependencies required (uses Node built-in `assert`).
 */

'use strict';

const assert = require('assert');

// ── Helpers mirroring the JS functions in agent.php ──────────────────────────

/**
 * Returns true if the parsed object is a valid cf-ui-design/1 payload.
 * (Mirrors the schema check done in chat.php and the import handler.)
 */
function isValidDesignPayload(obj) {
  if (!obj || typeof obj !== 'object') return false;
  if (obj.schema !== 'cf-ui-design/1') return false;
  if (!['create', 'patch'].includes(obj.op)) return false;
  if (typeof obj.project !== 'object' || obj.project === null) return false;
  return true;
}

/**
 * Extract the JSON design block from a raw AI reply string.
 * Mirrors the regex logic in chat.php.
 */
function extractDesignPayload(replyText) {
  const match = replyText.match(/```json\s*(\{[\s\S]*?\})\s*```/);
  if (!match) return null;
  let candidate;
  try { candidate = JSON.parse(match[1]); } catch (e) { return null; }
  if (!candidate || candidate.schema !== 'cf-ui-design/1') return null;
  return candidate;
}

/**
 * Applies a list of patch objects to a mutable designState.
 * Mirrors dwApplyPatch() in agent.php.
 */
function applyPatch(designState, patchList) {
  if (!designState || !Array.isArray(patchList)) return;
  patchList.forEach(function (patch) {
    const idx = designState.screens.findIndex(function (s) { return s.id === patch.screenId; });
    if (idx === -1) return;
    if (patch.html        !== undefined) designState.screens[idx].html        = patch.html;
    if (patch.css         !== undefined) designState.screens[idx].css         = patch.css;
    if (patch.mobileHtml  !== undefined) designState.screens[idx].mobileHtml  = patch.mobileHtml;
    if (patch.mobileCss   !== undefined) designState.screens[idx].mobileCss   = patch.mobileCss;
    if (patch.name        !== undefined) designState.screens[idx].name        = patch.name;
    if (patch.description !== undefined) designState.screens[idx].description = patch.description;
  });
}

/**
 * Given a screen and a breakpoint, return the correct HTML/CSS for rendering.
 * Mirrors the dwRenderCanvas() selection logic in agent.php.
 */
function getScreenContent(screen, breakpoint) {
  const isMobile = breakpoint === 'mobile';
  const html = isMobile ? (screen.mobileHtml || screen.html || '') : (screen.html || '');
  const css  = isMobile
    ? (String(screen.css || '') + '\n' + String(screen.mobileCss || ''))
    : String(screen.css || '');
  return { html, css };
}

/**
 * Validate flow completeness: every transition's from/to references a real screen ID.
 * Returns an array of error strings (empty = valid).
 */
function validateFlowCompleteness(design) {
  const errors = [];
  if (!design || !design.flow) return errors;
  const screenIds = new Set((design.screens || []).map(function (s) { return s.id; }));
  const transitions = design.flow.transitions || [];

  if (design.flow.entryScreen && !screenIds.has(design.flow.entryScreen)) {
    errors.push('entryScreen "' + design.flow.entryScreen + '" does not reference a known screen.');
  }

  transitions.forEach(function (t, i) {
    if (!screenIds.has(t.from)) {
      errors.push('Transition[' + i + '].from "' + t.from + '" does not reference a known screen.');
    }
    if (!screenIds.has(t.to)) {
      errors.push('Transition[' + i + '].to "' + t.to + '" does not reference a known screen.');
    }
  });
  return errors;
}

// ── Test runner ───────────────────────────────────────────────────────────────

let passed = 0;
let failed = 0;

function test(name, fn) {
  try {
    fn();
    console.log('  \x1b[32m✓\x1b[0m ' + name);
    passed++;
  } catch (err) {
    console.log('  \x1b[31m✗\x1b[0m ' + name);
    console.log('    ' + err.message);
    failed++;
  }
}

// ── 1. Payload Parsing ────────────────────────────────────────────────────────

console.log('\n1. Payload Parsing');

test('accepts a minimal valid cf-ui-design/1 create payload', function () {
  const payload = {
    schema: 'cf-ui-design/1',
    op: 'create',
    project: { name: 'Test App', description: 'A test' },
    styleTokens: {},
    screens: [],
    components: [],
    flow: { entryScreen: null, transitions: [] },
  };
  assert.strictEqual(isValidDesignPayload(payload), true);
});

test('accepts op:"patch" payloads', function () {
  const payload = { schema: 'cf-ui-design/1', op: 'patch', project: {}, patches: [] };
  assert.strictEqual(isValidDesignPayload(payload), true);
});

test('rejects payloads with wrong schema version', function () {
  const payload = { schema: 'cf-ui-design/2', op: 'create', project: {} };
  assert.strictEqual(isValidDesignPayload(payload), false);
});

test('rejects payloads with unknown op', function () {
  const payload = { schema: 'cf-ui-design/1', op: 'delete', project: {} };
  assert.strictEqual(isValidDesignPayload(payload), false);
});

test('rejects null', function () {
  assert.strictEqual(isValidDesignPayload(null), false);
});

test('rejects plain string', function () {
  assert.strictEqual(isValidDesignPayload('cf-ui-design/1'), false);
});

test('extracts JSON block from a mixed AI reply', function () {
  const fullDesign = {
    schema: 'cf-ui-design/1',
    op: 'create',
    project: { name: 'Dashboard', description: '' },
    screens: [{ id: 's1', name: 'Home', html: '<div>Home</div>', css: '' }],
    flow: { entryScreen: 's1', transitions: [] },
  };
  const reply = 'Here is your design!\n\n```json\n' + JSON.stringify(fullDesign) + '\n```\n\nLet me know if you want changes.';
  const extracted = extractDesignPayload(reply);
  assert.ok(extracted, 'Should extract a payload');
  assert.strictEqual(extracted.project.name, 'Dashboard');
  assert.strictEqual(extracted.screens[0].id, 's1');
});

test('returns null when no JSON block is present', function () {
  const extracted = extractDesignPayload('Here is some plain text without a code block.');
  assert.strictEqual(extracted, null);
});

test('returns null when JSON block exists but lacks cf-ui-design/1 schema', function () {
  const reply = '```json\n{"foo": "bar"}\n```';
  assert.strictEqual(extractDesignPayload(reply), null);
});

test('returns null when JSON block is malformed', function () {
  const reply = '```json\n{ invalid json }\n```';
  assert.strictEqual(extractDesignPayload(reply), null);
});

// ── 2. Iterative Patching ─────────────────────────────────────────────────────

console.log('\n2. Iterative Patching');

function makeDesignState() {
  return {
    schema: 'cf-ui-design/1',
    op: 'create',
    project: { name: 'My App' },
    styleTokens: { colors: { primary: '#ec4899' } },
    screens: [
      { id: 'screen_login',     name: 'Login',     html: '<div>Login</div>',     css: '',  mobileHtml: '<div>M-Login</div>',     mobileCss: '' },
      { id: 'screen_dashboard', name: 'Dashboard', html: '<div>Dashboard</div>', css: '',  mobileHtml: '<div>M-Dashboard</div>', mobileCss: '' },
    ],
    components: [],
    flow: { entryScreen: 'screen_login', transitions: [] },
    patches: [],
  };
}

test('patches html on a matching screen', function () {
  const state = makeDesignState();
  applyPatch(state, [{ screenId: 'screen_login', html: '<div>New Login</div>' }]);
  assert.strictEqual(state.screens[0].html, '<div>New Login</div>');
});

test('does not modify unpatched screens', function () {
  const state = makeDesignState();
  applyPatch(state, [{ screenId: 'screen_login', html: '<div>Updated</div>' }]);
  assert.strictEqual(state.screens[1].html, '<div>Dashboard</div>');
});

test('patches css independently from html', function () {
  const state = makeDesignState();
  applyPatch(state, [{ screenId: 'screen_dashboard', css: 'body{background:red}' }]);
  assert.strictEqual(state.screens[1].css, 'body{background:red}');
  assert.strictEqual(state.screens[1].html, '<div>Dashboard</div>');
});

test('patches mobileHtml without touching html', function () {
  const state = makeDesignState();
  applyPatch(state, [{ screenId: 'screen_login', mobileHtml: '<div>M-Login v2</div>' }]);
  assert.strictEqual(state.screens[0].mobileHtml, '<div>M-Login v2</div>');
  assert.strictEqual(state.screens[0].html, '<div>Login</div>');
});

test('patches multiple screens in a single patch list', function () {
  const state = makeDesignState();
  applyPatch(state, [
    { screenId: 'screen_login',     html: '<div>L2</div>' },
    { screenId: 'screen_dashboard', html: '<div>D2</div>' },
  ]);
  assert.strictEqual(state.screens[0].html, '<div>L2</div>');
  assert.strictEqual(state.screens[1].html, '<div>D2</div>');
});

test('silently ignores patches for unknown screen IDs', function () {
  const state = makeDesignState();
  applyPatch(state, [{ screenId: 'screen_nonexistent', html: '<div>Ghost</div>' }]);
  assert.strictEqual(state.screens[0].html, '<div>Login</div>');
});

test('patches screen name and description', function () {
  const state = makeDesignState();
  applyPatch(state, [{ screenId: 'screen_login', name: 'Sign In', description: 'Updated desc' }]);
  assert.strictEqual(state.screens[0].name, 'Sign In');
  assert.strictEqual(state.screens[0].description, 'Updated desc');
});

test('applying an empty patch list is a no-op', function () {
  const state = makeDesignState();
  applyPatch(state, []);
  assert.strictEqual(state.screens[0].html, '<div>Login</div>');
});

// ── 3. Responsive Variants ────────────────────────────────────────────────────

console.log('\n3. Responsive Variants');

const dualScreen = {
  id: 'screen_home',
  name: 'Home',
  html:       '<div class="web-layout">Web content</div>',
  css:        '.web-layout { max-width: 1280px; }',
  mobileHtml: '<div class="mobile-layout">Mobile content</div>',
  mobileCss:  '.mobile-layout { padding: 16px; }',
};

const webOnlyScreen = {
  id: 'screen_web',
  name: 'Web Only',
  html: '<div>Web only</div>',
  css:  '.x { color: red; }',
};

test('returns web html when breakpoint is "web"', function () {
  const { html } = getScreenContent(dualScreen, 'web');
  assert.ok(html.includes('Web content'));
});

test('returns mobile html when breakpoint is "mobile"', function () {
  const { html } = getScreenContent(dualScreen, 'mobile');
  assert.ok(html.includes('Mobile content'));
});

test('web css does not include mobileCss', function () {
  const { css } = getScreenContent(dualScreen, 'web');
  assert.ok(!css.includes('mobile-layout'));
});

test('mobile css concatenates base css and mobileCss', function () {
  const { css } = getScreenContent(dualScreen, 'mobile');
  assert.ok(css.includes('.web-layout'), 'Should include base css (.web-layout)');
  assert.ok(css.includes('.mobile-layout'), 'Should include mobile css (.mobile-layout)');
});

test('falls back to html when mobileHtml is absent (mobile breakpoint)', function () {
  const { html } = getScreenContent(webOnlyScreen, 'mobile');
  assert.strictEqual(html, '<div>Web only</div>');
});

test('empty strings are returned gracefully when screen has no html/css', function () {
  const empty = { id: 'e', name: 'Empty' };
  const { html, css } = getScreenContent(empty, 'web');
  assert.strictEqual(html, '');
  assert.strictEqual(css, '');
});

// ── 4. Flow Completeness ──────────────────────────────────────────────────────

console.log('\n4. Flow Completeness');

const validDesign = {
  schema: 'cf-ui-design/1',
  op: 'create',
  project: { name: 'Shop' },
  screens: [
    { id: 's_home',     name: 'Home' },
    { id: 's_product',  name: 'Product' },
    { id: 's_cart',     name: 'Cart' },
    { id: 's_checkout', name: 'Checkout' },
    { id: 's_confirm',  name: 'Confirmation' },
  ],
  flow: {
    entryScreen: 's_home',
    transitions: [
      { id: 't1', from: 's_home',    to: 's_product',  label: 'View Product', type: 'neutral' },
      { id: 't2', from: 's_product', to: 's_cart',     label: 'Add to Cart',  type: 'success' },
      { id: 't3', from: 's_cart',    to: 's_checkout', label: 'Checkout',     type: 'neutral' },
      { id: 't4', from: 's_checkout', to: 's_confirm', label: 'Pay',          type: 'success' },
      { id: 't5', from: 's_checkout', to: 's_cart',    label: 'Back',         type: 'neutral' },
    ],
  },
};

test('valid design with correct flow has no errors', function () {
  const errors = validateFlowCompleteness(validDesign);
  assert.strictEqual(errors.length, 0, 'Expected no errors, got: ' + errors.join(', '));
});

test('detects invalid entryScreen', function () {
  const design = JSON.parse(JSON.stringify(validDesign));
  design.flow.entryScreen = 's_nonexistent';
  const errors = validateFlowCompleteness(design);
  assert.ok(errors.some(function (e) { return e.includes('entryScreen'); }), 'Should flag entryScreen error');
});

test('detects transition.from pointing to nonexistent screen', function () {
  const design = JSON.parse(JSON.stringify(validDesign));
  design.flow.transitions.push({ id: 'bad', from: 's_ghost', to: 's_home', label: 'Ghost', type: 'neutral' });
  const errors = validateFlowCompleteness(design);
  assert.ok(errors.some(function (e) { return e.includes('from') && e.includes('s_ghost'); }));
});

test('detects transition.to pointing to nonexistent screen', function () {
  const design = JSON.parse(JSON.stringify(validDesign));
  design.flow.transitions.push({ id: 'bad', from: 's_home', to: 's_ghost', label: 'Ghost', type: 'neutral' });
  const errors = validateFlowCompleteness(design);
  assert.ok(errors.some(function (e) { return e.includes('to') && e.includes('s_ghost'); }));
});

test('reports multiple errors when both from and to are invalid', function () {
  const design = JSON.parse(JSON.stringify(validDesign));
  design.flow.transitions.push({ id: 'bad', from: 's_x', to: 's_y', type: 'neutral' });
  const errors = validateFlowCompleteness(design);
  assert.ok(errors.length >= 2);
});

test('design with no transitions is valid', function () {
  const design = {
    schema: 'cf-ui-design/1',
    op: 'create',
    project: { name: 'Simple' },
    screens: [{ id: 's1', name: 'Home' }],
    flow: { entryScreen: 's1', transitions: [] },
  };
  const errors = validateFlowCompleteness(design);
  assert.strictEqual(errors.length, 0);
});

test('design with null flow is treated as valid (no transitions to check)', function () {
  const design = { schema: 'cf-ui-design/1', op: 'create', project: {}, screens: [] };
  const errors = validateFlowCompleteness(design);
  assert.strictEqual(errors.length, 0);
});

test('all transition types are handled (success, error, branch, neutral)', function () {
  const design = {
    schema: 'cf-ui-design/1',
    op: 'create',
    project: {},
    screens: [
      { id: 's1', name: 'A' },
      { id: 's2', name: 'B' },
      { id: 's3', name: 'C' },
      { id: 's4', name: 'D' },
    ],
    flow: {
      entryScreen: 's1',
      transitions: [
        { id: 't1', from: 's1', to: 's2', type: 'success' },
        { id: 't2', from: 's1', to: 's3', type: 'error' },
        { id: 't3', from: 's1', to: 's4', type: 'branch' },
        { id: 't4', from: 's2', to: 's1', type: 'neutral' },
      ],
    },
  };
  const errors = validateFlowCompleteness(design);
  assert.strictEqual(errors.length, 0, 'All transition types should be valid: ' + errors.join(', '));
});

// ── Summary ───────────────────────────────────────────────────────────────────

console.log('\n' + (failed === 0 ? '\x1b[32m' : '\x1b[31m') +
  'Results: ' + passed + ' passed, ' + failed + ' failed\x1b[0m\n');

process.exit(failed > 0 ? 1 : 0);
