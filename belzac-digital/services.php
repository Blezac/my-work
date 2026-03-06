<?php
/**
 * Belzac Digital - Services Page
 */
require_once 'includes/config.php';
$page_title = 'Services';
include 'includes/header.php';
?>

<!-- Page Hero -->
<section class="bd-inner-hero">
    <div class="container">
        <div class="bd-breadcrumb">
            <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
            <span>/</span>
            <span>Services</span>
        </div>
        <span class="bd-hero-label">What We Offer</span>
        <h1>Services designed around<br>your digital foundation.</h1>
        <p style="color: rgba(255,255,255,0.65); max-width: 540px; font-size: 1.05rem; margin-top: 1rem; margin-bottom: 0;">
            Three focused services. Each one addresses a distinct layer of your digital presence, in the order that makes strategic sense.
        </p>
    </div>
</section>
<!-- End Page Hero -->

<!-- Services Listing -->
<section class="bd-section">
    <div class="container">

        <!-- Service 1: Digital Clarity Audit -->
        <div class="bd-service-full" id="audit">
            <div class="row align-items-start g-5">
                <div class="col-lg-4">
                    <div class="bd-service-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <span class="bd-label" style="margin-top: 1.5rem;">Service 01</span>
                    <h2>Digital Clarity Audit</h2>
                    <a href="<?php echo SITE_URL; ?>/contact.php" class="bd-btn-primary" style="margin-top: 1.5rem; display: inline-block;">Enquire About This Service</a>
                </div>

                <div class="col-lg-7 offset-lg-1">
                    <p style="font-size: 1.05rem; color: var(--bd-gray-700); line-height: 1.8;">
                        The Digital Clarity Audit is the starting point for any meaningful digital improvement. Before you allocate budget to campaigns, redesigns, or content creation, you need an accurate picture of where your digital presence currently stands and how it is being perceived by your target audience.
                    </p>
                    <p>
                        We conduct a systematic review of your existing digital touchpoints: your website, social profiles, Google presence, and any active marketing materials. We assess them against a structured framework that examines clarity of messaging, consistency of positioning, and alignment between what you say and what your audience needs to hear.
                    </p>

                    <div class="bd-service-meta">
                        <div class="bd-service-meta-block">
                            <h6>The Problem</h6>
                            <p>Most SMEs cannot accurately describe how their digital presence appears to someone encountering them for the first time. Assumptions replace evidence, and decisions are made without a reliable baseline.</p>
                        </div>
                        <div class="bd-service-meta-block">
                            <h6>Our Solution</h6>
                            <p>We provide an objective, structured audit that documents precisely what exists, what is unclear, and what is working against your business goals across your current digital presence.</p>
                        </div>
                        <div class="bd-service-meta-block">
                            <h6>The Benefit</h6>
                            <p>You gain a clear, prioritised action plan grounded in evidence rather than assumption. Every subsequent decision is better informed and more likely to produce the outcome you need.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Service 1 -->

        <!-- Service 2: Website Structure Consulting -->
        <div class="bd-service-full" id="consulting">
            <div class="row align-items-start g-5">
                <div class="col-lg-4">
                    <div class="bd-service-icon">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <span class="bd-label" style="margin-top: 1.5rem;">Service 02</span>
                    <h2>Website Structure Consulting</h2>
                    <a href="<?php echo SITE_URL; ?>/contact.php" class="bd-btn-primary" style="margin-top: 1.5rem; display: inline-block;">Enquire About This Service</a>
                </div>

                <div class="col-lg-7 offset-lg-1">
                    <p style="font-size: 1.05rem; color: var(--bd-gray-700); line-height: 1.8;">
                        Your website is the most important piece of digital real estate your business controls. Yet most SME websites suffer from the same structural issues: unclear navigation, unfocused pages, buried calls to action, and messaging that reflects internal thinking rather than the questions a visitor actually has.
                    </p>
                    <p>
                        Website Structure Consulting is a focused engagement that examines your site from the perspective of a potential client. We analyse how information is organised, what signals each page sends, and whether the visitor journey leads logically to a conversion point. We then provide a structured recommendation for restructuring the site in a way that guides rather than overwhelms.
                    </p>

                    <div class="bd-service-meta">
                        <div class="bd-service-meta-block">
                            <h6>The Problem</h6>
                            <p>Visitors land on your website and cannot quickly understand what you do, who you serve, or what they should do next. They leave without contacting you, and you never know why.</p>
                        </div>
                        <div class="bd-service-meta-block">
                            <h6>Our Solution</h6>
                            <p>We map your current site structure, identify friction points and messaging gaps, and deliver a clear restructuring plan with rationale for every recommended change.</p>
                        </div>
                        <div class="bd-service-meta-block">
                            <h6>The Benefit</h6>
                            <p>A site that does its job: educating, reassuring, and directing visitors toward the action you want them to take, without requiring them to work for the information they need.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Service 2 -->

        <!-- Service 3: Content Strategy Simplification -->
        <div class="bd-service-full" id="content">
            <div class="row align-items-start g-5">
                <div class="col-lg-4">
                    <div class="bd-service-icon">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <span class="bd-label" style="margin-top: 1.5rem;">Service 03</span>
                    <h2>Content Strategy Simplification</h2>
                    <a href="<?php echo SITE_URL; ?>/contact.php" class="bd-btn-primary" style="margin-top: 1.5rem; display: inline-block;">Enquire About This Service</a>
                </div>

                <div class="col-lg-7 offset-lg-1">
                    <p style="font-size: 1.05rem; color: var(--bd-gray-700); line-height: 1.8;">
                        Content is where clarity either holds or collapses. Many SMEs produce content, but without a clear framework, it ends up inconsistent in tone, unfocused in audience, and disconnected from the broader message the business is trying to communicate. The result is effort that does not compound.
                    </p>
                    <p>
                        Content Strategy Simplification gives your business a working content framework: a defined audience profile, a clear messaging hierarchy, a content structure that can be followed consistently by anyone in your organisation, and guidance on which channels deserve your attention and which do not.
                    </p>

                    <div class="bd-service-meta">
                        <div class="bd-service-meta-block">
                            <h6>The Problem</h6>
                            <p>Content is produced without a clear framework, resulting in inconsistent messaging, audience confusion, and a social or blog presence that does not build trust or generate enquiries over time.</p>
                        </div>
                        <div class="bd-service-meta-block">
                            <h6>Our Solution</h6>
                            <p>We develop a simplified content strategy tailored to your business, defining your messaging pillars, audience priorities, and a sustainable content structure any team can follow.</p>
                        </div>
                        <div class="bd-service-meta-block">
                            <h6>The Benefit</h6>
                            <p>Content that accumulates value over time. Every post, article, or update reinforces the same clear message and moves your audience incrementally closer to choosing your business.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Service 3 -->

    </div>
</section>
<!-- End Services Listing -->

<!-- CTA Section -->
<section class="bd-cta-band">
    <div class="container">
        <h2>Not sure which service fits your situation?</h2>
        <p>Get in touch and describe where you are. We will tell you honestly what we think would help most.</p>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="bd-btn-light">Start With a Conversation</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
