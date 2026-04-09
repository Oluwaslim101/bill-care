<?php
// Error reporting 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Function to insert notifications into the database
function insertNotification($user_id, $message) {
    global $sql;
    $query = "INSERT INTO notifications (user_id, action_type, message, status, created_at) 
              VALUES (:user_id, :action_type, :message, :status, NOW())";
    $stmt = $sql->prepare($query);
    $stmt->execute([
        ':user_id' => $user_id,
        ':action_type' => 'Points',
        ':message' => $message,
        ':status' => 'unread'
    ]);
}

// Handle AJAX claim request
if (isset($_GET['claim']) && $_GET['claim'] == 'true') {
    header('Content-Type: application/json');

    $points_claimed = 5;
    $today = date('Y-m-d');

    // Check if user already claimed today
    $check_query = "SELECT id FROM activity_log WHERE user_id = ? AND activity = 'Claimed Daily Points' AND DATE(created_at) = ?";
    $check_stmt = $sql->prepare($check_query);
    $check_stmt->execute([$user_id, $today]);

    if ($check_stmt->rowCount() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'You have already claimed your daily points today. Come back tomorrow!'
        ]);
        exit();
    }

    // Add points to user
    $update_query = "UPDATE users SET points = points + ? WHERE id = ?";
    $stmt = $sql->prepare($update_query);
    $stmt->execute([$points_claimed, $user_id]);

    if ($stmt->rowCount() > 0) {
        // Log the activity
        $log_query = "INSERT INTO activity_log (user_id, activity, points) VALUES (?, ?, ?)";
        $log_stmt = $sql->prepare($log_query);
        $log_stmt->execute([$user_id, 'Claimed Daily Points', $points_claimed]);

        // Add notification
        insertNotification($user_id, "You earned $points_claimed points from your daily claim.");

        echo json_encode([
            'success' => true,
            'message' => "You have successfully claimed $points_claimed points today!"
        ]);
        exit();
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred while claiming points. Please try again.'
        ]);
        exit();
    }
}

// Fetch user data (below this can remain as-is)
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}

$avatar_url = !empty($user['avatar_url']) ? htmlspecialchars($user['avatar_url']) : 'default-avatar.png';
$balance = number_format($user['balance'], 2);
$available_points = $user['points'];  // Leave it as an integer or float
$used_points = number_format($user['points_used']);

// Notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

// Tasks
$tasks_query = "SELECT * FROM tasks WHERE status = 'active' ORDER BY id ASC";
$tasks_stmt = $sql->prepare($tasks_query);
$tasks_stmt->execute();
$tasks = $tasks_stmt->fetchAll(PDO::FETCH_ASSOC);
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
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Earn Points
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


<!-- Content -->
<div id="appCapsule">
    <div class="section full">
        <div class="card p-0">
            <div class="listview-title mb-0">
                <h2 class="mb-0"><strong>Welcome <?= htmlspecialchars($user['nick_name']) ?>,</strong></h2>
                <p class="text-secondary mb-0">Complete tasks to earn points.</p>
            </div>
            

          <div class="section">
    <div class="row g-3">
        <!-- Available Points -->
        <div class="col-6 col-md-4">
            <div class="stat-box">
                <div class="title text-muted">Available Points</div>
                <div class="value text-success mt-0">⭐ <?= $available_points ?></div>
            </div>
        </div>

        <!-- Used Points -->
        <div class="col-6 col-md-4">
            <div class="stat-box">
                <div class="title text-muted">Used Points</div>
                <div class="value text-danger mt-0">⭐ <?= $used_points ?></div>
            </div>
        </div>

        <!-- Redeem Button -->
        <div class="col-12 col-md-4 d-flex align-items-end">
            <button id="redeemBtn" class="btn btn-success w-100">Redeem Points</button>
        </div>
    </div>
</div>

<!-- * Content -->

<!-- Redeem Points Action Sheet -->
<div class="modal fade action-sheet" id="redeemActionSheet" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Redeem Points for Funds</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form id="redeemForm">
                        <div class="row">
                            <!-- Available Points -->
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label class="label">Available Points</label>
                                    <input type="text" class="form-control" id="availablePoints" readonly
                                           value="<?= $available_points ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Points to Redeem -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label class="label">Enter Points to Redeem</label>
                                    <div class="input-group mb-2">
                                        <input type="number" class="form-control" id="pointsToRedeem" placeholder="Enter points to redeem">
                                        <span class="input-group-text">points</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Redeemed Amount -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group basic">
                                    <label class="label">Equivalent Amount (NGN)</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text" id="basic-addon2">₦</span>
                                        <input type="text" class="form-control" id="redeemedAmount" readonly
                                               value="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Redeem -->
                        <div class="form-group basic">
                            <button type="button" id="redeemPointsBtn" class="btn btn-primary btn-block btn-lg"
                                    data-bs-dismiss="modal">Redeem Points</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Redeem Points Action Sheet -->

