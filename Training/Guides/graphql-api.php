<?php
$page_title  = 'GraphQL API Implementation – CodeFoundry Training';
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
  <span class="breadcrumb-current">GraphQL API Implementation</span>
</div>

<section class="guide-hero">
  <div class="guide-hero-inner">
    <div class="guide-badge"><iconify-icon icon="lucide:share-2"></iconify-icon> Implementation Guide</div>
    <h1>GraphQL API Implementation</h1>
    <p class="guide-hero-desc">Design and build a production GraphQL API with Apollo Server — covering schema design, resolvers, DataLoader optimisation, real-time subscriptions, security, and testing.</p>
    <div class="guide-meta">
      <div class="guide-meta-item"><iconify-icon icon="lucide:clock"></iconify-icon> 45 min read</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Advanced</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:calendar"></iconify-icon> Updated 2025</div>
    </div>
    <div class="topic-tags">
      <span class="topic-tag">GraphQL</span>
      <span class="topic-tag">Apollo</span>
      <span class="topic-tag">Schema Design</span>
      <span class="topic-tag">Subscriptions</span>
    </div>
  </div>
</section>

<div class="guide-layout">
  <main class="guide-content">

    <!-- Section 1 -->
    <div class="guide-section" id="s1">
      <div class="section-header">
        <div class="section-num">1</div>
        <h2>GraphQL Fundamentals</h2>
      </div>
      <p>GraphQL is a query language for APIs and a runtime for executing those queries. Clients specify exactly what data they need — no more over-fetching (REST returning unused fields) or under-fetching (multiple REST round-trips for related data).</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">graphql</span><span class="code-filename">The three root operation types</span></div>
        <pre><code># Query – read data
query GetUser($id: ID!) {
  user(id: $id) {
    id
    name
    email
    posts(limit: 5) {     # nested, no extra round-trip
      title
      publishedAt
    }
  }
}

# Mutation – write data
mutation CreatePost($input: CreatePostInput!) {
  createPost(input: $input) {
    id
    title
    createdAt
  }
}

# Subscription – real-time push via WebSocket
subscription OnNewPost($authorId: ID!) {
  postCreated(authorId: $authorId) {
    id
    title
    author { name }
  }
}</code></pre>
      </div>
      <p>Every GraphQL API has a single endpoint (typically <code>/graphql</code>). The server validates each query against its <strong>type-safe schema</strong> before execution, providing self-documenting APIs via introspection.</p>
      <div class="callout callout-info">
        <div class="callout-icon"><iconify-icon icon="lucide:info"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">GraphQL vs REST</div>
          <p>GraphQL excels when clients have diverse data needs (mobile vs web), when data is highly relational, or when you're building a public API with many consumers. REST is simpler for simple CRUD, caching-heavy scenarios (HTTP caching works at the URL level), or file uploads.</p>
        </div>
      </div>
    </div>

    <!-- Section 2 -->
    <div class="guide-section" id="s2">
      <div class="section-header">
        <div class="section-num">2</div>
        <h2>Schema Design</h2>
      </div>
      <p>The schema is the contract between server and clients. Design it around your <strong>UI requirements</strong> and <strong>business domain</strong>, not your database schema. Use SDL (Schema Definition Language) for clear, version-controllable type definitions.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">graphql</span><span class="code-filename">schema.graphql</span></div>
        <pre><code>scalar DateTime
scalar URL

# Interfaces allow polymorphism
interface Node {
  id: ID!
}

interface Timestamped {
  createdAt: DateTime!
  updatedAt: DateTime!
}

type User implements Node & Timestamped {
  id:        ID!
  name:      String!
  email:     String!
  avatar:    URL
  role:      UserRole!
  posts(
    limit:  Int = 10,
    after:  String     # cursor-based pagination
  ): PostConnection!
  createdAt: DateTime!
  updatedAt: DateTime!
}

enum UserRole { USER ADMIN }

type Post implements Node & Timestamped {
  id:          ID!
  title:       String!
  body:        String!
  author:      User!
  tags:        [String!]!
  status:      PostStatus!
  comments:    [Comment!]!
  likeCount:   Int!
  viewCount:   Int!
  createdAt:   DateTime!
  updatedAt:   DateTime!
}

