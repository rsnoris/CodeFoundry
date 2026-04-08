<?php
$tutorial_title = 'AI Tool Development';
$tutorial_slug  = 'ai-tools';
$quiz_slug      = '';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>AI Tool Development is the practice of building software products and features powered by AI/LLM capabilities — chatbots, coding assistants, document analyser, intelligent search, and automated workflows. It bridges the gap between raw LLM API calls and production-grade AI-powered applications. This track focuses on the engineering discipline of building reliable, observable, and cost-efficient AI tools rather than training models from scratch.</p>',
        'concepts' => [
            'AI product taxonomy: chatbots, copilots, pipelines, classifiers, extractors',
            'API providers: OpenAI, Anthropic, Google Gemini, Together AI, Groq',
            'SDK selection: official provider SDKs vs. LangChain vs. LlamaIndex vs. raw HTTP',
            'API keys, rate limits, and cost management (tokens, per-call pricing)',
            'Error handling: retries, exponential backoff, fallback models',
            'Streaming responses: Server-Sent Events (SSE), async generators',
            'Environment variables and secret management for AI keys',
        ],
        'code' => [
            'title'   => 'Streaming LLM response with retry',
            'lang'    => 'python',
            'content' =>
"from openai import OpenAI, APIStatusError, APITimeoutError
import time

client = OpenAI()

def stream_with_retry(messages: list, max_retries: int = 3) -> str:
    for attempt in range(max_retries):
        try:
            with client.chat.completions.stream(
                model='gpt-4o-mini',
                messages=messages,
            ) as stream:
                result = ''
                for chunk in stream:
                    delta = chunk.choices[0].delta.content or ''
                    print(delta, end='', flush=True)
                    result += delta
                print()
                return result
        except APIStatusError as e:
            if e.status_code == 429 and attempt < max_retries - 1:
                wait = 2 ** attempt     # exponential backoff
                print(f'Rate limited. Retrying in {wait}s...')
                time.sleep(wait)
            else:
                raise
        except APITimeoutError:
            if attempt < max_retries - 1:
                time.sleep(2 ** attempt)
            else:
                raise
    raise RuntimeError('Max retries exceeded')",
        ],
        'tips' => [
            'Implement exponential backoff for 429 (rate limit) errors — aggressive retries worsen the situation.',
            'Stream responses for any user-facing chat interface — latency perception improves dramatically.',
            'Track token usage per user and per feature from day one — AI costs scale with usage.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Building a practical AI tool requires more than a single API call — you need conversation memory, prompt management, and output validation. Conversation memory enables multi-turn dialogues by including prior messages. Prompt templating keeps prompts version-controlled and configurable. Output validation (Pydantic, Guardrails AI) ensures LLM responses conform to expected schemas before they reach downstream systems.</p>',
        'concepts' => [
            'Conversation memory: in-memory message history, windowed memory, summarisation memory',
            'Prompt management: templates, versioning, A/B testing prompts',
            'Output parsing and validation: Pydantic, Instructor library, Guardrails AI',
            'Token management: counting tokens (tiktoken), truncating history to fit context',
            'Multi-model routing: GPT-4o for complex tasks, GPT-4o-mini for simple tasks',
            'Logging AI interactions: requests, responses, latency, token counts',
            'Cost estimation: tokens × price per 1M tokens',
        ],
        'code' => [
            'title'   => 'Conversation manager with token window',
            'lang'    => 'python',
            'content' =>
"import tiktoken
from openai import OpenAI
from dataclasses import dataclass, field

@dataclass
class ConversationManager:
    model:       str   = 'gpt-4o-mini'
    max_tokens:  int   = 4096   # leave room for response
    system:      str   = 'You are a helpful assistant.'
    messages:    list  = field(default_factory=list)
    _client:     OpenAI = field(default_factory=OpenAI, repr=False)

    def _count_tokens(self, msgs: list) -> int:
        enc = tiktoken.encoding_for_model(self.model)
        return sum(len(enc.encode(m['content'])) + 4 for m in msgs)

    def _trim_history(self) -> list:
        # Always keep system message; trim oldest user/assistant pairs
        system = [{'role': 'system', 'content': self.system}]
        history = list(self.messages)
        while history and self._count_tokens(system + history) > self.max_tokens:
            history = history[2:]  # remove oldest user + assistant pair
        return system + history

    def chat(self, user_input: str) -> str:
        self.messages.append({'role': 'user', 'content': user_input})
        response = self._client.chat.completions.create(
            model=self.model,
            messages=self._trim_history(),
        )
        reply = response.choices[0].message.content
        self.messages.append({'role': 'assistant', 'content': reply})
        return reply",
        ],
        'tips' => [
            'Use tiktoken to count tokens before sending — avoid unexpected 400 "context too long" errors.',
            'Remove the oldest user+assistant pairs together when trimming — orphaned messages confuse the model.',
            'Log every LLM call to a structured store — you will need it for debugging, evaluation, and auditing.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Production AI tools require robust evaluation pipelines. LLM-as-judge frameworks (DeepEval, RAGAS, Braintrust) use a strong model to grade the outputs of weaker models, enabling automated quality assessment at scale. Semantic caching — storing embeddings of past queries and returning cached responses for semantically similar inputs — reduces latency and API cost by 30–60% for FAQ-style use cases.</p>',
        'concepts' => [
            'LLM evaluation: LLM-as-judge, pairwise comparison, reference-free metrics',
            'RAGAS metrics: faithfulness, answer relevancy, context precision, context recall',
            'DeepEval: test cases, assertions, hallucination, bias, toxicity metrics',
            'Semantic caching: GPTCache, Redis + embedding similarity',
            'Prompt injection and jailbreak defence strategies',
            'Rate limiting and user quotas for AI-powered APIs',
            'Observability: LangSmith, Helicone, Braintrust for LLM tracing',
        ],
        'code' => [
            'title'   => 'LLM evaluation with DeepEval',
            'lang'    => 'python',
            'content' =>
"from deepeval import evaluate
from deepeval.metrics     import HallucinationMetric, AnswerRelevancyMetric
from deepeval.test_case   import LLMTestCase

# Define your evaluation cases
test_cases = [
    LLMTestCase(
        input='What is the capital of France?',
        actual_output='Paris is the capital and largest city of France.',
        expected_output='Paris',
        retrieval_context=['France is a country in Western Europe. Its capital is Paris.'],
    ),
    LLMTestCase(
        input='What is 2 + 2?',
        actual_output='The answer is 5.',
        expected_output='4',
    ),
]

# Run evaluation
results = evaluate(
    test_cases=test_cases,
    metrics=[
        HallucinationMetric(threshold=0.5),
        AnswerRelevancyMetric(threshold=0.7),
    ],
)

for r in results.test_results:
    print(f'{r.name}: passed={r.success}')
    for m in r.metrics_metadata:
        print(f'  {m.metric}: {m.score:.2f}')",
        ],
        'tips' => [
            'Run evaluations in CI to catch prompt regressions before they reach production.',
            'A failing hallucination metric is a blocker — never ship a model that fabricates facts.',
            'Use Braintrust or LangSmith to compare prompt variants side-by-side — qualitative review beats metrics alone.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced AI tool development covers building multi-agent orchestration systems where specialised agents (researcher, coder, reviewer, executor) collaborate to complete complex tasks. LangGraph provides a stateful, graph-based framework for building robust multi-step agents. Model Context Protocol (MCP), developed by Anthropic, is an open standard for connecting AI assistants to data sources and tools in a vendor-neutral way.</p>',
        'concepts' => [
            'Multi-agent systems: agent roles, communication patterns, shared memory',
            'LangGraph: StateGraph, nodes, edges, conditional routing, checkpointing',
            'Model Context Protocol (MCP): servers, clients, resources, tools, prompts',
            'Long-running tasks: async task queues (Celery, ARQ), status polling, webhooks',
            'Human-in-the-loop: interrupt points, approval workflows, correction feedback',
            'Persistent memory: user-level, session-level, and entity memory stores',
            'Cost and latency optimisation: batching, caching, model routing, async parallelism',
        ],
        'code' => [
            'title'   => 'LangGraph multi-step agent',
            'lang'    => 'python',
            'content' =>
"from langgraph.graph  import StateGraph, END
from typing           import TypedDict, Annotated
import operator

class AgentState(TypedDict):
    messages:  Annotated[list, operator.add]
    plan:      str
    code:      str
    result:    str
    iteration: int

def plan_node(state: AgentState) -> AgentState:
    '''LLM creates a step-by-step plan'''
    plan = llm.invoke([{'role': 'user', 'content': f'Create a plan for: {state[\"messages\"][-1][\"content\"]}'}])
    return {'plan': plan.content, 'iteration': 0}

def code_node(state: AgentState) -> AgentState:
    '''LLM writes code based on the plan'''
    code = llm.invoke([{'role': 'user', 'content': f'Implement this plan:\\n{state[\"plan\"]}'}])
    return {'code': code.content}

def execute_node(state: AgentState) -> AgentState:
    result = execute_python_safely(state['code'])
    return {'result': result, 'iteration': state['iteration'] + 1}

def should_retry(state: AgentState) -> str:
    if 'Error' in state['result'] and state['iteration'] < 3:
        return 'code'
    return END

graph = StateGraph(AgentState)
graph.add_node('plan',    plan_node)
graph.add_node('code',    code_node)
graph.add_node('execute', execute_node)
graph.add_edge('plan', 'code')
graph.add_edge('code', 'execute')
graph.add_conditional_edges('execute', should_retry, {'code': 'code', END: END})
graph.set_entry_point('plan')
agent = graph.compile()",
        ],
        'tips' => [
            'Use LangGraph checkpointing to resume interrupted long-running agents from their last saved state.',
            'Implement human-in-the-loop interrupts before any irreversible actions — file deletion, API writes, emails.',
            'Follow the MCP documentation (modelcontextprotocol.io) — it is becoming the standard for AI tool integration.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert AI tool development involves architecting enterprise AI platforms — with multi-tenant isolation, SLA guarantees, cost attribution, governance, and compliance (GDPR, HIPAA for AI). Contributing to open-source AI frameworks (LangChain, LlamaIndex, Transformers), building novel AI products that combine multiple modalities and agents, and staying at the research frontier define this tier.</p>',
        'concepts' => [
            'Enterprise AI: multi-tenancy, data isolation, audit logging, PII redaction',
            'AI compliance: GDPR implications for AI training and inference, HIPAA BAA with AI providers',
            'Vector database operations at scale: sharding, replication, ANN tuning',
            'Custom embedding models: fine-tuning sentence-transformers for domain vocabulary',
            'AI product strategy: build vs. buy, model selection, vendor lock-in tradeoffs',
            'Contributing to AI OSS: LangChain, LlamaIndex, Transformers, PEFT',
            'AI safety in production: input/output filtering, PII detection, content moderation',
        ],
        'code' => [
            'title'   => 'PII detection middleware for AI pipeline',
            'lang'    => 'python',
            'content' =>
"from presidio_analyzer   import AnalyzerEngine
from presidio_anonymizer import AnonymizerEngine

analyzer   = AnalyzerEngine()
anonymizer = AnonymizerEngine()

def sanitise_for_llm(text: str) -> tuple[str, list]:
    '''Detect and redact PII before sending to external LLM API'''
    results = analyzer.analyze(
        text=text,
        entities=['PERSON', 'EMAIL_ADDRESS', 'PHONE_NUMBER',
                  'CREDIT_CARD', 'SSN', 'IP_ADDRESS'],
        language='en',
    )
    if not results:
        return text, []

    anonymised = anonymizer.anonymize(
        text=text,
        analyzer_results=results,
    )
    return anonymised.text, results

def llm_call_with_pii_guard(user_input: str, user_id: str) -> str:
    clean_input, pii_entities = sanitise_for_llm(user_input)
    if pii_entities:
        # Log PII detection event for compliance audit
        audit_log.info({'user_id': user_id, 'pii_types': [e.entity_type for e in pii_entities]})

    response = client.chat.completions.create(
        model='gpt-4o-mini',
        messages=[{'role': 'user', 'content': clean_input}],
    )
    return response.choices[0].message.content",
        ],
        'tips' => [
            'Use Microsoft Presidio for PII detection and anonymisation — it is production-grade and supports 50+ entity types.',
            'Log AI audit trails (who asked what, when, what was returned) for compliance and incident response.',
            'Follow the EU AI Act and NIST AI Risk Management Framework to stay current on AI governance requirements.',
            'Read the papers behind the tools you use — understanding the research makes you a better AI engineer.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
