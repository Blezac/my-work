<?php

require_once 'includes/config.php';

// Authentication check
session_start();
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $service = sanitizeInput($_POST['service'] ?? '');
    $customer_name = sanitizeInput($_POST['customer_name'] ?? '');
    $phone = sanitizeInput($_POST['phone'] ?? '');
    $email = sanitizeInput($_POST['email'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    
    // Validation
    if (empty($service) || empty($customer_name) || empty($phone) || empty($message)) {
        $error = 'Please fill in all required fields.';
    } elseif (!in_array($service, ['Education', 'IT Services', 'CLACS-Farm', 'Automobile'])) {
        $error = 'Invalid service selected.';
    } elseif (!validatePhone($phone)) {
        $error = 'Please enter a valid phone number.';
    } elseif (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Create ticket
        $result = createTicket($service, $customer_name, $phone, $email, 'Staff', $message);
        
        if ($result['success']) {
            $ticket_code = $result['ticket_code'];
            
            // Format phone for WhatsApp
            $wa_phone = formatPhoneForWhatsApp($phone);
            
            // Create WhatsApp message
            $wa_message = "Hello " . $customer_name . "!\n\n";
            $wa_message .= "This is Greendome Engineering reaching out to you.\n\n";
            $wa_message .= " Ticket ID: " . $ticket_code . "\n";
            $wa_message .= " Service: " . $service . "\n\n";
            $wa_message .= " Message:\n" . $message . "\n\n";
            $wa_message .= "Please reply to this message to continue our conversation.\n";
            $wa_message .= "All our communication will be tracked under Ticket ID: " . $ticket_code;
            
            // URL encode the message
            $wa_message_encoded = urlencode($wa_message);
            
            // Redirect to WhatsApp with customer's number
            $whatsapp_url = "https://wa.me/" . $wa_phone . "?text=" . $wa_message_encoded;
            
            header("Location: " . $whatsapp_url);
            exit;
        } else {
            $error = $result['error'] ?? 'Failed to create ticket. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Contact - Greendome Engineering</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1><i class="logo"></i> Greendome Engineering</h1>
        <p>Staff Portal - Initiate Customer Contact</p>
    </div>

    <div class="container">
        <div style="background: #fff3cd; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
            <strong><i class="fas fa-user"></i> Logged in as:</strong> <?php echo htmlspecialchars($_SESSION['staff_name']); ?>
            <span style="float: right;">
                <a href="admin.php" style="color: #2c5f2d; text-decoration: none; font-weight: 600; margin-right: 15px;">
                    <i class="fas fa-chart-line"></i> View Dashboard
                </a>
                <a href="logout.php" style="color: #dc3545; text-decoration: none; font-weight: 600;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </span>
        </div>

        <div class="card">
            <h2 class="card-title">Initiate Customer Contact</h2>
            
            <div class="alert alert-info">
                Use this form to reach out to customers. A ticket will be created and you'll be redirected to WhatsApp to continue the conversation.
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                     <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                     <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="" id="staffForm">
                <div class="form-group">
                    <label for="service" class="required">Service Type</label>
                    <select name="service" id="service" required>
                        <option value="">-- Select Service --</option>
                        <option value="Education" <?php echo (isset($_POST['service']) && $_POST['service'] === 'Education') ? 'selected' : ''; ?>>
                            Education
                        </option>
                        <option value="IT Services" <?php echo (isset($_POST['service']) && $_POST['service'] === 'IT Services') ? 'selected' : ''; ?>>
                            IT Services
                        </option>
                        <option value="CLACS-Farm" <?php echo (isset($_POST['service']) && $_POST['service'] === 'CLACS-Farm') ? 'selected' : ''; ?>>
                            CLACS-Farm
                        </option>
                        <option value="Automobile" <?php echo (isset($_POST['service']) && $_POST['service'] === 'Automobile') ? 'selected' : ''; ?>>
                            Automobile
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="customer_name" class="required">Customer Name</label>
                    <input 
                        type="text" 
                        name="customer_name" 
                        id="customer_name" 
                        placeholder="Enter customer's full name"
                        value="<?php echo isset($_POST['customer_name']) ? htmlspecialchars($_POST['customer_name']) : ''; ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="phone" class="required">Customer WhatsApp Number</label>
                    <input 
                        type="tel" 
                        name="phone" 
                        id="phone" 
                        placeholder="e.g., 09012345678"
                        value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                        required
                    >
                    <small style="color: #7f8c8d; font-size: 12px; display: block; margin-top: 5px;">
                        Customer's WhatsApp number (you'll be redirected to chat with them)
                    </small>
                </div>

                <div class="form-group">
                    <label for="email">Customer Email (Optional)</label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        placeholder="customer@example.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="message" class="required">Your Message to Customer</label>
                    <textarea 
                        name="message" 
                        id="message" 
                        placeholder="Enter your message to the customer..."
                        required
                    ><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                    <small style="color: #7f8c8d; font-size: 12px; display: block; margin-top: 5px;">
                        This message will be sent to the customer on WhatsApp
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Create Ticket & Contact on WhatsApp
                </button>
            </form>
        </div>

        <div class="card">
            <h3 style="font-size: 16px; margin-bottom: 15px; color: #2c5f2d;">
                 Process Flow
            </h3>
            <ol style="padding-left: 20px; color: #7f8c8d; font-size: 14px; line-height: 1.8;">
                <li>Fill in customer details and your message</li>
                <li>System creates a ticket with unique Ticket ID</li>
                <li>Your message is logged under the ticket</li>
                <li>You're redirected to WhatsApp to contact the customer</li>
                <li>Customer receives message with Ticket ID</li>
                <li>All subsequent messages are tracked under this ticket</li>
            </ol>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Greendome Engineering Technology & Science</p>
        <p style="font-size: 11px; margin-top: 5px;">Staff Portal - Internal Use Only</p>
    </div>

    <script>
        // Simple form validation
        document.getElementById('staffForm').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value.trim();
            const message = document.getElementById('message').value.trim();
            
            if (phone.length < 10) {
                e.preventDefault();
                alert('Please enter a valid phone number (at least 10 digits)');
                return false;
            }
            
            if (message.length < 10) {
                e.preventDefault();
                alert('Please provide more details in your message (at least 10 characters)');
                return false;
            }
            
            // Confirm before sending
            const customerName = document.getElementById('customer_name').value;
            if (!confirm('Create ticket and contact ' + customerName + ' on WhatsApp?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>
