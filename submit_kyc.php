<?php
include('db.php');
session_start();

// Simulate user login
$user_id = $_SESSION['user_id'] ?? 1;

$success = '';
$error = '';

// Fetch user email (used later for sending email)
$stmtUser = $sql->prepare("SELECT email FROM users WHERE id = :user_id");
$stmtUser->execute([':user_id' => $user_id]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);
$email = $user['email'] ?? '';


// Fetch existing KYC if any
$stmt = $sql->prepare("SELECT * FROM kyc WHERE user_id = :user_id ORDER BY submitted_at DESC LIMIT 1");
$stmt->execute([':user_id' => $user_id]);
$existing_kyc = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle new submission only if none or if previously failed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!$existing_kyc || $existing_kyc['status'] === 'failed')) {
    $full_name = $_POST['full_name'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    $address = $_POST['address'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $document_type = $_POST['document_type'] ?? '';
    $document_image = $_FILES['document_image'] ?? null;

    if (!$full_name || !$id_number || !$address || !$dob || !$document_type || !$document_image) {
        $error = "Please fill in all fields.";
    } elseif ($document_image['error'] !== 0) {
        $error = "Error uploading document.";
    } else {
        $filename = uniqid() . '_' . basename($document_image['name']);
        $target = 'uploads/' . $filename;

        if (move_uploaded_file($document_image['tmp_name'], $target)) {
            try {
                // Insert into KYC
                $stmt = $sql->prepare("INSERT INTO kyc (user_id, full_name, id_number, address, dob, document_type, document_image_url, status, submitted_at)
                                       VALUES (:user_id, :full_name, :id_number, :address, :dob, :document_type, :document_image_url, 'pending', NOW())");

                $stmt->execute([
                    ':user_id' => $user_id,
                    ':full_name' => $full_name,
                    ':id_number' => $id_number,
                    ':address' => $address,
                    ':dob' => $dob,
                    ':document_type' => $document_type,
                    ':document_image_url' => $target,
                ]);

                // Insert notification
                $note = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) 
                                       VALUES (:user_id, 'kyc', :message, 'unread', NOW())");
                $note->execute([
                    ':user_id' => $user_id,
                    ':message' => 'Your KYC submission is received and pending review.'
                ]);

                // Send confirmation email
                if (!empty($email)) {
                    mail($email, "KYC Submitted", "Thank you for submitting your KYC. You can track the status here: https://swiftaffiliates.cloud/kyc_email_submitted.php");
                }

                // Refresh KYC data
                $existing_kyc = $sql->query("SELECT * FROM kyc WHERE user_id = $user_id ORDER BY submitted_at DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
                $success = "KYC submitted successfully!";
            } catch (Exception $e) {
                $error = "Database error: " . $e->getMessage();
            }
        } else {
            $error = "Failed to save uploaded document.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KYC Submission - FinApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
        }
        .finapp-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            padding: 30px;
            max-width: 600px;
            margin: 50px auto;
        }
        .btn-primary {
            background-color: #002b5b;
            border: none;
        }
        .btn-primary:hover {
            background-color: #014288;
        }
        .status-badge {
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="finapp-card">
    <h2 class="mb-4">KYC Submission</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($existing_kyc && $existing_kyc['status'] !== 'failed'): ?>
        <!-- Locked Form View -->
        <p class="mb-3">Your KYC has been submitted.</p>
        <ul class="list-group mb-3">
            <li class="list-group-item"><strong>Full Name:</strong> <?php echo htmlspecialchars($existing_kyc['full_name']); ?></li>
            <li class="list-group-item"><strong>ID Number:</strong> <?php echo htmlspecialchars($existing_kyc['id_number']); ?></li>
            <li class="list-group-item"><strong>Address:</strong> <?php echo htmlspecialchars($existing_kyc['address']); ?></li>
            <li class="list-group-item"><strong>DOB:</strong> <?php echo htmlspecialchars($existing_kyc['dob']); ?></li>
            <li class="list-group-item"><strong>Document:</strong> 
                <a href="<?php echo $existing_kyc['document_image_url']; ?>" target="_blank">View Document</a>
            </li>
            <li class="list-group-item"><strong>Status:</strong> 
                <span class="badge 
                    <?php 
                        echo $existing_kyc['status'] === 'pending' ? 'bg-warning text-dark' : 
                             ($existing_kyc['status'] === 'verified' ? 'bg-success' : 'bg-danger'); 
                    ?> status-badge">
                    <?php echo ucfirst($existing_kyc['status']); ?>
                </span>
            </li>
        </ul>
        <?php if ($existing_kyc['status'] === 'failed'): ?>
            <div class="alert alert-warning">Your KYC was rejected. You can resubmit the form below.</div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (!$existing_kyc || $existing_kyc['status'] === 'failed'): ?>
        <!-- Editable Form -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">ID Number</label>
                <input type="text" name="id_number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Residential Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Document Type</label>
                <select name="document_type" class="form-select" required>
                    <option value="">Select Type</option>
                    <option value="passport">Passport</option>
                    <option value="national_id">National ID</option>
                    <option value="driver_license">Driver's License</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload Document (Image/PDF)</label>
                <input type="file" name="document_image" accept="image/*,application/pdf" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit KYC</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>