<?php
$page_title  = 'Microservices Architecture Implementation – CodeFoundry Training';
$active_page = 'training';
$page_styles = <<<'PAGECSS'
:root {
  --navy:         #0e1828;
  --navy-2:       #121c2b;
  --navy-3:       #161f2f;
  --primary:      #18b3ff;
  --primary-hover:#009de0;
  --text:         #fff;
  --text-muted:   #92a3bb;
  --text-subtle:  #627193;
  --border-color: #1a2942;
  --button-radius:8px;
  --maxwidth:     1200px;
  --card-radius:  12px;
  --header-height:68px;
}
html, body { background:var(--navy-2); color:var(--text); font-family:'Inter',sans-serif; margin:0; padding:0; }
body { min-height:100vh; }
a { color:inherit; text-decoration:none; }
.breadcrumb { max-width:var(--maxwidth); margin:0 auto; padding:20px 40px 0; display:flex; align-items:center; gap:8px; font-size:.85rem; color:var(--text-muted); flex-wrap:wrap; }
.breadcrumb a { color:var(--text-muted); transition:color .2s; }
.breadcrumb a:hover { color:var(--primary); }
.breadcrumb-sep { color:var(--text-subtle); }
.breadcrumb-current { color:var(--text); font-weight:600; }
.guide-hero { background:linear-gradient(135deg,var(--navy) 0%,#0d1e36 60%,#0a1826 100%); border-bottom:1px solid var(--border-color); padding:60px 40px 56px; position:relative; overflow:hidden; }
.guide-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 900px 500px at 50% -80px,rgba(24,179,255,.12) 0%,transparent 70%); pointer-events:none; }
.guide-hero-inner { max-width:var(--maxwidth); margin:0 auto; position:relative; }
.guide-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(24,179,255,.1); border:1px solid rgba(24,179,255,.25); color:var(--primary); font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; padding:5px 14px; border-radius:100px; margin-bottom:20px; }
.guide-hero h1 { font-size:clamp(1.8rem,4vw,2.8rem); font-weight:900; line-height:1.15; margin:0 0 16px; background:linear-gradient(135deg,#fff 40%,var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.guide-hero-desc { max-width:680px; color:var(--text-muted); font-size:1.05rem; line-height:1.7; margin:0 0 28px; }
.guide-meta { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
.guide-meta-item { display:flex; align-items:center; gap:6px; color:var(--text-muted); font-size:.85rem; }
.guide-meta-item iconify-icon { color:var(--primary); font-size:1rem; }
.topic-tags { display:flex; gap:8px; flex-wrap:wrap; margin-top:16px; }
.topic-tag { background:rgba(24,179,255,.08); border:1px solid rgba(24,179,255,.2); color:var(--primary); font-size:.75rem; font-weight:600; padding:4px 12px; border-radius:100px; }
.guide-layout { max-width:var(--maxwidth); margin:0 auto; padding:48px 40px; display:grid; grid-template-columns:1fr 280px; gap:48px; align-items:start; }
@media(max-width:900px){ .guide-layout{ grid-template-columns:1fr; padding:32px 20px; } }
.toc-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:24px; position:sticky; top:calc(var(--header-height) + 20px); }
.toc-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--text-muted); margin:0 0 16px; }
.toc-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:4px; }
.toc-list a { display:flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:6px 10px; border-radius:6px; transition:all .2s; }
.toc-list a:hover { color:var(--primary); background:rgba(24,179,255,.06); }
.toc-num { font-size:.7rem; font-weight:700; color:var(--primary); min-width:18px; }
.back-link { display:inline-flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px; margin-top:20px; width:100%; box-sizing:border-box; justify-content:center; transition:all .2s; }
.back-link:hover { color:var(--primary); border-color:rgba(24,179,255,.4); background:rgba(24,179,255,.05); }
.guide-content { min-width:0; }
.guide-section { margin-bottom:56px; }
.guide-section:last-child { margin-bottom:0; }
.section-header { display:flex; align-items:center; gap:14px; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border-color); }
.section-num { display:flex; align-items:center; justify-content:center; width:36px; height:36px; background:rgba(24,179,255,.12); border:1px solid rgba(24,179,255,.25); border-radius:8px; color:var(--primary); font-weight:800; font-size:.9rem; flex-shrink:0; }
.section-header h2 { font-size:1.35rem; font-weight:800; margin:0; color:var(--text); }
.guide-section p { color:var(--text-muted); line-height:1.75; margin:0 0 16px; }
.guide-section ul, .guide-section ol { color:var(--text-muted); line-height:1.75; padding-left:24px; margin:0 0 16px; }
.guide-section li { margin-bottom:6px; }
.code-block { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; overflow:hidden; margin:20px 0; }
.code-block-header { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-bottom:1px solid var(--border-color); background:rgba(255,255,255,.02); }
.code-lang { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--primary); }
.code-filename { font-size:.78rem; color:var(--text-muted); font-family:'Fira Mono','Consolas',monospace; }
.code-block pre { margin:0; padding:20px; overflow-x:auto; }
.code-block code { font-family:'Fira Mono','Consolas','Courier New',monospace; font-size:.82rem; line-height:1.65; color:#c9d1d9; white-space:pre; }
.callout { display:flex; gap:14px; padding:18px 20px; border-radius:10px; margin:20px 0; }
.callout-tip  { background:rgba(24,179,255,.07); border:1px solid rgba(24,179,255,.2); }
.callout-warn { background:rgba(255,179,28,.07); border:1px solid rgba(255,179,28,.2); }
.callout-info { background:rgba(139,92,246,.07); border:1px solid rgba(139,92,246,.2); }
.callout-icon { font-size:1.3rem; flex-shrink:0; margin-top:1px; }
.callout-tip  .callout-icon { color:#18b3ff; }
.callout-warn .callout-icon { color:#ffb31c; }
.callout-info .callout-icon { color:#8b5cf6; }
.callout-body { flex:1; }
.callout-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin:0 0 6px; }
.callout-tip  .callout-title { color:#18b3ff; }
.callout-warn .callout-title { color:#ffb31c; }
.callout-info .callout-title { color:#8b5cf6; }
.callout-body p { color:var(--text-muted); font-size:.88rem; line-height:1.65; margin:0; }
.callout-body ul { color:var(--text-muted); font-size:.88rem; line-height:1.65; margin:8px 0 0; padding-left:20px; }
.principle-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:16px; margin:20px 0; }
.principle-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:10px; padding:20px; }
.principle-icon { font-size:1.6rem; color:var(--primary); margin-bottom:10px; }
.principle-title { font-size:.9rem; font-weight:700; color:var(--text); margin:0 0 8px; }
.principle-desc { font-size:.82rem; color:var(--text-muted); line-height:1.6; margin:0; }
.related-section { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:32px; margin-top:48px; }
.related-title { font-size:1.1rem; font-weight:800; margin:0 0 20px; display:flex; align-items:center; gap:10px; }
.related-title iconify-icon { color:var(--primary); }
.related-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:12px; }
.related-card { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; padding:16px; transition:border-color .2s,transform .2s; }
.related-card:hover { border-color:rgba(24,179,255,.4); transform:translateY(-2px); }
.related-card-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--primary); margin-bottom:6px; }
.related-card-title { font-size:.9rem; font-weight:600; color:var(--text); }
PAGECSS;
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="breadcrumb">
  <a href="/Training/">Training</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <a href="/Training/Guides/">Implementation Guides</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <span class="breadcrumb-current">Microservices Architecture Implementation</span>
</div>

<section class="guide-hero">
  <div class="guide-hero-inner">
    <div class="guide-badge"><iconify-icon icon="lucide:boxes"></iconify-icon> Implementation Guide</div>
    <h1>Microservices Architecture Implementation</h1>
    <p class="guide-hero-desc">Design, build, containerise, and orchestrate a production microservices system — from domain-driven service boundaries to Kubernetes deployments with full observability.</p>
    <div class="guide-meta">
      <div class="guide-meta-item"><iconify-icon icon="lucide:clock"></iconify-icon> 60 min read</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Expert</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:calendar"></iconify-icon> Updated 2025</div>
    </div>
    <div class="topic-tags">
      <span class="topic-tag">Microservices</span>
      <span class="topic-tag">Docker</span>
      <span class="topic-tag">Kubernetes</span>
      <span class="topic-tag">API Gateway</span>
    </div>
  </div>
</section>

<div class="guide-layout">
  <main class="guide-content">

    <!-- Section 1 -->
    <div class="guide-section" id="s1">
      <div class="section-header">
        <div class="section-num">1</div>
        <h2>Microservices Principles</h2>
      </div>
      <p>Microservices decompose a monolith into independently deployable services, each owning a single business capability. The architecture enables teams to scale, deploy, and evolve services independently — but introduces distributed systems complexity.</p>
      <div class="principle-grid">
        <div class="principle-card">
          <div class="principle-icon"><iconify-icon icon="lucide:target"></iconify-icon></div>
          <div class="principle-title">Single Responsibility</div>
          <p class="principle-desc">Each service owns one bounded context — User Service, Order Service, Notification Service. If it does two things, split it.</p>
        </div>
        <div class="principle-card">
          <div class="principle-icon"><iconify-icon icon="lucide:unlink"></iconify-icon></div>
          <div class="principle-title">Loose Coupling</div>
          <p class="principle-desc">Services communicate only via explicit APIs or events. No shared databases. Changes inside a service should not break consumers.</p>
        </div>
        <div class="principle-card">
          <div class="principle-icon"><iconify-icon icon="lucide:rocket"></iconify-icon></div>
          <div class="principle-title">Independent Deployment</div>
          <p class="principle-desc">Each service has its own CI/CD pipeline and can be deployed without coordinating with other teams or services.</p>
        </div>
        <div class="principle-card">
          <div class="principle-icon"><iconify-icon icon="lucide:shield"></iconify-icon></div>
          <div class="principle-title">Fault Isolation</div>
          <p class="principle-desc">A failing service does not cascade to the whole system. Circuit breakers, timeouts, and fallbacks contain blast radius.</p>
        </div>
      </div>
      <div class="callout callout-warn">
        <div class="callout-icon"><iconify-icon icon="lucide:alert-triangle"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Don't Start with Microservices</div>
          <p>For new products, start with a modular monolith and extract services when clear domain boundaries emerge and team scaling demands it. Premature decomposition creates network overhead without team-scaling benefits.</p>
        </div>
      </div>
    </div>

    <!-- Section 2 -->
    <div class="guide-section" id="s2">
      <div class="section-header">
        <div class="section-num">2</div>
        <h2>Service Design</h2>
      </div>
      <p>Domain-Driven Design (DDD) provides the vocabulary for finding service boundaries. A <strong>Bounded Context</strong> is an explicit boundary within which a domain model applies. Each microservice maps to one bounded context.</p>
      <p>Define explicit service contracts using OpenAPI (REST) or Protobuf (gRPC). Version your APIs — never introduce breaking changes without a new version.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">openapi.yaml (User Service contract)</span></div>
        <pre><code>openapi: 3.1.0
info:
  title: User Service API
  version: 1.0.0
paths:
  /users/{id}:
    get:
      summary: Get user by ID
      parameters:
        - name: id
          in: path
          required: true
          schema: { type: string, format: uuid }
      responses:
        '200':
          description: User object
          content:
            application/json:
              schema:
                type: object
                properties:
                  id:    { type: string }
                  name:  { type: string }
                  email: { type: string }
                  role:  { type: string, enum: [user, admin] }
        '404':
          description: User not found</code></pre>
      </div>
      <div class="callout callout-info">
        <div class="callout-icon"><iconify-icon icon="lucide:info"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Consumer-Driven Contract Testing</div>
          <p>Use Pact to write consumer-driven contract tests. Each consumer defines what it expects from a provider API. The provider runs these contracts as tests in its CI pipeline — preventing breaking changes before deployment.</p>
        </div>
      </div>
    </div>

    <!-- Section 3 -->
    <div class="guide-section" id="s3">
      <div class="section-header">
        <div class="section-num">3</div>
        <h2>Containerising Services with Docker</h2>
      </div>
      <p>Every service ships as an immutable Docker image. Multi-stage builds separate build-time dependencies from the runtime image, producing smaller, more secure artefacts.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">dockerfile</span><span class="code-filename">services/order-service/Dockerfile</span></div>
        <pre><code># Stage 1: Build
FROM golang:1.22-alpine AS builder
WORKDIR /app
COPY go.mod go.sum ./
RUN go mod download
COPY . .
RUN CGO_ENABLED=0 GOOS=linux go build -ldflags="-s -w" -o order-service ./cmd/server

# Stage 2: Minimal production image
FROM gcr.io/distroless/static:nonroot
COPY --from=builder /app/order-service /order-service
EXPOSE 8080
ENTRYPOINT ["/order-service"]</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">docker-compose.yml (local dev)</span></div>
        <pre><code>version: '3.9'
services:
  user-service:
    build: ./services/user-service
    environment:
      DB_URL: postgres://postgres:secret@users-db:5432/users
      KAFKA_BROKERS: kafka:9092
    depends_on: [users-db, kafka]

  order-service:
    build: ./services/order-service
    environment:
      DB_URL: postgres://postgres:secret@orders-db:5432/orders
      USER_SERVICE_URL: http://user-service:8080
      KAFKA_BROKERS: kafka:9092
    depends_on: [orders-db, kafka, user-service]

  users-db:
    image: postgres:16-alpine
    environment: { POSTGRES_DB: users, POSTGRES_PASSWORD: secret }

  orders-db:
    image: postgres:16-alpine
    environment: { POSTGRES_DB: orders, POSTGRES_PASSWORD: secret }

  kafka:
    image: confluentinc/cp-kafka:7.6.0
    environment:
      KAFKA_PROCESS_ROLES: broker,controller
      KAFKA_LISTENERS: PLAINTEXT://0.0.0.0:9092,CONTROLLER://0.0.0.0:9093
      KAFKA_ADVERTISED_LISTENERS: PLAINTEXT://kafka:9092</code></pre>
      </div>
    </div>

    <!-- Section 4 -->
    <div class="guide-section" id="s4">
      <div class="section-header">
        <div class="section-num">4</div>
        <h2>API Gateway Pattern</h2>
      </div>
      <p>The API Gateway is the single entry point for all clients. It handles cross-cutting concerns: routing, SSL termination, rate limiting, authentication, and request/response transformation — so individual services don't have to.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">kong/declarative.yml</span></div>
        <pre><code>_format_version: "3.0"
services:
  - name: user-service
    url: http://user-service:8080
    routes:
      - name: users-route
        paths: [/api/v1/users]
    plugins:
      - name: jwt
      - name: rate-limiting
        config:
          minute: 100
          policy: local

  - name: order-service
    url: http://order-service:8080
    routes:
      - name: orders-route
        paths: [/api/v1/orders]
    plugins:
      - name: jwt
      - name: request-transformer
        config:
          add:
            headers: ["X-Consumer-ID:$(consumer.id)"]</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Gateway vs. Service Mesh</div>
          <ul>
            <li><strong>API Gateway</strong>: North–South traffic (external clients → internal services). Kong, NGINX, AWS API Gateway.</li>
            <li><strong>Service Mesh</strong>: East–West traffic (service-to-service). Istio, Linkerd. Handles mTLS, circuit breaking, retries transparently via sidecar proxies.</li>
            <li>Production systems typically use both — gateway at the edge, mesh for internal communication.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 5 -->
    <div class="guide-section" id="s5">
      <div class="section-header">
        <div class="section-num">5</div>
        <h2>Inter-service Communication</h2>
      </div>
      <p>Services communicate <strong>synchronously</strong> (HTTP/gRPC — request/response) or <strong>asynchronously</strong> (message queues — fire and forget). Use synchronous calls when the caller needs an immediate response; use async messaging for workflows that can tolerate eventual consistency.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">go</span><span class="code-filename">order-service: async event publishing via Kafka</span></div>
        <pre><code>package events

import (
  "encoding/json"
  "github.com/segmentio/kafka-go"
)

type OrderCreatedEvent struct {
  OrderID    string  `json:"order_id"`
  UserID     string  `json:"user_id"`
  TotalCents int64   `json:"total_cents"`
  Currency   string  `json:"currency"`
}

type Publisher struct {
  writer *kafka.Writer
}

func NewPublisher(brokers []string) *Publisher {
  return &Publisher{
    writer: &kafka.Writer{
      Addr:     kafka.TCP(brokers...),
      Balancer: &kafka.LeastBytes{},
    },
  }
}

func (p *Publisher) OrderCreated(evt OrderCreatedEvent) error {
  payload, _ := json.Marshal(evt)
  return p.writer.WriteMessages(context.Background(),
    kafka.Message{
      Topic: "order.created",
      Key:   []byte(evt.OrderID),
      Value: payload,
    },
  )
}</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">go</span><span class="code-filename">notification-service: Kafka consumer</span></div>
        <pre><code>func startConsumer(brokers []string) {
  r := kafka.NewReader(kafka.ReaderConfig{
    Brokers:  brokers,
    GroupID:  "notification-service",
    Topic:    "order.created",
    MinBytes: 1,
    MaxBytes: 10e6,
  })
  defer r.Close()

  for {
    msg, err := r.ReadMessage(context.Background())
    if err != nil { log.Println("read error:", err); continue }

    var evt events.OrderCreatedEvent
    if err := json.Unmarshal(msg.Value, &evt); err != nil { continue }

    sendOrderConfirmationEmail(evt.UserID, evt.OrderID)
  }
}</code></pre>
      </div>
    </div>

    <!-- Section 6 -->
    <div class="guide-section" id="s6">
      <div class="section-header">
        <div class="section-num">6</div>
        <h2>Data Management</h2>
      </div>
      <p>The <strong>Database per Service</strong> pattern gives each service full ownership and autonomy over its data. No service reads another's database directly — all data access goes through the owning service's API or events.</p>
      <ul>
        <li><strong>CQRS (Command Query Responsibility Segregation)</strong>: Separate write models (commands) from read models (queries). Write sides emit events; read sides maintain denormalised projections optimised for queries.</li>
        <li><strong>Event Sourcing</strong>: Instead of storing current state, store the full sequence of events. Replay events to reconstruct state. Provides a built-in audit log and enables temporal queries.</li>
        <li><strong>Saga Pattern</strong>: Coordinate multi-step distributed transactions via a sequence of local transactions and compensating events — avoiding two-phase commits across service boundaries.</li>
      </ul>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Choreography-based Saga</span></div>
        <pre><code>// Order Service emits → Payment Service listens → emits result
// No central orchestrator — services react to events

// Order Service
async function createOrder(data: CreateOrderDTO) {
  const order = await orderRepo.create({ ...data, status: 'PENDING_PAYMENT' });
  await eventBus.publish('order.created', { orderId: order.id, amount: order.total });
  return order;
}

// Payment Service
eventBus.subscribe('order.created', async ({ orderId, amount }) => {
  const result = await chargeCard(amount);
  if (result.success) {
    await eventBus.publish('payment.succeeded', { orderId });
  } else {
    await eventBus.publish('payment.failed', { orderId, reason: result.error });
  }
});

// Order Service — listens for payment result
eventBus.subscribe('payment.succeeded', async ({ orderId }) => {
  await orderRepo.update(orderId, { status: 'CONFIRMED' });
});
eventBus.subscribe('payment.failed', async ({ orderId }) => {
  await orderRepo.update(orderId, { status: 'CANCELLED' });
});</code></pre>
      </div>
    </div>

    <!-- Section 7 -->
    <div class="guide-section" id="s7">
      <div class="section-header">
        <div class="section-num">7</div>
        <h2>Kubernetes Orchestration</h2>
      </div>
      <p>Kubernetes manages container lifecycle, scaling, service discovery, and self-healing. Each microservice gets a <code>Deployment</code> (manages pods), a <code>Service</code> (stable DNS), and optionally an <code>Ingress</code> (external HTTP routing).</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">k8s/user-service/deployment.yaml</span></div>
        <pre><code>apiVersion: apps/v1
kind: Deployment
metadata:
  name: user-service
  namespace: production
spec:
  replicas: 3
  selector:
    matchLabels:
      app: user-service
  strategy:
    type: RollingUpdate
    rollingUpdate: { maxUnavailable: 0, maxSurge: 1 }
  template:
    metadata:
      labels:
        app: user-service
    spec:
      containers:
        - name: user-service
          image: myorg/user-service:2.1.0
          ports:
            - containerPort: 8080
          envFrom:
            - configMapRef:  { name: user-service-config }
            - secretRef:     { name: user-service-secrets }
          resources:
            requests: { cpu: 100m, memory: 128Mi }
            limits:   { cpu: 500m, memory: 256Mi }
          readinessProbe:
            httpGet: { path: /health, port: 8080 }
            initialDelaySeconds: 10
            periodSeconds: 5
          livenessProbe:
            httpGet: { path: /health, port: 8080 }
            initialDelaySeconds: 30
            periodSeconds: 10</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">k8s/user-service/service.yaml</span></div>
        <pre><code>apiVersion: v1
kind: Service
metadata:
  name: user-service
  namespace: production
spec:
  selector:
    app: user-service
  ports:
    - port: 80
      targetPort: 8080
  type: ClusterIP   # internal-only; exposed via Ingress</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">k8s/ingress.yaml</span></div>
        <pre><code>apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: api-ingress
  namespace: production
  annotations:
    kubernetes.io/ingress.class: nginx
    cert-manager.io/cluster-issuer: letsencrypt-prod
    nginx.ingress.kubernetes.io/rate-limit: "100"
spec:
  tls:
    - hosts: [api.myapp.com]
      secretName: api-tls
  rules:
    - host: api.myapp.com
      http:
        paths:
          - path: /api/v1/users
            pathType: Prefix
            backend:
              service: { name: user-service, port: { number: 80 } }
          - path: /api/v1/orders
            pathType: Prefix
            backend:
              service: { name: order-service, port: { number: 80 } }</code></pre>
      </div>
    </div>

    <!-- Section 8 -->
    <div class="guide-section" id="s8">
      <div class="section-header">
        <div class="section-num">8</div>
        <h2>Observability</h2>
      </div>
      <p>In a distributed system, bugs cross service boundaries. You need three pillars of observability: <strong>logs</strong> (what happened), <strong>metrics</strong> (system health), and <strong>traces</strong> (request flow across services).</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Distributed tracing with OpenTelemetry</span></div>
        <pre><code>import { NodeSDK } from '@opentelemetry/sdk-node';
import { OTLPTraceExporter } from '@opentelemetry/exporter-trace-otlp-http';
import { Resource } from '@opentelemetry/resources';
import { SEMRESATTRS_SERVICE_NAME } from '@opentelemetry/semantic-conventions';

const sdk = new NodeSDK({
  resource: new Resource({
    [SEMRESATTRS_SERVICE_NAME]: 'order-service',
  }),
  traceExporter: new OTLPTraceExporter({
    url: process.env.OTEL_EXPORTER_OTLP_ENDPOINT,
  }),
});

sdk.start();
// All HTTP calls made by Express + fetch/axios are now auto-instrumented
// Trace IDs propagate via W3C Trace Context headers</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">k8s/prometheus-servicemonitor.yaml</span></div>
        <pre><code>apiVersion: monitoring.coreos.com/v1
kind: ServiceMonitor
metadata:
  name: order-service-monitor
  namespace: monitoring
spec:
  selector:
    matchLabels:
      app: order-service
  endpoints:
    - port: metrics
      interval: 15s
      path: /metrics</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Observability Stack Recommendations</div>
          <ul>
            <li><strong>Logs</strong>: Structured JSON logs → Fluentd/Fluent Bit → Elasticsearch → Kibana.</li>
            <li><strong>Metrics</strong>: Prometheus scrapes <code>/metrics</code> endpoints → Grafana dashboards.</li>
            <li><strong>Traces</strong>: OpenTelemetry SDK → Jaeger or Tempo → visualise request waterfalls.</li>
            <li>Correlate all three by propagating a <code>trace-id</code> through logs, spans, and metrics labels.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Related Resources -->
    <div class="related-section">
      <div class="related-title"><iconify-icon icon="lucide:link"></iconify-icon> Related Resources</div>
      <div class="related-grid">
        <a href="/Training/Guides/cicd-pipeline.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">CI/CD Pipeline Setup</div>
        </a>
        <a href="/Training/Guides/cloud-native-development.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Cloud-Native Development</div>
        </a>
        <a href="/Training/Tutorials/docker.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">Docker Fundamentals</div>
        </a>
        <a href="/Training/Guides/fullstack-web-app.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Full-Stack Web App</div>
        </a>
      </div>
    </div>

  </main>

  <aside>
    <div class="toc-card">
      <div class="toc-title">Contents</div>
      <ul class="toc-list">
        <li><a href="#s1"><span class="toc-num">1</span> Principles</a></li>
        <li><a href="#s2"><span class="toc-num">2</span> Service Design</a></li>
        <li><a href="#s3"><span class="toc-num">3</span> Docker Containers</a></li>
        <li><a href="#s4"><span class="toc-num">4</span> API Gateway</a></li>
        <li><a href="#s5"><span class="toc-num">5</span> Inter-service Comms</a></li>
        <li><a href="#s6"><span class="toc-num">6</span> Data Management</a></li>
        <li><a href="#s7"><span class="toc-num">7</span> Kubernetes</a></li>
        <li><a href="#s8"><span class="toc-num">8</span> Observability</a></li>
      </ul>
      <a href="/Training/Guides/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Guides</a>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
