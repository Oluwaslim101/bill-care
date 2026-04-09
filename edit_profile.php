<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

// Handle POST form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field = $_POST['field_type'] ?? '';

    switch ($field) {
        case 'avatar':
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == UPLOAD_ERR_OK) {
                $avatar_name = $_FILES['avatar']['name'];
                $tmp_name = $_FILES['avatar']['tmp_name'];
                $ext = strtolower(pathinfo($avatar_name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($ext, $allowed)) {
                    $new_name = 'avatar_' . time() . '.' . $ext;
                    $upload_path = 'uploads/avatars/';
                    if (!file_exists($upload_path)) mkdir($upload_path, 0777, true);
                    if (move_uploaded_file($tmp_name, $upload_path . $new_name)) {
                        $stmt = $sql->prepare("UPDATE users SET avatar_url = ? WHERE id = ?");
                        $stmt->execute([$upload_path . $new_name, $user_id]);
                    }
                }
            }
            break;

        case 'full_name':
            $full_name = trim($_POST['full_name']);
            $stmt = $sql->prepare("UPDATE users SET full_name = ? WHERE id = ?");
            $stmt->execute([$full_name, $user_id]);
            break;

        case 'nick_name':
            $nick_name = trim($_POST['nick_name']);
            $stmt = $sql->prepare("UPDATE users SET nick_name = ? WHERE id = ?");
            $stmt->execute([$nick_name, $user_id]);
            break;

        case 'gender':
            $gender = trim($_POST['gender']);
            $stmt = $sql->prepare("UPDATE users SET gender = ? WHERE id = ?");
            $stmt->execute([$gender, $user_id]);
            break;

        case 'dob':
            $dob = trim($_POST['dob']);
            $stmt = $sql->prepare("UPDATE users SET dob = ? WHERE id = ?");
            $stmt->execute([$dob, $user_id]);
            break;

        case 'email':
            $email = trim($_POST['email']);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $stmt = $sql->prepare("UPDATE users SET email = ? WHERE id = ?");
                $stmt->execute([$email, $user_id]);
            }
            break;

        case 'address':
            $address = trim($_POST['address']);
            $stmt = $sql->prepare("UPDATE users SET address = ? WHERE id = ?");
            $stmt->execute([$address, $user_id]);
            break;
    }

    $_SESSION['flash_message'] = "Profile updated successfully.";
    header("Location: edit_profile.php");
    exit;
}
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
                        header('Location: ' . $_SERVER['PHP_SELF'] . '?update=success');
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
                        header('Location: ' . $_SERVER['PHP_SELF'] . '?update=success');
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


   
<!DOCTYPE html>  
<html lang="en">    <head>    

    <meta charset="UTF-8">    

<meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>DtheHub</title>
    <meta name="description" content="Finapp HTML Mobile Template">
    <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> 

    <style>
        
        /* Avatar image styling */
#avatarPreview {
    width: 150px;  /* Set the desired size */
    height: 150px; /* Set the desired size */
    object-fit: cover; /* Ensure the image maintains aspect ratio without stretching */
    border-radius: 50%; /* Make the image circular */
    border: 3px solid #fff; /* Optional: Add a border around the circle */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Add a shadow for visual appeal */
}

.avatar-section {
    position: relative;
    display: inline-block;
}

#avatarLink {
    position: relative;
    display: inline-block;
}

#avatarPreview {
    cursor: pointer; /* Make the avatar clickable */
}

button {
    display: none;
}
        
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
     

<!-- Loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>

  <?php
    // Show flash message if it exists
    if (isset($_SESSION['flash_message']) && $_SESSION['flash_message'] != '') {
        echo '<div class="alert alert-success">' . $_SESSION['flash_message'] . '</div>';
        unset($_SESSION['flash_message']); // Clear message after showing
    }
    ?>
 
    <?php if (!empty($updateMessage)): ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($updateMessage); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    

 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Profile 

    </div>
    
   
