<?php
/**
 * Belzac Digital - About Page
 */
require_once 'includes/config.php';
$page_title = 'About';
include 'includes/header.php';
?>

<!-- Page Hero -->
<section class="bd-inner-hero">
    <div class="container">
        <div class="bd-breadcrumb">
            <a href="<?php echo SITE_URL; ?>/index.php">Home</a>
            <span>/</span>
            <span>About</span>
        </div>
        <span class="bd-hero-label">Our Story</span>
        <h1>Built on strategy.<br>Grounded in clarity.</h1>
        <p style="color: rgba(255,255,255,0.65); max-width: 520px; font-size: 1.05rem; margin-top: 1rem; margin-bottom: 0;">
            Belzac Digital exists to give SMEs the strategic foundation they need to grow with purpose, not just activity.
        </p>
    </div>
</section>
<!-- End Page Hero -->

<!-- Founder Story Section -->
<section class="bd-section">
    <div class="container">
        <div class="row g-5 align-items-start">

            <div class="col-lg-5">
                <span class="bd-label">Founder</span>
                <span class="bd-divider"></span>
                <h2>The thinking behind Belzac Digital</h2>
                <div class="bd-philosophy-block">
                    <p>"Most businesses do not have a visibility problem. They have a clarity problem. Fix the clarity and visibility follows."</p>
                </div>
                <p>
                    Belzac Digital was founded with a deliberate focus on one segment of the market: SMEs that are serious about growth but are not yet operating with the digital clarity their ambitions require.
                </p>
                <p>
                    The founder observed, across many engagements, that the same pattern repeated itself. Business owners would invest in social media, paid advertising, or website redesigns without first establishing what they were trying to say, to whom, and why it mattered. The result was consistent underperformance and frustration.
                </p>
                <p>
                    The response was straightforward: build a practice that addresses the foundation before the facade. Belzac Digital was created to fill that gap, working with SME owners who are willing to slow down briefly to get it right.
                </p>
            </div>

            <div class="col-lg-6 offset-lg-1">

                <div class="bd-section-sm" style="padding-top: 0;">
                    <span class="bd-label">Our Philosophy</span>
                    <span class="bd-divider"></span>
                    <h3>Clarity is not simplicity. It is precision.</h3>
                    <p>
                        There is a common misconception that clarity means reducing complexity to the point of losing nuance. That is not our position. Clarity means communicating the right level of complexity to the right audience in a way they can immediately act on.
                    </p>
                    <p>
                        A clear digital presence does not mean a bare or generic one. It means every element of your digital communication, from your website structure to your social media tone, is intentional, consistent, and aligned with what your business genuinely offers.
                    </p>
                </div>

                <div>
                    <span class="bd-label">What We Stand For</span>
                    <span class="bd-divider"></span>
                    <div class="bd-values-grid">
                        <div class="bd-value-item">
                            <h4>Intentionality</h4>
                            <p>Every recommendation we make is grounded in a specific reason tied to your business objectives.</p>
                        </div>
                        <div class="bd-value-item">
                            <h4>Structural Thinking</h4>
                            <p>We address root causes, not surface symptoms. Strategy before tactics, always.</p>
                        </div>
                        <div class="bd-value-item">
                            <h4>Honest Assessment</h4>
                            <p>We tell you what is not working and why, even when the answer is uncomfortable.</p>
                        </div>
                        <div class="bd-value-item">
                            <h4>Measured Growth</h4>
                            <p>Sustainable growth is built on a stable foundation. We help you build that foundation first.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>
<!-- End Founder Story -->

<!-- Mission Section -->
<section class="bd-section" style="background-color: var(--bd-off-white);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <span class="bd-label">Mission</span>
                <span class="bd-divider bd-divider-center"></span>
                <h2>Our mission is to make digital clarity accessible to every SME willing to pursue it.</h2>
                <p style="font-size: 1.05rem; color: var(--bd-gray-500); margin-top: 1.5rem; margin-bottom: 2.5rem;">
                    Large corporations have entire departments dedicated to brand consistency, messaging strategy, and digital architecture. SMEs rarely do. We exist to provide that same level of strategic thinking in a form that is practical and proportionate to your scale.
                </p>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="bd-service-card" style="text-align: center;">
                    <div class="bd-service-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h4>Near-Term Vision</h4>
                    <p style="font-size: 0.9rem;">Become the go-to clarity partner for SMEs in our target markets, known for producing measurable improvements in digital coherence and lead quality.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="bd-service-card" style="text-align: center;">
                    <div class="bd-service-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Long-Term Vision</h4>
                    <p style="font-size: 0.9rem;">Build a body of frameworks and resources that SME founders can use to self-assess and improve their digital clarity, with or without direct engagement.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="bd-service-card" style="text-align: center;">
                    <div class="bd-service-icon" style="margin: 0 auto 1.5rem;">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4>How We Operate</h4>
                    <p style="font-size: 0.9rem;">We take on a small number of clients at any given time. This is not a volume business. It is a precision practice built on trust and thoroughness.</p>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- End Mission -->

<!-- CTA Section -->
<section class="bd-cta-band">
    <div class="container">
        <h2>Work with a team that leads with strategy.</h2>
        <p>If you are an SME owner who wants to grow with intention, we would like to hear from you.</p>
        <a href="<?php echo SITE_URL; ?>/contact.php" class="bd-btn-light">Start the Conversation</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
