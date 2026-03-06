<?php
/* ========================================
   DESOK LUXURY APARTMENT - LOGIN HANDLER
   Processes resident portal login
   ======================================== */

require_once 'config.php';

// Start session
session_start();

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Invalid request method');
}

// Get database connection
$conn = getDatabaseConnection();

if (!$conn) {
    sendJsonResponse(false, 'Database connection failed. Please try again later.');
}

// Collect and sanitize form data
$username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$remember = isset($_POST['remember']);

// Validate required fields
$errors = [];

if (empty($username)) {
    $errors[] = 'Username or email is required';
}

if (empty($password)) {
    $errors[] = 'Password is required';
}

// If there are validation errors, return them
if (!empty($errors)) {
    sendJsonResponse(false, implode(', ', $errors));
}

// Prepare SQL statement to find user by username or email
$sql = "SELECT id, username, email, password, full_name, unit_number, phone, member_since, status 
        FROM users 
        WHERE (username = ? OR email = ?) AND status = 'active'";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    sendJsonResponse(false, 'Error preparing database query');
}

// Bind parameters
$stmt->bind_param("ss", $username, $username);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Password is correct, create session
        $_SESSION['resident_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['resident_name'] = $user['full_name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['unit_number'] = $user['unit_number'];
        $_SESSION['member_since'] = $user['member_since'];
        
        // Update last login time
        $update_sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $user['id']);
        $update_stmt->execute();
        $update_stmt->close();
        
        // Set remember me cookie if requested (30 days)
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
            // TODO: Store token in database for security
        }
        
        $stmt->close();
        $conn->close();
        
        sendJsonResponse(
            true, 
            'Login successful! Redirecting to your dashboard...',
            null,
            'portal.php'
        );
    } else {
        // Incorrect password
        $stmt->close();
        $conn->close();
        
        // Add delay to prevent brute force attacks
        sleep(2);
        
        sendJsonResponse(false, 'Invalid username/email or password');
    }
} else {
    // User not found
    $stmt->close();
    $conn->close();
    
    // Add delay to prevent user enumeration
    sleep(2);
    
    sendJsonResponse(false, 'Invalid username/email or password');
}
?>
