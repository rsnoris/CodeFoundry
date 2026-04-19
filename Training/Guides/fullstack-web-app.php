<?php
$page_title  = 'Building a Full-Stack Web Application – CodeFoundry Training';
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

/* ── Breadcrumb ── */
.breadcrumb { max-width:var(--maxwidth); margin:0 auto; padding:20px 40px 0; display:flex; align-items:center; gap:8px; font-size:.85rem; color:var(--text-muted); flex-wrap:wrap; }
.breadcrumb a { color:var(--text-muted); transition:color .2s; }
.breadcrumb a:hover { color:var(--primary); }
.breadcrumb-sep { color:var(--text-subtle); }
.breadcrumb-current { color:var(--text); font-weight:600; }

/* ── Hero ── */
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

/* ── Layout ── */
.guide-layout { max-width:var(--maxwidth); margin:0 auto; padding:48px 40px; display:grid; grid-template-columns:1fr 280px; gap:48px; align-items:start; }
@media(max-width:900px){ .guide-layout{ grid-template-columns:1fr; padding:32px 20px; } }

/* ── TOC Sidebar ── */
.toc-card { background:var(--navy-3); border:1px solid var(--border-color); border-radius:var(--card-radius); padding:24px; position:sticky; top:calc(var(--header-height) + 20px); }
.toc-title { font-size:.8rem; font-weight:700; text-transform:uppercase; letter-spacing:.1em; color:var(--text-muted); margin:0 0 16px; }
.toc-list { list-style:none; margin:0; padding:0; display:flex; flex-direction:column; gap:4px; }
.toc-list a { display:flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:6px 10px; border-radius:6px; transition:all .2s; }
.toc-list a:hover { color:var(--primary); background:rgba(24,179,255,.06); }
.toc-num { font-size:.7rem; font-weight:700; color:var(--primary); min-width:18px; }
.back-link { display:inline-flex; align-items:center; gap:8px; color:var(--text-muted); font-size:.85rem; padding:10px 14px; border:1px solid var(--border-color); border-radius:8px; margin-top:20px; width:100%; box-sizing:border-box; justify-content:center; transition:all .2s; }
.back-link:hover { color:var(--primary); border-color:rgba(24,179,255,.4); background:rgba(24,179,255,.05); }

/* ── Content ── */
.guide-content { min-width:0; }
.guide-section { margin-bottom:56px; }
.guide-section:last-child { margin-bottom:0; }
.section-header { display:flex; align-items:center; gap:14px; margin-bottom:20px; padding-bottom:16px; border-bottom:1px solid var(--border-color); }
.section-num { display:flex; align-items:center; justify-content:center; width:36px; height:36px; background:rgba(24,179,255,.12); border:1px solid rgba(24,179,255,.25); border-radius:8px; color:var(--primary); font-weight:800; font-size:.9rem; flex-shrink:0; }
.section-header h2 { font-size:1.35rem; font-weight:800; margin:0; color:var(--text); }
.guide-section p { color:var(--text-muted); line-height:1.75; margin:0 0 16px; }
.guide-section ul, .guide-section ol { color:var(--text-muted); line-height:1.75; padding-left:24px; margin:0 0 16px; }
.guide-section li { margin-bottom:6px; }

