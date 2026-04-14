<?php
$page_title  = 'Cloud-Native Application Development – CodeFoundry Training';
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
.comparison-table { width:100%; border-collapse:collapse; margin:20px 0; font-size:.85rem; }
.comparison-table th { background:var(--navy-3); color:var(--primary); font-weight:700; text-align:left; padding:12px 16px; border:1px solid var(--border-color); font-size:.78rem; text-transform:uppercase; letter-spacing:.06em; }
.comparison-table td { padding:12px 16px; border:1px solid var(--border-color); color:var(--text-muted); vertical-align:top; }
.comparison-table tr:nth-child(even) td { background:rgba(255,255,255,.02); }
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
  <span class="breadcrumb-current">Cloud-Native Application Development</span>
</div>

<section class="guide-hero">
  <div class="guide-hero-inner">
    <div class="guide-badge"><iconify-icon icon="lucide:cloud"></iconify-icon> Implementation Guide</div>
    <h1>Cloud-Native Application Development</h1>
    <p class="guide-hero-desc">Build scalable, resilient applications on AWS using serverless architecture, infrastructure as code, event-driven patterns, and cloud-native security best practices.</p>
    <div class="guide-meta">
      <div class="guide-meta-item"><iconify-icon icon="lucide:clock"></iconify-icon> 50 min read</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Advanced</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:calendar"></iconify-icon> Updated 2025</div>
    </div>
    <div class="topic-tags">
      <span class="topic-tag">AWS</span>
      <span class="topic-tag">Serverless</span>
      <span class="topic-tag">Lambda</span>
      <span class="topic-tag">DynamoDB</span>
    </div>
  </div>
</section>

<div class="guide-layout">
  <main class="guide-content">

    <!-- Section 1 -->
    <div class="guide-section" id="s1">
      <div class="section-header">
        <div class="section-num">1</div>
        <h2>Cloud-Native Principles</h2>
      </div>
      <p>Cloud-native applications are designed from the ground up to exploit cloud infrastructure — elastic scaling, managed services, pay-per-use pricing, and global distribution. The <strong>12-Factor App</strong> methodology provides foundational guidelines:</p>
      <ul>
        <li><strong>Codebase</strong>: One codebase, many deploys. Track in version control.</li>
        <li><strong>Dependencies</strong>: Explicitly declare and isolate dependencies. No system-level packages.</li>
        <li><strong>Config</strong>: Store config in environment variables, not code. Use AWS Secrets Manager / Parameter Store.</li>
        <li><strong>Backing services</strong>: Treat databases, caches, and queues as attached resources.</li>
        <li><strong>Build/release/run</strong>: Strictly separate build (compile), release (config injection), and run stages.</li>
        <li><strong>Processes</strong>: Execute as stateless, share-nothing processes. Persist state in backing services.</li>
        <li><strong>Concurrency</strong>: Scale out via process model. Lambda scales to thousands of concurrent invocations automatically.</li>
        <li><strong>Disposability</strong>: Fast startup and graceful shutdown. Lambda cold start optimisation matters here.</li>
        <li><strong>Dev/prod parity</strong>: Keep development, staging, and production as similar as possible. Terraform enables this.</li>
        <li><strong>Logs</strong>: Treat logs as event streams. Write to stdout; AWS captures to CloudWatch.</li>
      </ul>
      <div class="callout callout-info">
        <div class="callout-icon"><iconify-icon icon="lucide:info"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Immutable Infrastructure</div>
          <p>Never SSH into production servers to apply changes. All infrastructure changes go through code (Terraform), which replaces rather than patches resources. This makes your infrastructure auditable, reproducible, and disaster-proof.</p>
        </div>
      </div>
    </div>

    <!-- Section 2 -->
    <div class="guide-section" id="s2">
      <div class="section-header">
        <div class="section-num">2</div>
        <h2>AWS Serverless Architecture</h2>
      </div>
      <p>A serverless architecture on AWS eliminates server management entirely. You write functions; AWS handles provisioning, scaling, patching, and availability.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Lambda handler with API Gateway</span></div>
        <pre><code>import { APIGatewayProxyHandlerV2 } from 'aws-lambda';
