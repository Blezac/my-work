<?php
/**
 * Add New User
 * Super Admin Only
 */

require_once 'includes/config.php';

session_start();
if (!isset($_SESSION['staff_logged_in']) || $_SESSION['staff_role'] !== 'Super Admin') {
    header("Location: admin.php");
    exit;
}

$pdo = getDBConnection();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $full_name = sanitizeInput($_POST['full_name'] ?? '');
    $role = sanitizeInput($_POST['role'] ?? '');
    
    if (empty($username) || empty($password) || empty($full_name) || empty($role)) {
        $error = 'All fields are required.';
    } elseif (!in_array($role, ['Super Admin', 'Admin', 'Staff'])) {
        $error = 'Invalid role selected.';
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetchColumn() > 0) {
            $error = 'Username already exists.';
        } else {
            // Create user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO admin_users (username, password, full_name, role)
                VALUES (?, ?, ?, ?)
            ");
            
            if ($stmt->execute([$username, $hashed_password, $full_name, $role])) {
                $success = 'User created successfully!';
                
                // Log activity
                $pdo->prepare("
                    INSERT INTO activity_logs (staff_name, action_type, details)
                    VALUES (?, 'Create User', ?)
                ")->execute([$_SESSION['staff_name'], "Created user: $username ($role)"]);
            } else {
                $error = 'Failed to create user.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User - Greendome Engineering</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-seedling"></i> Greendome Engineering</h1>
        <p>User Management - Add New User</p>
    </div>

    <div class="container">
        <div class="card">
            <h2 class="card-title"><i class="fas fa-user-plus"></i> Add New User</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error">⚠️ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    ✅ <?php echo $success; ?>
                    <a href="admin.php?page=users" class="btn btn-primary btn-sm" style="margin-left: 10px;">
                        Back to Users
                    </a>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username" class="required">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        required
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="required">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        required
                        minlength="6"
                    >
                    <small style="color: #6c757d;">Minimum 6 characters</small>
                </div>

                <div class="form-group">
                    <label for="full_name" class="required">Full Name</label>
                    <input 
                        type="text" 
                        name="full_name" 
                        id="full_name" 
                        required
                        value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="role" class="required">Role</label>
                    <select name="role" id="role" required>
                        <option value="">-- Select Role --</option>
                        <option value="Staff">Staff</option>
                        <option value="Admin">Admin</option>
                        <option value="Super Admin">Super Admin</option>
                    </select>
                    <small style="color: #6c757d;">
                        <strong>Staff:</strong> View & respond to tickets<br>
                        <strong>Admin:</strong> Staff + soft delete & restore<br>
                        <strong>Super Admin:</strong> Admin + user management & permanent delete
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create User
                </button>
                
                <a href="admin.php?page=users" class="btn btn-secondary" style="margin-left: 10px;">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Greendome Engineering Technology & Science</p>
    </div>
</body>
</html>
