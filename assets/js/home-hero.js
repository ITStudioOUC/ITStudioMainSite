(() => {
  const hero = document.querySelector('.hero-section');
  if (!hero) return;

  const body = document.body;
  const header = document.querySelector('.site-header');
  const titleSvg = hero.querySelector('.hero-title-svg');
  const scrollIndicator = hero.querySelector('.hero-scroll-indicator');
  const scrollArrow = scrollIndicator ? scrollIndicator.querySelector('.scroll-arrow') : null;
  const scrollText = scrollIndicator ? scrollIndicator.querySelector('.scroll-text') : null;

  const desktopQuery = window.matchMedia('(min-width: 900px)');
  const reducedMotionQuery = window.matchMedia('(prefers-reduced-motion: reduce)');
  const anime = window.anime;
  const hasAnime = typeof anime === 'function';

  let arrowPulse = null;
  let didIntro = false;
  let ticking = false;

  const stopArrowPulse = () => {
    if (!arrowPulse) return;
    arrowPulse.pause();
    arrowPulse = null;
  };

  const startArrowPulse = () => {
    if (!hasAnime || reducedMotionQuery.matches || !scrollArrow) return;
    stopArrowPulse();
    scrollArrow.style.animation = 'none';
    anime.remove(scrollArrow);
    anime.set(scrollArrow, {
      translateY: 0,
      opacity: 0.5,
      rotate: 45,
    });
    arrowPulse = anime({
      targets: scrollArrow,
      translateY: [0, 6, 0],
      opacity: [0.5, 1, 0.5],
      rotate: 45,
      duration: 1600,
      easing: 'easeInOutSine',
      loop: true,
    });
  };

  const clearInlineState = () => {
    if (header) {
      header.style.transform = '';
    }
    if (scrollIndicator) {
      scrollIndicator.style.opacity = '';
      scrollIndicator.style.transform = '';
    }
  };

  const animateScrollState = (isAtTop) => {
    if (!hasAnime || reducedMotionQuery.matches) return;

    if (header) {
      anime.remove(header);
      anime({
        targets: header,
        translateY: isAtTop ? '-100%' : '0%',
        duration: isAtTop ? 500 : 380,
        easing: isAtTop ? 'easeInOutCubic' : 'easeOutCubic',
      });
    }

    if (scrollIndicator) {
      anime.remove(scrollIndicator);
      anime({
        targets: scrollIndicator,
        opacity: isAtTop ? 1 : 0,
        duration: isAtTop ? 380 : 260,
        easing: isAtTop ? 'easeOutCubic' : 'easeOutQuad',
      });
    }
  };

  const applyState = (animated) => {
    if (!desktopQuery.matches) {
      body.classList.remove('home-hero-initial', 'home-hero-scrolled');
      stopArrowPulse();
      clearInlineState();
      return;
    }

    const isAtTop = window.scrollY <= 6;
    body.classList.toggle('home-hero-initial', isAtTop);
    body.classList.toggle('home-hero-scrolled', !isAtTop);

    if (animated) {
      animateScrollState(isAtTop);
    } else if (hasAnime) {
      if (header) {
        anime.remove(header);
      }
      if (scrollIndicator) {
        anime.remove(scrollIndicator);
      }
    }

    if (isAtTop) {
      startArrowPulse();
    } else {
      stopArrowPulse();
    }
  };

  const playIntro = () => {
    if (didIntro || !hasAnime || reducedMotionQuery.matches || !desktopQuery.matches || window.scrollY > 6) {
      return;
    }

    didIntro = true;
    if (titleSvg) {
      anime.set(titleSvg, { opacity: 0, translateY: 14, scale: 1.01 });
    }
    if (scrollArrow) {
      anime.set(scrollArrow, { opacity: 0, translateY: 8, rotate: 45 });
    }
    if (scrollText) {
      anime.set(scrollText, { opacity: 0, translateY: 8 });
    }

    const timeline = anime.timeline({
      easing: 'easeOutCubic',
    });

    if (titleSvg) {
      timeline.add({
        targets: titleSvg,
        opacity: 1,
        translateY: -8,
        scale: 1.06,
        duration: 860,
        easing: 'easeOutExpo',
      });
    }

    if (scrollArrow || scrollText) {
      timeline.add({
        targets: [scrollArrow, scrollText].filter(Boolean),
        opacity: (el) => (el === scrollArrow ? [0, 1] : [0, 1]),
        translateY: 0,
        rotate: (el) => (el === scrollArrow ? 45 : 0),
        duration: 420,
        delay: anime.stagger(80),
      }, '-=300');
    }

    timeline.finished.then(() => {
      startArrowPulse();
    });
  };

  const onScroll = () => {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(() => {
      applyState(true);
      ticking = false;
    });
  };

  const onResize = () => {
    applyState(false);
  };

  const onReducedMotionChange = () => {
    if (reducedMotionQuery.matches) {
      stopArrowPulse();
      if (scrollArrow) {
        scrollArrow.style.animation = 'none';
      }
    } else if (window.scrollY <= 6 && desktopQuery.matches) {
      startArrowPulse();
    }
    applyState(false);
  };

  applyState(false);
  playIntro();

  window.addEventListener('scroll', onScroll, { passive: true });
  window.addEventListener('resize', onResize);
  desktopQuery.addEventListener('change', onResize);
  reducedMotionQuery.addEventListener('change', onReducedMotionChange);
})();
