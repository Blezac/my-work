# DEPLOYMENT GUIDE
# Mahanaim Impact Network - Event Registration System

## Pre-Deployment Checklist

### 1. Server Requirements
- [ ] PHP 7.4 or higher
- [ ] MySQL 5.7 or higher
- [ ] Apache/Nginx with mod_rewrite enabled
- [ ] SSL certificate installed
- [ ] Composer installed

### 2. Domain & Hosting
- [ ] Domain name configured
- [ ] DNS records pointed to server
- [ ] SSL/HTTPS enabled
- [ ] Email sending capability (SMTP)

---

## Step-by-Step Deployment

### STEP 1: Upload Files

Upload all files to your web server (e.g., via FTP, cPanel File Manager, or Git):

```bash
# If using Git
git clone [your-repo-url] /path/to/public_html
cd /path/to/public_html

# Or upload via FTP to public_html directory
```

### STEP 2: Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

### STEP 3: Create Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE mahanaim_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Exit MySQL
exit;

# Import schema
mysql -u your_username -p mahanaim_db < database.sql
```

### STEP 4: Configure Database

Edit `config/db.php`:

```php
define('DB_HOST', 'localhost'); // or your DB host
define('DB_NAME', 'mahanaim_db');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
```

### STEP 5: Set File Permissions

```bash
chmod 755 qrcodes/
chmod 644 config/db.php
chmod 644 .htaccess
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
```

### STEP 6: Configure Paystack

1. Sign up at https://paystack.com
2. Get your Live API keys from Dashboard > Settings > API Keys & Webhooks
3. Update `payments/paystack.php`:

```php
define('PAYSTACK_SECRET_KEY', 'sk_live_YOUR_SECRET_KEY');
define('PAYSTACK_PUBLIC_KEY', 'pk_live_YOUR_PUBLIC_KEY');
```

4. Set webhook URL in Paystack dashboard (optional):
   `https://yourdomain.com/verify.php`

### STEP 7: Configure Email (SMTP)

Update email settings in:
- `emails/mailer.php`
- `verify.php` (sendParticipantEmail and sendAdminNotification functions)

```php
$mail->Host = 'smtp.gmail.com'; // or your SMTP server
$mail->Username = 'your_email@domain.com';
$mail->Password = 'your_app_password';
$mail->Port = 587; // or 465 for SSL
```

**For Gmail:**
1. Enable 2-factor authentication
2. Generate App Password: https://myaccount.google.com/apppasswords
3. Use the app password in the configuration

### STEP 8: Update Admin Password

```bash
# Generate new password hash
php -r "echo password_hash('YOUR_NEW_PASSWORD', PASSWORD_DEFAULT);"

# Update in database
mysql -u your_username -p mahanaim_db
UPDATE admins SET password = 'NEW_HASH_HERE' WHERE username = 'admin';
exit;
```

### STEP 9: Update Site URLs

Search and replace in files if needed:
- Callback URLs in payment files
- Email links
- Asset paths (if using CDN)

### STEP 10: Enable HTTPS

Uncomment in `.htaccess`:

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### STEP 11: Configure PHP Settings

If needed, create `php.ini` or `.user.ini`:

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M
display_errors = Off
log_errors = On
error_log = /path/to/error.log
```

### STEP 12: Security Hardening

1. **Remove install.php:**
```bash
rm install.php
```

2. **Protect sensitive files** (already in .htaccess):
- database.sql
- composer.json
- README.md

3. **Set secure session settings** in php.ini:
```ini
session.cookie_httponly = 1
session.cookie_secure = 1
session.use_strict_mode = 1
```

### STEP 13: Test Everything

1. **Website Pages:**
   - [ ] Home page loads correctly
   - [ ] Navigation works on all pages
   - [ ] Images and assets load
   - [ ] Forms validate properly

2. **Registration Flow:**
   - [ ] Event listing appears
   - [ ] Registration form submits
   - [ ] Paystack payment works
   - [ ] Payment verification succeeds
   - [ ] QR code generates
   - [ ] Emails are sent

3. **Admin Dashboard:**
   - [ ] Login works
   - [ ] Statistics display correctly
   - [ ] Event registrations show
   - [ ] CSV export works
   - [ ] Logout functions

4. **Security:**
   - [ ] HTTPS works (no mixed content)
   - [ ] Admin area requires login
   - [ ] Database credentials secure
   - [ ] install.php deleted

### STEP 14: Monitor & Maintain

1. **Set up monitoring:**
   - Server uptime monitoring
   - Error log monitoring
   - Database backup schedule

2. **Regular backups:**
```bash
# Database backup
mysqldump -u username -p mahanaim_db > backup_$(date +%Y%m%d).sql

# Files backup
tar -czf mahanaim_backup_$(date +%Y%m%d).tar.gz /path/to/website
```

3. **Update regularly:**
   - PHP dependencies: `composer update`
   - PHP version
   - SSL certificate

---

## Production Optimization

### Performance

1. **Enable Gzip compression** (in .htaccess - already included)

2. **Enable browser caching** (in .htaccess - already included)

3. **Optimize images:**
```bash
# Use tools like:
# - TinyPNG (online)
# - ImageOptim (Mac)
# - jpegoptim, optipng (Linux)
```

4. **Enable OPcache** in php.ini:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

### Database

1. **Add indexes for performance:**
```sql
CREATE INDEX idx_event_date ON events(event_date);
CREATE INDEX idx_payment_status ON registrations(payment_status);
CREATE INDEX idx_created_at ON registrations(created_at);
```

2. **Regular maintenance:**
```sql
OPTIMIZE TABLE events;
OPTIMIZE TABLE registrations;
```

---

## Troubleshooting Common Issues

### Payment Not Working
- Check Paystack API keys
- Verify webhook URL
- Check callback URL is accessible
- Test with Paystack test keys first

### Emails Not Sending
- Verify SMTP credentials
- Check firewall allows port 587/465
- Test email manually with PHPMailer test script
- Check spam folder

### QR Codes Not Generating
- Verify qrcodes/ directory exists and is writable
- Check PHP GD extension: `php -m | grep gd`
- Check Composer dependencies installed

### Database Connection Failed
- Verify MySQL is running: `systemctl status mysql`
- Check credentials in config/db.php
- Test connection: `mysql -u username -p -h localhost`

### 500 Internal Server Error
- Check PHP error logs: `tail -f /var/log/apache2/error.log`
- Verify .htaccess syntax
- Check file permissions

---

## Support Resources

- **Paystack Docs:** https://paystack.com/docs
- **PHPMailer:** https://github.com/PHPMailer/PHPMailer
- **PHP Manual:** https://php.net/manual

---

## Post-Deployment Tasks

1. **Update contact information** throughout site
2. **Test on multiple devices** (mobile, tablet, desktop)
3. **Test with real payment** (small amount)
4. **Set up Google Analytics** (optional)
5. **Submit sitemap to Google** (optional)
6. **Social media integration** (add real links)
7. **Monitor first week closely**

---

## Emergency Contacts

Keep these handy:
- Hosting provider support
- Domain registrar support
- Paystack support: support@paystack.com
- Database admin contact

---

**Deployment completed successfully! 🎉**

Your Mahanaim Impact Network website is now live and ready to empower youth across Nigeria!

*Empowering Purpose. Building Legacy.*