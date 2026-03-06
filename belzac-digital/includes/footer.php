<?php
/**
 * Belzac Digital - Reusable Footer
 */
?>

<!-- Footer -->
<footer class="bd-footer">
    <div class="container">

        <div class="row bd-footer-top">

            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6 mb-5 mb-lg-0">
                <div class="bd-footer-brand">
                    <span class="bd-footer-logo">Belzac Digital</span>
                </div>
                <p class="bd-footer-desc">
                    We help SMEs establish clarity in their digital presence before pursuing growth. Strategy first. Execution with purpose.
                </p>
            </div>

            <!-- Navigation Column -->
            <div class="col-lg-2 col-md-6 mb-5 mb-lg-0 offset-lg-1">
                <h6 class="bd-footer-heading">Navigation</h6>
                <ul class="bd-footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/index.php">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/about.php">About</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/services.php">Services</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/contact.php">Contact</a></li>
                </ul>
            </div>

            <!-- Services Column -->
            <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                <h6 class="bd-footer-heading">Services</h6>
                <ul class="bd-footer-links">
                    <li><a href="<?php echo SITE_URL; ?>/services.php#audit">Digital Clarity Audit</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/services.php#consulting">Website Structure Consulting</a></li>
                    <li><a href="<?php echo SITE_URL; ?>/services.php#content">Content Strategy Simplification</a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="col-lg-2 col-md-6 mb-5 mb-lg-0">
                <h6 class="bd-footer-heading">Connect</h6>
                <div class="bd-social-links">
                    <a href="<?php echo SOCIAL_LINKEDIN; ?>" aria-label="LinkedIn" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="<?php echo SOCIAL_TWITTER; ?>" aria-label="X / Twitter" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-x-twitter"></i>
                    </a>
                    <a href="<?php echo SOCIAL_INSTAGRAM; ?>" aria-label="Instagram" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
                <p class="bd-footer-email mt-3">
                    <a href="mailto:<?php echo CONTACT_EMAIL; ?>"><?php echo CONTACT_EMAIL; ?></a>
                </p>
            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="bd-footer-bottom">
            <p class="bd-copyright">
                &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
            </p>
            <p class="bd-footer-note">Built with clarity.</p>
        </div>

    </div>
</footer>
<!-- End Footer -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>

</body>
</html>
