<?php  
error_reporting(E_ALL);  
ini_set('display_errors', 1);  
include('db.php');  
session_start();  

if (!isset($_SESSION['user_id'])) {  
    header('Location: login.php');  
    exit();  
}  

$user_id = $_SESSION['user_id'];  

$queries = [];
$allParams = array_fill(0, 11, $user_id); 

$queries[] = "SELECT 'Airtime' AS type, id, NULL AS reference, CONCAT(network, ' ₦', amount, ' to ', mobile_number) AS description, amount, status, created_at FROM airtime_transactions WHERE user_id = ?";
$queries[] = "SELECT 'Data' AS type, id, NULL AS reference, CONCAT(network, ' - ', plan_name, ' ₦', amount, ' to ', mobile_number) AS description, amount, status, created_at FROM data_transactions WHERE user_id = ?";
$queries[] = "SELECT 'Gift Card' AS type, id, NULL AS reference, CONCAT(card_type, ' - ', country, ' ₦', amount) AS description, amount, status, created_at FROM giftcard_trades WHERE user_id = ?";
$queries[] = "SELECT 'Deposit' AS type, id, reference, CONCAT(payment_method, ' - ₦', amount) AS description, amount, 'Completed' AS status, created_at FROM payments WHERE user_id = ?";
$queries[] = "SELECT 'Shop Order' AS type, id, NULL AS reference, CONCAT('Order #', reference, ' - ₦', full_amount) AS description, pay_amount AS amount, status, created_at FROM cart_orders WHERE user_id = ?";
$queries[] = "SELECT 'Cable TV' AS type, id, NULL AS reference, CONCAT(provider, ' - ₦', amount, ' - ', smartcard) AS description, amount, status, created_at FROM cable_transactions WHERE phone = (SELECT phone_number FROM users WHERE id = ?)";
$queries[] = "SELECT 'Transfer' AS type, id, reference, CONCAT(bank_name, ' - ₦', amount, ' to ', account_number) AS description, amount, status, created_at FROM user_withdrawals WHERE user_id = ?";
$queries[] = "SELECT 'P2P Transfer' AS type, p.id, p.reference, CONCAT('Sent ₦', p.amount, ' to ', u.full_name) AS description, p.amount, 'Completed' AS status, p.created_at 
FROM p2p p 
JOIN users u ON p.receiver = u.id 
WHERE p.sender = ?";
$queries[] = "SELECT 'P2P Received' AS type, p.id, p.reference, CONCAT('Received ₦', p.amount, ' from ', u.full_name) AS description, p.amount, 'Completed' AS status, p.created_at 
FROM p2p p 
JOIN users u ON p.sender = u.id 
WHERE p.receiver = ?";
$queries[] = "SELECT 'Fixed Savings' AS type, id, NULL AS reference, 
CONCAT('₦', amount, ' for ', duration_days, ' days @ ', interest_rate, '%') AS description, 
amount, status, start_date AS created_at 
FROM fixed_savings 
WHERE user_id = ?";
$queries[] = "SELECT 'Hotel Booking' AS type, id, reference, CONCAT('Hotel #', hotel_id, ' - ', room_type, ' (', guests, ' guests) from ', checkin_date, ' to ', checkout_date) AS description, total_cost AS amount, status, created_at FROM bookings WHERE customer_email = (SELECT email FROM users WHERE id = ?)";



$finalQuery = implode(" UNION ALL ", $queries) . " ORDER BY created_at DESC";
$stmt = $sql->prepare($finalQuery);
$stmt->execute($allParams);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Categorize
$grouped = ['Today' => [], 'This Week' => [], 'Older' => []];
$today = date('Y-m-d');
$weekStart = date('Y-m-d', strtotime('-7 days'));

foreach ($transactions as $tx) {
    $txDate = date('Y-m-d', strtotime($tx['created_at']));
    if ($txDate == $today) {
        $grouped['Today'][] = $tx;
    } elseif ($txDate >= $weekStart) {
        $grouped['This Week'][] = $tx;
    } else {
        $grouped['Older'][] = $tx;
    }
}

