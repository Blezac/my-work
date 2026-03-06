<?php
/**
 * Database Configuration File
 * WendyGee Alaga Website
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');  // Change this to your database username
define('DB_PASS', '');      // Change this to your database password
define('DB_NAME', 'wendygee');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    error_log("Database Connection Failed: " . $conn->connect_error);
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed. Please try again later.'
    ]));
}

// Set charset to utf8mb4 for full Unicode support (including emojis)
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('Africa/Lagos');

// Application settings
define('SITE_NAME', 'WendyGee Alaga');
define('SITE_EMAIL', 'info@wendygeealaga.com');
define('SITE_PHONE', '08142953849');
define('SITE_ADDRESS', 'Team6 House29 6th avenue Gwarinpa Abuja');

// Training prices
define('TRAINING_BEGINNER_PRICE', 150000);
define('TRAINING_MASTER_PRICE', 300000);

// Email configuration (optional - for sending confirmation emails)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-app-password');
define('SMTP_FROM', 'noreply@wendygeealaga.com');
define('SMTP_FROM_NAME', 'WendyGee Alaga');

// Function to send confirmation email (optional)
function send_confirmation_email($phone, $groom_name, $bride_name, $date) {
    // Email sending logic here
    // You can use PHPMailer or similar library
    return true;
}
?>