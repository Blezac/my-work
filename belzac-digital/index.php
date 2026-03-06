<?php
/**
 * Belzac Digital - Home Page
 */
require_once 'includes/config.php';
$page_title = 'Home';
include 'includes/header.php';
?>

<!-- Hero Section -->
<section class="bd-hero">
    <div class="container">
        <span class="bd-hero-label">Digital Strategy for SMEs</span>
        <h1>Clear Digital Growth<br>for SMEs</h1>
        <p class="bd-hero-sub">
            Before you scale, you need clarity. We help small and medium enterprises build a structured, coherent digital presence that supports long-term growth without the noise.
        </p>
        <div class="bd-hero-actions">
            <a href="<?php echo SITE_URL; ?>/contact.php" class="bd-btn-light">Work With Us</a>
            <a href="<?php echo SITE_URL; ?>/services.php" class="bd-btn-outline" style="border-color: rgba(255,255,255,0.5); color: rgba(255,255,255,0.85) !important;">View Services</a>
        </div>
        <div class="bd-hero-scroll">
            <span class="bd-hero-scroll-line"></span>
            <span>Scroll to explore</span>
        </div>
    </div>
</section>
<!-- End Hero -->

<!-- About Preview Section -->
<section class="bd-about-preview bd-section">
    <div class="container">
        <div class="bd-about-preview-inner">

            <div class="bd-about-visual">
                <span class="bd-label" style="color: var(--bd-accent-line);">About Belzac Digital</span>
                <h3 style="color: var(--bd-white); margin-bottom: 0.75rem;">Built around one principle: clarity before scale.</h3>
                <p style="color: rgba(255,255,255,0.6); font-size: 0.9rem; margin-bottom: 0;">
                    Most SMEs try to grow before they have a clear message. We believe a structured digital foundation is the prerequisite to everything else.
                </p>
                <div class="bd-stat-grid">
                    <div class="bd-stat">
                        <div class="bd-stat-number">3</div>
                        <div class="bd-stat-label">Core Services</div>
                    </div>
                    <div class="bd-stat">
                        <div class="bd-stat-number">SME</div>
                        <div class="bd-stat-label">Focused</div>
                    </div>
                    <div class="bd-stat">
                        <div class="bd-stat-number">100%</div>
                        <div class="bd-stat-label">Strategy First</div>
                    </div>
                    <div class="bd-stat">
                        <div class="bd-stat-number">0</div>
                        <div class="bd-stat-label">Empty Promises</div>
                    </div>
                </div>
            </div>

            <div class="bd-about-content">
                <span class="bd-label">Our Approach</span>
                <span class="bd-divider"></span>
                <h2>We work where structure meets strategy.</h2>
                <p>
                    Belzac Digital was founded on a simple observation: many SMEs invest in marketing activity before they have a stable digital foundation. The result is wasted budget and inconsistent messaging.
                </p>
                <p>
                    We take a different path. Before growth, we establish clarity. We examine how your business communicates digitally, where the gaps are, and how to close them in a way that supports real, measurable progress.
                </p>
                <a href="<?php echo SITE_URL; ?>/about.php" class="bd-btn-outline">More About Us</a>
            </div>

        </div>
    </div>
</section>
<!-- End About Preview -->

<!-- Services Overview Section -->
<section class="bd-services-overview bd-section">
    <div class="container">

        <div class="bd-section-header">
            <span class="bd-label">What We Do</span>
            <span class="bd-divider"></span>
            <h2>Three services. One clear purpose.</h2>
            <p class="bd-text-muted">
                Each service is designed to address a specific challenge that SMEs face in building a credible, functional digital presence.
            </p>
        </div>

        <div class="row g-4">

            <!-- Service Card 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="bd-service-card">
                    <div class="bd-service-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Digital Clarity Audit</h3>
                    <p>A structured review of your current digital presence, messaging, and positioning. We identify what is working, what is unclear, and what needs to change before any growth strategy is applied.</p>
                    <a href="<?php echo SITE_URL; ?>/services.php#audit" class="bd-service-card-link">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Service Card 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="bd-service-card">
                    <div class="bd-service-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h3>Website Structure Consulting</h3>
                    <p>Your website should guide visitors through a clear journey. We analyse and restructure the information architecture, page hierarchy, and messaging flow to convert visitors into enquiries.</p>
                    <a href="<?php echo SITE_URL; ?>/services.php#consulting" class="bd-service-card-link">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Service Card 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="bd-service-card">
                    <div class="bd-service-icon">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <h3>Content Strategy Simplification</h3>
                    <p>Overcomplicated content loses customers. We simplify your messaging framework so that every piece of content your business produces is coherent, on-brand, and directed at the right audience.</p>
                    <a href="<?php echo SITE_URL; ?>/services.php#content" class="bd-service-card-link">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>
<!-- End Services Overview -->

<!-- Why Clarity Matters Section -->
<section class="bd-clarity bd-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-5">
                <span class="bd-label" style="color: var(--bd-accent-line);">Why It Matters</span>
                <span class="bd-divider" style="background: var(--bd-accent-line);"></span>
                <h2>Clarity is not cosmetic. It is structural.</h2>
                <p class="bd-clarity-intro">
                    Without a clear digital foundation, every marketing investment you make is less effective than it should be. Clarity multiplies the value of everything that follows.
                </p>
            </div>

            <div class="col-lg-6 offset-lg-1">
                <ul class="bd-clarity-points">
                    <li>
                        <i class="fas fa-check bd-clarity-icon"></i>
                        <span>Clear positioning eliminates wasted ad spend on audiences that will never convert.</span>
                    </li>
                    <li>
                        <i class="fas fa-check bd-clarity-icon"></i>
                        <span>Structured websites guide visitors to action without friction or confusion.</span>
                    </li>
                    <li>
                        <i class="fas fa-check bd-clarity-icon"></i>
                        <span>Simplified content is easier to produce consistently, reducing internal workload.</span>
                    </li>
                    <li>
                        <i class="fas fa-check bd-clarity-icon"></i>
                        <span>A coherent digital presence builds trust with prospective clients before the first conversation.</span>
                    </li>
                    <li>
                        <i class="fas fa-check bd-clarity-icon"></i>
                        <span>SMEs that establish clarity first scale faster and with less friction than those who do not.</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>
<!-- End Why Clarity Matters -->

<!-- Contact CTA Section -->
<section class="bd-cta-band">
    <div class="container">
        <h2>Ready to bring clarity to your digital presence?</h2>
        <p>Let us start with a straightforward conversation about where your business is and where you want it to go.</p>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="bd-btn-light">Work With Us</a>
    </div>
</section>
<!-- End CTA -->

<?php include 'includes/footer.php'; ?>
