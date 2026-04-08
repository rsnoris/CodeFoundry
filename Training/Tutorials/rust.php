<?php
$tutorial_title = 'Rust';
$tutorial_slug  = 'rust';
$quiz_slug      = 'rust';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Rust is a systems programming language focused on safety, speed, and concurrency. Created at Mozilla Research and first stable-released in 2015, Rust achieves memory safety and data-race freedom through its ownership system — without a garbage collector. This makes Rust uniquely suited for systems programming, WebAssembly, game development, and embedded systems where performance and safety both matter. Rust has been the "most loved language" in the Stack Overflow Developer Survey for nine consecutive years.</p>',
        'concepts' => [
            'Rust toolchain: rustup, cargo (build, run, test, fmt, clippy, doc)',
            'Ownership: each value has exactly one owner; owner goes out of scope → value dropped',
            'Borrowing: & (immutable borrow) and &mut (mutable borrow); borrow checker rules',
            'Lifetimes: \'a annotations for the borrow checker on functions and structs',
            'Primitive types: i32/u32/f64/bool/char/usize; arrays, tuples, slices',
            'Structs: struct definition, impl blocks, methods',
            'Pattern matching: match expression, if let, while let',
        ],
        'code' => [
            'title'   => 'Rust ownership and pattern matching',
            'lang'    => 'rust',
            'content' =>
'#[derive(Debug)]
struct Point { x: f64, y: f64 }

impl Point {
    fn new(x: f64, y: f64) -> Self { Self { x, y } }

    fn distance_to(&self, other: &Point) -> f64 {
        ((self.x - other.x).powi(2) + (self.y - other.y).powi(2)).sqrt()
    }
}

fn classify_number(n: i32) -> &\'static str {
    match n {
        i32::MIN..=-1 => "negative",
        0             => "zero",
        1..=9         => "single digit",
        _             => "large",
    }
}

