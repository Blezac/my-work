<?php
$pageTitle = 'Shop All Products';
require_once 'includes/header.php';

// Filters
$category = isset($_GET['category']) ? sanitize($_GET['category']) : 'all';
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sort = isset($_GET['sort']) ? sanitize($_GET['sort']) : 'featured';

// Build query
$where = "WHERE 1=1";
$params = [];

if ($category !== 'all') {
    $where .= " AND category = ?";
    $params[] = $category;
}

if ($search) {
    $where .= " AND (name LIKE ? OR description LIKE ? OR ingredients LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

$orderBy = match($sort) {
    'price_asc' => 'ORDER BY COALESCE(sale_price, price) ASC',
    'price_desc' => 'ORDER BY COALESCE(sale_price, price) DESC',
    'rating' => 'ORDER BY rating DESC',
    'newest' => 'ORDER BY created_at DESC',
    default => 'ORDER BY featured DESC, review_count DESC'
};

$stmt = $pdo->prepare("SELECT * FROM products $where $orderBy");
$stmt->execute($params);
$products = $stmt->fetchAll();

// Get categories for filter
$cats = $pdo->query("SELECT DISTINCT category FROM products ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
?>

<!-- Page Header -->
<section class="bg-cream py-16 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-4">
            <a href="index.php" class="hover:text-primary">Home</a>
            <span>/</span>
            <span class="text-gray-700">Shop</span>
        </div>
        <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4">
            <div>
                <h1 class="font-display text-4xl md:text-5xl font-semibold">
                    <?= $category !== 'all' ? ucfirst($category) : 'All Products' ?>
                    <?php if ($search): ?><em class="font-light text-gray-500"> — "<?= htmlspecialchars($search) ?>"</em><?php endif; ?>
                </h1>
                <p class="text-gray-500 text-sm mt-2"><?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> found</p>
            </div>
            
            <!-- Sort -->
            <div class="flex items-center gap-3">
                <label class="text-xs text-gray-500 uppercase tracking-widest">Sort by</label>
                <select onchange="location.href='shop.php?sort='+this.value+'&category=<?= urlencode($category) ?>&search=<?= urlencode($search) ?>'" 
                        class="form-input text-sm py-2 w-44">
                    <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Featured</option>
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                    <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Top Rated</option>
                    <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                    <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                </select>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-10">
            
            <!-- Sidebar Filters -->
            <aside class="lg:w-56 flex-shrink-0">
                <div class="sticky top-24">
                    <!-- Search -->
                    <div class="mb-8">
                        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4 text-gray-900">Search</h3>
                        <form action="shop.php" method="GET">
                            <div class="relative">
                                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                                       placeholder="Find products..." class="form-input pr-8 text-sm">
                                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fas fa-search text-xs"></i>
                                </button>
                            </div>
                            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                        </form>
                    </div>
                    
                    <!-- Categories -->
                    <div class="mb-8">
                        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4 text-gray-900">Categories</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="shop.php?sort=<?= urlencode($sort) ?>" 
                                   class="flex items-center justify-between text-sm py-1 <?= $category === 'all' ? 'text-primary font-medium' : 'text-gray-500 hover:text-black' ?> transition-colors">
                                    All Products
                                    <span class="text-xs text-gray-300"><?= $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() ?></span>
                                </a>
                            </li>
                            <?php foreach ($cats as $cat): ?>
                            <li>
                                <a href="shop.php?category=<?= urlencode($cat) ?>&sort=<?= urlencode($sort) ?>" 
                                   class="flex items-center justify-between text-sm py-1 <?= $category === $cat ? 'text-primary font-medium' : 'text-gray-500 hover:text-black' ?> transition-colors">
                                    <?= ucfirst($cat) ?>
                                    <span class="text-xs text-gray-300"><?= $pdo->prepare("SELECT COUNT(*) FROM products WHERE category = ?")->execute([$cat]) ? $pdo->query("SELECT COUNT(*) FROM products WHERE category = '$cat'")->fetchColumn() : '' ?></span>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!-- Benefits -->
                    <div class="bg-cream p-4">
                        <h3 class="text-xs font-semibold uppercase tracking-widest mb-3">Why Choose Us</h3>
                        <ul class="space-y-2 text-xs text-gray-600">
                            <li class="flex gap-2"><i class="fas fa-check text-primary mt-0.5"></i> Melanin-skin formulated</li>
                            <li class="flex gap-2"><i class="fas fa-check text-primary mt-0.5"></i> Free shipping over ₦15k</li>
                            <li class="flex gap-2"><i class="fas fa-check text-primary mt-0.5"></i> 30-day returns</li>
                            <li class="flex gap-2"><i class="fas fa-check text-primary mt-0.5"></i> Dermatologist approved</li>
                        </ul>
                    </div>
                </div>
            </aside>
            
            <!-- Product Grid -->
            <div class="flex-1">
                <?php if (empty($products)): ?>
                <div class="text-center py-20">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="font-display text-2xl font-semibold mb-2">No products found</h3>
                    <p class="text-gray-500 text-sm mb-6">Try a different search term or browse all products.</p>
                    <a href="shop.php" class="btn-primary">Browse All Products</a>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    <?php foreach ($products as $product): ?>
                    <div class="product-card-wrap" data-category="<?= htmlspecialchars($product['category']) ?>">
                        <div class="product-card bg-white border border-gray-100 overflow-hidden group">
                            <div class="relative overflow-hidden bg-cream" style="height: 280px;">
                                <div class="w-full h-full placeholder-img">
                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:0.5rem;">
                                        <div style="font-size:2.5rem;">✨</div>
                                        <span style="font-size:0.65rem;color:#1F7A5A;font-style:italic;font-family:'Playfair Display',serif;"><?= htmlspecialchars($product['name']) ?></span>
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
                                
                                <!-- Quick Actions -->
                                <div class="product-overlay absolute inset-0 bg-black/10 flex items-end justify-center pb-4 z-10">
                                    <button onclick="addToCart(<?= $product['id'] ?>, '<?= htmlspecialchars(addslashes($product['name'])) ?>', <?= $product['sale_price'] ?: $product['price'] ?>, '')"
                                        class="add-to-cart-btn btn-primary text-xs py-2 px-5">
                                        <i class="fas fa-shopping-bag mr-2"></i>Add to Cart
                                    </button>
                                </div>
                            </div>
                            
                            <div class="p-4 border-t border-gray-50">
                                <p class="text-gray-400 text-xs uppercase tracking-widest mb-1"><?= ucfirst($product['category']) ?></p>
                                <a href="products/product.php?slug=<?= urlencode($product['slug']) ?>">
                                    <h3 class="font-medium text-sm text-black mb-2 hover:text-primary transition-colors"><?= htmlspecialchars($product['name']) ?></h3>
                                </a>
                                <p class="text-gray-500 text-xs mb-3 leading-relaxed"><?= htmlspecialchars(substr($product['description'], 0, 70)) ?>...</p>
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
                                    <a href="products/product.php?slug=<?= urlencode($product['slug']) ?>" class="text-gray-400 hover:text-primary text-xs">Details →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float"><i class="fab fa-whatsapp"></i></a>

<?php require_once 'includes/footer.php'; ?>
