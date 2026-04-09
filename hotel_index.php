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
     <link rel="stylesheet" href="style.css">
    <link rel="manifest" href="__manifest.json">
           <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
    .hotel-card {
    display: flex;
    align-items: center;
    padding: 12px;
    margin-bottom: 10px;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    text-decoration: none;
    color: inherit;
    transition: background 0.2s ease;
}

.hotel-card:hover {
}

.hotel-card img {
    width: 70px;
    height: 70px;
    object-fit: cover;
    border-radius: 10px;
    margin-right: 15px;
}

.hotel-card h3 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.hotel-card p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #666;
}

.advert-slider {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    margin-top: 20px;
}

.advert-slider .slides {
    display: flex;
    width: 100%;
    height: 170px;
    transition: transform 0.4s ease-in-out;
}

.advert-slider img {
    width: 100%;
    flex-shrink: 0;
    border-radius: 12px;
}

.advert-slider .dots {
    text-align: center;
    margin-top: 10px;
}

.advert-slider .dots span {
    height: 10px;
    width: 10px;
    margin: 0 4px;
    background-color: #bbb;
    border-radius: 50%;
    display: inline-block;
    transition: background-color 0.3s ease;
}

.advert-slider .dots .active {
    background-color: #28a745;
}

.advert-slider .prev,
.advert-slider .next {
    position: absolute;
    top: 45%;
    background: rgba(0, 0, 0, 0.25);
    color: white;
    padding: 6px 10px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    z-index: 2;
    font-size: 14px;
}

.advert-slider .prev {
    left: 10px;
}

.advert-slider .next {
    right: 10px;
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
        Select Hotel
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
<div id="appCapsule" class="pb-5">

    <!-- Hotel Listings -->
    <div class="section full mb-2">
        <div class="wide-block pt-2">
            <?php
            $stmt = $sql->query("SELECT * FROM hotels");
            while ($hotel = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
                <a class="hotel-card" href="hotel.php?id=<?= $hotel['id'] ?>">
                    <img src="<?= htmlspecialchars($hotel['logo']) ?>" alt="Hotel Logo">
                    <div>
                        <h3><?= htmlspecialchars($hotel['name']) ?></h3>
                        <p><?= htmlspecialchars($hotel['address']) ?></p>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Advert Slider -->
    <div class="advert-slider mt-3 px-0">
        <div class="slides">
            <img src="ads_sliders/hotel1.png" alt="Ad 1">
            <img src="ads_sliders/hotel2.png" alt="Ad 2">
            <img src="ads_sliders/hotel3.png" alt="Ad 3">
            <img src="ads_sliders/hotel4.png" alt="Ad 4">
            <img src="ads_sliders/hotel5.png" alt="Ad 5">
            <img src="ads_sliders/hotel6.png" alt="Ad 6">
        </div>

        <!-- Navigation Buttons -->
        <button class="prev"><i class="fas fa-chevron-left"></i></button>
        <button class="next"><i class="fas fa-chevron-right"></i></button>

        <!-- Dots for Pagination -->
        <div class="dots"></div>
    </div>

</div>
<!-- * App Capsule -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let slideIndex = 0;
    const slides = document.querySelector(".slides");
    const images = document.querySelectorAll(".slides img");
    const dotsContainer = document.querySelector(".dots");

    images.forEach((_, index) => {
        let dot = document.createElement("span");
        dot.addEventListener("click", () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = document.querySelectorAll(".dots span");
    if (dots.length > 0) dots[slideIndex].classList.add("active");

    function goToSlide(index) {
        slideIndex = index;
        slides.style.transform = `translateX(-${index * 100}%)`;
        updateDots();
    }

    function updateDots() {
        dots.forEach(dot => dot.classList.remove("active"));
        dots[slideIndex].classList.add("active");
    }

    function nextSlide() {
        slideIndex = (slideIndex + 1) % images.length;
        goToSlide(slideIndex);
    }

    function prevSlide() {
        slideIndex = (slideIndex - 1 + images.length) % images.length;
        goToSlide(slideIndex);
    }

    document.querySelector(".next").addEventListener("click", nextSlide);
    document.querySelector(".prev").addEventListener("click", prevSlide);

    setInterval(nextSlide, 7000);
});
</script>
<?php include 'footer.php'; ?>

</body>

</html>
