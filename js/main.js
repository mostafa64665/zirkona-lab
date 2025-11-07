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

// optional: close menu when clicking a link
document.querySelectorAll('#mobile-menu .mobile-link').forEach(link => {
    link.addEventListener('click', () => menu.classList.add('hidden'));
});



// Add loading animation for images
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

// Observe sections for scroll animations
document.querySelectorAll('section').forEach(section => {
    observer.observe(section);
});