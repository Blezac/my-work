# Belzac Digital - Local LAMP Deployment Guide

## Requirements
- Ubuntu (20.04 / 22.04 / 24.04)
- Apache2
- PHP 8.1+
- No database required

---

## 1. Install LAMP (skip steps for packages already installed)

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 php libapache2-mod-php php-mbstring -y
sudo systemctl enable apache2
sudo systemctl start apache2
```

---

## 2. Deploy the Project

```bash
# Copy project to Apache web root
sudo cp -r belzac-digital /var/www/html/

# Set correct ownership and permissions
sudo chown -R www-data:www-data /var/www/html/belzac-digital
sudo chmod -R 755 /var/www/html/belzac-digital
```

---

## 3. Update the Config File

Open `/var/www/html/belzac-digital/includes/config.php` and verify:

```php
define('SITE_URL', 'http://localhost/belzac-digital');
define('CONTACT_EMAIL', 'hello@belzacdigital.com'); // change to your real email
```

---

## 4. Enable Apache mod_rewrite (optional but recommended)

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## 5. Access the Site

Open your browser and visit:

```
http://localhost/belzac-digital/
```

---

## 6. Configure PHP mail() for Local Testing

For local email testing, install `sendmail` or use a mail catcher:

### Option A: Install sendmail
```bash
sudo apt install sendmail -y
sudo sendmailconfig
```

### Option B: Use Mailhog (recommended for development)
```bash
# Install Go if not present
sudo apt install golang -y

# Install MailHog
go install github.com/mailhog/MailHog@latest

# Run MailHog (access at http://localhost:8025)
~/go/bin/MailHog
```

Then configure PHP to use MailHog by editing `/etc/php/8.x/apache2/php.ini`:
```ini
sendmail_path = /usr/local/bin/mhsendmail
```

---

## File Structure

```
belzac-digital/
├── index.php          # Home page
├── about.php          # About page
├── services.php       # Services page
├── contact.php        # Contact page with form
├── includes/
│   ├── config.php     # Site configuration + helpers
│   ├── header.php     # Reusable header + nav
│   └── footer.php     # Reusable footer
└── assets/
    ├── css/
    │   └── style.css  # Main stylesheet
    └── js/
        └── main.js    # Client-side interactions
```

---

## VS Code Setup

1. Install the **PHP Intelephense** extension for PHP support.
2. Install **Live Server** or use Apache directly.
3. Open the `/var/www/html/belzac-digital` folder as your workspace root.

---

## Security Notes

- CSRF tokens are generated per session and validated on POST.
- Honeypot field silently discards bot submissions.
- All user input is sanitised with `htmlspecialchars`, `strip_tags`, and `filter_var`.
- Email headers are protected against injection via regex and `filter_var`.
- Update `CONTACT_EMAIL` in `config.php` before going live.

---

## Customisation

| Item             | Location                                  |
|------------------|-------------------------------------------|
| Logo             | `includes/header.php` - `.bd-logo-text`   |
| Brand colours    | `assets/css/style.css` - `:root` variables|
| Contact email    | `includes/config.php`                     |
| Social links     | `includes/config.php`                     |
| Site URL         | `includes/config.php`                     |
