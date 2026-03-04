const langToggle = document.querySelector('.lang-toggle');
const body = document.body;

function getPreferredLang() {
  const savedLang = localStorage.getItem('language');
  if (savedLang === 'zh' || savedLang === 'en') {
    return savedLang;
  }
  return 'zh';
}

function setElementTextByLang(element, lang) {
  const nextText = lang === 'en' ? element.getAttribute('data-en') : element.getAttribute('data-cn');
  if (!nextText) {
    return;
  }

  if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
    const inputType = (element.getAttribute('type') || '').toLowerCase();
    if (inputType === 'submit' || inputType === 'button' || inputType === 'reset') {
      element.value = nextText;
      return;
    }
  }

  element.textContent = nextText;
}

function setElementPlaceholderByLang(element, lang) {
  const key = lang === 'en' ? 'data-en-placeholder' : 'data-cn-placeholder';
  const value = element.getAttribute(key);
  if (value) {
    element.setAttribute('placeholder', value);
  }
}

function setElementAriaLabelByLang(element, lang) {
  const key = lang === 'en' ? 'data-en-aria-label' : 'data-cn-aria-label';
  const value = element.getAttribute(key);
  if (value) {
    element.setAttribute('aria-label', value);
  }
}

function setLang(lang) {
  body.setAttribute('lang', lang);

  if (langToggle) {
    const langText = langToggle.querySelector('.lang-text');
    if (langText) {
      langText.textContent = lang === 'en' ? '中' : 'EN';
    }
  }

  const textNodes = document.querySelectorAll('[data-cn][data-en]');
  textNodes.forEach((element) => setElementTextByLang(element, lang));

  const placeholderNodes = document.querySelectorAll('[data-cn-placeholder][data-en-placeholder]');
  placeholderNodes.forEach((element) => setElementPlaceholderByLang(element, lang));

  const ariaNodes = document.querySelectorAll('[data-cn-aria-label][data-en-aria-label]');
  ariaNodes.forEach((element) => setElementAriaLabelByLang(element, lang));

  localStorage.setItem('language', lang);
}

const initialLang = getPreferredLang();
setLang(initialLang);

if (langToggle) {
  langToggle.addEventListener('click', () => {
    const currentLang = body.getAttribute('lang');
    const nextLang = currentLang === 'en' ? 'zh' : 'en';
    setLang(nextLang);
  });
}
