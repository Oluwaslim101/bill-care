<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Redirect if user not found
if (!$user) {
    header('Location: login.php');
    exit();
}

// Assign user data

$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';
$balance = number_format($user['balance'], 2);
$kycStatus = $user['status'] ?? 'pending'; 


// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();


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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
    
   .swal2-container {
    z-index: 20000 !important;
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
    <div class="pageTitle">
        Settings
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

       <!-- Profile Header -->
<div class="section mt-1 px-1">
    <div class="d-flex align-items-center">
        <!-- Avatar Left -->
        <div class="me-3">
            <img src="<?= $avatar_url ?>" alt="User Avatar" class="imaged w92 rounded" style="width: 92px; height: 92px;">
        </div>

        <!-- Info Right -->
        <div>
            <h3 class="mb-0"><?= htmlspecialchars($user['full_name']) ?></h3>
            <p class="text-muted small mb-0"><?= htmlspecialchars($user['email']) ?></p>
            <p class="text-muted small mb-0"><?= htmlspecialchars($user['phone_number']) ?></p>
        </div>
    </div>
</div>
  <div class="card mt-1 p-2">
  <div class="card-body py-0 px-1">
    <h4 class="mb-0">Account Details</h4>
    <p class="mb-0 small"><strong>Bank:</strong> <?= $user['bank_name'] ?></p>
    <p class="mb-0 small"><strong>Account No:</strong> <?= $user['virtual_account_number'] ?></p>
    <p class="mb-0 small"><strong>Name:</strong> <?= $user['account_name'] ?></p>
  </div>
</div>

        <!-- Profile Options -->
        <div class="listview-title mt-0"></div>
        <ul class="listview image-listview inset">

            <li>
                <a href="edit_profile.php" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="create-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Edit Profile
                    </div>
                </a>
            </li>
            
          
            <li>
                <a href="transactions.php" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Transactions History
                    </div>
                </a>
            </li>
            <li>
                <a href="my_disputes.php" class="item">
                    <div class="icon-box bg-warning">
                        <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Disputes History
                    </div>
                </a>
            </li>


<li>
    <a href="<?= $kycStatus === 'verified' ? '#' : 'verifyface.html' ?>" 
       class="item kyc-link" 
       data-status="<?= $kycStatus ?>">
        <div class="icon-box bg-warning">
            <ion-icon name="receipt-outline"></ion-icon>
        </div>
        <div class="in d-flex justify-content-between align-items-center w-100">
            <span>KYC Verification</span>
            <span class="badge <?= $kycStatus === 'verified' ? 'bg-success' : 'bg-secondary' ?>">
                <?= ucfirst($kycStatus) ?>
            </span>
        </div>
    </a>
</li>

          <li>
                <a href="support.php" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="headset-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Customer Service
                    </div>
                </a>
            </li>
            
             <li>
                <a href="about.php" class="item">
                    <div class="icon-box bg-dark">
                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                    </div>
                    <div class="in">
                       About Us
                    </div>
                </a>
            </li>
            
             <li>
                <a href="#" class="item">
                    <div class="icon-box bg-dark">
                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                    </div>
                    <div class="in">
                       Close Account
                    </div>
                </a>
            </li>

            <li>
                <a href="logout.php" class="item">
                    <div class="icon-box bg-danger">
                        <ion-icon name="log-out-outline"></ion-icon>
                    </div>
                    <div class="in">
                        Logout
                    </div>
                </a>
            </li>

        </ul>

    </div>
    <!-- * App Capsule -->
    
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.querySelectorAll('.kyc-link').forEach(link => {
    link.addEventListener('click', function (e) {
      const status = this.getAttribute('data-status');
      if (status === 'verified') {
        e.preventDefault();
        Swal.fire({
          icon: 'success',
          title: 'KYC Verified 🎉',
          text: 'Congratulations! Your KYC has been verified successfully.',
          confirmButtonText: 'Awesome!'
        });
      }
    });
  });
</script>


  <?php include 'footer.php'; ?>
</body>
</html>
