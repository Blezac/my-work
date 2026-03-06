/**
 * Belzac Digital - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // Smooth header shadow on scroll
    const header = document.querySelector('.bd-header');
    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 20) {
                header.style.boxShadow = '0 2px 16px rgba(0,0,0,0.08)';
            } else {
                header.style.boxShadow = '0 1px 4px rgba(0,0,0,0.05)';
            }
        });
    }

    // Reveal sections on scroll
    const revealElements = document.querySelectorAll('.bd-service-card, .bd-value-item, .bd-service-meta-block');
    if ('IntersectionObserver' in window && revealElements.length > 0) {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        revealElements.forEach(function (el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(16px)';
            el.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
            observer.observe(el);
        });
    }

    // Contact form: client-side validation feedback
    const contactForm = document.getElementById('bd-contact-form');
    if (contactForm) {
        const inputs = contactForm.querySelectorAll('.bd-form-control');

        inputs.forEach(function (input) {
            input.addEventListener('blur', function () {
                validateField(input);
            });
        });
    }

    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;

        if (field.required && value === '') {
            isValid = false;
        }

        if (field.type === 'email' && value !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            isValid = emailRegex.test(value);
        }

        if (!isValid) {
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    }

});
