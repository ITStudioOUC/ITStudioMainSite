(() => {
  const canvas = document.getElementById('joinProgressCanvas');
  if (!canvas) {
    return;
  }

  const context = canvas.getContext('2d');
  if (!context) {
    return;
  }

  const animeLib = window.anime;
  const hasAnime = typeof animeLib === 'function';

  const waveTrack = document.getElementById('joinWaveTrack');
  const waveFill = document.getElementById('joinWaveFill');
  const waveBoat = document.getElementById('joinWaveBoat');
  const waveMarks = document.getElementById('joinWaveMarks');
  const waveProgress = waveTrack ? waveTrack.closest('.join-wave-progress') : null;
  const overlayPanel = document.querySelector('.join-canvas-overlay');
  const joinData = window.itstudioJoinData && typeof window.itstudioJoinData === 'object'
    ? window.itstudioJoinData
    : {};

  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

  let width = 0;
  let height = 0;
  let dpr = 1;
  let rafId = 0;
  let targetProgress = 0;
  let displayedProgress = 0;
  let trackLeft = 0;
  let trackWidth = 0;
  let stageMarkers = [];
  let stageLayout = null;

  const waveShape1 = { base: 0.16, amp: 7.5, len: 280, speed: 0.9 };
  const waveShape2 = { base: 0.44, amp: 6, len: 340, speed: 0.75 };
  const waveShape3 = { base: 0.68, amp: 6.5, len: 400, speed: 0.62 };
  const DAY_MS = 24 * 60 * 60 * 1000;
  const ONE_DAY_STAGE_WEIGHT_MS = DAY_MS * 1.8;

  const palettes = {
    light: {
      wave1: 'rgba(122, 178, 230, 0.95)',
      wave2: 'rgba(73, 134, 197, 0.72)',
      wave3: 'rgba(40, 96, 161, 0.78)',
    },
    dark: {
      wave1: 'rgba(86, 146, 214, 0.92)',
      wave2: 'rgba(49, 104, 172, 0.78)',
      wave3: 'rgba(27, 73, 136, 0.82)',
    },
  };

  function clamp(value, min, max) {
    return Math.min(max, Math.max(min, value));
  }

  function getTheme() {
    return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function getNumber(value) {
    const numeric = Number(value);
    return Number.isFinite(numeric) ? numeric : null;
  }

  function getStages() {
    return Array.isArray(joinData.stages) ? joinData.stages : [];
  }

  function getStageDurationMs(stage) {
    const start = getNumber(stage ? stage.startTs : null);
    const end = getNumber(stage ? stage.endTs : null);
    if (start === null || end === null) {
      return null;
    }
    return Math.max(0, end - start);
  }

  function getEffectiveStageWeightMs(stage) {
    const duration = getStageDurationMs(stage);
    if (duration === null) {
      return ONE_DAY_STAGE_WEIGHT_MS;
    }
    if (duration <= DAY_MS) {
      return ONE_DAY_STAGE_WEIGHT_MS;
    }
    return duration;
  }

  function buildStageLayout(stages) {
    if (!stages.length) {
      return {
        markerByIndex: [],
        endByIndex: [],
      };
    }

    const weights = stages.map((stage) => getEffectiveStageWeightMs(stage));
    const totalWeight = Math.max(1, weights.reduce((sum, w) => sum + w, 0));
    const markerByIndex = [];
    const endByIndex = [];
    let cumulative = 0;

    for (let i = 0; i < stages.length; i += 1) {
      markerByIndex[i] = clamp(cumulative / totalWeight, 0, 1);
      cumulative += weights[i];
      endByIndex[i] = clamp(cumulative / totalWeight, 0, 1);
    }

    endByIndex[endByIndex.length - 1] = 1;
    return { markerByIndex, endByIndex };
  }

  function getStageLayout(stages) {
    if (!stageLayout || !stageLayout.markerByIndex || stageLayout.markerByIndex.length !== stages.length) {
      stageLayout = buildStageLayout(stages);
    }
    return stageLayout;
  }

  function getNavigationType() {
    if (window.performance && typeof window.performance.getEntriesByType === 'function') {
      const entries = window.performance.getEntriesByType('navigation');
      if (entries && entries.length > 0 && entries[0].type) {
        return entries[0].type;
      }
    }

    if (window.performance && window.performance.navigation) {
      if (window.performance.navigation.type === 1) {
        return 'reload';
      }
      if (window.performance.navigation.type === 2) {
        return 'back_forward';
      }
    }

    return 'navigate';
  }

  function shouldAnimateEntry() {
    if (prefersReducedMotion.matches) {
      return false;
    }

    return getNavigationType() !== 'reload';
  }

  function setupOverlayEntryAnimation() {
    if (!overlayPanel) {
      return;
    }

    overlayPanel.classList.remove('is-enter-animate');
    if (!shouldAnimateEntry()) {
      return;
    }

    requestAnimationFrame(() => {
      overlayPanel.classList.add('is-enter-animate');
    });
  }

  function resize() {
    dpr = window.devicePixelRatio || 1;
    width = canvas.clientWidth;
    height = canvas.clientHeight;
    canvas.width = Math.max(1, Math.floor(width * dpr));
    canvas.height = Math.max(1, Math.floor(height * dpr));
    context.setTransform(dpr, 0, 0, dpr, 0, 0);
    updateTrackMetrics();
  }

  function updateTrackMetrics() {
    if (!waveTrack) {
      trackLeft = 0;
      trackWidth = width;
      return;
    }

    const canvasRect = canvas.getBoundingClientRect();
    const trackRect = waveTrack.getBoundingClientRect();
    trackLeft = clamp(trackRect.left - canvasRect.left, 0, width);
    trackWidth = Number.isFinite(trackRect.width) && trackRect.width > 0 ? trackRect.width : width;
  }

  function drawWave(color, yBase, amplitude, wavelength, speed, time) {
    const overscan = 24;
    const step = 6;
    context.beginPath();
    context.moveTo(-overscan, height + 2);
    for (let x = -overscan; x <= width + overscan; x += step) {
      const theta = (x / wavelength) * Math.PI * 2 + time * speed;
      const y = yBase + Math.sin(theta) * amplitude;
      context.lineTo(x, y);
    }
    context.lineTo(width + overscan, height + 2);
    context.lineTo(-overscan, height + 2);
    context.closePath();
    context.fillStyle = color;
    context.fill();
  }

  function sampleWaveAtX(shape, x, time) {
    const theta = (x / shape.len) * Math.PI * 2 + time * shape.speed;
    const y = Math.sin(theta) * shape.amp;
    const slope = Math.cos(theta) * (Math.PI * 2 / shape.len) * shape.amp;
    return { y, slope };
  }

  function getWaveXFromProgress(progress) {
    const safeProgress = clamp(progress, 0, 1);
    const currentTrackWidth = trackWidth > 0 ? trackWidth : width;
    return trackLeft + (safeProgress * currentTrackWidth);
  }

  function applyFloatingMotion(time) {
    if (width <= 0) {
      return;
    }

    const boatWave = sampleWaveAtX(waveShape3, getWaveXFromProgress(displayedProgress), time);
    if (waveBoat) {
      const boatTilt = clamp(boatWave.slope * 34, -7.2, 7.2);
      waveBoat.style.setProperty('--boat-wave-offset', `${boatWave.y.toFixed(2)}px`);
      waveBoat.style.setProperty('--boat-tilt', `${boatTilt.toFixed(2)}deg`);
    }

    stageMarkers.forEach((marker) => {
      if (marker.type === 'lighthouse') {
        marker.element.style.setProperty('--mark-wave-offset', '0px');
        marker.element.style.setProperty('--mark-tilt', '0deg');
        return;
      }

      const markerWave = sampleWaveAtX(waveShape3, getWaveXFromProgress(marker.progress), time);
      const markerTilt = clamp(markerWave.slope * 34, -7.2, 7.2);
      marker.element.style.setProperty('--mark-wave-offset', `${markerWave.y.toFixed(2)}px`);
      marker.element.style.setProperty('--mark-tilt', `${markerTilt.toFixed(2)}deg`);
    });
  }

  function renderWaves(timestamp) {
    const theme = getTheme();
    const palette = palettes[theme];
    const time = timestamp * 0.001;

    context.clearRect(0, 0, width, height);
    drawWave(palette.wave1, height * waveShape1.base, waveShape1.amp, waveShape1.len, waveShape1.speed, time);
    drawWave(palette.wave2, height * waveShape2.base, waveShape2.amp, waveShape2.len, waveShape2.speed, time);
    drawWave(palette.wave3, height * waveShape3.base, waveShape3.amp, waveShape3.len, waveShape3.speed, time);
    applyFloatingMotion(time);

    rafId = requestAnimationFrame(renderWaves);
  }

  function stopWaves() {
    if (rafId) {
      cancelAnimationFrame(rafId);
      rafId = 0;
    }
  }

  function drawStaticWaves() {
    const theme = getTheme();
    const palette = palettes[theme];

    context.clearRect(0, 0, width, height);
    drawWave(palette.wave1, height * waveShape1.base, waveShape1.amp, waveShape1.len, waveShape1.speed, 0);
    drawWave(palette.wave2, height * waveShape2.base, waveShape2.amp, waveShape2.len, waveShape2.speed, 0);
    drawWave(palette.wave3, height * waveShape3.base, waveShape3.amp, waveShape3.len, waveShape3.speed, 0);
    applyFloatingMotion(0);
  }

  function startWaves() {
    stopWaves();
    resize();
    if (prefersReducedMotion.matches) {
      drawStaticWaves();
      return;
    }
    rafId = requestAnimationFrame(renderWaves);
  }

  function computeTargetProgress() {
    const stages = getStages();
    if (!stages.length) {
      return 0;
    }

    const layout = getStageLayout(stages);
    const markerByIndex = layout.markerByIndex;
    const endByIndex = layout.endByIndex;
    const currentIndex = getNumber(joinData.currentStageIndex);
    const nowTs = getNumber(joinData.nowTs) || Date.now();

    // 有进行中阶段时，在该阶段对应区间内按时间连续推进。
    if (currentIndex !== null && currentIndex >= 0 && currentIndex < stages.length) {
      const stage = stages[currentIndex];
      const startProgress = getNumber(markerByIndex[currentIndex]) ?? 0;
      const endProgress = getNumber(endByIndex[currentIndex]) ?? startProgress;
      const startTs = getNumber(stage ? stage.startTs : null);
      const endTs = getNumber(stage ? stage.endTs : null);

      if (startTs !== null && endTs !== null && endTs > startTs) {
        const ratio = clamp((nowTs - startTs) / (endTs - startTs), 0, 1);
        return clamp(startProgress + ((endProgress - startProgress) * ratio), 0, 1);
      }

      return clamp(startProgress, 0, 1);
    }

    // 无进行中阶段时，停在最近已完成阶段的末端。
    let lastCompletedIndex = -1;
    for (let i = 0; i < stages.length; i += 1) {
      if (stages[i] && stages[i].status === 'completed') {
        lastCompletedIndex = i;
      }
    }

    // 阶段空档期：在“上一阶段浮标”和“下一阶段浮标”之间按时间线性推进。
    if (lastCompletedIndex >= 0) {
      let nextUpcomingIndex = -1;
      for (let i = lastCompletedIndex + 1; i < stages.length; i += 1) {
        if (stages[i] && stages[i].status === 'upcoming') {
          nextUpcomingIndex = i;
          break;
        }
      }

      if (nextUpcomingIndex >= 0) {
        const completedStage = stages[lastCompletedIndex];
        const upcomingStage = stages[nextUpcomingIndex];
        const gapStartTs = getNumber(completedStage ? completedStage.endTs : null);
        const gapEndTs = getNumber(upcomingStage ? upcomingStage.startTs : null);
        const fromProgress = clamp(getNumber(markerByIndex[lastCompletedIndex]) ?? 0, 0, 1);
        const toProgress = clamp(getNumber(markerByIndex[nextUpcomingIndex]) ?? fromProgress, 0, 1);

        if (gapStartTs !== null && gapEndTs !== null && gapEndTs > gapStartTs && nowTs > gapStartTs && nowTs < gapEndTs) {
          const gapRatio = clamp((nowTs - gapStartTs) / (gapEndTs - gapStartTs), 0, 1);
          return clamp(fromProgress + ((toProgress - fromProgress) * gapRatio), 0, 1);
        }

        if (nowTs <= gapStartTs) {
          return fromProgress;
        }

        if (nowTs < gapEndTs) {
          return clamp((fromProgress + toProgress) * 0.5, 0, 1);
        }
      }
    }

    if (lastCompletedIndex >= 0) {
      return clamp(getNumber(endByIndex[lastCompletedIndex]) ?? 0, 0, 1);
    }

    return 0;
  }

  function getBuoyMarkerSvg() {
    return `
      <svg viewBox="0 0 40 52" role="img" aria-hidden="true" focusable="false">
        <ellipse class="marker-buoy-ring" cx="20" cy="40.5" rx="12.8" ry="5.4"></ellipse>
        <path class="marker-buoy-base" d="M13.2 21.2C13.2 16.8 16.8 13.2 21.2 13.2C25.6 13.2 29.2 16.8 29.2 21.2V31.8C29.2 35.1 26.5 37.8 23.2 37.8H19.2C15.9 37.8 13.2 35.1 13.2 31.8V21.2Z"></path>
        <path class="marker-buoy-stripe" d="M13.2 23.6H29.2V28.1H13.2Z"></path>
        <path class="marker-buoy-cap" d="M16.6 12.6H25.8V15.4H16.6Z"></path>
        <circle class="marker-buoy-light" cx="21.2" cy="10.2" r="2.4"></circle>
        <path class="marker-buoy-gloss" d="M17 18.4C17.6 16.9 19 15.9 20.5 15.9V35.1C18.4 35.1 16.7 33.4 16.7 31.3V19.2C16.7 18.9 16.8 18.6 17 18.4Z"></path>
      </svg>
    `;
  }

  function getLighthouseMarkerSvg() {
    return `
      <svg viewBox="0 0 48 66" role="img" aria-hidden="true" focusable="false">
        <path class="marker-reef-rock" d="M6 59L12 52L20 55L27 49L35 52L42 48L45 59L6 59Z"></path>
        <path class="marker-reef-highlight" d="M13 56L19 54L24 55L31 52L36 54L33 57L20 58L13 56Z"></path>
        <path class="marker-lh-base" d="M15 50H33L30 57H18L15 50Z"></path>
        <path class="marker-lh-tower" d="M18 50L21 15H27L30 50H18Z"></path>
        <path class="marker-lh-top" d="M18 15H30L27.5 10H20.5L18 15Z"></path>
        <path class="marker-lh-top" d="M16 20H32V23H16Z"></path>
        <path class="marker-lh-beam marker-lh-beam-right" d="M32 18L46 15V22L32 20Z"></path>
        <path class="marker-lh-beam marker-lh-beam-left" d="M16 18L2 15V22L16 20Z"></path>
      </svg>
    `;
  }

  function renderStageMarks() {
    if (!waveMarks) {
      return;
    }

    stageMarkers = [];
    waveMarks.innerHTML = '';
    if (waveProgress) {
      waveProgress.querySelectorAll('.join-wave-mark.is-lighthouse.is-docked').forEach((node) => node.remove());
    }

    const stages = getStages();
    if (!stages.length) {
      return;
    }
    const layout = getStageLayout(stages);
    const markerByIndex = layout.markerByIndex;

    const currentIndex = getNumber(joinData.currentStageIndex);
    const safeCurrentIndex = currentIndex === null ? -1 : Math.round(currentIndex);

    stages.forEach((stage, index) => {
      const marker = document.createElement('span');
      marker.className = 'join-wave-mark';

      const isLighthouse = index === stages.length - 1 || (stage && stage.key === 'public_notice');
      const progress = isLighthouse
        ? 1
        : clamp(getNumber(markerByIndex[index]) ?? 0, 0, 1);

      marker.classList.add(isLighthouse ? 'is-lighthouse' : 'is-buoy');
      if (index === safeCurrentIndex) {
        marker.classList.add('is-active');
      }

      const icon = document.createElement('span');
      icon.className = 'join-wave-mark-icon';
      icon.innerHTML = isLighthouse ? getLighthouseMarkerSvg() : getBuoyMarkerSvg();
      marker.appendChild(icon);

      const beams = isLighthouse ? Array.from(icon.querySelectorAll('.marker-lh-beam')) : [];

      if (isLighthouse && waveProgress) {
        marker.classList.add('is-docked');
        marker.style.right = '';
        marker.style.left = 'calc(100% - clamp(18px, 2.2vw, 30px))';
        waveProgress.appendChild(marker);
      } else {
        marker.style.left = `${(progress * 100).toFixed(3)}%`;
        waveMarks.appendChild(marker);
      }
      stageMarkers.push({
        element: marker,
        icon,
        beams,
        progress,
        type: isLighthouse ? 'lighthouse' : 'buoy',
      });
    });
  }

  function stopBoatAnimations() {
    if (!hasAnime) {
      return;
    }

    const targets = [waveFill, waveBoat];
    stageMarkers.forEach((marker) => {
      if (marker.icon) {
        targets.push(marker.icon);
      }
      if (Array.isArray(marker.beams) && marker.beams.length) {
        marker.beams.forEach((beam) => targets.push(beam));
      }
    });
    animeLib.remove(targets);
  }

  function startLighthouseBeacon() {
    const allBeams = [];
    const leftBeams = [];
    const rightBeams = [];

    stageMarkers.forEach((marker) => {
      if (marker.type !== 'lighthouse' || !Array.isArray(marker.beams)) {
        return;
      }

      marker.beams.forEach((beam) => {
        allBeams.push(beam);
        beam.style.opacity = '0.28';
        if (beam.classList.contains('marker-lh-beam-left')) {
          leftBeams.push(beam);
        } else if (beam.classList.contains('marker-lh-beam-right')) {
          rightBeams.push(beam);
        }
      });
    });

    if (!allBeams.length || !hasAnime || prefersReducedMotion.matches) {
      return;
    }

    const duration = 3600;
    const easing = 'easeInOutSine';

    if (rightBeams.length) {
      animeLib({
        targets: rightBeams,
        opacity: [0.2, 0.86, 0.2],
        duration,
        easing,
        loop: true,
      });
    }

    if (leftBeams.length) {
      animeLib({
        targets: leftBeams,
        opacity: [0.2, 0.82, 0.2],
        duration,
        delay: duration / 2,
        easing,
        loop: true,
      });
    } else if (!rightBeams.length) {
      animeLib({
        targets: allBeams,
        opacity: [0.2, 0.84, 0.2],
        duration,
        easing,
        loop: true,
      });
    }
  }

  function setProgress(value) {
    const safe = clamp(value, 0, 1);
    displayedProgress = safe;
    const percent = `${(safe * 100).toFixed(3)}%`;

    if (waveFill) {
      waveFill.style.width = percent;
    }
    if (waveBoat) {
      waveBoat.style.left = percent;
    }
  }

  function animateBoatToTarget() {
    if (!waveBoat || !waveFill) {
      return;
    }

    stopBoatAnimations();

    if (!hasAnime || !shouldAnimateEntry()) {
      setProgress(targetProgress);
      startLighthouseBeacon();
      return;
    }

    const state = { value: 0 };
    setProgress(0);

    animeLib({
      targets: state,
      value: targetProgress,
      duration: 1750,
      easing: 'easeOutCubic',
      update: () => {
        setProgress(state.value);
      },
      complete: () => {
        setProgress(targetProgress);
        startLighthouseBeacon();
      },
    });

    animeLib({
      targets: waveFill,
      opacity: [0.55, 1],
      duration: 1100,
      easing: 'easeOutSine',
    });

    const activeIcon = waveMarks ? waveMarks.querySelector('.join-wave-mark.is-active .join-wave-mark-icon') : null;
    if (activeIcon) {
      animeLib({
        targets: activeIcon,
        scale: [1, 1.24, 1],
        opacity: [0.82, 1, 0.92],
        duration: 850,
        delay: 980,
        easing: 'easeOutSine',
      });
    }
  }

  function handleMotionPreferenceChange() {
    targetProgress = computeTargetProgress();
    setProgress(targetProgress);
    stopBoatAnimations();
    startWaves();
    startLighthouseBeacon();
  }

  const observer = new MutationObserver(() => {
    if (prefersReducedMotion.matches) {
      drawStaticWaves();
    }
  });
  observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

  if (typeof prefersReducedMotion.addEventListener === 'function') {
    prefersReducedMotion.addEventListener('change', handleMotionPreferenceChange);
  } else if (typeof prefersReducedMotion.addListener === 'function') {
    prefersReducedMotion.addListener(handleMotionPreferenceChange);
  }

  window.addEventListener('resize', () => {
    startWaves();
    setProgress(targetProgress);
  });

  window.addEventListener('orientationchange', () => {
    startWaves();
    setProgress(targetProgress);
  });

  window.addEventListener('beforeunload', () => {
    stopWaves();
    stopBoatAnimations();
  });

  function resolveLanguage() {
    const bootLang = typeof window.__ITSTUDIO_LANG__ === 'string' ? window.__ITSTUDIO_LANG__ : '';
    const htmlLang = document.documentElement.getAttribute('lang') || '';
    const lang = (bootLang || htmlLang || 'zh').toLowerCase();
    return lang.indexOf('en') === 0 ? 'en' : 'zh';
  }

  function pickLocaleText(cnText, enText) {
    return resolveLanguage() === 'en'
      ? (enText || cnText || '')
      : (cnText || enText || '');
  }

  function getProgressLookupConfig() {
    const config = joinData && typeof joinData.progressLookup === 'object' ? joinData.progressLookup : null;
    if (!config) {
      return null;
    }
    if (!config.ajaxUrl || !config.action || !config.nonce) {
      return null;
    }
    return config;
  }

  function ensureProgressFeedbackNode(formElement) {
    const host = formElement.closest('.join-form-content') || formElement.parentElement;
    if (!host) {
      return null;
    }

    let feedback = host.querySelector('.join-progress-query-feedback');
    if (!feedback) {
      feedback = document.createElement('p');
      feedback.className = 'join-progress-query-feedback';
      feedback.hidden = true;
      feedback.setAttribute('aria-live', 'polite');
      host.appendChild(feedback);
    }
    return feedback;
  }

  function setProgressFeedback(feedback, tone, messageCn, messageEn) {
    if (!feedback) {
      return;
    }

    const normalizedTone = ['success', 'warning', 'error', 'info'].indexOf(tone) >= 0 ? tone : 'info';
    feedback.classList.remove('is-success', 'is-warning', 'is-error', 'is-info');
    feedback.classList.add(`is-${normalizedTone}`);
    feedback.dataset.cn = messageCn || '';
    feedback.dataset.en = messageEn || '';
    feedback.textContent = pickLocaleText(messageCn, messageEn);
    feedback.hidden = !(feedback.textContent && feedback.textContent.trim() !== '');
  }

  function setupProgressLookupAjax() {
    const formElement = document.querySelector('.join-progress-query-form');
    if (!formElement || typeof window.fetch !== 'function') {
      return;
    }

    const config = getProgressLookupConfig();
    if (!config) {
      return;
    }

    const identityInput = formElement.querySelector('input[name="join_query_identity"]');
    const submitButton = formElement.querySelector('button[type="submit"], input[type="submit"]');
    const feedback = ensureProgressFeedbackNode(formElement);

    formElement.addEventListener('submit', async (event) => {
      event.preventDefault();

      const identity = identityInput ? identityInput.value.trim() : '';
      const payload = new URLSearchParams();
      payload.set('action', config.action);
      payload.set('nonce', config.nonce);
      payload.set('join_progress_lookup', '1');
      payload.set('join_query_identity', identity);

      if (submitButton) {
        submitButton.disabled = true;
        submitButton.setAttribute('aria-busy', 'true');
      }
      setProgressFeedback(feedback, 'info', '查询中，请稍候…', 'Checking, please wait...');

      try {
        const response = await window.fetch(config.ajaxUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          },
          body: payload.toString(),
        });

        const result = await response.json();
        if (!result || !result.success || !result.data) {
          throw new Error('invalid_response');
        }

        const data = result.data;
        setProgressFeedback(
          feedback,
          data.tone || 'info',
          data.message_cn || '',
          data.message_en || ''
        );
      } catch (error) {
        setProgressFeedback(
          feedback,
          'error',
          '查询失败，请稍后重试。',
          'Lookup failed. Please try again later.'
        );
      } finally {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.removeAttribute('aria-busy');
        }
      }
    });
  }

  function detectJoinSubmitIntentFromElement(element) {
    if (!element) {
      return '';
    }

    const name = (element.getAttribute('name') || '').toLowerCase();
    const value = String(element.value || element.textContent || '').toLowerCase();
    if (name.indexOf('draft') !== -1 || value.indexOf('draft') !== -1 || value.indexOf('草稿') !== -1) {
      return 'draft';
    }
    return 'submitted';
  }

  function ensureJoinSubmitNoticeNode() {
    let notice = document.getElementById('joinSubmitNotice');
    if (notice) {
      return notice;
    }

    notice = document.querySelector('.join-submit-notice');
    if (notice) {
      notice.id = 'joinSubmitNotice';
      return notice;
    }

    const head = document.querySelector('.join-head');
    if (!head || !head.parentNode) {
      return null;
    }

    notice = document.createElement('p');
    notice.id = 'joinSubmitNotice';
    notice.className = 'join-submit-notice';
    notice.hidden = true;
    head.parentNode.insertBefore(notice, head.nextSibling);
    return notice;
  }

  function showJoinSubmitNotice(intent) {
    const notice = ensureJoinSubmitNoticeNode();
    if (!notice) {
      return;
    }

    const isDraft = intent === 'draft';
    const messageCn = isDraft ? '草稿已保存' : '你的报名表单已提交，感谢报名';
    const messageEn = isDraft ? 'Draft saved.' : 'Your registration form has been submitted. Thank you for applying.';
    notice.dataset.cn = messageCn;
    notice.dataset.en = messageEn;
    notice.textContent = pickLocaleText(messageCn, messageEn);
    notice.hidden = false;
  }

  function setupRegistrationSubmitNoticeBridge() {
    const formElement = document.querySelector('.join-form-content .frm_forms form');
    if (!formElement) {
      return;
    }

    let submitIntent = 'submitted';
    formElement.addEventListener('click', (event) => {
      const control = event.target.closest('button, input[type="submit"]');
      const nextIntent = detectJoinSubmitIntentFromElement(control);
      if (nextIntent) {
        submitIntent = nextIntent;
      }
    });

    formElement.addEventListener('submit', (event) => {
      const submitter = event.submitter || null;
      const nextIntent = detectJoinSubmitIntentFromElement(submitter);
      if (nextIntent) {
        submitIntent = nextIntent;
      }
    });

    const formHost = formElement.closest('.join-form-content') || formElement.parentElement;
    if (!formHost) {
      return;
    }

    const updateSuccessMessage = () => {
      const successNode = formHost.querySelector('.frm_message, .frm_success_style, .frm_success');
      if (!successNode) {
        return;
      }

      const lowerText = (successNode.textContent || '').toLowerCase();
      const resolvedIntent = (lowerText.indexOf('draft') !== -1 || lowerText.indexOf('草稿') !== -1)
        ? 'draft'
        : submitIntent;

      const messageCn = resolvedIntent === 'draft' ? '草稿已保存' : '你的报名表单已提交，感谢报名';
      const messageEn = resolvedIntent === 'draft' ? 'Draft saved.' : 'Your registration form has been submitted. Thank you for applying.';
      successNode.dataset.cn = messageCn;
      successNode.dataset.en = messageEn;
      successNode.textContent = pickLocaleText(messageCn, messageEn);
      showJoinSubmitNotice(resolvedIntent);
    };

    const observer = new MutationObserver(updateSuccessMessage);
    observer.observe(formHost, { childList: true, subtree: true });
  }

  targetProgress = computeTargetProgress();
  setupOverlayEntryAnimation();
  renderStageMarks();
  startWaves();
  animateBoatToTarget();
  setupProgressLookupAjax();
  setupRegistrationSubmitNoticeBridge();
})();
