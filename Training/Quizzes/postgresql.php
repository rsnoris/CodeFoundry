<?php
$page_title = 'PostgreSQL Quiz – 100 Levels – CodeFoundry';
$active_page = 'training';
$quiz_title  = 'PostgreSQL';
$quiz_slug   = 'postgresql';
$quiz_tiers  = [
    [
        'label'     => 'Introduction',
        'questions' => [
            [
                'question' => 'What type of database management system is PostgreSQL?',
                'options'  => [
                    'NoSQL document store',
                    'Open-source object-relational database management system',
                    'In-memory key-value store',
                    'Column-oriented warehouse database',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'Which command is used to list all databases in psql?',
                'options'  => ['\l', '\d', '\c', '\dt'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which SQL statement retrieves data from a table?',
                'options'  => ['GET', 'FETCH', 'SELECT', 'RETRIEVE'],
                'correct'  => 2,
            ],
            [
                'question' => 'What does INSERT INTO do?',
                'options'  => [
                    'Creates a new table',
                    'Adds new rows to a table',
                    'Updates existing rows',
                    'Deletes rows from a table',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does UPDATE do in SQL?',
                'options'  => [
                    'Creates a new record',
                    'Modifies existing records in a table',
                    'Removes records',
                    'Locks a table',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does DELETE FROM do?',
                'options'  => [
                    'Drops the table',
                    'Removes rows from a table based on a condition',
                    'Truncates the table',
                    'Drops the database',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What PostgreSQL data type stores whole numbers?',
                'options'  => ['FLOAT', 'INTEGER', 'TEXT', 'BOOLEAN'],
                'correct'  => 1,
            ],
            [
                'question' => 'Which PostgreSQL data type stores variable-length character strings?',
                'options'  => ['CHAR(n)', 'TEXT', 'VARCHAR(n)', 'Both TEXT and VARCHAR(n)'],
                'correct'  => 3,
            ],
            [
                'question' => 'What does the BOOLEAN data type store?',
                'options'  => [
                    '0 or 1 only',
                    'TRUE, FALSE, or NULL',
                    'Yes or No as strings',
                    'Binary data',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'Which psql command connects to a specific database?',
                'options'  => ['\c dbname', '\l dbname', '\connect dbname together', '\use dbname'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the SERIAL data type do in PostgreSQL?',
                'options'  => [
                    'Stores serial numbers as strings',
                    'Creates an auto-incrementing integer column',
                    'Creates a UUID column',
                    'Stores serial port data',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does SELECT * FROM employees; do?',
                'options'  => [
                    'Selects the first column only',
                    'Retrieves all columns and rows from the employees table',
                    'Counts all rows',
                    'Creates a copy of the table',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the WHERE clause do?',
                'options'  => [
                    'Sorts results',
                    'Filters rows based on a condition',
                    'Groups results',
                    'Joins two tables',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the PRIMARY KEY constraint ensure?',
                'options'  => [
                    'The column is indexed only',
                    'Each row has a unique, non-null identifier',
                    'The column is the first column',
                    'Values are sorted automatically',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the NOT NULL constraint do?',
                'options'  => [
                    'Prevents duplicate values',
                    'Ensures a column cannot contain NULL values',
                    'Sets a default value',
                    'Creates an index',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is NULL in SQL?',
                'options'  => [
                    'Zero',
                    'An empty string',
                    'An unknown or missing value',
                    'False',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'Which command in psql shows all tables in the current database?',
                'options'  => ['\l', '\d', '\dt', '\show'],
                'correct'  => 2,
            ],
            [
                'question' => 'What does the UNIQUE constraint do?',
                'options'  => [
                    'Ensures no two rows have the same value in the column',
                    'Makes the column a primary key',
                    'Prevents NULL values',
                    'Indexes the column with BRIN',
                ],
                'correct'  => 0,
            ],
            [
                'question' => 'What does CREATE TABLE do?',
                'options'  => [
                    'Creates a new database',
                    'Creates a new table with specified columns and constraints',
                    'Creates a new schema',
                    'Creates a new index',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does DROP TABLE do?',
                'options'  => [
                    'Clears all data but keeps structure',
                    'Removes the table structure and all data permanently',
                    'Drops the current database',
                    'Removes constraints from the table',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a schema in PostgreSQL?',
                'options'  => [
                    'A database design document',
                    'A namespace that organizes tables and other objects within a database',
                    'A type of index',
                    'A backup configuration',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the TIMESTAMP data type store?',
                'options'  => [
                    'Only the date',
                    'Only the time',
                    'Both date and time',
                    'Unix epoch seconds as an integer',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'What does the FOREIGN KEY constraint do?',
                'options'  => [
                    'Links a column to the primary key of another table to enforce referential integrity',
                    'Creates a secondary index',
                    'Marks a column as unique',
                    'Creates a cross-database link',
                ],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the psql command to describe a table structure?',
                'options'  => ['\l tablename', '\dt tablename', '\d tablename', '\show tablename'],
                'correct'  => 2,
            ],
            [
                'question' => 'Which SQL keyword removes duplicate rows from a result set?',
                'options'  => ['UNIQUE', 'DISTINCT', 'FILTER', 'NODUPE'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does TRUNCATE TABLE do?',
                'options'  => [
                    'Removes the table entirely',
                    'Removes all rows quickly without logging individual deletions',
                    'Removes rows matching a condition',
                    'Reduces the table to one row',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the DEFAULT keyword do in a column definition?',
                'options'  => [
                    'Sets the value as the primary key',
                    'Specifies a value to use when no value is provided on INSERT',
                    'Prevents NULL values',
                    'Sets the display format',
                ],
                'correct'  => 1,
            ],
        ],
    ],
    [
        'label'     => 'Beginner',
        'questions' => [
            [
                'question' => 'What does INNER JOIN do?',
                'options'  => [
                    'Returns all rows from both tables',
                    'Returns only rows that have matching values in both tables',
                    'Returns all left table rows plus matching right rows',
                    'Returns rows that do not match',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does LEFT JOIN (LEFT OUTER JOIN) return?',
                'options'  => [
                    'Only matching rows',
                    'All rows from the left table and matching rows from the right (NULL for non-matches)',
                    'All rows from the right table',
                    'Only non-matching rows',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does RIGHT JOIN return?',
                'options'  => [
                    'Only matching rows',
                    'All rows from the left table',
                    'All rows from the right table and matching rows from the left (NULL for non-matches)',
                    'All rows from both tables',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'What does FULL OUTER JOIN return?',
                'options'  => [
                    'Only matching rows',
                    'All rows from both tables, with NULLs where there is no match',
                    'Only non-matching rows',
                    'A Cartesian product',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the COUNT() function do?',
                'options'  => [
                    'Sums values',
                    'Counts the number of rows or non-NULL values',
                    'Calculates the average',
                    'Returns the maximum value',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does SUM() do?',
                'options'  => [
                    'Counts rows',
                    'Returns the maximum value',
                    'Returns the total sum of a numeric column',
                    'Returns the average',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'What does GROUP BY do?',
                'options'  => [
                    'Sorts results',
                    'Groups rows with identical values in specified columns so aggregate functions can be applied per group',
                    'Joins tables',
                    'Filters individual rows',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What clause filters groups after GROUP BY (not individual rows)?',
                'options'  => ['WHERE', 'FILTER', 'HAVING', 'GROUP FILTER'],
                'correct'  => 2,
            ],
            [
                'question' => 'What does ORDER BY ASC do?',
                'options'  => [
                    'Sorts results in descending order',
                    'Sorts results in ascending order (default)',
                    'Groups results by order',
                    'Creates an ordered index',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is an index in PostgreSQL?',
                'options'  => [
                    'A table of contents document',
                    'A data structure that improves query speed by enabling faster data lookup',
                    'A constraint on a column',
                    'A sorted copy of the table',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What type of index does PostgreSQL create by default with CREATE INDEX?',
                'options'  => ['Hash index', 'GIN index', 'B-tree index', 'GiST index'],
                'correct'  => 2,
            ],
            [
                'question' => 'What does AVG() return?',
                'options'  => [
                    'The sum of values',
                    'The count of values',
                    'The arithmetic mean of a numeric column',
                    'The median value',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'What does MAX() return?',
                'options'  => [
                    'The minimum value',
                    'The largest value in a column',
                    'The average value',
                    'The top 10 values',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does MIN() return?',
                'options'  => [
                    'The smallest value in a column',
                    'The average value',
                    'The count of rows',
                    'The total sum',
                ],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a CROSS JOIN?',
                'options'  => [
                    'A join using cross-table conditions',
                    'A Cartesian product returning every combination of rows from both tables',
                    'A join that crosses schemas',
                    'An inefficient INNER JOIN',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does LIMIT do in a SELECT query?',
                'options'  => [
                    'Limits the columns returned',
                    'Limits the number of rows returned',
                    'Limits query execution time',
                    'Limits joins to one table',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does OFFSET do?',
                'options'  => [
                    'Skips the first N rows before returning results',
                    'Offsets column values by a number',
                    'Starts the result from a named row',
                    'Creates a row offset index',
                ],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the LIKE operator do?',
                'options'  => [
                    'Compares exact string values',
                    'Pattern matches strings using wildcards (% and _)',
                    'Performs a case-sensitive comparison',
                    'Compares numeric ranges',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What wildcard character in LIKE matches any sequence of characters?',
                'options'  => ['_', '*', '%', '?'],
                'correct'  => 2,
            ],
            [
                'question' => 'What does IS NULL test for?',
                'options'  => [
                    'Zero values',
                    'Empty string values',
                    'Rows where the column value is NULL',
                    'False boolean values',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'What does the BETWEEN operator do?',
                'options'  => [
                    'Selects values outside a range',
                    'Selects values within an inclusive range',
                    'Compares two columns',
                    'Selects between two tables',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does IN do in a WHERE clause?',
                'options'  => [
                    'Checks if a value is in a subquery or list of values',
                    'Performs an INNER join',
                    'Checks membership in a set using regex',
                    'Selects rows IN a specific table',
                ],
                'correct'  => 0,
            ],
            [
                'question' => 'What does a UNIQUE INDEX do compared to a regular index?',
                'options'  => [
                    'It is faster than a regular index',
                    'It additionally enforces that no two rows have the same value(s) in the indexed column(s)',
                    'It compresses the index',
                    'It creates a covering index',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does EXPLAIN do in PostgreSQL?',
                'options'  => [
                    'Explains the table structure',
                    'Shows the query execution plan without actually executing the query',
                    'Provides column documentation',
                    'Shows index definitions',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the COALESCE() function return?',
                'options'  => [
                    'The average of its arguments',
                    'The first non-NULL value from a list of arguments',
                    'The sum of non-NULL values',
                    'NULL if any argument is NULL',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does NULLIF(a, b) return?',
                'options'  => [
                    'NULL if a does not equal b',
                    'b if a equals b',
                    'NULL if a equals b, otherwise a',
                    'a if b is NULL',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'What does a composite index cover?',
                'options'  => [
                    'Multiple databases',
                    'Multiple tables',
                    'Multiple columns in a single index',
                    'Multiple data types',
                ],
                'correct'  => 2,
            ],
        ],
    ],
    [
        'label'     => 'Intermediate',
        'questions' => [
            [
                'question' => 'What is a window function in PostgreSQL?',
                'options'  => [
                    'A function for GUI windows',
                    'A function that performs calculations across a set of rows related to the current row without collapsing them into one',
                    'A function that operates on a fixed window of time',
                    'A cursor-based function',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the OVER() clause specify for a window function?',
                'options'  => [
                    'The output format',
                    'The window frame: partitioning and ordering of rows the function operates on',
                    'The function return type',
                    'A filter condition',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does ROW_NUMBER() do?',
                'options'  => [
                    'Counts all rows in the table',
                    'Assigns a unique sequential integer to each row within a partition',
                    'Returns the table row ID',
                    'Ranks rows with gaps for ties',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between RANK() and DENSE_RANK()?',
                'options'  => [
                    'They are identical',
                    'RANK() leaves gaps for ties; DENSE_RANK() does not',
                    'DENSE_RANK() leaves gaps; RANK() does not',
                    'RANK() is for numbers; DENSE_RANK() is for strings',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does PARTITION BY do in a window function?',
                'options'  => [
                    'Splits the table into physical partitions',
                    'Divides the result set into groups for the window function to operate on independently',
                    'Filters rows before windowing',
                    'Partitions the query plan',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a CTE (Common Table Expression)?',
                'options'  => [
                    'A cached table expression stored permanently',
                    'A temporary named result set defined with WITH that can be referenced in the main query',
                    'A cross-table expression',
                    'A compiled table expression for performance',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a recursive CTE used for?',
                'options'  => [
                    'Improving query performance',
                    'Querying hierarchical or tree-structured data (e.g., org charts)',
                    'Creating recursive indexes',
                    'Generating sequences',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is JSONB in PostgreSQL?',
                'options'  => [
                    'JSON stored as text',
                    'Binary JSON that is stored in a decomposed binary format, allowing indexing and faster operations',
                    'JSON with binary encoding only',
                    'A JSON validator type',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What operator retrieves a JSON object field by key?',
                'options'  => ['-&gt;', '->>', '#&gt;', '->key'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the difference between -&gt; and -&gt;&gt; in PostgreSQL JSONB?',
                'options'  => [
                    'They are identical',
                    '-&gt; returns JSON; -&gt;&gt; returns text',
                    '-&gt;&gt; returns JSON; -&gt; returns text',
                    '-&gt; is for arrays; -&gt;&gt; is for objects',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does full-text search use to match documents?',
                'options'  => [
                    'LIKE operator',
                    'tsvector and tsquery types with @@ operator',
                    'Regular expressions only',
                    'JSONB path queries',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does to_tsvector() do?',
                'options'  => [
                    'Converts a string to a vector of numbers',
                    'Converts a document text into a tsvector for full-text search indexing',
                    'Converts a timestamp to a vector',
                    'Creates an array from text',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does to_tsquery() do?',
                'options'  => [
                    'Converts text to a timestamp query',
                    'Converts a query string to a tsquery for full-text search matching',
                    'Creates a text search vector',
                    'Parses SQL queries',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a VIEW in PostgreSQL?',
                'options'  => [
                    'A screenshot of query results',
                    'A stored SELECT query that can be referenced like a table',
                    'A temporary table',
                    'A materialized result set',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a MATERIALIZED VIEW?',
                'options'  => [
                    'The same as a regular view',
                    'A view whose query results are stored on disk and must be refreshed explicitly',
                    'A view with materialized joins',
                    'A view optimized by the query planner',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'How do you refresh a materialized view?',
                'options'  => [
                    'UPDATE MATERIALIZED VIEW name',
                    'REFRESH MATERIALIZED VIEW name',
                    'RELOAD MATERIALIZED VIEW name',
                    'ALTER MATERIALIZED VIEW REFRESH name',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a stored procedure in PostgreSQL?',
                'options'  => [
                    'A saved query template',
                    'A named block of PL/pgSQL (or other language) code stored in the database that can be called',
                    'A scheduled SQL job',
                    'A parameterized view',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between a function and a stored procedure in PostgreSQL 11+?',
                'options'  => [
                    'They are identical',
                    'Functions must return a value; procedures can manage transactions (COMMIT/ROLLBACK) and do not need to return a value',
                    'Procedures are faster',
                    'Functions cannot take parameters',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does LAG() window function do?',
                'options'  => [
                    'Looks ahead to the next row',
                    'Accesses a value from a previous row without a self-join',
                    'Calculates a running lag time',
                    'Returns the lag between two timestamps',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does LEAD() window function do?',
                'options'  => [
                    'Accesses a value from a previous row',
                    'Accesses a value from a following row',
                    'Calculates the moving average ahead',
                    'Returns the next row ID',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is SUM() OVER() used for?',
                'options'  => [
                    'A grouped sum',
                    'A running total (cumulative sum) without collapsing rows',
                    'Summing within a subquery',
                    'A partitioned group sum',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does LATERAL in a FROM clause do?',
                'options'  => [
                    'Joins tables laterally by column',
                    'Allows a subquery to reference columns from preceding FROM items in the same query',
                    'Creates a lateral index',
                    'Cross-joins with ordering',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does RETURNING do in an INSERT, UPDATE, or DELETE statement?',
                'options'  => [
                    'Returns the number of rows affected only',
                    'Returns the values of columns from the affected rows',
                    'Returns a boolean success flag',
                    'Returns the execution plan',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a trigger in PostgreSQL?',
                'options'  => [
                    'A scheduled job',
                    'A function automatically executed when a specified event (INSERT/UPDATE/DELETE) occurs on a table',
                    'A constraint enforced at application level',
                    'A rule that rewrites queries',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does UNNEST() do?',
                'options'  => [
                    'Removes nesting from subqueries',
                    'Expands an array into a set of rows',
                    'Unnests JSONB objects',
                    'Normalizes nested table data',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the generate_series() function used for?',
                'options'  => [
                    'Generating random data',
                    'Generating a series of integers, timestamps, or other values',
                    'Generating table sequences',
                    'Generating UUIDs in series',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the DISTINCT ON (expression) clause do?',
                'options'  => [
                    'Selects all distinct values',
                    'Keeps only the first row for each distinct value of the expression',
                    'Creates a distinct index',
                    'Removes duplicate columns',
                ],
                'correct'  => 1,
            ],
        ],
    ],
    [
        'label'     => 'Advanced',
        'questions' => [
            [
                'question' => 'What is table partitioning in PostgreSQL?',
                'options'  => [
                    'Splitting a database across servers',
                    'Dividing a large table into smaller, manageable pieces based on a partition key',
                    'Creating multiple schemas',
                    'Sharding across multiple databases',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What are the types of partitioning available in PostgreSQL?',
                'options'  => [
                    'Horizontal and vertical',
                    'Range, list, and hash partitioning',
                    'Horizontal, time, and hash',
                    'Row and column partitioning',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is range partitioning?',
                'options'  => [
                    'Partitioning by random ranges',
                    'Partitioning where each partition holds rows with partition key values within a specified range',
                    'Partitioning by hash ranges',
                    'Partitioning by IP address ranges',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does VACUUM do in PostgreSQL?',
                'options'  => [
                    'Removes all data from the table',
                    'Reclaims storage from dead tuples created by UPDATE and DELETE operations',
                    'Optimizes query plans',
                    'Compresses table data',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does VACUUM FULL do differently from regular VACUUM?',
                'options'  => [
                    'It runs faster',
                    'It fully rewrites the table, reclaiming all dead space (requires table lock)',
                    'It also rebuilds all indexes',
                    'It removes all rows older than one day',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is autovacuum in PostgreSQL?',
                'options'  => [
                    'A manual vacuum script',
                    'A background process that automatically runs VACUUM and ANALYZE to maintain table health',
                    'An automatic database backup',
                    'An automatic index builder',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does ANALYZE do in PostgreSQL?',
                'options'  => [
                    'Analyzes query execution time',
                    'Collects statistics about table column distributions for the query planner',
                    'Detects table corruption',
                    'Checks constraint validity',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does EXPLAIN ANALYZE do?',
                'options'  => [
                    'Shows the estimated plan only',
                    'Executes the query and shows the actual execution plan with real timing and row counts',
                    'Analyzes table structure',
                    'Shows index usage statistics',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does a Seq Scan node in EXPLAIN output indicate?',
                'options'  => [
                    'The query uses an index',
                    'PostgreSQL is reading all rows of the table sequentially',
                    'The table is corrupted',
                    'A hash join is being performed',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is streaming replication in PostgreSQL?',
                'options'  => [
                    'Streaming query results to a client',
                    'Continuously sending WAL records from a primary to a standby server for replication',
                    'Replicating data over HTTP',
                    'A replication method using triggers',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is WAL (Write-Ahead Log)?',
                'options'  => [
                    'A write audit log',
                    'A log of all changes made to the database, written before the changes are applied, used for crash recovery and replication',
                    'A pre-write buffer',
                    'A wall of logs for monitoring',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a hot standby?',
                'options'  => [
                    'A standby server that is running but not used',
                    'A standby server that accepts read-only queries while replicating from the primary',
                    'A warm standby with write capabilities',
                    'A standby in the same datacenter',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a CREATE TYPE used for in PostgreSQL?',
                'options'  => [
                    'Creating a new table type',
                    'Defining a custom data type (composite, enum, range, base type)',
                    'Creating a typed view',
                    'Creating a typed index',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is an ENUM type in PostgreSQL?',
                'options'  => [
                    'A type that stores only integers',
                    'A type consisting of a static, ordered set of string values',
                    'A type for enumerating table rows',
                    'A type that auto-increments',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does a BEFORE trigger do?',
                'options'  => [
                    'Fires after the triggering event',
                    'Fires before the triggering event, allowing the action to be modified or prevented',
                    'Fires instead of the event',
                    'Fires before the transaction commits',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does an AFTER trigger do?',
                'options'  => [
                    'Fires before the event',
                    'Fires after the triggering event completes',
                    'Fires instead of the event',
                    'Fires before the transaction starts',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is an INSTEAD OF trigger?',
                'options'  => [
                    'A trigger that runs before INSTEAD of AFTER',
                    'A trigger that replaces the triggering operation, used for updatable views',
                    'A trigger on foreign tables',
                    'A trigger that overrides constraints',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does REINDEX do?',
                'options'  => [
                    'Refreshes statistics',
                    'Rebuilds one or more indexes from scratch',
                    'Renames an index',
                    'Removes duplicate index entries',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is logical replication in PostgreSQL?',
                'options'  => [
                    'Replication based on logical SQL statements',
                    'Replication at the logical (row) level using a publish/subscribe model, allowing selective table replication',
                    'A replication method using stored procedures',
                    'Replication via triggers',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a composite type in PostgreSQL?',
                'options'  => [
                    'A type that combines text and numbers',
                    'A user-defined type that represents the structure of a row with named fields and types',
                    'A type that inherits from another',
                    'A type for composite indexes',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does pg_dump do?',
                'options'  => [
                    'Dumps query execution plans',
                    'Creates a logical backup of a PostgreSQL database',
                    'Dumps WAL files',
                    'Shows current locks',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the cost estimate in EXPLAIN output represent?',
                'options'  => [
                    'Actual execution time in milliseconds',
                    'An arbitrary unit estimating relative cost (startup..total) for the query planner',
                    'The number of rows to process',
                    'The memory required',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is row-level security (RLS) in PostgreSQL?',
                'options'  => [
                    'Encrypting individual rows',
                    'Policies that restrict which rows users can see or modify based on the user role',
                    'A row-level locking mechanism',
                    'A trigger-based access control',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does CLUSTER do?',
                'options'  => [
                    'Creates a database cluster',
                    'Physically reorders table data according to an index to improve range query performance',
                    'Groups tables into schemas',
                    'Creates a table cluster for partitioning',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a domain in PostgreSQL?',
                'options'  => [
                    'A network domain setting',
                    'A user-defined data type based on an existing type with optional constraints',
                    'A schema alias',
                    'A replication domain',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the pg_stat_activity view show?',
                'options'  => [
                    'Table statistics',
                    'Information about current database connections and their query activity',
                    'Index statistics',
                    'Replication lag',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does LOCK TABLE do?',
                'options'  => [
                    'Permanently locks a table',
                    'Acquires an explicit lock on a table for the duration of the current transaction',
                    'Prevents autovacuum from running',
                    'Locks specific rows only',
                ],
                'correct'  => 1,
            ],
        ],
    ],
    [
        'label'     => 'Expert',
        'questions' => [
            [
                'question' => 'What is a GIN (Generalized Inverted Index) best used for?',
                'options'  => [
                    'Range queries on timestamps',
                    'Indexing composite types and arrays, JSONB, and full-text search',
                    'Hash-based lookups',
                    'Point-in-polygon queries',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a GiST (Generalized Search Tree) index best used for?',
                'options'  => [
                    'Exact B-tree lookups',
                    'Geometric data, range types, full-text search, and custom operator classes',
                    'Integer hash lookups',
                    'JSONB array indexing',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a BRIN (Block Range INdex) index?',
                'options'  => [
                    'A balanced range index',
                    'A small index that stores min/max values per block range, ideal for naturally ordered large tables',
                    'A binary range index',
                    'A block-level replication index',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a covering index (INCLUDE clause) in PostgreSQL 11+?',
                'options'  => [
                    'An index covering all tables',
                    'An index that includes additional non-key columns so queries can be satisfied without a table heap fetch',
                    'An index that covers all columns automatically',
                    'An index covering foreign key constraints',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a partial index?',
                'options'  => [
                    'An index on a subset of columns',
                    'An index built on a subset of rows satisfying a WHERE condition',
                    'A half-built index',
                    'An index for partial string matching',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a foreign data wrapper (FDW)?',
                'options'  => [
                    'A foreign key constraint wrapper',
                    'An extension that allows querying external data sources as if they were local PostgreSQL tables',
                    'A wrapper for foreign schema imports',
                    'A data type for foreign currencies',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does postgres_fdw allow?',
                'options'  => [
                    'Wrapping stored procedures',
                    'Querying remote PostgreSQL databases from within a local PostgreSQL instance',
                    'Importing foreign keys',
                    'Wrapping foreign API calls',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is parallel query execution in PostgreSQL?',
                'options'  => [
                    'Running multiple unrelated queries simultaneously',
                    'Using multiple CPU workers to execute a single query plan in parallel',
                    'Running queries on multiple databases',
                    'Parallel index builds only',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What setting controls the number of parallel workers per query?',
                'options'  => [
                    'max_connections',
                    'max_worker_processes',
                    'max_parallel_workers_per_gather',
                    'parallel_degree',
                ],
                'correct'  => 2,
            ],
            [
                'question' => 'What does pg_stat_user_tables provide?',
                'options'  => [
                    'User-level access logs',
                    'Statistics about table activity (seq scans, index scans, rows inserted/updated/deleted)',
                    'Column statistics',
                    'Trigger execution statistics',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does pg_stat_statements extension provide?',
                'options'  => [
                    'Statement syntax validation',
                    'Tracking execution statistics for all SQL statements executed',
                    'A query plan cache',
                    'A slow query log',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is logical replication in PostgreSQL?',
                'options'  => [
                    'Replication using logical backup/restore',
                    'Row-level replication using a publication/subscription model allowing selective replication',
                    'Replication using logical operators',
                    'A synchronous replication method',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a publication in logical replication?',
                'options'  => [
                    'A WAL file export',
                    'A set of tables on the publisher database whose changes are sent to subscribers',
                    'A database documentation object',
                    'A replication slot configuration',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a replication slot in PostgreSQL?',
                'options'  => [
                    'A named connection for a replica',
                    'A mechanism ensuring WAL is retained until a subscriber has consumed it, preventing loss of replication data',
                    'A slot in the connection pool for replicas',
                    'A WAL segment placeholder',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does EXPLAIN (BUFFERS, ANALYZE) show that regular EXPLAIN ANALYZE does not?',
                'options'  => [
                    'Parallel worker details',
                    'Buffer usage statistics (shared/local blocks hit/read/dirtied/written)',
                    'Lock contention data',
                    'Network I/O statistics',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the pg_trgm extension used for?',
                'options'  => [
                    'Trigonometric math functions',
                    'Trigram-based similarity matching for fuzzy text search with GIN/GiST indexes',
                    'Trigger management',
                    'Triangle geometry operations',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is connection pooling and why is it needed for PostgreSQL?',
                'options'  => [
                    'Pooling query results for reuse',
                    'Reusing database connections to reduce overhead, since PostgreSQL forks a new process per connection',
                    'A method of pooling transactions',
                    'Pooling WAL segments',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is PgBouncer?',
                'options'  => [
                    'A PostgreSQL performance benchmarking tool',
                    'A lightweight connection pooler for PostgreSQL',
                    'A query optimizer add-on',
                    'A PostgreSQL monitoring dashboard',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the hash index in PostgreSQL support?',
                'options'  => [
                    'Range queries',
                    'Only equality comparisons (=)',
                    'Partial matches',
                    'Multi-column lookups',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is table bloat in PostgreSQL?',
                'options'  => [
                    'Tables with too many columns',
                    'Wasted space in table files due to dead tuples not yet reclaimed by VACUUM',
                    'Tables that have grown beyond a size limit',
                    'Tables with bloated statistics',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does pg_repack do?',
                'options'  => [
                    'Repackages PostgreSQL extensions',
                    'Repacks tables and indexes online to remove bloat without a full table lock',
                    'Rebuilds the WAL archive',
                    'Recreates all foreign keys',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between synchronous and asynchronous replication in PostgreSQL?',
                'options'  => [
                    'They are equivalent',
                    'Synchronous replication waits for the standby to confirm receipt before acknowledging the transaction; asynchronous does not wait',
                    'Asynchronous is always more reliable',
                    'Synchronous replication uses logical replication; asynchronous uses streaming',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the operator class in an index definition specify?',
                'options'  => [
                    'The access method of the index',
                    'How operators are applied to index entries, determining which queries the index can satisfy',
                    'The owner of the index',
                    'The storage format of the index',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is just-in-time (JIT) compilation in PostgreSQL?',
                'options'  => [
                    'Compiling queries before execution',
                    'Compiling parts of query execution plans at runtime using LLVM to speed up CPU-intensive queries',
                    'Pre-compiling stored procedures',
                    'A compile-time optimization of PostgreSQL itself',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does pg_stat_bgwriter show?',
                'options'  => [
                    'Background query statistics',
                    'Statistics about the background writer process (buffers written, checkpoint activity)',
                    'Background job execution logs',
                    'Background autovacuum activity',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What is declarative partitioning (PostgreSQL 10+) vs. inheritance-based partitioning?',
                'options'  => [
                    'They are identical since PostgreSQL 10',
                    'Declarative uses PARTITION BY syntax with native routing/pruning; inheritance-based uses table inheritance with manual constraints and triggers',
                    'Inheritance-based is newer',
                    'Declarative only supports range partitioning',
                ],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the EXCLUDE constraint in PostgreSQL allow?',
                'options'  => [
                    'Excluding NULL values',
                    'Defining exclusion constraints using GiST indexes to prevent overlapping ranges or conflicting values',
                    'Excluding rows from a view',
                    'Excluding columns from an index',
                ],
                'correct'  => 1,
            ],
        ],
    ],
];
require_once __DIR__ . '/quiz-engine.php';
