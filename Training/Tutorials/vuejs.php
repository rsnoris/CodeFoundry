<?php
$tutorial_title = 'Vue.js';
$tutorial_slug  = 'vuejs';
$quiz_slug      = 'vuejs';

$tutorial_tiers = [
    [
        'label'    => 'Introduction',
        'overview' => '<p>Vue.js is a progressive JavaScript framework for building user interfaces. "Progressive" means you can adopt it incrementally — drop a script tag into an existing page, or scaffold a full SPA. Vue is celebrated for its gentle learning curve, excellent documentation, and the elegance of its single-file component (SFC) format that co-locates template, script, and styles in one file.</p><p>This tier introduces the Composition API and the Vue 3 mental model, setting up a project with Vite, and building the first reactive component.</p>',
        'concepts' => [
            'Vue 3 Composition API vs. Options API: why Composition API is the modern choice',
            'Single-file components (SFC): <template>, <script setup>, <style>',
            'Project setup: npm create vue@latest (create-vue with Vite)',
            'Reactivity primitives: ref() for primitives, reactive() for objects',
            'Template syntax: text interpolation {{ }}, v-bind (:), v-on (@), v-model',
            'Computed properties: computed(() => ...) for derived values',
            'Watchers: watch() and watchEffect() for side effects',
        ],
        'code' => [
            'title'   => 'Vue 3 Composition API counter',
            'lang'    => 'vue',
            'content' =>
'<script setup>
import { ref, computed } from \'vue\'

const count  = ref(0)
const double = computed(() => count.value * 2)

function increment(step = 1) { count.value += step }
function reset()             { count.value = 0 }
</script>

<template>
  <div class="counter">
    <button @click="increment(-1)">−</button>
    <span>{{ count }}</span>
    <button @click="increment()">+</button>
    <p>Doubled: {{ double }}</p>
    <button @click="reset">Reset</button>
  </div>
</template>

<style scoped>
.counter { display: flex; align-items: center; gap: 12px; }
</style>',
        ],
        'tips' => [
            'Use <script setup> for all new components — it is terser, more performant, and fully TypeScript-friendly.',
            'Remember ref() values are accessed as .value in <script> but unwrapped automatically in <template>.',
            'Use the Vue DevTools browser extension to inspect reactive state and component trees.',
        ],
    ],
    [
        'label'    => 'Beginner',
        'overview' => '<p>Vue\'s built-in directives — <code>v-if</code>, <code>v-for</code>, <code>v-show</code>, <code>v-model</code>, and <code>v-bind</code> — cover the vast majority of template logic needs. Props and emits create the parent-child communication contract, while <code>defineExpose</code> selectively exposes a component\'s internals to parents via template refs.</p><p>Lifecycle hooks (<code>onMounted</code>, <code>onUnmounted</code>) integrate with the component lifecycle without classes or decorators, and Vue\'s <code>&lt;Transition&gt;</code> and <code>&lt;TransitionGroup&gt;</code> components add polished animations with minimal code.</p>',
        'concepts' => [
            'v-if / v-else-if / v-else vs. v-show: when to use each',
            'v-for with :key; iterating arrays and objects',
            'v-model modifiers: .trim, .number, .lazy',
            'Props: defineProps<{...}>() with TypeScript; required and default values',
            'Emits: defineEmits<{ eventName: [payload] }>() type safety',
            'Lifecycle hooks: onBeforeMount, onMounted, onUpdated, onUnmounted',
            'Template refs: ref="el" and useTemplateRef()',
            'Transition and TransitionGroup: enter-active-class, leave-active-class',
        ],
        'code' => [
            'title'   => 'Component with props and emits',
            'lang'    => 'vue',
            'content' =>
"<script setup lang=\"ts\">
const props = defineProps<{
  title: string
  items: string[]
  selected?: string
}>()

const emit = defineEmits<{
  select: [item: string]
  clear:  []
}>()
</script>

<template>
  <section>
    <h3>{{ title }}</h3>
    <ul>
      <li
        v-for=\"item in items\"
        :key=\"item\"
        :class=\"{ active: item === selected }\"
        @click=\"emit('select', item)\"
      >
        {{ item }}
      </li>
    </ul>
    <button @click=\"emit('clear')\">Clear</button>
  </section>
</template>",
        ],
        'tips' => [
            'Use v-show for elements that toggle frequently; v-if for elements rarely rendered (it destroys/creates the DOM).',
            'Emit payloads as typed tuples in defineEmits — TypeScript will enforce correct usage at call sites.',
            'Never mutate props directly — emit an event to the parent and let it update its own state.',
        ],
    ],
    [
        'label'    => 'Intermediate',
        'overview' => '<p>Composables are Vue 3\'s equivalent of React custom hooks — functions that use Vue\'s reactivity APIs to encapsulate and reuse stateful logic. The naming convention is <code>use</code> prefix (e.g., <code>useMouse</code>, <code>useFetch</code>), and VueUse provides a vast library of ready-made composables.</p><p>Vue Router 4 and Pinia (the official state management library) complete the production Vue 3 stack. Pinia\'s store design — with stores, getters, and actions — is simpler than Vuex and fully type-safe with TypeScript.</p>',
        'concepts' => [
            'Composables: useXxx functions encapsulating reactive logic',
            'VueUse library: useFetch, useMouse, useLocalStorage, useDark, and 200+ more',
            'Provide / inject: dependency injection for ancestor → descendant communication',
            'Vue Router 4: createRouter, RouterView, RouterLink, useRouter, useRoute',
            'Navigation guards: beforeEach, beforeEnter, meta fields',
            'Pinia: defineStore (options and setup syntax), storeToRefs',
            'Pinia persistence plugin and devtools integration',
        ],
        'code' => [
            'title'   => 'Pinia store with Composition API',
            'lang'    => 'typescript',
            'content' =>
"// stores/cart.ts
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

interface CartItem { id: number; name: string; price: number; qty: number }

export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])

  const total   = computed(() => items.value.reduce((s, i) => s + i.price * i.qty, 0))
  const isEmpty = computed(() => items.value.length === 0)

  function add(item: Omit<CartItem, 'qty'>) {
    const existing = items.value.find(i => i.id === item.id)
    if (existing) { existing.qty++ }
    else          { items.value.push({ ...item, qty: 1 }) }
  }

  function remove(id: number) {
    items.value = items.value.filter(i => i.id !== id)
  }

  function clear() { items.value = [] }

  return { items, total, isEmpty, add, remove, clear }
})",
        ],
        'tips' => [
            'Use storeToRefs() to destructure Pinia stores while keeping reactivity — plain destructuring loses it.',
            'Browse VueUse (vueuse.org) before writing a composable from scratch — most exist already.',
            'Use defineStore with the setup syntax for Pinia if you prefer the Composition API style.',
        ],
    ],
    [
        'label'    => 'Advanced',
        'overview' => '<p>Nuxt 3 is the Vue meta-framework that adds file-based routing, Server-Side Rendering, Static Site Generation, and a rich auto-import system on top of Vue 3 and Vite. Its composables (<code>useFetch</code>, <code>useAsyncData</code>, <code>useHead</code>) and server routes make it a full-stack framework comparable to Next.js.</p><p>Advanced Vue patterns include renderless components (components that provide logic without rendering markup), async components with <code>defineAsyncComponent</code>, and the teleport built-in component for rendering markup outside the component\'s DOM position.</p>',
        'concepts' => [
            'Nuxt 3: pages/ directory routing, layouts/, composables/ auto-import',
            'Nuxt useFetch and useAsyncData with server/client fetch strategies',
            'Nuxt server routes: server/api/ and server/middleware/',
            'Renderless components: providing logic via default slot scoped data',
            'defineAsyncComponent for lazy-loading heavy components',
            'Teleport: rendering into #modals or body outside the component tree',
            'Vue performance: v-memo, v-once, shallowRef, markRaw for non-reactive data',
        ],
        'code' => [
            'title'   => 'Nuxt 3 server route + useFetch',
            'lang'    => 'typescript',
            'content' =>
"// server/api/users.get.ts
export default defineEventHandler(async (event) => {
  const users = await \$fetch('https://jsonplaceholder.typicode.com/users')
  return users
})

// pages/users.vue
<script setup lang=\"ts\">
const { data: users, pending, error } = await useFetch('/api/users')
</script>

<template>
  <div>
    <div v-if=\"pending\">Loading…</div>
    <div v-else-if=\"error\">Error: {{ error.message }}</div>
    <ul v-else>
      <li v-for=\"user in users\" :key=\"user.id\">{{ user.name }}</li>
    </ul>
  </div>
</template>",
        ],
        'tips' => [
            'Nuxt auto-imports components, composables, and utils — no need to write import statements.',
            'Use useAsyncData with a unique key when you need more control than useFetch provides.',
            'Apply v-memo to static list items with changing parent state to avoid unnecessary reconciliation.',
        ],
    ],
    [
        'label'    => 'Expert',
        'overview' => '<p>Expert Vue development involves deep knowledge of Vue\'s reactivity system internals — how <code>track</code> and <code>trigger</code> work, the effect scheduler, and how computed properties use lazy evaluation. This understanding lets you debug complex reactivity issues and write custom reactive utilities.</p><p>Building and publishing Vue component libraries, writing custom Vite plugins for Vue SFC transforms, and contributing to the Vue ecosystem through open-source close this tier. Understanding Vue\'s virtual DOM algorithm and the optimisations the compiler applies to SFC templates — hoisting static nodes, caching event handlers — is the hallmark of expert-level framework knowledge.</p>',
        'concepts' => [
            'Vue reactivity internals: Proxy-based tracking, track(), trigger(), ReactiveEffect',
            'Effect scope and the effectScope() API',
            'Vue compiler optimisations: static hoisting, patch flags, tree flattening',
            'Custom renderer: createRenderer() for non-DOM targets (e.g., canvas, WebGL)',
            'Vue plugin authoring: app.use(), app.component(), app.directive(), app.provide()',
            'Vue component library: vite-plugin-dts, CSS variable theming, storybook integration',
            'Server-side rendering with createSSRApp and renderToString / renderToNodeStream',
            'Vue Test Utils 2: mount, shallowMount, flushPromises, VTU + Vitest',
        ],
        'code' => [
            'title'   => 'Custom Vue plugin',
            'lang'    => 'typescript',
            'content' =>
"// plugins/toast.ts
import { App, ref } from 'vue'
import ToastContainer from './ToastContainer.vue'

export interface Toast { id: number; message: string; type: 'success' | 'error' | 'info' }

const toasts = ref<Toast[]>([])
let nextId = 1

export function useToast() {
  function show(message: string, type: Toast['type'] = 'info') {
    const id = nextId++
    toasts.value.push({ id, message, type })
    setTimeout(() => { toasts.value = toasts.value.filter(t => t.id !== id) }, 3000)
  }
  return { toasts, show }
}

export default {
  install(app: App) {
    app.provide('toast', useToast())
    app.component('ToastContainer', ToastContainer)
  }
}",
        ],
        'tips' => [
            'Read the Vue 3 source code at github.com/vuejs/core — the reactivity package is well-commented.',
            'Use Vitest (not Jest) for Vue 3 projects — it understands Vite config and SFC transforms natively.',
            'Follow Evan You\'s announcements and the Vue RFC repo for upcoming language-level features.',
            'Contribute composables to VueUse — it is an excellent first open-source Vue contribution.',
        ],
    ],
];

require_once __DIR__ . '/tutorial-engine.php';
