<?php
session_start();
require_once("db.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Validate user_id
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
if (!$user_id) {
    die("User ID is required.");
}

// Sanitize inputs
$full_name = htmlspecialchars(trim($_POST['full_name']));
$email = htmlspecialchars(trim($_POST['email']));
$phone_number = htmlspecialchars(trim($_POST['phone_number']));

// Handle avatar upload
$avatar_url = null;
if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    $avatar = $_FILES['avatar'];
    $target_dir = "uploads/avatars/";
    $ext = strtolower(pathinfo($avatar["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid('avatar_') . "." . $ext;
    $target_file = $target_dir . $new_filename;

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($ext, $allowed)) {
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        if (move_uploaded_file($avatar["tmp_name"], $target_file)) {
            $avatar_url = $target_file;
        }
    }
}

// Update user
$query = "UPDATE users SET full_name = ?, email = ?, phone_number = ?" .
    ($avatar_url ? ", avatar_url = ?" : "") . " WHERE id = ?";
$params = $avatar_url ? [$full_name, $email, $phone_number, $avatar_url, $user_id] : [$full_name, $email, $phone_number, $user_id];

$stmt = $sql->prepare($query);
$stmt->execute($params);

header("Location: user_profile.php?id=$user_id&update=success");
exit();
