<?php
// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
header('Content-Type: application/json');

include('db.php'); // Database connection

try {
    // ✅ Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in. Please login to continue.");
    }
    $user_id = intval($_SESSION['user_id']);

    // ✅ Get the order reference from POST
    $reference = $_POST['reference'] ?? '';
    if (empty($reference)) {
        throw new Exception("Invalid request: Order reference is missing.");
    }

    // ✅ Fetch the order details
    $stmt = $sql->prepare("SELECT * FROM cart_orders WHERE user_id = ? AND reference = ? LIMIT 1");
    $stmt->execute([$user_id, $reference]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception("Order not found or does not belong to this user.");
    }

    if ($order['status'] !== 'pending') {
        throw new Exception("This order has already been processed.");
    }

    // ✅ Fetch user's current wallet balance
    $stmt = $sql->prepare("SELECT balance FROM users WHERE id = ? LIMIT 1");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        throw new Exception("User record not found.");
    }

    $wallet_balance = (float)$user['balance'];
    $pay_amount = (float)$order['pay_amount'];

    // ✅ Check if user has enough funds
    if ($wallet_balance < $pay_amount) {
        echo json_encode([
            'success' => false,
            'status'  => 'insufficient',
            'message' => "Insufficient wallet balance to complete this payment."
        ]);
        exit;
    }

    // ✅ Deduct wallet balance
    $new_balance = $wallet_balance - $pay_amount;
    $stmt = $sql->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $stmt->execute([$new_balance, $user_id]);

    // ✅ Mark order as ordered and assign a tracking ID
  
    $stmt = $sql->prepare("
        UPDATE cart_orders 
        SET status = 'ordered', updated_at = NOW() 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$order['id'], $user_id]);

    // ✅ Respond with success
    echo json_encode([
        'success' => true,
        'message' => "Payment successful! Order marked as ordered.",
        
    ]);
} catch (Exception $e) {
    // ❌ Handle errors gracefully
    echo json_encode([
        'success' => false,
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
}
?>
