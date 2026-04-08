<?php
$tutorial_title = 'DSA';
$tutorial_slug  = 'dsa';
$quiz_slug      = 'dsa';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Data Structures and Algorithms (DSA) is the mathematical foundation of computer science and the cornerstone of technical interviews at top technology companies. DSA teaches you to think about problems in terms of data organisation and computational efficiency — choosing the right structure and algorithm can be the difference between a solution that runs in milliseconds and one that takes hours.</p><p>This tier introduces Big-O notation for analysing time and space complexity, and the most fundamental data structures: arrays and linked lists.</p>',
        'concepts' => [
            'Big-O notation: O(1), O(log n), O(n), O(n log n), O(n²), O(2ⁿ)',
            'Time complexity vs. space complexity',
            'Best, worst, and average case analysis',
            'Arrays: indexing O(1), insertion/deletion O(n), search O(n)',
            'Singly and doubly linked lists: nodes, head/tail pointers',
            'Linked list operations: prepend/append O(1), search/delete O(n)',
            'Static vs. dynamic arrays (ArrayList/Vector)',
        ],
        'code' => [
            'title'   => 'Linked list implementation',
            'lang'    => 'javascript',
            'content' =>
"class Node {
  constructor(val) { this.val = val; this.next = null; }
}

class LinkedList {
  constructor() { this.head = null; this.size = 0; }

  prepend(val) {
    const node = new Node(val);
    node.next  = this.head;
    this.head  = node;
    this.size++;
    return this;
  }

  append(val) {
    const node = new Node(val);
    if (!this.head) { this.head = node; this.size++; return this; }
    let cur = this.head;
    while (cur.next) cur = cur.next;
    cur.next = node;
    this.size++;
    return this;
  }

  // Floyd's tortoise-and-hare cycle detection
  hasCycle() {
    let slow = this.head, fast = this.head;
    while (fast && fast.next) {
      slow = slow.next;
      fast = fast.next.next;
      if (slow === fast) return true;
    }
    return false;
  }

