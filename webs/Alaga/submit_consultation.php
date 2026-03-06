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
    $preferred_date = $_POST['preferred_date'] ?? '';
    $consultation_type = $_POST['consultation_type'] ?? '';
    $message = trim($_POST['message'] ?? '');

    if (empty($full_name) || empty($email) || empty($phone) || empty($preferred_date) || empty($consultation_type) || empty($message)) {
        throw new Exception('All fields are required');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    $sql = "INSERT INTO consultations (full_name, email, phone, preferred_date, consultation_type, message, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $full_name, $email, $phone, $preferred_date, $consultation_type, $message);

    if ($stmt->execute()) {
        // Send confirmation email
        $to = $email;
        $subject = "Consultation Request Received - WendyGee Alaga";
        $email_message = "
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
                <p>Thank you for requesting a consultation with us. We have received your request and will get back to you shortly.</p>
                <p><strong>Your Details:</strong></p>
                <ul>
                    <li>Consultation Type: " . ucwords(str_replace('_', ' ', $consultation_type)) . "</li>
                    <li>Preferred Date: " . date('F d, Y', strtotime($preferred_date)) . "</li>
                </ul>
                <p>We will contact you at " . htmlspecialchars($phone) . " to confirm your consultation.</p>
                <p>Best regards,<br>WendyGee Alaga Team</p>
            </div>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: WendyGee Alaga <info@wendygeealaga.com>" . "\r\n";

        mail($to, $subject, $email_message, $headers);

        // Notify admin
        mail("info@wendygeealaga.com", "New Consultation Request", 
             "New consultation request from " . $full_name . ". Check admin dashboard for details.", $headers);

        $_SESSION['success_message'] = 'Your consultation request has been submitted successfully! We will contact you shortly.';
        header('Location: index.php#consultation');
    } else {
        throw new Exception('Failed to submit consultation request');
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: index.php#consultation');
}
exit;
?>