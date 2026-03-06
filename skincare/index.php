<?php
$pageTitle = 'Premium Skincare for Melanin-Rich Skin';
require_once 'includes/header.php';

// Fetch featured products
$stmt = $pdo->prepare("SELECT * FROM products WHERE featured = 1 ORDER BY created_at DESC LIMIT 4");
$stmt->execute();
$featuredProducts = $stmt->fetchAll();

// Fetch recent blog posts
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE published = 1 ORDER BY created_at DESC LIMIT 3");
$stmt->execute();
$blogPosts = $stmt->fetchAll();

// Fetch approved testimonials
$stmt = $pdo->prepare("SELECT r.*, p.name as product_name FROM reviews r JOIN products p ON r.product_id = p.id WHERE r.approved = 1 ORDER BY RAND() LIMIT 3");
$stmt->execute();
$testimonials = $stmt->fetchAll();
?>

<!-- ============ HERO SECTION ============ -->
<section class="relative h-screen min-h-[600px] overflow-hidden bg-black">
    <!-- Slide 1 -->
    <div class="hero-slide absolute inset-0 transition-opacity duration-1000" style="opacity:1;">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent z-10"></div>
        <div class="w-full h-full placeholder-img" style="background: linear-gradient(135deg, #0a3d26 0%, #1F7A5A 40%, #2ecc8e 80%, #d4f5e7 100%);">
            <div style="position:absolute;inset:0;background:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%25%22 height=%22100%25%22><defs><radialGradient id=%22g%22 cx=%2260%25%22 cy=%2250%25%22 r=%2260%25%22><stop offset=%220%25%22 stop-color=%22%23e8f9f0%22 stop-opacity=%220.4%22/><stop offset=%22100%25%22 stop-color=%22%231F7A5A%22 stop-opacity=%220%22/></radialGradient></defs><rect width=%22100%25%22 height=%22100%25%22 fill=%22url(%23g)%22/></svg>') no-repeat center/cover;"></div>
        </div>
        <div class="absolute inset-0 z-20 flex items-center">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="max-w-lg animate-on-scroll visible">
                    <p class="text-primary-light text-xs tracking-[0.3em] uppercase mb-4 font-medium">New Collection 2024</p>
                    <h1 class="font-display text-5xl md:text-6xl lg:text-7xl text-white font-semibold leading-tight mb-6">
                        Your Glow,<br>
                        <em class="italic font-light">Amplified.</em>
                    </h1>
                    <p class="text-white/80 text-base md:text-lg mb-8 leading-relaxed">
                        Premium skincare crafted for melanin-rich skin. Rooted in African botanical wisdom, powered by modern science.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="shop.php" class="btn-primary">Shop Now</a>
                        <a href="about.php" class="btn-outline" style="border-color:white;color:white;" onmouseover="this.style.background='white';this.style.color='black';" onmouseout="this.style.background='transparent';this.style.color='white';">Our Story</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 2 -->
    <div class="hero-slide absolute inset-0 transition-opacity duration-1000" style="opacity:0;">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent z-10"></div>
        <div class="w-full h-full" style="background: linear-gradient(135deg, #2d1a00 0%, #8b4513 40%, #d4a866 70%, #f5e6c8 100%);"></div>
        <div class="absolute inset-0 z-20 flex items-center">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="max-w-lg">
                    <p class="text-yellow-300 text-xs tracking-[0.3em] uppercase mb-4">Bestseller</p>
                    <h1 class="font-display text-5xl md:text-6xl text-white font-semibold leading-tight mb-6">
                        Radiance<br>
                        <em class="italic font-light text-yellow-200">Serum</em>
                    </h1>
                    <p class="text-white/80 text-base mb-8">Vitamin C + Niacinamide for visibly brighter skin in 14 days.</p>
                    <a href="products/product.php?slug=radiance-glow-serum" class="btn-primary">Shop Serum</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slide 3 -->
    <div class="hero-slide absolute inset-0 transition-opacity duration-1000" style="opacity:0;">
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/40 to-transparent z-10"></div>
        <div class="w-full h-full" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 70%, #533483 100%);"></div>
        <div class="absolute inset-0 z-20 flex items-center">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="max-w-lg">
                    <p class="text-purple-300 text-xs tracking-[0.3em] uppercase mb-4">Night Ritual</p>
                    <h1 class="font-display text-5xl md:text-6xl text-white font-semibold leading-tight mb-6">
                        Sleep. Repair.<br>
                        <em class="italic font-light text-purple-200">Transform.</em>
                    </h1>
                    <p class="text-white/80 text-base mb-8">Our Night Repair Oil works while you rest, for skin that wakes up glowing.</p>
                    <a href="products/product.php?slug=night-repair-oil" class="btn-primary">Discover</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Slider Controls -->
    <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 bg-white/20 backdrop-blur-sm text-white flex items-center justify-center hover:bg-white/40 transition-colors">
        <i class="fas fa-chevron-left text-sm"></i>
    </button>
    <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 bg-white/20 backdrop-blur-sm text-white flex items-center justify-center hover:bg-white/40 transition-colors">
        <i class="fas fa-chevron-right text-sm"></i>
    </button>
    
    <!-- Dots -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex gap-2">
        <button onclick="goToSlide(0)" class="slider-dot w-6 h-1 bg-white transition-all"></button>
        <button onclick="goToSlide(1)" class="slider-dot w-6 h-1 bg-white/40 transition-all"></button>
        <button onclick="goToSlide(2)" class="slider-dot w-6 h-1 bg-white/40 transition-all"></button>
    </div>
    
    <!-- Scroll Hint -->
    <div class="absolute bottom-8 right-8 z-30 hidden md:flex flex-col items-center gap-2">
        <span class="text-white/50 text-xs tracking-widest uppercase" style="writing-mode:vertical-rl">Scroll</span>
        <div class="w-px h-12 bg-white/30"></div>
    </div>
