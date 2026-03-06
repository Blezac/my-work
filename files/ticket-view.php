<?php
/**
 * Ticket View & Reply
 * Greendome Engineering Ticketing System
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/whatsapp.php';

session_name(SESSION_NAME);
session_start();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$currentStaff = getCurrentStaff();
$ticketId = $_GET['id'] ?? 0;

$pdo = getDBConnection();

// Fetch ticket details
$stmt = $pdo->prepare("
    SELECT t.*, s.service_name, su.full_name as assigned_to_name
    FROM tickets t
    LEFT JOIN services s ON t.service_id = s.id
    LEFT JOIN staff_users su ON t.assigned_to = su.id
    WHERE t.id = ?
");
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header("Location: dashboard.php");
    exit;
}

// Fetch all messages
$stmt = $pdo->prepare("
    SELECT tm.*, su.full_name as staff_name
    FROM ticket_messages tm
    LEFT JOIN staff_users su ON tm.sender_id = su.id
    WHERE tm.ticket_id = ?
    ORDER BY tm.created_at ASC
");
$stmt->execute([$ticketId]);
$messages = $stmt->fetchAll();

// Mark incoming messages as read
$stmt = $pdo->prepare("
    UPDATE ticket_messages 
    SET is_read = 1 
    WHERE ticket_id = ? AND message_type = 'incoming' AND is_read = 0
");
$stmt->execute([$ticketId]);

// Get all staff for assignment
$staffList = $pdo->query("SELECT * FROM staff_users WHERE is_active = 1")->fetchAll();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    // Send reply
    if ($action === 'reply') {
        $replyMessage = trim($_POST['reply_message'] ?? '');
        
        if (!empty($replyMessage)) {
            try {
                $pdo->beginTransaction();
                
                // Send via WhatsApp
                $whatsappMsgId = sendWhatsAppMessage($ticket['customer_whatsapp'], $replyMessage, $ticketId);
                
                if ($whatsappMsgId) {
                    // Log the message (already done in sendWhatsAppMessage, but ensure sender_id is set)
                    $stmt = $pdo->prepare("
                        UPDATE ticket_messages 
                        SET sender_id = ? 
                        WHERE whatsapp_message_id = ? AND ticket_id = ?
                    ");
                    $stmt->execute([$currentStaff['id'], $whatsappMsgId, $ticketId]);
                    
                    // Update ticket timestamp
                    $stmt = $pdo->prepare("UPDATE tickets SET updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$ticketId]);
                    
                    $pdo->commit();
                    setFlashMessage('success', 'Reply sent successfully');
                } else {
                    $pdo->rollBack();
                    setFlashMessage('error', 'Failed to send WhatsApp message');
                }
            } catch (Exception $e) {
                $pdo->rollBack();
                error_log("Reply error: " . $e->getMessage());
                setFlashMessage('error', 'An error occurred while sending reply');
            }
            
            header("Location: ticket-view.php?id=" . $ticketId);
            exit;
        }
    }
    
    // Update status
    if ($action === 'update_status') {
        $newStatus = $_POST['status'] ?? '';
        
        if (!empty($newStatus) && $newStatus !== $ticket['status']) {
            $oldStatus = $ticket['status'];
            
            $stmt = $pdo->prepare("UPDATE tickets SET status = ? WHERE id = ?");
            $stmt->execute([$newStatus, $ticketId]);
            
            logStatusChange($ticketId, $oldStatus, $newStatus, $currentStaff['id']);
            addSystemMessage($ticketId, "Status changed from '$oldStatus' to '$newStatus'");
            
            // Send WhatsApp notification
            sendStatusChangeNotification($ticketId, $newStatus);
            
            setFlashMessage('success', 'Status updated successfully');
            header("Location: ticket-view.php?id=" . $ticketId);
            exit;
        }
    }
    
    // Update priority
    if ($action === 'update_priority') {
        $newPriority = $_POST['priority'] ?? '';
        
        if (!empty($newPriority)) {
            $stmt = $pdo->prepare("UPDATE tickets SET priority = ? WHERE id = ?");
            $stmt->execute([$newPriority, $ticketId]);
            
            addSystemMessage($ticketId, "Priority changed to '$newPriority'");
            
            setFlashMessage('success', 'Priority updated successfully');
            header("Location: ticket-view.php?id=" . $ticketId);
            exit;
        }
    }
    
    // Assign ticket
    if ($action === 'assign') {
        $assignToId = $_POST['assign_to'] ?? null;
        $oldAssignedTo = $ticket['assigned_to'];
        
        if ($assignToId !== $oldAssignedTo) {
            $stmt = $pdo->prepare("UPDATE tickets SET assigned_to = ? WHERE id = ?");
            $stmt->execute([$assignToId ?: null, $ticketId]);
            
            logAssignment($ticketId, $oldAssignedTo, $assignToId, $currentStaff['id']);
            
            if ($assignToId) {
                $stmt = $pdo->prepare("SELECT full_name FROM staff_users WHERE id = ?");
                $stmt->execute([$assignToId]);
                $assignedUser = $stmt->fetch();
                addSystemMessage($ticketId, "Ticket assigned to " . $assignedUser['full_name']);
            } else {
                addSystemMessage($ticketId, "Ticket unassigned");
            }
            
            setFlashMessage('success', 'Assignment updated successfully');
            header("Location: ticket-view.php?id=" . $ticketId);
            exit;
        }
    }
}

// Refresh ticket data
$stmt = $pdo->prepare("
    SELECT t.*, s.service_name, su.full_name as assigned_to_name
    FROM tickets t
    LEFT JOIN services s ON t.service_id = s.id
    LEFT JOIN staff_users su ON t.assigned_to = su.id
    WHERE t.id = ?
");
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

// Re-fetch messages
$stmt = $pdo->prepare("
    SELECT tm.*, su.full_name as staff_name
    FROM ticket_messages tm
    LEFT JOIN staff_users su ON tm.sender_id = su.id
    WHERE tm.ticket_id = ?
    ORDER BY tm.created_at ASC
");
$stmt->execute([$ticketId]);
$messages = $stmt->fetchAll();

$flashMessage = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket <?php echo e($ticket['ticket_code']); ?> - <?php echo APP_NAME; ?></title>
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
        
        .navbar {
            background: #2c5f2d;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar h1 {
            font-size: 1.25rem;
        }
        
        .navbar a {
            color: white;
            text-decoration: none;
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .back-link {
            margin-bottom: 1rem;
        }
        
        .back-link a {
            color: #2c5f2d;
            text-decoration: none;
        }
        
        .ticket-layout {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 1.5rem;
        }
        
        .main-content {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .ticket-header {
            padding: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .ticket-code {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c5f2d;
            margin-bottom: 0.5rem;
        }
        
        .customer-info {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }
        
        .customer-info div {
            flex: 1;
        }
        
        .customer-info label {
            font-size: 0.8rem;
            color: #666;
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .customer-info strong {
            font-size: 0.95rem;
        }
        
        .messages-container {
            padding: 1.5rem;
            max-height: 60vh;
            overflow-y: auto;
        }
        
        .message {
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
        }
        
        .message-incoming {
            align-items: flex-start;
        }
        
        .message-outgoing {
            align-items: flex-end;
        }
        
        .message-system {
            align-items: center;
        }
        
        .message-bubble {
            max-width: 70%;
            padding: 0.875rem 1rem;
            border-radius: 12px;
            position: relative;
        }
        
        .message-incoming .message-bubble {
            background: #f0f0f0;
            color: #333;
        }
        
        .message-outgoing .message-bubble {
            background: #2c5f2d;
            color: white;
        }
        
        .message-system .message-bubble {
            background: #fff3cd;
            color: #856404;
            font-size: 0.85rem;
            text-align: center;
            max-width: 90%;
        }
        
        .message-meta {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            opacity: 0.7;
        }
        
        .message-sender {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 0.85rem;
        }
        
        .reply-form {
            padding: 1.5rem;
            border-top: 2px solid #eee;
        }
        
        .reply-form textarea {
            width: 100%;
            padding: 0.875rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            resize: vertical;
            min-height: 100px;
        }
        
        .reply-form textarea:focus {
            outline: none;
            border-color: #2c5f2d;
        }
        
        .reply-form button {
            margin-top: 1rem;
            padding: 0.75rem 2rem;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
        }
        
        .reply-form button:hover {
            background: #1e4620;
        }
        
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .sidebar-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sidebar-card h3 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-update {
            width: 100%;
            padding: 0.5rem;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85rem;
        }
        
        .btn-update:hover {
            background: #1e4620;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-primary { background: #e3f2fd; color: #1976d2; }
        .badge-info { background: #e1f5fe; color: #0288d1; }
        .badge-warning { background: #fff3cd; color: #f57c00; }
        .badge-success { background: #e8f5e9; color: #388e3c; }
        .badge-secondary { background: #e0e0e0; color: #616161; }
        .badge-light { background: #f5f5f5; color: #757575; }
        .badge-danger { background: #ffebee; color: #c62828; }
        
        .flash-message {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 4px;
        }
        
        .flash-success {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            color: #2e7d32;
        }
        
        .flash-error {
            background: #ffebee;
            border-left: 4px solid #f44336;
            color: #c62828;
        }
        
        @media (max-width: 1024px) {
            .ticket-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📊 <?php echo APP_NAME; ?></h1>
        <a href="dashboard.php">← Back to Dashboard</a>
    </div>
    
    <div class="container">
        <?php if ($flashMessage): ?>
            <div class="flash-message flash-<?php echo e($flashMessage['type']); ?>">
                <?php echo e($flashMessage['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="ticket-layout">
            <div class="main-content">
                <div class="ticket-header">
                    <div class="ticket-code"><?php echo e($ticket['ticket_code']); ?></div>
                    <div><?php echo e($ticket['service_name']); ?> • <?php echo e($ticket['request_type']); ?></div>
                    
                    <div class="customer-info">
                        <div>
                            <label>Customer Name</label>
                            <strong><?php echo e($ticket['customer_name']); ?></strong>
                        </div>
                        <div>
                            <label>WhatsApp</label>
                            <strong><?php echo formatWhatsAppNumber($ticket['customer_whatsapp']); ?></strong>
                        </div>
                        <?php if ($ticket['customer_email']): ?>
                        <div>
                            <label>Email</label>
                            <strong><?php echo e($ticket['customer_email']); ?></strong>
                        </div>
                        <?php endif; ?>
                        <div>
                            <label>Created</label>
                            <strong><?php echo formatDate($ticket['created_at']); ?></strong>
                        </div>
                    </div>
                </div>
                
                <div class="messages-container">
                    <?php foreach ($messages as $message): ?>
                        <div class="message message-<?php echo e($message['message_type']); ?>">
                            <div class="message-bubble">
                                <?php if ($message['sender_type'] === 'staff'): ?>
                                    <div class="message-sender"><?php echo e($message['staff_name'] ?? 'Staff'); ?></div>
                                <?php elseif ($message['sender_type'] === 'customer'): ?>
                                    <div class="message-sender"><?php echo e($ticket['customer_name']); ?></div>
                                <?php endif; ?>
                                
                                <?php echo nl2br(e($message['message_text'])); ?>
                                
                                <div class="message-meta">
                                    <?php echo formatDate($message['created_at'], 'd M Y, h:i A'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <?php if ($ticket['status'] !== 'Closed'): ?>
                <div class="reply-form">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="reply">
                        <textarea 
                            name="reply_message" 
                            placeholder="Type your reply here... (will be sent via WhatsApp)"
                            required
                        ></textarea>
                        <button type="submit">📱 Send Reply via WhatsApp</button>
                    </form>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="sidebar">
                <div class="sidebar-card">
                    <h3>Status</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="update_status">
                        <div class="form-group">
                            <select name="status" onchange="this.form.submit()">
                                <option value="Open" <?php echo $ticket['status'] === 'Open' ? 'selected' : ''; ?>>Open</option>
                                <option value="In Progress" <?php echo $ticket['status'] === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Waiting on Customer" <?php echo $ticket['status'] === 'Waiting on Customer' ? 'selected' : ''; ?>>Waiting on Customer</option>
                                <option value="Resolved" <?php echo $ticket['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                <option value="Closed" <?php echo $ticket['status'] === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                            </select>
                        </div>
                    </form>
                </div>
                
                <div class="sidebar-card">
                    <h3>Priority</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="update_priority">
                        <div class="form-group">
                            <select name="priority" onchange="this.form.submit()">
                                <option value="Low" <?php echo $ticket['priority'] === 'Low' ? 'selected' : ''; ?>>Low</option>
                                <option value="Normal" <?php echo $ticket['priority'] === 'Normal' ? 'selected' : ''; ?>>Normal</option>
                                <option value="High" <?php echo $ticket['priority'] === 'High' ? 'selected' : ''; ?>>High</option>
                                <option value="Urgent" <?php echo $ticket['priority'] === 'Urgent' ? 'selected' : ''; ?>>Urgent</option>
                            </select>
                        </div>
                    </form>
                </div>
                
                <div class="sidebar-card">
                    <h3>Assign To</h3>
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="assign">
                        <div class="form-group">
                            <select name="assign_to" onchange="this.form.submit()">
                                <option value="">Unassigned</option>
                                <?php foreach ($staffList as $staff): ?>
                                    <option value="<?php echo $staff['id']; ?>" <?php echo $ticket['assigned_to'] == $staff['id'] ? 'selected' : ''; ?>>
                                        <?php echo e($staff['full_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
