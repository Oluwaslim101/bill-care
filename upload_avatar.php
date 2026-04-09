<?php
include('db.php');
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit;
}

if (!empty($_FILES['avatar']['name'])) {
    $uploadDir = 'uploads/avatars/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES['avatar']['name']);
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadFile)) {
        $stmt = $sql->prepare("UPDATE users SET avatar_url = ? WHERE id = ?");
        $stmt->execute([$uploadFile, $user_id]);

        echo json_encode(["success" => true, "avatar_url" => $uploadFile]);
    } else {
        echo json_encode(["success" => false, "message" => "Upload failed"]);
    }
}
?>