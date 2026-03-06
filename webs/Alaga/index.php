<?php
// Start session
session_start();

// Set page title (you can change this)
$page_title = "WendyGee Alaga - Traditional Wedding Services";

// Get current year for footer
$current_year = date('Y');

// Check for success message from form submission
$success_message = '';
$error_message = '';

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <div class="logo-circle">
                    
                </div>
                <div class="logo-text">
                    <h1></h1>
                </div>
            </div>
            <nav class="nav">
                <a onclick="showSection('home')">HOME</a>
                <a onclick="showSection('services')">SERVICES</a>
                <a onclick="showSection('training')">TRAINING</a>
                <a onclick="showSection('booking')">BOOKING</a>
                <a onclick="showSection('consultation')">CONSULTATION</a>
                <a onclick="showSection('enquiry')">ENQUIRY</a>
                <a onclick="showSection('contact')">CONTACT</a>
            </nav>
        </div>
    </header>

    <section class="hero">
        <h2>TRADITIONAL WEDDING EXCELLENCE</h2>
        <p>Professional Alaga Services for Your Special Day</p>
        <div class="services-list">
            <ul>
                <li>◆ ALAGA IDURO AND IJOKO</li>
                <li>◆ ERU IYAWO WRAPPING AND SET UP</li>
                <li>◆ SELLING OF PROPOSAL AND ACCEPTANCE</li>
                <li>◆ ALAGA TRAINING LETTER</li>
            </ul>
        </div>
    </section>

    <div class="container">
        <!-- HOME SECTION -->
        <div id="home" class="section active">
            <h2 class="section-title">Welcome to WendyGee Alaga</h2>
            <p class="intro-text">
                We are your trusted partner in making your traditional wedding ceremony unforgettable. With years of experience in Yoruba traditional wedding ceremonies, we provide professional Alaga services that combine cultural authenticity with modern elegance.
            </p>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Expert Compere</h3>
                    <p>Our experienced Alaga Iduro and Ijoko ensure smooth proceedings and joyful celebrations at your engagement ceremony.</p>
                </div>
                <div class="service-card">
                    <h3>Eru Iyawo Services</h3>
                    <p>Professional wrapping and beautiful setup of engagement items that showcase cultural heritage.</p>
                </div>
                <div class="service-card">
                    <h3>Proposal Letters</h3>
                    <p>Authentic proposal and acceptance letters crafted with cultural significance and elegance.</p>
                </div>
            </div>
        </div>

        <!-- SERVICES SECTION -->
        <div id="services" class="section">
            <h2 class="section-title">Our Services</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Alaga Iduro (Standing Compere)</h3>
                    <p>The Alaga Iduro is the lead compere who guides the groom's family through the traditional engagement ceremony. Our expert Alaga ensures all protocols are followed with grace and joy, creating memorable moments for everyone.</p>
                </div>
                <div class="service-card">
                    <h3>Alaga Ijoko (Sitting Compere)</h3>
                    <p>The Alaga Ijoko represents the bride's family, welcoming guests and coordinating the bride's side of the ceremony. Our professional compere brings warmth, humor, and cultural expertise to your celebration.</p>
                </div>
                <div class="service-card">
                    <h3>Eru Iyawo Wrapping</h3>
                    <p>We provide expert wrapping and presentation of all engagement items (Eru Iyawo) including fabrics, shoes, bags, and traditional items. Every package is wrapped beautifully to impress.</p>
                </div>
                <div class="service-card">
                    <h3>Engagement Setup</h3>
                    <p>Complete setup service for your traditional engagement ceremony including arrangement of items, decorative displays, and ensuring everything is picture-perfect.</p>
                </div>
                <div class="service-card">
                    <h3>Proposal Letters</h3>
                    <p>We provide authentic Yoruba traditional proposal and acceptance letters, carefully crafted to reflect cultural values and formalities required for your engagement.</p>
                </div>
                <div class="service-card">
                    <h3>Complete Packages</h3>
                    <p>Get all our services bundled together for a seamless experience. From planning to execution, we handle everything for your traditional wedding ceremony.</p>
                </div>
            </div>
        </div>

        <!-- TRAINING SECTION -->
        <div id="training" class="section">
            <h2 class="section-title">Alaga Training Programs</h2>
            <p class="intro-text">
                Join our professional training program to become a certified Alaga. Learn the art of traditional wedding compering from experienced practitioners.
            </p>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Beginners Class</h3>
                    <p>Perfect for those new to Alaga services. Learn the fundamentals of traditional wedding ceremonies, protocol, etiquette, and basic compering skills.</p>
                    <p style="margin-top: 15px;"><strong>Duration:</strong> 4 weeks</p>
                    <p><strong>Topics Covered:</strong></p>
                    <ul class="training-list">
                        <li>Introduction to Yoruba wedding traditions</li>
                        <li>Basic protocol and etiquette</li>
                        <li>Compering fundamentals</li>
                        <li>Item presentation skills</li>
                    </ul>
                    <span class="price-tag">₦150,000</span>
                    <button class="pay-now-btn" onclick="showPaymentModal('beginner', 150000)">PAY NOW</button>
                </div>
                <div class="service-card">
                    <h3>Masterclass</h3>
                    <p>Advanced training for experienced Alaga or those who want comprehensive knowledge. Master the art of compering and become a sought-after professional.</p>
                    <p style="margin-top: 15px;"><strong>Duration:</strong> 8 weeks</p>
                    <p><strong>Topics Covered:</strong></p>
                    <ul class="training-list">
                        <li>Advanced compering techniques</li>
                        <li>Crisis management</li>
                        <li>Professional presentation</li>
                        <li>Business development</li>
                        <li>Eru Iyawo expertise</li>
                    </ul>
                    <span class="price-tag">₦300,000</span>
                    <button class="pay-now-btn" onclick="showPaymentModal('masterclass', 300000)">PAY NOW</button>
                </div>
            </div>

            <!-- Payment Modal (Inside Training Section) -->
            <div id="paymentModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal" onclick="closePaymentModal()">&times;</span>
                    <h2 class="modal-title">Training Payment - Bank Transfer</h2>
                    
                    <div class="payment-details">
                        <h3 id="trainingType"></h3>
                        <p class="amount">Amount: ₦<span id="trainingAmount"></span></p>
                        
                        <div class="bank-details">
                            <h4>Bank Transfer Details</h4>
                            <div class="bank-info">
                                <p><strong>Bank Name:</strong> Guaranty Trust Bank</p>
                                <p><strong>Account Name:</strong>Adeoti Temiloluwa Oluwaseun</p>
                                <p><strong>Account Number:</strong> 0226514522/p>
                            </div>
                            <div class="payment-instructions">
                                <p><strong>Payment Instructions:</strong></p>
                                <ol>
                                    <li>Transfer the amount to the account above</li>
                                    <li>Take a screenshot or download the receipt</li>
                                    <li>Fill the form below and upload your receipt</li>
                                    <li>We will verify and contact you within 24-48 hours</li>
                                </ol>
                            </div>
                        </div>

                        <form id="paymentForm" method="POST" action="submit_payment.php" enctype="multipart/form-data">
                            <input type="hidden" name="training_type" id="hiddenTrainingType">
                            <input type="hidden" name="amount" id="hiddenAmount">
                            
                            <div class="form-group">
                                <label>Full Name: *</label>
                                <input type="text" name="full_name" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Email: *</label>
                                <input type="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Phone: *</label>
                                <input type="tel" name="phone" required pattern="[0-9]{11}" placeholder="08012345678">
                            </div>
                            
                            <div class="form-group">
                                <label>Upload Payment Receipt: *</label>
                                <input type="file" name="payment_receipt" accept="image/*,.pdf" required>
                                <small>Upload proof of payment (Image or PDF, Max 5MB)</small>
                            </div>
                            
                            <div class="form-group">
                                <label>Transaction Reference: (Optional)</label>
                                <input type="text" name="transaction_ref" placeholder="Enter transaction reference if available">
                            </div>
                            
                            <button type="submit" class="submit-btn">SUBMIT PAYMENT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- BOOKING SECTION -->
        <div id="booking" class="section">
            <div class="booking-form">
                <h2 class="form-main-title">BOOKING FORM</h2>
                
                <?php if ($success_message): ?>
                    <div class="alert success"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                
                <?php if ($error_message): ?>
                    <div class="alert error"><?php echo htmlspecialchars($error_message); ?></div>
                <?php endif; ?>
                
                <div id="alertBox" class="alert"></div>

                <form id="bookingForm" method="POST" action="submit_booking.php">
                    <div class="form-header">
                        <div class="form-group">
                            <label>Date:</label>
                            <input type="date" name="date" required>
                        </div>
                        <div class="form-group">
                            <label>Time:</label>
                            <input type="time" name="time" required>
                        </div>
                        <div class="form-group">
                            <label>Phone No:</label>
                            <input type="tel" name="phone" required pattern="[0-9]{11}" placeholder="08012345678">
                        </div>
                        <div class="form-group">
                            <label>Venue:</label>
                            <input type="text" name="venue" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Service Type:</label>
                        <select name="service_type" required>
                            <option value="">Select Service</option>
                            <option value="alaga_iduro">Alaga Iduro</option>
                            <option value="alaga_ijoko">Alaga Ijoko</option>
                            <option value="both_alaga">Both Alaga (Iduro & Ijoko)</option>
                            <option value="eru_wrapping">Eru Iyawo Wrapping</option>
                            <option value="proposal_letter">Proposal & Acceptance Letter</option>
                            <option value="complete_package">Complete Package</option>
                            <option value="training_beginner">Training - Beginners (₦150,000)</option>
                            <option value="training_master">Training - Masterclass (₦300,000)</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <!-- GROOM'S SECTION -->
                        <div class="form-section">
                            <h3 class="section-header groom-header">GROOM'S INFORMATION</h3>
                            <div class="form-group">
                                <label>Groom's First Name:</label>
                                <input type="text" name="groom_firstname" required>
                            </div>
                            <div class="form-group">
                                <label>Groom's Last Name:</label>
                                <input type="text" name="groom_lastname" required>
                            </div>
                            <div class="form-group">
                                <label>Oruko Oriki:</label>
                                <input type="text" name="groom_oriki">
                            </div>
                            <div class="form-group">
                                <label>Religion:</label>
                                <input type="text" name="groom_religion">
                            </div>
                            <div class="form-group">
                                <label>Denomination:</label>
                                <input type="text" name="groom_denomination">
                            </div>
                            <div class="form-group">
                                <label>Favourite Musician:</label>
                                <input type="text" name="groom_musician">
                            </div>

                            <div class="parent-section">
                                <h4>FATHER'S FULL NAME</h4>
                                <input type="text" name="groom_father_name" placeholder="Full Name">
                                <div class="form-group">
                                    <label>Deceased/Alive:</label>
                                    <select name="groom_father_status">
                                        <option value="alive">Alive</option>
                                        <option value="deceased">Deceased</option>
                                    </select>
                                </div>
                                <input type="text" name="groom_father_religion" placeholder="Religion">
                                <input type="text" name="groom_father_denomination" placeholder="Denomination">
                                <input type="text" name="groom_father_hometown" placeholder="Home Town">
                                <input type="text" name="groom_father_compound" placeholder="Compound">
                            </div>

                            <div class="parent-section">
                                <h4>MOTHER'S FULLNAME</h4>
                                <input type="text" name="groom_mother_name" placeholder="Full Name">
                                <div class="form-group">
                                    <label>Deceased/Alive:</label>
                                    <select name="groom_mother_status">
                                        <option value="alive">Alive</option>
                                        <option value="deceased">Deceased</option>
                                    </select>
                                </div>
                                <input type="text" name="groom_mother_religion" placeholder="Religion">
                                <input type="text" name="groom_mother_denomination" placeholder="Denomination">
                                <input type="text" name="groom_mother_hometown" placeholder="Home Town">
                                <input type="text" name="groom_mother_compound" placeholder="Compound">
                                <input type="text" name="groom_mother_colour" placeholder="Colour">
                            </div>
                        </div>

                        <!-- BRIDE'S SECTION -->
                        <div class="form-section">
                            <h3 class="section-header bride-header">BRIDE'S INFORMATION</h3>
                            <div class="form-group">
                                <label>Bride's First Name:</label>
                                <input type="text" name="bride_firstname" required>
                            </div>
                            <div class="form-group">
                                <label>Bride's Last Name:</label>
                                <input type="text" name="bride_lastname" required>
                            </div>
                            <div class="form-group">
                                <label>Oruko Oriki:</label>
                                <input type="text" name="bride_oriki">
                            </div>
                            <div class="form-group">
                                <label>Religion:</label>
                                <input type="text" name="bride_religion">
                            </div>
                            <div class="form-group">
                                <label>Denomination:</label>
                                <input type="text" name="bride_denomination">
                            </div>
                            <div class="form-group">
                                <label>Favourite Musician:</label>
                                <input type="text" name="bride_musician">
                            </div>

                            <div class="parent-section">
                                <h4>FATHER'S FULL NAME</h4>
                                <input type="text" name="bride_father_name" placeholder="Full Name">
                                <div class="form-group">
                                    <label>Deceased/Alive:</label>
                                    <select name="bride_father_status">
                                        <option value="alive">Alive</option>
                                        <option value="deceased">Deceased</option>
                                    </select>
                                </div>
                                <input type="text" name="bride_father_religion" placeholder="Religion">
                                <input type="text" name="bride_father_denomination" placeholder="Denomination">
                                <input type="text" name="bride_father_hometown" placeholder="Home Town">
                                <input type="text" name="bride_father_compound" placeholder="Compound">
                            </div>

                            <div class="parent-section">
                                <h4>MOTHER'S FULLNAME</h4>
                                <input type="text" name="bride_mother_name" placeholder="Full Name">
                                <div class="form-group">
                                    <label>Deceased/Alive:</label>
                                    <select name="bride_mother_status">
                                        <option value="alive">Alive</option>
                                        <option value="deceased">Deceased</option>
                                    </select>
                                </div>
                                <input type="text" name="bride_mother_religion" placeholder="Religion">
                                <input type="text" name="bride_mother_denomination" placeholder="Denomination">
                                <input type="text" name="bride_mother_hometown" placeholder="Home Town">
                                <input type="text" name="bride_mother_compound" placeholder="Compound">
                                <input type="text" name="bride_mother_colour" placeholder="Colour">
                            </div>
                        </div>
                    </div>

                    <div class="commitment">
                        50% commitment is to be made
                    </div>

                    <div class="social-media">
                        <label>Are we permitted to share your event on Social Media?</label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="social_media" value="yes" required> YES
                            </label>
                            <label>
                                <input type="radio" name="social_media" value="no"> NO
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">SUBMIT BOOKING</button>
                </form>
            </div>
        </div>

        <!-- CONTACT SECTION -->
        <div id="contact" class="section">
            <h2 class="section-title">Contact Us</h2>
            <div class="services-grid">
                <div class="service-card">
                    <h3>Phone</h3>
                    <p style="font-size: 20px; font-weight: bold;">08142953849</p>
                </div>
                <div class="service-card">
                    <h3>Email</h3>
                    <p style="font-size: 18px; font-weight: bold;">info@wendygeealaga.com</p>
                </div>
                <div class="service-card">
                    <h3>Location</h3>
                    <p>Team6 House29<br>6th Avenue, Gwarinpa<br>Abuja, Nigeria</p>
                </div>
                <div class="service-card">
                    <h3>Social Media</h3>
                    <p><strong>Facebook:</strong> wendygee Alaga</p>
                    <p><strong>Instagram:</strong> WENDYGEEALAGAINABUJA</p>
                    <p><strong>TikTok:</strong> wendygeealaga1</p>
                </div>
            </div>
        </div>

        <!-- CONSULTATION SECTION -->
        <div id="consultation" class="section">
            <h2 class="section-title">Book a Consultation</h2>
            <p class="intro-text">
                Need guidance for your traditional wedding? Book a consultation with our experienced Alaga team. We provide expert advice for prospective couples, family members, and anyone planning a traditional ceremony.
            </p>
            <div class="booking-form">
                <h2 class="form-main-title">CONSULTATION REQUEST</h2>
                
                <form id="consultationForm" method="POST" action="submit_consultation.php">
                    <div class="form-header">
                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="tel" name="phone" required pattern="[0-9]{11}">
                        </div>
                        <div class="form-group">
                            <label>Preferred Date:</label>
                            <input type="date" name="preferred_date" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Consultation Type:</label>
                        <select name="consultation_type" required>
                            <option value="">Select Type</option>
                            <option value="couple">Prospective Couple</option>
                            <option value="family">Family Member</option>
                            <option value="wedding_planning">Wedding Planning</option>
                            <option value="training_inquiry">Training Inquiry</option>
                            <option value="general">General Consultation</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Message / Questions:</label>
                        <textarea name="message" rows="6" required placeholder="Tell us what you'd like to discuss..."></textarea>
                    </div>

                    <button type="submit" class="submit-btn">REQUEST CONSULTATION</button>
                </form>
            </div>
        </div>

        <!-- ENQUIRY SECTION -->
        <div id="enquiry" class="section">
            <h2 class="section-title">Make an Enquiry</h2>
            <p class="intro-text">
                Have questions about our services? Send us an enquiry and we'll get back to you as soon as possible.
            </p>
            <div class="booking-form">
                <h2 class="form-main-title">ENQUIRY FORM</h2>
                
                <form id="enquiryForm" method="POST" action="submit_enquiry.php">
                    <div class="form-header">
                        <div class="form-group">
                            <label>Full Name:</label>
                            <input type="text" name="full_name" required>
                        </div>
                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone:</label>
                            <input type="tel" name="phone" required pattern="[0-9]{11}">
                        </div>
                        <div class="form-group">
                            <label>Subject:</label>
                            <input type="text" name="subject" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Your Enquiry:</label>
                        <textarea name="enquiry_message" rows="6" required placeholder="What would you like to know?"></textarea>
                    </div>

                    <button type="submit" class="submit-btn">SUBMIT ENQUIRY</button>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; <?php echo $current_year; ?> WendyGee Alaga. All Rights Reserved.</p>
        <p>Professional Traditional Wedding Services | <a href="tel:08142953849">08142953849</a></p>
    </footer>

    <script src="script.js"></script>
</body>
</html>