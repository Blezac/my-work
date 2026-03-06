<?php
/* ========================================
   DESOK LUXURY APARTMENT - APARTMENTS PAGE
   Showcase all apartment types with detailed information
   ======================================== */

session_start();
$page_title = "Our Luxury Apartments - Desok Luxury Apartment";
$current_page = "apartments";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore our collection of luxury apartments. From cozy 1-bedroom units to spacious luxury suites.">
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
            <p class="hero-subtitle">Discover</p>
            <h1 class="hero-title">Our Luxury Apartments</h1>
            <p class="hero-description">Choose from our meticulously designed spaces</p>
        </div>
    </section>

    <!-- Apartments Section -->
    <section class="section">
        <div class="container">
            
            <!-- 1-Bedroom Apartment -->
            <div class="apartment-card" style="margin-bottom: 4rem; display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
                <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800" alt="1-Bedroom Apartment" style="width: 100%; height: 400px; object-fit: cover; border-radius: 15px;">
                <div>
                    <div class="apartment-type">Standard</div>
                    <h2 class="apartment-title">1-Bedroom Apartment</h2>
                    <p class="apartment-description" style="margin-bottom: 2rem;">
                        Our 1-bedroom apartments offer the perfect blend of comfort and sophistication. 
                        Ideal for solo travelers or couples seeking a luxurious retreat. Each unit features 
                        contemporary design, premium furnishings, and modern amenities to make your stay unforgettable.
                    </p>
                    
                    <h4 style="margin-bottom: 1rem; color: var(--primary-gold);">Features & Amenities</h4>
                    <ul style="list-style: none; padding: 0; margin-bottom: 2rem;">
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Spacious bedroom with king-size bed</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Fully equipped modern kitchen</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Smart TV with premium channels</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> High-speed Wi-Fi</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Air conditioning & heating</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Private balcony with city views</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> 24/7 concierge service</li>
                    </ul>
                    
                    <div class="apartment-features" style="margin-bottom: 2rem;">
                        <span class="feature-item"><i class="fas fa-bed feature-icon"></i> 1 Bedroom</span>
                        <span class="feature-item"><i class="fas fa-bath feature-icon"></i> 1 Bathroom</span>
                        <span class="feature-item"><i class="fas fa-expand feature-icon"></i> 45 sqm</span>
                        <span class="feature-item"><i class="fas fa-users feature-icon"></i> 1-2 Guests</span>
                    </div>
                    
                    <a href="book.php?type=1-bedroom" class="btn btn-primary">Book This Apartment</a>
                </div>
            </div>

            <!-- 2-Bedroom Apartment -->
            <div class="apartment-card" style="margin-bottom: 4rem; display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
                <div>
                    <div class="apartment-type">Premium</div>
                    <h2 class="apartment-title">2-Bedroom Apartment</h2>
                    <p class="apartment-description" style="margin-bottom: 2rem;">
                        Perfect for families or groups, our 2-bedroom apartments provide spacious luxury living 
                        with separate bedrooms for privacy and comfort. Elegantly designed with attention to every 
                        detail, these apartments feature premium amenities and refined finishes throughout.
                    </p>
                    
                    <h4 style="margin-bottom: 1rem; color: var(--primary-gold);">Features & Amenities</h4>
                    <ul style="list-style: none; padding: 0; margin-bottom: 2rem;">
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Two spacious bedrooms with premium bedding</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Luxurious living and dining area</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Fully equipped gourmet kitchen</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Two modern bathrooms with premium fixtures</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> In-unit washer & dryer</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Work desk with ergonomic chair</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Daily housekeeping service</li>
                    </ul>
                    
                    <div class="apartment-features" style="margin-bottom: 2rem;">
                        <span class="feature-item"><i class="fas fa-bed feature-icon"></i> 2 Bedrooms</span>
                        <span class="feature-item"><i class="fas fa-bath feature-icon"></i> 2 Bathrooms</span>
                        <span class="feature-item"><i class="fas fa-expand feature-icon"></i> 75 sqm</span>
                        <span class="feature-item"><i class="fas fa-users feature-icon"></i> 2-4 Guests</span>
                    </div>
                    
                    <a href="book.php?type=2-bedroom" class="btn btn-primary">Book This Apartment</a>
                </div>
                <img src="https://images.unsplash.com/photo-1502672260066-6bc35f0bec78?w=800" alt="2-Bedroom Apartment" style="width: 100%; height: 400px; object-fit: cover; border-radius: 15px;">
            </div>

            <!-- Luxury Suite -->
            <div class="apartment-card" style="margin-bottom: 4rem; display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; align-items: center;">
                <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800" alt="Luxury Suite" style="width: 100%; height: 400px; object-fit: cover; border-radius: 15px;">
                <div>
                    <div class="apartment-type">Executive</div>
                    <h2 class="apartment-title">Luxury Suite</h2>
                    <p class="apartment-description" style="margin-bottom: 2rem;">
                        Experience the pinnacle of luxury living in our exclusive suites. Featuring panoramic views, 
                        expansive living spaces, and bespoke furnishings, these suites offer unparalleled comfort and 
                        sophistication. Perfect for those who demand nothing but the best.
                    </p>
                    
                    <h4 style="margin-bottom: 1rem; color: var(--primary-gold);">Features & Amenities</h4>
                    <ul style="list-style: none; padding: 0; margin-bottom: 2rem;">
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Three master bedrooms with en-suite bathrooms</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Grand living room with entertainment system</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Designer kitchen with premium appliances</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Private study/home office</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Luxury spa-like bathrooms</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Expansive terrace with panoramic views</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Dedicated concierge & butler service</li>
                        <li style="margin-bottom: 0.8rem;"><i class="fas fa-check" style="color: var(--primary-gold); margin-right: 0.8rem;"></i> Priority access to all amenities</li>
                    </ul>
                    
                    <div class="apartment-features" style="margin-bottom: 2rem;">
                        <span class="feature-item"><i class="fas fa-bed feature-icon"></i> 3 Bedrooms</span>
                        <span class="feature-item"><i class="fas fa-bath feature-icon"></i> 3 Bathrooms</span>
                        <span class="feature-item"><i class="fas fa-expand feature-icon"></i> 120 sqm</span>
                        <span class="feature-item"><i class="fas fa-users feature-icon"></i> Up to 6 Guests</span>
                    </div>
                    
                    <a href="book.php?type=luxury-suite" class="btn btn-primary">Book This Suite</a>
                </div>
            </div>

        </div>
    </section>

    <!-- Call to Action -->
    <section class="section" style="background: linear-gradient(135deg, var(--primary-navy), var(--navy-light)); padding: 4rem 0;">
        <div class="container text-center">
            <h2 style="color: var(--primary-white); margin-bottom: 1rem;">Ready to Experience Luxury Living?</h2>
            <p style="color: rgba(255,255,255,0.9); font-size: 1.2rem; margin-bottom: 2rem;">Book your stay today and discover unparalleled comfort</p>
            <a href="book.php" class="btn btn-primary">Book Your Apartment Now</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
    
    <style>
        @media (max-width: 968px) {
            .apartment-card[style*="grid-template-columns"] {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
</body>
</html>
