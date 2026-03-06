-- ============================================
-- GLŌW BY NARA - Nigerian Skincare Brand
-- Database Schema
-- ============================================

CREATE DATABASE IF NOT EXISTS glow_nara CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE glow_nara;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(10,2) NOT NULL,
    sale_price DECIMAL(10,2) DEFAULT NULL,
    image VARCHAR(255) NOT NULL,
    gallery TEXT DEFAULT NULL,
    description TEXT NOT NULL,
    ingredients TEXT DEFAULT NULL,
    category VARCHAR(100) DEFAULT 'skincare',
    stock INT DEFAULT 100,
    rating DECIMAL(3,2) DEFAULT 4.5,
    review_count INT DEFAULT 0,
    featured TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content LONGTEXT NOT NULL,
    excerpt TEXT DEFAULT NULL,
    image VARCHAR(255) NOT NULL,
    author VARCHAR(100) DEFAULT 'Glow by Nara',
    category VARCHAR(100) DEFAULT 'skincare',
    published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(50) NOT NULL UNIQUE,
    customer_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    notes TEXT DEFAULT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    shipping DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'bank_transfer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    customer_name VARCHAR(255) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review TEXT NOT NULL,
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    message TEXT NOT NULL,
    read_status TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS newsletter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Default Admin (password: Admin@2024)
INSERT INTO admin_users (username, password, email) VALUES 
('admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@glownara.com');

-- Sample Products
INSERT INTO products (name, slug, price, sale_price, image, description, ingredients, category, featured, rating, review_count) VALUES
('Radiance Glow Serum', 'radiance-glow-serum', 12500.00, 9999.00, 'assets/images/p1.jpg', 'Our bestselling brightening serum formulated specifically for melanin-rich skin. Packed with Vitamin C and natural extracts to even skin tone, reduce dark spots, and give you that luminous glow you deserve.', 'Aqua, Ascorbic Acid (Vitamin C) 15%, Niacinamide 5%, Kojic Acid, Turmeric Extract, Shea Butter, Aloe Vera, Hyaluronic Acid, Glycerin', 'serums', 1, 4.8, 248),
('Deep Moisture Cream', 'deep-moisture-cream', 8500.00, NULL, 'assets/images/p2.jpg', 'A rich, non-greasy moisturizing cream that deeply hydrates and nourishes the skin. Formulated with African shea butter and marula oil for 24-hour hydration.', 'Shea Butter, Marula Oil, Jojoba Beads, Ceramides, Peptides, Aloe Vera Gel, Glycerin, Vitamin E', 'moisturizers', 1, 4.7, 183),
('Black Soap Cleanser', 'black-soap-cleanser', 5500.00, NULL, 'assets/images/p3.jpg', 'Inspired by traditional African black soap, this gentle cleanser removes impurities without stripping the skin. Perfect for oily and combination skin types.', 'Black Soap Extract, Plantain Peel, Palm Kernel Oil, Cocoa Pod Ash, Tea Tree Oil, Glycerin', 'cleansers', 1, 4.6, 156),
('SPF 50 Sunscreen', 'spf-50-sunscreen', 7500.00, 6500.00, 'assets/images/p4.jpg', 'Lightweight, invisible sunscreen with SPF 50 protection. Leaves no white cast on darker skin tones. Protects against UVA and UVB rays while keeping skin hydrated.', 'Zinc Oxide, Titanium Dioxide, Niacinamide, Hyaluronic Acid, Vitamin C, Aloe Vera', 'sunscreen', 1, 4.9, 312),
('Exfoliating Face Mask', 'exfoliating-face-mask', 6800.00, NULL, 'assets/images/p5.jpg', 'A luxurious clay mask infused with African kaolin clay and papaya enzymes to gently exfoliate dead skin cells and reveal brighter, smoother skin.', 'Kaolin Clay, Bentonite, Papaya Enzyme, Turmeric, Rose Water, Honey Extract, Green Tea Extract', 'masks', 0, 4.5, 97),
('Toning Mist', 'toning-mist', 4500.00, NULL, 'assets/images/p6.jpg', 'A refreshing facial mist that tones, hydrates, and primes skin for the rest of your routine. Infused with rose water and green tea for antioxidant protection.', 'Rose Water, Green Tea Extract, Hyaluronic Acid, Witch Hazel, Niacinamide, Aloe Vera', 'toners', 0, 4.4, 74),
('Night Repair Oil', 'night-repair-oil', 11000.00, NULL, 'assets/images/p7.jpg', 'A luxurious overnight facial oil that repairs and rejuvenates skin while you sleep. Formulated with rosehip, marula, and moringa oils for intense nourishment.', 'Rosehip Oil, Marula Oil, Moringa Oil, Bakuchiol, Vitamin E, Lavender Essential Oil', 'oils', 0, 4.7, 128),
('Brightening Eye Cream', 'brightening-eye-cream', 9200.00, 7800.00, 'assets/images/p8.jpg', 'Targeted treatment for the delicate eye area. Reduces dark circles, puffiness, and fine lines with a blend of caffeine, vitamin K, and peptides.', 'Caffeine, Vitamin K, Peptides, Hyaluronic Acid, Arnica Extract, Aloe Vera', 'treatments', 0, 4.6, 89);

