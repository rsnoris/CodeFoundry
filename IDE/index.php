<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/lib/CodeGenProvider.php';

// Build a minimal provider/model list for the client-side selector.
$_cf_providers_js = [];
foreach (CodeGenProvider::all() as $pid => $pdata) {
    if (!$pdata['available']) continue;
    $models = [];
    foreach ($pdata['models'] as $m) {
        $models[] = ['id' => $m['id'], 'label' => $m['label']];
    }
    $_cf_providers_js[] = [
        'id'            => $pid,
        'label'         => $pdata['label'],
        'opensource'    => !empty($pdata['opensource']),
        'default_model' => $pdata['default_model'],
        'models'        => $models,
    ];
}

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

/* ── Explain modal ─────────────────────────────────────────────── */
.explain-modal {
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 24px;
  width: min(680px, 95vw);
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

.explain-modal h2 iconify-icon {
  color: #a78bfa;
}

.explain-body {
  flex: 1;
  overflow-y: auto;
  font-size: 14px;
  line-height: 1.7;
  color: var(--text);
  white-space: pre-wrap;
  word-break: break-word;
  min-height: 0;
}

.explain-loading {
  color: var(--text-subtle);
  font-style: italic;
  display: flex;
  align-items: center;
  gap: 8px;
}

/* ── CodeGen mode tabs ─────────────────────────────────────────── */
.codegen-tabs {
  display: flex;
  gap: 4px;
  background: #0d1117;
  border-radius: 8px;
  padding: 4px;
}

.codegen-tab {
  flex: 1;
  padding: 6px 10px;
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-muted);
  background: transparent;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background .15s, color .15s;
  white-space: nowrap;
}

.codegen-tab.active {
  background: var(--navy-3);
  color: var(--text);
}

.codegen-tab:hover:not(.active) {
  color: var(--text);
}

/* ── Fix button in output pane ─────────────────────────────────── */
.fix-ai-btn {
  display: none;
}

.fix-ai-btn.visible {
  display: inline-flex;
}

/* ── Insert mode selector ──────────────────────────────────────── */
.codegen-insert-row {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  color: var(--text-muted);
}

.codegen-insert-row label {
  display: flex;
  align-items: center;
  gap: 5px;
  cursor: pointer;
  user-select: none;
}

.codegen-insert-row input[type="radio"] {
  accent-color: var(--primary);
  cursor: pointer;
}

/* ── AI model selector ─────────────────────────────────────────── */
.ai-model-select-wrapper {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  color: var(--text-subtle);
}

.ai-model-select {
  background: var(--navy-3);
  color: var(--text-muted);
  border: 1px solid var(--border-color);
  border-radius: var(--button-radius);
  padding: 5px 28px 5px 10px;
  font-family: 'Inter', sans-serif;
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  max-width: 220px;
  transition: border-color .2s, color .2s;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%2392a3bb'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 9px center;
}