enum PostStatus { DRAFT PUBLISHED ARCHIVED }

# Cursor-based pagination connection pattern
type PostConnection {
  edges:    [PostEdge!]!
  pageInfo: PageInfo!
  total:    Int!
}
type PostEdge { node: Post!, cursor: String! }
type PageInfo { hasNextPage: Boolean!, endCursor: String }

# Input types for mutations
input CreatePostInput {
  title:  String!
  body:   String!
  tags:   [String!]
  status: PostStatus = DRAFT
}

# Custom directives
directive @auth(requires: UserRole = USER) on FIELD_DEFINITION
directive @rateLimit(max: Int!, window: Int!) on FIELD_DEFINITION

type Query {
  user(id: ID!):      User
  me:                 User          @auth
  posts(
    status: PostStatus,
    limit: Int = 10,
    after: String
  ): PostConnection!
}

type Mutation {
  createPost(input: CreatePostInput!): Post!   @auth
  updatePost(id: ID!, input: CreatePostInput!): Post! @auth
  deletePost(id: ID!): Boolean!               @auth(requires: ADMIN)
}

type Subscription {
  postCreated: Post!
  postUpdated(id: ID!): Post!
}</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Schema Design Principles</div>
          <ul>
            <li>Prefer cursor-based pagination over offset — it's stable when items are inserted or deleted.</li>
            <li>Return mutation payloads, not scalars — this lets you evolve mutations without breaking changes.</li>
            <li>Use <code>!</code> (non-null) aggressively — it signals required data and enables better client-side type inference.</li>
            <li>Never expose internal IDs or database details directly in the schema.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 3 -->
    <div class="guide-section" id="s3">
      <div class="section-header">
        <div class="section-num">3</div>
        <h2>Apollo Server Setup</h2>
      </div>
      <p>Apollo Server 4 is framework-agnostic — it runs as Express middleware, standalone, or on serverless platforms. Use datasource classes to encapsulate database/REST interactions.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">server.ts</span></div>
        <pre><code>import { ApolloServer } from '@apollo/server';
import { expressMiddleware } from '@apollo/server/express4';
import { ApolloServerPluginDrainHttpServer } from '@apollo/server/plugin/drainHttpServer';
import express from 'express';
import http from 'http';
import { typeDefs } from './schema';
import { resolvers } from './resolvers';
import { createContext } from './context';

async function main() {
  const app    = express();
  const httpServer = http.createServer(app);

  const server = new ApolloServer({
    typeDefs,
    resolvers,
    plugins: [ApolloServerPluginDrainHttpServer({ httpServer })],
    formatError: (formattedError, error) => {
      // Don't expose internal errors in production
      if (process.env.NODE_ENV === 'production' && !formattedError.extensions?.code) {
        return { message: 'Internal server error' };
      }
      return formattedError;
    },
  });

  await server.start();

  app.use('/graphql',
    express.json(),
    expressMiddleware(server, {
      context: async ({ req }) => createContext(req),
    })
  );

  await new Promise<void>(resolve => httpServer.listen(4000, resolve));
  console.log('🚀 GraphQL server ready at http://localhost:4000/graphql');
}
main();</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">context.ts</span></div>
        <pre><code>import { Request } from 'express';
import { User } from './models';
import { PostDataSource } from './datasources/PostDataSource';
import { UserDataSource } from './datasources/UserDataSource';
import { verifyToken } from './utils/auth';

export interface Context {
  user:        User | null;
  dataSources: {
    posts: PostDataSource;
    users: UserDataSource;
  };
}

