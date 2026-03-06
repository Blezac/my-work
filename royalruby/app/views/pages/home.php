<?php require APP_ROOT . '/app/views/partials/header.php'; ?>

<!-- ═══ HERO ═══════════════════════════════════════════════ -->
<section class="hero">
  <div class="hero-particles"></div>
  <div class="container">
    <div class="row align-items-center" style="min-height:100vh; padding:5rem 0;">

      <!-- Left -->
      <div class="col-lg-7 hero-content" data-aos="fade-right">
        <div class="hero-eyebrow">
          <span style="display:inline-block;width:28px;height:1px;background:var(--gold)"></span>
          Premium Marketing &amp; Business Consulting
          <span style="display:inline-block;width:28px;height:1px;background:var(--gold)"></span>
        </div>

        <h1 class="hero-title">
          <?php
          $headline = isset($settings['hero_headline'])
              ? $settings['hero_headline']
              : 'Elevating Brands. Maximizing Revenue. Delivering Premium Experiences.';
          $parts = explode('.', $headline, 3);
          foreach ($parts as $i => $part) {
              $part = trim($part);
              if ($part === '') { continue; }
              if ($i === 1) {
                  echo '<span class="accent">' . htmlspecialchars($part) . '.</span>';
              } else {
                  echo htmlspecialchars($part);
                  if ($i < 2) { echo '. '; }
              }
          }
          ?>
        </h1>

        <p class="hero-subtitle">
          <?php echo htmlspecialchars(
              isset($settings['hero_subheadline'])
                  ? $settings['hero_subheadline']
                  : 'We transform businesses through strategic marketing, elite consulting, and luxury hospitality solutions.'
          ); ?>
        </p>

        <div class="d-flex flex-wrap gap-3">
          <a href="<?php echo APP_URL; ?>/contact"  class="btn-gold">Book a Consultation <i class="bi bi-arrow-right"></i></a>
          <a href="<?php echo APP_URL; ?>/services" class="btn-outline-white">Our Services</a>
        </div>

        <div class="hero-stats">
          <?php
          $stats = array(
              array('200+', 'Clients Served',    200, '+'),
              array('8',    'Years Excellence',  8,   'yrs'),
              array('340%', 'Avg Lead Growth',   340, '%'),
              array('15+',  'Industries',        15,  '+'),
          );
          foreach ($stats as $s) {
          ?>
          <div class="hero-stat">
            <div class="hero-stat-num"
                 data-counter
                 data-target="<?php echo $s[2]; ?>"
                 data-suffix="<?php echo htmlspecialchars($s[3]); ?>">
              <?php echo $s[0]; ?>
            </div>
            <div class="hero-stat-label"><?php echo $s[1]; ?></div>
          </div>
          <?php } ?>
        </div>
      </div>

      <!-- Right panel -->
      <div class="col-lg-5 d-none d-lg-block" data-aos="fade-left">
        <div style="padding:2rem;">
          <div style="background:linear-gradient(135deg,rgba(201,168,76,.12),rgba(139,0,0,.18));border:1px solid rgba(201,168,76,.22);border-radius:18px;padding:3rem 2rem;text-align:center;">
            <i class="bi bi-gem" style="font-size:5rem;color:var(--gold);display:block;margin-bottom:1.5rem;"></i>
            <p style="color:rgba(255,255,255,.78);font-style:italic;font-family:var(--serif);font-size:1.12rem;line-height:1.72;">
              &ldquo;We don&rsquo;t just consult &mdash; we partner in your ascent to market leadership.&rdquo;
            </p>
            <div style="width:40px;height:2px;background:var(--gold);margin:1.5rem auto;"></div>
            <p style="color:var(--gold);font-size:.75rem;letter-spacing:2px;text-transform:uppercase;font-weight:600;">
              Royal Ruby Exotic Place
            </p>
          </div>
          <!-- Badge -->
          <div style="position:absolute;right:0;bottom:40px;background:linear-gradient(135deg,var(--gold-dark),var(--gold));color:var(--black);padding:1rem 1.4rem;border-radius:var(--radius);font-weight:700;font-size:.82rem;text-align:center;box-shadow:var(--shadow-lg);">
            <div style="font-size:1.4rem;margin-bottom:.25rem;">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
            Award Winning<br/>
            <small>Consulting Firm</small>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══ SERVICES ═══════════════════════════════════════════ -->
