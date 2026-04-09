<?php
session_start();

header('Content-Type: application/json');
include 'db.php'; // PDO connection

try {
    // Read and decode JSON input
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON input: " . json_last_error_msg());
    }

    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
        throw new Exception("User not logged in");
    }

    $user_id = intval($_SESSION['user_id']);

    // Validate required fields
    $requiredFields = ['reference', 'cart', 'address', 'deliveryFee', 'payAmount', 'fullAmount', 'paymentMethod', 'shop_id'];
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty($input[$field])) {
            throw new Exception("Missing or empty field: $field");
        }
    }

    // Insert into cart_orders table
    $stmt = $sql->prepare("
        INSERT INTO cart_orders 
        (user_id, reference, shop_id, cart_data, delivery_address, delivery_fee, pay_amount, full_amount, payment_method, status)
        VALUES
        (:user_id, :reference, :shop_id, :cart_data, :delivery_address, :delivery_fee, :pay_amount, :full_amount, :payment_method, 'pending')
    ");

    $stmt->execute([
        ':user_id'         => $user_id,
        ':reference'       => $input['reference'],
        ':shop_id'         => $input['shop_id'],
        ':cart_data'       => json_encode($input['cart']),
        ':delivery_address'=> $input['address'],
        ':delivery_fee'    => $input['deliveryFee'],
        ':pay_amount'      => $input['payAmount'],
        ':full_amount'     => $input['fullAmount'],
        ':payment_method'  => $input['paymentMethod'],
    ]);

    echo json_encode([
        'success'   => true,
        'message'   => 'Cart saved. Proceed to payment.',
        'reference' => $input['reference']
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
