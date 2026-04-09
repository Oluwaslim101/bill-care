<?php
require_once 'db.php'; // contains your DB connection

$provider = $_GET['provider'] ?? '';
if (!$provider) {
    echo json_encode([]);
    exit;
}

$stmt = $sql->prepare("SELECT p.code, p.name, p.price FROM tv_packages p
                       JOIN tv_providers t ON p.provider_id = t.id
                       WHERE t.code = ?");
$stmt->execute([$provider]);
$packages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($packages);