.ai-model-select:focus {
  outline: none;
  border-color: var(--primary);
  color: var(--text);
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

    <!-- Improve -->
    <button id="improveBtn" class="ide-btn ghost" title="Improve existing code with AI">
      <iconify-icon icon="lucide:wand-2" aria-hidden="true"></iconify-icon>
      Improve
    </button>

    <!-- Explain -->
    <button id="explainBtn" class="ide-btn ghost" title="Explain the current code with AI">
      <iconify-icon icon="lucide:book-open" aria-hidden="true"></iconify-icon>
      Explain
    </button>

    <!-- AI model selector -->
    <div class="ai-model-select-wrapper" title="AI model for Generate/Improve/Explain/Fix">
      <iconify-icon icon="lucide:cpu" aria-hidden="true"></iconify-icon>
      <select id="aiModelSelect" class="ai-model-select" aria-label="AI model">
        <option value="">Loading…</option>
      </select>
    </div>

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
            <button id="fixBtn" class="ide-btn ghost pane-clear-btn fix-ai-btn"
                    aria-label="Fix errors with AI" title="Fix errors with AI">
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
    <!-- Mode tabs: Generate / Improve -->
    <div class="codegen-tabs" role="tablist">
      <button class="codegen-tab active" id="tabGenerate" role="tab"
              aria-selected="true" aria-controls="codegenTabPanel">
        <iconify-icon icon="lucide:sparkles" width="13" aria-hidden="true"></iconify-icon>
        Generate new
      </button>
      <button class="codegen-tab" id="tabImprove" role="tab"
              aria-selected="false" aria-controls="codegenTabPanel">
        <iconify-icon icon="lucide:wand-2" width="13" aria-hidden="true"></iconify-icon>
        Improve existing
      </button>
    </div>
    <div id="codegenTabPanel">
      <textarea id="codegenPrompt" class="codegen-prompt"
                placeholder="Describe the code you want to generate…&#10;e.g. "Write a function that sorts a list of dictionaries by a given key.""
                rows="4" aria-label="Code generation prompt"></textarea>
    </div>
    <!-- Insert mode -->
    <div class="codegen-insert-row" id="insertModeRow">
      <span>Insert:</span>
      <label>
        <input type="radio" name="insertMode" value="replace" checked>
        Replace all
      </label>
      <label>
        <input type="radio" name="insertMode" value="cursor">
        At cursor
      </label>
    </div>
    <p class="codegen-hint" id="codegenHint">The generated code will replace the current editor content.</p>
    <div class="codegen-actions">
      <button id="codegenCancelBtn" class="ide-btn ghost">Cancel</button>
      <button id="codegenSubmitBtn" class="ide-btn primary">
        <iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon>
        Generate
      </button>
    </div>
  </div>
</div>

<!-- ── Explain modal ────────────────────────────────────────────── -->
<div id="explainOverlay" class="codegen-overlay" role="dialog"
     aria-modal="true" aria-labelledby="explainTitle">
  <div class="explain-modal">
    <h2 id="explainTitle">
      <iconify-icon icon="lucide:book-open" aria-hidden="true"></iconify-icon>
      Code Explanation
    </h2>
    <div id="explainBody" class="explain-body">
      <div class="explain-loading">
        <span class="spinner"></span> Generating explanation…
      </div>
    </div>
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

/* ── Available AI providers (injected from PHP) ─────────── */
const CF_PROVIDERS = <?= json_encode($_cf_providers_js, JSON_UNESCAPED_UNICODE) ?>;

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

/* ── AI model selector ──────────────────────────────────── */
(function () {
  const sel = document.getElementById('aiModelSelect');
  if (!sel || !CF_PROVIDERS.length) {
    if (sel) { sel.closest('.ai-model-select-wrapper').style.display = 'none'; }
    return;
  }

  sel.innerHTML = '';
  CF_PROVIDERS.forEach(function (p) {
    const group = document.createElement('optgroup');
    group.label = p.label + (p.opensource ? ' ✦' : '');
    p.models.forEach(function (m) {
      const opt = document.createElement('option');
      opt.value       = p.id + ':' + m.id;
      opt.textContent = m.label;
      group.appendChild(opt);
    });
    sel.appendChild(group);
  });

  // Restore from localStorage
  const saved = localStorage.getItem('cf_ai_model');
  let hasValidSaved = false;
  if (saved) {
    // Verify the saved value is still valid (provider may have been removed)
    hasValidSaved = Array.from(sel.options).some(o => o.value === saved);
    if (hasValidSaved) sel.value = saved;
  }

  if (!hasValidSaved) {
    const maybeOpenAIProvider = CF_PROVIDERS.find(p => p.id === 'openai');
    if (maybeOpenAIProvider && maybeOpenAIProvider.default_model) {
      const preferred = 'openai:' + maybeOpenAIProvider.default_model;
      const preferredExists = Array.from(sel.options).some(o => o.value === preferred);
      if (preferredExists) {
        sel.value = preferred;
      }
    }
  }

  sel.addEventListener('change', function () {
    localStorage.setItem('cf_ai_model', sel.value);
  });
})();

/** Return {provider, model} from the toolbar selector. */
function getAiSelection() {
  const sel = document.getElementById('aiModelSelect');
  const val = sel ? sel.value : '';
  if (!val) return {};
  const sep = val.indexOf(':');
  return sep > 0
    ? { provider: val.slice(0, sep), model: val.slice(sep + 1) }
    : {};
}

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

  // ── Restore generated code from Generate page ──────────
  (function () {
    const savedCode = sessionStorage.getItem('cf_generated_code');
    const savedLang = sessionStorage.getItem('cf_generated_language');
    if (savedCode) {
      sessionStorage.removeItem('cf_generated_code');
      sessionStorage.removeItem('cf_generated_language');
      if (savedLang && LANGUAGES[savedLang]) {
        document.getElementById('langSelect').value = savedLang;
        monaco.editor.setModelLanguage(editor.getModel(), LANGUAGES[savedLang].monaco);
        currentLang = savedLang;
      }
      editor.setValue(savedCode);
    }
  })();
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
  const panel = document.getElementById('outputPanel');
  const badge = document.getElementById('statusBadge');

  panel.className = 'output-content';
  panel.innerHTML = '';

  if (errorMsg) {
    const el = document.createElement('span');
    el.className   = 'output-stderr';
    el.textContent = '⚠ ' + errorMsg;
    panel.appendChild(el);
    badge.style.display = 'none';
    document.getElementById('fixBtn').classList.add('visible');
    return;
  }

  const hasOut = stdout && stdout.length > 0;
  const hasErr = stderr && stderr.length > 0;

  // Show "Fix with AI" button when there is stderr
  document.getElementById('fixBtn').classList.toggle('visible', hasErr);

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
  } else if (exitCode !== null) {
    badge.className   = 'pane-badge exit-err';
    badge.textContent = 'Exit ' + exitCode;
  } else {
    badge.style.display = 'none';
  }
}

