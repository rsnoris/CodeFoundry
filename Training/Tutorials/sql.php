<?php
$tutorial_title = 'SQL';
$tutorial_slug  = 'sql';
$quiz_slug      = 'sql';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>SQL (Structured Query Language) is the standard language for relational database management. Created in the 1970s based on Edgar Codd\'s relational model, SQL lets you define schemas, insert and update data, and retrieve exactly what you need from complex, multi-table databases. SQL is the single most valuable skill in data-related careers and is used daily by developers, analysts, data scientists, and DBAs alike.</p>',
        'concepts' => [
            'Relational model: tables, rows, columns, primary keys, foreign keys',
            'SELECT: selecting columns, aliases (AS), DISTINCT',
            'WHERE: filtering with comparison and logical operators (AND, OR, NOT, BETWEEN, IN, LIKE)',
            'ORDER BY: ASC and DESC sorting',
            'LIMIT / TOP / FETCH FIRST for row count control',
            'NULL handling: IS NULL, IS NOT NULL, COALESCE, NULLIF',
            'Basic data types: INTEGER, VARCHAR, TEXT, DATE, TIMESTAMP, BOOLEAN, DECIMAL',
        ],
        'code' => [
            'title'   => 'SELECT with filtering and sorting',
            'lang'    => 'sql',
            'content' =>
"-- Find active users registered in the last 30 days, ordered by name
SELECT
    id,
    name,
    email,
    created_at
FROM users
WHERE
    active = true
    AND created_at >= CURRENT_DATE - INTERVAL '30 days'
    AND email NOT LIKE '%+%'   -- exclude address-tagged emails
ORDER BY name ASC
LIMIT 50;

-- NULL-safe email display
SELECT
    name,
    COALESCE(phone, 'N/A') AS phone,
    NULLIF(bio, '') AS bio  -- treat empty string as NULL
FROM users;",
        ],
        'tips' => [
            'Always use parameterised queries in application code — never interpolate user input into SQL strings.',
            'COALESCE returns the first non-NULL argument — use it to provide defaults for nullable columns.',
            'Use EXPLAIN (or EXPLAIN ANALYZE in PostgreSQL) to understand how the database executes your query.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Joins are the mechanism for combining data from multiple tables. INNER JOIN returns matching rows from both tables; LEFT JOIN returns all rows from the left table plus matches from the right. Aggregation functions (COUNT, SUM, AVG, MIN, MAX) combined with GROUP BY and HAVING produce summary statistics across groups of rows.</p><p>Subqueries — SELECT statements nested inside other queries — are the workhorse of complex data retrieval, though modern SQL increasingly replaces them with more readable CTEs (Common Table Expressions) using the WITH clause.</p>',
        'concepts' => [
            'INNER JOIN, LEFT JOIN, RIGHT JOIN, FULL OUTER JOIN, CROSS JOIN',
            'Self-joins: joining a table to itself (hierarchy, predecessor/successor)',
            'Aggregate functions: COUNT(), SUM(), AVG(), MIN(), MAX(), COUNT(DISTINCT col)',
            'GROUP BY and HAVING (filter on aggregates)',
            'Subqueries: correlated vs. non-correlated subqueries',
            'CTEs: WITH cte_name AS (SELECT ...) SELECT ...',
            'INSERT INTO ... VALUES ...; INSERT INTO ... SELECT ...;',
            'UPDATE ... SET ... WHERE ...; DELETE FROM ... WHERE ...;',
        ],
        'code' => [
            'title'   => 'JOIN with GROUP BY and HAVING',
            'lang'    => 'sql',
            'content' =>
"-- Orders with customer info and totals
SELECT
    c.name                           AS customer,
    COUNT(o.id)                      AS order_count,
    SUM(o.total_amount)              AS lifetime_value,
    AVG(o.total_amount)              AS avg_order_value,
    MAX(o.created_at)                AS last_order_date
FROM customers c
INNER JOIN orders o ON o.customer_id = c.id
WHERE o.status = 'completed'
GROUP BY c.id, c.name
HAVING COUNT(o.id) >= 3              -- only customers with 3+ orders
   AND SUM(o.total_amount) > 500
ORDER BY lifetime_value DESC;",
        ],
        'tips' => [
            'Use LEFT JOIN when you want all rows from the left table even without a matching right-side row.',
            'Move WHERE conditions on the joined table (not the drive table) inside the ON clause for LEFT JOINs.',
            'A CTE (WITH clause) improves readability and can be referenced multiple times in the same query.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Window functions are SQL\'s most powerful analytical feature. They compute values across a set of rows related to the current row — without collapsing them like GROUP BY does. ROW_NUMBER, RANK, DENSE_RANK, LAG, LEAD, SUM OVER, and running aggregates enable cohort analysis, ranking, and time-series calculations that would otherwise require multiple queries or application code.</p><p>CASE expressions implement conditional logic in SQL, and advanced JOINs (LATERAL, CROSS APPLY) unlock correlated subqueries in the FROM clause.</p>',
        'concepts' => [
            'Window functions: OVER(), PARTITION BY, ORDER BY within window',
            'Ranking: ROW_NUMBER(), RANK(), DENSE_RANK(), NTILE()',
            'Analytics: LAG(), LEAD(), FIRST_VALUE(), LAST_VALUE()',
            'Running aggregates: SUM() OVER(...), AVG() OVER(...), cumulative distribution',
            'CASE WHEN ... THEN ... ELSE ... END expressions',
            'LATERAL / CROSS APPLY for correlated subqueries in FROM',
            'PIVOT and conditional aggregation: SUM(CASE WHEN ... THEN col END)',
        ],
        'code' => [
            'title'   => 'Window function ranking and running totals',
            'lang'    => 'sql',
            'content' =>
"-- Rank customers by lifetime value within each country
-- and show running total
WITH ranked AS (
    SELECT
        c.name,
        c.country,
        SUM(o.total_amount)  AS lifetime_value,
        RANK() OVER (
            PARTITION BY c.country
            ORDER BY SUM(o.total_amount) DESC
        )                    AS country_rank,
        SUM(SUM(o.total_amount)) OVER (
            PARTITION BY c.country
            ORDER BY SUM(o.total_amount) DESC
            ROWS BETWEEN UNBOUNDED PRECEDING AND CURRENT ROW
        )                    AS running_total
    FROM customers c
    JOIN orders o ON o.customer_id = c.id
    WHERE o.status = 'completed'
    GROUP BY c.id, c.name, c.country
)
SELECT * FROM ranked WHERE country_rank <= 5
ORDER BY country, country_rank;",
        ],
        'tips' => [
            'PARTITION BY is like GROUP BY for window functions — it resets the window for each partition.',
            'LAG(col, 1) gets the previous row\'s value; LEAD(col, 1) gets the next row\'s — essential for time-series deltas.',
            'Use ROWS BETWEEN for frame specification over physical rows; RANGE BETWEEN for value-based frames.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Query optimisation begins with understanding the query planner: indexes (B-tree, hash, GiST, GIN), execution plans (EXPLAIN ANALYZE), and the statistics the planner uses to estimate row counts. Well-placed indexes can reduce query time from minutes to milliseconds; missing indexes cause full table scans that degrade under load.</p><p>Transactions (ACID guarantees), isolation levels (READ COMMITTED, REPEATABLE READ, SERIALIZABLE), and lock types determine concurrent data safety. Recursive CTEs enable tree and graph traversals directly in SQL.</p>',
        'concepts' => [
            'B-tree indexes: equality, range, prefix queries; composite index column order',
            'Partial indexes: WHERE clause on the index for filtered workloads',
            'EXPLAIN ANALYZE: seq scan vs. index scan, cost, rows, buffers',
            'ACID: atomicity, consistency, isolation, durability',
            'Transaction isolation levels and anomalies (dirty read, phantom read)',
            'Recursive CTEs: WITH RECURSIVE for hierarchical/graph queries',
            'Materialized views: CREATE MATERIALIZED VIEW, REFRESH',
        ],
        'code' => [
            'title'   => 'Recursive CTE for hierarchy traversal',
            'lang'    => 'sql',
            'content' =>
"-- Traverse an employee → manager hierarchy to any depth
WITH RECURSIVE org_chart AS (
    -- Anchor: start with the CEO (no manager)
    SELECT
        id,
        name,
        manager_id,
        0           AS depth,
        name::TEXT  AS path
    FROM employees
    WHERE manager_id IS NULL

    UNION ALL

    -- Recursive: join each employee to their parent row
    SELECT
        e.id,
        e.name,
        e.manager_id,
        oc.depth + 1,
        oc.path || ' > ' || e.name
    FROM employees e
    INNER JOIN org_chart oc ON oc.id = e.manager_id
)
SELECT depth, name, path
FROM org_chart
ORDER BY path;",
        ],
        'tips' => [
            'Always run EXPLAIN ANALYZE (not just EXPLAIN) — ANALYZE shows actual vs. estimated rows, which diagnoses stale statistics.',
            'Index the foreign key column on the many-side of every relationship — joins on un-indexed FKs are slow.',
            'Use partial indexes (WHERE active = true) to index only the subset of rows actually queried.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert SQL covers query rewriting for performance, statistics management (ANALYZE, pg_stats), function-based indexes, and understanding the internal data structures (heap files, TOAST, WAL) of production databases. JSON operators, full-text search, and geospatial queries (PostGIS) extend SQL beyond simple relational operations.</p><p>Stored procedures, triggers, row-level security (RLS), and database-level access control implement business logic and multi-tenant security inside the database — closer to the data and in a single transaction boundary.</p>',
        'concepts' => [
            'JSON/JSONB operators: ->, ->>, @>, #>, json_path_query',
            'Full-text search: to_tsvector, to_tsquery, GIN index, ts_rank',
            'PostGIS: ST_Distance, ST_Within, ST_Intersects, spatial indexes',
            'Row Level Security (RLS): CREATE POLICY, USING, WITH CHECK',
            'Stored procedures and functions: PL/pgSQL, RETURNS TABLE, LANGUAGE SQL',
            'Triggers: BEFORE/AFTER INSERT/UPDATE/DELETE, trigger functions',
            'Partitioning: RANGE, LIST, HASH; partition pruning',
        ],
        'code' => [
            'title'   => 'Row Level Security for multi-tenancy',
            'lang'    => 'sql',
            'content' =>
"-- Row Level Security: each user sees only their organisation's data
ALTER TABLE documents ENABLE ROW LEVEL SECURITY;

-- Policy: users can SELECT documents belonging to their organisation
CREATE POLICY docs_isolation ON documents
    FOR SELECT
    USING (organisation_id = current_setting('app.current_org_id')::uuid);

-- Policy: users can INSERT only into their own organisation
CREATE POLICY docs_insert ON documents
    FOR INSERT
    WITH CHECK (organisation_id = current_setting('app.current_org_id')::uuid);

-- Application sets the setting at connection start:
-- SET app.current_org_id = 'org-uuid-here';
-- Or via a transaction:
-- BEGIN; SET LOCAL app.current_org_id = ?; ... COMMIT;",
        ],
        'tips' => [
            'Use RLS instead of application-level WHERE clauses for multi-tenant isolation — the database enforces it.',
            'JSONB (binary JSON) is faster than JSON for indexing and querying — always use JSONB in PostgreSQL.',
            'Study "Use the Index, Luke" (use-the-index-luke.com) — the definitive guide to SQL indexing.',
            'Follow the PostgreSQL weekly newsletter and release notes for new query planner improvements.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