import { DynamoDBClient, GetItemCommand } from '@aws-sdk/client-dynamodb';
import { marshall, unmarshall } from '@aws-sdk/util-dynamodb';

const dynamo = new DynamoDBClient({ region: process.env.AWS_REGION });

export const handler: APIGatewayProxyHandlerV2 = async (event) => {
  const userId = event.pathParameters?.userId;
  if (!userId) {
    return { statusCode: 400, body: JSON.stringify({ error: 'userId required' }) };
  }

  try {
    const result = await dynamo.send(new GetItemCommand({
      TableName: process.env.USERS_TABLE!,
      Key: marshall({ PK: `USER#${userId}`, SK: 'PROFILE' }),
    }));

    if (!result.Item) {
      return { statusCode: 404, body: JSON.stringify({ error: 'User not found' }) };
    }

    const user = unmarshall(result.Item);
    return {
      statusCode: 200,
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(user),
    };
  } catch (err) {
    console.error('DynamoDB error:', err);
    return { statusCode: 500, body: JSON.stringify({ error: 'Internal server error' }) };
  }
};</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Lambda Cold Start Optimisation</div>
          <ul>
            <li>Initialise AWS SDK clients and DB connections <strong>outside</strong> the handler function so they're reused across invocations.</li>
            <li>Use <strong>Lambda SnapStart</strong> (Java) or <strong>Provisioned Concurrency</strong> (all runtimes) for latency-sensitive APIs.</li>
            <li>Keep deployment packages small — use Lambda Layers for shared dependencies.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 3 -->
    <div class="guide-section" id="s3">
      <div class="section-header">
        <div class="section-num">3</div>
        <h2>Infrastructure as Code with Terraform</h2>
      </div>
      <p>Terraform declares AWS infrastructure as HCL code. Running <code>terraform apply</code> creates or updates resources to match your declaration. State is stored remotely in S3 with DynamoDB locking.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">hcl</span><span class="code-filename">infra/main.tf</span></div>
        <pre><code>terraform {
  required_version = ">= 1.7"
  required_providers {
    aws = { source = "hashicorp/aws", version = "~> 5.0" }
  }
  backend "s3" {
    bucket         = "myorg-terraform-state"
    key            = "prod/main.tfstate"
    region         = "us-east-1"
    dynamodb_table = "terraform-locks"
    encrypt        = true
  }
}

provider "aws" {
  region = var.aws_region
  default_tags {
    tags = { Environment = var.environment, ManagedBy = "terraform" }
  }
}

# Lambda function
resource "aws_lambda_function" "api" {
  function_name = "${var.project}-api-${var.environment}"
  role          = aws_iam_role.lambda_exec.arn
  runtime       = "nodejs20.x"
  handler       = "dist/handler.handler"
  filename      = data.archive_file.lambda_zip.output_path
  timeout       = 30
  memory_size   = 512

  environment {
    variables = {
      USERS_TABLE = aws_dynamodb_table.users.name
      AWS_NODEJS_CONNECTION_REUSE_ENABLED = "1"
    }
  }

  tracing_config { mode = "Active" }  # X-Ray tracing
}

# DynamoDB table (single-table design)
resource "aws_dynamodb_table" "users" {
  name           = "${var.project}-users-${var.environment}"
  billing_mode   = "PAY_PER_REQUEST"
  hash_key       = "PK"
  range_key      = "SK"

  attribute { name = "PK" type = "S" }
  attribute { name = "SK" type = "S" }
  attribute { name = "GSI1PK" type = "S" }

  global_secondary_index {
    name            = "GSI1"
    hash_key        = "GSI1PK"
    range_key       = "SK"
    projection_type = "ALL"
  }

  point_in_time_recovery { enabled = true }
  server_side_encryption  { enabled = true }
  deletion_protection_enabled = var.environment == "production"
}</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">bash</span><span class="code-filename">Common Terraform commands</span></div>
        <pre><code># Initialise (download providers, configure backend)
terraform init

# Preview changes
terraform plan -var-file=envs/production.tfvars -out=tfplan

# Apply changes
terraform apply tfplan

# Destroy (use with caution!)
terraform destroy -var-file=envs/staging.tfvars

