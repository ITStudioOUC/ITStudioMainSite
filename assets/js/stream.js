document.addEventListener('DOMContentLoaded', function() {
  const container = document.querySelector('.stream-text') || document.querySelector('.hero-description');
  if (!container) return;

  const cnText = container.getAttribute('data-cn') || '';
  const enText = container.getAttribute('data-en') || '';

  // 标记动画是否已经完整播放过一次
  let hasPlayedOnce = false;
  // 存储当前的定时器，以便清理
  let timeouts = [];

  function getCurrentText() {
    return document.body.getAttribute('lang') === 'en' ? enText : cnText;
  }

  function clearTimeouts() {
    timeouts.forEach(t => clearTimeout(t));
    timeouts = [];
  }

  // 渲染文本的核心函数
  // text: 要显示的文本
  // animate: 是否需要逐字流式动画
  function render(text, animate) {
    clearTimeouts();

    // 使用 Array.from 正确处理 Unicode 字符
    const chars = Array.from(text);

    // 生成 HTML：每个字符包裹在 span 中
    // 如果需要动画，初始透明度为 0
    // 如果不需要动画（切换语言时），初始透明度直接为 1
    const initialOpacity = animate ? '0' : '1';

    // 生成 spans 字符串
    const html = chars.map(char => {
      // 对空格也可以处理，虽然空格看不见
      return `<span class="stream-char" style="opacity: ${initialOpacity};">${char}</span>`;
    }).join('');

    // 一次性插入 DOM，这时容器会被透明字符撑开，布局位置固定
    container.innerHTML = html;

    // 如果不需要动画，直接结束
    if (!animate) {
      return;
    }

    // 开始执行流式淡入动画
    const spans = container.querySelectorAll('.stream-char');

    // 基础延迟累加器
    let accumulatedDelay = 0;

    spans.forEach((span, index) => {
      // 模拟流式生成的随机节奏
      // 基础 20ms，随机增加 0-30ms
      const step = 20 + Math.random() * 30;
      accumulatedDelay += step;

      // 遇到标点符号稍微停顿一下
      if (/[,，.。;；!！?？:：]/.test(span.textContent)) {
        accumulatedDelay += 100;
      }

      const t = setTimeout(() => {
        span.style.opacity = '1';

        // 当最后一个字符显示完毕，标记动画已播放
        if (index === spans.length - 1) {
          hasPlayedOnce = true;
        }
      }, accumulatedDelay);

      timeouts.push(t);
    });
  }

  // 监听语言切换
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'lang') {
        const newText = getCurrentText();
        // 需求：如果动画已经播放完成过一次，切换语言就不再播放
        // 注意：如果用户在动画还没播放完时就切换语言，为了体验，通常也应该直接显示新语言(视为打断)，防止混乱
        // 所以只要是切换语言，我们将动画设为 false (除非你想每次刷新页面都看)
        // 既然用户强调 "如果动画已经播放完成过一次...就不应该再播放"，隐含初次加载要播放。
        // 我们这里处理逻辑：切换语言时，强制不播放动画 (直接显示)，并标记 hasPlayedOnce = true (以防万一)
        hasPlayedOnce = true;
        render(newText, false);
      }
    });
  });

  observer.observe(document.body, { attributes: true });

  // 页面首次加载：播放动画
  render(getCurrentText(), true);
});
