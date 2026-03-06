<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <p class="section-subtitle" style="color:var(--gold-light);">Our Services</p>
    <h1><?php echo htmlspecialchars($service['title']); ?></h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/services">Services</a></li>
      <li class="breadcrumb-item active"><?php echo htmlspecialchars($service['title']); ?></li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <div class="row g-5 align-items-start">
      <div class="col-lg-8" data-aos="fade-right">
        <div style="background:linear-gradient(135deg,rgba(139,0,0,.05),rgba(201,168,76,.05));border-radius:16px;padding:3rem;margin-bottom:2rem;border:1px solid rgba(201,168,76,.12);">
          <i class="bi <?php echo htmlspecialchars($service['icon']); ?>" style="font-size:4rem;color:var(--ruby);display:block;margin-bottom:1.5rem;"></i>
          <h3 style="font-family:var(--serif);margin-bottom:1rem;"><?php echo htmlspecialchars($service['title']); ?></h3>
          <p style="color:var(--mid-grey);font-style:italic;font-size:1.05rem;"><?php echo htmlspecialchars($service['short_desc']); ?></p>
        </div>
        <p style="color:var(--mid-grey);line-height:1.88;font-size:.98rem;"><?php echo htmlspecialchars($service['full_desc']); ?></p>
        <?php
        $benefits = array();
        if (!empty($service['benefits'])) { $decoded = json_decode($service['benefits'],true); if (is_array($decoded)) $benefits = $decoded; }
        if (!empty($benefits)) : ?>
        <h4 style="font-family:var(--serif);margin:2rem 0 1.25rem;">Key Benefits</h4>
        <div class="row g-3">
          <?php foreach ($benefits as $b) : ?>
          <div class="col-md-6"><div class="d-flex gap-2">
            <i class="bi bi-check-circle-fill text-ruby" style="margin-top:.25rem;flex-shrink:0;"></i>
            <span style="font-size:.9rem;color:var(--dark-grey);"><?php echo htmlspecialchars($b); ?></span>
          </div></div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-4" data-aos="fade-left">
        <div style="background:linear-gradient(135deg,var(--ruby-dark),var(--charcoal));border-radius:16px;padding:2rem;color:var(--white);text-align:center;">
          <i class="bi bi-calendar-check" style="font-size:2.5rem;color:var(--gold);margin-bottom:1.25rem;display:block;"></i>
          <h5 style="color:var(--white);margin-bottom:.75rem;">Ready to Get Started?</h5>
          <p style="color:rgba(255,255,255,.65);font-size:.88rem;margin-bottom:1.5rem;">Book a free consultation today.</p>
          <a href="<?php echo APP_URL; ?>/contact" class="btn-gold" style="display:block;justify-content:center;">Book Consultation <i class="bi bi-arrow-right"></i></a>
          <hr style="border-color:rgba(255,255,255,.1);margin:1.5rem 0;">
          <a href="<?php echo APP_URL; ?>/services" class="btn-outline-white" style="display:block;justify-content:center;font-size:.8rem;">View All Services</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>