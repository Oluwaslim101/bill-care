<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response type to JSON
header('Content-Type: application/json');

// Include database connection
include 'db.php';

// Check required POST data
if (isset($_POST['from']) && isset($_POST['to'])) {
    // Sanitize and normalize input
    $from = strtoupper(trim($_POST['from']));
    $to = strtoupper(trim($_POST['to']));

    // Prepare query to fetch rate
    $stmt = $sql->prepare("SELECT rate FROM currency_rates WHERE base_currency = :from AND target_currency = :to");
    $stmt->bindParam(':from', $from, PDO::PARAM_STR);
    $stmt->bindParam(':to', $to, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode([
            'success' => true,
            'rate' => (float)$result['rate']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Exchange rate not found for this currency pair.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request. Please provide both "from" and "to" currencies.'
    ]);
}
?>