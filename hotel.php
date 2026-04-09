<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit();
}

$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';
$balance = number_format($user['balance'], 2);

$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

$id = $_GET['id'] ?? 0;
$stmt = $sql->prepare("SELECT * FROM hotels WHERE id = ?");
$stmt->execute([$id]);
$hotel = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$hotel) {
    die("Hotel not found");
}
$hotel_name = htmlspecialchars($hotel['name']);
$hotel_logo = !empty($hotel['logo']) ? htmlspecialchars($hotel['logo']) : 'default-hotel.png';

// Fetch hotel images
$images_stmt = $sql->prepare("SELECT * FROM hotel_images WHERE hotel_id = ?");
$images_stmt->execute([$id]);
$hotel_images = $images_stmt->fetchAll(PDO::FETCH_ASSOC);
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
/* Chat bubbles */
#chatMessages .chat-bubble {
    padding: 10px 15px;
    border-radius: 20px;
    max-width: 75%;
    margin-bottom: 8px;
    word-wrap: break-word;
    position: relative;
    animation: fadeIn 0.3s ease;
}

#chatMessages .chat-bubble.customer {
    background-color: #dcf8c6; /* Light green */
    align-self: flex-end;
    border-top-right-radius: 0;
}

#chatMessages .chat-bubble.hotel {
    background-color: #fff;
    border: 1px solid #ddd;
    align-self: flex-start;
    border-top-left-radius: 0;
}

#chatMessages {
    display: flex;
    flex-direction: column;
}

/* Typing indicator */
#chatMessages .typing-indicator {
    font-style: italic;
    color: #888;
    margin-top: 5px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
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
.modal-header {
    border-bottom: none;
}
.modal-body {
    font-size: 14px;
    color: #555;
}
#stayDuration p {
    background: #f0f8ff;
    border-left: 4px solid #0d6efd;
    padding: 6px 10px;
    border-radius: 4px;
}
#bookingCost {
    border-left: 4px solid #198754;
    padding: 6px 10px;
    border-radius: 4px;
}
#balanceStatus {
    font-size: 14px;
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
<div id="appCapsule" class="pb-8">

<!-- Hotel Header Section -->
<div class="section mt-1 px-2">
    <div class="d-flex align-items-center justify-content-between">
        <!-- Logo Left -->
        <div class="me-3 flex-shrink-0">
            <img src="<?= htmlspecialchars($hotel['logo']) ?>" alt="Hotel Logo"
                 class="imaged w92 rounded border" style="width: 92px; height: 92px; object-fit: cover;">
        </div>

        <!-- Hotel Info Right -->
        <div class="flex-grow-1 ms-3">
            <h3 class="mb-1 fw-bold"><?= htmlspecialchars($hotel['name']) ?></h3>
            <p class="text-muted small fw-semibold lh-sm mb-1">
                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                <?= htmlspecialchars($hotel['address']) ?>
            </p>
            <p class="text-muted small fw-semibold lh-sm mb-1">
                <i class="fas fa-phone-alt text-success me-1"></i>
                <?= htmlspecialchars($hotel['phone']) ?>
            </p>
            <p class="text-muted small fw-semibold lh-sm mb-1">
                <i class="fas fa-envelope text-primary me-1"></i>
                <?= htmlspecialchars($hotel['email']) ?>
            </p>

           <!-- Chat Trigger Button -->
<button class="btn btn-primary w-100 rounded-pill mt-1" data-bs-toggle="modal" data-bs-target="#chatModal">
    <i class="fas fa-comments"></i> Chat with Hotel
</button>
        </div>
    </div>
