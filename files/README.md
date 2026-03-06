# Greendome Engineering Ticketing System

**WhatsApp-First Customer Support & Ticketing System**

A production-ready PHP ticketing system that integrates with WhatsApp Business API for seamless customer communication.

---

## 🎯 Features

- **WhatsApp Integration**: All customer communication happens through WhatsApp
- **Ticket Management**: Complete ticket lifecycle tracking
- **Service Categories**: Education, IT Services, CLACS-Farm, Automobile
- **Admin Dashboard**: Full-featured admin panel for managing tickets
- **Message History**: Complete audit trail of all conversations
- **Staff Assignment**: Assign tickets to team members
- **Status Tracking**: Track ticket progress through Open, In Progress, Waiting, Resolved, Closed
- **Priority Management**: Set ticket priorities (Low, Normal, High, Urgent)
- **Secure**: Built with security best practices

---

## 📋 Requirements

- Ubuntu 20.04 LTS or higher
- Apache 2.4+
- MySQL 5.7+ or MariaDB 10.3+
- PHP 7.4+ or PHP 8.0+
- WhatsApp Business API Account
- Domain name (for production)
- SSL Certificate (recommended)

---

## 🚀 Quick Start Installation

### 1. Clone/Upload Files

Upload all files to your server or clone the repository.

### 2. Run Deployment Script

```bash
sudo chmod +x deployment/deploy.sh
sudo ./deployment/deploy.sh
```

The script will:
- Install required packages
- Configure Apache
- Create MySQL database
- Set up file permissions
- Configure SSL (optional)

### 3. Configure WhatsApp Business API

Edit `/var/www/greendome-ticketing/config/config.php`:

```php
define('WHATSAPP_PHONE_NUMBER_ID', 'your_phone_number_id');
define('WHATSAPP_ACCESS_TOKEN', 'your_access_token');
define('WHATSAPP_VERIFY_TOKEN', 'your_webhook_verify_token');
```

### 4. Set Up Webhook

In your WhatsApp Business API dashboard:
- Webhook URL: `https://yourdomain.com/webhook.php`
- Verify Token: (same as WHATSAPP_VERIFY_TOKEN in config)
- Subscribe to: `messages`

### 5. Update Business Number

Edit `/var/www/greendome-ticketing/public/ticket-success.php`:

```php
$whatsappNumber = '2348012345678'; // Replace with your WhatsApp Business number
```

### 6. Login to Admin Panel

- URL: `https://yourdomain.com/admin/login.php`
- Username: `admin`
- Password: `admin123` (⚠️ CHANGE THIS IMMEDIATELY!)

---

## 📁 File Structure

```
greendome-ticketing/
├── config/
│   └── config.php              # Main configuration file
├── database/
│   └── schema.sql              # Database schema
├── deployment/
│   ├── deploy.sh               # Automated deployment script
│   └── greendome.conf          # Apache vHost configuration
├── includes/
│   ├── functions.php           # Utility functions
│   └── whatsapp.php            # WhatsApp API functions
├── logs/                       # Application logs (auto-created)
├── public/
│   ├── admin/
│   │   ├── login.php           # Admin login
│   │   ├── dashboard.php       # Ticket list
│   │   ├── ticket-view.php     # View/reply to tickets
│   │   └── logout.php          # Logout
│   ├── create-ticket.php       # Ticket creation form
│   ├── index.php               # Homepage/service selection
│   ├── ticket-success.php      # Success page with WhatsApp redirect
│   ├── webhook.php             # WhatsApp webhook receiver
│   └── .htaccess               # Security & rewrite rules
└── README.md                   # This file
```

---

## 🔧 Manual Installation (Alternative)

### 1. Install LAMP Stack

```bash
sudo apt update
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-curl php-json php-mbstring php-xml
```

### 2. Create Database

```bash
mysql -u root -p
```

```sql
CREATE DATABASE greendome_tickets CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'greendome_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON greendome_tickets.* TO 'greendome_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Import Schema

```bash
mysql -u root -p greendome_tickets < database/schema.sql
```

### 4. Configure Apache

```bash
sudo cp deployment/greendome.conf /etc/apache2/sites-available/
sudo a2ensite greendome.conf
sudo a2enmod rewrite headers ssl
sudo systemctl restart apache2
```

### 5. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/greendome-ticketing
sudo chmod -R 755 /var/www/greendome-ticketing
sudo chmod -R 777 /var/www/greendome-ticketing/logs
```

