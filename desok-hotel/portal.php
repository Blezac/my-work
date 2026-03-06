<?php
/* ========================================
   DESOK LUXURY APARTMENT - RESIDENT PORTAL
   Login page and dashboard placeholder
   ======================================== */

session_start();
$page_title = "Resident Portal - Desok Luxury Apartment";
$current_page = "portal";

// Check if user is logged in
$is_logged_in = isset($_SESSION['resident_logged_in']) && $_SESSION['resident_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Resident portal for Desok Luxury Apartment residents.">
    <title><?php echo $page_title; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navigation -->
    <?php include 'includes/navbar.php'; ?>

    <?php if (!$is_logged_in): ?>
        <!-- Login Page -->
        <section class="section" style="min-height: 80vh; display: flex; align-items: center; background: var(--bg-cream);">
            <div class="container">
                <div class="portal-container">
                    <div class="portal-header">
                        <div class="portal-icon">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h2 style="color: var(--primary-navy); margin-bottom: 0.5rem;">Resident Portal</h2>
                        <p style="color: var(--text-gray);">Access your account and manage your stay</p>
                    </div>

                    <form id="login-form" method="POST">
                        <div class="form-group">
                            <label class="form-label" for="username">Username or Email</label>
                            <input type="text" id="username" name="username" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-input" required>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                                <input type="checkbox" name="remember">
                                <span style="font-size: 0.9rem; color: var(--text-gray);">Remember me</span>
                            </label>
                            <a href="#" style="font-size: 0.9rem; color: var(--primary-gold);">Forgot password?</a>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 1rem;">
                            <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i> Sign In
                        </button>

                        <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #E0E0E0;">
                            <p style="color: var(--text-gray); margin-bottom: 0.5rem;">Don't have an account?</p>
                            <a href="contact.php" class="btn btn-secondary">Contact Us for Access</a>
                        </div>
                    </form>
                </div>

                <!-- Features -->
                <div style="max-width: 900px; margin: 4rem auto 0; text-align: center;">
                    <h3 style="color: var(--primary-navy); margin-bottom: 2rem;">Portal Features</h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                        <div>
                            <i class="fas fa-file-invoice" style="font-size: 2.5rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">View Statements</h4>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">Access your billing and payment history</p>
                        </div>
                        <div>
                            <i class="fas fa-tools" style="font-size: 2.5rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">Request Maintenance</h4>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">Submit and track service requests</p>
                        </div>
                        <div>
                            <i class="fas fa-calendar-check" style="font-size: 2.5rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">Book Amenities</h4>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">Reserve gym, pool, and other facilities</p>
                        </div>
                        <div>
                            <i class="fas fa-bell" style="font-size: 2.5rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                            <h4 style="margin-bottom: 0.5rem;">Notifications</h4>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">Stay updated with important announcements</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php else: ?>
        <!-- Dashboard Page -->
        <section class="section" style="min-height: 80vh; background: var(--bg-cream); padding-top: 8rem;">
            <div class="container">
                <div class="dashboard-container">
                    
                    <!-- Welcome Section -->
                    <div class="dashboard-welcome">
                        <h2 style="margin-bottom: 0.5rem;">Welcome back, <?php echo isset($_SESSION['resident_name']) ? $_SESSION['resident_name'] : 'Resident'; ?>!</h2>
                        <p style="opacity: 0.9;">Unit #<?php echo isset($_SESSION['unit_number']) ? $_SESSION['unit_number'] : 'A-101'; ?> | Member since <?php echo isset($_SESSION['member_since']) ? $_SESSION['member_since'] : date('Y'); ?></p>
                    </div>

                    <!-- Quick Actions -->
                    <div style="margin-bottom: 3rem;">
                        <h3 style="color: var(--primary-navy); margin-bottom: 1.5rem;">Quick Actions</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <button class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-credit-card" style="margin-right: 0.5rem;"></i> Make Payment
                            </button>
                            <button class="btn btn-secondary" style="width: 100%;">
                                <i class="fas fa-wrench" style="margin-right: 0.5rem;"></i> Request Service
                            </button>
                            <button class="btn btn-secondary" style="width: 100%;">
                                <i class="fas fa-calendar" style="margin-right: 0.5rem;"></i> Book Amenity
                            </button>
                            <button class="btn btn-secondary" style="width: 100%;">
                                <i class="fas fa-envelope" style="margin-right: 0.5rem;"></i> Messages
                            </button>
                        </div>
                    </div>

                    <!-- Dashboard Grid -->
                    <div class="dashboard-grid">
                        <div class="dashboard-card">
                            <h4><i class="fas fa-file-invoice-dollar" style="color: var(--primary-gold); margin-right: 0.5rem;"></i> Account Balance</h4>
                            <p style="font-size: 2rem; color: var(--primary-navy); font-weight: 600; margin: 1rem 0;">₦0.00</p>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">No outstanding balance</p>
                        </div>

                        <div class="dashboard-card">
                            <h4><i class="fas fa-tools" style="color: var(--primary-gold); margin-right: 0.5rem;"></i> Service Requests</h4>
                            <p style="font-size: 2rem; color: var(--primary-navy); font-weight: 600; margin: 1rem 0;">0</p>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">No pending requests</p>
                        </div>

                        <div class="dashboard-card">
                            <h4><i class="fas fa-calendar-alt" style="color: var(--primary-gold); margin-right: 0.5rem;"></i> Lease Expiry</h4>
                            <p style="font-size: 1.3rem; color: var(--primary-navy); font-weight: 600; margin: 1rem 0;">Dec 31, 2026</p>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">337 days remaining</p>
                        </div>

                        <div class="dashboard-card">
                            <h4><i class="fas fa-bell" style="color: var(--primary-gold); margin-right: 0.5rem;"></i> Notifications</h4>
                            <p style="font-size: 2rem; color: var(--primary-navy); font-weight: 600; margin: 1rem 0;">2</p>
                            <p style="color: var(--text-gray); font-size: 0.9rem;">New announcements</p>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div style="margin-top: 3rem;">
                        <h3 style="color: var(--primary-navy); margin-bottom: 1.5rem;">Recent Activity</h3>
                        <div style="background: white; border-radius: 15px; padding: 2rem; box-shadow: var(--shadow-soft);">
                            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #E0E0E0;">
                                <i class="fas fa-check-circle" style="color: #28a745; font-size: 1.5rem;"></i>
                                <div style="flex: 1;">
                                    <h4 style="margin-bottom: 0.3rem; color: var(--primary-navy);">Payment Received</h4>
                                    <p style="color: var(--text-gray); font-size: 0.9rem;">Monthly rent payment for January 2026</p>
                                </div>
                                <span style="color: var(--text-light); font-size: 0.85rem;">Jan 1, 2026</span>
                            </div>
                            
                            <div style="display: flex; align-items: center; gap: 1rem; padding: 1rem 0;">
                                <i class="fas fa-info-circle" style="color: var(--primary-gold); font-size: 1.5rem;"></i>
                                <div style="flex: 1;">
                                    <h4 style="margin-bottom: 0.3rem; color: var(--primary-navy);">Pool Maintenance</h4>
                                    <p style="color: var(--text-gray); font-size: 0.9rem;">Scheduled maintenance on Jan 15, 2026</p>
                                </div>
                                <span style="color: var(--text-light); font-size: 0.85rem;">Dec 28, 2025</span>
                            </div>
                        </div>
                    </div>

                    <!-- Logout -->
                    <div style="text-align: center; margin-top: 3rem;">
                        <a href="backend/logout.php" class="btn btn-secondary">
                            <i class="fas fa-sign-out-alt" style="margin-right: 0.5rem;"></i> Logout
                        </a>
                    </div>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
