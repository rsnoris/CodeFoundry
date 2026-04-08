<?php
$tutorial_title = 'MongoDB';
$tutorial_slug  = 'mongodb';
$quiz_slug      = 'mongodb';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>MongoDB is the world\'s most popular NoSQL database — a document database that stores data as flexible, JSON-like BSON documents rather than rows in tables. Each document can have a different structure, making MongoDB ideal for hierarchical data, rapidly changing schemas, and high-write-throughput workloads. MongoDB powers applications at Uber, eBay, Viacom, and thousands of other companies.</p>',
        'concepts' => [
            'Document model: collections vs. tables; documents vs. rows; BSON vs. JSON',
            'MongoDB Atlas (hosted) vs. self-hosted MongoDB Community/Enterprise',
            'mongosh: connecting, use db, show dbs, show collections',
            'CRUD: insertOne/Many, findOne/find, updateOne/Many, deleteOne/Many',
            'Query operators: $eq, $ne, $gt, $lt, $gte, $lte, $in, $nin, $exists',
            'Projection: { field: 1 } to include, { field: 0 } to exclude',
            'ObjectId: _id generation, timestamp extraction',
        ],
        'code' => [
            'title'   => 'MongoDB basic CRUD operations',
            'lang'    => 'javascript',
            'content' =>
"// Insert
await db.users.insertOne({
  name:      'Alice',
  email:     'alice@example.com',
  scores:    [92, 88, 95],
  profile:   { bio: 'Developer', location: 'NYC' },
  createdAt: new Date(),
});

// Query with operators
const highScorers = await db.users.find({
  'scores':    { \$elemMatch: { \$gte: 90 } },
  'profile.location': 'NYC',
}, {
  projection: { name: 1, email: 1, _id: 0 },
}).sort({ name: 1 }).limit(10).toArray();

// Update
await db.users.updateOne(
  { email: 'alice@example.com' },
  {
    \$set:   { 'profile.bio': 'Senior Developer' },
    \$push:  { scores: 97 },
    \$currentDate: { updatedAt: true },
  }
);",
        ],
        'tips' => [
            'Always include a created_at / updated_at field — MongoDB doesn\'t add timestamps automatically.',
            'Use $set for partial updates — $replaceOne replaces the entire document.',
            'ObjectId contains a 4-byte timestamp — new ObjectId().getTimestamp() gives the creation time.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>MongoDB\'s Aggregation Pipeline is its most powerful query feature — a sequence of stages (match, group, project, sort, lookup, unwind) that transform documents. It replaces SQL\'s SELECT/GROUP BY/JOIN capabilities with a composable, stage-by-stage approach. Understanding the pipeline is essential for any non-trivial MongoDB query.</p>',
        'concepts' => [
            'Aggregation pipeline stages: $match, $group, $project, $sort, $limit, $skip',
            '$group with accumulators: $sum, $avg, $min, $max, $push, $addToSet, $first',
            '$lookup: left outer join between collections',
            '$unwind: deconstruct an array field into separate documents',
            '$facet: multiple aggregations in a single pipeline pass',
            'Array update operators: $push, $pull, $addToSet, $pop, $splice',
            'Logical operators: $and, $or, $not, $nor',
        ],
        'code' => [
            'title'   => 'MongoDB aggregation pipeline',
            'lang'    => 'javascript',
            'content' =>
"const orderStats = await db.orders.aggregate([
  // Stage 1: filter completed orders in the last 30 days
  { \$match: {
    status: 'completed',
    createdAt: { \$gte: new Date(Date.now() - 30 * 24 * 60 * 60 * 1000) }
  }},

  // Stage 2: join with customers collection
  { \$lookup: {
    from:         'customers',
    localField:   'customerId',
    foreignField: '_id',
    as:           'customer',
  }},
  { \$unwind: '\$customer' },

  // Stage 3: group by country
  { \$group: {
    _id:           '\$customer.country',
    orderCount:    { \$sum: 1 },
    totalRevenue:  { \$sum: '\$total' },
    avgOrderValue: { \$avg: '\$total' },
    customers:     { \$addToSet: '\$customerId' },
  }},

  // Stage 4: add derived fields
  { \$addFields: { uniqueCustomers: { \$size: '\$customers' } }},

  // Stage 5: sort and limit
  { \$sort: { totalRevenue: -1 }},
  { \$limit: 10 },
]).toArray();",
        ],
        'tips' => [
            'Always start pipelines with $match to reduce the document set before expensive stages.',
            '$lookup is expensive on large collections — consider embedding related data for read-heavy access patterns.',
            'Use $facet to return multiple aggregation results (totals + buckets) in a single query round-trip.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>MongoDB indexes are the primary performance lever. Single-field, compound, multikey (for arrays), text, geospatial (2dsphere), and partial indexes cover all workload patterns. The MongoDB profiler and explain("executionStats") reveal whether queries use index scans or collection scans.</p><p>Data modelling in MongoDB requires choosing between embedding (for one-to-few, accessed together) and referencing (for one-to-many, accessed independently) — the "right" schema depends on access patterns, not normalisation rules.</p>',
        'concepts' => [
            'Index types: single field, compound, multikey, text, 2dsphere, hashed, partial',
            'Compound index key order: equality → sort → range',
            'explain("executionStats"): COLLSCAN vs. IXSCAN, nReturned vs. nScanned',
            'Data modelling: embed vs. reference tradeoffs, the access-pattern-first approach',
            'Schema validation: $jsonSchema with bsonType, required, properties',
            'Transactions (multi-document): withTransaction(), session-based ACID',
            'Change Streams: watch() for real-time document change events',
        ],
        'code' => [
            'title'   => 'Schema validation and index',
            'lang'    => 'javascript',
            'content' =>
"// Create collection with JSON Schema validation
await db.createCollection('products', {
  validator: {
    \$jsonSchema: {
      bsonType: 'object',
      required: ['name', 'price', 'sku'],
      properties: {
        name:  { bsonType: 'string', minLength: 1, maxLength: 200 },
        price: { bsonType: 'number', minimum: 0 },
        sku:   { bsonType: 'string', pattern: '^SKU-[0-9]{6}$' },
        tags:  { bsonType: 'array', items: { bsonType: 'string' } },
      }
    }
  },
  validationAction: 'error',
});

// Compound index: equality + sort (price for range queries)
await db.products.createIndex(
  { category: 1, price: 1 },
  { name: 'idx_category_price' }
);

// Partial index: only active products need fast lookup by SKU
await db.products.createIndex(
  { sku: 1 },
  { unique: true, partialFilterExpression: { active: { \$eq: true } } }
);",
        ],
        'tips' => [
            'Add schema validation ($jsonSchema) from the start — retrofitting validation on production data is painful.',
            'Use explain().executionStats to confirm index usage before deploying a query to production.',
            'Embed documents for one-to-few relationships accessed together; reference for one-to-many independently accessed.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>MongoDB replica sets provide automatic failover and horizontal read scaling. A replica set consists of a primary (accepts writes) and one or more secondaries (replicate the oplog). Understanding write concerns (w:1, w:"majority") and read preferences (primary, secondary, nearest) lets you trade consistency for performance and latency.</p><p>Sharding horizontally partitions data across multiple MongoDB instances (shards), enabling petabyte-scale databases. The shard key choice — hashed vs. range — determines data distribution and query routing efficiency.</p>',
        'concepts' => [
            'Replica sets: oplog, primary election, write concern (w:1, w:"majority"), read preference',
            'Sharding: mongos, config servers, shard keys, hashed vs. range sharding',
            'Sharding considerations: cardinality, frequency, monotonic growth problems',
            'MongoDB Atlas: automated operations, Atlas Search (Lucene), Atlas Triggers',
            'Atlas Search: $search aggregation stage, compound operator, autocomplete',
            'Time-series collections: expireAfterSeconds, metaField, granularity',
            'Encryption: client-side field-level encryption (CSFLE), encryption-at-rest',
        ],
        'code' => [
            'title'   => 'Atlas Search pipeline',
            'lang'    => 'javascript',
            'content' =>
"// Atlas Search: full-text + fuzzy + facets in one aggregation
const results = await db.products.aggregate([
  {
    \$search: {
      index: 'products_search',
      compound: {
        must: [{
          text: {
            query: 'wireless headphones',
            path:  ['name', 'description'],
            fuzzy: { maxEdits: 1 }
          }
        }],
        filter: [{
          range: {
            path: 'price',
            gte:  50,
            lte:  300,
          }
        }]
      }
    }
  },
  { \$facet: {
    results:    [{ \$limit: 20 }, { \$project: { name: 1, price: 1, score: { \$meta: 'searchScore' } } }],
    totalCount: [{ \$count: 'count' }],
  }}
]).toArray();",
        ],
        'tips' => [
            'Set write concern w:"majority" for data that must not be lost on primary failover.',
            'Choose a shard key with high cardinality and non-monotonic growth — ObjectId and dates make poor shard keys.',
            'Atlas Search is far more powerful than MongoDB text indexes — use it for production full-text search.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert MongoDB engineering involves WiredTiger storage engine internals — cache sizing (wiredTigerCacheSizeGB), checkpoint intervals, and journal flushing — alongside oplog tailing for CDC pipelines, debugging slow operations with the profiler and currentOp, and designing multi-region, globally distributed deployments with Atlas Global Clusters.</p>',
        'concepts' => [
            'WiredTiger: cache sizing, compression (snappy/zstd), checkpoint, journal',
            'Oplog tailing: custom Change Stream consumers for CDC pipelines',
            'db.currentOp(): finding and killing long-running operations',
            'Database profiler: db.setProfilingLevel(1, {slowms: 100})',
            'Connection pooling: maxPoolSize, minPoolSize, waitQueueTimeoutMS',
            'MongoDB Atlas Global Clusters: geographic sharding, zone sharding',
            'Queryable Encryption (MongoDB 6+): in-use encryption with range queries',
        ],
        'code' => [
            'title'   => 'Change Stream consumer for CDC',
            'lang'    => 'javascript',
            'content' =>
"const { MongoClient } = require('mongodb');

async function startCDCPipeline() {
  const client = await MongoClient.connect(process.env.MONGO_URI);
  const db     = client.db('myapp');

  let resumeToken = await loadResumeToken(); // persist and resume after restart

  const stream = db.collection('orders').watch(
    [{ \$match: { operationType: { \$in: ['insert', 'update', 'replace'] } } }],
    { resumeAfter: resumeToken, fullDocument: 'updateLookup' }
  );

  for await (const change of stream) {
    resumeToken = change._id;

    await processChange(change);         // send to data warehouse / cache
    await persistResumeToken(resumeToken); // save after each successful process
  }
}

// Persist the resume token so the pipeline can restart
// from where it left off after a crash or deployment.",
        ],
        'tips' => [
            'Always persist the Change Stream resume token — without it, you may miss events after a restart.',
            'Set wiredTigerCacheSizeGB to ~50% of available RAM for a dedicated MongoDB server.',
            'Follow the MongoDB Engineering Blog and the MongoDB University courses for expert-level content.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
