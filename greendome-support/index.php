<?php


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greendome Engineering - Support System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="header">
        <h1><i class="logo"></i> Greendome Engineering</h1>
        <p>Technology & Science</p>
        <p class="tagline">Engineering Technology, Science & Innovation — Empowering Growth Through Education, Technology, Agriculture & Automotive Excellence</p>
    </div>

    <div class="container">
        <div class="card">
            <h2 class="card-title">Welcome to Our Support System</h2>
            <p style="color: #7f8c8d; line-height: 1.8; margin-bottom: 25px;">
                Get professional support for all our services. Our integrated ticket system ensures 
                every conversation is tracked and managed efficiently.
            </p>

            <div style="display: grid; gap: 15px;">
                <a href="support.php" class="btn btn-primary" style="text-decoration: none;">
                    <i class="fas fa-ticket-alt"></i> Start Support Request
                </a>
                
                <a href="staff_start.php" class="btn btn-secondary" style="text-decoration: none;">
                    <i class="fas fa-user-tie"></i> Staff Portal
                </a>
                
                <a href="admin.php" class="btn btn-secondary" style="text-decoration: none;">
                    <i class="fas fa-chart-line"></i> Admin Dashboard
                </a>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 18px; margin-bottom: 15px; color: #2c5f2d;">
                <i class="fas fa-phone"></i> Our Services
            </h3>
            
            <div style="display: grid; gap: 15px;">
                <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #2c5f2d;">
                    <strong style="color: #2c5f2d;"><i class="fas fa-book"></i> Education</strong>
                    <p style="color: #7f8c8d; font-size: 14px; margin: 5px 0 0 0;">
                        Educational programs and training services
                    </p>
                </div>
                
                <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #2c5f2d;">
                    <strong style="color: #2c5f2d;"><i class="fas fa-laptop"></i> IT Services</strong>
                    <p style="color: #7f8c8d; font-size: 14px; margin: 5px 0 0 0;">
                        Technology solutions and digital services
                    </p>
                </div>
                
                <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #2c5f2d;">
                    <strong style="color: #2c5f2d;"><i class="fas fa-tractor"></i> CLACS-Farm</strong>
                    <p style="color: #7f8c8d; font-size: 14px; margin: 5px 0 0 0;">
                        Poultry, Fishery and farm product
                    </p>
                </div>
                
                <div style="padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #2c5f2d;">
                    <strong style="color: #2c5f2d;"><i class="fas fa-car"></i> Automobile</strong>
                    <p style="color: #7f8c8d; font-size: 14px; margin: 5px 0 0 0;">
                        Automotive services and solutions
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 style="font-size: 18px; margin-bottom: 15px; color: #2c5f2d;">
                 System Features
            </h3>
            
            <ul style="padding-left: 20px; color: #7f8c8d; line-height: 2;">
                <li>Unique ticket ID for every interaction</li>
                <li>WhatsApp integration for seamless communication</li>
                <li>Complete conversation tracking</li>
                <li>Staff and customer portals</li>
                <li>Real-time status updates</li>
                <li>Professional ticket management</li>
            </ul>
        </div>
    </div>

    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> Greendome Engineering Technology & Science</p>
        <p style="font-size: 11px; margin-top: 5px;">All rights reserved</p>
    </div>
</body>
</html>
