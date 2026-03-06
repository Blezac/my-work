<?php
/**
 * Database Configuration
 * Greendome Engineering Support System
 */

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'greendome_support');
define('DB_USER', 'greendometicket_user');
define('DB_PASS', 'Ticketing2026.1');
define('DB_CHARSET', 'utf8mb4');

// Company WhatsApp number
define('COMPANY_WHATSAPP', '2349024900124');

// System settings
define('TICKET_PREFIX', 'GETS-');

// Database connection
function getDBConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die("Database connection failed. Please try again later.");
        }
    }
    
    return $pdo;
}

// Generate unique ticket code
function generateTicketCode() {
    $pdo = getDBConnection();
    
    do {
        // Generate 6-digit code
        $code = TICKET_PREFIX . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        
        // Check if code exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE ticket_code = ?");
        $stmt->execute([$code]);
        $exists = $stmt->fetchColumn() > 0;
    } while ($exists);
    
    return $code;
}

// Sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Validate phone number
function validatePhone($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Check if it's a valid Nigerian number
    if (strlen($phone) >= 10 && strlen($phone) <= 14) {
        return $phone;
    }
    
    return false;
}

// Format phone for WhatsApp
function formatPhoneForWhatsApp($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // If starts with 0, replace with 234
    if (substr($phone, 0, 1) === '0') {
        $phone = '234' . substr($phone, 1);
    }
    
    // If doesn't start with country code, add 234
    if (substr($phone, 0, 3) !== '234') {
        $phone = '234' . $phone;
    }
    
    return $phone;
}

// Create ticket
function createTicket($service, $customer_name, $phone, $email, $initiated_by, $initial_message) {
    $pdo = getDBConnection();
    
    try {
        $pdo->beginTransaction();
        
        // Generate ticket code
        $ticket_code = generateTicketCode();
        
        // Insert ticket
        $stmt = $pdo->prepare("
            INSERT INTO tickets (ticket_code, service, customer_name, phone, email, initiated_by, status) 
            VALUES (?, ?, ?, ?, ?, ?, 'New')
        ");
        $stmt->execute([$ticket_code, $service, $customer_name, $phone, $email, $initiated_by]);
        
        $ticket_id = $pdo->lastInsertId();
        
        // Insert initial message
        $stmt = $pdo->prepare("
            INSERT INTO messages (ticket_id, sender, message) 
            VALUES (?, ?, ?)
        ");
        $sender = ($initiated_by === 'Customer') ? 'Customer' : 'Staff';
        $stmt->execute([$ticket_id, $sender, $initial_message]);
        
        // Log status creation
        $stmt = $pdo->prepare("
            INSERT INTO status_history (ticket_id, old_status, new_status, changed_by) 
            VALUES (?, NULL, 'New', ?)
        ");
        $stmt->execute([$ticket_id, $initiated_by]);
        
        $pdo->commit();
        
        return [
            'success' => true,
            'ticket_id' => $ticket_id,
            'ticket_code' => $ticket_code
        ];
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Ticket Creation Error: " . $e->getMessage());
        return [
            'success' => false,
            'error' => 'Failed to create ticket'
        ];
    }
}

// Add message to ticket
function addMessage($ticket_id, $sender, $message, $staff_name = null) {
    $pdo = getDBConnection();
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO messages (ticket_id, sender, message, staff_name) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$ticket_id, $sender, $message, $staff_name]);
        
        // Update ticket timestamp
        $stmt = $pdo->prepare("UPDATE tickets SET updated_at = NOW() WHERE id = ?");
        $stmt->execute([$ticket_id]);
        
        return true;
    } catch (Exception $e) {
        error_log("Add Message Error: " . $e->getMessage());
        return false;
    }
}

// Update ticket status
function updateTicketStatus($ticket_id, $new_status, $changed_by = 'System') {
    $pdo = getDBConnection();
    
    try {
        // Get current status
        $stmt = $pdo->prepare("SELECT status FROM tickets WHERE id = ?");
        $stmt->execute([$ticket_id]);
        $old_status = $stmt->fetchColumn();
        
        if ($old_status === $new_status) {
            return true; // No change needed
        }
        
        $pdo->beginTransaction();
        
        // Update ticket status
        $stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $ticket_id]);
        
        // Log status change
        $stmt = $pdo->prepare("
            INSERT INTO status_history (ticket_id, old_status, new_status, changed_by) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$ticket_id, $old_status, $new_status, $changed_by]);
        
        $pdo->commit();
        return true;
        
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Update Status Error: " . $e->getMessage());
        return false;
    }
}
