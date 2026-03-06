<?php
/**
 * Homepage - Service Selection
 * Greendome Engineering Ticketing System
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';

// Get all active services
$pdo = getDBConnection();
$stmt = $pdo->query("SELECT * FROM services WHERE is_active = 1 ORDER BY id");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Support Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }
        
        .header {
            background: linear-gradient(135deg, #2c5f2d 0%, #97bc62 100%);
            color: white;
            padding: 2rem 1rem;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        
        .header p {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
        }
        
        .intro {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .intro h2 {
            color: #2c5f2d;
            margin-bottom: 1rem;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .service-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .service-card h3 {
            color: #2c5f2d;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .service-card p {
            color: #666;
            line-height: 1.5;
        }
        
        .service-icon {
            width: 60px;
            height: 60px;
            background: #e8f5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .footer {
            text-align: center;
            padding: 2rem 1rem;
            color: #666;
            background: white;
            margin-top: 3rem;
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.5rem;
            }
            
            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo APP_NAME; ?></h1>
        <p>Engineering Technology, Science & Innovation</p>
        <p>Empowering Growth Through Education, Technology, Agriculture & Automotive Excellence</p>
    </div>
    
    <div class="container">
        <div class="intro">
            <h2>Welcome to Our Support Portal</h2>
            <p>Select a service below to create a support ticket. We'll connect with you via WhatsApp to provide fast and personalized assistance.</p>
        </div>
        
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <a href="create-ticket.php?service=<?php echo e($service['service_slug']); ?>" class="service-card">
                    <div class="service-icon">
                        <?php
                        $icons = [
                            'education' => '📚',
                            'it-services' => '💻',
                            'clacs-farm' => '🌾',
                            'automobile' => '🚗'
                        ];
                        echo $icons[$service['service_slug']] ?? '📋';
                        ?>
                    </div>
                    <h3><?php echo e($service['service_name']); ?></h3>
                    <p><?php echo e($service['description']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
        <p>For urgent matters, contact us at <?php echo SUPPORT_EMAIL; ?></p>
    </div>
</body>
</html>
