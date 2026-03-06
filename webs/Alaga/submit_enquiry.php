<?php
session_start();
require_once 'config.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $enquiry_message = trim($_POST['enquiry_message'] ?? '');

    if (empty($full_name) || empty($email) || empty($phone) || empty($subject) || empty($enquiry_message)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    $sql = "INSERT INTO enquiries (full_name, email, phone, subject, message, status, created_at) 
            VALUES (?, ?, ?, ?, ?, 'new', NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $full_name, $email, $phone, $subject, $enquiry_message);

    if ($stmt->execute()) {
        // Send confirmation email
        $to = $email;
        $email_subject = "Enquiry Received - WendyGee Alaga";
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .header { background: #8B4513; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>WendyGee Alaga</h1>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($full_name) . ",</p>
                <p>Thank you for your enquiry. We have received your message and will respond to you as soon as possible.</p>
                <p><strong>Your Enquiry:</strong></p>
                <p><strong>Subject:</strong> " . htmlspecialchars($subject) . "</p>
                <p>" . nl2br(htmlspecialchars($enquiry_message)) . "</p>
                <p>We typically respond within 24-48 hours.</p>
                <p>Best regards,<br>WendyGee Alaga Team</p>
            </div>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: WendyGee Alaga <info@wendygeealaga.com>" . "\r\n";

        mail($to, $email_subject, $message, $headers);

        // Notify admin
        mail("info@wendygeealaga.com", "New Enquiry: " . $subject, 
             "New enquiry from " . $full_name . ". Check admin dashboard for details.", $headers);

        $_SESSION['success_message'] = 'Your enquiry has been submitted successfully! We will respond to you shortly.';
        header('Location: index.php#enquiry');
    } else {
        throw new Exception('Failed to submit enquiry');
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: index.php#enquiry');
}
exit;
?>