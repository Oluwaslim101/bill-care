<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user once
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(['error' => 'User not found']);
    exit;
}

// Collect input
$hotel_id = $_POST['hotel_id'] ?? '';
$room_type = $_POST['room_type'] ?? '';
$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$guests = $_POST['guests'] ?? 1;
$purpose = $_POST['purpose'] ?? 'Leisure';

if (!$hotel_id || !$room_type || !$checkin || !$checkout) {
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

// Get room price
$room_stmt = $sql->prepare("SELECT price FROM hotel_rooms WHERE hotel_id = ? AND room_type = ?");
$room_stmt->execute([$hotel_id, $room_type]);
$room = $room_stmt->fetch();

if (!$room) {
    echo json_encode(['error' => 'Invalid room selection']);
    exit;
}

$price_per_night = (float)$room['price'];
$days = (strtotime($checkout) - strtotime($checkin)) / (60 * 60 * 24);
if ($days < 1) $days = 1;
$total_cost = $days * $price_per_night;

// Check if user has enough balance
if ($user['balance'] < $total_cost) {
    echo json_encode(['error' => 'Insufficient balance']);
    exit;
}

// Start transaction
$sql->beginTransaction();

try {
    // Deduct from user wallet
    $deduct = $sql->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $deduct->execute([$total_cost, $user_id]);

    // Credit hotel wallet_balance
    $credit = $sql->prepare("UPDATE hotels SET wallet_balance = wallet_balance + ? WHERE id = ?");
    $credit->execute([$total_cost, $hotel_id]);

    // Generate booking reference
    $reference = strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));

    // Insert booking
    $insert = $sql->prepare("INSERT INTO bookings (
        reference, hotel_id, room_type, checkin_date, checkout_date,
        customer_name, customer_email, created_at,
        guests, purpose, total_cost, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)");

    $insert->execute([
        $reference,
        $hotel_id,
        $room_type,
        $checkin,
        $checkout,
        $user['full_name'],
        $user['email'],
        $guests,
        $purpose,
        $total_cost,
        'Booking Order Confirmed'
    ]);

    // Get hotel name for notification
    $hotel_stmt = $sql->prepare("SELECT name FROM hotels WHERE id = ?");
    $hotel_stmt->execute([$hotel_id]);
    $hotel = $hotel_stmt->fetch();
    $hotel_name = $hotel ? $hotel['name'] : "Unknown Hotel";

    // Insert notification with hotel name
    $notify = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) 
                            VALUES (?, ?, ?, ?, NOW())");

    $notify->execute([
        $user_id,
        'Hotel Booking',
        "Your booking for '{$room_type}' at '{$hotel_name}' has been confirmed. Ref: {$reference}",
        'unread'
    ]);

    // Generate QR code
    require 'phpqrcode/qrlib.php';
    $qr_dir = "qrcodes/";
    if (!is_dir($qr_dir)) mkdir($qr_dir);
    $qr_file = $qr_dir . $reference . ".png";
    $qr_data = "https://swiftaffiliates.cloud/app/hotel_receipt.php?reference=$reference";
    QRcode::png($qr_data, $qr_file, QR_ECLEVEL_H, 4);

    // Commit transaction
    $sql->commit();

    // Respond success
    echo json_encode([
        'status' => 'success',
        'reference' => $reference
    ]);
} catch (Exception $e) {
    $sql->rollBack();
    echo json_encode(['error' => 'Booking failed: ' . $e->getMessage()]);
}
?>
