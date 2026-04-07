<?php
$tutorial_title = 'Intro to Programming';
$tutorial_slug  = 'intro-programming';
$quiz_slug      = 'intro-programming';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Programming is the process of writing instructions that a computer can understand and execute. At its core, every program is simply a sequence of logical steps that transforms input into output. This tier introduces the essential vocabulary and mindset behind software development.</p><p>You will learn what a computer program is, how source code becomes running software, and explore the landscape of programming paradigms — imperative, declarative, object-oriented, and functional — so you can choose the right approach for any problem.</p>',
        'concepts' => [
            'What is a program? Source code vs. compiled/interpreted execution',
            'Variables and data types (integer, float, string, boolean)',
            'Operators: arithmetic, comparison, logical',
            'Comments and code readability conventions',
            'The difference between compiled languages (C, Go) and interpreted (Python, JS)',
            'REPL environments and running your first "Hello, World!"',
            'Basic input and output (stdin / stdout)',
        ],
        'code' => [
            'title'   => 'Hello World in three languages',
            'lang'    => 'multi',
            'content' =>
"# Python
print('Hello, World!')

// JavaScript
console.log('Hello, World!');

// Java
public class Main {
    public static void main(String[] args) {
        System.out.println(\"Hello, World!\");
    }
}",
        ],
        'tips' => [
            'Type every example by hand rather than copy-pasting — muscle memory accelerates learning.',
            'Read error messages carefully; they tell you exactly what went wrong and on which line.',
            'Use a simple text editor (VS Code, Notepad++) with syntax highlighting from day one.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>With the vocabulary in place, you are ready to write programs that make decisions and repeat actions. Control flow is the backbone of every algorithm: <em>if</em> lets you branch on conditions, and <em>loops</em> let you process collections of data without repeating code.</p><p>You will also meet functions — the fundamental unit of reusable code — and learn how to pass data in and return results out, replacing repetition with abstraction.</p>',
        'concepts' => [
            'Conditionals: if / else if / else and ternary expressions',
            'Comparison and logical operators in conditions',
            'while loops and the risk of infinite loops',
            'for loops: counter-based iteration and for-each style',
            'Break and continue statements',
            'Defining functions / methods and calling them',
            'Parameters vs. arguments; return values',
            'Scope: local vs. global variables',
        ],
        'code' => [
            'title'   => 'FizzBuzz — classic beginner exercise',
            'lang'    => 'python',
            'content' =>
"def fizzbuzz(n):
    for i in range(1, n + 1):
        if i % 15 == 0:
            print('FizzBuzz')
        elif i % 3 == 0:
            print('Fizz')
        elif i % 5 == 0:
            print('Buzz')
        else:
            print(i)

fizzbuzz(20)",
        ],
        'tips' => [
            'Solve every new concept with a small, isolated program before adding complexity.',
            'Trace through your loops on paper before running them — catch off-by-one errors early.',
            'Name functions as verbs (calculate_total, get_user) to make intent obvious.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Real programs manage collections of related data. Arrays (or lists) store ordered sequences, while dictionaries (hash maps) store key-value pairs for fast lookup. Understanding how to choose and traverse the right data structure separates novice from competent programmers.</p><p>This tier also covers error handling — how to anticipate failures, use try/catch blocks, and write programs that degrade gracefully instead of crashing unexpectedly.</p>',
        'concepts' => [
            'Arrays / lists: indexing, slicing, iteration, mutability',
            'Dictionaries / hash maps: keys, values, collision concepts',
            'Stacks and queues as logical abstractions over arrays',
            'Nested data structures and how to traverse them',
            'Exception handling: try / catch / finally blocks',
            'Custom error types and meaningful error messages',
            'Null / None handling and defensive programming',
            'Reading and writing plain-text files',
        ],
        'code' => [
            'title'   => 'Processing a list with error handling',
            'lang'    => 'python',
            'content' =>
"def average(numbers):
    if not numbers:
        raise ValueError('List must not be empty')
    return sum(numbers) / len(numbers)

scores = [88, 92, 75, 'N/A', 91]
cleaned = []

for s in scores:
    try:
        cleaned.append(float(s))
    except (ValueError, TypeError):
        print(f'Skipping invalid score: {s}')

print(f'Average: {average(cleaned):.2f}')",
        ],
        'tips' => [
            'Prefer specific exception types over bare except — it prevents silently hiding bugs.',
            'Think about data structures before writing code: the right structure halves your algorithm complexity.',
            'Use list comprehensions for simple transformations; keep complex logic in regular loops.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Object-Oriented Programming (OOP) organises code around data structures called <em>objects</em> that bundle state (attributes) and behaviour (methods). The four pillars — encapsulation, abstraction, inheritance, and polymorphism — let you model complex domains cleanly and extend systems without rewriting existing code.</p><p>This tier also introduces algorithmic thinking: Big-O notation, recursion, and common sorting and searching algorithms that appear in technical interviews and everyday engineering decisions.</p>',
        'concepts' => [
            'Classes, constructors, and instantiation',
            'Instance vs. class (static) attributes and methods',
            'Encapsulation: public, protected, and private access',
            'Inheritance and method overriding',
            'Polymorphism: duck typing vs. interface-based',
            'Abstract classes and interfaces / protocols',
            'Big-O notation: O(1), O(n), O(n log n), O(n²)',
            'Recursion, base cases, and the call stack',
        ],
        'code' => [
            'title'   => 'Inheritance and polymorphism example',
            'lang'    => 'python',
            'content' =>
"from abc import ABC, abstractmethod

class Shape(ABC):
    @abstractmethod
    def area(self) -> float:
        pass

    def describe(self):
        print(f'{type(self).__name__} area = {self.area():.2f}')

class Circle(Shape):
    def __init__(self, radius):
        self.radius = radius

    def area(self):
        import math
        return math.pi * self.radius ** 2

class Rectangle(Shape):
    def __init__(self, width, height):
        self.width, self.height = width, height

    def area(self):
        return self.width * self.height

shapes = [Circle(5), Rectangle(4, 6)]
for s in shapes:
    s.describe()",
        ],
        'tips' => [
            'Favour composition over inheritance for flexibility — "has-a" is often better than "is-a".',
            'Write unit tests alongside classes to verify behaviour early and catch regressions.',
            'Profile before optimising: measure where time is actually spent before rewriting.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>At the expert level, programming shifts from writing correct code to writing maintainable, scalable, and robust systems. You will study design patterns (creational, structural, behavioural) that encode solutions to recurring engineering problems, and learn the SOLID principles that make large codebases navigable.</p><p>Concurrency, asynchronous programming, and memory management complete this tier — skills needed to build high-performance services and deeply understand how the languages and runtimes you use every day actually work under the hood.</p>',
        'concepts' => [
            'SOLID principles: SRP, OCP, LSP, ISP, DIP',
            'Creational patterns: Singleton, Factory, Builder',
            'Structural patterns: Adapter, Decorator, Proxy',
            'Behavioural patterns: Observer, Strategy, Command',
            'Concurrency vs. parallelism; threads and processes',
            'Async / await and event-loop architecture',
            'Memory management: stack vs. heap, garbage collection, reference counting',
            'Profiling, benchmarking, and performance optimisation strategies',
        ],
        'code' => [
            'title'   => 'Observer pattern implementation',
            'lang'    => 'python',
            'content' =>
"from __future__ import annotations
from typing import Callable

class EventEmitter:
    def __init__(self):
        self._listeners: dict[str, list[Callable]] = {}

    def on(self, event: str, listener: Callable):
        self._listeners.setdefault(event, []).append(listener)

    def emit(self, event: str, *args, **kwargs):
        for fn in self._listeners.get(event, []):
            fn(*args, **kwargs)

bus = EventEmitter()
bus.on('data', lambda x: print(f'Listener 1 received: {x}'))
bus.on('data', lambda x: print(f'Listener 2 received: {x}'))
bus.emit('data', {'user': 'alice', 'score': 99})",
        ],
        'tips' => [
            'Study open-source projects in your language to see patterns applied at scale.',
            'Read "Clean Code" by Robert Martin and "Designing Data-Intensive Applications" by Kleppmann.',
            'Write code for the next developer who reads it — clarity beats cleverness every time.',
            'Revisit the Introduction tier with fresh eyes; expert understanding transforms simple concepts.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