</div>

   <!-- Advert Slider -->
    <div class="advert-slider mt-1 px-0">
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

    <!-- Facilities -->
    <div class="facilities-section mb-4">
        <h5 class="fw-bold mb-3">Facilities</h5>
        <div class="row g-2">
            <?php
            $facilities = $sql->prepare("SELECT facility FROM hotel_facilities WHERE hotel_id = ?");
            $facilities->execute([$id]);
            while ($f = $facilities->fetch(PDO::FETCH_ASSOC)):
            ?>
                <div class="col-6">
                    <div class="d-flex align-items-center p-2 rounded border  shadow-sm">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <span class="small"><?= htmlspecialchars($f['facility']) ?></span>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<form id="bookingForm" method="POST">
    <input type="hidden" name="hotel_id" value="<?= $id ?>">

    <!-- Room Type Tile -->
    <div class="card bg-dark-light rounded-2 mb-1 py-1 px-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold text-muted small mb-0">Room Type</label>
            <select name="room_type" id="room_type" class="form-select border-0 bg-transparent text-end fw-semibold small text-muted" required>
                <?php
                $rooms = $sql->prepare("SELECT room_type, price FROM hotel_rooms WHERE hotel_id = ?");
                $rooms->execute([$id]);
                while ($r = $rooms->fetch(PDO::FETCH_ASSOC)):
                ?>
                    <option value="<?= htmlspecialchars($r['room_type']) ?>" data-price="<?= $r['price'] ?>">
                        <?= htmlspecialchars($r['room_type']) ?> - ₦<?= number_format($r['price']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Guests Tile -->
    <div class="card bg-dark-light rounded-2 mb-2 py-1 px-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold text-muted small mb-0">Guests</label>
            <select name="guests" id="guests" class="form-select border-0 bg-transparent text-end fw-semibold small text-muted" required>
                <option value="">Select</option>
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>

    <!-- Purpose Tile -->
    <div class="card bg-dark-light rounded-2 mb-2 py-1 px-2">
        <div class="d-flex justify-content-between align-items-center">
            <label class="form-label fw-semibold text-muted small mb-0">Purpose</label>
            <select name="purpose" id="purpose" class="form-select border-0 bg-transparent text-end fw-semibold small text-muted" required>
                <option value="">Select</option>
                <option value="Business">Business</option>
                <option value="Leisure">Leisure</option>
                <option value="Honeymoon">Honeymoon</option>
                <option value="Family Visit">Family Visit</option>
                <option value="Other">Confidential</option>
            </select>
        </div>
    </div>

    <!-- Check-in & Check-out (Flex Row Compact) -->
    <div class="d-flex gap-2 mb-2">
        <div class="card bg-dark-light rounded-2 flex-fill py-1 px-2">
            <label class="form-label fw-semibold text-muted small mb-1">Check-in</label>
            <input type="date" name="checkin" id="checkin" class="form-control border-0 bg-transparent text-end small fw-semibold text-muted" required>
        </div>
        <div class="card bg-dark-light rounded-2 flex-fill py-1 px-2">
            <label class="form-label fw-semibold text-muted small mb-1">Check-out</label>
            <input type="date" name="checkout" id="checkout" class="form-control border-0 bg-transparent text-end small fw-semibold text-muted" required>
        </div>
    </div>

    <!-- Stay Duration -->
    <div class="alert alert-info text-center small py-1 mb-1 rounded-2 shadow-sm" id="stayDuration" style="display:none;">
        <i class="fas fa-calendar-alt me-1"></i>
        <strong>Stay Duration:</strong> <span id="numberOfDays">0</span> night(s)
    </div>

    <!-- Preview Booking Button -->
    <div class="sticky-bottom bg-dark-light py-2">
        <button type="button" id="previewBooking" class="btn btn-primary w-100 rounded-pill shadow-sm">
            <i class="fas fa-eye me-1"></i> Preview Booking
        </button>
    </div>
</form>
<br>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" data-hotel-id="<?= $id ?>" data-user-id="<?= htmlspecialchars($user_id) ?>">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 shadow border-0" style="background: #fff; color: #333;">
            <!-- Header -->
            <div class="modal-header" style="background: linear-gradient(135deg, #007bff, #00bcd4); color: #fff; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                <div class="d-flex align-items-center">
                    <img src="<?= $hotel_logo ?>" class="rounded-circle me-2 border border-light" width="40" height="40" alt="Hotel Logo" style="object-fit: cover;">
                    <div>
                        <h6 class="mb-0 fw-bold"><?= $hotel_name ?></h6>
                        <small class="text-light">Online now</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white shadow-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body (Chat Messages) -->
            <div class="modal-body p-3" id="chatMessages" style="background: #f8f9fa; max-height:400px; overflow-y:auto; border-bottom: 1px solid #ddd;">
                <div class="text-center text-muted small">Loading chat...</div>
            </div>

            <!-- Footer (Chat Input) -->
            <div class="modal-footer border-0 bg-light p-2">
                <div class="input-group shadow-sm">
                    <input type="text" id="chatInput" class="form-control rounded-start-pill border-0" placeholder="Type a message..." autocomplete="off" style="background: #fff;">
                    <button id="sendChatBtn" class="btn btn-primary rounded-end-pill">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


  <!-- Bottom Action Sheet -->
<div class="modal fade action-sheet" id="confirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-top shadow-lg">

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0">
                    <i class="fas fa-bed me-1"></i> Confirm Your Booking
                </h5>
            </div>

            <!-- Modal Body -->
            <div class="modal-body px-4 py-3">
                <!-- Booking Summary -->
                <div id="bookingSummary" class="mb-3"></div>

                <!-- Stay Duration -->
                <div id="stayDurationModal" class="mb-3">
                    <p class="mb-0 small">
                        <i class="fas fa-calendar-alt text-success me-1"></i>
                        <strong>Stay Duration:</strong>
                        <span id="numberOfDaysModal">0</span> night(s)
                    </p>
                </div>

                <!-- Booking Cost -->
                <div id="bookingCost" class="fw-bold text-primary fs-5 mb-3"></div>

            <!-- Modal Footer -->
            <div class="modal-footer px-4 pb-3 pt-2">
                <button type="button" id="confirmBooking" class="btn btn-success w-100 rounded-pill shadow-sm" disabled>
                    <i class="fas fa-check-circle me-1"></i> Book Now
                </button>
            </div>

        </div>
    </div>
</div>

</div>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
 document.addEventListener("DOMContentLoaded", function () {
    const sendBtn = document.getElementById("sendChatBtn");
    const chatInput = document.getElementById("chatInput");
    const chatMessages = document.getElementById("chatMessages");
    const chatModal = document.getElementById("chatModal");
    const hotelId = chatModal.getAttribute("data-hotel-id");
    const userId = chatModal.getAttribute("data-user-id");

    let lastMessageId = 0; // Track last message ID

    // Smoothly append new messages
    function fetchNewMessages() {
        fetch(`fetch_hotel_chat.php?hotel_id=${hotelId}&user_id=${userId}&last_id=${lastMessageId}`)
            .then(res => res.json())
            .then(data => {
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const bubble = document.createElement("div");
                        bubble.className = `chat-bubble ${msg.sender === 'hotel' ? 'hotel' : 'customer'}`;
                        bubble.innerHTML = `
                            <div>${msg.message}</div>
                            <small class="text-muted">${msg.time}</small>
                        `;
                        chatMessages.appendChild(bubble);
                        chatMessages.scrollTop = chatMessages.scrollHeight; // Auto scroll down
                        lastMessageId = msg.id; // Update last message ID
                    });
                }
            })
            .catch(err => console.error("Fetch error:", err));
    }

    // Send message
    sendBtn.addEventListener("click", () => {
        const msg = chatInput.value.trim();
        if (!msg) return;

        sendBtn.disabled = true; // prevent double send
        fetch("send_hotel_chat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `hotel_id=${hotelId}&user_id=${userId}&message=${encodeURIComponent(msg)}`
        })
        .then(res => res.json())
        .then(data => {
            sendBtn.disabled = false;
            if (data.status === "success") {
                chatInput.value = "";
                fetchNewMessages(); // Fetch immediately after send
            } else {
                alert("Failed to send: " + data.message);
            }
        })
        .catch(err => {
            sendBtn.disabled = false;
            console.error("Send error:", err);
        });
    });

    // Auto fetch new messages every 2s
    const refreshInterval = setInterval(fetchNewMessages, 2000);

    // Fetch all messages on load
    function loadAllMessages() {
        fetch(`fetch_hotel_chat.php?hotel_id=${hotelId}&user_id=${userId}`)
            .then(res => res.json())
            .then(data => {
                chatMessages.innerHTML = ""; // Clear old messages
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        const bubble = document.createElement("div");
                        bubble.className = `chat-bubble ${msg.sender === 'hotel' ? 'hotel' : 'customer'}`;
                        bubble.innerHTML = `
                            <div>${msg.message}</div>
                            <small class="text-muted">${msg.time}</small>
                        `;
                        chatMessages.appendChild(bubble);
                        lastMessageId = msg.id; // Track last message ID
                    });
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }
            });
    }

    // Load all messages when modal opens
    loadAllMessages();
});


    
document.addEventListener('DOMContentLoaded', function () {
    const checkinInput = document.getElementById('checkin');
    const checkoutInput = document.getElementById('checkout');
    const stayDuration = document.getElementById('stayDuration');
    const numberOfDays = document.getElementById('numberOfDays');

    function calculateDays() {
        const checkin = new Date(checkinInput.value);
        const checkout = new Date(checkoutInput.value);

        if (checkin && checkout && checkout > checkin) {
            const diffTime = Math.abs(checkout - checkin);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            numberOfDays.textContent = diffDays;
            stayDuration.style.display = 'block';
        } else {
            stayDuration.style.display = 'none';
        }
    }

    checkinInput.addEventListener('change', calculateDays);
    checkoutInput.addEventListener('change', calculateDays);
});
</script>

