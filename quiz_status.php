<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');
session_start();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

function addUserPoints($user_id, $points_to_add) {
    global $sql;
    $update = $sql->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $update->execute([$points_to_add, $user_id]);
}

// Constants
define('MAX_QUESTIONS', 15);
define('LOCKOUT_LIMIT_HOURS', 4);
define('FAIL_STREAK_LIMIT', 3);
define('FAIL_LOCKOUT_HOURS', 6);

// Fetch or init quiz_progress row
$stmt = $sql->prepare("SELECT * FROM quiz_progress WHERE user_id = ?");
$stmt->execute([$user_id]);
$progress = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$progress) {
    // Init row if none exists
    $insert = $sql->prepare("INSERT INTO quiz_progress (user_id) VALUES (?)");
    $insert->execute([$user_id]);
    $progress = [
        'asked_count' => 0,
        'fail_streak' => 0,
        'last_fail_time' => null,
        'last_lockout_time' => null,
        'lockout_type' => 'none'
    ];
}

// Helper to check if locked out currently
function getLockoutStatus($progress) {
    $now = new DateTime();
    $locked_out = false;
    $lockout_remaining = 0;

    if ($progress['lockout_type'] === 'limit_15') {
        if ($progress['last_lockout_time']) {
            $lockout_start = new DateTime($progress['last_lockout_time']);
            $lockout_end = clone $lockout_start;
            $lockout_end->modify('+'.LOCKOUT_LIMIT_HOURS.' hours');

            if ($now < $lockout_end) {
                $locked_out = true;
                $lockout_remaining = $lockout_end->getTimestamp() - $now->getTimestamp();
            } else {
                // Reset lockout
                $locked_out = false;
            }
        }
    } elseif ($progress['lockout_type'] === 'fail_3') {
        if ($progress['last_lockout_time']) {
            $lockout_start = new DateTime($progress['last_lockout_time']);
            $lockout_end = clone $lockout_start;
            $lockout_end->modify('+'.FAIL_LOCKOUT_HOURS.' hours');

            if ($now < $lockout_end) {
                $locked_out = true;
                $lockout_remaining = $lockout_end->getTimestamp() - $now->getTimestamp();
            } else {
                // Reset lockout
                $locked_out = false;
            }
        }
    }

    return ['locked_out' => $locked_out, 'lockout_remaining' => $lockout_remaining];
}

$action = $_POST['action'] ?? null;

if ($action === 'increment_fail') {
    // Increase fail streak by 1
    $fail_streak = $progress['fail_streak'] + 1;
    $asked_count = $progress['asked_count'];

    $now = (new DateTime())->format('Y-m-d H:i:s');

    // Determine if fail lockout triggered
    $lockout_type = $progress['lockout_type'];
    $last_lockout_time = $progress['last_lockout_time'];

    if ($fail_streak >= FAIL_STREAK_LIMIT) {
        // Fail lockout triggered
        $lockout_type = 'fail_3';
        $last_lockout_time = $now;
        // Reset fail streak to 0 after lockout
        $fail_streak = 0;
    }

    // Save updated progress
    $update = $sql->prepare("
        UPDATE quiz_progress SET
            fail_streak = ?,
            last_fail_time = ?,
            lockout_type = ?,
            last_lockout_time = ?,
            asked_count = ?
        WHERE user_id = ?
    ");
    $update->execute([$fail_streak, $now, $lockout_type, $last_lockout_time, $asked_count, $user_id]);

    // Re-fetch progress after update
    $stmt->execute([$user_id]);
    $progress = $stmt->fetch(PDO::FETCH_ASSOC);

    $lockoutStatus = getLockoutStatus($progress);

    echo json_encode([
        'success' => true,
        'fail_count' => $fail_streak,
        'locked_out' => $lockoutStatus['locked_out'],
        'lockout_remaining' => $lockoutStatus['lockout_remaining']
    ]);
    exit;
}

if ($action === 'reset_fail') {
    $update = $sql->prepare("UPDATE quiz_progress SET fail_streak = 0 WHERE user_id = ?");
    $update->execute([$user_id]);

    $pointsEarned = 10;
    addUserPoints($user_id, $pointsEarned);

    // Fetch updated points
    $stmtPoints = $sql->prepare("SELECT points FROM users WHERE id = ?");
    $stmtPoints->execute([$user_id]);
    $currentPoints = (int)$stmtPoints->fetchColumn();

    echo json_encode([
        'success' => true,
        'points_added' => $pointsEarned,
        'current_points' => $currentPoints
    ]);
    exit;
}


// Normal GET check for lockouts and question limit
$lockoutStatus = getLockoutStatus($progress);

// Check if user reached max questions
if ($progress['asked_count'] >= MAX_QUESTIONS && $progress['lockout_type'] !== 'fail_3') {
    // Lock user for 4 hours after 15 questions answered
    if ($progress['lockout_type'] !== 'limit_15') {
        $nowStr = (new DateTime())->format('Y-m-d H:i:s');
        $update = $sql->prepare("UPDATE quiz_progress SET lockout_type = 'limit_15', last_lockout_time = ? WHERE user_id = ?");
        $update->execute([$nowStr, $user_id]);
        $lockoutStatus = getLockoutStatus(['lockout_type' => 'limit_15', 'last_lockout_time' => $nowStr]);
    }
}

// If lockout expired, reset lockout_type and counters
if (!$lockoutStatus['locked_out'] && $progress['lockout_type'] !== 'none') {
    $update = $sql->prepare("UPDATE quiz_progress SET lockout_type = 'none', asked_count = 0, fail_streak = 0, last_lockout_time = NULL WHERE user_id = ?");
    $update->execute([$user_id]);
    $progress['asked_count'] = 0;
    $progress['fail_streak'] = 0;
    $lockoutStatus['locked_out'] = false;
    $lockoutStatus['lockout_remaining'] = 0;
}

echo json_encode([
    'success' => true,
    'fail_count' => $progress['fail_streak'],
    'asked_count' => $progress['asked_count'],
    'locked_out' => $lockoutStatus['locked_out'],
    'lockout_remaining' => $lockoutStatus['lockout_remaining']
]);
