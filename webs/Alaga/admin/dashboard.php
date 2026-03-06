<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once '../config.php';

// Get statistics
$stats = [];

// Total bookings
$result = $conn->query("SELECT COUNT(*) as total FROM bookings");
$stats['total_bookings'] = $result->fetch_assoc()['total'];

// Pending bookings
$result = $conn->query("SELECT COUNT(*) as total FROM bookings WHERE booking_status = 'pending'");
$stats['pending_bookings'] = $result->fetch_assoc()['total'];

// Total payments
$result = $conn->query("SELECT COUNT(*) as total FROM payment_transactions");
$stats['total_payments'] = $result->fetch_assoc()['total'];

// Total consultations
$result = $conn->query("SELECT COUNT(*) as total FROM consultations");
$stats['total_consultations'] = $result->fetch_assoc()['total'];

// Total enquiries
$result = $conn->query("SELECT COUNT(*) as total FROM enquiries");
$stats['total_enquiries'] = $result->fetch_assoc()['total'];

// Recent bookings
$recent_bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10");

// Recent payments
$recent_payments = $conn->query("SELECT p.*, b.groom_firstname, b.bride_firstname 
    FROM payment_transactions p 
    LEFT JOIN bookings b ON p.booking_id = b.id 
    ORDER BY p.transaction_date DESC LIMIT 10");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - WendyGee Alaga</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body class="admin-body">
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-logo">
            <h2>WENDYGEE ALAGA</h2>
            <p>Admin Panel</p>
        </div>
        
        <nav class="admin-nav">
            <a href="dashboard.php" class="active">📊 Dashboard</a>
            <a href="bookings.php">📅 Bookings</a>
            <a href="payments.php">💳 Payments</a>
            <a href="consultations.php">🤝 Consultations</a>
            <a href="enquiries.php">✉️ Enquiries</a>
            <a href="training.php">🎓 Training Enrollments</a>
            <a href="send-email.php">📧 Send Email</a>
            <a href="settings.php">⚙️ Settings</a>
            <a href="logout.php">🚪 Logout</a>
        </nav>
        
        <div class="admin-user">
            <p>Logged in as:</p>
            <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong>
        </div>
    </div>

    <!-- Main Content -->
    <div class="admin-main">
        <div class="admin-header">
            <h1>Dashboard Overview</h1>
            <div class="admin-welcome">
                Welcome back, <strong><?php echo htmlspecialchars($_SESSION['admin_name']); ?></strong>!
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_bookings']; ?></h3>
                    <p>Total Bookings</p>
                </div>
            </div>
            
            <div class="stat-card pending">
                <div class="stat-icon">⏳</div>
                <div class="stat-info">
                    <h3><?php echo $stats['pending_bookings']; ?></h3>
                    <p>Pending Bookings</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">💳</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_payments']; ?></h3>
                    <p>Total Payments</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">🤝</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_consultations']; ?></h3>
                    <p>Consultations</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">✉️</div>
                <div class="stat-info">
                    <h3><?php echo $stats['total_enquiries']; ?></h3>
                    <p>Enquiries</p>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="admin-section">
            <h2>Recent Bookings</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Groom</th>
                            <th>Bride</th>
                            <th>Date</th>
                            <th>Service</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $booking['id']; ?></td>
                            <td><?php echo htmlspecialchars($booking['groom_firstname'] . ' ' . $booking['groom_lastname']); ?></td>
                            <td><?php echo htmlspecialchars($booking['bride_firstname'] . ' ' . $booking['bride_lastname']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                            <td><?php echo ucwords(str_replace('_', ' ', $booking['service_type'])); ?></td>
                            <td><span class="status-badge status-<?php echo $booking['booking_status']; ?>"><?php echo ucfirst($booking['booking_status']); ?></span></td>
                            <td>
                                <a href="view-booking.php?id=<?php echo $booking['id']; ?>" class="btn-view">View</a>
                                <a href="respond-booking.php?id=<?php echo $booking['id']; ?>" class="btn-respond">Respond</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <a href="bookings.php" class="view-all-link">View All Bookings →</a>
        </div>

        <!-- Recent Payments -->
        <div class="admin-section">
            <h2>Recent Payments</h2>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($payment = $recent_payments->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($payment['transaction_reference']); ?></td>
                            <td><?php echo htmlspecialchars($payment['groom_firstname'] ?? 'N/A'); ?></td>
                            <td>₦<?php echo number_format($payment['amount'], 2); ?></td>
                            <td><span class="status-badge status-<?php echo $payment['payment_status']; ?>"><?php echo ucfirst($payment['payment_status']); ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($payment['transaction_date'])); ?></td>
                            <td>
                                <a href="view-payment.php?id=<?php echo $payment['id']; ?>" class="btn-view">View Receipt</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <a href="payments.php" class="view-all-link">View All Payments →</a>
        </div>
    </div>
</body>
</html>