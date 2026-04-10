<?php
$page_title  = 'Developer Tools – CodeFoundry';
$active_page = 'tools';
$page_styles = <<<'PAGECSS'
/* ── Tools layout ──────────────────────────────────────── */
.tools-hero {
  background: var(--navy);
  border-bottom: 1px solid var(--border-color);
  padding: 52px 40px 44px;
  text-align: center;
}
.tools-hero-badge {
  display: inline-block;
  padding: 5px 14px;
  background: rgba(24,179,255,.12);
  color: var(--primary);
  border: 1px solid rgba(24,179,255,.25);
  border-radius: 100px;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: .06em;
  text-transform: uppercase;
  margin-bottom: 18px;
}
.tools-hero h1 {
  font-size: clamp(28px, 5vw, 42px);
  font-weight: 900;
  margin: 0 0 14px;
  letter-spacing: -.5px;
}
.tools-hero p {
  font-size: 16px;
  color: var(--text-muted);
  max-width: 560px;
  margin: 0 auto;
  line-height: 1.6;
}

/* ── Workspace ──────────────────────────────────────────── */
.tools-workspace {
  display: flex;
  max-width: 1300px;
  margin: 0 auto;
  padding: 0 20px 60px;
  gap: 0;
  min-height: calc(100vh - var(--header-height) - 220px);
}

/* ── Sidebar ────────────────────────────────────────────── */
.tools-sidebar {
  width: 220px;
  flex-shrink: 0;
  padding: 28px 0;
  border-right: 1px solid var(--border-color);
}
.tools-sidebar-title {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  color: var(--text-subtle);
  padding: 0 16px 12px;
}
.tools-cat-btn {
  display: flex;
  align-items: center;
  gap: 10px;
  background: none;
  border: none;
  border-radius: 8px;
  margin: 1px 6px;
  width: calc(100% - 12px);
  padding: 10px 16px;
  color: var(--text-muted);
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: background .15s, color .15s;
  text-align: left;
}
.tools-cat-btn iconify-icon {
  font-size: 16px;
  flex-shrink: 0;
}
.tools-cat-btn:hover {
  background: var(--navy-3);
  color: var(--text);
}
.tools-cat-btn.active {
  background: rgba(24,179,255,.12);
  color: var(--primary);
}
.tools-cat-count {
  margin-left: auto;
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  color: var(--text-subtle);
  font-size: 10px;
  font-weight: 700;
  border-radius: 100px;
  padding: 1px 7px;
  line-height: 16px;
  min-width: 20px;
  text-align: center;
}
.tools-cat-btn.active .tools-cat-count {
  background: rgba(24,179,255,.2);
  border-color: rgba(24,179,255,.3);
  color: var(--primary);
}

/* ── Main Panel ─────────────────────────────────────────── */
.tools-main {
  flex: 1;
  padding: 28px 32px 60px;
  min-width: 0;
}
.tools-category-panel { display: none; }
.tools-category-panel.active { display: block; }
.tools-cat-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 24px;
}
.tools-cat-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: rgba(24,179,255,.12);
  color: var(--primary);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  flex-shrink: 0;
}
.tools-cat-header h2 {
  font-size: 20px;
  font-weight: 800;
  margin: 0 0 2px;
}
.tools-cat-header p {
  font-size: 13px;
  color: var(--text-muted);
  margin: 0;
}

