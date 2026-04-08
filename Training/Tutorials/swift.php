<?php
$tutorial_title = 'Swift';
$tutorial_slug  = 'swift';
$quiz_slug      = 'swift';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Swift is a modern, safe, fast, and expressive programming language developed by Apple and first released in 2014. It replaced Objective-C as the primary language for iOS, macOS, watchOS, and tvOS development, while also running on Linux and embedded systems through Swift Embedded. Swift\'s design prioritises safety (no null pointer dereferences, no buffer overflows), performance (comparable to C on most benchmarks), and developer experience (expressive syntax, powerful type inference, and a REPL).</p>',
        'concepts' => [
            'Swift toolchain: Xcode, swift CLI, Swift Package Manager (SPM)',
            'Variables: let (constant), var (mutable); type inference',
            'Optionals: String?, unwrapping (if let, guard let, ??), force unwrap !',
            'Control flow: if/else, switch with pattern matching, for-in, while',
            'Functions: parameter labels, default values, variadic parameters, inout',
            'Closures: { params in body }, trailing closure syntax, capturing',
            'String interpolation: "Hello, \\(name)!"',
        ],
        'code' => [
            'title'   => 'Swift optionals and closures',
            'lang'    => 'swift',
            'content' =>
'import Foundation

func greet(_ name: String?) -> String {
    guard let name = name, !name.isEmpty else {
        return "Hello, stranger!"
    }
    return "Hello, \\(name)!"
}

// Closures and higher-order functions
let scores = [88, 92, 74, 95, 61]
let passing = scores.filter { $0 >= 75 }.sorted(by: >)
let grades  = passing.map { score -> String in
    switch score {
    case 90...: return "A"
    case 80...: return "B"
    default:    return "C"
    }
}

print(greet(nil))        // Hello, stranger!
print(greet("Alice"))    // Hello, Alice!
print(grades)            // ["A", "A", "B"]',
        ],
        'tips' => [
            'Use guard let for early exits; if let when you need both branches — this keeps nesting shallow.',
            'The ?? (nil coalescing) operator provides a clean default: name ?? "Guest".',
            'Use parameter labels to make call sites read like English: move(from: a, to: b).',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Swift\'s type system — structs, classes, enums, and protocols — combines value semantics (structs copy on assignment) with reference semantics (classes share instances). Swift enums are algebraic data types with associated values, making them the ideal tool for modelling domain states. Protocols define interfaces; protocol extensions provide default implementations.</p><p>Swift\'s error handling model — throwing functions, try/catch, and Result<T, E> — is explicit and composable, preventing silent failure propagation.</p>',
        'concepts' => [
            'Structs vs. classes: value types vs. reference types, when to use each',
            'Enums with associated values: enum Result<T, E> { case success(T); case failure(E) }',
            'Protocols: protocol definitions, conformance, protocol extensions',
            'Protocol-oriented programming: composition over inheritance',
            'Error handling: throws, try, try?, try!, do-catch, custom Error types',
            'Extensions: adding methods to existing types (including standard library)',
            'Computed properties and property observers (willSet/didSet)',
        ],
        'code' => [
            'title'   => 'Swift protocol-oriented design',
            'lang'    => 'swift',
            'content' =>
'protocol Describable {
    var description: String { get }
}

extension Describable {
    func print() { Swift.print(description) }
}

enum NetworkError: Error, Describable {
    case notFound(String)
    case unauthorized
    case serverError(Int)

    var description: String {
        switch self {
        case .notFound(let path):    return "404 Not Found: \\(path)"
        case .unauthorized:          return "401 Unauthorized"
        case .serverError(let code): return "Server error: \\(code)"
        }
    }
}

struct User: Describable {
    let id:   Int
    let name: String
    var description: String { "User(\\(id): \\(name))" }
}

func fetchUser(id: Int) throws -> User {
    guard id > 0 else { throw NetworkError.notFound("/users/\\(id)") }
    return User(id: id, name: "Alice")
}',
        ],
        'tips' => [
            'Default to struct — use class only when you need reference semantics (shared mutable state, inheritance).',
            'Use protocol extensions to provide default implementations — it enables mixin-like code reuse.',
            'Model domain states with enums + associated values: enum LoadState { case idle, loading, loaded(User), error(Error) }.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>SwiftUI is Apple\'s declarative UI framework — views are functions of state, and the framework handles diffing and rendering. Combined with the <code>@State</code>, <code>@StateObject</code>, <code>@ObservableObject</code> / <code>@Observable</code> property wrappers and Combine or Swift Concurrency for data flow, SwiftUI enables reactive, composable UI for all Apple platforms.</p><p>Swift Concurrency (async/await, actors, structured concurrency with Task and TaskGroup) replaces completion handlers and DispatchQueue-based code with readable, safe asynchronous code.</p>',
        'concepts' => [
            'SwiftUI: View protocol, body computed property, modifiers',
            '@State, @Binding, @StateObject, @EnvironmentObject, @Environment',
            '@Observable macro (iOS 17+): replacing ObservableObject',
            'async/await: async functions, await, Task, TaskGroup',
            'Actors: actor keyword, isolated state, MainActor for UI',
            'Combine: Publisher, Subscriber, operators (map, filter, flatMap, debounce)',
            'Swift Package Manager (SPM): Package.swift, dependencies, targets, products',
        ],
        'code' => [
            'title'   => 'SwiftUI with async/await data fetching',
            'lang'    => 'swift',
            'content' =>
'import SwiftUI

@Observable
final class UserViewModel {
    var users:   [User] = []
    var isLoading = false
    var error:   String?

    func loadUsers() async {
        isLoading = true
        error     = nil
        do {
            let url = URL(string: "https://jsonplaceholder.typicode.com/users")!
            let (data, _) = try await URLSession.shared.data(from: url)
            users = try JSONDecoder().decode([User].self, from: data)
        } catch {
            self.error = error.localizedDescription
        }
        isLoading = false
    }
}

struct UserListView: View {
    @State private var vm = UserViewModel()

    var body: some View {
        List(vm.users, id: \\.id) { user in
            Text(user.name)
        }
        .overlay { if vm.isLoading { ProgressView() } }
        .task { await vm.loadUsers() }
    }
}',
        ],
        'tips' => [
            'Use @Observable (iOS 17+) over ObservableObject — it is simpler, faster, and requires less boilerplate.',
            'Always await URLSession calls on a background task, then update @Observable state (which auto-dispatches to main).',
            '.task modifier is the correct place for async data loading — it cancels automatically when the view disappears.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Result Builders are the Swift feature that powers SwiftUI\'s DSL syntax — the ability to write HTML-like, declarative code by composing expressions into a result. Understanding how result builders work lets you design your own DSLs for HTML generation, query building, or configuration.</p><p>Property wrappers encapsulate common getter/setter logic into reusable annotations. Combined with macros (introduced in Swift 5.9), they represent the metaprogramming layer of Swift — enabling code generation with type safety and IDE integration.</p>',
        'concepts' => [
            '@resultBuilder: buildBlock, buildOptional, buildEither, buildArray',
            'Property wrappers: @propertyWrapper, wrappedValue, projectedValue',
            'Swift Macros: @freestanding(expression), @attached(member), Swift Syntax',
            'Generics with primary associated types: some Collection<Int>',
            'Existential types: any Protocol vs. some Protocol (opaque types)',
            'Key paths: \\Type.property, key-path expressions in SwiftUI',
            'Memory management: ARC, strong/weak/unowned references, retain cycles',
        ],
        'code' => [
            'title'   => 'Custom @resultBuilder DSL',
            'lang'    => 'swift',
            'content' =>
'@resultBuilder
struct HTMLBuilder {
    static func buildBlock(_ components: String...) -> String {
        components.joined(separator: "\\n")
    }
    static func buildOptional(_ component: String?) -> String {
        component ?? ""
    }
}

func div(@HTMLBuilder content: () -> String) -> String {
    "<div>\\n\\(content())\\n</div>"
}

func p(_ text: String)   -> String { "<p>\\(text)</p>" }
func h1(_ text: String)  -> String { "<h1>\\(text)</h1>" }

let showSubtitle = true

let html = div {
    h1("Hello, Swift!")
    if showSubtitle {
        p("Built with a result builder DSL")
    }
    p("Every line is a String expression")
}
print(html)',
        ],
        'tips' => [
            'Use some Protocol (opaque type) for return types when you have one concrete type; any Protocol for heterogeneous collections.',
            'Break retain cycles with [weak self] in closures where self is captured — especially in async callbacks.',
            'Swift Macros eliminate boilerplate at compile time — explore the swift-syntax package to write your own.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Swift development encompasses contributing to the Swift evolution process (Swift Evolution proposals on swift-evolution/proposals), deep knowledge of the Swift type checker and the constraint solver (understanding why complex generic code fails to type-check), and working with the Swift compiler internals for tooling development.</p><p>Swift Embedded for microcontrollers, Swift on Linux for server workloads, and cross-platform development with Swift Package Manager on non-Apple platforms mark the breadth of the expert Swift developer. Performance engineering with Instruments, the Swift Allocation Profiler, and understanding value type performance characteristics complete this tier.</p>',
        'concepts' => [
            'Swift type checker: bidirectional type inference, constraint solver limitations',
            'Swift Evolution process: proposal format, review process, implementation',
            'Swift Embedded: #if embedded, minimal runtime, bare-metal deployment',
            'Swift on Linux: Foundation on non-Apple platforms, swift-corelibs',
            'Instruments: Time Profiler, Allocations, Leaks, SwiftUI profiling template',
            'LLDB: p, po, expression, frame variable, watchpoints in Xcode debugging',
            'Swift compiler flags: -O, -whole-module-optimization, -Onone for Debug',
        ],
        'code' => [
            'title'   => 'Instruments-guided allocation reduction',
            'lang'    => 'swift',
            'content' =>
'// BEFORE: Strings are heap-allocated — bad in a tight loop
func processItems(_ items: [String]) -> [String] {
    items.map { $0.uppercased() }  // allocates a new String per item
}

// AFTER: Use Substring/String views to defer allocation
func processItemsLazy(_ items: [String]) -> some Sequence {
    items.lazy.map { $0.uppercased() }  // lazy: defers map until iteration
}

// For fixed-size identifiers, consider StaticString or inline storage
struct Tag {
    // Stores up to 15 UTF-8 bytes on the stack — zero heap allocation
    let value: StaticString
    init(_ s: StaticString) { value = s }
}

// Profile with:
// Instruments → Allocations template → track "String" in category filter
// Goal: reduce transient allocations in hot paths to near zero',
        ],
        'tips' => [
            'Use lazy collections in hot loops to avoid intermediate array allocations.',
            'Profile with Instruments before optimising — Swift\'s value-type semantics often avoids allocation you expect.',
            'Follow the Swift Forums (forums.swift.org) for Swift Evolution proposals and compiler roadmap discussions.',
            'Read "Advanced Swift" by objc.io for the definitive expert-level Swift reference.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
