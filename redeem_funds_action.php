<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'];
$points = intval($_POST['points']);
$amount = floatval($_POST['amount']);

// Fetch user info
$query = "SELECT points FROM users WHERE id = :user_id";
$stmt = $sql->prepare($query);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

if ($points > $user['points']) {
    echo json_encode(['success' => false, 'message' => 'Not enough points to redeem.']);
    exit;
}

try {
    $sql->beginTransaction();

    // Update users table (balance, points, points_used)
    $update_query = "
        UPDATE users 
        SET balance = balance + :amount,
            points = points - :points,
            points_used = points_used + :points 
        WHERE id = :user_id
    ";
    $stmt = $sql->prepare($update_query);
    $stmt->execute([
        'amount' => $amount,
        'points' => $points,
        'user_id' => $user_id
    ]);

    // Log the redemption activity
    $log_query = "
        INSERT INTO activity_log (user_id, activity, points, created_at) 
        VALUES (:user_id, :activity, :points, NOW())
    ";
    $activity = "Redeemed $points points for ₦$amount";
    $stmt = $sql->prepare($log_query);
    $stmt->execute([
        'user_id' => $user_id,
        'activity' => $activity,
        'points' => $points
    ]);

    // Insert notification for redemption
    $notification_message = "You have redeemed $points points for ₦$amount.";
    $notification_query = "
        INSERT INTO notifications (user_id, action_type, message, status, created_at) 
        VALUES (:user_id, 'Points Redeemed', :message, 'unread', NOW())
    ";
    $stmt = $sql->prepare($notification_query);
    $stmt->execute([
        'user_id' => $user_id,
        'message' => $notification_message
    ]);

    $sql->commit();
    echo json_encode(['success' => true, 'message' => "You redeemed $points points for ₦$amount."]);

} catch (Exception $e) {
    $sql->rollBack();
    echo json_encode(['success' => false, 'message' => 'Redemption failed. Please try again.']);
}
