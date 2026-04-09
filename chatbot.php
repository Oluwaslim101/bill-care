<?php
header('Content-Type: application/json');
$input = trim($_POST['message'] ?? '');
if (!$input) exit(json_encode("Please enter a message."));

$api_key = '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';

$data = [
    'model' => 'gpt-4',
    'messages' => [['role' => 'user', 'content' => $input]],
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key,
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
]);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
echo json_encode($result['choices'][0]['message']['content'] ?? 'Sorry, something went wrong.');