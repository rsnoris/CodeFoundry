<?php
$tutorial_title = 'PHP';
$tutorial_slug  = 'php';
$quiz_slug      = 'php';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>PHP (Hypertext Preprocessor) is a server-side scripting language designed specifically for web development. It powers approximately 77% of all websites with a known server-side language, including WordPress, Wikipedia, and Facebook\'s initial codebase. PHP is embedded directly in HTML and executed on the server, sending the result to the browser.</p><p>This tier covers PHP syntax, data types, control structures, and the superglobal arrays that give PHP scripts access to HTTP request data.</p>',
        'concepts' => [
            'PHP tags: <?php ... ?> and the short echo tag <?= ?>',
            'Variables: $name convention, dynamic typing, type juggling',
            'Primitive types: int, float, string, bool, null; type functions: is_int(), gettype()',
            'String functions: strlen, str_contains, strtolower, trim, sprintf, str_replace',
            'Control flow: if/elseif/else, switch, match expression (PHP 8)',
            'Loops: while, do-while, for, foreach',
            'Superglobals: $_GET, $_POST, $_SERVER, $_SESSION, $_COOKIE',
        ],
        'code' => [
            'title'   => 'PHP form processing',
            'lang'    => 'php',
            'content' =>
'<?php
// Process form submission
if ($_SERVER[\'REQUEST_METHOD\'] === \'POST\') {
    $name  = trim(htmlspecialchars($_POST[\'name\']  ?? \'\', ENT_QUOTES, \'UTF-8\'));
    $email = filter_input(INPUT_POST, \'email\', FILTER_VALIDATE_EMAIL);

    if (!$name || !$email) {
        $error = \'Please provide a valid name and email address.\';
    } else {
        // Save to database, send email, etc.
        $success = "Welcome, $name! We\'ll be in touch at $email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<body>
  <?php if (isset($error)):   ?><p class="error"><?= $error ?></p><?php endif; ?>
  <?php if (isset($success)): ?><p class="ok"><?= $success ?></p><?php endif; ?>
  <form method="post">
    <input name="name"  placeholder="Your name"  required>
    <input name="email" type="email" placeholder="Email" required>
    <button>Submit</button>
  </form>
</body>
</html>',
        ],
        'tips' => [
            'Always use htmlspecialchars() when outputting user data to prevent XSS attacks.',
            'Use filter_input() or filter_var() for data validation — never trust raw $_POST/$_GET values.',
            'Enable error reporting (error_reporting(E_ALL)) during development; disable in production.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>PHP functions and arrays are the workhorses of everyday PHP development. PHP\'s array type is remarkably versatile — it acts as both a sequential list and an associative map. The standard library provides over 100 array functions covering sorting, searching, filtering, and transforming data.</p><p>Functions support default parameters, variadic arguments, type declarations, and return type declarations. PHP 8 adds named arguments, union types, and nullsafe operator (?->) which significantly reduce boilerplate.</p>',
        'concepts' => [
            'Functions: type declarations, return types, nullable types (?string)',
            'Named arguments: function(param: value) (PHP 8)',
            'Union types: int|string, null safety with ?? and ?->',
            'Arrays: indexed, associative, multidimensional; array unpacking',
            'Array functions: array_map, array_filter, array_reduce, array_keys, array_values, usort',
            'Array destructuring: [$a, $b] = $array; [\'key\' => $val] = $assoc',
            'Spread operator: function(...$args) and [...$arr1, ...$arr2]',
        ],
        'code' => [
            'title'   => 'Array operations in PHP 8',
            'lang'    => 'php',
            'content' =>
'<?php

$users = [
    [\'id\' => 1, \'name\' => \'Alice\', \'score\' => 92, \'active\' => true],
    [\'id\' => 2, \'name\' => \'Bob\',   \'score\' => 74, \'active\' => false],
    [\'id\' => 3, \'name\' => \'Carol\', \'score\' => 88, \'active\' => true],
];

// Filter active users then extract names
$activeNames = array_map(
    fn($u) => $u[\'name\'],
    array_filter($users, fn($u) => $u[\'active\'])
);

// Sort by score descending
usort($users, fn($a, $b) => $b[\'score\'] <=> $a[\'score\']);

// Named argument: array_slice with preserve_keys
$top2 = array_slice($users, offset: 0, length: 2, preserve_keys: true);

foreach ($top2 as [\'name\' => $name, \'score\' => $score]) {
    echo "$name: $score\n";
}',
        ],
        'tips' => [
            'Use fn($x) => expr arrow functions for short, pure callbacks — they capture outer scope automatically.',
            'Prefer the spaceship operator (<=>) in usort callbacks — it handles all numeric comparison cases.',
            'Destructure arrays in foreach to make the iteration variable names self-documenting.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>PHP\'s object-oriented model supports classes, interfaces, abstract classes, traits, and anonymous classes. PHP 8 enums and readonly properties eliminate large swaths of boilerplate and make domain models safer and more expressive.</p><p>PDO (PHP Data Objects) provides a unified, parameterised interface to MySQL, PostgreSQL, SQLite, and other databases, preventing SQL injection. Namespaces and Composer — PHP\'s dependency manager — organise code and manage third-party packages with autoloading.</p>',
        'concepts' => [
            'Classes: constructor promotion (PHP 8), readonly properties, access modifiers',
            'Interfaces, abstract classes, and trait composition',
            'PHP 8 enums: backed enums, methods, interface implementation',
            'Late static binding: static::class vs. self::class',
            'PDO: PDO::prepare(), execute() with named parameters, fetch modes',
            'Prepared statements and SQL injection prevention',
            'Composer: composer.json, require, autoload PSR-4, vendor/autoload.php',
            'Namespaces: namespace declaration, use aliases, FQCN',
        ],
        'code' => [
            'title'   => 'PHP 8 class with constructor promotion',
            'lang'    => 'php',
            'content' =>
'<?php

enum Status: string {
    case Active   = \'active\';
    case Inactive = \'inactive\';
    case Banned   = \'banned\';

    public function label(): string {
        return match($this) {
            Status::Active   => \'Active\',
            Status::Inactive => \'Inactive\',
            Status::Banned   => \'Banned — contact support\',
        };
    }
}

class User {
    public function __construct(
        public readonly int    $id,
        public string          $name,
        public string          $email,
        public Status          $status = Status::Active,
    ) {}

    public function isActive(): bool {
        return $this->status === Status::Active;
    }
}

$user = new User(id: 1, name: \'Alice\', email: \'alice@example.com\');
echo $user->status->label(); // "Active"',
        ],
        'tips' => [
            'Use constructor property promotion to eliminate repetitive $this->prop = $prop patterns.',
            'Always use PDO prepared statements — never interpolate user values directly into SQL strings.',
            'Add "classmap-authoritative": true to composer.json in production for faster autoloading.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Modern PHP development is built on frameworks. Laravel is the most popular — its expressive ORM (Eloquent), migrations, queues, events, and Blade templating make full-stack development productive. Symfony provides lower-level components used by Laravel, Drupal, and many other frameworks.</p><p>Advanced PHP covers HTTP abstraction (PSR-7 / PSR-15), dependency injection containers (PSR-11), middleware pipelines, and designing testable, SOLID PHP applications with PHPUnit and Mockery.</p>',
        'concepts' => [
            'Laravel: Artisan CLI, routes, controllers, Eloquent ORM, migrations, Blade',
            'Laravel relationships: hasOne, hasMany, belongsTo, belongsToMany, polymorphic',
            'Laravel middleware: global, route, and terminable middleware',
            'PSR-7: ServerRequest, Response, Stream interfaces for framework-agnostic HTTP',
            'PSR-11: ContainerInterface and dependency injection containers',
            'PHP-DI or Laravel service container: binding interfaces to implementations',
            'PHPUnit: test structure, assertions, mocks, data providers, test doubles',
        ],
        'code' => [
            'title'   => 'Laravel Eloquent model with relationships',
            'lang'    => 'php',
            'content' =>
'<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Model {
    protected $fillable = [\'name\', \'email\', \'status\'];
    protected $hidden   = [\'password\', \'remember_token\'];
    protected $casts    = [
        \'email_verified_at\' => \'datetime\',
        \'status\'            => Status::class, // backed enum cast
    ];

    public function posts(): HasMany {
        return $this->hasMany(Post::class);
    }

    public function roles(): BelongsToMany {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    // Query scope
    public function scopeActive($query) {
        return $query->where(\'status\', Status::Active);
    }
}

// Usage:
// User::active()->with(\'posts\')->paginate(15);',
        ],
        'tips' => [
            'Always eager-load relationships (with()) when iterating over collections — N+1 queries are a silent killer.',
            'Use Laravel\'s FormRequest classes for validation logic — they keep controllers thin.',
            'Write feature tests (not just unit tests) for Laravel routes — they test the full request cycle.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert PHP engineering involves performance optimisation (OPcache configuration, preloading, JIT in PHP 8+), asynchronous PHP with ReactPHP or Swoole/OpenSwoole for high-concurrency workloads, and deep knowledge of the Zend Engine — the PHP interpreter — for diagnosing memory and CPU issues.</p><p>Security at the expert level means applying the OWASP PHP Security Cheat Sheet, understanding PHP\'s type juggling vulnerabilities, writing constant-time comparison functions, and designing hardened production configurations (disable_functions, open_basedir, expose_php=Off).</p>',
        'concepts' => [
            'OPcache: opcache.enable, opcache.preload, opcache.jit settings',
            'PHP 8 JIT: tracing JIT vs. function JIT, profiling JIT impact',
            'Preloading: opcache.preload_user and preload scripts',
            'Asynchronous PHP: ReactPHP event loop, promises, HTTP server',
            'Swoole / OpenSwoole: coroutines, co-routines, high-concurrency servers',
            'PHP security hardening: type juggling pitfalls, hash_equals(), timing attacks',
            'OWASP PHP Security Cheat Sheet: injection, CSRF, session fixation',
            'Fibers (PHP 8.1): cooperative multitasking in synchronous-style code',
        ],
        'code' => [
            'title'   => 'PHP 8.1 Fiber for cooperative concurrency',
            'lang'    => 'php',
            'content' =>
'<?php

$fiber = new Fiber(function(): void {
    $value = Fiber::suspend(\'first suspension\');
    echo "Fiber resumed with: $value\n";
    Fiber::suspend(\'second suspension\');
    echo "Fiber finished\n";
});

$result1 = $fiber->start();
echo "Fiber suspended, yielded: $result1\n"; // "first suspension"

$result2 = $fiber->resume(\'hello\');
echo "Fiber suspended again, yielded: $result2\n"; // "second suspension"

$fiber->resume();
// Output:
// Fiber suspended, yielded: first suspension
// Fiber resumed with: hello
// Fiber suspended again, yielded: second suspension
// Fiber finished',
        ],
        'tips' => [
            'Enable OPcache and preloading on every production PHP server — they typically double throughput.',
            'Use hash_equals() for all security-sensitive string comparisons to prevent timing attacks.',
            'Follow PHP internals (internals.php.net) and the php/php-src GitHub for upcoming language changes.',
            'Audit dependencies regularly with composer audit to detect known security vulnerabilities.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
