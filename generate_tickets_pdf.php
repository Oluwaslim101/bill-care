<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Dompdf for PDF
include('phpqrcode/qrlib.php'); // PHPQRCode
include('db.php');
session_start();

use Dompdf\Dompdf;

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    die('Unauthorized access');
}

$user_email = $_SESSION['email'];
$user_id = $_SESSION['user_id'];
$reference = htmlspecialchars($_GET['reference'] ?? '');

if (empty($reference)) {
    die('Invalid reference');
}

// Fetch booking details
$stmt = $sql->prepare("SELECT b.*, e.name AS event_name, e.location, e.event_date, e.event_time 
    FROM event_bookings b
    JOIN events e ON b.event_id = e.id
    WHERE b.user_id = ? AND b.reference = ?");
$stmt->execute([$user_id, $reference]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    die('Booking not found');
}

// Prepare ticket data
$event_name = htmlspecialchars($booking['event_name']);
$location = htmlspecialchars($booking['location']);
$event_date = date('F d, Y', strtotime($booking['event_date']));
$event_time = date('g:i A', strtotime($booking['event_time']));
$total_tickets = intval($booking['number_of_tickets']);
$created_at = date('F d, Y g:i A', strtotime($booking['created_at']));
$ticket_price = number_format($booking['total_cost'] / $total_tickets, 2);

$html = '<style>
    body { font-family: Arial, sans-serif; }
    .ticket {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        margin-bottom: 20px;
        page-break-inside: avoid;
    }
    .left {
        background-color: #FF7F50;
        color: #fff;
        width: 70%;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .right {
        background-color: #fff8dc;
        color: #333;
        width: 30%;
        padding: 10px;
        border-left: 2px dashed #ccc;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-align: center;
    }
    .ticket-title {
        font-size: 24px;
        font-weight: bold;
    }
    .details {
        margin-top: 10px;
        font-size: 14px;
    }
    .barcode {
        margin-top: 10px;
        text-align: center;
    }
    img.qr {
        width: 80px;
        height: 80px;
    }
</style>';

for ($i = 1; $i <= $total_tickets; $i++) {
    // Generate QR Code for this ticket
    $qrTempFile = "temp_qr_$i.png";
    $qrData = "REF:$reference|TICKET:$i|EMAIL:$user_email";
    QRcode::png($qrData, $qrTempFile, QR_ECLEVEL_L, 3);

    $html .= '
    <div class="ticket">
        <div class="left">
            <div>
                <div class="ticket-title">' . $event_name . '</div>
                <div class="details">
                    <p><strong>Date:</strong> ' . $event_date . '</p>
                    <p><strong>Time:</strong> ' . $event_time . '</p>
                    <p><strong>Location:</strong> ' . $location . '</p>
                </div>
            </div>
            <div class="details">
                <p><strong>Buyer:</strong> ' . $user_email . '</p>
                <p><strong>Reference:</strong> ' . $reference . '</p>
                <p><strong>Ticket:</strong> #' . $i . ' of ' . $total_tickets . '</p>
            </div>
        </div>
        <div class="right">
            <div class="ticket-title" style="font-size:16px;">' . $event_name . '</div>
            <div class="barcode">
                <img src="' . $qrTempFile . '" alt="QR Code" class="qr">
            </div>
            <small>' . $created_at . '</small>
        </div>
    </div>';

    // Delete temp QR file after use
    unlink($qrTempFile);
}

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Stream PDF
$filename = "Tickets_" . $reference . ".pdf";
$dompdf->stream($filename, ["Attachment" => true]);
exit;
?>
