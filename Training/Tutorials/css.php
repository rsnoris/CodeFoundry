<?php
$tutorial_title = 'CSS';
$tutorial_slug  = 'css';
$quiz_slug      = 'css';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>CSS (Cascading Style Sheets) is the language that controls the visual presentation of HTML documents. Where HTML answers "what is this content?", CSS answers "how should it look?". The two languages are deliberately separate, keeping structure and presentation concerns independent so each can evolve freely.</p><p>In this tier you will learn how CSS rules are structured (selector → declaration block → property: value), how stylesheets are linked to HTML, and how the browser\'s default stylesheet is overridden to create custom designs.</p>',
        'concepts' => [
            'CSS rule anatomy: selector { property: value; }',
            'Three ways to add CSS: external stylesheet, <style> tag, inline style',
            'Type, class, and ID selectors; combining selectors',
            'Core text properties: font-family, font-size, font-weight, color, text-align',
            'Background properties: background-color, background-image, background-size',
            'The CSS cascade: how conflicting declarations resolve (origin → specificity → order)',
            'Inheritance: which properties inherit by default and using inherit / initial',
        ],
        'code' => [
            'title'   => 'First CSS stylesheet',
            'lang'    => 'css',
            'content' =>
'/* === Reset basics === */
*, *::before, *::after { box-sizing: border-box; }
body {
  margin: 0;
  font-family: "Inter", system-ui, sans-serif;
  background: #f8f9fa;
  color: #212529;
}

/* === Typography === */
h1, h2, h3 { line-height: 1.2; color: #0d1117; }
h1 { font-size: clamp(1.8rem, 5vw, 3rem); }
p  { line-height: 1.7; margin-block: 0.75em; }

/* === Utility === */
.container {
  max-width: 1140px;
  margin-inline: auto;
  padding-inline: 1.5rem;
}',
        ],
        'tips' => [
            'Always start with a small CSS reset (box-sizing: border-box) to create a consistent baseline.',
            'Use system-ui as a font-family fallback — it resolves to the best native font on every OS.',
            'Organise your CSS into clear sections with comment headers for easier navigation.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>The CSS Box Model is the single most important concept for controlling layout. Every element is a rectangular box composed of content, padding, border, and margin. Understanding how these layers interact — and how <code>box-sizing: border-box</code> simplifies the maths — eliminates the majority of layout confusion novices experience.</p><p>Pseudo-classes and pseudo-elements extend CSS\'s reach into user interactions (<code>:hover</code>, <code>:focus</code>) and structural positions (<code>:first-child</code>), while the <code>::before</code> and <code>::after</code> pseudo-elements let you insert decorative content without touching the HTML.</p>',
        'concepts' => [
            'Box model layers: content, padding, border, margin; shorthand notation',
            'box-sizing: content-box (default) vs. border-box',
            'Width and height: auto, px, %, min/max-width/height',
            'CSS display: block, inline, inline-block, none, contents',
            'CSS position: static, relative, absolute, fixed, sticky',
            'z-index and stacking contexts',
            'Pseudo-classes: :hover, :focus, :active, :nth-child(), :not()',
            'Pseudo-elements: ::before, ::after, ::placeholder, ::selection',
        ],
        'code' => [
            'title'   => 'Card component using Box Model',
            'lang'    => 'css',
            'content' =>
'.card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 24px;        /* inner spacing */
  margin-block: 16px;   /* outer vertical spacing */
  max-width: 360px;
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
  transition: box-shadow 0.2s, transform 0.2s;
}

.card:hover {
  box-shadow: 0 8px 24px rgba(0,0,0,.12);
  transform: translateY(-2px);
}

.card::before {
  content: "";
  display: block;
  height: 4px;
  background: linear-gradient(90deg, #18b3ff, #a855f7);
  border-radius: 12px 12px 0 0;
  margin: -24px -24px 20px;
}',
        ],
        'tips' => [
            'Apply box-sizing: border-box globally at the top of every stylesheet — it prevents sizing surprises.',
            'Use position: sticky for headers and sidebars that should stay visible while the page scrolls.',
            'Inspect the box model diagram in browser DevTools for every element you cannot lay out correctly.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Flexbox and CSS Grid are the two layout systems that replaced decades of float-based and table-based hacks. Flexbox handles one-dimensional flows (a toolbar, a footer, a centered hero), while Grid manages two-dimensional surfaces (full-page layouts, image galleries, dashboards).</p><p>CSS custom properties (variables) make values reusable and enable run-time theming via JavaScript; together with calc() and clamp(), they power fluid, adaptive designs that look right at every viewport without media-query breakpoints for every single change.</p>',
        'concepts' => [
            'Flexbox container: display:flex, flex-direction, justify-content, align-items, flex-wrap, gap',
            'Flex items: flex-grow, flex-shrink, flex-basis, order, align-self',
            'CSS Grid: grid-template-columns/rows, fr unit, repeat(), minmax()',
            'Placing items: grid-column, grid-row, named grid areas',
            'CSS custom properties: --token: value; and var(--token, fallback)',
            'calc() for mixed-unit arithmetic',
            'clamp(min, preferred, max) for fluid typography and sizing',
            'CSS logical properties: margin-inline, padding-block, inset',
        ],
        'code' => [
            'title'   => 'Holy Grail layout with CSS Grid',
            'lang'    => 'css',
            'content' =>
'.page {
  display: grid;
  grid-template:
    "header  header " auto
    "sidebar main   " 1fr
    "footer  footer " auto
    / 240px  1fr;
  min-height: 100vh;
  gap: 0;
}

header  { grid-area: header;  background: #0e1828; }
.sidebar{ grid-area: sidebar; background: #121c2b; }
main    { grid-area: main;    padding: 32px;       }
footer  { grid-area: footer;  background: #0e1828; }

@media (max-width: 768px) {
  .page {
    grid-template:
      "header"  auto
      "main"    1fr
      "sidebar" auto
      "footer"  auto
      / 1fr;
  }
}',
        ],
        'tips' => [
            'Learn both Flexbox and Grid — they are complementary, not competing tools.',
            'Use CSS custom properties for colours, spacing, and radii to create a consistent design token system.',
            'Replace magic numbers in calc() with named variables so their intent is self-documenting.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced CSS covers animations, transitions, and transforms that bring interfaces to life. The key is restraint: subtle, purposeful motion guides attention and communicates state changes; excessive animation distracts and disables users who are sensitive to motion.</p><p>CSS architecture patterns — BEM, OOCSS, Utility-first (Tailwind), and CSS-in-JS — address the scalability problem of large stylesheets. This tier also covers CSS containment, the paint / layout / composite rendering pipeline, and writing CSS that avoids triggering expensive layout recalculations.</p>',
        'concepts' => [
            'CSS transitions: shorthand, easing functions, cubic-bezier()',
            'CSS animations: @keyframes, fill-mode, iteration-count, play-state',
            'CSS transforms: translate, rotate, scale, perspective for 3D',
            'will-change property: benefits, costs, and when to use it',
            'BEM methodology and why naming conventions matter at scale',
            'CSS containment: contain: layout size paint style',
            'Rendering pipeline: Layout → Paint → Composite and which properties trigger each',
            'Media features: prefers-color-scheme, prefers-reduced-motion, forced-colors',
        ],
        'code' => [
            'title'   => 'GPU-accelerated card flip animation',
            'lang'    => 'css',
            'content' =>
'.flip-card { perspective: 800px; }

.flip-card-inner {
  position: relative;
  width: 100%; height: 200px;
  transform-style: preserve-3d;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  will-change: transform;
}

.flip-card:hover .flip-card-inner,
.flip-card:focus-within .flip-card-inner {
  transform: rotateY(180deg);
}

.flip-card-front,
.flip-card-back {
  position: absolute; inset: 0;
  backface-visibility: hidden;
  border-radius: 12px;
}

.flip-card-back { transform: rotateY(180deg); }

@media (prefers-reduced-motion: reduce) {
  .flip-card-inner { transition: none; }
}',
        ],
        'tips' => [
            'Stick to transform and opacity for animations — they compose on the GPU without triggering layout.',
            'Measure paint performance with the Chrome DevTools Performance panel before adding will-change.',
            'Use the prefers-color-scheme media feature to implement a dark mode that respects OS settings.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert CSS encompasses the newest specifications that are shipping in modern browsers: Container Queries for component-level responsiveness, the @layer cascade management rule, Subgrid for aligning nested grid items to the parent grid, the :has() relational selector, and the CSS Houdini APIs that expose browser internals.</p><p>Performance at the expert level means understanding Core Web Vitals — how layout shifts (CLS), render-blocking stylesheets, and content-visibility affect real-user experience scores, and how to optimise a large design system stylesheet for production delivery.</p>',
        'concepts' => [
            'Container Queries: @container, container-type, cqi/cqb units',
            'CSS Cascade Layers: @layer declaration order and reversion',
            'CSS Subgrid: grid-template-columns: subgrid for aligned children',
            ':has() relational pseudo-class: parent and sibling selection',
            'CSS Houdini: Paint Worklet, Layout Worklet, registerProperty()',
            'content-visibility: auto for rendering performance',
            'Critical CSS extraction and above-the-fold optimisation',
            'CSS Modules and Scoped Styles in component frameworks',
        ],
        'code' => [
            'title'   => '@layer and :has() in practice',
            'lang'    => 'css',
            'content' =>
'/* Declare layers from lowest to highest priority */
@layer reset, base, components, utilities;

@layer reset {
  *, *::before, *::after { box-sizing: border-box; margin: 0; }
}

@layer base {
  body { font-family: system-ui; line-height: 1.6; }
}

@layer components {
  /* :has() — style a form that contains an invalid input */
  .field:has(input:invalid) label { color: #ef4444; }
  .field:has(input:focus)   label { color: #18b3ff; }
}

@layer utilities {
  .sr-only {
    position: absolute; width: 1px; height: 1px;
    overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap;
  }
}',
        ],
        'tips' => [
            'Use @layer to give third-party CSS lower priority than your own without !important wars.',
            'Adopt content-visibility: auto on below-the-fold sections for significant render-time gains.',
            'Follow the CSS Working Group blog at css.oddbird.net and the CSSWG GitHub for upcoming features.',
            'Build a personal CSS starter kit that encapsulates your preferred reset, tokens, and utilities.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
