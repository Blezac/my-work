<?php
$pageTitle = 'Frequently Asked Questions';
require_once 'includes/header.php';

$faqs = [
    'Products & Ingredients' => [
        ['q' => 'Are your products safe for dark skin tones?', 'a' => 'Absolutely. Every Glōw by Nara product is specifically formulated and tested for melanin-rich skin. We understand the unique needs of darker skin tones, including sensitivity to hyperpigmentation, and have tailored our formulas accordingly.'],
        ['q' => 'Are your products dermatologist approved?', 'a' => 'Yes. Our entire range has been reviewed and approved by dermatologists who specialize in melanin-rich skin. We collaborate with skin experts to ensure every formula is safe, effective, and backed by science.'],
        ['q' => 'Do your products contain harsh bleaching agents?', 'a' => 'Never. We do not use mercury, hydroquinone (at harmful concentrations), parabens, or any other ingredient that could cause long-term harm to your skin. Our brightening products use natural actives like Vitamin C, niacinamide, and kojic acid.'],
        ['q' => 'Are your products suitable for sensitive skin?', 'a' => 'Most of our products are formulated to be gentle enough for sensitive skin. Products suitable for sensitive skin are clearly marked. We always recommend doing a patch test on a small area before applying to your face.'],
        ['q' => 'Are your products cruelty-free?', 'a' => 'Yes, all Glōw by Nara products are 100% cruelty-free. We never test on animals, and we never will. We are committed to ethical beauty.'],
    ],
    'Shipping & Delivery' => [
        ['q' => 'Do you deliver across Nigeria?', 'a' => 'Yes! We deliver to all 36 states and the FCT. Delivery within Lagos takes 1-3 business days, while delivery to other states takes 3-7 business days.'],
        ['q' => 'How much does shipping cost?', 'a' => 'Shipping within Lagos is ₦1,500. Shipping to other states is ₦2,500. Orders above ₦15,000 qualify for free shipping anywhere in Nigeria!'],
        ['q' => 'Do you ship internationally?', 'a' => 'Currently we ship within Nigeria only. However, we are actively working on expanding to international shipping, especially to the UK, US, and Canada. Sign up for our newsletter to be notified when this launches.'],
        ['q' => 'How do I track my order?', 'a' => 'Once your order is shipped, we will send you a tracking number via SMS and email. You can also contact us via WhatsApp with your order number for real-time updates.'],
    ],
    'Orders & Payment' => [
        ['q' => 'What payment methods do you accept?', 'a' => 'We accept bank transfer (Zenith Bank), online card payments via Paystack, USSD payments, and cash/POS on delivery (Lagos only). All online payments are secured with SSL encryption.'],
        ['q' => 'Can I modify or cancel my order?', 'a' => 'You can cancel or modify your order within 2 hours of placing it by contacting us via WhatsApp: +234 901 234 5678. Once orders are processed and packaged, cancellations may not be possible.'],
        ['q' => 'What is your return policy?', 'a' => 'We offer a 30-day return policy for unused, unopened products in their original packaging. If a product has caused an adverse skin reaction, please contact us with photos and we will work to resolve the situation.'],
        ['q' => 'Can I get a refund?', 'a' => 'Refunds are processed within 5-7 business days after we receive and inspect the returned item. Refunds are issued via the original payment method or bank transfer.'],
    ],
    'Skincare Advice' => [
        ['q' => 'How do I build a skincare routine?', 'a' => 'A basic routine includes: Cleanse → Tone → Treat (serum/oil) → Moisturise → SPF (morning). We recommend starting simple with 3-4 products and introducing new products one at a time so you can monitor your skin\'s response.'],
        ['q' => 'How long until I see results?', 'a' => 'Results vary depending on the product and skin concern. For brightening products like our Radiance Glow Serum, many customers notice a difference in 2-4 weeks. For stubborn hyperpigmentation, allow 6-8 weeks of consistent use.'],
        ['q' => 'Can I use multiple Glōw by Nara products together?', 'a' => 'Yes! Our products are designed to work together as a complete system. We recommend building your routine around the Glow Ritual: Cleanser → Toner → Serum → Moisturiser. Add SPF in the morning and our Night Repair Oil in the evening.'],
        ['q' => 'I have acne-prone skin. Which products should I use?', 'a' => 'For acne-prone skin, we recommend our Black Soap Cleanser (gentle, antibacterial), Toning Mist (non-comedogenic), and Deep Moisture Cream (lightweight formula). Avoid heavy oils if you are highly acne-prone.'],
    ],
];
?>

<section class="py-20 bg-cream border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3">Help Center</p>
        <h1 class="font-display text-5xl md:text-6xl font-semibold mb-4">Frequently Asked Questions</h1>
        <p class="text-gray-500 text-sm max-w-md mx-auto">Find answers to common questions about our products, orders, and skincare advice.</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Category Nav -->
        <div class="flex flex-wrap gap-2 mb-10">
            <?php foreach (array_keys($faqs) as $cat): ?>
            <a href="#<?= sanitize(strtolower(str_replace(['&', ' '], ['', '-'], $cat))) ?>" 
               class="px-4 py-2 text-xs border border-gray-200 text-gray-500 hover:border-primary hover:text-primary transition-colors">
                <?= htmlspecialchars($cat) ?>
            </a>
            <?php endforeach; ?>
        </div>
        
        <!-- FAQ Sections -->
        <?php foreach ($faqs as $category => $questions): ?>
        <?php $catId = strtolower(str_replace(['&', ' '], ['', '-'], $category)); ?>
        <div id="<?= $catId ?>" class="mb-12 animate-on-scroll">
            <h2 class="font-display text-2xl font-semibold mb-6 pb-3 border-b border-gray-100"><?= htmlspecialchars($category) ?></h2>
            
            <div class="space-y-1">
                <?php foreach ($questions as $i => $faq): ?>
                <?php $faqId = $catId . '-' . $i; ?>
                <div class="border border-gray-100">
                    <button onclick="toggleFAQ('<?= $faqId ?>')" 
                            class="flex items-center justify-between w-full px-5 py-4 text-sm font-medium text-left hover:bg-cream transition-colors">
                        <span><?= htmlspecialchars($faq['q']) ?></span>
                        <i class="fas fa-plus text-xs text-gray-400 flex-shrink-0 ml-4" id="icon-<?= $faqId ?>"></i>
                    </button>
                    <div class="faq-answer" id="faq-<?= $faqId ?>">
                        <div class="px-5 pb-4 text-sm text-gray-600 leading-relaxed">
                            <?= htmlspecialchars($faq['a']) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        
        <!-- Contact CTA -->
        <div class="bg-cream p-8 text-center mt-12 animate-on-scroll">
            <h3 class="font-display text-2xl font-semibold mb-3">Still Have Questions?</h3>
            <p class="text-gray-500 text-sm mb-6">Our skincare experts are ready to help you personally.</p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="contact.php" class="btn-primary">Contact Us</a>
                <a href="https://wa.me/2349012345678" target="_blank" class="btn-outline">
                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp Chat
                </a>
            </div>
        </div>
    </div>
</section>

<script>
function toggleFAQ(id) {
    const content = document.getElementById('faq-' + id);
    const icon = document.getElementById('icon-' + id);
    content.classList.toggle('open');
    icon.className = content.classList.contains('open') 
        ? 'fas fa-minus text-xs text-primary flex-shrink-0 ml-4' 
        : 'fas fa-plus text-xs text-gray-400 flex-shrink-0 ml-4';
}
</script>

<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float"><i class="fab fa-whatsapp"></i></a>

<?php require_once 'includes/footer.php'; ?>
