<?php
$tutorial_title = 'Angular';
$tutorial_slug  = 'angular';
$quiz_slug      = 'angular';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Angular is a comprehensive, opinionated TypeScript framework developed by Google. Unlike React (a library) or Vue (a progressive framework), Angular ships with everything built in: routing, HTTP client, forms, testing utilities, and a CLI. This all-inclusive approach makes it the dominant choice for large enterprise applications where consistency and convention matter as much as flexibility.</p><p>This tier covers Angular\'s architecture — modules, components, templates, and services — and gets a first application running with the Angular CLI.</p>',
        'concepts' => [
            'Angular CLI: ng new, ng serve, ng generate component/service',
            'NgModules vs. standalone components (Angular 14+)',
            'Component anatomy: @Component decorator, template, styles, selector',
            'Template syntax: interpolation {{ }}, property binding [ ], event binding ( )',
            'Two-way binding: [(ngModel)] with FormsModule',
            'Directives: *ngIf, *ngFor, ngClass, ngStyle',
            'Dependency injection: the injector tree and constructor injection',
        ],
        'code' => [
            'title'   => 'Standalone Angular component',
            'lang'    => 'typescript',
            'content' =>
"import { Component, signal } from '@angular/core';
import { NgFor, NgIf } from '@angular/common';

interface Todo { id: number; text: string; done: boolean; }

@Component({
  selector: 'app-todo',
  standalone: true,
  imports: [NgFor, NgIf],
  template: `
    <h2>Todo List</h2>
    <ul>
      <li *ngFor=\"let todo of todos()\">
        <span [class.done]=\"todo.done\">{{ todo.text }}</span>
        <button (click)=\"toggle(todo)\">✓</button>
      </li>
    </ul>
    <p *ngIf=\"todos().length === 0\">No todos yet!</p>
  `,
  styles: ['.done { text-decoration: line-through; color: #999; }']
})
export class TodoComponent {
  todos = signal<Todo[]>([
    { id: 1, text: 'Learn Angular', done: false },
    { id: 2, text: 'Build something', done: false },
  ]);

  toggle(todo: Todo) {
    this.todos.update(list =>
      list.map(t => t.id === todo.id ? { ...t, done: !t.done } : t)
    );
  }
}",
        ],
        'tips' => [
            'Use ng generate (ng g) for all scaffolding — it keeps naming conventions and module registration consistent.',
            'Prefer standalone components (Angular 14+) for new projects — they remove NgModule boilerplate.',
            'Run ng lint and ng test from the start; Angular CLI sets them up automatically.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Services are the mechanism Angular uses to share data and logic between components. Decorated with <code>@Injectable</code>, they are provided to the entire application (or a subtree) via Angular\'s dependency injection system, making them easy to mock in tests and reuse across routes.</p><p>Angular\'s built-in HTTP client (<code>HttpClient</code>) uses RxJS Observables for all network requests. This tier introduces Observables, the subscribe pattern, and the most common RxJS operators that every Angular developer uses daily.</p>',
        'concepts' => [
            'Services: @Injectable({ providedIn: \'root\' }) singleton pattern',
            'HttpClient: GET, POST, PUT, DELETE; typed response generics',
            'RxJS Observable: the push-based data stream abstraction',
            'Subscribing: subscribe(), async pipe in templates (preferred)',
            'Core operators: map, filter, switchMap, mergeMap, catchError, tap, debounceTime',
            'Subject and BehaviorSubject for multicasting state',
            'takeUntilDestroyed / unsubscribe to prevent memory leaks',
        ],
        'code' => [
            'title'   => 'Angular service with HttpClient',
            'lang'    => 'typescript',
            'content' =>
"import { Injectable, inject } from '@angular/core';
import { HttpClient }          from '@angular/common/http';
import { Observable, catchError, throwError } from 'rxjs';

export interface User { id: number; name: string; email: string; }

@Injectable({ providedIn: 'root' })
export class UserService {
  private http = inject(HttpClient);
  private base = 'https://jsonplaceholder.typicode.com';

  getAll(): Observable<User[]> {
    return this.http.get<User[]>(`\${this.base}/users`).pipe(
      catchError(err => throwError(() => new Error(err.message)))
    );
  }

  getById(id: number): Observable<User> {
    return this.http.get<User>(`\${this.base}/users/\${id}`);
  }
}

// In component template:
// users$ = this.userService.getAll();
// <li *ngFor=\"let u of users$ | async\">{{ u.name }}</li>",
        ],
        'tips' => [
            'Always use the async pipe in templates instead of manual subscribe — it auto-unsubscribes on destroy.',
            'Use inject() function instead of constructor injection in standalone components — it is more concise.',
            'Learn switchMap early — it is the correct operator for "cancel previous, start new" HTTP request patterns.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Angular\'s router is one of the most powerful in any framework. It supports lazy loading entire feature modules, route guards for authentication and authorisation, resolvers for pre-fetching data before navigation, and child routes for nested layouts.</p><p>Angular\'s two approaches to forms — Template-Driven and Reactive Forms — serve different needs. Reactive Forms (using <code>FormGroup</code>, <code>FormControl</code>, <code>FormArray</code>) are the production choice for complex, dynamic forms with programmatic validation and unit-testable form logic.</p>',
        'concepts' => [
            'Router: RouterModule.forRoot(), routerLink, RouterOutlet, navigate()',
            'Lazy loading: loadComponent and loadChildren with dynamic import()',
            'Route guards: CanActivate, CanDeactivate, using inject() pattern',
            'Resolvers: fetching data before a route activates',
            'Reactive Forms: FormGroup, FormControl, FormArray, FormBuilder',
            'Validators: built-in (required, minLength, pattern) and custom ValidatorFn',
            'Cross-field validation and async validators',
        ],
        'code' => [
            'title'   => 'Reactive Form with custom validator',
            'lang'    => 'typescript',
            'content' =>
"import { Component, inject } from '@angular/core';
import { FormBuilder, Validators, AbstractControl, ValidationErrors } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';

function passwordMatch(group: AbstractControl): ValidationErrors | null {
  const pw  = group.get('password')?.value;
  const confirm = group.get('confirm')?.value;
  return pw === confirm ? null : { mismatch: true };
}

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [ReactiveFormsModule],
  template: `
    <form [formGroup]=\"form\" (ngSubmit)=\"submit()\">
      <input formControlName=\"email\" type=\"email\" placeholder=\"Email\">
      <div formGroupName=\"passwords\">
        <input formControlName=\"password\" type=\"password\" placeholder=\"Password\">
        <input formControlName=\"confirm\"  type=\"password\" placeholder=\"Confirm\">
        <span *ngIf=\"form.get('passwords')?.errors?.['mismatch']\">Passwords must match</span>
      </div>
      <button type=\"submit\" [disabled]=\"form.invalid\">Register</button>
    </form>
  `
})
export class RegisterComponent {
  private fb = inject(FormBuilder);
  form = this.fb.group({
    email:     ['', [Validators.required, Validators.email]],
    passwords: this.fb.group({
      password: ['', [Validators.required, Validators.minLength(8)]],
      confirm:  ['', Validators.required],
    }, { validators: passwordMatch }),
  });
  submit() { if (this.form.valid) console.log(this.form.value); }
}",
        ],
        'tips' => [
            'Use FormBuilder (inject(FormBuilder)) to reduce boilerplate when creating complex form groups.',
            'Mark controls as touched on submit to display validation errors for untouched fields.',
            'Lazy load every feature module — Angular\'s initial bundle should only contain the shell.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Angular Signals (introduced in v16, stable in v17) represent a fundamental shift in how Angular detects changes. Unlike Zone.js-based change detection that re-renders entire component trees, Signals provide fine-grained reactivity — only components that read a changed signal re-render. This tier covers Signals, computed values, effects, and the migration path from traditional change detection.</p><p>Angular\'s OnPush change detection strategy, the performance implications of different pipe types, and advanced DI techniques (injection tokens, multi-providers) complete the advanced tier.</p>',
        'concepts' => [
            'Signals: signal(), computed(), effect() and their subscription model',
            'Input signals: input() and model() for two-way bindable inputs',
            'OnPush change detection strategy and when to use it',
            'Pure vs. impure pipes and performance implications',
            'InjectionToken for non-class dependencies',
            'Angular CDK: virtual scrolling, drag and drop, overlay',
            'NgRx: Store, Actions, Reducers, Effects, Selectors pattern',
            'NgRx Signals Store: the modern signal-based state management alternative',
        ],
        'code' => [
            'title'   => 'Angular Signals counter',
            'lang'    => 'typescript',
            'content' =>
"import { Component, signal, computed, effect } from '@angular/core';

@Component({
  selector: 'app-counter',
  standalone: true,
  template: `
    <button (click)=\"decrement()\">−</button>
    <span>{{ count() }}</span>
    <button (click)=\"increment()\">+</button>
    <p>Doubled: {{ doubled() }}</p>
    <p [style.color]=\"count() < 0 ? 'red' : 'inherit'\">
      {{ count() < 0 ? 'Negative!' : 'Positive' }}
    </p>
  `
})
export class CounterComponent {
  count   = signal(0);
  doubled = computed(() => this.count() * 2);

  constructor() {
    effect(() => console.log('Count changed to', this.count()));
  }

  increment() { this.count.update(n => n + 1); }
  decrement() { this.count.update(n => n - 1); }
}",
        ],
        'tips' => [
            'Adopt Signals for new code — they are the future of Angular reactivity and reduce reliance on Zone.js.',
            'Always use OnPush for presentational components — it dramatically reduces re-renders in large trees.',
            'Use the Angular CDK instead of custom implementations for virtual scrolling and overlays.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Angular engineers understand the framework internals: the Ivy compiler (Angular\'s next-generation compilation and rendering pipeline), how server-side rendering works with Angular Universal / the new @angular/ssr package, and how to build and publish a reusable Angular library with ng-packagr.</p><p>Performance at scale involves code-splitting strategies, deferrable views (<code>@defer</code>), tree-shaking analysis, and Web Vitals optimisation within the Angular ecosystem. Contributing to the Angular community — writing schematics, Angular DevKit builders, or ESLint rules for Angular — marks the pinnacle of the framework mastery.</p>',
        'concepts' => [
            'Ivy compiler: compilation model, locality principle, template type checking',
            'Angular SSR with @angular/ssr: hydration, transferState',
            'Deferrable views: @defer, @placeholder, @loading, @error blocks',
            'Angular library authoring: ng-packagr, secondary entry points, peer dependencies',
            'Custom Angular schematics for code generation',
            'Angular ESLint and custom lint rules for team conventions',
            'Zone.js-less Angular: zoneless change detection with provideExperimentalZonelessChangeDetection',
            'Micro-frontends with Module Federation and Angular',
        ],
        'code' => [
            'title'   => '@defer deferrable view',
            'lang'    => 'html',
            'content' =>
'<!-- Defer a heavy chart component until it enters the viewport -->
@defer (on viewport) {
  <app-analytics-chart [data]="chartData()" />
} @placeholder {
  <div class="chart-skeleton" aria-label="Loading chart..."></div>
} @loading (minimum 300ms) {
  <app-spinner />
} @error {
  <p>Failed to load chart. <button (click)="retry()">Retry</button></p>
}',
        ],
        'tips' => [
            'Use @defer for heavy third-party components (charts, maps, rich text editors) to improve LCP.',
            'Run ng build --stats-json and inspect with webpack-bundle-analyzer to find tree-shaking gaps.',
            'Follow the Angular blog (blog.angular.dev) and the Angular GitHub repo for upcoming features.',
            'Migrate to zoneless change detection incrementally — it is the future direction of Angular.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
