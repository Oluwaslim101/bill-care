<?php
include 'db.php';

$orderRef = $_GET['order'] ?? null;

if (!$orderRef) {
    die('<p style="color:red;">❌ Invalid confirmation link.</p>');
}

// Fetch order
$stmt = $sql->prepare("SELECT * FROM cart_orders WHERE reference = :ref");
$stmt->execute([':ref' => $orderRef]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('<p style="color:red;">❌ Order not found.</p>');
}

if ($order['shipping_status'] === 'Delivery Successful') {
    echo '<p style="color:green;">✅ This order has already been confirmed as delivered.</p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update shipping status
        $updateStmt = $sql->prepare("UPDATE cart_orders SET shipping_status = 'Delivery Successful', customer_confirmed_at = NOW() WHERE id = :id");
        $updateStmt->execute([':id' => $order['id']]);

        // Insert into shipping_logs
        $logStmt = $sql->prepare("INSERT INTO shipping_logs (order_id, status) VALUES (:order_id, 'Delivery Successful')");
        $logStmt->execute([':order_id' => $order['id']]);

        echo <<<HTML
        <div style="text-align:center; margin-top:50px;">
            <h2 style="color:green;">🎉 Delivery Confirmed!</h2>
            <p>Thank you for confirming that you have received your order <strong>#{$orderRef}</strong>.</p>
        </div>
HTML;
        exit;
    } catch (Exception $e) {
        echo '<p style="color:red;">❌ Failed to confirm delivery. Please try again.</p>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Delivery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body text-center">
                <h3 class="card-title mb-3">Confirm Delivery</h3>
                <p>Order Reference: <strong><?= htmlspecialchars($orderRef) ?></strong></p>
                <p>Click below to confirm you have received this order.</p>
                <form method="POST">
                    <button type="submit" class="btn btn-success">✅ Confirm Delivery</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
