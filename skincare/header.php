<?php
require_once __DIR__ . '/db.php';
$cartCount = getCartCount();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Glōw by Nara - Premium Nigerian skincare crafted for melanin-rich skin. Natural ingredients, dermatologist approved.">
    <title><?= isset($pageTitle) ? $pageTitle . ' | ' : '' ?>Glōw by Nara</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#1F7A5A',
                    'primary-dark': '#145A41',
                    'primary-light': '#2a9972',
                    cream: '#FAF8F4',
                    sand: '#F0EBE1',
                },
                fontFamily: {
                    display: ['"Playfair Display"', 'Georgia', 'serif'],
                    body: ['"DM Sans"', 'sans-serif'],
                },
                animation: {
                    'fade-up': 'fadeUp 0.6s ease forwards',
                    'fade-in': 'fadeIn 0.5s ease forwards',
                    'slide-in': 'slideIn 0.4s ease forwards',
                }
            }
        }
    }
    </script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">

    <style>
        * { font-family: 'DM Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        :root {
            --primary: #1F7A5A;
            --primary-dark: #145A41;
            --primary-light: #2a9972;
        }
        html { scroll-behavior: smooth; }
        .nav-link.active { color: var(--primary); font-weight: 600; }
        .btn-primary {
            background: var(--primary);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 2px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(31,122,90,0.3);
        }
        .btn-outline {
            border: 1.5px solid var(--primary);
            color: var(--primary);
            padding: 0.75rem 2rem;
            border-radius: 2px;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            display: inline-block;
        }
        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.1);
        }
        .product-card:hover .product-overlay {
            opacity: 1;
        }
        .product-overlay {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(25px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #1F7A5A;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 4px;
            z-index: 9999;
            transform: translateX(200%);
            transition: transform 0.4s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .toast.show { transform: translateX(0); }
        .placeholder-img {
            background: linear-gradient(135deg, #e8f5f0 0%, #d0ece2 50%, #b8e2d4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1F7A5A;
            font-family: 'Playfair Display', serif;
            font-style: italic;
        }
    </style>
</head>
<body class="bg-white text-black antialiased">

<!-- Announcement Bar -->
<div class="bg-primary text-white text-center py-2 text-xs tracking-widest uppercase font-medium" id="announcementBar">
    Free shipping on orders over ₦15,000 &nbsp;|&nbsp; 
    <span class="italic font-light">Crafted for melanin-rich skin</span>
    <button onclick="document.getElementById('announcementBar').style.display='none'" class="ml-4 opacity-60 hover:opacity-100">✕</button>
</div>

<!-- Navigation -->
<nav class="bg-white sticky top-0 z-50 border-b border-gray-100" id="mainNav">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18 py-4">
            
            <!-- Logo -->
            <a href="<?= SITE_URL ?>/index.php" class="flex items-center gap-2 group">
                <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-bold font-display">G</span>
                </div>
                <div>
                    <span class="font-display text-xl font-semibold text-black tracking-tight">Glōw</span>
                    <span class="font-display text-xl text-primary italic ml-1">by Nara</span>
                </div>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-8">
                <a href="<?= SITE_URL ?>/index.php" class="nav-link text-sm text-gray-700 hover:text-primary transition-colors <?= $currentPage === 'index' ? 'active' : '' ?>">Home</a>
                <a href="<?= SITE_URL ?>/shop.php" class="nav-link text-sm text-gray-700 hover:text-primary transition-colors <?= $currentPage === 'shop' ? 'active' : '' ?>">Shop</a>
                <a href="<?= SITE_URL ?>/about.php" class="nav-link text-sm text-gray-700 hover:text-primary transition-colors <?= $currentPage === 'about' ? 'active' : '' ?>">About</a>
                <a href="<?= SITE_URL ?>/blog.php" class="nav-link text-sm text-gray-700 hover:text-primary transition-colors <?= $currentPage === 'blog' ? 'active' : '' ?>">Journal</a>
                <a href="<?= SITE_URL ?>/faq.php" class="nav-link text-sm text-gray-700 hover:text-primary transition-colors <?= $currentPage === 'faq' ? 'active' : '' ?>">FAQ</a>
                <a href="<?= SITE_URL ?>/contact.php" class="nav-link text-sm text-gray-700 hover:text-primary transition-colors <?= $currentPage === 'contact' ? 'active' : '' ?>">Contact</a>
            </div>

            <!-- Icons -->
            <div class="flex items-center gap-4">
                <button class="text-gray-700 hover:text-primary transition-colors hidden md:block" onclick="toggleSearch()">
                    <i class="fas fa-search text-sm"></i>
                </button>
                <a href="<?= SITE_URL ?>/cart.php" class="relative text-gray-700 hover:text-primary transition-colors">
                    <i class="fas fa-shopping-bag text-sm"></i>
                    <?php if ($cartCount > 0): ?>
                    <span class="absolute -top-2 -right-2 bg-primary text-white text-xs w-4 h-4 rounded-full flex items-center justify-center font-medium" id="cartBadge"><?= $cartCount ?></span>
                    <?php else: ?>
                    <span class="absolute -top-2 -right-2 bg-primary text-white text-xs w-4 h-4 rounded-full items-center justify-center font-medium hidden" id="cartBadge">0</span>
                    <?php endif; ?>
                </a>
                <!-- Mobile Menu Button -->
                <button class="md:hidden text-gray-700" id="mobileMenuBtn" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-lg" id="menuIcon"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Search Bar -->
    <div class="hidden bg-sand border-t border-gray-100 py-3" id="searchBar">
        <div class="max-w-7xl mx-auto px-4 flex items-center gap-3">
            <i class="fas fa-search text-gray-400"></i>
            <input type="text" placeholder="Search for products, ingredients..." 
                   class="flex-1 bg-transparent outline-none text-sm text-gray-700 placeholder-gray-400"
                   id="searchInput" onkeyup="handleSearch(event)">
            <button onclick="toggleSearch()" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div class="hidden md:hidden bg-white border-t border-gray-100 shadow-lg" id="mobileMenu">
        <div class="px-4 py-4 flex flex-col gap-4">
            <a href="<?= SITE_URL ?>/index.php" class="text-sm text-gray-700 hover:text-primary py-2 border-b border-gray-50">Home</a>
            <a href="<?= SITE_URL ?>/shop.php" class="text-sm text-gray-700 hover:text-primary py-2 border-b border-gray-50">Shop</a>
            <a href="<?= SITE_URL ?>/about.php" class="text-sm text-gray-700 hover:text-primary py-2 border-b border-gray-50">About</a>
            <a href="<?= SITE_URL ?>/blog.php" class="text-sm text-gray-700 hover:text-primary py-2 border-b border-gray-50">Journal</a>
            <a href="<?= SITE_URL ?>/faq.php" class="text-sm text-gray-700 hover:text-primary py-2 border-b border-gray-50">FAQ</a>
            <a href="<?= SITE_URL ?>/contact.php" class="text-sm text-gray-700 hover:text-primary py-2">Contact</a>
        </div>
    </div>
</nav>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMsg">Added to cart!</span>
</div>