<script>
$("#previewBooking").click(function () {
    const roomType = $("#room_type option:selected").text();
    const price = $("#room_type option:selected").data("price");
    const guests = $("#guests").val();
    const purpose = $("#purpose").val();
    const checkin = $("input[name='checkin']").val();
    const checkout = $("input[name='checkout']").val();

    if (!roomType || !checkin || !checkout || !guests || !purpose) {
        Swal.fire("Error", "Please complete all fields!", "error");
        return;
    }

    // Calculate number of nights
    const days = Math.ceil((new Date(checkout) - new Date(checkin)) / (1000 * 60 * 60 * 24));
    const totalCost = days * price;

    // Update Modal Content
    $("#bookingSummary").html(`
        <p><strong>Room Type:</strong> ${roomType}</p>
        <p><strong>Guests:</strong> ${guests}</p>
        <p><strong>Check-in:</strong> ${checkin}</p>
        <p><strong>Check-out:</strong> ${checkout}</p>
        <p><strong>Purpose:</strong> ${purpose}</p>
    `);

    // ✅ Update both duration displays
    $("#numberOfDays").text(days);          // For the inline display
    $("#numberOfDaysModal").text(days);     // For the modal display

    $("#bookingCost").html(`<strong>Total Cost:</strong> ₦${totalCost.toLocaleString()}`);

    $.ajax({
        url: "check_balance.php",
        method: "POST",
        dataType: "json",
        success: function (data) {
            const balance = data.balance;
            const balanceStatus = balance >= totalCost
                
            $("#balanceStatus").html(balanceStatus);
            $("#confirmBooking").prop("disabled", balance < totalCost);

            // Show Modal
            $("#confirmationModal").modal("show");
        }
    });
});

