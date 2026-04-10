<?php
$page_title  = 'Pricing – CodeFoundry';
$active_page = 'pricing';
$page_styles = <<<'PAGECSS'
/* ── Page hero ──────────────────────────────────────────── */
.pricing-hero {
  text-align: center;
  padding: 72px 24px 48px;
}
.pricing-hero h1 {
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 900;
  margin: 0 0 16px;
  letter-spacing: -1px;
  line-height: 1.15;
}
.pricing-hero h1 span {
  color: var(--primary);
}
.pricing-hero p {
  font-size: 1.125rem;
  color: var(--text-muted);
  max-width: 600px;
  margin: 0 auto;
  line-height: 1.7;
}

/* ── Cards grid ─────────────────────────────────────────── */
.pricing-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 24px;
  max-width: var(--maxwidth);
  margin: 0 auto 80px;
  padding: 0 24px;
}

.pricing-card {
  background: var(--navy-3);
  border: 1px solid var(--border-color);
  border-radius: var(--card-radius);
  padding: 32px 28px;
  display: flex;
  flex-direction: column;
  transition: border-color .2s, transform .2s;
  position: relative;
}
.pricing-card:hover {
  border-color: var(--primary);
  transform: translateY(-4px);
}
.pricing-card.featured {
  border-color: var(--primary);
  background: #0d1e35;
}
.featured-badge {
  position: absolute;
  top: -13px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--primary);
  color: var(--navy);
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .06em;
  padding: 4px 14px;
  border-radius: 20px;
  white-space: nowrap;
}

.plan-icon {
  font-size: 28px;
  margin-bottom: 12px;
  line-height: 1;
}
.plan-name {
  font-size: 1.125rem;
  font-weight: 800;
  margin: 0 0 6px;
}
.plan-tagline {
  font-size: 0.85rem;
  color: var(--text-muted);
  margin: 0 0 20px;
  line-height: 1.5;
}

.plan-price {
  display: flex;
  align-items: baseline;
  gap: 4px;
  margin-bottom: 6px;
}
.plan-price .amount {
  font-size: 2.25rem;
  font-weight: 900;
  letter-spacing: -1px;
  color: var(--text);
}
.plan-price .currency {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-muted);
  align-self: flex-start;
  margin-top: 6px;
}
.plan-price .period {
  font-size: 0.85rem;
  color: var(--text-muted);
}
.plan-price.contact-price .amount {
  font-size: 1.5rem;
  letter-spacing: -.5px;
}
.plan-billing-note {
  font-size: 0.75rem;
  color: var(--text-subtle);
  margin: 0 0 24px;
  min-height: 18px;
}

.plan-features {
  list-style: none;
  margin: 0 0 28px;
  padding: 0;
  flex: 1;
}
.plan-features li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  font-size: 0.875rem;
  color: var(--text-muted);
  padding: 6px 0;
  border-bottom: 1px solid var(--border-color);
  line-height: 1.4;
}
.plan-features li:last-child {
  border-bottom: none;
}
.plan-features li .check {
  color: var(--primary);
  flex-shrink: 0;
  margin-top: 1px;
}
.plan-features li.muted .check {
  color: var(--text-subtle);
}
.plan-features li.muted {
  opacity: .55;
}

.plan-cta {
  display: block;
  width: 100%;
  text-align: center;
  padding: 12px 20px;
  border-radius: var(--button-radius);
  font-family: inherit;
  font-size: 0.9375rem;
  font-weight: 700;
  cursor: pointer;
  text-decoration: none;
  transition: background .2s, color .2s, border-color .2s;
  border: 2px solid var(--border-color);
  background: transparent;
  color: var(--text);
}
.plan-cta:hover {
  border-color: var(--primary);
  color: var(--primary);
}
.plan-cta.primary {
  background: var(--primary);
  border-color: var(--primary);
  color: var(--navy);
}
.plan-cta.primary:hover {
  background: var(--primary-hover);
  border-color: var(--primary-hover);
  color: var(--navy);
}

/* ── FAQ / reassurance strip ─────────────────────────────── */
.pricing-note {
  text-align: center;
  padding: 0 24px 80px;
  color: var(--text-muted);
  font-size: 0.9rem;
}
.pricing-note a {
  color: var(--primary);
  text-decoration: underline;
}

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 900px) {
  .pricing-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  }
}
@media (max-width: 600px) {
  .pricing-grid {
    grid-template-columns: 1fr;
  }
  .pricing-hero {
    padding: 48px 20px 32px;
  }
}
PAGECSS;

require_once dirname(__DIR__) . '/config.php';
require_once CF_ROOT . '/includes/header.php';
?>

