<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

session_start(); // Prevent multiple alerts per user session


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

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
// Assuming you're fetching user's correct PIN from DB during login
$_SESSION['user_pin'] = $user['pin']; // plain-text if that's how you're storing it

// Assign user data
$full_name = $user['full_name'];
$balance = $user['balance'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';
$referral_code = $user['referral_code']; 
$referral_link = "https://billcare.shop/signup.php?ref=" . $referral_code;

// Notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();


$stmt = $sql->prepare("SELECT balance, earnings FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user_wallet = $stmt->fetch(PDO::FETCH_ASSOC);

// Format transaction data
function formatDate($date)
{
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $transactionDate = date('Y-m-d', strtotime($date));

    if ($transactionDate === $today) return "Today";
    if ($transactionDate === $yesterday) return "Yesterday";
    return date('d M Y', strtotime($date));
}

function formatTime($datetime)
{
    return date('h:i A', strtotime($datetime));
}


// Fetch active promotions
$stmt = $sql->prepare("SELECT * FROM promotions WHERE status = 'active' ORDER BY created_at DESC");
$stmt->execute();
$slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>

<script type="module" src="firebase.js"></script>
<!-- Firebase SDK -->
<script src="https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.11.0/firebase-messaging.js"></script>



    <style>
    .service-icon {
  width: 36px;
  height: 36px;
  filter: brightness(0) saturate(100%) invert(26%) sepia(96%) 
          saturate(7480%) hue-rotate(202deg) brightness(95%) contrast(103%);
}

    .default-font { font-family: Arial, sans-serif; }
.elegant-font { font-family: 'Georgia', serif; }
.modern-font { font-family: 'Poppins', sans-serif; }
.fun-font { font-family: 'Comic Sans MS', cursive; }


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


@keyframes zoomIn {
  from { transform: scale(0.8); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}

@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.1); opacity: 0.8; }
}

    </style>
</head>

<body>

  
 
<!-- Bootstrap Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="toastBox" class="toast align-items-center text-white bg-success border-0" role="alert">
    <div class="d-flex">
      <div class="toast-body" id="toastMessage"></div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
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



    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <!-- Wallet Card -->
    <div class="section wallet-card-section mb-0 pt-0">
        <div class="wallet-card" style="padding: 12px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">

            <strong style="font-size: 14px; display: block; margin-bottom: 1px;">
                Welcome, <b><?= htmlspecialchars($user['nick_name']) ?></b>
            </strong>

            <!-- Balance -->
            <div class="balance d-flex justify-content-between align-items-center">
                <div class="left">
                    <span class="title" style="font-size: 16px; text-align: center;">Total Balance</span>
                    <h2 id="balance-amount" style="font-size: 20px; margin: 0;">₦<?= number_format($balance, 2); ?></h2>
                </div>
                <div class="right">
                    <a href="#" class="button btn-sm btn-primary" style="padding: 4px 8px; font-size: 12px; border-radius: 8px;">
                        <ion-icon name="add-outline" style="font-size: 16px;"></ion-icon>
                    </a>
                </div>
            </div>
            <!-- * Balance -->

<!-- Wallet Footer -->
<div class="wallet-footer d-flex justify-content-around text-center py-2 border-top  shadow-sm">

    <!-- Withdrawal -->
    <div class="item">
        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#withdrawModal">
            <div class="icon-wrapper bg-blue rounded-circle mb-1">
                <ion-icon name="arrow-up-outline" size="large"></ion-icon>
            </div>
            <strong class="d-block small">Transfer</strong>
        </a>
    </div>

 <!-- Deposit -->
 <div class="item ">
    <a href="#" data-bs-toggle="offcanvas" data-bs-target="#actionSheetDeposit">
        <div class="icon-wrapper bg-blue rounded-circle mb-1">
            <ion-icon name="arrow-down-outline" size="large"></ion-icon>
        </div>
        <strong class="d-block small">Deposit</strong>
    </a>
</div>

    <!-- P2P -->
     <div class="item">
       <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#actionSheetP2p">
            <div class="icon-wrapper bg-blue rounded-circle mb-1">
                <ion-icon name="person-add-outline" size="large"></ion-icon>
            </div>
            <strong class="d-block small">P2P</strong>
        </a>
    </div>

    <!-- Exchange -->
     <div class="item">
        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#actionSheetCurrency">
            <div class="icon-wrapper bg-blue rounded-circle mb-1">
                <ion-icon name="swap-horizontal-outline" size="large"></ion-icon>
            </div>
            <strong class="d-block small">Exchange</strong>
        </a>
    </div>

</div>
<!-- * Wallet Footer -->
            </div>
        </div>
        <!-- Wallet Card -->
<?php include 'get_active_alerts.php'; ?>


<!-- Services Card -->
<div class="section full mt-1">
  <div class="card" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden;">

    <!-- Card Header -->
    <div class="section-heading padding" style="padding: 12px 16px 8px; margin: 0;">
      <div class="d-flex justify-content-between align-items-center">
        <div class="section-title" style="font-size: 14px;">Services</div>
      </div>
    </div>

    <!-- Grid of Services -->
    <div class="container px-2 pb-2">
      <div class="row gy-2 services">

       <!-- Airtime -->
        <div class="col-3 text-center">
          <a href="#" data-bs-toggle="modal" data-bs-target="#airtimeModal">
            <img src="icons/smartphone.png" alt="Airtime" class="service-icon">
            <div style="font-size: 12px; margin-top: 4px;">Airtime</div>
          </a>
        </div>
        
        <!-- Data -->
        <div class="col-3 text-center">
          <a href="#" onclick="openBuyDataSheet()" style="width: 100%;">
            <img src="icons/wifi.png" alt="Data" class="service-icon">
            <div style="font-size: 12px; margin-top: 4px;">Data</div>
          </a>
        </div>

        <!-- Cable TV -->
        <div class="col-3 text-center">
          <a href="#" data-bs-toggle="modal" data-bs-target="#modalCableTV">
            <img src="icons/tv.png" alt="Cable TV" class="service-icon">
            <div style="font-size: 12px; margin-top: 4px;">Cable TV</div>
          </a>
        </div>

        <!-- Bulk SMS -->
        <div class="col-3 text-center">
           <a href="#" data-bs-toggle="modal" data-bs-target="#betTopupSheet">
            <img src="icons/sms.png" alt="Bulk SMS" class="service-icon">
            <div style="font-size: 12px; margin-top: 4px;">Betting</div>
          </a>
        </div>
        
        
        <!-- Electricity  -->
        <div class="col-3 text-center">
           <a href="#" data-bs-toggle="modal" data-bs-target="#electricityModal">
             <i class="bi bi-lightning-charge-fill network-icon"></i>
            <div style="font-size: 12px; margin-top: 4px;">Electricity</div>
          </a>
        </div>

        <!-- Bookings -->
        <div class="col-3 text-center">
          <a href="booking_category.php">
            <img src="icons/event.png" alt="Bookings" class="service-icon">
            <div style="font-size: 12px; margin-top: 4px;">Bookings</div>
          </a>
        </div>

       <!-- Shopping -->
<div class="col-3 text-center">
  <a href="#" data-bs-toggle="modal" data-bs-target="#onlineMartModal">
    <img src="icons/online-shopping.png" alt="Shopping" class="service-icon">
    <div style="font-size: 12px; margin-top: 4px;">Shopping</div>
  </a>
</div>
<script>
    function fetchPage(page) {
  fetch(page)
    .then(res => res.text())
    .then(html => {
      document.getElementById('dashboard-content').innerHTML = html;
    });
}

</script>


        <!-- Gift Cards -->
        <div class="col-3 text-center">
          <a href="get_gift_cards.php">
            <img src="icons/card.png" alt="Gift Cards" class="service-icon">
            <div style="font-size: 12px; margin-top: 4px;">Gift Cards</div>
          </a>
        </div>

        <!-- Exam Pins -->
        <div class="col-3 text-center">
          <a href="#" data-bs-toggle="modal" data-bs-target="#examPinSheet">
            <img src="icons/exam (1).png" alt="Academic" class="service-icon">
            <div style="font-size: 11px; margin-top: 4px;">Exam Pins</div>
          </a>
        </div>

      </div>
    </div>
  </div>
</div>


<!-- Swiper Card -->
<div class="section full mt-1">
  <div class="card" style="border-radius: 12px; overflow: hidden;">
    <div class="container my-1">
      <div class="swiper leaderboard-swiper">
        <div class="swiper-wrapper">
          <?php foreach($slides as $slide): ?>
            <div class="swiper-slide">
              <a href="<?= htmlspecialchars($slide['link_url']) ?>" target="_blank" 
                 style="display: block; width: 100%; height: 180px; border-radius: 12px; overflow: hidden;">
                <img src="<?= htmlspecialchars($slide['image_url']) ?>" 
                     alt="Promotion"
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<br>

<!-- Swiper Script -->
<script>
  const swiper = new Swiper('.leaderboard-swiper', {
    slidesPerView: 1,
    spaceBetween: 10,
    loop: true,
    autoplay: {
      delay: 2000,
    },
    speed: 400,
  });
