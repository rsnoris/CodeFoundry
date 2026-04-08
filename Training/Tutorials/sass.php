<?php
$tutorial_title = 'SASS/SCSS';
$tutorial_slug  = 'sass';
$quiz_slug      = 'sass';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Sass (Syntactically Awesome Style Sheets) is a CSS preprocessor that adds programming constructs — variables, nesting, mixins, and functions — to vanilla CSS. It compiles to standard CSS that every browser understands. SCSS syntax (Sassy CSS) is the most popular flavour: it is a superset of CSS, so any valid CSS is valid SCSS.</p><p>This tier covers installing the Sass compiler, understanding the SCSS superset relationship with CSS, and using the two features that immediately improve every codebase: variables and nesting.</p>',
        'concepts' => [
            'Sass vs. SCSS: indented syntax vs. CSS-superset syntax',
            'Installing: npm install sass, CLI usage sass input.scss output.css --watch',
            'SCSS variables: $name: value; and variable scope',
            'Nesting rules and the & parent selector reference',
            'Nesting @media queries inside selectors',
            'Partials: _partial.scss naming convention, @use vs. @import',
            'Comments: // single-line (compiled out) vs. /* */ (kept)',
        ],
        'code' => [
            'title'   => 'Variables and nesting',
            'lang'    => 'scss',
            'content' =>
'// Variables
$color-primary:  #18b3ff;
$color-navy:     #0e1828;
$radius-card:    12px;
$font-body:      "Inter", system-ui, sans-serif;

.card {
  background: $color-navy;
  border-radius: $radius-card;
  padding: 24px;
  font-family: $font-body;

  &:hover {
    outline: 2px solid $color-primary;
  }

  &__title {
    font-size: 1.2rem;
    font-weight: 700;
    color: $color-primary;
  }

  &__body {
    margin-top: 12px;
    line-height: 1.6;

    @media (max-width: 600px) {
      font-size: 0.9rem;
    }
  }
}',
        ],
        'tips' => [
            'Prefer @use over @import — @import is deprecated in Dart Sass and will be removed.',
            'Keep nesting shallow (maximum 3 levels) to avoid specificity wars in the compiled CSS.',
            'Run sass --watch src/styles:dist/styles to auto-compile on file change during development.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Mixins are reusable blocks of CSS declarations, optionally parameterised. They are perfect for vendor prefixes, media-query breakpoints, and complex property groups you repeat across the codebase. <code>@include</code> applies a mixin; <code>@extend</code> shares a CSS rule set without duplication.</p><p>Sass operators (arithmetic, comparison) and control directives (<code>@if</code>, <code>@each</code>, <code>@for</code>) enable programmatic generation of utility classes and responsive scales, turning what would be hundreds of handwritten CSS rules into concise, maintainable code.</p>',
        'concepts' => [
            'Mixins: @mixin name($params) {} and @include name(args)',
            'Content blocks: @mixin name { @content } and @include name { ... }',
            '@extend and placeholder selectors (%name) for rule sharing',
            '@if / @else if / @else conditional rules',
            '@each $item in list {} iteration',
            '@for $i from 1 through n {} numeric iteration',
            'Arithmetic operators in values: $size * 2, calc() integration',
            'String interpolation: #{$variable} in selectors and values',
        ],
        'code' => [
            'title'   => 'Responsive breakpoint mixin',
            'lang'    => 'scss',
            'content' =>
'$breakpoints: (
  "sm":  576px,
  "md":  768px,
  "lg":  992px,
  "xl": 1200px,
);

@mixin respond-to($bp) {
  $value: map-get($breakpoints, $bp);
  @if $value == null {
    @error "Unknown breakpoint: #{$bp}";
  }
  @media (min-width: $value) {
    @content;
  }
}

// Generate spacing utilities
@each $size, $value in (0: 0, 1: 0.25rem, 2: 0.5rem, 3: 1rem, 4: 1.5rem, 5: 3rem) {
  .mt-#{$size} { margin-top: $value; }
  .mb-#{$size} { margin-bottom: $value; }
  .pt-#{$size} { padding-top: $value; }
  .pb-#{$size} { padding-bottom: $value; }
}

// Usage:
.hero {
  font-size: 1.5rem;

  @include respond-to("lg") {
    font-size: 2.5rem;
  }
}',
        ],
        'tips' => [
            'Prefer placeholder selectors (%name) with @extend over plain selectors to avoid unintentional rule cascade.',
            'Use @error and @warn inside mixins to give meaningful messages when incorrect values are passed.',
            'Generate spacing, colour, and typography utility classes with @each to keep them consistent and DRY.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>The Sass module system (<code>@use</code> and <code>@forward</code>) replaces the deprecated <code>@import</code> with namespace-scoped access to variables, mixins, and functions. Organising a codebase into partials grouped by purpose — tokens, typography, components, layouts, utilities — creates a scalable architecture that large teams can navigate.</p><p>Sass built-in modules (<code>sass:math</code>, <code>sass:color</code>, <code>sass:list</code>, <code>sass:map</code>) provide powerful functions for colour manipulation, unit conversion, and data structure operations.</p>',
        'concepts' => [
            '@use "sass:math", "sass:color", "sass:list", "sass:map" module imports',
            'Namespaced access: math.div(), color.adjust(), map.get()',
            '@use with "as" alias and @use with "as *" to pull into scope',
            '@forward for re-exporting from index files',
            '!default flag for overridable default variable values',
            'Functions: @function name($param) { @return value; }',
            'SCSS architecture: 7-1 pattern or tokens/components/utilities structure',
        ],
        'code' => [
            'title'   => '@use module system and colour functions',
            'lang'    => 'scss',
            'content' =>
'// tokens/_colors.scss
@use "sass:color";

$blue-500: #18b3ff;
$navy-900: #0e1828;

// Generate tints and shades
$blue-100: color.adjust($blue-500, $lightness: 40%) !default;
$blue-900: color.adjust($blue-500, $lightness: -40%) !default;

// components/_button.scss
@use "../tokens/colors" as c;
@use "sass:math";

@mixin button-variant($bg, $hover-bg: color.adjust($bg, $lightness: -10%)) {
  background: $bg;
  color: if(color.lightness($bg) > 60%, #000, #fff);

  &:hover {
    background: $hover-bg;
  }
}

.btn-primary { @include button-variant(c.$blue-500); }
.btn-navy    { @include button-variant(c.$navy-900); }

// Fluid font size: clamp based on a scale ratio
@function fluid($min, $max, $min-vw: 400px, $max-vw: 1200px) {
  $slope: math.div($max - $min, $max-vw - $min-vw);
  $intercept: $min - $slope * $min-vw;
  @return clamp(#{$min}, #{$intercept + $slope * 100vw}, #{$max});
}',
        ],
        'tips' => [
            'Use sass:color functions instead of hand-coding tints/shades — they are consistent and parameterised.',
            'Create an _index.scss in each folder that @forwards all partials, then @use the folder as one import.',
            'Apply the !default flag to every public variable so consuming projects can override them.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced Sass covers writing reusable CSS component frameworks — managing theming via a design token layer, building responsive type scales and spacing systems with Sass functions, and testing Sass functions and mixins with True (the Sass unit testing framework).</p><p>Integration with PostCSS — autoprefixer, postcss-preset-env, and custom PostCSS plugins — gives you a full transformation pipeline that takes Sass-compiled CSS and further optimises it for production: vendor prefixes, modern CSS fallbacks, and dead-code elimination.</p>',
        'concepts' => [
            'Design token layer in Sass: primitive → semantic → component tokens hierarchy',
            'Fluid type and space scales with clamp() generated from Sass functions',
            'True: unit testing Sass mixins and functions with @include test()',
            'PostCSS integration: sass → postcss → autoprefixer → minify pipeline',
            'postcss-preset-env: polyfilling future CSS in the pipeline',
            'Critical CSS extraction with PurgeCSS and Sass',
            'CSS Modules with Sass: :local() and composition in webpack/Vite',
        ],
        'code' => [
            'title'   => 'Design token system with Sass maps',
            'lang'    => 'scss',
            'content' =>
'// Primitive tokens
$palette: (
  "blue-100": #e0f4ff,
  "blue-500": #18b3ff,
  "blue-900": #0a3a56,
  "navy":     #0e1828,
);

// Semantic tokens
$color-tokens: (
  "surface-primary":    map.get($palette, "navy"),
  "interactive-default": map.get($palette, "blue-500"),
  "text-on-interactive": #fff,
);

// Emit CSS custom properties
:root {
  @each $token, $value in $color-tokens {
    --cf-#{$token}: #{$value};
  }
}

// Component tokens reference semantic tokens
.btn-primary {
  background: var(--cf-interactive-default);
  color:      var(--cf-text-on-interactive);
}',
        ],
        'tips' => [
            'Emit all tokens as CSS custom properties — they let JavaScript and runtime theming read them.',
            'Write True unit tests for any non-trivial Sass function before publishing a library.',
            'Use the PostCSS + Sass combo: Sass for authoring convenience, PostCSS for production transformations.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Sass knowledge involves understanding Dart Sass internals — the AST representation of stylesheets, the evaluator, and how compilation errors are generated — which helps you write robust large-scale Sass libraries. The emerging CSS features (native cascade layers, custom properties, @scope) that Sass must either support or defer to native CSS create interesting architectural decisions for framework maintainers.</p><p>Contributing to the Sass specification process (proposing and implementing changes through the sass/sass GitHub repo), publishing widely-used Sass libraries, and designing migration paths from SCSS to CSS Modules or Tailwind are the marks of the expert practitioner.</p>',
        'concepts' => [
            'Dart Sass vs. LibSass vs. node-sass: why Dart Sass is the only maintained implementation',
            'Sass module system internals: load path resolution, canonical URL, import cache',
            'CSS layers (@layer) and how Sass partials interact with cascade layers',
            'Sass migrator tool for automating @import → @use upgrades',
            'Performance: reducing Sass compilation time in large monorepos',
            'Sass + CSS custom properties: when to use Sass vars vs. CSS vars',
            'Future of Sass: CSS features making some Sass features redundant (nesting, :is())',
            'Contributing to the Sass spec: proposal process and Dart Sass implementation',
        ],
        'code' => [
            'title'   => 'Sass migrator: @import to @use',
            'lang'    => 'bash',
            'content' =>
'# Install the Sass migrator
npm install -g sass-migrator

# Migrate all .scss files from @import to @use / @forward
sass-migrator module --migrate-deps src/**/*.scss

# Preview changes without writing (dry run)
sass-migrator module --migrate-deps --dry-run src/**/*.scss

# Migrate a specific file
sass-migrator module src/styles/main.scss',
        ],
        'tips' => [
            'Run the Sass migrator on legacy codebases before the @import removal date to automate 80% of the work.',
            'Follow the sass/sass GitHub repository for upcoming deprecation notices and spec changes.',
            'Consider CSS native nesting (now widely supported) as a reason to reduce Sass nesting reliance.',
            'Publish Sass libraries with both compiled CSS and SCSS sources so consumers can choose their approach.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
