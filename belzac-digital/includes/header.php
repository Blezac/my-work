<?php
/**
 * Belzac Digital - Reusable Header
 * $page_title must be set before including this file
 */
$page_title = isset($page_title) ? $page_title . ' | ' . SITE_NAME : SITE_NAME;
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Belzac Digital helps SMEs simplify their digital presence and messaging before scaling. Strategy-first digital marketing.">
    <title><?php echo htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8'); ?></title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">

    <script>
        tailwind.config = {
            prefix: 'tw-',
            corePlugins: { preflight: false }
        };
    </script>
</head>
<body>

<!-- Navigation -->
<header class="bd-header">
    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <!-- Logo Placeholder -->
            <a class="navbar-brand bd-brand" href="<?php echo SITE_URL; ?>/index.php">
                <div class="bd-logo-placeholder">
                    <span class="bd-logo-text">Belzac Digital</span>
                </div>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNav" aria-controls="mainNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav Links -->
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'index' ? 'active' : ''; ?>"
                           href="<?php echo SITE_URL; ?>/index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'about' ? 'active' : ''; ?>"
                           href="<?php echo SITE_URL; ?>/about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $current_page === 'services' ? 'active' : ''; ?>"
                           href="<?php echo SITE_URL; ?>/services.php">Services</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn bd-btn-primary" href="<?php echo SITE_URL; ?>/contact.php">Work With Us</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>
</header>
<!-- End Navigation -->
