<?php
/**
 * WhatsApp Webhook Receiver
 * Greendome Engineering Ticketing System
 * 
 * This endpoint receives incoming WhatsApp messages from the WhatsApp Business API
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/whatsapp.php';

// Log all incoming requests for debugging
$logFile = __DIR__ . '/../logs/webhook.log';
$requestBody = file_get_contents('php://input');
file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $requestBody . "\n\n", FILE_APPEND);

// Handle webhook verification (GET request)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $mode = $_GET['hub_mode'] ?? '';
    $token = $_GET['hub_verify_token'] ?? '';
    $challenge = $_GET['hub_challenge'] ?? '';
    
    if ($mode === 'subscribe' && $token === WHATSAPP_VERIFY_TOKEN) {
        // Respond with the challenge token from the request
        http_response_code(200);
        echo $challenge;
        exit;
    } else {
        // Invalid verification token
        http_response_code(403);
        exit;
    }
}

// Handle webhook notifications (POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode($requestBody, true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
        exit;
    }
    
    // Process the webhook
    try {
        // Check if this is a message notification
        if (isset($data['entry'][0]['changes'][0]['value']['messages'][0])) {
            processWhatsAppWebhook($data);
        }
        
        // Always return 200 OK to acknowledge receipt
        http_response_code(200);
        echo json_encode(['status' => 'success']);
        
    } catch (Exception $e) {
        error_log("Webhook processing error: " . $e->getMessage());
        
        // Still return 200 to prevent webhook retries
        http_response_code(200);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    
    exit;
}

// Invalid request method
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);
