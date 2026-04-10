<?php
declare(strict_types=1);

/**
 * CodeFoundry – Create Stripe PaymentIntent
 *
 * POST /Checkout/create_intent.php
 * Body: JSON { plan: 'starter'|'pro', billing: 'monthly'|'annual' }
 * Returns: JSON { clientSecret: '...' } or { error: '...' }
 */

require_once dirname(__DIR__) . '/config.php';
require_once CF_ROOT . '/lib/UserStore.php';
require_once CF_ROOT . '/includes/auth.php';

header('Content-Type: application/json');

cf_require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

if (CF_STRIPE_SECRET_KEY === '') {
    http_response_code(503);
    echo json_encode(['error' => 'Stripe is not configured on this server.']);
    exit;
}

$body    = json_decode(file_get_contents('php://input'), true) ?? [];
$plan    = $body['plan']    ?? '';
$billing = $body['billing'] ?? 'monthly';

$plans = CF_PLANS;
if (!isset($plans[$plan]) || $plans[$plan]['price'] === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid plan.']);
    exit;
}

$price  = $billing === 'annual' ? ($plans[$plan]['price_annual'] ?? $plans[$plan]['price']) : $plans[$plan]['price'];
$amount = (int) round($price * 100); // cents

$user    = cf_current_user();
$display = $user['display'] ?? $user['username'] ?? '';
$email   = $user['email']   ?? '';

// Build Stripe PaymentIntent via REST API
$payload = http_build_query([
    'amount'                    => $amount,
    'currency'                  => 'usd',
    'description'               => 'CodeFoundry ' . ucfirst($plan) . ' Plan (' . ucfirst($billing) . ')',
    'metadata[username]'        => $user['username'],
    'metadata[plan]'            => $plan,
    'metadata[billing]'         => $billing,
    'automatic_payment_methods[enabled]' => 'true',
]);

if ($email !== '') {
    $payload .= '&receipt_email=' . urlencode($email);
}

$ch = curl_init('https://api.stripe.com/v1/payment_intents');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_USERPWD        => CF_STRIPE_SECRET_KEY . ':',
    CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    CURLOPT_TIMEOUT        => 15,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false || $httpCode !== 200) {
    $err = json_decode($response ?: '{}', true);
    $msg = $err['error']['message'] ?? 'Failed to create payment intent.';
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}

$data = json_decode($response, true);
echo json_encode(['clientSecret' => $data['client_secret']]);