</script>






        <!-- app footer -->
      

    </div>
    <!-- * App Capsule -->


    <!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- profile box -->
                    <div class="profileBox pt-2 pb-2">
                        <div class="image-wrapper">
                           <img src="<?= $avatar_url ?>" alt="User Avatar" class="imaged w32" style='h32 w32'>
                        </div>
                        <div class="in">
                             <span>Welcome, <b><?= htmlspecialchars($user['nick_name']) ?></b></span>
                         
                        </div>
                        <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                    <!-- * profile box -->
                    <!-- balance -->
                    <div class="sidebar-balance">
                        <div class="listview-title">Balance</div>
                        <div class="in">
                        <h2 id="balance-amount" style="font-size: 20px; margin: 2px 0;">₦<?= number_format($balance, 2); ?></h2>
                        </div>
                    </div>
                    <!-- * balance -->




                    <!-- menu -->
                    <div class="listview-title mt-1">Menu</div>
                    <ul class="listview flush transparent no-line image-listview">
                        <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-primary">
                               <ion-icon name="storefront-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Bussiness Tools
                                   
                                </div>
                            </a>
                        </li>
                        
                         <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="receipt-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Bills & Utility
                                </div>
                            </a>
                        </li>
                          <li>
                            <a href="snappy.php" class="item">
                                <div class="icon-box bg-primary">
                                 <ion-icon name="cloud-upload-outline"></ion-icon>
                                </div>
                                <div class="in">
                                   Snap_Pay
                                   
                                </div>
                            </a>
                        </li>
                         
                         <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-primary">
                                   <ion-icon name="share-social-outline"></ion-icon>
                                </div>
                                <div class="in">
                                   Social Media Services
                                </div>
                            </a>
                        </li>
                        
                         <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="cart-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Online Shopping
                                </div>
                            </a>
                        </li>
                        
                    </ul>
                    <!-- * menu -->

                    <!-- others -->
                    <div class="listview-title mt-1">Others</div>
                    <ul class="listview flush transparent no-line image-listview">
                       <li>
                            <a href="#" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="chatbubble-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Support
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Log out
                                </div>
                            </a>
                        </li>


                    </ul>
                    <!-- * others -->

                   

                </div>
            </div>
        </div>
    </div>
    <!-- * App Sidebar -->

<!-- ✅ Hidden Field -->
<input type="hidden" id="user_name" value="<?= htmlspecialchars($user['full_name']) ?>">
<!-- ✅ Hidden Balance -->
<input type="hidden" id="user_balance" value="<?= htmlspecialchars($user['balance']) ?>">

<!-- ✅ Withdraw Modal -->
<div class="modal fade action-sheet" id="withdrawModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="action-sheet-content">
        <form id="withdrawForm">

          <!-- Beneficiary -->
          <div class="form-group">
            <label>Choose Saved Beneficiary</label>
            <select id="beneficiarySelect" class="form-control">
              <option value="">-- Select --</option>
            </select>
          </div>

          <!-- Bank Select -->
          <div class="form-group">
            <label>Select Bank</label>
            <select id="withdraw_bank_select" class="form-control" required>
              <option value="">Loading banks...</option>
            </select>
          </div>

          <!-- Hidden Fields -->
          <input type="hidden" name="withdraw_bank_name" id="withdraw_bank_name">
          <input type="hidden" name="withdraw_bank_code" id="bank_code">

          <!-- Account Number -->
          <div class="form-group">
            <label>Account Number</label>
            <input type="number" name="withdraw_account_number" id="account_number" class="form-control" required />
            <div id="resolvedName" class="small text-muted mt-1"></div>
          </div>

           <!-- Amount -->
            <div class="form-group">
              <label>Amount (₦)</label>
             <input type="number" name="withdraw_amount" id="withdraw_amount" class="form-control" required />
             <small id="amountError" class="text-danger d-none">Insufficient balance</small>
            </div>


          <!-- Save Beneficiary -->
          <div class="form-check mt-2">
            <input type="checkbox" class="form-check-input" id="save_beneficiary" name="save_beneficiary" value="yes">
            <label class="form-check-label" for="save_beneficiary">Save as Beneficiary</label>
          </div>
          
    <style>
  .withdrawal-toggle {
    margin: 12px 0;
    display: flex;
    flex-direction: column;
  }

  .withdrawal-toggle label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 6px;
    color: #333;
  }

  .withdrawal-toggle select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #f9f9f9 url("data:image/svg+xml;utf8,<svg fill='%23333' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>") no-repeat right 10px center;
    background-size: 14px;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 10px 40px 10px 12px;
    font-size: 15px;
    color: #444;
    cursor: pointer;
    transition: border 0.2s ease, box-shadow 0.2s ease;
  }

  .withdrawal-toggle select:focus {
    outline: none;
    border: 1px solid #4CAF50;
    box-shadow: 0 0 6px rgba(76, 175, 80, 0.3);
    background-color: #fff;
  }
</style>

<div class="withdrawal-toggle">
  <label for="withdrawal_mode">Withdrawal Mode</label>
  <select id="withdrawal_mode" name="withdrawal_mode">
    <option value="paystack">💳 Paystack Balance</option>
    <option value="direct">⚡ Direct Credit</option>
  </select>
</div>

          <!-- Hidden PIN -->
          <input type="hidden" name="pin" id="hidden_withdraw_pin">

          <!-- Withdraw Button -->
          <button id="withdrawBtn" type="button" class="btn btn-primary w-100 mt-3">Withdraw</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- PIN Confirmation Modal -->
<div class="modal fade action-sheet" id="confirmPinSheet" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="action-sheet-content">

        <h5 class="text-center mt-2 mb-3">Confirm Transfer</h5>

        <!-- Summary -->
        <div class="mb-2">
          <p><strong>Bank:</strong> <span id="summaryBank"></span></p>
          <p><strong>Account Name:</strong> <span id="summaryAcctName"></span></p>
          <p><strong>Account Number:</strong> <span id="summaryAcctNumber"></span></p>
          <p><strong>Amount:</strong> ₦<span id="summaryAmount"></span></p>
        </div>

        <hr>
        <p class="text-muted small text-center">Enter your 4-digit PIN</p>
        <div class="form-group">
          <input type="password" id="pinInput" class="form-control text-center" maxlength="4" inputmode="numeric" placeholder="••••" autocomplete="off" />
        </div>

        <button class="btn btn-primary w-100 mt-2" id="submitPinBtn">Submit Transfer</button>
        <p class="text-danger small text-center mt-2" id="pinErrorMsg" style="display:none;"></p>

      </div>
    </div>
  </div>
</div>


<!-- 📸 Face Verification Modal -->
<div class="modal fade" id="faceVerifyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-3 text-center">
      <h5 class="mb-2">Facial Verification Required</h5>
      <video id="video" width="100%" autoplay playsinline></video>
      <canvas id="canvas" style="display: none;"></canvas>
      <button id="captureBtn" class="btn btn-primary mt-2">Capture Selfie</button>
      <div id="verifyStatus" class="mt-2 text-muted small"></div>
    </div>
  </div>
</div>
    
    <script>
// ===============================
// Withdrawal JS - Fully Optimized
// ===============================

let pinAttempts = 0;
const MAX_ATTEMPTS = 4;
let bankList = [];
let faceVerified = false;
let nameMatch = false; // track account verification

document.addEventListener('DOMContentLoaded', () => {
  loadBanks();
  loadBeneficiaries();
  attachEventListeners();

  // Start with Withdraw button disabled
  $('#withdrawBtn').prop('disabled', true);
});

// -------------------------------
// Load Banks
// -------------------------------
function loadBanks() {
  fetch('get_banks.php')
    .then(res => res.json())
    .then(data => {
      const select = $('#withdraw_bank_select');
      select.empty().append('<option value="">Select Bank</option>');
      bankList = data.data || [];
      bankList.forEach(bank => {
        select.append(new Option(bank.name, bank.code, false, false));
        select.find('option:last').data('bankName', bank.name);
      });
    });
}

// -------------------------------
// Load Saved Beneficiaries
// -------------------------------
function loadBeneficiaries() {
  fetch('get_beneficiaries.php')
    .then(res => res.json())
    .then(data => {
      const select = $('#beneficiarySelect');
      data.forEach(b => {
        const opt = new Option(
          `${b.account_name} - ${b.account_number} (${b.bank_name})`,
          `${b.account_number}|${b.bank_code}`
        );
        $(opt).data('bankName', b.bank_name);
        $(opt).data('accountName', b.account_name);
        select.append(opt);
      });
    });
}

