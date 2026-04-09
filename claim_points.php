<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// First, get quiz progress for user
$sql = "SELECT asked_count, fail_streak, lockout_type FROM quiz_progress WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$progress) {
    echo json_encode(['success' => false, 'message' => 'No quiz progress found.']);
    exit;
}

if ($progress['lockout_type'] !== 'none') {
    echo json_encode(['success' => false, 'message' => 'You are currently locked out and cannot claim points.']);
    exit;
}

// Define required questions count to claim points (e.g. 15)
$required_questions = 15;

if ($progress['asked_count'] < $required_questions) {
    echo json_encode(['success' => false, 'message' => "You must complete {$required_questions} questions to claim points."]);
    exit;
}

// Calculate total points earned in session from quiz_answers
$sql = "SELECT SUM(points) AS session_points FROM quiz_answers WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$sessionPoints = (int)($result['session_points'] ?? 0);

if ($sessionPoints <= 0) {
    echo json_encode(['success' => false, 'message' => 'No points to claim.']);
    exit;
}

// Add points to user total points in users table
$sql = "UPDATE users SET points = points + ? WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$sessionPoints, $user_id]);

// Clear session data to start new quiz cycle:
// Reset quiz_progress for user
$sql = "UPDATE quiz_progress SET asked_count = 0, fail_streak = 0, lockout_type = 'none', last_lockout_time = NULL WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

// Optionally clear quiz_answers for this user to start fresh
$sql = "DELETE FROM quiz_answers WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

echo json_encode(['success' => true, 'message' => "You claimed {$sessionPoints} points!"]);
