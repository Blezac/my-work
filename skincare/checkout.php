<?php
$pageTitle = 'Checkout';
require_once 'includes/header.php';

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect(SITE_URL . '/cart.php');
}

$cartTotal = getCartTotal();
$shipping = $cartTotal >= 15000 ? 0 : 2500;
$grandTotal = $cartTotal + $shipping;
?>

<section class="bg-cream py-10 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-2 text-xs text-gray-400 mb-3">
            <a href="index.php" class="hover:text-primary">Home</a>
            <span>/</span>
            <a href="cart.php" class="hover:text-primary">Cart</a>
            <span>/</span>
            <span class="text-gray-700">Checkout</span>
        </div>
        <h1 class="font-display text-4xl font-semibold">Checkout</h1>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <form action="order_process.php" method="POST" id="checkoutForm" onsubmit="return validateForm('checkoutForm')">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <!-- Customer Details -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Contact Info -->
                    <div>
                        <h2 class="font-display text-xl font-semibold mb-5 pb-3 border-b border-gray-100">Contact Information</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Full Name *</label>
                                <input type="text" name="customer_name" required class="form-input" placeholder="Adaeze Okonkwo">
                                <span class="text-xs text-red-400" data-error="customer_name"></span>
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Email Address *</label>
                                <input type="email" name="email" required class="form-input" placeholder="adaeze@email.com">
                                <span class="text-xs text-red-400" data-error="email"></span>
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Phone Number *</label>
                                <input type="tel" name="phone" required class="form-input" placeholder="+234 801 234 5678">
                                <span class="text-xs text-red-400" data-error="phone"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div>
                        <h2 class="font-display text-xl font-semibold mb-5 pb-3 border-b border-gray-100">Shipping Address</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Street Address *</label>
                                <input type="text" name="address" required class="form-input" placeholder="14 Admiralty Way, Lekki Phase 1">
                                <span class="text-xs text-red-400" data-error="address"></span>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">City *</label>
                                    <input type="text" name="city" required class="form-input" placeholder="Lagos">
                                </div>
                                <div>
                                    <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">State *</label>
                                    <select name="state" required class="form-input">
                                        <option value="">Select State</option>
                                        <?php
                                        $states = ['Abia','Adamawa','Akwa Ibom','Anambra','Bauchi','Bayelsa','Benue','Borno','Cross River','Delta','Ebonyi','Edo','Ekiti','Enugu','FCT','Gombe','Imo','Jigawa','Kaduna','Kano','Katsina','Kebbi','Kogi','Kwara','Lagos','Nasarawa','Niger','Ogun','Ondo','Osun','Oyo','Plateau','Rivers','Sokoto','Taraba','Yobe','Zamfara'];
                                        foreach ($states as $state): ?>
                                        <option value="<?= $state ?>"><?= $state ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Order Notes (optional)</label>
                                <textarea name="notes" rows="3" class="form-input resize-none" placeholder="Special delivery instructions, apartment number, etc."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div>
                        <h2 class="font-display text-xl font-semibold mb-5 pb-3 border-b border-gray-100">Payment Method</h2>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-primary transition-colors has-[:checked]:border-primary">
                                <input type="radio" name="payment_method" value="bank_transfer" checked class="accent-primary">
                                <div>
                                    <p class="text-sm font-medium">Bank Transfer</p>
                                    <p class="text-xs text-gray-500">Transfer to our account and send proof of payment via WhatsApp</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-primary transition-colors has-[:checked]:border-primary">
                                <input type="radio" name="payment_method" value="paystack" class="accent-primary">
                                <div>
                                    <p class="text-sm font-medium">Pay with Paystack</p>
                                    <p class="text-xs text-gray-500">Secure online payment via card, USSD, or bank</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border border-gray-200 cursor-pointer hover:border-primary transition-colors has-[:checked]:border-primary">
                                <input type="radio" name="payment_method" value="pay_on_delivery" class="accent-primary">
                                <div>
                                    <p class="text-sm font-medium">Pay on Delivery</p>
                                    <p class="text-xs text-gray-500">Available within Lagos. Cash or POS on delivery.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-cream p-6 sticky top-24">
                        <h3 class="font-display text-xl font-semibold mb-5">Your Order</h3>
                        
                        <!-- Items -->
                        <div class="space-y-4 pb-5 border-b border-gray-200">
                            <?php foreach ($cart as $item): ?>
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 bg-white flex-shrink-0 placeholder-img border border-gray-100">
                                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:1.2rem;">✨</div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-medium text-black leading-snug"><?= htmlspecialchars($item['name']) ?></p>
                                    <p class="text-xs text-gray-400">x<?= $item['quantity'] ?></p>
                                </div>
                                <span class="text-xs font-semibold"><?= formatPrice($item['price'] * $item['quantity']) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Totals -->
                        <div class="space-y-3 py-5 border-b border-gray-200">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal</span>
                                <span><?= formatPrice($cartTotal) ?></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Shipping</span>
                                <span class="<?= $shipping === 0 ? 'text-primary font-medium' : '' ?>">
                                    <?= $shipping === 0 ? 'FREE' : formatPrice($shipping) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between font-bold py-5 mb-5">
                            <span>Total</span>
                            <span class="text-primary text-lg"><?= formatPrice($grandTotal) ?></span>
                        </div>
                        
                        <!-- Bank Info (shown when bank transfer selected) -->
                        <div class="bg-white p-4 mb-5 border border-gray-200" id="bankInfo">
                            <p class="text-xs font-semibold uppercase tracking-widest mb-2 text-primary">Bank Transfer Details</p>
                            <p class="text-xs text-gray-600">Bank: <strong>Zenith Bank</strong></p>
                            <p class="text-xs text-gray-600">Account: <strong>1234567890</strong></p>
                            <p class="text-xs text-gray-600">Name: <strong>Glow by Nara Ltd</strong></p>
                            <p class="text-xs text-gray-400 mt-2">Send receipt to: <a href="https://wa.me/2349012345678" class="text-primary">WhatsApp</a></p>
                        </div>
                        
                        <button type="submit" class="btn-primary w-full text-center">
                            <i class="fas fa-lock mr-2"></i>Place Order
                        </button>
                        
                        <p class="text-xs text-gray-400 text-center mt-4">
                            By placing your order, you agree to our <a href="#" class="text-primary">Terms</a> and <a href="#" class="text-primary">Privacy Policy</a>.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
// Toggle bank info visibility
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const bankInfo = document.getElementById('bankInfo');
        bankInfo.style.display = this.value === 'bank_transfer' ? 'block' : 'none';
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