function getIconAndColor($type) {
    return match ($type) {
        'Airtime' => ['call-outline', 'bg-danger'],
        'Data' => ['wifi-outline', 'bg-danger'],
        'Gift Card' => ['gift-outline', 'bg-warning'],
        'Deposit' => ['arrow-down-outline', 'bg-success'],
        'Shop Order' => ['bag-handle-outline', 'bg-primary'],
        'Cable TV' => ['tv-outline', 'bg-danger'],
        'Transfer' => ['arrow-up-outline', 'bg-danger'],
        'Fixed Savings' => ['lock-closed-outline', 'bg-primary'],
        'P2P Transfer' => ['swap-horizontal-outline', 'bg-danger'],
        'P2P Received' => ['swap-horizontal-outline', 'bg-success'],
        'Hotel Booking' => ['bed-outline', 'bg-danger'], 
        default => ['help-outline', 'bg-dark'],
    };
}


$totalIn = 0;
$totalOut = 0;

foreach ($transactions as $tx) {
    $type = $tx['type'];
    $status = strtolower($tx['status']);
    $amountRaw = isset($tx['amount']) ? (float) $tx['amount'] : 0.00; // ✅ Safely define

    $displayAmount = '';
    $badge = 'text-muted';
    $receiptLink = '#';

    if ($type === 'Fixed Savings') {
        $displayAmount = "- ₦" . number_format($amountRaw, 2);
        $badge = 'text-primary';
        $receiptLink = '#'; // Optional: link to a receipt if needed
    }

    switch ($type) {
        case 'Airtime':
        case 'Data':
        case 'Cable TV':
        case 'Transfer':
        case 'Shop Order':
        case 'Fixed Savings':
            $displayAmount = "- ₦" . number_format($amountRaw, 2);
            $badge = ($type === 'Fixed Savings') ? 'text-primary' : 'text-danger';
            break;

        case 'Deposit':
        case 'Payment':
            $displayAmount = "+ ₦" . number_format($amountRaw, 2);
            $badge = 'text-success';
            break;
    }

    // Optional: accumulate totals
    if ($badge === 'text-success') {
        $totalIn += $amountRaw;
    } elseif (in_array($badge, ['text-danger', 'text-primary'])) {
        $totalOut += $amountRaw;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover"/>
    <meta name="theme-color" content="#000000">
    <title>DtheHub - Transaction History</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .compact-list .item { padding-top: 6px; padding-bottom: 3px; }
        .text-extra-small { font-size: 11px; line-height: 1.4; }
        .text-end-small { font-size: 8px; }
        .icon-box { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; }
        .transaction-item { padding: 6px 0; margin-bottom: 2px; border-bottom: 1px solid rgba(0, 0, 0, 0.03); }
        .transaction-item:last-child { margin-bottom: 0; border-bottom: none; }
        .listview.detailed-list { padding-top: 6px; padding-bottom: 4px; }

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
        .nav a i { font-size: 20px; color: gray; transition: color 0.3s ease; }
        .nav a span { font-size: 12px; font-weight: 500; }
        .nav a.active i, .nav a.active span { color: green; font-weight: bold; }
    </style>
</head>
<body>

<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack"><ion-icon name="chevron-back-outline"></ion-icon></a>
    </div>
    <div class="pageTitle">Transaction History</div>
    <div class="right">
        <i class="fas fa-filter" style="font-size: 25px; color: blue;" data-bs-toggle="modal" data-bs-target="#filterModal"></i>
    </div>
</div>

<div id="appCapsule">

    <!-- Money In / Out Summary -->
    <div class="section mt-1 mb-0 px-1">
        <div class="row text-center">
            <div class="col-6">
                <div class="card p-0">
                    <div class="text-small text-muted">Money In</div>
                    <div class="text-success fw-bold">+ ₦<?= number_format($totalIn, 2) ?></div>
                </div>
            </div>
            <div class="col-6">
                <div class="card p-0">
                    <div class="text-small text-muted">Money Out</div>
                    <div class="text-danger fw-bold">- ₦<?= number_format($totalOut, 2) ?></div>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($grouped as $section => $items): ?>
        <?php if (!empty($items)): ?>
            <div class="section mt-0 full">
                <div class="section-title"><?= $section ?></div>
                <div class="card">
                    <ul class="listview flush transparent no-line image-listview detailed-list compact-list mb-1">
                    <?php foreach ($items as $tx): 
                        $amountRaw = isset($tx['amount']) ? floatval($tx['amount']) : 0.00;
                        [$icon, $color] = getIconAndColor($tx['type']);
                        $time = date('g:i A', strtotime($tx['created_at']));
                        $date = date('M j, Y', strtotime($tx['created_at']));
                        $status = strtolower($tx['status']);
                        $badge = "text-muted";
                        $receiptLink = '#';

                        $displayAmount = '';
                        $description = htmlspecialchars($tx['description'] ?? '');
                        $type = $tx['type'];
                        $amountRaw = floatval($tx['amount']);

                       if ($type === 'Gift Card') {
    $symbol = $tx['currency_symbol'] ?? '$';
    $originalAmount = isset($tx['original_amount']) ? (float)$tx['original_amount'] : 0;
    $original = $symbol . number_format($originalAmount, 2);
    $country = $tx['country'] ?? '';
    $giftcard = $tx['giftcard_name'] ?? '';
    $description = "$giftcard - $country | $original";

    // Payout in Naira
    $amountRaw = isset($tx['amount']) ? (float)$tx['amount'] : 0;

    if ($status === 'pending') {
        $badge = 'text-warning';
        $displayAmount = "+ ₦" . number_format($amountRaw, 2);
    } elseif (in_array($status, ['approved', 'completed'])) {
        $badge = 'text-success';
        $displayAmount = "+ ₦" . number_format($amountRaw, 2);
    } elseif ($status === 'rejected') {
        $badge = 'text-danger';
        $displayAmount = "<s>₦" . number_format($amountRaw, 2) . "</s>";
    }

    $ref = $tx['reference'] ?? '';
    $receiptLink = 'giftcard_receipt.php?ref=' . urlencode($ref);
                        } else {
                            $formattedAmount = number_format($amountRaw, 2);
                           switch ($type) {
    case 'Airtime':
    case 'Data':
    case 'Cable TV':
    case 'Transfer':
    case 'Fixed Savings':
    case 'Shop Order':
    case 'P2P Transfer':
    case 'Hotel Booking':
    case 'P2P Sent':
    $displayAmount = "- ₦" . $formattedAmount;
    $badge = 'text-danger';
    break;
    case 'P2P Received':
    $displayAmount = "+ ₦" . $formattedAmount;
    $badge = 'text-success';
    break;

    case 'Deposit':
    case 'Payment':
        $displayAmount = "+ ₦" . $formattedAmount;
        $badge = 'text-success';
        break;
}


                            if ($type === 'Deposit') {
                                $receiptLink = 'dep_receipt.php?reference=' . urlencode($tx['reference']);
                            } elseif ($type === 'Transfer') {
                                $receiptLink = 'withdrawal_receipt.php?reference=' . urlencode($tx['reference']);
                            }
                            if ($type === 'P2P Transfer') {
                             $receiptLink = 'p2p_receipt.php?ref=' . urlencode($tx['reference']);
                           }
                            if ($type === 'P2P Received') {
                             $receiptLink = 'p2p_received_receipt.php?ref=' . urlencode($tx['reference']);
                           }
                           if ($type === 'Hotel Booking') {
                           $receiptLink = 'hotel_receipt.php?reference=' . urlencode($tx['reference']);
                            }
                        }
                    ?>

                    <li class="transaction-item">
                        <a href="<?= $receiptLink ?>" class="item">
                            <div class="icon-box <?= $color ?>" style="width: 36px; height: 36px;">
                                <ion-icon name="<?= $icon ?>" style="font-size: 18px;"></ion-icon>
                            </div>
                            <div class="in">
                                <div>
                                    <strong class="small"><?= $type ?></strong>
                                    <div class="text-extra-small text-secondary"><?= $description ?></div>
                                    <div class="text-extra-small text-muted" style="font-size: 11px; font-style: italic;"><?= ucfirst($status) ?></div>
                                </div>
                                <div class="text-end small">
                                    <strong class="<?= $badge ?>"><?= $displayAmount ?></strong>
                                    <div class="text-muted" style="font-size: 11px; font-style: italic;"><?= ($section === 'Today') ? "Today $time" : $date ?></div>
                                </div>
                            </div>
                        </a>
                    </li>

                    <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<!-- Bottom Nav -->
<?php include 'footer.php'; ?>

</body>
</html>