/* ── Tool cards ─────────────────────────────────────────── */
.tools-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(440px, 1fr));
  gap: 20px;
}
.tool-card {
  background: var(--navy);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  overflow: hidden;
  transition: border-color .2s;
}
.tool-card:hover { border-color: rgba(24,179,255,.3); }
.tool-card-head {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 14px 18px;
  border-bottom: 1px solid var(--border-color);
}
.tool-card-head iconify-icon { font-size: 17px; color: var(--primary); flex-shrink: 0; }
.tool-card-head h3 { font-size: 14px; font-weight: 700; margin: 0; }
.tool-card-body { padding: 16px 18px; }
.tool-label {
  font-size: 12px;
  font-weight: 600;
  color: var(--text-subtle);
  text-transform: uppercase;
  letter-spacing: .06em;
  margin: 0 0 6px;
}
.tool-textarea {
  width: 100%;
  background: var(--navy-2);
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 10px 12px;
  font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
  font-size: 13px;
  line-height: 1.5;
  resize: vertical;
  min-height: 80px;
  transition: border-color .2s;
  box-sizing: border-box;
}
.tool-textarea:focus { outline: none; border-color: var(--primary); }
.tool-input {
  width: 100%;
  background: var(--navy-2);
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 9px 12px;
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  transition: border-color .2s;
  box-sizing: border-box;
}
.tool-input:focus { outline: none; border-color: var(--primary); }
.tool-select {
  background: var(--navy-2);
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 8px 12px;
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  cursor: pointer;
}
.tool-select:focus { outline: none; border-color: var(--primary); }
.tool-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin: 10px 0;
}
.tool-btn {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 7px 14px;
  border-radius: var(--button-radius);
  font-family: 'Inter', sans-serif;
  font-size: 13px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: background .2s, color .2s;
}
.tool-btn.primary { background: var(--primary); color: var(--navy); }
.tool-btn.primary:hover { background: var(--primary-hover); }
.tool-btn.secondary {
  background: var(--navy-3);
  color: var(--text-muted);
  border: 1px solid var(--border-color);
}
.tool-btn.secondary:hover { color: var(--text); border-color: rgba(24,179,255,.3); }
.tool-btn.outline {
  background: transparent;
  color: var(--text-muted);
  border: 1px solid var(--border-color);
}
.tool-btn.outline:hover { color: var(--text); border-color: rgba(24,179,255,.3); }
.tool-output-wrap { position: relative; margin-top: 10px; }
.tool-output {
  width: 100%;
  background: var(--navy-3);
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 10px 12px;
  font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
  font-size: 13px;
  line-height: 1.5;
  min-height: 70px;
  word-break: break-all;
  white-space: pre-wrap;
  box-sizing: border-box;
}
.tool-output-copy {
  position: absolute;
  top: 7px;
  right: 7px;
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 6px;
  color: var(--text-muted);
  font-size: 12px;
  padding: 4px 9px;
  cursor: pointer;
  font-family: 'Inter', sans-serif;
  font-weight: 600;
  transition: background .2s, color .2s;
  display: flex;
  align-items: center;
  gap: 4px;
}
.tool-output-copy:hover {
  background: rgba(24,179,255,.12);
  color: var(--primary);
  border-color: rgba(24,179,255,.3);
}
.tool-spacer { height: 10px; }
.tool-row {
  display: flex;
  gap: 8px;
  align-items: flex-end;
}
.tool-row > * { flex: 1; }
.tool-error { color: #ff6b6b; font-size: 12px; margin-top: 5px; }
.tool-success { color: #51cf66; font-size: 12px; margin-top: 5px; }

/* Color swatch */
.color-swatch {
  width: 40px;
  height: 38px;
  border-radius: 8px;
  border: 1px solid var(--border-color);
  flex-shrink: 0;
  cursor: pointer;
  padding: 0;
}

/* Counter stats */
.counter-stats {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
  margin-top: 10px;
}
.counter-stat {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 8px 14px;
  text-align: center;
}
.counter-stat-val {
  font-size: 22px;
  font-weight: 800;
  color: var(--primary);
  line-height: 1;
}
.counter-stat-label {
  font-size: 11px;
  color: var(--text-muted);
  margin-top: 2px;
  text-transform: uppercase;
  letter-spacing: .05em;
}

/* Regex match highlight */
.regex-match { background: rgba(24,179,255,.25); border-radius: 2px; }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 960px) {
  .tools-workspace { flex-direction: column; padding: 0 0 40px; }
  .tools-sidebar {
    width: 100%;
    border-right: none;
    border-bottom: 1px solid var(--border-color);
    padding: 12px 0;
    display: flex;
    overflow-x: auto;
    gap: 0;
  }
  .tools-sidebar-title { display: none; }
  .tools-cat-btn { flex-shrink: 0; white-space: nowrap; width: auto; margin: 0 3px; }
  .tools-cat-count { display: none; }
  .tools-main { padding: 20px 16px 40px; }
  .tools-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
  .tools-hero { padding: 36px 20px 32px; }
}
PAGECSS;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="tools-hero">
  <div class="tools-hero-badge">Free Developer Tools</div>
  <h1>Developer Toolbox</h1>
  <p>A collection of free, browser-based utilities for developers. All tools run locally in your browser — nothing is sent to any server.</p>
</div>

<div class="tools-workspace">

  <!-- ── Sidebar ──────────────────────────────────────── -->
  <aside class="tools-sidebar">
    <div class="tools-sidebar-title">Categories</div>
    <button class="tools-cat-btn active" data-cat="text" onclick="switchCat('text')">
      <iconify-icon icon="lucide:type"></iconify-icon>
      Text
      <span class="tools-cat-count">5</span>
    </button>
    <button class="tools-cat-btn" data-cat="json" onclick="switchCat('json')">
      <iconify-icon icon="lucide:braces"></iconify-icon>
      JSON
      <span class="tools-cat-count">3</span>
    </button>
    <button class="tools-cat-btn" data-cat="web" onclick="switchCat('web')">
      <iconify-icon icon="lucide:globe"></iconify-icon>
      Web
      <span class="tools-cat-count">4</span>
    </button>
    <button class="tools-cat-btn" data-cat="crypto" onclick="switchCat('crypto')">
      <iconify-icon icon="lucide:lock"></iconify-icon>
      Crypto
      <span class="tools-cat-count">3</span>
    </button>
    <button class="tools-cat-btn" data-cat="numbers" onclick="switchCat('numbers')">
      <iconify-icon icon="lucide:hash"></iconify-icon>
      Numbers
      <span class="tools-cat-count">3</span>
    </button>
    <button class="tools-cat-btn" data-cat="code" onclick="switchCat('code')">
      <iconify-icon icon="lucide:code-2"></iconify-icon>
      Code
      <span class="tools-cat-count">3</span>
    </button>
    <div style="height:1px;background:var(--border-color);margin:10px 6px;"></div>
    <div class="tools-sidebar-title" style="margin-top:4px;">Industry</div>
    <button class="tools-cat-btn" data-cat="education" onclick="switchCat('education')">
      <iconify-icon icon="lucide:graduation-cap"></iconify-icon>
      Education
      <span class="tools-cat-count">4</span>
    </button>
    <button class="tools-cat-btn" data-cat="finance" onclick="switchCat('finance')">
      <iconify-icon icon="lucide:dollar-sign"></iconify-icon>
      Finance
      <span class="tools-cat-count">4</span>
    </button>
    <button class="tools-cat-btn" data-cat="healthcare" onclick="switchCat('healthcare')">
      <iconify-icon icon="lucide:heart-pulse"></iconify-icon>
      Healthcare
      <span class="tools-cat-count">4</span>
    </button>
    <button class="tools-cat-btn" data-cat="realestate" onclick="switchCat('realestate')">
      <iconify-icon icon="lucide:home"></iconify-icon>
      Real Estate
      <span class="tools-cat-count">3</span>
    </button>
    <button class="tools-cat-btn" data-cat="hr" onclick="switchCat('hr')">
      <iconify-icon icon="lucide:users"></iconify-icon>
      HR &amp; Payroll
      <span class="tools-cat-count">3</span>
    </button>
    <button class="tools-cat-btn" data-cat="marketing" onclick="switchCat('marketing')">
      <iconify-icon icon="lucide:megaphone"></iconify-icon>
      Marketing
      <span class="tools-cat-count">3</span>
    </button>
  </aside>

  <!-- ── Main panel ────────────────────────────────────── -->
  <div class="tools-main">

    <!-- ════════════ TEXT ════════════ -->
    <div class="tools-category-panel active" id="cat-text">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:type"></iconify-icon></div>
        <div>
          <h2>Text Tools</h2>
          <p>Encode, decode, transform and analyse text</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- Base64 -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:file-code-2"></iconify-icon>
            <h3>Base64 Encode / Decode</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="b64-in" rows="4" placeholder="Enter text to encode or Base64 to decode…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="b64Encode()"><iconify-icon icon="lucide:arrow-down"></iconify-icon> Encode</button>
              <button class="tool-btn secondary" onclick="b64Decode()"><iconify-icon icon="lucide:arrow-up"></iconify-icon> Decode</button>
              <button class="tool-btn outline" onclick="clearTool('b64-in','b64-out','b64-err')"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-error" id="b64-err"></div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="b64-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('b64-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- URL Encode/Decode -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:link"></iconify-icon>
            <h3>URL Encode / Decode</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="url-in" rows="4" placeholder="Enter URL or text…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="urlEncode()"><iconify-icon icon="lucide:arrow-down"></iconify-icon> Encode</button>
              <button class="tool-btn secondary" onclick="urlDecode()"><iconify-icon icon="lucide:arrow-up"></iconify-icon> Decode</button>
              <button class="tool-btn outline" onclick="clearTool('url-in','url-out','url-err')"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-error" id="url-err"></div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="url-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('url-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Case Converter -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:case-sensitive"></iconify-icon>
            <h3>Text Case Converter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="case-in" rows="4" placeholder="Enter text…"></textarea>
            <div class="tool-actions" style="flex-wrap:wrap;">
              <button class="tool-btn primary"   onclick="caseConvert('upper')">UPPER</button>
              <button class="tool-btn secondary" onclick="caseConvert('lower')">lower</button>
              <button class="tool-btn secondary" onclick="caseConvert('title')">Title</button>
              <button class="tool-btn secondary" onclick="caseConvert('camel')">camelCase</button>
              <button class="tool-btn secondary" onclick="caseConvert('snake')">snake_case</button>
              <button class="tool-btn secondary" onclick="caseConvert('kebab')">kebab-case</button>
              <button class="tool-btn outline"   onclick="clearTool('case-in','case-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="case-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('case-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Word / Char Counter -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:bar-chart-2"></iconify-icon>
            <h3>Word &amp; Character Counter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="count-in" rows="5" placeholder="Start typing…" oninput="updateCounter()"></textarea>
            <div class="counter-stats">
              <div class="counter-stat"><div class="counter-stat-val" id="cnt-chars">0</div><div class="counter-stat-label">Characters</div></div>
              <div class="counter-stat"><div class="counter-stat-val" id="cnt-chars-nospace">0</div><div class="counter-stat-label">No Spaces</div></div>
              <div class="counter-stat"><div class="counter-stat-val" id="cnt-words">0</div><div class="counter-stat-label">Words</div></div>
              <div class="counter-stat"><div class="counter-stat-val" id="cnt-lines">0</div><div class="counter-stat-label">Lines</div></div>
              <div class="counter-stat"><div class="counter-stat-val" id="cnt-sentences">0</div><div class="counter-stat-label">Sentences</div></div>
            </div>
          </div>
        </div>

        <!-- Lorem Ipsum -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:align-left"></iconify-icon>
            <h3>Lorem Ipsum Generator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row">
              <div style="flex:0 0 110px;">
                <div class="tool-label">Count</div>
                <input type="number" class="tool-input" id="lorem-count" value="2" min="1" max="20">
              </div>
              <div>
                <div class="tool-label">Type</div>
                <select class="tool-select" id="lorem-type" style="width:100%;">
                  <option value="para">Paragraphs</option>
                  <option value="words">Words</option>
                  <option value="sentences">Sentences</option>
                </select>
              </div>
            </div>
            <div class="tool-actions" style="margin-top:10px;">
              <button class="tool-btn primary" onclick="genLorem()"><iconify-icon icon="lucide:sparkles"></iconify-icon> Generate</button>
              <button class="tool-btn outline" onclick="clearTool(null,'lorem-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="lorem-out" style="min-height:100px;"></div>
              <button class="tool-output-copy" onclick="copyOutput('lorem-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div><!-- /tools-grid -->
    </div><!-- /cat-text -->

    <!-- ════════════ JSON ════════════ -->
    <div class="tools-category-panel" id="cat-json">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:braces"></iconify-icon></div>
        <div>
          <h2>JSON Tools</h2>
          <p>Format, validate and transform JSON data</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- JSON Formatter -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:indent"></iconify-icon>
            <h3>JSON Formatter &amp; Validator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input JSON</div>
            <textarea class="tool-textarea" id="json-fmt-in" rows="6" placeholder='{"key":"value"}'></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary"   onclick="jsonFormat()"><iconify-icon icon="lucide:list-tree"></iconify-icon> Format</button>
              <button class="tool-btn secondary" onclick="jsonValidate()"><iconify-icon icon="lucide:check-circle"></iconify-icon> Validate</button>
              <button class="tool-btn outline"   onclick="clearTool('json-fmt-in','json-fmt-out','json-fmt-err')"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div id="json-fmt-err"></div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="json-fmt-out" style="min-height:100px;"></div>
              <button class="tool-output-copy" onclick="copyOutput('json-fmt-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- JSON Minifier -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:minimize-2"></iconify-icon>
            <h3>JSON Minifier</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input JSON</div>
            <textarea class="tool-textarea" id="json-min-in" rows="6" placeholder='{"key": "value"}'></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="jsonMinify()"><iconify-icon icon="lucide:compress"></iconify-icon> Minify</button>
              <button class="tool-btn outline" onclick="clearTool('json-min-in','json-min-out','json-min-err')"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div id="json-min-err"></div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="json-min-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('json-min-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- JSON to CSV -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:table"></iconify-icon>
            <h3>JSON to CSV</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input JSON (array of objects)</div>
            <textarea class="tool-textarea" id="json-csv-in" rows="6" placeholder='[{"name":"Alice","age":30},{"name":"Bob","age":25}]'></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="jsonToCsv()"><iconify-icon icon="lucide:arrow-right-from-line"></iconify-icon> Convert</button>
              <button class="tool-btn outline" onclick="clearTool('json-csv-in','json-csv-out','json-csv-err')"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div id="json-csv-err"></div>
            <div class="tool-label">CSV Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="json-csv-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('json-csv-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-json -->

    <!-- ════════════ WEB ════════════ -->
    <div class="tools-category-panel" id="cat-web">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:globe"></iconify-icon></div>
        <div>
          <h2>Web Tools</h2>
          <p>HTML encoding, colour conversion, regex testing and text diff</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- HTML Entity Encode/Decode -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:code"></iconify-icon>
            <h3>HTML Entity Encode / Decode</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="html-in" rows="4" placeholder="Enter HTML or plain text…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary"   onclick="htmlEncode()"><iconify-icon icon="lucide:arrow-down"></iconify-icon> Encode</button>
              <button class="tool-btn secondary" onclick="htmlDecode()"><iconify-icon icon="lucide:arrow-up"></iconify-icon> Decode</button>
              <button class="tool-btn outline"   onclick="clearTool('html-in','html-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="html-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('html-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Color Converter -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:palette"></iconify-icon>
            <h3>Colour Converter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="align-items:center;">
              <div>
                <div class="tool-label">HEX</div>
                <input type="text" class="tool-input" id="col-hex" placeholder="#18b3ff" oninput="colorFromHex()" maxlength="9">
              </div>
              <input type="color" class="color-swatch" id="col-picker" value="#18b3ff" oninput="colorFromPicker()">
            </div>
            <div class="tool-spacer"></div>
            <div class="tool-row">
              <div>
                <div class="tool-label">R</div>
                <input type="number" class="tool-input" id="col-r" min="0" max="255" placeholder="0" oninput="colorFromRgb()">
              </div>
              <div>
                <div class="tool-label">G</div>
                <input type="number" class="tool-input" id="col-g" min="0" max="255" placeholder="0" oninput="colorFromRgb()">
              </div>
              <div>
                <div class="tool-label">B</div>
                <input type="number" class="tool-input" id="col-b" min="0" max="255" placeholder="0" oninput="colorFromRgb()">
              </div>
            </div>
            <div class="tool-spacer"></div>
            <div class="tool-row">
              <div>
                <div class="tool-label">H (0–360)</div>
                <input type="number" class="tool-input" id="col-h" min="0" max="360" placeholder="0" oninput="colorFromHsl()">
              </div>
              <div>
                <div class="tool-label">S (%)</div>
                <input type="number" class="tool-input" id="col-s" min="0" max="100" placeholder="0" oninput="colorFromHsl()">
              </div>
              <div>
                <div class="tool-label">L (%)</div>
                <input type="number" class="tool-input" id="col-l" min="0" max="100" placeholder="0" oninput="colorFromHsl()">
              </div>
            </div>
          </div>
        </div>

        <!-- Regex Tester -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:search-code"></iconify-icon>
            <h3>Regex Tester</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row">
              <div style="flex:3;">
                <div class="tool-label">Pattern</div>
                <input type="text" class="tool-input" id="rx-pattern" placeholder="[a-z]+" oninput="regexTest()">
              </div>
              <div style="flex:1;">
                <div class="tool-label">Flags</div>
                <input type="text" class="tool-input" id="rx-flags" placeholder="gi" maxlength="6" oninput="regexTest()">
              </div>
            </div>
            <div class="tool-spacer"></div>
            <div class="tool-label">Test String</div>
            <textarea class="tool-textarea" id="rx-text" rows="4" placeholder="Test your regex here…" oninput="regexTest()"></textarea>
            <div class="tool-error" id="rx-err"></div>
            <div class="tool-spacer"></div>
            <div class="tool-label">Highlighted Matches</div>
            <div class="tool-output" id="rx-out" style="word-break:break-word;"></div>
            <div id="rx-info" style="font-size:12px;color:var(--text-muted);margin-top:6px;"></div>
          </div>
        </div>

        <!-- Text Diff -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:git-compare"></iconify-icon>
            <h3>Text Diff</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="align-items:flex-start;">
              <div>
                <div class="tool-label">Original</div>
                <textarea class="tool-textarea" id="diff-a" rows="5" oninput="runDiff()"></textarea>
              </div>
              <div>
                <div class="tool-label">Modified</div>
                <textarea class="tool-textarea" id="diff-b" rows="5" oninput="runDiff()"></textarea>
              </div>
            </div>
            <div class="tool-spacer"></div>
            <div class="tool-label">Diff (line-by-line)</div>
            <div class="tool-output" id="diff-out" style="min-height:80px;font-size:12px;"></div>
          </div>
        </div>

      </div>
    </div><!-- /cat-web -->

    <!-- ════════════ CRYPTO ════════════ -->
    <div class="tools-category-panel" id="cat-crypto">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:lock"></iconify-icon></div>
        <div>
          <h2>Crypto &amp; Hash Tools</h2>
          <p>Generate hashes and encode / decode data</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- SHA Hash -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:fingerprint"></iconify-icon>
            <h3>Hash Generator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="hash-in" rows="4" placeholder="Enter text to hash…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary"   onclick="genHash('SHA-256')">SHA-256</button>
              <button class="tool-btn secondary" onclick="genHash('SHA-1')">SHA-1</button>
              <button class="tool-btn secondary" onclick="genHash('SHA-512')">SHA-512</button>
              <button class="tool-btn outline"   onclick="clearTool('hash-in','hash-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-label">Hash Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="hash-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('hash-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Hex Encode/Decode -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:binary"></iconify-icon>
            <h3>Hex Encoder / Decoder</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="hex-in" rows="4" placeholder="Enter text or hex bytes (e.g. 48 65 6c 6c 6f)…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary"   onclick="hexEncode()"><iconify-icon icon="lucide:arrow-down"></iconify-icon> Text → Hex</button>
              <button class="tool-btn secondary" onclick="hexDecode()"><iconify-icon icon="lucide:arrow-up"></iconify-icon> Hex → Text</button>
              <button class="tool-btn outline"   onclick="clearTool('hex-in','hex-out','hex-err')"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-error" id="hex-err"></div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="hex-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('hex-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- JWT Decoder -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:key-round"></iconify-icon>
            <h3>JWT Decoder</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">JWT Token</div>
            <textarea class="tool-textarea" id="jwt-in" rows="4" placeholder="Paste a JWT token…" oninput="decodeJwt()"></textarea>
            <div class="tool-error" id="jwt-err"></div>
            <div class="tool-label">Header</div>
            <div class="tool-output" id="jwt-header" style="min-height:40px;"></div>
            <div class="tool-spacer"></div>
            <div class="tool-label">Payload</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="jwt-payload" style="min-height:60px;"></div>
              <button class="tool-output-copy" onclick="copyOutput('jwt-payload',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-crypto -->

    <!-- ════════════ NUMBERS ════════════ -->
    <div class="tools-category-panel" id="cat-numbers">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:hash"></iconify-icon></div>
        <div>
          <h2>Number Tools</h2>
          <p>Convert between number bases, timestamps and units</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- Base Converter -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:arrow-left-right"></iconify-icon>
            <h3>Number Base Converter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Decimal</div>
            <input type="text" class="tool-input" id="base-dec" placeholder="255" oninput="baseConvert('dec')">
            <div class="tool-spacer"></div>
            <div class="tool-label">Binary</div>
            <input type="text" class="tool-input" id="base-bin" placeholder="11111111" oninput="baseConvert('bin')">
            <div class="tool-spacer"></div>
            <div class="tool-label">Octal</div>
            <input type="text" class="tool-input" id="base-oct" placeholder="377" oninput="baseConvert('oct')">
            <div class="tool-spacer"></div>
            <div class="tool-label">Hexadecimal</div>
            <input type="text" class="tool-input" id="base-hex" placeholder="ff" oninput="baseConvert('hex')">
            <div class="tool-error" id="base-err" style="margin-top:6px;"></div>
          </div>
        </div>

        <!-- Unix Timestamp -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:clock"></iconify-icon>
            <h3>Unix Timestamp Converter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Unix Timestamp (seconds or ms)</div>
            <div class="tool-row">
              <input type="text" class="tool-input" id="ts-in" placeholder="1704067200">
              <button class="tool-btn primary" onclick="tsConvert()" style="flex:0 0 auto;white-space:nowrap;">Convert</button>
            </div>
            <div class="tool-actions">
              <button class="tool-btn secondary" onclick="tsNow()"><iconify-icon icon="lucide:refresh-cw"></iconify-icon> Use Now</button>
            </div>
            <div class="tool-output" id="ts-out" style="min-height:60px;margin-top:8px;"></div>
          </div>
        </div>

        <!-- Unit Converter -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:ruler"></iconify-icon>
            <h3>Unit Converter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Category</div>
            <select class="tool-select" id="unit-cat" onchange="unitCatChange()" style="width:100%;margin-bottom:10px;">
              <option value="length">Length</option>
              <option value="mass">Mass / Weight</option>
              <option value="temp">Temperature</option>
              <option value="data">Data Storage</option>
            </select>
            <div class="tool-row">
              <div>
                <div class="tool-label">From</div>
                <select class="tool-select" id="unit-from" style="width:100%;" onchange="unitConvert()"></select>
              </div>
              <div>
                <div class="tool-label">To</div>
                <select class="tool-select" id="unit-to" style="width:100%;" onchange="unitConvert()"></select>
              </div>
            </div>
            <div class="tool-spacer"></div>
            <div>
              <div class="tool-label">Value</div>
              <input type="number" class="tool-input" id="unit-val" value="1" oninput="unitConvert()">
            </div>
            <div class="tool-output" id="unit-out" style="min-height:40px;margin-top:10px;"></div>
          </div>
        </div>

      </div>
    </div><!-- /cat-numbers -->

    <!-- ════════════ CODE ════════════ -->
    <div class="tools-category-panel" id="cat-code">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:code-2"></iconify-icon></div>
        <div>
          <h2>Code Tools</h2>
          <p>Minify, escape and manipulate code</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- CSS Minifier -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:file-code"></iconify-icon>
            <h3>CSS Minifier</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input CSS</div>
            <textarea class="tool-textarea" id="css-in" rows="6" placeholder=".selector { color: red; }"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="cssMinify()"><iconify-icon icon="lucide:compress"></iconify-icon> Minify</button>
              <button class="tool-btn outline" onclick="clearTool('css-in','css-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-label">Minified Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="css-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('css-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- String Escaper -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:shield"></iconify-icon>
            <h3>HTML / JS String Escaper</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input</div>
            <textarea class="tool-textarea" id="esc-in" rows="5" placeholder="Enter text to escape…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary"   onclick="escapeHtml()">HTML Escape</button>
              <button class="tool-btn secondary" onclick="escapeJs()">JS String Escape</button>
              <button class="tool-btn outline"   onclick="clearTool('esc-in','esc-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="esc-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('esc-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Line Sort / Dedup -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:list-ordered"></iconify-icon>
            <h3>Line Sort &amp; Deduplicator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Input (one item per line)</div>
            <textarea class="tool-textarea" id="lines-in" rows="6" placeholder="banana&#10;apple&#10;cherry&#10;apple"></textarea>
            <div class="tool-actions" style="flex-wrap:wrap;">
              <button class="tool-btn primary"   onclick="linesSort(false)"><iconify-icon icon="lucide:arrow-up-a-z"></iconify-icon> Sort A–Z</button>
              <button class="tool-btn secondary" onclick="linesSort(true)"><iconify-icon icon="lucide:arrow-down-z-a"></iconify-icon> Sort Z–A</button>
              <button class="tool-btn secondary" onclick="linesDedup()"><iconify-icon icon="lucide:copy-x"></iconify-icon> Remove Duplicates</button>
              <button class="tool-btn secondary" onclick="linesReverse()"><iconify-icon icon="lucide:flip-vertical-2"></iconify-icon> Reverse</button>
              <button class="tool-btn outline"   onclick="clearTool('lines-in','lines-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-label">Output</div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="lines-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('lines-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-code -->

    <!-- ════════════ EDUCATION ════════════ -->
    <div class="tools-category-panel" id="cat-education">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:graduation-cap"></iconify-icon></div>
        <div>
          <h2>Education Tools</h2>
          <p>Calculators and utilities for students, teachers, and schools</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- GPA Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:star"></iconify-icon>
            <h3>GPA Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Courses (Grade, Credits per line — e.g. <em>A 3</em>)</div>
            <textarea class="tool-textarea" id="gpa-in" rows="6" placeholder="A 3&#10;B+ 4&#10;C 2&#10;A- 3"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcGpa()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate GPA</button>
              <button class="tool-btn outline" onclick="clearTool('gpa-in','gpa-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="gpa-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('gpa-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Grade Percentage -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:percent"></iconify-icon>
            <h3>Grade Percentage Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Score Earned</div>
                <input class="tool-input" id="grade-score" type="number" min="0" placeholder="85">
              </div>
              <div>
                <div class="tool-label">Total Points</div>
                <input class="tool-input" id="grade-total" type="number" min="1" placeholder="100">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcGradePercent()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('grade-score',null,null);clearTool('grade-total',null,null);document.getElementById('grade-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="grade-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('grade-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Attendance Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:calendar-check"></iconify-icon>
            <h3>Attendance Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Days Present</div>
                <input class="tool-input" id="att-present" type="number" min="0" placeholder="85">
              </div>
              <div>
                <div class="tool-label">Total School Days</div>
                <input class="tool-input" id="att-total" type="number" min="1" placeholder="100">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Minimum Required %</div>
                <input class="tool-input" id="att-min" type="number" min="0" max="100" value="75" placeholder="75">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcAttendance()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('att-present',null,null);clearTool('att-total',null,null);document.getElementById('att-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="att-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('att-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Reading Level -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:book-open"></iconify-icon>
            <h3>Reading Level Estimator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Text</div>
            <textarea class="tool-textarea" id="rl-in" rows="6" placeholder="Paste a passage to estimate its reading level…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcReadingLevel()"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Analyse</button>
              <button class="tool-btn outline" onclick="clearTool('rl-in','rl-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="rl-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('rl-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-education -->

    <!-- ════════════ FINANCE ════════════ -->
    <div class="tools-category-panel" id="cat-finance">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:dollar-sign"></iconify-icon></div>
        <div>
          <h2>Finance Tools</h2>
          <p>Loan, interest, and investment calculators for everyday financial decisions</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- Loan Payment Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:landmark"></iconify-icon>
            <h3>Loan Payment Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Loan Amount ($)</div>
                <input class="tool-input" id="loan-principal" type="number" min="0" placeholder="10000">
              </div>
              <div>
                <div class="tool-label">Annual Rate (%)</div>
                <input class="tool-input" id="loan-rate" type="number" min="0" step="0.01" placeholder="5.5">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Term (months)</div>
                <input class="tool-input" id="loan-term" type="number" min="1" placeholder="60">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcLoan()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('loan-principal',null,null);clearTool('loan-rate',null,null);clearTool('loan-term',null,null);document.getElementById('loan-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="loan-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('loan-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Compound Interest -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:trending-up"></iconify-icon>
            <h3>Compound Interest Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Principal ($)</div>
                <input class="tool-input" id="ci-principal" type="number" min="0" placeholder="5000">
              </div>
              <div>
                <div class="tool-label">Annual Rate (%)</div>
                <input class="tool-input" id="ci-rate" type="number" min="0" step="0.01" placeholder="7">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Years</div>
                <input class="tool-input" id="ci-years" type="number" min="1" placeholder="10">
              </div>
              <div>
                <div class="tool-label">Compounds / Year</div>
                <select class="tool-select" id="ci-freq" style="width:100%;">
                  <option value="1">Annually</option>
                  <option value="2">Semi-annually</option>
                  <option value="4">Quarterly</option>
                  <option value="12" selected>Monthly</option>
                  <option value="365">Daily</option>
                </select>
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcCompoundInterest()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('ci-principal',null,null);clearTool('ci-rate',null,null);clearTool('ci-years',null,null);document.getElementById('ci-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="ci-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('ci-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Tip Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:receipt"></iconify-icon>
            <h3>Tip Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Bill Amount ($)</div>
                <input class="tool-input" id="tip-bill" type="number" min="0" step="0.01" placeholder="45.00">
              </div>
              <div>
                <div class="tool-label">Tip %</div>
                <input class="tool-input" id="tip-pct" type="number" min="0" value="18" placeholder="18">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Number of People</div>
                <input class="tool-input" id="tip-people" type="number" min="1" value="1" placeholder="1">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcTip()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('tip-bill',null,null);document.getElementById('tip-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="tip-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('tip-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- ROI Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:bar-chart"></iconify-icon>
            <h3>ROI Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Initial Investment ($)</div>
                <input class="tool-input" id="roi-init" type="number" min="0" placeholder="10000">
              </div>
              <div>
                <div class="tool-label">Final Value ($)</div>
                <input class="tool-input" id="roi-final" type="number" min="0" placeholder="13500">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcRoi()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('roi-init',null,null);clearTool('roi-final',null,null);document.getElementById('roi-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="roi-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('roi-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-finance -->

    <!-- ════════════ HEALTHCARE ════════════ -->
    <div class="tools-category-panel" id="cat-healthcare">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:heart-pulse"></iconify-icon></div>
        <div>
          <h2>Healthcare Tools</h2>
          <p>Health calculators and clinical utilities for patients and practitioners</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- BMI Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:activity"></iconify-icon>
            <h3>BMI Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Unit System</div>
                <select class="tool-select" id="bmi-unit" style="width:100%;" onchange="bmiUnitToggle()">
                  <option value="metric">Metric (kg / cm)</option>
                  <option value="imperial">Imperial (lb / in)</option>
                </select>
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;" id="bmi-metric-row">
              <div>
                <div class="tool-label">Weight (kg)</div>
                <input class="tool-input" id="bmi-wt-kg" type="number" min="0" step="0.1" placeholder="70">
              </div>
              <div>
                <div class="tool-label">Height (cm)</div>
                <input class="tool-input" id="bmi-ht-cm" type="number" min="0" step="0.1" placeholder="175">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;display:none;" id="bmi-imperial-row">
              <div>
                <div class="tool-label">Weight (lb)</div>
                <input class="tool-input" id="bmi-wt-lb" type="number" min="0" step="0.1" placeholder="154">
              </div>
              <div>
                <div class="tool-label">Height (in)</div>
                <input class="tool-input" id="bmi-ht-in" type="number" min="0" step="0.1" placeholder="69">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcBmi()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate BMI</button>
              <button class="tool-btn outline" onclick="document.getElementById('bmi-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="bmi-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('bmi-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Age Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:cake"></iconify-icon>
            <h3>Age Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Date of Birth</div>
                <input class="tool-input" id="age-dob" type="date">
              </div>
              <div>
                <div class="tool-label">As of Date</div>
                <input class="tool-input" id="age-asof" type="date">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcAge()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate Age</button>
              <button class="tool-btn outline" onclick="document.getElementById('age-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="age-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('age-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Ideal Body Weight -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:scale"></iconify-icon>
            <h3>Ideal Body Weight</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Height (cm)</div>
                <input class="tool-input" id="ibw-ht" type="number" min="100" placeholder="175">
              </div>
              <div>
                <div class="tool-label">Sex</div>
                <select class="tool-select" id="ibw-sex" style="width:100%;">
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                </select>
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcIbw()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('ibw-ht',null,null);document.getElementById('ibw-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="ibw-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('ibw-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Calorie Burn Estimator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:flame"></iconify-icon>
            <h3>Calorie Burn Estimator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Body Weight (kg)</div>
                <input class="tool-input" id="cal-wt" type="number" min="0" step="0.1" placeholder="70">
              </div>
              <div>
                <div class="tool-label">Duration (min)</div>
                <input class="tool-input" id="cal-dur" type="number" min="1" placeholder="30">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Activity</div>
                <select class="tool-select" id="cal-act" style="width:100%;">
                  <option value="1.5">Rest / Sleeping</option>
                  <option value="2.5">Light Walking</option>
                  <option value="4.0" selected>Brisk Walking</option>
                  <option value="6.0">Jogging</option>
                  <option value="9.8">Running (fast)</option>
                  <option value="7.0">Cycling (moderate)</option>
                  <option value="8.0">Swimming</option>
                  <option value="5.0">Yoga</option>
                  <option value="8.0">Weight Training</option>
                </select>
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcCalorieBurn()"><iconify-icon icon="lucide:calculator"></iconify-icon> Estimate</button>
              <button class="tool-btn outline" onclick="clearTool('cal-wt',null,null);clearTool('cal-dur',null,null);document.getElementById('cal-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="cal-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('cal-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-healthcare -->

    <!-- ════════════ REAL ESTATE ════════════ -->
    <div class="tools-category-panel" id="cat-realestate">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:home"></iconify-icon></div>
        <div>
          <h2>Real Estate Tools</h2>
          <p>Mortgage, property, and investment calculators for buyers and agents</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- Mortgage Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:building-2"></iconify-icon>
            <h3>Mortgage Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Home Price ($)</div>
                <input class="tool-input" id="mort-price" type="number" min="0" placeholder="350000">
              </div>
              <div>
                <div class="tool-label">Down Payment ($)</div>
                <input class="tool-input" id="mort-down" type="number" min="0" placeholder="70000">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Annual Rate (%)</div>
                <input class="tool-input" id="mort-rate" type="number" min="0" step="0.01" placeholder="6.5">
              </div>
              <div>
                <div class="tool-label">Term (years)</div>
                <select class="tool-select" id="mort-term" style="width:100%;">
                  <option value="10">10 years</option>
                  <option value="15">15 years</option>
                  <option value="20">20 years</option>
                  <option value="30" selected>30 years</option>
                </select>
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcMortgage()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('mort-price',null,null);clearTool('mort-down',null,null);clearTool('mort-rate',null,null);document.getElementById('mort-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="mort-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('mort-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Square Footage Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:ruler"></iconify-icon>
            <h3>Square Footage Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Rooms (Length × Width per line, e.g. <em>12 10</em>)</div>
            <textarea class="tool-textarea" id="sqft-in" rows="5" placeholder="12 10&#10;8 9&#10;15 12"></textarea>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;margin-top:8px;">
              <div>
                <div class="tool-label">Unit</div>
                <select class="tool-select" id="sqft-unit" style="width:100%;">
                  <option value="ft">Feet → sq ft</option>
                  <option value="m">Metres → sq m</option>
                </select>
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcSqft()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('sqft-in','sqft-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="sqft-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('sqft-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Property ROI -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:trending-up"></iconify-icon>
            <h3>Rental Property ROI</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Purchase Price ($)</div>
                <input class="tool-input" id="prop-price" type="number" min="0" placeholder="300000">
              </div>
              <div>
                <div class="tool-label">Monthly Rent ($)</div>
                <input class="tool-input" id="prop-rent" type="number" min="0" placeholder="2200">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Annual Expenses ($)</div>
                <input class="tool-input" id="prop-exp" type="number" min="0" placeholder="6000">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcPropRoi()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="clearTool('prop-price',null,null);clearTool('prop-rent',null,null);clearTool('prop-exp',null,null);document.getElementById('prop-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="prop-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('prop-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-realestate -->

    <!-- ════════════ HR & PAYROLL ════════════ -->
    <div class="tools-category-panel" id="cat-hr">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:users"></iconify-icon></div>
        <div>
          <h2>HR &amp; Payroll Tools</h2>
          <p>Salary, working days, and payroll calculators for HR professionals</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- Salary ↔ Hourly -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:banknote"></iconify-icon>
            <h3>Salary ↔ Hourly Converter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Annual Salary ($)</div>
                <input class="tool-input" id="sal-annual" type="number" min="0" placeholder="60000" oninput="salFromAnnual()">
              </div>
              <div>
                <div class="tool-label">Hourly Rate ($)</div>
                <input class="tool-input" id="sal-hourly" type="number" min="0" step="0.01" placeholder="28.85" oninput="salFromHourly()">
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Hours / Week</div>
                <input class="tool-input" id="sal-hrs" type="number" min="1" value="40" placeholder="40" oninput="salFromAnnual()">
              </div>
              <div>
                <div class="tool-label">Weeks / Year</div>
                <input class="tool-input" id="sal-wks" type="number" min="1" value="52" placeholder="52" oninput="salFromAnnual()">
              </div>
            </div>
            <div class="tool-output-wrap" style="margin-top:4px;">
              <div class="tool-output" id="sal-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('sal-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Working Days Calculator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:calendar-days"></iconify-icon>
            <h3>Working Days Calculator</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Start Date</div>
                <input class="tool-input" id="wd-start" type="date">
              </div>
              <div>
                <div class="tool-label">End Date</div>
                <input class="tool-input" id="wd-end" type="date">
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcWorkingDays()"><iconify-icon icon="lucide:calculator"></iconify-icon> Calculate</button>
              <button class="tool-btn outline" onclick="document.getElementById('wd-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="wd-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('wd-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Take-Home Pay Estimator -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:wallet"></iconify-icon>
            <h3>Take-Home Pay Estimator (US)</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Gross Pay ($)</div>
                <input class="tool-input" id="pay-gross" type="number" min="0" step="0.01" placeholder="5000">
              </div>
              <div>
                <div class="tool-label">Pay Period</div>
                <select class="tool-select" id="pay-period" style="width:100%;">
                  <option value="weekly">Weekly</option>
                  <option value="biweekly">Bi-weekly</option>
                  <option value="semimonthly">Semi-monthly</option>
                  <option value="monthly" selected>Monthly</option>
                </select>
              </div>
            </div>
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Filing Status</div>
                <select class="tool-select" id="pay-status" style="width:100%;">
                  <option value="single">Single</option>
                  <option value="married">Married</option>
                  <option value="hoh">Head of Household</option>
                </select>
              </div>
            </div>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcTakeHome()"><iconify-icon icon="lucide:calculator"></iconify-icon> Estimate</button>
              <button class="tool-btn outline" onclick="clearTool('pay-gross',null,null);document.getElementById('pay-out').textContent=''"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <p style="font-size:11px;color:var(--text-subtle);margin:4px 0 8px;">Simplified estimate. Consult a payroll professional for accuracy.</p>
            <div class="tool-output-wrap">
              <div class="tool-output" id="pay-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('pay-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-hr -->

    <!-- ════════════ MARKETING ════════════ -->
    <div class="tools-category-panel" id="cat-marketing">
      <div class="tools-cat-header">
        <div class="tools-cat-icon"><iconify-icon icon="lucide:megaphone"></iconify-icon></div>
        <div>
          <h2>Marketing Tools</h2>
          <p>Copy, readability, and content analysis tools for marketers</p>
        </div>
      </div>
      <div class="tools-grid">

        <!-- Ad Character Counter -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:type"></iconify-icon>
            <h3>Ad Copy Character Counter</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-row" style="gap:8px;margin-bottom:8px;">
              <div>
                <div class="tool-label">Platform</div>
                <select class="tool-select" id="adcc-platform" style="width:100%;" onchange="adccUpdate()">
                  <option value="140">X / Twitter (140)</option>
                  <option value="280">X / Twitter (280)</option>
                  <option value="30" data-desc="Google Headline">Google Ads Headline (30)</option>
                  <option value="90" data-desc="Google Desc">Google Ads Description (90)</option>
                  <option value="125" data-desc="Facebook">Facebook Ad Text (125)</option>
                  <option value="2200" data-desc="Instagram">Instagram Caption (2200)</option>
                  <option value="150" data-desc="LinkedIn">LinkedIn Headline (150)</option>
                </select>
              </div>
            </div>
            <div class="tool-label">Ad Copy</div>
            <textarea class="tool-textarea" id="adcc-text" rows="4" placeholder="Write your ad copy here…" oninput="adccUpdate()"></textarea>
            <div class="tool-output-wrap" style="margin-top:8px;">
              <div class="tool-output" id="adcc-out"></div>
            </div>
          </div>
        </div>

        <!-- Keyword Density -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:search"></iconify-icon>
            <h3>Keyword Density Analyser</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Content</div>
            <textarea class="tool-textarea" id="kd-text" rows="5" placeholder="Paste your article or page content here…"></textarea>
            <div class="tool-label" style="margin-top:8px;">Keyword / Phrase</div>
            <input class="tool-input" id="kd-kw" type="text" placeholder="e.g. cloud hosting">
            <div class="tool-actions" style="margin-top:8px;">
              <button class="tool-btn primary" onclick="calcKwDensity()"><iconify-icon icon="lucide:search"></iconify-icon> Analyse</button>
              <button class="tool-btn outline" onclick="clearTool('kd-text','kd-out',null);clearTool('kd-kw',null,null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="kd-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('kd-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

        <!-- Flesch Readability -->
        <div class="tool-card">
          <div class="tool-card-head">
            <iconify-icon icon="lucide:bar-chart-2"></iconify-icon>
            <h3>Flesch Readability Score</h3>
          </div>
          <div class="tool-card-body">
            <div class="tool-label">Text</div>
            <textarea class="tool-textarea" id="fre-in" rows="6" placeholder="Paste your marketing copy, blog post, or email here…"></textarea>
            <div class="tool-actions">
              <button class="tool-btn primary" onclick="calcFlesch()"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Score</button>
              <button class="tool-btn outline" onclick="clearTool('fre-in','fre-out',null)"><iconify-icon icon="lucide:trash-2"></iconify-icon></button>
            </div>
            <div class="tool-output-wrap">
              <div class="tool-output" id="fre-out"></div>
              <button class="tool-output-copy" onclick="copyOutput('fre-out',this)"><iconify-icon icon="lucide:copy"></iconify-icon> Copy</button>
            </div>
          </div>
        </div>

      </div>
    </div><!-- /cat-marketing -->

  </div><!-- /tools-main -->
</div><!-- /tools-workspace -->

<?php
$page_scripts = <<<'PAGEJS'
/* ── Category switcher ─────────────────────────────────── */
function switchCat(id) {
  document.querySelectorAll('.tools-cat-btn').forEach(function(b) {
    b.classList.toggle('active', b.dataset.cat === id);
  });
  document.querySelectorAll('.tools-category-panel').forEach(function(p) {
    p.classList.toggle('active', p.id === 'cat-' + id);
  });
}

/* ── Shared helpers ────────────────────────────────────── */
function clearTool(inId, outId, errId) {
  if (inId)  { var i = document.getElementById(inId);  if (i)  i.value = ''; }
  if (outId) { var o = document.getElementById(outId); if (o)  o.textContent = ''; }
  if (errId) { var e = document.getElementById(errId); if (e)  e.textContent = ''; }
}

function copyOutput(id, btn) {
  var el = document.getElementById(id);
  var text = el ? (el.textContent || el.innerText) : '';
  if (!text.trim()) return;
  navigator.clipboard.writeText(text).then(function() {
    var orig = btn.innerHTML;
    btn.innerHTML = '<iconify-icon icon="lucide:check"></iconify-icon> Copied!';
    btn.style.color = '#51cf66';
    setTimeout(function() { btn.innerHTML = orig; btn.style.color = ''; }, 1800);
  }).catch(function() {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;opacity:0;';
    document.body.appendChild(ta);
    ta.select();
    document.execCommand('copy'); /* legacy fallback for older browsers */
    document.body.removeChild(ta);
  });
}

function _setErr(id, msg, isSuccess) {
  var el = document.getElementById(id);
  if (!el) return;
  el.textContent = msg;
  el.className = isSuccess ? 'tool-success' : 'tool-error';
}

/* ── TEXT: Base64 ──────────────────────────────────────── */
function b64Encode() {
  var v = document.getElementById('b64-in').value;
  _setErr('b64-err', '', false);
  try {
    var bytes = new TextEncoder().encode(v);
    var binary = String.fromCharCode.apply(null, bytes);
    document.getElementById('b64-out').textContent = btoa(binary);
  } catch(e) {
    _setErr('b64-err', 'Error: ' + e.message, false);
  }
}
function b64Decode() {
  var v = document.getElementById('b64-in').value.trim();
  _setErr('b64-err', '', false);
  try {
    var binary = atob(v);
    var bytes = Uint8Array.from(binary, function(c) { return c.charCodeAt(0); });
    document.getElementById('b64-out').textContent = new TextDecoder().decode(bytes);
  } catch(e) {
    _setErr('b64-err', 'Invalid Base64 input.', false);
  }
}

/* ── TEXT: URL Encode/Decode ──────────────────────────── */
function urlEncode() {
  var v = document.getElementById('url-in').value;
  _setErr('url-err', '', false);
  document.getElementById('url-out').textContent = encodeURIComponent(v);
}
function urlDecode() {
  var v = document.getElementById('url-in').value;
  _setErr('url-err', '', false);
  try {
    document.getElementById('url-out').textContent = decodeURIComponent(v);
  } catch(e) {
    _setErr('url-err', 'Invalid URL-encoded input.', false);
  }
}

/* ── TEXT: Case converter ─────────────────────────────── */
function _toTitleCase(s) {
  return s.replace(/\w\S*/g, function(t) { return t.charAt(0).toUpperCase() + t.slice(1).toLowerCase(); });
}
function _toCamelCase(s) {
  return s.toLowerCase().replace(/[^a-zA-Z0-9]+(.)/g, function(m, c) { return c.toUpperCase(); });
}
function _toSnakeCase(s) {
  return s.trim()
    .replace(/([A-Z])/g, function(m) { return '_' + m; })
    .replace(/[\s\-]+/g, '_')
    .replace(/^_/, '')
    .toLowerCase();
}
function _toKebabCase(s) { return _toSnakeCase(s).replace(/_/g, '-'); }
function caseConvert(type) {
  var v   = document.getElementById('case-in').value;
  var out = '';
  if      (type === 'upper') out = v.toUpperCase();
  else if (type === 'lower') out = v.toLowerCase();
  else if (type === 'title') out = _toTitleCase(v);
  else if (type === 'camel') out = _toCamelCase(v);
  else if (type === 'snake') out = _toSnakeCase(v);
  else if (type === 'kebab') out = _toKebabCase(v);
  document.getElementById('case-out').textContent = out;
}

/* ── TEXT: Counter ────────────────────────────────────── */
function updateCounter() {
  var v = document.getElementById('count-in').value;
  document.getElementById('cnt-chars').textContent         = v.length;
  document.getElementById('cnt-chars-nospace').textContent = v.replace(/\s/g, '').length;
  document.getElementById('cnt-words').textContent         = v.trim() ? v.trim().split(/\s+/).length : 0;
  document.getElementById('cnt-lines').textContent         = v ? v.split('\n').length : 0;
  document.getElementById('cnt-sentences').textContent     = v.trim() ? (v.match(/[.!?]+/g) || []).length : 0;
}

/* ── TEXT: Lorem ipsum ────────────────────────────────── */
var _loremWords = ['lorem','ipsum','dolor','sit','amet','consectetur','adipiscing','elit','sed','do','eiusmod','tempor','incididunt','ut','labore','et','dolore','magna','aliqua','enim','ad','minim','veniam','quis','nostrud','exercitation','ullamco','laboris','nisi','aliquip','ex','ea','commodo','consequat','duis','aute','irure','in','reprehenderit','voluptate','velit','esse','cillum','fugiat','nulla','pariatur','excepteur','sint','occaecat','cupidatat','non','proident','sunt','culpa','qui','officia','deserunt','mollit','anim','est','laborum'];
function _loremSentence() {
  var len = 8 + Math.floor(Math.random() * 10);
  var words = [];
  for (var i = 0; i < len; i++) words.push(_loremWords[Math.floor(Math.random() * _loremWords.length)]);
  words[0] = words[0].charAt(0).toUpperCase() + words[0].slice(1);
  return words.join(' ') + '.';
}
function _loremParagraph() {
  var sc = 4 + Math.floor(Math.random() * 4);
  var sents = [];
  for (var i = 0; i < sc; i++) sents.push(_loremSentence());
  return sents.join(' ');
}
function genLorem() {
  var count = Math.max(1, parseInt(document.getElementById('lorem-count').value) || 2);
  var type  = document.getElementById('lorem-type').value;
  var out = '';
  if (type === 'words') {
    var ws = [];
    for (var i = 0; i < count; i++) ws.push(_loremWords[Math.floor(Math.random() * _loremWords.length)]);
    out = ws.join(' ');
  } else if (type === 'sentences') {
    var ss = [];
    for (var i = 0; i < count; i++) ss.push(_loremSentence());
    out = ss.join(' ');
  } else {
    var ps = [];
    for (var i = 0; i < count; i++) ps.push(_loremParagraph());
    out = ps.join('\n\n');
  }
  document.getElementById('lorem-out').textContent = out;
}

/* ── JSON: Formatter ──────────────────────────────────── */
function jsonFormat() {
  var v   = document.getElementById('json-fmt-in').value;
  var err = document.getElementById('json-fmt-err');
  err.textContent = '';
  try {
    document.getElementById('json-fmt-out').textContent = JSON.stringify(JSON.parse(v), null, 2);
  } catch(e) {
    _setErr('json-fmt-err', 'Invalid JSON: ' + e.message, false);
    document.getElementById('json-fmt-out').textContent = '';
  }
}
function jsonValidate() {
  var v = document.getElementById('json-fmt-in').value;
  try {
    JSON.parse(v);
    _setErr('json-fmt-err', '✓ Valid JSON', true);
    document.getElementById('json-fmt-out').textContent = '';
  } catch(e) {
    _setErr('json-fmt-err', 'Invalid JSON: ' + e.message, false);
  }
}

/* ── JSON: Minifier ───────────────────────────────────── */
function jsonMinify() {
  var v = document.getElementById('json-min-in').value;
  try {
    document.getElementById('json-min-out').textContent = JSON.stringify(JSON.parse(v));
    document.getElementById('json-min-err').textContent = '';
  } catch(e) {
    _setErr('json-min-err', 'Invalid JSON: ' + e.message, false);
    document.getElementById('json-min-out').textContent = '';
  }
}

/* ── JSON: to CSV ─────────────────────────────────────── */
function jsonToCsv() {
  var v = document.getElementById('json-csv-in').value;
  try {
    var data = JSON.parse(v);
    if (!Array.isArray(data)) throw new Error('Root element must be a JSON array.');
    if (!data.length) { document.getElementById('json-csv-out').textContent = ''; return; }
    var keys = Object.keys(data[0]);
    var rows = [keys.join(',')];
    data.forEach(function(row) {
      rows.push(keys.map(function(k) {
        var val = (row[k] === null || row[k] === undefined) ? '' : String(row[k]);
        if (val.includes(',') || val.includes('"') || val.includes('\n')) {
          val = '"' + val.replace(/"/g, '""') + '"';
        }
        return val;
      }).join(','));
    });
    document.getElementById('json-csv-out').textContent = rows.join('\n');
    document.getElementById('json-csv-err').textContent = '';
  } catch(e) {
    _setErr('json-csv-err', 'Error: ' + e.message, false);
  }
}

/* ── WEB: HTML entity encode/decode ──────────────────── */
function htmlEncode() {
  var v = document.getElementById('html-in').value;
  var d = document.createElement('div');
  d.textContent = v;
  document.getElementById('html-out').textContent = d.innerHTML;
}
function htmlDecode() {
  var v = document.getElementById('html-in').value;
  var d = document.createElement('div');
  d.innerHTML = v;
  document.getElementById('html-out').textContent = d.textContent;
}

/* ── WEB: Colour converter ────────────────────────────── */
function _hexToRgb(hex) {
  hex = hex.replace(/^#/, '');
  if (hex.length === 3) hex = hex.split('').map(function(c) { return c + c; }).join('');
  if (hex.length !== 6) return null;
  var n = parseInt(hex, 16);
  return { r: (n >> 16) & 255, g: (n >> 8) & 255, b: n & 255 };
}
function _rgbToHsl(r, g, b) {
  r /= 255; g /= 255; b /= 255;
  var max = Math.max(r, g, b), min = Math.min(r, g, b), h, s;
  var l = (max + min) / 2;
  if (max === min) {
    h = s = 0;
  } else {
    var d = max - min;
    s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
    switch (max) {
      case r: h = (g - b) / d + (g < b ? 6 : 0); break;
      case g: h = (b - r) / d + 2; break;
      default: h = (r - g) / d + 4; break;
    }
    h /= 6;
  }
  return { h: Math.round(h * 360), s: Math.round(s * 100), l: Math.round(l * 100) };
}
function _hslToRgb(h, s, l) {
  s /= 100; l /= 100;
  var k = function(n) { return (n + h / 30) % 12; };
  var a = s * Math.min(l, 1 - l);
  var f = function(n) { return l - a * Math.max(-1, Math.min(k(n) - 3, Math.min(9 - k(n), 1))); };
  return { r: Math.round(f(0) * 255), g: Math.round(f(8) * 255), b: Math.round(f(4) * 255) };
}
function _updateColorUI(r, g, b) {
  var hex = '#' + [r, g, b].map(function(x) { return x.toString(16).padStart(2, '0'); }).join('');
  var hsl = _rgbToHsl(r, g, b);
  document.getElementById('col-hex').value    = hex;
  document.getElementById('col-picker').value = hex;
  document.getElementById('col-r').value      = r;
  document.getElementById('col-g').value      = g;
  document.getElementById('col-b').value      = b;
  document.getElementById('col-h').value      = hsl.h;
  document.getElementById('col-s').value      = hsl.s;
  document.getElementById('col-l').value      = hsl.l;
}
function colorFromHex() {
  var rgb = _hexToRgb(document.getElementById('col-hex').value);
  if (rgb) _updateColorUI(rgb.r, rgb.g, rgb.b);
}
function colorFromPicker() {
  var rgb = _hexToRgb(document.getElementById('col-picker').value);
  if (rgb) _updateColorUI(rgb.r, rgb.g, rgb.b);
}
function colorFromRgb() {
  var r = Math.min(255, Math.max(0, parseInt(document.getElementById('col-r').value) || 0));
  var g = Math.min(255, Math.max(0, parseInt(document.getElementById('col-g').value) || 0));
  var b = Math.min(255, Math.max(0, parseInt(document.getElementById('col-b').value) || 0));
  _updateColorUI(r, g, b);
}
function colorFromHsl() {
  var h = parseInt(document.getElementById('col-h').value) || 0;
  var s = parseInt(document.getElementById('col-s').value) || 0;
  var l = parseInt(document.getElementById('col-l').value) || 0;
  var rgb = _hslToRgb(h, s, l);
  _updateColorUI(rgb.r, rgb.g, rgb.b);
}
_updateColorUI(24, 179, 255);

/* ── WEB: Regex tester ────────────────────────────────── */
function _escHtml(s) {
  return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}
function regexTest() {
  var pat    = document.getElementById('rx-pattern').value;
  var flags  = document.getElementById('rx-flags').value.replace(/[^gimsuy]/g, '');
  var text   = document.getElementById('rx-text').value;
  var errEl  = document.getElementById('rx-err');
  var outEl  = document.getElementById('rx-out');
  var infoEl = document.getElementById('rx-info');
  errEl.textContent = '';
  if (!pat) { outEl.textContent = text; infoEl.textContent = ''; return; }
  try {
    var globalFlags = flags.includes('g') ? flags : flags + 'g';
    var re      = new RegExp(pat, globalFlags);
    var matches = Array.from(text.matchAll(re));
    infoEl.textContent = matches.length + ' match' + (matches.length !== 1 ? 'es' : '') + ' found';
    if (!matches.length) { outEl.textContent = text; return; }
    var result = '', last = 0;
    matches.forEach(function(m) {
      result += _escHtml(text.slice(last, m.index));
      result += '<mark class="regex-match">' + _escHtml(m[0]) + '</mark>';
      last = m.index + m[0].length;
    });
    result += _escHtml(text.slice(last));
    outEl.innerHTML = result;
  } catch(e) {
    _setErr('rx-err', 'Invalid regex: ' + e.message, false);
    outEl.textContent = '';
    infoEl.textContent = '';
  }
}

/* ── WEB: Diff ────────────────────────────────────────── */
function runDiff() {
  var a = document.getElementById('diff-a').value.split('\n');
  var b = document.getElementById('diff-b').value.split('\n');
  var out = [];
  var maxLen = Math.max(a.length, b.length);
  for (var i = 0; i < maxLen; i++) {
    var la = a[i], lb = b[i];
    if (la === undefined) {
      out.push('<span style="color:#51cf66">+ ' + _escHtml(lb) + '</span>');
    } else if (lb === undefined) {
      out.push('<span style="color:#ff6b6b">- ' + _escHtml(la) + '</span>');
    } else if (la !== lb) {
      out.push('<span style="color:#ff6b6b">- ' + _escHtml(la) + '</span>');
      out.push('<span style="color:#51cf66">+ ' + _escHtml(lb) + '</span>');
    } else {
      out.push('<span style="color:var(--text-muted)">  ' + _escHtml(la) + '</span>');
    }
  }
  document.getElementById('diff-out').innerHTML = out.join('\n');
}

/* ── CRYPTO: Hash (Web Crypto API) ───────────────────── */
async function genHash(algo) {
  var v     = document.getElementById('hash-in').value;
  var outEl = document.getElementById('hash-out');
  if (!v) { outEl.textContent = ''; return; }
  var enc = new TextEncoder().encode(v);
  var buf = await crypto.subtle.digest(algo, enc);
  var hex = Array.from(new Uint8Array(buf)).map(function(b) { return b.toString(16).padStart(2, '0'); }).join('');
  outEl.textContent = algo + ':\n' + hex;
}

/* ── CRYPTO: Hex encode/decode ───────────────────────── */
function hexEncode() {
  var v = document.getElementById('hex-in').value;
  _setErr('hex-err', '', false);
  var hex = Array.from(new TextEncoder().encode(v))
    .map(function(b) { return b.toString(16).padStart(2, '0'); })
    .join(' ');
  document.getElementById('hex-out').textContent = hex;
}
function hexDecode() {
  var v = document.getElementById('hex-in').value.trim().replace(/\s+/g, '');
  _setErr('hex-err', '', false);
  if (v.length % 2 !== 0) {
    _setErr('hex-err', 'Hex string must have an even number of characters.', false);
    return;
  }
  try {
    var bytes = [];
    for (var i = 0; i < v.length; i += 2) bytes.push(parseInt(v.slice(i, i + 2), 16));
    document.getElementById('hex-out').textContent = new TextDecoder().decode(new Uint8Array(bytes));
  } catch(e) {
    _setErr('hex-err', 'Invalid hex input.', false);
  }
}

/* ── CRYPTO: JWT decoder ─────────────────────────────── */
function decodeJwt() {
  var v     = document.getElementById('jwt-in').value.trim();
  var errEl = document.getElementById('jwt-err');
  errEl.textContent = '';
  if (!v) {
    document.getElementById('jwt-header').textContent  = '';
    document.getElementById('jwt-payload').textContent = '';
    return;
  }
  var parts = v.split('.');
  if (parts.length < 2) {
    _setErr('jwt-err', 'Not a valid JWT (must have at least 2 parts separated by dots).', false);
    return;
  }
  function b64UrlDecode(s) {
    s = s.replace(/-/g, '+').replace(/_/g, '/');
    while (s.length % 4) s += '=';
    try { return JSON.parse(decodeURIComponent(escape(atob(s)))); } catch(e) { return null; }
  }
  var header  = b64UrlDecode(parts[0]);
  var payload = b64UrlDecode(parts[1]);
  document.getElementById('jwt-header').textContent  = header  ? JSON.stringify(header,  null, 2) : '(decode error)';
  document.getElementById('jwt-payload').textContent = payload ? JSON.stringify(payload, null, 2) : '(decode error)';
}

/* ── NUMBERS: Base converter ─────────────────────────── */
function baseConvert(src) {
  var errEl = document.getElementById('base-err');
  errEl.textContent = '';
  var v = document.getElementById('base-' + src).value.trim();
  if (!v) {
    ['dec', 'bin', 'oct', 'hex'].forEach(function(b) {
      if (b !== src) document.getElementById('base-' + b).value = '';
    });
    return;
  }
  var n;
  if      (src === 'dec') n = parseInt(v, 10);
  else if (src === 'bin') n = parseInt(v, 2);
  else if (src === 'oct') n = parseInt(v, 8);
  else if (src === 'hex') n = parseInt(v, 16);
  if (isNaN(n)) { _setErr('base-err', 'Invalid ' + src + ' value.', false); return; }
  if (src !== 'dec') document.getElementById('base-dec').value = n.toString(10);
  if (src !== 'bin') document.getElementById('base-bin').value = n.toString(2);
  if (src !== 'oct') document.getElementById('base-oct').value = n.toString(8);
  if (src !== 'hex') document.getElementById('base-hex').value = n.toString(16).toUpperCase();
}

/* ── NUMBERS: Timestamp ──────────────────────────────── */
function tsConvert() {
  var v     = document.getElementById('ts-in').value.trim();
  var outEl = document.getElementById('ts-out');
  if (!v) { outEl.textContent = ''; return; }
  var n = parseInt(v, 10);
  if (isNaN(n)) { outEl.textContent = 'Invalid timestamp.'; return; }
  var ms = v.length >= 13 ? n : n * 1000;
  var d  = new Date(ms);
  outEl.textContent = [
    'UTC:    ' + d.toUTCString(),
    'Local:  ' + d.toLocaleString(),
    'ISO:    ' + d.toISOString(),
    'Date:   ' + d.toLocaleDateString(),
    'Time:   ' + d.toLocaleTimeString(),
  ].join('\n');
}
function tsNow() {
  document.getElementById('ts-in').value = Math.floor(Date.now() / 1000);
  tsConvert();
}

/* ── NUMBERS: Unit converter ─────────────────────────── */
var _unitDefs = {
  length: { m: 1, km: 1e-3, cm: 100, mm: 1000, mi: 6.21371e-4, yd: 1.09361, ft: 3.28084, 'in': 39.3701 },
  mass:   { kg: 1, g: 1000, lb: 2.20462, oz: 35.274, t: 0.001, stone: 0.157473 },
  data:   { B: 1, KB: 1/1024, MB: 1/1048576, GB: 1/1073741824, TB: 1/1099511627776, b: 8 },
  temp:   { c: null, f: null, k: null }
};
var _unitLabels = {
  length: { m: 'Meter', km: 'Kilometer', cm: 'Centimeter', mm: 'Millimeter', mi: 'Mile', yd: 'Yard', ft: 'Foot', 'in': 'Inch' },
  mass:   { kg: 'Kilogram', g: 'Gram', lb: 'Pound', oz: 'Ounce', t: 'Metric Ton', stone: 'Stone' },
  data:   { B: 'Byte', KB: 'Kilobyte', MB: 'Megabyte', GB: 'Gigabyte', TB: 'Terabyte', b: 'Bit' },
  temp:   { c: 'Celsius', f: 'Fahrenheit', k: 'Kelvin' }
};
function unitCatChange() {
  var cat  = document.getElementById('unit-cat').value;
  var keys = Object.keys(_unitLabels[cat]);
  ['unit-from', 'unit-to'].forEach(function(id, idx) {
    var sel = document.getElementById(id);
    sel.innerHTML = '';
    keys.forEach(function(k) {
      var o = document.createElement('option');
      o.value = k;
      o.textContent = _unitLabels[cat][k];
      sel.appendChild(o);
    });
    sel.selectedIndex = idx;
  });
  unitConvert();
}
function unitConvert() {
  var cat  = document.getElementById('unit-cat').value;
  var from = document.getElementById('unit-from').value;
  var to   = document.getElementById('unit-to').value;
  var val  = parseFloat(document.getElementById('unit-val').value);
  var outEl = document.getElementById('unit-out');
  if (isNaN(val)) { outEl.textContent = ''; return; }
  var result;
  if (cat === 'temp') {
    var c;
    if      (from === 'c') c = val;
    else if (from === 'f') c = (val - 32) * 5 / 9;
    else                   c = val - 273.15;
    if      (to === 'c')   result = c;
    else if (to === 'f')   result = c * 9 / 5 + 32;
    else                   result = c + 273.15;
  } else {
    var defs = _unitDefs[cat];
    result = val / defs[from] * defs[to];
  }
  outEl.textContent = val + ' ' + from.toUpperCase() + ' = ' + (Math.round(result * 1e8) / 1e8) + ' ' + to.toUpperCase();
}
unitCatChange();

/* ── CODE: CSS Minifier ──────────────────────────────── */
function cssMinify() {
  var v   = document.getElementById('css-in').value;
  var out = v
    .replace(/\/\*[\s\S]*?\*\//g, '')
    .replace(/\s*([{}:;,>~+])\s*/g, '$1')
    .replace(/\s+/g, ' ')
    .replace(/;\}/g, '}')
    .trim();
  document.getElementById('css-out').textContent = out;
}

/* ── CODE: String escaper ────────────────────────────── */
function escapeHtml() {
  var v = document.getElementById('esc-in').value;
  document.getElementById('esc-out').textContent = v
    .replace(/&/g,  '&amp;')
    .replace(/</g,  '&lt;')
    .replace(/>/g,  '&gt;')
    .replace(/"/g,  '&quot;')
    .replace(/'/g,  '&#39;');
}
function escapeJs() {
  var v = document.getElementById('esc-in').value;
  document.getElementById('esc-out').textContent = v
    .replace(/\\/g, '\\\\')
    .replace(/'/g,  "\\'")
    .replace(/"/g,  '\\"')
    .replace(/\n/g, '\\n')
    .replace(/\r/g, '\\r')
    .replace(/\t/g, '\\t');
}

/* ── CODE: Line tools ────────────────────────────────── */
function linesSort(desc) {
  var lines = document.getElementById('lines-in').value.split('\n');
  lines.sort(function(a, b) { return desc ? b.localeCompare(a) : a.localeCompare(b); });
  document.getElementById('lines-out').textContent = lines.join('\n');
}
function linesDedup() {
  var lines = document.getElementById('lines-in').value.split('\n');
  var seen = Object.create(null);
  var out  = [];
  lines.forEach(function(l) { if (!seen[l]) { out.push(l); seen[l] = true; } });
  document.getElementById('lines-out').textContent = out.join('\n');
}
function linesReverse() {
  var lines = document.getElementById('lines-in').value.split('\n');
  document.getElementById('lines-out').textContent = lines.reverse().join('\n');
}

/* ══════════════════════════════════════════════════════════
   EDUCATION TOOLS
   ══════════════════════════════════════════════════════════ */

/* ── EDUCATION: GPA Calculator ──────────────────────────── */
var _gradePoints = {
  'A+':4.0,'A':4.0,'A-':3.7,
  'B+':3.3,'B':3.0,'B-':2.7,
  'C+':2.3,'C':2.0,'C-':1.7,
  'D+':1.3,'D':1.0,'D-':0.7,
  'F':0.0
};
function calcGpa() {
  var lines = document.getElementById('gpa-in').value.trim().split('\n');
  var totalPoints = 0, totalCredits = 0, rows = [];
  var ok = true;
  lines.forEach(function(line, i) {
    line = line.trim();
    if (!line) return;
    var parts = line.trim().split(/\s+/);
    if (parts.length < 2) { ok = false; return; }
    var grade = parts[0].toUpperCase();
    var credits = parseFloat(parts[1]);
    if (isNaN(credits) || credits <= 0) { ok = false; return; }
    var pts = _gradePoints[grade];
    if (pts === undefined) { ok = false; return; }
    totalPoints += pts * credits;
    totalCredits += credits;
    rows.push('Course ' + (i + 1) + ': ' + grade + ' × ' + credits + ' cr = ' + (pts * credits).toFixed(2) + ' pts');
  });
  if (!ok || totalCredits === 0) {
    document.getElementById('gpa-out').textContent = 'Invalid input. Use: grade credits (e.g. "A 3").\nSupported grades: A+, A, A-, B+, B, B-, C+, C, C-, D+, D, D-, F';
    return;
  }
  var gpa = totalPoints / totalCredits;
  var out = rows.join('\n') + '\n\nTotal Credits : ' + totalCredits + '\nTotal Points  : ' + totalPoints.toFixed(2) + '\n\nGPA           : ' + gpa.toFixed(2);
  document.getElementById('gpa-out').textContent = out;
}

/* ── EDUCATION: Grade Percentage ─────────────────────────── */
function calcGradePercent() {
  var score = parseFloat(document.getElementById('grade-score').value);
  var total = parseFloat(document.getElementById('grade-total').value);
  if (isNaN(score) || isNaN(total) || total <= 0) {
    document.getElementById('grade-out').textContent = 'Please enter a valid score and total.';
    return;
  }
  var pct = (score / total) * 100;
  var letter;
  if      (pct >= 90) letter = 'A';
  else if (pct >= 80) letter = 'B';
  else if (pct >= 70) letter = 'C';
  else if (pct >= 60) letter = 'D';
  else                letter = 'F';
  document.getElementById('grade-out').textContent =
    'Score      : ' + score + ' / ' + total +
    '\nPercentage : ' + pct.toFixed(2) + '%' +
    '\nLetter     : ' + letter;
}

/* ── EDUCATION: Attendance Calculator ───────────────────── */
function calcAttendance() {
  var present = parseFloat(document.getElementById('att-present').value);
  var total   = parseFloat(document.getElementById('att-total').value);
  var minPct  = parseFloat(document.getElementById('att-min').value) || 75;
  if (isNaN(present) || isNaN(total) || total <= 0) {
    document.getElementById('att-out').textContent = 'Please enter valid values.';
    return;
  }
  var pct = (present / total) * 100;
  var daysShort = 0;
  if (pct < minPct) {
    /* how many more days needed to reach minimum */
    var denom = 1 - minPct / 100;
    daysShort = denom > 0 ? Math.ceil((minPct / 100 * total - present) / denom) : Infinity;
  }
  var status = pct >= minPct ? '✓ Meets requirement' : '✗ Below requirement';
  var out = 'Days Present : ' + present + ' / ' + total +
    '\nAttendance   : ' + pct.toFixed(2) + '%' +
    '\nRequired     : ' + minPct + '%' +
    '\nStatus       : ' + status;
  if (pct < minPct) {
    out += '\nDays needed to reach ' + minPct + '%: ' + (isFinite(daysShort) ? Math.max(0, daysShort) : 'N/A (100% required)');
  }
  document.getElementById('att-out').textContent = out;
}

/* ── EDUCATION: Reading Level (Flesch-Kincaid Grade) ─────── */
function _countSyllables(word) {
  word = word.toLowerCase().replace(/[^a-z]/g, '');
  if (!word) return 0;
  if (word.length <= 3) return 1;
  word = word.replace(/(?:[^laeiouy]es|[^laeiouy]ed|[aeiouy]$)/g, '');
  var count = (word.match(/[aeiouy]{1,2}/g) || []).length;
  return count || 1;
}
function calcReadingLevel() {
  var text = document.getElementById('rl-in').value.trim();
  if (!text) { document.getElementById('rl-out').textContent = 'Please enter some text.'; return; }
  var sentences = (text.match(/[.!?]+/g) || []).length || 1;
  var words     = text.trim().split(/\s+/).filter(Boolean);
  var wordCount = words.length;
  if (wordCount < 5) { document.getElementById('rl-out').textContent = 'Please enter more text for an accurate estimate.'; return; }
  var syllables = words.reduce(function(s, w) { return s + _countSyllables(w); }, 0);
  var fre = 206.835 - 1.015 * (wordCount / sentences) - 84.6 * (syllables / wordCount);
  var fkg = 0.39 * (wordCount / sentences) + 11.8 * (syllables / wordCount) - 15.59;
  fre = Math.max(0, Math.min(100, fre));
  fkg = Math.max(0, fkg);
  var freDesc;
  if      (fre >= 90) freDesc = 'Very Easy (Grade 5)';
  else if (fre >= 80) freDesc = 'Easy (Grade 6)';
  else if (fre >= 70) freDesc = 'Fairly Easy (Grade 7)';
  else if (fre >= 60) freDesc = 'Standard (Grades 8–9)';
  else if (fre >= 50) freDesc = 'Fairly Difficult (Grades 10–12)';
  else if (fre >= 30) freDesc = 'Difficult (College)';
  else                freDesc = 'Very Difficult (Professional)';
  document.getElementById('rl-out').textContent =
    'Words           : ' + wordCount +
    '\nSentences       : ' + sentences +
    '\nSyllables       : ' + syllables +
    '\n\nFlesch Reading Ease : ' + fre.toFixed(1) + ' — ' + freDesc +
    '\nFlesch-Kincaid Grade: ' + fkg.toFixed(1) + ' (US school grade level)';
}

/* ══════════════════════════════════════════════════════════
   FINANCE TOOLS
   ══════════════════════════════════════════════════════════ */

/* ── FINANCE: Loan Payment ───────────────────────────────── */
function calcLoan() {
  var P = parseFloat(document.getElementById('loan-principal').value);
  var r = parseFloat(document.getElementById('loan-rate').value) / 100 / 12;
  var n = parseInt(document.getElementById('loan-term').value);
  if (isNaN(P) || isNaN(r) || isNaN(n) || P <= 0 || n <= 0) {
    document.getElementById('loan-out').textContent = 'Please enter valid values.'; return;
  }
  var monthly, totalPaid, totalInterest;
  if (r === 0) {
    monthly = P / n;
  } else {
    monthly = P * (r * Math.pow(1 + r, n)) / (Math.pow(1 + r, n) - 1);
  }
  totalPaid     = monthly * n;
  totalInterest = totalPaid - P;
  document.getElementById('loan-out').textContent =
    'Monthly Payment  : $' + monthly.toFixed(2) +
    '\nTotal Paid       : $' + totalPaid.toFixed(2) +
    '\nTotal Interest   : $' + totalInterest.toFixed(2) +
    '\nLoan Amount      : $' + P.toFixed(2);
}

/* ── FINANCE: Compound Interest ─────────────────────────── */
function calcCompoundInterest() {
  var P = parseFloat(document.getElementById('ci-principal').value);
  var r = parseFloat(document.getElementById('ci-rate').value) / 100;
  var t = parseFloat(document.getElementById('ci-years').value);
  var n = parseInt(document.getElementById('ci-freq').value);
  if (isNaN(P) || isNaN(r) || isNaN(t) || isNaN(n) || P <= 0 || t <= 0) {
    document.getElementById('ci-out').textContent = 'Please enter valid values.'; return;
  }
  var A = P * Math.pow(1 + r / n, n * t);
  var interest = A - P;
  document.getElementById('ci-out').textContent =
    'Principal        : $' + P.toFixed(2) +
    '\nFinal Amount     : $' + A.toFixed(2) +
    '\nInterest Earned  : $' + interest.toFixed(2) +
    '\nTotal Return     : ' + ((interest / P) * 100).toFixed(2) + '%';
}

/* ── FINANCE: Tip Calculator ─────────────────────────────── */
function calcTip() {
  var bill   = parseFloat(document.getElementById('tip-bill').value);
  var pct    = parseFloat(document.getElementById('tip-pct').value) || 0;
  var people = parseInt(document.getElementById('tip-people').value) || 1;
  if (isNaN(bill) || bill < 0) {
    document.getElementById('tip-out').textContent = 'Please enter a valid bill amount.'; return;
  }
  var tip       = bill * (pct / 100);
  var total     = bill + tip;
  var perPerson = total / people;
  document.getElementById('tip-out').textContent =
    'Bill Amount      : $' + bill.toFixed(2) +
    '\nTip (' + pct + '%)       : $' + tip.toFixed(2) +
    '\nTotal            : $' + total.toFixed(2) +
    '\nPer Person       : $' + perPerson.toFixed(2) + (people > 1 ? ' (' + people + ' people)' : '');
}

/* ── FINANCE: ROI ────────────────────────────────────────── */
function calcRoi() {
  var init  = parseFloat(document.getElementById('roi-init').value);
  var final = parseFloat(document.getElementById('roi-final').value);
  if (isNaN(init) || isNaN(final) || init <= 0) {
    document.getElementById('roi-out').textContent = 'Please enter valid values.'; return;
  }
  var profit   = final - init;
  var roi      = (profit / init) * 100;
  document.getElementById('roi-out').textContent =
    'Initial Investment : $' + init.toFixed(2) +
    '\nFinal Value        : $' + final.toFixed(2) +
    '\nNet Profit / Loss  : $' + profit.toFixed(2) +
    '\nROI                : ' + roi.toFixed(2) + '%';
}

/* ══════════════════════════════════════════════════════════
   HEALTHCARE TOOLS
   ══════════════════════════════════════════════════════════ */

/* ── HEALTHCARE: BMI ─────────────────────────────────────── */
function bmiUnitToggle() {
  var unit = document.getElementById('bmi-unit').value;
  document.getElementById('bmi-metric-row').style.display   = unit === 'metric'   ? '' : 'none';
  document.getElementById('bmi-imperial-row').style.display = unit === 'imperial' ? '' : 'none';
}
function calcBmi() {
  var unit = document.getElementById('bmi-unit').value;
  var bmi;
  if (unit === 'metric') {
    var wt = parseFloat(document.getElementById('bmi-wt-kg').value);
    var ht = parseFloat(document.getElementById('bmi-ht-cm').value) / 100;
    if (isNaN(wt) || isNaN(ht) || ht <= 0) { document.getElementById('bmi-out').textContent = 'Please enter valid values.'; return; }
    bmi = wt / (ht * ht);
  } else {
    var lb = parseFloat(document.getElementById('bmi-wt-lb').value);
    var ins = parseFloat(document.getElementById('bmi-ht-in').value);
    if (isNaN(lb) || isNaN(ins) || ins <= 0) { document.getElementById('bmi-out').textContent = 'Please enter valid values.'; return; }
    bmi = (lb / (ins * ins)) * 703;
  }
  var cat;
  if      (bmi < 18.5) cat = 'Underweight';
  else if (bmi < 25)   cat = 'Normal weight';
  else if (bmi < 30)   cat = 'Overweight';
  else                 cat = 'Obese';
  document.getElementById('bmi-out').textContent =
    'BMI      : ' + bmi.toFixed(1) +
    '\nCategory : ' + cat +
    '\n\nRanges: <18.5 Underweight | 18.5–24.9 Normal | 25–29.9 Overweight | ≥30 Obese';
}

/* ── HEALTHCARE: Age Calculator ─────────────────────────── */
function calcAge() {
  var dob   = document.getElementById('age-dob').value;
  var asof  = document.getElementById('age-asof').value || new Date().toISOString().slice(0, 10);
  if (!dob) { document.getElementById('age-out').textContent = 'Please enter a date of birth.'; return; }
  var d1 = new Date(dob), d2 = new Date(asof);
  if (d1 >= d2) { document.getElementById('age-out').textContent = 'Date of birth must be before the as-of date.'; return; }
  var years  = d2.getFullYear() - d1.getFullYear();
  var months = d2.getMonth()    - d1.getMonth();
  var days   = d2.getDate()     - d1.getDate();
  if (days   < 0) { months--; var tmp = new Date(d2.getFullYear(), d2.getMonth(), 0); days += tmp.getDate(); }
  if (months < 0) { years--;  months += 12; }
  var totalDays = Math.floor((d2 - d1) / 86400000);
  document.getElementById('age-out').textContent =
    'Age        : ' + years + ' years, ' + months + ' months, ' + days + ' days' +
    '\nTotal days : ' + totalDays.toLocaleString();
}

/* ── HEALTHCARE: Ideal Body Weight (Devine formula) ─────── */
function calcIbw() {
  var htCm = parseFloat(document.getElementById('ibw-ht').value);
  var sex  = document.getElementById('ibw-sex').value;
  if (isNaN(htCm) || htCm < 100) { document.getElementById('ibw-out').textContent = 'Please enter a height ≥ 100 cm.'; return; }
  var htIn  = htCm / 2.54;
  var extra = Math.max(0, htIn - 60);
  var ibwKg = sex === 'male' ? 50 + 2.3 * extra : 45.5 + 2.3 * extra;
  var ibwLb = ibwKg * 2.205;
  document.getElementById('ibw-out').textContent =
    'Ideal Body Weight : ' + ibwKg.toFixed(1) + ' kg  (' + ibwLb.toFixed(1) + ' lb)' +
    '\nHealthy Range     : ' + (ibwKg * 0.9).toFixed(1) + '–' + (ibwKg * 1.1).toFixed(1) + ' kg' +
    '\n\n(Devine formula — for clinical estimation only)';
}

/* ── HEALTHCARE: Calorie Burn ───────────────────────────── */
function calcCalorieBurn() {
  var wt  = parseFloat(document.getElementById('cal-wt').value);
  var dur = parseFloat(document.getElementById('cal-dur').value);
  var met = parseFloat(document.getElementById('cal-act').value);
  if (isNaN(wt) || isNaN(dur) || wt <= 0 || dur <= 0) {
    document.getElementById('cal-out').textContent = 'Please enter valid weight and duration.'; return;
  }
  var calories = met * wt * (dur / 60);
  document.getElementById('cal-out').textContent =
    'Estimated Calories Burned : ' + Math.round(calories) + ' kcal' +
    '\nActivity MET              : ' + met +
    '\nDuration                  : ' + dur + ' minutes';
}

/* ══════════════════════════════════════════════════════════
   REAL ESTATE TOOLS
   ══════════════════════════════════════════════════════════ */

/* ── REAL ESTATE: Mortgage ───────────────────────────────── */
function calcMortgage() {
  var price = parseFloat(document.getElementById('mort-price').value);
  var down  = parseFloat(document.getElementById('mort-down').value) || 0;
  var rate  = parseFloat(document.getElementById('mort-rate').value) / 100 / 12;
  var term  = parseInt(document.getElementById('mort-term').value) * 12;
  var P = price - down;
  if (isNaN(price) || isNaN(rate) || P <= 0 || term <= 0) {
    document.getElementById('mort-out').textContent = 'Please enter valid values.'; return;
  }
  var monthly;
  if (rate === 0) { monthly = P / term; }
  else { monthly = P * (rate * Math.pow(1 + rate, term)) / (Math.pow(1 + rate, term) - 1); }
  var total     = monthly * term;
  var interest  = total - P;
  document.getElementById('mort-out').textContent =
    'Loan Amount      : $' + P.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) +
    '\nMonthly Payment  : $' + monthly.toFixed(2) +
    '\nTotal Paid       : $' + total.toFixed(2) +
    '\nTotal Interest   : $' + interest.toFixed(2) +
    '\nDown Payment     : $' + down.toFixed(2) + ' (' + ((down/price)*100).toFixed(1) + '%)';
}

/* ── REAL ESTATE: Square Footage ─────────────────────────── */
function calcSqft() {
  var lines  = document.getElementById('sqft-in').value.trim().split('\n');
  var unit   = document.getElementById('sqft-unit').value;
  var total  = 0, rows = [], ok = true;
  lines.forEach(function(line, i) {
    line = line.trim();
    if (!line) return;
    var parts = line.split(/[\s,x×*]+/);
    var l = parseFloat(parts[0]), w = parseFloat(parts[1]);
    if (isNaN(l) || isNaN(w)) { ok = false; return; }
    var area = l * w;
    total += area;
    rows.push('Room ' + (i + 1) + ': ' + l + ' × ' + w + ' = ' + area.toFixed(2) + ' sq ' + unit);
  });
  if (!ok || !rows.length) { document.getElementById('sqft-out').textContent = 'Invalid input. Use: length width (e.g. "12 10").'; return; }
  document.getElementById('sqft-out').textContent = rows.join('\n') + '\n\nTotal: ' + total.toFixed(2) + ' sq ' + unit;
}

/* ── REAL ESTATE: Property ROI ───────────────────────────── */
function calcPropRoi() {
  var price = parseFloat(document.getElementById('prop-price').value);
  var rent  = parseFloat(document.getElementById('prop-rent').value);
  var exp   = parseFloat(document.getElementById('prop-exp').value) || 0;
  if (isNaN(price) || isNaN(rent) || price <= 0) {
    document.getElementById('prop-out').textContent = 'Please enter valid values.'; return;
  }
  var annualRent = rent * 12;
  var noi        = annualRent - exp;
  var capRate    = (noi / price) * 100;
  var grossYield = (annualRent / price) * 100;
  document.getElementById('prop-out').textContent =
    'Annual Gross Rent   : $' + annualRent.toFixed(2) +
    '\nAnnual Expenses     : $' + exp.toFixed(2) +
    '\nNet Operating Income: $' + noi.toFixed(2) +
    '\n\nGross Yield (ROI)   : ' + grossYield.toFixed(2) + '%' +
    '\nCap Rate            : ' + capRate.toFixed(2) + '%';
}

/* ══════════════════════════════════════════════════════════
   HR & PAYROLL TOOLS
   ══════════════════════════════════════════════════════════ */

/* ── HR: Salary ↔ Hourly ─────────────────────────────────── */
function salFromAnnual() {
  var annual = parseFloat(document.getElementById('sal-annual').value);
  var hrs    = parseFloat(document.getElementById('sal-hrs').value) || 40;
  var wks    = parseFloat(document.getElementById('sal-wks').value) || 52;
  if (isNaN(annual)) { document.getElementById('sal-out').textContent = ''; return; }
  var hourly  = annual / (hrs * wks);
  var monthly = annual / 12;
  var weekly  = annual / wks;
  document.getElementById('sal-hourly').value = hourly.toFixed(2);
  document.getElementById('sal-out').textContent =
    'Annual    : $' + annual.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) +
    '\nMonthly   : $' + monthly.toFixed(2) +
    '\nWeekly    : $' + weekly.toFixed(2) +
    '\nHourly    : $' + hourly.toFixed(2) +
    '\n(' + hrs + ' hrs/week × ' + wks + ' weeks)';
}
function salFromHourly() {
  var hourly = parseFloat(document.getElementById('sal-hourly').value);
  var hrs    = parseFloat(document.getElementById('sal-hrs').value) || 40;
  var wks    = parseFloat(document.getElementById('sal-wks').value) || 52;
  if (isNaN(hourly)) { document.getElementById('sal-out').textContent = ''; return; }
  var annual  = hourly * hrs * wks;
  var monthly = annual / 12;
  var weekly  = hourly * hrs;
  document.getElementById('sal-annual').value = annual.toFixed(2);
  document.getElementById('sal-out').textContent =
    'Annual    : $' + annual.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) +
    '\nMonthly   : $' + monthly.toFixed(2) +
    '\nWeekly    : $' + weekly.toFixed(2) +
    '\nHourly    : $' + hourly.toFixed(2) +
    '\n(' + hrs + ' hrs/week × ' + wks + ' weeks)';
}

/* ── HR: Working Days Calculator ─────────────────────────── */
function calcWorkingDays() {
  var start = document.getElementById('wd-start').value;
  var end   = document.getElementById('wd-end').value;
  if (!start || !end) { document.getElementById('wd-out').textContent = 'Please select both dates.'; return; }
  var d1 = new Date(start + 'T00:00:00');
  var d2 = new Date(end   + 'T00:00:00');
  if (d1 > d2) { document.getElementById('wd-out').textContent = 'Start date must be before end date.'; return; }
  var working = 0, total = 0, cur = new Date(d1);
  while (cur <= d2) {
    total++;
    var day = cur.getDay();
    if (day !== 0 && day !== 6) working++;
    cur.setDate(cur.getDate() + 1);
  }
  document.getElementById('wd-out').textContent =
    'From         : ' + start +
    '\nTo           : ' + end +
    '\nCalendar Days: ' + total +
    '\nWeekend Days : ' + (total - working) +
    '\nWorking Days : ' + working;
}

/* ── HR: Take-Home Pay Estimator (simplified US) ─────────── */
function calcTakeHome() {
  var gross  = parseFloat(document.getElementById('pay-gross').value);
  var period = document.getElementById('pay-period').value;
  var status = document.getElementById('pay-status').value;
  if (isNaN(gross) || gross <= 0) { document.getElementById('pay-out').textContent = 'Please enter a valid gross pay amount.'; return; }
  /* convert to annual for bracket lookup */
  var annualMultiplier = { weekly: 52, biweekly: 26, semimonthly: 24, monthly: 12 }[period] || 12;
  var annualGross = gross * annualMultiplier;
  /* 2025 US federal income tax brackets (simplified, standard deduction) */
  var stdDeduction = { single: 14600, married: 29200, hoh: 21900 }[status] || 14600;
  var taxable = Math.max(0, annualGross - stdDeduction);
  /* single brackets */
  var brackets = status === 'married'
    ? [[23200,0.10],[94300,0.12],[201050,0.22],[383900,0.24],[487450,0.32],[731200,0.35],[Infinity,0.37]]
    : [[11600,0.10],[47150,0.12],[100525,0.22],[191950,0.24],[243725,0.32],[609350,0.35],[Infinity,0.37]];
  var tax = 0, prev = 0;
  brackets.forEach(function(b) {
    if (taxable > prev) { tax += (Math.min(taxable, b[0]) - prev) * b[1]; }
    prev = b[0];
  });
  var fica   = gross * 0.0765; /* 6.2% SS (up to wage base) + 1.45% Medicare — simplified estimate */
  var netPay = gross - (tax / annualMultiplier) - fica;
  document.getElementById('pay-out').textContent =
    'Gross Pay           : $' + gross.toFixed(2) +
    '\nFederal Income Tax  : $' + (tax / annualMultiplier).toFixed(2) +
    '\nFICA (SS + Medicare): $' + fica.toFixed(2) +
    '\nEstimated Net Pay   : $' + netPay.toFixed(2) +
    '\nEffective Tax Rate  : ' + ((tax / annualGross) * 100).toFixed(1) + '% (federal only)';
}

/* ══════════════════════════════════════════════════════════
   MARKETING TOOLS
   ══════════════════════════════════════════════════════════ */

/* ── MARKETING: Ad Character Counter ───────────────────────── */
function adccUpdate() {
  var text  = document.getElementById('adcc-text').value;
  var limit = parseInt(document.getElementById('adcc-platform').value);
  var len   = text.length;
  var remaining = limit - len;
  var pct   = Math.min(100, Math.round((len / limit) * 100));
  var color = remaining >= 0 ? (remaining < limit * 0.1 ? '#ffd43b' : '#51cf66') : '#ff6b6b';
  document.getElementById('adcc-out').textContent =
    len + ' / ' + limit + ' characters  (' + Math.abs(remaining) + (remaining >= 0 ? ' remaining' : ' over limit') + ')';
  document.getElementById('adcc-out').style.color = color;
}

/* ── MARKETING: Keyword Density ──────────────────────────── */
function calcKwDensity() {
  var text = document.getElementById('kd-text').value.trim();
  var kw   = document.getElementById('kd-kw').value.trim().toLowerCase();
  if (!text || !kw) { document.getElementById('kd-out').textContent = 'Please enter content and a keyword.'; return; }
  var words   = text.toLowerCase().split(/\s+/).filter(Boolean);
  var kwWords = kw.split(/\s+/).filter(Boolean);
  var count   = 0;
  if (kwWords.length === 1) {
    count = words.filter(function(w) { return w.replace(/[^a-z0-9]/g, '') === kw.replace(/[^a-z0-9]/g, ''); }).length;
  } else {
    var pattern = kw.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    var re = new RegExp(pattern, 'gi');
    count = (text.match(re) || []).length;
  }
  var density = words.length > 0 ? ((kwWords.length === 1 ? count : count * kwWords.length) / words.length) * 100 : 0;
  var advice = density < 0.5 ? 'Too low — consider using the keyword more.' : density > 3 ? 'Too high — may appear spammy. Aim for 1–2%.' : 'Good keyword density (1–2% is the sweet spot for SEO).';
  document.getElementById('kd-out').textContent =
    'Keyword      : "' + kw + '"' +
    '\nOccurrences  : ' + count +
    '\nTotal Words  : ' + words.length +
    '\nDensity      : ' + density.toFixed(2) + '%' +
    '\n\n' + advice;
}

/* ── MARKETING: Flesch Readability Score ─────────────────── */
function calcFlesch() {
  var text = document.getElementById('fre-in').value.trim();
  if (!text) { document.getElementById('fre-out').textContent = 'Please enter some text.'; return; }
  var sentences = (text.match(/[.!?]+/g) || []).length || 1;
  var words     = text.trim().split(/\s+/).filter(Boolean);
  var wordCount = words.length;
  if (wordCount < 5) { document.getElementById('fre-out').textContent = 'Please enter more text for an accurate score.'; return; }
  var syllables = words.reduce(function(s, w) { return s + _countSyllables(w); }, 0);
  var fre = 206.835 - 1.015 * (wordCount / sentences) - 84.6 * (syllables / wordCount);
  fre = Math.max(0, Math.min(100, fre));
  var desc, audience;
  if      (fre >= 90) { desc = 'Very Easy';       audience = 'Elementary school (Grade 5)'; }
  else if (fre >= 80) { desc = 'Easy';             audience = 'Middle school (Grade 6)'; }
  else if (fre >= 70) { desc = 'Fairly Easy';      audience = 'Middle school (Grade 7)'; }
  else if (fre >= 60) { desc = 'Standard';         audience = 'High school (Grades 8–9)'; }
  else if (fre >= 50) { desc = 'Fairly Difficult'; audience = 'High school (Grades 10–12)'; }
  else if (fre >= 30) { desc = 'Difficult';        audience = 'College level'; }
  else                { desc = 'Very Difficult';   audience = 'Professional / Academic'; }
  var tip = fre >= 60 ? 'Great for marketing copy and web content.' : 'Consider shorter sentences and simpler words for better engagement.';
  document.getElementById('fre-out').textContent =
    'Flesch Reading Ease : ' + fre.toFixed(1) + ' / 100' +
    '\nLevel               : ' + desc +
    '\nAudience            : ' + audience +
    '\nWords               : ' + wordCount +
    '\nSentences           : ' + sentences +
    '\nAvg words/sentence  : ' + (wordCount / sentences).toFixed(1) +
    '\n\n' + tip;
}
PAGEJS;

require_once __DIR__ . '/../includes/footer.php';
?>
