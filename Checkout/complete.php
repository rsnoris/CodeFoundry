<?php
declare(strict_types=1);

/**
 * CodeFoundry – Checkout Complete
 *
 * Handles two cases:
 *   1. Stripe redirect: ?payment_intent=pi_xxx&payment_intent_client_secret=xxx&redirect_status=succeeded&plan=X&billing=Y
 *   2. PayPal success:  ?method=paypal&plan=X&billing=Y  (plan already upgraded by capture_paypal.php)
 */

require_once dirname(__DIR__) . '/config.php';
require_once CF_ROOT . '/lib/UserStore.php';
require_once CF_ROOT . '/includes/auth.php';

cf_require_login();

$user       = cf_current_user();
$method     = $_GET['method']          ?? 'stripe';
$plan       = $_GET['plan']            ?? '';
$billing    = $_GET['billing']         ?? 'monthly';
$piId       = $_GET['payment_intent']  ?? '';
$piSecret   = $_GET['payment_intent_client_secret'] ?? '';
$status     = $_GET['redirect_status'] ?? '';

$plans      = CF_PLANS;
$planData   = $plans[$plan] ?? null;
$success    = false;
$errorMsg   = '';

// ── Stripe path ────────────────────────────────────────────────────────────
if ($method !== 'paypal') {
    if ($status === 'succeeded' && $piId !== '' && $planData !== null && $planData['price'] > 0) {
        if (CF_STRIPE_SECRET_KEY !== '') {
            // Retrieve the PaymentIntent to confirm server-side
            $ch = curl_init('https://api.stripe.com/v1/payment_intents/' . rawurlencode($piId));
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERPWD        => CF_STRIPE_SECRET_KEY . ':',
                CURLOPT_TIMEOUT        => 10,
            ]);
            $resp     = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($resp !== false && $httpCode === 200) {
                $pi = json_decode($resp, true);
                if (($pi['status'] ?? '') === 'succeeded') {
                    $amountPaid = ((float)($pi['amount'] ?? 0)) / 100;
                    if ($amountPaid <= 0) {
                        $amountPaid = (float) ($plans[$plan]['price'] ?? 0);
                    }
                    $username   = $pi['metadata']['username'] ?? $user['username'];
                    $planKey    = $pi['metadata']['plan']     ?? $plan;
                    $billingKey = $pi['metadata']['billing']  ?? $billing;

                    // Guard: only save once (check if this txn_id already recorded)
                    $existing = UserStore::paymentsForUser($username);
                    $alreadyRecorded = false;
                    foreach ($existing as $p) {
                        if (($p['txn_id'] ?? '') === $piId) {
                            $alreadyRecorded = true;
                            break;
                        }
                    }

                    if (!$alreadyRecorded) {
                        UserStore::savePayment(
                            $username,
                            $planKey,
                            $amountPaid,
                            'stripe',
                            $piId,
                            'CodeFoundry ' . ($plans[$planKey]['label'] ?? ucfirst($planKey)) . ' Plan (' . ucfirst($billingKey) . ')'
                        );
                    }
                    $success = true;
                } else {
                    $errorMsg = 'Payment status: ' . ($pi['status'] ?? 'unknown');
                }
            } else {
                $errorMsg = 'Unable to verify payment with Stripe.';
            }
        } else {
            $errorMsg = 'Stripe is not configured on this server.';
        }
    } elseif ($status === 'processing') {
        $success  = true; // treat as success; webhook would confirm later
        $errorMsg = 'Your payment is being processed.';
    } else {
        $errorMsg = 'Payment was not completed. Please try again.';
    }
} else {
    // PayPal path – plan already upgraded by capture_paypal.php
    if ($planData !== null && $planData['price'] > 0) {
        $success = true;
    } else {
        $errorMsg = 'Invalid plan.';
    }
}

