# Desok Luxury Apartment Website

A fully responsive, luxury apartment website with booking system, resident portal, and comprehensive amenities showcase.

## Features

### Frontend
- **Responsive Design**: Mobile-first design that works on all devices
- **Modern UI**: Elegant gold, navy blue, and white color scheme
- **Interactive Elements**: 
  - Smooth animations and transitions
  - Lightbox gallery
  - Mobile-friendly navigation
  - Form validation

### Pages
1. **Homepage** - Hero section with quick booking form
2. **Apartments** - Detailed apartment listings (1-bedroom, 2-bedroom, luxury suite)
3. **Amenities** - World-class facilities showcase
4. **Gallery** - Photo gallery with lightbox effect
5. **Book a Stay** - Comprehensive booking form
6. **Contact** - Contact form, WhatsApp integration, Google Maps
7. **Resident Portal** - Login and dashboard for residents

### Backend
- **PHP/MySQL**: Secure form handling and data storage
- **Database Tables**:
  - bookings - Store all booking inquiries
  - contact_submissions - Store contact form messages
  - users - Resident portal authentication
  - newsletter_subscribers - Newsletter email list

## Installation Instructions

### Prerequisites
- Web server (Apache/Nginx)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

### Step 1: Upload Files
Upload all files to your web server's public directory (e.g., `public_html`, `www`, `htdocs`)

### Step 2: Configure Database
1. Open `backend/config.php`
2. Update database credentials:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'your_username');
   define('DB_PASS', 'your_password');
   define('DB_NAME', 'desok_luxury');
   ```

### Step 3: Create Database
1. Navigate to `http://yoursite.com/backend/setup-database.php` in your browser
2. This will create the database and all required tables
3. **IMPORTANT**: After setup, delete or restrict access to `setup-database.php`

### Step 4: Add Your Logo
Replace `assets/img/logo.png` with your actual logo image

### Step 5: Update Contact Information
Edit the following files to update contact details:
- `includes/footer.php` - Footer contact info
- `contact.php` - Contact page details
- Update WhatsApp number in `contact.php`
- Update Google Maps embed in `contact.php` with your location

### Step 6: Test the Website
1. Homepage: `http://yoursite.com/index.php`
2. Test booking form
3. Test contact form
4. Test resident login (Demo credentials: username: `demo`, password: `demo123`)

## Default Login Credentials

**Resident Portal:**
- Username: `demo`
- Password: `demo123`

**Important**: Change or remove the demo account in production!

## File Structure

```
desok-luxury-apartment/
├── index.php                 # Homepage
├── apartments.php            # Apartments listing
├── amenities.php            # Amenities showcase
├── gallery.php              # Photo gallery
├── book.php                 # Booking page
├── contact.php              # Contact page
├── portal.php               # Resident portal
├── assets/
│   ├── css/
│   │   └── style.css        # Main stylesheet
│   ├── js/
│   │   └── script.js        # JavaScript interactions
│   └── img/
│       └── logo.png         # Logo (placeholder)
├── backend/
│   ├── config.php           # Database configuration
│   ├── setup-database.php   # Database setup script
│   ├── booking-handler.php  # Booking form processor
│   ├── contact-handler.php  # Contact form processor
│   ├── login-handler.php    # Login authentication
│   └── logout.php           # Logout handler
└── includes/
    ├── navbar.php           # Navigation bar
    └── footer.php           # Footer section
```

## Customization

### Colors
Edit CSS variables in `assets/css/style.css`:
```css
:root {
  --primary-white: #FFFFFF;
  --primary-gold: #D4AF37;
  --primary-navy: #0A1628;
  /* ... */
}
```

### Fonts
Current fonts (Google Fonts):
- Display: Cormorant Garamond
- Body: Montserrat
- Accent: Playfair Display

Update in the `<head>` section of each PHP file.

### Images
Replace placeholder Unsplash images with your own:
- Edit image URLs in each PHP file
- Upload images to `assets/img/` directory
- Update image paths

## Email Configuration

The website includes placeholder email functions. To enable email notifications:

1. Install PHPMailer: `composer require phpmailer/phpmailer`
2. Update email functions in:
   - `backend/booking-handler.php`
   - `backend/contact-handler.php`
3. Configure SMTP settings

## Security Recommendations

1. **Delete setup file**: Remove `backend/setup-database.php` after installation
2. **Update passwords**: Change demo account password or remove it
3. **Enable HTTPS**: Use SSL certificate for secure connections
4. **Input validation**: All forms include validation (already implemented)
5. **SQL injection prevention**: Prepared statements used throughout
6. **XSS protection**: Input sanitization implemented
7. **Session security**: Secure session handling in place

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Support

For issues or questions:
- Email: info@desokluxury.com
- Phone: +234 800 123 4567

## License

© 2026 Desok Luxury Apartment. All rights reserved.

## Developer Notes

### Adding New Apartment Types
1. Update dropdown options in `index.php` and `book.php`
2. Add new apartment card in `apartments.php`
3. Update booking handler if needed

### Database Backups
Regular backups recommended:
```bash
mysqldump -u username -p desok_luxury > backup.sql
```

### Performance Optimization
- Enable gzip compression
- Optimize images
- Enable browser caching
- Consider CDN for assets

---

**Built with attention to detail and luxury in mind.**
