<?php
$tutorial_title = 'Svelte';
$tutorial_slug  = 'svelte';
$quiz_slug      = 'svelte';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Svelte takes a radically different approach to building user interfaces. Instead of shipping a runtime (like React or Vue do), Svelte compiles your components into highly optimised, vanilla JavaScript at build time. The result is smaller bundles, faster initial loads, and no virtual DOM overhead — reactivity is compiled into direct DOM updates.</p><p>This tier introduces Svelte\'s syntax — remarkably close to standard HTML — and the zero-boilerplate reactivity model where a simple assignment triggers a DOM update.</p>',
        'concepts' => [
            'Svelte\'s compilation model vs. runtime frameworks',
            'Component file structure: <script>, markup, <style scoped>',
            'Reactive declarations: let variable assignment triggers updates',
            'Reactive statements: $: label for derived values and side effects',
            'Event handling: on:click, on:submit, createEventDispatcher',
            'Bindings: bind:value, bind:checked, bind:this',
            'Project setup: npm create svelte@latest or SvelteKit',
        ],
        'code' => [
            'title'   => 'Svelte reactive counter',
            'lang'    => 'svelte',
            'content' =>
'<script>
  let count = 0
  $: double = count * 2
  $: if (count > 10) alert("Over ten!")

  function increment(step = 1) { count += step }
</script>

<div>
  <button on:click={() => increment(-1)}>−</button>
  <strong>{count}</strong>
  <button on:click={increment}>+</button>
  <p>Doubled: {double}</p>
</div>

<style>
  strong { font-size: 2rem; margin: 0 12px; }
</style>',
        ],
        'tips' => [
            'Svelte styles are scoped by default — you do not need CSS Modules or styled-components.',
            'The $: reactive label is powerful but easy to over-use; keep reactive statements simple and focused.',
            'Run the Svelte REPL (svelte.dev/repl) to prototype components without a local setup.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Svelte\'s template logic blocks — <code>{#if}</code>, <code>{#each}</code>, <code>{#await}</code> — handle conditional rendering, list iteration, and asynchronous data in a clean, familiar syntax. Component props use the <code>export let</code> convention, and custom events flow up via <code>createEventDispatcher</code> or event forwarding.</p><p>Svelte\'s <code>transition:</code> and <code>animate:</code> directives make adding polished motion trivially easy — no CSS class management or animation libraries required for common cases.</p>',
        'concepts' => [
            '{#if}/{:else if}/{:else} conditional blocks',
            '{#each items as item (key)} iteration with keyed lists',
            '{#await promise} blocks: pending, fulfilled, rejected states',
            'Component props: export let prop = defaultValue',
            'Spreading props: <Component {...$$props}>',
            'createEventDispatcher for custom events',
            'Event modifiers: on:click|preventDefault, on:click|once, on:click|stopPropagation',
            'Transitions: transition:fade, in:fly, out:scale with parameters',
        ],
        'code' => [
            'title'   => 'Async data fetch with {#await}',
            'lang'    => 'svelte',
            'content' =>
"<script>
  async function fetchUsers() {
    const res = await fetch('https://jsonplaceholder.typicode.com/users')
    if (!res.ok) throw new Error(res.statusText)
    return res.json()
  }

  let promise = fetchUsers()
</script>

{#await promise}
  <p>Loading users…</p>
{:then users}
  <ul>
    {#each users as user (user.id)}
      <li>{user.name} — {user.email}</li>
    {/each}
  </ul>
{:catch error}
  <p style=\"color: red\">{error.message}</p>
{/await}",
        ],
        'tips' => [
            'Always provide a key in {#each} loops to help Svelte reconcile list changes efficiently.',
            'Use {#await} for inline async data instead of lifecycle hooks — it is cleaner and self-contained.',
            'Event modifiers chain: on:click|preventDefault|stopPropagation is valid and readable.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Svelte stores are reactive data containers that live outside components. <code>writable</code>, <code>readable</code>, and <code>derived</code> stores cover most use cases, and the <code>$store</code> auto-subscription syntax makes them feel like local reactive variables in templates. For shared mutable state, stores replace the need for a dedicated state management library in most Svelte applications.</p><p>The Context API (<code>setContext</code> / <code>getContext</code>) enables dependency injection between parent and child components without prop drilling, and Svelte actions provide a clean way to encapsulate imperative DOM interactions (e.g., click-outside detection, tooltip anchoring).</p>',
        'concepts' => [
            'Writable stores: writable(initial), store.set(), store.update(), store.subscribe()',
            'Auto-subscription: $storeName in templates and scripts',
            'Readable stores: readable(initial, start/stop subscriber)',
            'Derived stores: derived(stores, deriveFn) for computed state',
            'Custom stores: returning a subset of the store interface',
            'Context API: setContext(key, value) and getContext(key)',
            'Svelte actions: use:action and action parameters',
            'Slots: default slot, named slots, slot props (let:)',
        ],
        'code' => [
            'title'   => 'Svelte writable store + derived',
            'lang'    => 'javascript',
            'content' =>
"// stores/cart.js
import { writable, derived } from 'svelte/store'

export const cartItems = writable([])

export const cartTotal = derived(
  cartItems,
  \$items => \$items.reduce((sum, item) => sum + item.price * item.qty, 0)
)

export const cartCount = derived(
  cartItems,
  \$items => \$items.reduce((sum, item) => sum + item.qty, 0)
)

export function addToCart(item) {
  cartItems.update(items => {
    const exists = items.find(i => i.id === item.id)
    if (exists) return items.map(i => i.id === item.id ? { ...i, qty: i.qty + 1 } : i)
    return [...items, { ...item, qty: 1 }]
  })
}",
        ],
        'tips' => [
            'Prefix store names with $ in scripts too (import { $store }) — it makes auto-subscription intent clear.',
            'Use derived() generously — they are lazy (only recalculate when subscribers exist) and very performant.',
            'Actions are Svelte\'s answer to React hooks for DOM-specific logic — prefer them over lifecycle hooks for DOM work.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>SvelteKit is the full-stack framework for Svelte — analogous to Next.js for React. It provides file-based routing, server-side rendering, API routes (form actions and load functions), and a flexible adapter system for deployment to Node.js, Cloudflare Workers, Vercel, Netlify, and static hosts.</p><p>Svelte 5 introduces Runes — a new reactivity primitive that replaces the magic $: label with explicit <code>$state</code>, <code>$derived</code>, and <code>$effect</code> runes, making reactivity more predictable and composable. Understanding the migration path from Svelte 4 to 5 is essential for teams adopting the framework today.</p>',
        'concepts' => [
            'SvelteKit routing: +page.svelte, +layout.svelte, +page.server.ts',
            'SvelteKit load functions: server load, shared load, streaming data',
            'SvelteKit form actions: default and named actions, use:enhance',
            'SvelteKit adapters: Node, static, Vercel, Cloudflare',
            'Svelte 5 Runes: $state, $derived, $effect, $props, $bindable',
            'Svelte 5 snippets and the render() function',
            'SvelteKit error handling: +error.svelte, error() helper',
        ],
        'code' => [
            'title'   => 'Svelte 5 Runes component',
            'lang'    => 'svelte',
            'content' =>
'<script>
  // Svelte 5 runes
  let count  = $state(0)
  let double = $derived(count * 2)

  $effect(() => {
    document.title = `Count: ${count}`
    return () => { document.title = "App" } // cleanup
  })
</script>

<button onclick={() => count--}>−</button>
<strong>{count}</strong>
<button onclick={() => count++}>+</button>
<p>Doubled: {double}</p>',
        ],
        'tips' => [
            'Use SvelteKit form actions for server mutations — they work with and without JavaScript (progressive enhancement).',
            'Svelte 5 runes are opt-in — existing Svelte 4 components continue to work alongside them.',
            'The SvelteKit load function co-location keeps data fetching close to the page that needs it.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Svelte development requires understanding the compiler output — what JavaScript Svelte generates from your components, why it is fast, and where performance cliffs exist. The Svelte compiler is open source and relatively small; reading it builds deep intuition for writing optimal components.</p><p>Building and publishing Svelte component libraries with package.json <code>exports</code>, TypeScript type generation, and Storybook integration, alongside contributing to the SvelteKit ecosystem through plugins and adapters, marks the expert practitioner.</p>',
        'concepts' => [
            'Svelte compiler output: analysing generated JavaScript for a component',
            'Svelte compiler options: generate, hydratable, accessors, immutable',
            'Performance: keyed vs. non-keyed lists, {#key} block for forced remount',
            'Svelte component library packaging: svelte field in package.json, preprocessors',
            'svelte-preprocess: TypeScript, SCSS, PostCSS in SFC files',
            'Storybook for Svelte: storybook/svelte-vite framework',
            'SSR and hydration in SvelteKit: how renderToHTML and hydrate work',
            'Writing SvelteKit plugins and hooks: handle, handleFetch, handleError',
        ],
        'code' => [
            'title'   => 'SvelteKit server hook for auth',
            'lang'    => 'typescript',
            'content' =>
"// src/hooks.server.ts
import type { Handle } from '@sveltejs/kit'
import { verifyJWT } from '\$lib/auth'

export const handle: Handle = async ({ event, resolve }) => {
  const token = event.cookies.get('session')

  if (token) {
    try {
      event.locals.user = await verifyJWT(token)
    } catch {
      event.cookies.delete('session', { path: '/' })
    }
  }

  // Block protected routes at the edge
  if (event.url.pathname.startsWith('/dashboard') && !event.locals.user) {
    return new Response(null, {
      status: 302,
      headers: { location: '/login' }
    })
  }

  return resolve(event)
}",
        ],
        'tips' => [
            'Read the Svelte compiler source (packages/svelte/src/compiler) — it is approachable and educational.',
            'Use {#key expr} to force a component to remount when expr changes — useful for animated route transitions.',
            'Follow Rich Harris\'s talks and the Svelte Discord for Svelte 5 migration insights.',
            'Package Svelte libraries with both ESM and CJS outputs and a svelte field for SvelteKit tree-shaking.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
