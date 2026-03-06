# Testing Guide - Greendome Ticketing System

Complete testing procedures to ensure the system is working correctly.

---

## Pre-Testing Checklist

Before running tests, ensure:

- [ ] Application is deployed
- [ ] Database is set up
- [ ] Apache is running
- [ ] Configuration is updated
- [ ] WhatsApp API credentials are configured
- [ ] Webhook is configured in Meta

---

## Test 1: Basic Connectivity

### Test Apache

```bash
# Check Apache status
sudo systemctl status apache2

# Test configuration
sudo apache2ctl configtest
```

**Expected:** Apache2 Syntax OK

### Test Website Access

```bash
curl -I http://yourdomain.com
```

**Expected:** HTTP 200 OK

### Test SSL (if configured)

```bash
curl -I https://yourdomain.com
```

**Expected:** HTTP 200 OK with SSL headers

---

## Test 2: Database Connectivity

```bash
# Test database connection
mysql -u greendome_user -p greendome_tickets -e "SHOW TABLES;"
```

**Expected Output:**

```
+-------------------------------+
| Tables_in_greendome_tickets   |
+-------------------------------+
| services                      |
| staff_users                   |
| status_history                |
| ticket_assignments            |
| ticket_messages               |
| tickets                       |
| whatsapp_webhook_logs         |
+-------------------------------+
```

### Verify Default Data

```bash
mysql -u greendome_user -p greendome_tickets
```

```sql
-- Check services
SELECT * FROM services;

-- Check admin user
SELECT username, full_name, role FROM staff_users;
```

**Expected:** 4 services and 1 admin user

---

## Test 3: Homepage & Service Selection

### Manual Test

1. Open browser: `http://yourdomain.com`
2. Verify:
   - [ ] Page loads correctly
   - [ ] 4 service cards are visible
   - [ ] Each service has icon, name, description
   - [ ] Links work when clicked

### Automated Test

```bash
# Test homepage
curl http://yourdomain.com | grep "Greendome Engineering"

# Test service links
curl http://yourdomain.com/create-ticket.php?service=education
curl http://yourdomain.com/create-ticket.php?service=it-services
curl http://yourdomain.com/create-ticket.php?service=clacs-farm
curl http://yourdomain.com/create-ticket.php?service=automobile
```

**Expected:** Each returns 200 OK and shows the form

---

## Test 4: Ticket Creation

### Manual Test

1. Go to: `http://yourdomain.com`
2. Click "Education" service
3. Fill form:
   - Name: "Test User"
   - WhatsApp: "08012345678" or "2348012345678"
   - Email: "test@example.com" (optional)
   - Type: "Inquiry"
   - Message: "This is a test ticket"
4. Submit form

**Expected:**
- [ ] Redirected to success page
- [ ] Ticket code displayed (format: GDM-XXXXXX)
- [ ] WhatsApp redirect initiated
- [ ] WhatsApp notification received

### Database Verification

```sql
-- Check ticket was created
SELECT * FROM tickets ORDER BY id DESC LIMIT 1;

-- Check first message was logged
SELECT * FROM ticket_messages ORDER BY id DESC LIMIT 1;
```

### Log Verification

```bash
# Check PHP errors
sudo tail -20 /var/www/greendome-ticketing/logs/php_errors.log

# Should show no errors related to ticket creation
```

---

## Test 5: WhatsApp Integration

### Test Webhook Verification (GET)

```bash
curl "http://yourdomain.com/webhook.php?hub.mode=subscribe&hub.verify_token=your_verify_token&hub.challenge=test123"
```

**Expected:** Returns "test123"

### Test Webhook Message Reception (POST)

```bash
curl -X POST http://yourdomain.com/webhook.php \
  -H "Content-Type: application/json" \
  -d '{
    "entry": [{
      "changes": [{
        "value": {
          "messages": [{
            "from": "2348012345678",
            "id": "test_msg_123",
            "type": "text",
            "text": {
              "body": "Test message from customer"
            }
          }]
        }
      }]
    }]
  }'
```

**Expected:** HTTP 200 OK, message logged

### Verify in Database

```sql
SELECT * FROM ticket_messages WHERE whatsapp_message_id = 'test_msg_123';
```

