<?php
header('Content-Type: application/json');
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$giftcard_id = $data['giftcard_id'] ?? null;
$country = $data['country'] ?? null;
$card_type = $data['card_type'] ?? null;

if (!$giftcard_id || !$country || !$card_type) {
    echo json_encode(['rate' => 0, 'currency_symbol' => '']);
    exit;
}

$stmt = $sql->prepare("SELECT rate, currency_symbol FROM giftcard_rates WHERE giftcard_id = ? AND country = ? AND card_type = ?");
$stmt->execute([$giftcard_id, $country, $card_type]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    echo json_encode([
        'rate' => $result['rate'],
        'currency_symbol' => $result['currency_symbol']
    ]);
} else {
    echo json_encode([
        'rate' => 0,
        'currency_symbol' => ''
    ]);
}

