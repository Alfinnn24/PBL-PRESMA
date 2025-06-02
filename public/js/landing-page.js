// Loading animation
window.addEventListener('load', function() {
  const loadingOverlay = document.getElementById('loadingOverlay');
  setTimeout(() => {
    loadingOverlay.classList.add('fade-out');
    setTimeout(() => {
      loadingOverlay.style.display = 'none';
    }, 500);
  }, 1000);
});

// Enhanced Scroll Animations with Intersection Observer
const observerOptions = {
  threshold: 0.1,
  rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      // Add a small delay for smoother effect
      setTimeout(() => {
        entry.target.style.willChange = 'auto';
      }, 1000);
    }
  });
}, observerOptions);

// Observe all animated elements
document.querySelectorAll('.animate-on-scroll').forEach((el) => {
  observer.observe(el);
});

// Navbar scroll effect
const navbar = document.getElementById('navbar');
let lastScrollTop = 0;

window.addEventListener('scroll', () => {
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  
  if (scrollTop > 100) {
    navbar.classList.add('scrolled');
  } else {
    navbar.classList.remove('scrolled');
  }
  
  lastScrollTop = scrollTop;
}, { passive: true });

// Smooth scroll function
function smoothScrollTo(target) {
  const element = document.querySelector(target);
  if (element) {
    element.scrollIntoView({
      behavior: 'smooth',
      block: 'start'
    });
  }
}

// Enhanced parallax effect
let ticking = false;

function updateParallax() {
  const scrolled = window.pageYOffset;
  const parallaxElements = document.querySelectorAll('.parallax');
  
  parallaxElements.forEach(element => {
    const speed = element.dataset.speed || 0.5;
    const yPos = -(scrolled * speed);
    element.style.transform = `translateY(${yPos}px)`;
  });
  
  ticking = false;
}

window.addEventListener('scroll', () => {
  if (!ticking) {
    requestAnimationFrame(updateParallax);
    ticking = true;
  }
}, { passive: true });

// Interactive hover effects for cards
document.querySelectorAll('.interactive-card').forEach(card => {
  card.addEventListener('mouseenter', function() {
    this.style.transition = 'all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
  });
  
  card.addEventListener('mouseleave', function() {
    this.style.transition = 'all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
  });
});

// Mouse tracking effect for hero section
const hero = document.querySelector('.hero');
if (hero) {
  hero.addEventListener('mousemove', (e) => {
    const rect = hero.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    const deltaX = (x - centerX) / centerX;
    const deltaY = (y - centerY) / centerY;
    
    const heroContent = hero.querySelector('div');
    if (heroContent) {
      heroContent.style.transform = `translate(${deltaX * 10}px, ${deltaY * 10}px)`;
    }
  });
  
  hero.addEventListener('mouseleave', () => {
    const heroContent = hero.querySelector('div');
    if (heroContent) {
      heroContent.style.transform = 'translate(0, 0)';
    }
  });
}

// Smooth button interactions
document.querySelectorAll('button, .btn, .nav-link').forEach(element => {
  element.addEventListener('mouseenter', function() {
    this.style.transform = this.style.transform + ' scale(1.02)';
  });
  
  element.addEventListener('mouseleave', function() {
    this.style.transform = this.style.transform.replace(' scale(1.02)', '');
  });
});

// Page visibility API for performance
document.addEventListener('visibilitychange', () => {
  if (document.hidden) {
    // Pause animations when page is not visible
    document.body.style.animationPlayState = 'paused';
  } else {
    // Resume animations when page becomes visible
    document.body.style.animationPlayState = 'running';
  }
});

// Reduce motion for accessibility
if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  document.querySelectorAll('.animate-on-scroll').forEach(el => {
    el.style.transition = 'opacity 0.3s ease';
  });
}