-- Sample Blog Posts
INSERT INTO blog_posts (title, slug, content, excerpt, image, category) VALUES
('5 Skincare Mistakes Women with Melanin Skin Should Avoid', '5-skincare-mistakes-melanin-skin', '<p>Having melanin-rich skin is beautiful, but it also comes with unique challenges that require a tailored skincare approach. Many of us unknowingly make mistakes that can lead to hyperpigmentation, uneven skin tone, and premature aging.</p><p>In this comprehensive guide, we explore the most common skincare mistakes and how to correct them for glowing, healthy skin.</p><p><strong>1. Over-exfoliating</strong><br>Exfoliating too often or using harsh scrubs can damage the skin barrier and lead to post-inflammatory hyperpigmentation, which appears darker on melanin-rich skin. Stick to gentle chemical exfoliants 2-3 times per week.</p><p><strong>2. Skipping SPF</strong><br>The myth that dark skin does not need sunscreen is one of the most damaging misconceptions. UV exposure is a major cause of dark spots and uneven tone. Use SPF 30-50 daily, rain or shine.</p><p><strong>3. Using products with harsh bleaching agents</strong><br>Products containing mercury or high concentrations of hydroquinone can cause permanent skin damage. Opt for natural brightening ingredients like Vitamin C, niacinamide, and kojic acid.</p>', 'Discover the most common skincare mistakes that could be sabotaging your glow and learn how to build a routine that truly works for melanin-rich skin.', 'assets/images/blog-1.jpg', 'skincare-tips'),
('The Ultimate Guide to Treating Hyperpigmentation Naturally', 'guide-treating-hyperpigmentation-naturally', '<p>Hyperpigmentation — those stubborn dark spots and uneven patches — is one of the most common skin concerns for people with darker skin tones. While it can feel frustrating, the good news is that with the right ingredients and consistency, you can achieve a more even complexion.</p><p><strong>Understanding Hyperpigmentation</strong><br>Hyperpigmentation occurs when the skin produces excess melanin in certain areas. This can be triggered by sun exposure, acne, hormonal changes, and inflammation.</p><p><strong>Natural Ingredients That Work</strong><br>Vitamin C is a powerful antioxidant that inhibits melanin production. Niacinamide helps reduce the transfer of melanin to skin cells. Turmeric has been used in African and Asian beauty traditions for centuries. Kojic acid, derived from fungi, is highly effective for brightening.</p>', 'A deep dive into natural, effective ways to treat dark spots and achieve the even skin tone you have always wanted.', 'assets/images/blog-2.jpg', 'ingredients'),
('Why Nigerian Women Are Switching to Natural Skincare', 'nigerian-women-switching-natural-skincare', '<p>There is a quiet revolution happening in Nigerian beauty culture. More and more women are moving away from harsh chemical products and embracing skincare that draws from African botanical traditions while incorporating modern dermatological science.</p><p>For generations, Nigerian women relied on natural ingredients — shea butter, coconut oil, black soap, turmeric — passed down from mothers and grandmothers. These ingredients work because they were developed in response to our climate, our skin, and our needs.</p><p>Today, brands like Glow by Nara are bridging the gap between ancestral knowledge and cutting-edge skincare science, creating products that truly understand and celebrate melanin-rich skin.</p>', 'Explore the growing movement toward natural, science-backed skincare among Nigerian women and why it matters for our skin health and cultural identity.', 'assets/images/blog-3.jpg', 'culture');

-- Sample Reviews
INSERT INTO reviews (product_id, customer_name, rating, review, approved) VALUES
(1, 'Adaeze O.', 5, 'This serum changed my skin in just 3 weeks! My dark spots are fading and I get so many compliments on my glow. Worth every kobo!', 1),
(1, 'Funmi B.', 5, 'I was skeptical at first but oh my goodness. My hyperpigmentation from years of harsh creams is finally clearing. Thank you!', 1),
(2, 'Chidinma E.', 5, 'The deep moisture cream is absolutely divine. My skin drinks it up and stays soft all day. No more ashy skin!', 1),
(4, 'Blessing A.', 5, 'FINALLY a sunscreen that does not leave a white cast on my dark skin. This is a game changer for melanin queens!', 1),
(3, 'Ngozi K.', 4, 'Love this cleanser. My skin feels so clean without that tight, stripped feeling. Will definitely repurchase.', 1);
