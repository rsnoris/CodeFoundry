<?php
declare(strict_types=1);

/**
 * CodeFoundry – Checkout
 *
 * /Checkout/?plan=starter|pro&billing=monthly|annual
 */

require_once dirname(__DIR__) . '/config.php';
require_once CF_ROOT . '/lib/UserStore.php';
require_once CF_ROOT . '/includes/auth.php';

cf_require_login();

$plans = CF_PLANS;
$plan  = $_GET['plan']    ?? '';
$billing = $_GET['billing'] ?? 'monthly';

// Validate plan
if (!isset($plans[$plan]) || $plans[$plan]['price'] === 0) {
    // Free plan or invalid → redirect to dashboard
    header('Location: /Dashboard/');
    exit;
}

if ($billing !== 'annual') {
    $billing = 'monthly';
}

$planData     = $plans[$plan];
$priceMonthly = (float) $planData['price'];
$priceAnnual  = (float) ($planData['price_annual'] ?? $planData['price']);
$price        = $billing === 'annual' ? $priceAnnual : $priceMonthly;
$amountCents  = (int) round($price * 100);

$user       = cf_current_user();
$userFull   = UserStore::findUser($user['username']) ?? $user;
$userEmail  = $userFull['email'] ?? '';
$userPlan   = $userFull['plan']  ?? 'free';

// Already on this plan
if ($userPlan === $plan) {
    header('Location: /Dashboard/payments/');
    exit;
}

$stripeConfigured = CF_STRIPE_PUBLISHABLE_KEY !== '';
$paypalConfigured = CF_PAYPAL_CLIENT_ID !== '';

// Build return URL for Stripe redirect
$returnUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
    . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost')
    . '/Checkout/complete.php?plan=' . rawurlencode($plan) . '&billing=' . rawurlencode($billing);

