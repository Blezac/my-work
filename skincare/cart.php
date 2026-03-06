<?php
$pageTitle = 'Shopping Cart';
require_once 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
$cartTotal = getCartTotal();
$shipping = $cartTotal >= 15000 ? 0 : 2500;
$grandTotal = $cartTotal + $shipping;
?>

<section class="bg-cream py-10 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
            <a href="index.php" class="hover:text-primary">Home</a>
            <span>/</span>
            <span class="text-gray-700">Cart</span>
        </div>
        <h1 class="font-display text-4xl md:text-5xl font-semibold">Your Cart</h1>
        <p class="text-gray-500 text-sm mt-2"><?= count($cart) ?> item<?= count($cart) !== 1 ? 's' : '' ?></p>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <?php if (empty($cart)): ?>
        <div class="text-center py-20">
            <div class="text-6xl mb-5">🛍️</div>
            <h2 class="font-display text-3xl font-semibold mb-3">Your cart is empty</h2>
            <p class="text-gray-500 text-sm mb-8">Discover our collection of premium skincare products crafted for your skin.</p>
            <a href="shop.php" class="btn-primary">Continue Shopping</a>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="hidden md:grid grid-cols-12 gap-4 text-xs uppercase tracking-widest text-gray-400 pb-4 border-b border-gray-100 mb-4">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Qty</div>
                    <div class="col-span-2 text-right">Total</div>
                </div>
                
                <?php foreach ($cart as $itemKey => $item): ?>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center py-5 border-b border-gray-100" id="cart-item-<?= htmlspecialchars($itemKey) ?>">
                    <!-- Product -->
                    <div class="md:col-span-6 flex items-center gap-4">
                        <div class="w-20 h-20 bg-cream flex-shrink-0 placeholder-img">
                            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:1.5rem;">✨</div>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-black"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-xs text-gray-400 mt-1">Glōw by Nara</p>
                            <button onclick="removeCartItem('<?= htmlspecialchars($itemKey) ?>')" class="text-xs text-red-400 hover:text-red-600 mt-1 transition-colors">
                                <i class="fas fa-trash-alt mr-1"></i>Remove
                            </button>
                        </div>
                    </div>
                    
                    <!-- Price -->
                    <div class="md:col-span-2 text-center">
                        <span class="text-sm font-medium"><?= formatPrice($item['price']) ?></span>
                    </div>
                    
                    <!-- Quantity -->
                    <div class="md:col-span-2 flex justify-center">
                        <div class="flex items-center border border-gray-200">
                            <button onclick="updateQuantity('<?= htmlspecialchars($itemKey) ?>', -1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-cream text-sm">−</button>
                            <input type="number" id="qty-<?= htmlspecialchars($itemKey) ?>" value="<?= $item['quantity'] ?>" min="1" 
                                   onchange="setQuantity('<?= htmlspecialchars($itemKey) ?>', this.value)"
                                   class="qty-input">
                            <button onclick="updateQuantity('<?= htmlspecialchars($itemKey) ?>', 1)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-cream text-sm">+</button>
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="md:col-span-2 text-right">
                        <span class="text-sm font-semibold text-primary" id="item-total-<?= htmlspecialchars($itemKey) ?>">
                            <?= formatPrice($item['price'] * $item['quantity']) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Cart Actions -->
                <div class="flex items-center justify-between mt-6">
                    <a href="shop.php" class="btn-outline text-sm py-2">← Continue Shopping</a>
                    <button onclick="if(confirm('Clear all items?')) fetch('cart_action.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=clear'}).then(()=>location.reload())" 
                            class="text-sm text-red-400 hover:text-red-600 transition-colors">
                        <i class="fas fa-trash mr-1"></i>Clear Cart
                    </button>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-cream p-6 sticky top-24">
                    <h3 class="font-display text-xl font-semibold mb-5">Order Summary</h3>
                    
                    <div class="space-y-3 pb-5 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Subtotal</span>
                            <span id="cartTotal"><?= formatPrice($cartTotal) ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Shipping</span>
                            <span class="<?= $shipping === 0 ? 'text-primary font-medium' : '' ?>">
                                <?= $shipping === 0 ? 'FREE' : formatPrice($shipping) ?>
                            </span>
                        </div>
                        <?php if ($shipping > 0): ?>
                        <p class="text-xs text-gray-400">Add <?= formatPrice(15000 - $cartTotal) ?> more for free shipping</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex justify-between font-semibold py-5 border-b border-gray-200 mb-5">
                        <span>Total</span>
                        <span class="text-primary text-lg"><?= formatPrice($grandTotal) ?></span>
                    </div>
                    
                    <!-- Coupon -->
                    <div class="mb-5">
                        <div class="flex gap-2">
                            <input type="text" placeholder="Coupon code" class="form-input flex-1 text-sm py-2">
                            <button class="px-4 py-2 border border-gray-200 text-sm text-gray-600 hover:border-primary hover:text-primary transition-colors">Apply</button>
                        </div>
                    </div>
                    
                    <a href="checkout.php" class="btn-primary w-full text-center block">
                        Proceed to Checkout <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    
                    <div class="mt-5 flex items-center justify-center gap-3 text-gray-400 text-xs">
                        <i class="fas fa-lock"></i>
                        <span>Secure Checkout</span>
                        <i class="fab fa-cc-visa text-base"></i>
                        <i class="fab fa-cc-mastercard text-base"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float"><i class="fab fa-whatsapp"></i></a>

<?php require_once 'includes/footer.php'; ?>
