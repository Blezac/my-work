<?php
/**
 * Ticket Success Page - WhatsApp Redirect
 * Greendome Engineering Ticketing System
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$ticketCode = $_GET['ticket'] ?? '';

if (empty($ticketCode)) {
    header("Location: index.php");
    exit;
}

// Fetch ticket details
$pdo = getDBConnection();
$stmt = $pdo->prepare("
    SELECT t.*, s.service_name 
    FROM tickets t
    LEFT JOIN services s ON t.service_id = s.id
    WHERE t.ticket_code = ?
");
$stmt->execute([$ticketCode]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header("Location: index.php");
    exit;
}

// Get the first message
$stmt = $pdo->prepare("
    SELECT message_text FROM ticket_messages 
    WHERE ticket_id = ? 
    ORDER BY created_at ASC 
    LIMIT 1
");
$stmt->execute([$ticket['id']]);
$firstMessage = $stmt->fetch();

// Build WhatsApp message
$whatsappMessage = "Hi! I just created ticket " . $ticket['ticket_code'] . " for " . $ticket['service_name'] . ".\n\n";
$whatsappMessage .= $firstMessage['message_text'];

// WhatsApp URL (use the business number from config)
// For WhatsApp Business, you'll want to use your business number
$whatsappNumber = '2348012345678'; // Replace with your actual WhatsApp Business number
$whatsappURL = 'https://wa.me/' . $whatsappNumber . '?text=' . urlencode($whatsappMessage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Created Successfully</title>
    <meta http-equiv="refresh" content="5;url=<?php echo e($whatsappURL); ?>">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        
        .success-container {
            background: white;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            max-width: 500px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
        }
        
        h1 {
            color: #2c5f2d;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        
        .ticket-code {
            display: inline-block;
            background: #e8f5e9;
            color: #2c5f2d;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 1rem 0;
            letter-spacing: 1px;
        }
        
        .info-box {
            background: #f5f5f5;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            text-align: left;
        }
        
        .info-box p {
            margin: 0.5rem 0;
            color: #555;
        }
        
        .info-box strong {
            color: #333;
        }
        
        .whatsapp-redirect {
            background: #fff3cd;
            border-left: 4px solid #25D366;
            padding: 1rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }
        
        .whatsapp-redirect p {
            margin: 0.5rem 0;
            font-size: 0.95rem;
        }
        
        .btn-whatsapp {
            display: inline-block;
            background: #25D366;
            color: white;
            padding: 1rem 2rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 1rem;
            transition: background 0.2s;
            font-size: 1.1rem;
        }
        
        .btn-whatsapp:hover {
            background: #1fb855;
        }
        
        .btn-whatsapp::before {
            content: '💬 ';
        }
        
        .countdown {
            color: #666;
            font-size: 0.9rem;
            margin-top: 1rem;
        }
        
        @media (max-width: 768px) {
            .success-container {
                padding: 2rem 1.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
    <script>
        let countdown = 5;
        
        function updateCountdown() {
            const element = document.getElementById('countdown');
            if (element) {
                element.textContent = countdown;
                countdown--;
                
                if (countdown < 0) {
                    window.location.href = <?php echo json_encode($whatsappURL); ?>;
                }
            }
        }
        
        window.addEventListener('DOMContentLoaded', function() {
            setInterval(updateCountdown, 1000);
        });
    </script>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        
        <h1>Ticket Created Successfully!</h1>
        
        <p>Your support ticket has been created</p>
        
        <div class="ticket-code"><?php echo e($ticket['ticket_code']); ?></div>
        
        <div class="info-box">
            <p><strong>Service:</strong> <?php echo e($ticket['service_name']); ?></p>
            <p><strong>Type:</strong> <?php echo e($ticket['request_type']); ?></p>
            <p><strong>Status:</strong> Open</p>
            <p><strong>Created:</strong> <?php echo formatDate($ticket['created_at']); ?></p>
        </div>
        
        <div class="whatsapp-redirect">
            <p><strong>🚀 Redirecting to WhatsApp...</strong></p>
            <p>You'll be redirected to WhatsApp to continue your conversation with our support team.</p>
        </div>
        
        <a href="<?php echo e($whatsappURL); ?>" class="btn-whatsapp">
            Continue on WhatsApp
        </a>
        
        <p class="countdown">
            Auto-redirecting in <span id="countdown">5</span> seconds...
        </p>
    </div>
</body>
</html>