  toArray() {
    const arr = []; let cur = this.head;
    while (cur) { arr.push(cur.val); cur = cur.next; }
    return arr;
  }
}",
        ],
        'tips' => [
            'O(n²) is the first complexity to look for when optimising — it almost always has an O(n log n) or O(n) solution.',
            'Linked lists excel at O(1) prepend/append — arrays are better for random access.',
            'Draw diagrams when solving pointer problems — visualising node links prevents most bugs.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Stacks (LIFO), queues (FIFO), and hash tables are the workhorses of algorithmic problem solving. Stacks underlie function call frames, expression evaluation, and DFS. Queues underlie BFS, task scheduling, and buffering. Hash tables provide O(1) average-case lookup and are the answer to almost every "find duplicates" or "count occurrences" problem.</p><p>Sorting algorithms — from the pedagogically clear bubble sort to the practically useful merge sort and quicksort — and binary search are the foundational algorithms every developer must know.</p>',
        'concepts' => [
            'Stack: push/pop O(1), applications (function calls, undo, balanced parentheses)',
            'Queue: enqueue/dequeue O(1), circular queue, deque',
            'Hash table: hash function, collisions (chaining, open addressing), load factor',
            'Sorting: bubble sort O(n²), merge sort O(n log n), quicksort O(n log n) avg',
            'Binary search: O(log n), sorted array requirement, left/right pointer technique',
            'Two-pointer technique: opposite ends, same direction, sliding window',
            'Recursion: base case, recursive case, call stack, tail recursion',
        ],
        'code' => [
            'title'   => 'Stack for balanced brackets',
            'lang'    => 'javascript',
            'content' =>
"function isBalanced(s) {
  const stack = [];
  const pairs = { ')': '(', ']': '[', '}': '{' };

  for (const ch of s) {
    if ('([{'.includes(ch)) {
      stack.push(ch);
    } else if (ch in pairs) {
      if (stack.pop() !== pairs[ch]) return false;
    }
  }
  return stack.length === 0;
}

// Merge sort — O(n log n), stable
function mergeSort(arr) {
  if (arr.length <= 1) return arr;
  const mid   = arr.length >> 1;
  const left  = mergeSort(arr.slice(0, mid));
  const right = mergeSort(arr.slice(mid));
  return merge(left, right);
}

function merge(a, b) {
  const result = []; let i = 0, j = 0;
  while (i < a.length && j < b.length)
    result.push(a[i] <= b[j] ? a[i++] : b[j++]);
  return [...result, ...a.slice(i), ...b.slice(j)];
}",
        ],
        'tips' => [
            'Hash maps are the answer to "find pair with sum X" — single-pass O(n) solution every time.',
            'Master binary search variants: first occurrence, last occurrence, search in rotated array.',
            'Recursion = iteration + stack — if recursion causes stack overflow, convert to iterative with an explicit stack.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Trees and graphs are the most important data structures for intermediate DSA. Binary trees, binary search trees (BST), and heaps appear in compilers, databases, and operating systems. BFS (level-order) and DFS (pre/in/post-order) traversals are the basis for solving hundreds of interview problems.</p><p>Dynamic programming (DP) solves optimisation problems by breaking them into overlapping subproblems and caching results (memoisation / tabulation). Recognising DP patterns — 1D/2D DP, knapsack, LCS, edit distance — is the key to mastering this category.</p>',
        'concepts' => [
            'Binary trees: nodes, height, depth, balanced vs. unbalanced',
            'BST: insert/search/delete O(log n) average, O(n) worst',
            'Tree traversals: pre-order, in-order, post-order, level-order (BFS)',
            'Heap: min-heap, max-heap, heapify O(n), heappush/pop O(log n)',
            'Graphs: adjacency list vs. matrix, directed vs. undirected, weighted',
            'BFS: level-order, shortest path in unweighted graph',
            'DFS: cycle detection, topological sort, connected components',
            'Dynamic programming: Fibonacci, 0-1 knapsack, LCS, coin change',
        ],
        'code' => [
            'title'   => 'Dynamic programming — coin change',
            'lang'    => 'javascript',
            'content' =>
"// Coin Change: minimum coins to make amount
// dp[i] = min coins to make i
function coinChange(coins, amount) {
  const dp = new Array(amount + 1).fill(Infinity);
  dp[0] = 0;

  for (let i = 1; i <= amount; i++) {
    for (const coin of coins) {
      if (coin <= i && dp[i - coin] + 1 < dp[i]) {
        dp[i] = dp[i - coin] + 1;
      }
    }
  }
  return dp[amount] === Infinity ? -1 : dp[amount];
}

// Coin change 2: number of distinct combinations
function coinChange2(coins, amount) {
  const dp = new Array(amount + 1).fill(0);
  dp[0] = 1;
  for (const coin of coins)
    for (let i = coin; i <= amount; i++)
      dp[i] += dp[i - coin];
  return dp[amount];
}

console.log(coinChange([1, 5, 10, 25], 36));   // 3 (25+10+1)
console.log(coinChange2([1, 2, 5], 5));          // 4",
        ],
        'tips' => [
            'DP state definition is the hardest part — ask "what information do I need at each subproblem?"',
            'Draw the recursion tree first, then identify overlapping subproblems to add memoisation.',
            'Heap (priority queue) problems usually involve "k largest", "k smallest", or "merge k sorted" patterns.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced graph algorithms — Dijkstra\'s shortest path, Bellman-Ford, Floyd-Warshall, Kruskal\'s MST — solve network routing, social graph analysis, and optimisation problems. Advanced trees — AVL trees, Red-Black trees (used in C++ std::map and Java TreeMap), B-trees (used in databases) — provide guaranteed O(log n) operations by maintaining balance.</p><p>Trie (prefix tree), segment tree, Fenwick tree (Binary Indexed Tree), and Union-Find (Disjoint Set Union) are specialised structures that make certain problems — autocomplete, range queries, connected components — tractable.</p>',
        'concepts' => [
            'Dijkstra\'s algorithm: O((V+E) log V) with priority queue, non-negative weights',
            'Bellman-Ford: O(VE), handles negative weights, detects negative cycles',
            'Minimum Spanning Tree: Kruskal\'s (Union-Find) and Prim\'s algorithms',
            'Trie: prefix search, autocomplete, word frequency',
            'Union-Find (DSU): union by rank, path compression — nearly O(α) per operation',
            'Segment tree: range query + point update in O(log n)',
            'Fenwick tree (BIT): prefix sums and point updates in O(log n)',
        ],
        'code' => [
            'title'   => "Dijkstra's shortest path",
            'lang'    => 'javascript',
            'content' =>
"class MinHeap {
  constructor() { this.h = []; }
  push([d, n]) {
    this.h.push([d, n]);
    let i = this.h.length - 1;
    while (i > 0) {
      const p = (i - 1) >> 1;
      if (this.h[p][0] <= this.h[i][0]) break;
      [this.h[p], this.h[i]] = [this.h[i], this.h[p]]; i = p;
    }
  }
  pop() {
    const top = this.h[0]; const last = this.h.pop();
    if (this.h.length) { this.h[0] = last; this._sink(0); }
    return top;
  }
  _sink(i) {
    const n = this.h.length;
    while (true) {
      let s = i, l = 2*i+1, r = 2*i+2;
      if (l < n && this.h[l][0] < this.h[s][0]) s = l;
      if (r < n && this.h[r][0] < this.h[s][0]) s = r;
      if (s === i) break;
      [this.h[s], this.h[i]] = [this.h[i], this.h[s]]; i = s;
    }
  }
  get size() { return this.h.length; }
}

function dijkstra(graph, src) {
  const dist = {}; const heap = new MinHeap();
  dist[src] = 0; heap.push([0, src]);
  while (heap.size) {
    const [d, u] = heap.pop();
    if (d > (dist[u] ?? Infinity)) continue;
    for (const [v, w] of (graph[u] || [])) {
      const nd = d + w;
      if (nd < (dist[v] ?? Infinity)) { dist[v] = nd; heap.push([nd, v]); }
    }
  }
  return dist;
}",
        ],
        'tips' => [
            'Dijkstra fails with negative edges — use Bellman-Ford instead (or re-weight with Johnson\'s algorithm).',
            'Union-Find with path compression is the canonical solution for "number of connected components" problems.',
            'Segment trees solve range query + point update problems; Fenwick trees are simpler for pure prefix sums.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert DSA covers advanced graph algorithms (network flow with Ford-Fulkerson, matching algorithms), string algorithms (KMP, Z-algorithm, Aho-Corasick for multi-pattern search, suffix arrays), and computational geometry (convex hull, line intersection). Understanding amortised analysis — why dynamic arrays have O(1) amortised push — gives you a rigorous tool for analysing complex data structures.</p><p>NP-completeness theory — recognising NP-hard problems (TSP, SAT, 3-colouring) and knowing when to use approximation algorithms or heuristics instead of exact solutions — marks the boundary between algorithmic proficiency and algorithmic mastery.</p>',
        'concepts' => [
            'Network flow: Ford-Fulkerson, Edmonds-Karp O(VE²), max-flow min-cut theorem',
            'Bipartite matching: Hopcroft-Karp O(E√V)',
            'String matching: KMP O(n+m), Z-algorithm, suffix array + LCP',
            'Aho-Corasick: multi-pattern string search in O(n + m + k)',
            'Amortised analysis: aggregate, accounting, and potential methods',
            'P, NP, NP-complete, NP-hard: Cook-Levin theorem, reductions',
            'Approximation algorithms: 2-approx for vertex cover, PTAS, FPTAS',
        ],
        'code' => [
            'title'   => 'KMP string search algorithm',
            'lang'    => 'javascript',
            'content' =>
"// KMP: O(n + m) pattern matching
function buildLPS(pattern) {
  const lps = new Int32Array(pattern.length);
  let len = 0, i = 1;
  while (i < pattern.length) {
    if (pattern[i] === pattern[len]) {
      lps[i++] = ++len;
    } else if (len > 0) {
      len = lps[len - 1];
    } else {
      lps[i++] = 0;
    }
  }
  return lps;
}

function kmpSearch(text, pattern) {
  const matches = [];
  const lps     = buildLPS(pattern);
  let i = 0, j = 0;
  while (i < text.length) {
    if (text[i] === pattern[j]) { i++; j++; }
    if (j === pattern.length) {
      matches.push(i - j); // found at index i - j
      j = lps[j - 1];
    } else if (i < text.length && text[i] !== pattern[j]) {
      j > 0 ? (j = lps[j - 1]) : i++;
    }
  }
  return matches;
}

console.log(kmpSearch('ababcababd', 'abab')); // [0, 5]",
        ],
        'tips' => [
            'KMP\'s LPS (Longest Proper Prefix which is also Suffix) array is the key insight — understand it thoroughly.',
            'Most competitive programming problems have an elegant O(n log n) or O(n) solution — look for it before coding.',
            'Read "Algorithm Design" by Kleinberg & Tardos for NP-hardness reductions and approximation algorithms.',
            'Follow Codeforces and LeetCode editorial solutions to learn patterns used by top competitive programmers.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