fn main() {
    let p1 = Point::new(0.0, 0.0);
    let p2 = Point::new(3.0, 4.0);
    println!("Distance: {:.2}", p1.distance_to(&p2)); // 5.00

    let nums = vec![-5, 0, 7, 42];
    for &n in &nums {
        println!("{n} is {}", classify_number(n));
    }
}',
        ],
        'tips' => [
            'Run cargo clippy on every commit — it catches dozens of common mistakes the compiler misses.',
            'The borrow checker is your friend: when it rejects code, the code usually has a real bug.',
            'Read "The Rust Programming Language" (the Book) at doc.rust-lang.org — it is exceptional.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Rust\'s <code>Result&lt;T, E&gt;</code> and <code>Option&lt;T&gt;</code> types make error handling and null safety explicit and composable. The <code>?</code> operator propagates errors cleanly, replacing try/catch-style boilerplate. Traits are Rust\'s abstraction mechanism — like interfaces but more powerful — enabling polymorphism without virtual dispatch overhead.</p><p>Collections (Vec<T>, HashMap<K,V>, HashSet<T>) and iterators with their rich method chains (map, filter, fold, collect) are the everyday tools of Rust programming.</p>',
        'concepts' => [
            'Option<T>: Some(value), None; is_some(), unwrap_or(), map(), and_then()',
            'Result<T, E>: Ok(value), Err(e); ? operator, map_err(), unwrap_or_else()',
            'Traits: trait definition, impl Trait for Type, trait objects (dyn Trait)',
            'Generics: fn name<T: Trait>(arg: T) and where clauses',
            'Iterators: Iterator trait, map, filter, fold, collect, chain, zip, enumerate',
            'Vec<T>: push, pop, iter, len, capacity, indexing, slices',
            'HashMap<K,V>: entry API, or_insert_with, contains_key',
        ],
        'code' => [
            'title'   => 'Result and ? operator',
            'lang'    => 'rust',
            'content' =>
'use std::num::ParseIntError;
use std::fmt;

#[derive(Debug)]
enum AppError {
    Parse(ParseIntError),
    OutOfRange(i32),
}

impl fmt::Display for AppError {
    fn fmt(&self, f: &mut fmt::Formatter<'_>) -> fmt::Result {
        match self {
            AppError::Parse(e)      => write!(f, "Parse error: {e}"),
            AppError::OutOfRange(n) => write!(f, "Value {n} out of range [1, 100]"),
        }
    }
}

impl From<ParseIntError> for AppError {
    fn from(e: ParseIntError) -> Self { AppError::Parse(e) }
}

fn parse_score(s: &str) -> Result<i32, AppError> {
    let n: i32 = s.trim().parse()?;  // ? converts ParseIntError via From impl
    if !(1..=100).contains(&n) {
        return Err(AppError::OutOfRange(n));
    }
    Ok(n)
}',
        ],
        'tips' => [
            'Implement the From trait to use ? for ergonomic error conversion between error types.',
            'Prefer collect::<Result<Vec<_>, _>>() to early-exit on the first error in an iterator pipeline.',
            'Use if let Some(val) = option {} instead of match when you only care about the Some case.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Rust\'s smart pointers — <code>Box&lt;T&gt;</code>, <code>Rc&lt;T&gt;</code>, <code>Arc&lt;T&gt;</code>, <code>RefCell&lt;T&gt;</code> — extend the ownership model to handle more complex ownership scenarios: heap allocation, shared ownership, and interior mutability. Understanding when to use each is a rite of passage for intermediate Rust developers.</p><p>Closures, higher-order functions, and iterators in combination produce zero-overhead functional pipelines. Cargo workspaces, features flags, and conditional compilation are the tools for managing large Rust codebases.</p>',
        'concepts' => [
            'Box<T>: heap allocation, recursive data structures',
            'Rc<T> and Arc<T>: reference-counted shared ownership (single-threaded vs. multi)',
            'RefCell<T> and Mutex<T>: interior mutability — runtime-checked borrow rules',
            'Closures: Fn, FnMut, FnOnce traits; capturing by reference vs. by value (move)',
            'Trait objects: Box<dyn Trait> for runtime polymorphism',
            'Cargo features: [features] in Cargo.toml, #[cfg(feature = "feat")]',
            'cargo workspace: shared target directory, inter-crate dependencies',
        ],
        'code' => [
            'title'   => 'Trait objects for a plugin system',
            'lang'    => 'rust',
            'content' =>
'use std::fmt;

trait Formatter: fmt::Debug + Send + Sync {
    fn name(&self) -> &str;
    fn format(&self, data: &str) -> String;
}

#[derive(Debug)]
struct UpperFormatter;
impl Formatter for UpperFormatter {
    fn name(&self) -> &str { "upper" }
    fn format(&self, data: &str) -> String { data.to_uppercase() }
}

#[derive(Debug)]
struct JsonFormatter;
impl Formatter for JsonFormatter {
    fn name(&self) -> &str { "json" }
    fn format(&self, data: &str) -> String { format!("{{\"data\":\"{data}\"}}") }
}

struct Pipeline { steps: Vec<Box<dyn Formatter>> }

impl Pipeline {
    fn add(&mut self, f: impl Formatter + \'static) { self.steps.push(Box::new(f)); }
    fn run(&self, input: &str) -> String {
        self.steps.iter().fold(input.to_owned(), |acc, f| f.format(&acc))
    }
}',
        ],
        'tips' => [
            'Prefer Arc<Mutex<T>> over Rc<RefCell<T>> in async code — async tasks often cross thread boundaries.',
            'Use Box<dyn Trait> for heterogeneous collections; use generics for monomorphised performance.',
            'Add cargo features for optional dependencies — it keeps your crate lean for users who don\'t need them.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Async Rust with <code>async/await</code> and the Tokio runtime enables high-throughput, non-blocking I/O. The Axum web framework (built on Tokio/Hyper) and async database access with sqlx make Rust competitive with Go and Node.js for API servers. Tokio tasks, channels (<code>mpsc</code>, <code>broadcast</code>, <code>oneshot</code>), and the select! macro are the concurrency primitives.</p><p>Procedural macros — derive macros, attribute macros, and function-like macros — extend Rust\'s syntax and power the derive(Debug, Serialize, Deserialize) system underlying the ecosystem.</p>',
        'concepts' => [
            'async/await: async fn, Future trait, Pin<T>, poll()',
            'Tokio: tokio::main, spawn, JoinHandle, select!, time::sleep',
            'Tokio channels: mpsc, broadcast, oneshot, watch',
            'Axum: Router, handler functions, extractors (Path, Query, Json, State)',
            'sqlx: query!, query_as!, Pool, transactions, migrations',
            'Procedural macros: proc-macro crate, TokenStream, syn, quote',
            'Serde: #[derive(Serialize, Deserialize)], custom (de)serialisers',
        ],
        'code' => [
            'title'   => 'Axum REST API handler',
            'lang'    => 'rust',
            'content' =>
'use axum::{extract::{Path, State}, Json, Router};
use axum::http::StatusCode;
use sqlx::PgPool;
use serde::{Deserialize, Serialize};

#[derive(Debug, Serialize, sqlx::FromRow)]
struct User { id: i32, name: String, email: String }

#[derive(Debug, Deserialize)]
struct CreateUser { name: String, email: String }

async fn list_users(State(pool): State<PgPool>) -> Json<Vec<User>> {
    let users = sqlx::query_as!(User, "SELECT id, name, email FROM users ORDER BY id")
        .fetch_all(&pool).await.unwrap_or_default();
    Json(users)
}

async fn get_user(
    State(pool): State<PgPool>,
    Path(id): Path<i32>,
) -> Result<Json<User>, StatusCode> {
    sqlx::query_as!(User, "SELECT id, name, email FROM users WHERE id = $1", id)
        .fetch_optional(&pool).await
        .map_err(|_| StatusCode::INTERNAL_SERVER_ERROR)?
        .map(Json)
        .ok_or(StatusCode::NOT_FOUND)
}

pub fn router(pool: PgPool) -> Router {
    Router::new()
        .route("/users",     axum::routing::get(list_users))
        .route("/users/:id", axum::routing::get(get_user))
        .with_state(pool)
}',
        ],
        'tips' => [
            'Use sqlx compile-time query verification (DATABASE_URL env var) — it catches SQL type errors at build time.',
            'Clone Arc<State> for Axum\'s State extractor — it is cheap and the idiomatic Axum pattern.',
            'Axum extractors run in order; put cheap extractors (Path, Query) before expensive ones (Json body).',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Rust involves unsafe code — the 5 superpowers that bypass the borrow checker (raw pointers, calling unsafe functions, implementing unsafe traits, accessing mutable statics, accessing union fields) — and knowing exactly when and how to use them safely. The unsafe keyword is a contract: you promise the compiler the code is correct; the compiler trusts you and skips its checks.</p><p>Contributing to the Rust language (RFCs at github.com/rust-lang/rfcs), the standard library, or popular ecosystem crates (Tokio, Serde, axum), and deep knowledge of LLVM IR that Rust generates for performance-critical code mark the expert Rust practitioner.</p>',
        'concepts' => [
            'unsafe Rust: raw pointers, unsafe functions and traits, union, global mutable state',
            'Unsafe abstractions: building safe APIs on top of unsafe code',
            'FFI: extern "C", #[no_mangle], cbindgen for C headers',
            'Embedded Rust: #![no_std], #![no_main], HAL crates, RTIC framework',
            'WebAssembly: wasm-pack, wasm-bindgen, web-sys, js-sys',
            'Rust performance: SIMD with std::simd (nightly), profile-guided optimisation',
            'Rust RFC process: github.com/rust-lang/rfcs, T-lang and T-libs teams',
        ],
        'code' => [
            'title'   => 'Safe wrapper around unsafe raw pointer',
            'lang'    => 'rust',
            'content' =>
'use std::ptr::NonNull;
use std::marker::PhantomData;

/// A non-owning, non-null view into a typed value.
/// SAFETY invariant: ptr must be valid and aligned for the lifetime 'a.
pub struct Ref<\'a, T> {
    ptr:     NonNull<T>,
    _marker: PhantomData<&\'a T>,
}

impl<\'a, T> Ref<\'a, T> {
    /// SAFETY: caller must ensure `ptr` is valid, non-null, and
    /// properly aligned for the entire lifetime `\'a`.
    pub unsafe fn from_raw(ptr: *const T) -> Option<Self> {
        Some(Self {
            ptr:     NonNull::new(ptr as *mut T)?,
            _marker: PhantomData,
        })
    }

    pub fn get(&self) -> &T {
        // SAFETY: invariant guarantees ptr is valid for \'a
        unsafe { self.ptr.as_ref() }
    }
}

// Mark as Sync if T: Sync — safe because we only provide shared access
unsafe impl<T: Sync> Sync for Ref<\'_, T> {}',
        ],
        'tips' => [
            'Document every unsafe block with a // SAFETY: comment explaining the invariant you are upholding.',
            'Wrap unsafe internals in safe public APIs — the goal is to reduce the unsafety surface, not eliminate unsafe.',
            'Follow the Rust blog (blog.rust-lang.org) and This Week in Rust for community news and RFC updates.',
            'Read the Rustonomicon (doc.rust-lang.org/nomicon) for the authoritative guide to unsafe Rust.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