$page_title  = 'Checkout – CodeFoundry';
$active_page = '';
$page_styles = <<<'CSS'
.checkout-layout {
  max-width: 920px; margin: 0 auto; padding: 48px 24px 80px;
  display: grid; grid-template-columns: 1fr 400px; gap: 40px; align-items: start;
}
@media (max-width: 760px) {
  .checkout-layout { grid-template-columns: 1fr; padding: 32px 16px 60px; }
}
/* ── Order summary ── */
.order-summary {
  background: var(--navy-3); border: 1px solid var(--border-color);
  border-radius: var(--card-radius); padding: 28px;
}
.order-summary h2 { font-size: 18px; font-weight: 800; margin: 0 0 20px; }
.summary-plan {
  display: flex; align-items: center; gap: 14px; margin-bottom: 20px;
}
.summary-plan-icon { font-size: 32px; line-height: 1; }
.summary-plan-name { font-size: 20px; font-weight: 800; }
.summary-plan-sub  { font-size: 13px; color: var(--text-muted); margin-top: 2px; }
.summary-divider { border: none; border-top: 1px solid var(--border-color); margin: 20px 0; }
.summary-row {
  display: flex; justify-content: space-between; align-items: center;
  font-size: 14px; color: var(--text-muted); margin-bottom: 10px;
}
.summary-row.total {
  font-size: 20px; font-weight: 800; color: var(--text); margin: 0;
}
.summary-row .val { color: var(--text); font-weight: 600; }
.summary-row.total .val { color: var(--primary); }
.billing-toggle {
  display: flex; gap: 6px; margin-bottom: 20px;
}
.billing-toggle button {
  flex: 1; padding: 8px 12px; border-radius: 8px; border: 2px solid var(--border-color);
  background: transparent; color: var(--text-muted); font-size: 13px; font-weight: 600;
  cursor: pointer; transition: all .15s; font-family: inherit;
}
.billing-toggle button.active {
  border-color: var(--primary); background: rgba(24,179,255,.1); color: var(--primary);
}
.plan-features-small { list-style: none; margin: 20px 0 0; padding: 0; }
.plan-features-small li {
  display: flex; align-items: center; gap: 9px; font-size: 13px;
  color: var(--text-muted); padding: 5px 0;
}
.plan-features-small li iconify-icon { color: var(--primary); font-size: 15px; flex-shrink: 0; }
/* ── Payment box ── */
.payment-box {
  background: var(--navy-3); border: 1px solid var(--border-color);
  border-radius: var(--card-radius); padding: 28px;
}
.payment-box h2 { font-size: 18px; font-weight: 800; margin: 0 0 20px; }
.payment-methods {
  display: flex; gap: 8px; margin-bottom: 24px;
}
.pay-method-btn {
  flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px;
  padding: 12px 8px; border-radius: 10px; border: 2px solid var(--border-color);
  background: transparent; cursor: pointer; transition: all .15s; font-family: inherit;
  color: var(--text-muted); font-size: 12px; font-weight: 600;
}
.pay-method-btn:hover { border-color: var(--primary); color: var(--text); }
.pay-method-btn.active { border-color: var(--primary); background: rgba(24,179,255,.08); color: var(--primary); }
.pay-method-btn iconify-icon { font-size: 22px; }
.pay-method-btn img { height: 22px; width: auto; object-fit: contain; }
/* ── Stripe elements ── */
#stripe-panel, #paypal-panel, #applepay-panel { display: none; }
#stripe-panel.active, #paypal-panel.active, #applepay-panel.active { display: block; }
#payment-element { margin-bottom: 20px; }
#card-errors {
  color: #ff7373; font-size: 13px; margin-bottom: 12px; min-height: 18px;
}
.btn-pay {
  width: 100%; padding: 14px; background: var(--primary); color: var(--navy);
  font-weight: 800; font-size: 16px; border: none; border-radius: var(--button-radius);
  cursor: pointer; font-family: inherit; transition: background .2s; display: flex;
  align-items: center; justify-content: center; gap: 9px;
}
.btn-pay:hover:not(:disabled) { background: var(--primary-hover); }
.btn-pay:disabled { opacity: .55; cursor: not-allowed; }
/* ── PayPal panel ── */
#paypal-button-container { margin-bottom: 8px; }
/* ── Apple Pay panel ── */
#payment-request-button { margin-bottom: 8px; }
#applepay-not-available {
  text-align: center; color: var(--text-muted); font-size: 14px; padding: 20px 0;
}
/* ── Misc ── */
.secure-note {
  display: flex; align-items: center; gap: 7px; color: var(--text-subtle);
  font-size: 12px; margin-top: 16px; justify-content: center;
}
.secure-note iconify-icon { font-size: 14px; }
.not-configured {
  background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.25);
  border-radius: 10px; padding: 16px; color: #fbbf24; font-size: 13px; text-align: center;
}
.not-configured a { color: #fbbf24; text-decoration: underline; }
.spinner { display: inline-block; width: 16px; height: 16px; border: 2px solid currentColor;
  border-right-color: transparent; border-radius: 50%; animation: spin .6s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
CSS;

require_once CF_ROOT . '/includes/header.php';
?>

<main>
  <div class="checkout-layout">

    <!-- ── Order Summary ── -->
    <div class="order-summary">
      <h2>Order Summary</h2>

      <div class="summary-plan">
        <div class="summary-plan-icon"><?= $plan === 'pro' ? '⚡' : '🚀' ?></div>
        <div>
          <div class="summary-plan-name"><?= cf_e($planData['label']) ?> Plan</div>
          <div class="summary-plan-sub">CodeFoundry AI features</div>
        </div>
      </div>

      <!-- Billing toggle -->
      <div class="billing-toggle">
        <button class="billing-btn <?= $billing === 'monthly' ? 'active' : '' ?>" data-cycle="monthly">Monthly</button>
        <button class="billing-btn <?= $billing === 'annual'  ? 'active' : '' ?>" data-cycle="annual">
          Annual <?php if ($priceMonthly > 0): ?><span style="color:var(--primary);font-size:11px">(save <?= round((1 - $priceAnnual / $priceMonthly) * 100) ?>%)</span><?php endif; ?>
        </button>
      </div>

      <hr class="summary-divider">

      <div class="summary-row">
        <span>Plan</span>
        <span class="val"><?= cf_e($planData['label']) ?></span>
      </div>
      <div class="summary-row">
        <span>Billing</span>
        <span class="val" id="billing-label"><?= $billing === 'annual' ? 'Annual' : 'Monthly' ?></span>
      </div>
      <div class="summary-row">
        <span>Subtotal</span>
        <span class="val" id="subtotal-val">$<?= number_format($price, 2) ?></span>
      </div>

      <hr class="summary-divider">

      <div class="summary-row total">
        <span>Total / month</span>
        <span class="val" id="total-val">$<?= number_format($price, 2) ?></span>
      </div>

      <!-- Plan features -->
      <?php
      $features = [];
      if ($plan === 'starter') {
          $features = ['Everything in Free', '50 AI generations / month', 'AI code fix & explain', 'Insert-at-cursor mode'];
      } elseif ($plan === 'pro') {
          $features = ['Everything in Starter', '500 AI operations / month', 'AI generate, improve, fix, explain', 'Multi-language AI support', 'Email support'];
      }
      ?>
      <?php if (!empty($features)): ?>
        <ul class="plan-features-small">
          <?php foreach ($features as $f): ?>
            <li><iconify-icon icon="lucide:check"></iconify-icon> <?= cf_e($f) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <!-- ── Payment ── -->
    <div class="payment-box">
      <h2>Payment Method</h2>

      <?php if (!$stripeConfigured && !$paypalConfigured): ?>
        <div class="not-configured">
          <iconify-icon icon="lucide:alert-triangle" style="font-size:24px;display:block;margin:0 auto 10px"></iconify-icon>
          <strong>Payment system not configured.</strong><br>
          Please set the <code>STRIPE_PUBLISHABLE_KEY</code>, <code>STRIPE_SECRET_KEY</code>,
          and/or <code>PAYPAL_CLIENT_ID</code> / <code>PAYPAL_CLIENT_SECRET</code> environment
          variables on the server.<br><br>
          <a href="/Support/">Contact us</a> to set up your account manually.
        </div>
      <?php else: ?>

        <!-- Payment method selector -->
        <div class="payment-methods">
          <?php if ($stripeConfigured): ?>
            <button class="pay-method-btn active" data-panel="stripe-panel" title="Credit / Debit Card">
              <iconify-icon icon="lucide:credit-card"></iconify-icon>Card
            </button>
            <button class="pay-method-btn" data-panel="applepay-panel" title="Apple Pay / Google Pay">
              <iconify-icon icon="lucide:smartphone"></iconify-icon>Apple Pay
            </button>
          <?php endif; ?>
          <?php if ($paypalConfigured): ?>
            <button class="pay-method-btn <?= !$stripeConfigured ? 'active' : '' ?>" data-panel="paypal-panel" title="PayPal">
              <img src="https://www.paypalobjects.com/webstatic/mktg/Logo/pp-logo-100px.png" alt="PayPal">
              PayPal
            </button>
          <?php endif; ?>
        </div>

        <!-- ── Stripe Card panel ── -->
        <?php if ($stripeConfigured): ?>
          <div id="stripe-panel" class="active">
            <form id="stripe-form">
              <div id="payment-element"><!-- Stripe mounts here --></div>
              <div id="card-errors" role="alert"></div>
              <button type="submit" class="btn-pay" id="stripe-submit">
                <iconify-icon icon="lucide:lock"></iconify-icon>
                Pay $<span id="pay-amount"><?= number_format($price, 2) ?></span>
              </button>
            </form>
          </div>

          <!-- ── Apple Pay / Google Pay panel ── -->
          <div id="applepay-panel">
            <div id="payment-request-button"><!-- Stripe Payment Request Button --></div>
            <p id="applepay-not-available" style="display:none">
              Apple Pay / Google Pay is not available on this device or browser.<br>
              Please use the <strong>Card</strong> option instead.
            </p>
          </div>
        <?php endif; ?>

        <!-- ── PayPal panel ── -->
        <?php if ($paypalConfigured): ?>
          <div id="paypal-panel" <?= !$stripeConfigured ? 'class="active"' : '' ?>>
            <div id="paypal-button-container"></div>
          </div>
        <?php endif; ?>

        <div class="secure-note">
          <iconify-icon icon="lucide:shield-check"></iconify-icon>
          Secured &amp; encrypted payment · Cancel anytime
        </div>

      <?php endif; ?>
    </div><!-- /.payment-box -->

  </div><!-- /.checkout-layout -->
</main>

<?php if ($stripeConfigured || $paypalConfigured): ?>
<script>
// ── Config ───────────────────────────────────────────────────────────────
const CF_PLAN     = <?= json_encode($plan) ?>;
const CF_BILLING  = <?= json_encode($billing) ?>;
const CF_PRICES   = {
  monthly: <?= json_encode($priceMonthly) ?>,
  annual:  <?= json_encode($priceAnnual) ?>,
};
const CF_RETURN_URL = <?= json_encode($returnUrl) ?>;
<?php if ($stripeConfigured): ?>
const CF_STRIPE_PK = <?= json_encode(CF_STRIPE_PUBLISHABLE_KEY) ?>;
<?php endif; ?>
<?php if ($paypalConfigured): ?>
const CF_PAYPAL_CLIENT_ID = <?= json_encode(CF_PAYPAL_CLIENT_ID) ?>;
const CF_PAYPAL_MODE      = <?= json_encode(CF_PAYPAL_MODE) ?>;
<?php endif; ?>

// ── Billing toggle ───────────────────────────────────────────────────────
let currentBilling = CF_BILLING;

document.querySelectorAll('.billing-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    currentBilling = btn.dataset.cycle;
    document.querySelectorAll('.billing-btn').forEach(b => b.classList.toggle('active', b === btn));
    const price = CF_PRICES[currentBilling];
    document.getElementById('billing-label').textContent = currentBilling === 'annual' ? 'Annual' : 'Monthly';
    document.getElementById('subtotal-val').textContent  = '$' + price.toFixed(2);
    document.getElementById('total-val').textContent     = '$' + price.toFixed(2);
    document.getElementById('pay-amount').textContent    = price.toFixed(2);
    // Re-initialise Stripe with new amount
    if (typeof reinitStripe === 'function') reinitStripe();
  });
});

