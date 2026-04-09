<?php
// Debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB and session
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit();
}

$full_name = $user['full_name'];
$balance = $user['balance'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';

// Fetch unread notifications
$notifications_stmt = $sql->prepare("SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'");
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

// ---------------------
// ✅ Termii SMS Logic
// ---------------------
$sms_feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sender_id'], $_POST['recipients'], $_POST['message'])) {

    $api_key = "TLZugUiorQTxeDaIyTOScmNBcoTnuzYFKQiPHHSptRcwLsNTdzwRFDHVWptPOm";
    $api_url = "https://api.ng.termii.com/api/sms/send";

    $sender_id = $_POST['sender_id'];
    $message = $_POST['message'];

    // Format recipients
    $raw_recipients = $_POST['recipients'];
    $numbers = explode(',', $raw_recipients);
    $clean_numbers = [];

    foreach ($numbers as $number) {
        $number = preg_replace('/\D/', '', trim($number)); // Strip non-digits

        if (preg_match('/^0\d{10}$/', $number)) {
            $number = '234' . substr($number, 1);
        }

        if (preg_match('/^234\d{10}$/', $number)) {
            $clean_numbers[] = $number;
        }
    }

    if (empty($clean_numbers)) {
        $sms_feedback = "error|No valid Nigerian numbers found.";
    } else {
        $recipients = implode(',', $clean_numbers);

        // Prepare API payload
        $data = [
            "api_key" => $api_key,
            "to" => $recipients,
            "from" => $sender_id,
            "sms" => $message,
            "type" => "plain",
            "channel" => "generic"
        ];

        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);
        $message_id = $result['message_id'] ?? null;
        $status = $result['message'] ?? 'Failed';
        $api_response = json_encode($result);
        $balance_remaining = $result['balance'] ?? null;

        // Log to DB
        $insertLog = "INSERT INTO sms_logs (user_id, sender_id, recipients, message, message_id, status, api_response, balance_remaining)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $logStmt = $sql->prepare($insertLog);
        $logStmt->execute([
            $user_id,
            $sender_id,
            $recipients,
            $message,
            $message_id,
            $status,
            $api_response,
            $balance_remaining
        ]);

     if ($http_code == 200 && isset($result["code"]) && $result["code"] === "ok") {
    $total_cost = count($clean_numbers) * ceil(strlen($message) / 160) * 7;

    // Deduct balance
    $deduct_stmt = $sql->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
    $deduct_stmt->execute([$total_cost, $user_id]);

    // Insert notification
    $notif = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at)
        VALUES (?, ?, ?, ?, NOW())");
    $notif->execute([
        $user_id,
        'sms_sent',
        "Bulk SMS sent to " . count($clean_numbers) . " recipient(s). ₦{$total_cost} deducted.",
        'unread'
    ]);

    $sms_feedback = "success|" . $result["message"];
} else {
    $sms_feedback = "error|" . ($result["message"] ?? "SMS sending failed.");
}


    }
}
?>

<!DOCTYPE html> 

<html lang="en">    

<head> 
<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" /> 
<meta name="apple-mobile-web-app-capable" content="yes" /> 
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> <meta name="theme-color" content="#000000">
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
    
<!-- SweetAlert Feedback -->
<?php if (!empty($sms_feedback)): 
    list($type, $text) = explode('|', $sms_feedback); ?>
