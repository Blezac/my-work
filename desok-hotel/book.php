<?php
/* ========================================
   DESOK LUXURY APARTMENT - BOOKING PAGE
   Complete booking form for reservations
   ======================================== */

session_start();
$page_title = "Book Your Stay - Desok Luxury Apartment";
$current_page = "book";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Book your luxury apartment stay at Desok. Easy online reservation with instant confirmation.">
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
            <p class="hero-subtitle">Reserve</p>
            <h1 class="hero-title">Book Your Stay</h1>
            <p class="hero-description">Secure your luxury apartment in just a few simple steps</p>
        </div>
    </section>

    <!-- Booking Form Section -->
    <section class="section" style="background: var(--bg-cream);">
        <div class="container">
            <div class="booking-form-container">
                <div style="text-align: center; margin-bottom: 3rem;">
                    <h2 style="color: var(--primary-navy); margin-bottom: 1rem;">Reservation Details</h2>
                    <p style="color: var(--text-gray);">Please fill in the information below to complete your booking</p>
                </div>

                <form id="booking-form" method="POST">
                    
                    <!-- Personal Information -->
                    <div style="margin-bottom: 3rem;">
                        <h3 style="color: var(--primary-gold); margin-bottom: 1.5rem; font-size: 1.3rem;">
                            <i class="fas fa-user" style="margin-right: 0.5rem;"></i> Personal Information
                        </h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="first_name">First Name *</label>
                                <input type="text" id="first_name" name="first_name" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="last_name">Last Name *</label>
                                <input type="text" id="last_name" name="last_name" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="country">Country *</label>
                                <select id="country" name="country" class="form-select" required>
                                    <option value="">Select country</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="nationality">Nationality</label>
                                <input type="text" id="nationality" name="nationality" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Booking Details -->
                    <div style="margin-bottom: 3rem;">
                        <h3 style="color: var(--primary-gold); margin-bottom: 1.5rem; font-size: 1.3rem;">
                            <i class="fas fa-calendar-alt" style="margin-right: 0.5rem;"></i> Booking Details
                        </h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="apartment_type">Apartment Type *</label>
                                <select id="apartment_type" name="apartment_type" class="form-select" required>
                                    <option value="">Select apartment type</option>
                                    <option value="1-bedroom">1-Bedroom Apartment</option>
                                    <option value="2-bedroom">2-Bedroom Apartment</option>
                                    <option value="luxury-suite">Luxury Suite</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="guests">Number of Guests *</label>
                                <select id="guests" name="guests" class="form-select" required>
                                    <option value="">Select number</option>
                                    <option value="1">1 Guest</option>
                                    <option value="2">2 Guests</option>
                                    <option value="3">3 Guests</option>
                                    <option value="4">4 Guests</option>
                                    <option value="5">5 Guests</option>
                                    <option value="6">6 Guests</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="check_in">Check-In Date *</label>
                                <input type="date" id="check_in" name="check_in" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="check_out">Check-Out Date *</label>
                                <input type="date" id="check_out" name="check_out" class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="arrival_time">Expected Arrival Time</label>
                                <input type="time" id="arrival_time" name="arrival_time" class="form-input">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="purpose">Purpose of Stay</label>
                                <select id="purpose" name="purpose" class="form-select">
                                    <option value="">Select purpose</option>
                                    <option value="business">Business</option>
                                    <option value="leisure">Leisure</option>
                                    <option value="vacation">Vacation</option>
                                    <option value="relocation">Relocation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Services -->
                    <div style="margin-bottom: 3rem;">
                        <h3 style="color: var(--primary-gold); margin-bottom: 1.5rem; font-size: 1.3rem;">
                            <i class="fas fa-plus-circle" style="margin-right: 0.5rem;"></i> Additional Services
                        </h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                            <label style="display: flex; align-items: center; gap: 0.8rem; cursor: pointer;">
                                <input type="checkbox" name="services[]" value="airport_pickup" style="width: 20px; height: 20px;">
                                <span>Airport Pickup</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.8rem; cursor: pointer;">
                                <input type="checkbox" name="services[]" value="breakfast" style="width: 20px; height: 20px;">
                                <span>Daily Breakfast</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.8rem; cursor: pointer;">
                                <input type="checkbox" name="services[]" value="extra_cleaning" style="width: 20px; height: 20px;">
                                <span>Extra Housekeeping</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.8rem; cursor: pointer;">
                                <input type="checkbox" name="services[]" value="parking" style="width: 20px; height: 20px;">
                                <span>Parking Space</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.8rem; cursor: pointer;">
                                <input type="checkbox" name="services[]" value="late_checkout" style="width: 20px; height: 20px;">
                                <span>Late Checkout</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.8rem; cursor: pointer;">
                                <input type="checkbox" name="services[]" value="pet_friendly" style="width: 20px; height: 20px;">
                                <span>Pet-Friendly Unit</span>
                            </label>
                        </div>
                    </div>

                    <!-- Special Requests -->
                    <div style="margin-bottom: 3rem;">
                        <h3 style="color: var(--primary-gold); margin-bottom: 1.5rem; font-size: 1.3rem;">
                            <i class="fas fa-comment-dots" style="margin-right: 0.5rem;"></i> Special Requests
                        </h3>
                        <div class="form-group">
                            <label class="form-label" for="special_requests">Any special requests or requirements?</label>
                            <textarea id="special_requests" name="special_requests" class="form-textarea" rows="5" placeholder="Please let us know if you have any special requests, dietary requirements, accessibility needs, or preferences..."></textarea>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div style="margin-bottom: 2rem;">
                        <label style="display: flex; align-items: flex-start; gap: 0.8rem; cursor: pointer;">
                            <input type="checkbox" name="terms" required style="width: 20px; height: 20px; margin-top: 0.2rem;">
                            <span style="color: var(--text-gray);">
                                I agree to the <a href="#" style="color: var(--primary-gold); text-decoration: underline;">Terms and Conditions</a> 
                                and <a href="#" style="color: var(--primary-gold); text-decoration: underline;">Cancellation Policy</a> *
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" style="min-width: 250px;">
                            <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i> Confirm Booking
                        </button>
                        <p style="margin-top: 1rem; color: var(--text-light); font-size: 0.9rem;">
                            You will receive a confirmation email within 24 hours
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </section>

    <!-- Why Book With Us -->
    <section class="section">
        <div class="container">
            <div class="section-title">
                <h2>Why Book With Us?</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div style="text-align: center;">
                    <i class="fas fa-shield-alt" style="font-size: 3rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                    <h4 style="margin-bottom: 0.5rem;">Best Rate Guarantee</h4>
                    <p style="color: var(--text-gray);">Always get the best price when you book directly</p>
                </div>
                
                <div style="text-align: center;">
                    <i class="fas fa-calendar-check" style="font-size: 3rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                    <h4 style="margin-bottom: 0.5rem;">Flexible Booking</h4>
                    <p style="color: var(--text-gray);">Free cancellation up to 48 hours before check-in</p>
                </div>
                
                <div style="text-align: center;">
                    <i class="fas fa-headset" style="font-size: 3rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                    <h4 style="margin-bottom: 0.5rem;">24/7 Support</h4>
                    <p style="color: var(--text-gray);">Round-the-clock customer service for your peace of mind</p>
                </div>
                
                <div style="text-align: center;">
                    <i class="fas fa-star" style="font-size: 3rem; color: var(--primary-gold); margin-bottom: 1rem;"></i>
                    <h4 style="margin-bottom: 0.5rem;">Premium Quality</h4>
                    <p style="color: var(--text-gray);">Consistently rated 5 stars by our guests</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
    
    <script>
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('check_in').setAttribute('min', today);
        document.getElementById('check_out').setAttribute('min', today);
    </script>
</body>
</html>
