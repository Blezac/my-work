<?php
/**
 * Belzac Digital - Contact Page
 * Includes: server-side validation, CSRF, honeypot, email sending
 */
require_once 'includes/config.php';

$form_success = false;
$form_errors  = [];
$form_data    = [
    'name'          => '',
    'email'         => '',
    'business_name' => '',
    'message'       => '',
];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF verification
    $submitted_token = isset($_POST['csrf_token']) ? trim($_POST['csrf_token']) : '';
    if (!verify_csrf($submitted_token)) {
        $form_errors['general'] = 'Your session has expired. Please refresh the page and try again.';
    }

    // Honeypot check: if filled, silently ignore
    $honeypot = isset($_POST['website_url']) ? trim($_POST['website_url']) : '';
    if (empty($form_errors) && $honeypot !== '') {
        // Bot detected: simulate success without sending email
        $form_success = true;
    }

    if (empty($form_errors) && !$form_success) {

        // Sanitize inputs
        $name          = sanitize_string($_POST['name'] ?? '');
        $email_raw     = sanitize_email($_POST['email'] ?? '');
        $business_name = sanitize_string($_POST['business_name'] ?? '');
        $message       = sanitize_string($_POST['message'] ?? '');

        // Populate form data for re-display on error
        $form_data = [
            'name'          => $name,
            'email'         => htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8'),
            'business_name' => $business_name,
            'message'       => $message,
        ];

        // Validation
        if (empty($name) || mb_strlen($name) < 2) {
            $form_errors['name'] = 'Please enter your full name (minimum 2 characters).';
        }

        if ($email_raw === false || empty($email_raw)) {
            $form_errors['email'] = 'Please enter a valid email address.';
        }

        if (empty($business_name) || mb_strlen($business_name) < 2) {
            $form_errors['business_name'] = 'Please enter your business name.';
        }

        if (empty($message) || mb_strlen($message) < 20) {
            $form_errors['message'] = 'Please provide a message of at least 20 characters.';
        }

        if (empty($form_errors)) {
            // Build email
            $to      = CONTACT_EMAIL;
            $subject = 'New Enquiry from ' . $name . ' - ' . SITE_NAME;

            $email_body  = "You have received a new enquiry via the Belzac Digital website.\n\n";
            $email_body .= "Name:          " . $name . "\n";
            $email_body .= "Email:         " . $email_raw . "\n";
            $email_body .= "Business Name: " . $business_name . "\n";
            $email_body .= "Message:\n" . $message . "\n\n";
            $email_body .= "---\nSent from: " . SITE_URL . "\n";

            // Headers: prevent injection by using sanitized email
            $headers  = "From: " . CONTACT_FROM_NAME . " <" . CONTACT_EMAIL . ">\r\n";
            $headers .= "Reply-To: " . $name . " <" . $email_raw . ">\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

            $mail_sent = mail($to, $subject, $email_body, $headers);

            if ($mail_sent) {
                $form_success = true;
                // Regenerate CSRF token after successful submission
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            } else {
                $form_errors['general'] = 'We were unable to send your message at this time. Please email us directly at ' . CONTACT_EMAIL . '.';
            }
        }
    }
}

$page_title = 'Contact';
include 'includes/header.php';
?>

<!-- Page Hero -->
<section class="bd-inner-hero">
    <div class="container">
        <div class="bd-breadcrumb">
            <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
            <span>/</span>
            <span>Contact</span>
        </div>
        <span class="bd-hero-label">Get In Touch</span>
        <h1>Start a conversation<br>about your business.</h1>
        <p style="color: rgba(255,255,255,0.65); max-width: 500px; font-size: 1.05rem; margin-top: 1rem; margin-bottom: 0;">
            Use the form below to introduce yourself and your business. We respond to all genuine enquiries within two business days.
        </p>
    </div>
</section>
<!-- End Page Hero -->

