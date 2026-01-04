

// Initialize Embla Carousel with Autoplay
window.addEventListener('load', () => {
  const emblaNode = document.querySelector('.embla');

  if (emblaNode && window.EmblaCarousel && window.EmblaCarouselAutoplay) {
    const autoplay = EmblaCarouselAutoplay({
      delay: 4000,
      stopOnInteraction: false,
    });

    if (emblaNode) {
      const embla = EmblaCarousel(emblaNode, { loop: true, speed: 6 }, [autoplay]);
    }
  }
});

// Initialize BeerSlider for before/after comparisons with enhanced performance
window.addEventListener("DOMContentLoaded", () => {
  const sliders = document.querySelectorAll(".beer-slider, .compare-item");

  sliders.forEach((slider) => {
    if (!window.BeerSlider) return;

    const beer = new BeerSlider(slider, { start: 50 });
    const reveal = slider.querySelector(".beer-reveal");
    const handle = slider.querySelector(".beer-handle");
    if (!reveal || !handle) return;

    let lastPercent = 50;
    let animationFrame;

    const updateSlider = (percent) => {
      cancelAnimationFrame(animationFrame);
      animationFrame = requestAnimationFrame(() => {
        reveal.style.width = percent + "%";
        handle.style.left = percent + "%";
      });
    };

    // --- Mouse Events ---
    slider.addEventListener("mousemove", (e) => {
      const rect = slider.getBoundingClientRect();
      const percent = Math.min(
        100,
        Math.max(0, ((e.clientX - rect.left) / rect.width) * 100)
      );
      lastPercent = percent;
      updateSlider(percent);
    });

    slider.addEventListener("mouseleave", () => {
      updateSlider(lastPercent);
    });

    // --- Touch Events (Mobile) ---
    slider.addEventListener("touchmove", (e) => {
      if (!e.touches.length) return;
      const touch = e.touches[0];
      const rect = slider.getBoundingClientRect();
      const percent = Math.min(
        100,
        Math.max(0, ((touch.clientX - rect.left) / rect.width) * 100)
      );
      lastPercent = percent;
      updateSlider(percent);
    });

    slider.addEventListener("touchend", () => {
      updateSlider(lastPercent);
    });
  });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const href = this.getAttribute('href');
    if (href !== '#') {
      e.preventDefault();
      const target = document.querySelector(href);
      if (target) {
        const navHeight = 64; // Height of fixed nav
        const targetPosition = target.offsetTop - navHeight;
        window.scrollTo({
          top: targetPosition,
          behavior: 'smooth'
        });
      }
    }
  });
});

// Mobile menu functionality
const btn = document.getElementById('mobile-toggle');
const menu = document.getElementById('mobile-menu');

if (btn && menu) {
  btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });

  // Close menu when clicking on links
  document.querySelectorAll('#mobile-menu a').forEach(link => {
    link.addEventListener('click', () => menu.classList.add('hidden'));
  });
}



document.querySelectorAll('img[loading="lazy"]').forEach(img => {
  img.addEventListener('load', function () {
    this.classList.add('loaded');
  });
});


// Intersection Observer for animations
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('animate-fade-in');
    }
  });
}, observerOptions);

document.querySelectorAll('section').forEach(section => {
  observer.observe(section);
});



window.addEventListener('DOMContentLoaded', () => {
  localStorage.removeItem('formData');
  localStorage.removeItem('orderData');
  localStorage.removeItem('zirkonaCart');
  
  // Initialize cart badge
  updateCartBadge();
});

// Cart badge functionality for all pages
function updateCartBadge() {
  const cart = JSON.parse(localStorage.getItem('zirkonaCart')) || [];
  const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
  const badge = document.querySelector('.cart-badge');
  
  if (badge) {
    badge.textContent = totalItems;
    badge.style.display = totalItems > 0 ? 'flex' : 'none';
  }
}

// Global toast notification function
function showToast(message, type = 'success') {
  // Remove existing notifications
  const existing = document.querySelectorAll('.toast-notification');
  existing.forEach(toast => toast.remove());

  // Create toast
  const toast = document.createElement('div');
  toast.className = `toast-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform translate-x-full transition-all duration-300 max-w-sm ${
      type === 'success' ? 'bg-green-500 text-white' : 'bg-orange-500 text-white'
  }`;
  
  const icon = type === 'success' 
      ? `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
         </svg>`
      : `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"/>
         </svg>`;

  toast.innerHTML = `
      <div class="flex items-center gap-3">
          ${icon}
          <span class="text-sm font-medium">${message}</span>
          <button class="close-toast ml-2 hover:bg-white hover:bg-opacity-20 rounded p-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
          </button>
      </div>
  `;

  document.body.appendChild(toast);

  // Animate in
  setTimeout(() => {
      toast.style.transform = 'translateX(0)';
  }, 100);

  // Close function
  const closeToast = () => {
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 300);
  };

  // Close button
  toast.querySelector('.close-toast').addEventListener('click', closeToast);

  // Auto close after 3 seconds
  setTimeout(closeToast, 3000);
}

// Appointment form handling
const Fname = document.getElementById('Fname');
const Lname = document.getElementById('Lname');
const email = document.getElementById('Email');
const phone = document.getElementById('Phone');
const message = document.getElementById('message');
const btnSubmit = document.getElementById('btnSubmit');

// Only run appointment form code if elements exist
if (Fname && Lname && email && phone && btnSubmit) {
  const nameRegex = /^[A-Za-z\u0600-\u06FF\s]{2,}$/; // Allow Arabic names
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const phoneRegex = /^(\+?\d{1,3}[- ]?)?\d{5,12}$/;

  function validateForm() {
    const firstNameValid = Fname.value.trim().length >= 2;
    const lastNameValid = Lname.value.trim().length >= 2;
    const emailValid = emailRegex.test(email.value.trim());
    const phoneValid = phoneRegex.test(phone.value.trim());
    
    return firstNameValid && lastNameValid && emailValid && phoneValid;
  }

  function toggleSubmitBtn() {
    const isValid = validateForm();
    btnSubmit.disabled = !isValid;
    
    if (isValid) {
      btnSubmit.style.opacity = '1';
      btnSubmit.style.cursor = 'pointer';
    } else {
      btnSubmit.style.opacity = '0.65';
      btnSubmit.style.cursor = 'not-allowed';
    }
  }

  // Add event listeners for real-time validation
  [Fname, Lname, email, phone].forEach(input => {
    if (input) {
      input.addEventListener('input', toggleSubmitBtn);
      input.addEventListener('blur', toggleSubmitBtn);
    }
  });

  // Initial state
  toggleSubmitBtn();

  btnSubmit.addEventListener('click', function (e) {
    e.preventDefault();
    
    if (!validateForm()) {
      alert('يرجى ملء جميع الحقول المطلوبة بشكل صحيح');
      return;
    }

    // Clear previous data
    localStorage.removeItem('formData');
    localStorage.removeItem('orderData');
    localStorage.removeItem('zirkonaCart');

    const formData = {
      firstName: Fname.value.trim(),
      lastName: Lname.value.trim(),
      email: email.value.trim(),
      phone: phone.value.trim(),
      message: message ? message.value.trim() : ''
    };

    localStorage.setItem('formData', JSON.stringify(formData));
    
    // Show success message
    alert("تم حفظ البيانات بنجاح! سيتم توجيهك لصفحة الأسعار");
    
    // Redirect to pricing page
    window.location.href = "pricing.html";
  });
}