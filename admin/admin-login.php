<?php
session_start();
require_once("db.php");

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
} elseif (isset($_SESSION['user_logged_in'])) {
    header("Location: ../user/dashboard.php"); // adjust user dashboard path if needed
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        try {
            // 1. Check admin login
            $stmt = $sql->prepare("SELECT * FROM admin_users WHERE username = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin && password_verify($password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: index.php");
                exit();
            }

            // 2. Check regular user login
            $stmt = $sql->prepare("SELECT * FROM users WHERE email = :username OR phone_number = :username LIMIT 1");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                header("Location: ../user/dashboard.php");
                exit();
            }

            // If neither matched
            $error = "Invalid credentials.";

        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Swift Contract</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-box">
    <div class="section mt-5 text-center">
        <h1>Admin Login</h1>
        <p>Please sign in to manage the platform</p>
    </div>

    <div class="section mt-2">
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="username">Username</label>
                    <input type="text" name="username" class="form-control" id="username" required>
                </div>
            </div>

            <div class="form-group boxed">
                <div class="input-wrapper">
                    <label class="label" for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>
            </div>

            <div class="form-button-group">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
        </form>
    </div>
</div>

<!-- Ionicons and Bootstrap -->
<script type="module" src="https://unpkg.com/ionicons@6.0.3/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@6.0.3/dist/ionicons/ionicons.js"></script>
<script src="assets/js/lib/bootstrap.bundle.min.js"></script>

</body>
</html>