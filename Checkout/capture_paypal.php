<?php
declare(strict_types=1);

/**
 * CodeFoundry – Capture PayPal Order
 *
 * POST /Checkout/capture_paypal.php
 * Body: JSON { orderID: 'PAYPAL_ORDER_ID', plan: 'starter'|'pro', billing: 'monthly'|'annual' }
 * Returns: JSON { success: true } or { error: '...' }
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
$orderId = trim($body['orderID'] ?? '');
$plan    = $body['plan']         ?? '';
$billing = $body['billing']      ?? 'monthly';

if ($orderId === '' || !isset(CF_PLANS[$plan]) || CF_PLANS[$plan]['price'] === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$baseUrl = CF_PAYPAL_MODE === 'live'
    ? 'https://api-m.paypal.com'
    : 'https://api-m.sandbox.paypal.com';

// Get access token
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

// Capture the order
$ch = curl_init($baseUrl . '/v2/checkout/orders/' . rawurlencode($orderId) . '/capture');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => '{}',
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $accessToken,
    ],
    CURLOPT_TIMEOUT => 15,
]);
$captureResponse = curl_exec($ch);
$captureStatus   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($captureResponse === false || ($captureStatus !== 200 && $captureStatus !== 201)) {
    $err = json_decode($captureResponse ?: '{}', true);
    $msg = $err['message'] ?? 'Failed to capture PayPal payment.';
    http_response_code(500);
    echo json_encode(['error' => $msg]);
    exit;
}

$captureData   = json_decode($captureResponse, true);
$captureStatus2 = $captureData['status'] ?? '';

if ($captureStatus2 !== 'COMPLETED') {
    http_response_code(400);
    echo json_encode(['error' => 'Payment not completed. Status: ' . $captureStatus2]);
    exit;
}

// Extract amount from capture
$captures     = $captureData['purchase_units'][0]['payments']['captures'][0] ?? [];
$amountValue  = (float) ($captures['amount']['value'] ?? CF_PLANS[$plan]['price']);
$captureId    = $captures['id'] ?? $orderId;

// Save payment record and upgrade plan
$user = cf_current_user();
UserStore::savePayment(
    $user['username'],
    $plan,
    $amountValue,
    'paypal',
    $captureId,
    'CodeFoundry ' . CF_PLANS[$plan]['label'] . ' Plan (' . ucfirst($billing) . ')'
);

echo json_encode(['success' => true]);
