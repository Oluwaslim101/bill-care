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


// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

// Fetch upcoming events
$stmt = $sql->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    .action-sheet-body {
    max-height: 65vh;
    overflow-y: auto;
    padding-bottom: 10px;
}

    
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
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section mt-2">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?= htmlspecialchars($event['name']) ?></h5>
                        <p class="text-muted small mb-1">
                            📍 <?= htmlspecialchars($event['location']) ?><br>
                            📅 <?= date('M d, Y', strtotime($event['event_date'])) ?> @ <?= date('g:i A', strtotime($event['event_time'])) ?><br>
                            💵 <span class="text-success fw-bold">₦<?= number_format($event['ticket_price'], 2) ?></span> / ticket
                        </p>
                        <button 
                            class="btn btn-sm btn-success w-100 mt-2 book-btn" 
                            data-event-id="<?= $event['id'] ?>"
                            data-name="<?= htmlspecialchars($event['name']) ?>"
                            data-price="<?= $event['ticket_price'] ?>"
                        >
                            <ion-icon name="ticket-outline" class="me-1"></ion-icon>
                            Book Now
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted mt-4">🎉 No upcoming events at the moment. Check back later!</p>
        <?php endif; ?>
    </div>
</div>

<!-- Bottom Action Sheet for Booking -->
<div class="modal fade action-sheet" id="bookEventSheet" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-bottom" role="document">
        <div class="modal-content rounded-top shadow">
            <div class="modal-header bg-primary text-white rounded-top">
                <h5 class="modal-title">🎟 Book Event</h5>
                <a href="#" class="close text-white" data-bs-dismiss="modal" aria-label="Close">&times;</a>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form id="bookEventForm">
                        <input type="hidden" name="event_id" id="event_id">

                        <!-- Event Name -->
                        <div class="form-group basic mb-3">
                            <label class="label">Event Name</label>
                            <input type="text" class="form-control" id="event_name" readonly>
                        </div>

                        <!-- Ticket Price -->
                        <div class="form-group basic mb-3">
                            <label class="label">Ticket Price</label>
                            <input type="text" class="form-control" id="ticket_price" readonly>
                        </div>

                        <!-- Number of Tickets -->
                        <div class="form-group basic mb-3">
                            <label class="label">Number of Tickets</label>
                            <input type="number" name="number_of_tickets" class="form-control" min="1" value="1" required>
                        </div>

                        <!-- Confirm Button -->
                        <div class="form-group basic mt-3">
                            <button type="submit" class="btn btn-success btn-block btn-lg">
                                <i class="fa fa-check-circle"></i> Confirm Booking
                            </button>
                        </div>

                        <!-- Cancel Button -->
                        <div class="form-group basic mt-2">
                            <button type="button" class="btn btn-danger btn-block btn-lg" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
// Open bottom action sheet on "Book Now" click
$('.book-btn').on('click', function () {
    let eventId = $(this).data('event-id');
    let eventName = $(this).data('name');
    let price = parseFloat($(this).data('price')).toLocaleString();

    $('#event_id').val(eventId);
    $('#event_name').val(eventName);
    $('#ticket_price').val('₦' + price);

    let bookSheet = new bootstrap.Modal(document.getElementById('bookEventSheet'));
    bookSheet.show();
});

// Handle booking form submission
$('#bookEventForm').submit(function (e) {
    e.preventDefault();

    Swal.showLoading(); // Show loader

    $.ajax({
        type: "POST",
        url: "process_event_booking.php",
        data: $(this).serialize(),
        success: function (res) {
            Swal.close(); // Hide loader

            let response = typeof res === "object" ? res : JSON.parse(res);

            if (response.status === 'success') {
                // Close bottom sheet
                const bookSheet = bootstrap.Modal.getInstance(document.getElementById('bookEventSheet'));
                bookSheet.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Booking Confirmed 🎉',
                    text: `Ref: ${response.reference}`,
                    confirmButtonColor: '#198754'
                }).then(() => {
                    // Redirect to receipt
                    window.location.href = 'event_receipt.php?reference=' + response.reference;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Booking Failed',
                    text: response.message,
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function () {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Unable to process booking. Try again later.',
                confirmButtonColor: '#dc3545'
            });
        }
    });
});

    
</script>


<?php include 'footer.php'; ?>
</body>
</html>
