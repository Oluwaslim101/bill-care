<?php
// Validate input
if (!isset($_GET['account_number'], $_GET['bank_code'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Missing account number or bank code'
    ]);
    exit;
}

$account_number = preg_replace('/\D/', '', $_GET['account_number']); // ensure it's digits only
$bank_code = htmlspecialchars(trim($_GET['bank_code']));

// Validate formats
if (strlen($account_number) !== 10 || strlen($bank_code) < 3) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid account number or bank code format'
    ]);
    exit;
}

// Secure API key usage (store in a config file ideally)
$paystack_secret_key = "sk_live_5dfd636941e51a27446ee4adcbeff427055bf827";

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=$account_number&bank_code=$bank_code",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $paystack_secret_key",
        "Cache-Control: no-cache"
    ],
));

$response = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);

// Handle errors
if ($http_status !== 200) {
    echo json_encode([
        'status' => false,
        'message' => 'Error verifying account'
    ]);
    exit;
}

echo $response;
