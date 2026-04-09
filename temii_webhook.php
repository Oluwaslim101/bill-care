<?php
// Enable error reporting (turn off in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include DB connection
include('db.php');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Read raw input
$payload = file_get_contents("php://input");
file_put_contents("termii_webhook_log.txt", date('Y-m-d H:i:s') . "\n$payload\n\n", FILE_APPEND); // Log for debug

$data = json_decode($payload, true);

// Validate payload
if (!isset($data['message_id'], $data['status'], $data['recipient'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid payload structure"]);
    exit;
}

// Extract
$message_id = $data['message_id'];
$status = $data['status'];
$recipient = $data['recipient'];

// Optional: sanitize inputs
$recipient = preg_replace('/[^0-9]/', '', $recipient);

// Update status
$update_query = "UPDATE sms_logs SET status = ? WHERE message_id = ? AND recipients LIKE ?";
$stmt = $sql->prepare($update_query);
$stmt->execute([$status, $message_id, "%$recipient%"]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["message" => "Status updated successfully"]);
} else {
    echo json_encode(["message" => "No matching record found"]);
}
?>
