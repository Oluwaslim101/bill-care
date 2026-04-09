<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$reference = $_GET['reference'] ?? null;

if (!$user_id || !$reference) {
    header("Location: index.php");
    exit;
}

$stmt = $sql->prepare("SELECT * FROM user_withdrawals WHERE user_id = ? AND reference = ?");
$stmt->execute([$user_id, $reference]);
$transaction = $stmt->fetch();

if (!$transaction) {
    echo "Transaction not found.";
    exit;
}

$amount          = $transaction['amount'];
$bank_name       = $transaction['bank_name'];
$account_number  = $transaction['account_number'];
$account_name    = $transaction['account_name'] ?? 'Unavailable';
$created_at      = $transaction['created_at'] ?? date('Y-m-d H:i:s');
$transaction_ref = $transaction['reference'];
$session_id      = $transaction['session_id'] ?? strtoupper(uniqid('SID_'));

if (!$transaction['session_id']) {
    $update = $sql->prepare("UPDATE user_withdrawals SET session_id = ? WHERE id = ?");
    $update->execute([$session_id, $transaction['id']]);
}
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
            <li><strong>Transaction Type</strong><span>Money Transfer</span></li>
            <li><strong>Amount</strong><span>&#8358;<?= number_format($amount, 2); ?></span></li>
            <li><strong>Reference</strong><span><?= htmlspecialchars($transaction_ref); ?></span></li>
            <li><strong>Date</strong><span><?= date('M d, Y h:i A', strtotime($created_at)); ?></span></li>
            <li><strong>Recipient Name</strong><span><?= htmlspecialchars($account_name); ?></span></li>
            <li><strong>Bank</strong><span><?= htmlspecialchars($bank_name); ?> (<?= $account_number; ?>)</span></li>
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
          <button id="downloadPdfBtn" class="btn btn-outline-danger w-100">  📄 Get PDF</button>
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
</div>

<!-- Info Modal -->
<div class="modal fade dialogbox" id="DialogBasic" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Receipt Info</h5></div>
            <div class="modal-body">You can download or screenshot this receipt for your records.</div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-bs-dismiss="modal">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dispute Modal -->
<div class="modal fade" id="disputeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="disputeForm" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Dispute Transaction</h5>
      </div>
      <div class="modal-body">
        <p>You're disputing transaction ref: <strong><?= $transaction_ref; ?></strong></p>
        <div class="form-group">
          <label for="disputeReason">Reason</label>
          <textarea class="form-control" name="reason" id="disputeReason" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="reference" value="<?= $transaction_ref; ?>">
        <input type="hidden" name="session_id" value="<?= $session_id; ?>">
        <button type="button" class="btn btn-text-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">Submit Dispute</button>
      </div>
    </form>
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

function shareReceipt() {
    const shareData = {
        title: 'Transaction Receipt',
        text: `Transaction Receipt\nRef: <?= $transaction_ref ?>\nAmount: ₦<?= number_format($amount, 2) ?>\nStatus: Successful`,
        url: window.location.href
    };
    if (navigator.share) {
        navigator.share(shareData).catch(console.error);
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Sharing Not Supported',
            text: 'Your browser does not support direct sharing. You can download the receipt instead.',
            confirmButtonText: 'Okay'
        });
    }
}

function checkDisputeStatus(reference) {
    fetch(`dispute_logger.php?reference=${reference}`)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'pending') {
                Swal.fire({
                    icon: 'info',
                    title: 'Dispute In Progress',
                    text: 'Dispute resolution in process. Please wait for feedback within 24 hours.',
                });
            } else if (data.status === 'resolved') {
                Swal.fire({
                    icon: 'success',
                    title: 'Dispute Resolved',
                    text: 'This dispute has been resolved. Thank you for your patience.',
                });
            } else {
                const modal = new bootstrap.Modal(document.getElementById('disputeModal'));
                modal.show();
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Unable to check dispute status. Try again later.',
            });
        });
}

document.getElementById('disputeForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('dispute_logger.php', {
        method: 'POST',
        body: formData
    }).then(res => res.json()).then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Dispute Initiated',
                text: 'Your dispute has been initiated. Please kindly wait for feedback within 24 hours.',
            });
            document.getElementById('disputeForm').reset();
            const modal = bootstrap.Modal.getInstance(document.getElementById('disputeModal'));
            modal.hide();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: data.message || 'You have already initiated a dispute. Please wait for feedback.',
            });
        }
    }).catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again later.',
        });
    });
});
</script>

<script src="assets/js/lib/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script src="assets/js/base.js"></script>
</body>
</html>
