#!/bin/bash

#############################################################################
# Post-Installation Configuration Helper
# Run this after deploying the application
#############################################################################

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Greendome - Post-Installation Setup${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

CONFIG_FILE="/var/www/greendome-ticketing/config/config.php"
SUCCESS_FILE="/var/www/greendome-ticketing/public/ticket-success.php"

# Check if files exist
if [ ! -f "$CONFIG_FILE" ]; then
    echo -e "${RED}Error: Configuration file not found!${NC}"
    echo "Expected: $CONFIG_FILE"
    exit 1
fi

echo -e "${YELLOW}WhatsApp Business API Configuration${NC}"
echo "================================================"
echo ""

echo "Enter your WhatsApp Phone Number ID:"
read PHONE_NUMBER_ID

echo "Enter your WhatsApp Access Token:"
read ACCESS_TOKEN

echo "Enter your Webhook Verify Token (create a random string):"
read VERIFY_TOKEN

echo "Enter your WhatsApp Business Number (e.g., 2348012345678):"
read BUSINESS_NUMBER

# Update config.php
echo ""
echo -e "${GREEN}Updating configuration file...${NC}"

sed -i "s/define('WHATSAPP_PHONE_NUMBER_ID', 'your_phone_number_id');/define('WHATSAPP_PHONE_NUMBER_ID', '$PHONE_NUMBER_ID');/g" $CONFIG_FILE
sed -i "s/define('WHATSAPP_ACCESS_TOKEN', 'your_access_token');/define('WHATSAPP_ACCESS_TOKEN', '$ACCESS_TOKEN');/g" $CONFIG_FILE
sed -i "s/define('WHATSAPP_VERIFY_TOKEN', 'your_webhook_verify_token');/define('WHATSAPP_VERIFY_TOKEN', '$VERIFY_TOKEN');/g" $CONFIG_FILE

# Update ticket-success.php
sed -i "s/\$whatsappNumber = '2348012345678';/\$whatsappNumber = '$BUSINESS_NUMBER';/g" $SUCCESS_FILE

echo -e "${GREEN}Configuration updated successfully!${NC}"
echo ""
echo -e "${YELLOW}Next Steps:${NC}"
echo "1. Configure WhatsApp webhook in Meta for Developers:"
echo "   - Callback URL: https://yourdomain.com/webhook.php"
echo "   - Verify Token: $VERIFY_TOKEN"
echo ""
echo "2. Test the webhook by sending a message to your WhatsApp Business number"
echo ""
echo "3. Change the default admin password:"
echo "   - Login at: https://yourdomain.com/admin/login.php"
echo "   - Username: admin"
echo "   - Password: admin123 (CHANGE THIS!)"
echo ""
echo -e "${GREEN}Setup complete!${NC}"
