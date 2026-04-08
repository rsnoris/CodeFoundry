<?php
$tutorial_title = 'AWS';
$tutorial_slug  = 'aws';
$quiz_slug      = 'aws';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Amazon Web Services (AWS) is the world\'s largest cloud computing platform, launched in 2006. It offers over 200 services covering compute, storage, networking, databases, AI/ML, analytics, security, and developer tools. AWS serves millions of customers including Netflix, Airbnb, NASA, and the majority of Fortune 500 companies. Understanding AWS is a foundational skill for modern software engineers, DevOps engineers, and cloud architects.</p>',
        'concepts' => [
            'Cloud computing models: IaaS, PaaS, SaaS; on-premises vs. cloud',
            'AWS global infrastructure: Regions, Availability Zones, Edge Locations',
            'IAM: users, groups, roles, policies; the principle of least privilege',
            'AWS Console, AWS CLI (aws configure), AWS SDKs',
            'EC2 (Elastic Compute Cloud): instances, AMIs, instance types, key pairs',
            'S3 (Simple Storage Service): buckets, objects, storage classes, public access',
            'Free Tier: what\'s free for 12 months and what\'s always free',
        ],
        'code' => [
            'title'   => 'AWS CLI basics',
            'lang'    => 'bash',
            'content' =>
'# Configure credentials
aws configure
# Enter: Access Key ID, Secret Key, Region (e.g. us-east-1), output (json)

# S3: create bucket, upload, list, download
aws s3 mb s3://my-unique-bucket-name-2024
aws s3 cp ./dist/ s3://my-unique-bucket-name-2024/ --recursive
aws s3 ls s3://my-unique-bucket-name-2024/
aws s3 sync ./dist/ s3://my-unique-bucket-name-2024/ --delete

# EC2: launch an instance
aws ec2 run-instances \
  --image-id ami-0c55b159cbfafe1f0 \
  --instance-type t3.micro \
  --key-name my-key-pair \
  --security-group-ids sg-903004f8 \
  --subnet-id subnet-6e7f829e

# IAM: create a user and attach policy
aws iam create-user --user-name deploy-bot
aws iam attach-user-policy \
  --user-name deploy-bot \
  --policy-arn arn:aws:iam::aws:policy/AmazonS3FullAccess',
        ],
        'tips' => [
            'Never use root account credentials for day-to-day work — create an IAM user with the minimum needed permissions.',
            'Use aws --profile <name> to manage multiple AWS accounts without overwriting the default configuration.',
            'Enable MFA on your AWS root account and all IAM users — it is the single most important security step.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>AWS\'s core compute services — EC2 for virtual machines, Lambda for serverless functions, and ECS/Fargate for containers — cover most application hosting needs. VPC (Virtual Private Cloud) provides network isolation: public subnets for internet-facing resources, private subnets for databases and backends, security groups as virtual firewalls, and internet/NAT gateways for connectivity.</p>',
        'concepts' => [
            'VPC: subnets (public/private), route tables, internet gateway, NAT gateway',
            'Security groups: stateful firewall rules (inbound/outbound), reference by SG ID',
            'Lambda: function code, triggers (API GW, S3, EventBridge), execution role, layers',
            'API Gateway: REST API, HTTP API, WebSocket API; Lambda proxy integration',
            'RDS: managed relational databases (MySQL, PostgreSQL, Aurora); Multi-AZ',
            'DynamoDB: serverless NoSQL; on-demand vs. provisioned capacity; GSI',
            'CloudWatch: metrics, logs, alarms, dashboards',
        ],
        'code' => [
            'title'   => 'Lambda function with API Gateway',
            'lang'    => 'javascript',
            'content' =>
'// Lambda handler (Node.js 20.x runtime)
export const handler = async (event) => {
  // API Gateway proxy event
  const { httpMethod, path, queryStringParameters, body } = event;

  if (httpMethod === "GET" && path === "/users") {
    const limit = parseInt(queryStringParameters?.limit ?? "20", 10);
    const users = await fetchUsers(limit);
    return {
      statusCode: 200,
      headers: {
        "Content-Type": "application/json",
        "X-Correlation-ID": event.requestContext?.requestId,
      },
      body: JSON.stringify({ users, count: users.length }),
    };
  }

  return { statusCode: 404, body: JSON.stringify({ error: "Not found" }) };
};

// Environment variables from Lambda console or SAM template
const TABLE_NAME = process.env.TABLE_NAME;
const REGION     = process.env.AWS_REGION;',
        ],
        'tips' => [
            'Lambda cold start time scales with package size — use Lambda Layers for large dependencies.',
            'Use Lambda environment variables for configuration — never hardcode ARNs, table names, or secrets.',
            'Security groups are stateful: allowing inbound port 443 automatically allows the outbound response.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Infrastructure as Code (IaC) with AWS CloudFormation or Terraform prevents configuration drift and enables reproducible environments. AWS SAM (Serverless Application Model) extends CloudFormation specifically for serverless workloads — Lambda functions, API Gateway, DynamoDB tables — with a concise YAML syntax and local testing with <code>sam local invoke</code>.</p><p>ECS with Fargate runs containerised workloads without managing EC2 instances. Application Load Balancer (ALB) distributes traffic, and ECR stores container images. Auto Scaling adjusts capacity based on CloudWatch metrics.</p>',
        'concepts' => [
            'CloudFormation: stacks, templates, resources, outputs, parameters, change sets',
            'AWS SAM: template.yaml, sam build/deploy/local, API and Lambda resources',
            'Terraform on AWS: provider, resource, data, module, state backend (S3+DynamoDB)',
            'ECS: clusters, task definitions, services, Fargate launch type',
            'ECR: docker push/pull, image scan on push, lifecycle policies',
            'ALB: target groups, health checks, path-based routing, listener rules',
            'Auto Scaling: target tracking, step scaling, scheduled scaling',
        ],
        'code' => [
            'title'   => 'AWS SAM template for Lambda + API Gateway',
            'lang'    => 'yaml',
            'content' =>
'AWSTemplateFormatVersion: "2010-09-09"
Transform: AWS::Serverless-2016-10-31

Globals:
  Function:
    Runtime:     nodejs20.x
    MemorySize:  256
    Timeout:     10
    Environment:
      Variables:
        TABLE_NAME: !Ref UsersTable

Resources:
  UsersFunction:
    Type: AWS::Serverless::Function
    Properties:
      Handler:  src/handlers/users.handler
      Policies:
        - DynamoDBCrudPolicy:
            TableName: !Ref UsersTable
      Events:
        ListUsers:
          Type:       Api
          Properties: { Path: /users, Method: get }
        GetUser:
          Type:       Api
          Properties: { Path: /users/{id}, Method: get }

  UsersTable:
    Type: AWS::DynamoDB::Table
    Properties:
      BillingMode:  PAY_PER_REQUEST
      AttributeDefinitions:
        - { AttributeName: id, AttributeType: S }
      KeySchema:
        - { AttributeName: id, KeyType: HASH }

Outputs:
  ApiUrl: { Value: !Sub "https://${ServerlessRestApi}.execute-api.${AWS::Region}.amazonaws.com/Prod" }',
        ],
        'tips' => [
            'Use sam local start-api to test your API Gateway + Lambda integration locally before deploying.',
            'Tag all CloudFormation resources with project, env, and team — it makes cost attribution and cleanup easier.',
            'Use Change Sets before applying CloudFormation updates — they show exactly what will be created/modified/deleted.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced AWS covers designing for high availability — multi-AZ and multi-region architectures, Route 53 for DNS failover and latency-based routing, CloudFront as a CDN with Lambda@Edge for edge computing. AWS SQS, SNS, and EventBridge decouple microservices with queuing, pub/sub, and event-routing patterns. Step Functions orchestrate complex serverless workflows with visual state machines.</p>',
        'concepts' => [
            'Route 53: hosted zones, health checks, routing policies (failover, latency, geolocation)',
            'CloudFront: origins, cache behaviours, Lambda@Edge, OAI/OAC for private S3',
            'SQS: standard vs. FIFO queues, DLQ, visibility timeout, message batching',
            'SNS: topics, subscriptions, fan-out pattern, message filtering',
            'EventBridge: event buses, rules, targets, schema registry',
            'Step Functions: state machines, Express vs. Standard, wait for callback',
            'AWS Secrets Manager and Parameter Store for credential management',
        ],
        'code' => [
            'title'   => 'SQS + Lambda event-driven pipeline',
            'lang'    => 'javascript',
            'content' =>
'// Lambda triggered by SQS — processes batches of messages
export const handler = async (event) => {
  const failures = [];

  for (const record of event.Records) {
    const messageId = record.messageId;
    try {
      const body = JSON.parse(record.body);
      await processOrder(body);
    } catch (err) {
      console.error({ messageId, error: err.message });
      // Return failed message IDs to SQS for requeue / DLQ routing
      failures.push({ itemIdentifier: messageId });
    }
  }

  // Partial batch response: only failed messages are re-queued
  return { batchItemFailures: failures };
};

// SAM template snippet:
// OrdersFunction:
//   Events:
//     SqsEvent:
//       Type: SQS
//       Properties:
//         Queue: !GetAtt OrdersQueue.Arn
//         BatchSize: 10
//         FunctionResponseTypes: [ReportBatchItemFailures]',
        ],
        'tips' => [
            'Return batchItemFailures in SQS Lambda handlers — it re-queues only failed messages, not the entire batch.',
            'Use SQS FIFO queues for exactly-once processing; standard queues for higher throughput.',
            'Store Lambda secrets in AWS Secrets Manager and cache them in the Lambda execution context — never hardcode.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert AWS engineering involves architecting for the AWS Well-Architected Framework\'s six pillars — operational excellence, security, reliability, performance efficiency, cost optimisation, and sustainability. AWS Control Tower, Service Control Policies (SCPs), and AWS Organizations manage multi-account strategies that enforce guardrails across hundreds of accounts. Deep knowledge of AWS networking — Transit Gateway, PrivateLink, Direct Connect — and data lake architectures (S3 + Glue + Athena + Redshift) complete the expert AWS practitioner\'s toolkit.</p>',
        'concepts' => [
            'AWS Well-Architected Framework: six pillars and the review process',
            'AWS Organizations: OUs, Service Control Policies (SCPs), consolidated billing',
            'AWS Control Tower: landing zone, guardrails, Account Factory',
            'Transit Gateway: hub-and-spoke VPC connectivity, route tables, attachments',
            'AWS PrivateLink: private service connectivity without internet exposure',
            'AWS Direct Connect: dedicated network connection to AWS',
            'Data lake on AWS: S3 + Glue Catalog + Athena + Redshift Spectrum',
        ],
        'code' => [
            'title'   => 'S3 data lake query with Athena',
            'lang'    => 'sql',
            'content' =>
"-- Query S3 Parquet data lake directly with Athena (Presto/Trino SQL)
-- (Defined in Glue Data Catalog)

-- Create external table pointing at S3
CREATE EXTERNAL TABLE events (
  event_id   STRING,
  user_id    BIGINT,
  event_type STRING,
  properties MAP<STRING, STRING>,
  occurred_at TIMESTAMP
)
PARTITIONED BY (dt STRING)
STORED AS PARQUET
LOCATION 's3://my-data-lake/events/'
TBLPROPERTIES ('parquet.compress' = 'SNAPPY');

-- Add new partition (or use MSCK REPAIR TABLE to auto-discover)
ALTER TABLE events ADD PARTITION (dt='2024-01-15')
LOCATION 's3://my-data-lake/events/dt=2024-01-15/';

-- Query — Athena prunes partitions automatically
SELECT event_type, COUNT(*) AS count
FROM events
WHERE dt BETWEEN '2024-01-01' AND '2024-01-31'
GROUP BY event_type
ORDER BY count DESC;",
        ],
        'tips' => [
            'Store data lake files as Parquet (columnar, compressed) — Athena costs per byte scanned.',
            'Always partition large tables by date in Athena — it reduces scanned data and cost by 90%+.',
            'Use AWS Well-Architected Tool for formal architectural reviews — it generates a prioritised improvement plan.',
            'Follow the AWS Architecture Blog and the AWS re:Invent YouTube channel for new service launches and patterns.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