// Refresh user data after potential plan upgrade
$user     = UserStore::findUser($user['username']) ?? $user;
$planKey  = $user['plan'] ?? 'free';
$planData = $plans[$planKey] ?? $plans['free'];

$page_title  = $success ? 'Payment Successful – CodeFoundry' : 'Payment Failed – CodeFoundry';
$active_page = '';
$page_styles = <<<'CSS'
  .complete-wrap {
    max-width: 560px; margin: 80px auto 120px; padding: 0 24px; text-align: center;
  }
  .complete-icon {
    font-size: 64px; margin-bottom: 20px; display: block;
  }
  .complete-wrap h1 {
    font-size: clamp(1.6rem, 3vw, 2.2rem); font-weight: 900; margin: 0 0 12px;
  }
  .complete-wrap p {
    color: var(--text-muted); font-size: 1rem; line-height: 1.7; margin: 0 0 32px;
  }
  .complete-plan-badge {
    display: inline-block; padding: 8px 22px; border-radius: 100px;
    background: rgba(24,179,255,.12); color: var(--primary);
    font-weight: 700; font-size: 0.95rem; border: 1px solid rgba(24,179,255,.25);
    margin-bottom: 32px;
  }
  .complete-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
  .btn-primary {
    display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px;
    background: var(--primary); color: var(--navy); font-weight: 700; font-size: 15px;
    border-radius: var(--button-radius); text-decoration: none; transition: background .2s;
  }
  .btn-primary:hover { background: var(--primary-hover); }
  .btn-outline {
    display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px;
    background: transparent; color: var(--text); font-weight: 600; font-size: 15px;
    border-radius: var(--button-radius); text-decoration: none;
    border: 2px solid var(--border-color); transition: border-color .2s, color .2s;
  }
  .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
  .error-box {
    background: rgba(255,72,72,.08); border: 1px solid rgba(255,72,72,.25);
    border-radius: 10px; padding: 16px 20px; margin-bottom: 28px;
    color: #ff7373; font-size: 0.9rem;
  }
CSS;

require_once CF_ROOT . '/includes/header.php';
?>

<main>
  <div class="complete-wrap">
    <?php if ($success): ?>
      <span class="complete-icon">🎉</span>
      <h1>You're all set!</h1>
      <p>Your payment was successful. Welcome to the <strong><?= cf_e($planData['label']) ?> plan</strong>.<br>
         Your AI features are now active and ready to use.</p>
      <div class="complete-plan-badge">
        <iconify-icon icon="lucide:zap" style="vertical-align:middle;margin-right:6px"></iconify-icon>
        <?= cf_e($planData['label']) ?> Plan – Active
      </div>
      <?php if ($errorMsg !== ''): ?>
        <div class="error-box"><?= cf_e($errorMsg) ?></div>
      <?php endif; ?>
      <div class="complete-actions">
        <a href="/IDE/" class="btn-primary">
          <iconify-icon icon="lucide:code-2"></iconify-icon> Open the IDE
        </a>
        <a href="/Dashboard/payments/" class="btn-outline">
          <iconify-icon icon="lucide:receipt"></iconify-icon> View Receipt
        </a>
      </div>
    <?php else: ?>
      <span class="complete-icon">❌</span>
      <h1>Payment Failed</h1>
      <p>Something went wrong with your payment. No charge was made to your account.</p>
      <?php if ($errorMsg !== ''): ?>
        <div class="error-box"><?= cf_e($errorMsg) ?></div>
      <?php endif; ?>
      <div class="complete-actions">
        <a href="/Checkout/?plan=<?= cf_e($plan) ?>&billing=<?= cf_e($billing) ?>" class="btn-primary">
          <iconify-icon icon="lucide:refresh-cw"></iconify-icon> Try Again
        </a>
        <a href="/Pricing/" class="btn-outline">
          <iconify-icon icon="lucide:arrow-left"></iconify-icon> Back to Pricing
        </a>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php require_once CF_ROOT . '/includes/footer.php'; ?>