</section>

<!-- ============ BENEFITS STRIP ============ -->
<section class="bg-cream py-10 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="benefit-item group cursor-default py-4">
                <div class="benefit-icon">
                    <i class="fas fa-leaf text-xl text-primary"></i>
                </div>
                <p class="text-sm font-medium text-black">Organic Ingredients</p>
                <p class="text-xs text-gray-500 mt-1">100% natural actives</p>
            </div>
            <div class="benefit-item group cursor-default py-4">
                <div class="benefit-icon">
                    <i class="fas fa-shield-alt text-xl text-primary"></i>
                </div>
                <p class="text-sm font-medium text-black">Dermatologist Approved</p>
                <p class="text-xs text-gray-500 mt-1">Clinically tested formulas</p>
            </div>
            <div class="benefit-item group cursor-default py-4">
                <div class="benefit-icon">
                    <i class="fas fa-sun text-xl text-primary"></i>
                </div>
                <p class="text-sm font-medium text-black">Made for Melanin Skin</p>
                <p class="text-xs text-gray-500 mt-1">Formulated for our skin</p>
            </div>
            <div class="benefit-item group cursor-default py-4">
                <div class="benefit-icon">
                    <i class="fas fa-heart text-xl text-primary"></i>
                </div>
                <p class="text-sm font-medium text-black">Sensitive Skin Safe</p>
                <p class="text-xs text-gray-500 mt-1">Fragrance-free options</p>
            </div>
        </div>
    </div>
</section>

<!-- ============ FEATURED PRODUCTS ============ -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Section Header -->
        <div class="text-center mb-12 animate-on-scroll">
            <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3 font-medium">Bestsellers</p>
            <h2 class="font-display text-4xl md:text-5xl font-semibold mb-4">Curated for Your Glow</h2>
            <p class="text-gray-500 text-sm max-w-md mx-auto">Each product is thoughtfully formulated with clean, effective ingredients that celebrate and enhance melanin-rich skin.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $product): ?>
            <div class="product-card-wrap animate-on-scroll" data-category="<?= htmlspecialchars($product['category']) ?>">
                <div class="product-card bg-white border border-gray-100 overflow-hidden group">
                    <!-- Image -->
                    <div class="relative overflow-hidden bg-cream" style="height: 300px;">
                        <div class="w-full h-full placeholder-img">
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.5rem;">
                                <div style="font-size:3rem;">✨</div>
                                <span style="font-size:0.7rem;color:#1F7A5A;font-style:italic;font-family:'Playfair Display',serif;"><?= htmlspecialchars($product['name']) ?></span>
                            </div>
                        </div>
                        
                        <!-- Badges -->
                        <div class="absolute top-3 left-3 flex flex-col gap-1 z-10">
                            <?php if ($product['sale_price']): ?>
                            <span class="badge-sale">Sale</span>
                            <?php endif; ?>
                            <?php if ($product['review_count'] > 200): ?>
                            <span class="badge-new">Bestseller</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Quick Actions Overlay -->
                        <div class="product-overlay absolute inset-0 bg-black/10 flex items-end justify-center pb-4 z-10">
                            <button onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>', <?= $product['sale_price'] ?: $product['price'] ?>, '')"
                                class="add-to-cart-btn btn-primary text-xs py-2 px-5">
                                <i class="fas fa-shopping-bag mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                    
                    <!-- Info -->
                    <div class="p-4 border-t border-gray-50">
                        <p class="text-gray-400 text-xs uppercase tracking-widest mb-1"><?= ucfirst($product['category']) ?></p>
                        <a href="products/product.php?slug=<?= urlencode($product['slug']) ?>">
                            <h3 class="font-medium text-sm text-black mb-2 hover:text-primary transition-colors"><?= htmlspecialchars($product['name']) ?></h3>
                        </a>
                        <div class="flex items-center gap-1 mb-3">
                            <?= renderStars($product['rating']) ?>
                            <span class="text-gray-400 text-xs ml-1">(<?= number_format($product['review_count']) ?>)</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div>
                                <?php if ($product['sale_price']): ?>
                                <span class="font-semibold text-primary"><?= formatPrice($product['sale_price']) ?></span>
                                <span class="text-gray-400 text-xs line-through ml-1"><?= formatPrice($product['price']) ?></span>
                                <?php else: ?>
                                <span class="font-semibold text-black"><?= formatPrice($product['price']) ?></span>
                                <?php endif; ?>
                            </div>
                            <a href="products/product.php?slug=<?= urlencode($product['slug']) ?>" class="text-gray-400 hover:text-primary text-xs">View →</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-10 animate-on-scroll">
            <a href="shop.php" class="btn-outline">View All Products</a>
        </div>
    </div>
