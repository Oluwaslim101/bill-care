<?php
session_start();
include('db.php');


$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Get user's quiz progress info
$sql = "SELECT asked_count, fail_streak, last_lockout_time, lockout_type 
        FROM quiz_progress WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$progress) {
    // No progress yet
    $progress = [
        'asked_count' => 0,
        'fail_streak' => 0,
        'last_lockout_time' => null,
        'lockout_type' => 'none'
    ];
}

// Get answers summary for this user (session answers assumed to be all current answers)
$sql = "SELECT COUNT(*) AS total_answered, 
               SUM(is_correct) AS correct_answers,
               SUM(points) AS total_points
        FROM quiz_answers WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$answerSummary = $stmt->fetch(PDO::FETCH_ASSOC);

$totalAnswered = (int)($answerSummary['total_answered'] ?? 0);
$correctAnswers = (int)($answerSummary['correct_answers'] ?? 0);
$totalPoints = (int)($answerSummary['total_points'] ?? 0);
$failCount = $totalAnswered - $correctAnswers;

// Lockout check
$lockedOut = ($progress['lockout_type'] !== 'none');
$lockoutMessage = null;
if ($lockedOut) {
    $lockoutMessage = "You are currently locked out due to '{$progress['lockout_type']}' lockout.";
}

// Output JSON summary
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'asked_count' => $progress['asked_count'],
    'fail_streak' => $progress['fail_streak'],
    'fail_count' => $failCount,
    'total_answered' => $totalAnswered,
    'correct_answers' => $correctAnswers,
    'total_points' => $totalPoints,
    'locked_out' => $lockedOut,
    'lockout_message' => $lockoutMessage
]);
