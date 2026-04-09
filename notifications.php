<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Notifications

$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";

$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

$notifications = [];

if ($user_id) {
    $stmt = $sql->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> 

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
       <?php
    // Show flash message if it exists
    if (isset($_SESSION['flash_message']) && $_SESSION['flash_message'] != '') {
        echo '<div class="alert alert-success">' . $_SESSION['flash_message'] . '</div>';
        unset($_SESSION['flash_message']); // Clear message after showing
    }
    ?>



<!-- Loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>

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
 <div class="pageTitle">Notifications Details</div>
   <div class="right">
        <div style="position: relative;">
           <a href="notifications.php"> <i class="fas fa-bell" style="font-size: 25px; color: blue; margin-right: 19px; margin-top: 5px"></i></a>
            <?php if ($unread_count > 0): ?>
                <span style="
                    position: absolute;
                    top: -5px;
                    right: 8px;
                    background: red;
                    color: white;
                    font-size: 10px;
                    font-weight: bold;
                    padding: 2px 6px;
                    border-radius: 50%;
                ">
                    <?= $unread_count ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>


    <!-- App Capsule -->
  
<div id="appCapsule">
    <div class="section full">
        <ul class="listview image-listview flush">
            <?php foreach ($notifications as $index => $notify): ?>
                <li class="<?= $notify['status'] == 'unread' ? 'active' : '' ?>">
                    <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#notifyModal<?= $index; ?>">
                        <div class="icon-box bg-primary">
                            <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                        <div class="in">
                            <div>
                                <div class="mb-05"><strong><?= htmlspecialchars($notify['action_type']); ?></strong></div>
                                <div class="text-small mb-05"><?= htmlspecialchars($notify['message']); ?></div>
                                <div class="text-xsmall"><?= date("d M Y h:i A", strtotime($notify['created_at'])); ?></div>
                            </div>
                            <?php if ($notify['status'] == 'unread'): ?>
                                <span class="badge badge-primary badge-empty"></span>
                            <?php endif; ?>
                        </div>
                    </a>
                </li>

                <!-- Modal for this notification -->
                <div class="modal fade" id="notifyModal<?= $index; ?>" tabindex="-1" aria-hidden="true" data-id="<?= $notify['id']; ?>">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content rounded-top">
                            <div class="modal-header">
                                <h5 class="modal-title"><?= htmlspecialchars($notify['action_type']); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><?= nl2br(htmlspecialchars($notify['message'])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<!-- JS & Bootstrap -->
<script src="assets/js/lib/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script>
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('shown.bs.modal', function () {
        const id = this.getAttribute('data-id');
        if (!id) return;

        fetch('mark-notification-read.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'id=' + encodeURIComponent(id)
        }).then(res => res.text()).then(data => {
            if (data.trim() === 'success') {
                const listItem = document.querySelector(`a[data-bs-target="#${this.id}"]`).closest('li');
                listItem.classList.remove('active');
                const badge = listItem.querySelector('.badge');
                if (badge) badge.remove();
            }
        });
    });
});
</script>
<!-- Bottom Navigation -->

<nav class="nav">   
<a href="index.php">    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="cards.html">    
    <i class="fas fa-receipt"></i>    
    <span>Cards</span>    
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