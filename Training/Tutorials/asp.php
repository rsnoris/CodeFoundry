<?php
$tutorial_title = 'ASP.NET';
$tutorial_slug  = 'asp';
$quiz_slug      = 'asp';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>ASP.NET Core is Microsoft\'s open-source, cross-platform framework for building web applications and APIs with C#. It runs on .NET — a high-performance runtime available on Windows, macOS, and Linux. ASP.NET Core powers high-traffic services at Microsoft, Stack Overflow, and thousands of enterprises. Its request pipeline, dependency injection, and Kestrel web server are designed for speed and scalability.</p><p>This tier introduces the ASP.NET Core project structure, the minimal API style (introduced in .NET 6), and the fundamental concepts of the request pipeline.</p>',
        'concepts' => [
            '.NET CLI: dotnet new webapi, dotnet run, dotnet build, dotnet publish',
            'Program.cs: top-level program model, WebApplication.CreateBuilder()',
            'Minimal APIs: app.MapGet/MapPost/MapPut/MapDelete()',
            'Controller-based APIs: [ApiController], [Route], ActionResult<T>',
            'appsettings.json and environment-specific appsettings.{env}.json',
            'Dependency injection built-in: AddSingleton, AddScoped, AddTransient',
            'Kestrel web server and reverse proxy deployment (Nginx, IIS)',
        ],
        'code' => [
            'title'   => 'Minimal API with dependency injection',
            'lang'    => 'csharp',
            'content' =>
'var builder = WebApplication.CreateBuilder(args);

builder.Services.AddScoped<IUserRepository, UserRepository>();
builder.Services.AddScoped<IUserService, UserService>();

var app = builder.Build();

app.MapGet("/api/users", async (IUserService svc) =>
{
    var users = await svc.GetAllAsync();
    return Results.Ok(users);
});

app.MapGet("/api/users/{id:int}", async (int id, IUserService svc) =>
{
    var user = await svc.GetByIdAsync(id);
    return user is null ? Results.NotFound() : Results.Ok(user);
});

app.MapPost("/api/users", async (CreateUserRequest req, IUserService svc) =>
{
    var user = await svc.CreateAsync(req);
    return Results.Created($"/api/users/{user.Id}", user);
});

app.Run();',
        ],
        'tips' => [
            'Use minimal APIs for microservices and simple CRUD — they have less boilerplate than controllers.',
            'Use controller-based APIs for large projects that benefit from filters, action conventions, and grouping.',
            'Always use the built-in DI container — constructor injection makes testing with mocks straightforward.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Entity Framework Core is .NET\'s ORM. Code-first migrations let you define your database schema in C# model classes and generate SQL migrations automatically. LINQ (Language Integrated Query) provides a type-safe, composable query syntax that compiles to SQL through EF Core\'s query provider.</p><p>Data annotations and FluentValidation provide two approaches to model validation. ASP.NET Core\'s model binding automatically maps HTTP request data (query strings, route parameters, request body) to C# types, with automatic validation and 400 responses for invalid input.</p>',
        'concepts' => [
            'EF Core: DbContext, DbSet<T>, OnModelCreating, migrations',
            'LINQ queries: Where, Select, OrderBy, Include (eager loading), FirstOrDefaultAsync',
            'Data annotations: [Required], [MaxLength], [EmailAddress], [Range]',
            'FluentValidation: AbstractValidator<T>, RuleFor().NotEmpty().MaximumLength()',
            'Model binding: [FromRoute], [FromQuery], [FromBody], [FromForm]',
            'Automatic 400 validation: [ApiController] attribute triggers ProblemDetails',
            'DTOs: separating API contracts from domain models with AutoMapper',
        ],
        'code' => [
            'title'   => 'EF Core model and LINQ query',
            'lang'    => 'csharp',
            'content' =>
'// Model
public class Post
{
    public int     Id        { get; set; }
    [Required, MaxLength(200)]
    public string  Title     { get; set; } = string.Empty;
    public string  Body      { get; set; } = string.Empty;
    public bool    Published { get; set; }
    public DateTime CreatedAt { get; set; }
    public int     AuthorId  { get; set; }
    public User    Author    { get; set; } = null!;
}

// DbContext
public class AppDbContext(DbContextOptions<AppDbContext> options) : DbContext(options)
{
    public DbSet<Post> Posts { get; set; }
    public DbSet<User> Users { get; set; }
}

// Repository method
public async Task<IEnumerable<Post>> GetPublishedAsync(int page, int size)
    => await _context.Posts
        .Where(p => p.Published)
        .Include(p => p.Author)
        .OrderByDescending(p => p.CreatedAt)
        .Skip((page - 1) * size)
        .Take(size)
        .ToListAsync();',
        ],
        'tips' => [
            'Always call AsNoTracking() on read-only queries — it skips the change tracker and improves performance.',
            'Use Include() (eager loading) for related data you know you will need, to avoid N+1 queries.',
            'Run dotnet ef migrations add <Name> && dotnet ef database update to apply schema changes.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Middleware is the core extensibility mechanism of ASP.NET Core. Each middleware component can inspect, short-circuit, or modify the request and response. Authentication, authorisation, logging, exception handling, and rate limiting are all implemented as middleware. Understanding the order of middleware registration is critical — placing exception handling first and auth before endpoint execution.</p><p>ASP.NET Core\'s authentication system supports JWTs, cookies, OpenID Connect (via Azure AD, Auth0), and API keys through a provider model. Role-based and policy-based authorisation let you express access rules declaratively.</p>',
        'concepts' => [
            'Middleware pipeline: app.Use, app.Run, app.Map, app.UseMiddleware<T>',
            'Exception handling middleware: UseExceptionHandler, ProblemDetails',
            'JWT authentication: AddAuthentication().AddJwtBearer(), JwtBearerOptions',
            'Authorization: [Authorize], [AllowAnonymous], roles, policies, requirements',
            'Custom authorization policy: IAuthorizationRequirement, IAuthorizationHandler',
            'CORS middleware: AddCors, UseCors, policy configuration',
            'Rate limiting middleware (.NET 7+): AddRateLimiter, fixed/sliding/token policies',
        ],
        'code' => [
            'title'   => 'JWT authentication setup',
            'lang'    => 'csharp',
            'content' =>
'builder.Services
    .AddAuthentication(JwtBearerDefaults.AuthenticationScheme)
    .AddJwtBearer(options =>
    {
        options.TokenValidationParameters = new TokenValidationParameters
        {
            ValidateIssuer           = true,
            ValidIssuer              = builder.Configuration["Jwt:Issuer"],
            ValidateAudience         = true,
            ValidAudience            = builder.Configuration["Jwt:Audience"],
            ValidateLifetime         = true,
            ValidateIssuerSigningKey = true,
            IssuerSigningKey         = new SymmetricSecurityKey(
                Encoding.UTF8.GetBytes(builder.Configuration["Jwt:Secret"]!))
        };
    });

builder.Services.AddAuthorization(options =>
{
    options.AddPolicy("AdminOnly", p => p.RequireRole("Admin"));
    options.AddPolicy("MinAge18",  p => p.Requirements.Add(new MinAgeRequirement(18)));
});

// In endpoint:
app.MapGet("/admin/dashboard", [Authorize("AdminOnly")] async () => { ... });',
        ],
        'tips' => [
            'Store JWT secrets in User Secrets (dotnet user-secrets) during development, not appsettings.json.',
            'Use policy-based authorization over role-based for complex rules — policies are composable and testable.',
            'Register UseAuthentication() before UseAuthorization() in the middleware pipeline — order matters.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>SignalR provides real-time communication over WebSocket (with SSE and long-polling fallbacks) with a hub-based API. Background services (<code>IHostedService</code>, <code>BackgroundService</code>) run long-lived tasks alongside the web server. Health checks, structured logging with Serilog, and distributed caching with Redis round out the production backend toolkit.</p><p>ASP.NET Core\'s performance is exceptional — Kestrel regularly tops TechEmpower benchmarks. Understanding response caching, output caching (.NET 7+), and connection pooling (EF Core connection resilience, Polly for retries) maximises throughput and reliability.</p>',
        'concepts' => [
            'SignalR: Hubs, client methods, groups, connection lifecycle',
            'BackgroundService: ExecuteAsync, IHostedService, Hosted Service DI registration',
            'Health checks: AddHealthChecks, MapHealthChecks, custom health checks',
            'Serilog: structured logging, sinks (console, file, Seq), enrichers',
            'Output caching (.NET 7+): AddOutputCache, [OutputCache] attribute, cache tags',
            'Redis distributed cache: AddStackExchangeRedisCache, IDistributedCache',
            'Polly: retry, circuit breaker, timeout, bulkhead isolation policies',
        ],
        'code' => [
            'title'   => 'BackgroundService example',
            'lang'    => 'csharp',
            'content' =>
'public class EmailQueueProcessor(
    IServiceScopeFactory scopeFactory,
    ILogger<EmailQueueProcessor> logger
) : BackgroundService
{
    protected override async Task ExecuteAsync(CancellationToken stoppingToken)
    {
        logger.LogInformation("Email processor started");

        while (!stoppingToken.IsCancellationRequested)
        {
            try
            {
                using var scope = scopeFactory.CreateScope();
                var queue = scope.ServiceProvider.GetRequiredService<IEmailQueue>();
                await queue.ProcessPendingAsync(stoppingToken);
            }
            catch (Exception ex) when (ex is not OperationCanceledException)
            {
                logger.LogError(ex, "Error processing email queue");
            }

            await Task.Delay(TimeSpan.FromSeconds(30), stoppingToken);
        }
    }
}

// Register: builder.Services.AddHostedService<EmailQueueProcessor>();',
        ],
        'tips' => [
            'Use IServiceScopeFactory in BackgroundService — the service itself is singleton but scoped services need a scope.',
            'Add health checks for every external dependency (database, Redis, message queue) used by your service.',
            'Configure Polly retry policies for all outbound HTTP calls — transient failures are inevitable in production.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert ASP.NET Core development covers gRPC services with Protobuf serialisation, Native AOT compilation for ultra-fast startup times in serverless and container environments, the .NET Aspire framework for distributed application orchestration, and deep diagnostic tooling — dotnet-trace, dotnet-dump, and PerfView — for production incident analysis.</p><p>Designing multi-tenant SaaS applications on ASP.NET Core, contributing to the ASP.NET Core open-source codebase, and navigating the long-term .NET roadmap mark the expert practitioner who builds and maintains platform-level backend systems.</p>',
        'concepts' => [
            'gRPC with ASP.NET Core: .proto files, MapGrpcService, client factory',
            'Native AOT: publishing with dotnet publish -r linux-x64 --aot, trimming',
            '.NET Aspire: AppHost orchestration, service defaults, telemetry',
            'Blazor Server and Blazor WebAssembly for C# in the browser',
            'OpenTelemetry in .NET: AddOpenTelemetry, OTLP exporter',
            'dotnet-trace, dotnet-counters, dotnet-dump for production diagnostics',
            'Multi-tenancy in ASP.NET Core: per-tenant DI, request resolution strategies',
            'Source generators and Roslyn analyzers for ASP.NET Core tooling',
        ],
        'code' => [
            'title'   => 'gRPC service definition and implementation',
            'lang'    => 'csharp',
            'content' =>
'// users.proto
// syntax = "proto3";
// service UserService {
//   rpc GetUser (GetUserRequest) returns (UserResponse);
//   rpc ListUsers (ListUsersRequest) returns (stream UserResponse);
// }

// C# implementation
public class UserGrpcService(IUserService userService) : UserService.UserServiceBase
{
    public override async Task<UserResponse> GetUser(
        GetUserRequest request, ServerCallContext context)
    {
        var user = await userService.GetByIdAsync(request.Id)
            ?? throw new RpcException(new Status(StatusCode.NotFound, "User not found"));

        return new UserResponse { Id = user.Id, Name = user.Name, Email = user.Email };
    }

    public override async Task ListUsers(
        ListUsersRequest request,
        IServerStreamWriter<UserResponse> responseStream,
        ServerCallContext context)
    {
        await foreach (var user in userService.StreamAllAsync(context.CancellationToken))
        {
            await responseStream.WriteAsync(
                new UserResponse { Id = user.Id, Name = user.Name, Email = user.Email });
        }
    }
}',
        ],
        'tips' => [
            'Use .NET Aspire for new distributed applications — it dramatically simplifies local orchestration and telemetry.',
            'Profile with dotnet-counters live monitor before reaching for full heap dumps in production.',
            'Follow the ASP.NET Core GitHub repo (dotnet/aspnetcore) for feature previews and breaking change notices.',
            'Read the .NET performance blog (devblogs.microsoft.com/dotnet) for deep-dive benchmark analysis.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