export async function createContext(req: Request): Promise<Context> {
  const token = req.headers.authorization?.replace('Bearer ', '');
  let user: User | null = null;

  if (token) {
    try {
      const payload = verifyToken(token);
      user = await User.findById(payload.sub);
    } catch { /* invalid token — unauthenticated */ }
  }

  return {
    user,
    dataSources: {
      posts: new PostDataSource(),
      users: new UserDataSource(),
    },
  };
}</code></pre>
      </div>
    </div>

    <!-- Section 4 -->
    <div class="guide-section" id="s4">
      <div class="section-header">
        <div class="section-num">4</div>
        <h2>Authentication and Authorization</h2>
      </div>
      <p>Implement authentication in the context function (run once per request). Implement authorisation in resolvers or via directives. Use <strong>graphql-shield</strong> for declarative, rule-based field-level permissions.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">permissions.ts (graphql-shield)</span></div>
        <pre><code>import { shield, rule, and, or, allow, deny } from 'graphql-shield';

const isAuthenticated = rule({ cache: 'contextual' })(
  async (_parent, _args, ctx: Context) => {
    return ctx.user !== null || new Error('You must be logged in');
  }
);

const isAdmin = rule({ cache: 'contextual' })(
  async (_parent, _args, ctx: Context) => {
    return ctx.user?.role === 'ADMIN' || new Error('Admin access required');
  }
);

const isPostOwner = rule({ cache: 'strict' })(
  async (_parent, args, ctx: Context) => {
    const post = await ctx.dataSources.posts.findById(args.id);
    return post?.authorId === ctx.user?.id || new Error('Not the post owner');
  }
);

export const permissions = shield({
  Query: {
    me:    isAuthenticated,
    posts: allow,
    user:  allow,
  },
  Mutation: {
    createPost: isAuthenticated,
    updatePost: and(isAuthenticated, isPostOwner),
    deletePost: and(isAuthenticated, or(isPostOwner, isAdmin)),
  },
  Subscription: {
    postCreated: isAuthenticated,
  },
}, {
  allowExternalErrors: true,
  fallbackError: 'Not authorised',
});</code></pre>
      </div>
    </div>

    <!-- Section 5 -->
    <div class="guide-section" id="s5">
      <div class="section-header">
        <div class="section-num">5</div>
        <h2>Efficient Data Fetching with DataLoader</h2>
      </div>
      <p>Without batching, resolving a list of 10 posts that each request their author would fire 10 individual <code>User.findById()</code> queries — the classic <strong>N+1 problem</strong>. DataLoader solves this by batching all user lookups within a single tick into one query.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">datasources/UserDataSource.ts</span></div>
        <pre><code>import DataLoader from 'dataloader';
import { User } from '../models';

export class UserDataSource {
  // Batch: collect all requested IDs in one tick, then query once
  private userLoader = new DataLoader<string, User | null>(
    async (ids) => {
      const users = await User.find({ _id: { $in: ids } });
      // CRITICAL: results must be in the same order as input IDs
      const map = new Map(users.map(u => [u.id, u]));
      return ids.map(id => map.get(id) ?? null);
    },
    { cache: true }   // per-request cache — same user fetched multiple times hits cache
  );

  async findById(id: string) {
    return this.userLoader.load(id);
  }

  async findByIds(ids: string[]) {
    return this.userLoader.loadMany(ids);
  }
}

// Resolver — looks like N+1 but DataLoader batches it
const resolvers = {
  Post: {
    author: (post: Post, _: unknown, ctx: Context) =>
      ctx.dataSources.users.findById(post.authorId),   // batched!
  },
};</code></pre>
      </div>
      <div class="callout callout-warn">
        <div class="callout-icon"><iconify-icon icon="lucide:alert-triangle"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Create DataLoader Per Request</div>
          <p>Instantiate DataLoader inside the context function (once per request), not at the module level. A shared DataLoader would cache user data across requests, causing privacy leaks where user A sees user B's stale cached data.</p>
        </div>
      </div>
    </div>

    <!-- Section 6 -->
    <div class="guide-section" id="s6">
      <div class="section-header">
        <div class="section-num">6</div>
        <h2>Real-time Subscriptions</h2>
      </div>
      <p>GraphQL subscriptions use WebSockets to push updates to clients. Apollo Server supports subscriptions via the <code>graphql-ws</code> library over a separate WebSocket server.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">subscriptions setup</span></div>
        <pre><code>import { useServer } from 'graphql-ws/lib/use/ws';
import { WebSocketServer } from 'ws';
import { makeExecutableSchema } from '@graphql-tools/schema';
import { PubSub } from 'graphql-subscriptions';

