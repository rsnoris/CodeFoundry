<?php
$tutorial_title = 'GraphQL';
$tutorial_slug  = 'graphql';
$quiz_slug      = 'graphql';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>GraphQL is a query language for APIs and a runtime for executing those queries, developed by Facebook (Meta) in 2012 and open-sourced in 2015. Unlike REST, where the server dictates the response shape, GraphQL lets the client specify exactly what data it needs — no over-fetching, no under-fetching. A single endpoint handles all operations through a strongly-typed schema.</p><p>This tier introduces the GraphQL type system, queries, and how to explore an API using GraphiQL or GraphQL Playground.</p>',
        'concepts' => [
            'GraphQL vs. REST: one endpoint, client-specified fields, strongly typed',
            'Schema Definition Language (SDL): type, scalar, enum, interface, union, input',
            'Built-in scalars: String, Int, Float, Boolean, ID',
            'Query basics: selecting fields, aliases, nested objects',
            'Arguments: passing parameters to fields',
            'Variables: parameterised queries with $variable: Type',
            'GraphiQL and GraphQL Playground for interactive exploration',
        ],
        'code' => [
            'title'   => 'GraphQL query with variables',
            'lang'    => 'graphql',
            'content' =>
'# Schema (SDL)
type User {
  id:    ID!
  name:  String!
  email: String!
  posts: [Post!]!
}

type Post {
  id:        ID!
  title:     String!
  published: Boolean!
  author:    User!
}

type Query {
  user(id: ID!): User
  posts(limit: Int = 10, published: Boolean): [Post!]!
}

# Client query with variable
query GetUser($id: ID!) {
  user(id: $id) {
    name
    email
    posts {
      title
      published
    }
  }
}

# Variables JSON: { "id": "42" }',
        ],
        'tips' => [
            'The ! (non-null) modifier is important — it tells clients a field is guaranteed to have a value.',
            'Use variables instead of string interpolation in queries — they are type-safe and prevent injection.',
            'GraphiQL\'s schema documentation panel is the fastest way to explore an unfamiliar GraphQL API.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>GraphQL operations beyond queries include <em>mutations</em> (changing data) and <em>subscriptions</em> (real-time data over WebSocket). Fragments let you define reusable field sets to keep queries DRY, and directives (<code>@include</code>, <code>@skip</code>, <code>@deprecated</code>) control field behaviour at the query or schema level.</p><p>On the server side, resolvers are the functions that actually fetch or compute each field\'s value. Understanding the resolver chain and the context object is the foundation of building any GraphQL server.</p>',
        'concepts' => [
            'Mutations: mutation keyword, input types for mutation arguments',
            'Subscriptions: subscription keyword, real-time data over WebSocket',
            'Fragments: named fragments and inline fragments',
            'Directives: @include(if:), @skip(if:), @deprecated(reason:)',
            'Resolver functions: (parent, args, context, info) signature',
            'Context object: injecting auth user, database connection, DataLoader',
            'Resolver chain: root type → field → nested field resolution',
        ],
        'code' => [
            'title'   => 'GraphQL mutation and subscription',
            'lang'    => 'graphql',
            'content' =>
'# Mutation to create a post
mutation CreatePost($input: CreatePostInput!) {
  createPost(input: $input) {
    id
    title
    published
    author {
      name
    }
  }
}

# Variables: { "input": { "title": "Hello", "body": "World", "published": true } }

# Subscription for real-time new posts
subscription OnNewPost {
  postCreated {
    id
    title
    author {
      name
    }
  }
}

# Reusable fragment
fragment PostFields on Post {
  id
  title
  published
  createdAt
}

query GetPosts {
  posts {
    ...PostFields
    author { name }
  }
}',
        ],
        'tips' => [
            'Always accept mutation arguments as a single input: type — it is easier to extend and version.',
            'Define fragments in a central file and import them in queries to avoid repetition across the codebase.',
            'Inject auth context once at the server level; resolvers read context.user instead of re-checking auth.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>The N+1 problem is the most common GraphQL performance issue: fetching a list of posts and their authors triggers one query per author. DataLoader solves this by batching and caching data loads within a single request, collapsing N queries into one. Every production GraphQL server must use DataLoader for any field that touches a database.</p><p>Apollo Server and GraphQL Yoga are the two most popular Node.js GraphQL server libraries. This tier covers setting up a server, connecting resolvers to real data, and enabling persisted queries for production performance.</p>',
        'concepts' => [
            'N+1 problem: why naive resolvers cause excessive database queries',
            'DataLoader: batch and cache loads within a single request',
            'Apollo Server 4: createHandler, context function, formatError',
            'GraphQL Yoga: createServer, createSchema, plugin system',
            'Schema-first vs. code-first approaches (type-graphql, nexus)',
            'Cursor-based pagination: edges/node, pageInfo, first/after convention',
            'Persisted queries: reducing query payload size in production',
        ],
        'code' => [
            'title'   => 'DataLoader for batching user loads',
            'lang'    => 'javascript',
            'content' =>
"import DataLoader from 'dataloader';

// Batch function: receives array of IDs, returns array of users in same order
async function batchUsers(ids) {
  const users = await db.query(
    'SELECT * FROM users WHERE id = ANY(\$1)', [ids]
  );
  // DataLoader requires results in the same order as the IDs
  const userMap = Object.fromEntries(users.map(u => [u.id, u]));
  return ids.map(id => userMap[id] ?? new Error(`User \${id} not found`));
}

// Create a new DataLoader per request (never share across requests)
function createContext({ req }) {
  return {
    user:       req.user,
    userLoader: new DataLoader(batchUsers),
  };
}

// Resolver uses the loader, not a direct DB call
const resolvers = {
  Post: {
    author: (post, _args, { userLoader }) => userLoader.load(post.authorId),
  },
};",
        ],
        'tips' => [
            'Always create a new DataLoader instance per request — shared loaders cause cross-request data leaks.',
            'Use cursor-based pagination (first/after) instead of offset/limit for correct behaviour on live data.',
            'Enable query complexity analysis to protect your server from deeply nested, expensive queries.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced GraphQL covers schema stitching and federation — composing multiple GraphQL services into a single supergraph. Apollo Federation v2 lets independent teams own separate subgraphs that the Apollo Router merges into a unified schema without tight coupling.</p><p>Authorization in GraphQL requires a different approach than REST: field-level permissions must be enforced in resolvers (or via a directive-based approach like graphql-shield). Error handling, query depth limiting, query cost analysis, and persisted operations complete the production security toolkit.</p>',
        'concepts' => [
            'Apollo Federation v2: @key, @external, @requires, @provides, subgraph schema',
            'Apollo Router: supergraph composition, query planning',
            'Schema stitching with graphql-tools: mergeSchemas, delegateToSchema',
            'Authorization: graphql-shield, role-based field permissions',
            'Query complexity and depth limiting',
            'Error handling: formatError, masking internal errors in production',
            'Subscriptions at scale: Redis pub/sub for multi-instance subscription fan-out',
        ],
        'code' => [
            'title'   => 'Apollo Federation subgraph schema',
            'lang'    => 'graphql',
            'content' =>
'# users subgraph
type User @key(fields: "id") {
  id:    ID!
  name:  String!
  email: String!
}

type Query {
  me: User
  user(id: ID!): User
}

# ---

# posts subgraph — extends User from users subgraph
type User @key(fields: "id") @extends {
  id:    ID! @external
  posts: [Post!]!
}

type Post {
  id:     ID!
  title:  String!
  author: User!
}

type Query {
  posts(limit: Int): [Post!]!
}',
        ],
        'tips' => [
            'Design Federation subgraphs around domain boundaries — each team owns their service end-to-end.',
            'Never expose internal error messages in production GraphQL responses — log them, mask them in the response.',
            'Use graphql-shield policies as the single source of truth for field-level authorization rules.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert GraphQL involves understanding the execution engine — how the query document is parsed, validated, and executed by the runtime — enabling you to write custom directives, execution plugins, and schema transformations. The @defer and @stream directives (now in the GraphQL specification) enable incremental delivery of large query results.</p><p>Client-side, Apollo Client\'s normalized cache, reactive variables, and local state management represent the full power of a GraphQL client ecosystem. Contributing to the GraphQL specification (graphql/graphql-spec) and building developer tooling (code generators, schema linters) are the marks of the expert practitioner.</p>',
        'concepts' => [
            'GraphQL execution: parse → validate → execute phases',
            'Custom scalar types: GraphQLScalarType, parseValue, parseLiteral, serialize',
            'Custom directives: SchemaDirectiveVisitor or transformer pattern',
            '@defer and @stream for incremental delivery',
            'Apollo Client: InMemoryCache, cache policies, reactive variables',
            'graphql-code-generator: typed queries, typed mutations, custom plugins',
            'GraphQL spec contribution: RFC process, reference implementation (graphql-js)',
        ],
        'code' => [
            'title'   => 'Custom Date scalar type',
            'lang'    => 'javascript',
            'content' =>
"import { GraphQLScalarType, Kind } from 'graphql';

const DateScalar = new GraphQLScalarType({
  name:        'Date',
  description: 'ISO 8601 date string, serialised as YYYY-MM-DD',

  serialize(value) {
    if (value instanceof Date) return value.toISOString().slice(0, 10);
    throw new Error('DateScalar: value must be a Date instance');
  },

  parseValue(value) {
    const d = new Date(value);
    if (isNaN(d.getTime())) throw new Error(`Invalid date: \${value}`);
    return d;
  },

  parseLiteral(ast) {
    if (ast.kind !== Kind.STRING) throw new Error('DateScalar: expected string literal');
    const d = new Date(ast.value);
    if (isNaN(d.getTime())) throw new Error(`Invalid date: \${ast.value}`);
    return d;
  },
});

export default DateScalar;",
        ],
        'tips' => [
            'Use graphql-code-generator to keep client-side TypeScript types in sync with the schema automatically.',
            'Implement @defer for heavy fields (analytics, recommendations) so the main payload arrives fast.',
            'Follow the GraphQL Foundation blog and the graphql/graphql-spec repo for specification progress.',
            'Read "Production Ready GraphQL" by Marc-André Giroux for a comprehensive deep-dive.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