### Check Webhook Logs

```bash
sudo tail -20 /var/www/greendome-ticketing/logs/webhook.log
```

**Expected:** JSON data from webhook

---

## Test 6: Admin Login

### Manual Test

1. Go to: `http://yourdomain.com/admin/login.php`
2. Enter credentials:
   - Username: `admin`
   - Password: `admin123`
3. Click "Sign In"

**Expected:**
- [ ] Redirected to dashboard
- [ ] Session created
- [ ] User info displayed in navbar

### Test Invalid Login

1. Try wrong password
2. Try non-existent username

**Expected:** Error message displayed

### Test Session

```bash
# Login then check
curl -c cookies.txt -d "username=admin&password=admin123" \
  http://yourdomain.com/admin/login.php

# Access dashboard with session
curl -b cookies.txt http://yourdomain.com/admin/dashboard.php
```

---

## Test 7: Admin Dashboard

### Manual Test

1. Login to admin panel
2. Verify:
   - [ ] Statistics cards show correct counts
   - [ ] Ticket list displays
   - [ ] Filters work (service, status, assigned)
   - [ ] Search works
   - [ ] Ticket links work

### Test Filters

```bash
# Test service filter
curl -b cookies.txt "http://yourdomain.com/admin/dashboard.php?service=1"

# Test status filter
curl -b cookies.txt "http://yourdomain.com/admin/dashboard.php?status=Open"

# Test search
curl -b cookies.txt "http://yourdomain.com/admin/dashboard.php?search=GDM"
```

---

## Test 8: Ticket Viewing & Reply

### Manual Test

1. Login to admin
2. Click on a ticket
3. Verify:
   - [ ] All ticket details visible
   - [ ] Customer info displayed
   - [ ] Messages shown in chronological order
   - [ ] Reply form present (if not closed)

### Test Reply

1. Type a test reply
2. Click "Send Reply via WhatsApp"
3. Check:
   - [ ] Reply saved in database
   - [ ] WhatsApp API called
   - [ ] Message appears in conversation

### Database Verification

```sql
-- Check reply was logged
SELECT * FROM ticket_messages 
WHERE message_type = 'outgoing' 
ORDER BY id DESC LIMIT 1;
```

---

## Test 9: Status Updates

### Manual Test

1. Open a ticket in admin
2. Change status to "In Progress"
3. Verify:
   - [ ] Status updated immediately
   - [ ] System message added
   - [ ] WhatsApp notification sent (if configured)

### Database Verification

```sql
-- Check status history
SELECT * FROM status_history ORDER BY id DESC LIMIT 1;
```

---

## Test 10: Ticket Assignment

### Create Test Staff User

```sql
INSERT INTO staff_users (username, password_hash, full_name, email, role) 
VALUES ('teststaff', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Staff', 'test@greendome.com', 'support');
```

### Test Assignment

1. Open a ticket
2. Assign to "Test Staff"
3. Verify:
   - [ ] Assignment saved
   - [ ] System message added
   - [ ] Assignment history logged

### Database Verification

```sql
SELECT * FROM ticket_assignments ORDER BY id DESC LIMIT 1;
```

---

## Test 11: Security Tests

### Test SQL Injection

```bash
# Try SQL injection in login
curl -d "username=admin' OR '1'='1&password=test" \
  http://yourdomain.com/admin/login.php
```

**Expected:** Should NOT login (properly escaped)

### Test XSS

Create ticket with:
- Name: `<script>alert('XSS')</script>`
- Message: `<img src=x onerror=alert('XSS')>`

**Expected:** Scripts escaped and displayed as text

### Test Directory Access

```bash
# Try to access config
curl http://yourdomain.com/config/config.php

# Try to access includes
curl http://yourdomain.com/includes/functions.php

# Try to access database
curl http://yourdomain.com/database/schema.sql
```

**Expected:** 403 Forbidden or 404 Not Found

### Test Session Security

```bash
# Try to access dashboard without login
curl http://yourdomain.com/admin/dashboard.php
```

**Expected:** Redirect to login

---

## Test 12: Load Testing (Optional)

### Install Apache Bench

```bash
sudo apt install apache2-utils -y
```

### Test Homepage

