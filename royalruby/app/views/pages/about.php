<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <p class="section-subtitle" style="color:var(--gold-light);">Our Story</p>
    <h1>About Royal Ruby Exotic Place</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item active">About Us</li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6" data-aos="fade-right">
        <p class="section-subtitle text-ruby">Who We Are</p>
        <h2 class="section-title">Where Strategic Brilliance Meets Luxury Excellence</h2>
        <p class="lead"><?php echo htmlspecialchars(isset($settings['about_intro']) ? $settings['about_intro'] : 'Royal Ruby Exotic Place stands at the intersection of strategic brilliance and luxury excellence.'); ?></p>
        <p style="color:var(--mid-grey);line-height:1.85;">Founded with a singular vision — to provide Nigerian and international businesses with world-class consulting services that combine rigorous strategy with premium delivery standards.</p>
        <div class="d-flex gap-3 mt-4">
          <a href="<?php echo APP_URL; ?>/services" class="btn-ruby">Our Services <i class="bi bi-arrow-right"></i></a>
          <a href="<?php echo APP_URL; ?>/contact"  class="btn-outline-ruby">Get In Touch</a>
        </div>
      </div>
      <div class="col-lg-6" data-aos="fade-left">
        <div style="background:linear-gradient(135deg,#F9F5F5,#FAF7F0);border:1px solid rgba(201,168,76,.2);border-radius:16px;padding:2.5rem;">
          <div class="row g-4 text-center">
            <?php
            $kpis = array(
              array('200+','Clients Served'),array('&#x20A6;2B+','Revenue Generated'),
              array('98%','Client Retention'),array('15+','Industries Served'),
              array('8yrs','Of Excellence'),array('50+','Expert Consultants'),
            );
            foreach ($kpis as $k) { ?>
            <div class="col-6">
              <div style="font-family:var(--serif);font-size:2rem;font-weight:700;color:var(--ruby);"><?php echo $k[0]; ?></div>
              <div style="font-size:.75rem;text-transform:uppercase;letter-spacing:1.5px;color:var(--mid-grey);"><?php echo $k[1]; ?></div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section-pad bg-subtle">
  <div class="container">
    <div class="row g-4">
      <?php
      $pillars = array(
        array('bi-crosshair','Our Mission','To empower ambitious businesses with precision-engineered marketing strategies and luxury-standard service delivery.','var(--ruby)'),
        array('bi-eye','Our Vision','To be the definitive consulting partner for Africa\'s fastest-growing businesses — recognized globally for strategic excellence.','var(--gold)'),
        array('bi-gem','Our Values','Excellence in every engagement. Integrity in every recommendation. Innovation in every strategy.','var(--charcoal)'),
      );
      foreach ($pillars as $i => $p) { ?>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $i*100; ?>">
        <div class="service-card text-center">
          <div class="service-icon" style="margin:0 auto 1.5rem;"><i class="bi <?php echo $p[0]; ?>" style="color:<?php echo $p[3]; ?>;"></i></div>
          <h4><?php echo $p[1]; ?></h4><p><?php echo $p[2]; ?></p>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>
<section class="cta-section section-pad">
  <div class="container text-center position-relative" style="z-index:2;" data-aos="fade-up">
    <h2 style="font-family:var(--serif);font-size:clamp(1.8rem,3.5vw,2.8rem);color:var(--white);margin-bottom:1rem;">Ready to Start Your Growth Journey?</h2>
    <p style="color:rgba(255,255,255,.7);max-width:500px;margin:0 auto 2.5rem;">Talk to a Royal Ruby senior consultant today.</p>
    <a href="<?php echo APP_URL; ?>/contact" class="btn-gold">Book Your Consultation <i class="bi bi-arrow-right"></i></a>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>