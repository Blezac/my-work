<?php
/* ========================================
   DESOK LUXURY APARTMENT - AMENITIES PAGE
   Showcase all facilities and services
   ======================================== */

session_start();
$page_title = "World-Class Amenities - Desok Luxury Apartment";
$current_page = "amenities";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Discover our world-class amenities including gym, pool, concierge service, and more.">
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
            <p class="hero-subtitle">Facilities</p>
            <h1 class="hero-title">World-Class Amenities</h1>
            <p class="hero-description">Everything you need for an exceptional stay</p>
        </div>
    </section>

    <!-- Amenities Section -->
    <section class="section">
        <div class="container">
            <div class="amenities-grid">
                
                <!-- Wi-Fi -->
                <div class="amenity-card">
                    <i class="fas fa-wifi amenity-icon"></i>
                    <h3 class="amenity-title">High-Speed Wi-Fi</h3>
                    <p class="amenity-description">
                        Stay connected with complimentary fiber-optic internet throughout the property. 
                        Enjoy seamless streaming, video conferencing, and browsing with our ultra-fast connection.
                    </p>
                </div>

                <!-- Parking -->
                <div class="amenity-card">
                    <i class="fas fa-car amenity-icon"></i>
                    <h3 class="amenity-title">Secure Parking</h3>
                    <p class="amenity-description">
                        Underground parking facility with 24/7 security surveillance and dedicated spaces 
                        for residents. Valet parking service available upon request.
                    </p>
                </div>

                <!-- Gym -->
                <div class="amenity-card">
                    <i class="fas fa-dumbbell amenity-icon"></i>
                    <h3 class="amenity-title">Fitness Center</h3>
                    <p class="amenity-description">
                        State-of-the-art fitness center equipped with the latest cardio and strength training 
                        equipment. Personal training sessions available. Open 24/7 for your convenience.
                    </p>
                </div>

                <!-- Pool -->
                <div class="amenity-card">
                    <i class="fas fa-swimming-pool amenity-icon"></i>
                    <h3 class="amenity-title">Swimming Pool</h3>
                    <p class="amenity-description">
                        Rooftop infinity pool with breathtaking city views. Includes a children's pool, 
                        poolside bar, and comfortable lounge areas for relaxation.
                    </p>
                </div>

                <!-- Concierge -->
                <div class="amenity-card">
                    <i class="fas fa-concierge-bell amenity-icon"></i>
                    <h3 class="amenity-title">Concierge Service</h3>
                    <p class="amenity-description">
                        Our dedicated concierge team is available 24/7 to assist with reservations, 
                        transportation, local recommendations, and any special requests you may have.
                    </p>
                </div>

                <!-- Housekeeping -->
                <div class="amenity-card">
                    <i class="fas fa-broom amenity-icon"></i>
                    <h3 class="amenity-title">Housekeeping</h3>
                    <p class="amenity-description">
                        Professional daily housekeeping and laundry services to ensure your apartment 
                        remains spotless. Linen change and deep cleaning available upon request.
                    </p>
                </div>

                <!-- Security -->
                <div class="amenity-card">
                    <i class="fas fa-shield-alt amenity-icon"></i>
                    <h3 class="amenity-title">24/7 Security</h3>
                    <p class="amenity-description">
                        Round-the-clock security personnel and advanced surveillance systems ensure your 
                        safety and peace of mind throughout your stay.
                    </p>
                </div>

                <!-- Business Center -->
                <div class="amenity-card">
                    <i class="fas fa-briefcase amenity-icon"></i>
                    <h3 class="amenity-title">Business Center</h3>
                    <p class="amenity-description">
                        Fully equipped business center with meeting rooms, printing services, and 
                        high-speed internet for all your professional needs.
                    </p>
                </div>

                <!-- Restaurant -->
                <div class="amenity-card">
                    <i class="fas fa-utensils amenity-icon"></i>
                    <h3 class="amenity-title">Restaurant & Café</h3>
                    <p class="amenity-description">
                        On-site restaurant serving international cuisine and a cozy café for casual dining. 
                        Room service available daily from 6 AM to 11 PM.
                    </p>
                </div>

                <!-- Spa -->
                <div class="amenity-card">
                    <i class="fas fa-spa amenity-icon"></i>
                    <h3 class="amenity-title">Wellness Spa</h3>
                    <p class="amenity-description">
                        Luxurious spa offering a range of treatments including massages, facials, and 
                        wellness therapies. Sauna and steam room facilities available.
                    </p>
                </div>

                <!-- Kids Play Area -->
                <div class="amenity-card">
                    <i class="fas fa-child amenity-icon"></i>
                    <h3 class="amenity-title">Children's Play Area</h3>
                    <p class="amenity-description">
                        Safe and supervised play area for children with age-appropriate toys and activities. 
                        Babysitting services available upon request.
                    </p>
                </div>

                <!-- Laundry -->
                <div class="amenity-card">
                    <i class="fas fa-tshirt amenity-icon"></i>
                    <h3 class="amenity-title">Laundry Service</h3>
                    <p class="amenity-description">
                        Professional laundry and dry cleaning services with same-day delivery. 
                        Self-service laundry facilities also available.
                    </p>
                </div>

                <!-- Garden -->
                <div class="amenity-card">
                    <i class="fas fa-tree amenity-icon"></i>
                    <h3 class="amenity-title">Landscaped Gardens</h3>
                    <p class="amenity-description">
                        Beautifully landscaped gardens and outdoor seating areas perfect for relaxation 
                        and social gatherings in a tranquil setting.
                    </p>
                </div>

                <!-- Smart Home -->
                <div class="amenity-card">
                    <i class="fas fa-home amenity-icon"></i>
                    <h3 class="amenity-title">Smart Home Technology</h3>
                    <p class="amenity-description">
                        All apartments feature smart home automation for lighting, climate control, and 
                        entertainment systems controlled via mobile app.
                    </p>
                </div>

                <!-- Pet Friendly -->
                <div class="amenity-card">
                    <i class="fas fa-paw amenity-icon"></i>
                    <h3 class="amenity-title">Pet-Friendly</h3>
                    <p class="amenity-description">
                        We welcome your furry friends! Pet-friendly apartments available with designated 
                        walking areas and pet grooming services.
                    </p>
                </div>

                <!-- Shuttle Service -->
                <div class="amenity-card">
                    <i class="fas fa-bus amenity-icon"></i>
                    <h3 class="amenity-title">Shuttle Service</h3>
                    <p class="amenity-description">
                        Complimentary shuttle service to major business districts and shopping centers. 
                        Airport transfer service available at additional cost.
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Additional Services -->
    <section class="section" style="background: var(--bg-cream);">
        <div class="container">
            <div class="section-title">
                <h2>Additional Services</h2>
                <p>Enhancing your luxury living experience</p>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow-soft);">
                    <h4 style="color: var(--primary-navy); margin-bottom: 1rem;">Event & Meeting Spaces</h4>
                    <p>Host your events in our elegant function rooms equipped with modern AV systems and catering services.</p>
                </div>

                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow-soft);">
                    <h4 style="color: var(--primary-navy); margin-bottom: 1rem;">Personal Shopping</h4>
                    <p>Our concierge can arrange personal shopping experiences and grocery delivery services.</p>
                </div>

                <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow-soft);">
                    <h4 style="color: var(--primary-navy); margin-bottom: 1rem;">Medical Services</h4>
                    <p>24/7 on-call medical assistance and pharmacy delivery available for all residents.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section" style="background: linear-gradient(135deg, var(--primary-navy), var(--navy-light)); padding: 4rem 0;">
        <div class="container text-center">
            <h2 style="color: var(--primary-white); margin-bottom: 1rem;">Experience Our Exceptional Amenities</h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.2rem; margin-bottom: 2rem;">Book your stay and enjoy access to all our world-class facilities</p>
            <a href="book.php" class="btn btn-primary">Book Your Stay</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
