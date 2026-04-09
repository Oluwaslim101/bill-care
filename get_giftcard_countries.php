<?php
require 'db.php';

header('Content-Type: application/json');

$card_id = $_GET['card_id'] ?? null;

if (!$card_id) {
    echo json_encode(['countries' => []]);
    exit;
}

$stmt = $sql->prepare("SELECT DISTINCT country FROM giftcard_rates WHERE giftcard_id = ? AND status = 'active'");
$stmt->execute([$card_id]);
$countries = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode(['countries' => $countries]);
