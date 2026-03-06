<?php
/**
 * Edit User
 * Super Admin Only
 */

require_once 'includes/config.php';

session_start();
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_role'] !== 'Super Admin') {
    header("Location: admin.php");
    exit;
}

$pdo = getDBConnection();
$user_id = intval($_GET['id'] ?? 0);
$error = '';
$success = '';

// Get user details
$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: admin.php?page=users");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $role = sanitizeInput($_POST['role'] ?? '');
    $is_active = intval($_POST['is_active'] ?? 1);
    $new_password = $_POST['new_password'] ?? '';
    
    if (empty($full_name) || empty($role)) {
        $error = 'Full name and role are required.';
    } elseif (!in_array($role, ['Super Admin', 'Admin', 'Staff'])) {
        $error = 'Invalid role selected.';
    } else {
        // Update user
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                UPDATE admin_users 
                SET full_name = ?, role = ?, is_active = ?, password = ?
                WHERE id = ?
            ");
            $stmt->execute([$full_name, $role, $is_active, $hashed_password, $user_id]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE admin_users 
                SET full_name = ?, role = ?, is_active = ?
                WHERE id = ?
            ");
            $stmt->execute([$full_name, $role, $is_active, $user_id]);
        }
        
        $success = 'User updated successfully!';
        
        // Log activity
        $pdo->prepare("
            INSERT INTO activity_logs (staff_name, action_type, details)
            VALUES (?, 'Update User', ?)
        ")->execute([$_SESSION['staff_name'], "Updated user: {$user['username']}"]);
        
        // Refresh user data
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Greendome Engineering</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-seedling"></i> Greendome Engineering</h1>
        <p>User Management - Edit User</p>
    </div>

    <div class="container">
        <div class="card">
            <h2 class="card-title"><i class="fas fa-user-edit"></i> Edit User: <?php echo htmlspecialchars($user['username']); ?></h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">✅ <?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input 
                        type="text" 
                        value="<?php echo htmlspecialchars($user['username']); ?>" 
                        disabled
                        style="background: #f8f9fa;"
                    >
                    <small style="color: #6c757d;">Username cannot be changed</small>
                </div>

                <div class="form-group">
                    <label for="full_name" class="required">Full Name</label>
                    <input 
                        type="text" 
                        name="full_name" 
                        id="full_name" 
                        required
                        value="<?php echo htmlspecialchars($user['full_name']); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="role" class="required">Role</label>
                    <select name="role" id="role" required>
                        <option value="Staff" <?php echo $user['role'] === 'Staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="Admin" <?php echo $user['role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
                        <option value="Super Admin" <?php echo $user['role'] === 'Super Admin' ? 'selected' : ''; ?>>Super Admin</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="is_active">Status</label>
                    <select name="is_active" id="is_active">
                        <option value="1" <?php echo $user['is_active'] ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?php echo !$user['is_active'] ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password (Optional)</label>
                    <input 
                        type="password" 
                        name="new_password" 
                        id="new_password" 
                        minlength="6"
                    >
                    <small style="color: #6c757d;">Leave blank to keep current password</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update User
                </button>
                
                <a href="admin.php?page=users" class="btn btn-secondary" style="margin-left: 10px;">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Greendome Engineering Technology & Science</p>
    </div>
</body>
</html>
