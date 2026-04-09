<?php
require 'db.php';

// Fetch active banner alerts
$stmt = $sql->prepare("SELECT * FROM banner_alerts WHERE is_active = 1 AND display_start <= NOW() AND display_end >= NOW() ORDER BY display_start DESC");
$stmt->execute();
$alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count the number of alerts to manage carousel items
$alert_count = count($alerts);
?>

<?php if ($alert_count > 0): ?>
    <div id="alertCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000"> <!-- 5000ms = 5 seconds -->
        <div class="carousel-inner">
            <?php foreach ($alerts as $index => $alert): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="alert alert-<?= htmlspecialchars($alert['alert_type']) ?> text-center mb-0" role="alert">
                        <strong><?= htmlspecialchars($alert['title']) ?>:</strong> <?= htmlspecialchars($alert['message']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
<?php endif; ?>