$("#confirmBooking").click(function () {
    const $btn = $(this);
    $btn.prop("disabled", true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

    $.ajax({
        url: "process_hotel_booking.php", // Your PHP booking handler
        method: "POST",
        data: $("#bookingForm").serialize(), // Send all form data
        dataType: "json",
        success: function (response) {
            // ✅ Close the modal first
            $("#confirmationModal").modal("hide");

            // Add a slight delay to allow modal animation to complete
            setTimeout(() => {
                if (response.status === "success") {
                    Swal.fire({
                        title: "Booking Confirmed!",
                        text: "Your booking has been successfully processed.",
                        icon: "success",
                        confirmButtonText: "View Receipt"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "hotel_receipt.php?reference=" + response.reference;
                        }
                    });
                } else {
                    Swal.fire("Error", response.message || "Something went wrong. Please try again.", "error");
                    $btn.prop("disabled", false).html('<i class="fas fa-check-circle"></i> Book Now');
                }
            }, 300); // ⏳ Small delay for modal close animation
        },
        error: function () {
            // ✅ Close the modal before showing error
            $("#confirmationModal").modal("hide");

            setTimeout(() => {
                Swal.fire("Error", "Could not process your booking. Please check your network and try again.", "error");
                $btn.prop("disabled", false).html('<i class="fas fa-check-circle"></i> Book Now');
            }, 300);
        }
    });
});

</script>

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