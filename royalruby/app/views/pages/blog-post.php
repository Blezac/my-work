<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <span class="blog-cat" style="position:static;display:inline-block;margin-bottom:1rem;"><?php echo htmlspecialchars(isset($post['category'])?$post['category']:'Business'); ?></span>
    <h1 style="font-size:clamp(1.6rem,3.5vw,2.6rem);"><?php echo htmlspecialchars($post['title']); ?></h1>
    <div style="color:rgba(255,255,255,.55);font-size:.85rem;margin-top:.75rem;">
      <i class="bi bi-calendar3 me-2"></i><?php echo date('F j, Y',strtotime(isset($post['published_at'])&&$post['published_at']?$post['published_at']:$post['created_at'])); ?>
    </div>
    <nav aria-label="breadcrumb" class="mt-2"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/blog">Blog</a></li>
      <li class="breadcrumb-item active"><?php echo htmlspecialchars(substr($post['title'],0,40)); ?>...</li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <?php if (!empty($post['featured_image'])) : ?>
        <img src="<?php echo APP_URL; ?>/storage/<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="width:100%;border-radius:12px;margin-bottom:2rem;" loading="lazy"/>
        <?php endif; ?>
        <div style="font-size:1rem;line-height:1.9;color:var(--dark-grey);"><?php echo $post['content']; ?></div>
        <hr style="margin:3rem 0;border-color:#F0EAEA;">
        <a href="<?php echo APP_URL; ?>/blog" class="btn-outline-ruby"><i class="bi bi-arrow-left me-2"></i>Back to Blog</a>
      </div>
      <div class="col-lg-4">
        <div style="background:linear-gradient(135deg,var(--ruby-dark),var(--charcoal));border-radius:12px;padding:1.75rem;text-align:center;">
          <i class="bi bi-calendar-check" style="font-size:2rem;color:var(--gold);margin-bottom:1rem;display:block;"></i>
          <h6 style="color:var(--white);margin-bottom:.5rem;">Ready to Grow?</h6>
          <p style="color:rgba(255,255,255,.62);font-size:.85rem;margin-bottom:1.25rem;">Book a free consultation today.</p>
          <a href="<?php echo APP_URL; ?>/contact" class="btn-gold" style="font-size:.8rem;padding:.6rem 1.25rem;">Book Now</a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>