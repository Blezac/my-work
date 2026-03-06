<?php
/**
 * WhatsApp Cloud API Webhook
 * Greendome Engineering Technology & Science
 * 
 * This webhook receives incoming WhatsApp messages and logs them to tickets
 */

require_once 'includes/config.php';

// Log all webhook requests for debugging
function logWebhook($data, $type = 'info') {
    $log_file = __DIR__ . '/logs/webhook.log';
    $log_dir = dirname($log_file);
    
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$type] " . json_encode($data) . "\n";
    file_put_contents($log_file, $log_entry, FILE_APPEND);
}

// Verify webhook (WhatsApp Cloud API verification)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $verify_token = 'greendome_webhook_token_2024'; // Set this in your WhatsApp App settings
    
    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';
    
    if ($mode === 'subscribe' && $token === $verify_token) {
        logWebhook(['action' => 'verification', 'status' => 'success']);
        echo $challenge;
        exit;
    } else {
        logWebhook(['action' => 'verification', 'status' => 'failed', 'token' => $token]);
        http_response_code(403);
        exit;
    }
}

// Handle incoming messages
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    logWebhook(['action' => 'incoming_message', 'data' => $data]);
    
    // Validate webhook data structure
    if (!isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
        logWebhook(['action' => 'invalid_structure'], 'error');
        http_response_code(200); // Still return 200 to WhatsApp
        exit;
    }
    
    $message_data = $data['entry'][0]['changes'][0]['value']['messages'][0];
    $contact_data = $data['entry'][0]['changes'][0]['value']['contacts'][0] ?? null;
    
    // Extract message details
    $from = $message_data['from'] ?? '';
    $message_body = $message_data['text']['body'] ?? '';
    $message_type = $message_data['type'] ?? 'text';
    
    // Only process text messages
    if ($message_type !== 'text' || empty($message_body)) {
        logWebhook(['action' => 'skipped', 'reason' => 'not_text_message'], 'info');
        http_response_code(200);
        exit;
    }
    
    // Extract Ticket ID from message using regex
    // Looking for patterns like: GETS-123456 or Ticket ID: GETS-123456
    preg_match('/GETS-\d{6}/', $message_body, $matches);
    
    if (empty($matches)) {
        // No ticket ID found - ignore message
        logWebhook([
            'action' => 'no_ticket_id',
            'from' => $from,
            'message' => substr($message_body, 0, 50)
        ], 'info');
        http_response_code(200);
        exit;
    }
    
    $ticket_code = $matches[0];
    
    // Find the ticket
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id, status FROM tickets WHERE ticket_code = ?");
    $stmt->execute([$ticket_code]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) {
        logWebhook([
            'action' => 'ticket_not_found',
            'ticket_code' => $ticket_code,
            'from' => $from
        ], 'warning');
        http_response_code(200);
        exit;
    }
    
    $ticket_id = $ticket['id'];
    $current_status = $ticket['status'];
    
    // Determine sender (if from customer's number, it's customer; otherwise staff)
    // You may need to enhance this logic based on your setup
    $sender = 'Customer'; // Default assumption
    
    // Add message to ticket
    $success = addMessage($ticket_id, $sender, $message_body);
    
    if ($success) {
        // Update ticket status if needed
        if ($current_status === 'New' && $sender === 'Customer') {
            updateTicketStatus($ticket_id, 'Enquiry', 'System');
        } elseif ($sender === 'Staff' && in_array($current_status, ['Enquiry', 'Follow-up'])) {
            updateTicketStatus($ticket_id, 'Responded', 'System');
        }
        
        logWebhook([
            'action' => 'message_logged',
            'ticket_code' => $ticket_code,
            'ticket_id' => $ticket_id,
            'sender' => $sender,
            'status' => 'success'
        ], 'info');
    } else {
        logWebhook([
            'action' => 'message_log_failed',
            'ticket_code' => $ticket_code,
            'ticket_id' => $ticket_id
        ], 'error');
    }
    
    // Always return 200 to WhatsApp
    http_response_code(200);
    echo json_encode(['status' => 'received']);
    exit;
}

// Invalid request method
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
