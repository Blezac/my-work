<?php
/* ========================================
   DESOK LUXURY APARTMENT - CONTACT PAGE
   Contact form, WhatsApp integration, and map
   ======================================== */

session_start();
$page_title = "Contact Us - Desok Luxury Apartment";
$current_page = "contact";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Get in touch with Desok Luxury Apartment. We're here to assist you 24/7.">
    <title><?php echo $page_title; ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- Navigation -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Page Header -->
    <section class="hero" style="height: 50vh; min-height: 400px;">
        <div class="hero-content">
            <p class="hero-subtitle">Get in Touch</p>
            <h1 class="hero-title">Contact Us</h1>
            <p class="hero-description">We're here to help you 24 hours a day, 7 days a week</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="section">
        <div class="container">
            <div class="contact-container">
                
                <!-- Contact Information -->
                <div class="contact-info">
                    <div style="margin-bottom: 3rem;">
                        <h2 style="color: var(--primary-navy); margin-bottom: 1rem;">Let's Connect</h2>
                        <p style="color: var(--text-gray); line-height: 1.8;">
                            Have questions about our luxury apartments or need assistance with your booking? 
                            Our dedicated team is ready to assist you. Reach out to us through any of the 
                            channels below.
                        </p>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Visit Us</h4>
                            <p>123 Luxury Avenue<br>Victoria Island, Lagos<br>Nigeria</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Call Us</h4>
                            <p>
                                <a href="tel:+2348001234567">+234 800 123 4567</a><br>
                                <a href="tel:+2349012345678">+234 901 234 5678</a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email Us</h4>
                            <p>
                                <a href="mailto:info@desokluxury.com">info@desokluxury.com</a><br>
                                <a href="mailto:reservations@desokluxury.com">reservations@desokluxury.com</a>
                            </p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Office Hours</h4>
                            <p>
                                Front Desk: 24/7<br>
                                Office: Mon-Sat, 8:00 AM - 6:00 PM<br>
                                Sunday: 10:00 AM - 4:00 PM
                            </p>
                        </div>
                    </div>

                    <!-- WhatsApp Button -->
                    <div style="margin-top: 2rem;">
                        <a href="https://wa.me/2348001234567?text=Hello%20Desok%20Luxury%20Apartment,%20I%20would%20like%20to%20make%20an%20inquiry" 
                           class="whatsapp-btn" target="_blank">
                            <i class="fab fa-whatsapp" style="font-size: 1.5rem;"></i>
                            Chat with us on WhatsApp
                        </a>
                    </div>

                    <!-- Social Media -->
                    <div style="margin-top: 3rem;">
                        <h4 style="margin-bottom: 1rem; color: var(--primary-navy);">Follow Us</h4>
                        <div class="social-links">
                            <a href="#" class="social-icon" style="background: var(--primary-gold); color: white;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-icon" style="background: var(--primary-gold); color: white;">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-icon" style="background: var(--primary-gold); color: white;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-icon" style="background: var(--primary-gold); color: white;">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div>
                    <div style="background: var(--primary-white); padding: 3rem; border-radius: 20px; box-shadow: var(--shadow-strong);">
                        <h3 style="color: var(--primary-navy); margin-bottom: 1.5rem;">Send Us a Message</h3>
                        
                        <form id="contact-form" method="POST">
                            <div class="form-group">
                                <label class="form-label" for="name">Full Name *</label>
                                <input type="text" id="name" name="name" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="email">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-input">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="subject">Subject *</label>
                                <select id="subject" name="subject" class="form-select" required>
                                    <option value="">Select subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="booking">Booking Inquiry</option>
                                    <option value="facilities">Facilities & Amenities</option>
                                    <option value="pricing">Pricing Information</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="complaint">Complaint</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="message">Message *</label>
                                <textarea id="message" name="message" class="form-textarea" rows="6" required placeholder="Please share your inquiry or feedback..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                <i class="fas fa-paper-plane" style="margin-right: 0.5rem;"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="section" style="background: var(--bg-cream); padding-top: 0;">
        <div class="container">
            <div class="section-title">
                <h2>Find Us On The Map</h2>
                <p>Visit us at our prime location in Victoria Island, Lagos</p>
            </div>

            <div class="map-container" style="height: 500px;">
                <!-- Google Maps Embed -->
                <!-- Replace the coordinates with your actual location -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3964.7285276436395!2d3.4199025!3d6.4280556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8b2ae68280c1%3A0xdc9e87a367c3d9cb!2sVictoria%20Island%2C%20Lagos!5e0!3m2!1sen!2sng!4v1234567890123!5m2!1sen!2sng" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Frequently Asked Questions</h2>
            </div>

            <div style="max-width: 800px; margin: 3rem auto 0;">
                <div style="margin-bottom: 2rem;">
                    <h4 style="color: var(--primary-navy); margin-bottom: 0.8rem;">
                        <i class="fas fa-question-circle" style="color: var(--primary-gold); margin-right: 0.5rem;"></i>
                        What is your cancellation policy?
                    </h4>
                    <p style="color: var(--text-gray); padding-left: 2rem;">
                        We offer free cancellation up to 48 hours before your check-in date. Cancellations made 
                        within 48 hours of check-in will incur a one-night charge.
                    </p>
                </div>

                <div style="margin-bottom: 2rem;">
                    <h4 style="color: var(--primary-navy); margin-bottom: 0.8rem;">
                        <i class="fas fa-question-circle" style="color: var(--primary-gold); margin-right: 0.5rem;"></i>
                        What time is check-in and check-out?
                    </h4>
                    <p style="color: var(--text-gray); padding-left: 2rem;">
                        Check-in time is 2:00 PM and check-out time is 12:00 PM. Early check-in and late 
                        check-out can be arranged upon request, subject to availability.
                    </p>
                </div>

                <div style="margin-bottom: 2rem;">
                    <h4 style="color: var(--primary-navy); margin-bottom: 0.8rem;">
                        <i class="fas fa-question-circle" style="color: var(--primary-gold); margin-right: 0.5rem;"></i>
                        Do you provide airport transfer services?
                    </h4>
                    <p style="color: var(--text-gray); padding-left: 2rem;">
                        Yes, we offer airport pickup and drop-off services at an additional cost. Please 
                        inform us of your flight details when booking.
                    </p>
                </div>

                <div style="margin-bottom: 2rem;">
                    <h4 style="color: var(--primary-navy); margin-bottom: 0.8rem;">
                        <i class="fas fa-question-circle" style="color: var(--primary-gold); margin-right: 0.5rem;"></i>
                        Are pets allowed?
                    </h4>
                    <p style="color: var(--text-gray); padding-left: 2rem;">
                        Yes, we have pet-friendly apartments available. Please inform us when booking if you'll 
                        be bringing a pet. Additional cleaning fees may apply.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