// -------------------------------
// Attach Event Listeners
// -------------------------------
function attachEventListeners() {
  const amountInput = $('#withdraw_amount');
  const withdrawBtn = $('#withdrawBtn');
  const amountError = $('#amountError');
  const userBalance = parseFloat($('#user_balance').val());

  // Validate Amount
  amountInput.on('input', () => {
    const amount = parseFloat(amountInput.val());
    if (amount > 0 && amount <= userBalance) {
      amountError.addClass('d-none');
      if (nameMatch) withdrawBtn.prop('disabled', false);
    } else {
      withdrawBtn.prop('disabled', true);
      amountError.text(amount > userBalance ? 'Insufficient balance' : '').toggleClass('d-none', amount <= userBalance);
    }
  });

  // Bank select change
  $('#withdraw_bank_select').on('change', function() {
    const selected = $(this).find('option:selected');
    $('#withdraw_bank_name').val(selected.data('bankName') || '');
    $('#bank_code').val($(this).val());
    verifyAccountName();
  });

  // Beneficiary select change
  $('#beneficiarySelect').on('change', function() {
    const [acct, code] = $(this).val().split('|');
    const bank = bankList.find(b => b.code === code);
    if (bank) {
      $('#account_number').val(acct);
      $('#withdraw_bank_select').val(code).trigger('change');
      $('#withdraw_bank_name').val(bank.name);
      $('#bank_code').val(code);
      verifyAccountName();
    }
  });

  // Account number blur/change
  $('#account_number').on('blur change', verifyAccountName);

  // Withdraw click
  $('#withdrawBtn').on('click', () => {
    if (!nameMatch) {
      alert("Please verify the account before proceeding.");
      return;
    }
    showPinModal();
  });

  // PIN submit
  $('#submitPinBtn').on('click', handlePinSubmit);

  // Withdraw Form submit
  $('#withdrawForm').submit(handleFormSubmit);

  // Capture selfie
  $('#captureBtn').on('click', captureSelfie);
}

// -------------------------------
// Verify Account Name (Paystack)
// -------------------------------
function verifyAccountName() {
  const acct = $('#account_number').val().trim();
  const bank = $('#bank_code').val().trim();

  if (acct.length !== 10 || !bank) {
    nameMatch = false;
    $('#withdrawBtn').prop('disabled', true);
    return;
  }

  $('#resolvedName').text("Verifying...").css('color', 'gray');

  $.get('verify_account.php', { account_number: acct, bank_code: bank }, res => {
    if (res.status && res.data?.account_name) {
      $('#resolvedName').text("Account Name: " + res.data.account_name).css('color', 'green');
      nameMatch = true;
      if (parseFloat($('#withdraw_amount').val()) > 0) $('#withdrawBtn').prop('disabled', false);
    } else {
      $('#resolvedName').text("⚠ Could not verify account").css('color', 'red');
      nameMatch = false;
      $('#withdrawBtn').prop('disabled', true);
    }
  }, 'json').fail(() => {
    $('#resolvedName').text("⚠ Verification failed").css('color', 'red');
    nameMatch = false;
    $('#withdrawBtn').prop('disabled', true);
  });
}

// -------------------------------
// Show PIN Modal
// -------------------------------
function showPinModal() {
  const amount = parseFloat($('#withdraw_amount').val());
  const balance = parseFloat($('#user_balance').val());
  if (isNaN(amount) || amount > balance) {
    alert("Insufficient balance for this withdrawal.");
    return;
  }

  $('#summaryBank').text($('#withdraw_bank_name').val());
  $('#summaryAcctNumber').text($('#account_number').val());
  $('#summaryAcctName').text($('#resolvedName').text().replace("Account Name: ", "").trim());
  $('#summaryAmount').text(amount);

  $('#pinInput').val('');
  $('#pinErrorMsg').hide();

  new bootstrap.Modal(document.getElementById('confirmPinSheet')).show();
}

// -------------------------------
// Handle PIN Submit
// -------------------------------
function handlePinSubmit() {
  const pin = $('#pinInput').val().trim();
  if (!/^\d{4}$/.test(pin)) {
    $('#pinErrorMsg').text("Enter a valid 4-digit PIN").show();
    return;
  }

  $.post('verify_pin.php', { pin }, res => {
    if (res.success) {
      $('#hidden_withdraw_pin').val(pin);
      bootstrap.Modal.getInstance(document.getElementById('confirmPinSheet')).hide();
      $('#withdrawForm').submit();

    } else if (res.locked) {
      const msg = res.retry_after ? `Too many attempts. Try again in ${Math.ceil(res.retry_after / 60)} minutes.` : (res.message || "Your withdrawal has been locked.");
      $('#pinErrorMsg').text(msg).show();
      if (!res.retry_after) $('#pinInput, #submitPinBtn').hide();

    } else if (res.face_required) {
      pinAttempts = MAX_ATTEMPTS;
      bootstrap.Modal.getInstance(document.getElementById('confirmPinSheet')).hide();
      startCamera();
      $('#faceVerifyModal').modal('show');

    } else {
      pinAttempts++;
      const left = MAX_ATTEMPTS - pinAttempts;
      $('#pinErrorMsg').text(res.message || `Incorrect PIN. ${left} attempt${left === 1 ? '' : 's'} left.`).show();
      $('#pinInput').val('').focus();
    }
  }, 'json');
}

// -------------------------------
// Handle Form Submit
// -------------------------------
function handleFormSubmit(e) {
  e.preventDefault();
  const btn = $('#withdrawBtn');
  btn.prop('disabled', true).text('Processing...');

  $.post('process_withdrawal.php', $(this).serialize(), res => {
    btn.prop('disabled', false).text('Withdraw');

    if (res.success) {
      $('#toast-success').addClass('show');
      $('#withdrawModal').modal('hide');
      setTimeout(() => window.location.href = `withdrawal_receipt.php?reference=${encodeURIComponent(res.reference)}`, 1000);

    } else {
      $('#toast-failure .toast-body').text(res.message);
      $('#toast-failure').addClass('show');
      setTimeout(() => $('#toast-failure').removeClass('show'), 3000);
    }
  }, 'json').fail(() => {
    btn.prop('disabled', false).text('Withdraw');
    $('#toast-failure .toast-body').text('An error occurred. Please try again.');
    $('#toast-failure').addClass('show');
    setTimeout(() => $('#toast-failure').removeClass('show'), 3000);
  });
}

// -------------------------------
// Facial Verification
// -------------------------------
function startCamera() {
  const video = document.getElementById('video');
  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => video.srcObject = stream)
    .catch(() => $('#verifyStatus').text("Camera permission denied.").css('color', 'red'));
}

function captureSelfie() {
  const canvas = document.getElementById('canvas');
  const video = document.getElementById('video');
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  canvas.getContext('2d').drawImage(video, 0, 0);
  const imageData = canvas.toDataURL('image/jpeg');

  $('#verifyStatus').text("Verifying...").css('color', 'gray');

  $.post('withdrawal_face_verify.php', { selfie_image: imageData }, response => {
    if (response.status === 'success') {
      $('#verifyStatus').text("✅ Face Verified").css('color', 'green');
      faceVerified = true;
      setTimeout(() => {
        $('#faceVerifyModal').modal('hide');
        $('#confirmPinSheet').modal('show');
      }, 1200);
    } else {
      $('#verifyStatus').text(response.message || "❌ Face mismatch. Contact support or retry.").css('color', 'red');
    }
  }, 'json');
}
function handleFormSubmit(e) {
  e.preventDefault();
 
  const btn = $('#withdrawBtn');
  btn.prop('disabled', true).text('Processing...');

  $.post('process_withdrawal.php', $(this).serialize(), res => {
    btn.prop('disabled', false).text('Withdraw');

    if (res.success) {
      $('#toast-success').addClass('show');
      $('#withdrawModal').modal('hide');
      setTimeout(() => {
        window.location.href = 'withdrawal_receipt.php?reference=' + encodeURIComponent(res.reference);
      }, 1000);
    } else {
      $('#toast-failure .toast-body').text(res.message);
      $('#toast-failure').addClass('show');
      setTimeout(() => $('#toast-failure').removeClass('show'), 3000);
    }
  }, 'json').fail(() => {
    btn.prop('disabled', false).text('Withdraw');
    $('#toast-failure .toast-body').text('An error occurred. Please try again.');
    $('#toast-failure').addClass('show');
    setTimeout(() => $('#toast-failure').removeClass('show'), 3000);
  });
}

</script>

   
<!-- Action Sheet: Deposit -->
<div class="offcanvas offcanvas-bottom" tabindex="-1" id="actionSheetDeposit" style="height: auto;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Deposit Funds</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body small">
        <form action="process_payment.php" method="POST">
            <div class="mb-3">
                <label for="amount" class="form-label"><strong>Enter Amount (₦):</strong></label>
                <input type="number" name="amount" id="amount" class="form-control" min="10" required placeholder="₦10 Minimum">
            </div>
            <button type="submit" class="btn btn-success w-100">Proceed to Payment</button>
        </form>
    </div>
</div>

<!-- ✅ FinApp P2P Action Sheet Modal -->
<div class="modal fade action-sheet" id="actionSheetP2p" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="action-sheet-content">
        <form id="p2pForm">

        <h5 class="text-center small text-muted mt-1">Send Money (P2P)</h5>

          <!-- Receiver Phone -->
          <div class="form-group">
            <label class="form-label">Receiver's Phone</label>
            <input type="tel" class="form-control form-control-lg" id="receiver_phone" name="receiver_phone" placeholder="e.g. 08012345678" required>
            <div id="receiver_info" class="small text-muted mt-1"></div>
          </div>

          <!-- Receiver Name -->
          <div class="form-group">
            <label class="form-label">Receiver's Name</label>
            <input type="text" class="form-control form-control-lg" id="receiver_name" name="receiver_name" readonly placeholder="Name will appear here">
          </div>

          <!-- Amount -->
          <div class="form-group">
            <label class="form-label">Amount</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text">₦</span>
              <input type="number" class="form-control" id="amount" name="amount" min="1" required>
            </div>
          </div>

        <!-- PIN -->
