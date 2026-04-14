<?php
$page_title  = 'Mobile App Development with React Native – CodeFoundry Training';
$active_page = 'training';
$page_styles = <<<'PAGECSS'
:root {
  --navy:         #0e1828;
  --navy-2:       #121c2b;
  --navy-3:       #161f2f;
  --primary:      #18b3ff;
  --primary-hover:#009de0;
  --text:         #fff;
  --text-muted:   #92a3bb;
  --text-subtle:  #627193;
  --border-color: #1a2942;
  --button-radius:8px;
  --maxwidth:     1200px;
  --card-radius:  12px;
  --header-height:68px;
}
html, body { background:var(--navy-2); color:var(--text); font-family:'Inter',sans-serif; margin:0; padding:0; }
body { min-height:100vh; }
a { color:inherit; text-decoration:none; }
.breadcrumb { max-width:var(--maxwidth); margin:0 auto; padding:20px 40px 0; display:flex; align-items:center; gap:8px; font-size:.85rem; color:var(--text-muted); flex-wrap:wrap; }
.breadcrumb a { color:var(--text-muted); transition:color .2s; }
.breadcrumb a:hover { color:var(--primary); }
.breadcrumb-sep { color:var(--text-subtle); }
.breadcrumb-current { color:var(--text); font-weight:600; }
.guide-hero { background:linear-gradient(135deg,var(--navy) 0%,#0d1e36 60%,#0a1826 100%); border-bottom:1px solid var(--border-color); padding:60px 40px 56px; position:relative; overflow:hidden; }
.guide-hero::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse 900px 500px at 50% -80px,rgba(24,179,255,.12) 0%,transparent 70%); pointer-events:none; }
.guide-hero-inner { max-width:var(--maxwidth); margin:0 auto; position:relative; }
.guide-badge { display:inline-flex; align-items:center; gap:8px; background:rgba(24,179,255,.1); border:1px solid rgba(24,179,255,.25); color:var(--primary); font-size:.72rem; font-weight:700; letter-spacing:.12em; text-transform:uppercase; padding:5px 14px; border-radius:100px; margin-bottom:20px; }
.guide-hero h1 { font-size:clamp(1.8rem,4vw,2.8rem); font-weight:900; line-height:1.15; margin:0 0 16px; background:linear-gradient(135deg,#fff 40%,var(--primary)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
.guide-hero-desc { max-width:680px; color:var(--text-muted); font-size:1.05rem; line-height:1.7; margin:0 0 28px; }
.guide-meta { display:flex; align-items:center; gap:20px; flex-wrap:wrap; }
.guide-meta-item { display:flex; align-items:center; gap:6px; color:var(--text-muted); font-size:.85rem; }
.guide-meta-item iconify-icon { color:var(--primary); font-size:1rem; }
.topic-tags { display:flex; gap:8px; flex-wrap:wrap; margin-top:16px; }
.topic-tag { background:rgba(24,179,255,.08); border:1px solid rgba(24,179,255,.2); color:var(--primary); font-size:.75rem; font-weight:600; padding:4px 12px; border-radius:100px; }
.guide-layout { max-width:var(--maxwidth); margin:0 auto; padding:48px 40px; display:grid; grid-template-columns:1fr 280px; gap:48px; align-items:start; }
@media(max-width:900px){ .guide-layout{ grid-template-columns:1fr; padding:32px 20px; } }
.toc-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:24px; position:sticky; top:calc(var(--header-height) + 20px); }
.toc-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--text-muted); margin:0 0 16px; }
.toc-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:4px; }
.toc-list a { display:flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:6px 10px; border-radius:6px; transition:all .2s; }
.toc-list a:hover { color:var(--primary); background:rgba(24,179,255,.06); }
.toc-num { font-size:.7rem; font-weight:700; color:var(--primary); min-width:18px; }
.back-link { display:inline-flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px; margin-top:20px; width:100%; box-sizing:border-box; justify-content:center; transition:all .2s; }
.back-link:hover { color:var(--primary); border-color:rgba(24,179,255,.4); background:rgba(24,179,255,.05); }
.guide-content { min-width:0; }
.guide-section { margin-bottom:56px; }
.guide-section:last-child { margin-bottom:0; }
.section-header { display:flex; align-items:center; gap:14px; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border-color); }
.section-num { display:flex; align-items:center; justify-content:center; width:36px; height:36px; background:rgba(24,179,255,.12); border:1px solid rgba(24,179,255,.25); border-radius:8px; color:var(--primary); font-weight:800; font-size:.9rem; flex-shrink:0; }
.section-header h2 { font-size:1.35rem; font-weight:800; margin:0; color:var(--text); }
.guide-section p { color:var(--text-muted); line-height:1.75; margin:0 0 16px; }
.guide-section ul, .guide-section ol { color:var(--text-muted); line-height:1.75; padding-left:24px; margin:0 0 16px; }
.guide-section li { margin-bottom:6px; }
.code-block { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; overflow:hidden; margin:20px 0; }
.code-block-header { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-bottom:1px solid var(--border-color); background:rgba(255,255,255,.02); }
.code-lang { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--primary); }
.code-filename { font-size:.78rem; color:var(--text-muted); font-family:'Fira Mono','Consolas',monospace; }
.code-block pre { margin:0; padding:20px; overflow-x:auto; }
.code-block code { font-family:'Fira Mono','Consolas','Courier New',monospace; font-size:.82rem; line-height:1.65; color:#c9d1d9; white-space:pre; }
.callout { display:flex; gap:14px; padding:18px 20px; border-radius:10px; margin:20px 0; }
.callout-tip  { background:rgba(24,179,255,.07); border:1px solid rgba(24,179,255,.2); }
.callout-warn { background:rgba(255,179,28,.07); border:1px solid rgba(255,179,28,.2); }
.callout-info { background:rgba(139,92,246,.07); border:1px solid rgba(139,92,246,.2); }
.callout-icon { font-size:1.3rem; flex-shrink:0; margin-top:1px; }
.callout-tip  .callout-icon { color:#18b3ff; }
.callout-warn .callout-icon { color:#ffb31c; }
.callout-info .callout-icon { color:#8b5cf6; }
.callout-body { flex:1; }
.callout-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; margin:0 0 6px; }
.callout-tip  .callout-title { color:#18b3ff; }
.callout-warn .callout-title { color:#ffb31c; }
.callout-info .callout-title { color:#8b5cf6; }
.callout-body p { color:var(--text-muted); font-size:.88rem; line-height:1.65; margin:0; }
.callout-body ul { color:var(--text-muted); font-size:.88rem; line-height:1.65; margin:8px 0 0; padding-left:20px; }
.two-col { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin:20px 0; }
@media(max-width:600px){ .two-col { grid-template-columns:1fr; } }
.info-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:10px; padding:20px; }
.info-card-title { font-size:.85rem; font-weight:700; color:var(--primary); margin:0 0 10px; display:flex; align-items:center; gap:8px; }
.info-card ul { color:var(--text-muted); font-size:.82rem; line-height:1.7; padding-left:18px; margin:0; }
.related-section { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:32px; margin-top:48px; }
.related-title { font-size:1.1rem; font-weight:800; margin:0 0 20px; display:flex; align-items:center; gap:10px; }
.related-title iconify-icon { color:var(--primary); }
.related-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:12px; }
.related-card { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; padding:16px; transition:border-color .2s,transform .2s; }
.related-card:hover { border-color:rgba(24,179,255,.4); transform:translateY(-2px); }
.related-card-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--primary); margin-bottom:6px; }
.related-card-title { font-size:.9rem; font-weight:600; color:var(--text); }
PAGECSS;
require_once __DIR__ . '/../../includes/header.php';
?>

<div class="breadcrumb">
  <a href="/Training/">Training</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <a href="/Training/Guides/">Implementation Guides</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <span class="breadcrumb-current">Mobile App Development with React Native</span>
</div>

<section class="guide-hero">
  <div class="guide-hero-inner">
    <div class="guide-badge"><iconify-icon icon="lucide:smartphone"></iconify-icon> Implementation Guide</div>
    <h1>Mobile App Development with React Native</h1>
    <p class="guide-hero-desc">Build production-quality iOS and Android apps from a single TypeScript codebase — covering navigation, state management, native APIs, and App Store submission.</p>
    <div class="guide-meta">
      <div class="guide-meta-item"><iconify-icon icon="lucide:clock"></iconify-icon> 40 min read</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Intermediate</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:calendar"></iconify-icon> Updated 2025</div>
    </div>
    <div class="topic-tags">
      <span class="topic-tag">React Native</span>
      <span class="topic-tag">Mobile</span>
      <span class="topic-tag">iOS</span>
      <span class="topic-tag">Android</span>
    </div>
  </div>
</section>

<div class="guide-layout">
  <main class="guide-content">

    <!-- Section 1 -->
    <div class="guide-section" id="s1">
      <div class="section-header">
        <div class="section-num">1</div>
        <h2>React Native Overview</h2>
      </div>
      <p>React Native renders real native UI components — not WebViews. Your JavaScript code runs in a separate thread and communicates with the native side via the <strong>New Architecture's JSI (JavaScript Interface)</strong>, which replaced the legacy asynchronous bridge with synchronous, direct memory access.</p>
      <div class="two-col">
        <div class="info-card">
          <div class="info-card-title"><iconify-icon icon="lucide:zap"></iconify-icon> Expo (Managed Workflow)</div>
          <ul>
            <li>Zero native toolchain setup</li>
            <li>OTA updates via Expo Updates</li>
            <li>Pre-built native modules</li>
            <li>EAS Build for App Store</li>
            <li>Best for: most apps, fast iteration</li>
          </ul>
        </div>
        <div class="info-card">
          <div class="info-card-title"><iconify-icon icon="lucide:settings-2"></iconify-icon> Bare Workflow / CLI</div>
          <ul>
            <li>Full Xcode + Android Studio control</li>
            <li>Any native module available</li>
            <li>Custom native code (Swift/Kotlin)</li>
            <li>Requires iOS/Android dev environment</li>
            <li>Best for: complex native integrations</li>
          </ul>
        </div>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Start with Expo</div>
          <p>The vast majority of apps are well-served by Expo's managed workflow. You can always "eject" to a bare workflow later if you need custom native code. Starting bare means managing Xcode and Android Studio from day one — unnecessary overhead for most projects.</p>
        </div>
      </div>
    </div>

    <!-- Section 2 -->
    <div class="guide-section" id="s2">
      <div class="section-header">
        <div class="section-num">2</div>
        <h2>Project Setup</h2>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">bash</span><span class="code-filename">Bootstrap with Expo + TypeScript</span></div>
        <pre><code>npx create-expo-app@latest MyApp --template blank-typescript
cd MyApp
npx expo install expo-router react-native-safe-area-context react-native-screens
npm install @tanstack/react-query zustand axios</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">json</span><span class="code-filename">app.json (Expo config)</span></div>
        <pre><code>{
  "expo": {
    "name": "MyApp",
    "slug": "myapp",
    "version": "1.0.0",
    "scheme": "myapp",
    "orientation": "portrait",
    "icon": "./assets/icon.png",
    "splash": {
      "image": "./assets/splash.png",
      "resizeMode": "contain",
      "backgroundColor": "#0e1828"
    },
    "ios": {
      "supportsTablet": true,
      "bundleIdentifier": "com.myorg.myapp",
      "infoPlist": {
        "NSLocationWhenInUseUsageDescription": "Used to show nearby content",
        "NSCameraUsageDescription": "Used to capture photos"
      }
    },
    "android": {
      "package": "com.myorg.myapp",
      "adaptiveIcon": {
        "foregroundImage": "./assets/adaptive-icon.png",
        "backgroundColor": "#0e1828"
      },
      "permissions": ["CAMERA", "ACCESS_FINE_LOCATION"]
    },
    "plugins": [
      "expo-router",
      ["expo-notifications", { "sounds": ["./assets/notification.wav"] }]
    ]
  }
}</code></pre>
      </div>
    </div>

    <!-- Section 3 -->
    <div class="guide-section" id="s3">
      <div class="section-header">
        <div class="section-num">3</div>
        <h2>Navigation</h2>
      </div>
      <p>Expo Router uses a file-based routing system (similar to Next.js) built on React Navigation. Files in the <code>app/</code> directory automatically become routes.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">text</span><span class="code-filename">app/ directory structure</span></div>
        <pre><code>app/
├── _layout.tsx          # Root layout (providers, auth guard)
├── index.tsx            # "/" → Home screen
├── (tabs)/
│   ├── _layout.tsx      # Tab bar layout
│   ├── feed.tsx         # "/feed"
│   ├── search.tsx       # "/search"
│   └── profile.tsx      # "/profile"
└── post/
    └── [id].tsx         # "/post/:id" dynamic route</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">app/(tabs)/_layout.tsx</span></div>
        <pre><code>import { Tabs } from 'expo-router';
import { Ionicons } from '@expo/vector-icons';

export default function TabLayout() {
  return (
    <Tabs
      screenOptions={{
        headerShown: false,
        tabBarStyle: {
          backgroundColor: '#0e1828',
          borderTopColor: '#1a2942',
        },
        tabBarActiveTintColor: '#18b3ff',
        tabBarInactiveTintColor: '#92a3bb',
      }}
    >
      <Tabs.Screen
        name="feed"
        options={{
          title: 'Feed',
          tabBarIcon: ({ color, size }) => (
            <Ionicons name="home-outline" size={size} color={color} />
          ),
        }}
      />
      <Tabs.Screen
        name="search"
        options={{
          title: 'Search',
          tabBarIcon: ({ color, size }) => (
            <Ionicons name="search-outline" size={size} color={color} />
          ),
        }}
      />
    </Tabs>
  );
}</code></pre>
      </div>
    </div>

    <!-- Section 4 -->
    <div class="guide-section" id="s4">
      <div class="section-header">
        <div class="section-num">4</div>
        <h2>State Management</h2>
      </div>
      <p>Separate <strong>server state</strong> (remote data — use TanStack Query) from <strong>client state</strong> (UI state — use Zustand or Context). This separation keeps components clean and avoids the complexity of managing cache invalidation manually.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">store/authStore.ts (Zustand)</span></div>
        <pre><code>import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';
import AsyncStorage from '@react-native-async-storage/async-storage';

interface AuthState {
  token:   string | null;
  userId:  string | null;
  setAuth: (token: string, userId: string) => void;
  logout:  () => void;
}

export const useAuthStore = create<AuthState>()(
  persist(
    (set) => ({
      token:   null,
      userId:  null,
      setAuth: (token, userId) => set({ token, userId }),
      logout:  () => set({ token: null, userId: null }),
    }),
    {
      name:    'auth-storage',
      storage: createJSONStorage(() => AsyncStorage),
    }
  )
);</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">hooks/useFeed.ts (TanStack Query)</span></div>
        <pre><code>import { useInfiniteQuery } from '@tanstack/react-query';
import { api } from '../services/api';

export function useFeed() {
  return useInfiniteQuery({
    queryKey: ['feed'],
    queryFn:  ({ pageParam = 1 }) =>
      api.get(`/posts?page=${pageParam}&limit=15`).then(r => r.data),
    getNextPageParam: (lastPage) =>
      lastPage.meta.page < lastPage.meta.pages
        ? lastPage.meta.page + 1
        : undefined,
    initialPageParam: 1,
    staleTime: 2 * 60 * 1000,   // 2 minutes
  });
}</code></pre>
      </div>
    </div>

    <!-- Section 5 -->
    <div class="guide-section" id="s5">
      <div class="section-header">
        <div class="section-num">5</div>
        <h2>Native UI Components</h2>
      </div>
      <p>React Native's core components map directly to native UI widgets. <code>View</code> = <code>UIView</code> / <code>android.view.View</code>. Layout uses Flexbox with the same properties as CSS, but with different defaults (column direction, no inherited styles).</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">Performant FlatList with skeleton loading</span></div>
        <pre><code>import { FlatList, View, Text, StyleSheet, ActivityIndicator } from 'react-native';
import { useFeed } from '../hooks/useFeed';
import { PostCard } from './PostCard';

export function FeedScreen() {
  const { data, fetchNextPage, hasNextPage, isFetchingNextPage, isLoading } = useFeed();

  const posts = data?.pages.flatMap(p => p.data) ?? [];

  if (isLoading) return <ActivityIndicator color="#18b3ff" style={styles.loader} />;

  return (
    <FlatList
      data={posts}
      keyExtractor={item => item.id}
      renderItem={({ item }) => <PostCard post={item} />}
      onEndReached={() => hasNextPage && fetchNextPage()}
      onEndReachedThreshold={0.5}
      contentContainerStyle={styles.list}
      ListFooterComponent={
        isFetchingNextPage
          ? <ActivityIndicator color="#18b3ff" style={styles.footer} />
          : null
      }
      // Performance optimisations
      removeClippedSubviews
      maxToRenderPerBatch={8}
      windowSize={10}
      initialNumToRender={6}
    />
  );
}

const styles = StyleSheet.create({
  loader: { flex: 1, justifyContent: 'center' },
  list:   { paddingHorizontal: 16, paddingTop: 8 },
  footer: { paddingVertical: 20 },
});</code></pre>
      </div>
    </div>

    <!-- Section 6 -->
    <div class="guide-section" id="s6">
      <div class="section-header">
        <div class="section-num">6</div>
        <h2>Native Module Integration</h2>
      </div>
      <p>Expo provides JavaScript wrappers for most native APIs. For custom native functionality, use Expo Modules API to write Swift/Kotlin native modules with a clean TypeScript interface.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">Camera + geolocation + push notifications</span></div>
        <pre><code>import * as Camera         from 'expo-camera';
import * as Location       from 'expo-location';
import * as Notifications  from 'expo-notifications';

// Camera
async function takePicture() {
  const { status } = await Camera.requestCameraPermissionsAsync();
  if (status !== 'granted') return;
  // Camera component handles the rest
}

// Geolocation
async function getCurrentLocation() {
  const { status } = await Location.requestForegroundPermissionsAsync();
  if (status !== 'granted') throw new Error('Location denied');
  const location = await Location.getCurrentPositionAsync({
    accuracy: Location.Accuracy.Balanced,
  });
  return { lat: location.coords.latitude, lng: location.coords.longitude };
}

// Push notifications
async function registerForPushNotifications(): Promise<string | null> {
  const { status } = await Notifications.requestPermissionsAsync();
  if (status !== 'granted') return null;

  const token = await Notifications.getExpoPushTokenAsync({
    projectId: process.env.EXPO_PUBLIC_PROJECT_ID,
  });
  return token.data;  // Send this to your server
}

// Handle foreground notifications
Notifications.setNotificationHandler({
  handleNotification: async () => ({
    shouldShowAlert: true,
    shouldPlaySound: true,
    shouldSetBadge:  true,
  }),
});</code></pre>
      </div>
    </div>

    <!-- Section 7 -->
    <div class="guide-section" id="s7">
      <div class="section-header">
        <div class="section-num">7</div>
        <h2>API Integration</h2>
      </div>
      <p>Use Axios with TanStack Query for most API needs. Configure a base Axios instance that reads the auth token from Zustand and handles token refresh — the same pattern as the full-stack guide.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">services/api.ts (mobile)</span></div>
        <pre><code>import axios from 'axios';
import { useAuthStore } from '../store/authStore';

export const api = axios.create({
  baseURL: process.env.EXPO_PUBLIC_API_URL,
  timeout: 10_000,
});

api.interceptors.request.use(config => {
  const token = useAuthStore.getState().token;
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

api.interceptors.response.use(
  res => res,
  async err => {
    if (err.response?.status === 401) {
      useAuthStore.getState().logout();
    }
    return Promise.reject(err);
  }
);

// Offline support: queue failed requests
import NetInfo from '@react-native-community/netinfo';

NetInfo.addEventListener(state => {
  if (state.isConnected) {
    // flush queued requests
  }
});</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Mobile-Specific API Considerations</div>
          <ul>
            <li>Always set a timeout — mobile networks are unreliable. 10 seconds is a reasonable default.</li>
            <li>Use TanStack Query's <code>networkMode: 'offlineFirst'</code> to serve stale cache data when offline.</li>
            <li>Implement exponential backoff on retry for failed network requests.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 8 -->
    <div class="guide-section" id="s8">
      <div class="section-header">
        <div class="section-num">8</div>
        <h2>Testing and Publishing</h2>
      </div>
      <p>Test React Native apps with Jest (unit/integration) and Detox (E2E on real simulators/devices). Use EAS Build to produce App Store and Google Play binaries without managing Xcode or Android Studio locally.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">Component test with @testing-library/react-native</span></div>
        <pre><code>import { render, screen, fireEvent } from '@testing-library/react-native';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { LoginScreen } from '../screens/LoginScreen';

const wrapper = ({ children }: any) => (
  <QueryClientProvider client={new QueryClient()}>
    {children}
  </QueryClientProvider>
);

describe('LoginScreen', () => {
  it('shows validation error for empty email', async () => {
    render(<LoginScreen />, { wrapper });

    fireEvent.press(screen.getByRole('button', { name: /sign in/i }));

    expect(await screen.findByText(/email is required/i)).toBeTruthy();
  });

  it('calls login with correct credentials', async () => {
    const mockLogin = jest.fn();
    render(<LoginScreen onLogin={mockLogin} />, { wrapper });

    fireEvent.changeText(screen.getByPlaceholderText(/email/i), 'test@example.com');
    fireEvent.changeText(screen.getByPlaceholderText(/password/i), 'password123');
    fireEvent.press(screen.getByRole('button', { name: /sign in/i }));

    await screen.findByText(/welcome/i);
    expect(mockLogin).toHaveBeenCalledWith('test@example.com', 'password123');
  });
});</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">bash</span><span class="code-filename">EAS Build + Submit</span></div>
        <pre><code># Install EAS CLI
npm install -g eas-cli && eas login

# Configure builds
eas build:configure

# Build for both platforms (production)
eas build --platform all --profile production

# Submit to App Store Connect and Google Play
eas submit --platform ios    --profile production
eas submit --platform android --profile production

# OTA update (no app store review needed for JS changes)
eas update --branch production --message "Fix login bug"</code></pre>
      </div>
    </div>

    <div class="related-section">
      <div class="related-title"><iconify-icon icon="lucide:link"></iconify-icon> Related Resources</div>
      <div class="related-grid">
        <a href="/Training/Tutorials/react.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">React Fundamentals</div>
        </a>
        <a href="/Training/Tutorials/typescript.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">TypeScript</div>
        </a>
        <a href="/Training/Guides/fullstack-web-app.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Full-Stack Web App</div>
        </a>
        <a href="/Training/Tutorials/flutter.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">Flutter Alternative</div>
        </a>
      </div>
    </div>

  </main>

  <aside>
    <div class="toc-card">
      <div class="toc-title">Contents</div>
      <ul class="toc-list">
        <li><a href="#s1"><span class="toc-num">1</span> RN Overview</a></li>
        <li><a href="#s2"><span class="toc-num">2</span> Project Setup</a></li>
        <li><a href="#s3"><span class="toc-num">3</span> Navigation</a></li>
        <li><a href="#s4"><span class="toc-num">4</span> State Management</a></li>
        <li><a href="#s5"><span class="toc-num">5</span> Native UI</a></li>
        <li><a href="#s6"><span class="toc-num">6</span> Native Modules</a></li>
        <li><a href="#s7"><span class="toc-num">7</span> API Integration</a></li>
        <li><a href="#s8"><span class="toc-num">8</span> Testing & Publishing</a></li>
      </ul>
      <a href="/Training/Guides/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Guides</a>
    </div>
  </aside>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
