<?php
session_start();
require_once("db.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
if (!$user_id) {
    die("User ID is required.");
}

// Clean inputs
$balance = floatval($_POST['balance']);
$earnings = floatval($_POST['earnings']);
$bonus = floatval($_POST['bonus']);
$deposit = floatval($_POST['deposit']);
$withdrawal = floatval($_POST['withdrawal']);
$investment = floatval($_POST['investment']);
$points = intval($_POST['points']);

// Update wallet fields
$query = "UPDATE users SET balance = ?, earnings = ?, bonus = ?, deposit = ?, withdrawal = ?, investment = ?, points = ? WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$balance, $earnings, $bonus, $deposit, $withdrawal, $investment, $points, $user_id]);

header("Location: user_profile.php?id=$user_id&update_wallet=success");
exit();
