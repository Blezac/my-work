# Greendome Engineering Support & Ticket Tracking System

## 📋 Overview

A lightweight, mobile-friendly Support & Ticket Tracking System integrated with WhatsApp for Greendome Engineering Technology & Science.

**Key Features:**
- Every interaction generates a unique Ticket ID (GETS-XXXXXX)
- Automatic WhatsApp integration
- Staff and customer-initiated communication
- Full conversation tracking
- Mobile-first responsive design
- Professional ticket management dashboard

---

## 🚀 Installation

### 1. Database Setup

```bash
# Import the database schema
mysql -u root -p < database.sql
```

Or manually:
1. Create database: `greendome_support`
2. Run all SQL commands from `database.sql`
3. Default admin credentials:
   - Username: `admin`
   - Password: `admin123` (**CHANGE THIS IMMEDIATELY IN PRODUCTION**)

### 2. Configure Database Connection

Edit `includes/config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'greendome_support');
define('DB_USER', 'your_db_username');
define('DB_PASS', 'your_db_password');
```

### 3. Set Company WhatsApp Number

In `includes/config.php`, update:

```php
define('COMPANY_WHATSAPP', '2349024900124');
```

### 4. File Permissions

```bash
chmod 755 greendome-support/
chmod 644 greendome-support/*.php
chmod 755 greendome-support/includes/
chmod 644 greendome-support/includes/*.php
mkdir greendome-support/logs
chmod 777 greendome-support/logs/
```

### 5. WhatsApp Cloud API Setup (Optional but Recommended)

For automatic message logging via webhook:

