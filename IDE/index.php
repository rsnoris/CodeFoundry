<?php
$page_title  = 'CodeFoundry IDE – Online Code Editor';
$active_page = 'ide';
$page_styles = <<<'PAGECSS'
/* ── IDE layout ─────────────────────────────────────────── */
.ide-wrapper {
  height: calc(100vh - var(--header-height));
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* ── Toolbar ─────────────────────────────────────────────── */
.ide-toolbar {
  background: var(--navy);
  border-bottom: 1px solid var(--border-color);
  padding: 9px 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
  flex-wrap: wrap;
}

.lang-select-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.lang-select {
  background: var(--navy-3);
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: var(--button-radius);
  padding: 7px 34px 7px 12px;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  min-width: 160px;
  transition: border-color .2s;
}

.lang-select:focus {
  outline: none;
  border-color: var(--primary);
}

.lang-select-arrow {
  position: absolute;
  right: 10px;
  pointer-events: none;
  color: var(--text-muted);
  font-size: 12px;
}

.ide-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 15px;
  border-radius: var(--button-radius);
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: background .2s, color .2s, border-color .2s;
  white-space: nowrap;
}

.ide-btn.run {
  background: #22c55e;
  color: #fff;
}

.ide-btn.run:hover:not(:disabled) {
  background: #16a34a;
}

.ide-btn.run:disabled {
  background: #1e2d1e;
  color: #4ade8077;
  cursor: not-allowed;
}

.ide-btn.ghost {
  background: transparent;
  color: var(--text-muted);
  border: 1px solid var(--border-color);
}

.ide-btn.ghost:hover {
  color: var(--text);
  border-color: var(--text-muted);
}

.toolbar-sep {
  width: 1px;
  height: 22px;
  background: var(--border-color);
  flex-shrink: 0;
}

.toolbar-hint {
  margin-left: auto;
  color: var(--text-subtle);
  font-size: 12px;
  display: flex;
  align-items: center;
  gap: 4px;
}

kbd {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: 4px;
  padding: 1px 5px;
  font-size: 11px;
  font-family: monospace;
}

/* ── Workspace ───────────────────────────────────────────── */
.ide-workspace {
  flex: 1;
  display: flex;
  overflow: hidden;
  min-height: 0;
}

.ide-editor-pane {
  flex: 0 0 62%;
  min-width: 200px;
  overflow: hidden;
  position: relative;
}

#monaco-editor {
  width: 100%;
  height: 100%;
}

.ide-divider {
  width: 4px;
  background: var(--border-color);
  cursor: col-resize;
  flex-shrink: 0;
  transition: background .15s;
}

.ide-divider:hover,
.ide-divider.dragging {
  background: var(--primary);
}

/* ── I/O pane ────────────────────────────────────────────── */
.ide-io-pane {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 180px;
  overflow: hidden;
  background: var(--navy-2);
}

.ide-pane-section {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.ide-pane-section.stdin-section {
  flex: 0 0 28%;
  border-bottom: 1px solid var(--border-color);
}

.ide-pane-section.output-section {
  flex: 1;
  min-height: 0;
}

.pane-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 5px 12px;
  background: var(--navy);
  border-bottom: 1px solid var(--border-color);
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .06em;
  color: var(--text-muted);
  flex-shrink: 0;
  gap: 8px;
}

.pane-header-right {
  display: flex;
  align-items: center;
  gap: 8px;
}

.pane-badge {
  font-size: 11px;
  font-weight: 600;
  padding: 2px 7px;
  border-radius: 4px;
  text-transform: none;
  letter-spacing: 0;
}

