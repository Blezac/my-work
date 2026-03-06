<?php
/**
 * Belzac Digital - Configuration File
 */

// Site settings
define('SITE_NAME', 'Belzac Digital');
define('SITE_URL', 'http://belzac.local');
define('SITE_TAGLINE', 'Digital Growth For Your Business Made Simple');

// Contact settings
define('CONTACT_EMAIL', 'hello@belzacdigital.com');
define('CONTACT_FROM_NAME', 'Belzac Digital Website');

// Social links (update with real URLs)
define('SOCIAL_LINKEDIN', '#');
define('SOCIAL_TWITTER', '#');
define('SOCIAL_INSTAGRAM', '#');


define('WHATSAPP_NUMBER', '2348149835307'); 
define('WHATSAPP_MESSAGE', 'Hello Belzac Digital, I would like to discuss my digital presence.');

// CSRF token session
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Helper: sanitize string input
function sanitize_string(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

// Helper: validate email and prevent header injection
function sanitize_email(string $email): string|false {
    $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/[\r\n]/', $email)) {
        return $email;
    }
    return false;
}

// Helper: verify CSRF token
function verify_csrf(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
