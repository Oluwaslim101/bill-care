<?php
session_start();
require 'db.php'; // your db connection and session checking

$user_id = $_SESSION['user_id'] ?? null;
$task_id = $_GET['task_id'] ?? null;

if (!$user_id || !$task_id) {
    header('Location: index.php');
    exit;
}

// Check if user already claimed today
$today = date('Y-m-d');

$stmt = $pdo->prepare("SELECT * FROM points_claims WHERE user_id = ? AND DATE(claimed_at) = ?");
$stmt->execute([$user_id, $today]);
$already_claimed = $stmt->fetch();

if ($already_claimed) {
    $status = 'already';
} else {
    // Insert points
    $task = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $task->execute([$task_id]);
    $task_data = $task->fetch();

    if ($task_data) {
        $points = $task_data['points'];

        // Insert into claims table
        $pdo->prepare("INSERT INTO points_claims (user_id, task_id, points, claimed_at) VALUES (?, ?, ?, NOW())")
            ->execute([$user_id, $task_id, $points]);

        // Update user points
        $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?")
            ->execute([$points, $user_id]);

        $status = 'success';
        $earned_points = $points;
    } else {
        $status = 'error';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Claim Daily Points</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($status === 'success'): ?>
        Swal.fire({
            icon: 'success',
            title: 'Points Claimed!',
            text: 'You earned ⭐<?= $earned_points ?> points today!',
            confirmButtonText: 'Awesome'
        }).then(() => {
            window.location.href = 'index.php'; // Redirect after ok
        });
    <?php elseif ($status === 'already'): ?>
        Swal.fire({
            icon: 'info',
            title: 'Already Claimed',
            text: 'You already claimed your points today. Please come back tomorrow!',
            confirmButtonText: 'Okay'
        }).then(() => {
            window.location.href = 'index.php'; // Redirect after ok
        });
    <?php else: ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again later.',
            confirmButtonText: 'Okay'
        }).then(() => {
            window.location.href = 'index.php'; // Redirect after ok
        });
    <?php endif; ?>
});
</script>

</body>
</html>