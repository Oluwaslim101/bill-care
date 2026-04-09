<?php

// Enable error reporting for debugging

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If resetting password
    if (isset($_POST['new_password'], $_POST['confirm_password'], $_POST['token'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $token = $_POST['token'];

        if (empty($new_password) || empty($confirm_password)) {
            $error = "All fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Find email linked to token
            $stmt = $sql->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at >= (NOW() - INTERVAL 1 HOUR)");
            $stmt->execute([$token]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $email = $row['email'];
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update user's password
                $sql->prepare("UPDATE users SET password = ? WHERE email = ?")->execute([$hashed_password, $email]);

                // Delete token after successful reset
                $sql->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);

                $success = "Password updated successfully. You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Invalid or expired token.";
            }
        }
    }

    // If requesting reset link
    elseif (isset($_POST['email'])) {
        $email = trim($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email address.";
        } else {
            $stmt = $sql->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $token = bin2hex(random_bytes(50));
                $sql->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())")->execute([$email, $token]);

                $resetLink = "https://swiftaffiliates.cloud/forgot_reset.php?token=$token";

                // Send email
                $subject = "Password Reset Request";
                $message = "Hi,\n\nClick the link below to reset your password:\n$resetLink\n\nIf you did not request this, ignore this email.";
                $headers = "From: no-reply@yourdomain.com";

                if (mail($email, $subject, $message, $headers)) {
                    $success = "We have sent a password reset link to your email.";
                } else {
                    $error = "Failed to send email. Please try again.";
                }
            } else {
                $error = "Email address not found.";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="theme-color" content="#000000">
    <title>Forgot/Reset Password</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<!-- loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>
<!-- * loader -->

<!-- App Header -->
<div class="appHeader no-border transparent position-absolute">
    <div class="left">
        <a href="login.php" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        <?php echo isset($_GET['token']) ? 'Reset Password' : 'Forgot Password'; ?>
    </div>
    <div class="right">
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-2 text-center">
        <?php if (isset($_GET['token'])): ?>
            <h1>Reset Your Password</h1>
            <h4>Enter a new password below</h4>
        <?php else: ?>
            <h1>Forgot Password</h1>
            <h4>Type your email to reset your password</h4>
        <?php endif; ?>
    </div>

    <div class="section mb-5 p-2">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?><?php if (!empty($success)): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: <?php echo json_encode(strip_tags($success)); ?>,
                timer: 5000,
                timerProgressBar: true,
                showConfirmButton: false
            });

            setTimeout(function(){
                window.location.href = 'login.php';
            }, 5000);
        });
    </script>
<?php endif; ?>
     

        <?php if (empty($success)): ?>
            <?php if (isset($_GET['token'])): ?>
                <!-- Reset password form -->
                <form method="POST" action="">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                    <div class="card">
                        <div class="card-body pb-1">

                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="new_password">New Password</label>
                                    <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                                </div>
                            </div>

                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="confirm_password">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-button-group transparent">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Reset Password</button>
                    </div>
                </form>
            <?php else: ?>
                <!-- Request reset link form -->
                <form method="POST" action="">
                    <div class="card">
                        <div class="card-body pb-1">

                            <div class="form-group basic">
                                <div class="input-wrapper">
                                    <label class="label" for="email1">E-mail</label>
                                    <input type="email" class="form-control" id="email1" name="email" placeholder="Your e-mail" required>
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="form-button-group transparent">
                        <button type="submit" class="btn btn-primary btn-block btn-lg">Send Reset Link</button>
                    </div>

                </form>
            <?php endif; ?>
        <?php endif; ?>

    </div>

</div>
<!-- * App Capsule -->

<!-- ========= JS Files ========= -->
<script src="assets/js/lib/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="assets/js/plugins/splide/splide.min.js"></script>
<script src="assets/js/base.js"></script>

</body>
</html>