1. Create a WhatsApp Business API account at [Facebook Developers](https://developers.facebook.com/)
2. Set up webhook URL: `https://yourdomain.com/webhook.php`
3. Set verify token in `webhook.php`: `greendome_webhook_token_2024`
4. Subscribe to messages webhook events

---

## 📂 File Structure

```
greendome-support/
│
├── database.sql              # Database schema
├── support.php               # Customer support form
├── staff_start.php           # Staff-initiated contact form
├── admin.php                 # Admin dashboard
├── webhook.php               # WhatsApp webhook handler
│
├── includes/
│   └── config.php            # Configuration & helper functions
│
├── css/
│   └── style.css             # Stylesheet
│
├── logs/
│   └── webhook.log           # Webhook activity logs (auto-created)
│
└── README.md                 # This file
```

---

## 🎯 Usage Guide

### For Customers

1. Visit `support.php`
2. Select service (Education, IT Services, CLACS-Farm, Automobile)
3. Fill in name, WhatsApp number, email (optional), and message
4. Click "Start Support on WhatsApp"
5. System generates Ticket ID (e.g., GETS-123456)
6. Automatically redirected to WhatsApp with pre-filled message containing Ticket ID
7. Continue conversation on WhatsApp

### For Staff

**Option 1: Initiate Contact**
1. Visit `staff_start.php`
2. Enter customer details and message
3. System generates Ticket ID
4. Redirected to WhatsApp to contact customer
5. Customer receives message with Ticket ID

**Option 2: Manage Tickets**
1. Visit `admin.php`
2. View all tickets with filtering options
3. Click "View" to see full conversation
4. Update ticket status
5. Add follow-up messages
6. Get WhatsApp link to send messages

---

## 🔄 Workflow

### Customer-Initiated Flow
```
Customer fills form → Ticket created → Redirected to WhatsApp → 
Customer sends message → Webhook logs message → Status: "Enquiry" →
Staff responds → Status: "Responded"
```

### Staff-Initiated Flow
```
Staff fills form → Ticket created → Redirected to WhatsApp →
Staff sends message → Customer responds → Webhook logs message →
Conversation tracked under Ticket ID
```

---

## 🎫 Ticket Statuses

| Status | Description |
|--------|-------------|
| **New** | Ticket just created |
| **Enquiry** | Customer has sent a message |
| **Follow-up** | Staff needs to follow up |
| **Responded** | Staff has responded to customer |
| **Closed** | Ticket resolved and closed |

Status changes are automatically logged in `status_history` table.

---

## 🔐 Security Notes

### Production Checklist

1. **Change default admin password:**
   ```php
   // Generate new password hash
   password_hash('your_secure_password', PASSWORD_DEFAULT);
   // Update in database
   ```

2. **Implement proper authentication:**
   - Add login page
   - Use secure session management
   - Implement role-based access control

3. **Secure webhook:**
   - Use HTTPS only
   - Validate webhook signatures
   - Rate limit webhook requests

4. **Database security:**
   - Use strong database credentials
   - Limit database user permissions
   - Enable MySQL logging

5. **Server security:**
   - Keep PHP updated
   - Disable PHP error display in production
   - Use secure file permissions

6. **Input validation:**
   - Already implemented via PDO prepared statements
   - Additional validation can be added as needed

---

## 📊 Database Schema

### Tables

**tickets**
- Stores all support tickets
- Unique ticket codes (GETS-XXXXXX)
- Customer information
- Service type
- Current status

**messages**
- All conversation messages
- Linked to tickets via foreign key
- Sender identification (Customer/Staff)
- Timestamps

**status_history**
- Complete audit trail of status changes
- Who changed status and when
- Old and new status values

**admin_users**
- Staff/admin authentication
- Password hashing

---

## 🔧 Customization

### Adding New Services

Edit `support.php`, `staff_start.php`, and `admin.php`:

```php
<option value="New Service">🔧 New Service</option>
```

And update database enum:

```sql
ALTER TABLE tickets MODIFY COLUMN service 
ENUM('Education', 'IT Services', 'CLACS-Farm', 'Automobile', 'New Service');
```

### Changing Ticket Prefix

Edit `includes/config.php`:

```php
define('TICKET_PREFIX', 'YOUR-PREFIX-');
```

### Adding Status Types

Update enum in database:

```sql
ALTER TABLE tickets MODIFY COLUMN status 
ENUM('New', 'Enquiry', 'Follow-up', 'Responded', 'Closed', 'YourNewStatus');
```

---

## 🐛 Troubleshooting

### Messages Not Logging from WhatsApp

1. Check webhook logs: `logs/webhook.log`
2. Verify webhook URL is accessible
3. Ensure Ticket ID format matches (GETS-XXXXXX)
4. Check database connection

### Tickets Not Creating

1. Check database credentials in `config.php`
2. Verify database tables exist
3. Check PHP error logs
4. Ensure proper file permissions

### WhatsApp Redirect Not Working

1. Verify `COMPANY_WHATSAPP` is set correctly
2. Check phone number format (must be international)
3. Test WhatsApp link manually

---

## 📱 Mobile Optimization

- Responsive design tested on iOS and Android
- Touch-friendly buttons (minimum 44px height)
- Readable font sizes (minimum 16px)
- Fast loading (no heavy frameworks)
- Works on 3G/4G connections

---

## 🔄 Backup & Maintenance

### Regular Backups

```bash
# Database backup
mysqldump -u root -p greendome_support > backup_$(date +%Y%m%d).sql

# File backup
tar -czf backup_files_$(date +%Y%m%d).tar.gz greendome-support/
```

### Log Rotation

```bash
# Add to crontab for weekly log rotation
0 0 * * 0 find /path/to/greendome-support/logs -name "*.log" -mtime +30 -delete
```

---

## 📈 Future Enhancements

Potential features to add:

- [ ] Email notifications
- [ ] SMS integration
- [ ] File attachment support
- [ ] Ticket assignment to specific staff
- [ ] Customer satisfaction ratings
- [ ] Analytics dashboard
- [ ] Export reports (PDF/CSV)
- [ ] Multi-language support
- [ ] API for third-party integrations

---

## 📞 Support

For issues or questions about this system:
- Check logs in `/logs/` directory
- Review database for data integrity
- Test webhook manually
- Contact system developer

---

## 📄 License

Proprietary - Greendome Engineering Technology & Science  
All rights reserved.

---

## 🙏 Credits

Developed for Greendome Engineering Technology & Science  
Engineering Technology, Science & Innovation — Empowering Growth Through Education, Technology, Agriculture & Automotive Excellence

---

**Last Updated:** February 2026  
**Version:** 1.0.0
