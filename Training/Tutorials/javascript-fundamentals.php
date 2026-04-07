<?php
$tutorial_title = 'JS Fundamentals';
$tutorial_slug  = 'javascript-fundamentals';
$quiz_slug      = 'javascript-fundamentals';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>JavaScript is the only programming language that runs natively in web browsers, making it the language of the interactive web. Every button click, form validation, dynamic update, and animation you see in a browser is powered by JavaScript. It is also increasingly used on servers (Node.js), mobile (React Native), and desktop (Electron).</p><p>This tier covers the essential fundamentals: declaring variables, understanding JavaScript\'s dynamic type system, using the browser console, and writing the first interactive scripts that respond to user events.</p>',
        'concepts' => [
            'var, let, and const: differences in scope, hoisting, and reassignability',
            'Primitive data types: number, string, boolean, null, undefined, symbol, bigint',
            'Type coercion and the difference between == and ===',
            'Template literals: backtick strings with ${expression} interpolation',
            'The browser console as a development REPL',
            'typeof operator and truthy/falsy values',
            'Basic DOM access: document.getElementById(), querySelector()',
        ],
        'code' => [
            'title'   => 'Variables and type fundamentals',
            'lang'    => 'javascript',
            'content' =>
"// Prefer const by default; use let only when reassignment is needed
const name    = 'Alice';
const age     = 30;
const active  = true;
let   score   = 0;

// Template literal
console.log(`${name} is ${age} years old`); // Alice is 30 years old

// Loose vs. strict equality
console.log(0 == false);  // true  — type coercion happens
console.log(0 === false); // false — no coercion

// Falsy values: 0, '', null, undefined, NaN, false
if (!score) console.log('No score yet');",
        ],
        'tips' => [
            'Default to const and only use let when a variable will be reassigned — never use var in new code.',
            'Always use === for equality checks to avoid unexpected type-coercion bugs.',
            'Open the browser console (F12 → Console) and type snippets directly to experiment instantly.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Functions are JavaScript\'s primary unit of reusability and abstraction. JavaScript has three syntax forms for functions — declarations, expressions, and arrow functions — each with different behaviour around hoisting and the <code>this</code> keyword. Understanding when to use each is a key distinction between novice and competent JS developers.</p><p>Arrays and objects are the two fundamental collection types. Arrays hold ordered lists; objects hold named properties. Together they model virtually every data structure you will encounter on the web.</p>',
        'concepts' => [
            'Function declarations vs. function expressions vs. arrow functions',
            'Default parameter values and rest parameters (...args)',
            'Array methods: push, pop, shift, unshift, splice, slice',
            'Array iteration: forEach, map, filter, reduce, find, some, every',
            'Object literals: property shorthand, computed property names, methods',
            'Destructuring: array destructuring, object destructuring with renaming',
            'Spread operator: copying/merging arrays and objects',
            'Short-circuit evaluation: && and || for default values',
        ],
        'code' => [
            'title'   => 'Array and object manipulation',
            'lang'    => 'javascript',
            'content' =>
"const users = [
  { id: 1, name: 'Alice', score: 92, active: true  },
  { id: 2, name: 'Bob',   score: 74, active: false },
  { id: 3, name: 'Carol', score: 88, active: true  },
];

// Filter active users and extract names
const activeNames = users
  .filter(u => u.active)
  .map(u => u.name);

console.log(activeNames); // ['Alice', 'Carol']

// Destructuring with default value
const { name = 'Anonymous', score = 0 } = users[1];
console.log(`${name}: ${score}`); // Bob: 74

// Spread to merge objects
const updated = { ...users[0], score: 95 };
console.log(updated.score); // 95",
        ],
        'tips' => [
            'Prefer map/filter/reduce over for loops for transforming arrays — the intent is clearer.',
            'Use the nullish coalescing operator (??) for defaults: value ?? \'default\' is safer than || when 0 or "" are valid values.',
            'Destructure function parameters to make the function signature self-documenting.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Asynchronous programming is where many JavaScript learners struggle. The event loop, call stack, and task queue determine the order operations execute. Promises provide a clean way to handle asynchronous results, and async/await syntax makes asynchronous code read like synchronous code, dramatically improving readability.</p><p>This tier also covers the Document Object Model (DOM): traversing the element tree, responding to events, modifying content and styles dynamically, and using the Fetch API to load data from remote APIs.</p>',
        'concepts' => [
            'The event loop: call stack, Web APIs, task queue, microtask queue',
            'Callbacks and callback hell',
            'Promises: new Promise(), .then(), .catch(), .finally()',
            'Promise combinators: Promise.all(), Promise.race(), Promise.allSettled()',
            'async/await: cleaner asynchronous code',
            'Fetch API: GET and POST requests, handling JSON responses',
            'DOM traversal: parentElement, children, querySelector, querySelectorAll',
            'DOM mutation: innerHTML, textContent, createElement, appendChild, remove',
            'Event listeners: addEventListener, event bubbling and capturing, removeEventListener',
        ],
        'code' => [
            'title'   => 'Fetching data with async/await',
            'lang'    => 'javascript',
            'content' =>
"async function fetchUsers(page = 1) {
  try {
    const res = await fetch(
      `https://reqres.in/api/users?page=\${page}`,
      { headers: { Accept: 'application/json' } }
    );

    if (!res.ok) throw new Error(`HTTP \${res.status}: \${res.statusText}`);

    const { data, total_pages } = await res.json();

    data.forEach(user => {
      console.log(`\${user.first_name} \${user.last_name} — \${user.email}`);
    });

    return { data, total_pages };
  } catch (err) {
    console.error('Fetch failed:', err.message);
    return null;
  }
}

fetchUsers(1);",
        ],
        'tips' => [
            'Always use try/catch with async/await — an unhandled rejected promise crashes Node.js servers.',
            'Use Promise.all() to fire multiple independent requests concurrently rather than awaiting them sequentially.',
            'Prefer event delegation (listener on parent) over individual listeners on many child elements.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>JavaScript\'s prototype-based inheritance, closures, and the module system are the advanced concepts that separate senior engineers from mid-level developers. Understanding how the prototype chain works — and how ES6 classes are syntactic sugar over it — enables you to debug framework internals, extend built-in types safely, and design powerful abstractions.</p><p>This tier also covers ES Modules (import/export), error handling best practices, regular expressions, WeakMap/WeakSet for memory-leak-safe caching, and the Iterator/Generator protocol for building lazy data pipelines.</p>',
        'concepts' => [
            'Closures: functions that capture their enclosing scope',
            'The this keyword: binding rules — default, implicit, explicit (call/apply/bind)',
            'Prototype chain: Object.create(), __proto__, Object.getPrototypeOf()',
            'ES6 classes: constructor, extends, super, static, private fields (#)',
            'ES Modules: import/export named and default, dynamic import()',
            'Iterators and the Iterable protocol: Symbol.iterator',
            'Generators: function*, yield, two-way data passing',
            'WeakMap and WeakSet for memory-safe key-to-value associations',
        ],
        'code' => [
            'title'   => 'Generator function as lazy range',
            'lang'    => 'javascript',
            'content' =>
"function* range(start, end, step = 1) {
  for (let i = start; i < end; i += step) {
    yield i;
  }
}

// Spread a lazy generator into an array
console.log([...range(0, 10, 2)]); // [0, 2, 4, 6, 8]

// Use in a for-of loop without materialising the whole sequence
for (const n of range(1, 1_000_000)) {
  if (n > 5) break;
  console.log(n); // 1 2 3 4 5
}

// Infinite sequence with early termination
function* naturals() { let n = 1; while (true) yield n++; }
const first5 = [...naturals()].slice(0, 5); // Don't spread infinitely!",
        ],
        'tips' => [
            'Learn closures by writing a counter factory and a memoize function — they make closures concrete.',
            'Use #privateField syntax for truly private class data rather than the convention of _underscore.',
            'Dynamic import() is the key to code-splitting in bundlers like Vite and webpack.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert-level JavaScript covers the runtime internals that every high-performance application depends on: V8\'s hidden classes and JIT compilation, memory management, the performance implications of different data structures, and how to profile and eliminate bottlenecks using Chrome DevTools.</p><p>The cutting edge of the language lives in the TC39 proposal process. Stage 3 and Stage 4 proposals — Temporal for dates, Records and Tuples for immutable data, Pattern Matching, Decorators — will shape how you write JavaScript over the next decade. Understanding the proposal process makes you an informed, future-proof developer.</p>',
        'concepts' => [
            'V8 engine: hidden classes, inline caches, JIT compilation, deoptimisation',
            'Memory model: heap, stack, Garbage Collector (mark-and-sweep, generational GC)',
            'Common memory leaks: closures over large data, forgotten listeners, detached DOM nodes',
            'Proxies and Reflect: intercepting fundamental operations',
            'Symbol.toPrimitive, Symbol.iterator, Symbol.hasInstance and well-known Symbols',
            'The TC39 proposal process: Stage 0–4',
            'Temporal API: modern date/time handling (Stage 3)',
            'Decorators (Stage 3): class and method decoration patterns',
        ],
        'code' => [
            'title'   => 'ES6 Proxy for validation',
            'lang'    => 'javascript',
            'content' =>
"function createValidated(target, schema) {
  return new Proxy(target, {
    set(obj, prop, value) {
      const rule = schema[prop];
      if (rule && !rule(value)) {
        throw new TypeError(`Invalid value for '${prop}': ${value}`);
      }
      obj[prop] = value;
      return true;
    }
  });
}

const user = createValidated({}, {
  age:   v => Number.isInteger(v) && v >= 0 && v <= 120,
  email: v => typeof v === 'string' && v.includes('@'),
});

user.age   = 25;          // OK
user.email = 'a@b.com';   // OK
user.age   = -1;          // TypeError: Invalid value for 'age': -1",
        ],
        'tips' => [
            'Profile with Chrome DevTools Performance tab and Memory tab — intuition about bottlenecks is often wrong.',
            'Follow the TC39 GitHub (github.com/tc39/proposals) to stay ahead of language changes.',
            'Read "JavaScript: The Good Parts" by Crockford and "You Don\'t Know JS" series by Simpson.',
            'Contribute to an open-source JS library to learn real-world patterns at scale.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
