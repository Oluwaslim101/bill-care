<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Include DB connection
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Sanitize and collect data
$user_id = $_SESSION['user_id'];
$car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : 0;
$pickup_location = trim($_POST['pickup_location']);
$dropoff_location = trim($_POST['dropoff_location']);
$pickup_date = $_POST['pickup_date'];
$dropoff_date = $_POST['dropoff_date'];

// Validate dates
if (!$car_id || !$pickup_date || !$dropoff_date || strtotime($pickup_date) >= strtotime($dropoff_date)) {
    die("Invalid booking details.");
}

// Fetch car details
$car_stmt = $sql->prepare("SELECT price_per_day FROM cars WHERE id = ?");
$car_stmt->execute([$car_id]);
$car = $car_stmt->fetch();

if (!$car) {
    die("Invalid car ID.");
}

$price_per_day = $car['price_per_day'];

// Calculate number of days
$start = new DateTime($pickup_date);
$end = new DateTime($dropoff_date);
$days = max(1, $start->diff($end)->days); // At least 1 day
$total_price = $days * $price_per_day;

// Check user balance
$balance_stmt = $sql->prepare("SELECT balance FROM users WHERE id = ?");
$balance_stmt->execute([$user_id]);
$user_balance = $balance_stmt->fetchColumn();

if ($user_balance < $total_price) {
    die("Insufficient balance. Required: ₦" . number_format($total_price));
}

// Deduct from user wallet
$deduct_stmt = $sql->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
$deduct_stmt->execute([$total_price, $user_id]);

// Insert booking
$book_stmt = $sql->prepare("
    INSERT INTO car_bookings 
    (user_id, car_id, pickup_location, dropoff_location, pickup_date, dropoff_date, total_price, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
");
$book_stmt->execute([
    $user_id,
    $car_id,
    $pickup_location,
    $dropoff_location,
    $pickup_date,
    $dropoff_date,
    $total_price
]);

// Get booking ID and redirect
$booking_id = $sql->lastInsertId();
$_SESSION['booking_id'] = $booking_id;
header("Location: booking_success.php?id=$booking_id");
exit();
?>