<script>
Swal.fire({
    icon: '<?= $type ?>',
    title: '<?= ucfirst($type) === "Success" ? "Message Sent" : "Failed" ?>',
    text: '<?= $text ?>',
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>


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
    <div class="pageTitle">
     <h3> Bulk SMS</h3>
    </div>
   <div class="right">
    <div style="position: relative; padding-right: 12px;" onclick="showCartModal()">
        <i class="fas fa-receipt fa-lg"></i>
        <span id="cartCountBadge"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="right: -6px;">
            0
        </span>
    </div>
</div>
</div>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section mt-1 mb-2">
        <div class="card">
            <div class="card-body">

                <form method="POST"><div class="form-group boxed">
    <div class="input-wrapper">
        <label class="form-label">Sender ID</label>
        <input type="text" class="form-control text-muted" value="DtheHubb" readonly disabled>
        <input type="hidden" name="sender_id" value="DtheHubb">
    </div>
</div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="form-label" for="recipients">Recipients <small>(comma-separated)</small></label>
                            <textarea class="form-control" id="recipients" name="recipients" required></textarea>
                        </div>
                    </div>

                    <div class="form-group boxed">
                        <div class="input-wrapper">
                            <label class="form-label" for="message">Message</label>
                            <textarea class="form-control" id="message" name="message" required></textarea>
                        </div>
                    </div>

                    <div class="mt-3">
                     <button type="button" class="btn btn-primary btn-block" onclick="previewSMS()">Send SMS</button>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade action-sheet" id="confirmSendModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="action-sheet-content">
        <h5 class="text-center">Confirm SMS Details</h5>
        <ul class="listview flush transparent simple-listview">
          <li><strong>Sender ID:</strong> <span id="previewSenderId">DtheHubb</span></li>
          <li><strong>Recipients:</strong> <span id="previewRecipientsCount">0</span></li>
          <li><strong>Pages:</strong> <span id="previewPages">1</span></li>
          <li><strong>Estimated Cost:</strong> ₦<span id="previewAmount">0</span></li>
        </ul>
        <div class="action-sheet-grouped">
          <button id="finalSubmitBtn" class="btn btn-primary btn-block">Confirm & Send</button>
          <button class="btn btn-text btn-block text-danger" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Sender ID Request Modal (FinApp Bottom Sheet Style) -->
<div class="modal fade action-sheet" id="senderIdModal" tabindex="-1" aria-labelledby="senderIdModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="action-sheet-content">
        <h5 class="text-center mb-3 text-primary">
          <i class="fas fa-user-tag"></i> Request New Sender ID
        </h5>

        <form action="request_sender_id.php" method="POST" id="senderIdForm">

          <!-- Preferred Sender ID -->
          <div class="form-group boxed">
            <div class="input-wrapper">
              <label class="form-label">Preferred Sender ID</label>
              <input type="text" class="form-control" name="sender_id" maxlength="11" required placeholder="e.g. DtheHub">
              <small class="text-muted">Max 11 characters, no spaces or special symbols.</small>
            </div>
          </div>

          <!-- Purpose -->
          <div class="form-group boxed mt-3">
            <div class="input-wrapper">
              <label class="form-label">Purpose</label>
              <textarea class="form-control" name="purpose" rows="3" placeholder="What is the purpose for this Sender ID?" required></textarea>
            </div>
          </div>

          <p class="text-muted mt-2 mb-3 text-center">
            NB: Sender ID registration is approved only on weekdays. It takes 24-48 hours for Nigeria.
          </p>

          <!-- Buttons -->
          <div class="action-sheet-grouped">
            <button type="submit" class="btn btn-primary btn-block">Submit Request</button>
        <button type="button" class="btn btn-text btn-block text-danger" onclick="closeSenderModal()">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


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
                            <a href="index.php" class="item">
                                <div class="icon-box bg-primary">
                               <ion-icon name="storefront-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Home Dashboard
                                   
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="bulksms.php" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="receipt-outline"></ion-icon>
                                </div>
                                <div class="in">
                                   Send SMS
                                </div>
                            </a>
                        </li>
                        
                         <li>
                            <a href="bulksms_history.php" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="receipt-outline"></ion-icon>
                                </div>
                                <div class="in">
                                   SMS History
                                </div>
                            </a>
                        </li>
                          <li>
                          <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#senderIdModal">
                                <div class="icon-box bg-primary">
                                 <ion-icon name="key-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Request New Sender ID
                                   
                                </div>
                            </a>
                        </li>
                         <li>
    <a href="sender_id_history.php" class="item">
        <div class="icon-box bg-primary">
            <ion-icon name="document-outline"></ion-icon>
        </div>
        <div class="in">
            Sender ID History
        </div>
    </a>
</li>

                       

                    <!-- others -->
                    <div class="listview-title mt-1">Others</div>
                    <ul class="listview flush transparent no-line image-listview">
                       <li>
                            <a href="support.php" class="item">
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

<?php include 'footer.php'; ?>

<?php if (isset($_GET['sender_request']) && $_GET['sender_request'] === 'success'): ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
      Swal.fire({
    icon: 'success',
    title: 'Sender ID Requested!',
    text: 'Your Sender ID request was submitted successfully.',
    confirmButtonColor: '#3085d6'
}).then(() => {
    closeSenderModal(); // Ensure modal cleaned up
});

    });
</script>
<?php endif; ?>

<script>
function previewSMS() {
    const message = document.getElementById('message').value.trim();
    const recipients = document.getElementById('recipients').value.trim();
    const senderId = document.querySelector('input[name="sender_id"]').value;

    if (!message || !recipients) {
        Swal.fire("Missing fields", "Please fill in both recipients and message.", "warning");
        return;
    }

    const recipientsArr = recipients.split(',').filter(n => n.trim() !== '');
    const pages = Math.ceil(message.length / 160);
    const costPerPage = 7; // e.g. ₦7 per page
    const totalCost = recipientsArr.length * pages * costPerPage;

    // Show modal preview
    document.getElementById('previewSenderId').innerText = senderId;
    document.getElementById('previewRecipientsCount').innerText = recipientsArr.length;
    document.getElementById('previewPages').innerText = pages;
    document.getElementById('previewAmount').innerText = totalCost;

    // Check balance via AJAX
    fetch('check_balance.php')
    .then(res => res.json())
    .then(data => {
        if (data.balance !== undefined) {
            if (parseFloat(data.balance) >= totalCost) {
                document.getElementById('finalSubmitBtn').disabled = false;
                new bootstrap.Modal(document.getElementById('confirmSendModal')).show();
            } else {
                Swal.fire("Insufficient Balance", `You need ₦${totalCost} but have only ₦${data.balance}`, "error");
            }
        } else {
            Swal.fire("Error", data.error || "Unable to fetch balance", "error");
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire("Error", "Something went wrong while checking balance.", "error");
    });
}

// Submit when confirmed
document.getElementById('finalSubmitBtn').addEventListener('click', function () {
    document.querySelector('form').submit();
});

function closeSenderModal() {
    const senderModal = document.getElementById('senderIdModal');
    const modalInstance = bootstrap.Modal.getInstance(senderModal);
    if (modalInstance) {
        modalInstance.hide();
    }

    // Force cleanup of stuck backdrop
    setTimeout(() => {
        document.body.classList.remove('modal-open');
        document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());
    }, 300); // Wait for modal fade-out
}

</script>


</body>
</html>
