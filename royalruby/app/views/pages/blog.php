<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <p class="section-subtitle" style="color:var(--gold-light);">Insights &amp; Strategy</p>
    <h1>Our Blog</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item active">Blog</li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <?php if (empty($posts)) : ?>
    <div class="text-center py-5" style="color:var(--mid-grey);">
      <i class="bi bi-newspaper" style="font-size:3rem;opacity:.3;display:block;margin-bottom:1rem;"></i>
      <p>No blog posts published yet. Check back soon.</p>
    </div>
    <?php else : ?>
    <div class="row g-4">
      <?php foreach ($posts as $i => $post) : ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo ($i%3)*100; ?>">
        <article class="blog-card">
          <div class="blog-card-img">
            <?php if (!empty($post['featured_image'])) : ?>
              <img src="<?php echo APP_URL; ?>/storage/<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" loading="lazy"/>
            <?php else : ?>
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-pencil-square" style="font-size:2.5rem;color:rgba(201,168,76,.35);"></i>
              </div>
            <?php endif; ?>
            <span class="blog-cat"><?php echo htmlspecialchars(isset($post['category'])?$post['category']:'Business'); ?></span>
          </div>
          <div class="blog-body">
            <div class="blog-meta"><i class="bi bi-calendar3 me-1"></i><?php echo date('M j, Y',strtotime(isset($post['published_at'])&&$post['published_at']?$post['published_at']:$post['created_at'])); ?></div>
            <h5><a href="<?php echo APP_URL; ?>/blog/<?php echo htmlspecialchars($post['slug']); ?>" style="color:inherit;"><?php echo htmlspecialchars($post['title']); ?></a></h5>
            <p><?php echo htmlspecialchars(substr($post['excerpt'],0,110)); ?>...</p>
            <a href="<?php echo APP_URL; ?>/blog/<?php echo htmlspecialchars($post['slug']); ?>" class="service-link">Read Article <i class="bi bi-arrow-right"></i></a>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>