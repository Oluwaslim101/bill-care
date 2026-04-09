<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Validate inputs
    if (!$name || !$email || !$message) {
        http_response_code(400);
        echo "Please fill in all required fields correctly.";
        exit;
    }

    // Save to database (example with PDO)
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=u822915062_dthehub_utilit", "u822915062_dthehub_utilit", "Lotanna@2024");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo "Failed to save message to database.";
        exit;
    }

    // Send email
    $to = "theodesmon71@gmail.com";
    $subject = "New Contact Message from $name";
    $body = "Name: $name\nEmail: $email\n\nMessage:\n$message";

    $headers = "From:debbydesmond231@gmail.com\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $body, $headers)) {
        echo "success";
    } else {
        // Optional: rollback DB if email fails
        http_response_code(500);
        echo "Failed to send email.";
    }
} else {
    http_response_code(405);
    echo "Method not allowed.";
}