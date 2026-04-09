<?php
include 'db.php';
$threshold = date('Y-m-d H:i:s', strtotime('-5 seconds'));
$stmt = $sql->prepare("SELECT COUNT(*) as total FROM visitor_activity WHERE last_active >= ?");
$stmt->execute([$threshold]);
$row = $stmt->fetch();
echo json_encode(['count' => (int)$row['total']]);