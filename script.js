// Initialize AOS Animation Library
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    mirror: false
});

// Mobile Menu Toggle
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.querySelector('.mobile-menu');
    const navLinks = document.querySelector('.nav-links');
    let isMenuOpen = false;

    // Improved click handling for mobile menu
    mobileMenuBtn.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        isMenuOpen = !isMenuOpen;
        navLinks.classList.toggle('active');
        mobileMenuBtn.classList.toggle('active');
        document.body.style.overflow = isMenuOpen ? 'hidden' : '';
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (isMenuOpen && !mobileMenuBtn.contains(e.target) && !navLinks.contains(e.target)) {
            isMenuOpen = false;
            navLinks.classList.remove('active');
            mobileMenuBtn.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Close menu when clicking a link
    const menuItems = document.querySelectorAll('.nav-links a');
    menuItems.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 850) {
                isMenuOpen = false;
                navLinks.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Form validation and enhancement
    const form = document.getElementById('repair-form');
    if (form) {
        // Helper: clear previous errors
        function clearErrors() {
            form.querySelectorAll('.field-error').forEach(el => el.remove());
            form.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        }
        // Helper: show error below field
        function showError(input, message) {
            let error = document.createElement('div');
            error.className = 'field-error';
            error.textContent = message;
            // Append error to the .form-group container
            const group = input.closest('.form-group');
            if (group) {
                group.appendChild(error);
            } else {
                input.parentNode.appendChild(error);
            }
            input.classList.add('input-error');
        }
        // Clear errors on input
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', clearErrors);
        });
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();
            const formData = new FormData(form);
            const name = formData.get('name')?.trim();
            const phone = formData.get('phone')?.trim();
            const address = formData.get('address')?.trim();
            const brand = formData.get('brand')?.trim();
            const appliance = formData.get('appliance')?.trim();
            const problem = formData.get('problem')?.trim();
            const email = formData.get('email')?.trim();
            let hasError = false;
            if (!name || name.length < 2) {
                showError(form.querySelector('[name="name"]'), 'Please enter your full name');
                hasError = true;
            }
            if (!phone || !/^[0-9]{10}$/.test(phone)) {
                showError(form.querySelector('[name="phone"]'), 'Please enter a valid 10-digit mobile number');
                hasError = true;
            }
            if (!address || address.length < 5) {
                showError(form.querySelector('[name="address"]'), 'Please enter your complete service address');
                hasError = true;
            }
            if (!brand) {
                showError(form.querySelector('[name="brand"]'), 'Please select your appliance brand');
                hasError = true;
            }
            if (!appliance) {
                showError(form.querySelector('[name="appliance"]'), 'Please select the type of appliance');
                hasError = true;
            }
            if (!problem || problem.length < 10) {
                showError(form.querySelector('[name="problem"]'), 'Please provide a detailed description of the problem (minimum 10 characters)');
                hasError = true;
            }
            if (!email) {
                showError(form.querySelector('[name="email"]'), 'Please enter your email address');
                hasError = true;
            } else if (!/^\S+@\S+\.\S+$/.test(email)) {
                showError(form.querySelector('[name="email"]'), 'Please enter a valid email address');
                hasError = true;
            }
            if (hasError) return;

            // Get form data
            const details = `
              <b>Name:</b> ${name}<br>
              <b>Phone:</b> ${phone}<br>
              <b>Address:</b> ${address}<br>
              <b>Brand:</b> ${brand}<br>
              <b>Appliance:</b> ${appliance}<br>
              <b>Problem:</b> ${problem}<br>
              <b>Email:</b> ${email}
            `;

            // Show progress modal
            Swal.fire({
              title: 'Processing your booking...',
              html: 'Please wait while we submit your request.',
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              }
            });

            // Send data to PHP via AJAX
            fetch('booking-mail.php', {

              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              Swal.fire({
                icon: 'success',
                title: 'Booking Confirmed!',
                html: `
                  <div class="swal-booking-details">
                    <p class="swal-booking-message">Thank you for choosing <b>Repair Service Same Day</b>!<br>Your booking has been received.</p>
                    <div class="swal-booking-divider"></div>
                    <div class="swal-booking-table">
                      <span>Name:</span> <span>${name}</span>
                      <span>Phone:</span> <span>${phone}</span>
                      <span>Address:</span> <span>${address}</span>
                      <span>Brand:</span> <span>${brand}</span>
                      <span>Appliance:</span> <span>${appliance}</span>
                      <span>Problem:</span> <span>${problem}</span>
                      <span>Email:</span> <span>${email}</span>
                    </div>
                    <div class="swal-booking-footer">We'll contact you soon to confirm your appointment.</div>
                  </div>
                `,
                confirmButtonText: 'OK',
                customClass: {
                  popup: 'swal2-booking-popup',
                  title: 'swal2-booking-title',
                  confirmButton: 'swal2-booking-btn'
                }
              });
              if (data.success) form.reset();
            })
            .catch(() => {
              Swal.fire('Error', 'Could not submit your booking. Please try again.', 'error');
            });
        });
    }

    // Close menu when clicking the close button in mobile menu
    const closeMenuBtn = document.querySelector('.close-menu');
    if (closeMenuBtn) {
        closeMenuBtn.addEventListener('click', (e) => {
            e.preventDefault();
            isMenuOpen = false;
            navLinks.classList.remove('active');
            mobileMenuBtn.classList.remove('active');
            mobileMenuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            document.body.style.overflow = '';
        });
    }

    // Back to Top Button functionality
    const backToTopBtn = document.querySelector('.back-to-top');
    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Add fixed header class on scroll
const header = document.querySelector('header');
let lastScroll = 0;

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        header.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
        header.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.backgroundColor = 'var(--white)';
        header.style.boxShadow = 'none';
    }

    lastScroll = currentScroll;
});

// Book Now button functionality
const bookNowBtn = document.querySelector('.cta-button');
bookNowBtn.addEventListener('click', () => {
    const bookingForm = document.querySelector('.booking-form');
    bookingForm.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}); 