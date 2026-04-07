<?php
$tutorial_title = 'Intro to HTML & CSS';
$tutorial_slug  = 'intro-html-css';
$quiz_slug      = 'intro-html-css';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>HTML (HyperText Markup Language) and CSS (Cascading Style Sheets) are the twin foundations of every webpage you have ever seen. HTML provides structure and meaning to content through a hierarchy of elements, while CSS controls the visual presentation — colours, fonts, spacing, and layout.</p><p>In this tier you will set up your first HTML file, understand what tags and attributes are, and add a simple stylesheet that transforms plain text into a styled document.</p>',
        'concepts' => [
            'What is HTML? Tags, elements, and the angle-bracket syntax',
            'The minimal HTML5 document: DOCTYPE, html, head, body',
            'Block vs. inline elements (div/p/h1 vs. span/a/strong)',
            'Essential attributes: id, class, href, src, alt',
            'Linking a CSS file with the <link> tag',
            'CSS selectors: element, class (.name), ID (#name)',
            'CSS properties: color, background-color, font-size, margin, padding',
        ],
        'code' => [
            'title'   => 'First HTML & CSS page',
            'lang'    => 'html',
            'content' =>
'<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My First Page</title>
  <style>
    body { font-family: sans-serif; background: #f4f4f4; color: #333; }
    h1   { color: #0066cc; }
    .card {
      background: #fff;
      border-radius: 8px;
      padding: 20px;
      max-width: 400px;
      margin: 40px auto;
      box-shadow: 0 2px 8px rgba(0,0,0,.1);
    }
  </style>
</head>
<body>
  <div class="card">
    <h1>Hello, World!</h1>
    <p>This is my first styled webpage.</p>
    <a href="https://example.com">Learn more</a>
  </div>
</body>
</html>',
        ],
        'tips' => [
            'Open your HTML file directly in a browser — no server needed for static files.',
            'Use the browser DevTools (F12) to inspect elements and experiment with CSS live.',
            'Validate your HTML at validator.w3.org to catch common structural mistakes early.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Once you can build a basic page, the next step is learning to lay out content precisely. The CSS Box Model — margin, border, padding, and the element\'s own width/height — governs how every element occupies space on the page. Misunderstanding the box model is the source of most layout headaches.</p><p>This tier also covers semantic HTML: choosing the right element (<code>header</code>, <code>nav</code>, <code>main</code>, <code>article</code>, <code>footer</code>) communicates meaning to screen readers and search engines, not just browsers.</p>',
        'concepts' => [
            'The CSS Box Model: content → padding → border → margin',
            'box-sizing: border-box and why it simplifies layouts',
            'Semantic HTML5 elements: header, nav, main, section, article, aside, footer',
            'Lists: ordered (ol), unordered (ul), description (dl)',
            'HTML forms: input types, label, select, textarea, button',
            'CSS display values: block, inline, inline-block, none',
            'CSS specificity: how conflicting rules resolve',
            'Pseudo-classes: :hover, :focus, :nth-child()',
        ],
        'code' => [
            'title'   => 'Semantic page structure',
            'lang'    => 'html',
            'content' =>
'<body>
  <header>
    <nav>
      <a href="/">Home</a>
      <a href="/about">About</a>
    </nav>
  </header>

  <main>
    <article>
      <h2>Article Title</h2>
      <p>Article content goes here.</p>
    </article>
    <aside>
      <h3>Related Links</h3>
      <ul>
        <li><a href="#">Resource 1</a></li>
        <li><a href="#">Resource 2</a></li>
      </ul>
    </aside>
  </main>

  <footer>
    <p>&copy; 2025 CodeFoundry</p>
  </footer>
</body>',
        ],
        'tips' => [
            'Always use <label> elements paired with form inputs — they improve accessibility and click targets.',
            'Memorise the box model by drawing it on paper: content / padding / border / margin from inside out.',
            'Avoid using <div> for everything — semantic tags give your HTML meaning for free.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Modern layouts are built with Flexbox and CSS Grid. Flexbox excels at one-dimensional layouts (a row of navigation items, a card with content and a button), while Grid shines for two-dimensional page structure. Both are essential tools in every front-end developer\'s kit.</p><p>This tier also introduces responsive design: using media queries and fluid units (%, vw, em, rem) to make your pages look great on any screen size, from a 320 px phone to a 4K monitor.</p>',
        'concepts' => [
            'Flexbox: flex-direction, justify-content, align-items, flex-wrap, gap',
            'Flexbox child properties: flex-grow, flex-shrink, flex-basis, align-self',
            'CSS Grid: grid-template-columns/rows, fr unit, grid-gap',
            'Placing items: grid-column, grid-row, grid-area',
            'Responsive design philosophy: mobile-first vs. desktop-first',
            'Media queries: @media (max-width), breakpoints',
            'Fluid units: %, vw, vh, em, rem vs. px',
            'CSS custom properties (variables): --name and var()',
        ],
        'code' => [
            'title'   => 'Responsive card grid with CSS Grid',
            'lang'    => 'css',
            'content' =>
'.card-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 24px;
  padding: 24px;
}

.card {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  box-shadow: 0 2px 8px rgba(0,0,0,.08);
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.card-footer {
  margin-top: auto;
  display: flex;
  justify-content: flex-end;
}

@media (max-width: 600px) {
  .card-grid { padding: 12px; gap: 12px; }
}',
        ],
        'tips' => [
            'Build a Flexbox cheat-sheet you can reference quickly — there are many shorthand properties.',
            'Use the browser\'s Grid inspector (DevTools → Layout tab) to visualise your grid lines visually.',
            'Design mobile-first: start with the smallest viewport and progressively enhance.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced front-end CSS encompasses animations, transitions, custom properties for theming, CSS architecture (BEM, SMACSS), and CSS preprocessors like SASS/SCSS that add variables, nesting, and mixins to vanilla CSS.</p><p>You will also explore accessibility (a11y) — ARIA attributes, colour contrast ratios, keyboard navigability, and screen reader support — ensuring your pages are usable by everyone, not just sighted mouse users.</p>',
        'concepts' => [
            'CSS transitions: property, duration, easing, delay',
            'CSS animations: @keyframes, animation shorthand, will-change',
            'CSS transforms: translate, rotate, scale, skew in 2D and 3D',
            'BEM naming methodology: Block__Element--Modifier',
            'SASS/SCSS: variables, nesting, mixins, extends, partials',
            'ARIA roles and attributes for accessibility',
            'Colour contrast and the WCAG 2.1 guidelines',
            'Focus management and keyboard-only navigation',
        ],
        'code' => [
            'title'   => 'CSS keyframe animation',
            'lang'    => 'css',
            'content' =>
'@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: translateY(0); }
}

.card {
  animation: fadeInUp 0.4s ease both;
}

.card:nth-child(2) { animation-delay: 0.1s; }
.card:nth-child(3) { animation-delay: 0.2s; }

/* Respect users who prefer reduced motion */
@media (prefers-reduced-motion: reduce) {
  .card { animation: none; }
}',
        ],
        'tips' => [
            'Always provide a prefers-reduced-motion fallback for any animation — it is a WCAG requirement.',
            'Test with a screen reader (VoiceOver on macOS, NVDA on Windows) to hear your page as others do.',
            'Keep CSS specificity low and flat — avoid deep nesting that creates hard-to-override rules.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert-level HTML and CSS involves deep knowledge of browser rendering pipelines, CSS containment, Houdini APIs for custom paint and layout worklets, and the emerging Container Queries specification that enables component-level responsiveness independent of the viewport.</p><p>Performance optimisation — critical rendering path, font loading strategies, content-visibility, and layout stability (Cumulative Layout Shift) — distinguishes professional implementations from amateur ones at this level.</p>',
        'concepts' => [
            'Critical rendering path: DOM → CSSOM → Render tree → Layout → Paint → Composite',
            'CSS containment: contain property for performance isolation',
            'Container Queries: @container and cqi/cqb units',
            'CSS Subgrid: aligning nested grids to the parent grid',
            'CSS Houdini: Paint API and Layout API worklets',
            'Font loading: font-display, preload, variable fonts',
            'Core Web Vitals: LCP, INP, CLS and how CSS affects them',
            'CSS Layers (@layer) for cascade management at scale',
        ],
        'code' => [
            'title'   => 'Container Query example',
            'lang'    => 'css',
            'content' =>
'.card-wrapper {
  container-type: inline-size;
  container-name: card;
}

.card { padding: 16px; }

/* Component responds to its container, not the viewport */
@container card (min-width: 400px) {
  .card { display: grid; grid-template-columns: 120px 1fr; gap: 20px; }
}

@container card (min-width: 700px) {
  .card { padding: 32px; }
  .card-title { font-size: 1.5rem; }
}',
        ],
        'tips' => [
            'Use Lighthouse and Web Vitals extension regularly — treat performance as a feature, not an afterthought.',
            'Study the CSS specification at drafts.csswg.org to understand why rules work the way they do.',
            'Follow Miriam Suzanne, Lea Verou, and Una Kravets for cutting-edge CSS insights.',
            'Build a small personal design system with CSS custom properties to practise scalable CSS architecture.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