.pane-badge.exit-ok  { background: #16a34a22; color: #4ade80; }
.pane-badge.exit-err { background: #dc262622; color: #f87171; }
.pane-badge.running  { background: #1d4ed822; color: #60a5fa; }

.pane-clear-btn {
  padding: 2px 8px;
  font-size: 11px;
  font-weight: 600;
}

.stdin-textarea {
  flex: 1;
  background: #0d1117;
  color: var(--text);
  border: none;
  resize: none;
  padding: 10px 14px;
  font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', 'Consolas', monospace;
  font-size: 13px;
  line-height: 1.55;
  outline: none;
  width: 100%;
  box-sizing: border-box;
}

.stdin-textarea::placeholder {
  color: var(--text-subtle);
}

/* ── Output panel ─────────────────────────────────────────── */
.output-content {
  flex: 1;
  overflow: auto;
  padding: 12px 14px;
  font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', 'Consolas', monospace;
  font-size: 13px;
  line-height: 1.6;
  white-space: pre-wrap;
  word-break: break-word;
  color: #e2e8f0;
  min-height: 0;
  background: #0d1117;
}

.output-content.empty {
  color: var(--text-subtle);
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  font-style: italic;
}

.output-stderr { color: #f87171; }

.output-section-label {
  font-size: 10px;
  text-transform: uppercase;
  letter-spacing: .07em;
  font-family: 'Inter', sans-serif;
  margin-bottom: 5px;
  font-style: normal;
}

.output-section-label.stderr-label { color: #f87171; }
.output-section-label.stdout-label { color: #4ade80; }

.output-divider {
  margin: 10px 0;
  border: none;
  border-top: 1px solid var(--border-color);
}

/* ── Spinner ──────────────────────────────────────────────── */
.spinner {
  display: inline-block;
  width: 13px;
  height: 13px;
  border: 2px solid rgba(255,255,255,.2);
  border-top-color: #fff;
  border-radius: 50%;
  animation: cf-spin .7s linear infinite;
  flex-shrink: 0;
}

@keyframes cf-spin { to { transform: rotate(360deg); } }

/* ── Responsive ───────────────────────────────────────────── */
@media (max-width: 768px) {
  .ide-workspace { flex-direction: column; }

  .ide-editor-pane { flex: 0 0 50%; }

  .ide-divider {
    width: 100%;
    height: 4px;
    cursor: row-resize;
  }

  .ide-io-pane { flex: 1; }

  .toolbar-hint { display: none; }
}

/* ── CodeGen modal ───────────────────────────────────────────── */
.codegen-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.6);
  z-index: 1000;
  align-items: center;
  justify-content: center;
}

.codegen-overlay.open {
  display: flex;
}

.codegen-modal {
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 24px;
  width: min(560px, 95vw);
  display: flex;
  flex-direction: column;
  gap: 14px;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
}

.codegen-modal h2 {
  font-size: 16px;
  font-weight: 700;
  color: var(--text);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.codegen-modal h2 iconify-icon {
  color: var(--primary);
}

.codegen-prompt {
  width: 100%;
  background: #0d1117;
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 10px 12px;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  line-height: 1.55;
  resize: vertical;
  min-height: 90px;
  box-sizing: border-box;
  outline: none;
  transition: border-color .2s;
}

.codegen-prompt:focus {
  border-color: var(--primary);
}

.codegen-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
}

.ide-btn.primary {
  background: var(--primary);
  color: #fff;
}

.ide-btn.primary:hover:not(:disabled) {
  background: #0ea5e9;
}

.ide-btn.primary:disabled {
  background: #1e3a4a;
  color: #38bdf866;
  cursor: not-allowed;
}

.codegen-hint {
  font-size: 12px;
  color: var(--text-subtle);
  margin: 0;
}

/* ── Pro toggle ─────────────────────────────────────── */
.pro-toggle-wrap {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--text-muted);
  user-select: none;
}

.pro-toggle {
  position: relative;
  width: 32px;
  height: 18px;
  cursor: pointer;
  flex-shrink: 0;
}

.pro-toggle input { display: none; }

.pro-toggle-slider {
  position: absolute;
  inset: 0;
  background: #1e293b;
  border: 1px solid var(--border-color);
  border-radius: 9px;
  transition: background .2s, border-color .2s;
}

.pro-toggle-slider::before {
  content: '';
  position: absolute;
  width: 12px;
  height: 12px;
  top: 2px;
  left: 2px;
  background: #475569;
  border-radius: 50%;
  transition: transform .2s, background .2s;
}

.pro-toggle input:checked + .pro-toggle-slider {
  background: rgba(14,165,233,.15);
  border-color: var(--primary);
}

.pro-toggle input:checked + .pro-toggle-slider::before {
  transform: translateX(14px);
  background: var(--primary);
}

/* ── CodeGen controls row ────────────────────────────── */
.codegen-controls {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
}

/* ── Chat history ────────────────────────────────────── */
.chat-history {
  max-height: 200px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 8px;
  scrollbar-width: thin;
}

.chat-history:empty { display: none; }

.chat-bubble {
  font-size: 13px;
  line-height: 1.5;
  padding: 8px 11px;
  border-radius: 8px;
  max-width: 96%;
  word-break: break-word;
}

.chat-bubble.user {
  background: #1e293b;
  color: var(--text);
  align-self: flex-end;
  border: 1px solid var(--border-color);
}

.chat-bubble.assistant {
  background: #0d1117;
  color: #4ade80;
  font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
  font-size: 11.5px;
  align-self: flex-start;
  border: 1px solid rgba(22,163,74,.25);
  white-space: pre-wrap;
}

/* ── History dropdown ────────────────────────────────── */
.codegen-history-row {
  display: flex;
  align-items: center;
  gap: 6px;
}

.history-select {
  flex: 1;
  background: #0d1117;
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 5px 8px;
  font-size: 12px;
  font-family: 'Inter', sans-serif;
  cursor: pointer;
  outline: none;
}

.history-select:focus { border-color: var(--primary); }
.history-select option { background: #0d1117; }

.chat-clear-btn {
  font-size: 11px;
  color: var(--text-subtle);
  background: none;
  border: none;
  cursor: pointer;
  padding: 0;
  text-decoration: underline;
}

.chat-clear-btn:hover { color: var(--text-muted); }

/* ── Inline-edit modal ───────────────────────────────── */
.inline-edit-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.6);
  z-index: 1100;
  align-items: center;
  justify-content: center;
}

.inline-edit-overlay.open { display: flex; }

.inline-edit-modal {
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px;
  width: min(520px, 95vw);
  display: flex;
  flex-direction: column;
  gap: 12px;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
}

.inline-edit-modal h2 {
  font-size: 15px;
  font-weight: 700;
  color: var(--text);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 7px;
}

.inline-edit-modal h2 iconify-icon { color: #a78bfa; }

.inline-edit-preview {
  background: #0d1117;
  border: 1px solid var(--border-color);
  border-radius: 6px;
  padding: 8px 10px;
  font-family: 'JetBrains Mono', 'Fira Code', 'Consolas', monospace;
  font-size: 11.5px;
  color: #94a3b8;
  max-height: 80px;
  overflow: auto;
  white-space: pre;
  word-break: break-all;
}

/* ── Explain modal ───────────────────────────────────── */
.explain-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.6);
  z-index: 1050;
  align-items: center;
  justify-content: center;
}

.explain-overlay.open { display: flex; }

.explain-modal {
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 24px;
  width: min(640px, 95vw);
  max-height: 80vh;
  display: flex;
  flex-direction: column;
  gap: 14px;
  box-shadow: 0 20px 60px rgba(0,0,0,.5);
}

.explain-modal h2 {
  font-size: 16px;
  font-weight: 700;
  color: var(--text);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.explain-modal h2 iconify-icon { color: #34d399; }

.explain-content {
  flex: 1;
  overflow-y: auto;
  font-size: 14px;
  line-height: 1.75;
  color: var(--text);
  white-space: pre-wrap;
  min-height: 60px;
  scrollbar-width: thin;
}

.explain-content.loading {
  color: var(--text-subtle);
  font-style: italic;
}
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="ide-wrapper">

  <!-- ── Toolbar ─────────────────────────────────────────── -->
  <div class="ide-toolbar">

    <!-- Language selector -->
    <div class="lang-select-wrapper">
      <select id="langSelect" class="lang-select" aria-label="Programming language">
        <optgroup label="General Purpose">
          <option value="python">Python</option>
          <option value="javascript">JavaScript</option>
          <option value="typescript">TypeScript</option>
          <option value="java">Java</option>
          <option value="c">C</option>
          <option value="c++">C++</option>
          <option value="csharp">C#</option>
          <option value="go">Go</option>
          <option value="rust">Rust</option>
          <option value="php">PHP</option>
          <option value="ruby">Ruby</option>
          <option value="bash">Bash</option>
          <option value="lua">Lua</option>
          <option value="perl">Perl</option>
          <option value="haskell">Haskell</option>
          <option value="scala">Scala</option>
          <option value="r">R</option>
        </optgroup>
        <optgroup label="Mobile Apps">
          <option value="swift">Swift</option>
          <option value="kotlin">Kotlin</option>
          <option value="dart">Dart</option>
        </optgroup>
        <optgroup label="Electrical &amp; Engineering">
          <option value="octave">Octave / MATLAB</option>
          <option value="fortran">Fortran</option>
        </optgroup>
        <optgroup label="Semiconductor &amp; Electronics">
          <option value="verilog">Verilog</option>
          <option value="vhdl">VHDL</option>
        </optgroup>
        <optgroup label="Design Automation / EDA">
          <option value="tcl">Tcl</option>
        </optgroup>
      </select>
      <span class="lang-select-arrow" aria-hidden="true">▾</span>
    </div>

    <!-- Run -->
    <button id="runBtn" class="ide-btn run" aria-label="Run code">
      <iconify-icon icon="lucide:play" aria-hidden="true"></iconify-icon>
      Run
    </button>

    <!-- Generate -->
    <button id="codegenBtn" class="ide-btn ghost" title="Generate code with AI">
      <iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon>
      Generate
    </button>

    <div class="toolbar-sep" aria-hidden="true"></div>

    <!-- Copy -->
    <button id="copyBtn" class="ide-btn ghost" title="Copy code to clipboard">
      <iconify-icon icon="lucide:copy" aria-hidden="true"></iconify-icon>
      Copy
    </button>

    <!-- Reset -->
    <button id="resetBtn" class="ide-btn ghost" title="Reset to starter code">
      <iconify-icon icon="lucide:rotate-ccw" aria-hidden="true"></iconify-icon>
      Reset
    </button>

    <!-- Download -->
    <button id="downloadBtn" class="ide-btn ghost" title="Download code as file">
      <iconify-icon icon="lucide:download" aria-hidden="true"></iconify-icon>
      Download
    </button>

    <!-- Explain -->
    <button id="explainBtn" class="ide-btn ghost" title="Explain code with AI">
      <iconify-icon icon="lucide:book-open" aria-hidden="true"></iconify-icon>
      Explain
    </button>

    <!-- Keyboard hint -->
    <div class="toolbar-hint" aria-hidden="true">
      <kbd>Ctrl</kbd>+<kbd>Enter</kbd> to run
    </div>
  </div>

  <!-- ── Workspace ────────────────────────────────────────── -->
  <div class="ide-workspace" id="workspace">

    <!-- Editor pane -->
    <div class="ide-editor-pane" id="editorPane" aria-label="Code editor">
      <div id="monaco-editor"></div>
    </div>

    <!-- Resizable divider -->
    <div class="ide-divider" id="divider"
         role="separator" aria-orientation="vertical"
         tabindex="0" aria-label="Resize editor/output panes"></div>

    <!-- I/O pane -->
    <div class="ide-io-pane" id="ioPane">

      <!-- Stdin -->
      <div class="ide-pane-section stdin-section">
        <div class="pane-header">
          <span>Input (stdin)</span>
          <div class="pane-header-right">
            <button class="ide-btn ghost pane-clear-btn" id="clearStdinBtn"
                    aria-label="Clear input">Clear</button>
          </div>
        </div>
        <textarea id="stdinInput" class="stdin-textarea"
                  placeholder="Optional program input…" aria-label="Standard input"></textarea>
      </div>

      <!-- Output -->
      <div class="ide-pane-section output-section">
        <div class="pane-header">
          <span>Output</span>
          <div class="pane-header-right">
            <span id="statusBadge" class="pane-badge" style="display:none;" aria-live="polite"></span>
            <button id="fixAiBtn" class="ide-btn ghost pane-clear-btn" title="Fix runtime error with AI" style="display:none;">
              <iconify-icon icon="lucide:wrench" aria-hidden="true"></iconify-icon>
              Fix with AI
            </button>
            <button class="ide-btn ghost pane-clear-btn" id="clearOutputBtn"
                    aria-label="Clear output">Clear</button>
          </div>
        </div>
        <div id="outputPanel" class="output-content empty"
             role="region" aria-label="Execution output" aria-live="polite">
          Run your code to see output here.
        </div>
      </div>

    </div><!-- /io-pane -->
  </div><!-- /workspace -->

<!-- ── CodeGen modal ────────────────────────────────────────────── -->
<div id="codegenOverlay" class="codegen-overlay" role="dialog"
     aria-modal="true" aria-labelledby="codegenTitle">
  <div class="codegen-modal">
    <h2 id="codegenTitle">
      <iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon>
      Generate Code with AI
    </h2>

    <!-- Chat history (multi-turn) -->
    <div id="chatHistory" class="chat-history" aria-live="polite"></div>

    <!-- Prompt history dropdown -->
    <div class="codegen-history-row" id="historyRow" style="display:none;">
      <select id="historySelect" class="history-select" aria-label="Recent prompts">
        <option value="">↑ Recent prompts…</option>
      </select>
    </div>

    <textarea id="codegenPrompt" class="codegen-prompt"
              placeholder="Describe the code you want to generate…&#10;e.g. &quot;Write a function that sorts a list of dictionaries by a given key.&quot;"
              rows="4" aria-label="Code generation prompt"></textarea>

    <!-- Controls row: Pro toggle + hint -->
    <div class="codegen-controls">
      <div class="pro-toggle-wrap">
        <label class="pro-toggle" title="GPT-4o for higher quality (uses more quota)">
          <input type="checkbox" id="proToggle" aria-label="Use Pro model (GPT-4o)">
          <span class="pro-toggle-slider"></span>
        </label>
        <span>Pro <span style="color:var(--primary);font-size:11px;">(GPT-4o)</span></span>
      </div>
      <p class="codegen-hint">Replaces editor content · <kbd>Ctrl</kbd>+<kbd>Enter</kbd></p>
    </div>

    <div class="codegen-actions">
      <button id="chatClearBtn" class="chat-clear-btn" style="display:none;" aria-label="Clear conversation">Clear chat</button>
      <button id="codegenCancelBtn" class="ide-btn ghost">Cancel</button>
      <button id="codegenSubmitBtn" class="ide-btn primary">
        <iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon>
        Generate
      </button>
    </div>
  </div>
</div>

<!-- ── Inline-edit modal ─────────────────────────────────────────────── -->
<div id="inlineEditOverlay" class="inline-edit-overlay" role="dialog"
     aria-modal="true" aria-labelledby="inlineEditTitle">
  <div class="inline-edit-modal">
    <h2 id="inlineEditTitle">
      <iconify-icon icon="lucide:wand-2" aria-hidden="true"></iconify-icon>
      Edit Selection with AI
    </h2>
    <pre id="inlineEditPreview" class="inline-edit-preview"></pre>
    <textarea id="inlineEditPrompt" class="codegen-prompt"
              placeholder="How should the AI modify this selection?&#10;e.g. &quot;Make this async&quot; or &quot;Add input validation&quot;"
              rows="3" aria-label="Edit instruction"></textarea>
    <div class="codegen-actions">
      <button id="inlineEditCancelBtn" class="ide-btn ghost">Cancel</button>
      <button id="inlineEditSubmitBtn" class="ide-btn primary" style="background:#7c3aed;">
        <iconify-icon icon="lucide:wand-2" aria-hidden="true"></iconify-icon>
        Apply Edit
      </button>
    </div>
  </div>
</div>

<!-- ── Explain modal ──────────────────────────────────────────────────── -->
<div id="explainOverlay" class="explain-overlay" role="dialog"
     aria-modal="true" aria-labelledby="explainTitle">
  <div class="explain-modal">
    <h2 id="explainTitle">
      <iconify-icon icon="lucide:book-open" aria-hidden="true"></iconify-icon>
      Code Explanation
    </h2>
    <div id="explainContent" class="explain-content loading">Analyzing…</div>
    <div class="codegen-actions">
      <button id="explainCloseBtn" class="ide-btn ghost">Close</button>
    </div>
  </div>
</div>

</div><!-- /ide-wrapper -->

<!-- ── Monaco Editor (AMD loader from CDN) ──────────────────── -->
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs/loader.js"></script>
<script>
/* ============================================================
   CodeFoundry IDE – client-side logic
   ============================================================ */

'use strict';

/* ── Language registry ──────────────────────────────────── */
const LANGUAGES = {
  python: {
    monaco: 'python', ext: 'py',
    starter: '# Python\nprint("Hello, World!")\n',
  },
  javascript: {
    monaco: 'javascript', ext: 'js',
    starter: '// JavaScript\nconsole.log("Hello, World!");\n',
  },
  typescript: {
    monaco: 'typescript', ext: 'ts',
    starter: '// TypeScript\nconst greet = (name: string): string => `Hello, ${name}!`;\nconsole.log(greet("World"));\n',
  },
  java: {
    monaco: 'java', ext: 'java',
    starter: 'public class Main {\n    public static void main(String[] args) {\n        System.out.println("Hello, World!");\n    }\n}\n',
  },
  c: {
    monaco: 'c', ext: 'c',
    starter: '#include <stdio.h>\n\nint main() {\n    printf("Hello, World!\\n");\n    return 0;\n}\n',
  },
  'c++': {
    monaco: 'cpp', ext: 'cpp',
    starter: '#include <iostream>\n\nint main() {\n    std::cout << "Hello, World!" << std::endl;\n    return 0;\n}\n',
  },
  csharp: {
    monaco: 'csharp', ext: 'cs',
    starter: 'using System;\n\nclass Program {\n    static void Main() {\n        Console.WriteLine("Hello, World!");\n    }\n}\n',
  },
  go: {
    monaco: 'go', ext: 'go',
    starter: 'package main\n\nimport "fmt"\n\nfunc main() {\n    fmt.Println("Hello, World!")\n}\n',
  },
  rust: {
    monaco: 'rust', ext: 'rs',
    starter: 'fn main() {\n    println!("Hello, World!");\n}\n',
  },
  php: {
    monaco: 'php', ext: 'php',
    starter: '<?php\necho "Hello, World!\\n";\n',
  },
  ruby: {
    monaco: 'ruby', ext: 'rb',
    starter: '# Ruby\nputs "Hello, World!"\n',
  },
  swift: {
    monaco: 'swift', ext: 'swift',
    starter: '// Swift\nprint("Hello, World!")\n',
  },
  kotlin: {
    monaco: 'kotlin', ext: 'kt',
    starter: 'fun main() {\n    println("Hello, World!")\n}\n',
  },
  r: {
    monaco: 'r', ext: 'r',
    starter: '# R\ncat("Hello, World!\\n")\n',
  },
  bash: {
    monaco: 'shell', ext: 'sh',
    starter: '#!/bin/bash\necho "Hello, World!"\n',
  },
  lua: {
    monaco: 'lua', ext: 'lua',
    starter: '-- Lua\nprint("Hello, World!")\n',
  },
  perl: {
    monaco: 'perl', ext: 'pl',
    starter: '#!/usr/bin/perl\nuse strict;\nuse warnings;\nprint "Hello, World!\\n";\n',
  },
  haskell: {
    monaco: 'haskell', ext: 'hs',
    starter: '-- Haskell\nmain :: IO ()\nmain = putStrLn "Hello, World!"\n',
  },
  scala: {
    monaco: 'scala', ext: 'scala',
    starter: 'object Main extends App {\n  println("Hello, World!")\n}\n',
  },

  // ── Mobile Apps ──────────────────────────────────────────────────────────
  dart: {
    monaco: 'dart', ext: 'dart',
    starter: '// Dart\nvoid main() {\n  print(\'Hello, World!\');\n}\n',
  },

  // ── Electrical & Engineering ─────────────────────────────────────────────
  octave: {
    monaco: 'plaintext', ext: 'm',
    starter: '% Octave / MATLAB\ndisp(\'Hello, World!\')\n',
  },
  fortran: {
    monaco: 'plaintext', ext: 'f90',
    starter: '! Fortran\nprogram main\n  print *, \'Hello, World!\'\nend program main\n',
  },

  // ── Semiconductor & Electronics ──────────────────────────────────────────
  verilog: {
    monaco: 'systemverilog', ext: 'v',
    starter: '// Verilog\nmodule main;\n  initial begin\n    $display("Hello, World!");\n    $finish;\n  end\nendmodule\n',
  },
  vhdl: {
    monaco: 'plaintext', ext: 'vhd',
    // Top-level entity must be named 'main' for the GHDL runner.
    starter: '-- VHDL\n-- The top-level entity must be named \'main\'.\nlibrary ieee;\nuse ieee.std_logic_1164.all;\n\nentity main is\nend entity main;\n\narchitecture sim of main is\nbegin\n  process\n  begin\n    report "Hello, World!";\n    wait;\n  end process;\nend architecture sim;\n',
  },

  // ── Design Automation / EDA ──────────────────────────────────────────────
  tcl: {
    monaco: 'tcl', ext: 'tcl',
    starter: '# Tcl\nputs "Hello, World!"\n',
  },
};

/* ── State ──────────────────────────────────────────────── */
let editor     = null;
let currentLang = 'python';
let isRunning   = false;
let lastError   = null; // tracks last run error for "Fix with AI"

/* ── Init Monaco ────────────────────────────────────────── */
require.config({
  paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs' },
});

require(['vs/editor/editor.main'], function () {

  monaco.editor.defineTheme('cf-dark', {
    base: 'vs-dark',
    inherit: true,
    rules: [],
    colors: {
      'editor.background':                '#0d1117',
      'editor.foreground':                '#e2e8f0',
      'editorLineNumber.foreground':      '#4a5568',
      'editorLineNumber.activeForeground':'#92a3bb',
      'editorCursor.foreground':          '#18b3ff',
      'editor.selectionBackground':       '#1a3d5c',
      'editor.lineHighlightBackground':   '#161f2f',
      'editorIndentGuide.background':     '#1a2942',
      'editorIndentGuide.activeBackground':'#2d4a7a',
      'editorGutter.background':          '#0d1117',
    },
  });

  editor = monaco.editor.create(document.getElementById('monaco-editor'), {
    value:                 LANGUAGES[currentLang].starter,
    language:              LANGUAGES[currentLang].monaco,
    theme:                 'cf-dark',
    fontSize:              14,
    fontFamily:            "'JetBrains Mono','Fira Code','Cascadia Code','Consolas',monospace",
    fontLigatures:         true,
    lineNumbers:           'on',
    minimap:               { enabled: false },
    scrollBeyondLastLine:  false,
    automaticLayout:       true,
    tabSize:               4,
    insertSpaces:          true,
    wordWrap:              'off',
    renderLineHighlight:   'line',
    bracketPairColorization: { enabled: true },
    padding:               { top: 12, bottom: 12 },
  });

  // Ctrl/Cmd + Enter → run
  editor.addCommand(
    monaco.KeyMod.CtrlCmd | monaco.KeyCode.Enter,
    runCode,
  );

  // Ctrl/Cmd + Shift + I → inline AI edit
  editor.addAction({
    id:    'cf-inline-edit',
    label: 'Edit with AI',
    keybindings: [monaco.KeyMod.CtrlCmd | monaco.KeyMod.Shift | monaco.KeyCode.KeyI],
    contextMenuGroupId: '1_modification',
    contextMenuOrder:   1.5,
    run: function (ed) {
      const sel          = ed.getSelection();
      const selectedText = ed.getModel().getValueInRange(sel);
      openInlineEdit(selectedText || '', sel);
    },
  });
});

/* ── Language switch ────────────────────────────────────── */
document.getElementById('langSelect').addEventListener('change', function () {
  const newLang = this.value;
  if (newLang === currentLang || !editor) return;

  const langData    = LANGUAGES[newLang];
  const currentCode = editor.getValue();
  const oldStarter  = LANGUAGES[currentLang].starter;

  // If the editor still shows the previous starter (or is blank), replace with
  // the new starter; otherwise just switch syntax highlighting.
  if (currentCode === oldStarter || currentCode.trim() === '') {
    editor.setValue(langData.starter);
  }

  monaco.editor.setModelLanguage(editor.getModel(), langData.monaco);
  currentLang = newLang;
  clearOutput();
});

/* ── Toolbar buttons ────────────────────────────────────── */
document.getElementById('runBtn').addEventListener('click', runCode);

document.getElementById('copyBtn').addEventListener('click', async function () {
  if (!editor) return;
  const btn  = this;
  const orig = btn.innerHTML;

  const text = editor.getValue();

  async function doCopy() {
    // Modern Clipboard API
    if (navigator.clipboard && navigator.clipboard.writeText) {
      await navigator.clipboard.writeText(text);
      return;
    }
    // Fallback: create a temporary textarea
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0;';
    document.body.appendChild(ta);
    ta.select();
    const ok = document.execCommand('copy');
    document.body.removeChild(ta);
    if (!ok) throw new Error('execCommand failed');
  }

  try {
    await doCopy();
    btn.innerHTML = '<iconify-icon icon="lucide:check"></iconify-icon> Copied!';
    setTimeout(() => { btn.innerHTML = orig; }, 2000);
  } catch (_) {
    btn.innerHTML = '<iconify-icon icon="lucide:x"></iconify-icon> Copy failed';
    setTimeout(() => { btn.innerHTML = orig; }, 2500);
  }
});

document.getElementById('resetBtn').addEventListener('click', function () {
  if (!editor) return;
  const starter = LANGUAGES[currentLang].starter;
  if (editor.getValue() === starter) return;
  if (confirm('Reset to starter code? Your current code will be lost.')) {
    editor.setValue(starter);
    clearOutput();
  }
});

/* ── Download ───────────────────────────────────────────── */
document.getElementById('downloadBtn').addEventListener('click', function () {
  if (!editor) return;
  const code = editor.getValue();
  if (!code) return;
  const lang     = LANGUAGES[currentLang];
  const filename = 'main.' + lang.ext;
  const blob     = new Blob([code], { type: 'text/plain' });
  const url      = URL.createObjectURL(blob);
  const a        = document.createElement('a');
  a.href         = url;
  a.download     = filename;
  a.click();
  URL.revokeObjectURL(url);
});

document.getElementById('clearStdinBtn').addEventListener('click', function () {
  document.getElementById('stdinInput').value = '';
});

document.getElementById('clearOutputBtn').addEventListener('click', clearOutput);

/* ── Run ────────────────────────────────────────────────── */
async function runCode() {
  if (isRunning || !editor) return;

  const code = editor.getValue().trim();
  if (!code) {
    showOutput(null, null, null, 'Please write some code before running.');
    return;
  }

  const stdin = document.getElementById('stdinInput').value;

  setRunning(true);

  try {
    const res = await fetch('/IDE/run.php', {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify({ language: currentLang, code, stdin }),
    });

    const data = await res.json();

    if (!res.ok) {
      showOutput(null, null, null, data.error || data.message || 'Execution failed.');
      return;
    }

    const run     = data.run;
    const compile = data.compile;

    let stdout   = run?.stdout     || '';
    let stderr   = run?.stderr     || '';
    const exitCode = run?.code     ?? null;

    // Surface compile-stage errors (e.g. Java, C, C++)
    if (compile && compile.code !== 0 && compile.stderr) {
      stderr = (compile.stderr + (stderr ? '\n' + stderr : '')).trim();
    }

    showOutput(stdout, stderr, exitCode, null);

  } catch (err) {
    showOutput(null, null, null, 'Network error: ' + err.message);
  } finally {
    setRunning(false);
  }
}

/* ── Output rendering ───────────────────────────────────── */
function showOutput(stdout, stderr, exitCode, errorMsg) {
  const panel    = document.getElementById('outputPanel');
  const badge    = document.getElementById('statusBadge');
  const fixBtn   = document.getElementById('fixAiBtn');

  panel.className = 'output-content';
  panel.innerHTML = '';

  if (errorMsg) {
    const el = document.createElement('span');
    el.className   = 'output-stderr';
    el.textContent = '⚠ ' + errorMsg;
    panel.appendChild(el);
    badge.style.display  = 'none';
    fixBtn.style.display = 'none';
    lastError = null;
    return;
  }

  const hasOut = stdout && stdout.length > 0;
  const hasErr = stderr && stderr.length > 0;

  if (!hasOut && !hasErr) {
    const el = document.createElement('span');
    el.style.cssText = 'color:var(--text-subtle);font-style:italic;';
    el.textContent   = '(no output)';
    panel.appendChild(el);
  }

  if (hasOut) {
    const label = document.createElement('div');
    label.className   = 'output-section-label stdout-label';
    label.textContent = 'stdout';
    panel.appendChild(label);

    const el = document.createElement('span');
    el.textContent = stdout;
    panel.appendChild(el);
  }

  if (hasErr) {
    if (hasOut) {
      const hr = document.createElement('hr');
      hr.className = 'output-divider';
      panel.appendChild(hr);
    }

    const label = document.createElement('div');
    label.className   = 'output-section-label stderr-label';
    label.textContent = 'stderr';
    panel.appendChild(label);

    const el = document.createElement('span');
    el.className   = 'output-stderr';
    el.textContent = stderr;
    panel.appendChild(el);
  }

  // Status badge
  badge.style.display = 'inline-block';
  if (exitCode === 0) {
    badge.className   = 'pane-badge exit-ok';
    badge.textContent = 'Exit 0 ✓';
    fixBtn.style.display = 'none';
    lastError = null;
  } else if (exitCode !== null) {
    badge.className   = 'pane-badge exit-err';
    badge.textContent = 'Exit ' + exitCode;
    // Show fix button and record error context
    lastError = { code: editor ? editor.getValue() : '', stderr: stderr || '', exitCode };
    fixBtn.style.display = '';
  } else {
    badge.style.display  = 'none';
    fixBtn.style.display = 'none';
    lastError = null;
  }
}

function clearOutput() {
  const panel  = document.getElementById('outputPanel');
  const badge  = document.getElementById('statusBadge');
  const fixBtn = document.getElementById('fixAiBtn');

  panel.textContent = 'Run your code to see output here.';
  panel.className   = 'output-content empty';

  badge.style.display  = 'none';
  badge.className      = 'pane-badge';
  fixBtn.style.display = 'none';
  lastError = null;
}

function setRunning(on) {
  isRunning = on;
  const btn   = document.getElementById('runBtn');
  const badge = document.getElementById('statusBadge');

  if (on) {
    btn.disabled   = true;
    btn.innerHTML  = '<span class="spinner"></span> Running…';

    badge.className   = 'pane-badge running';
    badge.textContent = 'Running…';
    badge.style.display = 'inline-block';

    const panel = document.getElementById('outputPanel');
    panel.className  = 'output-content';
    panel.innerHTML  = '<span style="color:var(--text-subtle);font-style:italic;">Executing…</span>';
  } else {
    btn.disabled  = false;
    btn.innerHTML = '<iconify-icon icon="lucide:play" aria-hidden="true"></iconify-icon> Run';
  }
}

/* ── Shared SSE streaming helper ────────────────────────── */
/**
 * Stream a codegen.php SSE response, calling onDelta(str) for each token.
 * Returns the full accumulated text, stripped of markdown fences.
 */
async function streamCodegen(payload, onDelta) {
  const res = await fetch('/IDE/codegen.php', {
    method:  'POST',
    headers: { 'Content-Type': 'application/json' },
    body:    JSON.stringify({ ...payload, stream: true }),
  });

  if (!res.ok) {
    let errMsg = 'Unknown error';
    try { errMsg = (await res.json()).error || errMsg; } catch (_) {}
    throw new Error(errMsg);
  }

  const reader  = res.body.getReader();
  const decoder = new TextDecoder();
  let buffer = '';
  let accumulated = '';

  while (true) {
    const { done, value } = await reader.read();
    if (done) break;
    buffer += decoder.decode(value, { stream: true });
    const lines = buffer.split('\n');
    buffer = lines.pop(); // keep incomplete last line
    for (const line of lines) {
      if (!line.startsWith('data: ')) continue;
      const raw = line.slice(6).trim();
      if (raw === '[DONE]') break;
      try {
        const parsed = JSON.parse(raw);
        if (parsed.error) throw new Error(parsed.error.message || String(parsed.error));
        const delta = parsed.choices?.[0]?.delta?.content;
        if (delta) {
          accumulated += delta;
          onDelta(accumulated);
        }
      } catch (parseErr) {
        if (parseErr.message && !parseErr.message.startsWith('JSON')) throw parseErr;
      }
    }
  }

  // Strip accidental markdown fences
  return accumulated
    .replace(/^```[a-zA-Z]*\n?/, '')
    .replace(/\n?```$/, '')
    .trim();
}

/* ── CodeGen (multi-turn, streaming, history, Pro toggle) ── */
(function () {
  const HISTORY_KEY = 'cf_codegen_history';
  const HISTORY_MAX = 20;

  const overlay      = document.getElementById('codegenOverlay');
  const promptEl     = document.getElementById('codegenPrompt');
  const submitBtn    = document.getElementById('codegenSubmitBtn');
  const cancelBtn    = document.getElementById('codegenCancelBtn');
  const codegenBtn   = document.getElementById('codegenBtn');
  const historyRow   = document.getElementById('historyRow');
  const histSelect   = document.getElementById('historySelect');
  const chatHistEl   = document.getElementById('chatHistory');
  const chatClearBtn = document.getElementById('chatClearBtn');
  const proToggle    = document.getElementById('proToggle');

  // Conversation history (user/assistant turns, no system message stored here)
  let conversation = [];

  // ── localStorage history helpers ──────────────────────────
  function loadHistory() {
    try { return JSON.parse(localStorage.getItem(HISTORY_KEY) || '[]'); }
    catch (_) { return []; }
  }

  function saveToHistory(text) {
    const hist = loadHistory().filter(h => h !== text);
    hist.unshift(text);
    if (hist.length > HISTORY_MAX) hist.length = HISTORY_MAX;
    try { localStorage.setItem(HISTORY_KEY, JSON.stringify(hist)); } catch (_) {}
  }

  function refreshHistoryDropdown() {
    const hist = loadHistory();
    histSelect.innerHTML = '<option value="">↑ Recent prompts…</option>';
    hist.forEach(h => {
      const opt = document.createElement('option');
      opt.value = h;
      opt.textContent = h.length > 65 ? h.slice(0, 62) + '…' : h;
      histSelect.appendChild(opt);
    });
    historyRow.style.display = hist.length > 0 ? '' : 'none';
  }

  histSelect.addEventListener('change', function () {
    if (this.value) { promptEl.value = this.value; promptEl.focus(); this.value = ''; }
  });

  // ── Chat history rendering ────────────────────────────────
  function renderChatHistory() {
    chatHistEl.innerHTML = '';
    conversation.forEach(msg => {
      const div = document.createElement('div');
      div.className = 'chat-bubble ' + (msg.role === 'user' ? 'user' : 'assistant');
      const preview = msg.content.length > 220 ? msg.content.slice(0, 217) + '…' : msg.content;
      div.textContent = preview;
      chatHistEl.appendChild(div);
    });
    if (chatHistEl.children.length > 0) {
      chatHistEl.scrollTop = chatHistEl.scrollHeight;
      chatClearBtn.style.display = '';
    } else {
      chatClearBtn.style.display = 'none';
    }
  }

  chatClearBtn.addEventListener('click', function () {
    conversation = [];
    renderChatHistory();
  });

  // ── Open / close ──────────────────────────────────────────
  function openModal() {
    refreshHistoryDropdown();
    renderChatHistory();
    promptEl.value = '';
    overlay.classList.add('open');
    promptEl.focus();
  }

  function closeModal() {
    overlay.classList.remove('open');
    codegenBtn.focus();
  }

  codegenBtn.addEventListener('click', openModal);
  cancelBtn.addEventListener('click', closeModal);

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
  });

  // ── Generate ──────────────────────────────────────────────
  async function generateCode() {
    const text = promptEl.value.trim();
    if (!text) { promptEl.focus(); return; }

    saveToHistory(text);

    submitBtn.disabled  = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Generating…';

    // Build payload
    const payload = { language: currentLang, mode: 'generate', pro: proToggle.checked };

    if (conversation.length > 0) {
      // Send full conversation + new user turn
      payload.messages = [...conversation, { role: 'user', content: text }];
    } else {
      payload.prompt = text;
    }

    try {
      if (editor) editor.setValue('');
      const clean = await streamCodegen(payload, (acc) => {
        if (editor) editor.setValue(acc);
      });
      if (editor) { editor.setValue(clean); editor.focus(); }

      // Record exchange in conversation history
      conversation.push({ role: 'user',      content: text  });
      conversation.push({ role: 'assistant', content: clean });
      renderChatHistory();

      promptEl.value = '';
      closeModal();
    } catch (err) {
      alert('CodeGen error: ' + err.message);
    } finally {
      submitBtn.disabled  = false;
      submitBtn.innerHTML = '<iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon> Generate';
    }
  }

  submitBtn.addEventListener('click', generateCode);

  promptEl.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
      e.preventDefault();
      generateCode();
    }
  });
}());

