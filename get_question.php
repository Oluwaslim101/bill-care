<?php
session_start();
header('Content-Type: application/json');
include('db.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

// Initialize session data for quiz if not present
if (!isset($_SESSION['quiz'])) {
    $_SESSION['quiz'] = [
        'used_questions' => [],
        'fail_streak' => 0,
        'total_answered' => 0,
        'locked_until' => 0,      // timestamp until which user is locked (either 4 or 6 hrs)
    ];
}

$now = time();

// Check lockout status first
if ($now < $_SESSION['quiz']['locked_until']) {
    $remaining = $_SESSION['quiz']['locked_until'] - $now;
    echo json_encode([
        'success' => false,
        'locked_out' => true,
        'lockout_remaining' => $remaining,
        'message' => 'You are currently locked out. Please wait before continuing.'
    ]);
    exit();
}

// Check if user has finished 15 questions
if ($_SESSION['quiz']['total_answered'] >= 15) {
    // Lock user for 4 hours
    $_SESSION['quiz']['locked_until'] = $now + (4 * 3600);
    echo json_encode([
        'success' => false,
        'locked_out' => true,
        'lockout_remaining' => 4 * 3600,
        'message' => 'You have reached the maximum of 15 questions. Locked for 4 hours.'
    ]);
    exit();
}

$used_questions = $_SESSION['quiz']['used_questions'];

// Fetch next unused question
if (count($used_questions) > 0) {
    $placeholders = rtrim(str_repeat('?,', count($used_questions)), ',');
    $query = "SELECT * FROM quiz_questions WHERE id NOT IN ($placeholders) ORDER BY id ASC LIMIT 1";
    $stmt = $sql->prepare($query);
    $stmt->execute($used_questions);
} else {
    $query = "SELECT * FROM quiz_questions ORDER BY id ASC LIMIT 1";
    $stmt = $sql->prepare($query);
    $stmt->execute();
}

$question = $stmt->fetch(PDO::FETCH_ASSOC);

if ($question) {
    $_SESSION['quiz']['used_questions'][] = $question['id'];
    $_SESSION['quiz']['total_answered']++;

    echo json_encode([
        'success' => true,
        'question' => [
            'id' => $question['id'],
            'question' => htmlspecialchars($question['question']),
            'option_a' => htmlspecialchars($question['option_a']),
            'option_b' => htmlspecialchars($question['option_b']),
            'option_c' => htmlspecialchars($question['option_c']),
            'option_d' => htmlspecialchars($question['option_d']),
            'correct_option' => $question['correct_option']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No more questions']);
}
