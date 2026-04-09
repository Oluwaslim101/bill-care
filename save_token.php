<?php
// Assuming PDO is being used for database connection
if (isset($_POST['fcm_token']) && isset($_POST['user_id'])) {
    $fcmToken = $_POST['fcm_token'];
    $userId = $_POST['user_id'];

    // Save the token into the database
    $stmt = $sql->prepare("UPDATE users SET fcm_token = ? WHERE id = ?");
    $stmt->execute([$fcmToken, $userId]);

    echo 'FCM Token saved successfully!';
} else {
    echo 'Missing parameters.';
}
?>
