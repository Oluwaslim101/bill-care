<?php
header('Content-Type: application/json');

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/bank?country=nigeria&perPage=100&type=nuban",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk_live_5dfd636941e51a27446ee4adcbeff427055bf827",  // Replace with your live/test secret key
    ]
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(['status' => false, 'message' => "cURL Error: $err"]);
    exit;
}

echo $response;
