<?php
// Webhook URL to test
$webhookUrl = 'https://swiftaffiliates.cloud/app/paystack_webhook.php';

// Fake webhook payload simulating a successful virtual account transaction
$payload = [
    'event' => 'dedicated_account.transaction',
    'data' => [
        'account_number' => '9324208464', // Change to a real account number from your DB for accurate testing
        'amount' => 500000, // In kobo (₦5000)
        'reference' => 'TEST123456789',
        'createdAt' => date('c'),
    ],
];

// Encode payload
$jsonPayload = json_encode($payload);

// Generate a fake signature (not valid, but your webhook can be configured to skip validation during test)
$fakeSecret = 'sk_live_5dfd636941e51a27446ee4adcbeff427055bf827'; // Same secret used in webhook
$signature = hash_hmac('sha512', $jsonPayload, $fakeSecret);

// Initialize cURL
$ch = curl_init($webhookUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Paystack-Signature: ' . $signature
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);

// Execute request
$response = curl_exec($ch);
$error = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Display result
echo "<h3>Webhook Test Result</h3>";
echo "<strong>Status:</strong> HTTP $httpCode<br>";
if ($error) {
    echo "<strong>cURL Error:</strong> $error<br>";
} else {
    echo "<strong>Response:</strong> $response<br>";
}
?>