<div class="form-group">
  <label class="form-label">Transaction PIN</label>
  <input
    type="password"
    inputmode="numeric"
    pattern="[0-9]*"
    maxlength="4"
    class="form-control form-control-lg text-center"
    id="pin"
    name="pin"
    required
  >
</div>


          <input type="hidden" id="receiver_id" name="receiver_id">

          <div class="d-grid">
            <button type="submit" id="submitBtn" class="btn btn-success btn-lg" disabled>Send Now</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<!-- Airtime Purchase Action Sheet -->
<div class="modal fade action-sheet" id="airtimeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content rounded-3 border-0 shadow">

      <!-- Header -->
      <div class="modal-header border-0">
        <h5 class="modal-title text-center fw-bold mb-0 w-100">Buy Airtime</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body -->
      <div class="modal-body p-3">
        <form id="airtimeForm">
            
               <!-- Phone Number -->
          <div class="mb-4">
            <label class="label fw-bold">Phone Number</label>
            <input type="tel" name="mobileNumber" id="mobileNumber" class="form-control" placeholder="e.g. 08012345678" required>
          </div>

          <!-- Network Selection Grid with Icons -->
          <div class="mb-4">
            <label class="label fw-bold mb-2">Select Network</label>
            <div class="d-grid gap-2" style="grid-template-columns: repeat(2, 1fr);">
              <button type="button" class="btn btn-outline-primary network-btn" data-network="mtn">
                <img src="icons/mtn.svg" alt="MTN" style="width:24px;height:24px;margin-right:8px;"> MTN
              </button>
              <button type="button" class="btn btn-outline-primary network-btn" data-network="glo">
                <img src="icons/glo.png" alt="Glo" style="width:24px;height:24px;margin-right:8px;"> Glo
              </button>
              <button type="button" class="btn btn-outline-primary network-btn" data-network="airtel">
                <img src="icons/airtel.png" alt="Airtel" style="width:24px;height:24px;margin-right:8px;"> Airtel
              </button>
              <button type="button" class="btn btn-outline-primary network-btn" data-network="9mobile">
                <img src="icons/9mobile.png" alt="9mobile" style="width:24px;height:24px;margin-right:8px;"> 9mobile
              </button>
            </div>
            <input type="hidden" name="network" id="selectedNetwork" required>
          </div>

       

          <!-- Amount Selection Grid + Custom Input -->
          <div class="mb-4">
            <label class="label fw-bold mb-2">Select Amount (₦)</label>
            <div class="d-grid gap-2 mb-2" style="grid-template-columns: repeat(3, 1fr);">
              <button type="button" class="btn btn-outline-success amount-btn" data-amount="50">₦50</button>
              <button type="button" class="btn btn-outline-success amount-btn" data-amount="100">₦100</button>
              <button type="button" class="btn btn-outline-success amount-btn" data-amount="200">₦200</button>
              <button type="button" class="btn btn-outline-success amount-btn" data-amount="500">₦500</button>
              <button type="button" class="btn btn-outline-success amount-btn" data-amount="1000">₦1,000</button>
              <button type="button" class="btn btn-outline-success amount-btn" data-amount="2000">₦2,000</button>
            </div>
            <input type="number" name="amount" id="selectedAmount" class="form-control text-muted" placeholder="Or enter custom amount" min="50" required>
          </div>

          <!-- Submit Button -->
          <div class="d-grid mt-3">
            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
              Continue <i class="fas fa-arrow-right ms-1"></i>
            </button>
          </div>

        </form>
      </div>

    </div>
  </div>
</div>

<!-- Modern Confirmation Action Sheet -->
<div class="modal fade action-sheet" id="airtimeConfirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content rounded-3 border-0 shadow-lg">

      <!-- Header with X close -->
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold w-100 text-center text-primary mb-0">Confirm Airtime Purchase</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body with modern card style -->
      <div class="modal-body p-3">
        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Network:</span>
              <strong id="confirmNetwork"></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Phone Number:</span>
              <strong id="confirmNumber"></strong>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Amount:</span>
              <strong>₦<span id="confirmAmount"></span></strong>
            </div>
          </div>
        </div>

        <!-- Spinner (hidden by default) -->
        <div id="airtimeSpinner" class="text-center my-3 d-none">
          <div class="spinner-border text-success" role="status"></div>
          <p class="small mt-2">Processing transaction...</p>
        </div>

        <button class="btn btn-success w-100 mt-2 rounded-pill" id="confirmAirtimeBtn">Confirm</button>
      </div>

    </div>
  </div>
</div>

<!-- JS for network icons, auto-detect, amount & confirmation -->
<script>
  const networkPatterns = {
    mtn: /^(0803|0806|0703|0706|0810|0813|0814|0816|0903|0906|0913|0916)/,
    glo: /^(0805|0807|0811|0815|0905|0915)/,
    airtel: /^(0802|0808|0708|0812|0701|0902|0907|0912)/,
    '9mobile': /^(0809|0817|0818|0909|0908|0918)/
  };

  // Auto-detect network from phone number
  const mobileInput = document.getElementById('mobileNumber');
  mobileInput.addEventListener('input', () => {
    const number = mobileInput.value.replace(/\D/g, ''); // remove non-digits
    let detected = null;
    for (const [network, pattern] of Object.entries(networkPatterns)) {
      if (pattern.test(number)) detected = network;
    }
    if (detected) {
      document.querySelectorAll('.network-btn').forEach(b => b.classList.remove('active'));
      const btn = document.querySelector(`.network-btn[data-network="${detected}"]`);
      if (btn) btn.classList.add('active');
      document.getElementById('selectedNetwork').value = detected;
    }
  });

  // Network selection
  document.querySelectorAll('.network-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.network-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('selectedNetwork').value = btn.dataset.network;
    });
  });

  // Quick amount selection
  document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      document.getElementById('selectedAmount').value = btn.dataset.amount;
    });
  });

  // Remove active if user types custom amount
  const amountInput = document.getElementById('selectedAmount');
  amountInput.addEventListener('input', () => {
    document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
  });

  // Submit form → show modern confirmation modal
  const airtimeForm = document.getElementById('airtimeForm');
  airtimeForm.addEventListener('submit', e => {
    e.preventDefault();
    const network = document.getElementById('selectedNetwork').value;
    const number = airtimeForm.mobileNumber.value;
    const amount = airtimeForm.amount.value;

    if (!network || !number || !amount) {
      alert("Please fill all fields!");
      return;
    }

    document.getElementById('confirmNetwork').textContent = network.toUpperCase();
    document.getElementById('confirmNumber').textContent = number;
    document.getElementById('confirmAmount').textContent = amount;

    const confirmModal = new bootstrap.Modal(document.getElementById('airtimeConfirmModal'));
    confirmModal.show();
  });
</script>

<style>
  .network-btn.active,
  .amount-btn.active {
    background-color: #0d6efd !important;
    color: white !important;
  }
  .network-btn, .amount-btn {
    border-radius: 0.75rem;
    padding: 0.75rem 0;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Modern card style for confirmation */
  #airtimeConfirmModal .card {
    border-radius: 0.75rem;
  }
</style>

<!-- Buy Data Modal -->
<div class="modal fade action-sheet" id="buyDataSheet" tabindex="-1" data-bs-backdrop="static" aria-labelledby="buyDataLabel">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content rounded-top p-4 shadow-sm position-relative">
      <!-- Close Button -->
      <a href="#" class="position-absolute top-0 end-0 mt-2 me-3 fs-4" data-bs-dismiss="modal" aria-label="Close">&times;</a>

      <h5 id="buyDataLabel" class="text-center fw-bold mb-3 text-primary">Buy Data</h5>

      <form id="buyDataForm" novalidate>
        <!-- Network Selection Row -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Select Network</label>
          <div class="d-flex justify-content-between gap-2 flex-wrap">
            <button type="button" class="network-btn-circle" data-network="mtn">
              <div class="network-icon">
                <img src="icons/mtn.svg" alt="MTN">
              </div>
              <span class="network-name">MTN</span>
            </button>
            <button type="button" class="network-btn-circle" data-network="glo">
              <div class="network-icon">
                <img src="icons/glo.png" alt="Glo">
              </div>
              <span class="network-name">Glo</span>
            </button>
            <button type="button" class="network-btn-circle" data-network="airtel">
              <div class="network-icon">
                <img src="icons/airtel.png" alt="Airtel">
              </div>
              <span class="network-name">Airtel</span>
            </button>
            <button type="button" class="network-btn-circle" data-network="9mobile">
              <div class="network-icon">
                <img src="icons/9mobile.png" alt="9mobile">
              </div>
              <span class="network-name">9mobile</span>
            </button>
          </div>
          <input type="hidden" name="network" id="selectedNetwork" required>
        </div>


        <!-- Data Plan Selection -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Data Plan</label>
          <select class="form-select" id="planSelect" disabled required>
            <option value="">-- Select Network First --</option>
          </select>
        </div>

        <!-- Mobile Number -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Recipient Number</label>
          <input type="tel" class="form-control" id="mobileInput" placeholder="e.g. 08148622359" maxlength="12" required>
        </div>

        <!-- Status / Errors -->
        <div id="statusMsg" class="text-center text-danger small mb-3"></div>

        <!-- Submit Button -->
        <div class="d-grid">
          <button type="submit" id="purchaseBtn" class="btn btn-primary rounded-pill" disabled>
            Continue
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Data Purchase Modal -->
<div class="modal fade action-sheet" id="confirmBuyDataSheet" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmBuyLabel">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content rounded-top p-4 shadow-sm text-center">

      <h5 id="confirmBuyLabel" class="text-primary fw-bold mb-3">Confirm Purchase</h5>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body text-start d-flex align-items-center gap-3">
          <div class="network-icon" style="width:50px;height:50px;">
            <img id="confirmNetworkIcon" src="" alt="Network" style="width:100%;height:100%;">
          </div>
          <div>
            <p class="mb-1"><span class="text-muted">Network:</span> <strong id="confirmNetwork"></strong></p>
            <p class="mb-1"><span class="text-muted">Plan:</span> <strong id="confirmPlan"></strong></p>
            <p class="mb-0"><span class="text-muted">Phone:</span> <strong id="confirmMobile"></strong></p>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary rounded-pill" id="confirmPurchaseBtn">
          Confirm & Purchase
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade action-sheet" id="errorSheet" tabindex="-1">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content text-center p-3 rounded-top bg-white">
      <h6 class="text-danger fw-bold mb-2">Transaction Failed</h6>
      <p id="errorMessage" class="text-muted small"></p>
      <button class="btn btn-danger w-100 rounded-pill mt-2" data-bs-dismiss="modal">Close</button>
    </div>
  </div>
