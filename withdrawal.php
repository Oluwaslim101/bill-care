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
 
$full_name = $user['full_name'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';    
$balance = number_format($user['balance'], 2);    

    
// Fetch unread notifications    
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";    
$notifications_stmt = $sql->prepare($notifications_query);    
$notifications_stmt->execute([$user_id]);    
$unread_count = $notifications_stmt->rowCount();    
    
    
    
// Function to fetch and sort banks    
function getBanks()    
{    
    $url = "https://api.flutterwave.com/v3/banks/NG";    
    $curl = curl_init();    
    curl_setopt_array($curl, [    
        CURLOPT_URL => $url,    
        CURLOPT_RETURNTRANSFER => true,    
        CURLOPT_HTTPHEADER => [    
            "Authorization: Bearer " . FLW_SECRET_KEY,    
            "Content-Type: application/json"    
        ],    
    ]);    
    $response = curl_exec($curl);    
    curl_close($curl);    
    
    $data = json_decode($response, true);    
    
    if (isset($data['status']) && $data['status'] === "success") {    
        $banks = $data['data'];    
        usort($banks, fn($a, $b) => strcmp($a['name'], $b['name']));    
        return $banks;    
    }    
    
    return [];    
}    
    
$bankList = getBanks();    
    
// Generate CSRF token    
if (!isset($_SESSION['csrf_token'])) {    
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));    
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
    <title>YenTown Hub</title>
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
    
.btn-block {
    display: block;
    width: 100%;
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

<script>
$(document).ready(function () {
  // Bootstrap modals
  const modals = {
    verification: new bootstrap.Modal(document.getElementById("verificationSheet")),
    pin: new bootstrap.Modal(document.getElementById("pinSheet")),
    error: new bootstrap.Modal(document.getElementById("errorSheet")),
    withdrawForm: new bootstrap.Modal(document.getElementById("withdrawFormSheet"))
  };

  // Fetch account details on input
  $("#account_number, #bank_name").on("input change", function () {
    const accountNumber = $("#account_number").val().trim();
    const selectedBank = $("#bank_name").find(":selected");
    const bankCode = selectedBank.val();
    const bankName = selectedBank.data("bank-name") || "";

    $("#bank_code").val(bankCode);
    $("#bank_name_hidden").val(bankName);

    if (accountNumber.length === 10 && bankCode) {
      fetchAccountDetails(accountNumber, bankCode);
    } else {
      resetAccountName();
    }
  });

  function fetchAccountDetails(accountNumber, bankCode) {
    $("#account_name").text("Fetching...");
    $("#account_name_box").show();

    fetch("fetch_account.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ account_number: accountNumber, account_bank: bankCode })
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        $("#account_name").text(data.account_name);
        $("#account_name_hidden").val(data.account_name);
      } else {
        $("#account_name").text("Invalid account");
        $("#account_name_hidden").val("");
      }
    })
    .catch(() => {
      $("#account_name").text("Error fetching details");
      $("#account_name_hidden").val("");
    });
  }

  function resetAccountName() {
    $("#account_name").text("-");
    $("#account_name_hidden").val("");
    $("#account_name_box").hide();
  }

  // ✅ Step 1: Withdraw → Verification
  window.openVerificationSheet = function () {
    const amount = $("#amount").val().trim();
    const accountName = $("#account_name").text().trim();
    const accountNumber = $("#account_number").val().trim();
    const bankName = $("#bank_name option:selected").data("bank-name");

    if (!amount || parseFloat(amount) <= 0) return showError("Enter a valid amount.");
    if (!accountNumber || accountNumber.length !== 10 || !accountName || accountName.includes("Invalid")) {
      return showError("Invalid account details.");
    }

    $("#verify_amount_text").text(parseFloat(amount).toFixed(2));
    $("#verify_account_name_text").text(accountName);
    $("#verify_account_number").text(accountNumber);
    $("#verify_bank_name").text(bankName);

    modals.withdrawForm.hide();
    modals.verification.show();
  };

  // ✅ Step 2: Confirm → PIN Sheet
  window.openPinSheet = function () {
    modals.verification.hide();
    modals.pin.show();
  };

  // ✅ Step 3: Submit withdrawal
  window.submitWithdrawal = function () {
    const pin = $("#pin").val().trim();
    if (!pin || pin.length !== 4 || isNaN(pin)) return showError("Enter a valid 4-digit PIN.");
    $("#pin_hidden").val(pin);

    const submitBtn = $("#pinSheet .btn-primary");
    submitBtn.html(`<span class="spinner-border spinner-border-sm"></span> Sending...`).prop("disabled", true);

    $.ajax({
      url: "process_withdrawal.php",
      type: "POST",
      data: $("#withdrawForm").serialize(),
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          window.location.href = "withdrawal_receipt.php?transaction_ref=" + encodeURIComponent(res.transaction_ref);
        } else {
          showError(res.message || "Failed to process withdrawal.");
        }
      },
      error: function () {
        showError("Unexpected error occurred.");
      },
      complete: function () {
        submitBtn.html("Submit").prop("disabled", false);
      }
    });
  };

  // ✅ Error handler
  function showError(msg) {
    $("#errorMessage").text(msg);
    modals.verification.hide();
    modals.pin.hide();
    modals.withdrawForm.hide();
    modals.error.show();
  }
});
</script>
</head>
<body>

