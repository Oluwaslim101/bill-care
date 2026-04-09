<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');

// Get today's date
$today = date('Y-m-d');

// Fetch all active contracts
$query = "SELECT uc.*, u.email, u.full_name 
          FROM user_contracts uc 
          JOIN users u ON u.id = uc.user_id
          WHERE uc.status = 'active'";
$stmt = $sql->query($query);
$contracts = $stmt->fetchAll();

foreach ($contracts as $contract) {
    $contract_id = $contract['id'];
    $user_id = $contract['user_id'];
    $daily_earnings = $contract['daily_earnings'];
    $days_paid = (int)$contract['days_paid'];
    $duration = (int)$contract['end_date'] ? (strtotime($contract['end_date']) - strtotime($contract['start_date'])) / 86400 : 0;
    $last_paid = $contract['last_paid_date'] ? date('Y-m-d', strtotime($contract['last_paid_date'])) : null;

    // Check if payment is due today
    if ($last_paid === $today || $days_paid >= $duration) {
        continue;
    }

    // Credit daily earnings
    $sql->prepare("UPDATE users SET earnings = earnings + ? WHERE id = ?")->execute([$daily_earnings, $user_id]);

    // Update contract record
    $sql->prepare("UPDATE user_contracts 
                   SET days_paid = days_paid + 1, last_paid_date = ? 
                   WHERE id = ?")->execute([$today, $contract_id]);

    // If last day, credit capital and mark as completed
    if ($days_paid + 1 >= $duration) {
        $capital = $contract['purchased_amount'];
        $sql->prepare("UPDATE users SET earnings = earnings + ? WHERE id = ?")->execute([$capital, $user_id]);

        $sql->prepare("UPDATE user_contracts 
                       SET status = 'completed' 
                       WHERE id = ?")->execute([$contract_id]);

        // Send notification
        $msg = "Your contract (Ref: {$contract['transaction_ref']}) has completed. Total earnings and capital have been credited.";
        $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at)
                       VALUES (?, 'contract_completed', ?, 'unread', NOW())")
            ->execute([$user_id, $msg]);
    }
}

// Optional: log that cron ran
file_put_contents(__DIR__ . '/cron_log.txt', "Cron ran at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

echo "Cron executed successfully.";
?>