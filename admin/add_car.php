<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("db.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $title)));
    $description = trim($_POST['description']);
    $car_type = $_POST['car_type'];
    $price_per_day = floatval($_POST['price_per_day']);
    $transmission = $_POST['transmission'];
    $fuel_type = $_POST['fuel_type'];
    $seats = intval($_POST['seats']);

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = "assets/img/cars/";
        $original_name = basename($_FILES['image']['name']);
        $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extension, $allowed)) {
            $error = "Invalid image type. Only JPG, PNG, or WEBP allowed.";
        } else {
            $unique_name = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '', $original_name);
            $target_path = $upload_dir . $unique_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                // Insert into DB
                $stmt = $sql->prepare("INSERT INTO cars (title, slug, description, car_type, price_per_day, transmission, fuel_type, seats, image, available) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
                $stmt->execute([$title, $slug, $description, $car_type, $price_per_day, $transmission, $fuel_type, $seats, $unique_name]);
                $success = "Car successfully added!";
            } else {
                $error = "Failed to upload image.";
            }
        }
    } else {
        $error = "Please upload an image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Car - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Your FinApp style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>

<div class="appHeader">
    <div class="left">
        <a href="admin_dashboard.php" class="btn btn-sm btn-secondary">← Dashboard</a>
    </div>
    <div class="pageTitle">Add New Car</div>
</div>

<div id="appCapsule">
    <div class="container mt-4">

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
            <div class="mb-3">
                <label class="form-label">Car Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Car Type</label>
                <select name="car_type" class="form-select" required>
                    <option>Sedan</option>
                    <option>SUV</option>
                    <option>Van</option>
                    <option>Coupe</option>
                    <option>Truck</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Transmission</label>
                <select name="transmission" class="form-select" required>
                    <option>Automatic</option>
                    <option>Manual</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Fuel Type</label>
                <select name="fuel_type" class="form-select" required>
                    <option>Petrol</option>
                    <option>Diesel</option>
                    <option>Electric</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Seats</label>
                <input type="number" name="seats" class="form-control" required min="1" max="20">
            </div>

            <div class="mb-3">
                <label class="form-label">Price Per Day (₦)</label>
                <input type="number" name="price_per_day" class="form-control" required step="0.01">
            </div>

            <div class="mb-3">
                <label class="form-label">Upload Car Image</label>
                <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Add Car</button>
        </form>

    </div>
</div>

</body>
</html>
