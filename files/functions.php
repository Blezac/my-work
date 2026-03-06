<?php
/**
 * Utility Functions
 * Greendome Engineering Ticketing System
 */

/**
 * Generate unique ticket code
 * Format: GDM-XXXXXX (6 random alphanumeric chars)
 */
function generateTicketCode() {
    $prefix = 'GDM-';
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[random_int(0, strlen($characters) - 1)];
    }
    
    return $prefix . $code;
}

/**
 * Check if ticket code is unique
 */
function isTicketCodeUnique($code) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tickets WHERE ticket_code = ?");
    $stmt->execute([$code]);
    return $stmt->fetchColumn() == 0;
}

/**
 * Generate unique ticket code (ensures uniqueness)
 */
function generateUniqueTicketCode() {
    do {
        $code = generateTicketCode();
    } while (!isTicketCodeUnique($code));
    
    return $code;
}

/**
 * Sanitize input
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * Validate WhatsApp number (Nigerian format)
 */
function validateWhatsAppNumber($number) {
    // Remove all non-numeric characters
    $cleaned = preg_replace('/[^0-9]/', '', $number);
    
    // Check if it's a valid Nigerian number (11 digits starting with 0, or 13 with 234)
    if (preg_match('/^0[789][01]\d{8}$/', $cleaned)) {
        return '234' . substr($cleaned, 1); // Convert to international format
    }
    
    if (preg_match('/^234[789][01]\d{8}$/', $cleaned)) {
        return $cleaned;
    }
    
    return false;
}

/**
 * Format WhatsApp number for display
 */
function formatWhatsAppNumber($number) {
    if (strlen($number) == 13 && substr($number, 0, 3) == '234') {
        return '+234 ' . substr($number, 3, 3) . ' ' . substr($number, 6, 4) . ' ' . substr($number, 10);
    }
    return $number;
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Log status change
 */
function logStatusChange($ticketId, $oldStatus, $newStatus, $changedBy = null, $notes = null) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO status_history (ticket_id, old_status, new_status, changed_by, notes)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    return $stmt->execute([$ticketId, $oldStatus, $newStatus, $changedBy, $notes]);
}

/**
 * Log ticket assignment
 */
function logAssignment($ticketId, $assignedFrom, $assignedTo, $assignedBy, $notes = null) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO ticket_assignments (ticket_id, assigned_from, assigned_to, assigned_by, notes)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    return $stmt->execute([$ticketId, $assignedFrom, $assignedTo, $assignedBy, $notes]);
}

/**
 * Send system message to ticket
 */
function addSystemMessage($ticketId, $message) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO ticket_messages (ticket_id, message_type, sender_type, message_text)
        VALUES (?, 'system', 'system', ?)
    ");
    
    return $stmt->execute([$ticketId, $message]);
}

/**
 * Format date for display
 */
function formatDate($datetime, $format = 'd M Y, h:i A') {
    if (empty($datetime)) return '-';
    $dt = new DateTime($datetime);
    return $dt->format($format);
}

/**
 * Time ago function
 */
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    
    if ($diff->d > 7) {
        return formatDate($datetime);
    } elseif ($diff->d > 0) {
        return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    } elseif ($diff->h > 0) {
        return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    } elseif ($diff->i > 0) {
        return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'Just now';
    }
}

/**
 * Get status badge HTML
 */
function getStatusBadge($status) {
    $badges = [
        'Open' => '<span class="badge badge-primary">Open</span>',
        'In Progress' => '<span class="badge badge-info">In Progress</span>',
        'Waiting on Customer' => '<span class="badge badge-warning">Waiting on Customer</span>',
        'Resolved' => '<span class="badge badge-success">Resolved</span>',
        'Closed' => '<span class="badge badge-secondary">Closed</span>'
    ];
    
    return $badges[$status] ?? $status;
}

/**
 * Get priority badge HTML
 */
function getPriorityBadge($priority) {
    $badges = [
        'Low' => '<span class="badge badge-light">Low</span>',
        'Normal' => '<span class="badge badge-secondary">Normal</span>',
        'High' => '<span class="badge badge-warning">High</span>',
        'Urgent' => '<span class="badge badge-danger">Urgent</span>'
    ];
    
    return $badges[$priority] ?? $priority;
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['staff_id']) && !empty($_SESSION['staff_id']);
}

/**
 * Get current logged-in staff
 */
function getCurrentStaff() {
    if (!isLoggedIn()) return null;
    
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM staff_users WHERE id = ? AND is_active = 1");
    $stmt->execute([$_SESSION['staff_id']]);
    
    return $stmt->fetch();
}

/**
 * Redirect function
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}

/**
 * Set flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return null;
}

/**
 * Escape output for HTML
 */
function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Log webhook data for debugging
 */
function logWebhookData($data) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO whatsapp_webhook_logs (webhook_data)
        VALUES (?)
    ");
    
    return $stmt->execute([json_encode($data)]);
}
