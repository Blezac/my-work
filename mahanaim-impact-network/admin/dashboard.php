<?php
// === admin/dashboard.php ===
session_start();
require_once '../config/db.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch statistics
$stmt = $pdo->query("SELECT COUNT(*) as total FROM registrations");
$totalRegistrations = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM registrations WHERE payment_status = 'paid'");
$paidRegistrations = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM registrations WHERE payment_status = 'pending'");
$pendingRegistrations = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT SUM(amount_paid) as total FROM registrations WHERE payment_status = 'paid'");
$totalRevenue = $stmt->fetch()['total'] ?? 0;

// Fetch all events
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();

// Fetch recent registrations
$stmt = $pdo->query("SELECT r.*, e.title as event_title FROM registrations r JOIN events e ON r.event_id = e.id ORDER BY r.created_at DESC LIMIT 10");
$recentRegistrations = $stmt->fetchAll();

// Handle event selection for detailed view
$selectedEventId = $_GET['event_id'] ?? null;
$eventRegistrations = [];

if ($selectedEventId) {
    $stmt = $pdo->prepare("SELECT * FROM registrations WHERE event_id = ? ORDER BY created_at DESC");
    $stmt->execute([$selectedEventId]);
    $eventRegistrations = $stmt->fetchAll();
}

// Export CSV functionality
if (isset($_GET['export']) && isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];
    $stmt = $pdo->prepare("SELECT r.*, e.title as event_title, e.event_date, e.location FROM registrations r JOIN events e ON r.event_id = e.id WHERE r.event_id = ?");
    $stmt->execute([$eventId]);
    $data = $stmt->fetchAll();
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="registrations_event_' . $eventId . '_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Event', 'Full Name', 'Email', 'Phone', 'Business Name', 'Ticket Type', 'Amount Paid', 'Payment Status', 'Transaction Ref', 'Registration Date']);
    
    foreach ($data as $row) {
        fputcsv($output, [
            $row['id'],
            $row['event_title'],
            $row['full_name'],
            $row['email'],
            $row['phone'],
            $row['business_name'],
            $row['ticket_type'],
            $row['amount_paid'],
            $row['payment_status'],
            $row['transaction_ref'],
            $row['created_at']
        ]);
    }
    
    fclose($output);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mahanaim Impact Network</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #4B0082 0%, #6A0DAD 100%);
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
            padding: 0;
        }
        .sidebar-header {
            padding: 2rem 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-menu {
            padding: 1rem 0;
        }
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            padding: 1rem 1.5rem;
            display: block;
            transition: all 0.3s;
        }
        .sidebar-menu a:hover, .sidebar-menu a.active {
            background: rgba(212, 175, 55, 0.2);
            border-left: 4px solid #D4AF37;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        .stat-card.blue .icon { background: #e3f2fd; color: #4B0082; }
        .stat-card.gold .icon { background: #fff8e1; color: #D4AF37; }
        .stat-card.green .icon { background: #e8f5e9; color: #4caf50; }
        .stat-card.orange .icon { background: #fff3e0; color: #ff9800; }
        
        .card-custom {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border: none;
        }
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-hands-helping fa-3x mb-2" style="color: #D4AF37;"></i>
            <h5 class="mb-0">Mahanaim Admin</h5>
            <small>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></small>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #4B0082;">Dashboard Overview</h2>
            <a href="../index.php" class="btn btn-outline-primary"><i class="fas fa-globe me-2"></i>View Website</a>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card blue">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3><?php echo $totalRegistrations; ?></h3>
                    <p class="text-muted mb-0">Total Registrations</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card green">
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3><?php echo $paidRegistrations; ?></h3>
                    <p class="text-muted mb-0">Paid</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card orange">
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3><?php echo $pendingRegistrations; ?></h3>
                    <p class="text-muted mb-0">Pending</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card gold">
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3>₦<?php echo number_format($totalRevenue, 0); ?></h3>
                    <p class="text-muted mb-0">Total Revenue</p>
                </div>
            </div>
        </div>

        <!-- Events Section -->
        <div class="card card-custom mb-4">
            <div class="card-body p-4">
                <h4 class="mb-4" style="color: #4B0082;"><i class="fas fa-calendar-alt me-2"></i>Events</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>Event Title</th>
                                <th>Date</th>
                                <th>Location</th>
                                <th>Registrations</th>
                                <th>Revenue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($events as $event): ?>
                                <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) as count, SUM(amount_paid) as revenue FROM registrations WHERE event_id = ? AND payment_status = 'paid'");
                                $stmt->execute([$event['id']]);
                                $eventStats = $stmt->fetch();
                                ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($event['title']); ?></strong></td>
                                    <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($event['location']); ?></td>
                                    <td><span class="badge bg-primary"><?php echo $eventStats['count']; ?></span></td>
                                    <td>₦<?php echo number_format($eventStats['revenue'] ?? 0, 0); ?></td>
                                    <td>
                                        <a href="?event_id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                                        <a href="?export=1&event_id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-download"></i> CSV</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Event Registrations Detail -->
        <?php if ($selectedEventId && !empty($eventRegistrations)): ?>
            <div class="card card-custom mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="color: #4B0082;"><i class="fas fa-list me-2"></i>Event Registrations</h4>
                        <a href="dashboard.php" class="btn btn-sm btn-secondary"><i class="fas fa-times"></i> Close</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Ticket Type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($eventRegistrations as $reg): ?>
                                    <tr>
                                        <td>#<?php echo $reg['id']; ?></td>
                                        <td><?php echo htmlspecialchars($reg['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($reg['email']); ?></td>
                                        <td><?php echo htmlspecialchars($reg['phone']); ?></td>
                                        <td><span class="badge bg-info"><?php echo ucfirst($reg['ticket_type']); ?></span></td>
                                        <td>₦<?php echo number_format($reg['amount_paid'], 0); ?></td>
                                        <td>
                                            <?php if ($reg['payment_status'] === 'paid'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('M d, Y', strtotime($reg['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Recent Registrations -->
        <div class="card card-custom">
            <div class="card-body p-4">
                <h4 class="mb-4" style="color: #4B0082;"><i class="fas fa-history me-2"></i>Recent Registrations</h4>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th>ID</th>
                                <th>Event</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentRegistrations as $reg): ?>
                                <tr>
                                    <td>#<?php echo $reg['id']; ?></td>
                                    <td><?php echo htmlspecialchars($reg['event_title']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($reg['email']); ?></td>
                                    <td>₦<?php echo number_format($reg['amount_paid'], 0); ?></td>
                                    <td>
                                        <?php if ($reg['payment_status'] === 'paid'): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M d, H:i', strtotime($reg['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
