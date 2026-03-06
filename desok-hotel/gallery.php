<?php
/* ========================================
   DESOK LUXURY APARTMENT - GALLERY PAGE
   Photo gallery with lightbox effect
   ======================================== */

session_start();
$page_title = "Gallery - Desok Luxury Apartment";
$current_page = "gallery";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore our luxury apartments through our photo gallery. See our elegant interiors and world-class amenities.">
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
            <p class="hero-subtitle">Explore</p>
            <h1 class="hero-title">Photo Gallery</h1>
            <p class="hero-description">A visual tour of our luxury apartments and amenities</p>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="section">
        <div class="container">
            <div class="gallery-grid">
                
                <!-- Gallery Item 1 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800" alt="Living Room" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Elegant Living Room</p>
                    </div>
                </div>

                <!-- Gallery Item 2 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1540518614846-7eded433c457?w=800" alt="Bedroom" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Master Bedroom Suite</p>
                    </div>
                </div>

                <!-- Gallery Item 3 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1556912173-3bb406ef7e77?w=800" alt="Kitchen" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Modern Kitchen</p>
                    </div>
                </div>

                <!-- Gallery Item 4 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1552321554-5fefe8c9ef14?w=800" alt="Bathroom" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Luxury Bathroom</p>
                    </div>
                </div>

                <!-- Gallery Item 5 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1571508601936-de19d4a50634?w=800" alt="Swimming Pool" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Rooftop Pool</p>
                    </div>
                </div>

                <!-- Gallery Item 6 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=800" alt="Gym" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Fitness Center</p>
                    </div>
                </div>

                <!-- Gallery Item 7 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1502672260066-6bc35f0bec78?w=800" alt="Dining Area" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Dining Space</p>
                    </div>
                </div>

                <!-- Gallery Item 8 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800" alt="Balcony" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Private Balcony</p>
                    </div>
                </div>

                <!-- Gallery Item 9 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800" alt="Luxury Suite" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Executive Suite</p>
                    </div>
                </div>

                <!-- Gallery Item 10 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=800" alt="Lobby" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Grand Lobby</p>
                    </div>
                </div>

                <!-- Gallery Item 11 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600566753376-12c8ab7fb75b?w=800" alt="Lounge" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Residents Lounge</p>
                    </div>
                </div>

                <!-- Gallery Item 12 -->
                <div class="gallery-item">
                    <img src="https://images.unsplash.com/photo-1600607687644-c7171b42498f?w=800" alt="Garden" class="gallery-image">
                    <div class="gallery-overlay">
                        <p class="gallery-caption">Landscaped Gardens</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Virtual Tour CTA -->
    <section class="section" style="background: var(--bg-cream);">
        <div class="container text-center">
            <div class="section-title">
                <h2>Want to See More?</h2>
                <p>Schedule a virtual tour or visit us in person</p>
            </div>
            <div style="margin-top: 2rem;">
                <a href="contact.php" class="btn btn-primary" style="margin-right: 1rem;">Schedule a Tour</a>
                <a href="book.php" class="btn btn-secondary">Book Your Stay</a>
            </div>
        </div>
    </section>

    <!-- Lightbox Modal -->
    <div class="lightbox">
        <span class="lightbox-close">&times;</span>
        <img src="" alt="Gallery Image" class="lightbox-content">
    </div>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- JavaScript -->
    <script src="assets/js/script.js"></script>
</body>
</html>
