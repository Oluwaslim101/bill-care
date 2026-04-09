<?php
// -------------------------------
// verify_account.php
// -------------------------------

header('Content-Type: application/json');

// -------------------------------
// 1️⃣ Validate Input
// -------------------------------
if (!isset($_GET['account_number'], $_GET['bank_code'])) {
    echo json_encode([
        'status' => false,
        'message' => 'Missing account number or bank code'
    ]);
    exit;
}

$account_number = preg_replace('/\D/', '', $_GET['account_number']); // digits only
$bank_code = htmlspecialchars(trim($_GET['bank_code']));

if (strlen($account_number) !== 10 || strlen($bank_code) < 3) {
    echo json_encode([
        'status' => false,
        'message' => 'Invalid account number or bank code format'
    ]);
    exit;
}

// -------------------------------
// 2️⃣ Load Paystack Secret from Env
// -------------------------------
$paystack_secret_key = getenv('PAYSTACK_SECRET');

if (!$paystack_secret_key) {
    error_log("PAYSTACK_SECRET not set on Render");
    echo json_encode([
        'status' => false,
        'message' => 'Server configuration error'
    ]);
    exit;
}

// -------------------------------
// 3️⃣ Initialize cURL to Paystack
// -------------------------------
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=$account_number&bank_code=$bank_code",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $paystack_secret_key",
        "Cache-Control: no-cache"
    ],
    CURLOPT_SSL_VERIFYPEER => true,
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($curl);
$http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
$curl_error = curl_error($curl);
curl_close($curl);

// -------------------------------
// 4️⃣ Handle cURL errors
// -------------------------------
if (!$response) {
    error_log("Paystack cURL Error: $curl_error");
    echo json_encode([
        'status' => false,
        'message' => 'Unable to reach Paystack API'
    ]);
    exit;
}

// -------------------------------
// 5️⃣ Decode Paystack Response
// -------------------------------
$data = json_decode($response, true);

if ($http_status !== 200 || !$data['status']) {
    error_log("Paystack API Error: " . ($data['message'] ?? 'Unknown error'));
    echo json_encode([
        'status' => false,
        'message' => $data['message'] ?? 'Error verifying account'
    ]);
    exit;
}

// -------------------------------
// 6️⃣ Success Response
// -------------------------------
echo json_encode([
    'status' => true,
    'data' => [
        'account_name' => $data['data']['account_name'] ?? '',
        'account_number' => $account_number,
        'bank_code' => $bank_code
    ]
]);
exit;
