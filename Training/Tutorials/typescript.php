<?php
$tutorial_title = 'TypeScript';
$tutorial_slug  = 'typescript';
$quiz_slug      = 'typescript';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>TypeScript is a typed superset of JavaScript developed by Microsoft. Every valid JavaScript file is also valid TypeScript — TypeScript simply adds an optional static type system that is erased at compile time, producing clean JavaScript output. The type system catches a large class of bugs before code ever runs.</p><p>This tier covers setting up a TypeScript project, understanding the compilation step, and annotating your first variables, parameters, and return types to begin benefiting from editor intelligence and early error detection.</p>',
        'concepts' => [
            'What TypeScript adds: static types, interfaces, enums, generics, decorators',
            'The TypeScript compiler (tsc): tsconfig.json key options (strict, target, module)',
            'Primitive type annotations: string, number, boolean, null, undefined',
            'Arrays: string[], number[], or the Array<T> generic form',
            'Tuples: fixed-length, heterogeneous arrays with type positions',
            'any vs. unknown vs. never — when to use each',
            'Type inference: when TypeScript deduces types without explicit annotations',
        ],
        'code' => [
            'title'   => 'First TypeScript annotations',
            'lang'    => 'typescript',
            'content' =>
"// Type annotations on variables and function signatures
const greeting: string = 'Hello, TypeScript!';
const year: number     = 2025;
const active: boolean  = true;

// Tuple: exactly [string, number]
const entry: [string, number] = ['Alice', 92];

// Function with typed params and return type
function add(a: number, b: number): number {
  return a + b;
}

// TypeScript infers the return type as number here
const multiply = (a: number, b: number) => a * b;

// unknown is safer than any — must narrow before use
function parse(input: unknown): string {
  if (typeof input === 'string') return input.toUpperCase();
  return String(input);
}",
        ],
        'tips' => [
            'Enable \"strict\": true in tsconfig.json from day one — it catches the most bugs.',
            'Let TypeScript infer return types in most functions; annotate them explicitly for public APIs.',
            'Use unknown instead of any for values whose type you do not yet know — then narrow with typeof/instanceof.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Interfaces and type aliases are TypeScript\'s primary tools for naming and reusing complex types. They describe the shape of objects — which properties exist, their types, and which are optional. Union types (<code>A | B</code>) let a value be one of several types; intersection types (<code>A & B</code>) combine types.</p><p>Enums give meaningful names to numeric or string constants, and literal types narrow a type to a specific set of values (like the strings "GET" | "POST" | "PUT"), making APIs self-documenting and impossible to misuse.</p>',
        'concepts' => [
            'interface declarations and extending interfaces (extends)',
            'type aliases: type Alias = { ... } and their differences from interface',
            'Optional properties (?), readonly modifier, index signatures',
            'Union types (A | B) and type narrowing with typeof/instanceof',
            'Intersection types (A & B) for mixin-style composition',
            'Literal types: "GET" | "POST", 1 | 2 | 3',
            'Enums: numeric enum, string enum, const enum',
            'The as type assertion and the satisfies operator',
        ],
        'code' => [
            'title'   => 'Interfaces and union types',
            'lang'    => 'typescript',
            'content' =>
"interface User {
  readonly id: number;
  name: string;
  email?: string;             // optional
  role: 'admin' | 'editor' | 'viewer';
}

interface AdminUser extends User {
  permissions: string[];
}

type ApiResponse<T> =
  | { status: 'ok';    data: T;      }
  | { status: 'error'; message: string; };

function handleResponse(res: ApiResponse<User[]>): void {
  if (res.status === 'ok') {
    // TypeScript knows res.data is User[] here
    res.data.forEach(u => console.log(u.name));
  } else {
    console.error(res.message);
  }
}",
        ],
        'tips' => [
            'Prefer interface over type for object shapes — it gives better error messages and allows declaration merging.',
            'Use const assertions (as const) to preserve literal types for array and object literals.',
            'The satisfies operator (TS 4.9+) validates a value against a type without widening its inferred type.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Generics are TypeScript\'s mechanism for writing reusable, type-safe components and functions that work with multiple types without sacrificing type information. A generic function is like a template: you parameterise it with a type variable (<code>T</code>) and TypeScript fills it in at each call site.</p><p>This tier also covers utility types — the built-in generic types TypeScript ships with: <code>Partial</code>, <code>Required</code>, <code>Readonly</code>, <code>Pick</code>, <code>Omit</code>, <code>Record</code>, <code>ReturnType</code>, and <code>Exclude</code> — that transform existing types into new ones without manual re-declaration.</p>',
        'concepts' => [
            'Generic functions: <T>(arg: T): T and multiple type parameters',
            'Generic interfaces and classes',
            'Generic constraints: <T extends SomeType>',
            'Default generic parameters: <T = string>',
            'Utility types: Partial<T>, Required<T>, Readonly<T>',
            'Utility types: Pick<T,K>, Omit<T,K>, Record<K,V>',
            'Utility types: ReturnType<F>, Parameters<F>, InstanceType<C>',
            'Utility types: Exclude<T,U>, Extract<T,U>, NonNullable<T>',
        ],
        'code' => [
            'title'   => 'Generic repository pattern',
            'lang'    => 'typescript',
            'content' =>
"interface Entity { id: number; }

interface Repository<T extends Entity> {
  findById(id: number): Promise<T | undefined>;
  findAll(): Promise<T[]>;
  save(entity: Omit<T, 'id'>): Promise<T>;
  delete(id: number): Promise<void>;
}

interface Product extends Entity {
  name: string;
  price: number;
  inStock: boolean;
}

// Partial<Product> — all fields optional for PATCH-style updates
type ProductUpdate = Partial<Omit<Product, 'id'>>;

// ReturnType extracts the resolved type of an async function
type FindResult = Awaited<ReturnType<Repository<Product>['findById']>>;
// ProductUpdate = { name?: string; price?: number; inStock?: boolean }
// FindResult    = Product | undefined",
        ],
        'tips' => [
            'Memorise the 10 most-used utility types — they replace hundreds of manual type declarations.',
            'Use Awaited<T> (TS 4.5+) to unwrap Promise return types without manual Promise<...> peeling.',
            'Constrain generics meaningfully — <T extends object> is more useful than unconstrained <T>.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced TypeScript uses the type system as a full programming language over types. Conditional types (<code>T extends U ? X : Y</code>), mapped types, template literal types, and recursive types enable you to express relationships between types that would require runtime logic in plain JavaScript.</p><p>Module augmentation and declaration merging let you extend third-party library types without forking them. Understanding how TypeScript\'s structural type system (duck typing) differs from nominal type systems (Java, C#) is key to correctly designing APIs and avoiding subtle type-compatibility bugs.</p>',
        'concepts' => [
            'Conditional types: T extends U ? X : Y and infer keyword',
            'Distributive conditional types over union members',
            'Mapped types: { [K in keyof T]: ... } and modifiers (+/- readonly/?)',
            'Template literal types: `${string}Id`, `get${Capitalize<string>}`',
            'Recursive types for JSON, tree structures, and deep utilities',
            'Declaration merging: interfaces, modules, and namespaces',
            'Module augmentation: adding types to third-party libraries',
            'Structural vs. nominal typing and brands / opaque types',
        ],
        'code' => [
            'title'   => 'Deep Readonly with recursive mapped type',
            'lang'    => 'typescript',
            'content' =>
"type DeepReadonly<T> = T extends (infer U)[]
  ? ReadonlyArray<DeepReadonly<U>>
  : T extends object
  ? { readonly [K in keyof T]: DeepReadonly<T[K]> }
  : T;

interface Config {
  server: { host: string; port: number; };
  features: { darkMode: boolean; beta: string[]; };
}

const cfg: DeepReadonly<Config> = {
  server:   { host: 'localhost', port: 8080 },
  features: { darkMode: true, beta: ['search'] },
};

// cfg.server.port = 9090; // Error: Cannot assign to 'port' because it is a read-only property
// cfg.features.beta.push('nav'); // Error: Property 'push' does not exist on type 'readonly string[]'",
        ],
        'tips' => [
            'Use the infer keyword to extract type components inside conditional types — it replaces workarounds.',
            'Template literal types are powerful for event-name validation, CSS-in-JS, and auto-completing string APIs.',
            'Build a type-level "playground" in TypeScript Playground (typescriptlang.org/play) to test complex types.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert TypeScript involves designing large-scale type systems that model domain rules at compile time, eliminating whole categories of runtime errors. Variadic tuple types enable precise typing for functions like zip, pipe, and curry. The type-level computation enabled by TypeScript\'s type system is Turing-complete — though knowing when <em>not</em> to over-engineer types is as important as knowing how.</p><p>Performance of the TypeScript compiler itself becomes a concern in very large codebases. Project references, incremental compilation, and the isolatedModules constraint shape how you structure monorepos and CI pipelines at scale.</p>',
        'concepts' => [
            'Variadic tuple types: [...T, ...U] and inferring rest elements',
            'Typing function composition and pipe operators with variadic generics',
            'Higher-kinded types simulation in TypeScript',
            'Compiler performance: project references, composite builds, isolatedModules',
            'TypeScript compiler API: ts.createProgram, ts.checker, AST traversal',
            'Covariance and contravariance in function parameter and return types',
            'Strict function types (strictFunctionTypes) and their implications',
            'Branded / opaque types for domain primitives (UserId, OrderId)',
        ],
        'code' => [
            'title'   => 'Branded type for type-safe IDs',
            'lang'    => 'typescript',
            'content' =>
"// Opaque / branded types prevent accidentally mixing IDs of different entity types
declare const __brand: unique symbol;
type Brand<T, B> = T & { readonly [__brand]: B };

type UserId    = Brand<number, 'UserId'>;
type ProductId = Brand<number, 'ProductId'>;

function createUserId(id: number): UserId { return id as UserId; }

function getUserById(id: UserId): Promise<User> {
  return fetch(`/api/users/\${id}`).then(r => r.json());
}

const uid = createUserId(42);
const pid = 99 as ProductId;

getUserById(uid); // OK
// getUserById(pid); // Error: Argument of type 'ProductId' is not assignable to 'UserId'
// getUserById(42);  // Error: 42 is not assignable to 'UserId'",
        ],
        'tips' => [
            'Use project references for large monorepos — they enable incremental compilation and correct cross-package types.',
            'Profile the TypeScript compiler with --extendedDiagnostics to find slow type-checking hot spots.',
            'Read the TypeScript Engineering Blog and Matt Pocock\'s TypeScript Tips for cutting-edge techniques.',
            'Contribute to DefinitelyTyped to deepen your understanding of real-world type declaration challenges.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
