<?php
$tutorial_title = 'jQuery';
$tutorial_slug  = 'jquery';
$quiz_slug      = 'jquery';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>jQuery is a fast, lightweight JavaScript library that revolutionised front-end development when it launched in 2006. Its slogan — "write less, do more" — captured how it solved real pain points: cross-browser inconsistencies, verbose DOM APIs, and complex Ajax calls. Today, most of jQuery\'s conveniences are available natively, but the library still powers a significant portion of the web and remains embedded in many enterprise codebases.</p><p>This tier covers including jQuery, the central <code>$</code> / <code>jQuery</code> function, basic selectors, and the document-ready pattern.</p>',
        'concepts' => [
            'Including jQuery via CDN and the integrity/crossorigin attributes',
            'The $ and jQuery functions: wrapper around querySelector',
            'Document-ready: $(document).ready() and the shorthand $(function(){})',
            'jQuery selectors: CSS selectors plus :first, :last, :eq(), :even, :odd',
            'Chaining: most jQuery methods return the jQuery object for fluent syntax',
            'jQuery objects vs. DOM elements: accessing raw DOM with .get(0) or [0]',
            '.length property to check if elements were found',
        ],
        'code' => [
            'title'   => 'jQuery hello world',
            'lang'    => 'javascript',
            'content' =>
'// Include in HTML:
// <script src="https://code.jquery.com/jquery-3.7.1.min.js"
//         integrity="sha256-..." crossorigin="anonymous"></script>

$(function () {
  // DOM is ready
  console.log(\'jQuery version:\', $.fn.jquery);

  // Selector returns a jQuery object wrapping all matches
  const $cards = $(\'.card\');
  console.log($cards.length, \'cards found\');

  // Access the raw DOM element
  console.log($cards.get(0)); // first <div class="card">
});',
        ],
        'tips' => [
            'Prefix jQuery variable names with $ (e.g., $button) to distinguish them from plain DOM references.',
            'Always initialise code inside $(function(){}) to ensure the DOM is fully parsed before your script runs.',
            'Lock the jQuery version in production via the SRI hash to prevent CDN-supplied code injection.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>jQuery\'s real power lies in its traversal and manipulation API. Instead of navigating the DOM node-by-node with <code>parentNode</code> and <code>nextSibling</code>, jQuery provides expressive, chained methods like <code>.find()</code>, <code>.closest()</code>, <code>.siblings()</code>, and <code>.children()</code>.</p><p>Modifying content, attributes, CSS, and the class list is also dramatically simpler with jQuery\'s unified setter/getter pattern, where the same method (e.g., <code>.text()</code>) reads when called with no argument and writes when called with one.</p>',
        'concepts' => [
            'Traversal: .find(), .children(), .parent(), .closest(), .siblings()',
            'Filtering: .first(), .last(), .eq(n), .filter(selector), .not(selector)',
            'Content manipulation: .text(), .html(), .val(), .append(), .prepend()',
            'Insertion: .after(), .before(), .appendTo(), .prependTo()',
            'Removal: .remove(), .empty(), .detach() (preserves data/events)',
            'Attributes and properties: .attr(), .prop(), .removeAttr()',
            'CSS and classes: .css(), .addClass(), .removeClass(), .toggleClass(), .hasClass()',
            'Dimensions: .width(), .height(), .innerWidth(), .outerWidth(true)',
        ],
        'code' => [
            'title'   => 'Dynamic list management',
            'lang'    => 'javascript',
            'content' =>
"$(function () {
  function addItem(text) {
    var \$li = \$('<li>')
      .addClass('list-item')
      .text(text)
      .append(\$('<button>').addClass('remove-btn').text('✕'));
    \$('#item-list').append(\$li);
  }

  // Add item on form submit
  \$('#add-form').on('submit', function (e) {
    e.preventDefault();
    var value = \$('#new-item').val().trim();
    if (value) {
      addItem(value);
      \$('#new-item').val('').focus();
    }
  });

  // Remove item via event delegation
  \$('#item-list').on('click', '.remove-btn', function () {
    \$(this).closest('li').remove();
  });
});",
        ],
        'tips' => [
            'Use event delegation (.on(event, selector, handler)) instead of attaching listeners to dynamic elements.',
            'Use .val() for form inputs; use .text() for plain text; use .html() only when you control the content.',
            'Cache jQuery selections in variables when you use them more than once in the same scope.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>jQuery\'s event system wraps browser inconsistencies and adds features like namespaced events for easy cleanup, one-time listeners with <code>.one()</code>, and event delegation for dynamically created elements. Custom events via <code>.trigger()</code> enable a simple publish/subscribe pattern between decoupled components.</p><p>jQuery\'s Ajax API — <code>$.ajax()</code>, <code>$.get()</code>, <code>$.post()</code>, and <code>$.getJSON()</code> — provides a consistent interface to XMLHttpRequest with automatic JSON parsing and jqXHR promise objects that pre-date the native Fetch API.</p>',
        'concepts' => [
            'Event namespaces: $(el).on(\'click.myPlugin\', fn) and .off(\'click.myPlugin\')',
            '.one() for single-fire event listeners',
            '.trigger() and .triggerHandler() for custom events',
            'Event object: event.target, event.currentTarget, event.preventDefault(), event.stopPropagation()',
            '$.ajax(): url, method, data, dataType, success, error, complete, timeout',
            '$.get(), $.post(), $.getJSON() shorthand methods',
            'jqXHR promise interface: .done(), .fail(), .always()',
            'JSONP and cross-origin requests with jQuery',
        ],
        'code' => [
            'title'   => 'Ajax JSON request with error handling',
            'lang'    => 'javascript',
            'content' =>
"function loadUsers(page) {
  var \$list  = \$('#user-list');
  var \$loader = \$('#loader');

  \$loader.show();
  \$list.empty();

  \$.ajax({
    url:     'https://reqres.in/api/users',
    method:  'GET',
    data:    { page: page },
    dataType:'json',
    timeout: 8000,
  })
  .done(function (res) {
    \$.each(res.data, function (i, user) {
      \$list.append(
        \$('<li>').text(user.first_name + ' ' + user.last_name)
      );
    });
  })
  .fail(function (jqXHR, status, error) {
    \$list.append(\$('<li>').addClass('error').text('Error: ' + error));
  })
  .always(function () {
    \$loader.hide();
  });
}

loadUsers(1);",
        ],
        'tips' => [
            'Always set a timeout on Ajax calls — without it, a hanging request can freeze your UI indefinitely.',
            'Use event namespaces to avoid removing unrelated event listeners when cleaning up.',
            'For new projects prefer the native Fetch API, but understand $.ajax for maintaining legacy code.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>jQuery\'s animation and effects engine provides a simple API for fading, sliding, and custom animating elements. The effects queue ensures animations chain correctly, and the <code>.stop()</code> method prevents animation pile-up on rapid user interaction. For complex orchestration, jQuery Deferred objects provide a promise-like pattern that predates native Promises.</p><p>The jQuery plugin pattern — extending <code>$.fn</code> — is the convention for packaging reusable UI behaviour as a chainable method, and understanding it helps you read and maintain the enormous ecosystem of jQuery plugins still in use.</p>',
        'concepts' => [
            'Effects: .show(), .hide(), .toggle(), .fadeIn(), .fadeOut(), .slideDown(), .slideUp()',
            'Custom animation: .animate() with CSS properties and easing',
            'Effects queue: .queue(), .dequeue(), .clearQueue()',
            '.stop(clearQueue, jumpToEnd) to prevent animation pile-up',
            'jQuery Deferred: $.Deferred(), .resolve(), .reject(), .promise()',
            'jQuery plugin authoring: $.fn.pluginName = function(options) {}',
            'Plugin best practices: defaults, chaining, data API, namespace',
            'jQuery UI: interactions (Draggable, Resizable) and widgets (Datepicker, Dialog)',
        ],
        'code' => [
            'title'   => 'jQuery plugin pattern',
            'lang'    => 'javascript',
            'content' =>
";(function (\$) {
  var defaults = {
    speed:     300,
    easing:    'swing',
    onToggle:  null,
  };

  \$.fn.collapsible = function (options) {
    var settings = \$.extend({}, defaults, options);

    return this.each(function () {
      var \$trigger = \$(this);
      var \$target  = \$(\$trigger.data('target'));

      \$trigger.on('click', function () {
        \$target.slideToggle(settings.speed, settings.easing, function () {
          var expanded = \$target.is(':visible');
          \$trigger.attr('aria-expanded', expanded);
          if (typeof settings.onToggle === 'function') {
            settings.onToggle.call(\$target[0], expanded);
          }
        });
      });
    });
  };
}(\$));

// Usage:
\$('[data-toggle=\"collapsible\"]').collapsible({ speed: 200 });",
        ],
        'tips' => [
            'Wrap plugins in an IIFE passing $ as an argument to ensure $ refers to jQuery even in noConflict mode.',
            'Use .data() to store plugin state on elements rather than global variables.',
            'Always return this from a plugin to support chaining.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert-level jQuery knowledge focuses on understanding what jQuery does under the hood — the Sizzle CSS selector engine, how event delegation maps to bubbling, how the effects queue is implemented, and how jqXHR wraps XMLHttpRequest — so you can predict edge cases and debug production issues confidently.</p><p>Equally important is knowing when to migrate away from jQuery: identifying code paths that can be replaced with native DOM APIs, Fetch, CSS transitions, and ES6+ language features to reduce bundle size and dependency on an aging library. This tier provides the tools for that responsible stewardship.</p>',
        'concepts' => [
            'Sizzle selector engine internals and its performance characteristics',
            'How jQuery normalises events across browsers (event.which, event.key legacy)',
            'jQuery internal data store ($.data vs. element.dataset)',
            'Deferred vs. native Promise interoperability (.then() alignment)',
            'jQuery.noConflict() for environments with multiple libraries',
            'Performance: reading/writing DOM in batches to avoid layout thrashing',
            'Migration guide: native equivalents for $.ajax (Fetch), $.each (forEach), $.extend (Object.assign)',
            'Identifying and removing jQuery from legacy codebases safely',
        ],
        'code' => [
            'title'   => 'jQuery to native API migration examples',
            'lang'    => 'javascript',
            'content' =>
"// ── Selector ──────────────────────────────────────────────────────
// jQuery:  $(\".card\")
// Native:  document.querySelectorAll(\".card\")

// ── Add class ─────────────────────────────────────────────────────
// jQuery:  \$(el).addClass('active')
// Native:  el.classList.add('active')

// ── Ajax GET ──────────────────────────────────────────────────────
// jQuery:  \$.getJSON(url, callback)
// Native:
fetch(url)
  .then(res => { if (!res.ok) throw new Error(res.statusText); return res.json(); })
  .then(data => console.log(data))
  .catch(err => console.error(err));

// ── Document ready ────────────────────────────────────────────────
// jQuery:  \$(function() { ... })
// Native:
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', init);
} else {
  init(); // Already loaded
}

function init() { console.log('DOM ready'); }",
        ],
        'tips' => [
            'Use the jQuery Migration Plugin when upgrading jQuery versions — it logs deprecated API usage.',
            'Audit bundle size with webpack-bundle-analyzer; jQuery adds ~30 KB gzipped.',
            'Migrate incrementally: replace jQuery in new code and refactor existing code module-by-module.',
            'You Don\'t Need jQuery (youmightnotneedjquery.com) is a practical reference for native equivalents.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
