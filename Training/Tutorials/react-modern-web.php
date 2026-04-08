<?php
$tutorial_title = 'React & Modern Web';
$tutorial_slug  = 'react-modern-web';
$quiz_slug      = '';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>The modern web is built on an ecosystem of tools that work alongside React: Vite for lightning-fast builds, React Router for navigation, Tailwind CSS for styling, and Zustand or Jotai for lightweight state management. Understanding how these pieces fit together is essential for building production applications.</p><p>This tier maps the modern React ecosystem, explains why each tool exists, and sets up a full-stack-ready project template that you can use as a starting point for real applications.</p>',
        'concepts' => [
            'Vite: ES-module-based dev server, instant HMR, and optimised production builds',
            'React Router v6: BrowserRouter, Routes, Route, Link, useNavigate, useParams',
            'File-based routing conventions (Next.js / Remix style)',
            'Tailwind CSS with React: className utilities, responsive prefixes, dark mode',
            'Zustand: minimal global state with a single store hook',
            'Axios vs. Fetch: when to use a library vs. native API',
            'ESLint + Prettier integration for consistent React codebases',
        ],
        'code' => [
            'title'   => 'React Router v6 setup',
            'lang'    => 'jsx',
            'content' =>
"// src/main.jsx
import { createBrowserRouter, RouterProvider } from 'react-router-dom';
import Root   from './routes/Root';
import Home   from './routes/Home';
import About  from './routes/About';
import Error  from './routes/Error';

const router = createBrowserRouter([
  {
    path:      '/',
    element:   <Root />,
    errorElement: <Error />,
    children: [
      { index: true,    element: <Home /> },
      { path: 'about',  element: <About /> },
    ],
  },
]);

ReactDOM.createRoot(document.getElementById('root'))
  .render(<RouterProvider router={router} />);",
        ],
        'tips' => [
            'Use createBrowserRouter (Data API) over BrowserRouter for loader/action data patterns.',
            'Co-locate route files with their test files and styles in a features/ directory structure.',
            'Set up ESLint with eslint-plugin-react-hooks from day one — it catches common hook mistakes.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Forms and data fetching are the two most common tasks in any web application. React Hook Form provides performant, accessible form validation with minimal re-renders. TanStack Query (React Query) replaces manual useEffect data-fetching with a declarative cache, background refetching, and loading/error state management out of the box.</p><p>This tier introduces both libraries alongside the patterns they replace, so you understand the problem as well as the solution.</p>',
        'concepts' => [
            'React Hook Form: useForm, register, handleSubmit, formState.errors',
            'Controlled vs. uncontrolled forms and when each approach wins',
            'Schema validation with Zod and zodResolver',
            'TanStack Query: QueryClient, QueryClientProvider, useQuery, useMutation',
            'Query keys and cache invalidation strategies',
            'Background refetching, stale time, and cache time configuration',
            'Optimistic updates with useMutation onMutate',
        ],
        'code' => [
            'title'   => 'React Hook Form + Zod validation',
            'lang'    => 'jsx',
            'content' =>
"import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';

const schema = z.object({
  email:    z.string().email('Invalid email'),
  password: z.string().min(8, 'At least 8 characters'),
});

function LoginForm({ onSubmit }) {
  const { register, handleSubmit, formState: { errors, isSubmitting } } =
    useForm({ resolver: zodResolver(schema) });

  return (
    <form onSubmit={handleSubmit(onSubmit)}>
      <input {...register('email')} type=\"email\" placeholder=\"Email\" />
      {errors.email && <p>{errors.email.message}</p>}

      <input {...register('password')} type=\"password\" placeholder=\"Password\" />
      {errors.password && <p>{errors.password.message}</p>}

      <button type=\"submit\" disabled={isSubmitting}>
        {isSubmitting ? 'Logging in…' : 'Login'}
      </button>
    </form>
  );
}",
        ],
        'tips' => [
            'Use zodResolver to share validation schemas between your React frontend and Node.js backend.',
            'Set staleTime in TanStack Query to avoid over-fetching on route changes — default is 0 (always stale).',
            'Prefer useQuery over useEffect for data fetching — it handles caching, deduplication, and retries for free.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>State management at scale requires a clear architecture. Zustand provides a minimal, unopinionated store; Jotai uses atomic state inspired by Recoil; Redux Toolkit is the official, opinionated choice for large teams. Choosing the right tool depends on team size, complexity, and how much predictability you need.</p><p>Authentication flows — JWT storage, refresh tokens, protected routes, and role-based access control — are patterns every professional React developer must implement correctly and securely.</p>',
        'concepts' => [
            'Zustand: create store, slices, devtools middleware, persistence',
            'Jotai: atoms, derived atoms, async atoms',
            'Redux Toolkit: configureStore, createSlice, createAsyncThunk',
            'RTK Query: createApi, endpoints, auto-generated hooks',
            'JWT authentication: storing tokens (httpOnly cookies vs. memory), refresh strategy',
            'Protected routes and role-based rendering with React Router',
            'React context vs. state management libraries: when each is appropriate',
        ],
        'code' => [
            'title'   => 'Zustand auth store',
            'lang'    => 'javascript',
            'content' =>
"import { create } from 'zustand';
import { persist } from 'zustand/middleware';

const useAuthStore = create(
  persist(
    (set) => ({
      user:   null,
      token:  null,
      login:  (user, token) => set({ user, token }),
      logout: ()            => set({ user: null, token: null }),
      get isAdmin() { return this.user?.role === 'admin'; },
    }),
    { name: 'auth-storage' }  // persists to localStorage
  )
);

// Usage in component:
// const { user, login, logout } = useAuthStore();
// const isAdmin = useAuthStore(s => s.user?.role === 'admin');",
        ],
        'tips' => [
            'Store JWTs in httpOnly cookies — localStorage is accessible to XSS; httpOnly cookies are not.',
            'Use RTK Query when already using Redux; otherwise Zustand + TanStack Query is a lighter stack.',
            'Centralise protected route logic in a single RequireAuth component to avoid duplication.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Next.js is the most popular React framework, extending React with file-based routing, Server-Side Rendering (SSR), Static Site Generation (SSG), and API Routes. The App Router (introduced in Next.js 13) introduces React Server Components as first-class citizens, blending server and client rendering in the same component tree.</p><p>Remix is an alternative framework focused on web standards — HTML forms, HTTP semantics, and progressive enhancement. Understanding both frameworks makes you a versatile React developer capable of choosing the right tool for each project.</p>',
        'concepts' => [
            'Next.js App Router: page.tsx, layout.tsx, loading.tsx, error.tsx conventions',
            'Server Components vs. Client Components: "use client" directive',
            'Next.js data fetching: fetch() with cache options, revalidate',
            'Next.js Image, Link, Font, and Metadata optimisations',
            'Remix loaders and actions: server-side data and mutations with useLoaderData',
            'Remix progressive enhancement: forms that work without JavaScript',
            'Edge Runtime vs. Node.js runtime in Next.js and Remix',
        ],
        'code' => [
            'title'   => 'Next.js 14 Server Component with fetch',
            'lang'    => 'tsx',
            'content' =>
"// app/users/page.tsx  — Server Component (no 'use client')
interface User { id: number; name: string; email: string; }

async function getUsers(): Promise<User[]> {
  const res = await fetch('https://jsonplaceholder.typicode.com/users', {
    next: { revalidate: 3600 }, // ISR: revalidate every hour
  });
  if (!res.ok) throw new Error('Failed to fetch users');
  return res.json();
}

export default async function UsersPage() {
  const users = await getUsers();

  return (
    <main>
      <h1>Users</h1>
      <ul>
        {users.map(u => (
          <li key={u.id}>
            <strong>{u.name}</strong> — {u.email}
          </li>
        ))}
      </ul>
    </main>
  );
}",
        ],
        'tips' => [
            'Start new projects with Next.js App Router — it is the long-term direction for React.',
            'Default to Server Components; add "use client" only when you need interactivity or browser APIs.',
            'Use the Next.js <Image> component for all images — it provides automatic WebP, lazy loading, and sizing.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert-level React and modern web development encompasses micro-frontend architecture, advanced performance patterns (streaming SSR, partial hydration, islands architecture), and designing component libraries with accessibility, theming, and design tokens at scale.</p><p>Understanding Core Web Vitals from a React perspective — how bundle splitting, font loading, Server Components, and streaming affect LCP, INP, and CLS — and building automated performance budgets into CI/CD pipelines marks the transition from senior to staff-level front-end engineering.</p>',
        'concepts' => [
            'Micro-frontends: Module Federation with Vite/webpack, routing strategies',
            'Streaming SSR: renderToPipeableStream, Suspense-based shell rendering',
            'Partial hydration and islands architecture (Astro, fresh)',
            'Component library design: polymorphic components, compound components, slot patterns',
            'Design tokens and Radix Themes / shadcn/ui as a base',
            'Accessibility in React: focus management, live regions, keyboard traps in modals',
            'Core Web Vitals from a React lens: INP budget, bundle analysis, font strategy',
            'Performance budgets in CI: bundle-size checks, Lighthouse CI',
        ],
        'code' => [
            'title'   => 'Polymorphic "as" component pattern',
            'lang'    => 'tsx',
            'content' =>
"import React from 'react';

type PolymorphicProps<E extends React.ElementType> = {
  as?: E;
  children?: React.ReactNode;
  className?: string;
} & React.ComponentPropsWithoutRef<E>;

function Text<E extends React.ElementType = 'p'>({
  as,
  children,
  className,
  ...rest
}: PolymorphicProps<E>) {
  const Tag = as ?? 'p';
  return <Tag className={className} {...rest}>{children}</Tag>;
}

// Usage:
// <Text>Paragraph</Text>
// <Text as=\"h1\">Heading</Text>
// <Text as=\"label\" htmlFor=\"name\">Label</Text>",
        ],
        'tips' => [
            'Audit bundle size with @next/bundle-analyzer before each major release.',
            'Use shadcn/ui as a foundation — it gives you accessible, unstyled components you own and can customise.',
            'Treat INP (Interaction to Next Paint) as seriously as LCP — long tasks block the main thread.',
            'Study the Vercel and Remix engineering blogs for cutting-edge full-stack React patterns.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
