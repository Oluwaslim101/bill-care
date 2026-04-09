<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=u822915062_dthehub_utilit", "u822915062_dthehub_utilit", "Lotanna@2024");
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div id="appCapsule">
  <div class="section mt-2 text-center">
    <img src="<?= $user['avatar'] ? $user['avatar'] : 'assets/img/default-avatar.png' ?>" class="avatar rounded-circle mb-2" width="100" height="100" style="object-fit:cover;border:2px solid #ccc;">
    <h2><?= htmlspecialchars($user['full_name']) ?></h2>
    <p><?= htmlspecialchars($user['email']) ?></p>
  </div>

  <div class="section mb-2">
    <div class="card">
      <div class="card-body">
        <h5>Profile Info</h5>
        <p><strong>Country:</strong> <?= $user['country'] ?></p>
        <p><strong>State:</strong> <?= $user['state'] ?></p>
        <p><strong>Address:</strong> <?= $user['address'] ?></p>
        <p><strong>Birthday:</strong> <?= $user['birthday'] ?></p>
      </div>
    </div>

    <div class="card mt-2">
      <div class="card-body">
        <h5>Virtual Account</h5>
        <p><strong>Bank:</strong> <?= $user['bank_name'] ?></p>
        <p><strong>Account Number:</strong> <?= $user['virtual_account_number'] ?></p>
        <p><strong>Account Name:</strong> <?= $user['account_name'] ?></p>
      </div>
    </div>

    <div class="text-center mt-3">
      <a href="logout.php" class="btn btn-danger btn-block">Logout</a>
    </div>
  </div>
</div>
</body>
</html>