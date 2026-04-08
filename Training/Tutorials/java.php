<?php
$tutorial_title = 'Java';
$tutorial_slug  = 'java';
$quiz_slug      = 'java';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Java is a statically typed, object-oriented, platform-independent programming language first released by Sun Microsystems in 1995. Its "Write Once, Run Anywhere" promise is delivered by the Java Virtual Machine (JVM), which executes compiled bytecode on any platform. Java powers Android apps, enterprise backends (Spring, Quarkus), big data systems (Hadoop, Spark), and has been the most widely used language in enterprise software for decades.</p><p>This tier introduces Java syntax, primitive types, the class structure, and compiling and running your first programs.</p>',
        'concepts' => [
            'JDK installation: java, javac, JVM vs. JDK vs. JRE',
            'Java syntax: statements, blocks, semicolons, case sensitivity',
            'Primitive types: int, long, double, boolean, char, byte, short, float',
            'Reference types: String, arrays, object references',
            'Operators: arithmetic, logical, bitwise, ternary',
            'Control flow: if/else, switch expression (Java 14+), for, while, do-while',
            'Methods: return types, parameters, overloading',
        ],
        'code' => [
            'title'   => 'Java basics — switch expression and methods',
            'lang'    => 'java',
            'content' =>
'public class FizzBuzz {

    public static String label(int n) {
        return switch (0) {
            case 0 when n % 15 == 0 -> "FizzBuzz";
            case 0 when n % 3  == 0 -> "Fizz";
            case 0 when n % 5  == 0 -> "Buzz";
            default                 -> String.valueOf(n);
        };
    }

    public static void main(String[] args) {
        for (int i = 1; i <= 30; i++) {
            System.out.println(label(i));
        }
    }
}',
        ],
        'tips' => [
            'Use var (Java 10+) for local variable type inference to reduce verbosity: var list = new ArrayList<String>();',
            'Prefer the switch expression (Java 14+) over switch statement — it is exhaustive and expression-oriented.',
            'Install via SDKMAN (sdkman.io) to manage multiple JDK versions on your machine.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Java\'s object-oriented model — encapsulation, inheritance, polymorphism, and abstraction — is expressed through classes, interfaces, abstract classes, and the access modifier system. Records (introduced in Java 16) provide immutable data classes with minimal boilerplate, replacing the pattern of verbose POJOs.</p><p>Exception handling with checked and unchecked exceptions, the try-with-resources statement for auto-closing resources, and the Collections framework (List, Set, Map, Queue) are everyday Java tools.</p>',
        'concepts' => [
            'Classes: fields, constructors, methods, access modifiers (public/private/protected)',
            'Records: public record Point(int x, int y) {} for immutable data',
            'Sealed classes and interfaces (Java 17+): permits keyword',
            'Interfaces: default methods, static methods, functional interfaces',
            'Inheritance: extends, super, @Override, abstract classes',
            'Collections: ArrayList, LinkedList, HashMap, HashSet, TreeMap, PriorityQueue',
            'Exception handling: try-catch-finally, try-with-resources, checked vs. unchecked',
        ],
        'code' => [
            'title'   => 'Java record and sealed interface',
            'lang'    => 'java',
            'content' =>
'// Sealed interface — only these implementations are permitted
public sealed interface Shape permits Circle, Rectangle, Triangle {}

public record Circle(double radius) implements Shape {
    public Circle {
        if (radius <= 0) throw new IllegalArgumentException("Radius must be positive");
    }
    public double area() { return Math.PI * radius * radius; }
}

public record Rectangle(double width, double height) implements Shape {
    public double area() { return width * height; }
}

// Pattern matching switch (Java 21)
public static double areaOf(Shape shape) {
    return switch (shape) {
        case Circle    c -> c.area();
        case Rectangle r -> r.area();
        case Triangle  t -> t.area();
    };
}',
        ],
        'tips' => [
            'Use records for all simple data-carrying classes — they generate equals, hashCode, and toString for free.',
            'Prefer interfaces over abstract classes when you can — they support multiple implementation.',
            'Use try-with-resources for all Closeable resources (files, streams, DB connections) to prevent leaks.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Java Generics enable type-safe containers and algorithms without casting. The Stream API (Java 8+) brings functional-style data processing with filter, map, reduce, collect, and flatMap operations. Optional<T> eliminates null pointer exceptions for potentially absent values.</p><p>Concurrency in Java — threads, ExecutorService, CompletableFuture, and the synchronized keyword — enables parallel workloads. The java.time API (Java 8+) replaces the error-prone Date/Calendar with an immutable, timezone-aware date/time model.</p>',
        'concepts' => [
            'Generics: <T>, bounded wildcards <? extends T>, <? super T>',
            'Stream API: stream(), filter(), map(), flatMap(), sorted(), collect(), reduce()',
            'Collectors: toList(), groupingBy(), partitioningBy(), joining()',
            'Optional<T>: of(), ofNullable(), orElse(), orElseThrow(), map(), flatMap()',
            'CompletableFuture: supplyAsync(), thenApply(), thenCompose(), allOf()',
            'java.time: LocalDate, LocalDateTime, ZonedDateTime, Duration, Period',
            'Lambdas and method references: ClassName::methodName, this::method',
        ],
        'code' => [
            'title'   => 'Stream API with collectors',
            'lang'    => 'java',
            'content' =>
'import java.util.*;
import java.util.stream.*;

record Employee(String name, String dept, double salary) {}

class Analysis {
    static void analyze(List<Employee> employees) {
        // Average salary by department
        Map<String, Double> avgByDept = employees.stream()
            .collect(Collectors.groupingBy(
                Employee::dept,
                Collectors.averagingDouble(Employee::salary)
            ));

        // Top earner per department
        Map<String, Optional<Employee>> topEarner = employees.stream()
            .collect(Collectors.groupingBy(
                Employee::dept,
                Collectors.maxBy(Comparator.comparingDouble(Employee::salary))
            ));

        // Employees earning above average
        double avg = employees.stream().mapToDouble(Employee::salary).average().orElse(0);
        List<String> aboveAvg = employees.stream()
            .filter(e -> e.salary() > avg)
            .map(Employee::name)
            .sorted()
            .toList(); // Java 16+
    }
}',
        ],
        'tips' => [
            'Use .toList() (Java 16+) instead of .collect(Collectors.toList()) — it is terser and returns an unmodifiable list.',
            'CompletableFuture.allOf() runs independent tasks in parallel — use it to fan out and then join results.',
            'Always specify a timezone when creating ZonedDateTime — UTC is the safe default for storage.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Spring Boot is the most popular Java application framework, providing auto-configuration, embedded servers, and starters for every infrastructure concern. Spring\'s dependency injection, Spring Data JPA for ORM, Spring Security for authentication and authorization, and Spring Web MVC (or WebFlux for reactive) form the standard Java enterprise stack.</p><p>Java virtual threads (Project Loom, Java 21) dramatically change the concurrency model: instead of 200 platform threads, you can spawn millions of virtual threads and write blocking-style async code without callbacks or reactive plumbing.</p>',
        'concepts' => [
            'Spring Boot: @SpringBootApplication, auto-configuration, starters, application.properties',
            'Spring MVC: @RestController, @GetMapping, @RequestBody, ResponseEntity<T>',
            'Spring Data JPA: JpaRepository, derived query methods, @Query, Specification',
            'Spring Security: SecurityFilterChain, authentication, authorisation, method security',
            'Spring Validation: @Valid, @NotNull, ConstraintViolationException, @ExceptionHandler',
            'Virtual threads (Java 21): Thread.ofVirtual().start(), spring.threads.virtual.enabled',
            'Reactive Spring WebFlux: Mono<T>, Flux<T>, R2DBC for reactive database access',
        ],
        'code' => [
            'title'   => 'Spring Boot REST controller',
            'lang'    => 'java',
            'content' =>
'@RestController
@RequestMapping("/api/v1/users")
@RequiredArgsConstructor
public class UserController {

    private final UserService userService;

    @GetMapping
    public Page<UserDto> list(
            @RequestParam(defaultValue = "0")  int page,
            @RequestParam(defaultValue = "20") int size) {
        return userService.findAll(PageRequest.of(page, size));
    }

    @GetMapping("/{id}")
    public ResponseEntity<UserDto> get(@PathVariable Long id) {
        return userService.findById(id)
                .map(ResponseEntity::ok)
                .orElse(ResponseEntity.notFound().build());
    }

    @PostMapping
    @ResponseStatus(HttpStatus.CREATED)
    public UserDto create(@Valid @RequestBody CreateUserRequest request) {
        return userService.create(request);
    }

    @ExceptionHandler(ConstraintViolationException.class)
    @ResponseStatus(HttpStatus.BAD_REQUEST)
    public Map<String, String> handleValidation(ConstraintViolationException ex) {
        return ex.getConstraintViolations().stream()
            .collect(toMap(v -> v.getPropertyPath().toString(), v -> v.getMessage()));
    }
}',
        ],
        'tips' => [
            'Enable virtual threads in Spring Boot 3.2+ with spring.threads.virtual.enabled=true — it is a one-liner.',
            'Use Spring Data JPA Specifications for dynamic query building instead of string concatenation.',
            'Write integration tests with @SpringBootTest and Testcontainers for realistic database testing.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Java engineering involves JVM performance tuning — heap sizing, garbage collector selection (G1, ZGC, Shenandoah), and JVM flags for throughput vs. latency tradeoffs — alongside deep profiling with async-profiler and JFR (Java Flight Recorder) for production incident analysis.</p><p>GraalVM Native Image compiles Java to a native binary with near-instant startup and low memory footprint, enabling Java in serverless and CLI tool scenarios. The Java module system (JPMS, Project Jigsaw) and contributing to OpenJDK complete the expert Java developer\'s journey.</p>',
        'concepts' => [
            'GC tuning: G1GC, ZGC, Shenandoah — throughput vs. pause time tradeoffs',
            'JVM flags: -Xmx, -Xms, -XX:+UseZGC, GC logging',
            'Java Flight Recorder (JFR) and JDK Mission Control (JMC)',
            'async-profiler: CPU and allocation flame graphs for production profiling',
            'GraalVM Native Image: native-image CLI, reflection configuration, Quarkus/Micronaut AOT',
            'Java Module System (JPMS): module-info.java, requires, exports',
            'Pattern matching and preview features: Java 21 language features',
            'Contributing to OpenJDK: the JDK contribution guide, JBS bug tracker',
        ],
        'code' => [
            'title'   => 'Java 21 pattern matching switch',
            'lang'    => 'java',
            'content' =>
'// Java 21 pattern matching in switch — exhaustive on sealed types
sealed interface JsonValue permits JsonNull, JsonBool, JsonNumber, JsonString, JsonArray {}

record JsonNull()             implements JsonValue {}
record JsonBool(boolean v)    implements JsonValue {}
record JsonNumber(double v)   implements JsonValue {}
record JsonString(String v)   implements JsonValue {}
record JsonArray(List<JsonValue> items) implements JsonValue {}

public static String prettyPrint(JsonValue val) {
    return switch (val) {
        case JsonNull   __   -> "null";
        case JsonBool   b    -> String.valueOf(b.v());
        case JsonNumber n    -> n.v() % 1 == 0 ? String.valueOf((long) n.v()) : String.valueOf(n.v());
        case JsonString s    -> "\\"" + s.v() + "\\"";
        case JsonArray  arr  -> "[" + arr.items().stream().map(Analysis::prettyPrint).collect(joining(", ")) + "]";
    };
}',
        ],
        'tips' => [
            'Use ZGC or Shenandoah for latency-sensitive services — they keep GC pauses under 1 ms even on large heaps.',
            'Record a JFR profile in production with jcmd <pid> JFR.start — it has negligible overhead.',
            'GraalVM Native Image dramatically reduces Lambda cold-start times — ideal for serverless Spring/Quarkus.',
            'Follow the JEP (Java Enhancement Proposal) list at openjdk.org to track upcoming language changes.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