<section class="section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-7" data-aos="fade-up">
        <p class="section-subtitle text-ruby">What We Do</p>
        <h2 class="section-title" style="display:inline-block;">
          Services Designed for <em>Results</em>
        </h2>
        <p class="lead mt-2">From brand elevation to revenue transformation, our services deliver measurable, lasting impact.</p>
      </div>
    </div>

    <div class="row g-4">
      <?php
      if (!empty($services)) {
          foreach ($services as $i => $service) {
      ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $i * 80; ?>">
        <div class="service-card">
          <div class="service-icon">
            <i class="bi <?php echo htmlspecialchars($service['icon']); ?>"></i>
          </div>
          <h4><?php echo htmlspecialchars($service['title']); ?></h4>
          <p><?php echo htmlspecialchars($service['short_desc']); ?></p>
          <a href="<?php echo APP_URL; ?>/services/<?php echo htmlspecialchars($service['slug']); ?>"
             class="service-link">
            Learn More <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>
      <?php
          }
      } else {
          // Fallback static cards
          $defaults = array(
              array('bi-megaphone-fill', 'Marketing & Client Acquisition', 'Precision-targeted strategies that attract and retain high-value clients.'),
              array('bi-graph-up-arrow', 'Business Development',           'Strategic frameworks that open new markets and forge partnerships.'),
              array('bi-currency-dollar','Revenue Growth Strategies',       'Systematic optimization that grows your bottom line measurably.'),
              array('bi-stars',          'Brand Positioning & Promotion',   'Establish authority and command premium pricing in your market.'),
              array('bi-gem',            'Hospitality Consulting',          'Transform ordinary interactions into extraordinary experiences.'),
          );
          foreach ($defaults as $i => $d) {
      ?>
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?php echo $i * 80; ?>">
        <div class="service-card">
          <div class="service-icon"><i class="bi <?php echo $d[0]; ?>"></i></div>
          <h4><?php echo $d[1]; ?></h4>
          <p><?php echo $d[2]; ?></p>
          <a href="<?php echo APP_URL; ?>/services" class="service-link">Learn More <i class="bi bi-arrow-right"></i></a>
        </div>
      </div>
      <?php
          }
      }
      ?>
    </div>

    <div class="text-center mt-5" data-aos="fade-up">
      <a href="<?php echo APP_URL; ?>/services" class="btn-ruby">
        Explore All Services <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ═══ ABOUT STRIP ════════════════════════════════════════ -->
