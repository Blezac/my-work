# Installation Guide - Greendome Ticketing System

Complete step-by-step installation guide for Ubuntu LAMP server.

---

## Prerequisites

Before you begin, ensure you have:

- ✅ Ubuntu 20.04 LTS or higher
- ✅ Root or sudo access
- ✅ Domain name pointing to your server
- ✅ WhatsApp Business API account
- ✅ Basic knowledge of Linux command line

---

## Method 1: Automated Installation (Recommended)

### Step 1: Upload Files

Upload all files to your server or use git:

```bash
cd /tmp
# Upload your files here or use git
# Then copy to deployment location
```

### Step 2: Run Deployment Script

```bash
cd /path/to/greendome-ticketing/deployment
sudo chmod +x deploy.sh
sudo ./deploy.sh
```

The script will ask for:
- Domain name
- MySQL root password
- Database password
- Email for SSL (optional)

### Step 3: Configure WhatsApp

```bash
sudo chmod +x configure-whatsapp.sh
sudo ./configure-whatsapp.sh
```

Enter your WhatsApp Business API credentials when prompted.

### Step 4: Access Your Site

- Public site: `http://yourdomain.com`
- Admin panel: `http://yourdomain.com/admin/login.php`

**Default credentials:**
- Username: `admin`
- Password: `admin123` (⚠️ CHANGE IMMEDIATELY!)

---

## Method 2: Manual Installation

### Step 1: Update System

```bash
sudo apt update
sudo apt upgrade -y
```

### Step 2: Install LAMP Stack

```bash
# Install Apache
sudo apt install apache2 -y

# Install MySQL
sudo apt install mysql-server -y

# Install PHP and extensions
sudo apt install php libapache2-mod-php php-mysql php-curl php-json php-mbstring php-xml php-zip -y
```

### Step 3: Configure MySQL

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p
```

In MySQL prompt:

```sql
CREATE DATABASE greendome_tickets CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'greendome_user'@'localhost' IDENTIFIED BY 'YourSecurePassword123!';
GRANT ALL PRIVILEGES ON greendome_tickets.* TO 'greendome_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Step 4: Import Database Schema

```bash
sudo mysql -u greendome_user -p greendome_tickets < /path/to/database/schema.sql
```

### Step 5: Deploy Application Files

```bash
# Create directory
sudo mkdir -p /var/www/greendome-ticketing

# Copy files
sudo cp -r /path/to/files/* /var/www/greendome-ticketing/

# Create logs directory
sudo mkdir -p /var/www/greendome-ticketing/logs

# Set permissions
sudo chown -R www-data:www-data /var/www/greendome-ticketing
sudo find /var/www/greendome-ticketing -type d -exec chmod 755 {} \;
sudo find /var/www/greendome-ticketing -type f -exec chmod 644 {} \;
sudo chmod -R 777 /var/www/greendome-ticketing/logs
```

### Step 6: Configure Apache

```bash
# Copy virtual host configuration
sudo cp /var/www/greendome-ticketing/deployment/greendome.conf /etc/apache2/sites-available/

# Edit configuration with your domain
sudo nano /etc/apache2/sites-available/greendome.conf
# Replace greendome.com with your domain

# Enable site and required modules
sudo a2ensite greendome.conf
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod ssl
sudo a2enmod expires

# Disable default site
sudo a2dissite 000-default.conf

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

### Step 7: Configure Application

```bash
# Edit configuration file
sudo nano /var/www/greendome-ticketing/config/config.php
```

Update these values:

```php
// Database
define('DB_PASS', 'YourSecurePassword123!');

// WhatsApp API
define('WHATSAPP_PHONE_NUMBER_ID', 'your_phone_number_id');
define('WHATSAPP_ACCESS_TOKEN', 'your_access_token');
define('WHATSAPP_VERIFY_TOKEN', 'your_webhook_verify_token');

// Application
define('APP_URL', 'https://yourdomain.com');

// Security - Generate random key
define('ENCRYPTION_KEY', 'your_random_32_character_key');
```

Generate encryption key:

```bash
openssl rand -base64 32 | tr -d "=+/" | cut -c1-32
```

### Step 8: Update WhatsApp Business Number

```bash
sudo nano /var/www/greendome-ticketing/public/ticket-success.php
```

Update line 37:

```php
$whatsappNumber = '2348012345678'; // Your WhatsApp Business number
```

### Step 9: Set Up SSL (Recommended)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get SSL certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

### Step 10: Configure Firewall

```bash
# Enable UFW
sudo ufw enable

# Allow required ports
sudo ufw allow 22/tcp      # SSH
sudo ufw allow 80/tcp      # HTTP
sudo ufw allow 443/tcp     # HTTPS

