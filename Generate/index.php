<?php
$page_title  = 'Generate Code with AI – CodeFoundry';
$active_page = 'ide';
$page_styles = <<<'PAGECSS'
/* ── Hero ───────────────────────────────────────────────── */
.gen-hero {
  text-align: center;
  padding: 64px 24px 40px;
}
.gen-hero h1 {
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  font-weight: 900;
  margin: 0 0 14px;
  letter-spacing: -0.5px;
  line-height: 1.15;
}
.gen-hero h1 span { color: var(--primary); }
.gen-hero p {
  font-size: 1.05rem;
  color: var(--text-muted);
  max-width: 560px;
  margin: 0 auto;
  line-height: 1.7;
}

/* ── Generator card ─────────────────────────────────────── */
.gen-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 32px;
  max-width: 720px;
  margin: 0 auto 48px;
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.gen-row {
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.gen-label {
  font-size: 13px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
}
.gen-lang-select {
  background: var(--navy-2);
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: var(--button-radius);
  padding: 9px 14px;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  appearance: none;
  -webkit-appearance: none;
  width: 100%;
  max-width: 280px;
  transition: border-color .2s;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2392a3bb' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
  padding-right: 32px;
}
.gen-lang-select:focus {
  outline: none;
  border-color: var(--primary);
}

.gen-prompt {
  width: 100%;
  background: #0d1117;
  color: var(--text);
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 12px 14px;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  line-height: 1.6;
  resize: vertical;
  min-height: 120px;
  box-sizing: border-box;
  outline: none;
  transition: border-color .2s;
}
.gen-prompt:focus { border-color: var(--primary); }

.gen-actions {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.gen-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 10px 20px;
  border-radius: var(--button-radius);
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: background .2s, color .2s, border-color .2s;
  white-space: nowrap;
}
.gen-btn.primary {
  background: var(--primary);
  color: #fff;
}
.gen-btn.primary:hover:not(:disabled) {
  background: var(--primary-hover);
}
.gen-btn.primary:disabled {
  background: #1e3a4a;
  color: #38bdf866;
  cursor: not-allowed;
}
.gen-btn.ghost {
  background: transparent;
  color: var(--text-muted);
  border: 1px solid var(--border-color);
}
.gen-btn.ghost:hover { color: var(--text); border-color: var(--text-muted); }
.gen-btn.success {
  background: #22c55e;
  color: #fff;
}
.gen-btn.success:hover { background: #16a34a; }

.gen-shortcut {
  font-size: 12px;
  color: var(--text-subtle);
}

/* ── Result section ─────────────────────────────────────── */
.gen-result {
  display: none;
  flex-direction: column;
  gap: 12px;
}
.gen-result.visible { display: flex; }

.gen-result-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 8px;
}
.gen-result-title {
  font-size: 13px;
  font-weight: 700;
  color: var(--text-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
}
.gen-result-actions { display: flex; gap: 8px; flex-wrap: wrap; }

.gen-code-output {
  background: #0d1117;
  border: 1px solid var(--border-color);
  border-radius: 8px;
  padding: 16px;
  font-family: 'Courier New', Courier, monospace;
  font-size: 13px;
  line-height: 1.6;
  color: #e2e8f0;
  white-space: pre;
  overflow-x: auto;
  max-height: 400px;
  overflow-y: auto;
  tab-size: 2;
}

/* ── Spinner ────────────────────────────────────────────── */
.spinner {
  display: inline-block;
  width: 14px;
  height: 14px;
  border: 2px solid rgba(255,255,255,.3);
  border-top-color: #fff;
  border-radius: 50%;
  animation: spin .7s linear infinite;
  vertical-align: middle;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Templates section ──────────────────────────────────── */
.templates-section {
  max-width: 1100px;
  margin: 0 auto 80px;
  padding: 0 24px;
}
.templates-heading {
  font-size: 1.4rem;
  font-weight: 900;
  margin: 0 0 6px;
  letter-spacing: -.3px;
}
.templates-subheading {
  font-size: 14px;
  color: var(--text-muted);
  margin: 0 0 28px;
}
.templates-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 16px;
}
.template-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 20px 22px;
  cursor: pointer;
  transition: border-color .2s, transform .15s;
  display: flex;
  flex-direction: column;
  gap: 6px;
}
.template-card:hover {
  border-color: var(--primary);
  transform: translateY(-2px);
}
.template-card-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
}
.template-icon { font-size: 20px; line-height: 1; }
.template-lang-badge {
  font-size: 11px;
  font-weight: 700;
  background: var(--navy-2);
  border: 1px solid var(--border-color);
  border-radius: 20px;
  padding: 2px 10px;
  color: var(--primary);
  white-space: nowrap;
}
.template-title {
  font-size: 14px;
  font-weight: 800;
  margin: 4px 0 0;
  line-height: 1.3;
}
.template-desc {
  font-size: 13px;
  color: var(--text-muted);
  line-height: 1.5;
}

