<?php
$tutorial_title = 'JavaScript';
$tutorial_slug  = 'javascript';
$quiz_slug      = 'javascript';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>JavaScript is a high-level, just-in-time compiled, multi-paradigm language with dynamic typing. It is the only language that runs natively in browsers, making it indispensable for web development. Over the years it has also expanded to servers (Node.js), mobile (React Native), desktop (Electron), and embedded systems.</p><p>This tier revisits the language foundations with a focus on the ECMAScript specification and how the browser environment exposes its APIs. You will learn the difference between the JS language and the browser runtime, and why understanding both is essential for professional development.</p>',
        'concepts' => [
            'ECMAScript standard vs. browser APIs (DOM, BOM, Fetch, etc.)',
            'JavaScript engines: V8, SpiderMonkey, JavaScriptCore',
            'Single-threaded execution model and the event loop',
            'let, const, var: scope, hoisting, and temporal dead zone',
            'Primitive types and the Object wrapper types (String, Number, Boolean)',
            'Automatic type coercion: when it helps and when it bites',
            'The typeof and instanceof operators',
        ],
        'code' => [
            'title'   => 'Hoisting and temporal dead zone',
            'lang'    => 'javascript',
            'content' =>
"// var is hoisted and initialised to undefined
console.log(x); // undefined (not ReferenceError)
var x = 10;

// let/const are hoisted but NOT initialised — TDZ
try {
  console.log(y); // ReferenceError: Cannot access 'y' before initialization
} catch (e) { console.log(e.message); }
let y = 20;

// Function declarations are fully hoisted
greet('World'); // 'Hello, World!'
function greet(name) { console.log(`Hello, \${name}!`); }

// Function expressions are NOT
try { sayBye('World'); } catch (e) { console.log(e.message); }
const sayBye = name => `Goodbye, \${name}!`;",
        ],
        'tips' => [
            'Use const by default; switch to let only when reassignment is genuinely needed.',
            'Bookmark MDN Web Docs (developer.mozilla.org) — it is the authoritative JavaScript reference.',
            'Read the ECMAScript spec at tc39.es/ecma262 when you need to understand exact language semantics.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>JavaScript\'s built-in objects — Array, Object, String, Number, Math, Date, RegExp — provide a rich standard library. Knowing which method does what, and understanding their time complexity, dramatically improves the quality and conciseness of everyday code.</p><p>The DOM (Document Object Model) is the in-memory representation of the HTML page. Manipulating it with JavaScript — creating, updating, and removing elements, responding to events — is the foundation of every interactive web application.</p>',
        'concepts' => [
            'String methods: split, join, trim, padStart, padEnd, includes, startsWith, replaceAll',
            'Array methods: flat, flatMap, Array.from, Array.isArray, fill, copyWithin',
            'Object methods: Object.keys(), values(), entries(), assign(), freeze(), fromEntries()',
            'Math methods: Math.min/max, round, floor, ceil, abs, pow, sqrt, random',
            'Date object: new Date(), getFullYear/Month/Date, toISOString(), Date.now()',
            'Regular expressions: literals, flags, test(), match(), matchAll(), replace()',
            'DOM selection: getElementById, querySelector, querySelectorAll',
            'DOM manipulation: textContent, innerHTML, classList, setAttribute, style',
        ],
        'code' => [
            'title'   => 'DOM manipulation — live character counter',
            'lang'    => 'javascript',
            'content' =>
"const textarea = document.querySelector('#bio');
const counter  = document.querySelector('#char-count');
const MAX      = 280;

function updateCounter() {
  const remaining = MAX - textarea.value.length;
  counter.textContent = `\${remaining} characters remaining`;
  counter.className = remaining < 20 ? 'counter--warning' : 'counter';
}

textarea.addEventListener('input', updateCounter);
updateCounter(); // Initialise on page load",
        ],
        'tips' => [
            'Cache DOM queries in variables — repeated document.querySelector() calls inside loops are slow.',
            'Use classList.add/remove/toggle/contains instead of manipulating className strings directly.',
            'Learn ten core array methods deeply (map, filter, reduce, find, some, every, flat, flatMap, sort, splice) — they replace 90% of loops.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Closures and the module pattern are the intermediate concepts that unlock real architectural power in JavaScript. A closure is a function that remembers the scope in which it was created, even after that scope has exited — enabling private state, factory functions, and partial application.</p><p>The ES Module system (import/export) replaces the old IIFE module pattern with native language support. Understanding how module bundlers (Webpack, Vite, Rollup) use modules, tree-shaking, and code-splitting is essential for modern front-end development.</p>',
        'concepts' => [
            'Closures and lexical scoping — private counters, factory functions',
            'Immediately Invoked Function Expressions (IIFE) and the old module pattern',
            'ES Modules: named exports, default exports, re-exports, barrel files',
            'Dynamic import() for lazy code loading',
            'this binding: implicit, explicit (call/apply/bind), arrow functions',
            'Prototype chain and ES6 class syntax sugar',
            'Error types: Error, TypeError, RangeError, SyntaxError; custom error classes',
            'JSON: JSON.stringify() and JSON.parse() with reviver/replacer',
        ],
        'code' => [
            'title'   => 'Closure-based counter with private state',
            'lang'    => 'javascript',
            'content' =>
"function createCounter(initial = 0) {
  let count = initial; // private via closure

  return {
    increment: (step = 1) => { count += step; return count; },
    decrement: (step = 1) => { count -= step; return count; },
    reset:     ()         => { count = initial; },
    get value()            { return count; },
  };
}

const c = createCounter(10);
console.log(c.increment(5)); // 15
console.log(c.decrement(3)); // 12
console.log(c.value);        // 12
c.reset();
console.log(c.value);        // 10

// count is truly inaccessible from outside
console.log(c.count);        // undefined",
        ],
        'tips' => [
            'Design closures to expose a minimal public API — only surface what callers need.',
            'Use barrel index.js files to simplify imports from a module directory.',
            'Always handle JSON.parse() in a try/catch — malformed JSON throws a SyntaxError.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Asynchronous patterns are at the heart of JavaScript\'s power and complexity. Promises, async/await, and the async iterator protocol let you orchestrate complex multi-step operations. Performance optimisation involves understanding microtasks vs. macrotasks, debouncing and throttling, and the Web Workers API for offloading heavy computation off the main thread.</p><p>The browser storage landscape — localStorage, sessionStorage, IndexedDB, cookies, and the Cache API — gives you multiple options for client-side persistence, each with different capacity, access patterns, and privacy implications.</p>',
        'concepts' => [
            'Promise internals: pending/fulfilled/rejected states; executor function',
            'Promise chaining and error propagation through .catch()',
            'Async iterators and for-await-of loops',
            'Microtask queue vs. macrotask queue (setTimeout, setInterval vs. queueMicrotask)',
            'Debounce and throttle patterns for high-frequency events',
            'Web Workers: postMessage, structured clone algorithm, shared memory (SharedArrayBuffer)',
            'localStorage / sessionStorage: getItem, setItem, removeItem, storage quota',
            'IndexedDB basics: object stores, transactions, indices, IDBRequest',
        ],
        'code' => [
            'title'   => 'Debounce utility function',
            'lang'    => 'javascript',
            'content' =>
"/**
 * Returns a debounced version of fn that fires only after
 * `delay` ms have passed since the last invocation.
 */
function debounce(fn, delay) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

const searchInput = document.querySelector('#search');

const handleSearch = debounce(async (event) => {
  const query = event.target.value.trim();
  if (!query) return;
  const results = await fetchResults(query);
  renderResults(results);
}, 300);

searchInput.addEventListener('input', handleSearch);",
        ],
        'tips' => [
            'Throttle scroll/resize handlers that run expensive DOM reads — they fire hundreds of times per second.',
            'Move CPU-heavy tasks (image processing, encryption, large array sorts) to a Web Worker.',
            'Prefer IndexedDB over localStorage for large or structured data; localStorage is synchronous and size-limited.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert JavaScript engineers deeply understand the V8 runtime: how JIT compilation, hidden classes, and deoptimisation work; how to read and interpret heap snapshots and flame graphs from Chrome DevTools; and how to write code that cooperates with the garbage collector instead of fighting it.</p><p>Advanced patterns — Proxy/Reflect-based metaprogramming, reactive state engines, structural sharing with persistent data structures, and parser/interpreter construction using JS — represent the frontier of the language. Contributing to TC39 proposals and open-source runtimes is the hallmark of the expert practitioner.</p>',
        'concepts' => [
            'V8 optimisation: monomorphic vs. polymorphic call sites, hidden classes, deoptimisation',
            'Heap snapshots and allocation timelines in Chrome DevTools',
            'Garbage collection: generational GC, major and minor GC cycles, finalisation',
            'Proxy and Reflect: all 13 traps and their invariants',
            'Reactive state with Proxy: implementing Vue 3\'s reactivity from scratch',
            'Persistent data structures and structural sharing (immutable update patterns)',
            'Abstract Syntax Trees (AST): babel-parser, AST Explorer, writing a codemod',
            'JavaScript interpreters: how eval(), new Function(), and Function.prototype work',
        ],
        'code' => [
            'title'   => 'Reactive state engine with Proxy',
            'lang'    => 'javascript',
            'content' =>
"let activeEffect = null;
const deps = new WeakMap();

function track(target, key) {
  if (!activeEffect) return;
  if (!deps.has(target)) deps.set(target, new Map());
  const map = deps.get(target);
  if (!map.has(key)) map.set(key, new Set());
  map.get(key).add(activeEffect);
}

function trigger(target, key) {
  deps.get(target)?.get(key)?.forEach(fn => fn());
}

function reactive(raw) {
  return new Proxy(raw, {
    get(t, k) { track(t, k); return Reflect.get(t, k); },
    set(t, k, v) { Reflect.set(t, k, v); trigger(t, k); return true; },
  });
}

function watchEffect(fn) {
  activeEffect = fn;
  fn();
  activeEffect = null;
}

const state = reactive({ count: 0 });
watchEffect(() => console.log('Count is:', state.count));
state.count++; // Count is: 1
state.count++; // Count is: 2",
        ],
        'tips' => [
            'Use the V8 blog (v8.dev) to understand optimisation decisions that affect your hot paths.',
            'Run node --prof and node --prof-process to identify CPU bottlenecks in Node.js services.',
            'Explore AST Explorer (astexplorer.net) to understand how Babel, ESLint, and TypeScript parse code.',
            'Read Kyle Simpson\'s "You Don\'t Know JS" series — it remains the deepest dive into JS mechanics.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
