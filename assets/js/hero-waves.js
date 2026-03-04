(() => {
  const main = document.querySelector('.site-main');
  const heroSection = document.querySelector('.hero-section');
  const servicesSection = document.querySelector('.services-section');
  const canvas = document.querySelector('.hero-waves');
  if (!main || !canvas) return;

  const ctx = canvas.getContext('2d');
  if (!ctx) return;

  const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)');
  let width = 0;
  let height = 0;
  let dpr = 1;
  let rafId = null;

  const waveDefs = [
    { yRatio: 0.3, amplitude: 24, wavelength: 940, speed: 0.5, alpha: 0.98 },
    { yRatio: 0.45, amplitude: 14, wavelength: 1020, speed: 0.58, alpha: 0.92 },
    { yRatio: 0.65, amplitude: 10, wavelength: 1160, speed: 0.6, alpha: 0.9 },
  ];

  const palettes = {
    light: {
      gradient: ['#d6e8fa', '#a7c8e8', '#6f9fc8', '#4f84b6', '#376e9f'],
      waves: ['rgba(124, 167, 205, 0.96)', 'rgba(90, 143, 190, 0.97)', 'rgba(62, 118, 169, 0.98)'],
    },
    dark: {
      gradient: ['#0d1117', '#111d2b', '#14334d', '#17507a', '#1b6aa6'],
      waves: ['rgba(90, 165, 225, 0.9)', 'rgba(65, 140, 210, 0.88)', 'rgba(50, 120, 190, 0.86)'],
    },
  };

  const getTheme = () => (document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light');

  const getCoverageHeight = () => {
    if (servicesSection) {
      const coverage = servicesSection.offsetTop + servicesSection.offsetHeight * 0.5;
      return Math.min(coverage, main.scrollHeight);
    }
    if (heroSection) {
      return heroSection.offsetHeight;
    }
    return main.scrollHeight;
  };

  const resize = () => {
    dpr = window.devicePixelRatio || 1;
    width = main.clientWidth;
    height = Math.max(1, Math.round(getCoverageHeight()));
    canvas.width = Math.floor(width * dpr);
    canvas.height = Math.floor(height * dpr);
    canvas.style.width = `${width}px`;
    canvas.style.height = `${height}px`;
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  };

  const drawBackground = (palette) => {
    const gradient = ctx.createLinearGradient(0, 0, 0, height);
    gradient.addColorStop(0, palette.gradient[0]);
    gradient.addColorStop(0.35, palette.gradient[1]);
    gradient.addColorStop(0.55, palette.gradient[2]);
    gradient.addColorStop(0.75, palette.gradient[3]);
    gradient.addColorStop(1, palette.gradient[4]);
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, width, height);
  };

  const drawWave = (palette, wave, time, index) => {
    const yBase = height * wave.yRatio;
    const phase = time * wave.speed;
    const prevAlpha = ctx.globalAlpha;
    ctx.globalAlpha = wave.alpha ?? 1;
    ctx.beginPath();
    ctx.moveTo(0, height);
    for (let x = 0; x <= width; x += 18) {
      const theta = (x / wave.wavelength) * Math.PI * 2 + phase;
      const y = yBase + Math.sin(theta) * wave.amplitude;
      ctx.lineTo(x, y);
    }
    ctx.lineTo(width, height);
    ctx.closePath();
    ctx.fillStyle = palette.waves[index % palette.waves.length];
    ctx.fill();
    ctx.globalAlpha = prevAlpha;
  };

  const render = (timestamp) => {
    const palette = palettes[getTheme()];
    const time = timestamp * 0.001;
    ctx.clearRect(0, 0, width, height);
    drawBackground(palette);
    waveDefs.forEach((wave, index) => drawWave(palette, wave, time, index));
    rafId = requestAnimationFrame(render);
  };

  const stop = () => {
    if (rafId) {
      cancelAnimationFrame(rafId);
      rafId = null;
    }
  };

  const drawStatic = () => {
    const palette = palettes[getTheme()];
    ctx.clearRect(0, 0, width, height);
    drawBackground(palette);
    waveDefs.forEach((wave, index) => drawWave(palette, wave, 0, index));
  };

  const start = () => {
    stop();
    resize();
    if (prefersReduced.matches) {
      drawStatic();
      return;
    }
    rafId = requestAnimationFrame(render);
  };

  const onResize = () => {
    resize();
    if (prefersReduced.matches) {
      drawStatic();
    }
  };

  const themeObserver = new MutationObserver(() => {
    if (prefersReduced.matches) {
      drawStatic();
    }
  });

  themeObserver.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });
  prefersReduced.addEventListener('change', start);
  window.addEventListener('resize', onResize);
  window.addEventListener('load', onResize);

  start();
})();
