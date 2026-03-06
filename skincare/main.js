// ============================================
// GLOW BY NARA - Main JavaScript
// ============================================

// Mobile Menu Toggle
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const icon = document.getElementById('menuIcon');
    if (menu) {
        menu.classList.toggle('hidden');
        if (menu.classList.contains('hidden')) {
            icon.className = 'fas fa-bars text-lg';
        } else {
            icon.className = 'fas fa-times text-lg';
        }
    }
}

// Search Toggle
function toggleSearch() {
    const bar = document.getElementById('searchBar');
    if (bar) {
        bar.classList.toggle('hidden');
        if (!bar.classList.contains('hidden')) {
            document.getElementById('searchInput')?.focus();
        }
    }
}

// Handle Search
function handleSearch(e) {
    if (e.key === 'Enter') {
        const query = document.getElementById('searchInput').value.trim();
        if (query) {
            window.location.href = 'shop.php?search=' + encodeURIComponent(query);
        }
    }
}

// Animate on Scroll
function initScrollAnimations() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, i * 100);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    elements.forEach(el => observer.observe(el));
}

// Sticky Nav Shadow
function initStickyNav() {
    const nav = document.getElementById('mainNav');
    if (!nav) return;
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.classList.add('shadow-sm');
        } else {
            nav.classList.remove('shadow-sm');
        }
    });
}

// Image Slider / Hero Slider
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    if (!slides.length) return;
    let current = 0;
    const dots = document.querySelectorAll('.slider-dot');
    
    function showSlide(index) {
        slides.forEach((s, i) => {
            s.style.opacity = i === index ? '1' : '0';
        });
        dots.forEach((d, i) => {
            d.classList.toggle('bg-white', i === index);
            d.classList.toggle('bg-white/40', i !== index);
        });
        current = index;
    }
    
    window.nextSlide = function() {
        showSlide((current + 1) % slides.length);
    };
    window.prevSlide = function() {
        showSlide((current - 1 + slides.length) % slides.length);
    };
    window.goToSlide = function(index) {
        showSlide(index);
    };
    
    // Auto-advance
    setInterval(nextSlide, 5000);
}

// Product image gallery
function initProductGallery() {
    const mainImg = document.getElementById('mainProductImage');
    const thumbs = document.querySelectorAll('.product-thumb');
    thumbs.forEach(thumb => {
        thumb.addEventListener('click', () => {
            if (mainImg) mainImg.src = thumb.dataset.src;
            thumbs.forEach(t => t.classList.remove('ring-2', 'ring-primary'));
            thumb.classList.add('ring-2', 'ring-primary');
        });
    });
}

// Cart Quantity Update
function updateQuantity(itemId, change) {
    const input = document.getElementById('qty-' + itemId);
    if (!input) return;
    const newQty = Math.max(1, parseInt(input.value) + change);
    input.value = newQty;
    updateCartItem(itemId, newQty);
}

function setQuantity(itemId, value) {
    const qty = Math.max(1, parseInt(value));
    document.getElementById('qty-' + itemId).value = qty;
    updateCartItem(itemId, qty);
}

function updateCartItem(itemId, qty) {
    fetch('cart_action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=update&item_id=${itemId}&quantity=${qty}`
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            // Update subtotals
            const itemTotal = document.getElementById('item-total-' + itemId);
            if (itemTotal && d.item_total !== undefined) {
                itemTotal.textContent = '₦' + Number(d.item_total).toLocaleString('en-NG', {minimumFractionDigits: 2});
            }
            const cartTotal = document.getElementById('cartTotal');
            if (cartTotal && d.cart_total !== undefined) {
                cartTotal.textContent = '₦' + Number(d.cart_total).toLocaleString('en-NG', {minimumFractionDigits: 2});
            }
            updateCartBadge(d.cart_count);
        }
    });
}

function removeCartItem(itemId) {
    if (!confirm('Remove this item from your cart?')) return;
    fetch('cart_action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=remove&item_id=${itemId}`
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const row = document.getElementById('cart-item-' + itemId);
            if (row) {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    if (d.cart_count === 0) location.reload();
                }, 300);
            }
            updateCartBadge(d.cart_count);
            const cartTotal = document.getElementById('cartTotal');
            if (cartTotal && d.cart_total !== undefined) {
                cartTotal.textContent = '₦' + Number(d.cart_total).toLocaleString('en-NG', {minimumFractionDigits: 2});
            }
        }
    });
}

// Product Filtering
function filterProducts(category) {
    const cards = document.querySelectorAll('.product-card-wrap');
    const btns = document.querySelectorAll('.filter-btn');
    
    btns.forEach(btn => {
        btn.classList.remove('bg-primary', 'text-white');
        btn.classList.add('text-gray-500');
        if (btn.dataset.cat === category) {
            btn.classList.add('bg-primary', 'text-white');
            btn.classList.remove('text-gray-500');
        }
    });
    
    cards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
            setTimeout(() => card.style.opacity = '1', 10);
        } else {
            card.style.opacity = '0';
            setTimeout(() => card.style.display = 'none', 300);
        }
    });
}

// Form Validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    let valid = true;
    form.querySelectorAll('[required]').forEach(field => {
        const value = field.value.trim();
        const error = form.querySelector('[data-error="' + field.name + '"]');
        if (!value) {
            field.classList.add('border-red-400');
            if (error) error.textContent = 'This field is required';
            valid = false;
        } else {
            field.classList.remove('border-red-400');
            if (error) error.textContent = '';
        }
        if (field.type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            field.classList.add('border-red-400');
            if (error) error.textContent = 'Please enter a valid email';
            valid = false;
        }
    });
    return valid;
}

// Smooth scroll for anchor links
document.addEventListener('click', function(e) {
    const a = e.target.closest('a[href^="#"]');
    if (a) {
        const target = document.querySelector(a.getAttribute('href'));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
});

// Lazy load placeholder images
function initPlaceholderImages() {
    document.querySelectorAll('img[data-src]').forEach(img => {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    img.src = img.dataset.src;
                    observer.unobserve(img);
                }
            });
        });
        observer.observe(img);
    });
}

// Init all
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    initStickyNav();
    initHeroSlider();
    initProductGallery();
    initPlaceholderImages();
});
