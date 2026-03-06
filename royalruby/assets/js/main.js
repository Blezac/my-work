/**
 * Royal Ruby Exotic Place — Main JavaScript
 * Vanilla JS, no dependencies beyond Bootstrap
 */
(function () {
  'use strict';

  // ── Page Loader ────────────────────────────────────────
  window.addEventListener('load', function () {
    var loader = document.getElementById('loader');
    if (loader) {
      setTimeout(function () { loader.classList.add('loaded'); }, 400);
    }
    initAOS();
    initCounters();
    initParticles();
  });

  // ── Navbar Scroll ──────────────────────────────────────
  var nav = document.getElementById('mainNav');
  if (nav) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 50) {
        nav.classList.add('scrolled');
      } else {
        nav.classList.remove('scrolled');
      }
    });
  }

  // ── Scroll-to-Top ──────────────────────────────────────
  var scrollBtn = document.getElementById('scrollTop');
  if (scrollBtn) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 400) {
        scrollBtn.classList.add('visible');
      } else {
        scrollBtn.classList.remove('visible');
      }
    });
    scrollBtn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // ── Active Nav Link ────────────────────────────────────
  var currentPath = window.location.pathname;
  var navLinks = document.querySelectorAll('#mainNav .nav-link');
  for (var i = 0; i < navLinks.length; i++) {
    var href = navLinks[i].getAttribute('href');
    if (href && href !== '/' && currentPath.indexOf(href) === 0) {
      navLinks[i].classList.add('active');
    } else if (href === '/' && currentPath === '/') {
      navLinks[i].classList.add('active');
    }
  }

  // ── Simple AOS (Intersection Observer) ────────────────
  function initAOS() {
    var elements = document.querySelectorAll('[data-aos]');
    if (!elements.length || !window.IntersectionObserver) {
      // Fallback: show everything immediately
      for (var i = 0; i < elements.length; i++) {
        elements[i].classList.add('aos-animate');
      }
      return;
    }
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          var el    = entry.target;
          var delay = parseInt(el.getAttribute('data-aos-delay') || '0', 10);
          setTimeout(function () {
            el.classList.add('aos-animate');
          }, delay);
          observer.unobserve(el);
        }
      });
    }, { threshold: 0.1 });
    for (var j = 0; j < elements.length; j++) {
      observer.observe(elements[j]);
    }
  }

  // ── Counter Animation ──────────────────────────────────
  function initCounters() {
    var counters = document.querySelectorAll('[data-counter]');
    if (!counters.length || !window.IntersectionObserver) { return; }
    var cObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          cObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    for (var i = 0; i < counters.length; i++) {
      cObserver.observe(counters[i]);
    }
  }

  function animateCounter(el) {
    var target   = parseInt(el.getAttribute('data-target') || el.textContent, 10);
    var suffix   = el.getAttribute('data-suffix') || '';
    var duration = 1800;
    var steps    = 60;
    var step     = target / steps;
    var current  = 0;
    var timer = setInterval(function () {
      current += step;
      if (current >= target) {
        current = target;
        clearInterval(timer);
      }
      el.textContent = Math.round(current) + suffix;
    }, duration / steps);
  }

  // ── Hero Particles ─────────────────────────────────────
  function initParticles() {
    var container = document.querySelector('.hero-particles');
    if (!container) { return; }
    for (var i = 0; i < 18; i++) {
      var p = document.createElement('div');
      p.className = 'hero-particle';
      p.style.cssText = [
        'left:' + (Math.random() * 100) + '%',
        'top:'  + (Math.random() * 100) + '%',
        '--dur:' + (6 + Math.random() * 7) + 's',
        '--delay:' + (Math.random() * 5) + 's'
      ].join(';');
      container.appendChild(p);
    }
  }

  // ── Gallery Lightbox ───────────────────────────────────
  var lightbox = document.getElementById('galleryLightbox');
  if (lightbox) {
    var lbImg   = lightbox.querySelector('#lbImage');
    var lbClose = lightbox.querySelector('.lb-close');
    var triggers = document.querySelectorAll('[data-lightbox]');
    for (var i = 0; i < triggers.length; i++) {
      triggers[i].addEventListener('click', function () {
        var src = this.getAttribute('data-src');
        if (!src) {
          var img = this.querySelector('img');
          src = img ? img.src : '';
        }
        if (lbImg && src) {
          lbImg.src = src;
          lightbox.style.display = 'flex';
        }
      });
    }
    if (lbClose) {
      lbClose.addEventListener('click', function () {
        lightbox.style.display = 'none';
      });
    }
    lightbox.addEventListener('click', function (e) {
      if (e.target === lightbox) { lightbox.style.display = 'none'; }
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { lightbox.style.display = 'none'; }
    });
  }

  // ── Newsletter Form (AJAX) ─────────────────────────────
  var nlForm = document.getElementById('newsletterForm');
  if (nlForm) {
    nlForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn   = nlForm.querySelector('button[type=submit]');
      var input = nlForm.querySelector('input[name=email]');
      if (!input || !input.value) { return; }
      btn.disabled    = true;
      btn.textContent = 'Subscribing…';
      var body = 'email=' + encodeURIComponent(input.value);
      fetch(nlForm.action, {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    body
      })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          nlForm.innerHTML = '<p style="color:#155724;font-weight:600;margin:0">&#10003; Subscribed! Welcome to Royal Ruby.</p>';
        } else {
          btn.disabled    = false;
          btn.textContent = 'Subscribe';
        }
      })
      .catch(function () {
        btn.disabled    = false;
        btn.textContent = 'Subscribe';
      });
    });
  }

  // ── Contact Form Client Validation ────────────────────
  var contactForm = document.getElementById('contactForm');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      var fields = contactForm.querySelectorAll('[required]');
      var valid  = true;
      for (var i = 0; i < fields.length; i++) {
        if (!fields[i].value.trim()) {
          fields[i].classList.add('is-invalid');
          valid = false;
        } else {
          fields[i].classList.remove('is-invalid');
        }
      }
      var emailField = contactForm.querySelector('[type=email]');
      if (emailField) {
        var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRe.test(emailField.value)) {
          emailField.classList.add('is-invalid');
          valid = false;
        }
      }
      if (!valid) { e.preventDefault(); }
    });
  }

  // ── Confirm Delete ─────────────────────────────────────
  var confirmLinks = document.querySelectorAll('[data-confirm]');
  for (var i = 0; i < confirmLinks.length; i++) {
    confirmLinks[i].addEventListener('click', function (e) {
      var msg = this.getAttribute('data-confirm') || 'Are you sure?';
      if (!confirm(msg)) { e.preventDefault(); }
    });
  }

  // ── Auto-dismiss Flash Alerts ──────────────────────────
  setTimeout(function () {
    var alerts = document.querySelectorAll('.alert-dismissible');
    for (var i = 0; i < alerts.length; i++) {
      try {
        var bsAlert = window.bootstrap && bootstrap.Alert.getOrCreateInstance(alerts[i]);
        if (bsAlert) { bsAlert.close(); }
      } catch (err) {
        alerts[i].style.display = 'none';
      }
    }
  }, 4500);

  // ── Smooth Scroll for Anchor Links ────────────────────
  var anchors = document.querySelectorAll('a[href^="#"]');
  for (var i = 0; i < anchors.length; i++) {
    anchors[i].addEventListener('click', function (e) {
      var target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  }

})();