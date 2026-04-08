<?php
$tutorial_title = 'Python';
$tutorial_slug  = 'python';
$quiz_slug      = 'python';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Python is a high-level, interpreted, dynamically typed programming language known for its readable syntax and massive ecosystem. Created by Guido van Rossum and first released in 1991, Python has become the dominant language for data science, machine learning, automation, and backend web development. Its philosophy — "Pythonic" code that is readable and explicit — is captured in PEP 20, "The Zen of Python".</p><p>This tier covers Python syntax, basic data types, control flow, and running your first programs.</p>',
        'concepts' => [
            'Python installation: python3, pip, virtual environments (venv)',
            'Variables and dynamic typing: int, float, str, bool, None',
            'String formatting: f-strings, .format(), %',
            'Control flow: if/elif/else, for loop, while loop, break/continue',
            'Functions: def, default parameters, *args, **kwargs',
            'Lists, tuples, sets, and dictionaries: creation, indexing, slicing',
            'List comprehensions: [expr for item in iterable if condition]',
        ],
        'code' => [
            'title'   => 'Python list comprehensions and functions',
            'lang'    => 'python',
            'content' =>
"def fizzbuzz(n: int) -> list[str]:
    return [
        'FizzBuzz' if i % 15 == 0
        else 'Fizz'  if i % 3  == 0
        else 'Buzz'  if i % 5  == 0
        else str(i)
        for i in range(1, n + 1)
    ]

# Dictionary comprehension
word_lengths = {word: len(word) for word in ['Python', 'is', 'awesome']}

# Nested comprehension: flatten a 2D list
matrix    = [[1, 2], [3, 4], [5, 6]]
flattened = [num for row in matrix for num in row]

print(fizzbuzz(15))
print(word_lengths)   # {'Python': 6, 'is': 2, 'awesome': 7}
print(flattened)      # [1, 2, 3, 4, 5, 6]",
        ],
        'tips' => [
            'Use f-strings (f"Hello, {name}") for string formatting — they are faster and more readable than .format().',
            'Create a virtual environment (python -m venv .venv) for every project to isolate dependencies.',
            'Read PEP 8 — Python\'s style guide — and use a linter (ruff or flake8) to enforce it automatically.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Python\'s object-oriented programming model — classes, inheritance, dunder methods, and properties — enables clear, reusable domain models. Exception handling with try/except/finally and context managers (the with statement) make resource management safe and idiomatic.</p><p>File I/O, the <code>pathlib</code> module for path manipulation, and the <code>json</code> module for serialisation are everyday tools in any Python developer\'s toolkit.</p>',
        'concepts' => [
            'Classes: __init__, self, instance vs. class attributes, @classmethod, @staticmethod',
            'Dunder methods: __str__, __repr__, __len__, __eq__, __lt__ for rich comparisons',
            '@property for computed attributes with getters and setters',
            'Inheritance and super(): extending and overriding parent classes',
            'Exception handling: try/except/else/finally, custom exception classes',
            'Context managers: with statement, __enter__/__exit__, contextlib.contextmanager',
            'File I/O: open(), pathlib.Path, read_text(), write_text()',
        ],
        'code' => [
            'title'   => 'Python class with dunder methods',
            'lang'    => 'python',
            'content' =>
"from dataclasses import dataclass, field
from datetime import datetime

@dataclass
class Post:
    title:      str
    body:       str
    author:     str
    created_at: datetime = field(default_factory=datetime.utcnow)
    published:  bool = False

    def __post_init__(self):
        if not self.title.strip():
            raise ValueError('Post title cannot be empty')

    def publish(self) -> None:
        self.published  = True
        self.created_at = datetime.utcnow()

    def __repr__(self) -> str:
        status = 'published' if self.published else 'draft'
        return f'Post({self.title!r}, {status})'

    def __len__(self) -> int:
        return len(self.body)",
        ],
        'tips' => [
            'Use @dataclass for simple data-holding classes — it generates __init__, __repr__, and __eq__ for free.',
            'Raise specific exception types (ValueError, TypeError) rather than bare Exception for better caller handling.',
            'Use pathlib.Path instead of os.path — it is object-oriented, readable, and cross-platform.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Python\'s functional programming tools — generators, itertools, functools, and first-class functions — enable efficient data pipeline patterns without loading everything into memory. Decorators transform functions and classes, implementing cross-cutting concerns like caching, timing, and access control with zero repetition.</p><p>Type hints (introduced in Python 3.5) and the <code>typing</code> module make Python codebases dramatically more maintainable and enable IDE support and static analysis with mypy or pyright.</p>',
        'concepts' => [
            'Generators: yield, yield from, generator expressions, lazy evaluation',
            'itertools: chain, islice, groupby, product, combinations, accumulate',
            'functools: lru_cache, partial, reduce, wraps for decorators',
            'Decorators: @wraps, parameterised decorators, class-based decorators',
            'Type hints: int|str, Optional[X], list[X], dict[K,V], TypeVar, Generic[T]',
            'Protocol classes for structural subtyping (duck typing with type safety)',
            'dataclasses vs. attrs vs. Pydantic for data modelling',
        ],
        'code' => [
            'title'   => 'Decorator with type hints',
            'lang'    => 'python',
            'content' =>
"import time
import functools
from typing import Callable, TypeVar, Any

F = TypeVar('F', bound=Callable[..., Any])

def retry(max_attempts: int = 3, delay: float = 1.0, exceptions: tuple = (Exception,)):
    def decorator(func: F) -> F:
        @functools.wraps(func)
        def wrapper(*args: Any, **kwargs: Any) -> Any:
            last_exc: Exception | None = None
            for attempt in range(1, max_attempts + 1):
                try:
                    return func(*args, **kwargs)
                except exceptions as e:
                    last_exc = e
                    print(f'Attempt {attempt}/{max_attempts} failed: {e}')
                    if attempt < max_attempts:
                        time.sleep(delay)
            raise last_exc  # type: ignore
        return wrapper  # type: ignore
    return decorator

@retry(max_attempts=3, delay=0.5, exceptions=(ConnectionError, TimeoutError))
def fetch_data(url: str) -> dict:
    import urllib.request, json
    with urllib.request.urlopen(url) as r:
        return json.loads(r.read())",
        ],
        'tips' => [
            'Use @functools.wraps(func) in every decorator to preserve the wrapped function\'s metadata.',
            'Enable mypy --strict or pyright in your CI pipeline — type errors caught statically are cheaper to fix.',
            'Prefer generators over list comprehensions when processing large datasets — they use O(1) memory.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Async Python with <code>asyncio</code>, <code>async def</code>, and <code>await</code> enables concurrent I/O-bound tasks — HTTP requests, database queries, file operations — without threads. FastAPI and aiohttp are built on asyncio and provide high-performance web frameworks that are increasingly popular alternatives to Django for API-first applications.</p><p>Concurrency beyond async: <code>threading</code> for I/O-bound parallel work, <code>multiprocessing</code> for CPU-bound parallelism that bypasses the GIL, and <code>concurrent.futures</code> as a high-level abstraction over both.</p>',
        'concepts' => [
            'asyncio: event loop, coroutines, tasks, asyncio.gather(), asyncio.create_task()',
            'async context managers and async generators',
            'FastAPI: path operations, Pydantic models, dependency injection, OpenAPI auto-generation',
            'aiohttp: async HTTP client and server',
            'threading.Thread for I/O-bound parallel work',
            'multiprocessing.Pool for CPU-bound parallel work (bypasses GIL)',
            'concurrent.futures: ThreadPoolExecutor, ProcessPoolExecutor',
        ],
        'code' => [
            'title'   => 'FastAPI with Pydantic and async',
            'lang'    => 'python',
            'content' =>
"from fastapi import FastAPI, HTTPException, Depends
from pydantic import BaseModel, EmailStr
from sqlalchemy.ext.asyncio import AsyncSession
from typing import Sequence

app = FastAPI(title='CodeFoundry API')

class UserCreate(BaseModel):
    name:  str
    email: EmailStr

class UserOut(BaseModel):
    id:    int
    name:  str
    email: str

    model_config = {'from_attributes': True}

@app.get('/users', response_model=list[UserOut])
async def list_users(db: AsyncSession = Depends(get_db)) -> Sequence:
    result = await db.execute(select(User))
    return result.scalars().all()

@app.post('/users', response_model=UserOut, status_code=201)
async def create_user(body: UserCreate, db: AsyncSession = Depends(get_db)):
    user = User(**body.model_dump())
    db.add(user)
    await db.commit()
    await db.refresh(user)
    return user",
        ],
        'tips' => [
            'Use asyncio.gather() to run independent async tasks concurrently — do not await them sequentially.',
            'FastAPI\'s dependency injection system is excellent for database sessions, auth, and configuration.',
            'Multiprocessing is required for CPU-bound work — Python\'s GIL prevents true thread parallelism.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Python involves deep knowledge of the interpreter internals — bytecode compilation, the GIL, reference counting, the cycle garbage collector — alongside performance optimisation techniques: profiling with cProfile and py-spy, Cython for C extensions, NumPy vectorisation for numerical work, and Mypyc for type-annotated Python to compiled code.</p><p>Packaging and distribution (pyproject.toml, hatch, build, twine, trusted publishing to PyPI), writing C extensions with cffi or ctypes, and contributing to CPython or popular libraries like NumPy, Pandas, and requests define the expert Pythonista.</p>',
        'concepts' => [
            'CPython bytecode: dis module, .pyc files, peephole optimiser',
            'The GIL: what it protects, when it is released (I/O, C extensions)',
            'Memory management: reference counting, cycle detector, __del__',
            'Profiling: cProfile, snakeviz, py-spy for sampling profiler (production-safe)',
            'Cython and Mypyc for compiling Python to C extensions',
            'Slots: __slots__ to reduce memory footprint of many instances',
            'pyproject.toml, hatchling, flit, and modern Python packaging',
            'Contributing to CPython: the devguide, buildbot, core developer workflow',
        ],
        'code' => [
            'title'   => '__slots__ for memory-efficient classes',
            'lang'    => 'python',
            'content' =>
"import sys

class PostWithDict:
    def __init__(self, title: str, views: int):
        self.title = title
        self.views = views

class PostWithSlots:
    __slots__ = ('title', 'views')
    def __init__(self, title: str, views: int):
        self.title = title
        self.views = views

# Memory comparison
without_slots = PostWithDict('Hello', 0)
with_slots    = PostWithSlots('Hello', 0)

print(sys.getsizeof(without_slots))  # ~48 bytes + dict overhead (~232 bytes)
print(sys.getsizeof(with_slots))     # ~56 bytes (no dict)

# Impact is significant at scale:
# 1_000_000 instances × 200 bytes saved = ~200 MB",
        ],
        'tips' => [
            'Profile before optimising — py-spy attach <pid> gives a live flame graph of a running process.',
            'Use __slots__ on classes with millions of instances (event data, ORM rows in memory).',
            'Follow the CPython devguide (devguide.python.org) to understand the contribution process.',
            'Read "Fluent Python" by Luciano Ramalho for the definitive expert-level Python reference.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
