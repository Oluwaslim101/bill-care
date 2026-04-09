<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');  // Ensure this file is correctly included for DB connection
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch top earners data for the current week
$start_of_week = date('Y-m-d', strtotime('monday this week'));
$end_of_week = date('Y-m-d', strtotime('sunday this week'));

// Print for debugging
echo "Start of week: $start_of_week<br>";
echo "End of week: $end_of_week<br>";

// Now use $sql (PDO object) to prepare the statement correctly
$query = "SELECT te.user_id, u.full_name, te.points
          FROM top_earners te
          JOIN users u ON te.user_id = u.id
          WHERE te.week_start = ? AND te.week_end = ?
          ORDER BY te.points DESC
          LIMIT 10";

$stmt = $sql->prepare($query);  // Prepare using the correct PDO instance
$stmt->execute([$start_of_week, $end_of_week]);  // Execute with bind parameters

// Fetch results into an array
$top_earners = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $top_earners[] = $row;
}

// Debugging output
echo "<pre>";
print_r($top_earners);
echo "</pre>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Earners</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2>Top Earners of the Week</h2>
        <p class="text-muted">These are the top earners based on points for the current week.</p>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Points Earned</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($top_earners) > 0): ?>
                    <?php foreach ($top_earners as $index => $earner): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($earner['full_name']) ?></td>
                            <td><?= $earner['points'] ?> points</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No top earners for this week.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>