</div>
<style>
    .network-btn-circle {
  background: #f8f9fa;
  border: 2px solid #dee2e6;
  border-radius: 50%;
  padding: 6px;              /* smaller padding */
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  outline: none;
  width: 60px;                /* smaller overall size */
  height: 60px;
}

.network-btn-circle:hover {
  transform: translateY(-2px);  /* smaller hover lift */
  border-color: #0d6efd;
}

.network-btn-circle.active {
  border-color: #0d6efd;
  background-color: #0d6efd;
}

.network-btn-circle.active img {
  filter: brightness(0) invert(1);
}

.network-icon {
  width: 40px;                  /* smaller icon wrapper */
  height: 40px;
  border-radius: 50%;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 4px;           /* less margin */
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.network-icon img {
  width: 28px;                  /* smaller icon */
  height: 28px;
}

.network-name {
  font-size: 12px;              /* slightly smaller font */
  font-weight: 500;
  color: #333;
  text-align: center;
}

</style>
<?php
// Fetch active currency rates
$query = "SELECT * FROM currency_rates WHERE is_active = 1";
$stmt = $sql->prepare($query);
$stmt->execute();
$currencyRates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Build unique currency list
$currencies = [];
foreach ($currencyRates as $rate) {
    if (!in_array($rate['base_currency'], $currencies)) {
        $currencies[] = $rate['base_currency'];
    }
    if (!in_array($rate['target_currency'], $currencies)) {
        $currencies[] = $rate['target_currency'];
    }
}
sort($currencies);
?>

<!-- Trigger Icon -->


<!-- Currency Converter Modal -->
<div class="modal fade action-sheet" id="actionSheetCurrency" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Currency Converter</h5>
      </div>
      <div class="modal-body">
        <div class="action-sheet-content">
          <form id="currencyForm">

            <div class="form-group basic text-muted">
              <label class="label">From</label>
              <input type="text" class="form-control text-muted" id="fromCurrency" value="USD" readonly>
            </div>

            <div class="form-group basic">
              <label class="label">To</label>
              <select class="form-control text-muted" id="toCurrency" required>
                <?php foreach ($currencies as $currency): ?>
                  <option value="<?= htmlspecialchars($currency) ?>"><?= htmlspecialchars($currency) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group basic">
              <label class="label">Amount</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" step="any" class="form-control" id="convertAmount" placeholder="Enter amount" required>
              </div>
            </div>

            <div class="form-group basic mt-3">
              <button type="button" class="btn btn-warning btn-block btn-lg" onclick="convertCurrency()">Convert</button>
            </div>

            <div class="form-group text-center mt-3" id="conversionResult" style="font-weight: bold;"></div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bet Top-Up Modal -->
<div class="modal fade action-sheet" id="betTopupSheet" tabindex="-1" data-bs-backdrop="static" aria-labelledby="betTopupLabel">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content rounded-top p-4 shadow-sm position-relative">

      <!-- Close Button on Left -->
      <a href="#" class="position-absolute top-0 start-0 mt-3 ms-3 fs-4 text-dark" data-bs-dismiss="modal" aria-label="Close">&times;</a>

      <h5 id="betTopupLabel" class="text-center fw-bold mb-4 text-primary">Bet Top-Up</h5>

      <form id="betTopupForm" novalidate>
        <!-- Betting Platform Selection -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Select Betting Platform</label>
          <div class="d-flex justify-content-between gap-2 flex-wrap">
            <button type="button" class="platform-btn-circle" data-platform="betking">
              <div class="platform-icon">
                <img src="icons/betking.png" alt="BetKing">
              </div>
              <span class="platform-name">BetKing</span>
            </button>
            <button type="button" class="platform-btn-circle" data-platform="bet9ja">
              <div class="platform-icon">
                <img src="icons/bet9ja.png" alt="Bet9ja">
              </div>
              <span class="platform-name">Bet9ja</span>
            </button>
            <button type="button" class="platform-btn-circle" data-platform="nairabet">
              <div class="platform-icon">
                <img src="icons/nairabet.png" alt="NairaBet">
              </div>
              <span class="platform-name">NairaBet</span>
            </button>
            <button type="button" class="platform-btn-circle" data-platform="sportybet">
              <div class="platform-icon">
                <img src="icons/sportybet.png" alt="SportyBet">
              </div>
              <span class="platform-name">SportyBet</span>
            </button>
          </div>
          <input type="hidden" name="platform" id="selectedPlatform" required>
        </div>

        <!-- Top-Up Amount Selection -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Top-Up Amount (₦)</label>
          <div class="d-grid gap-2 mb-2" style="grid-template-columns: repeat(3, 1fr);">
            <button type="button" class="btn btn-outline-success bet-amount-btn" data-amount="100">₦100</button>
            <button type="button" class="btn btn-outline-success bet-amount-btn" data-amount="500">₦500</button>
            <button type="button" class="btn btn-outline-success bet-amount-btn" data-amount="1000">₦1,000</button>
            <button type="button" class="btn btn-outline-success bet-amount-btn" data-amount="2000">₦2,000</button>
            <button type="button" class="btn btn-outline-success bet-amount-btn" data-amount="5000">₦5,000</button>
          </div>
          <input type="number" name="amount" id="betTopupAmount" class="form-control text-muted" placeholder="Or enter custom amount" min="100" required>
        </div>

        <!-- Account Number -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Account Number</label>
          <input type="text" class="form-control" id="betAccountNumber" placeholder="Enter account number" maxlength="15" required>
        </div>

        <!-- Status / Errors -->
        <div id="betStatusMsg" class="text-center text-danger small mb-3"></div>

        <!-- Submit Button -->
        <div class="d-grid">
          <button type="submit" id="betTopupBtn" class="btn btn-primary rounded-pill" disabled>
            Continue
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Bet Top-Up Modal -->
<div class="modal fade action-sheet" id="confirmBetTopupSheet" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmBetLabel">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content rounded-top p-4 shadow-sm text-center">

      <h5 id="confirmBetLabel" class="text-primary fw-bold mb-3">Confirm Top-Up</h5>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body text-start d-flex align-items-center gap-3">
          <div class="platform-icon" style="width:50px;height:50px;">
            <img id="confirmPlatformIcon" src="" alt="Platform" style="width:100%;height:100%;">
          </div>
          <div>
            <p class="mb-1"><span class="text-muted">Platform:</span> <strong id="confirmPlatform"></strong></p>
            <p class="mb-1"><span class="text-muted">Amount:</span> <strong id="confirmBetAmount"></strong></p>
            <p class="mb-0"><span class="text-muted">Account:</span> <strong id="confirmBetAccount"></strong></p>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary rounded-pill" id="confirmBetBtn">
          Confirm & Top-Up
        </button>
      </div>
    </div>
  </div>
</div>

<style>
.platform-btn-circle {
  background: #f8f9fa;
  border: 2px solid #dee2e6;
  border-radius: 50%;
  padding: 6px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  outline: none;
  width: 60px;
  height: 60px;
}

.platform-btn-circle:hover {
  transform: translateY(-2px);
  border-color: #0d6efd;
}

.platform-btn-circle.active {
  border-color: #0d6efd;
  background-color: #0d6efd;
}

.platform-btn-circle.active img {
  filter: brightness(0) invert(1);
}

.platform-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 4px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.platform-icon img {
  width: 28px;
  height: 28px;
}

.platform-name {
  font-size: 12px;
  font-weight: 500;
  color: #333;
  text-align: center;
}
</style>


<!-- Exams Pin Purchase Modal -->
<div class="modal fade action-sheet" id="examPinSheet" tabindex="-1" data-bs-backdrop="static" aria-labelledby="examPinLabel">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content rounded-top p-4 shadow-sm position-relative">

      <!-- Close Button on Left -->
      <a href="#" class="position-absolute top-0 start-0 mt-3 ms-3 fs-4 text-dark" data-bs-dismiss="modal" aria-label="Close">&times;</a>

      <h5 id="examPinLabel" class="text-center fw-bold mb-4 text-primary">Buy Exams PIN</h5>

      <form id="examPinForm" novalidate>
        <!-- Exam Board Selection -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Select Exam Board</label>
          <div class="d-flex justify-content-between gap-2 flex-wrap">
            <button type="button" class="exam-board-btn-circle" data-board="waec">
              <div class="exam-board-icon">
                <img src="icons/waec.png" alt="WAEC">
              </div>
              <span class="exam-board-name">WAEC</span>
            </button>
            <button type="button" class="exam-board-btn-circle" data-board="neco">
              <div class="exam-board-icon">
                <img src="icons/neco.png" alt="NECO">
              </div>
              <span class="exam-board-name">NECO</span>
            </button>
            <button type="button" class="exam-board-btn-circle" data-board="jamb">
              <div class="exam-board-icon">
                <img src="icons/jamb.png" alt="JAMB">
              </div>
              <span class="exam-board-name">JAMB</span>
            </button>
            <button type="button" class="exam-board-btn-circle" data-board="nabteb">
              <div class="exam-board-icon">
                <img src="icons/nabteb.png" alt="NABTEB">
              </div>
              <span class="exam-board-name">NABTEB</span>
            </button>
          </div>
          <input type="hidden" name="board" id="selectedExamBoard" required>
        </div>

        <!-- Pin Type Selection -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Select PIN Type</label>
          <div class="d-grid gap-2" style="grid-template-columns: repeat(2, 1fr);">
            <button type="button" class="btn btn-outline-primary exam-pin-type-btn" data-type="scratch-card">Scratch Card</button>
            <button type="button" class="btn btn-outline-primary exam-pin-type-btn" data-type="electronic">Electronic PIN</button>
          </div>
          <input type="hidden" name="pinType" id="selectedPinType" required>
        </div>

        <!-- Quantity -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Quantity</label>
          <div class="d-grid gap-2" style="grid-template-columns: repeat(3, 1fr);">
            <button type="button" class="btn btn-outline-success exam-quantity-btn" data-quantity="1">1</button>
            <button type="button" class="btn btn-outline-success exam-quantity-btn" data-quantity="5">5</button>
            <button type="button" class="btn btn-outline-success exam-quantity-btn" data-quantity="10">10</button>
          </div>
          <input type="number" name="quantity" id="examQuantityInput" class="form-control text-muted" placeholder="Or enter custom quantity" min="1" required>
        </div>

        <!-- Status / Errors -->
        <div id="examStatusMsg" class="text-center text-danger small mb-3"></div>

        <!-- Submit Button -->
        <div class="d-grid">
          <button type="submit" id="examPinBtn" class="btn btn-primary rounded-pill" disabled>
            Continue
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Confirm Exams Pin Purchase Modal -->
<div class="modal fade action-sheet" id="confirmExamPinSheet" tabindex="-1" data-bs-backdrop="static" aria-labelledby="confirmExamPinLabel">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content rounded-top p-4 shadow-sm text-center">

      <h5 id="confirmExamPinLabel" class="text-primary fw-bold mb-3">Confirm Purchase</h5>

      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body text-start d-flex align-items-center gap-3">
          <div class="exam-board-icon" style="width:50px;height:50px;">
            <img id="confirmExamBoardIcon" src="" alt="Exam Board" style="width:100%;height:100%;">
          </div>
          <div>
            <p class="mb-1"><span class="text-muted">Exam Board:</span> <strong id="confirmExamBoard"></strong></p>
            <p class="mb-1"><span class="text-muted">PIN Type:</span> <strong id="confirmPinType"></strong></p>
            <p class="mb-0"><span class="text-muted">Quantity:</span> <strong id="confirmExamQuantity"></strong></p>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary rounded-pill" id="confirmExamBtn">
          Confirm & Purchase
        </button>
      </div>
    </div>
  </div>
</div>

<style>
.exam-board-btn-circle {
  background: #f8f9fa;
  border: 2px solid #dee2e6;
  border-radius: 50%;
  padding: 6px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  outline: none;
  width: 60px;
  height: 60px;
}

.exam-board-btn-circle:hover {
  transform: translateY(-2px);
  border-color: #0d6efd;
}

.exam-board-btn-circle.active {
  border-color: #0d6efd;
  background-color: #0d6efd;
}

.exam-board-btn-circle.active img {
  filter: brightness(0) invert(1);
}

.exam-board-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 4px;
  overflow: hidden;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.exam-board-icon img {
  width: 28px;
  height: 28px;
}

.exam-board-name {
  font-size: 12px;
  font-weight: 500;
  color: #333;
  text-align: center;
}
</style>




<!-- Cable TV Action Sheet Modal -->
<div class="modal fade action-sheet" id="modalCableTV" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content rounded-2">
      <div class="modal-header px-3 pt-3">
        <h5 class="modal-title">Cable TV Subscription</h5>
        <a href="#" class="close-btn" data-bs-dismiss="modal">
          <i class="bi bi-x"></i>
        </a>
      </div>
      <div class="modal-body p-3">
        <form id="cableTVForm">

       <!-- Provider (Grid UI) -->
<div class="form-group mb-3">
  <label class="form-label">TV Provider</label>

  <div class="d-grid gap-3" style="grid-template-columns: repeat(3, 1fr); text-align:center;">
    <button type="button" class="provider-btn" data-value="dstv">
      <img src="icons/dstv.png" alt="DStv">
      <span>DStv</span>
    </button>

    <button type="button" class="provider-btn" data-value="gotv">
      <img src="icons/gotv.png" alt="GOtv">
      <span>GOtv</span>
    </button>

    <button type="button" class="provider-btn" data-value="startimes">
      <img src="icons/startimes.png" alt="Startimes">
      <span>Startimes</span>
    </button>
  </div>

  <!-- ORIGINAL SELECT (HIDDEN, JS STILL USES THIS) -->
  <select class="form-control form-select d-none" id="cableProvider" required>
    <option value="">Select Provider</option>
    <option value="dstv">DStv</option>
    <option value="gotv">GOtv</option>
    <option value="startimes">Startimes</option>
  </select>
</div>


          <!-- Smartcard -->
          <div class="form-group mb-3">
            <label for="smartcard" class="form-label">Smartcard Number</label>
            <input type="number" class="form-control text-muted" id="smartcard" required>
            <small id="smartcardName" class="text-muted d-block mt-1"></small>
          </div>

          <!-- Package -->
          <div class="form-group mb-3">
            <label for="cablePackage" class="form-label">Select Package</label>
            <select class="form-control form-select" id="cablePackage" required>
              <option value="">Select a package</option>
            </select>
          </div>

          <!-- Phone Number -->
          <div class="form-group mb-3">
            <label for="cablePhone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="cablePhone" placeholder="e.g. 08012345678" required>
          </div>

          <!-- Submit -->
          <div class="form-group mt-4">
        <button type="submit" id="submitCableBtn" class="btn btn-primary btn-block" disabled>Purchase</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<style>
.provider-btn {
  border: 2px solid #dee2e6;
  background: #f8f9fa;
  border-radius: 12px;
  padding: 10px 6px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  transition: all .2s ease;
}

.provider-btn img {
  width: 36px;
  height: 36px;
}

.provider-btn.active {
  border-color: #0d6efd;
  background: #0d6efd;
  color: #fff;
}

.provider-btn.active img {
  filter: brightness(0) invert(1);
}
</style>
<script>
document.querySelectorAll('.provider-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.provider-btn')
      .forEach(b => b.classList.remove('active'));

    btn.classList.add('active');

    const select = document.getElementById('cableProvider');
    select.value = btn.dataset.value;
    select.dispatchEvent(new Event('change'));
  });
});
</script>

    
<script>
const phoneInput = document.getElementById('receiver_phone');
const nameInput = document.getElementById('receiver_name');
const infoBox = document.getElementById('receiver_info');
const submitBtn = document.getElementById('submitBtn');
const receiverIdInput = document.getElementById('receiver_id');

