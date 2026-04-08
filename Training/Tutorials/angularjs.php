<?php
$tutorial_title = 'AngularJS';
$tutorial_slug  = 'angularjs';
$quiz_slug      = 'angularjs';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>AngularJS (Angular 1.x) was the framework that popularised MVC-style single-page applications in the browser. Released by Google in 2010, it introduced two-way data binding, directives, dependency injection, and the scope-based data model to a generation of front-end developers. Although AngularJS reached end-of-life in December 2021, millions of lines of enterprise code still run on it.</p><p>This tier covers the core concepts of AngularJS so you can read, maintain, and eventually migrate legacy AngularJS applications to modern Angular or React.</p>',
        'concepts' => [
            'AngularJS module: angular.module() and application bootstrapping with ng-app',
            'Controller: $scope, ng-controller, and the controller-as syntax',
            'Two-way data binding: ng-model and the $digest cycle',
            'Built-in directives: ng-repeat, ng-if, ng-show/hide, ng-class, ng-click',
            'Filters: currency, date, orderBy, filter, limitTo',
            'Templates: {{ expression }} interpolation and one-time binding ::value',
            'Module dependencies and injecting built-in services ($http, $location, $timeout)',
        ],
        'code' => [
            'title'   => 'AngularJS controller with ng-repeat',
            'lang'    => 'html',
            'content' =>
'<!DOCTYPE html>
<html ng-app="myApp">
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.3/angular.min.js"></script>
</head>
<body ng-controller="UserCtrl as vm">
  <h1>{{ vm.title }}</h1>
  <input ng-model="vm.search" placeholder="Filter users…">
  <ul>
    <li ng-repeat="user in vm.users | filter:vm.search | orderBy:\'name\'">
      {{ user.name }} — {{ user.email }}
    </li>
  </ul>
  <script>
    angular.module(\'myApp\', [])
      .controller(\'UserCtrl\', function() {
        this.title  = \'User List\';
        this.search = \'\';
        this.users  = [
          { name: \'Alice\', email: \'alice@example.com\' },
          { name: \'Bob\',   email: \'bob@example.com\'   },
        ];
      });
  </script>
</body>
</html>',
        ],
        'tips' => [
            'Always use the controller-as syntax (vm = this) instead of $scope — it is cleaner and easier to migrate.',
            'Use one-time binding (::value) for values that never change — it removes the watcher and improves performance.',
            'Understand that AngularJS reached end-of-life in 2021 — plan a migration to Angular or React.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>AngularJS services and factories are singletons that encapsulate shared logic and data. Services are instantiated with <code>new</code>, factories return an object — the distinction matters less than the pattern of separating business logic from controllers.</p><p>The <code>$http</code> service provides a promise-based API for AJAX requests. Custom directives extend HTML vocabulary and are the precursor to Angular components. Understanding how directives work explains much of how the modern Angular component model was designed.</p>',
        'concepts' => [
            'Service vs. Factory vs. Provider: the three DI recipe types',
            '$http service: GET/POST, config object, .then()/.catch(), $http.defaults',
            'Custom directives: directive() function, restrict, template, link, scope',
            'Isolate scope: \'=\' (two-way), \'@\' (one-way string), \'&\' (expression)',
            '$watch and $watchCollection: observing scope changes',
            '$rootScope: application-wide scope and event broadcasting ($emit, $broadcast, $on)',
            'Angular promises: $q.defer(), $q.all(), $q.resolve()',
        ],
        'code' => [
            'title'   => 'AngularJS factory service',
            'lang'    => 'javascript',
            'content' =>
"angular.module('myApp')
  .factory('UserService', function(\$http) {
    var base = 'https://jsonplaceholder.typicode.com';

    return {
      getAll: function() {
        return \$http.get(base + '/users')
          .then(function(res) { return res.data; });
      },
      getById: function(id) {
        return \$http.get(base + '/users/' + id)
          .then(function(res) { return res.data; });
      },
    };
  })

  .controller('UserCtrl', function(UserService) {
    var vm = this;
    vm.users = [];
    vm.error = null;

    UserService.getAll()
      .then(function(users) { vm.users = users; })
      .catch(function(err)  { vm.error = err.data; });
  });",
        ],
        'tips' => [
            'Return a plain object from factories for the clearest mental model — avoid unnecessary complexity.',
            'Use $http interceptors for global error handling, loading indicators, and auth header injection.',
            'Wrap third-party library callbacks in $timeout() or $scope.$apply() so AngularJS detects the changes.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Angular-Route (ngRoute) and UI-Router provide single-page application routing for AngularJS. UI-Router\'s state-based routing (rather than path-based) introduced concepts — named views, nested states, and abstract states — that heavily influenced the modern Angular router design.</p><p>This tier also covers components (introduced in AngularJS 1.5) — the bridge between AngularJS directives and modern Angular components — and the component lifecycle hooks that make AngularJS code easier to migrate.</p>',
        'concepts' => [
            'ngRoute: $routeProvider, ngView, $routeParams, resolve',
            'UI-Router: $stateProvider, ui-view, $state, $stateParams, nested states',
            'AngularJS component() API: bindings, lifecycle hooks ($onInit, $onChanges, $onDestroy)',
            'Component-based architecture as a migration strategy',
            '$onChanges: responding to input binding changes',
            'Custom filters for reusable data transformation',
            'AngularJS animation: ngAnimate, ng-enter/ng-leave CSS classes',
        ],
        'code' => [
            'title'   => 'AngularJS component with lifecycle hooks',
            'lang'    => 'javascript',
            'content' =>
"angular.module('myApp')
  .component('userCard', {
    bindings: {
      userId: '<',        // one-way in
      onSelect: '&',      // callback out
    },
    template: `
      <div class=\"card\" ng-if=\"\$ctrl.user\">
        <h3>{{ \$ctrl.user.name }}</h3>
        <p>{{ \$ctrl.user.email }}</p>
        <button ng-click=\"\$ctrl.onSelect({ user: \$ctrl.user })\">Select</button>
      </div>
    `,
    controller: function(UserService) {
      var \$ctrl = this;

      \$ctrl.\$onInit = function() {
        UserService.getById(\$ctrl.userId).then(function(u) {
          \$ctrl.user = u;
        });
      };

      \$ctrl.\$onChanges = function(changes) {
        if (changes.userId && !changes.userId.isFirstChange()) {
          UserService.getById(\$ctrl.userId).then(function(u) {
            \$ctrl.user = u;
          });
        }
      };
    },
  });",
        ],
        'tips' => [
            'Migrate directives to components using the component() API first — it is the easiest path toward Angular.',
            'Use < (one-way) bindings wherever possible; two-way = bindings are a common source of digest cycle bugs.',
            'Plan your migration early: ngUpgrade lets AngularJS and Angular coexist in the same application.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>AngularJS performance is dominated by the <code>$digest</code> cycle — the mechanism that checks all registered watchers to detect changes. Understanding how to measure and reduce watcher count, avoid expensive watch expressions, and use <code>track by</code> in ng-repeat are the key performance levers in AngularJS applications.</p><p>The advanced tier also covers AngularJS security: <code>$sce</code> (Strict Contextual Escaping), the <code>$sanitize</code> service for safely rendering user HTML, Content Security Policy configuration, and common AngularJS-specific XSS vectors.</p>',
        'concepts' => [
            '$digest cycle: how AngularJS detects changes, dirty checking, and TTL',
            'Watcher count and its impact on performance',
            'track by in ng-repeat for O(n) reconciliation instead of O(n²)',
            'bindToController pattern and component performance',
            '$sce (Strict Contextual Escaping): trusted HTML, URLs, and resource URLs',
            '$sanitize service and ng-bind-html for safely rendering HTML strings',
            'Content Security Policy and AngularJS nonce/hash support',
            'AngularJS devtools and Batarang for performance profiling',
        ],
        'code' => [
            'title'   => 'Optimised ng-repeat with track by',
            'lang'    => 'html',
            'content' =>
'<!-- Slow: AngularJS compares entire objects every digest -->
<li ng-repeat="item in vm.items">{{ item.name }}</li>

<!-- Fast: track by stable ID avoids object comparison -->
<li ng-repeat="item in vm.items track by item.id">{{ item.name }}</li>

<!-- Also valid: track by $index for immutable lists -->
<li ng-repeat="item in vm.staticList track by $index">{{ item }}</li>

<!-- One-time binding for values that never change -->
<li ng-repeat="item in ::vm.staticItems track by item.id">
  {{ ::item.name }}
</li>',
        ],
        'tips' => [
            'Run the Batarang Chrome extension to count watchers — anything over 2000 on a single view will be slow.',
            'Move computation out of watch expressions into controller methods called only when needed.',
            'Never trust user-supplied HTML without $sanitize — AngularJS does not escape ng-bind-html by default.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>The expert tier is primarily about responsible stewardship: migrating AngularJS applications to modern Angular using the ngUpgrade hybrid strategy, writing codemods to automate mechanical refactoring, and planning phased migrations that let teams ship features while progressively replacing AngularJS modules.</p><p>Understanding the AngularJS source code — how the injector, scope, compile, and link phases work — gives you the diagnostic depth to fix subtle bugs in large, undocumented codebases that no longer have original authors available.</p>',
        'concepts' => [
            'ngUpgrade: bootstrapping a hybrid AngularJS + Angular app with UpgradeModule',
            'Downgrading Angular components for use in AngularJS templates',
            'Upgrading AngularJS services for injection into Angular',
            'Migration strategy: module-by-module, strangler fig, feature-flag approaches',
            'AngularJS compiler internals: $compile, link phases (pre/post), transclude',
            'Injector internals: provider registration, instantiation, and circular dependency detection',
            'Automated migration with angular-eslint and custom codemods',
            'End-of-life planning: security patching, dependency audits, and timeline communication',
        ],
        'code' => [
            'title'   => 'ngUpgrade: downgrading Angular component',
            'lang'    => 'typescript',
            'content' =>
"// Angular side: declare the component
@Component({
  selector: 'app-user-card',
  template: '<div class=\"card\">{{ user?.name }}</div>',
})
export class UserCardComponent {
  @Input() user?: User;
}

// Bridge: downgrade so AngularJS templates can use it
import { downgradeComponent } from '@angular/upgrade/static';

angular.module('myApp')
  .directive(
    'appUserCard',
    downgradeComponent({ component: UserCardComponent }) as ng.IDirectiveFactory
  );

// AngularJS template (now works with Angular component):
// <app-user-card [user]=\"vm.user\"></app-user-card>",
        ],
        'tips' => [
            'Migrate leaf components first — they have no AngularJS children and are easiest to swap.',
            'Keep a live migration dashboard tracking what percentage of the app is Angular vs. AngularJS.',
            'Budget significant QA time for each migrated module — subtle $scope behaviour is hard to replicate exactly.',
            'Communicate end-of-life risk clearly to stakeholders — AngularJS receives no security patches.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
