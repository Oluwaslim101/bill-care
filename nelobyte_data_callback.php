<?php
file_put_contents('nelobyte_log.txt', date('Y-m-d H:i:s') . ' - ' . json_encode($_POST) . PHP_EOL, FILE_APPEND);

// Optionally decode and insert into DB
$pdo = new PDO("mysql:host=localhost;dbname=u822915062_dthehub_utilit", 'u822915062_dthehub_utilit', 'Lotanna@2024');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = $_POST;
$order_id = $data['orderid'] ?? null;
$status = $data['status'] ?? null;

if ($order_id && $status) {
    $stmt = $pdo->prepare("UPDATE data_transactions SET delivery_status = ? WHERE order_id = ?");
    $stmt->execute([$status, $order_id]);
}

echo "OK";
?>
