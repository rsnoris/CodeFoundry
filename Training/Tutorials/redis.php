<?php
$tutorial_title = 'Redis';
$tutorial_slug  = 'redis';
$quiz_slug      = 'redis';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Redis (Remote Dictionary Server) is an in-memory data structure store used as a database, cache, message broker, and queue. It supports strings, hashes, lists, sets, sorted sets, bitmaps, HyperLogLog, geospatial indexes, and streams. Because Redis stores data in RAM, it achieves sub-millisecond response times — making it the go-to solution for session storage, caching, rate limiting, and real-time leaderboards.</p>',
        'concepts' => [
            'Redis data model: key-value store with rich value types',
            'String commands: SET, GET, SETEX, SETNX, INCR, APPEND, GETSET',
            'Key expiry: EXPIRE, TTL, EXPIREAT, PERSIST',
            'Hash commands: HSET, HGET, HMGET, HGETALL, HDEL, HKEYS',
            'List commands: LPUSH, RPUSH, LPOP, RPOP, LRANGE, LLEN',
            'redis-cli: connect, AUTH, SELECT, INFO, KEYS, SCAN',
            'Namespacing keys: colon convention (user:123:profile)',
        ],
        'code' => [
            'title'   => 'Redis basics — strings and hashes',
            'lang'    => 'bash',
            'content' =>
'# String with TTL (session token)
SET session:abc123 "{\"userId\":42,\"role\":\"admin\"}" EX 3600
GET session:abc123
TTL session:abc123

# Atomic counter (page view tracking)
INCR views:page:/about
INCRBY views:page:/about 5
GET views:page:/about

# Hash (user profile — store fields separately)
HSET user:42 name "Alice" email "alice@example.com" plan "pro"
HGET  user:42 name
HMGET user:42 name email plan
HGETALL user:42
HINCRBY user:42 login_count 1

# Key expiry
EXPIRE user:42 86400   # expire in 24 hours
PERSIST user:42        # remove expiry
TTL user:42            # seconds until expiry (-1 = no expiry, -2 = gone)',
        ],
        'tips' => [
            'Always set a TTL on cache keys — unbounded Redis growth eventually fills memory and causes evictions.',
            'Use SETNX (SET ... NX) for distributed locks — it sets the key only if it does not exist.',
            'Use SCAN instead of KEYS in production — KEYS blocks the server; SCAN is non-blocking and cursor-based.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Redis sets, sorted sets, and lists power many common patterns. Sets store unique members (tags, follower lists). Sorted sets add a score to each member, enabling leaderboards, rate limiting, and priority queues where members are ranked by a numeric value. Lists work as message queues and activity feeds.</p><p>Redis Pub/Sub provides a lightweight message broadcasting mechanism where publishers send to channels and subscribers receive messages in real time.</p>',
        'concepts' => [
            'Set commands: SADD, SMEMBERS, SISMEMBER, SINTER, SUNION, SDIFF, SCARD',
            'Sorted set commands: ZADD, ZRANGE, ZREVRANGE, ZRANGEBYSCORE, ZSCORE, ZRANK, ZINCRBY',
            'List as queue: RPUSH + BLPOP or RPUSH + BRPOP for blocking pops',
            'Pub/Sub: PUBLISH, SUBSCRIBE, PSUBSCRIBE (pattern subscribe)',
            'MULTI/EXEC transactions: atomic command blocks',
            'WATCH for optimistic locking: WATCH key, MULTI, ..., EXEC',
            'Lua scripting: EVAL for atomic complex operations',
        ],
        'code' => [
            'title'   => 'Redis sorted set leaderboard',
            'lang'    => 'javascript',
            'content' =>
"import { createClient } from 'redis';

const redis = createClient();
await redis.connect();

const BOARD = 'leaderboard:global';

// Add/update player scores
async function addScore(player, score) {
  await redis.zAdd(BOARD, { score, value: player });
}

// Get top N players (highest score first)
async function getTopN(n = 10) {
  return redis.zRangeWithScores(BOARD, 0, n - 1, { REV: true });
}

// Get a player's rank (0-based, lowest = rank 1 when reversed)
async function getPlayerRank(player) {
  const rank = await redis.zRevRank(BOARD, player);
  return rank !== null ? rank + 1 : null;
}

// Increment score atomically
async function incrementScore(player, delta) {
  return redis.zIncrBy(BOARD, delta, player);
}

await addScore('alice', 4200);
await addScore('bob',   3750);
console.log(await getTopN(5));
console.log('Alice rank:', await getPlayerRank('alice'));",
        ],
        'tips' => [
            'ZADD with the NX flag adds only new members; with XX it updates only existing ones.',
            'BLPOP / BRPOP block until a list item is available — perfect for reliable message queues.',
            'Lua scripts (EVAL) are the correct way to perform atomic read-modify-write operations in Redis.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Redis caching patterns — cache-aside, write-through, and write-behind — each have different consistency and performance tradeoffs. Cache-aside (lazy loading) is the most common: check cache first, miss → load from DB → populate cache. Understanding cache stampede (thundering herd) and prevention techniques (mutex lock, probabilistic early expiry) is essential for high-traffic caching.</p><p>Redis Streams (XADD, XREAD, XREADGROUP) provide a durable, append-only log with consumer groups — a more robust alternative to Pub/Sub for event-driven architectures.</p>',
        'concepts' => [
            'Cache-aside pattern: check, miss, load, populate, TTL strategy',
            'Cache stampede / thundering herd: distributed lock prevention with SET NX PX',
            'Write-through and write-behind caching patterns',
            'Redis Streams: XADD, XREAD, XLEN, XTRIM; consumer groups XREADGROUP, XACK',
            'Redis as message queue: streams vs. pub/sub vs. list-based queues',
            'Rate limiting with INCR + EXPIRE or sliding window with sorted sets',
            'Session storage: centralised session store for horizontal scaling',
        ],
        'code' => [
            'title'   => 'Distributed rate limiter with Redis',
            'lang'    => 'javascript',
            'content' =>
"// Sliding window rate limiter using sorted sets
async function checkRateLimit(redis, key, limit, windowSeconds) {
  const now    = Date.now();
  const window = now - windowSeconds * 1000;

  const multi = redis.multi();
  multi.zRemRangeByScore(key, '-inf', window);         // remove old entries
  multi.zAdd(key, { score: now, value: String(now) }); // add current request
  multi.zCard(key);                                     // count in window
  multi.expire(key, windowSeconds);                     // reset TTL

  const results = await multi.exec();
  const count   = results[2];

  return {
    allowed:   count <= limit,
    count,
    remaining: Math.max(0, limit - count),
    resetAt:   new Date(now + windowSeconds * 1000),
  };
}

// Usage:
const result = await checkRateLimit(
  redis, `rate:api:${userId}`, 100, 60  // 100 requests / 60s
);
if (!result.allowed) {
  res.status(429).json({ error: 'Rate limit exceeded', ...result });
}",
        ],
        'tips' => [
            'Use Redis pipelines (multi/exec) to batch multiple commands in a single round-trip.',
            'The sliding window rate limiter with sorted sets is more accurate than the fixed-window INCR approach.',
            'Consumer groups in Redis Streams allow multiple workers to process messages without duplication.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Redis persistence — RDB (point-in-time snapshots) and AOF (append-only file, every write logged) — determine data durability. For most caching use cases, no persistence is needed; for message queues or session stores, AOF with everysec fsync balances durability and performance. Redis Sentinel provides automatic failover for a single master-replica setup; Redis Cluster distributes data across 16,384 hash slots for horizontal scaling.</p>',
        'concepts' => [
            'RDB persistence: BGSAVE, save directives, snapshot intervals',
            'AOF persistence: appendfsync (always/everysec/no), AOF rewrite',
            'Redis Sentinel: monitoring, automatic failover, client notification',
            'Redis Cluster: hash slots (0–16383), cluster-meet, MOVED/ASK redirections',
            'Cluster limitations: multi-key operations require same hash slot ({})',
            'Memory optimisation: maxmemory-policy (allkeys-lru, volatile-lru, etc.)',
            'Redis keyspace notifications: subscribe to key events (expiry, set, del)',
        ],
        'code' => [
            'title'   => 'Redis keyspace notification for cache invalidation',
            'lang'    => 'javascript',
            'content' =>
"// Enable keyspace notifications in redis.conf:
// notify-keyspace-events 'Ex'  (expired events)

const subscriber = redis.duplicate(); // separate connection for subscribe
await subscriber.connect();
await subscriber.configSet('notify-keyspace-events', 'Ex');

// Subscribe to expired key events on db 0
await subscriber.subscribe('__keyevent@0__:expired', async (expiredKey) => {
  console.log('Key expired:', expiredKey);

  // Pattern: refresh cache after expiry (pre-warm for high-traffic keys)
  if (expiredKey.startsWith('cache:user:')) {
    const userId = expiredKey.split(':')[2];
    const user   = await userService.getById(userId);
    await redis.set(expiredKey, JSON.stringify(user), { EX: 300 });
    console.log('Pre-warmed cache for user', userId);
  }
});",
        ],
        'tips' => [
            'Enable AOF with everysec fsync for session stores and queues — you lose at most 1 second of data on crash.',
            'Use hash tags ({tag}) in Redis Cluster to force related keys to the same slot for multi-key operations.',
            'Set maxmemory and maxmemory-policy — Redis silently starts evicting without them when memory is full.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Redis involves Redis Modules — extending Redis with custom data structures and commands (RedisJSON, RediSearch, RedisBloom, RedisTimeSeries) — and deep knowledge of the Redis event loop and memory allocator (jemalloc) for diagnosing latency spikes and memory fragmentation. RedisInsight provides the visual profiler and memory analyser for production Redis troubleshooting.</p>',
        'concepts' => [
            'RedisJSON: JSON.SET, JSON.GET, JSON.MGET, JSONPath syntax',
            'RediSearch: FT.CREATE, FT.SEARCH, FT.AGGREGATE for full-text and vector search',
            'RedisBloom: Bloom filter, Cuckoo filter, Count-Min Sketch, Top-K',
            'RedisTimeSeries: TS.ADD, TS.RANGE, TS.MRANGE, compaction rules',
            'Redis internals: single-threaded event loop, I/O threads (Redis 6+), RESP3 protocol',
            'Memory analysis: MEMORY USAGE, MEMORY DOCTOR, RedisInsight memory profiler',
            'Vector similarity search: Redis Stack HNSW index, VSS.SEARCH for AI embeddings',
        ],
        'code' => [
            'title'   => 'RediSearch full-text search index',
            'lang'    => 'bash',
            'content' =>
'# Create a full-text search index on hash keys
FT.CREATE products:idx ON HASH PREFIX 1 "product:"
  SCHEMA
    name       TEXT    WEIGHT 2.0
    description TEXT
    price      NUMERIC SORTABLE
    tags       TAG     SEPARATOR ","
    inStock    TAG

# Add documents (as Redis hashes)
HSET "product:1" name "Wireless Headphones" description "Premium noise-cancelling"
                 price 149.99 tags "audio,wireless" inStock "true"

# Full-text search with filters
FT.SEARCH products:idx "@name:wireless @inStock:{true} @price:[50 200]"
  SORTBY price ASC
  LIMIT 0 10
  RETURN 3 name price tags

# Aggregation: count products by tag
FT.AGGREGATE products:idx "*"
  APPLY "split(@tags, \",\")" AS tag
  GROUPBY 1 @tag REDUCE COUNT 0 AS count
  SORTBY 2 @count DESC',
        ],
        'tips' => [
            'Use Redis Stack (or Redis Enterprise) to get RedisJSON, RediSearch, and RedisBloom in one deployment.',
            'RediSearch vector similarity search enables semantic search for AI applications — index embedding vectors.',
            'Follow the Redis blog (redis.io/blog) and release notes for new module capabilities and performance improvements.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
