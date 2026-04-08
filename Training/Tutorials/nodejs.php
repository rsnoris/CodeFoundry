<?php
$tutorial_title = 'Node.js';
$tutorial_slug  = 'nodejs';
$quiz_slug      = 'nodejs';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Node.js is a JavaScript runtime built on Chrome\'s V8 engine that lets you run JavaScript outside the browser. Released in 2009, it transformed JavaScript from a browser-only language into a general-purpose platform for servers, CLIs, build tools, and desktop applications. Its non-blocking, event-driven I/O model makes it exceptionally efficient for network-intensive workloads.</p><p>This tier introduces the Node.js runtime, its module system, the built-in <code>http</code> module for a first server, and the npm ecosystem for managing packages.</p>',
        'concepts' => [
            'Node.js architecture: V8 engine + libuv event loop + core modules',
            'CommonJS modules: require() and module.exports',
            'ES Modules in Node.js: import/export, .mjs extension or "type": "module"',
            'Built-in modules: fs, path, os, http, https, url, crypto, events',
            'npm: package.json, npm install, dependencies vs. devDependencies',
            'npx for running package binaries without global install',
            'First HTTP server: http.createServer() and request/response',
        ],
        'code' => [
            'title'   => 'Minimal Node.js HTTP server',
            'lang'    => 'javascript',
            'content' =>
"import http from 'node:http';

const PORT = process.env.PORT || 3000;

const server = http.createServer((req, res) => {
  const url = new URL(req.url, `http://\${req.headers.host}`);

  if (url.pathname === '/health') {
    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify({ status: 'ok', uptime: process.uptime() }));
    return;
  }

  res.writeHead(404, { 'Content-Type': 'text/plain' });
  res.end('Not Found');
});

