<?php
// Fetch receipt data from DB
require 'db.php';
session_start();

$ref = $_GET['ref'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$ref || !$user_id) {
    die("Invalid or missing reference.");
}

$stmt = $sql->prepare("
    SELECT p.*, u.full_name AS receiver_name
    FROM p2p p
    JOIN users u ON p.receiver = u.id
    WHERE p.reference = ? AND p.sender = ?
");
$stmt->execute([$ref, $user_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Transaction not found.");
}

// Assign variables
$amount = $data['amount'];
$transaction_ref = $data['reference'];
$created_at = $data['created_at'];
$receiver_name = $data['receiver_name'];
$session_id = $data['session_id'] ?? 'N/A';
$remark = $data['note'] ?? '';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="theme-color" content="#000000">
    <title>Transaction Receipt</title>
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        <div class="listed-detail text-center mt-3">
            <div class="icon-wrapper">
                <div class="iconbox bg-success">
                    <ion-icon name="checkmark-done-circle-outline"></ion-icon>
                </div>
            </div>
            <h3 class="text-success mt-2">Successful</h3>
        </div>

        <ul class="listview flush transparent simple-listview no-space mt-3">
            <li><strong>Transaction Type</strong><span>P2P Transfer</span></li>
            <li><strong>Amount</strong><span>&#8358;<?= number_format($amount, 2); ?></span></li>
            <li><strong>Reference</strong><span><?= htmlspecialchars($transaction_ref); ?></span></li>
            <li><strong>Date</strong><span><?= date('M d, Y h:i A', strtotime($created_at)); ?></span></li>
            <li><strong>Recipient Name</strong><span><?= htmlspecialchars($receiver_name); ?></span></li>
            <li><strong>Remark</strong><span><?= $remark ? htmlspecialchars($remark) : 'None'; ?></span></li>
            <li><strong>Session ID</strong><span><?= htmlspecialchars($session_id); ?></span></li>
        </ul>

        <div class="mt-4">
            <div class="row g-2 text-center">
                <div class="col-6 col-md-3">
                    <a href="index.php" class="btn btn-primary w-100">
                        🏠 Dashboard
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <button id="downloadPdfBtn" class="btn btn-outline-danger w-100">📄 Get PDF</button>
                </div>
                <div class="col-6 col-md-3">
                    <button onclick="shareReceipt()" class="btn btn-outline-success w-100">
                        📤 Share
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <button class="btn btn-outline-danger w-100" onclick="checkDisputeStatus('<?= $transaction_ref ?>')">
                        ⚠️ Dispute
                    </button>
                </div>
            </div>
        </div>

        <small class="text-muted d-block mt-1">
            <i class="fas fa-info-circle"></i> Only one dispute per transaction is allowed until resolved.
        </small>
    </div>
</div>

<!-- Optional Modals -->
<?php include 'receipt_modals.php'; ?>

<script>
function shareReceipt() {
    alert("Sharing feature is coming soon.");
}
function checkDisputeStatus(ref) {
    // Show dispute modal
    var modal = new bootstrap.Modal(document.getElementById('disputeModal'));
    modal.show();
}
</script>
<script src="assets/js/lib/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script src="assets/js/base.js"></script>

</body>
</html>
