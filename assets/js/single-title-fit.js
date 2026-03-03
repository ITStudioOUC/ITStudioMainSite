(function () {
  "use strict";

  var TITLE_SELECTOR = ".single-article-title";
  var MAX_LINES = 2;
  var MIN_FONT_SIZE = 12;
  var MAX_FONT_SIZE = 92;
  var SEARCH_STEPS = 16;

  function getLineHeightPx(element) {
    var computed = window.getComputedStyle(element);
    var lineHeight = computed.lineHeight;
    var fontSize = parseFloat(computed.fontSize) || 16;

    if (lineHeight && lineHeight.indexOf("px") > -1) {
      return parseFloat(lineHeight);
    }

    if (lineHeight === "normal") {
      return fontSize * 1.2;
    }

    var ratio = parseFloat(lineHeight);
    if (Number.isFinite(ratio)) {
      return ratio * fontSize;
    }

    return fontSize * 1.2;
  }

  function getLineCount(element) {
    var lineHeightPx = getLineHeightPx(element);
    if (!lineHeightPx) {
      return 0;
    }
    return element.getBoundingClientRect().height / lineHeightPx;
  }

  function setFontSize(element, size) {
    element.style.fontSize = size.toFixed(2) + "px";
  }

  function fitsWithinLines(element, size) {
    setFontSize(element, size);
    return getLineCount(element) <= MAX_LINES + 0.01;
  }

  function fitTitle(title) {
    if (!title) {
      return;
    }

    var header = title.closest(".single-article-header") || title.parentElement || title;
    if (!header || header.getBoundingClientRect().width <= 0) {
      return;
    }

    title.style.fontSize = "";
    var computedSize = parseFloat(window.getComputedStyle(title).fontSize) || 48;

    var low = MIN_FONT_SIZE;
    var high = Math.min(MAX_FONT_SIZE, Math.max(computedSize * 1.8, computedSize + 8));

    while (high < MAX_FONT_SIZE && fitsWithinLines(title, high)) {
      high = Math.min(MAX_FONT_SIZE, high + 8);
      if (high === MAX_FONT_SIZE) {
        break;
      }
    }

    var best = low;
    for (var i = 0; i < SEARCH_STEPS; i += 1) {
      var mid = (low + high) / 2;
      if (fitsWithinLines(title, mid)) {
        best = mid;
        low = mid;
      } else {
        high = mid;
      }
    }

    if (!fitsWithinLines(title, best)) {
      while (best > MIN_FONT_SIZE && !fitsWithinLines(title, best)) {
        best -= 0.5;
      }
    }

    setFontSize(title, Math.max(MIN_FONT_SIZE, best - 0.05));
  }

  function initSingleTitleFitter() {
    var title = document.querySelector(TITLE_SELECTOR);
    if (!title) {
      return;
    }

    var scheduled = false;
    var scheduleFit = function () {
      if (scheduled) {
        return;
      }
      scheduled = true;
      window.requestAnimationFrame(function () {
        scheduled = false;
        fitTitle(title);
      });
    };

    scheduleFit();
    window.addEventListener("resize", scheduleFit, { passive: true });
    window.addEventListener("pageshow", scheduleFit);

    if ("ResizeObserver" in window) {
      var resizeObserver = new ResizeObserver(scheduleFit);
      resizeObserver.observe(title);
      var header = title.closest(".single-article-header");
      if (header) {
        resizeObserver.observe(header);
      }
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initSingleTitleFitter);
  } else {
    initSingleTitleFitter();
  }
})();
