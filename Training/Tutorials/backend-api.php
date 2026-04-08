<?php
$tutorial_title = 'Backend & API';
$tutorial_slug  = 'backend-api';
$quiz_slug      = '';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Backend development is the discipline of building the server-side of applications — the systems that store data, enforce business rules, authenticate users, and deliver responses to clients. APIs (Application Programming Interfaces) are the contracts through which clients communicate with backends: they define which operations are available, what input they expect, and what they return.</p><p>This tier maps the backend landscape — server environments, HTTP fundamentals, and the RESTful API architectural style — giving you the vocabulary and context needed for every tier that follows.</p>',
        'concepts' => [
            'Client-server model: requests, responses, and the HTTP protocol',
            'HTTP verbs: GET, POST, PUT, PATCH, DELETE and their semantics',
            'HTTP status codes: 2xx success, 3xx redirect, 4xx client error, 5xx server error',
            'REST: resources, uniform interface, stateless, client-server, cacheable',
            'JSON as the lingua franca of web APIs',
            'API endpoints and URL design conventions (/api/v1/resources/:id)',
            'Headers: Content-Type, Authorization, Accept, CORS headers',
        ],
        'code' => [
            'title'   => 'RESTful URL conventions',
            'lang'    => 'text',
            'content' =>
'# Resource-oriented URL design

GET    /api/v1/posts           # List all posts
POST   /api/v1/posts           # Create a new post

GET    /api/v1/posts/:id       # Get a specific post
PUT    /api/v1/posts/:id       # Replace a post (full update)
PATCH  /api/v1/posts/:id       # Update a post (partial update)
DELETE /api/v1/posts/:id       # Delete a post

# Nested resources
GET    /api/v1/posts/:id/comments
POST   /api/v1/posts/:id/comments

# Filtering, sorting, pagination via query params
GET    /api/v1/posts?status=published&sort=created_at&order=desc&page=2&limit=10',
        ],
        'tips' => [
            'Namespace APIs under /api/v1/ from the start — versioning later is painful.',
            'Return appropriate HTTP status codes — 201 for created resources, 204 for successful deletes.',
            'Use nouns (not verbs) in URLs — the HTTP method is the verb: GET /posts not GET /getPosts.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>A robust API requires authentication to verify identity, authorisation to check permissions, and input validation to reject malformed requests before they reach business logic. JSON Web Tokens (JWT) are the most common stateless authentication mechanism; API keys serve machine-to-machine scenarios.</p><p>Error responses must be consistent and informative. A good error response includes a machine-readable code, a human-readable message, and (in development) a stack trace. Consistent error shapes let API clients handle errors programmatically.</p>',
        'concepts' => [
            'Authentication vs. authorisation: who are you vs. what can you do',
            'API keys: generation, storage, rotation, rate limiting per key',
            'JWT: header.payload.signature, signing algorithms (HS256, RS256)',
            'OAuth 2.0 flows: client credentials, authorization code, PKCE',
            'Input validation: schema validation at the request boundary',
            'Consistent error responses: { error: { code, message, details } }',
            'HTTP 422 Unprocessable Entity for validation failures',
        ],
        'code' => [
            'title'   => 'Consistent error response shape',
            'lang'    => 'javascript',
            'content' =>
"// Centralised error handler (Express example)
class ApiError extends Error {
  constructor(status, code, message, details = null) {
    super(message);
    this.status  = status;
    this.code    = code;
    this.details = details;
  }
}

// Usage in route:
// throw new ApiError(404, 'USER_NOT_FOUND', 'User with that ID does not exist');

// Error middleware:
app.use((err, req, res, next) => {
  const status  = err.status  || 500;
  const code    = err.code    || 'INTERNAL_ERROR';
  const message = err.message || 'Something went wrong';

  res.status(status).json({
    error: {
      code,
      message,
      ...(process.env.NODE_ENV !== 'production' && { stack: err.stack }),
      ...(err.details && { details: err.details }),
    }
  });
});",
        ],
        'tips' => [
            'Define a central ApiError class so all error responses have a consistent shape across the codebase.',
            'Never expose stack traces in production responses — log them server-side and return a safe message.',
            'Document all error codes in your API documentation so clients can handle them programmatically.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>API design at an intermediate level involves pagination strategies (offset vs. cursor), rate limiting to protect against abuse, caching (HTTP cache headers, ETags, CDN caching), and API versioning strategies that let you evolve the API without breaking existing clients.</p><p>OpenAPI (formerly Swagger) is the standard for documenting REST APIs in a machine-readable format. An OpenAPI specification lets you generate documentation, client SDKs, mock servers, and integration tests automatically from a single source of truth.</p>',
        'concepts' => [
            'Offset vs. cursor pagination: pros, cons, and when to use each',
            'Rate limiting: token bucket algorithm, X-RateLimit-* headers, 429 Too Many Requests',
            'HTTP caching: Cache-Control, ETag, Last-Modified, 304 Not Modified',
            'CDN caching strategies for API responses',
            'API versioning: URI versioning vs. header versioning vs. media type versioning',
            'OpenAPI 3.1 specification: paths, components, schemas, security',
            'Swagger UI and Redoc for interactive API documentation',
        ],
        'code' => [
            'title'   => 'OpenAPI 3.1 path definition',
            'lang'    => 'yaml',
            'content' =>
'openapi: "3.1.0"
info:
  title: CodeFoundry API
  version: "1.0.0"

paths:
  /api/v1/posts:
    get:
      summary: List published posts
      parameters:
        - name: limit
          in: query
          schema: { type: integer, minimum: 1, maximum: 100, default: 20 }
        - name: cursor
          in: query
          schema: { type: string }
      responses:
        "200":
          description: Paginated post list
          content:
            application/json:
              schema:
                type: object
                properties:
                  data:     { type: array, items: { $ref: "#/components/schemas/Post" } }
                  nextCursor: { type: string, nullable: true }
        "429":
          description: Rate limit exceeded',
        ],
        'tips' => [
            'Use cursor-based pagination for live data feeds where rows can insert/delete between page requests.',
            'Add ETag / If-None-Match support to read-heavy endpoints — it dramatically reduces bandwidth.',
            'Generate OpenAPI specs from code annotations (tsoa, fastify-swagger) rather than writing YAML by hand.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced API design covers webhooks for event-driven integrations, idempotency keys for safe retry of state-changing operations, and background job queues for long-running tasks. The "API-first" workflow — designing the OpenAPI contract before writing server code — aligns frontend and backend teams and enables parallel development.</p><p>gRPC and Protocol Buffers offer a high-performance, strongly typed alternative to REST/JSON for internal microservice communication, with bi-directional streaming and automatic code generation in dozens of languages.</p>',
        'concepts' => [
            'Webhooks: delivery, signature verification (HMAC), retry with exponential backoff',
            'Idempotency keys: safe retry for POST/PUT with client-supplied keys',
            'Async jobs: 202 Accepted + job status polling endpoint pattern',
            'API-first design: contract-first with OpenAPI before implementation',
            'gRPC: Protocol Buffers, service definition, unary and streaming RPC',
            'GraphQL vs. REST vs. gRPC: choosing the right protocol for the use case',
            'Service mesh basics: Istio/Envoy for observability, mTLS, and traffic management',
        ],
        'code' => [
            'title'   => 'Webhook HMAC signature verification',
            'lang'    => 'javascript',
            'content' =>
"import crypto from 'node:crypto';

const WEBHOOK_SECRET = process.env.WEBHOOK_SECRET;

export function verifyWebhookSignature(req, res, next) {
  const signature = req.headers['x-webhook-signature-256'];
  if (!signature) return res.status(401).json({ error: 'Missing signature' });

  const expected = 'sha256=' + crypto
    .createHmac('sha256', WEBHOOK_SECRET)
    .update(req.rawBody)  // must capture raw body before JSON parsing
    .digest('hex');

  // constant-time comparison prevents timing attacks
  const isValid = crypto.timingSafeEqual(
    Buffer.from(signature, 'utf8'),
    Buffer.from(expected, 'utf8')
  );

  if (!isValid) return res.status(401).json({ error: 'Invalid signature' });
  next();
}",
        ],
        'tips' => [
            'Always use crypto.timingSafeEqual() for HMAC comparison — string equality leaks timing information.',
            'Include a webhook delivery log in your admin panel so developers can replay failed deliveries.',
            'Design idempotency keys as UNIQUE database constraints — correct at the DB level, not just application level.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert backend and API engineering involves architecting for reliability, observability, and scalability: distributed tracing with OpenTelemetry, SLO/SLA definitions, chaos engineering, and zero-downtime deployment strategies. The API gateway pattern — Nginx, Kong, AWS API Gateway — centralises cross-cutting concerns (auth, rate limiting, logging) across all services.</p><p>Designing and evolving public APIs is a long-term commitment: semantic versioning, deprecation policies, migration guides, and the contract between your API and its consumers must be managed with the same care as a software product release.</p>',
        'concepts' => [
            'OpenTelemetry: traces, metrics, logs — the three pillars of observability',
            'Distributed tracing: trace IDs, span propagation across service boundaries',
            'SLO / SLI / SLA definitions and error budget tracking',
            'API gateway: Kong, AWS API Gateway, Nginx as ingress controller',
            'Blue-green and canary deployments for zero-downtime API releases',
            'Semantic versioning for APIs: breaking vs. non-breaking changes',
            'API deprecation lifecycle: sunset headers, migration guides, tooling',
            'Chaos engineering: introducing controlled failures to test resilience',
        ],
        'code' => [
            'title'   => 'OpenTelemetry tracing setup (Node.js)',
            'lang'    => 'javascript',
            'content' =>
"// tracing.js — load BEFORE any other module
import { NodeSDK }           from '@opentelemetry/sdk-node';
import { OTLPTraceExporter } from '@opentelemetry/exporter-trace-otlp-http';
import { getNodeAutoInstrumentations } from '@opentelemetry/auto-instrumentations-node';

const sdk = new NodeSDK({
  traceExporter: new OTLPTraceExporter({
    url: process.env.OTEL_EXPORTER_OTLP_ENDPOINT || 'http://localhost:4318/v1/traces',
  }),
  instrumentations: [getNodeAutoInstrumentations()],
  serviceName: 'codefoundry-api',
});

sdk.start();

process.on('SIGTERM', () => sdk.shutdown().finally(() => process.exit(0)));",
        ],
        'tips' => [
            'Add OpenTelemetry auto-instrumentation as early as possible — it requires zero code changes to existing routes.',
            'Define SLOs before launch, not after an incident — reactive SLOs are always too lenient.',
            'Use Sunset and Deprecation HTTP headers to communicate API end-of-life to clients automatically.',
            'Read "Designing Web APIs" by Brenda Jin et al. and "The API Design Patterns" by JJ Geewax.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
