<?php
session_start();
include 'db.php'; // PDO connection in $sql

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = $sql->prepare("SELECT referral_code, bonus FROM users WHERE id = ?");
$query->execute([$user_id]);
$user = $query->fetch(PDO::FETCH_ASSOC);

$referral_code = $user['referral_code'];
$bonus = $user['bonus'] ?? 0;

// Fetch referred users
$referrals = [];
$refQuery = $sql->prepare("SELECT full_name, email, created_at FROM users WHERE referred_by = ?");
$refQuery->execute([$user_id]);
$referrals = $refQuery->fetchAll(PDO::FETCH_ASSOC);

$referral_link = "https://swiftaffiliates.cloud/signup.php?ref=" . urlencode($referral_code);

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

<!-- Header -->
 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Referral Analysis
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



    <!-- App Capsule -->
    <div id="appCapsule">

    <div class="card mb-3">
        <div class="card-body text-center">
            <h3>Your Referral Link</h3>
            <div class="input-group mb-2">
                <input type="text" id="referralLink" class="form-control" value="<?= htmlspecialchars($referral_link) ?>" readonly>
                <button class="btn btn-primary copy-btn" onclick="copyLink()">Copy</button>
            </div>

            <div class="d-flex justify-content-center gap-3 mt-3">
                <a href="https://wa.me/?text=Join%20SwiftContract:%20<?= urlencode($referral_link) ?>" class="btn btn-success btn-sm" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($referral_link) ?>" class="btn btn-primary btn-sm" target="_blank">
                    <i class="fab fa-facebook"></i> Facebook
                </a>
                <a href="sms:?body=Join%20SwiftContract:%20<?= urlencode($referral_link) ?>" class="btn btn-dark btn-sm">
                    <i class="fas fa-sms"></i> SMS
                </a>
                <a href="https://www.instagram.com/" class="btn btn-danger btn-sm" target="_blank">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h3>Total Referral Bonus</h3>
            <p class="fs-4 text-success fw-bold">₦<?= number_format($bonus, 2) ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3>Your Referrals</h3>
            <?php if (count($referrals) > 0): ?>
                <ul class="list-group">
                    <?php foreach ($referrals as $ref): ?>
                        <li class="list-group-item">
                            <strong><?= htmlspecialchars($ref['full_name']) ?></strong> <br>
                            <small><?= htmlspecialchars($ref['email']) ?> — <?= date('M d, Y', strtotime($ref['created_at'])) ?></small>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No referrals yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function copyLink() {
        const input = document.getElementById("referralLink");
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(() => {
            alert("Referral link copied!");
        });
    }
</script>

  <!-- Bottom Navigation -->
    <br>
<nav class="nav">   
<a href="index.php" >    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="contracts.php" >    
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
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>
    

</body>

</html>