<section class="dark-strip section-pad">
  <div class="container">
    <div class="row align-items-center g-5">

      <div class="col-lg-6" data-aos="fade-right">
        <p class="section-subtitle" style="color:var(--gold-light);">Who We Are</p>
        <h2 class="section-title">
          The Definitive Consulting Partner for Ambitious Brands
        </h2>
        <p style="color:rgba(255,255,255,.7);line-height:1.82;margin-bottom:1.25rem;">
          <?php echo htmlspecialchars(
              isset($settings['about_intro'])
                  ? $settings['about_intro']
                  : 'Royal Ruby Exotic Place stands at the intersection of strategic brilliance and luxury excellence.'
          ); ?>
        </p>
        <?php
        $points = array(
            'Deep industry expertise across 15+ sectors',
            'Proven revenue acceleration methodologies',
            'Luxury-standard client experience from day one',
            "Nigeria's most trusted premium consulting brand",
        );
        foreach ($points as $point) {
        ?>
        <div class="d-flex gap-2 mb-2" style="color:rgba(255,255,255,.72);font-size:.9rem;">
          <i class="bi bi-check-circle-fill" style="color:var(--gold);margin-top:.15rem;flex-shrink:0;"></i>
          <?php echo htmlspecialchars($point); ?>
        </div>
        <?php } ?>
        <div class="mt-4">
          <a href="<?php echo APP_URL; ?>/about" class="btn-gold">
            About Royal Ruby <i class="bi bi-arrow-right"></i>
          </a>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-left">
        <div class="row g-3 text-center">
          <?php
          $metrics = array(
              array('200+', 'Clients Served'),
              array('&#x20A6;2B+', 'Revenue Generated'),
              array('98%',  'Client Retention'),
              array('15+',  'Industries'),
          );
          foreach ($metrics as $m) {
          ?>
          <div class="col-6">
            <div class="stat-block">
              <div class="num"><?php echo $m[0]; ?></div>
              <div class="label"><?php echo $m[1]; ?></div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ═══ HOSPITALITY PREVIEW ════════════════════════════════ -->
<section class="section-pad bg-subtle">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-6" data-aos="fade-up">
        <p class="section-subtitle text-ruby">Premium Experiences</p>
        <h2 class="section-title" style="display:inline-block;">Hospitality Redefined</h2>
        <p class="lead mt-2">We engineer moments that transcend expectation — from intimate boardroom gatherings to grand luxury events.</p>
      </div>
    </div>

    <div class="row align-items-center g-5">

      <div class="col-lg-7" data-aos="fade-right">
        <div class="row g-3">
          <?php
          $galleryItems = isset($gallery) ? $gallery : array();
          $placeholders = array(
              array('linear-gradient(135deg,#8B0000,#1a0000)', 'Luxury Event Management'),
              array('linear-gradient(135deg,#1a0000,#C9A84C)', 'Premium Venue Consulting'),
              array('linear-gradient(135deg,#5C0000,#0A0A0A)', 'Corporate Hospitality'),
              array('linear-gradient(135deg,#0A0A0A,#8B0000)', 'VIP Experience Curation'),
          );
          $count = 0;
          foreach ($galleryItems as $item) {
              if ($count >= 4) { break; }
          ?>
          <div class="col-6">
            <div class="gallery-item"
                 data-lightbox
                 data-src="<?php echo APP_URL; ?>/../storage/<?php echo htmlspecialchars($item['image']); ?>">
              <img src="<?php echo APP_URL; ?>/../storage/<?php echo htmlspecialchars($item['image']); ?>"
                   alt="<?php echo htmlspecialchars($item['title']); ?>"
                   loading="lazy"/>
              <div class="gallery-overlay">
                <span><?php echo htmlspecialchars($item['title']); ?></span>
              </div>
            </div>
          </div>
          <?php
              $count++;
          }
          // Fill remaining slots with placeholders
          for ($p = $count; $p < 4; $p++) {
              $ph = $placeholders[$p];
          ?>
          <div class="col-6">
            <div class="gallery-item" style="background:<?php echo $ph[0]; ?>;">
              <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:.5rem;">
                <i class="bi bi-gem" style="color:var(--gold);font-size:2rem;"></i>
                <span style="color:rgba(255,255,255,.7);font-size:.8rem;font-weight:600;">
                  <?php echo $ph[1]; ?>
                </span>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>

      <div class="col-lg-5" data-aos="fade-left">
        <p class="section-subtitle text-ruby">Hospitality Consulting</p>
        <h3 class="section-title" style="font-size:1.85rem;">
          Crafting Premium Experiences That Create Loyal Advocates
        </h3>
        <p style="color:var(--mid-grey);line-height:1.82;">
          Royal Ruby's Hospitality Consulting practice combines deep expertise in luxury service design with a rigorous results orientation.
        </p>
        <?php
        $hItems = array(
            'Luxury service protocol design',
            'Premium event planning & management',
            'VIP experience curation',
            'Staff training & culture development',
        );
        foreach ($hItems as $hi) {
        ?>
        <div class="d-flex gap-2 mb-2" style="font-size:.88rem;color:var(--charcoal);">
          <i class="bi bi-gem text-ruby" style="margin-top:.18rem;flex-shrink:0;"></i>
          <?php echo htmlspecialchars($hi); ?>
        </div>
        <?php } ?>
        <a href="<?php echo APP_URL; ?>/hospitality" class="btn-ruby mt-3">
          Explore Hospitality <i class="bi bi-arrow-right"></i>
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ═══ TESTIMONIALS ═══════════════════════════════════════ -->
<?php if (!empty($testimonials)) : ?>
<section class="section-pad">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-6" data-aos="fade-up">
        <p class="section-subtitle text-ruby">Client Voices</p>
        <h2 class="section-title" style="display:inline-block;">What Our Clients Say</h2>
      </div>
    </div>
    <div class="row g-4">
      <?php foreach (array_slice($testimonials, 0, 3) as $i => $t) : ?>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
        <div class="tcard">
          <div class="quote">&ldquo;</div>
          <div class="stars">
            <?php for ($s = 0; $s < (int)$t['rating']; $s++) { echo '<i class="bi bi-star-fill"></i>'; } ?>
          </div>
          <p class="review"><?php echo htmlspecialchars($t['review']); ?></p>
          <div class="d-flex align-items-center gap-3">
            <div class="t-avatar">
              <?php echo strtoupper(substr($t['client_name'], 0, 1)); ?>
            </div>
            <div>
              <div class="t-name"><?php echo htmlspecialchars($t['client_name']); ?></div>
              <div class="t-title"><?php echo htmlspecialchars(isset($t['client_title']) ? $t['client_title'] : ''); ?></div>
              <div class="t-company"><?php echo htmlspecialchars(isset($t['company']) ? $t['company'] : ''); ?></div>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="<?php echo APP_URL; ?>/testimonials" class="btn-outline-ruby">
        Read All Testimonials <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ BLOG ════════════════════════════════════════════════ -->
<?php if (!empty($posts)) : ?>
<section class="section-pad bg-subtle">
  <div class="container">
    <div class="row justify-content-center text-center mb-5">
      <div class="col-lg-6" data-aos="fade-up">
        <p class="section-subtitle text-ruby">Insights &amp; Strategy</p>
        <h2 class="section-title" style="display:inline-block;">Latest from Our Blog</h2>
      </div>
    </div>
    <div class="row g-4">
      <?php foreach ($posts as $i => $post) : ?>
      <div class="col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo $i * 100; ?>">
        <article class="blog-card">
          <div class="blog-card-img">
            <?php if (!empty($post['featured_image'])) : ?>
              <img src="<?php echo APP_URL; ?>/../storage/<?php echo htmlspecialchars($post['featured_image']); ?>"
                   alt="<?php echo htmlspecialchars($post['title']); ?>"
                   loading="lazy"/>
            <?php else : ?>
              <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-pencil-square" style="font-size:2.5rem;color:rgba(201,168,76,.35);"></i>
              </div>
            <?php endif; ?>
            <span class="blog-cat"><?php echo htmlspecialchars(isset($post['category']) ? $post['category'] : 'Business'); ?></span>
          </div>
          <div class="blog-body">
            <div class="blog-meta">
              <i class="bi bi-calendar3 me-1"></i>
              <?php echo date('M j, Y', strtotime(isset($post['published_at']) && $post['published_at'] ? $post['published_at'] : $post['created_at'])); ?>
            </div>
            <h5>
              <a href="<?php echo APP_URL; ?>/blog/<?php echo htmlspecialchars($post['slug']); ?>"
                 style="color:inherit;">
                <?php echo htmlspecialchars($post['title']); ?>
              </a>
            </h5>
            <p><?php echo htmlspecialchars(substr($post['excerpt'], 0, 110)) . '...'; ?></p>
            <a href="<?php echo APP_URL; ?>/blog/<?php echo htmlspecialchars($post['slug']); ?>"
               class="service-link">
              Read Article <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-5">
      <a href="<?php echo APP_URL; ?>/blog" class="btn-outline-ruby">
        View All Articles <i class="bi bi-arrow-right"></i>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══ CTA ════════════════════════════════════════════════ -->
<section class="cta-section section-pad">
  <div class="container text-center position-relative" style="z-index:2;" data-aos="fade-up">
    <p class="section-subtitle" style="color:var(--gold-light);">Take the Next Step</p>
    <h2 style="font-family:var(--serif);font-size:clamp(1.9rem,4vw,3rem);font-weight:700;color:var(--white);margin-bottom:1rem;">
      Ready to Elevate Your Business?
    </h2>
    <p style="color:rgba(255,255,255,.7);max-width:540px;margin:0 auto 2.5rem;font-size:1.05rem;line-height:1.82;">
      Let&rsquo;s have a strategic conversation about your goals. Book a complimentary consultation with our senior consultants today.
    </p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="<?php echo APP_URL; ?>/contact" class="btn-gold">
        Book Free Consultation <i class="bi bi-calendar-check"></i>
      </a>
      <a href="tel:+2348000000000" class="btn-outline-white">
        Call Us Now <i class="bi bi-telephone"></i>
      </a>
    </div>
  </div>
</section>

<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>