<?php
// Enable error reporting
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

$user_id = $_SESSION['user_id'];

// Fetch user data
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}

// Get transaction reference
$ref = $_GET['reference'] ?? null;
if (!$ref) die('No reference provided.');

// Fetch transaction
$stmt = $sql->prepare("SELECT * FROM virtual_account_topups WHERE reference = ?");
$stmt->execute([$ref]);
$txn = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$txn) die('Transaction not found.');

// Format values
$amount = number_format($txn['amount'], 2); // if amount is stored in kobo
$paid_at = date('M d, Y h:i A', strtotime($txn['paid_at']));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="theme-color" content="#000000">
    <title>Transaction Receipt</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
</head>
<body class="bg-white">
 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Transaction Receipt</div>
    <div class="right">
        <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#DialogBasic">
            <ion-icon name="receipt-outline"></ion-icon>
        </a>
    </div>
</div>


<div id="appCapsule">
    <div class="section mt-2 mb-2">

        <div class="listed-detail text-center mt-1">
            <div class="icon-wrapper">
                <div class="iconbox bg-success">
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </div>
            </div>
            <h3 class="text-success mt-2">Successful</h3>
        </div>

        <ul class="listview flush transparent simple-listview no-space mt-3">
            <li><strong>Transaction Type</strong><span>Wallet Deposit</span></li>
            <li><strong>Amount</strong><span>&#8358;<?= $amount ?></span></li>
            <li><strong>Reference</strong><span><?= htmlspecialchars($txn['reference']) ?></span></li>
            <li><strong>Date</strong><span><?= $paid_at ?></span></li>
            <li><strong>Sender Name</strong><span><?= htmlspecialchars($txn['sender_name']) ?></span></li>
            <li><strong>Sender Bank</strong><span><?= htmlspecialchars($txn['sender_bank']) ?> (<?= $txn['sender_account'] ?>)</span></li>
            <li><strong>Receiver Bank</strong><span><?= htmlspecialchars($txn['receiver_bank']) ?> (<?= $txn['receiver_account'] ?>)</span></li>
            <li><strong>Receiver</strong><span><?= htmlspecialchars($txn['customer_name']) ?> (<?= $txn['customer_email'] ?>)</span></li>
        </ul>
   
<div class="mt-3">
    <div class="row g-2 text-center">
        <div class="col-4 col-md-3">
            <a href="index.php" class="btn btn-primary w-100">🏠 Dashboard</a>
        </div>
        <div class="col-4 col-md-3">
<button id="downloadPdfBtn" class="btn btn-outline-danger w-100">  📄 Get PDF</button>
</div>
        <div class="col-4 col-md-3">
            <button onclick="shareReceiptAsImage()" class="btn btn-outline-success w-100">🖼️ Share Image</button>
        </div>
    </div>
  
<!-- Info Modal -->
<div class="modal fade dialogbox" id="DialogBasic" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Receipt Info</h5></div>
            <div class="modal-body">You can screenshot this receipt for your records.</div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-bs-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('downloadPdfBtn').addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.innerText = '⏳ Preparing PDF...';

    Swal.fire({
        icon: 'info',
        title: 'Please wait',
        text: 'Generating your receipt...',
        showConfirmButton: false,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    // Start the download via hidden iframe (same-tab compatible)
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = 'generate_receipt_pdf.php?reference=<?= $txn['reference'] ?>';
    document.body.appendChild(iframe);

    // Close loader after a few seconds
    setTimeout(() => {
        Swal.close();
        btn.disabled = false;
        btn.innerText = '📄 Get PDF';
    }, 2000); // Adjust duration to match your PDF generation time
});


function shareReceiptAsImage() {
    const capsule = document.querySelector('#appCapsule');
    html2canvas(capsule).then(canvas => {
        canvas.toBlob(blob => {
            const file = new File([blob], "receipt.png", { type: 'image/png' });
            if (navigator.canShare && navigator.canShare({ files: [file] })) {
                navigator.share({
                    files: [file],
                    title: 'Top-up Receipt',
                    text: 'Transaction completed successfully.'
                }).catch(console.error);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Sharing Not Supported',
                    text: 'Your browser does not support image sharing.',
                });
            }
        });
    });
}
</script>

<script src="assets/js/lib/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script src="assets/js/base.js"></script>
</body>
</html>
