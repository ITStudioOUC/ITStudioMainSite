(() => {
  const steps = document.querySelectorAll('.intro-step');
  if (!steps.length) return;

  const markActive = () => {
    const vh = window.innerHeight;
    steps.forEach((step) => {
      const rect = step.getBoundingClientRect();
      const isActive = rect.top < vh * 0.8 && rect.bottom > vh * 0.2;
      step.classList.toggle('is-active', isActive);
    });
  };

  markActive();
  document.body.classList.add('has-intro-animations');

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        entry.target.classList.toggle('is-active', entry.isIntersecting);
      });
    },
    {
      threshold: 0.35,
      rootMargin: '0px 0px -15% 0px',
    }
  );

  steps.forEach((step) => observer.observe(step));
  window.addEventListener('resize', markActive);
})();