</section>

<!-- ============ ABOUT BRAND PREVIEW ============ -->
<section class="py-20 bg-cream overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <!-- Image Side -->
            <div class="animate-on-scroll">
                <div class="relative">
                    <div class="w-full aspect-square max-w-md mx-auto placeholder-img" style="background: linear-gradient(135deg, #0a3d26 0%, #1F7A5A 100%); min-height: 400px; border-radius: 0;">
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;padding:2rem;text-align:center;">
                            <div style="font-size:4rem;">🌿</div>
                            <p style="font-family:'Playfair Display',serif;font-size:1.5rem;color:white;font-style:italic;">Rooted in Africa,<br>Made for the World</p>
                        </div>
                    </div>
                    <!-- Floating stat box -->
                    <div class="absolute -bottom-5 -right-5 bg-white p-5 shadow-lg hidden md:block">
                        <p class="font-display text-3xl font-bold text-primary">50K+</p>
                        <p class="text-xs text-gray-500 mt-1">Happy Customers</p>
                    </div>
                </div>
            </div>
            
            <!-- Text Side -->
            <div class="animate-on-scroll">
                <p class="text-primary text-xs tracking-[0.3em] uppercase mb-4 font-medium">Our Story</p>
                <h2 class="font-display text-4xl md:text-5xl font-semibold mb-6 leading-tight">
                    Born in Lagos.<br>
                    <em class="italic font-normal text-primary">Built for Melanin.</em>
                </h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-4">
                    Glōw by Nara was born from a simple truth: melanin-rich skin is uniquely beautiful and deserves skincare that understands it. Founded by Nara Adeyemi in Lagos, we blend ancestral African botanical wisdom with cutting-edge dermatological science.
                </p>
                <p class="text-gray-600 text-sm leading-relaxed mb-8">
                    Every product we create is carefully formulated and tested on a diverse range of melanin-rich skin tones, because we believe your glow is our purpose.
                </p>
                
                <div class="grid grid-cols-3 gap-4 mb-8 pb-8 border-b border-gray-200">
                    <div class="text-center">
                        <p class="font-display text-2xl font-bold text-primary">5+</p>
                        <p class="text-xs text-gray-500 mt-1">Years of Research</p>
                    </div>
                    <div class="text-center">
                        <p class="font-display text-2xl font-bold text-primary">100%</p>
                        <p class="text-xs text-gray-500 mt-1">Natural Actives</p>
                    </div>
                    <div class="text-center">
                        <p class="font-display text-2xl font-bold text-primary">8</p>
                        <p class="text-xs text-gray-500 mt-1">Expert Products</p>
                    </div>
                </div>
                
                <a href="about.php" class="btn-primary">Discover Our Story</a>
            </div>
        </div>
    </div>
</section>