let checkTimeout = null;

// Real-time user check
phoneInput.addEventListener('input', function () {
  const phone = this.value.trim();

  clearTimeout(checkTimeout);
  nameInput.value = '';
  infoBox.textContent = '';
  receiverIdInput.value = '';
  submitBtn.disabled = true;

  if (phone.length >= 10) {
    infoBox.textContent = 'Checking...';

    checkTimeout = setTimeout(() => {
      fetch('fetch_user_by_phone.php?phone=' + encodeURIComponent(phone))
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            infoBox.textContent = 'Receiver: ' + data.name;
            nameInput.value = data.name;
            receiverIdInput.value = data.user_id;
            infoBox.classList.add('text-success');
            infoBox.classList.remove('text-danger');
            submitBtn.disabled = false;
          } else {
            infoBox.textContent = 'User not found';
            infoBox.classList.add('text-danger');
            infoBox.classList.remove('text-success');
            submitBtn.disabled = true;
          }
        });
    }, 500);
  } else {
    infoBox.textContent = 'Enter valid number';
    infoBox.classList.add('text-danger');
    submitBtn.disabled = true;
  }
});

// Submit P2P form with SweetAlert
document.getElementById('p2pForm').addEventListener('submit', function (e) {
  e.preventDefault();

  const formData = new FormData(this);
  submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Sending...';
  submitBtn.disabled = true;

  fetch('process_p2p.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
     Swal.fire({
  icon: 'success',
  title: 'Transfer Successful',
  text: data.message,
  confirmButtonColor: '#28a745'
}).then(() => {
  location.reload(); // ✅ reload the page after closing the alert
});

      this.reset();
      nameInput.value = '';
      receiverIdInput.value = '';
      infoBox.textContent = '';
      infoBox.classList.remove('text-success', 'text-danger');

      const modal = bootstrap.Modal.getInstance(document.getElementById('actionSheetP2p'));
      modal.hide();
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Transfer Failed',
        text: data.message,
        confirmButtonColor: '#dc3545'
      });
    }
  })
  .catch(() => {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'Something went wrong. Please try again.',
      confirmButtonColor: '#dc3545'
    });
  })
  .finally(() => {
    submitBtn.innerText = 'Send Now';
    submitBtn.disabled = true;
  });
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // Show the modal
    window.openCurrencySheet = function () {
        const modal = new bootstrap.Modal(document.getElementById('actionSheetCurrency'));
        modal.show();
    };

    // Convert currency
    window.convertCurrency = function () {
        const rawAmount = document.getElementById('convertAmount').value.trim();
        console.log("Raw input:", rawAmount);
        const amount = parseFloat(rawAmount);
        const from = document.getElementById('fromCurrency').value;
        const to = document.getElementById('toCurrency').value;

        const resultBox = document.getElementById('conversionResult');

        if (!rawAmount) {
            resultBox.innerHTML = "Amount is required.";
            return;
        }

        if (isNaN(amount) || amount <= 0) {
            resultBox.innerHTML = "Enter a valid amount.";
            return;
        }

        if (from === to) {
            resultBox.innerHTML = "Please select different currencies.";
            return;
        }

        // Fetch rate
        fetch('get_rate.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const converted = (amount * data.rate).toFixed(2);
                resultBox.innerHTML =
                    `${amount} ${from} = <span class="text-success">${converted} ${to}</span><br><small>Rate: 1 ${from} = ${data.rate} ${to}</small>`;
            } else {
                resultBox.innerHTML = data.message;
            }
        })
        .catch(() => {
            resultBox.innerHTML = "An error occurred. Please try again later.";
        });
    };

});
</script>