---

## 🔐 Security Checklist

- [ ] Change default admin password
- [ ] Update database credentials in config.php
- [ ] Set strong ENCRYPTION_KEY in config.php
- [ ] Enable HTTPS/SSL
- [ ] Keep WhatsApp API tokens secure
- [ ] Restrict access to /config, /includes, /database directories
- [ ] Enable firewall (UFW)
- [ ] Keep system and PHP updated
- [ ] Review Apache logs regularly
- [ ] Set up automated backups

---

## 📱 WhatsApp Business API Setup

### Getting Started

1. **Create Facebook Business Account**: https://business.facebook.com
2. **Set up WhatsApp Business API**: https://business.facebook.com/wa/manage
3. **Get Phone Number ID**: From WhatsApp Manager
4. **Generate Access Token**: From Meta Developer Console
5. **Create Verify Token**: Any random string for webhook verification

### Webhook Configuration

- **Callback URL**: `https://yourdomain.com/webhook.php`
- **Verify Token**: Match with config.php
- **Fields**: Subscribe to `messages`

---

## 🎫 Ticket Workflow

1. **Customer Action**: Visits website → Selects service → Fills form
2. **System Action**: Creates ticket → Sends WhatsApp notification
3. **Customer Action**: Continues conversation on WhatsApp
4. **Staff Action**: Receives notification → Views ticket → Replies via admin panel
5. **System Action**: Sends reply via WhatsApp → Logs conversation
6. **Staff Action**: Updates status → Resolves/Closes ticket
7. **Customer Action**: Receives status updates via WhatsApp

---

## 🔍 Troubleshooting

### WhatsApp Messages Not Sending

1. Check WhatsApp API credentials in config.php
2. Verify access token is valid
3. Check `/var/www/greendome-ticketing/logs/php_errors.log`
4. Ensure phone numbers are in international format (234...)

### Webhook Not Receiving Messages

1. Verify webhook URL is publicly accessible
2. Check verify token matches
3. Review `/var/www/greendome-ticketing/logs/webhook.log`
4. Ensure HTTPS is enabled (required by WhatsApp)

### Database Connection Error

1. Verify MySQL is running: `sudo systemctl status mysql`
2. Check database credentials in config.php
3. Ensure database user has proper permissions

### Apache 500 Error

1. Check Apache error logs: `sudo tail -f /var/log/apache2/greendome_error.log`
2. Verify file permissions
3. Check PHP error logs
4. Ensure all required PHP modules are installed

---

## 🔄 Backup & Maintenance

### Database Backup

```bash
# Create backup
mysqldump -u greendome_user -p greendome_tickets > backup_$(date +%Y%m%d).sql

# Restore backup
mysql -u greendome_user -p greendome_tickets < backup_20240101.sql
```

### File Backup

```bash
# Backup entire application
tar -czf greendome_backup_$(date +%Y%m%d).tar.gz /var/www/greendome-ticketing
```

### Automated Backups (Cron)

```bash
# Add to crontab: sudo crontab -e
0 2 * * * mysqldump -u greendome_user -p'password' greendome_tickets > /backup/db_$(date +\%Y\%m\%d).sql
0 3 * * * tar -czf /backup/files_$(date +\%Y\%m\%d).tar.gz /var/www/greendome-ticketing
```

---

## 👥 User Management

### Create New Staff User

```bash
mysql -u greendome_user -p greendome_tickets
```

```sql
INSERT INTO staff_users (username, password_hash, full_name, email, role) 
VALUES ('username', '$2y$10$hash', 'Full Name', 'email@example.com', 'support');
```

### Generate Password Hash

```php
<?php
echo password_hash('your_password', PASSWORD_DEFAULT);
?>
```

---

## 📊 Reports & Analytics

Access the admin dashboard to view:
- Total tickets
- Open tickets
- Tickets in progress
- Tickets waiting on customer
- Filter by service, status, assigned staff
- Search by ticket ID, customer name, or phone

---

## 🆘 Support

For issues or questions:
- Email: support@greendome.com
- Check logs: `/var/www/greendome-ticketing/logs/`
- Review Apache logs: `/var/log/apache2/`

---

## 📝 License

Copyright © 2024 Greendome Engineering Technology & Science

---

## 🙏 Credits

Developed for Greendome Engineering Technology & Science
*Engineering Technology, Science & Innovation*

---

**Made with ❤️ for better customer service**
