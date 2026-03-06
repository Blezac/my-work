<?php
/**
 * Royal Ruby Exotic Place — Configuration
 * Loads .env file if present, then defines constants
 */

// ── Load .env ──────────────────────────────────────────────
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            list($key, $val) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($val);
        }
    }
}

// ── Database ───────────────────────────────────────────────
define('DB_HOST',    isset($_ENV['DB_HOST'])    ? $_ENV['DB_HOST']    : 'localhost');
define('DB_NAME',    isset($_ENV['DB_NAME'])    ? $_ENV['DB_NAME']    : 'royalruby_db');
define('DB_USER',    isset($_ENV['DB_USER'])    ? $_ENV['DB_USER']    : 'ruby');
define('DB_PASS',    isset($_ENV['DB_PASS'])    ? $_ENV['DB_PASS']    : 'Royalruby123!');

// ── Application ────────────────────────────────────────────
define('APP_URL',    isset($_ENV['APP_URL'])    ? rtrim($_ENV['APP_URL'], '/')    : 'http:ruby.local');
define('APP_ENV',    isset($_ENV['APP_ENV'])    ? $_ENV['APP_ENV']    : 'production');
define('APP_DEBUG',  isset($_ENV['APP_DEBUG'])  ? ($_ENV['APP_DEBUG'] === 'true') : false);

// ── Paths ──────────────────────────────────────────────────
define('UPLOAD_DIR', APP_ROOT . '/storage/uploads/');
define('UPLOAD_URL', APP_URL  . '/../storage/uploads/');

// ── Session ────────────────────────────────────────────────
define('SESSION_LIFETIME', isset($_ENV['SESSION_LIFETIME']) ? (int)$_ENV['SESSION_LIFETIME'] : 3600);
