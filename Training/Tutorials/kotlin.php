<?php
$tutorial_title = 'Kotlin';
$tutorial_slug  = 'kotlin';
$quiz_slug      = 'kotlin';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Kotlin is a modern, statically typed, multi-paradigm language developed by JetBrains and first released in 2011. It compiles to JVM bytecode, JavaScript, and native binaries (Kotlin Multiplatform). Google adopted Kotlin as the preferred language for Android development in 2017, and it has since grown into a first-class choice for server-side development with Ktor and Spring Boot. Kotlin interoperates seamlessly with Java, letting teams migrate incrementally.</p>',
        'concepts' => [
            'Kotlin vs. Java: null safety, data classes, extension functions, coroutines',
            'Variables: val (immutable), var (mutable), type inference',
            'Null safety: nullable types (T?), safe call (?.), Elvis operator (?:), !! assert',
            'String templates: "Hello, $name!" and "${expr}"',
            'Control flow: if/else expressions, when expression (enhanced switch)',
            'Functions: default parameters, named arguments, single-expression functions',
            'Data classes: equals, hashCode, toString, copy() generated automatically',
        ],
        'code' => [
            'title'   => 'Kotlin data classes and null safety',
            'lang'    => 'kotlin',
            'content' =>
'data class User(
    val id:      Int,
    val name:    String,
    val email:   String,
    val score:   Double = 0.0,
    val active:  Boolean = true,
)

fun greet(user: User?): String =
    user?.takeIf { it.active }?.let { "Hello, ${it.name}!" } ?: "User not available"

fun rankLabel(score: Double) = when {
    score >= 90 -> "Gold"
    score >= 70 -> "Silver"
    score >= 50 -> "Bronze"
    else        -> "Starter"
}

