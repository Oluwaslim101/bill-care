<?php
require_once 'session_check.php'; // ensures user is logged in
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;

$stmt = $sql->prepare("SELECT * FROM beneficiaries WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$beneficiaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Beneficiaries</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css"> <!-- FinApp CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>

<div id="appCapsule">

  <div class="section full mt-2">
    <div class="section-title">My Beneficiaries</div>
    <div class="wide-block pt-2 pb-2">

      <?php if (empty($beneficiaries)) : ?>
        <p class="text-muted text-center">No saved beneficiaries yet.</p>
      <?php else: ?>
        <ul class="listview image-listview media flush">

          <?php foreach ($beneficiaries as $b) : ?>
            <li>
              <div class="item">
                <div class="in">
                  <div>
                    <strong><?= htmlspecialchars($b['bank_name']) ?></strong><br>
                    <small><?= htmlspecialchars($b['account_number']) ?> - <?= htmlspecialchars($b['account_name']) ?></small>
                  </div>
                  <div class="ml-auto">
                    <button class="btn btn-sm btn-danger delete-btn" data-id="<?= $b['id'] ?>">
                      <ion-icon name="trash-outline"></ion-icon>
                    </button>
                  </div>
                </div>
              </div>
            </li>
          <?php endforeach; ?>

        </ul>
      <?php endif; ?>

    </div>
  </div>

</div>

<!-- Toast -->
<div id="toast-success" class="toast-box toast-top bg-success">
  <div class="in">
    <div class="text">Deleted successfully</div>
  </div>
</div>

<div id="toast-fail" class="toast-box toast-top bg-danger">
  <div class="in">
    <div class="text">Something went wrong</div>
  </div>
</div>

<!-- Scripts -->
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/base.js"></script>
<script type="module" src="https://cdn.jsdelivr.net/npm/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

<script>
$('.delete-btn').on('click', function () {
  const id = $(this).data('id');
  if (!confirm('Are you sure you want to delete this beneficiary?')) return;

  $.post('delete_beneficiary.php', { id }, function (res) {
    if (res.success) {
      $('#toast-success').addClass('show');
      setTimeout(() => location.reload(), 800);
    } else {
      $('#toast-fail').addClass('show');
    }
  }, 'json').fail(() => {
    $('#toast-fail').addClass('show');
  });
});
</script>

</body>
</html>
