<?php
require 'db.php';
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false]);
    exit;
}

$sql->prepare("DELETE FROM notifications WHERE user_id = ?")->execute([$user_id]);
echo json_encode(['success' => true]);


