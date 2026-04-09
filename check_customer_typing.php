<?php
require "db.php";

header('Content-Type: application/json');

$user_id = intval($_GET['user_id'] ?? 0);

if ($user_id > 0) {
    // Example: Check a "typing" flag in DB
    $stmt = $sql->prepare("SELECT typing FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(["typing" => $row ? (bool)$row['typing'] : false]);
} else {
    echo json_encode(["typing" => false]);
}
