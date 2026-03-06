<?php
$pageTitle = 'Contact Us';
require_once 'includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $phone = sanitize($_POST['phone'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $message])) {
            $success = "Thank you, {$name}! We've received your message and will get back to you within 24 hours.";
        } else {
            $error = 'Message could not be sent. Please try again or contact us via WhatsApp.';
        }
    }
}
?>

<section class="py-20 bg-cream border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="text-primary text-xs tracking-[0.3em] uppercase mb-3">Get in Touch</p>
        <h1 class="font-display text-5xl md:text-6xl font-semibold mb-4">Contact Us</h1>
        <p class="text-gray-500 text-sm max-w-md mx-auto">We'd love to hear from you. Reach out with questions, feedback, or just to say hello.</p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12">
            <div class="lg:col-span-2">
                <h2 class="font-display text-2xl font-semibold mb-6">Let's Talk</h2>
                <p class="text-gray-600 text-sm leading-relaxed mb-8">Have a skincare question? Our team of experts is ready to help you achieve your best skin.</p>
                
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1">Visit Us</p>
                            <p class="text-gray-500 text-sm">14 Admiralty Way, Lekki Phase 1<br>Lagos, Nigeria</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-primary text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1">Call Us</p>
                            <a href="tel:+2349012345678" class="text-gray-500 text-sm hover:text-primary">+234 901 234 5678</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-primary text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1">Email Us</p>
                            <a href="mailto:hello@glownara.com" class="text-gray-500 text-sm hover:text-primary">hello@glownara.com</a>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <i class="fab fa-whatsapp text-primary text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium mb-1">WhatsApp</p>
                            <a href="https://wa.me/2349012345678" target="_blank" class="text-gray-500 text-sm hover:text-primary">+234 901 234 5678</a>
                            <p class="text-xs text-gray-400 mt-0.5">Fastest response</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 p-5 bg-cream">
                    <p class="text-sm font-medium mb-3">Business Hours</p>
                    <div class="space-y-1 text-sm text-gray-500">
                        <div class="flex justify-between"><span>Monday – Friday</span><span>9am – 6pm</span></div>
                        <div class="flex justify-between"><span>Saturday</span><span>10am – 4pm</span></div>
                        <div class="flex justify-between"><span>Sunday</span><span class="text-red-400">Closed</span></div>
                    </div>
                </div>
            </div>
            
            <div class="lg:col-span-3">
                <?php if ($success): ?>
                <div class="bg-green-50 border border-green-200 text-green-700 p-5 mb-6 text-sm">
                    <i class="fas fa-check-circle mr-2"></i><?= htmlspecialchars($success) ?>
                </div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 p-5 mb-6 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
                </div>
                <?php endif; ?>
                
                <div class="bg-cream p-8">
                    <h2 class="font-display text-2xl font-semibold mb-6">Send a Message</h2>
                    <form method="POST" class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Full Name </label>
                                <input type="text" name="name" required class="form-input bg-white" placeholder="Adaeze Okonkwo">
                            </div>
                            <div>
                                <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Email Address </label>
                                <input type="email" name="email" required class="form-input bg-white" placeholder="adaeze@email.com">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Phone Number</label>
                            <input type="tel" name="phone" class="form-input bg-white" placeholder="+234 801 234 5678">
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Subject</label>
                            <select name="subject" class="form-input bg-white">
                                <option>Product Inquiry</option>
                                <option>Order Support</option>
                                <option>Skincare Consultation</option>
                                <option>Wholesale/Retail Partnership</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs uppercase tracking-widest text-gray-500 block mb-2">Message </label>
                            <textarea name="message" rows="5" required class="form-input bg-white resize-none" placeholder="Tell us how we can help you..."></textarea>
                        </div>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<a href="https://wa.me/2349012345678" target="_blank" class="whatsapp-float"><i class="fab fa-whatsapp"></i></a>

<?php require_once 'includes/footer.php'; ?>