<!-- ============ RITUAL SECTION ============ -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 animate-on-scroll">
            <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3 font-medium">Your Daily Ritual</p>
            <h2 class="font-display text-4xl md:text-5xl font-semibold mb-4">The Glōw Routine</h2>
            <p class="text-gray-500 text-sm max-w-md mx-auto">A curated 4-step routine for radiant, nourished skin every single day.</p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $steps = [
                ['step' => '01', 'title' => 'Cleanse', 'desc' => 'Start with our Black Soap Cleanser to remove impurities gently without stripping.', 'icon' => '💧', 'color' => '#e8f5f0'],
                ['step' => '02', 'title' => 'Tone', 'desc' => 'Apply Toning Mist to balance skin pH and prep for actives.', 'icon' => '🌫️', 'color' => '#e8f5f0'],
                ['step' => '03', 'title' => 'Treat', 'desc' => 'Radiance Glow Serum delivers Vitamin C and Niacinamide where skin needs it most.', 'icon' => '✨', 'color' => '#e8f5f0'],
                ['step' => '04', 'title' => 'Moisturise', 'desc' => 'Seal in goodness with Deep Moisture Cream for all-day hydration.', 'icon' => '🌿', 'color' => '#e8f5f0'],
            ];
            foreach ($steps as $i => $step):
            ?>
            <div class="text-center p-6 border border-gray-100 group hover:border-primary transition-colors animate-on-scroll">
                <div class="w-14 h-14 mx-auto mb-4 flex items-center justify-center text-3xl bg-cream rounded-full group-hover:bg-primary/10 transition-colors">
                    <?= $step['icon'] ?>
                </div>
                <span class="text-primary text-xs font-bold tracking-widest">STEP <?= $step['step'] ?></span>
                <h3 class="font-display text-xl font-semibold mt-2 mb-3"><?= $step['title'] ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed"><?= $step['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ TESTIMONIALS ============ -->
<section class="py-20 bg-cream">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 animate-on-scroll">
            <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3 font-medium">Real Results</p>
            <h2 class="font-display text-4xl md:text-5xl font-semibold mb-4">Loved by Thousands</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php 
            $defaultTestimonials = [
                ['customer_name' => 'Adaeze O., Lagos', 'rating' => 5, 'review' => 'This serum changed my skin in just 3 weeks! My dark spots are fading and I get so many compliments on my glow. Worth every kobo!', 'product_name' => 'Radiance Glow Serum'],
                ['customer_name' => 'Blessing A., Abuja', 'rating' => 5, 'review' => 'FINALLY a sunscreen that does not leave a white cast on my dark skin. This is a game changer for us melanin queens! Will never use anything else.', 'product_name' => 'SPF 50 Sunscreen'],
                ['customer_name' => 'Chidinma E., Port Harcourt', 'rating' => 5, 'review' => 'The deep moisture cream is absolutely divine. My skin drinks it up and stays soft all day long. No more ashy skin!', 'product_name' => 'Deep Moisture Cream'],
            ];
            $displayTestimonials = !empty($testimonials) ? $testimonials : $defaultTestimonials;
            foreach ($displayTestimonials as $testimonial):
            ?>
            <div class="testimonial-card bg-white p-8 relative animate-on-scroll">
                <div class="flex items-center gap-1 mb-4">
                    <?php for ($i = 0; $i < 5; $i++): ?>
                    <i class="fas fa-star text-yellow-400 text-sm"></i>
                    <?php endfor; ?>
                </div>
                <p class="text-gray-700 text-sm leading-relaxed mb-6 italic">"<?= htmlspecialchars($testimonial['review']) ?>"</p>
                <div class="flex items-center gap-3 border-t border-gray-50 pt-4">
                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                        <span class="text-primary font-bold text-sm font-display"><?= strtoupper(substr($testimonial['customer_name'], 0, 1)) ?></span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-black"><?= htmlspecialchars($testimonial['customer_name']) ?></p>
                        <p class="text-xs text-gray-400"><?= isset($testimonial['product_name']) ? htmlspecialchars($testimonial['product_name']) : 'Verified Customer' ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ INGREDIENTS HIGHLIGHT ============ -->
