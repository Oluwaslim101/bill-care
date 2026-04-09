<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

session_start(); // Prevent multiple alerts per user session


// Ensure user is logged in
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
// Assign user data
$full_name = $user['full_name'];
$balance = $user['balance'];
$stored_pin = $user['pin'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';

// Notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();


$stmt = $sql->prepare("SELECT balance, earnings FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user_wallet = $stmt->fetch(PDO::FETCH_ASSOC);

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
    <meta name="description" content="DtheHub FinTech Wallet">
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
<link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
<script src="assets/js/lib/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script type="module" src="firebase.js"></script>
<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.11.0/firebase-messaging.js"></script>



    <style>
    
 
        .amount {
    font-size: 10px; /* Adjust this value as needed */
    color: green; /* Optional: Set text color */
    font-weight: bold; /* Optional: Adjust text weight */
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
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
            <ion-icon name="menu-outline"></ion-icon>
        </a>
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


<div id="appCapsule">
    <div class="section mt-3">
        <button class="btn btn-primary btn-block" data-bs-toggle="modal" data-bs-target="#createSavingsModal">
            + Start New Fixed Savings
        </button>
    </div>

   <!-- Show past savings -->
<!-- Show past savings -->
<div class="section full mt-2">
    <div class="section-title">My Savings</div>
    <div class="transactions">
        <?php
        $userId = $_SESSION['user_id'];
        $stmt = $sql->prepare("SELECT * FROM fixed_savings WHERE user_id = ? ORDER BY id DESC");
        $stmt->execute([$userId]);
        foreach ($stmt as $row) {
            echo "
            <div class='item' style='
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                padding: 10px 0;
                border-bottom: 1px solid #f1f1f1;
            '>
                <div class='detail' style='
                    display: flex;
                    flex-direction: column;
                    margin-left: 10px; /* 👈 Left margin for text */
                    margin-right: 10px; /* 👈 Right margin for text */
                '>
                    <strong style='
                        font-size: 16px;
                        color: #000;
                    '>₦" . number_format($row['amount'], 2) . "</strong>
                    <p style='
                        margin: 2px 0 0;
                        font-size: 13px;
                        color: #6c757d;
                        line-height: 1.3;
                    '>" .
                        $row['duration_days'] . " days @ " . $row['interest_rate'] . "%<br>" .
                        "Ends: " . date("M d, Y", strtotime($row['end_date'])) . "
                    </p>
                </div>
                <div class='right'>
                    <span class='badge bg-success' style='
                        font-size: 12px;
                        padding: 5px 8px;
                        border-radius: 5px;
                    '>" . htmlspecialchars($row['status']) . "</span>
                </div>
            </div>";
        }
        ?>
    </div>
</div>


<!-- Create Savings Modal -->
<div class="modal fade action-sheet" id="createSavingsModal" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="action-sheet-content">
        <form id="savingsForm">
            <h4 class="text-center mt-2">Create Fixed Savings</h4>
            <input type="hidden" name="user_id" value="<?= $userId ?>">
            <div class="form-group basic">
                <label>Amount (₦)</label>
                <input type="number" name="amount" id="amount" class="form-control" required>
            </div>
            <div class="form-group basic">
                <label>Duration</label>
                <select name="duration_days" id="duration" class="form-control" onchange="previewInterest()" required>
                    <option value="30" data-rate="3">30 Days @ 3%</option>
                    <option value="60" data-rate="6">60 Days @ 6%</option>
                    <option value="90" data-rate="10">90 Days @ 10%</option>
                </select>
            </div>
            <div class="form-group basic">
                <label>Funding Method</label>
                <select name="funding_method" id="funding_method" class="form-control" required>
                    <option value="user_wallet">Use Wallet</option>
                   
                </select>
            </div>
            <div class="form-group basic">
                <label>Estimated Total Payout</label>
                <p id="payoutPreview" class="text-success fw-bold">₦0.00</p>
            </div>
            <div class="form-group basic">
                <button type="submit" class="btn btn-primary btn-block">Proceed</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
function previewInterest() {
    let amt = parseFloat(document.getElementById("amount").value || 0);
    let rate = parseFloat(document.getElementById("duration").selectedOptions[0].getAttribute("data-rate"));
    let total = amt + (amt * rate / 100);
    document.getElementById("payoutPreview").textContent = "₦" + total.toFixed(2);
}
document.getElementById("amount").addEventListener("input", previewInterest);

document.getElementById("savingsForm").addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch('start_fixed_savings.php', {
        method: 'POST',
        body: formData
    }).then(res => res.json()).then(data => {
        alert(data.message);
        if (data.status === 'success') {
            window.location.reload();
        } else if (data.redirect) {
            window.location.href = data.redirect;
        }
    });
});
</script>
<?php include 'footer.php'; ?>

</body>

</html>