export const pubsub = new PubSub();

const schema = makeExecutableSchema({ typeDefs, resolvers });

// WebSocket server (separate from HTTP)
const wsServer = new WebSocketServer({ server: httpServer, path: '/graphql' });

useServer(
  {
    schema,
    context: async (ctx) => {
      // Authenticate WebSocket connection via connectionParams
      const token = ctx.connectionParams?.authorization as string;
      const user  = token ? await verifyAndGetUser(token) : null;
      return { user, dataSources: createDataSources() };
    },
    onConnect: async (ctx) => {
      if (!ctx.connectionParams?.authorization) {
        throw new Error('Unauthorised');
      }
    },
  },
  wsServer
);</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Subscription resolvers</span></div>
        <pre><code>const POST_CREATED = 'POST_CREATED';

const resolvers = {
  Mutation: {
    createPost: async (_: unknown, { input }: CreatePostArgs, ctx: Context) => {
      const post = await ctx.dataSources.posts.create({
        ...input,
        authorId: ctx.user!.id,
      });

      // Publish event to all subscribers
      pubsub.publish(POST_CREATED, { postCreated: post });

      return post;
    },
  },

  Subscription: {
    postCreated: {
      subscribe: (_: unknown, _args: unknown, ctx: Context) => {
        if (!ctx.user) throw new Error('Not authenticated');
        return pubsub.asyncIterableIterator(POST_CREATED);
      },
      resolve: (payload: { postCreated: Post }) => payload.postCreated,
    },
  },
};</code></pre>
      </div>
      <div class="callout callout-info">
        <div class="callout-icon"><iconify-icon icon="lucide:info"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Production PubSub</div>
          <p>The in-memory <code>PubSub</code> only works on a single server instance. For production with multiple instances, use <strong>Redis PubSub</strong> (<code>graphql-redis-subscriptions</code>) so events published on any instance reach all subscribers regardless of which instance they're connected to.</p>
        </div>
      </div>
    </div>

    <!-- Section 7 -->
    <div class="guide-section" id="s7">
      <div class="section-header">
        <div class="section-num">7</div>
        <h2>Performance Optimisation</h2>
      </div>
      <p>GraphQL's flexibility is a double-edged sword — a malicious client can construct deeply nested or highly complex queries that overwhelm your server. Protect against this with query complexity analysis and depth limiting.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Query complexity and depth limiting</span></div>
        <pre><code>import depthLimit         from 'graphql-depth-limit';
import { createComplexityLimitRule } from 'graphql-validation-complexity';

const server = new ApolloServer({
  typeDefs,
  resolvers,
  validationRules: [
    depthLimit(7),                         // max 7 levels of nesting
    createComplexityLimitRule(1000, {      // max complexity score of 1000
      onCost: (cost) => console.log('Query cost:', cost),
      formatErrorMessage: (cost) =>
        `Query complexity ${cost} exceeds maximum of 1000`,
    }),
  ],
});</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Persisted queries (APQ)</span></div>
        <pre><code">// Automatic Persisted Queries (APQ) — send only the hash on repeat requests
// Client (Apollo Client):
import { createPersistedQueryLink } from '@apollo/client/link/persisted-queries';
import { sha256 } from 'crypto-hash';

const link = createPersistedQueryLink({ sha256 });

// Server — enable APQ with a Redis cache for persistence across restarts
import { KeyvAdapter } from '@apollo/utils.keyvadapter';
import KeyvRedis from '@keyv/redis';

const server = new ApolloServer({
  cache: new KeyvAdapter(new KeyvRedis(process.env.REDIS_URL)),
  // APQ is enabled automatically when a cache is provided
});</code></pre>
      </div>
    </div>

    <!-- Section 8 -->
    <div class="guide-section" id="s8">
      <div class="section-header">
        <div class="section-num">8</div>
        <h2>Testing GraphQL APIs</h2>
      </div>
      <p>Test resolvers in isolation (unit tests) and the full GraphQL execution (integration tests). Use Apollo's <code>executeOperation</code> for integration tests without spinning up an HTTP server.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Resolver unit test</span></div>
        <pre><code">import { resolvers } from '../resolvers';

