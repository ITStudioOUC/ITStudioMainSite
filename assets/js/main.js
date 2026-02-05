const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const navMenu = document.querySelector('.nav-menu');

if (mobileMenuToggle && navMenu) {
  mobileMenuToggle.addEventListener('click', () => {
    navMenu.classList.toggle('active');
    mobileMenuToggle.classList.toggle('active');
  });

  document.addEventListener('click', (e) => {
    if (!e.target.closest('.main-navigation')) {
      navMenu.classList.remove('active');
      mobileMenuToggle.classList.remove('active');
    }
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
      navMenu.classList.remove('active');
      mobileMenuToggle.classList.remove('active');
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const postItems = document.querySelectorAll('.post-item');

  postItems.forEach((item, index) => {
    item.style.animationDelay = `${index * 0.1}s`;
  });
});
