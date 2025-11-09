// public/js/main.js
document.addEventListener('DOMContentLoaded', function() {
  // AOS init (if used)
  if (window.AOS) AOS.init();

  // GSAP simple cards animation
  if (window.gsap) {
    gsap.from('.card', { duration: 0.6, y: 20, opacity: 0, stagger: 0.08 });
  }
});
