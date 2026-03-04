const themeToggles = document.querySelectorAll('.theme-toggle');
const html = document.documentElement;
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const supportsViewTransition = typeof document.startViewTransition === 'function';
const viewTransitionStyleId = 'itstudio-view-transition-style';
let isThemeTransitioning = false;

function ensureViewTransitionStyles() {
  if (!supportsViewTransition || document.getElementById(viewTransitionStyleId)) {
    return;
  }

  const style = document.createElement('style');
  style.id = viewTransitionStyleId;
  style.textContent = `
    ::view-transition-old(root),
    ::view-transition-new(root) {
      animation: none;
      mix-blend-mode: normal;
    }

    ::view-transition-group(root) {
      animation: none;
    }
  `;
  document.head.appendChild(style);
}

function updateLogoColor(theme) {
  const logoText = document.querySelector('#logo-text-cn');
  if (logoText) {
    logoText.setAttribute('fill', theme === 'dark' ? '#ffffff' : '#000000');
  }
}

function getPreferredTheme() {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme) {
    return savedTheme;
  }

  const systemPreference = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  return systemPreference;
}

function setTheme(theme) {
  html.setAttribute('data-theme', theme);
  localStorage.setItem('theme', theme);
  themeToggles.forEach((toggle) => {
    toggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
  });
  updateLogoColor(theme);
}

const initialTheme = getPreferredTheme();
setTheme(initialTheme);
ensureViewTransitionStyles();

function getToggleCenter(toggle) {
  if (!toggle) {
    return {
      x: window.innerWidth / 2,
      y: window.innerHeight / 2,
    };
  }

  const rect = toggle.getBoundingClientRect();
  return {
    x: rect.left + rect.width / 2,
    y: rect.top + rect.height / 2,
  };
}

function getTransitionOrigin(event, toggle) {
  const hasPointerPoint = Number.isFinite(event.clientX) && Number.isFinite(event.clientY) && (event.clientX !== 0 || event.clientY !== 0);
  if (hasPointerPoint) {
    return {
      x: event.clientX,
      y: event.clientY,
    };
  }

  return getToggleCenter(toggle);
}

function getRevealRadius(x, y) {
  const maxX = Math.max(x, window.innerWidth - x);
  const maxY = Math.max(y, window.innerHeight - y);
  return Math.hypot(maxX, maxY);
}

function switchThemeWithScatter(theme, origin) {
  if (isThemeTransitioning || prefersReducedMotion.matches || !supportsViewTransition) {
    setTheme(theme);
    return;
  }

  isThemeTransitioning = true;
  const x = origin.x;
  const y = origin.y;
  const endRadius = getRevealRadius(x, y);
  const clipFrom = `circle(0px at ${x}px ${y}px)`;
  const clipTo = `circle(${endRadius}px at ${x}px ${y}px)`;

  const transition = document.startViewTransition(() => {
    setTheme(theme);
  });

  transition.ready
    .then(() => {
      document.documentElement.animate(
        {
          clipPath: [clipFrom, clipTo],
        },
        {
          duration: 650,
          easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
          pseudoElement: '::view-transition-new(root)',
        }
      );
    })
    .catch(() => {})
    .finally(() => {
      transition.finished.finally(() => {
        isThemeTransitioning = false;
      });
    });
}

themeToggles.forEach((themeToggle) => {
  themeToggle.addEventListener('click', (event) => {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    const origin = getTransitionOrigin(event, themeToggle);
    switchThemeWithScatter(newTheme, origin);
  });
});

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
  if (!localStorage.getItem('theme')) {
    const newTheme = e.matches ? 'dark' : 'light';
    setTheme(newTheme);
  }
});

window.addEventListener('load', () => {
  updateLogoColor(html.getAttribute('data-theme') || getPreferredTheme());
});
