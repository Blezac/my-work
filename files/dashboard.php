<?php
/**
 * Admin Dashboard - Ticket List
 * Greendome Engineering Ticketing System
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';

session_name(SESSION_NAME);
session_start();

// Check authentication
if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$currentStaff = getCurrentStaff();

// Get filter parameters
$filterService = $_GET['service'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$filterAssigned = $_GET['assigned'] ?? '';
$searchQuery = $_GET['search'] ?? '';

// Build query
$sql = "
    SELECT 
        t.*,
        s.service_name,
        su.full_name as assigned_to_name,
        (SELECT COUNT(*) FROM ticket_messages WHERE ticket_id = t.id) as message_count,
        (SELECT COUNT(*) FROM ticket_messages WHERE ticket_id = t.id AND is_read = 0 AND message_type = 'incoming') as unread_count
    FROM tickets t
    LEFT JOIN services s ON t.service_id = s.id
    LEFT JOIN staff_users su ON t.assigned_to = su.id
    WHERE 1=1
";

$params = [];

if ($filterService) {
    $sql .= " AND t.service_id = ?";
    $params[] = $filterService;
}

if ($filterStatus) {
    $sql .= " AND t.status = ?";
    $params[] = $filterStatus;
}

if ($filterAssigned === 'me') {
    $sql .= " AND t.assigned_to = ?";
    $params[] = $currentStaff['id'];
} elseif ($filterAssigned === 'unassigned') {
    $sql .= " AND t.assigned_to IS NULL";
}

if ($searchQuery) {
    $sql .= " AND (t.ticket_code LIKE ? OR t.customer_name LIKE ? OR t.customer_whatsapp LIKE ?)";
    $searchParam = '%' . $searchQuery . '%';
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
}

$sql .= " ORDER BY t.updated_at DESC";

$pdo = getDBConnection();
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();

// Get services for filter
$services = $pdo->query("SELECT * FROM services WHERE is_active = 1")->fetchAll();

// Get staff for filter
$staffList = $pdo->query("SELECT * FROM staff_users WHERE is_active = 1")->fetchAll();

// Get statistics
$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM tickets")->fetchColumn(),
    'open' => $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'Open'")->fetchColumn(),
    'in_progress' => $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'In Progress'")->fetchColumn(),
    'waiting' => $pdo->query("SELECT COUNT(*) FROM tickets WHERE status = 'Waiting on Customer'")->fetchColumn(),
];

$flashMessage = getFlashMessage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - <?php echo APP_NAME; ?></title>
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
        
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .navbar-user a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.2s;
        }
        
        .navbar-user a:hover {
            background: rgba(255,255,255,0.1);
        }
        
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            color: #2c5f2d;
        }
        
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .filters form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        
        .filter-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #555;
        }
        
        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            background: #2c5f2d;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #1e4620;
        }
        
        .btn-secondary {
            background: #666;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        thead {
            background: #f8f8f8;
        }
        
        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #eee;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        
        td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }
        
        tbody tr:hover {
            background: #f8f8f8;
        }
        
        .ticket-code {
            font-weight: 700;
            color: #2c5f2d;
            text-decoration: none;
        }
        
        .ticket-code:hover {
            text-decoration: underline;
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
        
        .unread-badge {
            background: #d32f2f;
            color: white;
            padding: 0.125rem 0.5rem;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }
        
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
        
        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }
            
            .table-container {
                overflow-x: auto;
            }
            
            table {
                min-width: 800px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>📊 <?php echo APP_NAME; ?> - Admin</h1>
        <div class="navbar-user">
            <span>👤 <?php echo e($currentStaff['full_name']); ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($flashMessage): ?>
            <div class="flash-message flash-<?php echo e($flashMessage['type']); ?>">
                <?php echo e($flashMessage['message']); ?>
            </div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Tickets</h3>
                <div class="number"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Open</h3>
                <div class="number"><?php echo $stats['open']; ?></div>
            </div>
            <div class="stat-card">
                <h3>In Progress</h3>
                <div class="number"><?php echo $stats['in_progress']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Waiting</h3>
                <div class="number"><?php echo $stats['waiting']; ?></div>
            </div>
        </div>
        
        <div class="filters">
            <form method="GET" action="">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" name="search" value="<?php echo e($searchQuery); ?>" placeholder="Ticket, name, phone...">
                </div>
                
                <div class="filter-group">
                    <label>Service</label>
                    <select name="service">
                        <option value="">All Services</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo $service['id']; ?>" <?php echo $filterService == $service['id'] ? 'selected' : ''; ?>>
                                <?php echo e($service['service_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="">All Statuses</option>
                        <option value="Open" <?php echo $filterStatus === 'Open' ? 'selected' : ''; ?>>Open</option>
                        <option value="In Progress" <?php echo $filterStatus === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="Waiting on Customer" <?php echo $filterStatus === 'Waiting on Customer' ? 'selected' : ''; ?>>Waiting on Customer</option>
                        <option value="Resolved" <?php echo $filterStatus === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                        <option value="Closed" <?php echo $filterStatus === 'Closed' ? 'selected' : ''; ?>>Closed</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>Assigned</label>
                    <select name="assigned">
                        <option value="">All</option>
                        <option value="me" <?php echo $filterAssigned === 'me' ? 'selected' : ''; ?>>My Tickets</option>
                        <option value="unassigned" <?php echo $filterAssigned === 'unassigned' ? 'selected' : ''; ?>>Unassigned</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="btn">Filter</button>
                    <a href="dashboard.php" class="btn btn-secondary">Clear</a>
                </div>
            </form>
        </div>
        
        <div class="table-container">
            <?php if (count($tickets) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Assigned To</th>
                            <th>Messages</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tickets as $ticket): ?>
                            <tr>
                                <td>
                                    <a href="ticket-view.php?id=<?php echo $ticket['id']; ?>" class="ticket-code">
                                        <?php echo e($ticket['ticket_code']); ?>
                                    </a>
                                </td>
                                <td>
                                    <strong><?php echo e($ticket['customer_name']); ?></strong><br>
                                    <small><?php echo formatWhatsAppNumber($ticket['customer_whatsapp']); ?></small>
                                </td>
                                <td><?php echo e($ticket['service_name']); ?></td>
                                <td><?php echo e($ticket['request_type']); ?></td>
                                <td><?php echo getStatusBadge($ticket['status']); ?></td>
                                <td><?php echo getPriorityBadge($ticket['priority']); ?></td>
                                <td><?php echo e($ticket['assigned_to_name'] ?? '-'); ?></td>
                                <td>
                                    <?php echo $ticket['message_count']; ?>
                                    <?php if ($ticket['unread_count'] > 0): ?>
                                        <span class="unread-badge"><?php echo $ticket['unread_count']; ?> new</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo timeAgo($ticket['updated_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <p>📭 No tickets found</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
