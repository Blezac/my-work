<?php
/**
 * WhatsApp Business API Functions
 * Greendome Engineering Ticketing System
 */

/**
 * Send WhatsApp message
 */
function sendWhatsAppMessage($to, $message, $ticketId = null) {
    $url = WHATSAPP_API_URL . '/' . WHATSAPP_PHONE_NUMBER_ID . '/messages';
    
    // Format phone number (ensure it has country code)
    $to = validateWhatsAppNumber($to);
    if (!$to) {
        error_log("Invalid WhatsApp number: " . $to);
        return false;
    }
    
    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $to,
        'type' => 'text',
        'text' => [
            'body' => $message
        ]
    ];
    
    $headers = [
        'Authorization: Bearer ' . WHATSAPP_ACCESS_TOKEN,
        'Content-Type: application/json'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    // Log the outgoing message if ticket ID is provided
    if ($ticketId && isset($result['messages'][0]['id'])) {
        logOutgoingMessage($ticketId, $message, $result['messages'][0]['id']);
    }
    
    if ($httpCode == 200 && isset($result['messages'][0]['id'])) {
        return $result['messages'][0]['id'];
    } else {
        error_log("WhatsApp API Error: " . $response);
        return false;
    }
}

/**
 * Log outgoing WhatsApp message
 */
function logOutgoingMessage($ticketId, $message, $whatsappMessageId, $senderId = null) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO ticket_messages 
        (ticket_id, message_type, sender_type, sender_id, message_text, whatsapp_message_id)
        VALUES (?, 'outgoing', 'staff', ?, ?, ?)
    ");
    
    return $stmt->execute([$ticketId, $senderId, $message, $whatsappMessageId]);
}

/**
 * Log incoming WhatsApp message
 */
function logIncomingMessage($ticketId, $message, $whatsappMessageId) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        INSERT INTO ticket_messages 
        (ticket_id, message_type, sender_type, message_text, whatsapp_message_id, is_read)
        VALUES (?, 'incoming', 'customer', ?, ?, 0)
    ");
    
    return $stmt->execute([$ticketId, $message, $whatsappMessageId]);
}

/**
 * Find ticket by WhatsApp number
 * Returns the most recent open ticket for the customer
 */
function findTicketByWhatsApp($whatsappNumber) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("
        SELECT * FROM tickets 
        WHERE customer_whatsapp = ? 
        AND status NOT IN ('Closed')
        ORDER BY updated_at DESC 
        LIMIT 1
    ");
    
    $stmt->execute([$whatsappNumber]);
    return $stmt->fetch();
}

/**
 * Mark WhatsApp message as read
 */
function markWhatsAppMessageAsRead($messageId) {
    $url = WHATSAPP_API_URL . '/' . WHATSAPP_PHONE_NUMBER_ID . '/messages';
    
    $data = [
        'messaging_product' => 'whatsapp',
        'status' => 'read',
        'message_id' => $messageId
    ];
    
    $headers = [
        'Authorization: Bearer ' . WHATSAPP_ACCESS_TOKEN,
        'Content-Type: application/json'
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode == 200;
}

/**
 * Send ticket creation notification via WhatsApp
 */
function sendTicketCreationNotification($ticket) {
    $pdo = getDBConnection();
    
    // Get service name
    $stmt = $pdo->prepare("SELECT service_name FROM services WHERE id = ?");
    $stmt->execute([$ticket['service_id']]);
    $service = $stmt->fetch();
    
    $message = "🎫 *Ticket Created - " . APP_NAME . "*\n\n";
    $message .= "Ticket ID: *" . $ticket['ticket_code'] . "*\n";
    $message .= "Service: " . $service['service_name'] . "\n";
    $message .= "Type: " . $ticket['request_type'] . "\n\n";
    $message .= "Thank you for contacting us! Our team will respond shortly.\n\n";
    $message .= "You can continue this conversation here, and all messages will be tracked against your ticket.";
    
    return sendWhatsAppMessage($ticket['customer_whatsapp'], $message, $ticket['id']);
}

/**
 * Send status change notification via WhatsApp
 */
function sendStatusChangeNotification($ticketId, $newStatus) {
    $pdo = getDBConnection();
    
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
    $stmt->execute([$ticketId]);
    $ticket = $stmt->fetch();
    
    if (!$ticket) return false;
    
    $statusMessages = [
        'In Progress' => "Your ticket is now being worked on by our team.",
        'Waiting on Customer' => "We need some additional information from you. Please reply to this message.",
        'Resolved' => "Your ticket has been resolved! If you need further assistance, please let us know.",
        'Closed' => "Your ticket has been closed. Thank you for choosing " . APP_NAME . "."
    ];
    
    if (!isset($statusMessages[$newStatus])) return false;
    
    $message = "🔔 *Ticket Update - " . $ticket['ticket_code'] . "*\n\n";
    $message .= "Status: *" . $newStatus . "*\n\n";
    $message .= $statusMessages[$newStatus];
    
    return sendWhatsAppMessage($ticket['customer_whatsapp'], $message, $ticketId);
}

/**
 * Process incoming webhook from WhatsApp
 */
function processWhatsAppWebhook($webhookData) {
    // Log the webhook for debugging
    logWebhookData($webhookData);
    
    // Extract message data
    if (!isset($webhookData['entry'][0]['changes'][0]['value']['messages'][0])) {
        return false;
    }
    
    $message = $webhookData['entry'][0]['changes'][0]['value']['messages'][0];
    $from = $message['from']; // Customer's WhatsApp number
    $messageId = $message['id'];
    $messageText = $message['text']['body'] ?? '';
    
    if (empty($messageText)) return false;
    
    // Find the customer's active ticket
    $ticket = findTicketByWhatsApp($from);
    
    if ($ticket) {
        // Log the incoming message
        logIncomingMessage($ticket['id'], $messageText, $messageId);
        
        // Mark message as read
        markWhatsAppMessageAsRead($messageId);
        
        // Update ticket's updated_at timestamp
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("UPDATE tickets SET updated_at = NOW() WHERE id = ?");
        $stmt->execute([$ticket['id']]);
        
        return true;
    } else {
        // No active ticket found - could send a message asking them to create a ticket
        $message = "Hello! We couldn't find an active ticket for your number. ";
        $message .= "Please visit " . APP_URL . " to create a support ticket.";
        
        sendWhatsAppMessage($from, $message);
        return false;
    }
}
