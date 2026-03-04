<?php
/* Template Name: IT Studio Introduction */
get_header();
?>

<main class="site-main">
    <canvas class="hero-waves" aria-hidden="true"></canvas>
    <section class="hero-section">
        <button
            type="button"
            class="theme-toggle about-theme-toggle"
            aria-label="Toggle Theme"
            data-cn-aria-label="切换主题"
            data-en-aria-label="Toggle Theme">
            <svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>
            <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
        </button>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    <span class="hero-title-svg" aria-hidden="true"></span>
                    <span class="hero-title-text" data-cn="爱特工作室" data-en="IT STUDIO"></span>
                </h1>
            </div>
        </div>
        <div class="hero-scroll-indicator" aria-hidden="true">
            <span class="scroll-arrow"></span>
            <span class="scroll-text" data-cn="向下滑动开始" data-en="Scroll to begin"></span>
        </div>
    </section>
</main>

<?php get_footer(); ?>
