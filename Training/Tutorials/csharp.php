<?php
$tutorial_title = 'C#';
$tutorial_slug  = 'csharp';
$quiz_slug      = 'csharp';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>C# (pronounced "C-sharp") is a modern, statically typed, object-oriented language designed by Anders Hejlsberg at Microsoft and first released in 2000 as part of .NET. It draws inspiration from C++, Java, and Delphi, but has evolved its own distinctive features — nullable reference types, pattern matching, records, and LINQ — that make it one of the most expressive languages in widespread use. C# powers Windows desktop apps, ASP.NET web servers, Unity games, and Azure cloud services.</p>',
        'concepts' => [
            '.NET ecosystem: .NET SDK, C# compiler (Roslyn), runtime (CoreCLR)',
            'Top-level programs (C# 9+): no boilerplate class/Main required',
            'Type system: value types (struct, enum) vs. reference types (class, interface)',
            'Nullable reference types: string? vs. string, null-forgiving operator !',
            'String interpolation: $"Hello, {name}!"; raw string literals (C# 11)',
            'Control flow: if/else, switch expression (C# 8+), for, foreach, while',
            'LINQ basics: query syntax vs. method syntax, deferred execution',
        ],
        'code' => [
            'title'   => 'C# records and pattern matching',
            'lang'    => 'csharp',
            'content' =>
'// Immutable record with value equality
public record Point(double X, double Y)
{
    public double DistanceTo(Point other) =>
        Math.Sqrt(Math.Pow(X - other.X, 2) + Math.Pow(Y - other.Y, 2));
}

// Discriminated union via sealed hierarchy + pattern matching
public abstract record Shape;
public record Circle(Point Centre, double Radius) : Shape;
public record Rect(Point TopLeft, Point BottomRight) : Shape;

double Area(Shape shape) => shape switch
{
    Circle c            => Math.PI * c.Radius * c.Radius,
    Rect { TopLeft: var tl, BottomRight: var br }
                        => Math.Abs(br.X - tl.X) * Math.Abs(br.Y - tl.Y),
    _                   => throw new ArgumentOutOfRangeException(nameof(shape))
};',
        ],
        'tips' => [
            'Enable <Nullable>enable</Nullable> in your .csproj — nullable reference types catch null bugs at compile time.',
            'Use records for all immutable data transfer objects — they generate Equals, GetHashCode, and ToString.',
            'Switch expressions (C# 8+) are exhaustive and expression-oriented; prefer them over switch statements.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>LINQ (Language Integrated Query) is C#\'s most distinctive feature — it brings SQL-like query operations (where, select, groupby, orderby, join) into the language itself and works over any IEnumerable<T>, including arrays, lists, databases (EF Core), XML, and JSON. Understanding deferred execution and how LINQ translates to expression trees for database providers separates proficient from expert C# developers.</p><p>Async/await — C#\'s first-class asynchronous programming model — makes I/O-bound operations non-blocking without the complexity of callbacks or reactive streams.</p>',
        'concepts' => [
            'LINQ method syntax: Where, Select, OrderBy, GroupBy, Join, SelectMany, Aggregate',
            'LINQ deferred execution: query objects vs. materialised results (ToList, ToArray, Count)',
            'IEnumerable<T> vs. IQueryable<T>: in-memory vs. translated-to-SQL',
            'async / await: Task<T>, Task, ValueTask<T>, ConfigureAwait(false)',
            'Task.WhenAll, Task.WhenAny for concurrent async operations',
            'CancellationToken: cooperative cancellation across async call chains',
            'IAsyncEnumerable<T> and await foreach for async streams',
        ],
        'code' => [
            'title'   => 'LINQ query over a collection',
            'lang'    => 'csharp',
            'content' =>
'var employees = new List<Employee>
{
    new("Alice",  "Engineering", 95_000),
    new("Bob",    "Marketing",   72_000),
    new("Carol",  "Engineering", 105_000),
    new("David",  "Marketing",   68_000),
};

// Average salary by department, sorted by average descending
var stats = employees
    .GroupBy(e => e.Department)
    .Select(g => new
    {
        Department = g.Key,
        Average    = g.Average(e => e.Salary),
        Count      = g.Count(),
        Top        = g.MaxBy(e => e.Salary)?.Name,
    })
    .OrderByDescending(x => x.Average)
    .ToList();

foreach (var s in stats)
    Console.WriteLine($"{s.Department}: avg={s.Average:C0}, top={s.Top}");',
        ],
        'tips' => [
            'Use ConfigureAwait(false) in library code to avoid context capture overhead.',
            'Pass CancellationToken through every async call chain — it is cheap to add early and expensive to retrofit.',
            'Never call .Result or .Wait() on a Task in async contexts — it causes deadlocks in synchronisation contexts.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>C# interfaces — enhanced in C# 8 with default interface methods — and generic constraints enable flexible, testable architectures. Dependency injection is the standard composition pattern in .NET: Microsoft.Extensions.DependencyInjection is built into ASP.NET Core and also usable in console applications, background services, and Blazor.</p><p>Exception handling in C# benefits from exception filters (when clause), custom exception types, and the global exception handling patterns provided by IExceptionHandler (ASP.NET Core 8+) and Application.DispatcherUnhandledException.</p>',
        'concepts' => [
            'Generics: type constraints (where T : IComparable<T>, new(), class, struct)',
            'Covariance and contravariance: IEnumerable<out T>, Action<in T>',
            'Extension methods: static class, static method with this T parameter',
            'Delegates, Func<>, Action<>, Predicate<>, and multicast delegates',
            'Events: event keyword, EventArgs, += / -= subscription',
            'Microsoft.Extensions.DependencyInjection: AddScoped/AddSingleton/AddTransient',
            'IOptions<T> pattern for strongly typed configuration',
        ],
        'code' => [
            'title'   => 'Generic repository pattern with DI',
            'lang'    => 'csharp',
            'content' =>
'public interface IRepository<T, TId> where T : class
{
    Task<T?> GetByIdAsync(TId id, CancellationToken ct = default);
    Task<IReadOnlyList<T>> GetAllAsync(CancellationToken ct = default);
    Task<T> AddAsync(T entity, CancellationToken ct = default);
    Task UpdateAsync(T entity, CancellationToken ct = default);
    Task DeleteAsync(TId id, CancellationToken ct = default);
}

public class EfRepository<T>(AppDbContext db) : IRepository<T, int>
    where T : class, IEntity
{
    public Task<T?> GetByIdAsync(int id, CancellationToken ct = default)
        => db.Set<T>().FirstOrDefaultAsync(e => e.Id == id, ct);

    public async Task<IReadOnlyList<T>> GetAllAsync(CancellationToken ct = default)
        => await db.Set<T>().AsNoTracking().ToListAsync(ct);

    public async Task<T> AddAsync(T entity, CancellationToken ct = default)
    {
        db.Set<T>().Add(entity);
        await db.SaveChangesAsync(ct);
        return entity;
    }
    // ... UpdateAsync, DeleteAsync
}',
        ],
        'tips' => [
            'Prefer IReadOnlyList<T> over List<T> in return types to communicate immutability intent.',
            'Use primary constructors (C# 12) to reduce DI boilerplate: class Service(IRepo repo).',
            'Register generic repositories with open generic registration: services.AddScoped(typeof(IRepository<,>), typeof(EfRepository<>));',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Source generators (Roslyn) run during compilation to generate C# code from metadata, driving zero-overhead serialisation (System.Text.Json source gen), DI registration, and pattern-driven code. Roslyn analyzers enforce custom coding standards at compile time. Understanding the Roslyn compilation pipeline — syntax trees, semantic models, and ISymbol — opens the door to powerful custom tooling.</p><p>Span<T> and Memory<T> enable zero-copy buffer manipulation — slicing large byte arrays without allocation — critical for high-throughput networking and parsing workloads.</p>',
        'concepts' => [
            'Span<T> and Memory<T>: stack-allocated, zero-copy buffer views',
            'ArrayPool<T> and MemoryPool<T> for reducing GC pressure',
            'System.Text.Json source generation for AOT-safe serialisation',
            'Roslyn analyzers and code fixes: DiagnosticAnalyzer, CodeFixProvider',
            'Incremental source generators: IIncrementalGenerator',
            'High-performance patterns: ref struct, stackalloc, unmanaged constraints',
            'System.IO.Pipelines for back-pressure-aware I/O processing',
        ],
        'code' => [
            'title'   => 'Span<T> for zero-copy CSV parsing',
            'lang'    => 'csharp',
            'content' =>
'public static IEnumerable<(string Name, int Score)> ParseCsv(ReadOnlySpan<char> input)
{
    while (!input.IsEmpty)
    {
        // Find end of line
        int lineEnd = input.IndexOf(\'\n\');
        var line    = lineEnd >= 0 ? input[..lineEnd] : input;
        input       = lineEnd >= 0 ? input[(lineEnd + 1)..] : ReadOnlySpan<char>.Empty;

        // Split on comma
        int comma = line.IndexOf(\',\');
        if (comma < 0) continue;

        var name  = line[..comma].Trim();
        var score = line[(comma + 1)..].Trim();

        if (int.TryParse(score, out int s))
            yield return (name.ToString(), s);
    }
}

// Zero allocations for the spans themselves — only ToString() allocates
// Usage: foreach (var (name, score) in ParseCsv(fileContent)) { ... }',
        ],
        'tips' => [
            'Use Span<T> for parsing hot paths — it eliminates intermediate string allocations entirely.',
            'Write Roslyn analyzers for team-specific conventions — they catch issues at compile time, not code review.',
            'Use ArrayPool<T>.Shared to rent temporary buffers in hot loops instead of allocating new arrays.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert C# engineering involves native AOT compilation (dotnet publish --aot) for minimal startup time in serverless and CLI scenarios, writing unsafe code with raw pointers for interoperability with native libraries, and deep .NET runtime diagnostics — EventPipe, dotnet-trace, and the managed heap object graph.</p><p>Contributing to the C# language specification (github.com/dotnet/csharplang), reviewing LDM (Language Design Meeting) notes, and writing compelling language feature proposals represent the community leadership dimension of expert C# mastery. The ongoing C# evolution — discriminated unions, roles/extensions, field keyword for auto-properties — shapes the language for millions of developers.</p>',
        'concepts' => [
            'Native AOT: trimming, ahead-of-time compilation, reflection limitations',
            'unsafe code: fixed, stackalloc, pointer arithmetic, P/Invoke',
            'P/Invoke and LibraryImport (C# 10 source-generated interop)',
            'EventPipe and dotnet-trace for production profiling',
            'Managed heap analysis: dotnet-dump, SOS commands, object reference graphs',
            'C# language specification: ECMA-334 and the roslyn/csharplang design process',
            'Upcoming C# features: discriminated unions, roles/extensions, field keyword',
        ],
        'code' => [
            'title'   => 'Native AOT-compatible source-generated JSON',
            'lang'    => 'csharp',
            'content' =>
'using System.Text.Json;
using System.Text.Json.Serialization;

// Source-generated serialisation context — AOT compatible
[JsonSerializable(typeof(UserDto))]
[JsonSerializable(typeof(List<UserDto>))]
[JsonSourceGenerationOptions(
    PropertyNamingPolicy = JsonKnownNamingPolicy.CamelCase,
    WriteIndented = false)]
internal partial class AppJsonContext : JsonSerializerContext {}

public record UserDto(int Id, string Name, string Email);

// Usage:
var json = JsonSerializer.Serialize(
    new UserDto(1, "Alice", "alice@example.com"),
    AppJsonContext.Default.UserDto);

var user = JsonSerializer.Deserialize(
    json,
    AppJsonContext.Default.UserDto);',
        ],
        'tips' => [
            'Enable trimming analysis with <EnableTrimAnalyzer>true</EnableTrimAnalyzer> — it surfaces AOT incompatibilities early.',
            'Use Source Generated JSON for all hot serialisation paths — it is ~3× faster than reflection-based.',
            'Follow the dotnet/csharplang GitHub repo and LDM notes to understand the long-term language direction.',
            'Read "C# in Depth" by Jon Skeet (4th ed.) for an authoritative deep-dive into every C# version.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
