# 🚀 BELZAC CRM - Complete Email Marketing Platform

A full-stack email marketing platform similar to Brevo/Sendinblue, built with PHP and MySQL.

## 📦 What's Included

- ✅ Complete LAMP stack deployment script
- ✅ RESTful API backend (PHP)
- ✅ Modern responsive frontend (HTML/CSS/JS)
- ✅ MySQL database with complete schema
- ✅ User authentication system
- ✅ Contact management
- ✅ Campaign builder (drag & drop)
- ✅ Email tracking & analytics
- ✅ Production-ready security features

## 🎯 Features

### Email Marketing
- Drag-and-drop campaign builder
- HTML email template editor
- Contact list management
- Bulk email sending
- Email scheduling
- Campaign analytics

### Contact Management
- Import/Export CSV
- Custom fields support
- Tags and segmentation
- List management
- Subscription handling

### Analytics
- Open rate tracking
- Click-through tracking
- Bounce management
- Real-time statistics
- Campaign performance reports

## 📋 System Requirements

- Ubuntu 20.04 or 22.04 LTS
- Apache 2.4+
- MySQL 8.0+
- PHP 8.0+
- 2GB RAM minimum
- 20GB disk space

## 🚀 Quick Installation

### Method 1: Automated Deployment (Recommended)

```bash
# 1. Download the deployment script
wget https://your-server.com/deploy-full-stack.sh

# 2. Make it executable
chmod +x deploy-full-stack.sh

# 3. Run as root
sudo ./deploy-full-stack.sh
```

### Method 2: Manual Installation

```bash
# 1. Update system
sudo apt update && sudo apt upgrade -y

# 2. Install LAMP stack
sudo apt install apache2 mysql-server php libapache2-mod-php php-mysql php-curl php-gd php-mbstring php-xml php-zip -y

# 3. Create database
sudo mysql
CREATE DATABASE belzac_crm;
CREATE USER 'belzac_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON belzac_crm.* TO 'belzac_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# 4. Import database schema
sudo mysql -u belzac_user -p belzac_crm < database.sql

# 5. Upload application files
sudo cp -r * /var/www/html/belzac-crm/

# 6. Set permissions
sudo chown -R www-data:www-data /var/www/html/belzac-crm
sudo chmod -R 755 /var/www/html/belzac-crm
sudo chmod -R 775 /var/www/html/belzac-crm/storage

# 7. Configure Apache
sudo nano /etc/apache2/sites-available/belzac-crm.conf
sudo a2ensite belzac-crm.conf
sudo systemctl restart apache2
```

## ⚙️ Configuration

### 1. Database Configuration
Edit `config/database.php`:
```php
return [
    'host' => 'localhost',
    'database' => 'belzac_crm',
    'username' => 'belzac_user',
    'password' => 'YOUR_PASSWORD_HERE',
];
```

### 2. SMTP Configuration
Edit `config/mail.php`:
```php
return [
    'smtp' => [
        'host' => 'smtp.your-provider.com',
        'port' => 587,
        'username' => 'your_username',
        'password' => 'your_password',
        'encryption' => 'tls',
    ]
];
```

### 3. Application Settings
Edit `config/app.php`:
```php
return [
    'name' => 'Belzac CRM',
    'url' => 'https://your-domain.com',
    'debug' => false, // Set to false in production
];
```

## 🔐 Default Credentials

```
Email: admin@belzac.com
Password: password
```

**⚠️ IMPORTANT: Change the default password immediately after first login!**

## 📁 Directory Structure

```
belzac-crm/
├── config/               # Configuration files
│   ├── database.php
│   ├── app.php
│   └── mail.php
├── app/                  # Backend application
│   ├── Database.php
│   ├── models/
│   │   ├── Auth.php
│   │   ├── Contact.php
│   │   └── Campaign.php
│   ├── controllers/
│   ├── middleware/
│   └── helpers/
├── public/               # Public web root
│   ├── index.html
│   ├── login.html
│   ├── contacts.html
│   ├── campaign-builder.html
│   ├── api/
│   │   └── index.php
│   ├── css/
│   ├── js/
│   └── uploads/
└── storage/
    ├── logs/
    ├── uploads/
    └── cache/
```

## 📡 API Documentation

### Authentication

#### Login
```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@belzac.com",
  "password": "password"
}
```

#### Register
```bash
POST /api/auth/register
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "securepassword",
  "first_name": "John",
  "last_name": "Doe"
}
```

#### Logout
```bash
POST /api/auth/logout
```

