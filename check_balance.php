<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$query = $sql->prepare("SELECT balance FROM users WHERE id = ?");
$query->execute([$user_id]);
$balance = $query->fetchColumn();

echo json_encode(['balance' => $balance]);
