<?php
session_start();
include('db.php'); // This sets $sql (PDO connection)

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$_SESSION['flash_message'] = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $nick_name = $_POST['nick_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';

    // Fetch existing avatar_url
    $stmt = $sql->prepare("SELECT avatar_url FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $avatar_url = $user['avatar_url'];

    // Handle avatar upload
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
        $avatar = $_FILES['avatar'];
        $avatar_name = $avatar['name'];
        $avatar_tmp_name = $avatar['tmp_name'];
        $avatar_ext = pathinfo($avatar_name, PATHINFO_EXTENSION);

        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array(strtolower($avatar_ext), $allowed_exts)) {
            $avatar_new_name = 'avatar_' . md5(time()) . '.' . $avatar_ext;

            $upload_dir = 'uploads/avatars/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            if (move_uploaded_file($avatar_tmp_name, $upload_dir . $avatar_new_name)) {
                $avatar_url = $upload_dir . $avatar_new_name;
            }
        }
    }

    // Update user details
    $query = "UPDATE users 
              SET full_name = ?, nick_name = ?, gender = ?, dob = ?, email = ?, address = ?, avatar_url = ? 
              WHERE id = ?";

    $stmt = $sql->prepare($query);
    $success = $stmt->execute([$full_name, $nick_name, $gender, $dob, $email, $address, $avatar_url, $user_id]);

    if ($success) {
        $_SESSION['flash_message'] = "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
              Profile updated successfully!
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        ";
        header('Location: edit_profile.php');
        exit;
    } else {
        $_SESSION['flash_message'] = "
            <div class='alert alert-danger alert-dismissible fade show' role='alert'>
              Error updating profile. Please try again.
              <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
        ";
        header('Location: edit_profile.php');
        exit;
    }
}
?>