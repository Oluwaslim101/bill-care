<?php
// send_deposit_sms.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$phone = $_POST['phone'] ?? '';
$amount = $_POST['amount'] ?? '';
$ref = $_POST['ref'] ?? '';

if (!$phone || !$amount || !$ref) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    exit;
}

$url = 'https://api.d7networks.com/messages/v1/send';
$token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJhdXRoLWJhY2tlbmQ6YXBwIiwic3ViIjoiODIwODllY2QtODkzYy00ZWQxLTg2YWQtYjUwYWNiMGQ5ZTc3In0.7KH81RN3Rye5koMa8IsPKGDWeVDD-oia5BjOocyPM20'; // replace with your actual token

$data = [
    "messages" => [
        [
            "channel" => "sms",
            "recipients" => [$phone],
            "content" => "Your deposit of $" . number_format($amount, 2) . " has been initiated. Ref: $ref. Awaiting confirmation.",
            "msg_type" => "text",
            "data_coding" => "text"
        ]
    ],
    "message_globals" => [
        "originator" => "SwiftContra",
        "report_url" => "https://swiftaffiliates.cloud/d7.php"
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['success' => false, 'error' => curl_error($ch)]);
} else {
    echo json_encode(['success' => true, 'response' => json_decode($response, true)]);
}

curl_close($ch);
?>