<!-- Contact Section -->
<section class="bd-section">
    <div class="container">
        <div class="bd-contact-grid">

            <!-- Contact Information -->
            <div class="bd-contact-info">
                <span class="bd-label">Contact Details</span>
                <span class="bd-divider"></span>
                <h3>We respond to every enquiry with care.</h3>
                <p style="font-size: 0.95rem; color: var(--bd-gray-500); margin-bottom: 2.5rem;">
                    We do not use automated responses or pass enquiries to a sales team. Every message is read by a person and replied to thoughtfully.
                </p>

                <div class="bd-contact-info-block">
                    <div class="bd-contact-info-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h6>Email</h6>
                        <p><a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a></p>
                    </div>
                </div>

                <div class="bd-contact-info-block">
                    <div class="bd-contact-info-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h6>Response Time</h6>
                        <p>Within 2 business days</p>
                    </div>
                </div>

                <div class="bd-contact-info-block">
                    <div class="bd-contact-info-icon">
                        <i class="fab fa-linkedin-in"></i>
                    </div>
                    <div>
                        <h6>LinkedIn</h6>
                        <p><a href="<?php echo SOCIAL_LINKEDIN; ?>" target="_blank" rel="noopener noreferrer">Belzac Digital</a></p>
                    </div>
                </div>

                <!-- WhatsApp Card -->
                <a href="https://wa.me/<?php echo WHATSAPP_NUMBER; ?>?text=<?php echo rawurlencode(WHATSAPP_MESSAGE); ?>"
                   class="bd-whatsapp-card"
                   target="_blank"
                   rel="noopener noreferrer">
                    <div class="bd-whatsapp-card-icon">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <div class="bd-whatsapp-card-text">
                        <h6>Prefer to chat?</h6>
                        <p>Message us directly on WhatsApp</p>
                    </div>
                </a>

                <div style="margin-top: 3rem; padding: 1.5rem; background: var(--bd-off-white); border-radius: var(--bd-radius-lg); border: 1px solid var(--bd-gray-300);">
                    <h6 style="font-size: 0.75rem; letter-spacing: 0.12em; text-transform: uppercase; color: var(--bd-navy-light); margin-bottom: 0.5rem; font-weight: 600;">Before You Write</h6>
                    <p style="font-size: 0.875rem; color: var(--bd-gray-500); margin: 0; line-height: 1.65;">
                        The more you can tell us about your current situation and what you are hoping to achieve, the more useful our response will be. There is no need to have it all figured out.
                    </p>
                </div>
            </div>
            <!-- End Contact Information -->

            <!-- Contact Form -->
            <div class="bd-form-card">

                <?php if ($form_success): ?>
                    <div class="bd-alert bd-alert-success">
                        <strong>Message received.</strong> Thank you for reaching out. We will review your enquiry and respond within two business days.
                    </div>
                <?php endif; ?>

                <?php if (!empty($form_errors['general'])): ?>
                    <div class="bd-alert bd-alert-error">
                        <?php echo htmlspecialchars($form_errors['general'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <?php if (!$form_success): ?>

                <form id="bd-contact-form" method="POST" action="<?php echo SITE_URL; ?>/contact.php" novalidate>

                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">

                    <!-- Honeypot Field (hidden from humans) -->
                    <div class="bd-form-honeypot" aria-hidden="true">
                        <label for="website_url">Website URL</label>
                        <input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off">
                    </div>

                    <!-- Name -->
                    <div class="bd-field-group">
                        <label class="bd-form-label" for="name">Full Name <span style="color: #dc2626;">*</span></label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="bd-form-control <?php echo isset($form_errors['name']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($form_data['name'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Your full name"
                            required
                            maxlength="120"
                        >
                        <?php if (isset($form_errors['name'])): ?>
                            <span class="bd-field-error"><?php echo htmlspecialchars($form_errors['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="bd-field-group">
                        <label class="bd-form-label" for="email">Email Address <span style="color: #dc2626;">*</span></label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="bd-form-control <?php echo isset($form_errors['email']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($form_data['email'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="you@yourbusiness.com"
                            required
                            maxlength="254"
                        >
                        <?php if (isset($form_errors['email'])): ?>
                            <span class="bd-field-error"><?php echo htmlspecialchars($form_errors['email'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Business Name -->
                    <div class="bd-field-group">
                        <label class="bd-form-label" for="business_name">Business Name <span style="color: #dc2626;">*</span></label>
                        <input
                            type="text"
                            id="business_name"
                            name="business_name"
                            class="bd-form-control <?php echo isset($form_errors['business_name']) ? 'is-invalid' : ''; ?>"
                            value="<?php echo htmlspecialchars($form_data['business_name'], ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="Your business or organisation name"
                            required
                            maxlength="200"
                        >
                        <?php if (isset($form_errors['business_name'])): ?>
                            <span class="bd-field-error"><?php echo htmlspecialchars($form_errors['business_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Message -->
                    <div class="bd-field-group">
                        <label class="bd-form-label" for="message">Your Message <span style="color: #dc2626;">*</span></label>
                        <textarea
                            id="message"
                            name="message"
                            class="bd-form-control <?php echo isset($form_errors['message']) ? 'is-invalid' : ''; ?>"
                            placeholder="Tell us about your business, where you are currently with your digital presence, and what you are hoping to achieve."
                            required
                            maxlength="3000"
                            rows="6"
                        ><?php echo htmlspecialchars($form_data['message'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <?php if (isset($form_errors['message'])): ?>
                            <span class="bd-field-error"><?php echo htmlspecialchars($form_errors['message'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Submit -->
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                        <button type="submit" class="bd-btn-primary" style="cursor: pointer;">Send Enquiry</button>
                        <p style="font-size: 0.75rem; color: var(--bd-gray-500); margin: 0;">
                            Fields marked <span style="color: #dc2626;">*</span> are required.
                        </p>
                    </div>

                </form>

                <?php endif; ?>

            </div>
            <!-- End Contact Form -->

        </div>
    </div>
</section>
<!-- End Contact Section -->

<?php include 'includes/footer.php'; ?>