/* ── Fix with AI ────────────────────────────────────────── */
(function () {
  const fixBtn = document.getElementById('fixAiBtn');
  const origLabel = '<iconify-icon icon="lucide:wrench" aria-hidden="true"></iconify-icon> Fix with AI';

  fixBtn.addEventListener('click', async function () {
    if (!lastError || !editor) return;

    fixBtn.disabled  = true;
    fixBtn.innerHTML = '<span class="spinner"></span> Fixing…';

    const { code, stderr } = lastError;

    try {
      editor.setValue('');
      const clean = await streamCodegen({
        language:     currentLang,
        mode:         'fix',
        prompt:       code,
        error_output: stderr,
      }, (acc) => {
        editor.setValue(acc);
      });
      editor.setValue(clean);
      editor.focus();
      clearOutput();
    } catch (err) {
      alert('Fix error: ' + err.message);
    } finally {
      fixBtn.disabled  = false;
      fixBtn.innerHTML = origLabel;
    }
  });
}());

/* ── Explain ────────────────────────────────────────────── */
(function () {
  const explainBtn   = document.getElementById('explainBtn');
  const overlay      = document.getElementById('explainOverlay');
  const contentEl    = document.getElementById('explainContent');
  const closeBtn     = document.getElementById('explainCloseBtn');

  function closeModal() { overlay.classList.remove('open'); }

  closeBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
  });

  explainBtn.addEventListener('click', async function () {
    if (!editor) return;

    // Use selected text if any, otherwise full editor content
    const sel      = editor.getSelection();
    const selText  = editor.getModel().getValueInRange(sel).trim();
    const codeText = selText || editor.getValue().trim();
    if (!codeText) return;

    contentEl.textContent = 'Analyzing…';
    contentEl.className   = 'explain-content loading';
    overlay.classList.add('open');

    try {
      let fullText = '';
      const clean = await streamCodegen({
        language: currentLang,
        mode:     'explain',
        prompt:   codeText,
      }, (acc) => {
        contentEl.textContent = acc;
        contentEl.className   = 'explain-content';
        fullText = acc;
      });
      contentEl.textContent = clean || fullText;
      contentEl.className   = 'explain-content';
    } catch (err) {
      contentEl.textContent = '⚠ ' + err.message;
      contentEl.className   = 'explain-content';
    }
  });
}());

