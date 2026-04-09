<?php
// Error reporting 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include ('db.php'); // Database connection, including Flutterwave API keys


$api_key = "FLWSECK-6f148fbb2fb4e625fa490bc9b3528647-1956425786evt-X"; // Replace with your key
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.flutterwave.com/v3/bill-categories?category=data",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ]
]);

$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response, true);

if ($data['status'] == "success") {
    echo json_encode($data['data']);
} else {
    echo json_encode([]);
}
?>