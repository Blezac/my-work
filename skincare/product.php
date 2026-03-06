<?php
require_once '../includes/db.php';

$slug = isset($_GET['slug']) ? sanitize($_GET['slug']) : '';
if (!$slug) {
    header('Location: ' . SITE_URL . '/shop.php');
    exit();
}

// Fetch product
$stmt = $pdo->prepare("SELECT * FROM products WHERE slug = ? LIMIT 1");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: ' . SITE_URL . '/shop.php');
    exit();
}

$pageTitle = $product['name'];

// Fetch reviews
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE product_id = ? AND approved = 1 ORDER BY created_at DESC LIMIT 10");
$stmt->execute([$product['id']]);
$reviews = $stmt->fetchAll();

// Related products
$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category'], $product['id']]);
$related = $stmt->fetchAll();

require_once '../includes/header.php';

// Review submitted?
$reviewMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $rName = sanitize($_POST['reviewer_name'] ?? '');
    $rRating = (int)($_POST['rating'] ?? 5);
    $rReview = sanitize($_POST['review_text'] ?? '');
    if ($rName && $rReview && $rRating >= 1 && $rRating <= 5) {
        $stmt = $pdo->prepare("INSERT INTO reviews (product_id, customer_name, rating, review) VALUES (?, ?, ?, ?)");
        $stmt->execute([$product['id'], $rName, $rRating, $rReview]);
        $reviewMsg = 'success';
    } else {
        $reviewMsg = 'error';
    }
}
?>

<!-- Breadcrumb -->
<div class="bg-cream py-4 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-xs text-gray-400">
            <a href="<?= SITE_URL ?>/index.php" class="hover:text-primary">Home</a>
            <span>/</span>
            <a href="<?= SITE_URL ?>/shop.php" class="hover:text-primary">Shop</a>
            <span>/</span>
            <a href="<?= SITE_URL ?>/shop.php?category=<?= urlencode($product['category']) ?>" class="hover:text-primary"><?= ucfirst($product['category']) ?></a>
            <span>/</span>
            <span class="text-gray-700"><?= htmlspecialchars($product['name']) ?></span>
        </div>
    </div>
</div>

