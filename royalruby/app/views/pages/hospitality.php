<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <p class="section-subtitle" style="color:var(--gold-light);">Premium Experiences</p>
    <h1>Hospitality Consulting</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item active">Hospitality</li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-7" data-aos="fade-up">
        <p class="section-subtitle text-ruby">Redefining Excellence</p>
        <h2 class="section-title" style="display:inline-block;">Where Every Detail is a Statement of Excellence</h2>
        <p class="lead mt-2">Royal Ruby engineers premium hospitality experiences that transform your brand into a destination of choice.</p>
      </div>
    </div>
    <div class="row g-4">
      <?php
      $offerings = array(
        array('bi-calendar-event-fill','Event Planning & Management','From intimate boardroom gatherings to grand gala events, we manage every detail with precision.'),
        array('bi-house-heart-fill','Venue Consulting','We identify and optimize venues to ensure your events align perfectly with your brand positioning.'),
        array('bi-person-badge-fill','Staff Training & Development','Comprehensive programs that elevate your team to world-class hospitality standards.'),
        array('bi-star-fill','VIP Experience Curation','Bespoke luxury experiences crafted for your most valuable clients and stakeholders.'),
        array('bi-diagram-3-fill','Guest Journey Mapping','Systematic design of every touchpoint to ensure seamless, memorable client interactions.'),
        array('bi-patch-check-fill','Hospitality Brand Standards','Development of proprietary service protocols that define your hospitality brand identity.'),
      );
      foreach ($offerings as $i => $o) { ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $i*80; ?>">
        <div class="service-card">
          <div class="service-icon"><i class="bi <?php echo $o[0]; ?>"></i></div>
          <h4><?php echo $o[1]; ?></h4><p><?php echo $o[2]; ?></p>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>
<section class="cta-section section-pad">
  <div class="container text-center position-relative" style="z-index:2;" data-aos="fade-up">
    <h2 style="font-family:var(--serif);font-size:clamp(1.8rem,3.5vw,2.8rem);color:var(--white);margin-bottom:1rem;">Ready to Create Unforgettable Experiences?</h2>
    <p style="color:rgba(255,255,255,.7);max-width:500px;margin:0 auto 2.5rem;">Contact our team to discuss your hospitality requirements.</p>
    <a href="<?php echo APP_URL; ?>/contact" class="btn-gold">Discuss Your Event <i class="bi bi-arrow-right"></i></a>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>