// ── Payment method tabs ──────────────────────────────────────────────────
document.querySelectorAll('.pay-method-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.pay-method-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    ['stripe-panel', 'paypal-panel', 'applepay-panel'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.classList.toggle('active', id === btn.dataset.panel);
    });
  });
});

<?php if ($stripeConfigured): ?>
// ── Stripe ───────────────────────────────────────────────────────────────
let stripe, elements, paymentElement;

async function initStripe() {
  if (!stripe) stripe = Stripe(CF_STRIPE_PK);

  // Fetch client secret from server
  const res = await fetch('/Checkout/create_intent.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({plan: CF_PLAN, billing: currentBilling}),
  });
  const data = await res.json();

  if (data.error) {
    document.getElementById('card-errors').textContent = data.error;
    return;
  }

  elements = stripe.elements({clientSecret: data.clientSecret, appearance: {
    theme: 'night',
    variables: {colorPrimary: '#18b3ff', colorBackground: '#0d1b2e', fontFamily: 'Inter, sans-serif'},
  }});

  paymentElement = elements.create('payment');
  paymentElement.mount('#payment-element');

  // Apple Pay / Google Pay via PaymentRequestButton
  initPaymentRequest(data.clientSecret);
}

async function reinitStripe() {
  if (paymentElement) {
    paymentElement.unmount();
    paymentElement = null;
  }
  document.getElementById('payment-element').innerHTML = '';
  await initStripe();
}