<main>
  <section class="pricing-hero">
    <h1>Simple, Transparent <span>Pricing</span></h1>
    <p>Unlock AI-powered code generation, smart fixes, and expert improvements. Choose the plan that fits how you build.</p>
  </section>

  <div class="pricing-grid">

    <!-- ── Free ── -->
    <div class="pricing-card">
      <div class="plan-icon">🆓</div>
      <div class="plan-name">Free</div>
      <div class="plan-tagline">Get started with the IDE at no cost.</div>
      <div class="plan-price">
        <span class="currency">$</span>
        <span class="amount">0</span>
        <span class="period">/ mo</span>
      </div>
      <div class="plan-billing-note">&nbsp;</div>
      <ul class="plan-features">
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Online IDE (all languages)</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Code execution &amp; run output</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Tutorials &amp; training access</li>
        <li class="muted"><iconify-icon icon="lucide:x" class="check"></iconify-icon> AI code generation</li>
        <li class="muted"><iconify-icon icon="lucide:x" class="check"></iconify-icon> AI improve / fix / explain</li>
        <li class="muted"><iconify-icon icon="lucide:x" class="check"></iconify-icon> Priority support</li>
      </ul>
      <a href="/Contact/" class="plan-cta">Get Started Free</a>
    </div>

    <!-- ── Starter ── -->
    <div class="pricing-card">
      <div class="plan-icon">🚀</div>
      <div class="plan-name">Starter</div>
      <div class="plan-tagline">Perfect for individual developers exploring AI assistance.</div>
      <div class="plan-price">
        <span class="currency">$</span>
        <span class="amount">9</span>
        <span class="period">/ mo</span>
      </div>
      <div class="plan-billing-note">Billed monthly · cancel anytime</div>
      <ul class="plan-features">
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Everything in Free</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> 50 AI generations / month</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> AI code fix &amp; explain</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Insert-at-cursor mode</li>
        <li class="muted"><iconify-icon icon="lucide:x" class="check"></iconify-icon> AI code improve</li>
        <li class="muted"><iconify-icon icon="lucide:x" class="check"></iconify-icon> Priority support</li>
      </ul>
      <a href="/Contact/" class="plan-cta">Get Starter</a>
    </div>

    <!-- ── Pro (featured) ── -->
    <div class="pricing-card featured">
      <div class="featured-badge">Most Popular</div>
      <div class="plan-icon">⚡</div>
      <div class="plan-name">Pro</div>
      <div class="plan-tagline">Full AI power for serious developers.</div>
      <div class="plan-price">
        <span class="currency">$</span>
        <span class="amount">29</span>
        <span class="period">/ mo</span>
      </div>
      <div class="plan-billing-note">Billed monthly · cancel anytime</div>
      <ul class="plan-features">
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Everything in Starter</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> 500 AI operations / month</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> AI generate, improve, fix, explain</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Multi-language AI support</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Email support</li>
        <li class="muted"><iconify-icon icon="lucide:x" class="check"></iconify-icon> Team collaboration</li>
      </ul>
      <a href="/Contact/" class="plan-cta primary">Get Pro</a>
    </div>

    <!-- ── Team ── -->
    <div class="pricing-card">
      <div class="plan-icon">👥</div>
      <div class="plan-name">Team</div>
      <div class="plan-tagline">Collaborate with your whole engineering team.</div>
      <div class="plan-price">
        <span class="currency">$</span>
        <span class="amount">79</span>
        <span class="period">/ mo</span>
      </div>
      <div class="plan-billing-note">Up to 10 seats · billed monthly</div>
      <ul class="plan-features">
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Everything in Pro</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> 2,000 AI operations / month</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Team workspace &amp; sharing</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Admin &amp; usage dashboard</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Priority email support</li>
        <li class="muted"><iconify-icon icon="lucide:x" class="check"></iconify-icon> Custom integrations</li>
      </ul>
      <a href="/Contact/" class="plan-cta">Get Team</a>
    </div>

    <!-- ── Enterprise ── -->
    <div class="pricing-card">
      <div class="plan-icon">🏢</div>
      <div class="plan-name">Enterprise</div>
      <div class="plan-tagline">Unlimited AI &amp; bespoke integrations for large organizations.</div>
      <div class="plan-price contact-price">
        <span class="amount">Contact us</span>
      </div>
      <div class="plan-billing-note">Custom billing &amp; SLA</div>
      <ul class="plan-features">
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Everything in Team</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Unlimited AI operations</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Unlimited seats</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Custom model &amp; API integrations</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> Dedicated account manager</li>
        <li><iconify-icon icon="lucide:check" class="check"></iconify-icon> 24/7 SLA &amp; phone support</li>
      </ul>
      <a href="/Contact/" class="plan-cta">Contact Sales</a>
    </div>

  </div>

  <div class="pricing-note">
    All plans include access to the CodeFoundry Online IDE. AI features require an active paid subscription.
    Questions? <a href="/Contact/">Contact our team</a> — we're happy to help you find the right fit.
  </div>
</main>

<?php require_once CF_ROOT . '/includes/footer.php'; ?>
