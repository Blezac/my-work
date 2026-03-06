<?php
// ============================================================
// CONTACT FORM HANDLER
// ============================================================
$form_success = false;
$form_error   = false;
$errors       = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name     = htmlspecialchars(strip_tags(trim($_POST['full_name']  ?? '')));
    $phone    = htmlspecialchars(strip_tags(trim($_POST['phone']      ?? '')));
    $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $service  = htmlspecialchars(strip_tags(trim($_POST['service']    ?? '')));
    $message  = htmlspecialchars(strip_tags(trim($_POST['message']    ?? '')));
    $honeypot = trim($_POST['website'] ?? ''); // Anti-spam honeypot

    if ($honeypot !== '') {
        $form_success = true; // Silent bot rejection
    } else {
        if (empty($name))    $errors[] = 'Full name is required.';
        if (empty($phone))   $errors[] = 'Phone number is required.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
                             $errors[] = 'A valid email address is required.';
        if (empty($service)) $errors[] = 'Please select a service.';
        if (empty($message)) $errors[] = 'Please include a short message.';

        if (empty($errors)) {
            $to      = 'info@yourcompany.com'; // <-- CHANGE THIS
            $subject = 'New Quote Request from ' . $name;
            $body    = "Name: $name\nPhone: $phone\nEmail: $email\nService: $service\n\nMessage:\n$message";
            $headers = "From: no-reply@yourcompany.com\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();
            if (mail($to, $subject, $body, $headers)) {
                $form_success = true;
            } else {
                $form_error = true;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>

<!-- ===== SEO ===== -->
<title>Electrician in Abuja | Inverter &amp; Solar Installation Experts</title>
<meta name="description" content="Top-rated electrical engineering company in Abuja (FCT). Inverter installation, solar power, wiring, CCTV, home automation &amp; repairs. Fast response. Call now for a free quote."/>
<meta name="keywords" content="electrician Abuja, solar installation Abuja, inverter installation FCT, electrical wiring Abuja, CCTV installation Abuja, home automation Abuja"/>

<!-- ===== Fonts ===== -->
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>

<!-- ===== Tailwind CSS ===== -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        navy:  { DEFAULT: '#0B1F4A', light: '#153270', dark: '#071530' },
        gold:  { DEFAULT: '#F5A623', light: '#FFB94A', dark: '#C8841A' },
        slate2: '#E8EDF5',
      },
      fontFamily: {
        display: ['"Barlow Condensed"', 'sans-serif'],
        body:    ['"DM Sans"', 'sans-serif'],
      },
    }
  }
}
</script>

<!-- ===== Font Awesome ===== -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

<style>
*,*::before,*::after { box-sizing: border-box; }
html { scroll-behavior: smooth; }
body { font-family: 'DM Sans', sans-serif; color: #1a1a2e; overflow-x: hidden; }

/* Scroll reveal */
.reveal       { opacity:0; transform:translateY(32px);  transition:opacity .65s ease,transform .65s ease; }
.reveal-left  { opacity:0; transform:translateX(-40px); transition:opacity .65s ease,transform .65s ease; }
.reveal-right { opacity:0; transform:translateX(40px);  transition:opacity .65s ease,transform .65s ease; }
.reveal.visible, .reveal-left.visible, .reveal-right.visible { opacity:1; transform:none; }

/* Header */
#site-header { transition: box-shadow .3s; }
#site-header.scrolled { box-shadow: 0 4px 24px rgba(11,31,74,.18); }

/* Hero */
#hero {
  background: linear-gradient(135deg,#0B1F4A 0%,#153270 60%,#0B1F4A 100%);
  position: relative; overflow: hidden;
}
#hero::before {
  content:''; position:absolute; inset:0;
  background: url('https://images.unsplash.com/photo-1621905251918-48416bd8575a?w=1600&auto=format&fit=crop&q=70') center/cover no-repeat;
  opacity:.12;
}
.hero-grid-line {
  position:absolute; inset:0;
  background-image: linear-gradient(rgba(245,166,35,.06) 1px,transparent 1px),
                    linear-gradient(90deg,rgba(245,166,35,.06) 1px,transparent 1px);
  background-size: 60px 60px;
}

/* Diagonal clips */
.clip-diag        { clip-path: polygon(0 0,100% 0,100% 88%,0 100%); }
.clip-diag-bottom { clip-path: polygon(0 6%,100% 0,100% 100%,0 100%); }

/* Service cards */
.service-card { transition: transform .3s, box-shadow .3s; }
.service-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(11,31,74,.14); }

