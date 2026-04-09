<?php
// Include the Pusher config file
require 'pusher_config.php';

// Retrieve the incoming POST data (FCM Token and User ID)
if (isset($_POST['fcm_token']) && isset($_POST['user_id'])) {
    $fcm_token = $_POST['fcm_token'];  // FCM Token
    $user_id = $_POST['user_id'];       // User ID (Dynamic)

    // You should save the token in your database (if needed)
    // Example: Save to the 'users' table (ensure you set up a database connection)
    try {
        // Example SQL (using PDO):
        $stmt = $pdo->prepare("UPDATE users SET fcm_token = :fcm_token WHERE id = :user_id");
        $stmt->bindParam(':fcm_token', $fcm_token);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
    } catch (PDOException $e) {
        // Handle error saving to DB
        die("Error saving FCM token: " . $e->getMessage());
    }

    // Trigger an event using Pusher to notify the frontend
    $data = [
        'message' => 'FCM Token saved successfully!',
        'user_id' => $user_id // Optionally send user ID if you need it in the frontend
    ];

    // Trigger the 'fcm-token' event on the 'notifications' channel
    $pusher->trigger('notifications', 'fcm-token', $data);

    // Respond with success (You can modify this based on the frontend expectations)
    echo json_encode(['status' => 'success', 'message' => 'FCM Token saved successfully!']);
} else {
    // Return an error if the data is missing
    echo json_encode(['status' => 'error', 'message' => 'FCM Token or User ID is missing']);
}
?>
