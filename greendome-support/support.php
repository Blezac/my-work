<?php
/**
 * =================================================================
 * GREENDOME ENGINEERING - CUSTOMER SUPPORT PORTAL
 * Professional Production System
 * =================================================================
 */

require_once 'includes/config.php';
session_start();

$mysqli = getDBConnection();
$error = '';
$success = '';
$ticket = null;
$messages = array();
$ticket_code = '';

// AJAX endpoint
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    header('Content-Type: application/json');
    
    if (isset($_GET['ticket'])) {
        $ticket_code = sanitizeInput($_GET['ticket']);
        $last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
        
        $stmt = $mysqli->prepare("SELECT id FROM tickets WHERE ticket_code = ? AND is_deleted = 0");
        $stmt->bind_param("s", $ticket_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $ticket_data = $result->fetch_assoc();
        $stmt->close();
        
        if ($ticket_data) {
            $stmt = $mysqli->prepare("SELECT * FROM messages WHERE ticket_id = ? AND id > ? ORDER BY created_at ASC");
            $stmt->bind_param("ii", $ticket_data['id'], $last_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $new_messages = array();
            while ($row = $result->fetch_assoc()) {
                $new_messages[] = $row;
            }
            $stmt->close();
            
            echo json_encode(array('success' => true, 'messages' => $new_messages));
        } else {
            echo json_encode(array('success' => false, 'messages' => array()));
        }
    }
    exit;
}

// Fetch ticket
if (isset($_GET['ticket'])) {
    $ticket_code = sanitizeInput($_GET['ticket']);
    $stmt = $mysqli->prepare("SELECT * FROM tickets WHERE ticket_code = ? AND is_deleted = 0");
    $stmt->bind_param("s", $ticket_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $ticket = $result->fetch_assoc();
    $stmt->close();
    
    if ($ticket) {
        $stmt = $mysqli->prepare("SELECT * FROM messages WHERE ticket_id = ? ORDER BY created_at ASC");
        $stmt->bind_param("i", $ticket['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        $stmt->close();
    } else {
        $error = "Ticket not found.";
    }
}

// Create ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $service = sanitizeInput(isset($_POST['service']) ? $_POST['service'] : '');
    $name = sanitizeInput(isset($_POST['customer_name']) ? $_POST['customer_name'] : '');
    $phone = sanitizeInput(isset($_POST['phone']) ? $_POST['phone'] : '');
    $email = sanitizeInput(isset($_POST['email']) ? $_POST['email'] : '');
    $message = sanitizeInput(isset($_POST['message']) ? $_POST['message'] : '');
    
    if (empty($service) || empty($name) || empty($phone) || empty($message)) {
        $error = "Please fill all required fields.";
    } else {
        $new_code = generateTicketCode();
        $stmt = $mysqli->prepare("INSERT INTO tickets (ticket_code, service, customer_name, phone, email, initiated_by, status) VALUES (?, ?, ?, ?, ?, 'Customer', 'New')");
        $stmt->bind_param("sssss", $new_code, $service, $name, $phone, $email);
        
        if ($stmt->execute()) {
            $new_id = $mysqli->insert_id;
            $stmt->close();
            
            $stmt = $mysqli->prepare("INSERT INTO messages (ticket_id, sender, message) VALUES (?, 'Customer', ?)");
            $stmt->bind_param("is", $new_id, $message);
            $stmt->execute();
            $stmt->close();
            
            header("Location: support.php?ticket=" . urlencode($new_code) . "&new=1");
            exit;
        }
    }
}

// Add reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'reply') {
    $msg = sanitizeInput(isset($_POST['message']) ? $_POST['message'] : '');
    $tid = isset($_POST['ticket_id']) ? intval($_POST['ticket_id']) : 0;
    
    if (empty($msg)) {
        $error = "Please enter a message.";
    } elseif ($tid > 0) {
        $stmt = $mysqli->prepare("INSERT INTO messages (ticket_id, sender, message) VALUES (?, 'Customer', ?)");
        $stmt->bind_param("is", $tid, $msg);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $mysqli->prepare("UPDATE tickets SET status = 'Follow-up', updated_at = NOW() WHERE id = ?");
        $stmt->bind_param("i", $tid);
        $stmt->execute();
        $stmt->close();
        
        header("Location: support.php?ticket=" . urlencode($ticket['ticket_code']) . "&replied=1");
        exit;
    }
}

if (isset($_GET['new'])) $success = "Ticket created successfully!";
if (isset($_GET['replied'])) $success = "Message sent!";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $ticket ? 'Ticket ' . htmlspecialchars($ticket['ticket_code']) : 'Support'; ?> - Greendome</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
:root{--primary:#2c5f2d;--primary-light:#97bc62;--bg:#f5f7fa;--border:#e0e0e0;--text:#2c3e50;--text-light:#6c757d;--white:#fff;--shadow:0 2px 10px rgba(0,0,0,0.08)}*{margin:0;padding:0;box-sizing:border-box}body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif;background:var(--bg);color:var(--text);line-height:1.6}.top-nav{background:var(--white);box-shadow:var(--shadow);position:sticky;top:0;z-index:1000}.nav-container{max-width:1200px;margin:0 auto;padding:0 20px;display:flex;justify-content:space-between;align-items:center;height:70px}.nav-brand{display:flex;align-items:center;gap:12px;font-weight:700;font-size:20px;color:var(--primary)}.brand-icon{width:45px;height:45px;background:linear-gradient(135deg,var(--primary),var(--primary-light));border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:24px}.nav-links{display:flex;gap:10px}.nav-link{padding:10px 20px;text-decoration:none;color:var(--text);border-radius:8px;transition:0.3s;display:flex;align-items:center;gap:8px}.nav-link:hover{background:var(--bg);color:var(--primary)}.nav-link.active{background:var(--primary);color:white}.main{min-height:calc(100vh - 200px);padding:40px 0}.container{max-width:1200px;margin:0 auto;padding:0 20px}.page-header{margin-bottom:30px}.page-header h1{font-size:32px;margin-bottom:8px}.page-header p{color:var(--text-light)}.card{background:var(--white);border-radius:12px;box-shadow:var(--shadow);margin-bottom:25px}.card-header{padding:20px 25px;border-bottom:2px solid var(--border);display:flex;justify-content:space-between;align-items:center}.card-header h2{font-size:20px;font-weight:700;color:var(--primary)}.card-body{padding:25px}.info-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px}.info-item{padding:15px;background:var(--bg);border-radius:8px;border-left:3px solid var(--primary)}.info-label{display:block;font-size:12px;color:var(--text-light);text-transform:uppercase;margin-bottom:5px}.info-value{display:block;font-size:16px;font-weight:600}.chat-box{background:#f8f9fa;border-radius:10px;padding:20px;min-height:400px;max-height:600px;overflow-y:auto;margin-bottom:20px}.message{display:flex;flex-direction:column;margin-bottom:20px;animation:slideIn 0.3s}@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}.message.customer{align-items:flex-end}.message.staff{align-items:flex-start}.message-sender{font-size:13px;font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:6px}.message.customer .message-sender{color:var(--primary)}.message.staff .message-sender{color:#d32f2f}.message-bubble{max-width:70%;padding:12px 18px;border-radius:15px;box-shadow:var(--shadow)}.message.customer .message-bubble{background:linear-gradient(135deg,var(--primary),var(--primary-light));color:white;border-radius:15px 15px 0 15px}.message.staff .message-bubble{background:white;border-radius:15px 15px 15px 0;border-left:3px solid var(--primary)}.message.staff.unread .message-bubble{background:#d4edda;animation:pulse 1s ease 3}@keyframes pulse{0%,100%{transform:scale(1)}50%{transform:scale(1.02)}}.message-text{font-size:14px;line-height:1.6}.message-time{font-size:11px;opacity:0.7;margin-top:5px}.message.customer .message-time{text-align:right}.typing{display:flex;align-items:center;gap:10px;padding:15px;background:white;border-radius:15px 15px 15px 0;width:fit-content;box-shadow:var(--shadow)}.typing-dots{display:flex;gap:4px}.typing-dots span{width:8px;height:8px;background:var(--primary);border-radius:50%;animation:bounce 1.4s infinite}.typing-dots span:nth-child(2){animation-delay:0.2s}.typing-dots span:nth-child(3){animation-delay:0.4s}@keyframes bounce{0%,60%,100%{transform:translateY(0)}30%{transform:translateY(-10px)}}.form-group{margin-bottom:20px}.form-group label{display:block;font-weight:600;margin-bottom:8px}.form-group label .required{color:#dc3545}.form-group input,.form-group select,.form-group textarea{width:100%;padding:12px 15px;border:2px solid var(--border);border-radius:8px;font-size:15px;transition:0.3s;font-family:inherit}.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(44,95,45,0.1)}.form-group textarea{min-height:120px;resize:vertical}.btn{padding:12px 24px;border:none;border-radius:8px;font-size:15px;font-weight:600;cursor:pointer;transition:0.3s;display:inline-flex;align-items:center;gap:8px;text-decoration:none}.btn-primary{background:linear-gradient(135deg,var(--primary),var(--primary-light));color:white}.btn-primary:hover{transform:translateY(-2px);box-shadow:0 5px 15px rgba(44,95,45,0.3)}.btn-secondary{background:var(--bg);color:var(--text)}.btn-secondary:hover{background:#e0e0e0}.btn-block{width:100%;justify-content:center}.badge{display:inline-block;padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;text-transform:uppercase}.badge.new{background:#e3f2fd;color:#1976d2}.badge.enquiry{background:#fff3e0;color:#f57c00}.badge.follow-up{background:#e8f5e9;color:#388e3c}.badge.responded{background:#e0f2f1;color:#00796b}.badge.closed{background:#f5f5f5;color:#616161}.alert{padding:15px 20px;border-radius:8px;margin-bottom:20px;display:flex;align-items:center;gap:10px}.alert.success{background:#d4edda;color:#155724;border-left:4px solid #28a745}.alert.error{background:#f8d7da;color:#721c24;border-left:4px solid #dc3545}.empty-state{text-align:center;padding:60px 20px;color:var(--text-light)}.empty-state i{font-size:64px;opacity:0.3;margin-bottom:15px}.search-form{display:flex;gap:10px}.search-form input{flex:1}.footer{background:var(--white);padding:30px 20px;text-align:center;color:var(--text-light);border-top:1px solid var(--border)}.footer p{margin:5px 0}.text-center{text-align:center}@media(max-width:768px){.nav-container{padding:0 15px}.brand-text{display:none}.container{padding:0 15px}.card-body{padding:20px}.message-bubble{max-width:85%}.info-grid{grid-template-columns:1fr}.search-form{flex-direction:column}}
</style>
</head>
<body>

<nav class="top-nav">
<div class="nav-container">
<div class="nav-brand">
<div class="brand-icon">G</div>
<span class="brand-text">Greendome Engineering</span>
</div>
<div class="nav-links">
<a href="https://greendomeng.com" class="nav-link"><i class="bi bi-house"></i> Home</a>
<a href="support.php" class="nav-link active"><i class="bi bi-headset"></i> Support</a>
</div>
</div>
</nav>

<main class="main">
<div class="container">

<?php if($ticket): ?>
<div class="page-header">
<h1><i class="bi bi-ticket-detailed"></i> Ticket <?=htmlspecialchars($ticket['ticket_code'])?></h1>
<p>Track your support request</p>
</div>

<?php if($success): ?><div class="alert success"><i class="bi bi-check-circle-fill"></i><span><?=$success?></span></div><?php endif; ?>
<?php if($error): ?><div class="alert error"><i class="bi bi-exclamation-circle-fill"></i><span><?=$error?></span></div><?php endif; ?>

<div class="card">
<div class="card-header">
<h2>Ticket Information</h2>
<span class="badge <?=strtolower($ticket['status'])?>"><?=htmlspecialchars($ticket['status'])?></span>
</div>
<div class="card-body">
<div class="info-grid">
<div class="info-item"><span class="info-label">Customer</span><span class="info-value"><?=htmlspecialchars($ticket['customer_name'])?></span></div>
<div class="info-item"><span class="info-label">Service</span><span class="info-value"><?=htmlspecialchars($ticket['service'])?></span></div>
<div class="info-item"><span class="info-label">Phone</span><span class="info-value"><?=htmlspecialchars($ticket['phone'])?></span></div>
<div class="info-item"><span class="info-label">Created</span><span class="info-value"><?=date('M d, Y H:i',strtotime($ticket['created_at']))?></span></div>
</div>
</div>
</div>

<div class="card">
<div class="card-header"><h2><i class="bi bi-chat-left-text"></i> Conversation</h2></div>
<div class="card-body">
<div class="chat-box" id="chatBox">
<?php if(empty($messages)): ?>
<div class="empty-state"><i class="bi bi-chat-dots"></i><p>No messages yet</p></div>
<?php else: ?>
<?php foreach($messages as $msg):
$is_cust=($msg['sender']==='Customer');
$sender=$is_cust?htmlspecialchars($ticket['customer_name']):(!empty($msg['staff_name'])?htmlspecialchars($msg['staff_name']):'Greendome Support');
$cls=$is_cust?'customer':'staff';
?>
<div class="message <?=$cls?>" data-id="<?=$msg['id']?>">
<div class="message-sender"><i class="bi bi-<?=$is_cust?'person-circle':'headset'?>"></i> <?=$sender?></div>
<div class="message-bubble">
<div class="message-text"><?=nl2br(htmlspecialchars($msg['message']))?></div>
<div class="message-time"><?=date('M d, H:i',strtotime($msg['created_at']))?></div>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
<div class="typing" id="typing" style="display:none;"><div class="typing-dots"><span></span><span></span><span></span></div>Support is typing...</div>
</div>

<?php if($ticket['status']!=='Closed'): ?>
<form method="POST" id="msgForm">
<input type="hidden" name="action" value="reply">
<input type="hidden" name="ticket_id" value="<?=$ticket['id']?>">
<div class="form-group">
<textarea name="message" id="msgInput" placeholder="Type your message..." required></textarea>
</div>
<button type="submit" class="btn btn-primary"><i class="bi bi-send-fill"></i> Send Message</button>
</form>
<?php else: ?>
<div class="alert info"><i class="bi bi-lock-fill"></i> This ticket is closed.</div>
<?php endif; ?>
</div>
</div>

<div class="text-center">
<a href="support.php" class="btn btn-secondary"><i class="bi bi-plus-circle"></i> New Ticket</a>
</div>

<?php else: ?>
<div class="page-header">
<h1><i class="bi bi-headset"></i> Customer Support</h1>
<p>Get help from our team</p>
</div>

<?php if($error): ?><div class="alert error"><i class="bi bi-exclamation-circle-fill"></i><span><?=$error?></span></div><?php endif; ?>

<div class="card">
<div class="card-header"><h2><i class="bi bi-ticket-detailed"></i> Create Support Ticket</h2></div>
<div class="card-body">
<form method="POST">
<input type="hidden" name="action" value="create">
<div class="form-group">
<label><i class="bi bi-buildings"></i> Department <span class="required">*</span></label>
<select name="service" required>
<option value="">-- Select --</option>
<option value="Education">Education</option>
<option value="IT Services">IT Services</option>
<option value="CLACS-Farm">CLACS-Farm</option>
<option value="Automobile">Automobile</option>
</select>
</div>
<div class="form-group">
<label><i class="bi bi-person"></i> Name <span class="required">*</span></label>
<input type="text" name="customer_name" placeholder="Your name" required>
</div>
<div class="form-group">
<label><i class="bi bi-telephone"></i> Phone <span class="required">*</span></label>
<input type="tel" name="phone" id="phoneInput" placeholder="09012345678" required>
</div>
<div class="form-group">
<label><i class="bi bi-envelope"></i> Email</label>
<input type="email" name="email" placeholder="Optional">
</div>
<div class="form-group">
<label><i class="bi bi-chat-left-text"></i> Message <span class="required">*</span></label>
<textarea name="message" placeholder="How can we help?" required></textarea>
</div>
<button type="submit" class="btn btn-primary btn-block"><i class="bi bi-send-fill"></i> Submit</button>
</form>
</div>
</div>

<div class="card">
<div class="card-header"><h2><i class="bi bi-search"></i> Have a Ticket?</h2></div>
<div class="card-body">
<form method="GET" class="search-form">
<input type="text" name="ticket" placeholder="GETS-123456" required>
<button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> View</button>
</form>
</div>
</div>
<?php endif; ?>

</div>
</main>

<footer class="footer">
<div class="container">
<p>&copy; <?=date('Y')?> Greendome Engineering. All rights reserved.</p>
<p><i class="bi bi-shield-check"></i> Secure & Protected</p>
</div>
</footer>

<script>
function scrollToBottom(){const b=document.getElementById('chatBox');if(b)b.scrollTop=b.scrollHeight;}
scrollToBottom();
const phoneInput=document.getElementById('phoneInput');
if(phoneInput){phoneInput.addEventListener('input',function(){this.value=this.value.replace(/[^0-9+]/g,'');});}
<?php if($ticket): ?>
let lastId=<?=empty($messages)?0:$messages[count($messages)-1]['id']?>;
const ticketCode='<?=addslashes($ticket_code)?>';
const customerName='<?=addslashes(htmlspecialchars($ticket['customer_name']))?>';
function showTyping(){const t=document.getElementById('typing');if(t){t.style.display='flex';scrollToBottom();}}
function hideTyping(){const t=document.getElementById('typing');if(t)t.style.display='none';}
function checkNew(){
fetch('support.php?ajax=1&ticket='+encodeURIComponent(ticketCode)+'&last_id='+lastId)
.then(r=>r.json())
.then(d=>{
if(d.success&&d.messages&&d.messages.length>0){
showTyping();
setTimeout(()=>{
hideTyping();
const box=document.getElementById('chatBox');
d.messages.forEach(msg=>{
const isCust=msg.sender==='Customer';
const sender=isCust?customerName:(msg.staff_name||'Greendome Support');
const cls=isCust?'customer':'staff unread';
const div=document.createElement('div');
div.className='message '+cls;
div.setAttribute('data-id',msg.id);
let html='<div class="message-sender"><i class="bi bi-'+(isCust?'person-circle':'headset')+'"></i> '+sender+'</div>';
html+='<div class="message-bubble"><div class="message-text">'+msg.message.replace(/\n/g,'<br>')+'</div>';
html+='<div class="message-time">'+formatTime(msg.created_at)+'</div></div>';
div.innerHTML=html;
box.appendChild(div);
lastId=msg.id;
if(!isCust){setTimeout(()=>div.classList.remove('unread'),3000);}
});
scrollToBottom();
},1500);
}
}).catch(e=>console.error('Error:',e));
}
function formatTime(dt){const d=new Date(dt);return d.toLocaleString('en-US',{month:'short',day:'numeric',hour:'2-digit',minute:'2-digit'});}
setInterval(checkNew,3000);
const form=document.getElementById('msgForm');
if(form){form.addEventListener('submit',function(){setTimeout(()=>{const f=document.getElementById('msgInput');if(f)f.value='';},100);});}
<?php endif; ?>
</script>

</body>
</html>
