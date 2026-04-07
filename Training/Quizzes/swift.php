<?php
$page_title = 'Swift Quiz – 100 Levels – CodeFoundry';
$active_page = 'training';
$quiz_title  = 'Swift';
$quiz_slug   = 'swift';
$quiz_tiers  = [
    [
        'label' => 'Introduction',
        'questions' => [
            [
                'question' => 'What company created the Swift programming language?',
                'options'  => ['Apple', 'Google', 'Microsoft', 'Mozilla'],
                'correct'  => 0,
            ],
            [
                'question' => 'In what year was Swift first announced?',
                'options'  => ['2014', '2012', '2016', '2010'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword declares a constant in Swift?',
                'options'  => ['let', 'const', 'final', 'var'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword declares a variable in Swift?',
                'options'  => ['var', 'let', 'mut', 'def'],
                'correct'  => 0,
            ],
            [
                'question' => 'Where does a Swift command-line program begin execution?',
                'options'  => ['Top-level code in main.swift', 'main()', 'start()', 'run()'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you print output to the console in Swift?',
                'options'  => ['print()', 'console.log()', 'echo()', 'printf()'],
                'correct'  => 0,
            ],
            [
                'question' => 'What character sequence starts a single-line comment in Swift?',
                'options'  => ['//', '#', '--', '/*'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which Swift type represents whole numbers?',
                'options'  => ['Int', 'Integer', 'Number', 'Whole'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which Swift type represents double-precision decimal numbers?',
                'options'  => ['Double', 'Float64', 'Decimal', 'Real'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which Swift type represents true/false values?',
                'options'  => ['Bool', 'Boolean', 'Bit', 'Flag'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which Swift type represents text?',
                'options'  => ['String', 'Text', 'Char[]', 'str'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which syntax is used for string interpolation in Swift?',
                'options'  => ['\\()', '#{}', '${}', '%s'],
                'correct'  => 0,
            ],
            [
                'question' => 'How is an array literal written in Swift?',
                'options'  => ['[1, 2, 3]', '{1, 2, 3}', '(1, 2, 3)', '<1, 2, 3>'],
                'correct'  => 0,
            ],
            [
                'question' => 'How is a dictionary literal written in Swift?',
                'options'  => ['["key": "value"]', '{"key": "value"}', '("key", "value")', '<"key", "value">'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword is used to define a function in Swift?',
                'options'  => ['func', 'function', 'def', 'fn'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword is used for conditional branching in Swift?',
                'options'  => ['if', 'when', 'cond', 'check'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword introduces a switch statement in Swift?',
                'options'  => ['switch', 'match', 'case', 'select'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which loop construct iterates over a range or sequence in Swift?',
                'options'  => ['for-in', 'foreach', 'while', 'do-while'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is an Optional in Swift?',
                'options'  => ['A value that may be nil', 'An optional function parameter', 'A lazy variable', 'A weak reference'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you declare an optional String in Swift?',
                'options'  => ['String?', 'String!', 'string | nil', 'Optional(String)'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the nil-coalescing operator ?? do?',
                'options'  => ['Provides a default value when the left side is nil', 'Checks equality', 'Force-unwraps an optional', 'Throws an error if nil'],
                'correct'  => 0,
            ],
            [
                'question' => 'What file extension do Swift source files use?',
                'options'  => ['.swift', '.sw', '.sft', '.swt'],
                'correct'  => 0,
            ],
            [
                'question' => 'What IDE is primarily used for Swift development?',
                'options'  => ['Xcode', 'Visual Studio', 'Eclipse', 'IntelliJ IDEA'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does ARC stand for in Swift?',
                'options'  => ['Automatic Reference Counting', 'Automatic Resource Collection', 'Abstract Runtime Control', 'Asynchronous Reference Check'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which platform was Swift primarily designed for?',
                'options'  => ['Apple platforms (iOS, macOS, etc.)', 'Android', 'Windows', 'Linux servers only'],
                'correct'  => 0,
            ],
        ],
    ],
    [
        'label' => 'Beginner',
        'questions' => [
            [
                'question' => 'What is optional binding in Swift?',
                'options'  => ['Using if let or guard let to safely unwrap an Optional', 'Using the ?? operator', 'Force-unwrapping with !', 'Checking a type with is'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does guard let do when its condition fails?',
                'options'  => ['Exits the current scope via return, throw, break, or continue', 'Loops until the condition is true', 'Creates a new inner scope', 'Checks type conformance'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a tuple in Swift?',
                'options'  => ['A fixed-size ordered collection of values', 'A resizable array', 'A dictionary', 'A class instance'],
                'correct'  => 0,
            ],
            [
                'question' => 'What keyword marks a method that overrides a superclass implementation?',
                'options'  => ['override', 'virtual', 'open', 'dynamic'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a Swift enum?',
                'options'  => ['A type that defines a group of related named values', 'An integer array', 'A class hierarchy', 'A protocol'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you define a struct in Swift?',
                'options'  => ['struct Name {}', 'class Name {}', 'type Name = {}', 'define Name {}'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the key difference between a struct and a class in Swift?',
                'options'  => ['Structs are value types; classes are reference types', 'Classes are value types; structs are reference types', 'There is no difference', 'Structs support inheritance'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you declare that a class inherits from another in Swift?',
                'options'  => ['class Child: Parent {}', 'class Child extends Parent {}', 'class Child inherits Parent {}', 'class Child(Parent) {}'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a closure in Swift?',
                'options'  => ['An anonymous, self-contained block of code', 'A sealed class', 'A final stored variable', 'A compiled module'],
                'correct'  => 0,
            ],
            [
                'question' => 'What shorthand names are used for closure arguments in Swift?',
                'options'  => ['$0, $1, $2, etc.', '@0, @1, @2', 'arg0, arg1, arg2', '_0, _1, _2'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @escaping mean on a closure parameter?',
                'options'  => ['The closure may outlive the function call', 'The closure throws errors', 'The closure is synchronous', 'The closure holds a weak reference'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a protocol in Swift?',
                'options'  => ['A blueprint of methods and properties a conforming type must implement', 'A class template', 'A compiled module', 'A design pattern'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you declare that a type conforms to a protocol?',
                'options'  => ['struct/class Name: ProtocolName {}', 'struct/class Name implements ProtocolName {}', 'struct/class Name conforms ProtocolName {}', 'struct/class Name uses ProtocolName {}'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the closed range operator ... do?',
                'options'  => ['Creates a range that includes both endpoints', 'Creates an open range', 'Creates a half-open range excluding the upper bound', 'Creates an exclusive range'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the half-open range operator ..< do?',
                'options'  => ['Creates a range that excludes the upper bound', 'Creates a range that includes both bounds', 'Creates a range that excludes the lower bound', 'Wraps around on overflow'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword is used to throw an error in Swift?',
                'options'  => ['throw', 'raise', 'error', 'throws'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword marks a function declaration as capable of throwing an error?',
                'options'  => ['throws', 'can-throw', 'error', 'unsafe'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you call a throwing function safely in Swift?',
                'options'  => ['Use try inside a do-catch block', 'Use try alone', 'Use do-catch alone without try', 'Use @catch'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the defer statement do in Swift?',
                'options'  => ['Executes a block of code when the current scope exits', 'Delays variable initialization', 'Creates a lazy property', 'Pauses execution for a time interval'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a lazy stored property in Swift?',
                'options'  => ['A property whose initial value is computed only the first time it is accessed', 'A property always initialized at startup', 'A computed property with a getter', 'A constant property'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the mutating keyword on a struct method allow?',
                'options'  => ['The method to modify the struct\'s stored properties', 'The struct to become immutable', 'The method to be overridden in subclasses', 'The method to run asynchronously'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a computed property in Swift?',
                'options'  => ['A property with custom get and optional set logic instead of stored value', 'A lazily initialized stored constant', 'A lazy variable', 'A protocol requirement only'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the willSet property observer do?',
                'options'  => ['Runs just before the property value is changed', 'Runs just after the property value changes', 'Runs both before and after the change', 'Prevents the property from changing'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the didSet property observer do?',
                'options'  => ['Runs just after the property value has changed', 'Runs just before the property value changes', 'Runs both before and after the change', 'Counts the number of changes'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is type inference in Swift?',
                'options'  => ['The compiler automatically deduces the type of a variable from its initial value', 'Runtime type checking', 'Dynamic typing at runtime', 'Explicit casting between types'],
                'correct'  => 0,
            ],
        ],
    ],
    [
        'label' => 'Intermediate',
        'questions' => [
            [
                'question' => 'What is a generic function in Swift?',
                'options'  => ['A function that works with any type satisfying its constraints', 'A protocol method', 'An overloaded function', 'A virtual method'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you constrain a generic type parameter to be Comparable?',
                'options'  => ['func f<T: Comparable>(...)', 'func f<T where T == Comparable>(...)', 'func f(T: Comparable)(...)', 'func f<T extends Comparable>(...)'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is an associated type in a Swift protocol?',
                'options'  => ['A placeholder type name defined inside the protocol', 'A concrete subtype', 'A computed property type', 'A generic constraint'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is protocol-oriented programming in Swift?',
                'options'  => ['Designing software around protocol abstractions and extensions', 'Using only classes and inheritance', 'Object-oriented programming with classes', 'Purely functional programming'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does an extension do in Swift?',
                'options'  => ['Adds new functionality to an existing type without subclassing', 'Creates a subclass', 'Defines a module', 'Marks a type as publicly extensible'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a property wrapper in Swift?',
                'options'  => ['A type that encapsulates reusable storage and behavior for a property', 'A computed property', 'An optional binding pattern', 'A type alias'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is @State in SwiftUI?',
                'options'  => ['A property wrapper that manages local mutable state in a view', 'A class-level property', 'A protocol for view state', 'A thread-safety attribute'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is @Binding in SwiftUI?',
                'options'  => ['A two-way connection to a state value owned elsewhere', 'A local state property wrapper', 'An observable object reference', 'A published property'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does conforming to Codable provide in Swift?',
                'options'  => ['Automatic encoding and decoding to/from external representations like JSON', 'JSON parsing only', 'Binary serialization only', 'Network request handling'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the difference between map and compactMap on a Swift sequence?',
                'options'  => ['compactMap removes nil values from the result; map keeps them as optionals', 'map removes nil values; compactMap keeps them', 'There is no difference', 'compactMap only transforms without mapping'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does flatMap do on an array of arrays in Swift?',
                'options'  => ['Flattens one level of nesting and applies a transform', 'Only applies a transform without flattening', 'Only flattens without transforming', 'Filters out empty arrays'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the Result type in Swift?',
                'options'  => ['An enum with .success(Value) and .failure(Error) cases', 'A protocol for operations', 'A struct wrapping an optional', 'A class for async results'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does an opaque return type (some Protocol) mean in Swift?',
                'options'  => ['The function returns a specific concrete type conforming to the protocol, but the type is hidden from callers', 'The function may return any conforming type', 'It is the same as a generic return type', 'It is an abstract class return'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @discardableResult do in Swift?',
                'options'  => ['Suppresses the compiler warning when a function\'s return value is unused', 'Forces the caller to use the return value', 'Marks a function as throwing', 'Caches the return value'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does a weak reference prevent in Swift?',
                'options'  => ['Retain cycles between objects', 'Nil pointer dereferences', 'Type-casting errors', 'Thread races'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the difference between weak and unowned references in Swift?',
                'options'  => ['weak becomes nil when the object is deallocated; unowned crashes if the object is gone', 'unowned becomes nil; weak crashes', 'There is no practical difference', 'Both crash when the object is deallocated'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is DispatchQueue.main.async used for?',
                'options'  => ['Scheduling a closure to run on the main thread asynchronously', 'Running background tasks', 'Creating new threads', 'Locking shared resources'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does async/await in Swift enable?',
                'options'  => ['Writing asynchronous code that reads like synchronous code', 'Automatic parallel execution of all functions', 'Complete replacement of all GCD APIs', 'Creating OS-level threads'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which keyword marks a function as asynchronous in Swift?',
                'options'  => ['async', '@async', 'concurrent', 'dispatch'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is an actor in Swift concurrency?',
                'options'  => ['A reference type that serializes access to its mutable state', 'An OS thread', 'A protocol for concurrent types', 'A subclass of DispatchQueue'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @MainActor guarantee?',
                'options'  => ['That the annotated code runs on the main thread', 'That the code runs on a background actor', 'That the code is a GCD main queue operation', 'That the code acquires a thread lock'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a Task in Swift concurrency?',
                'options'  => ['A unit of asynchronous work that can be awaited or cancelled', 'A background OS thread', 'A DispatchQueue wrapper', 'An OperationQueue item'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the Sendable protocol indicate in Swift?',
                'options'  => ['The type is safe to share across concurrency domains', 'The type is hashable', 'The type supports encoding', 'The type is thread-locked'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a KeyPath in Swift?',
                'options'  => ['A type-safe reference to a property of a specific type', 'A dictionary key', 'A closure capturing a property', 'A generic constraint'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the filter higher-order function do on a Swift collection?',
                'options'  => ['Returns a new collection containing only elements that satisfy a predicate', 'Transforms each element', 'Reduces the collection to a single value', 'Sorts the collection'],
                'correct'  => 0,
            ],
        ],
    ],
    [
        'label' => 'Advanced',
        'questions' => [
            [
                'question' => 'What is a phantom type in Swift?',
                'options'  => ['A generic type parameter used only for compile-time type safety, not stored at runtime', 'An unused protocol type', 'A nil-representable type', 'A type used only in debug builds'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the @resultBuilder attribute enable in Swift?',
                'options'  => ['Custom DSL syntax by transforming a block of statements into a single value', 'Caching function results automatically', 'Making a class lazily constructable', 'Returning results from closures lazily'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @dynamicMemberLookup enable on a Swift type?',
                'options'  => ['Subscript-style access using arbitrary member names resolved at compile time or runtime', 'Dynamic method dispatch', 'Runtime reflection of all members', 'Automatic protocol synthesis'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is an existential type written as any Protocol in Swift?',
                'options'  => ['A type-erased box that holds any value conforming to the protocol at runtime', 'Identical to a generic parameter', 'A concrete struct conforming to the protocol', 'The same as an opaque type'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the key difference between some Protocol and any Protocol?',
                'options'  => ['some is a compile-time opaque concrete type; any is a runtime type-erased existential', 'any is faster at runtime than some', 'some allows heterogeneous collections of conforming types', 'There is no practical difference'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a metatype in Swift?',
                'options'  => ['The type of a type itself, e.g. String.Type or any Protocol.Type', 'A protocol with associated types', 'A generic wrapper type', 'A metaclass from Objective-C'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the Mirror type provide in Swift?',
                'options'  => ['Runtime reflection of a value\'s structure, fields, and children', 'Memory management information', 'Type-casting utilities', 'ARC retain count access'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @inline(__always) tell the Swift compiler?',
                'options'  => ['Always inline this function at every call site', 'Never inline this function', 'Mark this as a hot code path', 'Treat this as an optimization hint only'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a nonisolated function in Swift concurrency?',
                'options'  => ['A function that can be called from any isolation context without switching executors', 'A synchronous-only function', 'A global top-level function', 'A method that belongs to no actor'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a continuation in Swift async?',
                'options'  => ['A mechanism to bridge legacy callback-based APIs into async/await', 'A type of for loop body', 'A TaskGroup result', 'A thread resume handle'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does withCheckedContinuation provide?',
                'options'  => ['A safe continuation for wrapping callback-based APIs into async functions', 'A memory-leak checker', 'Validation that all continuations are resumed exactly once (with trapping)', 'A way to resume a suspended Task from outside'],
                'correct'  => 2,
            ],
            [
                'question' => 'What is a TaskGroup in Swift concurrency?',
                'options'  => ['A structured way to run multiple async child tasks and collect their results', 'A thread pool abstraction', 'A replacement for DispatchGroup', 'A semaphore for async tasks'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is structured concurrency in Swift?',
                'options'  => ['Tasks have parent-child relationships with lifetimes scoped to the enclosing block', 'Using DispatchQueue hierarchies', 'Only actors, no unstructured tasks', 'Sequential async code with await'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @available do in Swift?',
                'options'  => ['Marks both platform/version availability and deprecation information for APIs', 'Marks deprecated APIs only', 'Marks availability without deprecation info', 'Hides APIs from generated documentation'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a variadic parameter in Swift?',
                'options'  => ['A parameter that accepts zero or more values of a given type', 'A parameter that always takes exactly one value', 'A parameter that takes exactly two values', 'A tuple parameter'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does an inout parameter do in Swift?',
                'options'  => ['Passes the argument by reference so the function can modify it', 'Passes by value for reading only', 'Makes the parameter optional', 'Marks the parameter as a constant'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a subscript in Swift?',
                'options'  => ['Custom bracket [] access syntax defined on a type', 'An array index type', 'A dictionary key type', 'A required protocol method'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is conditional conformance in Swift?',
                'options'  => ['A generic type conforming to a protocol only when its type parameters satisfy certain constraints', 'A type always conforming to a protocol', 'Protocol inheritance', 'A compile-time type alias'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is retroactive conformance in Swift?',
                'options'  => ['Making a type from one module conform to a protocol from another module', 'Protocol inheritance across modules', 'Type casting across modules', 'A generic constraint on imported types'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is module stability in Swift?',
                'options'  => ['Binary-stable module interfaces that allow clients to use a framework without recompiling', 'Fast per-file compilation', 'Static library linking', 'Source-level API compatibility'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does library evolution mode (BUILD_LIBRARY_FOR_DISTRIBUTION) enable?',
                'options'  => ['Changing library internals without breaking ABI for existing clients', 'Source-only compatibility changes', 'Forcing clients to recompile', 'Debug-only builds'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the @frozen attribute on an enum or struct promise?',
                'options'  => ['No new cases (for enums) or stored properties (for structs) will be added in future versions', 'Prevents subclassing', 'Marks the type as final', 'Disables runtime reflection'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the difference between static and class methods in Swift?',
                'options'  => ['static cannot be overridden in subclasses; class can be overridden', 'class cannot be overridden; static can', 'Both static and class can be overridden', 'Neither static nor class can be overridden'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the @preconcurrency attribute used for in Swift?',
                'options'  => ['Suppressing concurrency warnings when adopting pre-Swift-5.5 APIs', 'Enabling structured concurrency', 'Marking an actor as pre-existing', 'Sending Sendable types across modules'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @_implementationOnly import do?',
                'options'  => ['Imports a module for implementation use only without exposing it in the public interface', 'Imports only the public symbols of a module', 'Marks all imported symbols as internal', 'Prevents the module from being linked'],
                'correct'  => 0,
            ],
        ],
    ],
    [
        'label' => 'Expert',
        'questions' => [
            [
                'question' => 'In a Swift protocol extension, what does self refer to?',
                'options'  => ['The conforming type at the call site, not the protocol itself', 'Always the protocol type', 'An erased existential self', 'Any type'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a primary associated type introduced in Swift 5.7?',
                'options'  => ['An associated type that can appear in constrained existential types like any Collection<Int>', 'A default-valued associated type', 'A required associated type with no default', 'A retroactively added associated type'],
                'correct'  => 0,
            ],
            [
                'question' => 'What did SE-0352 introduce in Swift 5.7?',
                'options'  => ['Implicitly opened existentials enabling direct use of existential values as generics', 'Async sequences', 'Actor isolation checking', 'Opaque parameter types'],
                'correct'  => 0,
            ],
            [
                'question' => 'In which Swift version was ABI stability first achieved?',
                'options'  => ['Swift 5.0', 'Swift 4.0', 'Swift 3.0', 'Swift 5.5'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is whole-module optimization (WMO) in Swift?',
                'options'  => ['Compiling all source files together to enable cross-file inlining and dead-code elimination', 'Per-file incremental compilation', 'Link-time optimization at the linker level', 'Debug-mode optimization'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a resilience boundary in Swift?',
                'options'  => ['The boundary at which a library\'s types can change internally without requiring clients to recompile', 'A module import boundary', 'An ABI-breaking change', 'A memory region boundary'],
                'correct'  => 0,
            ],
            [
                'question' => 'How does Swift represent closures that capture context at the machine level?',
                'options'  => ['As thick function pointers: a pair of a function pointer and a heap-allocated context pointer', 'As thin C function pointers', 'As Objective-C blocks', 'As single raw function pointers'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does withUnsafePointer(to:_:) do in Swift?',
                'options'  => ['Temporarily exposes the raw memory address of a value to a closure via an UnsafePointer', 'Allocates new heap memory', 'Frees memory pointed to by the pointer', 'Performs unsafe type casts'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the compiler attribute @_silgen_name used for?',
                'options'  => ['Mapping a Swift function to a specific linker symbol name for interop', 'Marking functions as always-inline', 'A public optimization hint', 'A linker script directive'],
                'correct'  => 0,
            ],
            [
                'question' => 'What languages is the Swift runtime primarily implemented in?',
                'options'  => ['C++ and Swift', 'Pure C only', 'Pure Swift only', 'Objective-C and C'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is an existential metatype in Swift?',
                'options'  => ['The metatype of a protocol type, e.g. any Protocol.Type', 'The metatype of a concrete struct', 'A generic metatype parameter', 'A class metatype'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the @_transparent compiler attribute do?',
                'options'  => ['Inlines the function body even at -Onone and exposes it to SIL-level analysis', 'Marks the function as thread-safe', 'Forces type specialization', 'Acts as a no-op annotation'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is SIL in the Swift compilation pipeline?',
                'options'  => ['Swift Intermediate Language, a high-level IR between Swift AST and LLVM IR', 'Swift Implementation Layer', 'Swift Inline Language', 'Source Intermediate Linker format'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a witness table in Swift?',
                'options'  => ['A per-conformance table of function pointers implementing a protocol\'s requirements', 'A type registry for all known types', 'A vtable for class inheritance', 'A dispatch cache for method calls'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a vtable in Swift classes?',
                'options'  => ['A virtual dispatch table used to resolve overridden class method calls at runtime', 'A table of value-type methods', 'A variable lookup table', 'A view hierarchy table'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the @_specialize attribute do in Swift?',
                'options'  => ['Emits a fully specialized version of a generic function for a given set of concrete type arguments', 'Marks a function as already specialized', 'Prevents generic instantiation', 'Forces static dispatch on protocol calls'],
                'correct'  => 0,
            ],
            [
                'question' => 'When are Swift value types heap-allocated?',
                'options'  => ['When they contain reference-type fields or are too large to fit in a fixed-size allocation', 'Always on the heap', 'Based on a runtime decision', 'Always on the stack with no exceptions'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is copy-on-write (COW) semantics in Swift?',
                'options'  => ['Multiple value-type instances share underlying storage until one is mutated, triggering a copy', 'A copy is always made on every assignment', 'A copy is made on every read', 'Swift has no copy-on-write semantics'],
                'correct'  => 0,
            ],
            [
                'question' => 'How do you implement copy-on-write for a custom Swift value type?',
                'options'  => ['Wrap mutable state in a class buffer and check isKnownUniquelyReferenced before mutating', 'Apply the @cow attribute to the struct', 'Conform to the ValueSemantics protocol', 'Swift handles it automatically for all structs'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does a type descriptor contain in the Swift runtime?',
                'options'  => ['Metadata about a type\'s name, fields, conformances, and generic parameters', 'Only the type\'s name as a string', 'A reflection Mirror object', 'The type\'s memory layout only'],
                'correct'  => 0,
            ],
            [
                'question' => 'Why do @escaping closures that capture variables require heap allocation?',
                'options'  => ['The captured variables must outlive the stack frame, so the capture list is heap-allocated', 'All closures always allocate on the heap', 'There is no overhead; they use the stack', 'Closures always allocate based on capture count'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does withTaskCancellationHandler(operation:onCancel:) do?',
                'options'  => ['Registers a handler that is called immediately when the current Task is cancelled', 'Cancels a task and awaits its termination', 'Checks the current task\'s cancellation status', 'Handles errors thrown by cancelled tasks'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the Clock protocol introduced in Swift 5.7?',
                'options'  => ['A protocol abstracting time sources, used with Task.sleep(until:clock:)', 'A concrete Date-based timer type', 'A DispatchSourceTimer wrapper', 'A wall-clock measurement struct'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is AsyncStream in Swift?',
                'options'  => ['An AsyncSequence that bridges synchronous or callback-based producers to async consumers', 'An async wrapper around FileHandle streams', 'A network byte stream', 'A lazy sequence over arrays'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does the @_marker protocol attribute indicate?',
                'options'  => ['The protocol is a marker with no runtime representation or witness table overhead', 'The protocol is deprecated', 'The protocol adds documentation markers', 'The protocol enables retroactive conformance'],
                'correct'  => 0,
            ],
        ],
    ],
];
require_once __DIR__ . '/quiz-engine.php';
