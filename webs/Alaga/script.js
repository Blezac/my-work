// Navigation Function
function showSection(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Show selected section
    const selectedSection = document.getElementById(sectionId);
    if (selectedSection) {
        selectedSection.classList.add('active');
        
        // Scroll to top of page
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
}

// Form Validation Function
function validateForm(formData) {
    const errors = [];
    
    // Required fields validation
    if (!formData.get('date')) {
        errors.push('Date is required');
    }
    
    if (!formData.get('time')) {
        errors.push('Time is required');
    }
    
    if (!formData.get('phone')) {
        errors.push('Phone number is required');
    }
    
    if (!formData.get('venue')) {
        errors.push('Venue is required');
    }
    
    if (!formData.get('service_type')) {
        errors.push('Service type is required');
    }
    
    if (!formData.get('groom_firstname')) {
        errors.push("Groom's first name is required");
    }
    
    if (!formData.get('groom_lastname')) {
        errors.push("Groom's last name is required");
    }
    
    if (!formData.get('bride_firstname')) {
        errors.push("Bride's first name is required");
    }
    
    if (!formData.get('bride_lastname')) {
        errors.push("Bride's last name is required");
    }
    
    if (!formData.get('social_media')) {
        errors.push('Please select social media sharing preference');
    }
    
    // Phone number validation
    const phone = formData.get('phone');
    if (phone && !/^[0-9]{11}$/.test(phone)) {
        errors.push('Phone number must be 11 digits');
    }
    
    return errors;
}

// Show Alert Function
function showAlert(message, type) {
    const alertBox = document.getElementById('alertBox');
    alertBox.className = 'alert ' + type;
    alertBox.textContent = message;
    alertBox.style.display = 'block';
    
    // Scroll to alert
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Auto hide after 5 seconds
    setTimeout(() => {
        alertBox.style.display = 'none';
    }, 5000);
}

// Form Submit Handler with AJAX
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(bookingForm);
            
            // Validate form
            const errors = validateForm(formData);
            
            if (errors.length > 0) {
                showAlert(errors.join(', '), 'error');
                return;
            }
            
            // Show loading state
            const submitBtn = bookingForm.querySelector('.submit-btn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'SUBMITTING...';
            submitBtn.disabled = true;
            
            // Submit form via AJAX
            fetch('submit_booking.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    bookingForm.reset();
                    
                    // Optionally redirect or show confirmation
                    setTimeout(() => {
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    }, 2000);
                } else {
                    showAlert(data.message || 'An error occurred. Please try again.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while submitting the form. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Auto-hide alerts when clicking anywhere
document.addEventListener('click', function(e) {
    const alertBox = document.getElementById('alertBox');
    if (alertBox && alertBox.style.display === 'block' && !alertBox.contains(e.target)) {
        alertBox.style.display = 'none';
    }
});

// Form field auto-formatting
document.addEventListener('DOMContentLoaded', function() {
    // Format phone number input
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric characters
            this.value = this.value.replace(/\D/g, '');
            
            // Limit to 11 digits
            if (this.value.length > 11) {
                this.value = this.value.slice(0, 11);
            }
        });
    }
    
    // Capitalize first letter of name fields
    const nameInputs = document.querySelectorAll('input[name*="firstname"], input[name*="lastname"], input[name*="name"]');
    nameInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value) {
                this.value = this.value.split(' ')
                    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                    .join(' ');
            }
        });
    });
});

// Smooth scroll for navigation
document.querySelectorAll('.nav a').forEach(link => {
    link.addEventListener('click', function() {
        // Add active class to clicked nav item
        document.querySelectorAll('.nav a').forEach(a => a.classList.remove('active'));
        this.classList.add('active');
    });
});

// Add animation on scroll
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

// Observe all service cards
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.service-card');
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
});

// Print booking function (optional feature)
function printBooking() {
    window.print();
}

// Export form data as JSON (optional feature for admin)
function exportFormData() {
    const form = document.getElementById('bookingForm');
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    const dataStr = JSON.stringify(data, null, 2);
    const dataBlob = new Blob([dataStr], { type: 'application/json' });
    const url = URL.createObjectURL(dataBlob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'booking_data.json';
    link.click();
}

// Payment Modal Functions
function showPaymentModal(trainingType, amount) {
    const modal = document.getElementById('paymentModal');
    const typeDisplay = document.getElementById('trainingType');
    const amountDisplay = document.getElementById('trainingAmount');
    const hiddenType = document.getElementById('hiddenTrainingType');
    const hiddenAmount = document.getElementById('hiddenAmount');
    
    // Set values
    typeDisplay.textContent = trainingType === 'beginner' ? 'Beginners Class' : 'Masterclass';
    amountDisplay.textContent = amount.toLocaleString();
    hiddenType.value = trainingType;
    hiddenAmount.value = amount;
    
    // Show modal
    modal.style.display = 'block';
}

function closePaymentModal() {
    const modal = document.getElementById('paymentModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('paymentModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

// Handle payment form submission
document.addEventListener('DOMContentLoaded', function() {
    const paymentForm = document.getElementById('paymentForm');
    
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(paymentForm);
            const submitBtn = paymentForm.querySelector('.submit-btn');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'SUBMITTING...';
            submitBtn.disabled = true;
            
            fetch('submit_payment.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    closePaymentModal();
                    paymentForm.reset();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting payment. Please try again.');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Check form completeness indicator
function updateFormProgress() {
    const form = document.getElementById('bookingForm');
    if (!form) return;
    
    const requiredFields = form.querySelectorAll('[required]');
    let filledFields = 0;
    
    requiredFields.forEach(field => {
        if (field.value.trim() !== '') {
            filledFields++;
        }
    });
    
    const progress = (filledFields / requiredFields.length) * 100;
    console.log(`Form completion: ${progress.toFixed(0)}%`);
    
    return progress;
}

// Add event listeners to track form progress
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bookingForm');
    if (form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('change', updateFormProgress);
        });
    }
});