function clearOutput() {
  const panel = document.getElementById('outputPanel');
  panel.textContent = 'Run your code to see output here.';
  panel.className   = 'output-content empty';

  const badge = document.getElementById('statusBadge');
  badge.style.display = 'none';
  badge.className     = 'pane-badge';

  document.getElementById('fixBtn').classList.remove('visible');
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

/* ── CodeGen / Improve / Explain / Fix ─────────────────────────── */
(function () {
  /* ── Shared helper ─────────────────────────────────────────────── */
  function requireEditorCode(action) {
    const code = editor ? editor.getValue().trim() : '';
    if (!code) {
      alert('The editor is empty. Please add code to ' + action + '.');
      return null;
    }
    return code;
  }
  /* ── CodeGen & Improve modal ───────────────────────────────────── */
  const overlay     = document.getElementById('codegenOverlay');
  const promptTA    = document.getElementById('codegenPrompt');
  const submitBtn   = document.getElementById('codegenSubmitBtn');
  const cancelBtn   = document.getElementById('codegenCancelBtn');
  const codegenBtn  = document.getElementById('codegenBtn');
  const improveBtn  = document.getElementById('improveBtn');
  const tabGenerate = document.getElementById('tabGenerate');
  const tabImprove  = document.getElementById('tabImprove');
  const insertRow   = document.getElementById('insertModeRow');
  const hintEl      = document.getElementById('codegenHint');

  let currentMode = 'generate'; // 'generate' | 'improve'

  function getInsertMode() {
    const checked = document.querySelector('input[name="insertMode"]:checked');
    return checked ? checked.value : 'replace';
  }

  function setMode(mode) {
    currentMode = mode;
    if (mode === 'generate') {
      tabGenerate.classList.add('active');
      tabGenerate.setAttribute('aria-selected', 'true');
      tabImprove.classList.remove('active');
      tabImprove.setAttribute('aria-selected', 'false');
      promptTA.placeholder = 'Describe the code you want to generate…\ne.g. "Write a function that sorts a list of dictionaries by a given key."';
      submitBtn.innerHTML  = '<iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon> Generate';
      insertRow.style.display = '';
      updateHint();
    } else {
      tabImprove.classList.add('active');
      tabImprove.setAttribute('aria-selected', 'true');
      tabGenerate.classList.remove('active');
      tabGenerate.setAttribute('aria-selected', 'false');
      promptTA.placeholder = 'Describe how you want to improve the code…\ne.g. "Add error handling and input validation."';
      submitBtn.innerHTML  = '<iconify-icon icon="lucide:wand-2" aria-hidden="true"></iconify-icon> Improve';
      insertRow.style.display = 'none';
      hintEl.textContent      = 'The improved code will replace the current editor content.';
    }
  }

  function updateHint() {
    const mode = getInsertMode();
    hintEl.textContent = mode === 'cursor'
      ? 'The generated code will be inserted at the cursor position.'
      : 'The generated code will replace the current editor content.';
  }

  document.querySelectorAll('input[name="insertMode"]').forEach(function (radio) {
    radio.addEventListener('change', updateHint);
  });

  tabGenerate.addEventListener('click', function () { setMode('generate'); promptTA.focus(); });
  tabImprove.addEventListener('click',  function () { setMode('improve');  promptTA.focus(); });

  function openModal(mode) {
    setMode(mode || 'generate');
    promptTA.value = '';
    overlay.classList.add('open');
    promptTA.focus();
  }

  function closeModal() {
    overlay.classList.remove('open');
    (currentMode === 'improve' ? improveBtn : codegenBtn).focus();
  }

  codegenBtn.addEventListener('click',  function () { window.location.href = '/Generate/'; });
  improveBtn.addEventListener('click',  function () { openModal('improve');  });
  cancelBtn.addEventListener('click', closeModal);

  overlay.addEventListener('click', function (e) {
    if (e.target === overlay) closeModal();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
  });

  async function submitCodegen() {
    const text = promptTA.value.trim();
    if (!text) { promptTA.focus(); return; }

    // For improve mode, current editor code is required
    const code = (currentMode === 'improve') ? requireEditorCode('improve') : null;
    if (currentMode === 'improve' && code === null) return;

    submitBtn.disabled  = true;
    const originalLabel = submitBtn.innerHTML;
    submitBtn.innerHTML = '<span class="spinner"></span> ' +
      (currentMode === 'improve' ? 'Improving…' : 'Generating…');

    try {
      const body = {
        action:   currentMode,
        prompt:   text,
        language: currentLang,
        ...getAiSelection(),
      };
      if (currentMode === 'improve') {
        body.currentCode = code;
      }

      const res  = await fetch('/IDE/codegen.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(body),
      });
      const data = await res.json();

      if (!res.ok) {
        if (data.error_code === 'subscription_required') {
          window.location.href = '/Pricing/';
          return;
        }
        alert('CodeGen error: ' + (data.error || 'Unknown error'));
        return;
      }

      if (editor) {
        if (currentMode === 'generate' && getInsertMode() === 'cursor') {
          const position = editor.getPosition();
          editor.executeEdits('codegen', [{
            range: new monaco.Range(
              position.lineNumber, position.column,
              position.lineNumber, position.column
            ),
            text: data.code,
          }]);
        } else {
          editor.setValue(data.code);
        }
        editor.focus();
      }

      closeModal();
    } catch (err) {
      alert('Network error: ' + err.message);
    } finally {
      submitBtn.disabled  = false;
      submitBtn.innerHTML = originalLabel;
    }
  }

  submitBtn.addEventListener('click', submitCodegen);

  promptTA.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
      e.preventDefault();
      submitCodegen();
    }
  });

  /* ── Explain modal ─────────────────────────────────────────────── */
  const explainOverlay   = document.getElementById('explainOverlay');
  const explainBody      = document.getElementById('explainBody');
  const explainCloseBtn  = document.getElementById('explainCloseBtn');
  const explainBtnEl     = document.getElementById('explainBtn');

  function closeExplain() {
    explainOverlay.classList.remove('open');
    explainBtnEl.focus();
  }

  explainBtnEl.addEventListener('click', async function () {
    const code = requireEditorCode('explain');
    if (code === null) return;

    explainBody.innerHTML = '<div class="explain-loading"><span class="spinner"></span> Generating explanation…</div>';
    explainOverlay.classList.add('open');

    try {
      const body = {
        action:      'explain',
        language:    currentLang,
        currentCode: code,
        ...getAiSelection(),
      };
      const res  = await fetch('/IDE/codegen.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(body),
      });
      const data = await res.json();

      if (!res.ok) {
        if (data.error_code === 'subscription_required') {
          window.location.href = '/Pricing/';
          return;
        }
        explainBody.textContent = 'Error: ' + (data.error || 'Unknown error');
        return;
      }

      explainBody.textContent = data.explanation || '(No explanation returned.)';
    } catch (err) {
      explainBody.textContent = 'Network error: ' + err.message;
    }
  });

  explainCloseBtn.addEventListener('click', closeExplain);

  explainOverlay.addEventListener('click', function (e) {
    if (e.target === explainOverlay) closeExplain();
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && explainOverlay.classList.contains('open')) closeExplain();
  });

  /* ── Fix with AI ───────────────────────────────────────────────── */
  const fixBtn = document.getElementById('fixBtn');

  fixBtn.addEventListener('click', async function () {
    const code = requireEditorCode('fix');
    if (code === null) return;

    const outputPanel    = document.getElementById('outputPanel');
    const errorTextRaw   = outputPanel ? outputPanel.textContent : '';
    const errorText      = errorTextRaw.trim();

    fixBtn.disabled  = true;
    const origLabel  = fixBtn.innerHTML;
    fixBtn.innerHTML = '<span class="spinner"></span> Fixing…';

    try {
      const body = {
        action:      'fix',
        language:    currentLang,
        currentCode: code,
        ...getAiSelection(),
      };
      if (errorText !== '' && !outputPanel.classList.contains('empty')) {
        body.errorOutput = errorText;
      }

      const res  = await fetch('/IDE/codegen.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(body),
      });
      const data = await res.json();

      if (!res.ok) {
        if (data.error_code === 'subscription_required') {
          window.location.href = '/Pricing/';
          return;
        }
        alert('Fix error: ' + (data.error || 'Unknown error'));
        return;
      }

      if (editor) {
        editor.setValue(data.code);
        editor.focus();
      }
    } catch (err) {
      alert('Network error: ' + err.message);
    } finally {
      fixBtn.disabled  = false;
      fixBtn.innerHTML = origLabel;
    }
  });
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
