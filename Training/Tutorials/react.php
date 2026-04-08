<?php
$tutorial_title = 'React';
$tutorial_slug  = 'react';
$quiz_slug      = 'react';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>React is a JavaScript library for building user interfaces, developed and maintained by Meta. It introduced a component-based architecture and a virtual DOM that changed how developers think about front-end code. Instead of imperatively manipulating the DOM, you describe what the UI should look like for a given state and let React efficiently reconcile differences.</p><p>This tier walks through setting up a React project with Vite, understanding JSX, and rendering your first component to the screen.</p>',
        'concepts' => [
            'What React is and what problem it solves (declarative UI)',
            'JSX: JavaScript XML syntax and how it compiles to React.createElement()',
            'Functional components: a function that returns JSX',
            'Rendering with ReactDOM.createRoot().render()',
            'Props: passing data into components as attributes',
            'Project setup with Vite: npm create vite@latest',
            'File conventions: .jsx / .tsx extensions',
        ],
        'code' => [
            'title'   => 'First React component',
            'lang'    => 'jsx',
            'content' =>
"// src/components/Greeting.jsx
function Greeting({ name, role = 'Developer' }) {
  return (
    <div className=\"greeting\">
      <h1>Hello, {name}!</h1>
      <p>Role: {role}</p>
    </div>
  );
}

export default Greeting;

// src/main.jsx
import { createRoot } from 'react-dom/client';
import Greeting from './components/Greeting';

createRoot(document.getElementById('root'))
  .render(<Greeting name=\"Alice\" role=\"Engineer\" />);",
        ],
        'tips' => [
            'Use Vite (npm create vite@latest) rather than Create React App — it is faster and actively maintained.',
            'JSX is syntactic sugar; understanding React.createElement() helps debug cryptic JSX errors.',
            'Prefix component names with a capital letter — React uses this to distinguish components from HTML tags.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>React\'s <code>useState</code> hook is the foundation of interactive components. It lets a component remember values across re-renders and triggers a UI update whenever the value changes. Combined with event handlers, it produces the reactive, data-driven interfaces React is famous for.</p><p>Lists are rendered with <code>Array.map()</code>, and each item requires a stable <code>key</code> prop so React can efficiently reconcile the list. Conditional rendering uses short-circuit evaluation and ternary expressions to show or hide parts of the UI.</p>',
        'concepts' => [
            'useState hook: const [value, setValue] = useState(initial)',
            'Immutable state updates: spread operator and array methods that return new arrays',
            'Event handlers: onClick, onChange, onSubmit and the synthetic event object',
            'Controlled components: input value driven by state',
            'Rendering lists with .map() and the key prop',
            'Conditional rendering: && operator, ternary, and early returns',
            'Component composition: nesting components and the children prop',
        ],
        'code' => [
            'title'   => 'Counter with useState',
            'lang'    => 'jsx',
            'content' =>
"import { useState } from 'react';

function Counter({ initial = 0, step = 1 }) {
  const [count, setCount] = useState(initial);

  return (
    <div>
      <button onClick={() => setCount(c => c - step)}>−</button>
      <span style={{ margin: '0 16px', fontWeight: 700 }}>{count}</span>
      <button onClick={() => setCount(c => c + step)}>+</button>
      <button onClick={() => setCount(initial)} style={{ marginLeft: 12 }}>
        Reset
      </button>
    </div>
  );
}

export default Counter;",
        ],
        'tips' => [
            'Always use the functional update form (setState(prev => ...)) when new state depends on old state.',
            'Never mutate state directly — always create a new array or object to trigger a re-render.',
            'Keys should be stable unique IDs from your data, not array indices (indices break on reorder/insert).',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p><code>useEffect</code> synchronises a component with external systems — fetching data, setting up subscriptions, and interacting with browser APIs. Understanding the dependency array and the cleanup function separates developers who use useEffect correctly from those who create subtle infinite loops and memory leaks.</p><p><code>useContext</code> solves prop drilling by providing values to any descendant component without threading props through every level. This tier also introduces component optimisation with <code>React.memo</code>, <code>useMemo</code>, and <code>useCallback</code>.</p>',
        'concepts' => [
            'useEffect: synchronising with external systems, cleanup function',
            'Dependency array: [], [value], and no array — knowing the difference',
            'Data fetching in useEffect with AbortController for cleanup',
            'Context API: createContext, Provider, useContext',
            'useRef: accessing DOM nodes and persisting mutable values without re-render',
            'React.memo for preventing unnecessary re-renders of pure components',
            'useMemo and useCallback for memoising expensive computations and stable callbacks',
            'Custom hooks: extracting stateful logic into reusable functions (use prefix)',
        ],
        'code' => [
            'title'   => 'Data fetching custom hook',
            'lang'    => 'jsx',
            'content' =>
"import { useState, useEffect } from 'react';

function useFetch(url) {
  const [data,    setData]    = useState(null);
  const [loading, setLoading] = useState(true);
  const [error,   setError]   = useState(null);

  useEffect(() => {
    const controller = new AbortController();
    setLoading(true);

    fetch(url, { signal: controller.signal })
      .then(r => { if (!r.ok) throw new Error(r.statusText); return r.json(); })
      .then(json => { setData(json); setLoading(false); })
      .catch(err => { if (err.name !== 'AbortError') { setError(err); setLoading(false); } });

    return () => controller.abort(); // cleanup on unmount / url change
  }, [url]);

  return { data, loading, error };
}

export default useFetch;",
        ],
        'tips' => [
            'Think of useEffect as "synchronise to X" — describe what to sync, not when to run.',
            'Return a cleanup function from every effect that sets up a subscription or async operation.',
            'Extract complex effect logic into a named custom hook immediately — it will need testing later.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Advanced React covers the <code>useReducer</code> hook for managing complex state machines, <code>Suspense</code> and lazy loading for code-splitting at the component level, and the <code>useTransition</code> / <code>useDeferredValue</code> hooks from Concurrent React that keep UIs responsive under heavy load.</p><p>Error boundaries catch rendering errors in component subtrees, preventing a single component crash from tearing down the entire application. Portals let you render components outside the normal DOM hierarchy — essential for modals, tooltips, and floating dropdowns.</p>',
        'concepts' => [
            'useReducer: (state, action) => newState pattern for complex state',
            'Combining useReducer with useContext for global state without a library',
            'React.lazy() and Suspense for route-level and component-level code splitting',
            'Error boundaries: class components with componentDidCatch and getDerivedStateFromError',
            'Portals: ReactDOM.createPortal() for modals and overlays',
            'useTransition and startTransition for marking non-urgent updates',
            'useDeferredValue for deferring expensive re-renders',
            'Compound component pattern: Context + implicit state sharing',
        ],
        'code' => [
            'title'   => 'useReducer for a shopping cart',
            'lang'    => 'jsx',
            'content' =>
"const initialState = { items: [], total: 0 };

function cartReducer(state, action) {
  switch (action.type) {
    case 'ADD': {
      const exists = state.items.find(i => i.id === action.item.id);
      const items  = exists
        ? state.items.map(i => i.id === action.item.id ? { ...i, qty: i.qty + 1 } : i)
        : [...state.items, { ...action.item, qty: 1 }];
      return { items, total: state.total + action.item.price };
    }
    case 'REMOVE': {
      const item  = state.items.find(i => i.id === action.id);
      return {
        items: state.items.filter(i => i.id !== action.id),
        total: state.total - (item ? item.price * item.qty : 0),
      };
    }
    case 'CLEAR':
      return initialState;
    default:
      return state;
  }
}

// Usage:
// const [cart, dispatch] = useReducer(cartReducer, initialState);
// dispatch({ type: 'ADD', item: { id: 1, name: 'Widget', price: 9.99 } });",
        ],
        'tips' => [
            'Prefer useReducer over useState when state has multiple sub-values that change together.',
            'Wrap React.lazy() components in Suspense with a meaningful fallback, not just "Loading...".',
            'Error boundaries must be class components — create one reusable ErrorBoundary component for your app.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert React involves understanding the new React 19 features — Server Components, the <code>use</code> hook, and the Actions API — alongside deep knowledge of the reconciler (Fiber architecture), render phases, and how to write performant components that cooperate with React\'s scheduling.</p><p>Testing strategy — unit tests with React Testing Library, integration tests, and visual regression tests — and advanced patterns like render props, higher-order components (for legacy code), and the evolving patterns around React Server Components complete the expert curriculum.</p>',
        'concepts' => [
            'React Fiber architecture: work units, prioritised rendering, and the two render phases',
            'Strict Mode double-invocation and what it reveals about side effects',
            'React Server Components (RSC): server-only rendering, zero client JS',
            'The "use" hook and async components in React 19',
            'Server Actions: form actions with progressive enhancement',
            'Testing with React Testing Library: render, screen, userEvent, queries',
            'Higher-order components (HOC) and when to refactor them to hooks',
            'Render props pattern and its modern hook-based replacements',
        ],
        'code' => [
            'title'   => 'React Testing Library — component test',
            'lang'    => 'jsx',
            'content' =>
"import { render, screen } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import Counter from './Counter';

describe('Counter', () => {
  it('renders the initial count', () => {
    render(<Counter initial={5} />);
    expect(screen.getByText('5')).toBeInTheDocument();
  });

  it('increments on + click', async () => {
    const user = userEvent.setup();
    render(<Counter initial={0} step={2} />);
    await user.click(screen.getByRole('button', { name: '+' }));
    expect(screen.getByText('2')).toBeInTheDocument();
  });

  it('resets to initial value', async () => {
    const user = userEvent.setup();
    render(<Counter initial={10} />);
    await user.click(screen.getByRole('button', { name: '+' }));
    await user.click(screen.getByRole('button', { name: 'Reset' }));
    expect(screen.getByText('10')).toBeInTheDocument();
  });
});",
        ],
        'tips' => [
            'Test behaviour, not implementation — query by role, label, and text rather than class names.',
            'Read the React RFC for Server Components to understand the long-term direction of the framework.',
            'Profile with React DevTools Profiler before optimising — perceived performance problems are often elsewhere.',
            'Follow the React team blog (react.dev/blog) for official guidance on new patterns.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