### Contacts

#### Get All Contacts
```bash
GET /api/contacts?status=active&limit=50&offset=0
```

#### Get Single Contact
```bash
GET /api/contacts/123
```

#### Create Contact
```bash
POST /api/contacts
Content-Type: application/json

{
  "email": "contact@example.com",
  "first_name": "Jane",
  "last_name": "Smith",
  "company": "Acme Corp"
}
```

#### Update Contact
```bash
PUT /api/contacts/123
Content-Type: application/json

{
  "first_name": "Jane",
  "status": "subscribed"
}
```

#### Delete Contact
```bash
DELETE /api/contacts/123
```

### Campaigns

#### Get All Campaigns
```bash
GET /api/campaigns?status=sent
```

#### Get Campaign Stats
```bash
GET /api/campaigns/456?stats=true
```

#### Create Campaign
```bash
POST /api/campaigns
Content-Type: application/json

{
  "name": "Newsletter March 2026",
  "subject": "Your Monthly Update",
  "from_name": "Belzac Team",
  "from_email": "hello@belzac.com",
  "html_content": "<html>...</html>"
}
```

### Dashboard

#### Get Statistics
```bash
GET /api/dashboard
```

## 🧪 Testing

### Test Database Connection
```bash
php -r "require 'app/Database.php'; \$db = Database::getInstance(); echo 'Connected!';"
```

### Test API
```bash
# Test login
curl -X POST http://localhost/belzac-crm/public/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@belzac.com","password":"password"}'

# Test contacts endpoint
curl http://localhost/belzac-crm/public/api/contacts \
  -H "Cookie: PHPSESSID=your_session_id"
```

## 🔒 Security Features

- ✅ Password hashing (bcrypt)
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ XSS protection
- ✅ CSRF token support
- ✅ Session management
- ✅ Input validation
- ✅ Rate limiting ready
- ✅ Secure headers configured

## 📈 Performance Optimization

### Enable PHP OPcache
```bash
sudo nano /etc/php/8.0/apache2/conf.d/10-opcache.ini
```

Add:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

### Enable Gzip Compression
```bash
sudo a2enmod deflate
sudo systemctl restart apache2
```

## 🐛 Troubleshooting

### Cannot connect to database
- Check MySQL is running: `sudo systemctl status mysql`
- Verify credentials in `config/database.php`
- Check user permissions: `SHOW GRANTS FOR 'belzac_user'@'localhost';`

### Permission denied errors
```bash
sudo chown -R www-data:www-data /var/www/html/belzac-crm
sudo chmod -R 755 /var/www/html/belzac-crm
sudo chmod -R 775 /var/www/html/belzac-crm/storage
```

### Apache not serving the site
- Check virtual host: `sudo apache2ctl -S`
- Check error logs: `sudo tail -f /var/log/apache2/error.log`
- Enable rewrite module: `sudo a2enmod rewrite`

### API returns 404
- Ensure .htaccess is in public directory
- Check mod_rewrite is enabled
- Verify AllowOverride is set to All in Apache config

## 📝 Database Schema

### Main Tables
- **users** - User accounts and authentication
- **contacts** - Email contacts/subscribers
- **lists** - Contact lists/segments
- **campaigns** - Email campaigns
- **email_templates** - Reusable email templates
- **email_events** - Email tracking (opens, clicks, etc.)
- **smtp_settings** - SMTP server configurations
- **activity_logs** - Audit trail

## 🔄 Backup & Restore

### Backup Database
```bash
mysqldump -u belzac_user -p belzac_crm > backup_$(date +%Y%m%d).sql
```

### Restore Database
```bash
mysql -u belzac_user -p belzac_crm < backup_20260210.sql
```

### Backup Files
```bash
tar -czf belzac_backup_$(date +%Y%m%d).tar.gz /var/www/html/belzac-crm
```

## 📚 Additional Resources

- Documentation: `/docs`
- API Reference: `/api-docs`
- Support Forum: TBD
- GitHub Issues: TBD

## 📄 License

MIT License - Free for personal and commercial use

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## ⚡ Quick Tips

1. **First Login**: Use admin@belzac.com / password
2. **Change Password**: Go to Settings after first login
3. **Import Contacts**: Use CSV format with headers
4. **Test Emails**: Use Mailtrap.io for development
5. **Production**: Enable HTTPS and update all configs

## 📧 Support

For issues and questions:
- Email: support@belzac.com
- Documentation: https://docs.belzac.com

---

**Built with ❤️ for email marketers**
