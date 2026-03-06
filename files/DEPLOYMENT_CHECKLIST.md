# Production Deployment Checklist

Use this checklist to ensure a smooth production deployment.

---

## Pre-Deployment (1-2 Days Before)

### Server Preparation
- [ ] Ubuntu 20.04+ server provisioned
- [ ] Domain name configured and pointing to server IP
- [ ] SSH access confirmed
- [ ] Root/sudo access verified
- [ ] Server has minimum 2GB RAM, 20GB disk space
- [ ] Firewall configured (ports 22, 80, 443)

### WhatsApp Business API
- [ ] Facebook Business account created
- [ ] WhatsApp Business API account set up
- [ ] Phone number verified
- [ ] Access token generated
- [ ] Phone Number ID obtained
- [ ] Webhook verify token created (random string)
- [ ] Test message sent successfully

### Documentation Review
- [ ] Read README.md completely
- [ ] Read INSTALLATION.md step-by-step
- [ ] Review TESTING.md procedures
- [ ] Understand file structure

---

## Deployment Day

### Phase 1: Server Setup (30 minutes)

```bash
# 1. Update server
sudo apt update && sudo apt upgrade -y

# 2. Upload files
# Use SCP, SFTP, or Git to upload files to /tmp/greendome-ticketing

# 3. Verify files uploaded
ls -la /tmp/greendome-ticketing/

# 4. Check deployment script
cat /tmp/greendome-ticketing/deployment/deploy.sh
```

**Checkpoint:** ✅ Files uploaded successfully

---

### Phase 2: Run Deployment (15 minutes)

```bash
# 1. Make script executable
cd /tmp/greendome-ticketing/deployment
sudo chmod +x deploy.sh

# 2. Run deployment
sudo ./deploy.sh
```

**You will be prompted for:**
- Domain name: `greendome.com`
- MySQL root password: `[create strong password]`
- Database user password: `[create strong password]`
- SSL email (optional): `admin@greendome.com`

**Checkpoint:** ✅ Deployment completed without errors

---

### Phase 3: Configure WhatsApp (10 minutes)

```bash
# 1. Run WhatsApp configuration
sudo chmod +x configure-whatsapp.sh
sudo ./configure-whatsapp.sh
```

**You will be prompted for:**
- Phone Number ID
- Access Token
- Verify Token
- Business Number

**Alternative: Manual Configuration**

```bash
sudo nano /var/www/greendome-ticketing/config/config.php
```

Update:
```php
define('WHATSAPP_PHONE_NUMBER_ID', 'your_actual_id');
define('WHATSAPP_ACCESS_TOKEN', 'your_actual_token');
define('WHATSAPP_VERIFY_TOKEN', 'your_verify_token');
```

Also update:
```bash
sudo nano /var/www/greendome-ticketing/public/ticket-success.php
```

Line 37:
```php
$whatsappNumber = '2348012345678'; // Your number
```

**Checkpoint:** ✅ WhatsApp credentials configured

---

### Phase 4: Webhook Setup (5 minutes)

1. Go to Meta for Developers
2. Navigate to your WhatsApp app
3. Go to WhatsApp > Configuration
4. Click "Edit" on Webhook
5. Enter:
   - **Callback URL**: `https://greendome.com/webhook.php`
   - **Verify Token**: `[your verify token from config]`
6. Click "Verify and Save"
7. Subscribe to field: `messages`

**Test webhook:**
```bash
# Watch logs
sudo tail -f /var/www/greendome-ticketing/logs/webhook.log

# In another terminal, send test message to your WhatsApp Business number
# You should see the webhook data appear in logs
```

**Checkpoint:** ✅ Webhook verified and receiving messages

---

### Phase 5: Security Hardening (10 minutes)

```bash
# 1. Generate strong encryption key
ENCRYPTION_KEY=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-32)
echo "Save this key: $ENCRYPTION_KEY"

# 2. Update config
sudo nano /var/www/greendome-ticketing/config/config.php
# Replace ENCRYPTION_KEY value

# 3. Verify file permissions
sudo chown -R www-data:www-data /var/www/greendome-ticketing
sudo find /var/www/greendome-ticketing -type d -exec chmod 755 {} \;
sudo find /var/www/greendome-ticketing -type f -exec chmod 644 {} \;
sudo chmod -R 777 /var/www/greendome-ticketing/logs

# 4. Test directory protection
curl https://greendome.com/config/config.php
# Should return 403 or 404

# 5. Verify SSL
curl -I https://greendome.com
# Should show HTTPS with valid certificate
```

**Checkpoint:** ✅ Security measures in place

---

### Phase 6: Change Default Password (5 minutes)

```bash
# Generate password hash
php -r "echo password_hash('YourNewStrongPassword123!', PASSWORD_DEFAULT);"

# Copy the hash output
```

```bash
# Update database
sudo mysql -u greendome_user -p greendome_tickets
```

```sql
UPDATE staff_users 
SET password_hash = 'paste_hash_here' 
WHERE username = 'admin';

SELECT username, full_name FROM staff_users;
EXIT;
```

**Checkpoint:** ✅ Admin password changed

---

### Phase 7: Testing (20 minutes)

Follow TESTING.md procedures:

