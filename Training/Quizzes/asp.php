<?php
$page_title = 'ASP Quiz – 100 Levels – CodeFoundry';
$active_page = 'training';
$quiz_title  = 'ASP';
$quiz_slug   = 'asp';
$quiz_tiers  = [
    [
        'label'     => 'Introduction',
        'questions' => [
            [
                'question' => 'What does ASP.NET stand for?',
                'options'  => ['Active Server Pages .NET', 'Application Server Protocol .NET', 'Automated Server Processing .NET', 'Advanced Server Pages .NET'],
                'correct'  => 0,
            ],
            [
                'question' => 'Which company created and maintains ASP.NET?',
                'options'  => ['Oracle', 'Google', 'Microsoft', 'IBM'],
                'correct'  => 2,
            ],
            [
                'question' => 'What is ASP.NET Core?',
                'options'  => ['An upgrade to ASP.NET Web Forms', 'A cross-platform, open-source, high-performance web framework built on .NET Core', 'A server-side scripting language', 'A frontend JavaScript framework'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the main advantage of ASP.NET Core over ASP.NET Framework?',
                'options'  => ['Better Windows-only support', 'Cross-platform support, better performance, and open-source development', 'Requires less code for Web Forms', 'Built-in database engine'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is IIS?',
                'options'  => ['Internet Information Services - a web server for Windows', 'Internet Integration System', 'Internal Information Service', 'IIS Integrated Scripts'],
                'correct'  => 0,
            ],
            [
                'question' => 'What file serves as the entry point for an ASP.NET Core application?',
                'options'  => ['Global.asax', 'Startup.cs (or Program.cs in .NET 6+)', 'Web.config', 'App.config'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between ASP.NET Web Forms and ASP.NET MVC?',
                'options'  => ['Web Forms uses a Model-View-Controller pattern; MVC uses events', 'Web Forms uses event-driven, server-side controls; MVC uses a Model-View-Controller pattern', 'Web Forms is newer than MVC', 'They are identical frameworks'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the .NET CLR?',
                'options'  => ['Common Language Runtime - executes .NET applications', 'Common Language Reference', 'Core Language Runtime', 'Common Level Runtime'],
                'correct'  => 0,
            ],
            [
                'question' => 'What language is primarily used with ASP.NET?',
                'options'  => ['Java', 'Python', 'C#', 'Ruby'],
                'correct'  => 2,
            ],
            [
                'question' => 'What is Kestrel in ASP.NET Core?',
                'options'  => ['A testing framework', 'A cross-platform web server included with ASP.NET Core', 'A configuration library', 'A DI container'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the dotnet CLI command "dotnet new webapp" do?',
                'options'  => ['Creates a new console application', 'Creates a new ASP.NET Core web application project', 'Creates a new Web Forms project', 'Creates a new class library'],
                'correct'  => 1,
            ],
            [
                'question' => 'What file defines project dependencies in an ASP.NET Core project?',
                'options'  => ['packages.json', 'Web.config', 'App.config', '.csproj (C# project file)'],
                'correct'  => 3,
            ],
            [
                'question' => 'What is NuGet in the .NET ecosystem?',
                'options'  => ['A unit testing framework', 'A package manager for .NET libraries and tools', 'A build automation tool', 'A code formatter'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the appsettings.json file store?',
                'options'  => ['C# source code', 'Application configuration settings such as connection strings and app-specific values', 'HTML templates', 'NuGet package list'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the Program.cs file responsible for in .NET 6+ minimal hosting?',
                'options'  => ['Defining models', 'Configuring and starting the web host and middleware pipeline', 'Defining controllers', 'Managing database migrations'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does ASP.NET use to handle HTTP requests?',
                'options'  => ['Event handlers only', 'A request pipeline of middleware components', 'Thread-per-request handlers', 'IIS modules only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is Razor in ASP.NET?',
                'options'  => ['A JavaScript templating engine', 'A markup syntax combining C# with HTML for server-side rendering', 'A CSS preprocessor', 'An ORM for databases'],
                'correct'  => 1,
            ],
            [
                'question' => 'What file extension is used for Razor views in ASP.NET MVC?',
                'options'  => ['.asp', '.cshtml', '.razor', '.aspx'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a Web API in ASP.NET Core?',
                'options'  => ['A web service returning only HTML pages', 'A framework for building HTTP services returning data (JSON/XML) consumed by clients or apps', 'An API for IIS management', 'A Web Forms API layer'],
                'correct'  => 1,
            ],
            [
                'question' => 'What HTTP verb is typically used to retrieve data in a RESTful API?',
                'options'  => ['POST', 'PUT', 'GET', 'DELETE'],
                'correct'  => 2,
            ],
            [
                'question' => 'What does the [HttpGet] attribute do on a controller action?',
                'options'  => ['Maps the action to HTTP GET requests', 'Prevents the action from receiving POST requests only', 'Caches the GET response', 'Validates GET parameters'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is a controller in ASP.NET MVC?',
                'options'  => ['A server configuration file', 'A class that handles HTTP requests, processes input, and returns a response or view', 'A database connection manager', 'A client-side component'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does an action method return in ASP.NET MVC?',
                'options'  => ['Always a string', 'An IActionResult (or derived type) such as a View, JSON, or redirect', 'Always a View', 'A raw HTML string'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the default project structure in an ASP.NET Core MVC application?',
                'options'  => ['src/, test/, docs/', 'Controllers/, Models/, Views/, wwwroot/', 'app/, config/, lib/', 'Pages/, Components/, Services/'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does wwwroot contain in an ASP.NET Core project?',
                'options'  => ['Controller classes', 'Static files served directly (CSS, JS, images)', 'View templates', 'Configuration files'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does "dotnet run" do?',
                'options'  => ['Compiles only without running', 'Builds and runs the ASP.NET Core application', 'Runs unit tests', 'Publishes the application'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of launchSettings.json?',
                'options'  => ['Stores launch passwords', 'Configures how the project is launched during development (URLs, environment, profiles)', 'Defines launch controllers', 'Stores database connection strings'],
                'correct'  => 1,
            ],
        ],
    ],
    [
        'label'     => 'Beginner',
        'questions' => [
            [
                'question' => 'What naming convention must ASP.NET MVC controllers follow?',
                'options'  => ['Must end in "Handler"', 'Must end in "Controller" (e.g., HomeController)', 'Must start with "I" for interface', 'Must be in the root namespace'],
                'correct'  => 1,
            ],
            [
                'question' => 'What base class do ASP.NET Core MVC controllers typically inherit from?',
                'options'  => ['BaseController', 'Controller', 'MvcController', 'ControllerBase'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between Controller and ControllerBase in ASP.NET Core?',
                'options'  => ['They are identical', 'Controller adds View() support; ControllerBase is for API controllers without views', 'ControllerBase is deprecated', 'Controller is for Web API; ControllerBase is for MVC'],
                'correct'  => 1,
            ],
            [
                'question' => 'In ASP.NET Core routing, what does the default route template "{controller=Home}/{action=Index}/{id?}" specify?',
                'options'  => ['Fixed URL /Home/Index', 'Default controller is Home, default action is Index, and id is optional', 'Required id parameter', 'Only Home controller is allowed'],
                'correct'  => 1,
            ],
            [
                'question' => 'How do you define attribute routing on a controller?',
                'options'  => ['[Route("api/[controller]")]', '[Routing("api/[controller]")]', '[Path("api/[controller]")]', '[Url("api/[controller]")]'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does View() return in a controller action?',
                'options'  => ['A JSON response', 'A ViewResult rendering a Razor view', 'A redirect', 'A string'],
                'correct'  => 1,
            ],
            [
                'question' => 'How is data passed from a controller to a Razor view using ViewBag?',
                'options'  => ['ViewBag.Title = "Hello";', 'View.Title = "Hello";', 'ViewData["Title"] = "Hello";', 'Model.Title = "Hello";'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is ViewData?',
                'options'  => ['A dynamic property bag (same as ViewBag but dictionary-based)', 'A model passed to the view', 'A session variable', 'A cached view'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does @model in a Razor view do?',
                'options'  => ['Imports a namespace', 'Declares the type of the model passed to the view, enabling strongly typed access', 'Creates a new model instance', 'Defines a partial view model'],
                'correct'  => 1,
            ],
            [
                'question' => 'What Razor syntax is used to render C# expressions in HTML?',
                'options'  => ['&lt;% expression %&gt;', '#{ expression }', '{{ expression }}', '@expression'],
                'correct'  => 3,
            ],
            [
                'question' => 'What does @{ } do in Razor?',
                'options'  => ['Renders a C# expression', 'Defines a code block for multi-line C# statements', 'Creates an HTML helper', 'Imports a namespace'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does @Html.ActionLink() generate?',
                'options'  => ['An image tag', 'An &lt;a&gt; href link to a controller action', 'A form', 'A script tag'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does @Html.TextBoxFor(m =&gt; m.Name) generate?',
                'options'  => ['A textarea for the Name property', 'A text input bound to the Name property of the model', 'A label for Name', 'A span with the Name value'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does a model in ASP.NET MVC represent?',
                'options'  => ['A database table schema document', 'The data and business logic layer; a class representing the data passed between controller and view', 'A controller helper', 'A Razor template'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does RedirectToAction() do?',
                'options'  => ['Renders another action\'s view in place', 'Issues an HTTP redirect to another controller action', 'Forwards the request internally', 'Renders a partial view'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a partial view in ASP.NET MVC?',
                'options'  => ['An incomplete view', 'A reusable view component rendered within a parent view', 'A view for partial data', 'A mobile-optimized view'],
                'correct'  => 1,
            ],
            [
                'question' => 'How do you render a partial view in Razor?',
                'options'  => ['@Html.RenderPartial("_Name")', '@Html.Partial("_Name")', '@await Html.PartialAsync("_Name")', 'Any of the above depending on version'],
                'correct'  => 3,
            ],
            [
                'question' => 'What is _Layout.cshtml used for?',
                'options'  => ['A partial view', 'A master layout template providing a common HTML structure for multiple views', 'A model template', 'An error page layout'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does @RenderBody() do in a layout file?',
                'options'  => ['Renders the body HTML tag', 'Renders the content of a child view into the layout', 'Renders the &lt;body&gt; CSS', 'Renders partials only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a Tag Helper in ASP.NET Core?',
                'options'  => ['A JavaScript helper', 'A server-side component that enables generating and rendering HTML elements using C#-like syntax', 'A Razor helper function', 'An HTML attribute validator'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does &lt;a asp-controller="Home" asp-action="Index"&gt; do?',
                'options'  => ['Creates a static link to /Home/Index', 'Generates an &lt;a&gt; tag with the href set to the route for HomeController.Index', 'Loads the Home view inline', 'Creates a form action link'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of _ViewStart.cshtml?',
                'options'  => ['Starts the view engine', 'Runs before each view and typically sets the default layout', 'Defines startup models', 'Initializes ViewBag'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does @section in a Razor view do?',
                'options'  => ['Defines a CSS section', 'Defines a named section of content that can be rendered in the layout using @RenderSection()', 'Creates a partial view section', 'Sections an HTML region for styling'],
                'correct'  => 1,
            ],
            [
                'question' => 'How do you return JSON from an ASP.NET Core controller?',
                'options'  => ['return View(json)', 'return Json(data)', 'return Content(json)', 'return new JsonDocument(data)'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does IActionResult allow?',
                'options'  => ['Only View returns', 'A controller action to return any type of response (View, JSON, redirect, file, etc.)', 'Only API responses', 'Only HTTP status codes'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the [Route] attribute on an action do?',
                'options'  => ['Validates routing parameters', 'Specifies a URL template for the action, overriding convention-based routing', 'Caches the route', 'Generates a route table entry'],
                'correct'  => 1,
            ],
            [
                'question' => 'What Razor directive adds a using statement to a view?',
                'options'  => ['@model', '@using Namespace', '@import Namespace', '@include Namespace'],
                'correct'  => 1,
            ],
        ],
    ],
    [
        'label'     => 'Intermediate',
        'questions' => [
            [
                'question' => 'What is Entity Framework Core?',
                'options'  => ['A JavaScript ORM', 'A .NET object-relational mapper (ORM) that allows interacting with databases using C# objects', 'A database engine', 'A database migration tool only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What are the two approaches for working with EF Core?',
                'options'  => ['Push and Pull', 'Code First and Database First', 'Model First and Schema First', 'Online and Offline'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does Code First mean in Entity Framework?',
                'options'  => ['You write C# entity classes first and EF generates the database', 'You write the database schema first', 'You write stored procedures first', 'You use only code, no database'],
                'correct'  => 0,
            ],
            [
                'question' => 'What command creates an EF Core migration?',
                'options'  => ['dotnet ef db update', 'dotnet ef migrations add MigrationName', 'dotnet ef create migration', 'dotnet ef scaffold migration'],
                'correct'  => 1,
            ],
            [
                'question' => 'What command applies pending EF Core migrations to the database?',
                'options'  => ['dotnet ef migrations run', 'dotnet ef db update', 'dotnet ef apply migrations', 'dotnet ef migrate'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a DbContext in EF Core?',
                'options'  => ['A database connection string', 'The main class representing a session with the database, used for querying and saving data', 'A database view', 'A migration file'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is LINQ in the context of EF Core?',
                'options'  => ['A UI component library', 'Language Integrated Query - allows querying data using C# syntax that translates to SQL', 'A logging framework', 'A JSON serializer'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is model validation in ASP.NET Core?',
                'options'  => ['Testing models in unit tests', 'Using data annotation attributes or FluentValidation to validate model data before processing', 'Validating model file syntax', 'Schema validation of JSON models'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the [Required] attribute do?',
                'options'  => ['Marks a property as a database primary key', 'Specifies that the property must have a non-null/non-empty value for model validation to pass', 'Makes the property required in JSON', 'Creates a NOT NULL database constraint only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does ModelState.IsValid check?',
                'options'  => ['Whether the model exists', 'Whether all validation rules (data annotations) for the posted model have passed', 'Whether the model was modified', 'Whether the model is in a valid state in EF Core'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does the [Range] attribute do?',
                'options'  => ['Specifies a text length range', 'Validates that a numeric value is within a specified range', 'Creates a range index', 'Validates date ranges'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is middleware in ASP.NET Core?',
                'options'  => ['Middle-tier business logic', 'Components assembled into a pipeline to handle HTTP requests and responses', 'A JavaScript module between frontend and backend', 'A database connection layer'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does app.UseRouting() do?',
                'options'  => ['Registers routes', 'Adds routing middleware to the pipeline so it can match requests to endpoints', 'Uses a custom router', 'Builds the routing table'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does app.UseAuthentication() do?',
                'options'  => ['Adds user accounts', 'Adds authentication middleware to the pipeline to identify the current user', 'Enforces authorization policies', 'Validates JWT tokens only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is dependency injection (DI) in ASP.NET Core?',
                'options'  => ['Injecting JavaScript dependencies', 'A built-in IoC container that supplies services to classes, promoting loose coupling and testability', 'An HTTP injection technique', 'A method of injecting SQL into queries'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does services.AddScoped&lt;T&gt;() do?',
                'options'  => ['Creates a new instance every time it is requested', 'Creates a new instance per HTTP request (scope)', 'Creates a single instance for the app lifetime', 'Creates an instance per thread'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between AddScoped, AddTransient, and AddSingleton?',
                'options'  => ['AddScoped = per request; AddTransient = per injection; AddSingleton = app lifetime', 'They are identical', 'AddSingleton = per request; AddScoped = per injection', 'AddTransient = per request; AddSingleton = per session'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does a RESTful API use to indicate success when creating a resource?',
                'options'  => ['HTTP 200 OK', 'HTTP 201 Created', 'HTTP 204 No Content', 'HTTP 202 Accepted'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does [ApiController] attribute do in ASP.NET Core?',
                'options'  => ['Marks a class as an MVC controller', 'Enables API-specific behaviors: automatic model validation, binding source inference, and problem detail errors', 'Adds Swagger documentation automatically', 'Restricts controller to JSON only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does [FromBody] do?',
                'options'  => ['Binds a parameter from query string', 'Binds a parameter from the request body (typically JSON)', 'Binds from route data', 'Binds from form data'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does [FromRoute] do?',
                'options'  => ['Binds a parameter from the request body', 'Binds from query string', 'Binds a parameter from the route data', 'Binds from form fields'],
                'correct'  => 2,
            ],
            [
                'question' => 'What is the purpose of ILogger&lt;T&gt; in ASP.NET Core?',
                'options'  => ['Logging HTTP traffic only', 'Injecting a strongly typed logger for the class T to write log messages', 'Logging database queries only', 'A third-party logging interface'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does app.UseExceptionHandler() do?',
                'options'  => ['Catches compile-time exceptions', 'Adds middleware to catch unhandled exceptions and redirect to an error page', 'Handles model validation errors', 'Logs all exceptions to a file'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of the [StringLength] attribute?',
                'options'  => ['Sets max string pool length', 'Validates the maximum (and optionally minimum) length of a string property', 'Trims string values', 'Formats strings in views'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does CORS stand for and why is it relevant to Web APIs?',
                'options'  => ['Cross-Origin Resource Sharing - controls which origins can access the API', 'Cross-Origin Request Security - a firewall rule', 'Cross-Object Resource Sharing - for database access', 'Core Object Resolution System'],
                'correct'  => 0,
            ],
            [
                'question' => 'What does services.AddControllers() register?',
                'options'  => ['All controllers as singleton services', 'MVC services needed for controllers without view support (for APIs)', 'View engine services', 'All MVC and Razor Pages services'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is Swagger/OpenAPI in the context of ASP.NET Core?',
                'options'  => ['A testing framework', 'A specification and tooling for documenting and testing Web APIs interactively', 'A deployment tool', 'A security scanner'],
                'correct'  => 1,
            ],
        ],
    ],
    [
        'label'     => 'Advanced',
        'questions' => [
            [
                'question' => 'What is ASP.NET Core Identity?',
                'options'  => ['A role-based access library', 'A membership system for adding user authentication, authorization, and role management to ASP.NET Core apps', 'An identity server product', 'A JWT validation library'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is JWT (JSON Web Token) used for in ASP.NET Core APIs?',
                'options'  => ['Transmitting JSON data', 'Stateless authentication by encoding claims in a signed token sent with each request', 'Database connection tokens', 'A session management mechanism'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does [Authorize] attribute do?',
                'options'  => ['Logs authorized users', 'Restricts access to a controller/action to authenticated (and optionally authorized) users only', 'Authorizes all users by default', 'Validates CSRF tokens'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does [Authorize(Roles = "Admin")] do?',
                'options'  => ['Creates an Admin role', 'Restricts access to users with the Admin role only', 'Authorizes the Admin controller', 'Applies only to POST actions'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is policy-based authorization in ASP.NET Core?',
                'options'  => ['A fixed set of role-check policies', 'A flexible system for defining authorization policies based on claims, roles, or custom requirements', 'An IIS authorization module', 'A policy file for security settings'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is SignalR?',
                'options'  => ['A signal processing library', 'A library for adding real-time web functionality (server-to-client push) to ASP.NET Core apps using WebSockets', 'A signal monitoring tool', 'A push notification SDK for mobile'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a Hub in SignalR?',
                'options'  => ['A central server node', 'A high-level pipeline class that manages client-server communication in SignalR', 'A network hub integration', 'A message queue component'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does Clients.All.SendAsync() do in a SignalR Hub?',
                'options'  => ['Sends a message to all connected clients', 'Sends to a specific client', 'Broadcasts to a group only', 'Sends to the calling client only'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is gRPC in the context of ASP.NET Core?',
                'options'  => ['A REST API variant', 'A high-performance RPC framework using HTTP/2 and Protocol Buffers for service-to-service communication', 'A GraphQL implementation', 'A graded response protocol'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does IMemoryCache provide in ASP.NET Core?',
                'options'  => ['Distributed caching across servers', 'In-process memory caching within the application lifetime', 'Database query caching', 'Static file caching'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is IDistributedCache used for?',
                'options'  => ['In-memory caching only', 'Caching data in a distributed store (e.g., Redis, SQL Server) shared across multiple servers', 'File system caching', 'CDN caching integration'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is Response Caching in ASP.NET Core?',
                'options'  => ['Caching data in the database', 'Caching HTTP responses so subsequent requests are served from cache headers (Cache-Control)', 'In-memory caching of models', 'Caching middleware only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of the OutputCache middleware (ASP.NET Core 7+)?',
                'options'  => ['Caches model output', 'Caches complete HTTP responses on the server side, reducing backend computation', 'Caches database connections', 'Caches view compilation'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is anti-forgery (CSRF protection) in ASP.NET Core?',
                'options'  => ['Protection against SQL injection', 'Tokens added to forms and validated on submission to prevent Cross-Site Request Forgery attacks', 'Cross-origin request filtering', 'Encryption of form data'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does [ValidateAntiForgeryToken] do?',
                'options'  => ['Validates model annotations', 'Validates that a valid anti-forgery token is present in the request to prevent CSRF', 'Validates JWT tokens', 'Validates API keys'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is minimal API in ASP.NET Core 6+?',
                'options'  => ['An API with minimal features', 'A simplified way to define HTTP endpoints directly in Program.cs without controllers', 'An API for small projects only', 'A reduced version of Web API'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does UseHttpsRedirection() middleware do?',
                'options'  => ['Forces all responses to use HTTPS content', 'Redirects HTTP requests to HTTPS', 'Validates SSL certificates', 'Enforces HSTS headers'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of the UseStaticFiles() middleware?',
                'options'  => ['Processes .cshtml files', 'Enables serving static files from wwwroot without controller processing', 'Compresses static files', 'Pre-renders React apps'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does app.MapControllers() do?',
                'options'  => ['Registers all controllers', 'Maps controller actions to routes in the endpoint routing system', 'Creates route tables', 'Adds controllers to DI container'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is Serilog commonly used for in ASP.NET Core?',
                'options'  => ['Testing', 'Structured logging with sinks to various outputs (console, files, databases)', 'Code generation', 'Dependency injection'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of Health Checks in ASP.NET Core?',
                'options'  => ['Checking code health metrics', 'Endpoints that report the health status of the application and its dependencies for monitoring/load balancers', 'Checking for security vulnerabilities', 'Checking model health'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does services.AddResponseCompression() enable?',
                'options'  => ['Compresses request bodies', 'Compresses HTTP responses (gzip/Brotli) to reduce bandwidth', 'Compresses database results', 'Compresses static files only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is rate limiting in ASP.NET Core 7+?',
                'options'  => ['Limiting CPU usage', 'Restricting the number of requests a client can make in a time window', 'Limiting response sizes', 'Limiting database connections'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of UseHsts()?',
                'options'  => ['Sets HTTPS status', 'Adds the HTTP Strict Transport Security header, instructing browsers to use HTTPS only', 'Hosts static files', 'Enables HTTP/2'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a BackgroundService in ASP.NET Core?',
                'options'  => ['A service running in background threads for CPU work', 'A long-running hosted service executing background tasks independently of HTTP requests', 'A service for background image processing', 'A Windows Service wrapper'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does app.UseSession() add?',
                'options'  => ['Adds user account sessions', 'Adds session middleware enabling server-side HTTP session state', 'Adds database sessions', 'Adds OAuth sessions'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of the ICacheEntry in IMemoryCache?',
                'options'  => ['Defines cache key format', 'Allows configuring expiration, size, and priority when adding a cache entry', 'Manages cache invalidation events', 'Defines cache storage strategy'],
                'correct'  => 1,
            ],
        ],
    ],
    [
        'label'     => 'Expert',
        'questions' => [
            [
                'question' => 'What is Blazor?',
                'options'  => ['A CSS framework for ASP.NET', 'A framework for building interactive web UIs using C# instead of JavaScript, running on WebAssembly or as Blazor Server', 'A Node.js-based ASP.NET renderer', 'A Razor view compiler'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between Blazor Server and Blazor WebAssembly?',
                'options'  => ['They are identical', 'Blazor Server runs on the server with SignalR; Blazor WebAssembly runs C# in the browser via WebAssembly', 'Blazor WebAssembly requires IIS; Blazor Server does not', 'Blazor Server is deprecated'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a Razor Component (.razor file) in Blazor?',
                'options'  => ['A Razor view template', 'A reusable UI component combining C# logic and HTML markup', 'A Razor Page', 'A server-side control'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does @code { } block in a Blazor component contain?',
                'options'  => ['HTML markup', 'C# code for the component (properties, methods, lifecycle hooks)', 'CSS styles', 'JavaScript interop calls'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of the Dockerfile in an ASP.NET Core application?',
                'options'  => ['Defines .NET SDK settings', 'Defines how to build a Docker container image for the application', 'A configuration file for Docker Desktop', 'A deployment script for Kubernetes'],
                'correct'  => 1,
            ],
            [
                'question' => 'What base image is commonly used in a .NET 8 ASP.NET Core Dockerfile?',
                'options'  => ['mcr.microsoft.com/dotnet/aspnet:8.0', 'node:18-alpine', 'ubuntu:22.04', 'mcr.microsoft.com/dotnet/sdk:8.0'],
                'correct'  => 0,
            ],
            [
                'question' => 'What is the difference between the SDK and runtime Docker images for .NET?',
                'options'  => ['They are the same', 'SDK image is used to build; runtime image is smaller and used to run the app in production', 'Runtime image includes the SDK', 'SDK image is for Windows only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is multi-stage Docker build used for in ASP.NET Core?',
                'options'  => ['Building multiple containers', 'Building the app in an SDK image then copying the output to a smaller runtime image to minimize image size', 'Running multiple services', 'Multi-platform builds only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a microservices architecture?',
                'options'  => ['A monolith with micro-optimizations', 'An architectural style where an application is built as a collection of small, independently deployable services', 'A framework for small apps', 'A database sharding strategy'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is an API Gateway in a microservices architecture?',
                'options'  => ['A database gateway', 'A single entry point that routes client requests to appropriate backend microservices', 'A security firewall', 'A load balancer only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is YARP in ASP.NET Core?',
                'options'  => ['Yet Another REST Protocol', 'Yet Another Reverse Proxy - a reverse proxy library for ASP.NET Core', 'A YAML parser', 'A request routing pattern'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is gRPC-JSON transcoding in ASP.NET Core?',
                'options'  => ['Converting JSON to gRPC binary', 'Allowing gRPC services to be called via HTTP/JSON in addition to gRPC, using a transcoding layer', 'A codec for gRPC responses', 'A JSON schema for gRPC definitions'],
                'correct'  => 1,
            ],
            [
                'question' => 'What are owned entities in EF Core?',
                'options'  => ['Entities owned by a specific user', 'Entity types that belong to another entity and are mapped to the same table (value objects)', 'Entities with ownership constraints', 'Private entity models'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the EF Core Global Query Filter?',
                'options'  => ['A filter applied to all LINQ queries', 'A model-level filter automatically applied to all queries for a given entity type (e.g., soft delete, multi-tenancy)', 'A global WHERE clause in migrations', 'A filter for JSONB queries'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the Unit of Work pattern and how does DbContext implement it?',
                'options'  => ['DbContext does not implement it', 'DbContext represents a Unit of Work by tracking changes to entities and committing them all at once via SaveChanges()', 'Unit of Work requires a separate wrapper class always', 'DbContext implements Repository but not Unit of Work'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is a compiled query in EF Core?',
                'options'  => ['A pre-compiled stored procedure', 'A LINQ query compiled once and cached to avoid repeated query compilation overhead', 'A query stored in a compiled DLL', 'An EF migration script'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does AsNoTracking() do in EF Core?',
                'options'  => ['Disables the query', 'Returns entities without tracking changes in DbContext, improving performance for read-only scenarios', 'Prevents caching of the query', 'Disables lazy loading'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the N+1 query problem in EF Core?',
                'options'  => ['A migration versioning issue', 'Loading a list of N entities then issuing a separate query for each entity\'s related data, causing N+1 total queries', 'Querying the same table N+1 times', 'A deadlock scenario with N connections'],
                'correct'  => 1,
            ],
            [
                'question' => 'What does Include() do in EF Core?',
                'options'  => ['Includes raw SQL', 'Eagerly loads related entities in the same query using a JOIN', 'Includes a migration', 'Includes a raw view'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is Dapr in the context of ASP.NET Core microservices?',
                'options'  => ['A data processing runtime', 'A portable, event-driven runtime with building blocks (service invocation, pub/sub, state management) for microservices', 'A database abstraction layer', 'A dashboard for .NET apps'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the health check UI in ASP.NET Core?',
                'options'  => ['A built-in dashboard showing app metrics', 'A UI dashboard (via AspNetCore.Diagnostics.HealthChecks) displaying health check results of services', 'The Swagger UI for health endpoints', 'A Blazor component for monitoring'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the IHealthCheck interface used for?',
                'options'  => ['Implementing custom middleware', 'Implementing custom health checks that can report Healthy, Degraded, or Unhealthy status', 'Implementing custom diagnostics pages', 'Implementing service readiness probes only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the difference between liveness and readiness probes in Kubernetes with ASP.NET Core?',
                'options'  => ['They are identical', 'Liveness checks if the app is running; readiness checks if it is ready to receive traffic', 'Readiness is for databases; liveness is for apps', 'Liveness is for memory; readiness is for CPU'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is Polly used for in ASP.NET Core microservices?',
                'options'  => ['A CSS framework', 'A resilience and transient fault handling library (retry, circuit breaker, timeout, bulkhead isolation)', 'A logging library', 'A test mocking framework'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of IHttpClientFactory in ASP.NET Core?',
                'options'  => ['Creating new HttpClient instances per request always', 'Managing HttpClient lifetime and configuration to avoid socket exhaustion and enable resilience policies', 'A factory for WebClient', 'Creating typed API clients only'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is Blazor\'s EventCallback used for?',
                'options'  => ['Subscribing to server-side events', 'Passing event handlers from parent to child components in Blazor', 'Handling JavaScript events', 'Subscribing to WebSocket events'],
                'correct'  => 1,
            ],
            [
                'question' => 'What is the purpose of the [Parameter] attribute in Blazor components?',
                'options'  => ['Marks a C# parameter', 'Declares a public property that receives values passed from a parent component', 'Marks URL parameters', 'Marks injectable services'],
                'correct'  => 1,
            ],
        ],
    ],
];
require_once __DIR__ . '/quiz-engine.php';
