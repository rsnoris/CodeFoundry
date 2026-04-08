<?php
$tutorial_title = 'C++';
$tutorial_slug  = 'cpp';
$quiz_slug      = 'cpp';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>C++ is a general-purpose, multi-paradigm programming language created by Bjarne Stroustrup in 1985 as an extension of C. It adds classes, templates, exceptions, and the Standard Template Library to C\'s low-level power. C++ is the language of choice for game engines (Unreal Engine), embedded systems, high-frequency trading, browser engines (Chrome\'s Blink), and performance-critical infrastructure. Modern C++ (C++11 and beyond) is a very different language from classic C++.</p>',
        'concepts' => [
            'C++ vs. C: classes, RAII, templates, exceptions, std library',
            'Modern C++: C++11/14/17/20/23 — the evolution and key features',
            'Compilation: g++ -std=c++23 -Wall -Wextra -o prog main.cpp',
            'auto, range-based for, uniform initialisation {}, nullptr',
            'References: int& ref = var; — safer pointer alternative for aliases',
            'Const correctness: const references, const member functions',
            'std::string, std::vector<T>, std::array<T,N>',
        ],
        'code' => [
            'title'   => 'Modern C++ basics',
            'lang'    => 'cpp',
            'content' =>
'#include <iostream>
#include <vector>
#include <string>
#include <algorithm>
#include <ranges>

int main() {
    std::vector<std::string> names{"Carol", "Alice", "Bob", "Diana"};

    // Range-based for with auto
    for (const auto& name : names) {
        std::cout << name << "\n";
    }

    // Ranges pipeline (C++20)
    auto long_names = names
        | std::views::filter([](const auto& n) { return n.size() > 3; })
        | std::views::transform([](const auto& n) { return n + "!"; });

    for (const auto& n : long_names) {
        std::cout << n << "\n";  // Carol! Alice! Diana!
    }
    return 0;
}',
        ],
        'tips' => [
            'Compile with -std=c++23 (or at minimum -std=c++17) — classic C++98 style is verbose and error-prone.',
            'Use const& for function parameters you read but do not own, to avoid unnecessary copies.',
            'Enable compiler warnings -Wall -Wextra -Wpedantic from day one — C++ is silent about many bugs.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>RAII (Resource Acquisition Is Initialisation) is the core C++ idiom for resource management: a resource (memory, file, socket) is bound to an object\'s lifetime and released automatically in the destructor. Smart pointers — <code>unique_ptr</code>, <code>shared_ptr</code>, <code>weak_ptr</code> — implement RAII for heap memory, replacing raw new/delete and making memory safety automatic.</p><p>Class design in C++ involves understanding the Rule of Five (or Zero), move semantics (rvalue references, std::move), and copy elision — the performance and correctness model that underpins modern C++.</p>',
        'concepts' => [
            'Classes: constructors, destructors, member functions, access specifiers',
            'RAII: resource lifetime tied to object lifetime',
            'Rule of Zero / Three / Five: copy constructor, copy assignment, destructor, move constructor, move assignment',
            'std::unique_ptr<T>: exclusive ownership, make_unique, no copy',
            'std::shared_ptr<T> and std::weak_ptr<T>: shared ownership and cycle prevention',
            'Move semantics: rvalue references (T&&), std::move(), copy elision (RVO/NRVO)',
            'std::optional<T>, std::variant<Ts...>, std::any for type-safe optional/union patterns',
        ],
        'code' => [
            'title'   => 'RAII with unique_ptr',
            'lang'    => 'cpp',
            'content' =>
'#include <memory>
#include <string>
#include <iostream>

class File {
    FILE* handle_;
public:
    explicit File(const std::string& path, const char* mode)
        : handle_(fopen(path.c_str(), mode)) {
        if (!handle_) throw std::runtime_error("Cannot open: " + path);
    }
    ~File() { if (handle_) fclose(handle_); }

    // Rule of Five: disable copy, enable move
    File(const File&)            = delete;
    File& operator=(const File&) = delete;
    File(File&& o) noexcept : handle_(std::exchange(o.handle_, nullptr)) {}
    File& operator=(File&&) noexcept = default;

    void writeLine(std::string_view line) {
        fputs(line.data(), handle_);
        fputc(\'\n\', handle_);
    }
};

// unique_ptr for heap objects
auto makeBuffer(size_t n) {
    return std::make_unique<int[]>(n); // auto-freed when out of scope
}',
        ],
        'tips' => [
            'Follow the Rule of Zero: if your class has no raw resources, do not write any special member functions.',
            'Prefer make_unique and make_shared over raw new — they are exception-safe and avoid naked pointers.',
            'Use std::move only when you intend to transfer ownership — after moving, the source is in a valid but unspecified state.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Templates are C++\'s mechanism for generic programming — writing code parameterised by types or values, resolved at compile time with zero runtime overhead. Function templates, class templates, template specialisation, and variadic templates (parameter packs) cover the full range from simple generic containers to the complex compile-time metaprogramming used in standard library implementations.</p><p>C++20 Concepts provide a clean syntax for constraining template parameters, replacing the cryptic SFINAE patterns of C++17 with readable requires clauses.</p>',
        'concepts' => [
            'Function templates and class templates: template<typename T>',
            'Template specialisation: full and partial specialisation',
            'Variadic templates: parameter packs, fold expressions (C++17)',
            'C++20 Concepts: concept keyword, requires clause, abbreviated function templates',
            'constexpr and consteval: compile-time computation',
            'std::span<T>, std::string_view: non-owning views',
            'Structured bindings: auto [x, y] = pair;',
        ],
        'code' => [
            'title'   => 'C++20 concept-constrained template',
            'lang'    => 'cpp',
            'content' =>
'#include <concepts>
#include <numeric>
#include <vector>
#include <stdexcept>

template<typename T>
concept Numeric = std::integral<T> || std::floating_point<T>;

template<Numeric T>
struct Stats {
    std::vector<T> data;

    void add(T val) { data.push_back(val); }

    double mean() const {
        if (data.empty()) throw std::runtime_error("empty dataset");
        return static_cast<double>(
            std::reduce(data.begin(), data.end())) / data.size();
    }

    T min() const { return *std::min_element(data.begin(), data.end()); }
    T max() const { return *std::max_element(data.begin(), data.end()); }
};

// Abbreviated function template with concept constraint
auto sum(Numeric auto a, Numeric auto b) { return a + b; }',
        ],
        'tips' => [
            'Use concepts over SFINAE whenever targeting C++20 — concepts produce readable error messages.',
            'Prefer std::string_view over const std::string& for read-only string parameters — no allocation.',
            'constexpr functions run at compile time when possible and runtime otherwise — embrace them widely.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced C++ covers template metaprogramming (TMP) — using templates as a Turing-complete compile-time computation language — coroutines (C++20) for cooperative async programming, and the C++ memory model for writing lock-free data structures with std::atomic.</p><p>The standard library algorithms (sort, transform, accumulate, ranges pipeline) combined with execution policies (std::execution::par) provide parallel algorithm execution with a one-word change to existing code.</p>',
        'concepts' => [
            'Template metaprogramming: type traits, std::enable_if, if constexpr',
            'CRTP (Curiously Recurring Template Pattern) for static polymorphism',
            'C++20 coroutines: co_await, co_yield, co_return, promise_type',
            'std::atomic<T> and the C++ memory model: memory_order semantics',
            'Parallel algorithms: std::execution::par, par_unseq policies',
            'Custom allocators: std::pmr (polymorphic memory resource)',
            'Modules (C++20): module declarations, import, and module partitions',
        ],
        'code' => [
            'title'   => 'C++20 coroutine generator',
            'lang'    => 'cpp',
            'content' =>
'#include <coroutine>
#include <iostream>
#include <optional>

template<typename T>
struct Generator {
    struct promise_type {
        std::optional<T> value;
        auto get_return_object() { return Generator{std::coroutine_handle<promise_type>::from_promise(*this)}; }
        std::suspend_always initial_suspend() { return {}; }
        std::suspend_always final_suspend()   noexcept { return {}; }
        std::suspend_always yield_value(T v)  { value = v; return {}; }
        void return_void() {}
        void unhandled_exception() { std::terminate(); }
    };
    std::coroutine_handle<promise_type> coro;
    explicit Generator(std::coroutine_handle<promise_type> h) : coro(h) {}
    ~Generator() { if (coro) coro.destroy(); }

    bool next() { coro.resume(); return !coro.done(); }
    T    value() { return *coro.promise().value; }
};

Generator<int> fibonacci() {
    int a = 0, b = 1;
    while (true) { co_yield a; auto c = a + b; a = b; b = c; }
}',
        ],
        'tips' => [
            'Use if constexpr instead of SFINAE for compile-time branching — it is far more readable.',
            'C++20 coroutines are low-level; prefer a library (cppcoro, asio) over hand-rolling the machinery.',
            'Profile before using parallel algorithms — the overhead of parallelism is only worth it for large datasets.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert C++ involves deep knowledge of the abstract machine — undefined behaviour, the C++ object model (vtable layout, data member pointers), and ABI (Application Binary Interface) stability for library authors. Performance engineering at this level uses CPU counters (perf), cache analysis, and SIMD intrinsics with knowledge of when the auto-vectoriser will or won\'t apply them.</p><p>Contributing to ISO WG21 (the C++ Standards Committee), writing proposals (P-numbers), and maintaining widely-used C++ libraries represent the community leadership dimension of expert C++ mastery.</p>',
        'concepts' => [
            'C++ object model: vtable layout, multiple/virtual inheritance, data member pointers',
            'ABI stability: Itanium C++ ABI, pimpl idiom for stable library ABI',
            'UB deep-dive: signed overflow, null dereference, strict aliasing, TBAA',
            'Sanitizers: ASan, UBSan, TSan, MSan — systematic UB detection',
            'Compile-time reflection proposals (P2996) and metaclasses',
            'C++23 features: std::expected<T,E>, std::print, std::flat_map, ranges improvements',
            'ISO WG21: SG groups, papers, National Body process',
        ],
        'code' => [
            'title'   => 'std::expected for error handling (C++23)',
            'lang'    => 'cpp',
            'content' =>
'#include <expected>
#include <string>
#include <charconv>

enum class ParseError { InvalidFormat, OutOfRange };

std::expected<int, ParseError> parsePort(std::string_view input) {
    int value{};
    auto [ptr, ec] = std::from_chars(input.begin(), input.end(), value);
    if (ec != std::errc{})         return std::unexpected(ParseError::InvalidFormat);
    if (value < 1 || value > 65535) return std::unexpected(ParseError::OutOfRange);
    return value;
}

// Usage: clean error propagation without exceptions
void configureServer(std::string_view portStr) {
    auto port = parsePort(portStr);
    if (!port) {
        switch (port.error()) {
            case ParseError::InvalidFormat: throw std::runtime_error("invalid port");
            case ParseError::OutOfRange:    throw std::runtime_error("port out of range");
        }
    }
    // use *port
}',
        ],
        'tips' => [
            'Use std::expected over exception-throwing functions for performance-critical error paths.',
            'Read the WG21 papers at open-std.org — many accepted features started as individual proposals.',
            'Follow Herb Sutter\'s blog (sutter.ms) and the CppCon YouTube channel for expert-level C++ guidance.',
            'Study the CppCoreGuidelines (github.com/isocpp/CppCoreGuidelines) as the authoritative style reference.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
