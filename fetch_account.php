<?php
header("Content-Type: application/json");

// Secure API key storage
define("SECRET_KEY", "FLWSECK-e64fa04c69f8cc3af508862b529669cc-19742b447b3vt-X");

// Get input data
$data = json_decode(file_get_contents("php://input"), true);
$accountNumber = $data['account_number'] ?? "";
$bankCode = $data['account_bank'] ?? "";

if (!$accountNumber || !$bankCode) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

// Call Flutterwave API
$url = "https://api.flutterwave.com/v3/accounts/resolve";
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . SECRET_KEY,
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "account_number" => $accountNumber,
        "account_bank" => $bankCode
    ]),
]);

$response = curl_exec($curl);
$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
curl_close($curl);
$data = json_decode($response, true);

// Debugging: Print API response if error occurs
if ($httpCode !== 200) {
    echo json_encode(["status" => "error", "message" => "Error fetching details"]);
    exit;
}

if ($data['status'] === "success") {
    echo json_encode(["status" => "success", "account_name" => $data['data']['account_name']]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid account details"]);
}
?>