<!-- Right Side: Dark Mode Toggle with Icons and Notification Bell -->
<div class="right d-flex align-items-center">

    <!-- Dark Mode Toggle Icons -->
    <div class="d-flex align-items-center me-3">
 <div class="form-check form-switch  ms-2">
                            <input class="form-check-input dark-mode-switch" type="checkbox" id="darkmodeSwitch">
                            <label class="form-check-label" for="darkmodeSwitch"></label>
  
        </div>
    </div>

        <!-- Notification Bell Icon -->
    <a href="#" id="notificationsButton" class="headerButton position-relative">
        <ion-icon class="icon" name="notifications-outline"></ion-icon>
        <span class="badge badge-danger position-absolute top-6 start-100 translate-middle p-1 rounded-circle" id="notification-count"></span>
    </a>

</div>

<script>
    // Fetch unread notifications
    function fetchUnreadNotifications() {
        $.ajax({
            url: 'get_unread_notifications.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const count = data.unread_count;
                const notifBadge = $('#notification-count');
                
                if (count > 0) {
                    notifBadge.text(count).show();
                } else {
                    notifBadge.hide();
                }
            }
        });
    }

    // Fetch all notifications when the bell is clicked
    $('#notificationsButton').on('click', function() {
        $.ajax({
            url: 'get_all_notifications.php',  // Create a new PHP file to fetch all notifications
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                const notifications = data.notifications;
                let notificationHTML = '';

                notifications.forEach(notification => {
                    notificationHTML += `
                        <div class="notification-item">
                            <p>${notification.message}</p>
                            <small>${notification.created_at}</small>
                        </div>
                    `;
                });

                $('#notification-list').html(notificationHTML);
                $('#notificationsModal').modal('show');  // Show the modal
            }
        });
    });

    // Initial fetch of unread notifications
    $(document).ready(function() {
        fetchUnreadNotifications();
    });
</script>

    </div>
</div>
<!-- * App Header -->


<!-- App Capsule -->

<div id="appCapsule">

<!-- Avatar Upload Section -->
<div class="section mt-1 text-center">
    <div class="avatar-section">
        <form id="avatarForm" method="POST" action="edit_profile.php" enctype="multipart/form-data">
            <input type="hidden" name="field_type" value="avatar">
            <a href="#" id="avatarLink" onclick="document.getElementById('avatarInput').click(); return false;">
                <img src="<?= htmlspecialchars($user['avatar_url'] ?? 'assets/img/sample/avatar/avatar1.jpg') ?>" id="avatarPreview" alt="avatar" class="imaged rounded-circle" />
                <span class="button">
                    <ion-icon name="camera-outline"></ion-icon>
                </span>
            </a>
            <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none;" onchange="document.getElementById('avatarForm').submit();">
        </form>
    </div>
</div>

     <div class="card mt-0 p-2">
  <div class="card-body py-0 px-0">
    <h4 class="mb-0">Account Details</h4>

    <p class="mb-0 small">
      <strong>Bank:</strong> <?= htmlspecialchars($user['bank_name']) ?>
    </p>

    <p class="mb-0 small">
      <strong>Account No:</strong>
      <span id="virtualAccount"><?= htmlspecialchars($user['virtual_account_number']) ?></span>
      <i class="bi bi-clipboard ms-1 text-primary" style="cursor: pointer;" onclick="copyVirtualAccount()" title="Copy to clipboard"></i>
    </p>

    <p class="mb-0 small">
      <strong>Name:</strong> <?= htmlspecialchars($user['account_name']) ?>
    </p>
  </div>
</div>

<!-- Profile Info List -->

  <div class="section mt-1 full">
