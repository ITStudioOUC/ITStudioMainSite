(() => {
  const hero = document.querySelector('.hero-section');
  if (!hero) return;

  const body = document.body;
  const desktopQuery = window.matchMedia('(min-width: 900px)');
  let hasScrolled = false;

  const applyState = () => {
    if (!desktopQuery.matches) {
      body.classList.remove('home-hero-initial', 'home-hero-scrolled');
      return;
    }

    if (hasScrolled || window.scrollY > 6) {
      body.classList.remove('home-hero-initial');
      body.classList.add('home-hero-scrolled');
    } else {
      body.classList.add('home-hero-initial');
      body.classList.remove('home-hero-scrolled');
    }
  };

  const onScroll = () => {
    hasScrolled = window.scrollY > 6;
    applyState();
  };

  const onResize = () => {
    if (!desktopQuery.matches) {
      hasScrolled = false;
    }
    applyState();
  };

  applyState();
  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onResize);
  desktopQuery.addEventListener('change', onResize);
})();
