<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}

// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

// Handle form submission
$updateMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'password' || $action === 'pin') {
        $stmt = $sql->prepare("SELECT password, pin FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($action === 'password') {
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmNewPassword = $_POST['confirm_new_password'] ?? '';

                if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
                    $updateMessage = 'All password fields are required.';
                } elseif (!password_verify($currentPassword, $user['password'])) {
                    $updateMessage = 'Current password is incorrect.';
                } elseif ($newPassword !== $confirmNewPassword) {
                    $updateMessage = 'New passwords do not match.';
                } else {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $update = $sql->prepare("UPDATE users SET password = ? WHERE id = ?");
                    if ($update->execute([$hashedPassword, $user_id])) {
                        // Success: reload to clear form
                        header('Location: edit_profile.php?update=success');
exit();
                    } else {
                        $updateMessage = 'Failed to update password.';
                    }
                }
            } elseif ($action === 'pin') {
                $currentPin = $_POST['current_pin'] ?? '';
                $newPin = $_POST['new_pin'] ?? '';
                $confirmNewPin = $_POST['confirm_new_pin'] ?? '';

                if (empty($currentPin) || empty($newPin) || empty($confirmNewPin)) {
                    $updateMessage = 'All PIN fields are required.';
                } elseif ($currentPin !== $user['pin']) {
                    $updateMessage = 'Current PIN is incorrect.';
                } elseif ($newPin !== $confirmNewPin) {
                    $updateMessage = 'New PINs do not match.';
                } else {
                    $update = $sql->prepare("UPDATE users SET pin = ? WHERE id = ?");
                    if ($update->execute([$newPin, $user_id])) {
                        // Success: reload to clear form
 header('Location: edit_profile.php?update=success');
exit();
                    } else {
                        $updateMessage = 'Failed to update PIN.';
                    }
                }
            }
        } else {
            $updateMessage = 'User not found.';
        }
    }
}

// Show success message if redirected after update
if (isset($_GET['update']) && $_GET['update'] === 'success') {
    $updateMessage = 'Update successful!';
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>DtheHub</title>
    <meta name="description" content="Finapp HTML Mobile Template">
    <meta name="keywords"
        content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
           <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    
    <style>
        
        /* Fixed Bottom Navigation */
.nav {
position: fixed;
bottom: 0;
left: 50%;
transform: translateX(-50%);
width: 100%;
max-width: 410px;
display: flex;
justify-content: space-around;
background: white;
padding: 12px 7px;
border-radius: 8px 8px 0 0;
box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1);
z-index: 1000;
}

.nav a {
text-decoration: none;
color: gray;
font-size: 12px;
text-align: center;
display: flex;
flex-direction: column;
align-items: center;
gap: 3px;
flex: 1;
transition: color 0.3s ease;
}

.nav a i {
font-size: 20px;
color: gray;
transition: color 0.3s ease;
}

.nav a span {
font-size: 12px;
font-weight: 500;
}

.nav a.active i,
.nav a.active span {
color: green;
font-weight: bold;
}

    </style>
</head>

<body>

    <!-- loader -->
    <div id="loader">
        <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->
 <!-- App Header -->

<div class="appHeader">

    <div class="left">

        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Security</div>
    <div class="right">
        <div style="position: relative;">
            <i class="fas fa-bell" style="font-size: 25px; color: blue;"></i>
            <?php if ($unread_count > 0): ?>
                <span style="
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: red;
                    color: white;
                    font-size: 10px;
                    font-weight: bold;
                    padding: 2px 6px;
                    border-radius: 50%;
                ">
                    <?= $unread_count ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- App Capsule -->

<div id="appCapsule">
    

    <div class="section-heading">
        <h2 class="title">Get To Know Us Better</h2>
    </div>

    <?php if (!empty($updateMessage)): ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($updateMessage); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

 <div class="listview-title mt-1">Security</div>

<ul class="listview image-listview text mb-2 inset">

<!-- Update Password -->
<li>
    <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#changePasswordSheet">
        <div class="in">
            <div>Update Password</div>
        </div>
    </a>
</li>

<!-- Update PIN -->
<li>
    <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#changePinSheet">
        <div class="in">
            <div>Update PIN</div>
        </div>
    </a>
</li>

<!-- 2-Step Verification -->
<li>
    <div class="item">
        <div class="in">
            <div>2 Step Verification</div>
            <div class="form-check form-switch ms-2">
                <input class="form-check-input" type="checkbox" id="SwitchCheckDefault3" checked />
                <label class="form-check-label" for="SwitchCheckDefault3"></label>
            </div>
        </div>
    </div>
</li>

<!-- Log out all devices -->
<li>
    <a href="#" class="item" onclick="logoutAllDevices()">
        <div class="in">
            <div>Log out all devices</div>
        </div>
    </a>
</li>

<!-- Change Password Action Sheet -->
<div class="modal fade action-sheet" id="changePasswordSheet" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Password</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form method="POST" action="security.php">
                        <input type="hidden" name="action" value="password">
                        <div class="form-group basic">
                            <label class="label" for="current_password">Current Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <label class="label" for="new_password">New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter new password" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <label class="label" for="confirm_new_password">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" placeholder="Confirm new password" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change PIN Action Sheet -->
<div class="modal fade action-sheet" id="changePinSheet" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change PIN</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form method="POST" action="security.php">
                        <input type="hidden" name="action" value="pin">
                        <div class="form-group basic">
                            <label class="label" for="current_pin">Current PIN</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_pin" name="current_pin" placeholder="Enter current PIN" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <label class="label" for="new_pin">New PIN</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_pin" name="new_pin" placeholder="Enter new PIN" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <label class="label" for="confirm_new_pin">Confirm New PIN</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="confirm_new_pin" name="confirm_new_pin" placeholder="Confirm new PIN" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Update PIN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bottom Navigation -->
<nav class="nav">   
<a href="index.php">    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="contracts.php">    
    <i class="fas fa-receipt"></i>    
    <span>Contracts</span>    
</a>   

<a href="transactions.php">    
    <i class="fas fa-receipt"></i>    
    <span>Transactions</span>    
</a>    
<a href="profile.php" class="active">    
    <i class="fas fa-user"></i>    
    <span>Profile</span>    
</a>

</nav>    
    <!-- * App Bottom Menu -->


    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- Apex Charts -->
    <script src="assets/js/plugins/apexcharts/apexcharts.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>


</body>

</html>