<ul class="listview image-listview text inset">
  
   <li>
        <div class="card-body">
        <h4><strong> Personal Info</strong></h4>
                
                  
        </li>
        <li>
        <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#editFullName">
            <div class="in">
                <div>Full Name</div>
                <span class="text-muted"><?= htmlspecialchars($user['full_name'] ?? '') ?></span>
            </div>
        </a>
    </li>
    <li>
        <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#editNickName">
            <div class="in">
                <div>Nick Name</div>
                <span class="text-muted"><?= htmlspecialchars($user['nick_name'] ?? '') ?></span>
            </div>
        </a>
    </li>
    <li>
        <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#editGender">
            <div class="in">
                <div>Gender</div>
                <span class="text-muted"><?= htmlspecialchars($user['gender'] ?? '') ?></span>
            </div>
        </a>
    </li>
    <li>
        <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#editDOB">
            <div class="in">
                <div>Date of Birth</div>
                <span class="text-muted"><?= htmlspecialchars($user['dob'] ?? '') ?></span>
            </div>
        </a>
    </li>
    <li>
        <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#editEmail">
            <div class="in">
                <div>Email</div>
                <span class="text-muted"><?= htmlspecialchars($user['email'] ?? '') ?></span>
            </div>
        </a>
    </li>
    <li>
        <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#editAddress">
            <div class="in">
                <div>Address</div>
                <span class="text-muted"><?= htmlspecialchars($user['address'] ?? '') ?></span>
            </div>
        </a>
    </li>
</ul>

<div class="modal fade action-sheet" id="editFullName" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Full Name</h5></div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form method="POST" action="edit_profile.php">
                        <input type="hidden" name="field_type" value="full_name">
                        <div class="form-group basic">
                            <input type="text" class="form-control" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group basic mt-2">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Save Changes</button>
                            <button type="button" class="btn btn-text btn-block btn-lg" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade action-sheet" id="editNickName" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Nick Name</h5></div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form method="POST" action="edit_profile.php">
                        <input type="hidden" name="field_type" value="nick_name">
                        <div class="form-group basic">
                            <input type="text" class="form-control" name="nick_name" value="<?= htmlspecialchars($user['nick_name'] ?? '') ?>" required>
                        </div>
                        <div class="form-group basic mt-2">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Save Changes</button>
                            <button type="button" class="btn btn-text btn-block btn-lg" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade action-sheet" id="editGender" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Gender</h5></div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form method="POST" action="edit_profile.php">
                        <input type="hidden" name="field_type" value="gender">
                        <div class="form-group basic">
                            <select class="form-control" name="gender" required>
                                <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= $user['gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group basic mt-2">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Save Changes</button>
                            <button type="button" class="btn btn-text btn-block btn-lg" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade action-sheet" id="editEmail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Email</h5></div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form method="POST" action="edit_profile.php">
                        <input type="hidden" name="field_type" value="email">
                        <div class="form-group basic">
                            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                        </div>
                        <div class="form-group basic mt-2">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Save Changes</button>
                            <button type="button" class="btn btn-text btn-block btn-lg" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade action-sheet" id="editAddress" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Address</h5></div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form method="POST" action="edit_profile.php">
                        <input type="hidden" name="field_type" value="address">
                        <div class="form-group basic">
                            <textarea class="form-control" name="address" required><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group basic mt-2">
                            <button type="submit" class="btn btn-primary btn-block btn-lg">Save Changes</button>
                            <button type="button" class="btn btn-text btn-block btn-lg" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


  <div class="section mt-1 full">
<ul class="listview image-listview text inset">
  
   <li>
        <div class="card-body">
        <h4><strong> Security</strong></h4>
                
                  
        </li>
    

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
    <a href="logout.php" class="item" onclick="logoutAllDevices()">
        <div class="in">
            <div>Log out all devices</div>
        </div>
    </a>
</li>
</ul>
<br>
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
                                <input type="number" class="form-control" id="current_pin" name="current_pin" placeholder="Enter current PIN" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <label class="label" for="new_pin">New PIN</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="new_pin" name="new_pin" placeholder="Enter new PIN" required>
                            </div>
                        </div>
                        <div class="form-group basic">
                            <label class="label" for="confirm_new_pin">Confirm New PIN</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="confirm_new_pin" name="confirm_new_pin" placeholder="Confirm new PIN" required>
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

    <!-- ========= JS Files =========  -->

    <script>
function copyVirtualAccount() {
  const acct = document.getElementById('virtualAccount').textContent;
  navigator.clipboard.writeText(acct).then(() => {
    alert("Account number copied!");
  }).catch(() => {
    alert("Failed to copy account number.");
  });
}
</script>

<?php include 'footer.php'; ?>
</body>
</html>