<!-- Product Section -->
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
            
            <!-- Image Gallery -->
            <div>
                <div class="relative bg-cream mb-4" style="height: 480px;">
                    <div class="w-full h-full placeholder-img" id="mainProductImageContainer">
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:1rem;">
                            <div style="font-size:5rem;">✨</div>
                            <p style="font-family:'Playfair Display',serif;font-size:1.2rem;color:#1F7A5A;font-style:italic;"><?= htmlspecialchars($product['name']) ?></p>
                            <p style="font-size:0.7rem;color:#888;text-transform:uppercase;letter-spacing:0.2em;">Glōw by Nara</p>
                        </div>
                    </div>
                    
                    <?php if ($product['sale_price']): ?>
                    <div class="absolute top-4 left-4">
                        <span class="badge-sale">Sale</span>
                    </div>
                    <?php endif; ?>
                </div>
                <!-- Thumbnails -->
                <div class="grid grid-cols-4 gap-2">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="product-thumb bg-cream cursor-pointer border-2 <?= $i === 0 ? 'border-primary' : 'border-transparent hover:border-gray-300' ?> transition-colors" style="height: 90px;"
                         data-src="" onclick="this.parentNode.querySelectorAll('.product-thumb').forEach(t => t.className = t.className.replace('border-primary','border-transparent')); this.className = this.className.replace('border-transparent','border-primary');">
                        <div class="w-full h-full placeholder-img">
                            <span style="font-size:1.5rem;">✨</span>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div>
                <p class="text-primary text-xs tracking-widest uppercase mb-2"><?= ucfirst($product['category']) ?></p>
                <h1 class="font-display text-3xl md:text-4xl font-semibold mb-3"><?= htmlspecialchars($product['name']) ?></h1>
                
                <!-- Rating -->
                <div class="flex items-center gap-3 mb-5">
                    <div class="flex items-center gap-1">
                        <?= renderStars($product['rating']) ?>
                    </div>
                    <span class="text-sm text-gray-500"><?= number_format($product['rating'], 1) ?> (<?= number_format($product['review_count']) ?> reviews)</span>
                </div>
                
                <!-- Price -->
                <div class="mb-6 flex items-center gap-3">
                    <?php if ($product['sale_price']): ?>
                    <span class="font-display text-3xl font-bold text-primary"><?= formatPrice($product['sale_price']) ?></span>
                    <span class="text-gray-400 text-xl line-through"><?= formatPrice($product['price']) ?></span>
                    <span class="badge-sale text-sm"><?= round((1 - $product['sale_price']/$product['price'])*100) ?>% OFF</span>
                    <?php else: ?>
                    <span class="font-display text-3xl font-bold text-black"><?= formatPrice($product['price']) ?></span>
                    <?php endif; ?>
                </div>
                
                <!-- Description -->
                <p class="text-gray-600 text-sm leading-relaxed mb-6 border-b border-gray-100 pb-6">
                    <?= htmlspecialchars($product['description']) ?>
                </p>
                
                <!-- Quantity + Cart -->
                <div class="flex items-center gap-4 mb-5">
                    <div class="flex items-center border border-gray-200">
                        <button onclick="if(document.getElementById('qty').value>1) document.getElementById('qty').value--" 
                                class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-cream transition-colors">−</button>
                        <input type="number" id="qty" value="1" min="1" max="99" class="w-12 h-10 text-center text-sm outline-none border-x border-gray-200">
                        <button onclick="document.getElementById('qty').value++" 
                                class="w-10 h-10 flex items-center justify-center text-gray-500 hover:bg-cream transition-colors">+</button>
                    </div>
                    
                    <button onclick="addToCartWithQty()" class="btn-primary flex-1 flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-bag"></i>
                        Add to Cart
                    </button>
                </div>
                
                <a href="<?= SITE_URL ?>/checkout.php" class="btn-outline w-full text-center block mb-6">Buy Now</a>
                
                <!-- Trust icons -->
                <div class="grid grid-cols-3 gap-3 py-5 border-y border-gray-100 mb-6">
                    <div class="text-center">
                        <i class="fas fa-truck text-primary text-sm mb-1"></i>
                        <p class="text-xs text-gray-500">Free shipping<br>over ₦15k</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-undo text-primary text-sm mb-1"></i>
                        <p class="text-xs text-gray-500">30-day<br>returns</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-shield-alt text-primary text-sm mb-1"></i>
                        <p class="text-xs text-gray-500">Derm<br>approved</p>
                    </div>
                </div>
                
                <!-- Ingredients Accordion -->
                <div class="border-b border-gray-100">
                    <button onclick="toggleAccordion('ingredients')" class="flex items-center justify-between w-full py-4 text-sm font-medium text-left">
                        <span>Key Ingredients</span>
                        <i class="fas fa-plus text-xs text-gray-400" id="ingredients-icon"></i>
                    </button>
                    <div class="faq-answer" id="ingredients-content">
                        <div class="pb-4 text-sm text-gray-600 leading-relaxed">
                            <?= htmlspecialchars($product['ingredients']) ?>
                        </div>
                    </div>
                </div>
                
                <div class="border-b border-gray-100">
                    <button onclick="toggleAccordion('howto')" class="flex items-center justify-between w-full py-4 text-sm font-medium text-left">
                        <span>How to Use</span>
                        <i class="fas fa-plus text-xs text-gray-400" id="howto-icon"></i>
                    </button>
                    <div class="faq-answer" id="howto-content">
                        <div class="pb-4 text-sm text-gray-600 leading-relaxed">
                            Apply a small amount to clean, dry skin morning and evening. Gently massage in upward circular motions until fully absorbed. Follow with moisturizer. For external use only. Patch test before first use.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Reviews Section -->