<script>
document.getElementById('airtimeForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const form = e.target;
  const network = form.network.value;
  const number = form.mobileNumber.value;
  const amount = form.amount.value;

  document.getElementById('confirmNetwork').textContent = network;
  document.getElementById('confirmNumber').textContent = number;
  document.getElementById('confirmAmount').textContent = amount;

  const airtimeModal = bootstrap.Modal.getInstance(document.getElementById('airtimeModal'));
  airtimeModal.hide();

  const confirmModal = new bootstrap.Modal(document.getElementById('airtimeConfirmModal'));
  confirmModal.show();
});

document.getElementById('confirmAirtimeBtn').addEventListener('click', function () {
  const confirmBtn = this;
  const spinner = document.getElementById('airtimeSpinner');

  const network = document.getElementById('confirmNetwork').textContent;
  const number = document.getElementById('confirmNumber').textContent;
  const amount = document.getElementById('confirmAmount').textContent;

  // Show spinner and disable button
  confirmBtn.disabled = true;
  spinner.classList.remove('d-none');

  fetch('airtime_handler.php?action=purchaseAirtime', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({ network, mobileNumber: number, amount })
  })
  .then(res => res.json())
  .then(data => {
    // Hide spinner and re-enable button
    spinner.classList.add('d-none');
    confirmBtn.disabled = false;

    if (data.status === 'success') {
      window.location.href = data.redirect;
    } else {
      alert(data.error || 'Something went wrong');
    }
  })
  .catch(err => {
    spinner.classList.add('d-none');
    confirmBtn.disabled = false;
    alert('Error: ' + err.message);
  });
});

</script>


<script>
    
