<?php
$tutorial_title = 'Go';
$tutorial_slug  = 'golang';
$quiz_slug      = 'golang';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Go (often called Golang) is an open-source, statically typed, compiled language designed at Google by Robert Griesemer, Rob Pike, and Ken Thompson. Released in 2009, Go was created to address the pain points of large-scale software development at Google: slow builds, complex dependency management, and poor concurrency support. Its philosophy — simplicity, readability, and fast compilation — produces code that is fast to write, easy to read, and efficient to run. Docker, Kubernetes, Terraform, and Prometheus are all written in Go.</p>',
        'concepts' => [
            'Go toolchain: go build, go run, go test, go fmt, go vet, go mod',
            'Package system: package main, package declarations, import paths',
            'Variables: var, :=, zero values, multiple return values',
            'Types: int, int64, float64, string, bool, byte, rune',
            'Control flow: if/else (with init statement), switch (no fallthrough), for (only loop)',
            'Arrays, slices, and maps: make(), append(), delete()',
            'Pointers: &var, *ptr — simpler than C (no pointer arithmetic)',
        ],
        'code' => [
            'title'   => 'Go basics — slices and maps',
            'lang'    => 'go',
            'content' =>
'package main

import (
	"fmt"
	"strings"
)

func wordFrequency(text string) map[string]int {
	freq := make(map[string]int)
	for _, word := range strings.Fields(strings.ToLower(text)) {
		word = strings.Trim(word, ".,!?;:\\"\'")
		if word != "" {
			freq[word]++
		}
	}
	return freq
}

func topN(freq map[string]int, n int) []string {
	type pair struct{ word string; count int }
	pairs := make([]pair, 0, len(freq))
	for w, c := range freq {
		pairs = append(pairs, pair{w, c})
	}
	// sort descending by count
	sort.Slice(pairs, func(i, j int) bool { return pairs[i].count > pairs[j].count })
	result := make([]string, min(n, len(pairs)))
	for i := range result { result[i] = pairs[i].word }
	return result
}',
        ],
        'tips' => [
            'Run gofmt (or goimports) on save — Go enforces a single canonical formatting style.',
            'The zero value is always defined in Go (0 for int, "" for string, nil for pointers/slices/maps).',
            'Use := for short variable declarations inside functions; var for package-level declarations.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Go\'s type system uses structs, interfaces, and embedding to express relationships. The key insight is that Go interfaces are satisfied implicitly — no <code>implements</code> keyword — which enables loose coupling and powerful mocking in tests. Methods are functions with a receiver, and the choice between value and pointer receivers determines whether the method can mutate the receiver.</p><p>Error handling in Go is explicit: functions return (value, error) pairs, and callers must check the error. This makes error flow visible throughout the codebase, unlike exceptions which can propagate invisibly.</p>',
        'concepts' => [
            'Structs: fields, struct literals, anonymous fields (embedding)',
            'Methods: value receivers vs. pointer receivers',
            'Interfaces: implicit implementation, interface values, nil interface pitfalls',
            'Error handling: errors.New(), fmt.Errorf("%w"), errors.Is(), errors.As()',
            'Multiple return values: func f() (int, error)',
            'Defer: deferred function calls and the LIFO execution order',
            'Type assertions and type switches',
        ],
        'code' => [
            'title'   => 'Go structs with interfaces and error handling',
            'lang'    => 'go',
            'content' =>
'package store

import (
	"errors"
	"fmt"
)

var ErrNotFound = errors.New("not found")

type User struct {
	ID   int
	Name string
	Email string
}

type UserStore interface {
	FindByID(id int) (*User, error)
	Save(u *User) error
}

type MemoryStore struct {
	users map[int]*User
	nextID int
}

func NewMemoryStore() *MemoryStore {
	return &MemoryStore{users: make(map[int]*User), nextID: 1}
}

func (s *MemoryStore) FindByID(id int) (*User, error) {
	u, ok := s.users[id]
	if !ok {
		return nil, fmt.Errorf("FindByID %d: %w", id, ErrNotFound)
	}
	return u, nil
}

func (s *MemoryStore) Save(u *User) error {
	if u.ID == 0 { u.ID = s.nextID; s.nextID++ }
	s.users[u.ID] = u
	return nil
}',
        ],
        'tips' => [
            'Always check errors immediately after the call — skipping error checks is a common source of production bugs.',
            'Use errors.Is() to check for sentinel errors; errors.As() to extract wrapped error types.',
            'The interface{} / any type should be used sparingly — prefer concrete types or generics in Go 1.18+.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Go\'s concurrency model — goroutines and channels — is the feature that sets it apart. Goroutines are lightweight (a few KB of stack) and managed by the Go scheduler, enabling millions of concurrent tasks. Channels provide typed, synchronized communication between goroutines, and the select statement multiplexes over multiple channels without busy-waiting.</p><p>The <code>sync</code> package provides Mutex, RWMutex, WaitGroup, Once, and atomic operations for cases where shared-memory concurrency is more appropriate than channel-based communication.</p>',
        'concepts' => [
            'Goroutines: go func(){} and the goroutine scheduler',
            'Channels: make(chan T, bufferSize), send <-, receive <-, close()',
            'Select statement: multiplex over channels, default for non-blocking ops',
            'sync.WaitGroup: goroutine coordination',
            'sync.Mutex and sync.RWMutex: protecting shared state',
            'Context: context.WithCancel, WithTimeout, WithDeadline, WithValue',
            'Go generics (1.18+): type parameters, constraints, comparable, any',
        ],
        'code' => [
            'title'   => 'Goroutines with WaitGroup and channels',
            'lang'    => 'go',
            'content' =>
'package main

import (
	"context"
	"fmt"
	"sync"
	"time"
)

func fetchAll(ctx context.Context, urls []string) []string {
	results := make([]string, len(urls))
	var wg sync.WaitGroup

	for i, url := range urls {
		wg.Add(1)
		go func(i int, url string) {
			defer wg.Done()
			select {
			case <-ctx.Done():
				results[i] = fmt.Sprintf("cancelled: %s", url)
			default:
				time.Sleep(10 * time.Millisecond) // simulate work
				results[i] = fmt.Sprintf("fetched: %s", url)
			}
		}(i, url)
	}

	wg.Wait()
	return results
}',
        ],
        'tips' => [
            'Pass context.Context as the first parameter of every function that does I/O or runs a goroutine.',
            'Prefer channels for "pass ownership of data to another goroutine"; prefer Mutex for "shared state access".',
            'Always close channels from the sender side, never the receiver — closing a closed channel panics.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Building production HTTP services in Go uses the standard library\'s <code>net/http</code> package or a lightweight router like <code>chi</code> or <code>gorilla/mux</code>. The <code>database/sql</code> package with pgx (PostgreSQL) or sqlx provides type-safe database access without an ORM. Middleware patterns — wrapping <code>http.Handler</code> — are idiomatic Go for logging, auth, and request tracing.</p><p>The Go profiler (pprof) is built into every Go binary and provides CPU, memory, goroutine, and block profiling through an HTTP endpoint or file-based snapshots.</p>',
        'concepts' => [
            'net/http: Handler, HandlerFunc, ServeMux, middleware pattern',
            'Router libraries: chi, gorilla/mux, httprouter',
            'database/sql: Open, QueryContext, ScanRows, Exec, transactions',
            'sqlx and pgx for richer PostgreSQL integration',
            'pprof profiling: runtime/pprof, net/http/pprof, go tool pprof',
            'Structured logging: log/slog (Go 1.21+), zerolog, zap',
            'Go modules: go.mod, go.sum, module proxies, private modules',
        ],
        'code' => [
            'title'   => 'Go HTTP middleware chain',
            'lang'    => 'go',
            'content' =>
'package middleware

import (
	"log/slog"
	"net/http"
	"time"
)

type Middleware func(http.Handler) http.Handler

func Chain(h http.Handler, ms ...Middleware) http.Handler {
	for i := len(ms) - 1; i >= 0; i-- {
		h = ms[i](h)
	}
	return h
}

func Logger(next http.Handler) http.Handler {
	return http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
		start := time.Now()
		rw    := &responseWriter{ResponseWriter: w, status: 200}
		next.ServeHTTP(rw, r)
		slog.Info("request",
			"method",   r.Method,
			"path",     r.URL.Path,
			"status",   rw.status,
			"duration", time.Since(start),
		)
	})
}

type responseWriter struct {
	http.ResponseWriter
	status int
}
func (rw *responseWriter) WriteHeader(s int) {
	rw.status = s
	rw.ResponseWriter.WriteHeader(s)
}',
        ],
        'tips' => [
            'Enable pprof in production behind an auth check — the profiler data is sensitive but invaluable.',
            'Use log/slog (Go 1.21+) for structured logging — it is the standard library\'s official structured logger.',
            'Prefer context-aware database calls (QueryContext, ExecContext) so requests can be cancelled.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Go involves understanding the Go scheduler — the M:N cooperative/preemptive scheduling model, goroutine state transitions, and stack growth — alongside the garbage collector\'s tri-color mark-and-sweep algorithm and how to tune GC pressure with GOGC and GOMEMLIMIT. Assembly interop with asm files and unsafe.Pointer bridges Go to C and hardware-specific optimisations.</p><p>Contributing to the Go standard library or tools, writing Go proposals through the GitHub issue tracker, and designing APIs that feel idiomatic — following Russ Cox\'s compatibility promise and the Go proverbs — mark the expert Go practitioner.</p>',
        'concepts' => [
            'Go scheduler: G/M/P model, goroutine preemption (Go 1.14+), GOMAXPROCS',
            'GC tuning: GOGC, GOMEMLIMIT, ballast approach, gccheckmark',
            'unsafe package: Pointer rules, reflect.SliceHeader, arena (Go 1.20)',
            'CGo: import "C", calling C from Go, performance caveats',
            'Go assembly: plan9 assembly syntax, writing asm stubs for SIMD',
            'Go compatibility promise: what breaks between major versions',
            'Go proposals: github.com/golang/go issues, accepted proposals, GOEXPERIMENT',
        ],
        'code' => [
            'title'   => 'GOMEMLIMIT and GC tuning',
            'lang'    => 'go',
            'content' =>
'package main

import (
	"runtime"
	"runtime/debug"
)

func init() {
	// Set a hard memory limit (Go 1.19+) — GC will run more aggressively
	// before this limit is reached rather than letting the process OOM.
	// Set to ~90% of the container memory limit.
	debug.SetMemoryLimit(450 << 20) // 450 MiB

	// Reduce GC frequency for throughput-sensitive services.
	// Default GOGC=100 means GC when heap doubles.
	// GOGC=200 lets it grow more before collecting.
	debug.SetGCPercent(200)
}

func printRuntimeStats() {
	var ms runtime.MemStats
	runtime.ReadMemStats(&ms)
	println("HeapInuse:",  ms.HeapInuse>>20, "MiB")
	println("HeapObjects:", ms.HeapObjects)
	println("GC cycles:",   ms.NumGC)
}',
        ],
        'tips' => [
            'Set GOMEMLIMIT to ~90% of your container limit — it prevents OOM kills by triggering GC earlier.',
            'Use sync.Pool for frequently-allocated short-lived objects to reduce GC pressure.',
            'Read "The Go Memory Model" (go.dev/ref/mem) before writing any lock-free concurrent code.',
            'Follow Russ Cox\'s blog (research.swtch.com) and the official Go blog (go.dev/blog) for deep insights.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
