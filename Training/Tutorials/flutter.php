<?php
$tutorial_title = 'Flutter';
$tutorial_slug  = 'flutter';
$quiz_slug      = 'flutter';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Flutter is Google\'s open-source UI toolkit for building natively compiled applications for mobile, web, and desktop from a single Dart codebase. Released in 2018, Flutter uses its own rendering engine (Impeller/Skia) to draw every pixel, delivering consistent, 60fps (or 120fps on ProMotion displays) performance across all platforms. Dart — Flutter\'s programming language — is easy to learn if you know JavaScript, Java, or Kotlin.</p><p>This tier introduces Flutter\'s widget-centric architecture and the Dart language essentials needed to build first apps.</p>',
        'concepts' => [
            'Flutter SDK installation: flutter create, flutter run, flutter build',
            'Dart basics: var, final, const, types, null safety (String? vs. String)',
            'Widget tree: everything is a widget; BuildContext; Widget vs. Element vs. RenderObject',
            'StatelessWidget vs. StatefulWidget: when to use each',
            'Hot reload and hot restart in Flutter development workflow',
            'Material 3 and Cupertino widget libraries',
            'Scaffold, AppBar, Column, Row, Text, Container, ElevatedButton',
        ],
        'code' => [
            'title'   => 'Flutter StatefulWidget counter',
            'lang'    => 'dart',
            'content' =>
'import \'package:flutter/material.dart\';

void main() => runApp(const CounterApp());

class CounterApp extends StatelessWidget {
  const CounterApp({super.key});

  @override
  Widget build(BuildContext context) => MaterialApp(
        title: \'Counter\',
        theme: ThemeData(colorSchemeSeed: Colors.blue, useMaterial3: true),
        home: const CounterPage(),
      );
}

class CounterPage extends StatefulWidget {
  const CounterPage({super.key});
  @override State<CounterPage> createState() => _CounterPageState();
}

class _CounterPageState extends State<CounterPage> {
  int _count = 0;

  void _increment() => setState(() => _count++);

  @override
  Widget build(BuildContext context) => Scaffold(
        appBar: AppBar(title: const Text(\'Counter\')),
        body: Center(
          child: Column(mainAxisSize: MainAxisSize.min, children: [
            Text(\'$_count\', style: Theme.of(context).textTheme.displayLarge),
            const SizedBox(height: 16),
            FilledButton.icon(
              onPressed: _increment,
              icon: const Icon(Icons.add),
              label: const Text(\'Increment\'),
            ),
          ]),
        ),
      );
}',
        ],
        'tips' => [
            'Use const constructors wherever possible — they create compile-time constants and skip rebuilds.',
            'setState() only rebuilds the State it is called on — keep state as low in the tree as possible.',
            'Enable flutter analyze --watch in a separate terminal to catch Dart type errors instantly.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Flutter\'s layout system is built on widgets: <code>Column</code>, <code>Row</code>, <code>Stack</code>, <code>Expanded</code>, <code>Flexible</code>, and <code>SizedBox</code> compose to handle any layout. <code>ListView</code> and <code>GridView</code> display scrollable lists and grids efficiently. Navigation uses the Navigator or GoRouter for declarative routing between screens.</p><p>Dart\'s async/await and Future<T> power asynchronous operations (HTTP requests, file I/O), while the FutureBuilder and StreamBuilder widgets wire async data into the UI declaratively.</p>',
        'concepts' => [
            'Layout widgets: Column, Row, Stack, Expanded, Flexible, Padding, Align, Center',
            'ListView.builder for large lists: itemCount, itemBuilder',
            'GridView.builder: crossAxisCount, childAspectRatio',
            'Navigation: Navigator.push/pop, MaterialPageRoute, GoRouter for declarative routing',
            'Dart async: Future<T>, async/await, Stream<T>',
            'FutureBuilder: snapshot.connectionState, snapshot.data, snapshot.error',
            'http package: http.get(), jsonDecode(), catching HttpException',
        ],
        'code' => [
            'title'   => 'FutureBuilder with HTTP fetch',
            'lang'    => 'dart',
            'content' =>
'import \'package:flutter/material.dart\';
import \'package:http/http.dart\' as http;
import \'dart:convert\';

Future<List<dynamic>> fetchUsers() async {
  final res = await http.get(
    Uri.parse(\'https://jsonplaceholder.typicode.com/users\'),
  );
  if (res.statusCode != 200) throw Exception(\'Failed: \${res.statusCode}\');
  return jsonDecode(res.body) as List;
}

class UserListScreen extends StatelessWidget {
  const UserListScreen({super.key});

  @override
  Widget build(BuildContext context) => Scaffold(
        appBar: AppBar(title: const Text(\'Users\')),
        body: FutureBuilder<List<dynamic>>(
          future: fetchUsers(),
          builder: (ctx, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return const Center(child: CircularProgressIndicator());
            }
            if (snapshot.hasError) {
              return Center(child: Text(\'Error: \${snapshot.error}\'));
            }
            final users = snapshot.data!;
            return ListView.builder(
              itemCount: users.length,
              itemBuilder: (_, i) => ListTile(
                title:    Text(users[i][\'name\']),
                subtitle: Text(users[i][\'email\']),
                leading:  const CircleAvatar(child: Icon(Icons.person)),
              ),
            );
          },
        ),
      );
}',
        ],
        'tips' => [
            'Use GoRouter for production navigation — deep links, named routes, and redirect guards are built in.',
            'Wrap async calls in try/catch before FutureBuilder — unhandled Future errors become snapshot.error.',
            'Use ListView.builder over ListView with children for any list that could have more than ~20 items.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>State management beyond simple setState is the central challenge in Flutter. Riverpod (the evolution of Provider) is the recommended approach for most apps: it provides a compile-time safe, testable, observable state layer. BLoC (Business Logic Component) is preferred in large teams for its strict separation of UI and business logic through events and states.</p><p>Custom animations with AnimationController, Tween, and AnimatedWidget give Flutter UIs their distinctive fluidity. The CustomPainter API provides a canvas for completely custom graphics.</p>',
        'concepts' => [
            'Riverpod: Provider, StateProvider, FutureProvider, NotifierProvider',
            'ConsumerWidget, ref.watch(), ref.read(), ref.listen()',
            'BLoC pattern: Bloc<Event, State>, emit(), BlocBuilder, BlocListener',
            'AnimationController, Tween, CurvedAnimation, AnimatedBuilder',
            'Implicit animations: AnimatedContainer, AnimatedOpacity, TweenAnimationBuilder',
            'CustomPainter: Canvas, Paint, Path for custom graphics',
            'ThemeData customisation, ColorScheme, TextTheme, dynamic theming',
        ],
        'code' => [
            'title'   => 'Riverpod counter with NotifierProvider',
            'lang'    => 'dart',
            'content' =>
'import \'package:flutter_riverpod/flutter_riverpod.dart\';
import \'package:flutter/material.dart\';

// Notifier
class CounterNotifier extends Notifier<int> {
  @override
  int build() => 0;

  void increment() => state++;
  void decrement() => state--;
  void reset()     => state = 0;
}

// Provider
final counterProvider = NotifierProvider<CounterNotifier, int>(CounterNotifier.new);

// UI
class CounterView extends ConsumerWidget {
  const CounterView({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final count = ref.watch(counterProvider);
    return Scaffold(
      appBar: AppBar(title: const Text(\'Riverpod Counter\')),
      body: Center(
        child: Column(mainAxisSize: MainAxisSize.min, children: [
          Text(\'$count\', style: const TextStyle(fontSize: 48)),
          Row(mainAxisSize: MainAxisSize.min, children: [
            IconButton(icon: const Icon(Icons.remove), onPressed: () => ref.read(counterProvider.notifier).decrement()),
            IconButton(icon: const Icon(Icons.add),    onPressed: () => ref.read(counterProvider.notifier).increment()),
          ]),
        ]),
      ),
    );
  }
}',
        ],
        'tips' => [
            'Use ref.watch() in build() for reactive state; ref.read() in callbacks — never ref.watch() in a callback.',
            'Prefer NotifierProvider over StateNotifierProvider — it is the modern Riverpod API (v2+).',
            'AnimatedContainer with a duration is the fastest way to add polished transitions to layout changes.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Flutter\'s platform channel API enables calling native Android (Kotlin/Java) and iOS (Swift/Objective-C) code from Dart — essential for accessing platform-specific hardware APIs (camera, NFC, Bluetooth, health sensors) or wrapping existing native SDKs. The Pigeon tool generates type-safe bindings, eliminating the error-prone MethodChannel string-based API.</p><p>Flutter Web and Flutter Desktop (Windows, macOS, Linux) extend the same Dart code to additional platforms, with platform-specific adaptations for file systems, system tray, and menu bars. Flutter DevTools provides deep performance, memory, and widget tree profiling.</p>',
        'concepts' => [
            'Platform channels: MethodChannel, EventChannel, BasicMessageChannel',
            'Pigeon: type-safe, code-generated platform channels',
            'Flutter plugin development: federated plugin structure, platform implementations',
            'Flutter Web: rendering targets (CanvasKit vs. HTML), WASM compilation',
            'Flutter Desktop: window management, menu bar, system tray plugins',
            'Isolates: Isolate.spawn() and compute() for CPU-bound background work',
            'Flutter DevTools: Timeline, Memory, Widget Inspector profiling',
        ],
        'code' => [
            'title'   => 'Isolate for CPU-intensive work',
            'lang'    => 'dart',
            'content' =>
'import \'dart:isolate\';

// Heavy computation — runs on a separate isolate (thread)
int _computeInBackground(List<int> numbers) {
  return numbers.fold(0, (sum, n) => sum + n * n);  // sum of squares
}

// In a widget or service:
Future<int> computeSumOfSquares(List<int> numbers) async {
  // compute() spawns an isolate, runs the function, returns result
  return await Isolate.run(() => _computeInBackground(numbers));
}

// Usage in UI:
class HeavyComputeWidget extends StatefulWidget {
  const HeavyComputeWidget({super.key});
  @override State<HeavyComputeWidget> createState() => _State();
}

class _State extends State<HeavyComputeWidget> {
  int? _result;

  @override
  void initState() {
    super.initState();
    computeSumOfSquares(List.generate(1000000, (i) => i))
        .then((r) => setState(() => _result = r));
  }

  @override
  Widget build(BuildContext context) =>
      Text(_result == null ? \'Computing...\' : \'Result: $_result\');
}',
        ],
        'tips' => [
            'Use Isolate.run() (Dart 2.19+) instead of compute() for cleaner isolate spawning.',
            'Isolates do not share memory — pass only simple, serialisable data between isolates.',
            'Profile with Flutter DevTools Timeline before optimising UI jank — the culprit is often a large list.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Flutter engineering involves understanding the three trees (Widget, Element, RenderObject), how the framework diffs them, and when to override RenderObject for fully custom layout and painting. The Impeller rendering engine (Flutter\'s Metal/Vulkan-based replacement for Skia) and understanding its shader compilation and draw call batching help you build apps that maintain 120fps on high-refresh-rate displays.</p><p>Contributing to Flutter or its package ecosystem, designing scalable architectures for large teams (feature-first directory structure, clean architecture layers), and advanced testing (golden tests, integration tests with Patrol) represent the expert Flutter practitioner.</p>',
        'concepts' => [
            'Flutter rendering pipeline: Widget tree → Element tree → RenderObject tree → Layer tree',
            'Custom RenderObjects: RenderBox, performLayout, paint, hitTest',
            'Impeller: Metal/Vulkan renderer, shader pre-compilation, DisplayList',
            'Flutter deep linking: GoRouter redirect, deepLinkBuilder, AppLinks',
            'Patrol: integration test framework replacing FlutterDriver',
            'Golden tests: matchesGoldenFile() for visual regression testing',
            'Clean architecture in Flutter: domain/data/presentation layers with Riverpod',
        ],
        'code' => [
            'title'   => 'Custom RenderObject for a gradient progress bar',
            'lang'    => 'dart',
            'content' =>
'import \'package:flutter/rendering.dart\';
import \'package:flutter/material.dart\';

class GradientProgressBar extends LeafRenderObjectWidget {
  final double progress;  // 0.0 – 1.0
  const GradientProgressBar({super.key, required this.progress});

  @override
  RenderObject createRenderObject(BuildContext context) =>
      _RenderGradientBar(progress: progress);

  @override
  void updateRenderObject(BuildContext ctx, _RenderGradientBar ro) {
    ro.progress = progress;
  }
}

class _RenderGradientBar extends RenderBox {
  double _progress;
  _RenderGradientBar({required double progress}) : _progress = progress;

  set progress(double v) { if (_progress != v) { _progress = v; markNeedsPaint(); } }

  @override
  Size computeDryLayout(BoxConstraints c) => Size(c.maxWidth, 8);
  @override
  void performLayout() => size = computeDryLayout(constraints);

  @override
  void paint(PaintingContext ctx, Offset offset) {
    final rect = offset & size;
    ctx.canvas.drawRRect(
      RRect.fromRectAndRadius(
        Rect.fromLTWH(rect.left, rect.top, rect.width * _progress, rect.height),
        const Radius.circular(4),
      ),
      Paint()..shader = LinearGradient(
        colors: const [Color(0xFF18b3ff), Color(0xFFa855f7)],
      ).createShader(rect),
    );
  }
}',
        ],
        'tips' => [
            'Custom RenderObjects are powerful but complex — exhaust CustomPainter options first.',
            'Enable Impeller on Android via --enable-impeller flag and report shading issues to the Flutter team.',
            'Follow flutter.dev/community and the Flutter GitHub repo for release notes and migration guides.',
            'Read the Flutter source for Material widgets — they are excellent examples of production-quality widget design.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