server.listen(PORT, () => {
  console.log(`Server listening on http://localhost:\${PORT}`);
});",
        ],
        'tips' => [
            'Prefix built-in modules with node: (import fs from \'node:fs\') to distinguish them from npm packages.',
            'Use process.env for configuration — never hardcode ports, secrets, or environment-specific values.',
            'Run node --watch server.js (Node 18+) for auto-restart during development without nodemon.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Express.js is the de facto standard HTTP framework for Node.js, providing routing, middleware, and a clean API for building web servers and REST APIs. Its middleware pipeline — a series of functions that process each request in order — is the architectural pattern that most Node.js frameworks adopt.</p><p>File system operations (reading, writing, streaming), environment variables, and basic error handling complete the foundation for building real Node.js applications.</p>',
        'concepts' => [
            'Express: app.get/post/put/delete, route parameters, query strings',
            'Middleware: app.use(), next(), error-handling middleware (4-param)',
            'express.json() and express.urlencoded() body parsers',
            'Serving static files with express.static()',
            'fs.readFile / fs.writeFile and the Promise-based fs/promises API',
            'Streams: Readable, Writable, pipe() for large file handling',
            'dotenv: loading .env files into process.env',
        ],
        'code' => [
            'title'   => 'Express REST API skeleton',
            'lang'    => 'javascript',
            'content' =>
"import express from 'express';
import 'dotenv/config';

const app  = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

const users = [
  { id: 1, name: 'Alice' },
  { id: 2, name: 'Bob'   },
];

app.get('/api/users',     (_req, res) => res.json(users));
app.get('/api/users/:id', (req, res) => {
  const user = users.find(u => u.id === Number(req.params.id));
  if (!user) return res.status(404).json({ error: 'Not found' });
  res.json(user);
});

// Global error handler
app.use((err, _req, res, _next) => {
  console.error(err);
  res.status(500).json({ error: err.message });
});

app.listen(PORT, () => console.log(`API on http://localhost:\${PORT}`));",
        ],
        'tips' => [
            'Always add an error-handling middleware (4 params: err, req, res, next) as the last middleware.',
            'Validate request bodies with Zod or Joi at the route level before touching your database.',
            'Use fs/promises (the Promise-based fs API) instead of the callback-based fs in new code.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Asynchronous Node.js programming at scale requires understanding the event loop deeply: the difference between I/O callbacks, setTimeout/setImmediate, and the microtask queue determines execution order in complex async flows. Correctly handling Promise rejections, using async/await throughout, and avoiding callback hell are non-negotiable in production code.</p><p>Database connectivity — with ORMs like Prisma or Drizzle, or query builders like Knex — and authentication with JWT and bcrypt are the building blocks of every CRUD application.</p>',
        'concepts' => [
            'Event loop phases: timers → pending callbacks → idle → poll → check → close',
            'process.nextTick() vs. setImmediate() vs. Promise microtasks',
            'Async/await patterns: parallel with Promise.all(), sequential with for-await-of',
            'Unhandled rejection and uncaught exception handling',
            'Prisma ORM: schema.prisma, migrations, typed queries',
            'JWT authentication: jsonwebtoken, sign, verify, token expiry',
            'bcrypt: hashing passwords, comparing hashes, salt rounds',
        ],
        'code' => [
            'title'   => 'JWT auth middleware',
            'lang'    => 'javascript',
            'content' =>
"import jwt from 'jsonwebtoken';

const JWT_SECRET = process.env.JWT_SECRET;
if (!JWT_SECRET) throw new Error('JWT_SECRET env var is required');

export function signToken(payload, expiresIn = '1h') {
  return jwt.sign(payload, JWT_SECRET, { expiresIn });
}

export function requireAuth(req, res, next) {
  const header = req.headers.authorization;
  if (!header?.startsWith('Bearer ')) {
    return res.status(401).json({ error: 'Missing token' });
  }
  try {
    req.user = jwt.verify(header.slice(7), JWT_SECRET);
    next();
  } catch (err) {
    res.status(401).json({ error: 'Invalid or expired token' });
  }
}

export function requireRole(...roles) {
  return (req, res, next) => {
    if (!roles.includes(req.user?.role)) {
      return res.status(403).json({ error: 'Forbidden' });
    }
    next();
  };
}",
        ],
        'tips' => [
            'Never log JWT payloads — they contain user data and are often mistakenly treated as opaque.',
            'Use 12+ bcrypt salt rounds in production — lower values are too fast for password hashing.',
            'Always handle unhandledRejection and uncaughtException at the process level to prevent silent crashes.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Production Node.js applications need robust process management (PM2 or systemd), clustering to use all CPU cores, and health-check and graceful-shutdown patterns so deployments and container orchestration work without dropped connections. Logging with Winston or Pino, structured JSON output, and correlation IDs make distributed debugging tractable.</p><p>WebSockets (with Socket.IO or the native ws library) enable real-time bidirectional communication, and Worker Threads let you run CPU-intensive tasks in parallel without blocking the event loop.</p>',
        'concepts' => [
            'Clustering: cluster module, PM2 cluster mode, CPU core utilisation',
            'Graceful shutdown: SIGTERM handler, draining connections, server.close()',
            'Health checks: /health and /ready endpoints for container orchestration',
            'Structured logging with Pino: JSON output, log levels, redaction',
            'Correlation IDs: AsyncLocalStorage for request-scoped context',
            'WebSockets: ws library, Socket.IO rooms and namespaces',
            'Worker Threads: workerData, parentPort, SharedArrayBuffer for CPU tasks',
        ],
        'code' => [
            'title'   => 'Graceful shutdown pattern',
            'lang'    => 'javascript',
            'content' =>
"import http from 'node:http';
import app from './app.js';

const server = http.createServer(app);
let isShuttingDown = false;

server.listen(process.env.PORT || 3000, () => {
  console.log('Server started');
});

async function shutdown(signal) {
  if (isShuttingDown) return;
  isShuttingDown = true;
  console.log(`\${signal} received — shutting down gracefully`);

  server.close(async () => {
    try {
      await closeDatabase(); // close DB connections
      console.log('Shutdown complete');
      process.exit(0);
    } catch (err) {
      console.error('Error during shutdown', err);
      process.exit(1);
    }
  });

  // Force shutdown after 10 s
  setTimeout(() => { console.error('Forced shutdown'); process.exit(1); }, 10_000);
}

process.on('SIGTERM', () => shutdown('SIGTERM'));
process.on('SIGINT',  () => shutdown('SIGINT'));",
        ],
        'tips' => [
            'Always implement a SIGTERM handler — Kubernetes sends SIGTERM before terminating a pod.',
            'Use AsyncLocalStorage to pass correlation IDs through async call chains without prop drilling.',
            'Run CPU-bound tasks (image processing, crypto) in Worker Threads — they cannot block the event loop.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Node.js engineering involves understanding libuv and the V8 heap, diagnosing production performance issues with profiling tools (node --prof, clinic.js), detecting and fixing memory leaks with heap snapshots, and optimising hot code paths with monomorphic functions and typed arrays.</p><p>Building and publishing Node.js libraries, designing a plugin architecture, contributing to the Node.js core, and understanding the implications of the OWASP Node.js Security Cheat Sheet mark the expert practitioner who ships reliable, high-performance backend services.</p>',
        'concepts' => [
            'libuv thread pool: default size 4, UV_THREADPOOL_SIZE for I/O-heavy workloads',
            'CPU profiling: node --prof, node --prof-process, clinic.js flame graphs',
            'Memory profiling: v8.writeHeapSnapshot(), Chrome DevTools heap snapshot analysis',
            'Performance: avoiding deoptimisation with monomorphic functions and stable object shapes',
            'Diagnostics channels: diagnostics_channel for observability hooks',
            'Node.js permissions model (--experimental-permission)',
            'OWASP Node.js security: injection, path traversal, ReDoS, prototype pollution',
            'Publishing npm packages: package.json exports map, ESM/CJS dual builds',
        ],
        'code' => [
            'title'   => 'Heap snapshot for memory leak detection',
            'lang'    => 'javascript',
            'content' =>
"import v8 from 'node:v8';
import { writeFileSync } from 'node:fs';

// Capture a heap snapshot programmatically
function takeSnapshot(label = Date.now()) {
  const filename = `heap-\${label}.heapsnapshot`;
  writeFileSync(filename, v8.writeHeapSnapshot());
  console.log(`Snapshot written: \${filename}`);
  return filename;
}

// In production, expose via a guarded admin endpoint:
app.get('/admin/heap-snapshot', requireAdminAuth, (_req, res) => {
  const file = takeSnapshot();
  res.download(file, () => fs.unlinkSync(file)); // clean up after download
});

// Tip: take two snapshots with a reproduce step between them,
// then open both in Chrome DevTools > Memory > Compare snapshots
// to find the growing object type.",
        ],
        'tips' => [
            'Use clinic.js (clinic doctor / clinic flame) for a guided performance diagnosis — it explains what it finds.',
            'Increase UV_THREADPOOL_SIZE to 16 when running many concurrent file or DNS operations.',
            'Read the Node.js Security Best Practices guide at nodejs.org before deploying to production.',
            'Follow the Node.js blog and the Node.js Technical Steering Committee (TSC) for roadmap updates.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
