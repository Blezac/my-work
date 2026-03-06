<!-- Footer -->
<footer class="bg-black text-white">
    <!-- Newsletter -->
    <div class="bg-primary py-14">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-white/70 text-xs tracking-widest uppercase mb-2">Stay Connected</p>
            <h3 class="font-display text-3xl md:text-4xl text-white mb-3">Your Glow Awaits</h3>
            <p class="text-white/80 mb-8 text-sm max-w-md mx-auto">Subscribe to receive skincare tips, product launches, and exclusive offers crafted for melanin-rich skin.</p>
            <form id="newsletterForm" onsubmit="subscribeNewsletter(event)" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                <input type="email" name="email" placeholder="Your email address" required
                    class="flex-1 px-5 py-3 text-black text-sm outline-none rounded-none placeholder-gray-400 focus:ring-2 focus:ring-white/30">
                <button type="submit" class="bg-black text-white px-6 py-3 text-xs tracking-widest uppercase font-medium hover:bg-gray-900 transition-colors">
                    Subscribe
                </button>
            </form>
            <p class="text-white/50 text-xs mt-4">No spam. Unsubscribe anytime.</p>
        </div>
    </div>
    
    <!-- Main Footer -->
    <div class="py-14">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <!-- Brand -->
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                        <span class="text-white text-sm font-bold font-display">G</span>
                    </div>
                    <div>
                        <span class="font-display text-xl font-semibold text-white tracking-tight">Glōw</span>
                        <span class="font-display text-xl text-primary italic ml-1">by Nara</span>
                    </div>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">
                    Premium skincare rooted in African botanical tradition. Formulated for melanin-rich skin, backed by dermatological science.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 border border-gray-700 flex items-center justify-center text-gray-400 hover:border-primary hover:text-primary transition-colors">
                        <i class="fab fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 border border-gray-700 flex items-center justify-center text-gray-400 hover:border-primary hover:text-primary transition-colors">
                        <i class="fab fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 border border-gray-700 flex items-center justify-center text-gray-400 hover:border-primary hover:text-primary transition-colors">
                        <i class="fab fa-tiktok text-sm"></i>
                    </a>
                    <a href="#" class="w-9 h-9 border border-gray-700 flex items-center justify-center text-gray-400 hover:border-primary hover:text-primary transition-colors">
                        <i class="fab fa-twitter text-sm"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-medium text-sm tracking-widest uppercase mb-5">Shop</h4>
                <ul class="space-y-3">
                    <li><a href="<?= SITE_URL ?>/shop.php" class="text-gray-400 text-sm hover:text-white transition-colors">All Products</a></li>
                    <li><a href="<?= SITE_URL ?>/shop.php?category=serums" class="text-gray-400 text-sm hover:text-white transition-colors">Serums</a></li>
                    <li><a href="<?= SITE_URL ?>/shop.php?category=moisturizers" class="text-gray-400 text-sm hover:text-white transition-colors">Moisturizers</a></li>
                    <li><a href="<?= SITE_URL ?>/shop.php?category=cleansers" class="text-gray-400 text-sm hover:text-white transition-colors">Cleansers</a></li>
                    <li><a href="<?= SITE_URL ?>/shop.php?category=sunscreen" class="text-gray-400 text-sm hover:text-white transition-colors">Sunscreen</a></li>
                    <li><a href="<?= SITE_URL ?>/shop.php?category=masks" class="text-gray-400 text-sm hover:text-white transition-colors">Masks</a></li>
                </ul>
            </div>
            
            <!-- Company -->
            <div>
                <h4 class="text-white font-medium text-sm tracking-widest uppercase mb-5">Company</h4>
                <ul class="space-y-3">
                    <li><a href="<?= SITE_URL ?>/about.php" class="text-gray-400 text-sm hover:text-white transition-colors">Our Story</a></li>
                    <li><a href="<?= SITE_URL ?>/blog.php" class="text-gray-400 text-sm hover:text-white transition-colors">Skin Journal</a></li>
                    <li><a href="<?= SITE_URL ?>/faq.php" class="text-gray-400 text-sm hover:text-white transition-colors">FAQ</a></li>
                    <li><a href="<?= SITE_URL ?>/contact.php" class="text-gray-400 text-sm hover:text-white transition-colors">Contact Us</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-gray-400 text-sm hover:text-white transition-colors">Returns Policy</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div>
                <h4 class="text-white font-medium text-sm tracking-widest uppercase mb-5">Contact</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-gray-400 text-sm">
                        <i class="fas fa-map-marker-alt mt-1 text-primary flex-shrink-0"></i>
                        <span>14 Admiralty Way, Lekki Phase 1, Lagos, Nigeria</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400 text-sm">
                        <i class="fas fa-phone text-primary flex-shrink-0"></i>
                        <a href="tel:+2349012345678" class="hover:text-white transition-colors">+234 901 234 5678</a>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400 text-sm">
                        <i class="fas fa-envelope text-primary flex-shrink-0"></i>
                        <a href="mailto:hello@glownara.com" class="hover:text-white transition-colors">hello@glownara.com</a>
                    </li>
                    <li class="flex items-center gap-3 text-gray-400 text-sm">
                        <i class="fab fa-whatsapp text-primary flex-shrink-0"></i>
                        <a href="https://wa.me/2349012345678" class="hover:text-white transition-colors">WhatsApp Us</a>
                    </li>
                </ul>
                <div class="mt-5 p-4 border border-gray-800">
                    <p class="text-gray-500 text-xs mb-1">Business Hours</p>
                    <p class="text-gray-300 text-sm">Mon – Fri: 9am – 6pm</p>
                    <p class="text-gray-300 text-sm">Sat: 10am – 4pm</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom -->
    <div class="border-t border-gray-900 py-5">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-gray-600 text-xs">&copy; <?= date('Y') ?> Glōw by Nara. All rights reserved. Made with ♥ in Lagos, Nigeria.</p>
            <div class="flex items-center gap-4">
                <span class="text-gray-600 text-xs">Secure Payments:</span>
                <div class="flex gap-3 text-gray-500">
                    <i class="fab fa-cc-visa text-lg"></i>
                    <i class="fab fa-cc-mastercard text-lg"></i>
                    <i class="fas fa-mobile-alt text-lg"></i>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
<script>
// Newsletter subscription
function subscribeNewsletter(e) {
    e.preventDefault();
    const email = e.target.email.value;
    fetch('<?= SITE_URL ?>/newsletter.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'email=' + encodeURIComponent(email)
    })
    .then(r => r.json())
    .then(d => {
        showToast(d.message || 'Subscribed successfully!');
        e.target.reset();
    })
    .catch(() => showToast('Subscribed successfully!'));
}

// Add to cart function (global)
function addToCart(productId, productName, price, image) {
    fetch('<?= SITE_URL ?>/cart_action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=add&product_id=${productId}&quantity=1`
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showToast(productName + ' added to cart!');
            updateCartBadge(d.cart_count);
        }
    })
    .catch(() => showToast('Added to cart!'));
}

function updateCartBadge(count) {
    const badge = document.getElementById('cartBadge');
    if (badge) {
        badge.textContent = count;
        badge.classList.remove('hidden');
        badge.classList.add('flex');
    }
}

function showToast(message) {
    const toast = document.getElementById('toast');
    const toastMsg = document.getElementById('toastMsg');
    toastMsg.textContent = message;
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}
</script>
</body>
</html>
