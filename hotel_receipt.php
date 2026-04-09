<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

$reference = $_GET['reference'] ?? '';

if (!$reference) {
    echo "Invalid or missing reference.";
    exit();
}

// Fetch booking details
$stmt = $sql->prepare("
    SELECT b.*, h.name AS hotel_name, u.full_name 
    FROM bookings b 
    JOIN hotels h ON b.hotel_id = h.id 
    JOIN users u ON b.customer_email = u.email 
    WHERE b.reference = ?
");
$stmt->execute([$reference]);
$data = $stmt->fetch();

if (!$data) {
    echo "Invalid receipt reference.";
    exit();
}
?>


<!DOCTYPE html>
<html>
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
        Select Category
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
<div id="appCapsule" class="pb-5">
    <div class="section mt-3 text-center">
        <h2 class="fw-bold">Hotel Booking Receipt</h2>
        <p class="text-secondary"><?= date('F j, Y', strtotime($data['created_at'])) ?></p>
    </div>

    <div class="section p-3 rounded shadow-sm mx-2 mb-3 theme-box">
        <!-- Booking Reference -->
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
            <span class="text-secondary small">Reference</span>
            <span class="fw-bold text-primary"><?= $data['reference'] ?></span>
        </div>

        <!-- Guest Details -->
        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Full Name</span>
            <span class="fw-semibold"><?= htmlspecialchars($data['full_name']) ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Email</span>
            <span class="fw-semibold"><?= htmlspecialchars($data['customer_email']) ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Hotel</span>
            <span class="fw-semibold"><?= htmlspecialchars($data['hotel_name']) ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Room Type</span>
            <span class="fw-semibold"><?= htmlspecialchars($data['room_type']) ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Guests</span>
            <span class="fw-semibold"><?= $data['guests'] ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Check-in</span>
            <span class="fw-semibold"><?= date('M d, Y', strtotime($data['checkin_date'])) ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Check-out</span>
            <span class="fw-semibold"><?= date('M d, Y', strtotime($data['checkout_date'])) ?></span>
        </div>

        <div class="d-flex justify-content-between mb-2">
            <span class="text-secondary small">Purpose</span>
            <span class="fw-semibold"><?= htmlspecialchars($data['purpose']) ?></span>
        </div>

        <hr class="mb-2 mt-2">

        <!-- Total -->
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-secondary">Total Amount</span>
            <h4 class="text-success fw-bold mb-0">₦<?= number_format($data['total_cost'], 2) ?></h4>
        </div>

        <!-- QR Code -->
        <div class="text-center mt-2">
            <img src="qrcodes/<?= $data['reference'] ?>.png" alt="QR Code" style="width: 150px; height: 150px;">
            <p class="text-secondary mt-1 small">Kindly Present this QR Code to Hotel Mgt on Check-in</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>

</html>