/* Testimonials */
.testi-card { border-left: 4px solid #F5A623; }

/* Pulse CTA */
@keyframes pulse-gold {
  0%,100% { box-shadow: 0 0 0 0 rgba(245,166,35,.5); }
  50%      { box-shadow: 0 0 0 12px rgba(245,166,35,0); }
}
.btn-pulse { animation: pulse-gold 2.4s infinite; }

/* Mobile menu */
#mobile-menu       { display: none; }
#mobile-menu.open  { display: block; }

/* Form */
.form-input {
  width:100%; padding:.65rem 1rem; border:1.5px solid #d1d9e6;
  border-radius:.5rem; font-family:'DM Sans',sans-serif; font-size:.95rem;
  background:#fff; color:#1a1a2e; transition:border-color .25s,box-shadow .25s; outline:none;
}
.form-input:focus { border-color:#0B1F4A; box-shadow:0 0 0 3px rgba(11,31,74,.12); }
.form-label { display:block; margin-bottom:.35rem; font-weight:600; font-size:.9rem; color:#0B1F4A; }
</style>
</head>
<body class="bg-white">

<!-- ============================================================
     STICKY HEADER
============================================================ -->
<header id="site-header" class="fixed top-0 left-0 right-0 z-50 bg-navy text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex items-center justify-between h-16 lg:h-20">

    <!-- Logo -->
    <a href="#" class="flex items-center gap-2" aria-label="Home">
      <span class="w-9 h-9 rounded bg-gold flex items-center justify-center">
        <i class="fa-solid fa-bolt text-navy text-lg"></i>
      </span>
      <span class="font-display font-800 text-xl tracking-wide uppercase leading-none">
        Volt<span class="text-gold">Pro</span><br>
        <span class="text-xs font-body font-normal tracking-widest text-blue-200 uppercase">Engineering</span>
      </span>
    </a>

    <!-- Desktop Nav -->
    <nav class="hidden lg:flex items-center gap-7 font-body text-sm font-medium" aria-label="Primary navigation">
      <a href="#services"     class="hover:text-gold transition-colors">Services</a>
      <a href="#why-us"       class="hover:text-gold transition-colors">Why Us</a>
      <a href="#projects"     class="hover:text-gold transition-colors">Projects</a>
      <a href="#testimonials" class="hover:text-gold transition-colors">Testimonials</a>
      <a href="#contact"      class="hover:text-gold transition-colors">Contact</a>
    </nav>

    <!-- Phone + CTA -->
    <div class="hidden lg:flex items-center gap-4">
      <a href="tel:+2348000000000" class="flex items-center gap-2 text-gold font-display font-700 text-lg tracking-wide hover:text-gold-light transition-colors">
        <i class="fa-solid fa-phone-volume text-sm"></i> +234 800 000 0000
      </a>
      <a href="#contact" class="bg-gold hover:bg-gold-light text-navy font-display font-700 text-sm uppercase tracking-widest px-5 py-2.5 rounded transition-colors btn-pulse">
        Free Quote
      </a>
    </div>

    <!-- Hamburger -->
    <button id="hamburger-btn" class="lg:hidden p-2 rounded text-white hover:text-gold transition-colors" aria-label="Toggle menu">
      <i class="fa-solid fa-bars text-xl"></i>
    </button>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="lg:hidden bg-navy-dark border-t border-navy-light px-4 pb-4">
    <nav class="flex flex-col gap-1 pt-2 font-body" aria-label="Mobile navigation">
      <a href="#services"     class="py-2.5 border-b border-navy-light hover:text-gold transition-colors" onclick="closeMobileMenu()">Services</a>
      <a href="#why-us"       class="py-2.5 border-b border-navy-light hover:text-gold transition-colors" onclick="closeMobileMenu()">Why Us</a>
      <a href="#projects"     class="py-2.5 border-b border-navy-light hover:text-gold transition-colors" onclick="closeMobileMenu()">Projects</a>
      <a href="#testimonials" class="py-2.5 border-b border-navy-light hover:text-gold transition-colors" onclick="closeMobileMenu()">Testimonials</a>
      <a href="#contact"      class="py-2.5 border-b border-navy-light hover:text-gold transition-colors" onclick="closeMobileMenu()">Contact</a>
      <a href="tel:+2348000000000" class="mt-3 flex items-center gap-2 text-gold font-semibold">
        <i class="fa-solid fa-phone-volume"></i> +234 800 000 0000
      </a>
      <a href="#contact" class="mt-2 bg-gold text-navy text-center font-display font-700 uppercase tracking-widest py-3 rounded" onclick="closeMobileMenu()">
        Get a Free Quote
      </a>
    </nav>
  </div>
</header>


<!-- ============================================================
     HERO
============================================================ -->
<section id="hero" class="clip-diag min-h-screen flex items-center pt-16 lg:pt-20 pb-24">
  <div class="hero-grid-line"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-20 lg:py-32 grid lg:grid-cols-2 gap-12 items-center">

    <!-- Left copy -->
    <div>
      <span class="inline-block bg-gold text-navy font-display font-700 text-xs uppercase tracking-widest px-3 py-1 rounded mb-5 reveal">
        Serving Abuja &amp; FCT &mdash; Same-Day Service Available
      </span>
      <h1 class="font-display font-800 text-white text-5xl sm:text-6xl lg:text-7xl uppercase leading-none mb-6 reveal" style="transition-delay:.1s">
        Reliable Electrical<br>&amp; Solar Solutions<br><span class="text-gold">in Abuja</span>
      </h1>
      <p class="font-body text-blue-100 text-lg mb-8 max-w-lg reveal" style="transition-delay:.2s">
        Safe, certified, and affordable electrical services for homes and businesses across the FCT.
        From inverter installation to home automation &mdash; one trusted team.
      </p>
      <div class="flex flex-wrap gap-4 mb-10 reveal" style="transition-delay:.3s">
        <span class="flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-white text-sm">
          <i class="fa-solid fa-certificate text-gold text-xs"></i> Licensed Professionals
        </span>
        <span class="flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-white text-sm">
          <i class="fa-solid fa-bolt text-gold text-xs"></i> Fast Response
        </span>
        <span class="flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-1.5 text-white text-sm">
          <i class="fa-solid fa-tag text-gold text-xs"></i> Competitive Pricing
        </span>
      </div>
      <div class="flex flex-wrap gap-4 reveal" style="transition-delay:.4s">
        <a href="#contact" class="bg-gold hover:bg-gold-light text-navy font-display font-700 uppercase tracking-widest text-base px-8 py-4 rounded shadow-lg transition-colors btn-pulse">
          <i class="fa-solid fa-file-invoice mr-2"></i>Get a Free Quote
        </a>
        <a href="tel:+2348000000000" class="border-2 border-white/60 hover:border-gold text-white hover:text-gold font-display font-700 uppercase tracking-widest text-base px-8 py-4 rounded transition-colors">
          <i class="fa-solid fa-phone mr-2"></i>Call Now
        </a>
      </div>
    </div>

    <!-- Right stats -->
    <div class="hidden lg:grid grid-cols-2 gap-4 reveal-right" style="transition-delay:.3s">
      <?php
      $stats = [
        ['500+','Projects Completed','fa-check-circle'],
        ['10+', 'Years of Experience','fa-calendar-check'],
        ['98%', 'Client Satisfaction','fa-star'],
        ['24/7','Emergency Support',  'fa-headset'],
      ];
      foreach ($stats as $s): ?>
      <div class="bg-white/10 border border-white/15 rounded-xl p-6 backdrop-blur-sm text-center">
        <p class="font-display font-800 text-gold text-5xl leading-none mb-1"><?= $s[0] ?></p>
        <p class="text-blue-100 text-sm font-body mt-1"><?= $s[1] ?></p>
        <i class="fa-solid <?= $s[2] ?> text-gold/40 text-3xl mt-3 block"></i>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>


<!-- ============================================================
     PROBLEM / SOLUTION
============================================================ -->
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-14 reveal">
      <span class="text-gold font-display font-700 uppercase tracking-widest text-sm">Sound Familiar?</span>
      <h2 class="font-display font-800 text-navy text-4xl sm:text-5xl uppercase mt-2">Common Problems We Solve</h2>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php
      $problems = [
        ['fa-plug-circle-xmark',    'Frequent Power Outages?',  'Unreliable NEPA supply destroying your appliances and productivity.'],
        ['fa-triangle-exclamation', 'Faulty Wiring?',           'Exposed cables, tripped breakers, and fire hazards in your home or office.'],
        ['fa-battery-quarter',      'Unstable Inverter?',       'Your inverter keeps failing and no one explains why &mdash; until now.'],
        ['fa-shield-halved',        'Security Concerns?',       'No CCTV, poor lighting, and no smart access control leaving you exposed.'],
      ];
      foreach ($problems as $p): ?>
      <div class="border border-slate-200 rounded-xl p-6 reveal">
        <div class="w-12 h-12 rounded-lg bg-red-50 flex items-center justify-center mb-4">
          <i class="fa-solid <?= $p[0] ?> text-red-500 text-xl"></i>
        </div>
        <h3 class="font-display font-700 text-navy text-xl uppercase mb-2"><?= $p[1] ?></h3>
        <p class="text-slate-500 text-sm font-body leading-relaxed"><?= $p[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Solution bridge -->
    <div class="mt-14 bg-navy rounded-2xl p-8 sm:p-12 flex flex-col sm:flex-row items-center gap-8 reveal">
      <div class="flex-1">
        <h3 class="font-display font-800 text-white text-3xl sm:text-4xl uppercase leading-tight mb-3">One Call Solves All of It.</h3>
        <p class="text-blue-200 font-body leading-relaxed">
          VoltPro Engineering is Abuja's trusted electrical partner &mdash; handling everything from emergency
          repairs to complete solar power systems with certified engineers and guaranteed workmanship.
        </p>
      </div>
      <a href="#contact" class="shrink-0 bg-gold hover:bg-gold-light text-navy font-display font-700 uppercase tracking-widest text-base px-8 py-4 rounded shadow-lg transition-colors">
        Get a Free Quote
      </a>
    </div>
  </div>
</section>


<!-- ============================================================
     SERVICES
============================================================ -->
<section id="services" class="py-20 bg-slate2">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-14 reveal">
      <span class="text-gold font-display font-700 uppercase tracking-widest text-sm">What We Do</span>
      <h2 class="font-display font-800 text-navy text-4xl sm:text-5xl uppercase mt-2">Our Services</h2>
      <p class="mt-3 text-slate-500 max-w-xl mx-auto font-body">Professional electrical solutions for residential and commercial clients across Abuja and the FCT.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $services = [
        ['fa-solar-panel',         'Solar Power Solutions',       'Complete off-grid and hybrid solar systems designed for Abuja\'s climate and your energy needs.'],
        ['fa-battery-full',        'Inverter Installation',       'Expert supply, installation, and maintenance of all inverter and battery backup systems.'],
        ['fa-plug',                'Electrical Wiring',           'Full residential and commercial wiring &mdash; new builds, renovations, and rewiring projects.'],
        ['fa-screwdriver-wrench',  'Electrical Repairs',          'Fast diagnosis and repair of all electrical faults. Same-day service available.'],
        ['fa-camera',              'CCTV &amp; Security Systems', 'IP camera installation, DVR/NVR setup, and remote monitoring for total property security.'],
        ['fa-house-signal',        'Home Automation',             'Smart lighting, smart locks, automated gates, and integrated control systems.'],
        ['fa-pipe-section',        'Electrical Piping',           'Proper conduit and trunking installation that protects your wiring and meets safety codes.'],
        ['fa-wrench',              'Maintenance Services',        'Scheduled preventive maintenance contracts for homes, estates, and commercial buildings.'],
        ['fa-hammer',              'General Home Improvements',   'Handyman and improvement services that complement our core electrical offering.'],
      ];
      foreach ($services as $i => $s): ?>
      <div class="service-card bg-white rounded-xl p-7 reveal" style="transition-delay:<?= ($i % 3) * .1 ?>s">
        <div class="w-12 h-12 rounded-lg bg-navy/5 flex items-center justify-center mb-5">
          <i class="fa-solid <?= $s[0] ?> text-navy text-2xl"></i>
        </div>
        <h3 class="font-display font-700 text-navy text-xl uppercase mb-2"><?= $s[1] ?></h3>
        <p class="text-slate-500 text-sm font-body leading-relaxed mb-4"><?= $s[2] ?></p>
        <a href="#contact" class="text-gold font-display font-700 uppercase text-xs tracking-widest hover:underline">
          Get a Quote <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ============================================================
     WHY CHOOSE US
============================================================ -->
<section id="why-us" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid lg:grid-cols-2 gap-12 items-center">

    <div class="reveal-left">
      <span class="text-gold font-display font-700 uppercase tracking-widest text-sm">Why VoltPro?</span>
      <h2 class="font-display font-800 text-navy text-4xl sm:text-5xl uppercase mt-2 mb-6">The Standard Others<br>Are Measured By.</h2>
      <div class="space-y-4">
        <?php
        $reasons = [
          ['fa-user-tie',         'Veteran Certified Engineers',    'Our team holds current certifications and brings years of hands-on experience to every project.'],
          ['fa-shield-check',     'Strict Safety Standards',        'All work meets Nigerian electrical codes and international best practices &mdash; no shortcuts.'],
          ['fa-microchip',        'Modern Equipment',               'We use the latest tools, materials, and systems to deliver durable, future-ready results.'],
          ['fa-receipt',          'Transparent Pricing',            'No hidden charges. You receive a detailed quote before any work begins.'],
          ['fa-rocket',           'Fast Project Delivery',          'We respect your time. Projects are completed on schedule with minimal disruption.'],
          ['fa-map-location-dot', 'All Areas of Abuja Covered',     'Wuse, Garki, Maitama, Gwarinpa, Lugbe, Kuje, Bwari &mdash; wherever you are in the FCT.'],
        ];
        foreach ($reasons as $r): ?>
        <div class="flex gap-4 p-4 rounded-lg hover:bg-slate2 transition-colors">
          <div class="w-10 h-10 shrink-0 rounded-lg bg-gold flex items-center justify-center">
            <i class="fa-solid <?= $r[0] ?> text-navy text-base"></i>
          </div>
          <div>
            <h4 class="font-display font-700 text-navy text-base uppercase"><?= $r[1] ?></h4>
            <p class="text-slate-500 text-sm font-body mt-0.5"><?= $r[2] ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="reveal-right">
      <div class="bg-navy rounded-2xl overflow-hidden">
        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800&auto=format&fit=crop&q=75"
             alt="VoltPro engineer installing solar panels in Abuja" class="w-full h-64 object-cover opacity-80"/>
        <div class="p-8">
          <p class="font-display font-700 text-white text-3xl uppercase leading-tight mb-2">
            "We don't just wire homes &mdash; we power livelihoods."
          </p>
          <p class="text-blue-200 font-body text-sm">&mdash; VoltPro Engineering, Abuja</p>
          <a href="tel:+2348000000000" class="mt-6 inline-flex items-center gap-3 bg-gold hover:bg-gold-light text-navy font-display font-700 uppercase tracking-widest text-sm px-6 py-3 rounded transition-colors">
            <i class="fa-solid fa-phone-volume"></i> Call +234 800 000 0000
          </a>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ============================================================
     PROJECTS
============================================================ -->
<section id="projects" class="py-20 bg-slate2">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-14 reveal">
      <span class="text-gold font-display font-700 uppercase tracking-widest text-sm">Our Work</span>
      <h2 class="font-display font-800 text-navy text-4xl sm:text-5xl uppercase mt-2">Recent Projects</h2>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php
      $projects = [
        ['https://images.unsplash.com/photo-1509391366360-2e959784a276?w=600&auto=format&fit=crop&q=70','Solar Array &mdash; Maitama Residence','15kVA Hybrid Solar System'],
        ['https://images.unsplash.com/photo-1621905251918-48416bd8575a?w=600&auto=format&fit=crop&q=70','Full Rewiring &mdash; Wuse II Office Block','Commercial Electrical Wiring'],
        ['https://images.unsplash.com/photo-1558002038-1055907df827?w=600&auto=format&fit=crop&q=70','Smart Home Automation &mdash; Gwarinpa','Home Automation &amp; Control'],
        ['https://images.unsplash.com/photo-1605152276897-4f618f831968?w=600&auto=format&fit=crop&q=70','CCTV Network &mdash; Garki Offices','32-Camera IP Security System'],
        ['https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&auto=format&fit=crop&q=70','Inverter + Battery Bank &mdash; Lugbe','10kVA Inverter System'],
        ['https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=600&auto=format&fit=crop&q=70','Estate Wiring &mdash; Kuje','Residential Estate Electrical'],
      ];
      foreach ($projects as $i => $p): ?>
      <div class="group relative rounded-xl overflow-hidden reveal" style="transition-delay:<?= ($i % 3) * .1 ?>s">
        <img src="<?= $p[0] ?>" alt="<?= $p[1] ?>"
             class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-500"/>
        <div class="absolute inset-0 bg-gradient-to-t from-navy/90 via-navy/30 to-transparent flex flex-col justify-end p-5">
          <span class="text-gold font-display font-700 text-xs uppercase tracking-widest"><?= $p[2] ?></span>
          <h4 class="text-white font-display font-700 text-lg uppercase leading-tight mt-1"><?= $p[1] ?></h4>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ============================================================
     TESTIMONIALS
============================================================ -->
<section id="testimonials" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center mb-14 reveal">
      <span class="text-gold font-display font-700 uppercase tracking-widest text-sm">Client Feedback</span>
      <h2 class="font-display font-800 text-navy text-4xl sm:text-5xl uppercase mt-2">What Our Clients Say</h2>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php
      $testimonials = [
        ['Engr. Chukwuemeka Obi',  'Property Developer, Maitama',
         'VoltPro installed the solar system for my 6-unit apartment complex in Maitama. Their team was thorough, professional, and on time. The system has been running perfectly for over a year without a single issue.'],
        ['Mrs. Fatima Aliyu',       'Homeowner, Gwarinpa Estate',
         'I had been struggling with faulty wiring for months. VoltPro came in, identified the root cause immediately, and fixed everything in one visit. They cleaned up after themselves too &mdash; that impressed me greatly.'],
        ['Mr. David Okonkwo',       'Business Owner, Wuse II',
         'We engaged VoltPro for a full CCTV and access control installation across our office. The pricing was transparent, the execution was clean, and the support after installation has been excellent. Highly recommended.'],
      ];
      foreach ($testimonials as $i => $t): ?>
      <div class="testi-card bg-slate2 rounded-xl p-7 reveal" style="transition-delay:<?= $i * .15 ?>s">
        <div class="flex gap-1 mb-4">
          <?php for ($s = 1; $s < 5; $s++): ?>
          <i class="fa-solid fa-star text-gold text-sm"></i>
          <?php endfor; ?>
        </div>
        <p class="text-slate-600 font-body text-sm leading-relaxed mb-6 italic">"<?= $t[2] ?>"</p>
        <p class="font-display font-700 text-navy text-base uppercase"><?= $t[0] ?></p>
        <p class="text-slate-399 text-xs font-body mt-0.5"><?= $t[1] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ============================================================
     MID-PAGE CTA
============================================================ -->
<section class="py-20 bg-navy clip-diag-bottom">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 text-center reveal">
    <span class="text-gold font-display font-700 uppercase tracking-widest text-sm">Limited Availability</span>
    <h2 class="font-display font-800 text-white text-4xl sm:text-5xl uppercase mt-3 mb-4 leading-tight">
      Book Your Free Electrical<br>Inspection Today.
    </h2>
    <p class="text-blue-200 font-body mb-8">
      Our engineers will assess your property, identify hazards, and recommend the most cost-effective
      solutions &mdash; at no charge.
    </p>
    <a href="#contact" class="inline-block bg-gold hover:bg-gold-light text-navy font-display font-700 uppercase tracking-widest text-base px-10 py-4 rounded shadow-lg transition-colors btn-pulse">
      Request a Free Inspection
    </a>
  </div>
</section>


<!-- ============================================================
     CONTACT / QUOTE FORM
============================================================ -->
<section id="contact" class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid lg:grid-cols-2 gap-14 items-start">

    <!-- Info -->
    <div class="reveal-left">
      <span class="text-gold font-display font-700 uppercase tracking-widest text-sm">Contact Us</span>
      <h2 class="font-display font-800 text-navy text-4xl sm:text-5xl uppercase mt-2 mb-6">Get Your Free Quote</h2>
      <p class="text-slate-500 font-body mb-8 leading-relaxed">
        Fill in the form and one of our engineers will contact you within 2 hours during business hours
        to discuss your project and provide a no-obligation quote.
      </p>
      <div class="space-y-5">
        <div class="flex gap-4 items-start">
          <div class="w-10 h-10 shrink-0 rounded-lg bg-gold flex items-center justify-center">
            <i class="fa-solid fa-phone text-navy text-base"></i>
          </div>
          <div>
            <p class="font-display font-700 text-navy text-sm uppercase">Phone / WhatsApp</p>
            <a href="tel:+2348000000000" class="text-slate-500 font-body text-sm hover:text-gold transition-colors">+234 800 000 0000</a>
          </div>
        </div>
        <div class="flex gap-4 items-start">
          <div class="w-10 h-10 shrink-0 rounded-lg bg-gold flex items-center justify-center">
            <i class="fa-solid fa-envelope text-navy text-base"></i>
          </div>
          <div>
            <p class="font-display font-700 text-navy text-sm uppercase">Email</p>
            <a href="mailto:info@voltproeng.com" class="text-slate-500 font-body text-sm hover:text-gold transition-colors">info@voltproeng.com</a>
          </div>
        </div>
        <div class="flex gap-4 items-start">
          <div class="w-10 h-10 shrink-0 rounded-lg bg-gold flex items-center justify-center">
            <i class="fa-solid fa-location-dot text-navy text-base"></i>
          </div>
          <div>
            <p class="font-display font-700 text-navy text-sm uppercase">Address</p>
            <p class="text-slate-500 font-body text-sm">Plot 12, Cadastral Zone, Abuja FCT, Nigeria</p>
          </div>
        </div>
        <div class="flex gap-4 items-start">
          <div class="w-10 h-10 shrink-0 rounded-lg bg-gold flex items-center justify-center">
            <i class="fa-solid fa-clock text-navy text-base"></i>
          </div>
          <div>
            <p class="font-display font-700 text-navy text-sm uppercase">Business Hours</p>
            <p class="text-slate-500 font-body text-sm">Mon &ndash; Sat: 7:00 AM &ndash; 7:00 PM</p>
          </div>
        </div>
      </div>
      <div class="mt-8 flex gap-3">
        <a href="#" aria-label="Facebook"  class="w-10 h-10 rounded-full bg-navy hover:bg-gold transition-colors flex items-center justify-center text-white text-sm"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" aria-label="Instagram" class="w-10 h-10 rounded-full bg-navy hover:bg-gold transition-colors flex items-center justify-center text-white text-sm"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" aria-label="WhatsApp"  class="w-10 h-10 rounded-full bg-navy hover:bg-gold transition-colors flex items-center justify-center text-white text-sm"><i class="fa-brands fa-whatsapp"></i></a>
        <a href="#" aria-label="LinkedIn"  class="w-10 h-10 rounded-full bg-navy hover:bg-gold transition-colors flex items-center justify-center text-white text-sm"><i class="fa-brands fa-linkedin-in"></i></a>
      </div>
    </div>

    <!-- Form -->
    <div class="reveal-right">
      <div class="bg-slate2 rounded-2xl p-8">

        <?php if ($form_success): ?>
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
          <i class="fa-solid fa-circle-check text-green-500 text-4xl mb-3"></i>
          <h3 class="font-display font-700 text-navy text-2xl uppercase">Message Sent!</h3>
          <p class="text-slate-500 font-body mt-2">Thank you. One of our engineers will be in touch within 2 hours.</p>
        </div>

        <?php else: ?>

        <?php if ($form_error): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-5 text-red-700 font-body text-sm">
          <i class="fa-solid fa-circle-exclamation mr-2"></i>
          There was a problem sending your message. Please call +234 800 000 0000 directly.
        </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-5">
          <ul class="list-disc list-inside text-yellow-800 font-body text-sm space-y-1">
            <?php foreach ($errors as $err): ?>
            <li><?= $err ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="#contact" novalidate>
          <!-- Honeypot (hidden anti-spam) -->
          <div style="display:none;" aria-hidden="true">
            <label for="website">Leave this blank</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off"/>
          </div>

          <div class="grid sm:grid-cols-2 gap-5">
            <div>
              <label class="form-label" for="full_name">Full Name *</label>
              <input class="form-input" type="text" id="full_name" name="full_name"
                     placeholder="e.g. Emeka Okonkwo" required
                     value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>"/>
            </div>
            <div>
              <label class="form-label" for="phone">Phone Number *</label>
              <input class="form-input" type="tel" id="phone" name="phone"
                     placeholder="e.g. 0812 345 6789" required
                     value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"/>
            </div>
            <div>
              <label class="form-label" for="email">Email Address *</label>
              <input class="form-input" type="email" id="email" name="email"
                     placeholder="you@example.com" required
                     value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"/>
            </div>
            <div>
              <label class="form-label" for="service">Service Needed *</label>
              <select class="form-input" id="service" name="service" required>
                <option value="">&#8212; Select a Service &#8212;</option>
                <?php
                $svc_options = [
                  'Solar Power Solutions',
                  'Inverter Installation',
                  'Electrical Wiring (Residential)',
                  'Electrical Wiring (Commercial)',
                  'Electrical Repairs',
                  'CCTV & Security Systems',
                  'Home Automation',
                  'Electrical Piping & Conduit',
                  'Maintenance Services',
                  'General Home Improvements',
                  'Other / Not Listed',
                ];
                $sel = $_POST['service'] ?? '';
                foreach ($svc_options as $opt): ?>
                <option value="<?= $opt ?>" <?= $sel === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="sm:col-span-2">
              <label class="form-label" for="message">Brief Description *</label>
              <textarea class="form-input" id="message" name="message" rows="4"
                        placeholder="Describe your project, location within Abuja, and any specific requirements..."
                        required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
            </div>
          </div>

          <button type="submit" name="contact_submit"
                  class="mt-6 w-full bg-navy hover:bg-navy-light text-white font-display font-700 uppercase tracking-widest text-base py-4 rounded shadow transition-colors">
            <i class="fa-solid fa-paper-plane mr-2"></i>Send My Quote Request
          </button>
          <p class="text-slate-400 text-xs font-body text-center mt-3">
            Your information is kept strictly confidential and never shared.
          </p>
        </form>

        <?php endif; ?>
      </div>
    </div>

  </div>
</section>


<!-- ============================================================
     FINAL CTA
============================================================ -->
<section class="py-20 bg-navy">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center reveal">
    <h2 class="font-display font-800 text-white text-5xl sm:text-6xl uppercase leading-none mb-4">
      Power Your Home or Business<br><span class="text-gold">the Right Way.</span>
    </h2>
    <p class="text-blue-200 font-body mb-8 max-w-xl mx-auto">
      Join hundreds of satisfied clients across Abuja who trust VoltPro Engineering for safe,
      reliable, and modern electrical solutions.
    </p>
    <a href="#contact" class="inline-block bg-gold hover:bg-gold-light text-navy font-display font-700 uppercase tracking-widest text-lg px-12 py-5 rounded shadow-xl transition-colors btn-pulse">
      Get Started Today
    </a>
  </div>
</section>


<!-- ============================================================
     FOOTER
============================================================ -->
<footer class="bg-[#071530] text-blue-200 font-body">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 grid sm:grid-cols-2 lg:grid-cols-4 gap-10">

    <!-- Brand -->
    <div>
      <div class="flex items-center gap-2 mb-4">
        <span class="w-9 h-9 rounded bg-gold flex items-center justify-center">
          <i class="fa-solid fa-bolt text-navy text-lg"></i>
        </span>
        <span class="font-display font-800 text-xl tracking-wide uppercase leading-none text-white">
          Volt<span class="text-gold">Pro</span><br>
          <span class="text-xs font-body font-normal tracking-widest text-blue-300 uppercase">Engineering</span>
        </span>
      </div>
      <p class="text-sm leading-relaxed text-blue-300">
        Abuja's trusted electrical engineering partner &mdash; delivering safe, reliable, and modern
        power solutions across the FCT.
      </p>
    </div>

    <!-- Quick Links -->
    <div>
      <h4 class="font-display font-700 text-white uppercase tracking-widest text-sm mb-4">Quick Links</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="#services"     class="hover:text-gold transition-colors">Services</a></li>
        <li><a href="#why-us"       class="hover:text-gold transition-colors">Why Choose Us</a></li>
        <li><a href="#projects"     class="hover:text-gold transition-colors">Projects</a></li>
        <li><a href="#testimonials" class="hover:text-gold transition-colors">Testimonials</a></li>
        <li><a href="#contact"      class="hover:text-gold transition-colors">Contact Us</a></li>
      </ul>
    </div>

    <!-- Services -->
    <div>
      <h4 class="font-display font-700 text-white uppercase tracking-widest text-sm mb-4">Services</h4>
      <ul class="space-y-2 text-sm">
        <li><a href="#contact" class="hover:text-gold transition-colors">Solar Power Solutions</a></li>
        <li><a href="#contact" class="hover:text-gold transition-colors">Inverter Installation</a></li>
        <li><a href="#contact" class="hover:text-gold transition-colors">Electrical Wiring</a></li>
        <li><a href="#contact" class="hover:text-gold transition-colors">CCTV &amp; Security</a></li>
        <li><a href="#contact" class="hover:text-gold transition-colors">Home Automation</a></li>
        <li><a href="#contact" class="hover:text-gold transition-colors">Electrical Repairs</a></li>
      </ul>
    </div>

    <!-- Contact -->
    <div>
      <h4 class="font-display font-700 text-white uppercase tracking-widest text-sm mb-4">Contact Info</h4>
      <ul class="space-y-3 text-sm">
        <li class="flex gap-2">
          <i class="fa-solid fa-phone text-gold mt-0.5"></i>
          <a href="tel:+2348000000000" class="hover:text-gold transition-colors">+234 800 000 0000</a>
        </li>
        <li class="flex gap-2">
          <i class="fa-solid fa-envelope text-gold mt-0.5"></i>
          <a href="mailto:info@voltproeng.com" class="hover:text-gold transition-colors">info@voltproeng.com</a>
        </li>
        <li class="flex gap-2">
          <i class="fa-solid fa-location-dot text-gold mt-0.5"></i>
          <span>Plot 12, Cadastral Zone,<br>Abuja FCT, Nigeria</span>
        </li>
      </ul>
      <p class="mt-4 text-xs text-blue-400">
        Serving: Maitama, Wuse, Garki, Gwarinpa,<br>Lugbe, Kuje, Bwari &amp; all FCT areas.
      </p>
    </div>

  </div>
  <div class="border-t border-white/10 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-blue-400">
      <p>&copy; <?= date('Y') ?> VoltPro Engineering Ltd. All rights reserved.</p>
      <div class="flex gap-3">
        <a href="#" aria-label="Facebook"  class="w-8 h-8 rounded-full border border-white/15 hover:border-gold hover:text-gold flex items-center justify-center transition-colors"><i class="fa-brands fa-facebook-f text-xs"></i></a>
        <a href="#" aria-label="Instagram" class="w-8 h-8 rounded-full border border-white/15 hover:border-gold hover:text-gold flex items-center justify-center transition-colors"><i class="fa-brands fa-instagram text-xs"></i></a>
        <a href="#" aria-label="WhatsApp"  class="w-8 h-8 rounded-full border border-white/15 hover:border-gold hover:text-gold flex items-center justify-center transition-colors"><i class="fa-brands fa-whatsapp text-xs"></i></a>
        <a href="#" aria-label="LinkedIn"  class="w-8 h-8 rounded-full border border-white/15 hover:border-gold hover:text-gold flex items-center justify-center transition-colors"><i class="fa-brands fa-linkedin-in text-xs"></i></a>
      </div>
    </div>
  </div>
</footer>


<!-- ============================================================
     JAVASCRIPT
============================================================ -->
<script>
// Sticky header shadow
const header = document.getElementById('site-header');
window.addEventListener('scroll', () => header.classList.toggle('scrolled', window.scrollY > 10));

// Hamburger toggle
const hamburgerBtn = document.getElementById('hamburger-btn');
const mobileMenu   = document.getElementById('mobile-menu');
hamburgerBtn.addEventListener('click', () => {
  mobileMenu.classList.toggle('open');
  const icon = hamburgerBtn.querySelector('i');
  icon.classList.toggle('fa-bars');
  icon.classList.toggle('fa-xmark');
});
function closeMobileMenu() {
  mobileMenu.classList.remove('open');
  const icon = hamburgerBtn.querySelector('i');
  icon.classList.add('fa-bars');
  icon.classList.remove('fa-xmark');
}

// Scroll reveal
const revealEls = document.querySelectorAll('.reveal, .reveal-left, .reveal-right');
const observer  = new IntersectionObserver(
  entries => entries.forEach(e => {
    if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
  }),
  { threshold: 0.12 }
);
revealEls.forEach(el => observer.observe(el));

// Smooth scroll (Safari fallback)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function(e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      e.preventDefault();
      window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY - 80, behavior: 'smooth' });
    }
  });
});
</script>

</body>
</html>