<section class="py-16 bg-cream">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-display text-3xl font-semibold mb-10">Customer Reviews</h2>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Rating Summary -->
            <div>
                <div class="text-center mb-6">
                    <p class="font-display text-6xl font-bold text-primary"><?= number_format($product['rating'], 1) ?></p>
                    <div class="flex justify-center gap-1 my-2">
                        <?= renderStars($product['rating']) ?>
                    </div>
                    <p class="text-gray-500 text-sm"><?= number_format($product['review_count']) ?> reviews</p>
                </div>
                
                <!-- Rating Bars -->
                <?php for ($i = 5; $i >= 1; $i--): ?>
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs text-gray-500 w-4"><?= $i ?></span>
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                    <div class="review-bar flex-1">
                        <div class="review-bar-fill" style="width: <?= $i === 5 ? 70 : ($i === 4 ? 20 : ($i === 3 ? 7 : 3)) ?>%"></div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
            
            <!-- Reviews List -->
            <div class="lg:col-span-2">
                <?php if ($reviewMsg === 'success'): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-4 rounded mb-6">
                    <i class="fas fa-check-circle mr-2"></i>Thank you! Your review has been submitted for approval.
                </div>
                <?php endif; ?>
                
                <?php if (empty($reviews)): ?>
                <div class="text-center py-10 text-gray-400">
                    <i class="fas fa-star text-3xl mb-3"></i>
                    <p>No reviews yet. Be the first to review!</p>
                </div>
                <?php else: ?>
                <div class="space-y-6 mb-10">
                    <?php foreach ($reviews as $review): ?>
                    <div class="bg-white p-5 border border-gray-100">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-primary/20 rounded-full flex items-center justify-center">
                                    <span class="text-primary font-bold text-sm"><?= strtoupper(substr($review['customer_name'], 0, 1)) ?></span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium"><?= htmlspecialchars($review['customer_name']) ?></p>
                                    <p class="text-xs text-gray-400"><?= date('M j, Y', strtotime($review['created_at'])) ?></p>
                                </div>
                            </div>
                            <div class="flex gap-0.5">
                                <?= renderStars($review['rating']) ?>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($review['review']) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- Write Review Form -->
                <div class="bg-white p-6 border border-gray-100">
                    <h3 class="font-display text-xl font-semibold mb-5">Write a Review</h3>
                    <form method="POST">
                        <div class="mb-4">
                            <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Your Rating *</label>
                            <div class="flex gap-2" id="starRating">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <label class="cursor-pointer">
                                    <input type="radio" name="rating" value="<?= $i ?>" class="hidden" <?= $i === 5 ? 'checked' : '' ?>>
                                    <i class="fas fa-star text-xl <?= $i <= 5 ? 'text-yellow-400' : 'text-gray-300' ?> hover:text-yellow-400 transition-colors star-btn" data-val="<?= $i ?>"></i>
                                </label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Your Name *</label>
                                <input type="text" name="reviewer_name" required class="form-input" placeholder="Adaeze O.">
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Email (optional)</label>
                                <input type="email" class="form-input" placeholder="your@email.com">
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Your Review *</label>
                            <textarea name="review_text" rows="4" required class="form-input resize-none" placeholder="Share your experience with this product..."></textarea>
                        </div>
                        <button type="submit" name="submit_review" class="btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
<?php if (!empty($related)): ?>
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-display text-3xl font-semibold mb-8">You May Also Like</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <?php foreach ($related as $rel): ?>
            <div class="product-card bg-white border border-gray-100 overflow-hidden group">
                <div class="relative bg-cream" style="height: 200px;">
                    <div class="w-full h-full placeholder-img">
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.25rem;">
                            <div style="font-size:2rem;">✨</div>
                            <span style="font-size:0.6rem;color:#1F7A5A;font-style:italic;font-family:'Playfair Display',serif;"><?= htmlspecialchars($rel['name']) ?></span>
                        </div>
                    </div>
                    <div class="product-overlay absolute inset-0 bg-black/10 flex items-end justify-center pb-3 z-10">
                        <button onclick="addToCart(<?= $rel['id'] ?>, '<?= htmlspecialchars(addslashes($rel['name'])) ?>', <?= $rel['sale_price'] ?: $rel['price'] ?>, '')"
                            class="add-to-cart-btn btn-primary text-xs py-1.5 px-4">Add to Cart</button>
                    </div>
                </div>
                <div class="p-3">
                    <a href="product.php?slug=<?= urlencode($rel['slug']) ?>">
                        <h4 class="text-sm font-medium hover:text-primary transition-colors mb-1"><?= htmlspecialchars($rel['name']) ?></h4>
                    </a>
                    <p class="text-sm font-semibold <?= $rel['sale_price'] ? 'text-primary' : 'text-black' ?>">
                        <?= formatPrice($rel['sale_price'] ?: $rel['price']) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
function addToCartWithQty() {
    const qty = parseInt(document.getElementById('qty').value) || 1;
    fetch('<?= SITE_URL ?>/cart_action.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=add&product_id=<?= $product['id'] ?>&quantity=${qty}`
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showToast('<?= htmlspecialchars(addslashes($product['name'])) ?> added to cart!');
            updateCartBadge(d.cart_count);
        }
    });
}

function toggleAccordion(id) {
    const content = document.getElementById(id + '-content');
    const icon = document.getElementById(id + '-icon');
    content.classList.toggle('open');
    icon.className = content.classList.contains('open') ? 'fas fa-minus text-xs text-primary' : 'fas fa-plus text-xs text-gray-400';
}

// Star rating interaction
document.querySelectorAll('.star-btn').forEach(star => {
    star.addEventListener('click', function() {
        const val = parseInt(this.dataset.val);
        document.querySelectorAll('.star-btn').forEach((s, i) => {
            s.className = s.className.replace('text-yellow-400', 'text-gray-300').replace('text-gray-300', '');
            s.classList.add(i < val ? 'text-yellow-400' : 'text-gray-300');
        });
    });
});
</script>

<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float"><i class="fab fa-whatsapp"></i></a>

<?php require_once '../includes/footer.php'; ?>