// Apple Pay / Google Pay
async function initPaymentRequest(clientSecret) {
  if (!stripe) return;
  const price = CF_PRICES[currentBilling];
  const pr = stripe.paymentRequest({
    country: 'US',
    currency: 'usd',
    total: {label: 'CodeFoundry ' + CF_PLAN.charAt(0).toUpperCase() + CF_PLAN.slice(1) + ' Plan', amount: Math.round(price * 100)},
    requestPayerName: true,
    requestPayerEmail: true,
  });

  const prBtn = elements.create('paymentRequestButton', {paymentRequest: pr});
  const canMake = await pr.canMakePayment();
  if (canMake) {
    prBtn.mount('#payment-request-button');
    pr.on('paymentmethod', async (e) => {
      const {paymentIntent, error: confirmError} = await stripe.confirmCardPayment(clientSecret, {
        payment_method: e.paymentMethod.id,
      }, {handleActions: false});
      if (confirmError) {
        e.complete('fail');
      } else {
        e.complete('success');
        window.location.href = CF_RETURN_URL + '&payment_intent=' + encodeURIComponent(paymentIntent.id);
      }
    });
  } else {
    const notAvail = document.getElementById('applepay-not-available');
    if (notAvail) notAvail.style.display = '';
  }
}

// Submit card payment
document.getElementById('stripe-form').addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('stripe-submit');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Processing…';
  document.getElementById('card-errors').textContent = '';

  if (!elements) { btn.disabled = false; btn.innerHTML = '<iconify-icon icon="lucide:lock"></iconify-icon> Pay $' + CF_PRICES[currentBilling].toFixed(2); return; }

  const {error} = await stripe.confirmPayment({
    elements,
    confirmParams: {return_url: CF_RETURN_URL},
  });

  if (error) {
    document.getElementById('card-errors').textContent = error.message;
    btn.disabled = false;
    btn.innerHTML = '<iconify-icon icon="lucide:lock"></iconify-icon> Pay $' + CF_PRICES[currentBilling].toFixed(2);
  }
  // On success Stripe redirects automatically
});

