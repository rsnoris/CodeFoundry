<?php
$tutorial_title = 'Generative AI';
$tutorial_slug  = 'gen-ai';
$quiz_slug      = '';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Generative AI refers to AI systems that can create new content — text, images, audio, video, code, and 3D models — by learning patterns from training data. The generative AI revolution was sparked by the 2017 Transformer architecture and reached mainstream adoption with GPT-3 (2020) and ChatGPT (2022). Today, models from OpenAI (GPT-4o), Anthropic (Claude), Google (Gemini), and Meta (LLaMA) are being integrated into every category of software.</p>',
        'concepts' => [
            'Large Language Models (LLMs): tokens, context window, temperature, top-p sampling',
            'Foundation models vs. fine-tuned models vs. RAG applications',
            'Prompt engineering: system prompts, user messages, few-shot examples',
            'Chat completions API: messages array, roles (system, user, assistant)',
            'Image generation: diffusion models, DALL-E, Stable Diffusion, Midjourney',
            'Multimodal AI: models that process text, images, audio simultaneously',
            'AI safety: hallucinations, bias, misuse, responsible use guidelines',
        ],
        'code' => [
            'title'   => 'OpenAI Chat Completions API',
            'lang'    => 'python',
            'content' =>
"from openai import OpenAI

client = OpenAI()  # uses OPENAI_API_KEY env var

response = client.chat.completions.create(
    model='gpt-4o-mini',
    messages=[
        {
            'role': 'system',
            'content': (
                'You are a senior software engineer who reviews code. '
                'Point out bugs, security issues, and style improvements. '
                'Be concise and actionable.'
            ),
        },
        {
            'role': 'user',
            'content': f'Review this Python function:\\n\\n```python\\n{code_snippet}\\n```',
        },
    ],
    temperature=0.2,  # lower = more deterministic
    max_tokens=500,
)

review = response.choices[0].message.content
print(review)
print(f'Tokens used: {response.usage.total_tokens}')",
        ],
        'tips' => [
            'Set temperature to 0.2–0.4 for factual/deterministic tasks; 0.7–1.0 for creative generation.',
            'Be specific in system prompts — vague instructions produce vague outputs.',
            'Never log raw API responses containing user data to plain-text logs — they may contain sensitive information.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Prompt engineering is the art and science of crafting inputs that reliably elicit high-quality outputs from LLMs. Techniques like chain-of-thought prompting (ask the model to reason step by step), few-shot learning (provide examples), and role prompting (assign a persona) dramatically improve output quality for complex tasks. Structured output (JSON mode, function calling) makes LLM responses machine-readable and reliably parseable.</p>',
        'concepts' => [
            'Chain-of-thought (CoT) prompting: "Let\'s think step by step"',
            'Few-shot prompting: providing input/output examples in the prompt',
            'Role prompting: assigning expert personas to improve domain-specific outputs',
            'Structured output: JSON mode, response_format, Pydantic models',
            'Function calling / tool use: defining JSON schema of callable tools',
            'Prompt templates: parameterised prompts for consistent, reusable instructions',
            'Token counting and context window management',
        ],
        'code' => [
            'title'   => 'Structured output with Pydantic',
            'lang'    => 'python',
            'content' =>
"from openai import OpenAI
from pydantic import BaseModel

client = OpenAI()

class CodeReview(BaseModel):
    summary:        str
    bugs:           list[str]
    security_issues: list[str]
    suggestions:    list[str]
    severity:       str  # 'low' | 'medium' | 'high' | 'critical'

def review_code(code: str) -> CodeReview:
    completion = client.beta.chat.completions.parse(
        model='gpt-4o-mini',
        messages=[
            {'role': 'system', 'content': 'You are an expert code reviewer.'},
            {'role': 'user',   'content': f'Review this code:\\n```\\n{code}\\n```'},
        ],
        response_format=CodeReview,
    )
    return completion.choices[0].message.parsed

review = review_code('def get_user(id): return db.query(f\"SELECT * FROM users WHERE id={id}\")')
print(f'Severity: {review.severity}')
print('Security issues:', review.security_issues)",
        ],
        'tips' => [
            'Use structured output (Pydantic + parse()) for any LLM output your code will process programmatically.',
            'Provide examples of the expected output format in the prompt when JSON mode is not available.',
            'Include "If you are unsure, say so" in factual prompts — it reduces hallucination significantly.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Retrieval-Augmented Generation (RAG) grounds LLM responses in retrieved facts from a knowledge base — reducing hallucinations and enabling up-to-date, source-cited answers. A RAG pipeline converts documents to vector embeddings, stores them in a vector database (Pinecone, Weaviate, pgvector), retrieves the most semantically similar chunks for a query, and provides them as context to the LLM.</p>',
        'concepts' => [
            'Embeddings: dense vector representations of text; cosine similarity',
            'Vector databases: Pinecone, Weaviate, Chroma, pgvector, Qdrant',
            'RAG pipeline: document chunking → embedding → indexing → retrieval → generation',
            'Chunking strategies: fixed-size, sentence, semantic, hierarchical',
            'LangChain and LlamaIndex: RAG orchestration frameworks',
            'Reranking: cross-encoder rerankers for better retrieval precision',
            'Hybrid search: combining vector search with keyword search (BM25)',
        ],
        'code' => [
            'title'   => 'Simple RAG pipeline with LangChain',
            'lang'    => 'python',
            'content' =>
"from langchain_openai      import ChatOpenAI, OpenAIEmbeddings
from langchain_community.vectorstores import Chroma
from langchain.text_splitter          import RecursiveCharacterTextSplitter
from langchain.chains                 import RetrievalQA
from langchain.document_loaders       import PyPDFLoader

# 1. Load and chunk documents
loader   = PyPDFLoader('docs/handbook.pdf')
docs     = loader.load()
splitter = RecursiveCharacterTextSplitter(chunk_size=1000, chunk_overlap=200)
chunks   = splitter.split_documents(docs)

# 2. Embed and store in vector DB
embeddings = OpenAIEmbeddings(model='text-embedding-3-small')
vectorstore = Chroma.from_documents(chunks, embeddings, persist_directory='./chroma_db')

# 3. Build RAG chain
llm   = ChatOpenAI(model='gpt-4o-mini', temperature=0)
chain = RetrievalQA.from_chain_type(
    llm=llm,
    retriever=vectorstore.as_retriever(search_kwargs={'k': 5}),
    return_source_documents=True,
)

# 4. Query
result = chain.invoke({'query': 'What is the refund policy?'})
print(result['result'])
for doc in result['source_documents']:
    print(f'Source: {doc.metadata[\"source\"]}, page {doc.metadata[\"page\"]}')",
        ],
        'tips' => [
            'Use chunk_overlap=10–20% of chunk_size to avoid losing context at chunk boundaries.',
            'Retrieve more chunks (k=10) then rerank — retrieval recall is often the bottleneck, not precision.',
            'Log retrieved chunks alongside LLM answers for debugging hallucinations and improving retrieval.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced generative AI covers LLM agents — systems where an LLM autonomously decides which tools to call, processes the results, and iterates until a goal is achieved. Function calling enables structured tool invocation; ReAct (Reasoning + Acting) and plan-and-execute patterns are common agent architectures. Multi-agent systems coordinate multiple specialised agents for complex, long-horizon tasks.</p><p>Fine-tuning LLMs on domain-specific data — using techniques like LoRA, QLoRA, and RLHF — adapts base models to specialised vocabularies, formats, and behaviours that prompt engineering alone cannot achieve.</p>',
        'concepts' => [
            'Function calling / tool use: defining, calling, and processing tool results',
            'ReAct pattern: interleaving reasoning and acting (Thought → Action → Observation)',
            'LangChain agents: AgentExecutor, tools, memory, callbacks',
            'OpenAI Assistants API: threads, runs, built-in tools (code interpreter, file search)',
            'Fine-tuning: SFT (Supervised Fine-Tuning), LoRA, QLoRA, RLHF overview',
            'Evaluation: LLM-as-judge, RAGAS for RAG evaluation, DeepEval',
            'Context window management: sliding window, summarisation, compression',
        ],
        'code' => [
            'title'   => 'OpenAI function calling agent',
            'lang'    => 'python',
            'content' =>
"import json
from openai import OpenAI

client = OpenAI()

tools = [
    {
        'type': 'function',
        'function': {
            'name': 'get_weather',
            'description': 'Get current weather for a city',
            'parameters': {
                'type': 'object',
                'properties': {
                    'city':  {'type': 'string'},
                    'units': {'type': 'string', 'enum': ['celsius', 'fahrenheit']},
                },
                'required': ['city'],
            },
        },
    },
]

def run_agent(user_message: str) -> str:
    messages = [{'role': 'user', 'content': user_message}]
    while True:
        response = client.chat.completions.create(
            model='gpt-4o-mini', messages=messages, tools=tools
        )
        msg = response.choices[0].message
        if not msg.tool_calls:
            return msg.content
        messages.append(msg)
        for call in msg.tool_calls:
            args   = json.loads(call.function.arguments)
            result = get_weather(**args)  # actual implementation
            messages.append({'role': 'tool', 'tool_call_id': call.id,
                              'content': json.dumps(result)})

print(run_agent('What is the weather in Paris in Celsius?'))",
        ],
        'tips' => [
            'Always validate and sanitise tool call arguments — the LLM may pass unexpected types or values.',
            'Add a step limit to agent loops — runaway agents can incur significant API costs.',
            'Use RAGAS (ragas.io) to systematically evaluate RAG pipeline quality: faithfulness, answer relevancy, context precision.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert generative AI engineering involves building and evaluating complete AI systems — from data curation and model selection through production deployment with observability, cost management, and safety guardrails. Understanding the research frontier — mixture-of-experts (MoE) architectures, speculative decoding for inference speedup, RLHF vs. RLAIF vs. DPO for alignment — enables informed decisions about model selection and deployment strategy.</p>',
        'concepts' => [
            'Inference optimisation: quantisation (GPTQ, AWQ), speculative decoding, KV cache',
            'Mixture of Experts (MoE): sparse activation, routing, Mixtral, GPT-4',
            'Alignment techniques: RLHF, RLAIF, DPO, Constitutional AI',
            'LLM Ops: LangSmith, Helicone, Braintrust for tracing and evaluation',
            'Safety guardrails: NeMo Guardrails, Llama Guard, content filtering',
            'Multi-modal generation: text-to-image (FLUX, SDXL), text-to-video (Sora)',
            'Open-source models: LLaMA 3, Mistral, Qwen, Phi-3, Gemma deployment',
        ],
        'code' => [
            'title'   => 'vLLM for high-throughput LLM serving',
            'lang'    => 'python',
            'content' =>
"# vLLM: high-throughput LLM serving with PagedAttention
# pip install vllm

from vllm import LLM, SamplingParams

# Load model (auto-downloads from HuggingFace)
llm = LLM(
    model='meta-llama/Llama-3.1-8B-Instruct',
    tensor_parallel_size=2,   # spread across 2 GPUs
    max_model_len=8192,
    dtype='bfloat16',
    enable_prefix_caching=True,  # cache system prompt KV
)

params = SamplingParams(temperature=0.7, max_tokens=256, top_p=0.9)

# Batch inference — vLLM handles scheduling and KV cache automatically
prompts = [
    'Explain quantum entanglement in one paragraph.',
    'Write a Python function to sort a list of dicts by key.',
]
outputs = llm.generate(prompts, params)
for output in outputs:
    print(output.outputs[0].text)

# For an OpenAI-compatible server:
# vllm serve meta-llama/Llama-3.1-8B-Instruct --port 8000",
        ],
        'tips' => [
            'vLLM\'s PagedAttention enables continuous batching — it serves 2–24× more throughput than HuggingFace generate.',
            'Use prefix caching for long system prompts that many users share — it reduces TTFT (time to first token).',
            'Follow the LMSys blog, HuggingFace blog, and Andrej Karpathy on X for the latest in LLM engineering.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
