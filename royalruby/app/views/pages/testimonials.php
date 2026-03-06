<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <p class="section-subtitle" style="color:var(--gold-light);">Client Voices</p>
    <h1>What Our Clients Say</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item active">Testimonials</li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <div class="row g-4">
      <?php if (!empty($testimonials)) :
      foreach ($testimonials as $i => $t) : ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i%3)*100; ?>">
        <div class="tcard">
          <div class="quote">&ldquo;</div>
          <div class="stars"><?php for ($s=0;$s<(int)$t['rating'];$s++) echo '<i class="bi bi-star-fill"></i>'; ?></div>
          <p class="review"><?php echo htmlspecialchars($t['review']); ?></p>
          <div class="d-flex align-items-center gap-3">
            <div class="t-avatar"><?php echo strtoupper(substr($t['client_name'],0,1)); ?></div>
            <div>
              <div class="t-name"><?php echo htmlspecialchars($t['client_name']); ?></div>
              <div class="t-title"><?php echo htmlspecialchars(isset($t['client_title'])?$t['client_title']:''); ?></div>
              <div class="t-company"><?php echo htmlspecialchars(isset($t['company'])?$t['company']:''); ?></div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; else : ?>
      <div class="col-12 text-center py-5" style="color:var(--mid-grey);">No testimonials yet.</div>
      <?php endif; ?>
    </div>
  </div>
</section>
<section class="cta-section section-pad">
  <div class="container text-center position-relative" style="z-index:2;">
    <h2 style="font-family:var(--serif);font-size:clamp(1.8rem,3.5vw,2.8rem);color:var(--white);margin-bottom:1rem;">Become Our Next Success Story</h2>
    <a href="<?php echo APP_URL; ?>/contact" class="btn-gold">Start Your Journey <i class="bi bi-arrow-right"></i></a>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>