fun main() {
    val alice = User(1, "Alice", "alice@example.com", score = 92.0)
    val copy  = alice.copy(name = "Alice Smith", score = 95.0)
    println(greet(alice))      // Hello, Alice!
    println(rankLabel(alice.score)) // Gold
    println(copy)              // User(id=1, name=Alice Smith, ...)
}',
        ],
        'tips' => [
            'Prefer val over var — Kotlin\'s type system is designed around immutability-first thinking.',
            'Use ?.let {} for null-safe transformations and ?: for default values — they replace null checks cleanly.',
            'data class generates equals/hashCode based on constructor properties — perfect for DTOs and domain models.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Kotlin\'s collections API — filter, map, flatMap, groupBy, sortedBy, fold — is expressive and concise, inspired by Scala and Haskell. Extension functions let you add methods to existing classes without inheritance, making Kotlin DSLs and fluent APIs feel natural. Companion objects replace Java\'s static members with a more structured approach.</p><p>Sealed classes define closed type hierarchies that the compiler can exhaustively check in when expressions — the Kotlin equivalent of algebraic data types and discriminated unions.</p>',
        'concepts' => [
            'Collections: listOf, mutableListOf, mapOf, setOf; immutable vs. mutable variants',
            'Higher-order functions and lambdas: (T) -> R type, trailing lambda syntax',
            'Extension functions: fun String.shout() = toUpperCase() + "!"',
            'Companion objects: object declarations inside classes for static-like members',
            'Sealed classes and interfaces: exhaustive when expressions',
            'Object expressions and object declarations (singletons)',
            'Type aliases: typealias UserId = Int',
        ],
        'code' => [
            'title'   => 'Sealed class with exhaustive when',
            'lang'    => 'kotlin',
            'content' =>
'sealed interface NetworkResult<out T> {
    data class Success<T>(val data: T)          : NetworkResult<T>
    data class Error(val code: Int, val msg: String) : NetworkResult<Nothing>
    data object Loading                          : NetworkResult<Nothing>
}

fun <T> handleResult(result: NetworkResult<T>): String =
    when (result) {
        is NetworkResult.Success -> "Data: ${result.data}"
        is NetworkResult.Error   -> "Error ${result.code}: ${result.msg}"
        is NetworkResult.Loading -> "Loading..."
        // Compiler enforces exhaustiveness — no default needed
    }

// Extension function on the sealed interface
fun <T> NetworkResult<T>.getOrNull(): T? =
    (this as? NetworkResult.Success)?.data',
        ],
        'tips' => [
            'Use sealed interface over sealed class — interfaces allow multi-inheritance of sealed hierarchies.',
            'The compiler enforces exhaustive when expressions on sealed types — use this to prevent missing cases.',
            'Write extension functions in a utils/ package to keep class files clean and focused.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Kotlin Coroutines are the language\'s answer to async programming — lightweight cooperative threads that suspend without blocking OS threads. <code>suspend</code> functions, <code>launch</code>, <code>async/await</code>, coroutine scopes, and structured concurrency with <code>coroutineScope</code> make concurrent code readable and safe.</p><p>Kotlin Flow is a cold asynchronous stream — the coroutines equivalent of RxJava Observable or Java\'s Stream. It supports backpressure, operators (map, filter, flatMapLatest), and collection with collect/toList/first.</p>',
        'concepts' => [
            'suspend functions: only callable from coroutines or other suspend functions',
            'Coroutine builders: launch (fire-and-forget), async (returns Deferred<T>)',
            'Coroutine scopes: CoroutineScope, GlobalScope, viewModelScope, lifecycleScope',
            'Structured concurrency: coroutineScope {} and supervisorScope {}',
            'Flow: flow {}, emit(), collect(), flowOf(), asFlow()',
            'Flow operators: map, filter, flatMapLatest, combine, zip, catch, onEach',
            'StateFlow and SharedFlow for hot observable state in Android/Ktor',
        ],
        'code' => [
            'title'   => 'Kotlin coroutines with structured concurrency',
            'lang'    => 'kotlin',
            'content' =>
'import kotlinx.coroutines.*

suspend fun fetchUser(id: Int): User = withContext(Dispatchers.IO) {
    httpClient.get("https://api.example.com/users/$id")
}

suspend fun loadDashboard(userId: Int): Dashboard = coroutineScope {
    // Run both fetches concurrently — cancel both if either fails
    val userDeferred  = async { fetchUser(userId) }
    val postsDeferred = async { fetchPosts(userId) }

    Dashboard(
        user  = userDeferred.await(),
        posts = postsDeferred.await(),
    )
}

// StateFlow example
class UserViewModel(private val repo: UserRepository) : ViewModel() {
    private val _state = MutableStateFlow<NetworkResult<User>>(NetworkResult.Loading)
    val state: StateFlow<NetworkResult<User>> = _state.asStateFlow()

    fun loadUser(id: Int) = viewModelScope.launch {
        _state.value = try {
            NetworkResult.Success(repo.getUser(id))
        } catch (e: Exception) {
            NetworkResult.Error(500, e.message ?: "Unknown error")
        }
    }
}',
        ],
        'tips' => [
            'Use coroutineScope {} instead of GlobalScope — it ties coroutine lifetime to the calling scope.',
            'Use async {} + await() for independent parallel work; sequential suspend calls for dependent work.',
            'Collect Flow in a lifecycle-aware scope (lifecycleScope) on Android to avoid memory leaks.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Kotlin Multiplatform (KMP) lets you share business logic, data models, and network code between Android, iOS, desktop, and web targets while keeping platform-specific UI code native. KMP is production-ready and used by companies like Netflix, McDonald\'s, and Philips.</p><p>Ktor is JetBrains\' asynchronous web framework built on Kotlin Coroutines — lightweight, extensible through plugins, and suitable for both client (HTTP client) and server (routing, serialisation, auth) applications.</p>',
        'concepts' => [
            'Kotlin Multiplatform: expect/actual declarations, commonMain/iosMain/androidMain',
            'KMP + Compose Multiplatform: shared UI across platforms',
            'Ktor server: routing DSL, plugins (auth, serialisation, CORS, rate limiting)',
            'Ktor client: HttpClient, request builders, response handling',
            'kotlinx.serialization: @Serializable, Json.encode/decode, custom serialisers',
            'Kotlin DSL design: type-safe builders, lambdas with receivers',
            'Inline functions and reified type parameters: inline fun <reified T> parse(json: String)',
        ],
        'code' => [
            'title'   => 'Ktor server with routing DSL',
            'lang'    => 'kotlin',
            'content' =>
'import io.ktor.server.engine.*
import io.ktor.server.netty.*
import io.ktor.server.routing.*
import io.ktor.server.application.*
import io.ktor.server.response.*
import io.ktor.server.request.*
import io.ktor.server.plugins.contentnegotiation.*
import io.ktor.serialization.kotlinx.json.*

fun main() {
    embeddedServer(Netty, port = 8080) {
        install(ContentNegotiation) { json() }

        routing {
            route("/api/v1") {
                get("/users") {
                    call.respond(userService.findAll())
                }
                get("/users/{id}") {
                    val id   = call.parameters["id"]?.toIntOrNull()
                        ?: return@get call.respond(HttpStatusCode.BadRequest)
                    val user = userService.findById(id)
                        ?: return@get call.respond(HttpStatusCode.NotFound)
                    call.respond(user)
                }
                post("/users") {
                    val req  = call.receive<CreateUserRequest>()
                    val user = userService.create(req)
                    call.respond(HttpStatusCode.Created, user)
                }
            }
        }
    }.start(wait = true)
}',
        ],
        'tips' => [
            'Use expect/actual declarations sparingly — put as much logic as possible in commonMain.',
            'Ktor\'s plugin system is composable — add only what you need and keep the binary small.',
            'kotlinx.serialization is AOT-safe and faster than Gson/Jackson — prefer it for new Kotlin projects.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Kotlin involves deep knowledge of the compiler plugin API — writing K2 compiler plugins for custom syntax extensions, inline class interop patterns, and the kotlinx.coroutines internals (CancellableContinuation, the event loop, and the IO dispatcher thread pool). Understanding the Kotlin type system at the level of covariance, contravariance, star projections, and type erasure is essential for writing correct generic code.</p><p>Contributing to the Kotlin language and standard library (KEEP proposals), JetBrains\' open-source projects, or the KotlinX ecosystem represents the community dimension of expert Kotlin mastery.</p>',
        'concepts' => [
            'Kotlin K2 compiler: new frontend, better type inference, compiler plugins API',
            'Inline classes / value classes: @JvmInline value class for zero-overhead wrappers',
            'Kotlin contracts: contract { callsInPlace() } for smart cast and init analysis',
            'Coroutines internals: Continuation, CancellableContinuation, the KOTLIN_INTERNAL_COROUTINES_DISPATCHER',
            'Type system: variance (in/out), star projection (*), type erasure, reified generics',
            'KEEP (Kotlin Evolution and Enhancement Process): proposal lifecycle',
            'Kotlin Symbol Processing (KSP): annotation processing replacement',
        ],
        'code' => [
            'title'   => 'Value class for type-safe IDs',
            'lang'    => 'kotlin',
            'content' =>
'@JvmInline
value class UserId(val value: Int) {
    init { require(value > 0) { "UserId must be positive" } }
}

@JvmInline
value class PostId(val value: Int) {
    init { require(value > 0) { "PostId must be positive" } }
}

// Compile-time type safety — you cannot mix UserId and PostId
fun findPost(userId: UserId, postId: PostId): Post? { /* ... */ return null }

// Usage:
val userId = UserId(42)
val postId = PostId(7)
val post   = findPost(userId, postId)           // ✓ correct
// findPost(postId, userId)                     // ✗ compile error
// findPost(UserId(42), UserId(7))              // ✗ compile error

// At runtime, UserId and PostId are just ints — no boxing overhead',
        ],
        'tips' => [
            'Use value classes for domain IDs — they eliminate a class of "wrong argument order" bugs at zero cost.',
            'Write KSP processors instead of KAPT — KSP is 2× faster and K2-compatible.',
            'Follow JetBrains\' Kotlin blog and the KEEP repository for language evolution direction.',
            'Read the Kotlin coroutines design document on GitHub for a deep understanding of the scheduler.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