/* ── Page wrapper padding ───────────────────────────────── */
.gen-page { padding: 0 24px; }

/* ── Error state ────────────────────────────────────────── */
.gen-error {
  display: none;
  align-items: center;
  gap: 8px;
  background: #2d1515;
  border: 1px solid #7f1d1d;
  border-radius: 8px;
  padding: 12px 14px;
  font-size: 13px;
  color: #fca5a5;
}
.gen-error.visible { display: flex; }
PAGECSS;

require_once dirname(__DIR__) . '/includes/header.php';
?>

<div class="gen-page">

  <!-- ── Hero ─────────────────────────────────────────────── -->
  <div class="gen-hero">
    <h1><iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon> Generate Code <span>with AI</span></h1>
    <p>Describe what you need and let AI write the code for you. Choose from sample templates below or type your own prompt.</p>
  </div>

  <!-- ── Generator card ─────────────────────────────────────── -->
  <div class="gen-card" id="genCard">

    <!-- Language -->
    <div class="gen-row">
      <label class="gen-label" for="genLangSelect">Language</label>
      <select id="genLangSelect" class="gen-lang-select" aria-label="Programming language">
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
    </div>

    <!-- Prompt -->
    <div class="gen-row">
      <label class="gen-label" for="genPrompt">Describe the code you want</label>
      <textarea id="genPrompt" class="gen-prompt" rows="5"
        placeholder="e.g. Write a function that reads a CSV file and returns a list of dictionaries, one per row, with the header row as keys."></textarea>
    </div>

    <!-- Error -->
    <div class="gen-error" id="genError" role="alert">
      <iconify-icon icon="lucide:alert-circle" aria-hidden="true"></iconify-icon>
      <span id="genErrorText"></span>
    </div>

    <!-- Actions -->
    <div class="gen-actions">
      <button id="genSubmitBtn" class="gen-btn primary">
        <iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon>
        Generate
      </button>
      <span class="gen-shortcut">or press <kbd>Ctrl</kbd>+<kbd>Enter</kbd></span>
    </div>

    <!-- Result -->
    <div class="gen-result" id="genResult">
      <div class="gen-result-header">
        <span class="gen-result-title">Generated Code</span>
        <div class="gen-result-actions">
          <button id="genCopyBtn" class="gen-btn ghost">
            <iconify-icon icon="lucide:copy" aria-hidden="true"></iconify-icon>
            Copy
          </button>
          <button id="genOpenIdeBtn" class="gen-btn success">
            <iconify-icon icon="lucide:code-2" aria-hidden="true"></iconify-icon>
            Open in IDE
          </button>
        </div>
      </div>
      <pre class="gen-code-output" id="genCodeOutput"></pre>
    </div>

  </div><!-- /gen-card -->

  <!-- ── Sample templates ─────────────────────────────────── -->
  <div class="templates-section">
    <div class="templates-heading">Sample Templates</div>
    <div class="templates-subheading">Click any template to fill the prompt above.</div>
    <div class="templates-grid" id="templatesGrid">

      <div class="template-card" data-lang="python"
           data-prompt="Write a function that reads a CSV file and returns a list of dictionaries, one per row, using the header row as keys. Handle file-not-found errors gracefully.">
        <div class="template-card-top">
          <span class="template-icon">📄</span>
          <span class="template-lang-badge">Python</span>
        </div>
        <div class="template-title">CSV File Parser</div>
        <div class="template-desc">Read a CSV into a list of dicts with error handling.</div>
      </div>

      <div class="template-card" data-lang="javascript"
           data-prompt="Write an Express.js REST API server with GET, POST, PUT, and DELETE endpoints for a simple to-do list stored in memory. Include basic input validation.">
        <div class="template-card-top">
          <span class="template-icon">🌐</span>
          <span class="template-lang-badge">JavaScript</span>
        </div>
        <div class="template-title">REST API Server</div>
        <div class="template-desc">Express.js CRUD API for a to-do list.</div>
      </div>

      <div class="template-card" data-lang="python"
           data-prompt="Implement a binary search algorithm that returns the index of a target value in a sorted list, or -1 if not found. Include docstring and example usage.">
        <div class="template-card-top">
          <span class="template-icon">🔍</span>
          <span class="template-lang-badge">Python</span>
        </div>
        <div class="template-title">Binary Search</div>
        <div class="template-desc">Search a sorted list efficiently in O(log n).</div>
      </div>

      <div class="template-card" data-lang="go"
           data-prompt="Write a Go function that makes an HTTP GET request to a given URL, handles timeouts, retries up to 3 times on failure, and returns the response body as a string.">
        <div class="template-card-top">
          <span class="template-icon">🔗</span>
          <span class="template-lang-badge">Go</span>
        </div>
        <div class="template-title">HTTP Client with Retry</div>
        <div class="template-desc">Resilient HTTP GET with timeout and retries.</div>
      </div>

      <div class="template-card" data-lang="python"
           data-prompt="Implement a Fibonacci sequence generator using memoization (functools.lru_cache). Add a function to print the first N Fibonacci numbers.">
        <div class="template-card-top">
          <span class="template-icon">🔢</span>
          <span class="template-lang-badge">Python</span>
        </div>
        <div class="template-title">Fibonacci with Memoization</div>
        <div class="template-desc">Efficient Fibonacci using lru_cache.</div>
      </div>

      <div class="template-card" data-lang="javascript"
           data-prompt="Write a JavaScript function that validates an email address using a regular expression. Return true if valid, false otherwise. Include a few test cases.">
        <div class="template-card-top">
          <span class="template-icon">✉️</span>
          <span class="template-lang-badge">JavaScript</span>
        </div>
        <div class="template-title">Email Validator</div>
        <div class="template-desc">Regex-based email validation with tests.</div>
      </div>

      <div class="template-card" data-lang="c++"
           data-prompt="Implement a singly linked list in C++ with methods to insert at head, insert at tail, delete a node by value, and print the list.">
        <div class="template-card-top">
          <span class="template-icon">🔗</span>
          <span class="template-lang-badge">C++</span>
        </div>
        <div class="template-title">Linked List</div>
        <div class="template-desc">Singly linked list with insert and delete.</div>
      </div>

      <div class="template-card" data-lang="python"
           data-prompt="Write a Python script that benchmarks and compares the runtime of bubble sort, merge sort, and quicksort on a list of 1000 random integers. Print a summary table.">
        <div class="template-card-top">
          <span class="template-icon">⚡</span>
          <span class="template-lang-badge">Python</span>
        </div>
        <div class="template-title">Sort Algorithm Benchmark</div>
        <div class="template-desc">Compare bubble, merge, and quicksort timings.</div>
      </div>

      <div class="template-card" data-lang="php"
           data-prompt="Write PHP functions to perform CRUD operations (create, read, update, delete) on a MySQL database table called 'users' with fields id, name, email, and created_at. Use PDO with prepared statements.">
        <div class="template-card-top">
          <span class="template-icon">🗄️</span>
          <span class="template-lang-badge">PHP</span>
        </div>
        <div class="template-title">Database CRUD (PDO)</div>
        <div class="template-desc">MySQL CRUD with PDO and prepared statements.</div>
      </div>

      <div class="template-card" data-lang="typescript"
           data-prompt="Write a TypeScript generic Stack class with push, pop, peek, isEmpty, and size methods. Include proper type annotations and JSDoc comments.">
        <div class="template-card-top">
          <span class="template-icon">📦</span>
          <span class="template-lang-badge">TypeScript</span>
        </div>
        <div class="template-title">Generic Stack</div>
        <div class="template-desc">Type-safe stack with full JSDoc annotations.</div>
      </div>

      <div class="template-card" data-lang="bash"
           data-prompt="Write a Bash script that monitors a directory for new files, logs each new file name with a timestamp to a log file, and sends an alert if the directory grows beyond 100 files.">
        <div class="template-card-top">
          <span class="template-icon">🖥️</span>
          <span class="template-lang-badge">Bash</span>
        </div>
        <div class="template-title">Directory Monitor</div>
        <div class="template-desc">Watch a directory and alert on growth.</div>
      </div>

      <div class="template-card" data-lang="rust"
           data-prompt="Write a Rust program that reads a text file, counts the frequency of each word (case-insensitive), and prints the top 10 most frequent words with their counts.">
        <div class="template-card-top">
          <span class="template-icon">📊</span>
          <span class="template-lang-badge">Rust</span>
        </div>
        <div class="template-title">Word Frequency Counter</div>
        <div class="template-desc">Count and rank word frequencies in a file.</div>
      </div>

    </div>
  </div><!-- /templates-section -->

