<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['reference']) || empty($_GET['reference'])) {
    header('Location: events.php');
    exit();
}

$reference = htmlspecialchars($_GET['reference']);

// Fetch booking details
$stmt = $sql->prepare("SELECT b.*, e.name AS event_name, e.location, e.event_date, e.event_time 
    FROM event_bookings b
    JOIN events e ON b.event_id = e.id
    WHERE b.user_id = ? AND b.reference = ?");
$stmt->execute([$user_id, $reference]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    header('Location: events.php');
    exit();
}

$event_datetime = date('M d, Y h:i A', strtotime($booking['event_date'] . ' ' . $booking['event_time']));
$booked_on = date('M d, Y h:i A', strtotime($booking['booked_at']));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Event Receipt - <?= htmlspecialchars($booking['event_name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f6;
            font-family: "Segoe UI", sans-serif;
            margin: 0;
            padding: 0;
        }

        .full-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #fff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .full-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: linear-gradient(135deg, #007bff, #00bcd4);
            color: #fff;
            border-radius: 0 0 20px 20px;
        }

        .full-modal-header h5 {
            margin: 0;
            font-weight: 600;
        }

        .close-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            color: #fff;
            font-size: 20px;
            line-height: 30px;
            text-align: center;
        }

        .qr-section {
            text-align: center;
            padding: 20px;
        }

        .qr-section img {
            width: 160px;
            height: 160px;
            border: 5px solid #f1f3f6;
            border-radius: 15px;
        }

        .receipt-details {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 15px;
        }

        .receipt-item strong {
            color: #333;
        }

        .receipt-total {
            font-size: 18px;
            font-weight: 700;
            color: #28a745;
        }

        .action-buttons {
            padding: 15px;
            border-top: 1px solid #eee;
            background: #f9f9f9;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #00bcd4);
            border: none;
            border-radius: 50px;
            font-weight: 600;
        }

        .btn-secondary {
            background: #e9ecef;
            color: #333;
            border-radius: 50px;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="full-modal">
        <!-- Header -->
        <div class="full-modal-header">
            <h5>🎟 Event Receipt</h5>
            <button class="close-btn" onclick="window.location.href='events.php'">&times;</button>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($booking['reference']) ?>"
                alt="QR Code">
            <p class="small text-muted mt-2">Scan to verify your ticket</p>
        </div>

        <!-- Receipt Details -->
        <div class="receipt-details">
            <div class="receipt-item">
                <span>📍 Location</span>
                <strong><?= htmlspecialchars($booking['location']) ?></strong>
            </div>
            <div class="receipt-item">
                <span>📅 Date & Time</span>
                <strong><?= $event_datetime ?></strong>
            </div>
            <div class="receipt-item">
                <span>🎟 Tickets</span>
                <strong><?= $booking['number_of_tickets'] ?></strong>
            </div>
            <div class="receipt-item">
                <span>🆔 Reference</span>
                <strong><?= htmlspecialchars($booking['reference']) ?></strong>
            </div>
            <div class="receipt-item">
                <span>⏱ Booked On</span>
                <strong><?= $booked_on ?></strong>
            </div>
            <hr>
            <div class="receipt-item receipt-total">
                <span>Total Paid</span>
                <strong>₦<?= number_format($booking['total_cost'], 2) ?></strong>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <div class="d-grid gap-2">
               <a href="generate_tickets_pdf.php?reference=<?= urlencode($reference) ?>" 
   class="btn btn-primary w-100 mt-2">
   🎟 Download Tickets
</a>

                <a href="events.php" class="btn btn-secondary btn-lg">Back to Events</a>
            </div>
        </div>
    </div>
</body>

</html>
