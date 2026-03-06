<?php
/**
 * Royal Ruby — Header Partial
 * Variables expected: $settings (array), $pageTitle (string), $metaDesc (string)
 */
$siteName = isset($settings['site_name']) ? $settings['site_name'] : 'Royal Ruby Exotic Place';
$metaDesc = isset($metaDesc) ? $metaDesc : (isset($settings['site_description']) ? $settings['site_description'] : '');
$metaKeys = isset($settings['site_keywords']) ? $settings['site_keywords'] : '';
$title    = isset($pageTitle) ? $pageTitle : $siteName;
$appUrl   = APP_URL;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="description" content="<?php echo htmlspecialchars($metaDesc, ENT_QUOTES, 'UTF-8'); ?>"/>
  <meta name="keywords"    content="<?php echo htmlspecialchars($metaKeys,  ENT_QUOTES, 'UTF-8'); ?>"/>
  <meta property="og:title"       content="<?php echo htmlspecialchars($title,   ENT_QUOTES, 'UTF-8'); ?>"/>
  <meta property="og:description" content="<?php echo htmlspecialchars($metaDesc, ENT_QUOTES, 'UTF-8'); ?>"/>
  <meta property="og:type"        content="website"/>
  <meta name="theme-color"        content="#8B0000"/>
  <title><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></title>
  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css"/>
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"/>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="stylesheet"  href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Montserrat:wght@300;400;500;600;700&display=swap"/>
  <!-- Main CSS -->
  <link rel="stylesheet" href="<?php echo $appUrl; ?>/assets/css/main.css"/>
</head>
<body>

<!-- Page Loader -->
<div id="loader">
  <div class="loader-ring"></div>
  <div class="loader-text">Royal Ruby</div>
</div>

<!-- Navbar -->
<nav id="mainNav" class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo $appUrl; ?>/">
      <div>
        <div class="brand-text"><?php echo htmlspecialchars($siteName); ?></div>
        <div class="brand-sub">Premium Consulting</div>
      </div>
    </a>
    <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#navMenu"
            aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-1">
        <li class="nav-item"><a class="nav-link" href="<?php echo $appUrl; ?>/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $appUrl; ?>/about">About</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $appUrl; ?>/services">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $appUrl; ?>/hospitality">Hospitality</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $appUrl; ?>/blog">Blog</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $appUrl; ?>/testimonials">Testimonials</a></li>
        <li class="nav-item ms-2">
          <a class="nav-link btn-nav px-3 py-2 rounded" href="<?php echo $appUrl; ?>/contact">Contact Us</a>
        </li>
      </ul>
    </div>
  </div>
</nav>