/* ── Code blocks ── */
.code-block { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; overflow:hidden; margin:20px 0; }
.code-block-header { display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-bottom:1px solid var(--border-color); background:rgba(255,255,255,.02); }
.code-lang { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:var(--primary); }
.code-filename { font-size:.78rem; color:var(--text-muted); font-family:'Fira Mono','Consolas',monospace; }
.code-block pre { margin:0; padding:20px; overflow-x:auto; }
.code-block code { font-family:'Fira Mono','Consolas','Courier New',monospace; font-size:.82rem; line-height:1.65; color:#c9d1d9; white-space:pre; }

/* ── Callout boxes ── */
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

/* ── Arch diagram ── */
.arch-diagram { background:var(--navy); border:1px solid var(--border-color); border-radius:10px; padding:28px; margin:20px 0; }
.arch-row { display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap; margin-bottom:12px; }
.arch-box { background:var(--navy-3); border:1px solid var(--border-color); border-radius:8px; padding:10px 18px; font-size:.82rem; font-weight:600; text-align:center; color:var(--text); }
.arch-box.primary { border-color:rgba(24,179,255,.4); background:rgba(24,179,255,.08); color:var(--primary); }
.arch-arrow { color:var(--text-subtle); font-size:1.2rem; }
.arch-label { font-size:.72rem; color:var(--text-subtle); text-align:center; margin-top:4px; }

/* ── Related resources ── */
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

<!-- Breadcrumb -->
<div class="breadcrumb">
  <a href="/Training/">Training</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <a href="/Training/Guides/">Implementation Guides</a>
  <span class="breadcrumb-sep"><iconify-icon icon="lucide:chevron-right"></iconify-icon></span>
  <span class="breadcrumb-current">Building a Full-Stack Web Application</span>
</div>

<!-- Hero -->
<section class="guide-hero">
  <div class="guide-hero-inner">
    <div class="guide-badge"><iconify-icon icon="lucide:layers"></iconify-icon> Implementation Guide</div>
    <h1>Building a Full-Stack Web Application</h1>
    <p class="guide-hero-desc">A complete end-to-end walkthrough of architecting, building, and deploying a production-grade full-stack application using React, Node.js, MongoDB, and JWT authentication.</p>
    <div class="guide-meta">
      <div class="guide-meta-item"><iconify-icon icon="lucide:clock"></iconify-icon> 45 min read</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon> Advanced</div>
      <div class="guide-meta-item"><iconify-icon icon="lucide:calendar"></iconify-icon> Updated 2025</div>
    </div>
    <div class="topic-tags">
      <span class="topic-tag">React</span>
      <span class="topic-tag">Node.js</span>
      <span class="topic-tag">MongoDB</span>
      <span class="topic-tag">JWT Auth</span>
      <span class="topic-tag">REST API</span>
    </div>
  </div>
</section>

<!-- Layout -->
<div class="guide-layout">

  <!-- Main content -->
  <main class="guide-content">

    <!-- Section 1 -->
    <div class="guide-section" id="s1">
      <div class="section-header">
        <div class="section-num">1</div>
        <h2>Project Architecture Overview</h2>
      </div>
      <p>A modern full-stack application separates concerns across distinct layers: a <strong>React SPA</strong> (client), a <strong>Node.js + Express REST API</strong> (server), and a <strong>MongoDB</strong> database. Communication flows through HTTP/JSON for CRUD operations and WebSockets for real-time events.</p>
      <div class="arch-diagram">
        <div class="arch-row">
          <div class="arch-box primary">React SPA<div class="arch-label">Port 3000</div></div>
          <div class="arch-arrow">⟺</div>
          <div class="arch-box primary">Express API<div class="arch-label">Port 5000</div></div>
          <div class="arch-arrow">⟺</div>
          <div class="arch-box primary">MongoDB<div class="arch-label">Port 27017</div></div>
        </div>
        <div class="arch-row">
          <div class="arch-box">Axios / Fetch</div>
          <div class="arch-arrow">→</div>
          <div class="arch-box">JWT Middleware</div>
          <div class="arch-arrow">→</div>
          <div class="arch-box">Mongoose ODM</div>
        </div>
        <div class="arch-row">
          <div class="arch-box">Socket.io Client</div>
          <div class="arch-arrow">⟺</div>
          <div class="arch-box">Socket.io Server</div>
        </div>
      </div>
      <p><strong>Tech stack rationale:</strong> React's component model scales well as UI complexity grows. Express is minimal and composable — you wire only what you need. MongoDB's document model maps naturally to JavaScript objects, and the flexible schema suits iterative development. JWTs provide stateless, scalable authentication without server-side sessions.</p>
      <div class="callout callout-info">
        <div class="callout-icon"><iconify-icon icon="lucide:info"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Monorepo Structure</div>
          <p>Organise your project as a monorepo: <code>/client</code> (React), <code>/server</code> (Express), and a root <code>docker-compose.yml</code>. This keeps CI/CD simple and lets you share TypeScript types between layers via a <code>/shared</code> package.</p>
        </div>
      </div>
    </div>

    <!-- Section 2 -->
    <div class="guide-section" id="s2">
      <div class="section-header">
        <div class="section-num">2</div>
        <h2>Setting Up the Backend</h2>
      </div>
      <p>Initialise the Node.js server with a clean, maintainable folder structure. Install Express, Mongoose, dotenv, and the essential middleware packages first.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">bash</span><span class="code-filename">terminal</span></div>
        <pre><code>mkdir server && cd server
npm init -y
npm install express mongoose dotenv cors helmet morgan bcryptjs jsonwebtoken express-validator
npm install -D nodemon typescript ts-node @types/node @types/express</code></pre>
      </div>
      <p>Adopt a feature-based folder structure so each domain (users, posts, etc.) owns its routes, controller, service, and model:</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">text</span><span class="code-filename">server/ folder structure</span></div>
        <pre><code>server/
├── src/
│   ├── config/         # db.ts, env.ts
│   ├── middleware/      # auth.ts, errorHandler.ts, validate.ts
│   ├── modules/
│   │   ├── users/      # user.model.ts, user.routes.ts, user.controller.ts
│   │   └── posts/
│   ├── utils/          # logger.ts, ApiError.ts
│   └── app.ts          # Express app factory
├── server.ts            # Entry: connects DB, starts HTTP + Socket server
└── .env</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">src/app.ts</span></div>
        <pre><code>import express from 'express';
import cors    from 'cors';
import helmet  from 'helmet';
import morgan  from 'morgan';
import { userRouter } from './modules/users/user.routes';
import { postRouter } from './modules/posts/post.routes';
import { errorHandler } from './middleware/errorHandler';

export function createApp() {
  const app = express();

  // Security & logging
  app.use(helmet());
  app.use(cors({ origin: process.env.CLIENT_URL, credentials: true }));
  app.use(morgan('dev'));
  app.use(express.json());

  // Routes
  app.use('/api/users', userRouter);
  app.use('/api/posts', postRouter);

  // Central error handler (must be last)
  app.use(errorHandler);
  return app;
}</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">src/config/db.ts</span></div>
        <pre><code>import mongoose from 'mongoose';

export async function connectDB() {
  const uri = process.env.MONGO_URI;
  if (!uri) throw new Error('MONGO_URI not set');

  await mongoose.connect(uri, {
    serverSelectionTimeoutMS: 5000,
    maxPoolSize: 10,
  });
  console.log('✅ MongoDB connected');
}</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Best Practices</div>
          <ul>
            <li>Never commit <code>.env</code> — add it to <code>.gitignore</code> and use <code>.env.example</code> for documentation.</li>
            <li>Use <code>helmet()</code> to set secure HTTP headers with a single line.</li>
            <li>Set <code>maxPoolSize</code> on the Mongoose connection to prevent connection exhaustion under load.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 3 -->
    <div class="guide-section" id="s3">
      <div class="section-header">
        <div class="section-num">3</div>
        <h2>Building the REST API</h2>
      </div>
      <p>Define a Mongoose schema, then wire CRUD controllers to Express routes. Follow the single-responsibility principle — routes declare paths, controllers call service functions, services hold business logic.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">modules/posts/post.model.ts</span></div>
        <pre><code>import { Schema, model, Document, Types } from 'mongoose';

export interface IPost extends Document {
  title:     string;
  body:      string;
  author:    Types.ObjectId;
  tags:      string[];
  published: boolean;
  createdAt: Date;
}

const PostSchema = new Schema<IPost>({
  title:     { type: String, required: true, trim: true, maxlength: 200 },
  body:      { type: String, required: true },
  author:    { type: Schema.Types.ObjectId, ref: 'User', required: true },
  tags:      [{ type: String, lowercase: true, trim: true }],
  published: { type: Boolean, default: false },
}, { timestamps: true });

PostSchema.index({ author: 1, createdAt: -1 });
PostSchema.index({ tags: 1 });

export const Post = model<IPost>('Post', PostSchema);</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">modules/posts/post.controller.ts</span></div>
        <pre><code>import { Request, Response, NextFunction } from 'express';
import { Post } from './post.model';
import { ApiError } from '../../utils/ApiError';

export async function getPosts(req: Request, res: Response, next: NextFunction) {
  try {
    const page  = Math.max(1, Number(req.query.page)  || 1);
    const limit = Math.min(50, Number(req.query.limit) || 10);
    const skip  = (page - 1) * limit;

    const [posts, total] = await Promise.all([
      Post.find({ published: true })
          .populate('author', 'name avatar')
          .sort({ createdAt: -1 })
          .skip(skip).limit(limit),
      Post.countDocuments({ published: true }),
    ]);

    res.json({ data: posts, meta: { page, limit, total, pages: Math.ceil(total / limit) } });
  } catch (err) { next(err); }
}

export async function createPost(req: Request, res: Response, next: NextFunction) {
  try {
    const post = await Post.create({ ...req.body, author: req.user!._id });
    res.status(201).json(post);
  } catch (err) { next(err); }
}

export async function updatePost(req: Request, res: Response, next: NextFunction) {
  try {
    const post = await Post.findOneAndUpdate(
      { _id: req.params.id, author: req.user!._id },
      req.body,
      { new: true, runValidators: true }
    );
    if (!post) throw new ApiError(404, 'Post not found');
    res.json(post);
  } catch (err) { next(err); }
}</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">middleware/errorHandler.ts</span></div>
        <pre><code>import { Request, Response, NextFunction } from 'express';
import { ApiError } from '../utils/ApiError';

export function errorHandler(
  err: Error, _req: Request, res: Response, _next: NextFunction
) {
  if (err instanceof ApiError) {
    return res.status(err.statusCode).json({ error: err.message });
  }
  // Mongoose validation error
  if (err.name === 'ValidationError') {
    return res.status(400).json({ error: err.message });
  }
  console.error(err);
  res.status(500).json({ error: 'Internal server error' });
}</code></pre>
      </div>
    </div>

    <!-- Section 4 -->
    <div class="guide-section" id="s4">
      <div class="section-header">
        <div class="section-num">4</div>
        <h2>JWT Authentication</h2>
      </div>
      <p>Implement a two-token strategy: a short-lived <strong>access token</strong> (15 min) sent in the Authorization header, and a long-lived <strong>refresh token</strong> (7 days) stored in an HttpOnly cookie to prevent XSS theft.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">modules/users/user.model.ts</span></div>
        <pre><code>import { Schema, model, Document } from 'mongoose';
import bcrypt from 'bcryptjs';

export interface IUser extends Document {
  name:          string;
  email:         string;
  passwordHash:  string;
  role:          'user' | 'admin';
  refreshTokens: string[];
  comparePassword(plain: string): Promise<boolean>;
}

const UserSchema = new Schema<IUser>({
  name:          { type: String, required: true, trim: true },
  email:         { type: String, required: true, unique: true, lowercase: true },
  passwordHash:  { type: String, required: true },
  role:          { type: String, enum: ['user','admin'], default: 'user' },
  refreshTokens: [String],
}, { timestamps: true });

UserSchema.pre('save', async function () {
  if (this.isModified('passwordHash'))
    this.passwordHash = await bcrypt.hash(this.passwordHash, 12);
});
UserSchema.methods.comparePassword = function (plain: string) {
  return bcrypt.compare(plain, this.passwordHash);
};

export const User = model<IUser>('User', UserSchema);</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">utils/tokens.ts</span></div>
        <pre><code>import jwt from 'jsonwebtoken';

const ACCESS_SECRET  = process.env.JWT_ACCESS_SECRET!;
const REFRESH_SECRET = process.env.JWT_REFRESH_SECRET!;

export const signAccess  = (userId: string) =>
  jwt.sign({ sub: userId }, ACCESS_SECRET, { expiresIn: '15m' });

export const signRefresh = (userId: string) =>
  jwt.sign({ sub: userId }, REFRESH_SECRET, { expiresIn: '7d' });

export const verifyAccess  = (token: string) =>
  jwt.verify(token, ACCESS_SECRET) as jwt.JwtPayload;

export const verifyRefresh = (token: string) =>
  jwt.verify(token, REFRESH_SECRET) as jwt.JwtPayload;</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">middleware/auth.ts</span></div>
        <pre><code>import { Request, Response, NextFunction } from 'express';
import { verifyAccess } from '../utils/tokens';
import { User } from '../modules/users/user.model';
import { ApiError } from '../utils/ApiError';

export async function requireAuth(req: Request, _res: Response, next: NextFunction) {
  const header = req.headers.authorization;
  if (!header?.startsWith('Bearer ')) throw new ApiError(401, 'No token provided');

  try {
    const payload = verifyAccess(header.slice(7));
    const user    = await User.findById(payload.sub).select('-passwordHash -refreshTokens');
    if (!user) throw new ApiError(401, 'User not found');
    req.user = user;
    next();
  } catch {
    next(new ApiError(401, 'Invalid or expired token'));
  }
}</code></pre>
      </div>
      <div class="callout callout-warn">
        <div class="callout-icon"><iconify-icon icon="lucide:alert-triangle"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Security Warning</div>
          <p>Store refresh tokens in the database and validate against the stored list on every refresh. This allows you to invalidate all sessions for a user (e.g., on password change or account compromise) by clearing their <code>refreshTokens</code> array.</p>
        </div>
      </div>
    </div>

    <!-- Section 5 -->
    <div class="guide-section" id="s5">
      <div class="section-header">
        <div class="section-num">5</div>
        <h2>Building the React Frontend</h2>
      </div>
      <p>Bootstrap the frontend with Vite and React + TypeScript. Use React Router v6 for routing and React Context for lightweight global state (auth user, notifications). For complex server state, reach for TanStack Query.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">bash</span><span class="code-filename">terminal</span></div>
        <pre><code>npm create vite@latest client -- --template react-ts
cd client && npm install react-router-dom axios @tanstack/react-query zustand</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">context/AuthContext.tsx</span></div>
        <pre><code>import { createContext, useContext, useState, ReactNode } from 'react';
import type { User } from '../types';

interface AuthState {
  user:   User | null;
  token:  string | null;
  login:  (user: User, token: string) => void;
  logout: () => void;
}

const AuthContext = createContext<AuthState | null>(null);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user,  setUser]  = useState<User | null>(null);
  const [token, setToken] = useState<string | null>(
    () => localStorage.getItem('cf_token')
  );

  const login = (u: User, t: string) => {
    setUser(u);
    setToken(t);
    localStorage.setItem('cf_token', t);
  };

  const logout = () => {
    setUser(null);
    setToken(null);
    localStorage.removeItem('cf_token');
  };

  return (
    <AuthContext.Provider value={{ user, token, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
}

export const useAuth = () => {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth must be used inside AuthProvider');
  return ctx;
};</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Component Architecture Tips</div>
          <ul>
            <li>Separate <strong>page components</strong> (route-level) from <strong>feature components</strong> (business logic) and <strong>UI components</strong> (reusable, presentational).</li>
            <li>Co-locate a component's styles, tests, and types in a single folder: <code>PostCard/index.tsx</code>, <code>PostCard/PostCard.test.tsx</code>.</li>
            <li>Use TanStack Query for all server state — it handles caching, background refetch, and stale data automatically.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Section 6 -->
    <div class="guide-section" id="s6">
      <div class="section-header">
        <div class="section-num">6</div>
        <h2>Connecting Frontend to Backend</h2>
      </div>
      <p>Create a typed Axios instance that automatically attaches the access token and handles 401 responses by attempting a token refresh before retrying the original request.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">services/api.ts</span></div>
        <pre><code>import axios from 'axios';

export const api = axios.create({
  baseURL:     import.meta.env.VITE_API_URL,
  withCredentials: true,   // send HttpOnly refresh-token cookie
});

// Attach access token to every request
api.interceptors.request.use(config => {
  const token = localStorage.getItem('cf_token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// Auto-refresh on 401
let refreshing = false;
let queue: Array<() => void> = [];

api.interceptors.response.use(
  res => res,
  async err => {
    const original = err.config;
    if (err.response?.status !== 401 || original._retry) return Promise.reject(err);
    original._retry = true;

    if (!refreshing) {
      refreshing = true;
      try {
        const { data } = await axios.post('/api/auth/refresh', {}, { withCredentials: true });
        localStorage.setItem('cf_token', data.accessToken);
        queue.forEach(r => r());
        queue = [];
      } finally { refreshing = false; }
    } else {
      await new Promise<void>(resolve => queue.push(resolve));
    }
    return api(original);
  }
);</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">hooks/usePosts.ts</span></div>
        <pre><code>import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { api } from '../services/api';
import type { Post } from '../types';

export function usePosts(page = 1) {
  return useQuery({
    queryKey: ['posts', page],
    queryFn:  () => api.get(`/api/posts?page=${page}&limit=10`).then(r => r.data),
    staleTime: 60_000,
  });
}

export function useCreatePost() {
  const qc = useQueryClient();
  return useMutation({
    mutationFn: (body: Partial<Post>) => api.post('/api/posts', body).then(r => r.data),
    onSuccess:  () => qc.invalidateQueries({ queryKey: ['posts'] }),
  });
}</code></pre>
      </div>
    </div>

    <!-- Section 7 -->
    <div class="guide-section" id="s7">
      <div class="section-header">
        <div class="section-num">7</div>
        <h2>Real-time Features with WebSockets</h2>
      </div>
      <p>Socket.io layered over your Express HTTP server enables bi-directional, event-driven communication — ideal for live notifications, collaborative cursors, and chat.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">typescript</span><span class="code-filename">server.ts</span></div>
        <pre><code>import http   from 'http';
import { Server } from 'socket.io';
import { createApp } from './src/app';
import { connectDB } from './src/config/db';
import { verifyAccess } from './src/utils/tokens';

async function main() {
  await connectDB();
  const app        = createApp();
  const httpServer = http.createServer(app);

  const io = new Server(httpServer, {
    cors: { origin: process.env.CLIENT_URL, credentials: true },
  });

  // Authenticate socket connection via handshake token
  io.use((socket, next) => {
    try {
      const token   = socket.handshake.auth.token as string;
      const payload = verifyAccess(token);
      socket.data.userId = payload.sub;
      next();
    } catch { next(new Error('Unauthorized')); }
  });

  io.on('connection', socket => {
    console.log(`Socket connected: ${socket.data.userId}`);
    socket.join(`user:${socket.data.userId}`);   // private room

    socket.on('disconnect', () =>
      console.log(`Socket disconnected: ${socket.data.userId}`)
    );
  });

  // Emit notification from anywhere in the app:
  // io.to(`user:${userId}`).emit('notification', { message });

  httpServer.listen(process.env.PORT ?? 5000, () =>
    console.log(`🚀 Server running on port ${process.env.PORT ?? 5000}`)
  );
}
main();</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">tsx</span><span class="code-filename">hooks/useSocket.ts</span></div>
        <pre><code>import { useEffect, useRef } from 'react';
import { io, Socket } from 'socket.io-client';
import { useAuth } from '../context/AuthContext';

export function useSocket() {
  const { token } = useAuth();
  const socketRef = useRef<Socket | null>(null);

  useEffect(() => {
    if (!token) return;
    const socket = io(import.meta.env.VITE_API_URL, {
      auth: { token },
    });
    socketRef.current = socket;
    socket.on('notification', (data) => {
      console.log('New notification:', data);
      // dispatch to global notification store
    });
    return () => { socket.disconnect(); };
  }, [token]);

  return socketRef;
}</code></pre>
      </div>
    </div>

    <!-- Section 8 -->
    <div class="guide-section" id="s8">
      <div class="section-header">
        <div class="section-num">8</div>
        <h2>Deployment</h2>
      </div>
      <p>Package the entire stack with Docker Compose for local parity and production deployment. Use multi-stage Docker builds to keep production images lean.</p>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">dockerfile</span><span class="code-filename">server/Dockerfile</span></div>
        <pre><code># ── Build stage ──────────────────────────────────────────
FROM node:20-alpine AS builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# ── Production stage ─────────────────────────────────────
FROM node:20-alpine AS production
WORKDIR /app
ENV NODE_ENV=production
COPY package*.json ./
RUN npm ci --omit=dev
COPY --from=builder /app/dist ./dist
EXPOSE 5000
CMD ["node", "dist/server.js"]</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">docker-compose.yml</span></div>
        <pre><code>version: '3.9'
services:
  mongo:
    image: mongo:7
    restart: unless-stopped
    volumes:
      - mongo_data:/data/db
    environment:
      MONGO_INITDB_ROOT_USERNAME: root
      MONGO_INITDB_ROOT_PASSWORD: ${MONGO_PASSWORD}

  api:
    build: ./server
    restart: unless-stopped
    depends_on: [mongo]
    env_file: ./server/.env
    ports:
      - "5000:5000"

  client:
    build: ./client
    restart: unless-stopped
    ports:
      - "3000:80"

  nginx:
    image: nginx:alpine
    restart: unless-stopped
    volumes:
      - ./nginx/nginx.conf:/etc/nginx/nginx.conf:ro
    ports:
      - "80:80"
      - "443:443"
    depends_on: [api, client]

volumes:
  mongo_data:</code></pre>
      </div>
      <div class="code-block">
        <div class="code-block-header"><span class="code-lang">yaml</span><span class="code-filename">.github/workflows/deploy.yml</span></div>
        <pre><code>name: Build & Deploy
on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v3

      - name: Log in to Docker Hub
        uses: docker/login-action@v3
        with:
          username: ${{ secrets.DOCKERHUB_USERNAME }}
          password: ${{ secrets.DOCKERHUB_TOKEN }}

      - name: Build and push API image
        uses: docker/build-push-action@v5
        with:
          context: ./server
          push: true
          tags: myorg/api:${{ github.sha }},myorg/api:latest
          cache-from: type=gha
          cache-to: type=gha,mode=max

      - name: Deploy via SSH
        uses: appleboy/ssh-action@v1
        with:
          host: ${{ secrets.PROD_HOST }}
          username: ${{ secrets.PROD_USER }}
          key: ${{ secrets.PROD_KEY }}
          script: |
            cd /opt/app
            docker compose pull
            docker compose up -d --remove-orphans</code></pre>
      </div>
      <div class="callout callout-tip">
        <div class="callout-icon"><iconify-icon icon="lucide:lightbulb"></iconify-icon></div>
        <div class="callout-body">
          <div class="callout-title">Production Checklist</div>
          <ul>
            <li>Enable MongoDB authentication and restrict network access to internal Docker network only.</li>
            <li>Use <code>NGINX</code> as a reverse proxy with TLS termination (Certbot/Let's Encrypt).</li>
            <li>Set <code>NODE_ENV=production</code> — Express disables stack traces in error responses automatically.</li>
            <li>Pin Docker image digests in production to prevent unexpected updates.</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Related Resources -->
    <div class="related-section">
      <div class="related-title"><iconify-icon icon="lucide:link"></iconify-icon> Related Resources</div>
      <div class="related-grid">
        <a href="/Training/Tutorials/react.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">React Deep Dive</div>
        </a>
        <a href="/Training/Tutorials/nodejs.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">Node.js Fundamentals</div>
        </a>
        <a href="/Training/Tutorials/mongodb.php" class="related-card">
          <div class="related-card-label">Tutorial</div>
          <div class="related-card-title">MongoDB & Mongoose</div>
        </a>
        <a href="/Training/Guides/cicd-pipeline.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">CI/CD Pipeline Setup</div>
        </a>
        <a href="/Training/Guides/microservices-architecture.php" class="related-card">
          <div class="related-card-label">Guide</div>
          <div class="related-card-title">Microservices Architecture</div>
        </a>
      </div>
    </div>

  </main>

  <!-- TOC Sidebar -->
  <aside>
    <div class="toc-card">
      <div class="toc-title">Contents</div>
      <ul class="toc-list">
        <li><a href="#s1"><span class="toc-num">1</span> Architecture Overview</a></li>
        <li><a href="#s2"><span class="toc-num">2</span> Backend Setup</a></li>
        <li><a href="#s3"><span class="toc-num">3</span> REST API</a></li>
        <li><a href="#s4"><span class="toc-num">4</span> JWT Authentication</a></li>
        <li><a href="#s5"><span class="toc-num">5</span> React Frontend</a></li>
        <li><a href="#s6"><span class="toc-num">6</span> Frontend ↔ Backend</a></li>
        <li><a href="#s7"><span class="toc-num">7</span> WebSockets</a></li>
        <li><a href="#s8"><span class="toc-num">8</span> Deployment</a></li>
      </ul>
      <a href="/Training/Guides/" class="back-link"><iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Guides</a>
    </div>
  </aside>

</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