<script>
  // Show the redeem points modal when button is clicked
document.getElementById('redeemBtn').addEventListener('click', () => {
    let availablePoints = <?= (int)$available_points ?>;
    console.log('Available points from PHP:', availablePoints); // Check the value in console

    // Open modal if points are available
    if (availablePoints >= 5) {
        console.log("Points are above 5, triggering modal.");
        $('#redeemActionSheet').modal('show');
    } else {
        console.log("Points are below 5, modal won't trigger.");
    }
});

// Calculate the equivalent USD when points to redeem is entered
document.getElementById('pointsToRedeem').addEventListener('input', function () {
    let pointsToRedeem = parseInt(this.value);
    let availablePoints = <?= (int)$available_points ?>;
    let conversionRate = 0.01; // Example conversion rate: 1 point = 0.01 USD

    console.log("Points to redeem:", pointsToRedeem, "Available points:", availablePoints);

    if (pointsToRedeem > availablePoints) {
        document.getElementById('redeemedAmount').value = 'Insufficient points';
    } else {
        let amount = pointsToRedeem * conversionRate;
        document.getElementById('redeemedAmount').value = amount.toFixed(2);
    }
});

// Handle the Redeem Points button
document.getElementById('redeemPointsBtn').addEventListener('click', () => {
    let pointsToRedeem = parseInt(document.getElementById('pointsToRedeem').value);
    let redeemedAmount = parseFloat(document.getElementById('redeemedAmount').value);

    console.log("Points to redeem:", pointsToRedeem, "Redeemed amount:", redeemedAmount);

    if (pointsToRedeem <= 0 || isNaN(redeemedAmount)) {
        Swal.fire('Error', 'Please enter valid points to redeem.', 'error');
        return;
    }

    // AJAX call to redeem points and convert to funds
    fetch('redeem_funds_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `points=${pointsToRedeem}&amount=${redeemedAmount}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Show success message and reload the page
            Swal.fire('Success', data.message, 'success').then(() => {
                window.location.reload(); // Reload the page after success
            });
            $('#redeemActionSheet').modal('hide');
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(err => {
        Swal.fire('Error', 'There was an issue redeeming your points. Please try again.', 'error');
    });
});


</script>

    <br>
      <div class="section mt-1 full ">
<ul class="listview image-listview media">
    <?php foreach ($tasks as $task): ?>
        <?php
        $link = ''; // default no link
        $is_clickable = true; // New

        if ($task['title'] == 'Spin the Wheel') {
            $link = 'spin_the_wheel.php?task_id=' . $task['id'];
        } elseif ($task['title'] == 'Daily Login Rewards') {
            $link = 'javascript:void(0);'; // No page link
            }
        ?>
        <li>
            <?php if ($is_clickable): ?>
                <a href="<?= htmlspecialchars($link) ?>" class="item" <?php if ($task['title'] == 'Daily Login Rewards') echo 'onclick="claimDailyPoints()"'; ?>>
            <?php else: ?>
                <div class="item" style="pointer-events: none; opacity: 0.6;">
            <?php endif; ?>

                <div class="imageWrapper">
                    <img src="<?= htmlspecialchars($task['image_url']) ?>" alt="image" class="imaged w64">
                </div>
                <div class="in">
                    <div>
                        <?= htmlspecialchars($task['title']) ?>
                        <div class="text-muted"><?= htmlspecialchars($task['description']) ?></div>
                    </div>
                    <span class="badge badge-primary"><?= htmlspecialchars($task['points']) ?> ⭐</span>
                </div>

            <?php if ($is_clickable): ?>
                </a>
            <?php else: ?>
                </div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
</div>
</div>

<script>
function claimDailyPoints() {
    Swal.fire({
        title: 'Claim Daily Points?',
        text: "You can only claim once every day!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, claim it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // AJAX to claim
            $.ajax({
                url: '?claim=true',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Oops', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Unable to process request. Try again.', 'error');
                }
            });
        }
    });
}
</script>


<?php include 'footer.php'; ?>
</body>
</html>
