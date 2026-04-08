<?php
$tutorial_title = 'MySQL';
$tutorial_slug  = 'mysql';
$quiz_slug      = 'mysql';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>MySQL is the world\'s most popular open-source relational database management system, powering WordPress, Drupal, GitHub, and thousands of other web applications. Originally developed by MySQL AB and now owned by Oracle, MySQL is known for its ease of setup, broad hosting support, and strong read performance. MariaDB is a popular drop-in replacement with additional features and a fully open-source license.</p>',
        'concepts' => [
            'MySQL installation: MySQL Server, mysql CLI, MySQL Workbench',
            'Databases and tables: CREATE DATABASE, USE, CREATE TABLE, SHOW TABLES, DESCRIBE',
            'MySQL data types: INT, BIGINT, VARCHAR(n), TEXT, DECIMAL(p,s), DATE, DATETIME, TINYINT(1) as BOOL',
            'INSERT, SELECT, UPDATE, DELETE basics',
            'AUTO_INCREMENT primary keys',
            'Character sets: utf8mb4 (always use over utf8)',
            'MySQL storage engines: InnoDB (ACID, FK) vs. MyISAM (legacy)',
        ],
        'code' => [
            'title'   => 'MySQL table creation and basic CRUD',
            'lang'    => 'sql',
            'content' =>
"CREATE DATABASE IF NOT EXISTS blog
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE blog;

CREATE TABLE posts (
  id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  title       VARCHAR(200)     NOT NULL,
  slug        VARCHAR(200)     NOT NULL,
  body        LONGTEXT         NOT NULL,
  published   TINYINT(1)       NOT NULL DEFAULT 0,
  created_at  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP
              ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE  KEY uq_slug (slug),
  INDEX       idx_published_date (published, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO posts (title, slug, body, published)
VALUES ('Hello MySQL', 'hello-mysql', 'My first post.', 1);",
        ],
        'tips' => [
            'Always use utf8mb4 — MySQL\'s "utf8" is broken (only 3 bytes, misses emoji and some CJK characters).',
            'Always use InnoDB — it supports transactions, foreign keys, and crash recovery. MyISAM is legacy.',
            'Add ON UPDATE CURRENT_TIMESTAMP to updated_at columns for automatic tracking.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>MySQL\'s JOIN types, GROUP BY aggregations, and subqueries work identically to standard SQL, with a few MySQL-specific extensions. Foreign keys enforce referential integrity between tables, and ON DELETE / ON UPDATE actions (CASCADE, SET NULL, RESTRICT) define what happens to child rows when a parent is modified.</p><p>Transactions (BEGIN / COMMIT / ROLLBACK) with InnoDB ensure that groups of statements succeed or fail atomically, critical for operations like bank transfers or order processing.</p>',
        'concepts' => [
            'Foreign keys: FOREIGN KEY (col) REFERENCES table(col) ON DELETE CASCADE',
            'Transactions: START TRANSACTION, COMMIT, ROLLBACK, SAVEPOINT',
            'MySQL-specific functions: NOW(), DATE_FORMAT(), YEAR(), MONTH(), DAYOFWEEK()',
            'String functions: CONCAT, LOWER, UPPER, TRIM, SUBSTRING, REPLACE, REGEXP',
            'GROUP_CONCAT for aggregating strings into a delimited list',
            'CASE expression and IF() / IFNULL() / COALESCE()',
            'INSERT ... ON DUPLICATE KEY UPDATE for upsert',
        ],
        'code' => [
            'title'   => 'MySQL upsert and GROUP_CONCAT',
            'lang'    => 'sql',
            'content' =>
"-- Upsert: insert or update on duplicate key
INSERT INTO page_views (page_slug, view_date, count)
VALUES ('hello-mysql', CURDATE(), 1)
ON DUPLICATE KEY UPDATE count = count + 1;

-- GROUP_CONCAT: aggregate tags per post
SELECT
  p.title,
  GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ', ') AS tags,
  COUNT(t.id) AS tag_count
FROM posts p
LEFT JOIN post_tags pt ON pt.post_id = p.id
LEFT JOIN tags t        ON t.id = pt.tag_id
GROUP BY p.id, p.title
HAVING tag_count > 0
ORDER BY p.title;",
        ],
        'tips' => [
            'Use ON DUPLICATE KEY UPDATE for counters and upserts — it is atomic and avoids race conditions.',
            'GROUP_CONCAT has a default max length of 1024 bytes — increase with SET group_concat_max_len if needed.',
            'Always specify ON DELETE and ON UPDATE actions on foreign keys — implicit RESTRICT is surprising.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>MySQL query optimisation starts with EXPLAIN and understanding the index types: B-tree (the default), FULLTEXT for text search, and SPATIAL for geometric data. The slow query log and Performance Schema help identify problematic queries in production. Index design — composite index column order, covering indexes, and index selectivity — is the primary lever for query performance.</p><p>Stored procedures, functions, triggers, and events (scheduled tasks) implement server-side logic that runs inside MySQL, useful for data validation, audit logging, and maintenance jobs.</p>',
        'concepts' => [
            'EXPLAIN output: type (ALL/index/range/ref/eq_ref/const), rows, Extra',
            'Composite indexes: leftmost prefix rule, covering indexes',
            'FULLTEXT search: MATCH ... AGAINST, natural language vs. boolean mode',
            'Slow query log: long_query_time, log_queries_not_using_indexes',
            'Performance Schema: events_statements_summary_by_digest',
            'Stored procedures: CREATE PROCEDURE, IN/OUT/INOUT params, DELIMITER',
            'Triggers: BEFORE/AFTER INSERT/UPDATE/DELETE, NEW and OLD row references',
        ],
        'code' => [
            'title'   => 'FULLTEXT search with boolean mode',
            'lang'    => 'sql',
            'content' =>
"-- Add fulltext index
ALTER TABLE posts ADD FULLTEXT INDEX ft_content (title, body);

-- Natural language search (relevance ranked)
SELECT title,
       MATCH(title, body) AGAINST ('mysql performance' IN NATURAL LANGUAGE MODE) AS score
FROM posts
WHERE MATCH(title, body) AGAINST ('mysql performance' IN NATURAL LANGUAGE MODE)
ORDER BY score DESC
LIMIT 10;

-- Boolean mode: must-have (+), must-not (-), wildcard (*)
SELECT title
FROM posts
WHERE MATCH(title, body)
  AGAINST ('+mysql -tutorial index*' IN BOOLEAN MODE);",
        ],
        'tips' => [
            'Add FULLTEXT indexes only on columns you actually search — they consume space and slow writes.',
            'The leftmost prefix rule is critical for composite indexes: (a, b, c) can be used for a, (a,b), or (a,b,c) queries, but not (b) alone.',
            'Run ANALYZE TABLE after large bulk loads — it updates index statistics for the query planner.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>MySQL replication (primary-replica) distributes read load and provides a hot standby for disaster recovery. Understanding binary log formats (ROW, STATEMENT, MIXED), GTID-based replication, and replica lag monitoring are essential for production MySQL operations. MySQL Group Replication and InnoDB Cluster provide multi-primary high-availability topologies.</p><p>Partitioning — range, list, hash, and key — horizontally splits large tables across physical segments, improving query performance and maintenance operations (dropping old partitions) for time-series and high-volume data.</p>',
        'concepts' => [
            'Replication: binary log, GTID, primary-replica setup, replication lag',
            'InnoDB Cluster: Group Replication, MySQL Router, MySQL Shell adminAPI',
            'Partitioning: RANGE, LIST, HASH, KEY; partition pruning, maintenance',
            'InnoDB internals: buffer pool, redo log, undo log, MVCC',
            'Connection pooling: ProxySQL, MySQL Router, connection limits',
            'MySQL JSON column type: JSON_EXTRACT, JSON_SET, generated columns from JSON',
            'MySQL 8 window functions: ROW_NUMBER, RANK, LAG, LEAD (same as standard SQL)',
        ],
        'code' => [
            'title'   => 'MySQL table partitioning by range',
            'lang'    => 'sql',
            'content' =>
"-- Range partition events table by year
CREATE TABLE events (
  id         BIGINT UNSIGNED  NOT NULL AUTO_INCREMENT,
  user_id    INT UNSIGNED     NOT NULL,
  type       VARCHAR(50)      NOT NULL,
  created_at DATETIME         NOT NULL,
  PRIMARY KEY (id, created_at)  -- partition key must be in PK
) ENGINE=InnoDB
PARTITION BY RANGE (YEAR(created_at)) (
  PARTITION p2022 VALUES LESS THAN (2023),
  PARTITION p2023 VALUES LESS THAN (2024),
  PARTITION p2024 VALUES LESS THAN (2025),
  PARTITION pmax  VALUES LESS THAN MAXVALUE
);

-- Add new partition for 2025 before it fills:
ALTER TABLE events REORGANIZE PARTITION pmax INTO (
  PARTITION p2025 VALUES LESS THAN (2026),
  PARTITION pmax  VALUES LESS THAN MAXVALUE
);

-- Drop old data by partition (instant — no row-by-row DELETE):
ALTER TABLE events DROP PARTITION p2022;",
        ],
        'tips' => [
            'Partition pruning only works when the query WHERE clause includes the partition column.',
            'The partition key must be part of every unique key (including PRIMARY KEY) in MySQL.',
            'Monitor replica lag with SHOW REPLICA STATUS\\G — query the replica\'s Seconds_Behind_Source metric.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert MySQL engineering involves deep InnoDB internals knowledge — buffer pool tuning (innodb_buffer_pool_size should be ~70% of RAM), redo log sizing, MVCC and transaction isolation tradeoffs, and deadlock diagnosis using SHOW ENGINE INNODB STATUS. ProxySQL for intelligent connection routing and query rewriting, and Percona XtraBackup for hot physical backups complete the expert DBA toolkit.</p>',
        'concepts' => [
            'InnoDB buffer pool: innodb_buffer_pool_size, buffer pool instances, warming',
            'Redo log: innodb_redo_log_capacity, write-ahead logging, checkpoint',
            'MVCC: read views, undo log chain, long-running transaction impact',
            'Deadlock diagnosis: SHOW ENGINE INNODB STATUS, innodb_print_all_deadlocks',
            'ProxySQL: connection multiplexing, query routing, query rewriting rules',
            'Percona XtraBackup: incremental hot backup, point-in-time recovery',
            'MySQL Shell: JavaScript/Python API, adminAPI for InnoDB Cluster management',
        ],
        'code' => [
            'title'   => 'InnoDB tuning configuration',
            'lang'    => 'ini',
            'content' =>
'[mysqld]
# Buffer pool: ~70% of available RAM for a dedicated MySQL server
innodb_buffer_pool_size        = 12G
innodb_buffer_pool_instances   = 8      # 1 per GB, max 64

# Redo log: larger = fewer checkpoints, faster writes, longer recovery
innodb_redo_log_capacity       = 4G

# I/O
innodb_io_capacity             = 2000   # IOPS your storage can sustain
innodb_io_capacity_max         = 4000
innodb_flush_method            = O_DIRECT  # skip OS cache double-buffering

# Logging
slow_query_log                 = ON
slow_query_log_file            = /var/log/mysql/slow.log
long_query_time                = 1
log_queries_not_using_indexes  = ON
innodb_print_all_deadlocks     = ON

# Replication (on replica)
# read_only = ON
# super_read_only = ON',
        ],
        'tips' => [
            'Set innodb_buffer_pool_size to 70% of RAM on a dedicated database server — everything else is secondary.',
            'Enable innodb_print_all_deadlocks in production — deadlock information is written to the error log.',
            'Follow the MySQL blog (dev.mysql.com/blog) and Percona Database Performance Blog for expert guidance.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
