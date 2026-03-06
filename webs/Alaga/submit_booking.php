<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Set JSON header
header('Content-Type: application/json');

// Include database configuration
require_once 'config.php';

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Function to validate phone number
function validate_phone($phone) {
    return preg_match('/^[0-9]{11}$/', $phone);
}

// Function to validate required fields
function validate_required_fields($data, $required_fields) {
    $errors = [];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            $errors[] = ucwords(str_replace('_', ' ', $field)) . ' is required';
        }
    }
    return $errors;
}

try {
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Required fields
    $required_fields = [
        'date', 'time', 'phone', 'venue', 'service_type',
        'groom_firstname', 'groom_lastname',
        'bride_firstname', 'bride_lastname',
        'social_media'
    ];

    // Validate required fields
    $validation_errors = validate_required_fields($_POST, $required_fields);
    
    if (!empty($validation_errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $validation_errors)
        ]);
        exit;
    }

    // Validate phone number
    if (!validate_phone($_POST['phone'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid phone number format. Must be 11 digits.'
        ]);
        exit;
    }

    // Sanitize all inputs
    $date = sanitize_input($_POST['date']);
    $time = sanitize_input($_POST['time']);
    $phone = sanitize_input($_POST['phone']);
    $venue = sanitize_input($_POST['venue']);
    $service_type = sanitize_input($_POST['service_type']);
    
    // Groom Information
    $groom_firstname = sanitize_input($_POST['groom_firstname']);
    $groom_lastname = sanitize_input($_POST['groom_lastname']);
    $groom_oriki = sanitize_input($_POST['groom_oriki'] ?? '');
    $groom_religion = sanitize_input($_POST['groom_religion'] ?? '');
    $groom_denomination = sanitize_input($_POST['groom_denomination'] ?? '');
    $groom_musician = sanitize_input($_POST['groom_musician'] ?? '');
    
    // Groom's Father Information
    $groom_father_name = sanitize_input($_POST['groom_father_name'] ?? '');
    $groom_father_status = sanitize_input($_POST['groom_father_status'] ?? 'alive');
    $groom_father_religion = sanitize_input($_POST['groom_father_religion'] ?? '');
    $groom_father_denomination = sanitize_input($_POST['groom_father_denomination'] ?? '');
    $groom_father_hometown = sanitize_input($_POST['groom_father_hometown'] ?? '');
    $groom_father_compound = sanitize_input($_POST['groom_father_compound'] ?? '');
    
    // Groom's Mother Information
    $groom_mother_name = sanitize_input($_POST['groom_mother_name'] ?? '');
    $groom_mother_status = sanitize_input($_POST['groom_mother_status'] ?? 'alive');
    $groom_mother_religion = sanitize_input($_POST['groom_mother_religion'] ?? '');
    $groom_mother_denomination = sanitize_input($_POST['groom_mother_denomination'] ?? '');
    $groom_mother_hometown = sanitize_input($_POST['groom_mother_hometown'] ?? '');
    $groom_mother_compound = sanitize_input($_POST['groom_mother_compound'] ?? '');
    $groom_mother_colour = sanitize_input($_POST['groom_mother_colour'] ?? '');
    
    // Bride Information
    $bride_firstname = sanitize_input($_POST['bride_firstname']);
    $bride_lastname = sanitize_input($_POST['bride_lastname']);
    $bride_oriki = sanitize_input($_POST['bride_oriki'] ?? '');
    $bride_religion = sanitize_input($_POST['bride_religion'] ?? '');
    $bride_denomination = sanitize_input($_POST['bride_denomination'] ?? '');
    $bride_musician = sanitize_input($_POST['bride_musician'] ?? '');
    
    // Bride's Father Information
    $bride_father_name = sanitize_input($_POST['bride_father_name'] ?? '');
    $bride_father_status = sanitize_input($_POST['bride_father_status'] ?? 'alive');
    $bride_father_religion = sanitize_input($_POST['bride_father_religion'] ?? '');
    $bride_father_denomination = sanitize_input($_POST['bride_father_denomination'] ?? '');
    $bride_father_hometown = sanitize_input($_POST['bride_father_hometown'] ?? '');
    $bride_father_compound = sanitize_input($_POST['bride_father_compound'] ?? '');
    
    // Bride's Mother Information
    $bride_mother_name = sanitize_input($_POST['bride_mother_name'] ?? '');
    $bride_mother_status = sanitize_input($_POST['bride_mother_status'] ?? 'alive');
    $bride_mother_religion = sanitize_input($_POST['bride_mother_religion'] ?? '');
    $bride_mother_denomination = sanitize_input($_POST['bride_mother_denomination'] ?? '');
    $bride_mother_hometown = sanitize_input($_POST['bride_mother_hometown'] ?? '');
    $bride_mother_compound = sanitize_input($_POST['bride_mother_compound'] ?? '');
    $bride_mother_colour = sanitize_input($_POST['bride_mother_colour'] ?? '');
    
    // Social Media Permission
    $social_media = sanitize_input($_POST['social_media']);
    
    // Prepare SQL statement
    $sql = "INSERT INTO bookings (
        booking_date, booking_time, phone, venue, service_type,
        groom_firstname, groom_lastname, groom_oriki, groom_religion, groom_denomination, groom_musician,
        groom_father_name, groom_father_status, groom_father_religion, groom_father_denomination, 
        groom_father_hometown, groom_father_compound,
        groom_mother_name, groom_mother_status, groom_mother_religion, groom_mother_denomination,
        groom_mother_hometown, groom_mother_compound, groom_mother_colour,
        bride_firstname, bride_lastname, bride_oriki, bride_religion, bride_denomination, bride_musician,
        bride_father_name, bride_father_status, bride_father_religion, bride_father_denomination,
        bride_father_hometown, bride_father_compound,
        bride_mother_name, bride_mother_status, bride_mother_religion, bride_mother_denomination,
        bride_mother_hometown, bride_mother_compound, bride_mother_colour,
        social_media_permission, created_at
    ) VALUES (
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?,
        ?, NOW()
    )";
    
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Database prepare error: ' . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param(
        "sssssssssssssssssssssssssssssssssssssssssssss",
        $date, $time, $phone, $venue, $service_type,
        $groom_firstname, $groom_lastname, $groom_oriki, $groom_religion, $groom_denomination, $groom_musician,
        $groom_father_name, $groom_father_status, $groom_father_religion, $groom_father_denomination,
        $groom_father_hometown, $groom_father_compound,
        $groom_mother_name, $groom_mother_status, $groom_mother_religion, $groom_mother_denomination,
        $groom_mother_hometown, $groom_mother_compound, $groom_mother_colour,
        $bride_firstname, $bride_lastname, $bride_oriki, $bride_religion, $bride_denomination, $bride_musician,
        $bride_father_name, $bride_father_status, $bride_father_religion, $bride_father_denomination,
        $bride_father_hometown, $bride_father_compound,
        $bride_mother_name, $bride_mother_status, $bride_mother_religion, $bride_mother_denomination,
        $bride_mother_hometown, $bride_mother_compound, $bride_mother_colour,
        $social_media
    );
    
    // Execute statement
    if ($stmt->execute()) {
        $booking_id = $stmt->insert_id;
        
        // Send confirmation email (optional)
        // send_confirmation_email($phone, $groom_firstname, $bride_firstname, $date);
        
        echo json_encode([
            'success' => true,
            'message' => 'Booking submitted successfully! We will contact you shortly on ' . $phone,
            'booking_id' => $booking_id
        ]);
        
        // Log successful booking
        error_log("New booking created: ID $booking_id");
        
    } else {
        throw new Exception('Failed to save booking: ' . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    // Log error
    error_log('Booking Error: ' . $e->getMessage());
    
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while processing your booking. Please try again or contact us at 08142953849.'
    ]);
}
?>