// Load Stripe.js then initialise
(function loadStripeJs() {
  if (window.Stripe) { initStripe(); return; }
  const s = document.createElement('script');
  s.src = 'https://js.stripe.com/v3/';
  s.onload = initStripe;
  document.head.appendChild(s);
})();
<?php endif; ?>

<?php if ($paypalConfigured): ?>
// ── PayPal ────────────────────────────────────────────────────────────────
(function loadPayPalJs() {
  if (window.paypal) { initPayPal(); return; }
  const sdkUrl = 'https://www.paypal.com/sdk/js?client-id=' + encodeURIComponent(CF_PAYPAL_CLIENT_ID) + '&currency=USD';
  const s = document.createElement('script');
  s.src = sdkUrl;
  s.onload = initPayPal;
  document.head.appendChild(s);
})();

function initPayPal() {
  const container = document.getElementById('paypal-button-container');
  if (!container || !window.paypal) return;

  paypal.Buttons({
    style: {layout: 'vertical', color: 'blue', shape: 'rect', label: 'paypal'},
    createOrder: async () => {
      const res = await fetch('/Checkout/paypal_order.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({plan: CF_PLAN, billing: currentBilling}),
      });
      const data = await res.json();
      if (data.error) { alert(data.error); return null; }
      return data.id;
    },
    onApprove: async (data) => {
      const res = await fetch('/Checkout/capture_paypal.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({orderID: data.orderID, plan: CF_PLAN, billing: currentBilling}),
      });
      const result = await res.json();
      if (result.error) { alert(result.error); return; }
      window.location.href = '/Checkout/complete.php?method=paypal&plan=' + encodeURIComponent(CF_PLAN) + '&billing=' + encodeURIComponent(currentBilling);
    },
    onError: (err) => {
      console.error('PayPal error:', err);
      alert('PayPal encountered an error. Please try another payment method.');
    },
  }).render('#paypal-button-container');
}
<?php endif; ?>
</script>
<?php endif; ?>

<?php require_once CF_ROOT . '/includes/footer.php'; ?>
