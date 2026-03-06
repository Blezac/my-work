<?php
/**
 * Test Webhook
 * Use this to test if webhook.php is working correctly
 * 
 * Usage: php test_webhook.php
 */

// Simulate a WhatsApp webhook POST request
$webhook_url = 'http://greendomesupport.local/webhook.php';

// Sample WhatsApp webhook data
$webhook_data = [
    'entry' => [
        [
            'changes' => [
                [
                    'value' => [
                        'messages' => [
                            [
                                'from' => '2349012345678',
                                'type' => 'text',
                                'text' => [
                                    'body' => 'Hello! This is a test message for Ticket ID: GETS-000001. Please help me with my inquiry.'
                                ]
                            ]
                        ],
                        'contacts' => [
                            [
                                'profile' => [
                                    'name' => 'Test User'
                                ],
                                'wa_id' => '2349012345678'
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

echo "Testing Webhook...\n";
echo "==================\n\n";

echo "Sending POST request to: $webhook_url\n";
echo "Data:\n";
echo json_encode($webhook_data, JSON_PRETTY_PRINT) . "\n\n";

// Initialize cURL
$ch = curl_init($webhook_url);

// Set cURL options
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($webhook_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: WhatsApp/2.0'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute request
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch) . "\n";
} else {
    echo "Response Code: $http_code\n";
    echo "Response Body:\n";
    echo $response . "\n\n";
    
    if ($http_code === 200) {
        echo "✅ Webhook test successful!\n";
    } else {
        echo "❌ Webhook test failed!\n";
    }
}

curl_close($ch);

echo "\nCheck logs/webhook.log for detailed webhook activity.\n";