# Import existing AWS resource into state
terraform import aws_s3_bucket.assets my-existing-bucket-name</code></pre>
      </div>
    </div>

    <!-- Section 4 -->
    <div class="guide-section" id="s4">
      <div class="section-header">
        <div class="section-num">4</div>
        <h2>Event-Driven Architecture</h2>
      </div>
      <p>AWS provides first-class event infrastructure. Lambda functions react to events from dozens of sources, enabling loosely coupled, asynchronous workflows without managing message brokers.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">hcl</span><span class="code-filename">Event-driven pipeline: S3 → Lambda → SQS → Lambda</span></div>
        <pre><code># Trigger Lambda when a file is uploaded to S3
resource "aws_s3_bucket_notification" "upload_trigger" {
  bucket = aws_s3_bucket.uploads.id
  lambda_function {
    lambda_function_arn = aws_lambda_function.process_upload.arn
    events              = ["s3:ObjectCreated:*"]
    filter_suffix       = ".csv"
  }
  depends_on = [aws_lambda_permission.s3_invoke]
}

# EventBridge rule: run Lambda on a schedule
resource "aws_cloudwatch_event_rule" "daily_report" {
  name                = "daily-report"
  schedule_expression = "cron(0 8 * * ? *)"   # 8 AM UTC daily
}
resource "aws_cloudwatch_event_target" "report_lambda" {
  rule = aws_cloudwatch_event_rule.daily_report.name
  arn  = aws_lambda_function.generate_report.arn
}

# SQS queue with DLQ for failed processing
resource "aws_sqs_queue" "jobs" {
  name                      = "${var.project}-jobs"
  visibility_timeout_seconds = 300
  redrive_policy = jsonencode({
    deadLetterTargetArn = aws_sqs_queue.jobs_dlq.arn
    maxReceiveCount     = 3
  })
}

# Lambda SQS trigger
resource "aws_lambda_event_source_mapping" "job_processor" {
  event_source_arn = aws_sqs_queue.jobs.arn
  function_name    = aws_lambda_function.job_processor.arn
  batch_size       = 10
  function_response_types = ["ReportBatchItemFailures"]
}</code></pre>
      </div>
    </div>

    <!-- Section 5 -->
    <div class="guide-section" id="s5">
      <div class="section-header">
        <div class="section-num">5</div>
        <h2>Managed Databases</h2>
      </div>
      <p>Choose the right managed database for each workload. AWS offers relational, key-value, document, and in-memory options — all fully managed with automated backups and multi-AZ failover.</p>
      <table class="comparison-table">
        <thead>
          <tr><th>Service</th><th>Type</th><th>Best For</th><th>Scaling</th></tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>DynamoDB</strong></td>
            <td>Key-value / Document</td>
            <td>Single-digit-ms latency, serverless, unpredictable traffic</td>
            <td>Automatic, on-demand</td>
          </tr>
          <tr>
            <td><strong>Aurora Serverless v2</strong></td>
            <td>Relational (MySQL/PG)</td>
            <td>Relational data model, complex joins, ACID transactions</td>
            <td>Auto scales ACUs</td>
          </tr>
          <tr>
            <td><strong>RDS PostgreSQL</strong></td>
            <td>Relational</td>
            <td>Steady workloads, complex queries, PostGIS, full-text search</td>
            <td>Manual + Read Replicas</td>
          </tr>
          <tr>
            <td><strong>ElastiCache</strong></td>
            <td>In-memory</td>
            <td>Session store, rate limiting, leaderboards, pub/sub</td>
            <td>Cluster mode</td>
          </tr>
        </tbody>
      </table>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">DynamoDB single-table design pattern</span></div>
        <pre><code>// Access patterns for a blog: get user, list user posts, get post by id
// All stored in one DynamoDB table using entity prefixes

// User record
{ PK: "USER#alice",   SK: "PROFILE",          name: "Alice", email: "alice@example.com" }

// Post record
{ PK: "POST#post-1",  SK: "METADATA",          title: "Hello", authorId: "alice" }

// GSI: query all posts by author
{ PK: "POST#post-1",  SK: "METADATA",  GSI1PK: "AUTHOR#alice", GSI1SK: "2025-01-15T10:00:00Z" }

