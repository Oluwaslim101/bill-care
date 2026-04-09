
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin-login.php');
    exit();
}

// Fetch pending deposits
$query = "SELECT d.*, u.full_name, u.email FROM deposits d
          JOIN users u ON d.user_id = u.id
          WHERE d.status = 'pending'
          ORDER BY d.created_at DESC";
$stmt = $sql->prepare($query);
$stmt->execute();
$deposits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle admin action (confirm, decline, or leave as pending)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $deposit_id = intval($_POST['deposit_id'] ?? 0);

    if (!$deposit_id || !in_array($action, ['confirm', 'decline'])) {
        $error_message = 'Invalid request';
        exit;
    }

    // Fetch the deposit details
    $stmt = $sql->prepare("SELECT * FROM deposits WHERE id = :id");
    $stmt->execute([':id' => $deposit_id]);
    $deposit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$deposit) {
        $error_message = 'Deposit not found';
        exit;
    }

    // Check if the deposit is already processed
    if ($deposit['status'] !== 'pending') {
        $error_message = 'This deposit has already been processed';
        exit;
    }

   
// Perform action based on admin choice
try {
    $sql->beginTransaction();

    if ($action === 'confirm') {
        // Update deposit status to confirmed
        $stmt = $sql->prepare("UPDATE deposits SET status = 'confirmed', updated_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $deposit_id]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("Failed to update deposit status to confirmed.");
        }

        // Update the user's balance based on the wallet (e.g., add to balance or earnings)
        if ($deposit['wallet'] === 'balance') {
            $stmt = $sql->prepare("UPDATE users SET balance = balance + :amount WHERE id = :id");
        } elseif ($deposit['wallet'] === 'earnings') {
            $stmt = $sql->prepare("UPDATE users SET earnings = earnings + :amount WHERE id = :id");
        } else {
            throw new Exception("Invalid wallet type.");
        }

        $stmt->execute([
            ':amount' => $deposit['amount'],
            ':id' => $deposit['user_id']
        ]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("Failed to update user balance.");
        }

        // Notify user about confirmation
        $stmt = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) 
                               VALUES (:user_id, :action_type, :message, :status, NOW())");
        $stmt->execute([
            ':user_id' => $deposit['user_id'],
            ':action_type' => 'deposit',
            ':message' => "Your deposit of $" . number_format($deposit['amount'], 2) . " has been confirmed and added to your " . htmlspecialchars($deposit['wallet']) . ".",
            ':status' => 'unread'
        ]);

    } elseif ($action === 'decline') {
        // Update deposit status to declined
        $stmt = $sql->prepare("UPDATE deposits SET status = 'declined', updated_at = NOW() WHERE id = :id");
        $stmt->execute([':id' => $deposit_id]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("Failed to update deposit status to declined.");
        }

        // Notify user about decline
        $stmt = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) 
                               VALUES (:user_id, :action_type, :message, :status, NOW())");
        $stmt->execute([
            ':user_id' => $deposit['user_id'],
            ':action_type' => 'deposit',
            ':message' => "Your deposit of $" . number_format($deposit['amount'], 2) . " has been declined.",
            ':status' => 'unread'
        ]);
    }

    $sql->commit();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;

} catch (Exception $e) {
    $sql->rollBack();
    $error_message = 'Action failed: ' . $e->getMessage();
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Deposit Actions</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Panel - Pending Deposits</h1>

    <?php if (isset($success_message)): ?>
        <div class="success"><?= htmlspecialchars($success_message) ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="error"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <?php if (count($deposits) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Transaction Ref</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($deposits as $deposit): ?>
                    <tr>
                        <td><?= htmlspecialchars($deposit['full_name']) ?></td>
                        <td>$<?= number_format($deposit['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($deposit['method_id']) ?></td>
                        <td><?= htmlspecialchars($deposit['transaction_ref']) ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="deposit_id" value="<?= $deposit['id'] ?>">
                                <button type="submit" name="action" value="confirm">Confirm</button>
                            </form>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="deposit_id" value="<?= $deposit['id'] ?>">
                                <button type="submit" name="action" value="decline">Decline</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending deposit requests.</p>
    <?php endif; ?>

</body>
</html>
