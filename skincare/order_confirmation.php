<?php
$pageTitle = 'Order Confirmed';
require_once 'includes/header.php';

$order = $_SESSION['last_order'] ?? null;
if (!$order) {
    redirect(SITE_URL . '/index.php');
}
unset($_SESSION['last_order']);
?>

<section class="min-h-screen bg-cream py-20 flex items-center">
    <div class="max-w-2xl mx-auto px-4 w-full text-center">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-primary rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check text-white text-3xl"></i>
        </div>
        
        <h1 class="font-display text-4xl font-semibold mb-3">Order Confirmed!</h1>
        <p class="text-gray-500 text-sm mb-8">Thank you for choosing Glōw by Nara. Your order has been received.</p>
        
        <!-- Order Details -->
        <div class="bg-white p-8 text-left mb-6">
            <div class="grid grid-cols-2 gap-y-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Order Number</p>
                    <p class="font-bold text-primary text-lg"><?= htmlspecialchars($order['order_number']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Customer</p>
                    <p class="font-medium"><?= htmlspecialchars($order['customer_name']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Email</p>
                    <p><?= htmlspecialchars($order['email']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Order Total</p>
                    <p class="font-bold text-lg"><?= formatPrice($order['total']) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Payment</p>
                    <p><?= ucwords(str_replace('_', ' ', $order['payment_method'])) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Status</p>
                    <span class="inline-flex items-center gap-1 text-xs bg-yellow-50 text-yellow-700 border border-yellow-200 px-2 py-1">
                        <i class="fas fa-clock text-xs"></i> Pending
                    </span>
                </div>
            </div>
        </div>
        
        <?php if ($order['payment_method'] === 'bank_transfer'): ?>
        <!-- Bank Transfer Instructions -->
        <div class="bg-white border-l-4 border-primary p-6 text-left mb-6">
            <h3 class="font-semibold text-sm mb-3 text-primary">
                <i class="fas fa-university mr-2"></i>Complete Your Payment
            </h3>
            <div class="space-y-2 text-sm text-gray-600">
                <p>Please transfer <strong class="text-black"><?= formatPrice($order['total']) ?></strong> to:</p>
                <p>Bank: <strong class="text-black">Zenith Bank</strong></p>
                <p>Account Number: <strong class="text-black">1234567890</strong></p>
                <p>Account Name: <strong class="text-black">Glow by Nara Limited</strong></p>
                <p class="mt-3">After payment, please send proof to our <a href="https://wa.me/2349012345678?text=Hi! I just placed order <?= htmlspecialchars($order['order_number']) ?> and have made payment." class="text-primary font-medium">WhatsApp: +234 901 234 5678</a></p>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- What's Next -->
        <div class="bg-white p-6 text-left mb-8">
            <h3 class="font-semibold text-sm mb-4">What happens next?</h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">1</div>
                    <p class="text-sm text-gray-600">You'll receive an order confirmation email at <strong><?= htmlspecialchars($order['email']) ?></strong></p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">2</div>
                    <p class="text-sm text-gray-600">Once payment is confirmed, your order will be processed and packaged</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center flex-shrink-0 text-white text-xs font-bold">3</div>
                    <p class="text-sm text-gray-600">Delivery within 2-5 business days. You'll receive tracking information.</p>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="shop.php" class="btn-primary">Continue Shopping</a>
            <a href="https://wa.me/2349012345678?text=Hi! I have a question about my order <?= htmlspecialchars($order['order_number']) ?>" 
               target="_blank" class="btn-outline">
                <i class="fab fa-whatsapp mr-2"></i>WhatsApp Support
            </a>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
