<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'Please log in to book.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = intval($_POST['event_id']);
$number_of_tickets = intval($_POST['number_of_tickets']);

// Fetch event details
$stmt = $sql->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo json_encode(['status' => 'error', 'message' => 'Event not found.']);
    exit();
}

// Check if enough tickets are available
if ($event['total_tickets'] - $event['tickets_sold'] < $number_of_tickets) {
    echo json_encode(['status' => 'error', 'message' => 'Not enough tickets available.']);
    exit();
}

$total_cost = $event['ticket_price'] * $number_of_tickets;

// Fetch user balance
$stmt = $sql->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['balance'] < $total_cost) {
    echo json_encode(['status' => 'error', 'message' => 'Insufficient wallet balance.']);
    exit();
}

// Deduct wallet balance
$new_balance = $user['balance'] - $total_cost;
$stmt = $sql->prepare("UPDATE users SET balance = ? WHERE id = ?");
$stmt->execute([$new_balance, $user_id]);

// Generate unique booking reference
$reference = strtoupper(bin2hex(random_bytes(8)));

// Insert booking record
$stmt = $sql->prepare("INSERT INTO event_bookings (reference, event_id, user_id, number_of_tickets, total_cost, status) VALUES (?, ?, ?, ?, ?, 'Confirmed')");
$stmt->execute([$reference, $event_id, $user_id, $number_of_tickets, $total_cost]);

// Update tickets sold
$stmt = $sql->prepare("UPDATE events SET tickets_sold = tickets_sold + ? WHERE id = ?");
$stmt->execute([$number_of_tickets, $event_id]);

// Success response
echo json_encode([
    'status' => 'success',
    'message' => 'Event booking successful!',
    'reference' => $reference
]);
exit();
?>
