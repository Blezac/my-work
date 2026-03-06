<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION['staff_name'])) {
    header("Location: login.php");
    exit;
}

// Only Admin can access trash
if (strtolower($_SESSION['staff_role']) !== 'admin') {
    die("Access Denied");
}

// Handle Restore
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'restore_ticket') {

        $ticket_id = intval($_POST['ticket_id']);
        $staff_name = $_SESSION['staff_name'];

        $stmt = $conn->prepare("
            UPDATE tickets 
            SET is_deleted = 0,
                deleted_by = NULL,
                deleted_at = NULL
            WHERE id = ?
        ");
        $stmt->bind_param("i", $ticket_id);
        $stmt->execute();

        // Log
        $log = $conn->prepare("
            INSERT INTO activity_logs (staff_name, action_type, ticket_id)
            VALUES (?, 'Restore Ticket', ?)
        ");
        $log->bind_param("si", $staff_name, $ticket_id);
        $log->execute();

        header("Location: trash.php");
        exit;
    }

    // Permanent Delete
    if ($_POST['action'] === 'permanent_delete') {

        $ticket_id = intval($_POST['ticket_id']);
        $staff_name = $_SESSION['staff_name'];

        $stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
        $stmt->bind_param("i", $ticket_id);
        $stmt->execute();

        $log = $conn->prepare("
            INSERT INTO activity_logs (staff_name, action_type, ticket_id)
            VALUES (?, 'Permanent Delete Ticket', ?)
        ");
        $log->bind_param("si", $staff_name, $ticket_id);
        $log->execute();

        header("Location: trash.php");
        exit;
    }
}

// Fetch deleted tickets
$result = $conn->query("
    SELECT * FROM tickets 
    WHERE is_deleted = 1 
    ORDER BY deleted_at DESC
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Trash - Deleted Tickets</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h2>Deleted Tickets (Trash)</h2>
<a href="admin.php">← Back to Dashboard</a>
<br><br>

<table border="1" cellpadding="10">
<tr>
    <th>Ticket Code</th>
    <th>Customer</th>
    <th>Deleted By</th>
    <th>Deleted At</th>
    <th>Actions</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?php echo $row['ticket_code']; ?></td>
    <td><?php echo $row['customer_name']; ?></td>
    <td><?php echo $row['deleted_by']; ?></td>
    <td><?php echo $row['deleted_at']; ?></td>
    <td>
        <!-- Restore -->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="action" value="restore_ticket">
            <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">
            <button type="submit">Restore</button>
        </form>

        <!-- Permanent Delete -->
        <form method="POST" style="display:inline;"
              onsubmit="return confirm('Permanent delete? This cannot be undone!');">
            <input type="hidden" name="action" value="permanent_delete">
            <input type="hidden" name="ticket_id" value="<?php echo $row['id']; ?>">
            <button type="submit" style="color:red;">Permanent Delete</button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
