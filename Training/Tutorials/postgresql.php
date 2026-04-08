<?php
$tutorial_title = 'PostgreSQL';
$tutorial_slug  = 'postgresql';
$quiz_slug      = 'postgresql';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>PostgreSQL (often called "Postgres") is the world\'s most advanced open-source relational database. Created at UC Berkeley in 1986, it is known for strict SQL compliance, extensibility, robustness, and a rich feature set that includes advanced data types, full-text search, geospatial data (PostGIS), time-series (TimescaleDB), and a powerful extension API. Postgres is the recommended choice for new applications that need a robust SQL database.</p>',
        'concepts' => [
            'PostgreSQL vs. MySQL: feature comparison, standards compliance, extensibility',
            'psql CLI: \\l, \\c, \\dt, \\d table, \\i file.sql, \\copy',
            'PostgreSQL data types: INTEGER, BIGSERIAL, TEXT, VARCHAR, NUMERIC, TIMESTAMPTZ, UUID, BOOLEAN, JSONB, ARRAY',
            'SERIAL / IDENTITY columns for auto-increment primary keys',
            'Schema organisation: CREATE SCHEMA, search_path',
            'psqlrc and .pgpass for connection configuration',
            'pgAdmin 4 and DBeaver as GUI tools',
        ],
        'code' => [
            'title'   => 'PostgreSQL table with modern types',
            'lang'    => 'sql',
            'content' =>
"-- Use gen_random_uuid() for UUID primary keys (no sequential enumeration risk)
CREATE TABLE users (
  id          UUID         NOT NULL DEFAULT gen_random_uuid(),
  name        TEXT         NOT NULL CHECK (length(trim(name)) > 0),
  email       TEXT         NOT NULL,
  metadata    JSONB        NOT NULL DEFAULT '{}',
  tags        TEXT[]       NOT NULL DEFAULT '{}',
  created_at  TIMESTAMPTZ  NOT NULL DEFAULT now(),
  updated_at  TIMESTAMPTZ  NOT NULL DEFAULT now(),

  PRIMARY KEY (id),
  UNIQUE (email),
  CONSTRAINT chk_email CHECK (email ~* '^[^@]+@[^@]+\\.[^@]+$')
);

-- Partial index: only active users need fast lookup by email
CREATE INDEX idx_active_email ON users (email)
WHERE metadata->>'status' = 'active';",
        ],
        'tips' => [
            'Use TIMESTAMPTZ (timestamp with time zone) for all timestamps — it stores UTC and converts on display.',
            'UUID primary keys prevent sequential ID enumeration and work well across distributed systems.',
            'JSONB is faster than JSON for queries and indexing — always prefer JSONB in PostgreSQL.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>PostgreSQL\'s extensions ecosystem — <code>\\dx</code> to list, <code>CREATE EXTENSION</code> to install — dramatically expands its capabilities. The <code>pg_trgm</code> extension enables fast fuzzy search with trigram GIN/GIST indexes. <code>uuid-ossp</code> provides UUID generation functions. <code>pgcrypto</code> adds cryptographic functions.</p><p>PostgreSQL-specific features like <code>RETURNING</code>, <code>ON CONFLICT</code> (upsert), <code>GENERATED</code> columns, and robust ARRAY and JSONB operators distinguish PostgreSQL from other SQL databases.</p>',
        'concepts' => [
            'Extensions: CREATE EXTENSION, \\dx; pg_trgm, pgcrypto, uuid-ossp, unaccent',
            'RETURNING clause: INSERT/UPDATE/DELETE ... RETURNING id, created_at',
            'ON CONFLICT (upsert): DO NOTHING or DO UPDATE SET col = EXCLUDED.col',
            'GENERATED columns: GENERATED ALWAYS AS (expr) STORED',
            'Array operations: ANY, ALL, @> (contains), array_agg(), unnest()',
            'JSONB operators: ->, ->>, @>, #>, jsonb_set(), jsonb_build_object()',
            'Sequences: CREATE SEQUENCE, nextval(), currval(), pg_get_serial_sequence()',
        ],
        'code' => [
            'title'   => 'PostgreSQL UPSERT with RETURNING',
            'lang'    => 'sql',
            'content' =>
"-- Upsert: insert or update if email already exists
INSERT INTO users (name, email, metadata)
VALUES ('Alice', 'alice@example.com', '{\"plan\": \"pro\"}'::jsonb)
ON CONFLICT (email) DO UPDATE
  SET name       = EXCLUDED.name,
      metadata   = users.metadata || EXCLUDED.metadata,  -- merge JSONB
      updated_at = now()
RETURNING id, created_at, (xmax = 0) AS was_inserted;  -- xmax=0 means new row

-- JSONB querying
SELECT id, name, metadata->>'plan' AS plan
FROM users
WHERE metadata @> '{\"plan\": \"pro\"}'::jsonb  -- contains operator
ORDER BY created_at DESC;",
        ],
        'tips' => [
            'Use EXCLUDED.column_name in ON CONFLICT DO UPDATE to refer to the values you tried to insert.',
            'The RETURNING clause lets you get back generated values without a second SELECT round-trip.',
            '|| on JSONB merges two objects — perfect for partial metadata updates.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>PostgreSQL\'s query planner is highly sophisticated. Understanding its statistics (pg_stats, pg_statistic), the planner methods it considers, and how to read EXPLAIN ANALYZE output — actual vs. estimated rows, buffer hits vs. disk reads — is the core skill for query optimisation. Index types beyond B-tree — GIN for JSONB and full-text search, GiST for geometric and range types, BRIN for sequential data — give the planner efficient access paths for diverse workloads.</p>',
        'concepts' => [
            'EXPLAIN ANALYZE: nodes, actual rows vs. estimated, Buffers (shared hit/read)',
            'Index types: B-tree, Hash, GIN (JSONB, arrays, FTS), GiST, BRIN, SP-GiST',
            'Index-only scans: covering indexes with INCLUDE clause',
            'Full-text search: to_tsvector, to_tsquery, GIN index, ts_rank_cd',
            'Table statistics: ANALYZE, pg_stats, auto_vacuum and its settings',
            'Parallel query: max_parallel_workers_per_gather, parallel seq scan',
            'Declarative partitioning: RANGE, LIST, HASH, partition pruning',
        ],
        'code' => [
            'title'   => 'Full-text search with GIN index',
            'lang'    => 'sql',
            'content' =>
"-- Add a generated tsvector column for full-text search
ALTER TABLE posts
  ADD COLUMN search_vector TSVECTOR
    GENERATED ALWAYS AS (
      to_tsvector('english', coalesce(title,'') || ' ' || coalesce(body,''))
    ) STORED;

CREATE INDEX idx_fts ON posts USING GIN (search_vector);

-- Full-text query with ranking
SELECT
  id,
  title,
  ts_rank_cd(search_vector, query)    AS rank,
  ts_headline('english', body, query,
    'MaxWords=50,MinWords=25')         AS excerpt
FROM posts,
     to_tsquery('english', 'postgresql & performance') AS query
WHERE search_vector @@ query
ORDER BY rank DESC
LIMIT 10;",
        ],
        'tips' => [
            'GENERATED ALWAYS AS ... STORED keeps the tsvector current automatically — no trigger needed.',
            'GIN indexes support containment operators (@>, <@, @@) — they are the right choice for JSONB and FTS.',
            'Run EXPLAIN (ANALYZE, BUFFERS) to see buffer hit/miss ratios — high disk reads indicate buffer pool pressure.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>PostgreSQL\'s concurrency model — MVCC (Multi-Version Concurrency Control) — means readers never block writers and writers never block readers. Understanding transaction isolation levels (READ COMMITTED, REPEATABLE READ, SERIALIZABLE), advisory locks, and SELECT FOR UPDATE is essential for building correct concurrent applications. Row Level Security (RLS) enforces access control inside the database.</p><p>Logical replication decouples subscribers from the replication slot, enabling live schema migrations, CDC (Change Data Capture) pipelines, and streaming data to analytics systems.</p>',
        'concepts' => [
            'MVCC: read views, visibility, vacuum and dead tuple reclamation',
            'Advisory locks: pg_advisory_lock, pg_try_advisory_lock for distributed locks',
            'SELECT FOR UPDATE / SKIP LOCKED for queue-style processing',
            'Logical replication: publication, subscription, logical replication slots',
            'pg_logical and Debezium for CDC pipelines',
            'Foreign Data Wrappers: postgres_fdw, file_fdw, oracle_fdw',
            'Tablespaces: placing tables/indexes on specific disks',
        ],
        'code' => [
            'title'   => 'SELECT FOR UPDATE SKIP LOCKED — job queue',
            'lang'    => 'sql',
            'content' =>
"-- High-concurrency job queue pattern
-- Multiple workers claim and process jobs without stepping on each other
WITH claimed AS (
  SELECT id
  FROM jobs
  WHERE
    status = 'pending'
    AND run_after <= now()
    AND attempts < 5
  ORDER BY priority DESC, run_after ASC
  LIMIT 1
  FOR UPDATE SKIP LOCKED  -- skip jobs locked by other workers
)
UPDATE jobs
SET
  status    = 'processing',
  worker_id = pg_backend_pid(),
  started_at = now(),
  attempts  = attempts + 1
FROM claimed
WHERE jobs.id = claimed.id
RETURNING jobs.*;",
        ],
        'tips' => [
            'SKIP LOCKED is the correct pattern for worker queues — it avoids lock contention on popular rows.',
            'Long-running transactions prevent VACUUM from reclaiming dead tuples — always set statement_timeout.',
            'Use pg_logical_replication_slot_get_changes() to inspect the replication stream before connecting a subscriber.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert PostgreSQL involves writing custom extensions in C, understanding the storage layer (heap files, FSM, VM, TOAST), WAL internals, and tuning for specific workloads. High-availability setups with Patroni or Citus for horizontal scaling, pgBouncer for connection pooling, and automated backup + PITR (Point-In-Time Recovery) with pgBackRest form the production PostgreSQL architecture.</p>',
        'concepts' => [
            'PostgreSQL extension API: C extension with PG_MODULE_MAGIC, pg_proc, operator classes',
            'WAL: write-ahead log, checkpoint, pg_basebackup, wal_level settings',
            'Point-in-time recovery: base backup + WAL archive replay',
            'Patroni: DCS-based automatic failover for PostgreSQL HA',
            'pgBouncer: transaction pooling, pool_mode, server_lifetime, stats query',
            'Citus: sharding PostgreSQL with distributed tables and reference tables',
            'pg_stat_statements: top queries by total_time, mean_exec_time, calls',
        ],
        'code' => [
            'title'   => 'pg_stat_statements — top 10 slowest queries',
            'lang'    => 'sql',
            'content' =>
"-- Must have pg_stat_statements enabled in postgresql.conf:
-- shared_preload_libraries = 'pg_stat_statements'
CREATE EXTENSION IF NOT EXISTS pg_stat_statements;

-- Top 10 queries by total execution time
SELECT
  left(query, 120)             AS query_sample,
  calls,
  total_exec_time::BIGINT      AS total_ms,
  mean_exec_time::BIGINT       AS mean_ms,
  stddev_exec_time::BIGINT     AS stddev_ms,
  rows / calls                 AS avg_rows,
  shared_blks_hit + shared_blks_read AS total_blocks
FROM pg_stat_statements
WHERE calls > 100  -- ignore one-off queries
ORDER BY total_exec_time DESC
LIMIT 10;

-- Reset statistics after a performance fix:
SELECT pg_stat_statements_reset();",
        ],
        'tips' => [
            'Add pg_stat_statements to shared_preload_libraries on every production server — it has minimal overhead.',
            'Use pgBouncer in transaction mode for most web applications — it multiplexes thousands of app connections.',
            'Follow the PostgreSQL release notes and commitfest to track new planner and executor improvements.',
            'Read "The Internals of PostgreSQL" (interdb.jp) for a deep-dive into the storage and execution internals.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