```bash
# 1. Test homepage
curl -I https://greendome.com

# 2. Test admin login
# Go to: https://greendome.com/admin/login.php
# Login with new password

# 3. Create test ticket
# Go to: https://greendome.com
# Select a service and create ticket

# 4. Verify ticket in admin dashboard

# 5. Test WhatsApp notification received

# 6. Send test message to WhatsApp Business number

# 7. Verify message appears in admin panel

# 8. Reply to ticket from admin

# 9. Verify reply sent via WhatsApp
```

**Checkpoint:** ✅ All core functions working

---

### Phase 8: Backup Setup (10 minutes)

```bash
# 1. Create backup directory
sudo mkdir -p /backup/greendome
sudo chown www-data:www-data /backup/greendome

# 2. Create backup script
sudo nano /usr/local/bin/greendome-backup.sh
```

Paste:
```bash
#!/bin/bash
BACKUP_DIR="/backup/greendome"
DATE=$(date +%Y%m%d_%H%M%S)
DB_PASS="your_db_password"

mysqldump -u greendome_user -p"$DB_PASS" greendome_tickets > $BACKUP_DIR/db_$DATE.sql
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/greendome-ticketing

find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
```

```bash
# 3. Make executable
sudo chmod +x /usr/local/bin/greendome-backup.sh

# 4. Test backup
sudo /usr/local/bin/greendome-backup.sh
ls -lh /backup/greendome/

# 5. Schedule daily backups
sudo crontab -e
```

Add:
```
0 2 * * * /usr/local/bin/greendome-backup.sh
```

**Checkpoint:** ✅ Automated backups configured

---

### Phase 9: Monitoring Setup (10 minutes)

```bash
# 1. Create monitoring script
sudo nano /usr/local/bin/greendome-monitor.sh
```

Paste:
```bash
#!/bin/bash
LOG_FILE="/var/log/greendome-monitor.log"

echo "$(date) - System Check" >> $LOG_FILE

# Check Apache
systemctl is-active --quiet apache2 || echo "ALERT: Apache down!" >> $LOG_FILE

# Check MySQL
systemctl is-active --quiet mysql || echo "ALERT: MySQL down!" >> $LOG_FILE

# Check disk space
DISK_USAGE=$(df -h / | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "WARNING: Disk usage at ${DISK_USAGE}%" >> $LOG_FILE
fi

# Check recent errors
ERROR_COUNT=$(tail -100 /var/www/greendome-ticketing/logs/php_errors.log | grep -c "ERROR")
if [ $ERROR_COUNT -gt 10 ]; then
    echo "WARNING: $ERROR_COUNT recent errors detected" >> $LOG_FILE
fi
```

```bash
# 2. Make executable
sudo chmod +x /usr/local/bin/greendome-monitor.sh

# 3. Schedule hourly checks
sudo crontab -e
```

Add:
```
0 * * * * /usr/local/bin/greendome-monitor.sh
```

**Checkpoint:** ✅ Monitoring active

---

## Post-Deployment

### Immediate (Within 1 Hour)

- [ ] Send test ticket from real customer phone
- [ ] Verify WhatsApp notifications working
- [ ] Test complete ticket lifecycle
- [ ] Monitor logs for errors
- [ ] Verify backups are running
- [ ] Document all passwords securely

### Within 24 Hours

- [ ] Create staff user accounts
- [ ] Train staff on admin panel
- [ ] Set up monitoring alerts
- [ ] Review all log files
- [ ] Performance test with multiple concurrent users
- [ ] Verify SSL certificate auto-renewal

### Within 1 Week

- [ ] Monitor ticket volume
- [ ] Gather user feedback
- [ ] Optimize database queries if needed
- [ ] Review security logs
- [ ] Test backup restoration
- [ ] Document any custom configurations

---

## Rollback Plan

If critical issues arise:

```bash
# 1. Disable site
sudo a2dissite greendome.conf
sudo systemctl reload apache2

# 2. Restore database backup
sudo mysql -u greendome_user -p greendome_tickets < /backup/greendome/db_LATEST.sql

# 3. Restore files
sudo tar -xzf /backup/greendome/files_LATEST.tar.gz -C /

# 4. Re-enable site
sudo a2ensite greendome.conf
sudo systemctl reload apache2
```

---

## Support Contacts

**Technical Support:**
- Email: support@greendome.com
- Phone: [Your support number]

**Emergency Contacts:**
- System Admin: [Phone/Email]
- Database Admin: [Phone/Email]
- WhatsApp API Support: https://business.facebook.com/help

---

## Final Verification

Before marking deployment complete:

- [ ] Homepage loads: https://greendome.com ✅
- [ ] SSL certificate valid ✅
- [ ] Ticket creation works ✅
- [ ] WhatsApp notifications sent ✅
- [ ] Webhook receives messages ✅
- [ ] Admin login works ✅
- [ ] Ticket viewing works ✅
- [ ] Reply via WhatsApp works ✅
- [ ] Status updates work ✅
- [ ] Backups configured ✅
- [ ] Monitoring active ✅
- [ ] Documentation complete ✅

---

## Deployment Sign-Off

```
Deployment Date: _______________
Deployed By: _______________
Verified By: _______________

Domain: _______________
Database: _______________
WhatsApp Number: _______________

Status: [ ] SUCCESSFUL  [ ] ISSUES (document below)

Notes:
_________________________________
_________________________________
_________________________________

Signature: _______________
```

---

**🎉 Deployment Complete - System Live!**
