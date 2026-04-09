<?php
session_start();
include('db.php');

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Fetch quiz progress from the database
$progressStmt = $sql->prepare("SELECT * FROM quiz_progress WHERE user_id = ?");
$progressStmt->execute([$user_id]);
$progress = $progressStmt->fetch(PDO::FETCH_ASSOC);

if (!$progress) {
    // Initialize quiz progress for the user if not exists
    $sql->prepare("INSERT INTO quiz_progress (user_id, asked_count, fail_streak, last_fail_time, last_lockout_time, lockout_type, used_questions)
                   VALUES (?, 0, 0, NULL, NULL, NULL, '[]')")
        ->execute([$user_id]);

    $progress = [
        'asked_count' => 0,
        'fail_streak' => 0,
        'last_fail_time' => null,
        'last_lockout_time' => null,
        'lockout_type' => null,
        'used_questions' => '[]'
    ];
}

// Decode used_questions JSON
$used_questions = json_decode($progress['used_questions'], true) ?? [];

// Check if the quiz is locked
if ($progress['last_lockout_time']) {
    $lockTime = new DateTime($progress['last_lockout_time']);
    $now = new DateTime();
    $diff = $now->getTimestamp() - $lockTime->getTimestamp();

    $lock_duration = $progress['lockout_type'] === '6h' ? 6 * 3600 : 4 * 3600;

    if ($diff < $lock_duration) {
        $remaining = $lock_duration - $diff;
        echo json_encode([
            'success' => false,
            'locked' => true,
            'message' => "Locked. Please wait " . gmdate("H:i:s", $remaining) . " before retrying."
        ]);
        exit;
    } else {
        // Clear expired lockout
        $sql->prepare("UPDATE quiz_progress SET asked_count = 0, fail_streak = 0, last_fail_time = NULL, last_lockout_time = NULL, lockout_type = NULL, used_questions = '[]' WHERE user_id = ?")
            ->execute([$user_id]);

        $progress['asked_count'] = 0;
        $progress['fail_streak'] = 0;
        $used_questions = [];
    }
}

// Parse input data
$data = json_decode(file_get_contents('php://input'), true);
$question_id = $data['question_id'] ?? null;
$selected_option = strtolower($data['selected_option'] ?? '');

if (!$question_id || !$selected_option) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Get correct option for the question
$questionQuery = $sql->prepare("SELECT correct_option FROM questions WHERE id = ?");
$questionQuery->execute([$question_id]);
$question = $questionQuery->fetch(PDO::FETCH_ASSOC);

if (!$question) {
    echo json_encode(['success' => false, 'message' => 'Question not found']);
    exit;
}

$correct_option = strtolower($question['correct_option']);

// Check if the answer is correct
if ($selected_option === $correct_option) {
    // Correct answer
    $progress['asked_count']++;
    $progress['fail_streak'] = 0;
    $used_questions[] = $question_id;

    // Update progress in the database
    $sql->prepare("UPDATE quiz_progress SET asked_count = ?, fail_streak = 0, last_fail_time = NULL, used_questions = ? WHERE user_id = ?")
        ->execute([$progress['asked_count'], json_encode($used_questions), $user_id]);

    if ($progress['asked_count'] >= 15) {
        $lock_time = (new DateTime())->format('Y-m-d H:i:s');
        $sql->prepare("UPDATE quiz_progress SET last_lockout_time = ?, lockout_type = '4h' WHERE user_id = ?")
            ->execute([$lock_time, $user_id]);
            
            $is_correct = 1;
$points = 1; // assuming each correct answer gives 1 point
$answered_at = (new DateTime())->format('Y-m-d H:i:s');

$logAnswer = $sql->prepare("INSERT INTO quiz_answers (user_id, question_id, is_correct, answered_at, points) VALUES (?, ?, ?, ?, ?)");
$logAnswer->execute([$user_id, $question_id, $is_correct, $answered_at, $points]);


        echo json_encode([
            'success' => true,
            'correct' => true,
            'locked' => true,
            'message' => 'Quiz finished. Locked for 4 hours before retry.'
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'correct' => true,
        'locked' => false
    ]);
} else {
    // Wrong answer
    $progress['fail_streak']++;
    $progress['asked_count']++;
    $used_questions[] = $question_id;

    if ($progress['fail_streak'] >= 3) {
        // Lock the user for 6 hours
        $lock_time = (new DateTime())->format('Y-m-d H:i:s');
        $sql->prepare("UPDATE quiz_progress SET asked_count = ?, fail_streak = ?, last_fail_time = ?, last_lockout_time = ?, lockout_type = '6h', used_questions = ? WHERE user_id = ?")
            ->execute([$progress['asked_count'], $progress['fail_streak'], $lock_time, $lock_time, json_encode($used_questions), $user_id]);
            
            $is_correct = 0;
$points = 0;
$answered_at = (new DateTime())->format('Y-m-d H:i:s');

$logAnswer = $sql->prepare("INSERT INTO quiz_answers (user_id, question_id, is_correct, answered_at, points) VALUES (?, ?, ?, ?, ?)");
$logAnswer->execute([$user_id, $question_id, $is_correct, $answered_at, $points]);


        echo json_encode([
            'success' => true,
            'correct' => false,
            'locked' => true,
            'message' => '3 wrong answers in a row. Locked for 6 hours.'
        ]);
        exit;
    } else {
        // Update progress without locking
        $sql->prepare("UPDATE quiz_progress SET asked_count = ?, fail_streak = ?, last_fail_time = ?, used_questions = ? WHERE user_id = ?")
            ->execute([$progress['asked_count'], $progress['fail_streak'], (new DateTime())->format('Y-m-d H:i:s'), json_encode($used_questions), $user_id]);

        echo json_encode([
            'success' => true,
            'correct' => false,
            'locked' => false
        ]);
    }
}
