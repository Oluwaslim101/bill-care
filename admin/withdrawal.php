<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once("db.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch pending withdrawals with user info
$query = "SELECT u.full_name, w.* 
          FROM withdrawals w 
          JOIN users u ON w.user_id = u.id 
          WHERE w.status = 'pending' 
          ORDER BY w.created_at DESC";
$stmt = $sql->prepare($query);
$stmt->execute();
$withdrawals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to approve withdrawal
function approveWithdrawal($withdrawal_id, $transaction_ref, $user_id, $amount, $wallet, $sql) {
    try {
        $sql->beginTransaction();

        // Update withdrawal status to approved
        $stmt = $sql->prepare("UPDATE withdrawals SET status = 'approved', updated_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $withdrawal_id]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("Failed to update withdrawal status to approved.");
        }

        // Deduct the amount from the user's wallet
        $stmt = $sql->prepare("UPDATE users SET $wallet = $wallet - :amount WHERE id = :id");
        $stmt->execute([':amount' => $amount, ':id' => $user_id]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("Failed to update user wallet.");
        }

        // Placeholder for notification (temporary)
        error_log("Notification: Withdrawal approved for user $user_id with transaction ref $transaction_ref");

        $sql->commit();
        return true;
    } catch (Exception $e) {
        $sql->rollBack();
        error_log("Error in approving withdrawal: " . $e->getMessage());
        return false;
    }
}

// Function to reject withdrawal
function rejectWithdrawal($withdrawal_id, $user_id, $amount, $wallet, $sql) {
    try {
        $sql->beginTransaction();

        $stmt = $sql->prepare("UPDATE withdrawals SET status = 'declined', updated_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $withdrawal_id]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("Failed to update withdrawal status to declined.");
        }

        // Placeholder for notification (temporary)
        error_log("Notification: Withdrawal declined for user $user_id");

        $sql->commit();
        return true;
    } catch (Exception $e) {
        $sql->rollBack();
        error_log("Error in rejecting withdrawal: " . $e->getMessage());
        return false;
    }
}

// Handle admin action (approve or reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $withdrawal_id = intval($_POST['withdrawal_id'] ?? 0);

    if (!$withdrawal_id || !in_array($action, ['approved', 'reject'])) {
        $error_message = 'Invalid request';
        exit;
    }

    // Fetch the withdrawal details
    $stmt = $sql->prepare("SELECT * FROM withdrawals WHERE id = :id");
    $stmt->execute([':id' => $withdrawal_id]);
    $withdrawal = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$withdrawal) {
        $error_message = 'Withdrawal not found';
        exit;
    }

    if ($withdrawal['status'] !== 'pending') {
        $error_message = 'This withdrawal has already been processed';
        exit;
    }

    // Fetch user details
    $stmt = $sql->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute([':id' => $withdrawal['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error_message = 'User not found';
        exit;
    }

    // Perform action based on admin choice
    $success = false;
    if ($action === 'approved') {
        $success = approveWithdrawal(
            $withdrawal_id,
            $withdrawal['transaction_ref'],
            $withdrawal['user_id'],
            $withdrawal['amount'],
            $withdrawal['source'], // 'balance' or 'earnings'
            $sql
        );
    } elseif ($action === 'reject') {
        $success = rejectWithdrawal($withdrawal_id, $withdrawal['user_id'], $withdrawal['amount'], $withdrawal['source'], $sql);
    }

    if ($success) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error_message = 'Action failed. Please try again.';
        error_log("Action failed for withdrawal ID: $withdrawal_id. Action: $action");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Withdrawal Requests</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Panel - Pending Withdrawal Requests</h1>

    <?php if (isset($success_message)): ?>
        <div class="success"><?= htmlspecialchars($success_message) ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (count($withdrawals) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Source</th>
                    <th>Transaction Ref</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($withdrawals as $withdrawal): ?>
                    <tr>
                        <td><?= htmlspecialchars($withdrawal['full_name']) ?></td>
                        <td>$<?= number_format($withdrawal['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($withdrawal['source']) ?></td>
                        <td><?= htmlspecialchars($withdrawal['transaction_ref']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="withdrawal_id" value="<?= $withdrawal['id'] ?>">
                                <button type="submit" name="action" value="approved">Approve</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="withdrawal_id" value="<?= $withdrawal['id'] ?>">
                                <button type="submit" name="action" value="reject">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending withdrawal requests.</p>
    <?php endif; ?>

</body>
</html>