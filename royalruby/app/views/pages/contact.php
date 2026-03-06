<?php require APP_ROOT . '/app/views/partials/header.php'; ?>
<div class="page-hero">
  <div class="container">
    <p class="section-subtitle" style="color:var(--gold-light);">Get In Touch</p>
    <h1>Contact Us</h1>
    <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/">Home</a></li>
      <li class="breadcrumb-item active">Contact</li>
    </ol></nav>
  </div>
</div>
<section class="section-pad">
  <div class="container">
    <?php if (isset($_GET['success'])) : ?>
    <div class="alert alert-success alert-dismissible fade show mb-4">
      <i class="bi bi-check-circle-fill me-2"></i><strong>Message sent!</strong> We'll respond within 24 hours.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])) : ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4">
      <i class="bi bi-exclamation-circle-fill me-2"></i>Please fill in all required fields correctly.
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <div class="row g-5">
      <div class="col-lg-4" data-aos="fade-right">
        <h3 class="section-title" style="font-size:1.65rem;">Let's Start a Conversation</h3>
        <p style="color:var(--mid-grey);line-height:1.82;margin-bottom:2rem;">Whether you're looking to scale your marketing, develop your business, or create premium experiences — we're here.</p>
        <?php
        $ci = array(
          array('bi-geo-alt-fill',    isset($settings['site_address'])?$settings['site_address']:'Victoria Island, Lagos, Nigeria'),
          array('bi-telephone-fill',  isset($settings['site_phone'])?$settings['site_phone']:'+234 800 000 0000'),
          array('bi-envelope-fill',   isset($settings['site_email'])?$settings['site_email']:'info@royalruby.com'),
          array('bi-clock-fill',      'Monday – Friday: 9am – 6pm WAT'),
        );
        foreach ($ci as $c) { ?>
        <div class="d-flex gap-3 mb-3">
          <div style="width:42px;height:42px;background:rgba(139,0,0,.08);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="bi <?php echo $c[0]; ?>" style="color:var(--ruby);"></i>
          </div>
          <div style="font-size:.9rem;color:var(--charcoal);padding-top:.55rem;"><?php echo htmlspecialchars($c[1]); ?></div>
        </div>
        <?php } ?>
      </div>
      <div class="col-lg-8" data-aos="fade-left">
        <div class="contact-card">
          <h4 style="font-family:var(--serif);margin-bottom:1.5rem;">Send Us a Message</h4>
          <form id="contactForm" action="<?php echo APP_URL; ?>/contact" method="POST" novalidate>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(isset($csrf)?$csrf:'',ENT_QUOTES,'UTF-8'); ?>"/>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name *</label>
                <input type="text" name="name" class="form-control" placeholder="John Adeyemi" required/>
              </div>
              <div class="col-md-6">
                <label class="form-label">Email Address *</label>
                <input type="email" name="email" class="form-control" placeholder="you@company.com" required/>
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" placeholder="+234 800 000 0000"/>
              </div>
              <div class="col-md-6">
                <label class="form-label">Subject</label>
                <select name="subject" class="form-select">
                  <option value="">Select a topic...</option>
                  <option>Marketing &amp; Client Acquisition</option>
                  <option>Business Development</option>
                  <option>Revenue Growth Strategy</option>
                  <option>Brand Positioning</option>
                  <option>Hospitality Consulting</option>
                  <option>General Inquiry</option>
                </select>
              </div>
              <div class="col-12">
                <label class="form-label">Your Message *</label>
                <textarea name="message" class="form-control" placeholder="Tell us about your business goals..." required></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-ruby">Send Message <i class="bi bi-send-fill ms-2"></i></button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require APP_ROOT . '/app/views/partials/footer.php'; ?>