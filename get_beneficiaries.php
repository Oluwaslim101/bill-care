<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode([]);
    exit;
}

$stmt = $sql->prepare("SELECT account_name, account_number, bank_code, bank_name 
                       FROM beneficiaries WHERE user_id = ?");
$stmt->execute([$user_id]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