<section class="gradient-green py-20 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 animate-on-scroll">
            <p class="text-white/60 text-xs tracking-[0.3em] uppercase mb-3">Our Commitment</p>
            <h2 class="font-display text-4xl md:text-5xl font-semibold mb-4">Pure. Potent. Proven.</h2>
            <p class="text-white/70 text-sm max-w-md mx-auto">Every ingredient is carefully selected for its efficacy on melanin-rich skin.</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <?php
            $ingredients = [
                ['name' => 'Shea Butter', 'origin' => 'West Africa', 'icon' => '🌰', 'benefit' => 'Deep hydration & healing'],
                ['name' => 'Vitamin C', 'origin' => 'Botanical', 'icon' => '🍊', 'benefit' => 'Brightening & antioxidant'],
                ['name' => 'Niacinamide', 'origin' => 'Science-backed', 'icon' => '⚗️', 'benefit' => 'Even skin tone'],
                ['name' => 'Turmeric', 'origin' => 'African tradition', 'icon' => '🌼', 'benefit' => 'Anti-inflammatory glow'],
            ];
            foreach ($ingredients as $ing):
            ?>
            <div class="animate-on-scroll">
                <div class="text-4xl mb-4"><?= $ing['icon'] ?></div>
                <h3 class="font-display text-xl font-semibold mb-1"><?= $ing['name'] ?></h3>
                <p class="text-white/50 text-xs mb-2"><?= $ing['origin'] ?></p>
                <p class="text-white/70 text-sm"><?= $ing['benefit'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12 animate-on-scroll">
            <a href="about.php#ingredients" class="btn-outline" style="border-color:white;color:white;" onmouseover="this.style.background='white';this.style.color='#1F7A5A';" onmouseout="this.style.background='transparent';this.style.color='white';">Learn About Our Ingredients</a>
        </div>
    </div>
</section>

<!-- ============ BLOG PREVIEW ============ -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between mb-12 animate-on-scroll">
            <div>
                <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3 font-medium">Knowledge & Glow</p>
                <h2 class="font-display text-4xl md:text-5xl font-semibold">The Skin Journal</h2>
            </div>
            <a href="blog.php" class="text-sm text-primary mt-4 md:mt-0 hover:underline">Read All Articles →</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($blogPosts as $post): ?>
            <div class="blog-card group animate-on-scroll overflow-hidden">
                <div class="overflow-hidden bg-cream" style="height: 220px;">
                    <div class="w-full h-full blog-image placeholder-img" style="background: linear-gradient(135deg, #0a3d26, #1F7A5A, #2ecc8e);">
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:3rem;">📖</span>
                        </div>
                    </div>
                </div>
                <div class="pt-5">
                    <span class="text-xs text-primary uppercase tracking-widest font-medium"><?= ucfirst(str_replace('-', ' ', $post['category'])) ?></span>
                    <a href="blog.php?slug=<?= urlencode($post['slug']) ?>">
                        <h3 class="font-display text-xl font-semibold mt-2 mb-3 group-hover:text-primary transition-colors leading-snug"><?= htmlspecialchars($post['title']) ?></h3>
                    </a>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4"><?= htmlspecialchars(substr($post['excerpt'], 0, 100)) ?>...</p>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400 text-xs"><?= date('M j, Y', strtotime($post['created_at'])) ?></span>
                        <a href="blog.php?slug=<?= urlencode($post['slug']) ?>" class="text-primary text-xs font-medium hover:underline">Read More →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============ TRUST BADGES ============ -->
<section class="bg-cream py-12 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="animate-on-scroll">
                <i class="fas fa-truck text-2xl text-primary mb-3"></i>
                <h4 class="text-sm font-medium mb-1">Fast Delivery</h4>
                <p class="text-xs text-gray-500">2-5 days nationwide</p>
            </div>
            <div class="animate-on-scroll">
                <i class="fas fa-undo text-2xl text-primary mb-3"></i>
                <h4 class="text-sm font-medium mb-1">Easy Returns</h4>
                <p class="text-xs text-gray-500">30-day return policy</p>
            </div>
            <div class="animate-on-scroll">
                <i class="fas fa-lock text-2xl text-primary mb-3"></i>
                <h4 class="text-sm font-medium mb-1">Secure Checkout</h4>
                <p class="text-xs text-gray-500">256-bit SSL encryption</p>
            </div>
            <div class="animate-on-scroll">
                <i class="fab fa-whatsapp text-2xl text-primary mb-3"></i>
                <h4 class="text-sm font-medium mb-1">WhatsApp Support</h4>
                <p class="text-xs text-gray-500">Available 7 days a week</p>
            </div>
        </div>
    </div>
</section>

<!-- WhatsApp Float -->
<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float" title="Chat on WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<!-- Back to Top -->
<button id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
    <i class="fas fa-chevron-up"></i>
</button>

<script>
window.addEventListener('scroll', () => {
    const btn = document.getElementById('backToTop');
    if (window.scrollY > 500) btn.classList.add('visible');
    else btn.classList.remove('visible');
});
</script>

<?php require_once 'includes/footer.php'; ?>
