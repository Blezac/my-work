<?php
/**
 * Royal Ruby Exotic Place — Front Controller
 * All requests are routed here via .htaccess
 */

// ── Bootstrap ──────────────────────────────────────────────
define('APP_ROOT', __DIR__);

require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/core/Model.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Router.php';

// ── Session ────────────────────────────────────────────────
session_set_cookie_params([
    'lifetime' => SESSION_LIFETIME,
    'path'     => '/',
    'secure'   => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// ── Error Reporting ────────────────────────────────────────
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// ── Security Headers ───────────────────────────────────────
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// ── Derive base path from APP_URL ──────────────────────────
$parsed   = parse_url(APP_URL);
$basePath = isset($parsed['path']) ? $parsed['path'] : '';

// ── Router ─────────────────────────────────────────────────
$router = new Router($basePath);

// Public — GET
$router->get('/',               'HomeController', 'index');
$router->get('/about',          'HomeController', 'about');
$router->get('/services',       'HomeController', 'services');
$router->get('/services/{slug}','HomeController', 'serviceDetail');
$router->get('/hospitality',    'HomeController', 'hospitality');
$router->get('/testimonials',   'HomeController', 'testimonials');
$router->get('/blog',           'HomeController', 'blog');
$router->get('/blog/{slug}',    'HomeController', 'blogPost');
$router->get('/contact',        'HomeController', 'contact');
$router->get('/sitemap.xml',    'HomeController', 'sitemap');

// Public — POST
$router->post('/contact',       'HomeController', 'submitContact');
$router->post('/subscribe',     'HomeController', 'subscribe');

// Admin — GET
$router->get('/admin',                           'AdminController', 'loginPage');
$router->get('/admin/login',                     'AdminController', 'loginPage');
$router->get('/admin/logout',                    'AdminController', 'logout');
$router->get('/admin/dashboard',                 'AdminController', 'dashboard');

$router->get('/admin/services',                  'AdminController', 'services');
$router->get('/admin/services/create',           'AdminController', 'serviceCreate');
$router->get('/admin/services/{id}/edit',        'AdminController', 'serviceEdit');
$router->get('/admin/services/{id}/delete',      'AdminController', 'serviceDelete');

$router->get('/admin/blog',                      'AdminController', 'blog');
$router->get('/admin/blog/create',               'AdminController', 'blogCreate');
$router->get('/admin/blog/{id}/edit',            'AdminController', 'blogEdit');
$router->get('/admin/blog/{id}/delete',          'AdminController', 'blogDelete');

$router->get('/admin/testimonials',              'AdminController', 'testimonials');
$router->get('/admin/testimonials/create',       'AdminController', 'testimonialCreate');
$router->get('/admin/testimonials/{id}/edit',    'AdminController', 'testimonialEdit');
$router->get('/admin/testimonials/{id}/delete',  'AdminController', 'testimonialDelete');

$router->get('/admin/contacts',                  'AdminController', 'contacts');
$router->get('/admin/contacts/{id}',             'AdminController', 'contactView');
$router->get('/admin/contacts/{id}/delete',      'AdminController', 'contactDelete');

$router->get('/admin/gallery',                   'AdminController', 'gallery');
$router->get('/admin/gallery/{id}/delete',       'AdminController', 'galleryDelete');

$router->get('/admin/settings',                  'AdminController', 'settings');
$router->get('/admin/newsletter',                'AdminController', 'newsletter');

// Admin — POST
$router->post('/admin/login',                    'AdminController', 'loginPost');
$router->post('/admin/services/store',           'AdminController', 'serviceStore');
$router->post('/admin/services/{id}/update',     'AdminController', 'serviceUpdate');
$router->post('/admin/blog/store',               'AdminController', 'blogStore');
$router->post('/admin/blog/{id}/update',         'AdminController', 'blogUpdate');
$router->post('/admin/testimonials/store',       'AdminController', 'testimonialStore');
$router->post('/admin/testimonials/{id}/update', 'AdminController', 'testimonialUpdate');
$router->post('/admin/gallery/store',            'AdminController', 'galleryStore');
$router->post('/admin/settings/update',          'AdminController', 'settingsUpdate');

// ── Dispatch ───────────────────────────────────────────────
$router->dispatch();
