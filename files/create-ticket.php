<?php
/**
 * Create Ticket Form
 * Greendome Engineering Ticketing System
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

// Get service from URL
$serviceSlug = $_GET['service'] ?? '';

// Fetch service details
$pdo = getDBConnection();
$stmt = $pdo->prepare("SELECT * FROM services WHERE service_slug = ? AND is_active = 1");
$stmt->execute([$serviceSlug]);
$service = $stmt->fetch();

if (!$service) {
    header("Location: index.php");
    exit;
}

$errors = [];
$formData = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $formData = [
        'service_id' => (int)$service['id'],
        'customer_name' => sanitizeInput($_POST['customer_name'] ?? ''),
        'customer_whatsapp' => sanitizeInput($_POST['customer_whatsapp'] ?? ''),
        'customer_email' => sanitizeInput($_POST['customer_email'] ?? ''),
        'request_type' => sanitizeInput($_POST['request_type'] ?? ''),
        'message' => sanitizeInput($_POST['message'] ?? '')
    ];
    
    // Validate inputs
    if (empty($formData['customer_name'])) {
        $errors[] = "Full name is required";
    }
    
    if (empty($formData['customer_whatsapp'])) {
        $errors[] = "WhatsApp number is required";
    } else {
        $validatedNumber = validateWhatsAppNumber($formData['customer_whatsapp']);
        if (!$validatedNumber) {
            $errors[] = "Invalid WhatsApp number. Please enter a valid Nigerian number";
        } else {
            $formData['customer_whatsapp'] = $validatedNumber;
        }
    }
    
    if (!empty($formData['customer_email']) && !validateEmail($formData['customer_email'])) {
        $errors[] = "Invalid email address";
    }
    
    if (empty($formData['request_type'])) {
        $errors[] = "Please select a request type";
    }
    
    if (empty($formData['message'])) {
        $errors[] = "Please describe your request";
    }
    
    // If no errors, create ticket
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // Generate unique ticket code
            $ticketCode = generateUniqueTicketCode();
            
            // Insert ticket
            $stmt = $pdo->prepare("
                INSERT INTO tickets (
                    ticket_code, service_id, customer_name, customer_whatsapp,
                    customer_email, request_type, status, priority
                ) VALUES (?, ?, ?, ?, ?, ?, 'Open', 'Normal')
            ");
            
            $stmt->execute([
                $ticketCode,
                $formData['service_id'],
                $formData['customer_name'],
                $formData['customer_whatsapp'],
                $formData['customer_email'],
                $formData['request_type']
            ]);
            
            $ticketId = $pdo->lastInsertId();
            
            // Insert first message
            $stmt = $pdo->prepare("
                INSERT INTO ticket_messages (
                    ticket_id, message_type, sender_type, message_text
                ) VALUES (?, 'incoming', 'customer', ?)
            ");
            
            $stmt->execute([$ticketId, $formData['message']]);
            
            // Log status
            logStatusChange($ticketId, null, 'Open', null, 'Ticket created');
            
            $pdo->commit();
            
            // Get ticket details for WhatsApp
            $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = ?");
            $stmt->execute([$ticketId]);
            $ticket = $stmt->fetch();
            
            // Send WhatsApp notification
            require_once __DIR__ . '/../includes/whatsapp.php';
            sendTicketCreationNotification($ticket);
            
            // Redirect to success page
            header("Location: ticket-success.php?ticket=" . urlencode($ticketCode));
            exit;
            
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Ticket creation error: " . $e->getMessage());
            $errors[] = "An error occurred while creating your ticket. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Support Ticket - <?php echo e($service['service_name']); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        
        .header {
            background: linear-gradient(135deg, #2c5f2d 0%, #97bc62 100%);
            color: white;
            padding: 1.5rem 1rem;
            text-align: center;
        }
        
        .container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .service-badge {
            display: inline-block;
            background: #e8f5e9;
            color: #2c5f2d;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        h2 {
            color: #2c5f2d;
            margin-bottom: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #555;
        }
        
        .required {
            color: #d32f2f;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #2c5f2d;
            box-shadow: 0 0 0 3px rgba(44, 95, 45, 0.1);
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .help-text {
            font-size: 0.85rem;
            color: #666;
            margin-top: 0.25rem;
        }
        
        .error-box {
            background: #ffebee;
            border-left: 4px solid #d32f2f;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
        }
        
        .error-box ul {
            margin-left: 1.5rem;
        }
        
        .error-box li {
            color: #c62828;
            margin: 0.25rem 0;
        }
        
        .btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .btn:hover {
            background: #1e4620;
        }
        
        .btn-secondary {
            background: #666;
            margin-left: 1rem;
        }
        
        .btn-secondary:hover {
            background: #555;
        }
        
        .whatsapp-info {
            background: #e8f5e9;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #25D366;
        }
        
        .whatsapp-info p {
            margin: 0.5rem 0;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 1rem auto;
            }
            
            .card {
                padding: 1.5rem;
            }
            
            .btn-secondary {
                margin-left: 0;
                margin-top: 0.5rem;
                display: block;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo APP_NAME; ?></h1>
        <p>Create Support Ticket</p>
    </div>
    
    <div class="container">
        <div class="card">
            <span class="service-badge"><?php echo e($service['service_name']); ?></span>
            
            <h2>Tell us how we can help</h2>
            
            <div class="whatsapp-info">
                <p><strong>📱 WhatsApp Support</strong></p>
                <p>After submitting this form, you'll be redirected to WhatsApp where we'll continue the conversation and track all communication.</p>
            </div>
            
            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <strong>⚠️ Please fix the following errors:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="customer_name">
                        Full Name <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="customer_name" 
                        name="customer_name"
                        value="<?php echo e($formData['customer_name'] ?? ''); ?>"
                        required
                    >
                </div>
                
                <div class="form-group">
                    <label for="customer_whatsapp">
                        WhatsApp Number <span class="required">*</span>
                    </label>
                    <input 
                        type="tel" 
                        id="customer_whatsapp" 
                        name="customer_whatsapp"
                        value="<?php echo e($formData['customer_whatsapp'] ?? ''); ?>"
                        placeholder="080XXXXXXXX or 234XXXXXXXXXX"
                        required
                    >
                    <div class="help-text">Enter your Nigerian WhatsApp number</div>
                </div>
                
                <div class="form-group">
                    <label for="customer_email">
                        Email (Optional)
                    </label>
                    <input 
                        type="email" 
                        id="customer_email" 
                        name="customer_email"
                        value="<?php echo e($formData['customer_email'] ?? ''); ?>"
                    >
                </div>
                
                <div class="form-group">
                    <label for="request_type">
                        Request Type <span class="required">*</span>
                    </label>
                    <select id="request_type" name="request_type" required>
                        <option value="">-- Select Type --</option>
                        <option value="Inquiry" <?php echo ($formData['request_type'] ?? '') === 'Inquiry' ? 'selected' : ''; ?>>General Inquiry</option>
                        <option value="Support" <?php echo ($formData['request_type'] ?? '') === 'Support' ? 'selected' : ''; ?>>Technical Support</option>
                        <option value="Payment" <?php echo ($formData['request_type'] ?? '') === 'Payment' ? 'selected' : ''; ?>>Payment Issue</option>
                        <option value="Order" <?php echo ($formData['request_type'] ?? '') === 'Order' ? 'selected' : ''; ?>>Place Order</option>
                        <option value="Training" <?php echo ($formData['request_type'] ?? '') === 'Training' ? 'selected' : ''; ?>>Training/Certification</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="message">
                        How can we help you? <span class="required">*</span>
                    </label>
                    <textarea 
                        id="message" 
                        name="message"
                        required
                    ><?php echo e($formData['message'] ?? ''); ?></textarea>
                    <div class="help-text">Please provide as much detail as possible</div>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn">Create Ticket & Continue on WhatsApp</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
