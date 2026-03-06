/* ========================================
   DESOK LUXURY APARTMENT - MAIN JAVASCRIPT
   Interactive Features & Form Handling
   ======================================== */

// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
  
  /* === NAVIGATION === */
  
  // Mobile menu toggle
  const menuToggle = document.querySelector('.menu-toggle');
  const navMenu = document.querySelector('.nav-menu');
  
  if (menuToggle) {
    menuToggle.addEventListener('click', function() {
      this.classList.toggle('active');
      navMenu.classList.toggle('active');
      document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
    });
    
    // Close menu when clicking on a link
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
      link.addEventListener('click', function() {
        menuToggle.classList.remove('active');
        navMenu.classList.remove('active');
        document.body.style.overflow = '';
      });
    });
  }
  
  // Navbar scroll effect
  const navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', function() {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
  
  // Active navigation link based on current page
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav-link');
  navLinks.forEach(link => {
    if (link.getAttribute('href') === currentPath.split('/').pop() || 
        (currentPath.includes(link.getAttribute('href').replace('.php', '')) && link.getAttribute('href') !== 'index.php')) {
      link.classList.add('active');
    }
  });
  
  /* === SMOOTH SCROLLING === */
  
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href !== '#') {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      }
    });
  });
  
  /* === GALLERY LIGHTBOX === */
  
  const galleryItems = document.querySelectorAll('.gallery-item');
  const lightbox = document.querySelector('.lightbox');
  const lightboxImg = document.querySelector('.lightbox-content');
  const lightboxClose = document.querySelector('.lightbox-close');
  
  if (galleryItems.length > 0 && lightbox) {
    galleryItems.forEach(item => {
      item.addEventListener('click', function() {
        const img = this.querySelector('.gallery-image');
        lightboxImg.src = img.src;
        lightbox.classList.add('active');
        document.body.style.overflow = 'hidden';
      });
    });
    
    // Close lightbox
    if (lightboxClose) {
      lightboxClose.addEventListener('click', closeLightbox);
    }
    
    lightbox.addEventListener('click', function(e) {
      if (e.target === lightbox) {
        closeLightbox();
      }
    });
    
    // Close with ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && lightbox.classList.contains('active')) {
        closeLightbox();
      }
    });
    
    function closeLightbox() {
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
    }
  }
  
  /* === FORM VALIDATION === */
  
  // Quick Booking Form
  const quickBookingForm = document.getElementById('quick-booking-form');
  if (quickBookingForm) {
    quickBookingForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (validateForm(this)) {
        submitForm(this, 'backend/booking-handler.php');
      }
    });
  }
  
  // Full Booking Form
  const bookingForm = document.getElementById('booking-form');
  if (bookingForm) {
    bookingForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (validateForm(this)) {
        submitForm(this, 'backend/booking-handler.php');
      }
    });
  }
  
  // Contact Form
  const contactForm = document.getElementById('contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (validateForm(this)) {
        submitForm(this, 'backend/contact-handler.php');
      }
    });
  }
  
  // Login Form
  const loginForm = document.getElementById('login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      if (validateForm(this)) {
        submitForm(this, 'backend/login-handler.php');
      }
    });
  }
  
  // Newsletter Form
  const newsletterForm = document.getElementById('newsletter-form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const email = this.querySelector('input[type="email"]').value;
      if (validateEmail(email)) {
        alert('Thank you for subscribing to our newsletter!');
        this.reset();
      } else {
        alert('Please enter a valid email address.');
      }
    });
  }
  
  /* === FORM VALIDATION FUNCTIONS === */
  
  function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    
    inputs.forEach(input => {
      if (!input.value.trim()) {
        showError(input, 'This field is required');
        isValid = false;
      } else if (input.type === 'email' && !validateEmail(input.value)) {
        showError(input, 'Please enter a valid email address');
        isValid = false;
      } else if (input.type === 'tel' && !validatePhone(input.value)) {
        showError(input, 'Please enter a valid phone number');
        isValid = false;
      } else {
        clearError(input);
      }
    });
    
    // Date validation for booking forms
    const checkIn = form.querySelector('input[name="check_in"]');
    const checkOut = form.querySelector('input[name="check_out"]');
    
    if (checkIn && checkOut) {
      const checkInDate = new Date(checkIn.value);
      const checkOutDate = new Date(checkOut.value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      
      if (checkInDate < today) {
        showError(checkIn, 'Check-in date cannot be in the past');
        isValid = false;
      }
      
      if (checkOutDate <= checkInDate) {
        showError(checkOut, 'Check-out date must be after check-in date');
        isValid = false;
      }
    }
    
    return isValid;
  }
  
  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }
  
  function validatePhone(phone) {
    const re = /^[\d\s\-\+\(\)]{10,}$/;
    return re.test(phone);
  }
  
  function showError(input, message) {
    clearError(input);
    input.style.borderColor = '#dc3545';
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-text';
    errorDiv.style.color = '#dc3545';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    input.parentElement.appendChild(errorDiv);
  }
  
  function clearError(input) {
    input.style.borderColor = '';
    const errorText = input.parentElement.querySelector('.error-text');
    if (errorText) {
      errorText.remove();
    }
  }
  
  /* === AJAX FORM SUBMISSION === */
  
  function submitForm(form, url) {
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner"></span> Sending...';
    
    fetch(url, {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
      
      if (data.success) {
        showMessage(form, data.message, 'success');
        form.reset();
        
        // Redirect if URL provided
        if (data.redirect) {
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 2000);
        }
      } else {
        showMessage(form, data.message || 'An error occurred. Please try again.', 'error');
      }
    })
    .catch(error => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
      showMessage(form, 'An error occurred. Please try again.', 'error');
      console.error('Error:', error);
    });
  }
  
  function showMessage(form, message, type) {
    // Remove existing messages
    const existingMsg = form.querySelector('.form-message');
    if (existingMsg) {
      existingMsg.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = `form-message ${type}-message`;
    messageDiv.textContent = message;
    form.insertBefore(messageDiv, form.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
      messageDiv.style.opacity = '0';
      setTimeout(() => messageDiv.remove(), 300);
    }, 5000);
  }
  
  /* === SCROLL ANIMATIONS === */
  
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };
  
  const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, observerOptions);
  
  // Observe elements with fade-in animation
  const animatedElements = document.querySelectorAll('.apartment-card, .amenity-card, .gallery-item');
  animatedElements.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });
  
  /* === APARTMENT INQUIRY === */
  
  const inquiryButtons = document.querySelectorAll('.btn-inquiry');
  inquiryButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const apartmentType = this.getAttribute('data-apartment');
      
      // Scroll to booking form
      const bookingSection = document.querySelector('#booking-section');
      if (bookingSection) {
        bookingSection.scrollIntoView({ behavior: 'smooth' });
        
        // Pre-select apartment type
        setTimeout(() => {
          const apartmentSelect = document.querySelector('select[name="apartment_type"]');
          if (apartmentSelect && apartmentType) {
            apartmentSelect.value = apartmentType;
          }
        }, 500);
      } else {
        // Redirect to booking page
        window.location.href = `book.php?type=${apartmentType}`;
      }
    });
  });
  
  /* === AUTO-FILL APARTMENT TYPE FROM URL === */
  
  const urlParams = new URLSearchParams(window.location.search);
  const apartmentType = urlParams.get('type');
  if (apartmentType) {
    const apartmentSelect = document.querySelector('select[name="apartment_type"]');
    if (apartmentSelect) {
      apartmentSelect.value = apartmentType;
    }
  }
  
  /* === COUNTER ANIMATION (if needed) === */
  
  function animateCounter(element, target, duration = 2000) {
    let current = 0;
    const increment = target / (duration / 16);
    const timer = setInterval(() => {
      current += increment;
      if (current >= target) {
        element.textContent = target;
        clearInterval(timer);
      } else {
        element.textContent = Math.floor(current);
      }
    }, 16);
  }
  
  /* === PARALLAX EFFECT (optional) === */
  
  window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallaxElements = document.querySelectorAll('.parallax');
    
    parallaxElements.forEach(element => {
      const speed = element.getAttribute('data-speed') || 0.5;
      element.style.transform = `translateY(${scrolled * speed}px)`;
    });
  });
  
  console.log('Desok Luxury Apartment - Website Initialized');
});
