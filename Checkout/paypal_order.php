<?php
declare(strict_types=1);

/**
 * CodeFoundry – Create PayPal Order
 *
 * POST /Checkout/paypal_order.php
 * Body: JSON { plan: 'starter'|'pro', billing: 'monthly'|'annual' }
 * Returns: JSON { id: 'PAYPAL_ORDER_ID' } or { error: '...' }
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

if (CF_PAYPAL_CLIENT_ID === '' || CF_PAYPAL_CLIENT_SECRET === '') {
    http_response_code(503);
    echo json_encode(['error' => 'PayPal is not configured on this server.']);
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
$amount = number_format((float) $price, 2, '.', '');

$baseUrl = CF_PAYPAL_MODE === 'live'
    ? 'https://api-m.paypal.com'
    : 'https://api-m.sandbox.paypal.com';

// Get PayPal access token
$ch = curl_init($baseUrl . '/v1/oauth2/token');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => 'grant_type=client_credentials',
    CURLOPT_USERPWD        => CF_PAYPAL_CLIENT_ID . ':' . CF_PAYPAL_CLIENT_SECRET,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    CURLOPT_TIMEOUT        => 15,
]);
$tokenResponse = curl_exec($ch);
$tokenStatus   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($tokenResponse === false || $tokenStatus !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to authenticate with PayPal.']);
    exit;
}

$tokenData   = json_decode($tokenResponse, true);
$accessToken = $tokenData['access_token'] ?? '';

if ($accessToken === '') {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to obtain PayPal access token.']);
    exit;
}

// Create PayPal order
$user = cf_current_user();
$orderPayload = json_encode([
    'intent'         => 'CAPTURE',
    'purchase_units' => [[
        'amount'      => [
            'currency_code' => 'USD',
            'value'         => $amount,
        ],
        'description' => 'CodeFoundry ' . ucfirst($plan) . ' Plan (' . ucfirst($billing) . ')',
        'custom_id'   => $user['username'] . '|' . $plan . '|' . $billing,
    ]],
]);

$ch = curl_init($baseUrl . '/v2/checkout/orders');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $orderPayload,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken,
    ],
    CURLOPT_TIMEOUT => 15,
]);
$orderResponse = curl_exec($ch);
$orderStatus   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($orderResponse === false || $orderStatus !== 201) {
    $err = json_decode($orderResponse ?: '{}', true);
    $msg = $err['message'] ?? 'Failed to create PayPal order.';
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}

$orderData = json_decode($orderResponse, true);
echo json_encode(['id' => $orderData['id']]);
