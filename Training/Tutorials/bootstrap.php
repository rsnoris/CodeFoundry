<?php
$tutorial_title = 'Bootstrap';
$tutorial_slug  = 'bootstrap';
$quiz_slug      = 'bootstrap';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Bootstrap is the world\'s most popular CSS framework, providing a comprehensive set of pre-built components, a responsive grid system, and utility classes that let developers create professional-looking interfaces without writing CSS from scratch. Originally released by Twitter in 2011, Bootstrap 5 dropped jQuery dependency and added a modern utilities API.</p><p>This tier covers including Bootstrap 5 in a project, understanding the 12-column grid, and using the most common pre-built components.</p>',
        'concepts' => [
            'Including Bootstrap 5 via CDN or npm install bootstrap',
            'Bootstrap\'s CSS custom properties and how to override them',
            'The 12-column grid: container, row, col-{breakpoint}-{n}',
            'Breakpoints: xs, sm (576px), md (768px), lg (992px), xl (1200px), xxl (1400px)',
            'Typography utilities: display headings, lead, text-muted, text-center',
            'Spacing utilities: m-{n}, p-{n}, mt-{n}, px-{n} (0–5 scale)',
            'Color utilities: text-primary, bg-warning, border-danger',
        ],
        'code' => [
            'title'   => 'Bootstrap 5 responsive card grid',
            'lang'    => 'html',
            'content' =>
'<div class="container py-5">
  <div class="row g-4">
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <img src="..." class="card-img-top" alt="...">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title">Card Title</h5>
          <p class="card-text text-muted flex-grow-1">
            Some quick example text.
          </p>
          <a href="#" class="btn btn-primary mt-auto">Go somewhere</a>
        </div>
      </div>
    </div>
    <!-- repeat col divs... -->
  </div>
</div>',
        ],
        'tips' => [
            'Always wrap grid rows in a .container or .container-fluid for correct horizontal padding.',
            'Use g-{n} (gutter) on .row rather than margin utilities on columns — it is the Bootstrap 5 way.',
            'The d-flex flex-column h-100 pattern on cards ensures equal-height cards with a push-to-bottom footer.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Bootstrap\'s component library covers the most common UI patterns: navigation bars, modals, dropdowns, accordions, carousels, toasts, and more. Each component follows a consistent HTML structure and data-attribute API for JavaScript behaviour, so you can add interactivity without writing JavaScript yourself.</p><p>Form controls, validation states, input groups, and floating labels give you a complete forms solution. Badges, alerts, progress bars, and spinners handle feedback and status communication.</p>',
        'concepts' => [
            'Navbar: responsive collapsible navigation with data-bs-toggle',
            'Modal: data-bs-toggle="modal", data-bs-target, Modal JS API',
            'Dropdown: data-bs-toggle="dropdown", dropup, dropend',
            'Accordion: data-bs-parent for exclusive open behaviour',
            'Forms: form-control, form-label, form-check, was-validated',
            'Input groups: input-group, input-group-text',
            'Alerts and toasts for feedback messages',
            'Spinner: border spinner, growing spinner, size variants',
        ],
        'code' => [
            'title'   => 'Bootstrap 5 modal example',
            'lang'    => 'html',
            'content' =>
'<!-- Trigger button -->
<button type="button" class="btn btn-primary"
        data-bs-toggle="modal" data-bs-target="#confirmModal">
  Delete Item
</button>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1"
     aria-labelledby="confirmModalLabel" aria-modal="true" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this item? This cannot be undone.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancel
        </button>
        <button type="button" class="btn btn-danger" id="confirmDelete">
          Delete
        </button>
      </div>
    </div>
  </div>
</div>',
        ],
        'tips' => [
            'Always include aria-modal="true" and role="dialog" on modal elements for screen reader support.',
            'Use Bootstrap\'s JS API (bootstrap.Modal.getInstance(el).hide()) for programmatic control from your code.',
            'The was-validated class on a form triggers Bootstrap\'s green/red validation state styling after submit.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Bootstrap\'s utility API, introduced in v5, lets you generate custom utility classes from your theme variables without writing CSS. Combined with Sass customisation — overriding default variables like <code>$primary</code>, <code>$border-radius</code>, and <code>$spacer</code> — you can create a fully branded Bootstrap theme while keeping the familiar component API.</p><p>Display utilities, flexbox utilities, and the responsive visibility pattern (<code>d-none d-md-block</code>) handle complex layout requirements without writing a line of custom CSS.</p>',
        'concepts' => [
            'Sass customisation: overriding $variables before @import "bootstrap"',
            'Utility API: @include generate-utility() for custom classes',
            'Display utilities: d-none, d-block, d-flex, d-grid, and responsive variants',
            'Flexbox utilities: justify-content-*, align-items-*, flex-wrap, gap-*',
            'Position utilities: position-fixed/sticky, top-0, start-0, translate-middle',
            'Overflow, border, rounded, shadow, and opacity utilities',
            'Bootstrap Icons: icon font and SVG sprite options',
        ],
        'code' => [
            'title'   => 'Bootstrap Sass customisation',
            'lang'    => 'scss',
            'content' =>
'// Override BEFORE importing Bootstrap
$primary:       #18b3ff;
$secondary:     #a855f7;
$font-family-sans-serif: "Inter", system-ui, sans-serif;
$border-radius: 0.5rem;
$spacer:        1.25rem;

// Optionally disable unused components to reduce bundle size
$enable-negative-margins: true;

// Import Bootstrap
@import "bootstrap/scss/bootstrap";

// Custom utility via Utility API
@include generate-utility((
  property:   background-color,
  class:      bg-brand,
  values: (
    "navy": #0e1828,
    "blue": #18b3ff,
  )
));',
        ],
        'tips' => [
            'Import Bootstrap via Sass to unlock customisation — the CDN version cannot be theme-adjusted.',
            'Use PurgeCSS (integrated into Vite/webpack) to strip unused Bootstrap classes from production builds.',
            'Prefer Bootstrap utilities over writing one-off CSS rules — consistency beats custom styles in team projects.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced Bootstrap usage covers the JavaScript plugin architecture — instantiating components programmatically, listening to Bootstrap events, and writing custom Bootstrap plugins that follow the same conventions. The Offcanvas component, Toast API, Popover positioning via Popper.js, and the Scrollspy plugin represent the full breadth of Bootstrap\'s JS layer.</p><p>Accessibility in Bootstrap components requires understanding which ARIA attributes Bootstrap manages automatically and which you must supply, ensuring your implementations meet WCAG 2.1 Level AA requirements.</p>',
        'concepts' => [
            'Bootstrap JS API: new bootstrap.Modal(el, options) programmatic instantiation',
            'Bootstrap events: show.bs.modal, shown.bs.modal, hide.bs.modal, etc.',
            'Offcanvas: sidebar drawers with data-bs-scroll and data-bs-backdrop options',
            'Toast: bootstrap.Toast programmatic show/hide, autohide option',
            'Popover and Tooltip: Popper.js integration, placement, custom HTML content',
            'Scrollspy: updating nav links based on scroll position',
            'Accessibility: ARIA roles Bootstrap manages vs. roles you must supply',
        ],
        'code' => [
            'title'   => 'Programmatic Toast notification',
            'lang'    => 'javascript',
            'content' =>
"function showToast(message, type = 'success') {
  const container = document.getElementById('toast-container');
  const id = 'toast-' + Date.now();

  container.insertAdjacentHTML('beforeend', `
    <div id=\"\${id}\" class=\"toast align-items-center text-bg-\${type} border-0\"
         role=\"alert\" aria-live=\"assertive\" aria-atomic=\"true\">
      <div class=\"d-flex\">
        <div class=\"toast-body\">\${message}</div>
        <button type=\"button\" class=\"btn-close btn-close-white me-2 m-auto\"
                data-bs-dismiss=\"toast\" aria-label=\"Close\"></button>
      </div>
    </div>
  `);

  const toastEl = document.getElementById(id);
  const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
  toast.show();
  toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}",
        ],
        'tips' => [
            'Add aria-live="assertive" aria-atomic="true" to toast elements for screen reader announcements.',
            'Clean up toast DOM nodes in the hidden.bs.toast event to avoid memory accumulation.',
            'Use Bootstrap\'s event system to hook into component lifecycle without overriding default behaviour.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Bootstrap knowledge focuses on designing scalable component systems built on top of Bootstrap\'s token layer, integrating Bootstrap into JavaScript framework component libraries (React Bootstrap, ng-bootstrap, BootstrapVue), and understanding when to migrate away from Bootstrap to a lower-level utility-first approach like Tailwind CSS.</p><p>Contributing to Bootstrap\'s open-source codebase, writing Bootstrap RTL (right-to-left) compatible layouts, and optimising Bootstrap bundle size for performance-critical applications round out the expert tier.</p>',
        'concepts' => [
            'CSS custom property token layer: overriding --bs-* variables at runtime for theming',
            'React Bootstrap and ng-bootstrap: wrapping Bootstrap JS behaviour in framework components',
            'RTL support: Bootstrap 5 RTL bundle, dir="rtl", and logical property equivalents',
            'Critical CSS extraction for above-the-fold Bootstrap styles',
            'Bootstrap vs. Tailwind: migration considerations, hybrid approaches',
            'Contributing to Bootstrap: SCSS architecture, JS plugin conventions, test suite',
            'Bootstrap Icons SVG sprite for performance-optimised icon rendering',
        ],
        'code' => [
            'title'   => 'Bootstrap CSS custom property theming',
            'lang'    => 'css',
            'content' =>
'/* Override Bootstrap 5 CSS custom properties at runtime — no Sass needed */
:root {
  --bs-primary:         #18b3ff;
  --bs-primary-rgb:     24, 179, 255;
  --bs-link-color:      #18b3ff;
  --bs-border-radius:   0.5rem;
  --bs-font-sans-serif: "Inter", system-ui, sans-serif;
  --bs-body-bg:         #0e1828;
  --bs-body-color:      #e2e8f0;
}

/* Dark-mode override via data attribute */
[data-bs-theme="dark"] {
  --bs-body-bg:   #0e1828;
  --bs-body-color: #e2e8f0;
  --bs-border-color: #1a2942;
}',
        ],
        'tips' => [
            'Bootstrap 5.3+ supports a data-bs-theme attribute for first-class dark mode — no custom CSS needed.',
            'Override --bs-* variables in a :root rule to theme without Sass compilation in pure HTML projects.',
            'Audit your Bootstrap bundle with Coverage in Chrome DevTools to identify unused CSS for removal.',
            'Study the Bootstrap GitHub discussion board for the rationale behind design decisions.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
