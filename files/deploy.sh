#!/bin/bash

#############################################################################
# Greendome Engineering Ticketing System - Deployment Script
# For Ubuntu LAMP Stack
# This script will deploy the ticketing system on Ubuntu with Apache, MySQL, PHP
#############################################################################

set -e  # Exit on error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Greendome Ticketing System Deployment${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Please run as root (use sudo)${NC}"
    exit 1
fi

# Get configuration from user
echo -e "${YELLOW}Enter your domain name (e.g., greendome.com):${NC}"
read DOMAIN_NAME

echo -e "${YELLOW}Enter MySQL root password:${NC}"
read -s MYSQL_ROOT_PASS

echo -e "${YELLOW}Enter new database password for greendome_user:${NC}"
read -s DB_PASSWORD

echo -e "${YELLOW}Enter your email for Let's Encrypt SSL (optional, press enter to skip):${NC}"
read SSL_EMAIL

# Update system
echo -e "${GREEN}[1/10] Updating system packages...${NC}"
apt update && apt upgrade -y

# Install required packages
echo -e "${GREEN}[2/10] Installing required packages...${NC}"
apt install -y apache2 mysql-server php libapache2-mod-php php-mysql php-curl php-json php-mbstring php-xml php-zip unzip curl

# Enable Apache modules
echo -e "${GREEN}[3/10] Enabling Apache modules...${NC}"
a2enmod rewrite
a2enmod headers
a2enmod ssl
a2enmod expires

# Create directory structure
echo -e "${GREEN}[4/10] Creating directory structure...${NC}"
mkdir -p /var/www/greendome-ticketing
mkdir -p /var/www/greendome-ticketing/logs
chmod 755 /var/www/greendome-ticketing
chmod 777 /var/www/greendome-ticketing/logs

# Copy application files
echo -e "${GREEN}[5/10] Deploying application files...${NC}"
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
cp -r $SCRIPT_DIR/../* /var/www/greendome-ticketing/

# Set permissions
echo -e "${GREEN}[6/10] Setting file permissions...${NC}"
chown -R www-data:www-data /var/www/greendome-ticketing
find /var/www/greendome-ticketing -type d -exec chmod 755 {} \;
find /var/www/greendome-ticketing -type f -exec chmod 644 {} \;
chmod 777 /var/www/greendome-ticketing/logs

# Configure database
echo -e "${GREEN}[7/10] Configuring MySQL database...${NC}"

# Create database and user
mysql -u root -p"$MYSQL_ROOT_PASS" <<EOF
CREATE DATABASE IF NOT EXISTS greendome_tickets CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS 'greendome_user'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON greendome_tickets.* TO 'greendome_user'@'localhost';
FLUSH PRIVILEGES;
EOF

# Import schema
mysql -u root -p"$MYSQL_ROOT_PASS" greendome_tickets < /var/www/greendome-ticketing/database/schema.sql

# Update configuration file
echo -e "${GREEN}[8/10] Updating configuration...${NC}"
CONFIG_FILE="/var/www/greendome-ticketing/config/config.php"

# Generate random encryption key
ENCRYPTION_KEY=$(openssl rand -base64 32 | tr -d "=+/" | cut -c1-32)

# Update database credentials
sed -i "s/your_secure_password_here/$DB_PASSWORD/g" $CONFIG_FILE
sed -i "s/change_this_to_random_32_char_key/$ENCRYPTION_KEY/g" $CONFIG_FILE

# Configure Apache virtual host
echo -e "${GREEN}[9/10] Configuring Apache virtual host...${NC}"
cp $SCRIPT_DIR/greendome.conf /etc/apache2/sites-available/
sed -i "s/greendome.com/$DOMAIN_NAME/g" /etc/apache2/sites-available/greendome.conf

# Enable site and disable default
a2ensite greendome.conf
a2dissite 000-default.conf

# Test Apache configuration
apache2ctl configtest

# Restart Apache
systemctl restart apache2

# Setup SSL with Let's Encrypt (if email provided)
if [ ! -z "$SSL_EMAIL" ]; then
    echo -e "${GREEN}[10/10] Setting up SSL with Let's Encrypt...${NC}"
    
    # Install certbot
    apt install -y certbot python3-certbot-apache
    
    # Get certificate
    certbot --apache -d $DOMAIN_NAME -d www.$DOMAIN_NAME --email $SSL_EMAIL --agree-tos --non-interactive --redirect
    
    echo -e "${GREEN}SSL certificate installed successfully!${NC}"
else
    echo -e "${YELLOW}[10/10] Skipping SSL setup (no email provided)${NC}"
fi

# Create log files
touch /var/www/greendome-ticketing/logs/php_errors.log
touch /var/www/greendome-ticketing/logs/webhook.log
chown www-data:www-data /var/www/greendome-ticketing/logs/*.log
chmod 666 /var/www/greendome-ticketing/logs/*.log

# Final message
echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Deployment Complete!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Important Information:${NC}"
echo ""
echo -e "Application URL: http://$DOMAIN_NAME"
echo -e "Admin Login: http://$DOMAIN_NAME/admin/login.php"
echo ""
echo -e "Default Admin Credentials:"
echo -e "  Username: ${GREEN}admin${NC}"
echo -e "  Password: ${RED}admin123${NC} ${YELLOW}(CHANGE THIS IMMEDIATELY!)${NC}"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "1. Update /var/www/greendome-ticketing/config/config.php with your WhatsApp API credentials"
echo "2. Change the default admin password"
echo "3. Configure your WhatsApp webhook URL: http://$DOMAIN_NAME/webhook.php"
echo "4. Update the WhatsApp business number in ticket-success.php"
echo "5. Test ticket creation and WhatsApp integration"
echo ""
echo -e "${YELLOW}Database Information:${NC}"
echo "  Database: greendome_tickets"
echo "  Username: greendome_user"
echo "  Password: $DB_PASSWORD"
echo ""
echo -e "${GREEN}System is ready to use!${NC}"