(() => {
  const buyDataSheet = new bootstrap.Modal(document.getElementById('buyDataSheet'));
  const confirmBuyDataSheet = new bootstrap.Modal(document.getElementById('confirmBuyDataSheet'));
  const errorSheet = new bootstrap.Modal(document.getElementById('errorSheet'));

  const buyDataForm = document.getElementById('buyDataForm');
  const networkBtns = document.querySelectorAll('.network-btn-circle');
  const planSelect = document.getElementById('planSelect');
  const mobileInput = document.getElementById('mobileInput');
  const purchaseBtn = document.getElementById('purchaseBtn');
  const statusMsg = document.getElementById('statusMsg');

  const confirmNetwork = document.getElementById('confirmNetwork');
  const confirmPlan = document.getElementById('confirmPlan');
  const confirmMobile = document.getElementById('confirmMobile');
  const confirmAmount = document.getElementById('confirmAmount');
  const confirmPurchaseBtn = document.getElementById('confirmPurchaseBtn');
  const errorMessage = document.getElementById('errorMessage');

  const networkPatterns = {
    mtn: /^(0803|0806|0703|0706|0810|0813|0814|0816|0903|0906|0913|0916)/,
    glo: /^(0805|0807|0811|0815|0905|0915)/,
    airtel: /^(0802|0808|0708|0812|0701|0902|0907|0912)/,
    '9mobile': /^(0809|0817|0818|0909|0908|0918)/
  };

  // Select network button click
  networkBtns.forEach(btn => {
    btn.addEventListener('click', async () => {
      networkBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const network = btn.dataset.network;
      await loadPlans(network);
      togglePurchaseBtn();
    });
  });

  // Auto-detect network
  mobileInput.addEventListener('input', async () => {
    const number = mobileInput.value.replace(/\D/g, '');
    let detected = null;
    for (const [network, pattern] of Object.entries(networkPatterns)) {
      if (pattern.test(number)) detected = network;
    }
    if (detected) {
      networkBtns.forEach(b => b.classList.remove('active'));
      const btn = document.querySelector(`.network-btn-circle[data-network="${detected}"]`);
      if (btn) btn.classList.add('active');
      await loadPlans(detected);
    }
    togglePurchaseBtn();
  });

  async function loadPlans(network) {
    planSelect.innerHTML = '<option>Loading...</option>';
    planSelect.disabled = true;
    purchaseBtn.disabled = true;

    try {
      const res = await fetch(`process_data.php?action=getPlans&network=${network}`);
      const data = await res.json();

      if (data.error) {
        planSelect.innerHTML = `<option disabled>${data.error}</option>`;
        return;
      }

      planSelect.innerHTML = '<option value="">-- Select Data Plan --</option>';
      data.plans.forEach(plan => {
        const option = document.createElement('option');
        option.value = plan.code;
        option.textContent = `${plan.description} - ₦${plan.price}`;
        option.dataset.price = plan.price;
        option.dataset.description = plan.description;
        planSelect.appendChild(option);
      });
      planSelect.disabled = false;
    } catch {
      planSelect.innerHTML = '<option disabled>Error loading plans</option>';
    }
  }

  function togglePurchaseBtn() {
    const activeNetwork = document.querySelector('.network-btn-circle.active');
    const network = activeNetwork ? activeNetwork.dataset.network : null;
    const plan = planSelect.value;
    const mobileValid = /^0\d{10}$/.test(mobileInput.value.trim());
    purchaseBtn.disabled = !(network && plan && mobileValid);
  }

  planSelect.addEventListener('change', togglePurchaseBtn);

  // Show confirmation
  buyDataForm.addEventListener('submit', e => {
    e.preventDefault();
    const selected = planSelect.selectedOptions[0];
    const activeNetwork = document.querySelector('.network-btn-circle.active');

    confirmNetwork.textContent = activeNetwork.querySelector('.network-name').textContent;
    confirmNetworkIcon.src = `icons/${activeNetwork.dataset.network}.svg`;
    confirmPlan.textContent = selected.dataset.description;
    confirmMobile.textContent = mobileInput.value.trim();
    confirmAmount.textContent = selected.dataset.price;

    buyDataSheet.hide();
    confirmBuyDataSheet.show();
  });

  // Final purchase
  confirmPurchaseBtn.addEventListener('click', async () => {
    confirmPurchaseBtn.disabled = true;
    confirmPurchaseBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Processing...`;

    const selected = planSelect.selectedOptions[0];
    const activeNetwork = document.querySelector('.network-btn-circle.active');
    const payload = {
      network: activeNetwork.dataset.network,
      dataPlanCode: planSelect.value,
      planName: selected.dataset.description,
      amount: selected.dataset.price,
      mobileNumber: mobileInput.value.trim()
    };

    try {
      const res = await fetch('process_data.php?action=purchaseData', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const result = await res.json();
      if (result.status === 'success') {
        confirmBuyDataSheet.hide();
        alert('🎉 Data purchase successful!');
        if (result.redirect) window.location.href = result.redirect;
      } else {
        showError(result.error || 'Transaction failed.');
      }
    } catch {
      showError('❌ Network error. Please try again.');
    } finally {
      confirmPurchaseBtn.disabled = false;
      confirmPurchaseBtn.innerHTML = 'Confirm & Purchase';
    }
  });

  function showError(msg) {
    errorMessage.textContent = msg;
    confirmBuyDataSheet.hide();
    errorSheet.show();
  }

  window.openBuyDataSheet = () => buyDataSheet.show();
})();

</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const providerSelect = document.getElementById('cableProvider');
  const packageSelect = document.getElementById('cablePackage');
  const smartcardInput = document.getElementById('smartcard');
  const smartcardName = document.getElementById('smartcardName');
  const purchaseBtn = document.getElementById('submitCableBtn');
  let isSmartcardValid = false;

  purchaseBtn.disabled = true; // default disabled

  providerSelect.addEventListener('change', function () {
    const provider = this.value;
    packageSelect.innerHTML = '<option value="">Loading packages...</option>';

    fetch(`load_packages.php?provider=${provider}`)
      .then(res => res.json())
      .then(data => {
        packageSelect.innerHTML = '<option value="">Select a package</option>';
        data.forEach(pkg => {
          const option = document.createElement('option');
          option.value = pkg.code;
          option.textContent = `${pkg.name} - ₦${Number(pkg.price).toLocaleString()}`;
          packageSelect.appendChild(option);
        });
      });

    // Reset everything on provider change
    smartcardName.textContent = '';
    isSmartcardValid = false;
    purchaseBtn.disabled = true;
  });

  smartcardInput.addEventListener('blur', function () {
    const provider = providerSelect.value;
    const smartcard = this.value.trim();

    smartcardName.textContent = '';
    isSmartcardValid = false;
    purchaseBtn.disabled = true;

    if (provider && smartcard.length >= 6) {
      smartcardName.textContent = 'Verifying...';

      fetch(`verify_smartcard.php?provider=${encodeURIComponent(provider)}&smartcard=${encodeURIComponent(smartcard)}`)
        .then(res => res.json())
        .then(data => {
          const name = typeof data.name === 'string' ? data.name.trim() : '';

          if (data.success && name.length > 2) {
            smartcardName.textContent = name;
            isSmartcardValid = true;
            purchaseBtn.disabled = false;
          } else {
            smartcardName.textContent = 'Invalid smartcard';
            isSmartcardValid = false;
            purchaseBtn.disabled = true;
          }
        })
        .catch(() => {
          smartcardName.textContent = 'Verification failed';
          isSmartcardValid = false;
          purchaseBtn.disabled = true;
        });
    }
  });

  document.getElementById('cableTVForm').addEventListener('submit', function (e) {
    e.preventDefault();

    if (!isSmartcardValid) {
      alert('Smartcard is not valid. Please verify it first.');
      return;
    }

    const formData = {
      provider: providerSelect.value,
      smartcard: smartcardInput.value,
      package: packageSelect.value,
      phone: document.getElementById('cablePhone').value
    };

    fetch('submit_cable_purchase.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(response => {
      if (response.success) {
        alert('Subscription successful');
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalCableTV'));
        modal.hide();
      } else {
        alert('Purchase failed: ' + response.message);
      }
    });
  });
});


</script>

<!-- Electricity Bill Modal (Full Page) -->
<div class="modal fade action-sheet show" id="electricityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content bg-background-light dark:bg-background-dark rounded-0 shadow-lg flex flex-col h-full">

      <!-- HEADER -->
      <div class="modal-header border-0 px-4 py-3 sticky top-0 bg-white dark:bg-background-dark z-10 flex items-center">
        <button type="button" class="btn-close me-3" onclick="closeElectricityModal()" aria-label="Close">
          <i class="bi bi-x-lg"></i>
        </button>
        <h5 class="modal-title text-center fw-bold flex-1 text-primary dark:text-white">Pay Electricity</h5>
        <div class="w-8"></div>
      </div>

      <!-- BODY -->
      <div class="modal-body flex-1 overflow-y-auto p-4">

        <!-- Network Selection -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Select Provider</label>
          <div class="d-flex justify-content-between gap-2 flex-wrap">
            <button type="button" class="network-btn-circle" data-network="eko">
              <i class="bi bi-lightning-charge-fill network-icon"></i>
              <span class="network-name">EKEDC</span>
            </button>
            <button type="button" class="network-btn-circle" data-network="ibadan">
              <i class="bi bi-lightning-charge-fill network-icon"></i>
              <span class="network-name">IBEDC</span>
            </button>
            <button type="button" class="network-btn-circle" data-network="ikeja">
              <i class="bi bi-lightning-charge-fill network-icon"></i>
              <span class="network-name">IKEDC</span>
            </button>
            <button type="button" class="network-btn-circle" data-network="jos">
              <i class="bi bi-lightning-charge-fill network-icon"></i>
              <span class="network-name">JEDC</span>
            </button>
          </div>
          <input type="hidden" name="network" id="selectedElectricityNetwork" required>
        </div>

        <!-- Units / Amount Selection -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Select Amount</label>
          <div class="amount-grid" id="amountGrid"></div>
          <input type="hidden" name="amount" id="selectedAmount" required>
        </div>

        <!-- Customer Number -->
        <div class="mb-4">
          <label class="fw-semibold mb-2">Customer Number</label>
          <input type="tel" class="form-control" id="customerNumber" placeholder="e.g. 1234567890" maxlength="12" required>
        </div>

        <!-- Status / Error Message -->
        <div id="statusMsg" class="text-center text-danger small mb-3"></div>

        <!-- Continue Button -->
        <div class="d-grid mb-4">
          <button type="submit" id="electricityContinueBtn" class="btn btn-primary rounded-pill" disabled>
            Continue
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Confirm Electricity Payment Modal (Full Page) -->
<div class="modal fade action-sheet show" id="confirmElectricityModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content bg-background-light dark:bg-background-dark rounded-0 shadow-lg flex flex-col h-full text-center">

      <!-- HEADER -->
      <div class="modal-header border-0 px-4 py-3 sticky top-0 bg-white dark:bg-background-dark z-10 flex items-center justify-center">
        <h5 class="modal-title text-primary fw-bold">Confirm Payment</h5>
      </div>

      <!-- BODY -->
      <div class="modal-body flex-1 overflow-y-auto p-4">

        <div class="card border-0 shadow-sm mb-3">
          <div class="card-body text-start d-flex align-items-center gap-3">
            <div class="network-icon" style="width:50px;height:50px;">
              <i class="bi bi-lightning-charge-fill fs-3" id="confirmElectricityIcon"></i>
            </div>
            <div>
              <p class="mb-1"><span class="text-muted">Provider:</span> <strong id="confirmElectricityNetwork"></strong></p>
              <p class="mb-1"><span class="text-muted">Amount:</span> <strong id="confirmElectricityAmount"></strong></p>
              <p class="mb-0"><span class="text-muted">Customer No:</span> <strong id="confirmCustomerNumber"></strong></p>
            </div>
          </div>
        </div>

        <div class="d-grid gap-2">
          <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-primary rounded-pill" id="confirmElectricityBtn">
            Confirm & Pay
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<style>
/* Network buttons */
.network-btn-circle {
  background: #f8f9fa;
  border: 2px solid #dee2e6;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.2s;
  margin-bottom: 0.5rem;
}
.network-btn-circle:hover {
  transform: translateY(-2px);
  border-color: #0d6efd;
}
.network-btn-circle.active {
  border-color: #0d6efd;
  background-color: #0d6efd;
  color: #fff;
}
.network-icon { font-size: 24px; margin-bottom: 4px; }
.network-name { font-size: 12px; text-align: center; }

/* Amount Grid */
.amount-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
  gap: 0.5rem;
}
.amount-block {
  background: #fff;
  border: 1px solid #dee2e6;
  border-radius: 1rem;
  padding: 0.5rem;
  text-align: center;
  cursor: pointer;
  font-weight: 600;
  transition: all 0.2s;
}
.amount-block:hover, .amount-block.active {
  background-color: #0d6efd;
  color: #fff;
  border-color: #0d6efd;
}
</style>

<script>
// Populate amount grid dynamically
const amounts = [1000, 2000, 5000, 10000, 20000];
const amountGrid = document.getElementById('amountGrid');

amounts.forEach(val => {
  const div = document.createElement('div');
  div.classList.add('amount-block');
  div.textContent = `₦${val.toLocaleString()}`;
  div.addEventListener('click', () => {
    document.querySelectorAll('.amount-block').forEach(b => b.classList.remove('active'));
    div.classList.add('active');
    document.getElementById('selectedAmount').value = val;
    document.getElementById('electricityContinueBtn').disabled = !document.getElementById('selectedElectricityNetwork').value;
  });
  amountGrid.appendChild(div);
});

// Network selection
document.querySelectorAll('#electricityModal .network-btn-circle').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('#electricityModal .network-btn-circle').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('selectedElectricityNetwork').value = btn.dataset.network;
    document.getElementById('electricityContinueBtn').disabled = !document.getElementById('selectedAmount').value;
  });
});

// Close function
function closeElectricityModal() {
  document.getElementById('electricityModal').classList.remove('show');
  document.getElementById('electricityModal').classList.add('hidden');
}
</script>


<?php include 'footer.php'; ?>
</body>
</html>