describe('Post resolvers', () => {
  const mockCtx = {
    user: { id: 'user-1', role: 'USER' },
    dataSources: {
      posts: {
        findById: jest.fn(),
        create:   jest.fn(),
      },
      users: { findById: jest.fn() },
    },
  };

  describe('Query.posts', () => {
    it('returns published posts', async () => {
      const mockPosts = [{ id: '1', title: 'Hello', status: 'PUBLISHED' }];
      mockCtx.dataSources.posts.findById.mockResolvedValue(mockPosts);

      const result = await resolvers.Query.posts(
        {}, { status: 'PUBLISHED' }, mockCtx, {} as any
      );
      expect(result).toEqual(mockPosts);
    });
  });

  describe('Mutation.createPost', () => {
    it('creates post with correct author', async () => {
      const input = { title: 'New Post', body: 'Content', status: 'DRAFT' };
      const expected = { id: '2', ...input, authorId: 'user-1' };
      mockCtx.dataSources.posts.create.mockResolvedValue(expected);

      const result = await resolvers.Mutation.createPost(
        {}, { input }, mockCtx, {} as any
      );
      expect(mockCtx.dataSources.posts.create)
        .toHaveBeenCalledWith({ ...input, authorId: 'user-1' });
      expect(result).toEqual(expected);
    });
  });
});</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">Integration test with executeOperation</span></div>
        <pre><code">import { ApolloServer } from '@apollo/server';
import { typeDefs } from '../schema';
import { resolvers } from '../resolvers';

const GET_ME = `
  query {
    me {
      id
      name
      email
    }
  }
`;

describe('me query', () => {
  let server: ApolloServer;
  beforeAll(async () => {
    server = new ApolloServer({ typeDefs, resolvers });
    await server.start();
  });
  afterAll(() => server.stop());

  it('returns null for unauthenticated request', async () => {
    const { body } = await server.executeOperation(
      { query: GET_ME },
      { contextValue: { user: null, dataSources: mockDataSources() } }
    );
    expect(body.kind).toBe('single');
    expect((body as any).singleResult.data?.me).toBeNull();
  });

  it('returns current user for authenticated request', async () => {
    const mockUser = { id: 'u1', name: 'Alice', email: 'alice@test.com' };
    const { body } = await server.executeOperation(
      { query: GET_ME },
      { contextValue: { user: mockUser, dataSources: mockDataSources() } }
    );
    expect((body as any).singleResult.data?.me).toEqual(mockUser);
  });
});</code></pre>
      </div>
    </div>

    <div class="related-section">
      <div class="related-title"><iconify-icon icon="lucide:link"></iconify-icon> Related Resources</div>
      <div class="related-grid">
        <a href="/Training/Tutorials/graphql.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">GraphQL Fundamentals</div>
        </a>
        <a href="/Training/Tutorials/nodejs.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">Node.js</div>
        </a>
        <a href="/Training/Guides/fullstack-web-app.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Full-Stack Web App</div>
        </a>
        <a href="/Training/Tutorials/typescript.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">TypeScript</div>
        </a>
      </div>
    </div>

  </main>

  <aside>
    <div class="toc-card">
      <div class="toc-title">Contents</div>
      <ul class="toc-list">
        <li><a href="#s1"><span class="toc-num">1</span> Fundamentals</a></li>
        <li><a href="#s2"><span class="toc-num">2</span> Schema Design</a></li>
        <li><a href="#s3"><span class="toc-num">3</span> Apollo Server</a></li>
        <li><a href="#s4"><span class="toc-num">4</span> Auth & Permissions</a></li>
        <li><a href="#s5"><span class="toc-num">5</span> DataLoader / N+1</a></li>
        <li><a href="#s6"><span class="toc-num">6</span> Subscriptions</a></li>
        <li><a href="#s7"><span class="toc-num">7</span> Performance</a></li>
        <li><a href="#s8"><span class="toc-num">8</span> Testing</a></li>
      </ul>
      <a href="/Training/Guides/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Guides</a>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