</div><!-- /gen-page -->

<?php
$page_scripts = <<<'PAGEJS'
(function () {
  'use strict';

  const langSelect  = document.getElementById('genLangSelect');
  const promptTA    = document.getElementById('genPrompt');
  const submitBtn   = document.getElementById('genSubmitBtn');
  const resultEl    = document.getElementById('genResult');
  const codeOutput  = document.getElementById('genCodeOutput');
  const copyBtn     = document.getElementById('genCopyBtn');
  const openIdeBtn  = document.getElementById('genOpenIdeBtn');
  const errorEl     = document.getElementById('genError');
  const errorTextEl = document.getElementById('genErrorText');

  let lastGeneratedCode = '';

  /* ── Error helper ─────────────────────────────────────── */
  function showError(msg) {
    errorTextEl.textContent = msg;
    errorEl.classList.add('visible');
    resultEl.classList.remove('visible');
  }
  function clearError() {
    errorEl.classList.remove('visible');
  }

  /* ── Generate ─────────────────────────────────────────── */
  async function generate() {
    const prompt = promptTA.value.trim();
    if (!prompt) { promptTA.focus(); return; }

    clearError();
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Generating…';
    resultEl.classList.remove('visible');

    try {
      const res  = await fetch('/IDE/codegen.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({
          action:   'generate',
          prompt:   prompt,
          language: langSelect.value,
        }),
      });
      const data = await res.json();

      if (!res.ok) {
        if (data.error_code === 'subscription_required') {
          window.location.href = '/Pricing/';
          return;
        }
        showError(data.error || 'An error occurred. Please try again.');
        return;
      }

      lastGeneratedCode = data.code || '';
      codeOutput.textContent = lastGeneratedCode;
      resultEl.classList.add('visible');
      resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } catch (err) {
      showError('Network error: ' + err.message);
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<iconify-icon icon="lucide:sparkles" aria-hidden="true"></iconify-icon> Generate';
    }
  }

  submitBtn.addEventListener('click', generate);

  promptTA.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && (e.ctrlKey || e.metaKey)) {
      e.preventDefault();
      generate();
    }
  });

  /* ── Copy button ──────────────────────────────────────── */
  copyBtn.addEventListener('click', function () {
    if (!lastGeneratedCode) return;
    navigator.clipboard.writeText(lastGeneratedCode).then(function () {
      const orig = copyBtn.innerHTML;
      copyBtn.innerHTML = '<iconify-icon icon="lucide:check" aria-hidden="true"></iconify-icon> Copied!';
      setTimeout(function () { copyBtn.innerHTML = orig; }, 1800);
    }).catch(function () {
      // Fallback for older browsers
      const ta = document.createElement('textarea');
      ta.value = lastGeneratedCode;
      ta.style.position = 'fixed';
      ta.style.opacity  = '0';
      document.body.appendChild(ta);
      ta.select();
      document.execCommand('copy');
      document.body.removeChild(ta);
    });
  });

  /* ── Open in IDE ──────────────────────────────────────── */
  openIdeBtn.addEventListener('click', function () {
    if (!lastGeneratedCode) return;
    sessionStorage.setItem('cf_generated_code',     lastGeneratedCode);
    sessionStorage.setItem('cf_generated_language', langSelect.value);
    window.location.href = '/IDE/';
  });

  /* ── Template cards ───────────────────────────────────── */
  document.querySelectorAll('.template-card').forEach(function (card) {
    card.addEventListener('click', function () {
      const lang   = card.dataset.lang;
      const prompt = card.dataset.prompt;
      if (lang) langSelect.value = lang;
      if (prompt) {
        promptTA.value = prompt;
        promptTA.focus();
        promptTA.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  });
})();
PAGEJS;

require_once dirname(__DIR__) . '/includes/footer.php';
?>
