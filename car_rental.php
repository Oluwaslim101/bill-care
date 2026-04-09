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
    <div class="container mt-1 px-2 pb-3">
<?php include "db.php"; ?>
<div class="section full mt-2">
    <h2 class="text-xl font-semibold mb-2 px-1">Available Cars</h2>
    <div class="row g-3">
        <?php
        $stmt = $sql->query("SELECT * FROM cars WHERE available = 1");
        while ($car = $stmt->fetch()):
        ?>
           <div class="col-6">
    <div class="card car-card" 
     data-id="<?= $car['id'] ?>" 
     data-title="<?= htmlspecialchars($car['title']) ?>" 
     data-description="<?= htmlspecialchars($car['description']) ?>" 
     data-price="<?= $car['price_per_day'] ?>" 
     data-image="admin/assets/img/cars/<?= htmlspecialchars($car['image']) ?>" 
     data-brand="<?= htmlspecialchars($car['brand'] ?? '') ?>" 
     data-type="<?= htmlspecialchars($car['type'] ?? '') ?>" 
     data-bs-toggle="modal" 
     data-bs-target="#carDetailsModal">
        <img src="admin/assets/img/cars/<?= htmlspecialchars($car['image']) ?>" 
             alt="<?= htmlspecialchars($car['title']) ?>" 
             class="img-fluid rounded mb-2" 
             style="height: 140px; object-fit: cover; width: 100%;">
        <h5 class="text-sm fw-bold mb-1"><?= htmlspecialchars($car['title']) ?></h5>
        <p class="text-muted small mb-2">₦<?= number_format($car['price_per_day']) ?>/day</p>
        <button class="btn btn-sm btn-success w-100">Book Now</button>
    </div>
</div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Bottom Action Sheet for Booking -->
<div class="modal fade action-sheet" id="carDetailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content rounded-top">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCarTitle">Car Details</h5>
        <a href="#" data-bs-dismiss="modal" class="btn-close"></a>
      </div>
      <div class="modal-body p-3">
        <img id="modalCarImage" src="" class="img-fluid rounded mb-3" style="height: 180px; object-fit: cover; width: 100%;" alt="Car Image">

        <p id="modalCarDescription" class="text-muted small mb-2"></p>

        <div class="mb-2">
          <strong>Price per day:</strong> ₦<span id="modalCarPrice">0</span>
        </div>
        <div class="mb-3">
          <strong>Total Cost:</strong> ₦<span id="modalTotalAmount">0</span>
        </div>

        <form method="POST" action="process_booking.php">
          <input type="hidden" name="car_id" id="modalCarId">
          <input type="hidden" name="total_cost" id="modalTotalCost">
          
          <div class="mb-2">
            <input type="text" name="pickup_location" class="form-control" placeholder="Pickup Location" required>
          </div>
          <div class="mb-2">
            <input type="text" name="dropoff_location" class="form-control" placeholder="Dropoff Location" required>
          </div>
          <div class="mb-2">
            <label class="form-label small">Pickup Date & Time</label>
            <input type="datetime-local" name="pickup_date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label small">Dropoff Date & Time</label>
            <input type="datetime-local" name="dropoff_date" class="form-control" required>
          </div>

          <button type="submit" class="btn btn-success w-100" id="confirmBookingBtn">Confirm Booking</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- appCapsule -->
<script>
document.querySelectorAll('.car-card').forEach(function(card) {
    card.addEventListener('click', function() {
        const title = card.dataset.title;
        const image = card.dataset.image;
        const description = card.dataset.description;
        const price = parseFloat(card.dataset.price);
        const carId = card.dataset.id;
        const userBalance = <?= $user['balance'] ?>;

        const pickupInput = document.querySelector('[name="pickup_date"]');
        const dropoffInput = document.querySelector('[name="dropoff_date"]');
        const totalSpan = document.getElementById('modalTotalAmount');
        const totalHidden = document.getElementById('modalTotalCost');
        const button = document.getElementById('confirmBookingBtn');

        // Set modal values
        document.getElementById('modalCarTitle').textContent = title;
        document.getElementById('modalCarImage').src = image;
        document.getElementById('modalCarDescription').textContent = description;
        document.getElementById('modalCarPrice').textContent = price.toLocaleString();
        document.getElementById('modalCarId').value = carId;
        totalSpan.textContent = "0";
        totalHidden.value = "";

        // Reset fields
        pickupInput.value = '';
        dropoffInput.value = '';
        button.disabled = true;
        button.textContent = 'Confirm Booking';
        button.classList.remove('btn-secondary');
        button.classList.add('btn-success');

        // Check rental duration and update total
        function checkDates() {
            const pickup = new Date(pickupInput.value);
            const dropoff = new Date(dropoffInput.value);

            if (pickup && dropoff && dropoff > pickup) {
                const diffTime = Math.abs(dropoff - pickup);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) || 1;
                const total = price * diffDays;

                totalSpan.textContent = total.toLocaleString();
                totalHidden.value = total;

                if (userBalance < total) {
                    button.disabled = true;
                    button.textContent = `Insufficient Balance (₦${total.toLocaleString()})`;
                    button.classList.remove('btn-success');
                    button.classList.add('btn-secondary');
                } else {
                    button.disabled = false;
                    button.textContent = `Confirm Booking - ₦${total.toLocaleString()}`;
                    button.classList.remove('btn-secondary');
                    button.classList.add('btn-success');
                }
            } else {
                button.disabled = true;
                totalSpan.textContent = "0";
                totalHidden.value = "";
                button.textContent = "Confirm Booking";
            }
        }

        pickupInput.addEventListener('change', checkDates);
        dropoffInput.addEventListener('change', checkDates);
    });
});
</script>

<?php include 'footer.php'; ?>
</body>
</html>
