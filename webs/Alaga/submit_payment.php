<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate inputs
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $training_type = $_POST['training_type'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    $transaction_ref = trim($_POST['transaction_ref'] ?? '');

    if (empty($full_name) || empty($email) || empty($phone) || empty($training_type)) {
        throw new Exception('All fields are required');
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Handle file upload
    $receipt_path = '';
    if (isset($_FILES['payment_receipt']) && $_FILES['payment_receipt']['error'] === 0) {
        $upload_dir = 'uploads/receipts/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES['payment_receipt']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];
        
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            throw new Exception('Invalid file type. Only JPG, PNG, and PDF are allowed.');
        }

        // Generate unique filename
        $filename = uniqid('receipt_') . '_' . time() . '.' . $file_extension;
        $receipt_path = $upload_dir . $filename;

        if (!move_uploaded_file($_FILES['payment_receipt']['tmp_name'], $receipt_path)) {
            throw new Exception('Failed to upload receipt');
        }
    } else {
        throw new Exception('Payment receipt is required');
    }

    // Generate transaction reference if not provided
    if (empty($transaction_ref)) {
        $transaction_ref = 'TXN' . time() . rand(1000, 9999);
    }

    // Insert into training_enrollments
    $sql = "INSERT INTO training_enrollments (
        full_name, email, phone, training_type, training_price, 
        payment_status, enrollment_status, payment_receipt, transaction_reference, created_at
    ) VALUES (?, ?, ?, ?, ?, 'pending', 'pending', ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssdss", $full_name, $email, $phone, $training_type, $amount, $receipt_path, $transaction_ref);

    if ($stmt->execute()) {
        $enrollment_id = $stmt->insert_id;

        // Send confirmation email to user
        $to = $email;
        $subject = "Training Payment Received - WendyGee Alaga";
        $message = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; }
                .header { background: #8B4513; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .footer { background: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>WendyGee Alaga</h1>
                <p>Training Payment Confirmation</p>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($full_name) . ",</p>
                <p>Thank you for your training enrollment payment. We have received your payment receipt and it is currently under review.</p>
                <p><strong>Payment Details:</strong></p>
                <ul>
                    <li>Training Type: " . ucfirst($training_type) . "</li>
                    <li>Amount: ₦" . number_format($amount, 2) . "</li>
                    <li>Transaction Reference: " . htmlspecialchars($transaction_ref) . "</li>
                </ul>
                <p>We will verify your payment and send you confirmation within 24-48 hours.</p>
                <p>If you have any questions, please contact us at info@wendygeealaga.com or call 08142953849.</p>
            </div>
            <div class='footer'>
                <p>&copy; " . date('Y') . " WendyGee Alaga. All Rights Reserved.</p>
                <p>Team6 House29, 6th Avenue, Gwarinpa Abuja</p>
            </div>
        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: WendyGee Alaga <info@wendygeealaga.com>" . "\r\n";

        mail($to, $subject, $message, $headers);

        // Send notification to admin
        $admin_email = "info@wendygeealaga.com";
        $admin_subject = "New Training Payment Received";
        $admin_message = "
        <html>
        <body>
            <h2>New Training Payment</h2>
            <p>A new training payment has been submitted:</p>
            <ul>
                <li>Name: " . htmlspecialchars($full_name) . "</li>
                <li>Email: " . htmlspecialchars($email) . "</li>
                <li>Phone: " . htmlspecialchars($phone) . "</li>
                <li>Training Type: " . ucfirst($training_type) . "</li>
                <li>Amount: ₦" . number_format($amount, 2) . "</li>
                <li>Reference: " . htmlspecialchars($transaction_ref) . "</li>
            </ul>
            <p>Please review the payment receipt in the admin dashboard.</p>
        </body>
        </html>
        ";

        mail($admin_email, $admin_subject, $admin_message, $headers);

        echo json_encode([
            'success' => true,
            'message' => 'Payment submitted successfully! We will verify your payment and contact you within 24-48 hours.',
            'enrollment_id' => $enrollment_id
        ]);
    } else {
        throw new Exception('Failed to process payment: ' . $stmt->error);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    error_log('Payment Error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>