# Check status
sudo ufw status
```

---

## WhatsApp Business API Setup

### 1. Create Facebook Business Account

Visit: https://business.facebook.com

### 2. Set Up WhatsApp Business API

1. Go to Meta for Developers: https://developers.facebook.com
2. Create or select an app
3. Add WhatsApp product
4. Follow the setup wizard

### 3. Get Credentials

**Phone Number ID:**
- Go to WhatsApp > API Setup
- Copy "Phone number ID"

**Access Token:**
- Go to WhatsApp > API Setup
- Generate or copy "Temporary access token"
- For production, create a permanent token

**Verify Token:**
- Create any random string (e.g., "GD_webhook_2024_secure")
- Save it for webhook configuration

### 4. Configure Webhook

1. Go to WhatsApp > Configuration
2. Click "Edit" on Webhook
3. Enter:
   - **Callback URL**: `https://yourdomain.com/webhook.php`
   - **Verify Token**: (the random string you created)
4. Subscribe to field: `messages`
5. Save

### 5. Test Webhook

```bash
# Watch webhook logs
sudo tail -f /var/www/greendome-ticketing/logs/webhook.log

# Send a test message to your WhatsApp Business number
# You should see the message appear in the logs
```

---

## Post-Installation Steps

### 1. Change Admin Password

```bash
# Login to admin panel
# Go to: https://yourdomain.com/admin/login.php
# Username: admin
# Password: admin123

# Change password via MySQL
sudo mysql -u greendome_user -p greendome_tickets
```

```sql
UPDATE staff_users 
SET password_hash = '$2y$10$YourNewPasswordHashHere' 
WHERE username = 'admin';
```

Generate password hash in PHP:

```php
<?php echo password_hash('your_new_password', PASSWORD_DEFAULT); ?>
```

### 2. Test Ticket Creation

1. Visit: `https://yourdomain.com`
2. Select a service
3. Fill out the form with test data
4. Check if you receive WhatsApp notification
5. Reply to the WhatsApp message
6. Check if reply appears in admin panel

### 3. Create Additional Staff Users

```sql
INSERT INTO staff_users (username, password_hash, full_name, email, role) 
VALUES ('john', '$2y$10$hash_here', 'John Doe', 'john@greendome.com', 'support');
```

### 4. Configure Backups

```bash
# Create backup script
sudo nano /usr/local/bin/greendome-backup.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/backup/greendome"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup database
mysqldump -u greendome_user -p'password' greendome_tickets > $BACKUP_DIR/db_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/greendome-ticketing

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
# Make executable
sudo chmod +x /usr/local/bin/greendome-backup.sh

# Add to crontab
sudo crontab -e
```

Add:

```
0 2 * * * /usr/local/bin/greendome-backup.sh
```

---

## Verification Checklist

After installation, verify:

- [ ] Website loads: `http://yourdomain.com`
- [ ] SSL works: `https://yourdomain.com`
- [ ] Can create ticket
- [ ] WhatsApp notification sent
- [ ] Can login to admin panel
- [ ] Can view ticket in dashboard
- [ ] Can reply to ticket
- [ ] Reply sent via WhatsApp
- [ ] Webhook receives messages
- [ ] Incoming messages logged
- [ ] Status changes work
- [ ] Ticket assignment works

---

## Troubleshooting

### Cannot Access Website

```bash
# Check Apache status
sudo systemctl status apache2

# Check Apache error logs
sudo tail -f /var/log/apache2/greendome_error.log

# Check file permissions
ls -la /var/www/greendome-ticketing/
```

### Database Connection Error

```bash
# Check MySQL status
sudo systemctl status mysql

# Test connection
mysql -u greendome_user -p greendome_tickets

# Check PHP errors
sudo tail -f /var/www/greendome-ticketing/logs/php_errors.log
```

### WhatsApp Not Working

```bash
# Check webhook logs
sudo tail -f /var/www/greendome-ticketing/logs/webhook.log

# Check PHP errors
sudo tail -f /var/www/greendome-ticketing/logs/php_errors.log

# Test webhook manually
curl -X POST https://yourdomain.com/webhook.php \
  -H "Content-Type: application/json" \
  -d '{"test": "data"}'
```

### Permission Errors

```bash
# Reset permissions
sudo chown -R www-data:www-data /var/www/greendome-ticketing
sudo find /var/www/greendome-ticketing -type d -exec chmod 755 {} \;
sudo find /var/www/greendome-ticketing -type f -exec chmod 644 {} \;
sudo chmod -R 777 /var/www/greendome-ticketing/logs
```

---

## Maintenance Commands

```bash
# View Apache logs
sudo tail -f /var/log/apache2/greendome_access.log
sudo tail -f /var/log/apache2/greendome_error.log

# View application logs
sudo tail -f /var/www/greendome-ticketing/logs/php_errors.log
sudo tail -f /var/www/greendome-ticketing/logs/webhook.log

# Restart services
sudo systemctl restart apache2
sudo systemctl restart mysql

# Check disk space
df -h

# Check database size
sudo mysql -u greendome_user -p -e "
  SELECT table_schema AS 'Database',
  ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)'
  FROM information_schema.TABLES
  WHERE table_schema = 'greendome_tickets'
  GROUP BY table_schema;
"
```

---

## Support

If you encounter issues:

1. Check the logs (see Maintenance Commands above)
2. Review the Troubleshooting section
3. Verify all configuration values
4. Test each component individually
5. Contact support: support@greendome.com

---

**Installation guide complete. Your system is ready for production use!**
