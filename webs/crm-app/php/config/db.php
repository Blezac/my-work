<?php
/**
 * CRM System - Database Configuration
 * Version: 2.0
 * Description: Production-ready database connection handler
 */

// ============================================
// DATABASE CREDENTIALS
// ============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');              // Change to your database user
define('DB_PASS', '');                  // Change to your database password
define('DB_NAME', 'crm_system');

// ============================================
// ERROR REPORTING
// ============================================
// Set to 0 for production, 1 for development
define('DEBUG_MODE', 1);

if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php-errors.log');

// ============================================
// DATABASE CONNECTION FUNCTION
// ============================================
/**
 * Get database connection with error handling
 * @return mysqli Database connection object
 * @throws Exception if connection fails
 */
function getDBConnection() {
    static $conn = null;
    
    // Return existing connection if available
    if ($conn !== null && $conn->ping()) {
        return $conn;
    }
    
    try {
        // Create new connection
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check for connection errors
        if ($conn->connect_error) {
            error_log("MySQL Connection Error: " . $conn->connect_error);
            
            if (DEBUG_MODE) {
                throw new Exception("Database connection failed: " . $conn->connect_error);
            } else {
                throw new Exception("Database connection failed. Please contact administrator.");
            }
        }
        
        // Set character set
        if (!$conn->set_charset("utf8mb4")) {
            error_log("MySQL Charset Error: " . $conn->error);
        }
        
        return $conn;
        
    } catch (mysqli_sql_exception $e) {
        error_log("MySQL Exception: " . $e->getMessage());
        
        if (DEBUG_MODE) {
            die("Database Error: " . $e->getMessage());
        } else {
            die("Database connection error. Please contact administrator.");
        }
    }
}

// ============================================
// PREPARED STATEMENT EXECUTION
// ============================================
/**
 * Execute prepared SQL statement with error handling
 * 
 * @param mysqli $conn Database connection
 * @param string $sql SQL query with placeholders (?)
 * @param string $types Parameter types (i=integer, d=double, s=string, b=blob)
 * @param array $params Array of parameters to bind
 * @return array ['success' => bool, 'stmt' => mysqli_stmt, 'error' => string]
 * 
 * Example:
 * $result = executeQuery($conn, "SELECT * FROM users WHERE id = ?", "i", [123]);
 */
function executeQuery($conn, $sql, $types = "", $params = []) {
    // Prepare statement
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        error_log("SQL Prepare Error: " . $conn->error . " | SQL: " . $sql);
        return [
            'success' => false, 
            'error' => DEBUG_MODE ? $conn->error : 'Query preparation failed',
            'stmt' => null
        ];
    }
    
    // Bind parameters if provided
    if (!empty($types) && !empty($params)) {
        $bind_result = $stmt->bind_param($types, ...$params);
        
        if (!$bind_result) {
            error_log("Parameter Binding Error: " . $stmt->error);
            return [
                'success' => false,
                'error' => DEBUG_MODE ? $stmt->error : 'Parameter binding failed',
                'stmt' => null
            ];
        }
    }
    
    // Execute statement
    $execute_result = $stmt->execute();
    
    if (!$execute_result) {
        error_log("SQL Execute Error: " . $stmt->error . " | SQL: " . $sql);
        return [
            'success' => false,
            'error' => DEBUG_MODE ? $stmt->error : 'Query execution failed',
            'stmt' => $stmt
        ];
    }
    
    return [
        'success' => true,
        'stmt' => $stmt,
        'error' => null
    ];
}

// ============================================
// JSON RESPONSE HELPER
// ============================================
/**
 * Send JSON response and exit
 * 
 * @param array $data Data to encode as JSON
 * @param int $status HTTP status code (default: 200)
 */
function jsonResponse($data, $status = 200) {
    // Set HTTP status code
    http_response_code($status);
    
    // Set JSON content type header
    header('Content-Type: application/json; charset=utf-8');
    
    // Prevent caching of API responses
    header('Cache-Control: no-cache, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    
    // Encode and output JSON
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    
    exit;
}

// ============================================
// INPUT SANITIZATION
// ============================================
/**
 * Sanitize user input to prevent XSS attacks
 * 
 * @param mixed $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    if ($data === null) {
        return '';
    }
    
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    
    // Remove whitespace from beginning and end
    $data = trim($data);
    
    // Remove backslashes
    $data = stripslashes($data);
    
    // Convert special characters to HTML entities
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    
    return $data;
}

// ============================================
// DATABASE TESTING FUNCTION
// ============================================
/**
 * Test database connection
 * 
 * @return bool True if connection successful, false otherwise
 */
function testDBConnection() {
    try {
        $conn = getDBConnection();
        $result = $conn->query("SELECT 1");
        
        if ($result === false) {
            return false;
        }
        
        return true;
        
    } catch (Exception $e) {
        error_log("Connection test failed: " . $e->getMessage());
        return false;
    }
}

// ============================================
// ADDITIONAL HELPER FUNCTIONS
// ============================================

/**
 * Get last insert ID after INSERT query
 * 
 * @param mysqli $conn Database connection
 * @return int Last inserted ID
 */
function getLastInsertId($conn) {
    return $conn->insert_id;
}

/**
 * Get number of affected rows after UPDATE/DELETE
 * 
 * @param mysqli $conn Database connection
 * @return int Number of affected rows
 */
function getAffectedRows($conn) {
    return $conn->affected_rows;
}

/**
 * Begin database transaction
 * 
 * @param mysqli $conn Database connection
 * @return bool True on success
 */
function beginTransaction($conn) {
    return $conn->begin_transaction();
}

/**
 * Commit database transaction
 * 
 * @param mysqli $conn Database connection
 * @return bool True on success
 */
function commitTransaction($conn) {
    return $conn->commit();
}

/**
 * Rollback database transaction
 * 
 * @param mysqli $conn Database connection
 * @return bool True on success
 */
function rollbackTransaction($conn) {
    return $conn->rollback();
}

/**
 * Escape string for use in SQL queries (use prepared statements instead when possible)
 * 
 * @param mysqli $conn Database connection
 * @param string $string String to escape
 * @return string Escaped string
 */
function escapeString($conn, $string) {
    return $conn->real_escape_string($string);
}

// ============================================
// AUTO-CONNECTION TEST (Optional)
// ============================================
// Uncomment to test connection on every file include
// if (DEBUG_MODE) {
//     if (!testDBConnection()) {
//         error_log("WARNING: Database connection test failed on load");
//     }
// }

?>