/* ── Inline edit ────────────────────────────────────────── */
let _inlineSel = null; // current Monaco selection for inline edit

function openInlineEdit(selectedText, selection) {
  _inlineSel = selection;
  const preview  = document.getElementById('inlineEditPreview');
  const promptEl = document.getElementById('inlineEditPrompt');
  const overlay  = document.getElementById('inlineEditOverlay');

  preview.textContent = selectedText.length > 0
    ? (selectedText.length > 400 ? selectedText.slice(0, 397) + '…' : selectedText)
    : '(entire file)';
  promptEl.value = '';
  overlay.classList.add('open');
  promptEl.focus();
}

(function () {
  const overlay   = document.getElementById('inlineEditOverlay');
  const promptEl  = document.getElementById('inlineEditPrompt');
  const submitBtn = document.getElementById('inlineEditSubmitBtn');
  const cancelBtn = document.getElementById('inlineEditCancelBtn');
  const origLabel = '<iconify-icon icon="lucide:wand-2" aria-hidden="true"></iconify-icon> Apply Edit';

  function closeModal() {
    overlay.classList.remove('open');
    if (editor) editor.focus();
  }

  cancelBtn.addEventListener('click', closeModal);
  overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal(); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
  });

  promptEl.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
      e.preventDefault();
      applyEdit();
    }
  });

  submitBtn.addEventListener('click', applyEdit);

  async function applyEdit() {
    const instruction = promptEl.value.trim();
    if (!instruction || !editor) return;

    const sel          = _inlineSel;
    const selectedText = sel ? editor.getModel().getValueInRange(sel).trim() : '';

    submitBtn.disabled  = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Applying…';

    try {
      const clean = await streamCodegen({
        language:  currentLang,
        mode:      'edit',
        selection: selectedText || editor.getValue(),
        prompt:    instruction,
      }, () => {});

      if (selectedText && sel) {
        editor.executeEdits('cf-inline-edit', [{ range: sel, text: clean }]);
      } else {
        editor.setValue(clean);
      }
      editor.focus();
      closeModal();
    } catch (err) {
      alert('Edit error: ' + err.message);
    } finally {
      submitBtn.disabled  = false;
      submitBtn.innerHTML = origLabel;
    }
  }
}());