<!-- Loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>

 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Instant Withdrawal
    </div>
    <div class="right">
        <div style="position: relative;">
            <i class="fas fa-bell" style="font-size: 25px; color: blue;"></i>
            <?php if ($unread_count > 0): ?>
                <span style="
                    position: absolute;
                    top: -5px;
                    right: -5px;
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


<div id="appCapsule">

    <div class="section full">
        <div class="section-title">Kindly Fill Your Recipient Accout</div>

        <div class="wide-block pb-1">

<!-- ✅ Withdrawal Action Sheet -->
<div class="modal fade action-sheet" id="withdrawFormSheet" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="action-sheet-content">
          <h3 class="text-center mt-2">Instant Withdrawal</h3>
          <form id="withdrawForm" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <input type="hidden" id="bank_code" name="bank_code">
            <input type="hidden" id="bank_name_hidden" name="bank_name_hidden">
            <input type="hidden" id="account_name_hidden" name="account_name_hidden">
            <input type="hidden" id="pin_hidden" name="pin">

            <div class="form-group">
              <label>Account Number</label>
              <input type="text" id="account_number" name="account_number" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Select Bank</label>
              <select id="bank_name" name="bank_name" class="form-control" required>
                <option value="">-- Select Bank --</option>
                <?php foreach ($bankList as $bank): ?>
                  <option value="<?= $bank['code'] ?>" data-bank-name="<?= $bank['name'] ?>"><?= $bank['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="form-group">
              <label>Account Name</label>
              <div class="form-control text-muted" id="account_name_box" style="min-height:45px">
                <span id="account_name">-</span>
              </div>
            </div>

            <div class="form-group">
              <label>Amount (NGN)</label>
              <input type="number" id="amount" name="amount" class="form-control" required>
            </div>

            <div class="form-group">
              <label>Narration</label>
              <input type="text" id="narration" name="narration" class="form-control" required>
            </div>

            <div class="form-group mt-3">
              <button type="button" class="btn btn-primary w-100" onclick="openVerificationSheet()">Withdraw</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Verification Sheet -->
<div class="modal fade action-sheet" id="verificationSheet" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="action-sheet-content text-center">
          <h4>Confirm Withdrawal</h4>
          <p>You are sending <strong><span id="verify_amount_text"></span> NGN</strong> to <span id="verify_account_name_text"></span>.</p>
          <p><strong>Bank:</strong> <span id="verify_bank_name"></span></p>
          <p><strong>Account Number:</strong> <span id="verify_account_number"></span></p>

          <div class="action-sheet-grouped mt-3">
            <button type="button" class="btn btn-outline-secondary btn-block" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary btn-block" onclick="openPinSheet()">Confirm</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ PIN Entry Sheet -->
<div class="modal fade action-sheet" id="pinSheet" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="action-sheet-content text-center">
          <h4>Enter Transaction PIN</h4>
          <p>Please input your 4-digit PIN</p>
          <input type="password" class="form-control text-center mb-3" id="pin" maxlength="4" inputmode="numeric" required>

          <div class="action-sheet-grouped">
            <button type="button" class="btn btn-outline-secondary btn-block" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary btn-block" onclick="submitWithdrawal()">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Error Sheet -->
<div class="modal fade action-sheet" id="errorSheet" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-bottom" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div class="action-sheet-content text-center">
          <h5>Error</h5>
          <p id="errorMessage"></p>
          <button class="btn btn-danger btn-block mt-2" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<!-- Bottom Navigation -->
<nav class="nav">   
<a href="index.php" class="active">    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="contracts.php">    
    <i class="fas fa-receipt"></i>    
    <span>Contracts</span>    
</a>   

<a href="transactions.php">    
    <i class="fas fa-receipt"></i>    
    <span>Transactions</span>    
</a>    
<a href="profile.php">    
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