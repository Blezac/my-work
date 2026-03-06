<?php
/**
 * Royal Ruby — Footer Partial
 * Variables expected: $settings (array)
 */
$settings  = isset($settings) ? $settings : array();
$appUrl    = APP_URL;
$siteName  = isset($settings['site_name'])    ? $settings['site_name']    : 'Royal Ruby Exotic Place';
$siteAbout = isset($settings['about_intro'])  ? $settings['about_intro']  : 'Royal Ruby Exotic Place — where strategy meets luxury.';
$siteAddr  = isset($settings['site_address']) ? $settings['site_address'] : 'Victoria Island, Lagos, Nigeria';
$sitePhone = isset($settings['site_phone'])   ? $settings['site_phone']   : '+234 800 000 0000';
$siteEmail = isset($settings['site_email'])   ? $settings['site_email']   : 'info@royalruby.com';
$fbUrl     = isset($settings['facebook_url'])  ? $settings['facebook_url']  : '#';
$igUrl     = isset($settings['instagram_url']) ? $settings['instagram_url'] : '#';
$liUrl     = isset($settings['linkedin_url'])  ? $settings['linkedin_url']  : '#';
$twUrl     = isset($settings['twitter_url'])   ? $settings['twitter_url']   : '#';
?>

<!-- Newsletter Bar -->
<section class="newsletter-bar">
  <div class="container">
    <div class="row align-items-center g-3">
      <div class="col-md-6">
        <h4 class="mb-1">Stay Ahead of the Curve</h4>
        <p class="mb-0">Get exclusive marketing insights and business growth strategies.</p>
      </div>
      <div class="col-md-6">
        <form id="newsletterForm" action="<?php echo $appUrl; ?>/subscribe" method="POST">
          <div class="input-group nl-group">
            <input type="email" name="email" class="form-control"
                   placeholder="Your business email address" required/>
            <button type="submit" class="btn-ruby px-3">
              Subscribe <i class="bi bi-arrow-right"></i>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Footer -->
<footer id="footer">
  <div class="footer-top">
    <div class="container">
      <div class="row g-5">

        <!-- Brand -->
        <div class="col-lg-4">
          <div class="footer-brand-name"><?php echo htmlspecialchars($siteName); ?></div>
          <div class="footer-brand-tagline">Premium Consulting Firm</div>
          <p class="footer-desc"><?php echo htmlspecialchars($siteAbout); ?></p>
          <div class="footer-social">
            <a href="<?php echo htmlspecialchars($fbUrl); ?>" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
            <a href="<?php echo htmlspecialchars($igUrl); ?>" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="<?php echo htmlspecialchars($liUrl); ?>" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
            <a href="<?php echo htmlspecialchars($twUrl); ?>" aria-label="Twitter/X"><i class="bi bi-twitter-x"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-4">
          <h6 class="footer-heading">Quick Links</h6>
          <ul class="footer-links">
            <li><a href="<?php echo $appUrl; ?>/"><i class="bi bi-chevron-right"></i>Home</a></li>
            <li><a href="<?php echo $appUrl; ?>/about"><i class="bi bi-chevron-right"></i>About Us</a></li>
            <li><a href="<?php echo $appUrl; ?>/services"><i class="bi bi-chevron-right"></i>Services</a></li>
            <li><a href="<?php echo $appUrl; ?>/hospitality"><i class="bi bi-chevron-right"></i>Hospitality</a></li>
            <li><a href="<?php echo $appUrl; ?>/blog"><i class="bi bi-chevron-right"></i>Blog</a></li>
            <li><a href="<?php echo $appUrl; ?>/contact"><i class="bi bi-chevron-right"></i>Contact</a></li>
          </ul>
        </div>

        <!-- Services -->
        <div class="col-lg-3 col-md-4">
          <h6 class="footer-heading">Our Services</h6>
          <ul class="footer-links">
            <li><a href="<?php echo $appUrl; ?>/services"><i class="bi bi-chevron-right"></i>Marketing & Acquisition</a></li>
            <li><a href="<?php echo $appUrl; ?>/services"><i class="bi bi-chevron-right"></i>Business Development</a></li>
            <li><a href="<?php echo $appUrl; ?>/services"><i class="bi bi-chevron-right"></i>Revenue Growth</a></li>
            <li><a href="<?php echo $appUrl; ?>/services"><i class="bi bi-chevron-right"></i>Brand Positioning</a></li>
            <li><a href="<?php echo $appUrl; ?>/hospitality"><i class="bi bi-chevron-right"></i>Hospitality Consulting</a></li>
          </ul>
        </div>

        <!-- Contact -->
        <div class="col-lg-3 col-md-4">
          <h6 class="footer-heading">Contact</h6>
          <div class="footer-contact-item">
            <i class="bi bi-geo-alt-fill"></i>
            <span><?php echo htmlspecialchars($siteAddr); ?></span>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-telephone-fill"></i>
            <span><?php echo htmlspecialchars($sitePhone); ?></span>
          </div>
          <div class="footer-contact-item">
            <i class="bi bi-envelope-fill"></i>
            <span><?php echo htmlspecialchars($siteEmail); ?></span>
          </div>
          <div class="mt-3">
            <a href="<?php echo $appUrl; ?>/contact" class="btn-ruby"
               style="padding:.6rem 1.5rem; font-size:.8rem;">
              Book Consultation
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  <hr class="footer-divider"/>

  <div class="footer-bottom">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($siteName); ?>. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <p class="mb-0">
            <a href="<?php echo $appUrl; ?>/sitemap.xml"
               style="color:rgba(255,255,255,.3); font-size:.78rem;">Sitemap</a>
            &nbsp;|&nbsp;
            <a href="#" style="color:rgba(255,255,255,.3); font-size:.78rem;">Privacy Policy</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>

<!-- Lightbox -->
<div id="galleryLightbox">
  <button class="lb-close" aria-label="Close">&times;</button>
  <img id="lbImage" src="" alt="Gallery image"
       style="max-width:90%; max-height:85vh; border-radius:8px; box-shadow:0 8px 40px rgba(0,0,0,.5);"/>
</div>

<!-- WhatsApp Float -->
<a href="https://wa.me/2348000000000" class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
  <i class="bi bi-whatsapp"></i>
</a>

<!-- Scroll to Top -->
<button id="scrollTop" title="Back to top" aria-label="Scroll to top">
  <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<!-- Main JS -->
<script src="<?php echo $appUrl; ?>/assets/js/main.js"></script>
</body>
</html>