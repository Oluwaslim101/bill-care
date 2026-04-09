<?php
// admin/active_users.php
session_start();
include 'db.php';

$threshold = date('Y-m-d H:i:s', strtotime('-30 seconds'));
$stmt = $sql->prepare("SELECT name, email, location, current_page, last_active FROM visitor_activity WHERE last_active >= ? ORDER BY last_active DESC");
$stmt->execute([$threshold]);
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Users</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">Back</a>
    </div>
    <div class="pageTitle">Active Visitors</div>
    <div class="right"></div>
</div>

<div class="appCapsule">
    <div class="section mt-2">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Currently Active (last 30 seconds)</h5>
            </div>
            <div class="card-body">
                <?php if (count($users) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Current Page</th>
                                    <th>Last Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td><?= htmlspecialchars($user['location']) ?></td>
                                        <td><a href="<?= htmlspecialchars($user['current_page']) ?>" target="_blank">View</a></td>
                                        <td><?= htmlspecialchars($user['last_active']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">No active users at the moment.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Notification Sound -->
<audio id="notifySound" src="../assets/notificationping.mp3" preload="auto"></audio>

<!-- Scripts -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/base.js"></script>

<script>
let previousCount = <?= count($users) ?>;

function checkActiveUsers() {
    fetch('get_active_count.php')
        .then(res => res.json())
        .then(data => {
            if (data.count > previousCount) {
                document.getElementById('notifySound').play();
            }
            previousCount = data.count;
        });
}

// Run every 10 seconds
setInterval(checkActiveUsers, 5000);
</script>
</body>
</html>