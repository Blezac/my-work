<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <p class="section-subtitle" style="color:var(--gold-light);">What We Offer</p>
    <h1>Our Services</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item active">Services</li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <?php if (!empty($services)) :
    foreach ($services as $i => $service) :
    $benefits = array();
    if (!empty($service['benefits'])) { $decoded = json_decode($service['benefits'], true); if (is_array($decoded)) $benefits = $decoded; }
    ?>
    <div class="row align-items-center g-5 mb-5 <?php echo $i%2!==0?'flex-lg-row-reverse':''; ?>" data-aos="fade-up">
      <div class="col-lg-6">
        <div style="background:linear-gradient(135deg,rgba(139,0,0,.06),rgba(201,168,76,.06));border-radius:16px;padding:3rem;min-height:280px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(201,168,76,.12);">
          <i class="bi <?php echo htmlspecialchars($service['icon']); ?>" style="font-size:6rem;color:var(--ruby);opacity:.55;"></i>
        </div>
      </div>
      <div class="col-lg-6">
        <p class="section-subtitle text-ruby">Service <?php echo str_pad($i+1,2,'0',STR_PAD_LEFT); ?></p>
        <h3 style="font-family:var(--serif);font-size:1.85rem;margin-bottom:1rem;"><?php echo htmlspecialchars($service['title']); ?></h3>
        <p style="color:var(--mid-grey);line-height:1.82;margin-bottom:1.25rem;"><?php echo htmlspecialchars($service['full_desc']); ?></p>
        <?php if (!empty($benefits)) : ?>
        <div class="mb-3">
          <?php foreach ($benefits as $b) : ?>
          <div class="d-flex gap-2 align-items-start mb-2">
            <i class="bi bi-check-circle-fill text-ruby" style="margin-top:.22rem;flex-shrink:0;font-size:.88rem;"></i>
            <span style="font-size:.9rem;color:var(--dark-grey);"><?php echo htmlspecialchars($b); ?></span>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <a href="<?php echo APP_URL; ?>/contact" class="btn-ruby">Get Started <i class="bi bi-arrow-right"></i></a>
      </div>
    </div>
    <?php if ($i < count($services)-1) : ?><hr style="border-color:#F0EAEA;margin:2rem 0;"><?php endif; ?>
    <?php endforeach; else : ?>
    <div class="text-center py-5" style="color:var(--mid-grey);">
      <i class="bi bi-briefcase" style="font-size:3rem;opacity:.3;display:block;margin-bottom:1rem;"></i>
      <p>No services published yet. Check back soon.</p>
    </div>
    <?php endif; ?>
  </div>
</section>
<section class="cta-section section-pad">
  <div class="container text-center position-relative" style="z-index:2;" data-aos="fade-up">
    <h2 style="font-family:var(--serif);font-size:clamp(1.8rem,3.5vw,2.8rem);color:var(--white);margin-bottom:1rem;">Not Sure Which Service You Need?</h2>
    <p style="color:rgba(255,255,255,.7);max-width:500px;margin:0 auto 2.5rem;">Book a free discovery call and we'll recommend the right solutions for your business.</p>
    <a href="<?php echo APP_URL; ?>/contact" class="btn-gold">Free Discovery Call <i class="bi bi-telephone"></i></a>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>