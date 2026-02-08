const langToggle = document.querySelector('.lang-toggle');
const body = document.body;

function getPreferredLang() {
  const savedLang = localStorage.getItem('language');
  if (savedLang) {
    return savedLang;
  }
  return 'zh';
}

function setLang(lang) {
  if (lang === 'en') {
    body.setAttribute('lang', 'en');
    if (langToggle) {
      langToggle.querySelector('.lang-text').textContent = '中';
    }
  } else {
    body.setAttribute('lang', 'zh');
    if (langToggle) {
      langToggle.querySelector('.lang-text').textContent = 'EN';
    }
  }

  // 处理 Input 元素的值切换 (input[type=submit] cannot use CSS content replacement)
  const inputs = document.querySelectorAll('input[type="submit"][data-cn][data-en]');
  inputs.forEach(input => {
    input.value = lang === 'en' ? input.getAttribute('data-en') : input.getAttribute('data-cn');
  });

  localStorage.setItem('language', lang);
}

const initialLang = getPreferredLang();
setLang(initialLang);

if (langToggle) {
  langToggle.addEventListener('click', () => {
    const currentLang = body.getAttribute('lang');
    const newLang = currentLang === 'en' ? 'zh' : 'en';
    setLang(newLang);
  });
}
