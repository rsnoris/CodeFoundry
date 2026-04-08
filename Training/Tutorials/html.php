<?php
$tutorial_title = 'HTML';
$tutorial_slug  = 'html';
$quiz_slug      = 'html';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>HTML is the language browsers read to understand what content to display. It uses <em>tags</em> — keywords wrapped in angle brackets — to annotate text with meaning: this is a heading, this is a paragraph, this is a link. Every website ever published begins with an HTML document.</p><p>This tier walks through the anatomy of an HTML page, the most common tags, and the difference between elements and attributes, giving you everything needed to build a readable, valid document from scratch.</p>',
        'concepts' => [
            'Opening tags, closing tags, and self-closing/void elements (br, img, input)',
            'HTML5 DOCTYPE declaration and why it matters',
            'The head element: title, meta charset, meta viewport, link',
            'Heading hierarchy: h1 through h6 and semantic importance',
            'Paragraphs (p), line breaks (br), and horizontal rules (hr)',
            'Hyperlinks: <a href>, absolute vs. relative URLs, target="_blank"',
            'Images: <img src alt width height> and the importance of alt text',
        ],
        'code' => [
            'title'   => 'Minimal valid HTML5 document',
            'lang'    => 'html',
            'content' =>
'<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="A minimal HTML5 example page.">
  <title>Page Title</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <h1>Main Heading</h1>
  <p>This is a paragraph with a
     <a href="https://example.com" target="_blank" rel="noopener">link</a>.
  </p>
  <img src="photo.jpg" alt="A descriptive caption" width="600" height="400">
</body>
</html>',
        ],
        'tips' => [
            'Use exactly one <h1> per page — search engines treat it as the primary topic signal.',
            'Always add meaningful alt text to images; omit alt="" only for purely decorative images.',
            'Validate your HTML with the W3C Markup Validator to catch nesting and attribute errors.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>HTML lists and tables organise repeating data, while forms collect user input. These elements appear on nearly every website — navigation menus, registration pages, data dashboards — making them essential building blocks to master early.</p><p>You will also learn character entities (for special characters), global attributes shared by all elements, and the critical role of the <code>lang</code> and <code>dir</code> attributes for internationalisation and accessibility.</p>',
        'concepts' => [
            'Ordered lists (ol), unordered lists (ul), list items (li)',
            'Nested lists and styling with CSS list-style-type',
            'Description lists: dl, dt, dd',
            'Tables: table, thead, tbody, tfoot, tr, th, td',
            'Table attributes: colspan, rowspan, scope',
            'Forms: form action/method, input types, label, select, option, textarea, button',
            'Character entities: &amp;, &lt;, &gt;, &nbsp;, &copy;',
            'Global attributes: id, class, title, lang, dir, tabindex, hidden',
        ],
        'code' => [
            'title'   => 'Accessible HTML form',
            'lang'    => 'html',
            'content' =>
'<form action="/register" method="post" novalidate>
  <div>
    <label for="name">Full Name <span aria-hidden="true">*</span></label>
    <input type="text" id="name" name="name" required autocomplete="name">
  </div>

  <div>
    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" autocomplete="email">
  </div>

  <div>
    <label for="role">Role</label>
    <select id="role" name="role">
      <option value="">-- select --</option>
      <option value="dev">Developer</option>
      <option value="design">Designer</option>
    </select>
  </div>

  <button type="submit">Create Account</button>
</form>',
        ],
        'tips' => [
            'Always associate a <label> with its control using the for/id pair — this doubles the click target.',
            'Use the correct input type (email, number, date) — mobile browsers show the right keyboard automatically.',
            'Add autocomplete attributes to form fields so browsers and password managers can assist users.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Semantic HTML goes beyond merely displaying content — it communicates the purpose and structure of that content to assistive technologies, search engine crawlers, and other developers. The HTML5 sectioning elements divide the page into logical regions, and microdata / schema.org annotations make information machine-readable.</p><p>You will also explore embedded media (audio, video, canvas, SVG inline), iframes for third-party content, and the HTML template / slot mechanism used by Web Components.</p>',
        'concepts' => [
            'Sectioning elements: header, nav, main, section, article, aside, footer',
            'Content grouping: figure, figcaption, details, summary, blockquote, cite',
            'Inline semantics: strong, em, mark, abbr, time, code, kbd, samp, var',
            'Audio and video elements: src, controls, autoplay, loop, muted, poster',
            'SVG inline embedding vs. <img src="file.svg">',
            '<canvas> element and the 2D drawing context basics',
            'iframes: sandboxing, allow, loading="lazy"',
            'HTML template element and slot for Web Components',
        ],
        'code' => [
            'title'   => 'Accessible video with captions',
            'lang'    => 'html',
            'content' =>
'<figure>
  <video controls width="720" poster="thumbnail.jpg">
    <source src="demo.webm" type="video/webm">
    <source src="demo.mp4"  type="video/mp4">
    <track kind="subtitles" src="demo.en.vtt" srclang="en" label="English" default>
    <p>Your browser does not support HTML5 video.
       <a href="demo.mp4">Download the video</a>.
    </p>
  </video>
  <figcaption>Product demo walkthrough (3 min)</figcaption>
</figure>',
        ],
        'tips' => [
            'Use <figure> and <figcaption> together — they give images and media a semantic container.',
            'Always provide a <track> with captions for videos; it is required for WCAG 1.2.2 compliance.',
            'Test page outlines with a screen reader or the WAVE accessibility tool to verify heading hierarchy.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>HTML performance and SEO depend on how markup communicates intent to browsers and crawlers. Resource hints (<code>preload</code>, <code>prefetch</code>, <code>preconnect</code>), lazy loading, and responsive images with <code>srcset</code> and <code>picture</code> dramatically improve page load speed without changing visual appearance.</p><p>This tier also covers Open Graph and Twitter Card meta tags for rich social sharing previews, JSON-LD structured data for search result enhancements, and progressive enhancement as a design philosophy.</p>',
        'concepts' => [
            'Responsive images: srcset, sizes, picture element with art direction',
            'Loading attribute: loading="lazy" for images and iframes',
            'Resource hints: rel="preload", rel="prefetch", rel="preconnect"',
            'Open Graph meta tags for social media previews',
            'JSON-LD structured data and schema.org vocabulary',
            'ARIA landmark roles, live regions, and expanded/collapsed states',
            'Content Security Policy meta tag basics',
            'Progressive enhancement vs. graceful degradation philosophy',
        ],
        'code' => [
            'title'   => 'Responsive image with srcset and picture',
            'lang'    => 'html',
            'content' =>
'<!-- Art direction: different crops for different screens -->
<picture>
  <source media="(max-width: 600px)"
          srcset="hero-sm.webp 600w, hero-sm@2x.webp 1200w"
          type="image/webp">
  <source media="(min-width: 601px)"
          srcset="hero-lg.webp 1200w, hero-lg@2x.webp 2400w"
          type="image/webp">
  <!-- Fallback for browsers without WebP or <picture> support -->
  <img src="hero-lg.jpg"
       srcset="hero-lg.jpg 1200w, hero-lg@2x.jpg 2400w"
       sizes="(max-width: 600px) 100vw, 1200px"
       alt="Team collaborating in a modern office"
       width="1200" height="630"
       loading="lazy">
</picture>',
        ],
        'tips' => [
            'Always supply explicit width and height on images — it prevents Cumulative Layout Shift (CLS).',
            'Use JSON-LD for structured data; it is easier to maintain than Microdata embedded in HTML.',
            'Run every important page through Google\'s Rich Results Test to verify schema markup.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert HTML encompasses the Web Components standard (Custom Elements, Shadow DOM, HTML Templates), Service Workers and the offline-first pattern via the Cache API, and the full scope of ARIA authoring practices for complex interactive widgets like datepickers, carousels, and data grids.</p><p>You will also explore emerging HTML specifications: the Popover API, the Dialog element, Declarative Shadow DOM for server-side rendering, and the long-term vision of HTML as a living standard driven by the WHATWG.</p>',
        'concepts' => [
            'Custom Elements: defining, registering, and lifecycle callbacks (connectedCallback, etc.)',
            'Shadow DOM: encapsulated trees, ::part and ::slotted selectors',
            'HTML Template and slot-based content distribution',
            'Declarative Shadow DOM: shadowrootmode attribute for SSR',
            'Service Workers: fetch interception, Cache API, offline strategies',
            'The <dialog> element: modal/non-modal, showModal(), close()',
            'The Popover API: popover attribute, popovertarget',
            'ARIA Authoring Practices Guide (APG) patterns for complex widgets',
        ],
        'code' => [
            'title'   => 'Custom Element with Shadow DOM',
            'lang'    => 'javascript',
            'content' =>
"class AlertBanner extends HTMLElement {
  static get observedAttributes() { return ['type', 'message']; }

  connectedCallback() {
    this.attachShadow({ mode: 'open' }).innerHTML = `
      <style>
        :host { display: block; }
        .banner {
          padding: 12px 16px; border-radius: 8px; font-family: sans-serif;
          background: var(--banner-bg, #fff3cd); color: var(--banner-color, #856404);
        }
      </style>
      <div class=\"banner\" role=\"alert\">
        <slot></slot>
      </div>
    `;
  }
}

customElements.define('alert-banner', AlertBanner);",
        ],
        'tips' => [
            'Read the WHATWG HTML Living Standard at html.spec.whatwg.org when in doubt about element semantics.',
            'Follow Scott O\'Hara and Adrian Roselli for authoritative ARIA and accessibility guidance.',
            'Test Custom Elements in all major browsers — polyfills are still needed for older Chromium versions.',
            'Use Declarative Shadow DOM on server-rendered pages to avoid a flash of unstyled content.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
