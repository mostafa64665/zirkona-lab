

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

const btn = document.getElementById('mobile-toggle');
const menu = document.getElementById('mobile-menu');

btn.addEventListener('click', () => {
  menu.classList.toggle('hidden');
});

document.querySelectorAll('#mobile-menu .mobile-link').forEach(link => {
  link.addEventListener('click', () => menu.classList.add('hidden'));
});



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
});

const Fname = document.getElementById('Fname');
const Lname = document.getElementById('Lname');
const email = document.getElementById('Email');
const phone = document.getElementById('Phone');
const message = document.getElementById('message');
const btnSubmit = document.getElementById('btnSubmit');

const nameRegex = /^[A-Za-z]{2,}$/;
const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const phoneRegex = /^(\+?\d{1,3}[- ]?)?\d{5,12}$/;

function validateForm() {
  return (
    nameRegex.test(Fname.value.trim()) &&
    nameRegex.test(Lname.value.trim()) &&
    emailRegex.test(email.value.trim()) &&
    phoneRegex.test(phone.value.trim())
  );
}

function toggleSubmitBtn() {
  btnSubmit.disabled = !validateForm();
}

[Fname, Lname, email, phone, message].forEach(input => {
  if (input) input.addEventListener('input', toggleSubmitBtn);
});

if (btnSubmit) {
  btnSubmit.disabled = true;

  btnSubmit.addEventListener('click', function (e) {
    e.preventDefault();
    if (!validateForm()) return;

    localStorage.removeItem('formData');
    localStorage.removeItem('orderData');
    localStorage.removeItem('zirkonaCart');

    const formData = {
      firstName: Fname.value.trim(),
      lastName: Lname.value.trim(),
      email: email.value.trim(),
      phone: phone.value.trim(),
      message: message.value.trim()
    };

    localStorage.setItem('formData', JSON.stringify(formData));
    alert("Saved Successfully!");
    window.location.href = "pricing.html";
  });
}