// Query: get all posts by Alice, newest first
const result = await dynamo.send(new QueryCommand({
  TableName: 'my-app',
  IndexName: 'GSI1',
  KeyConditionExpression: 'GSI1PK = :author',
  ExpressionAttributeValues: { ':author': { S: 'AUTHOR#alice' } },
  ScanIndexForward: false,   // newest first
  Limit: 20,
}));</code></pre>
      </div>
    </div>

    <!-- Section 6 -->
    <div class="guide-section" id="s6">
      <div class="section-header">
        <div class="section-num">6</div>
        <h2>Caching Strategies</h2>
      </div>
      <p>Caching is the single highest-impact performance optimisation. Layer your caches from the network edge to the database to minimise latency and cost.</p>
      <ul>
        <li><strong>CloudFront (CDN)</strong>: Cache static assets and API responses at 400+ edge locations globally. Configure <code>Cache-Control</code> headers to control TTLs. Invalidate on deploy.</li>
        <li><strong>API Gateway Cache</strong>: Cache Lambda responses at the gateway for GET endpoints. Keyed by URL + query string. Reduces Lambda invocation cost dramatically for read-heavy APIs.</li>
        <li><strong>ElastiCache (Redis)</strong>: In-memory cache for computed results, session data, and rate-limiting counters. Use Redis Cluster for high availability.</li>
        <li><strong>DynamoDB Accelerator (DAX)</strong>: In-memory cache layer in front of DynamoDB. Fully API-compatible; reduces read latency from single-digit ms to microseconds for hot data.</li>
      </ul>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Cache-aside pattern with ElastiCache</span></div>
        <pre><code>import { createClient } from 'redis';

const redis = createClient({ url: process.env.REDIS_URL });
await redis.connect();

async function getUserWithCache(userId: string) {
  const cacheKey = `user:${userId}`;

  // 1. Check cache
  const cached = await redis.get(cacheKey);
  if (cached) return JSON.parse(cached);

  // 2. Cache miss — fetch from DynamoDB
  const user = await fetchUserFromDynamo(userId);
  if (!user) return null;

  // 3. Store in cache with 5-minute TTL
  await redis.setEx(cacheKey, 300, JSON.stringify(user));
  return user;
}

// On user update — invalidate cache
async function updateUser(userId: string, data: Partial<User>) {
  await updateUserInDynamo(userId, data);
  await redis.del(`user:${userId}`);   // force refresh on next read
}</code></pre>
      </div>
    </div>

    <!-- Section 7 -->
    <div class="guide-section" id="s7">
      <div class="section-header">
        <div class="section-num">7</div>
        <h2>Security Best Practices</h2>
      </div>
      <p>Cloud-native security follows the <strong>principle of least privilege</strong>: every resource has only the permissions it needs, no more.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">hcl</span><span class="code-filename">Least-privilege Lambda IAM role</span></div>
        <pre><code>resource "aws_iam_role" "lambda_exec" {
  name = "${var.project}-lambda-exec"
  assume_role_policy = jsonencode({
    Version = "2012-10-17"
    Statement = [{
      Action    = "sts:AssumeRole"
      Effect    = "Allow"
      Principal = { Service = "lambda.amazonaws.com" }
    }]
  })
}

