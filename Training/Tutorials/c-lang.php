<?php
$tutorial_title = 'C';
$tutorial_slug  = 'c-lang';
$quiz_slug      = 'c';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>C is one of the oldest and most influential programming languages, created by Dennis Ritchie at Bell Labs between 1969 and 1973. It is a compiled, statically typed, procedural language that provides direct hardware access through pointer manipulation. C underpins operating systems (Linux, Windows NT, macOS), embedded firmware, databases (SQLite, PostgreSQL), and virtually every other language runtime. Understanding C gives you a deep intuition for how computers work that no higher-level language can fully provide.</p>',
        'concepts' => [
            'Compilation: gcc/clang, preprocessor → compiler → assembler → linker',
            'Data types: int, char, float, double, long, size_t, stdint.h types (int32_t…)',
            'Variables, constants, and #define macros',
            'Control flow: if/else, switch, for, while, do-while, goto (sparingly)',
            'Functions: prototypes, pass by value, return values',
            'Standard I/O: printf, scanf, fgets, puts',
            '#include, header files, and the C standard library (stdlib.h, string.h, math.h)',
        ],
        'code' => [
            'title'   => 'C basics — structs and functions',
            'lang'    => 'c',
            'content' =>
'#include <stdio.h>
#include <string.h>
#include <stdlib.h>

typedef struct {
    char   name[64];
    int    age;
    double score;
} Student;

static void print_student(const Student *s) {
    printf("Name: %-20s Age: %3d  Score: %.1f\n", s->name, s->age, s->score);
}

int main(void) {
    Student students[] = {
        {"Alice",   22, 91.5},
        {"Bob",     19, 78.0},
        {"Carol",   21, 95.3},
    };
    size_t count = sizeof(students) / sizeof(students[0]);

    for (size_t i = 0; i < count; i++) {
        print_student(&students[i]);
    }
    return 0;
}',
        ],
        'tips' => [
            'Always compile with -Wall -Wextra -Werror — C is silent about many mistakes that are actually bugs.',
            'Use const on pointer parameters whenever the function does not modify the pointed-to data.',
            'Prefer fixed-width types (int32_t, uint64_t from <stdint.h>) in protocol/serialisation code.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Pointers are C\'s most distinctive and powerful feature. They store memory addresses, enabling efficient data structures, dynamic memory allocation, and direct hardware manipulation. Understanding the pointer-array relationship, pointer arithmetic, and the difference between stack and heap allocation is the central challenge for every C beginner.</p><p>Dynamic memory management — malloc, calloc, realloc, and free — gives C programs control over memory lifetime, at the cost of requiring explicit deallocation to avoid leaks and dangling pointer bugs.</p>',
        'concepts' => [
            'Pointers: int *ptr = &var; dereferencing (*ptr), pointer arithmetic',
            'Arrays and pointer equivalence: arr == &arr[0]',
            'Strings as char arrays: null terminator, strcpy, strncpy, strlen, strcmp',
            'Stack vs. heap: automatic variables vs. malloc()/free()',
            'malloc, calloc, realloc, free — and detecting leaks with Valgrind',
            'Pointer to pointer (**pp): dynamic 2D arrays, argv pattern',
            'const pointers: const char *, char * const, const char * const',
        ],
        'code' => [
            'title'   => 'Dynamic array with malloc/realloc',
            'lang'    => 'c',
            'content' =>
'#include <stdio.h>
#include <stdlib.h>

typedef struct {
    int    *data;
    size_t  len;
    size_t  cap;
} IntVec;

IntVec vec_new(size_t initial_cap) {
    return (IntVec){
        .data = malloc(initial_cap * sizeof(int)),
        .len  = 0,
        .cap  = initial_cap,
    };
}

int vec_push(IntVec *v, int val) {
    if (v->len == v->cap) {
        size_t new_cap = v->cap * 2;
        int *tmp = realloc(v->data, new_cap * sizeof(int));
        if (!tmp) return -1;  // allocation failure
        v->data = tmp;
        v->cap  = new_cap;
    }
    v->data[v->len++] = val;
    return 0;
}

void vec_free(IntVec *v) {
    free(v->data);
    v->data = NULL;
    v->len  = v->cap = 0;
}',
        ],
        'tips' => [
            'Always check malloc/calloc/realloc return values — they return NULL on allocation failure.',
            'Run your program under Valgrind (valgrind --leak-check=full ./prog) to detect memory leaks and invalid reads.',
            'Set freed pointers to NULL immediately to catch use-after-free bugs with a segfault rather than silent corruption.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>C\'s type system is extended by function pointers, enabling callbacks, plugin architectures, and rudimentary object-oriented patterns. Bitwise operations and bit fields are essential for embedded systems, networking protocols, and performance-critical code. The C preprocessor — macros, conditional compilation, and include guards — is a powerful but dangerous tool that requires discipline.</p><p>POSIX system calls (file descriptors, open/read/write/close, fork, exec) expose the operating system interface that everything else is built on top of.</p>',
        'concepts' => [
            'Function pointers: int (*fn)(int, int) and typedef fn_t for callbacks',
            'Bitwise operators: &, |, ^, ~, <<, >> and their use in flags and masks',
            'Bit fields in structs: unsigned int flag:1',
            'Preprocessor: #define with parameters, #ifdef / #ifndef, include guards, _Generic',
            'POSIX file I/O: open(), read(), write(), close(), file descriptors',
            'POSIX process model: fork(), exec(), wait(), exit()',
            'Signal handling: signal(), sigaction()',
        ],
        'code' => [
            'title'   => 'Function pointer callback pattern',
            'lang'    => 'c',
            'content' =>
'#include <stdio.h>
#include <stdlib.h>

typedef int (*comparator_t)(const void *, const void *);

static int cmp_int_asc(const void *a, const void *b) {
    return (*(const int *)a) - (*(const int *)b);
}

static int cmp_int_desc(const void *a, const void *b) {
    return (*(const int *)b) - (*(const int *)a);
}

void sort_and_print(int *arr, size_t n, comparator_t cmp) {
    qsort(arr, n, sizeof(int), cmp);
    for (size_t i = 0; i < n; i++) printf("%d ", arr[i]);
    putchar(\'\n\');
}

int main(void) {
    int nums[] = {5, 3, 8, 1, 9, 2};
    size_t n   = sizeof(nums) / sizeof(nums[0]);

    sort_and_print(nums, n, cmp_int_asc);   // 1 2 3 5 8 9
    sort_and_print(nums, n, cmp_int_desc);  // 9 8 5 3 2 1
    return 0;
}',
        ],
        'tips' => [
            'Use typedef for function pointer types — the raw syntax int (*fn)(int) is easy to misread.',
            'Never call malloc inside a signal handler — signal handlers must only call async-signal-safe functions.',
            'Use #pragma once or include guards (#ifndef FILE_H / #define FILE_H / #endif) to prevent double inclusion.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced C covers the C memory model and undefined behaviour — the most dangerous aspect of C programming. Undefined behaviour (UB) is not just a runtime crash; compilers actively exploit UB for optimisation, turning subtle bugs into security vulnerabilities. Tools like AddressSanitizer, UndefinedBehaviorSanitizer, and MemorySanitizer detect UB at runtime during testing.</p><p>POSIX threads (pthreads), mutexes, condition variables, and lock-free programming with C11 atomics enable concurrent C programs. Network programming with POSIX sockets provides the foundation for writing TCP/UDP servers and clients.</p>',
        'concepts' => [
            'C memory model: alignment, strict aliasing, sequence points',
            'Undefined behaviour and the compiler\'s licence to exploit it',
            'Sanitizers: -fsanitize=address,undefined (ASan + UBSan)',
            'pthreads: pthread_create, pthread_join, pthread_mutex_t, pthread_cond_t',
            'C11 atomics: _Atomic, atomic_load, atomic_store, atomic_compare_exchange',
            'POSIX sockets: socket(), bind(), listen(), accept(), connect(), send(), recv()',
            'select() / poll() / epoll() for I/O multiplexing',
        ],
        'code' => [
            'title'   => 'POSIX thread pool pattern',
            'lang'    => 'c',
            'content' =>
'#include <pthread.h>
#include <stdatomic.h>

typedef struct { void (*fn)(void *); void *arg; } Task;

typedef struct {
    Task           *queue;
    size_t          head, tail, cap;
    pthread_mutex_t lock;
    pthread_cond_t  not_empty;
    pthread_cond_t  not_full;
    atomic_bool     stop;
} ThreadPool;

void *worker(void *arg) {
    ThreadPool *pool = arg;
    while (!atomic_load(&pool->stop)) {
        pthread_mutex_lock(&pool->lock);
        while (pool->head == pool->tail && !atomic_load(&pool->stop))
            pthread_cond_wait(&pool->not_empty, &pool->lock);
        if (atomic_load(&pool->stop)) { pthread_mutex_unlock(&pool->lock); break; }
        Task t = pool->queue[pool->head++ % pool->cap];
        pthread_cond_signal(&pool->not_full);
        pthread_mutex_unlock(&pool->lock);
        t.fn(t.arg);  // execute task outside lock
    }
    return NULL;
}',
        ],
        'tips' => [
            'Compile with -fsanitize=address,undefined in CI — ASan catches heap overflows and use-after-free.',
            'Strict aliasing violations are the most common source of "ghost" UB bugs — never cast int* to float*.',
            'Always hold a mutex for the minimum time necessary — release before any expensive or blocking operation.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert C engineers write optimised code that reasons about CPU cache behaviour, SIMD intrinsics, branch prediction, and memory layout for maximum throughput. Reading compiler output (objdump, godbolt.org) and writing benchmarks with criterion confirms that optimisations actually work. Link-time optimisation (LTO) and profile-guided optimisation (PGO) squeeze out the last percentages of performance.</p><p>Writing C that is safe, portable across compilers (GCC, Clang, MSVC) and architectures (x86_64, ARM64, RISC-V), and contributing to projects like the Linux kernel, SQLite, or CPython represents the pinnacle of C expertise.</p>',
        'concepts' => [
            'CPU cache hierarchy: L1/L2/L3, cache line size (64 bytes), false sharing',
            'SIMD intrinsics: SSE2, AVX2, NEON for vectorised data processing',
            'Branch prediction: __builtin_expect, branchless code patterns',
            'Link-time optimisation (LTO): -flto flag and whole-program analysis',
            'Profile-guided optimisation (PGO): -fprofile-generate / -fprofile-use',
            'Compiler extensions: __attribute__((packed)), __builtin_clz, restrict keyword',
            'C23 features: nullptr, bool/true/false keywords, #embed, type inference with auto',
        ],
        'code' => [
            'title'   => 'Cache-friendly struct layout',
            'lang'    => 'c',
            'content' =>
'// Bad: fields interleaved, bool causes 7-byte padding on 64-bit ABI
struct BadLayout {
    bool    active;     // 1 byte + 7 bytes padding
    double  value;      // 8 bytes
    bool    enabled;    // 1 byte + 7 bytes padding
    double  weight;     // 8 bytes
    // total: 32 bytes (50% wasted to padding)
};

// Good: larger fields first, bools grouped at end
struct GoodLayout {
    double  value;      // 8 bytes
    double  weight;     // 8 bytes
    bool    active;     // 1 byte
    bool    enabled;    // 1 byte  (6 bytes padding at end)
    // total: 24 bytes
};

// Verify with:
// _Static_assert(sizeof(GoodLayout) == 24, "Unexpected size");
// Use __attribute__((packed)) only when necessary — it disables alignment and hurts performance.',
        ],
        'tips' => [
            'Use godbolt.org (Compiler Explorer) to inspect assembly output — it makes optimisation tangible.',
            'Align hot structs to cache line boundaries with _Alignas(64) to eliminate false sharing.',
            'Read the C standard (N3220 for C23) and the MISRA C guidelines for safety-critical C practices.',
            'Follow the Linux kernel mailing list and SQLite\'s fossil repository for expert-level C style and review.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