/* ── Resizable divider ──────────────────────────────────── */
(function () {
  const divider    = document.getElementById('divider');
  const editorPane = document.getElementById('editorPane');
  const workspace  = document.getElementById('workspace');

  let dragging = false;
  let startX, startW;

  function onMoveH(e) {
    if (!dragging) return;
    const dx       = e.clientX - startX;
    const maxW     = workspace.getBoundingClientRect().width - 204;
    const newWidth = Math.max(200, Math.min(startW + dx, maxW));
    editorPane.style.flex  = 'none';
    editorPane.style.width = newWidth + 'px';
  }

  function onUp() {
    if (!dragging) return;
    dragging = false;
    divider.classList.remove('dragging');
    document.body.style.userSelect = '';
    document.body.style.cursor     = '';
  }

  divider.addEventListener('mousedown', function (e) {
    dragging = true;
    startX   = e.clientX;
    startW   = editorPane.getBoundingClientRect().width;
    divider.classList.add('dragging');
    document.body.style.userSelect = 'none';
    document.body.style.cursor     = 'col-resize';
    e.preventDefault();
  });

  document.addEventListener('mousemove', onMoveH);
  document.addEventListener('mouseup',   onUp);

  // Touch support
  divider.addEventListener('touchstart', function (e) {
    dragging = true;
    startX   = e.touches[0].clientX;
    startW   = editorPane.getBoundingClientRect().width;
    divider.classList.add('dragging');
  }, { passive: true });

  document.addEventListener('touchmove', function (e) {
    if (!dragging) return;
    const dx       = e.touches[0].clientX - startX;
    const maxW     = workspace.getBoundingClientRect().width - 204;
    const newWidth = Math.max(200, Math.min(startW + dx, maxW));
    editorPane.style.flex  = 'none';
    editorPane.style.width = newWidth + 'px';
  }, { passive: true });

  document.addEventListener('touchend', onUp);
}());
</script>

<?php
$page_scripts = '';
require_once dirname(__DIR__) . '/includes/footer.php';
?>