resource "aws_iam_role_policy" "lambda_policy" {
  role = aws_iam_role.lambda_exec.id
  policy = jsonencode({
    Version = "2012-10-17"
    Statement = [
      # CloudWatch Logs
      {
        Effect   = "Allow"
        Action   = ["logs:CreateLogGroup","logs:CreateLogStream","logs:PutLogEvents"]
        Resource = "arn:aws:logs:*:*:log-group:/aws/lambda/${var.project}-*"
      },
      # DynamoDB - specific table only
      {
        Effect   = "Allow"
        Action   = ["dynamodb:GetItem","dynamodb:PutItem","dynamodb:UpdateItem","dynamodb:Query"]
        Resource = [aws_dynamodb_table.users.arn, "${aws_dynamodb_table.users.arn}/index/*"]
      },
      # Secrets Manager - specific secret only
      {
        Effect   = "Allow"
        Action   = "secretsmanager:GetSecretValue"
        Resource = aws_secretsmanager_secret.app_secret.arn
      }
    ]
  })
}</code></pre>
      </div>
      <div class="callout callout-warn">
        <div class="callout-icon"><iconify-icon icon="lucide:alert-triangle"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Never Use AdministratorAccess on Lambda</div>
          <p>Grant only the specific DynamoDB actions, S3 prefixes, and Secrets Manager ARNs the function actually needs. Overly broad permissions turn a compromised Lambda into a full account takeover. Use IAM Access Analyzer to validate policies.</p>
        </div>
      </div>
    </div>

    <!-- Section 8 -->
    <div class="guide-section" id="s8">
      <div class="section-header">
        <div class="section-num">8</div>
        <h2>Cost Optimisation</h2>
      </div>
      <p>Cloud costs can spiral without guardrails. Embed cost awareness into your architecture decisions from day one.</p>
      <ul>
        <li><strong>Lambda</strong>: Billed per request + duration (ms). Optimise memory size — more memory = faster execution = often same or lower cost. Use <a href="https://github.com/alexcasalboni/aws-lambda-power-tuning" style="color:var(--primary)">Lambda Power Tuning</a> to find the optimal memory setting.</li>
        <li><strong>DynamoDB</strong>: Use on-demand billing for unpredictable traffic; provision capacity with auto-scaling for steady workloads. Archive old data to S3 + Athena.</li>
        <li><strong>S3</strong>: Set lifecycle policies to move infrequently accessed objects to S3-IA or Glacier after 30/90 days.</li>
        <li><strong>Reserved / Savings Plans</strong>: Commit to 1-year usage for steady workloads (EC2, RDS, Fargate) for up to 72% savings.</li>
      </ul>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">bash</span><span class="code-filename">AWS CLI cost monitoring</span></div>
        <pre><code># Get monthly cost breakdown by service
aws ce get-cost-and-usage \
  --time-period Start=2025-01-01,End=2025-02-01 \
  --granularity MONTHLY \
  --metrics "BlendedCost" \
  --group-by Type=DIMENSION,Key=SERVICE

# Set a billing alarm (alert at $100)
aws cloudwatch put-metric-alarm \
  --alarm-name "monthly-cost-100" \
  --alarm-description "Alert when monthly bill exceeds $100" \
  --metric-name EstimatedCharges \
  --namespace AWS/Billing \
  --statistic Maximum \
  --period 86400 \
  --threshold 100 \
  --comparison-operator GreaterThanThreshold \
  --alarm-actions arn:aws:sns:us-east-1:123456789:billing-alerts</code></pre>
      </div>
    </div>

    <div class="related-section">
      <div class="related-title"><iconify-icon icon="lucide:link"></iconify-icon> Related Resources</div>
      <div class="related-grid">
        <a href="/Training/Tutorials/aws.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">AWS Fundamentals</div>
        </a>
        <a href="/Training/Guides/cicd-pipeline.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">CI/CD Pipeline Setup</div>
        </a>
        <a href="/Training/Guides/microservices-architecture.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Microservices Architecture</div>
        </a>
        <a href="/Training/Tutorials/cloud-devops.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">Cloud DevOps</div>
        </a>
      </div>
    </div>

  </main>

  <aside>
    <div class="toc-card">
      <div class="toc-title">Contents</div>
      <ul class="toc-list">
        <li><a href="#s1"><span class="toc-num">1</span> Cloud-Native Principles</a></li>
        <li><a href="#s2"><span class="toc-num">2</span> AWS Serverless</a></li>
        <li><a href="#s3"><span class="toc-num">3</span> Terraform IaC</a></li>
        <li><a href="#s4"><span class="toc-num">4</span> Event-Driven Arch.</a></li>
        <li><a href="#s5"><span class="toc-num">5</span> Managed Databases</a></li>
        <li><a href="#s6"><span class="toc-num">6</span> Caching Strategies</a></li>
        <li><a href="#s7"><span class="toc-num">7</span> Security</a></li>
        <li><a href="#s8"><span class="toc-num">8</span> Cost Optimisation</a></li>
      </ul>
      <a href="/Training/Guides/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Guides</a>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
