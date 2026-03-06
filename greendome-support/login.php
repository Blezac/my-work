<?php
/**
 * Admin Login Page
 * Greendome Engineering Technology & Science
 */

require_once 'includes/config.php';

session_start();

// Redirect if already logged in
if (isset($_SESSION['staff_logged_in']) && $_SESSION['staff_logged_in'] === true) {
    header("Location: admin.php");
    exit;
}

$error = '';

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login (only if column exists)
            try {
                $pdo->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
            } catch (PDOException $e) {
                // Column doesn't exist yet - ignore error
            }
            
            // Login successful
            $_SESSION['staff_logged_in'] = true;
            $_SESSION['staff_id'] = $user['id'];
            $_SESSION['staff_name'] = $user['full_name'];
            $_SESSION['staff_username'] = $user['username'];
            $_SESSION['staff_role'] = isset($user['role']) ? $user['role'] : 'Staff';
            
            header("Location: admin.php");
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Greendome Engineering</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #2c5f2d, #97bc62);
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 40px 30px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .login-header h1 {
            color: #2c5f2d;
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1><i class="logo"></i> Greendome Engineering</h1>
                <p>Admin Portal Login</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username" class="required">Username</label>
                    <input 
                        type="text" 
                        name="username" 
                        id="username" 
                        placeholder="Enter username"
                        autocomplete="username"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="required">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password" 
                        placeholder="Enter password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-lock"></i> Login to Dashboard
                </button>
            </form>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="support.php" style="color: #2c5f2d; text-decoration: none; font-size: 14px;">
                    ← Back to Support Page
                </a>
            </div>
        </div>
        
        <div style="text-align: center; color: white; margin-top: 20px; font-size: 12px;">
            <p>&copy; <?php echo date('Y'); ?> Greendome Engineering Technology & Science</p>
        </div>
    </div>
</body>
</html>