```bash
ab -n 100 -c 10 http://yourdomain.com/
```

### Test Ticket Creation

```bash
ab -n 50 -c 5 -p ticket.txt -T application/x-www-form-urlencoded \
  http://yourdomain.com/create-ticket.php?service=education
```

**Expected:** All requests should complete successfully

---

## Test 13: Error Handling

### Test Invalid Ticket ID

```bash
curl http://yourdomain.com/admin/ticket-view.php?id=99999
```

**Expected:** Redirect to dashboard or error page

### Test Invalid Service

```bash
curl http://yourdomain.com/create-ticket.php?service=invalid
```

**Expected:** Redirect to homepage

### Test Malformed Data

Submit ticket with:
- Empty name
- Invalid phone number
- Invalid email

**Expected:** Validation errors displayed

---

## Test 14: Logging & Monitoring

### Check All Logs

```bash
# Application logs
sudo tail -50 /var/www/greendome-ticketing/logs/php_errors.log
sudo tail -50 /var/www/greendome-ticketing/logs/webhook.log

# Apache logs
sudo tail -50 /var/log/apache2/greendome_access.log
sudo tail -50 /var/log/apache2/greendome_error.log

# MySQL logs
sudo tail -50 /var/log/mysql/error.log
```

**Expected:** No critical errors

### Test Webhook Logging

Send test message, then:

```sql
SELECT * FROM whatsapp_webhook_logs ORDER BY id DESC LIMIT 5;
```

**Expected:** Webhook data logged

---

## Test 15: Backup & Recovery

### Test Backup

```bash
# Database backup
mysqldump -u greendome_user -p greendome_tickets > test_backup.sql

# File backup
tar -czf test_backup.tar.gz /var/www/greendome-ticketing
```

**Expected:** Backup files created successfully

### Test Restore

```bash
# Create test database
mysql -u root -p -e "CREATE DATABASE test_restore;"

# Restore
mysql -u root -p test_restore < test_backup.sql

# Verify
mysql -u root -p test_restore -e "SHOW TABLES;"

# Cleanup
mysql -u root -p -e "DROP DATABASE test_restore;"
```

**Expected:** Restore successful

---

## Performance Benchmarks

Expected performance on standard server:

- **Homepage load**: < 500ms
- **Ticket creation**: < 1s
- **Admin dashboard**: < 800ms
- **Ticket view**: < 600ms
- **WhatsApp send**: < 2s
- **Webhook processing**: < 300ms

---

## Test Results Template

```
GREENDOME TICKETING SYSTEM - TEST RESULTS
Date: _______________
Tester: _______________

[ ] Test 1: Basic Connectivity - PASS/FAIL
[ ] Test 2: Database Connectivity - PASS/FAIL
[ ] Test 3: Homepage - PASS/FAIL
[ ] Test 4: Ticket Creation - PASS/FAIL
[ ] Test 5: WhatsApp Integration - PASS/FAIL
[ ] Test 6: Admin Login - PASS/FAIL
[ ] Test 7: Dashboard - PASS/FAIL
[ ] Test 8: Ticket Viewing - PASS/FAIL
[ ] Test 9: Status Updates - PASS/FAIL
[ ] Test 10: Assignments - PASS/FAIL
[ ] Test 11: Security - PASS/FAIL
[ ] Test 12: Load Testing - PASS/FAIL
[ ] Test 13: Error Handling - PASS/FAIL
[ ] Test 14: Logging - PASS/FAIL
[ ] Test 15: Backup - PASS/FAIL

Notes:
_________________________________
_________________________________
_________________________________

Overall Result: PASS / FAIL
```

---

## Continuous Testing

### Daily Checks

```bash
# Check services
sudo systemctl status apache2
sudo systemctl status mysql

# Check disk space
df -h

# Check recent errors
sudo tail -20 /var/www/greendome-ticketing/logs/php_errors.log
```

### Weekly Checks

- Review ticket volumes
- Check database size
- Verify backups
- Review security logs
- Test WhatsApp integration

### Monthly Checks

- Full security audit
- Performance testing
- Update dependencies
- Review and archive old tickets

---

**Testing guide complete. Use this to verify your installation is production-ready!**
