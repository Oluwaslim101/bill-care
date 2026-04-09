<?php
// Replace with your actual Paystack secret key
$secret_key = 'sk_live_5dfd636941e51a27446ee4adcbeff427055bf827';

$account_number = '9324208464'; // Replace with the actual user's virtual account number
$provider_slug = 'wema-bank';   // Provider slug for the account (e.g., wema-bank, providus-bank)
$date = date('Y-m-d');          // Today's date

// Build the Paystack Requery URL
$url = "https://api.paystack.co/dedicated_account/requery?account_number={$account_number}&provider_slug={$provider_slug}&date={$date}";

// Initialize cURL
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $secret_key",
        "Content-Type: application/json"
    ),
));

// Execute and handle response
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(['status' => false, 'message' => "cURL Error: $err"]);
} else {
    $result = json_decode($response, true);
    if ($result && isset($result['status']) && $result['status'] === true) {
        echo json_encode(['status' => true, 'message' => 'Requery successful. If there was a missed payment, your wallet will be credited shortly.']);
    } else {
        $msg = $result['message'] ?? 'Unknown error occurred';
        echo json_encode(['status' => false, 'message' => "Requery failed: $msg"]);
    }
}
?>