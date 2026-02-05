const themeToggle = document.querySelector('.theme-toggle');
const html = document.documentElement;

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
  updateLogoColor(theme);
}

const initialTheme = getPreferredTheme();
setTheme(initialTheme);

if (themeToggle) {
  themeToggle.addEventListener('click', () => {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    setTheme(newTheme);
  });
}

window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
  if (!localStorage.getItem('theme')) {
    const newTheme = e.matches ? 'dark' : 'light';
    setTheme(newTheme);
  }
});

window.addEventListener('load', () => {
  updateLogoColor(html.getAttribute('data-theme') || getPreferredTheme());
});
