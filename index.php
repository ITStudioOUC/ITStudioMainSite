<?php get_header(); ?>

<main class="site-main home-landing">
    <?php
    $announcement_archive_url = get_post_type_archive_link('announcement');
    if (!$announcement_archive_url) {
        $announcement_archive_url = home_url('/announcements');
    }
    $club_news_url = get_post_type_archive_link('news');
    if (!$club_news_url) {
        $club_news_url = home_url('/news');
    }
    ?>

    <section class="landing-hero">
        <canvas class="landing-hero-canvas" aria-hidden="true"></canvas>
        <div class="container">
            <div class="landing-hero-content">
                <h1 class="landing-hero-title" data-cn="&#29233;&#29305;&#24037;&#20316;&#23460;" data-en="IT STUDIO"></h1>
                <p class="landing-hero-subtitle" data-cn="&#20013;&#22269;&#28023;&#27915;&#22823;&#23398;&#20449;&#24687;&#25216;&#26415;&#19982;&#24037;&#31243;&#23454;&#36341;&#22242;&#38431;" data-en="Technology and Engineering Practice Team at OUC"></p>
                <a class="landing-hero-btn" href="<?php echo esc_url(home_url('/about')); ?>" data-cn="&#20102;&#35299;&#26356;&#22810;" data-en="Learn More"></a>
            </div>
        </div>
    </section>

    <section class="services-section">
        <div class="container">
            <div class="services-provided">
                <h2 data-cn="@ &#26381;&#21153;&#25552;&#20379;" data-en="@ Services"></h2>
                <div class="services-grid-box">
                    <div class="service-item service-resources">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <path class="svc-stroke" d="M17 24h14l4 5h20a4 4 0 0 1 4 4v22a4 4 0 0 1-4 4H17a4 4 0 0 1-4-4V28a4 4 0 0 1 4-4z" />
    <rect class="svc-soft-strong" x="23" y="33" width="26" height="17" rx="2.6" />
    <path class="svc-accent" d="M28 38h16M28 43h12M28 48h9" />
    <path class="svc-muted" d="M51 35v13M51 48l3-3M51 48l-3-3" />
</svg>
                        </div>
                        <span data-cn="&#36164;&#28304;&#31449;" data-en="Resources"></span>
                    </div>
                    <div class="service-item service-mirror">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <circle class="svc-stroke" cx="36" cy="36" r="17" />
    <path class="svc-muted" d="M19 36h34M36 19c5 4.7 7.8 10.5 7.8 17S41 48.3 36 53M36 19c-5 4.7-7.8 10.5-7.8 17S31 48.3 36 53" />
    <path class="svc-accent" d="M50 29l7 7-7 7" />
    <path class="svc-accent" d="M57 36H45" />
    <path class="svc-accent" d="M22 43l-7-7 7-7" />
    <path class="svc-accent" d="M15 36h12" />
</svg>
                        </div>
                        <span data-cn="&#26657;&#20869;&#38236;&#20687;&#31449;" data-en="Mirror Site"></span>
                    </div>
                    <div class="service-item service-git">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <rect class="svc-stroke" x="12" y="19" width="30" height="30" rx="4" />
    <path class="svc-muted" d="M17 27h20M17 33h14" />
    <circle class="svc-dot" cx="49" cy="24" r="3" />
    <circle class="svc-dot" cx="49" cy="37" r="3" />
    <circle class="svc-dot" cx="56" cy="47" r="3" />
    <path class="svc-accent" d="M41 24h8" />
    <path class="svc-accent" d="M49 24v13" />
    <path class="svc-accent" d="M49 37c4 0 7 3 7 7" />
</svg>
                        </div>
                        <span data-cn="&#20195;&#30721;&#25176;&#31649;" data-en="Git Hosting"></span>
                    </div>
                    <div class="service-item service-minecraft">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <path class="svc-stroke" d="M36 15l20 11v23L36 60 16 49V26l20-11z" />
    <path class="svc-muted" d="M16 26l20 11 20-11M36 60V37" />
    <path class="svc-accent" d="M20 23h32" />
    <rect class="svc-soft-strong" x="24" y="40" width="6" height="6" rx="1.2" />
    <rect class="svc-soft-strong" x="34" y="43" width="5" height="5" rx="1.1" />
    <rect class="svc-dot" x="43" y="39" width="6" height="6" rx="1.2" />
</svg>
                        </div>
                        <span data-cn="Minecraft&#26381;&#21153;&#22120;" data-en="Minecraft Server"></span>
                    </div>
                    <div class="service-item service-forum">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <path class="svc-stroke" d="M11 28a6 6 0 0 1 6-6h22a6 6 0 0 1 6 6v13a6 6 0 0 1-6 6H28l-8 7v-7h-3a6 6 0 0 1-6-6V28z" />
    <path class="svc-accent" d="M36 19a6 6 0 0 1 6-6h13a6 6 0 0 1 6 6v9a6 6 0 0 1-6 6h-5l-4 4v-4h-4a6 6 0 0 1-6-6v-9z" />
    <circle class="svc-dot" cx="24" cy="35" r="1.8" />
    <circle class="svc-dot" cx="31" cy="35" r="1.8" />
    <circle class="svc-dot" cx="46" cy="24" r="1.7" />
    <circle class="svc-dot" cx="52" cy="24" r="1.7" />
</svg>
                        </div>
                        <span data-cn="OUC&#35770;&#22363;" data-en="OUC Forum"></span>
                    </div>
                    <div class="service-item service-repair">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <rect class="svc-stroke" x="11" y="18" width="37" height="25" rx="4" />
    <path class="svc-muted" d="M29.5 43v6M22 49h15" />
    <path class="svc-accent" d="M38 24.5a4.7 4.7 0 0 0-6.5 6.6l-7.2 7.2 3.4 3.4 7.2-7.2a4.7 4.7 0 0 0 6.6-6.5l-3 3-2.6-2.6 2.1-3z" />
    <path class="svc-accent" d="M50 19l10 10" />
    <circle class="svc-dot" cx="55" cy="24" r="2" />
</svg>
                        </div>
                        <span data-cn="&#30005;&#33041;&#32500;&#20462;" data-en="PC Repair"></span>
                    </div>
                    <div class="service-item service-workshop">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <rect class="svc-stroke" x="12" y="17" width="48" height="36" rx="5" />
    <path class="svc-muted" d="M12 30h48M24 12v10M48 12v10" />
    <rect class="svc-soft-strong" x="24" y="36" width="24" height="13" rx="3" />
    <path class="svc-accent" d="M29 42l4 4 9-9" />
    <circle class="svc-dot" cx="22" cy="24" r="1.8" />
    <circle class="svc-dot" cx="30" cy="24" r="1.8" />
</svg>
                        </div>
                        <span data-cn="&#20116;&#20843;&#24037;&#22346;&#39044;&#32422;" data-en="Workshop Booking"></span>
                    </div>
                    <div class="service-item service-campus">
                        <div class="service-icon">
                            <svg class="service-symbol" viewBox="0 0 72 72" fill="none" aria-hidden="true">
    <circle class="svc-soft" cx="36" cy="36" r="24" />
    <path class="svc-stroke" d="M36 14l18 8v14c0 12-7 20-18 25-11-5-18-13-18-25V22l18-8z" />
    <path class="svc-muted" d="M36 24c-4.5 0-8 3.5-8 8 0 5.5 8 13 8 13s8-7.5 8-13c0-4.5-3.5-8-8-8z" />
    <circle class="svc-soft-strong" cx="36" cy="32" r="3.2" />
    <circle class="svc-soft-strong" cx="52" cy="22" r="5.2" />
    <path class="svc-accent" d="M49.5 22H54.5M52 19.5v5" />
</svg>
                        </div>
                        <span data-cn="OUC&#20415;&#27665;&#26381;&#21153;" data-en="OUC Services"></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="landing-updates">
        <div class="container">
            <div class="landing-updates-grid">
                <article class="landing-feed-box">
                    <header class="landing-feed-head">
                        <h2 data-cn="@ &#20844;&#21578;&#36890;&#30693;" data-en="@ Announcements"></h2>
                        <a href="<?php echo esc_url($announcement_archive_url); ?>" data-cn="&#26356;&#22810;" data-en="More"></a>
                    </header>
                    <ul class="landing-feed">
                        <?php
                        $announcements = new WP_Query(array(
                            'post_type' => 'announcement',
                            'post_status' => 'publish',
                            'posts_per_page' => 5,
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'ignore_sticky_posts' => true,
                            'no_found_rows' => true
                        ));
                        if ($announcements->have_posts()) :
                            while ($announcements->have_posts()) : $announcements->the_post();
                                $announcement_excerpt = get_the_excerpt();
                                if ('' === trim($announcement_excerpt)) {
                                    $announcement_excerpt = wp_trim_words(wp_strip_all_tags(get_the_content()), 40, '...');
                                }
                        ?>
                            <li class="landing-feed-item">
                                <h3 class="landing-feed-title">
                                    <a class="landing-feed-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="landing-feed-meta">
                                    <span class="landing-feed-author"><?php echo esc_html(get_the_author()); ?></span>
                                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('Y-m-d'); ?></time>
                                </div>
                                <p class="landing-feed-excerpt"><?php echo esc_html($announcement_excerpt); ?></p>
                            </li>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <li class="landing-feed-empty" data-cn="&#26242;&#26080;&#20844;&#21578;" data-en="No announcements found."></li>
                        <?php endif; ?>
                    </ul>
                </article>

                <article class="landing-feed-box">
                    <header class="landing-feed-head">
                        <h2 data-cn="@ &#31038;&#22242;&#26032;&#38395;" data-en="@ Club News"></h2>
                        <a href="<?php echo esc_url($club_news_url); ?>" data-cn="&#26356;&#22810;" data-en="More"></a>
                    </header>
                    <ul class="landing-feed">
                        <?php
                        $blogs = new WP_Query(array(
                            'post_type' => 'news',
                            'post_status' => 'publish',
                            'posts_per_page' => 5,
                            'orderby' => 'date',
                            'order' => 'DESC',
                            'ignore_sticky_posts' => true,
                            'no_found_rows' => true
                        ));
                        if ($blogs->have_posts()) :
                            while ($blogs->have_posts()) : $blogs->the_post();
                                $blog_excerpt = get_the_excerpt();
                                if ('' === trim($blog_excerpt)) {
                                    $blog_excerpt = wp_trim_words(wp_strip_all_tags(get_the_content()), 40, '...');
                                }
                        ?>
                            <li class="landing-feed-item">
                                <h3 class="landing-feed-title">
                                    <a class="landing-feed-link" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <div class="landing-feed-meta">
                                    <span class="landing-feed-author"><?php echo esc_html(get_the_author()); ?></span>
                                    <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('Y-m-d'); ?></time>
                                </div>
                                <p class="landing-feed-excerpt"><?php echo esc_html($blog_excerpt); ?></p>
                            </li>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <li class="landing-feed-empty" data-cn="&#26242;&#26080;&#31038;&#22242;&#26032;&#38395;" data-en="No club news found."></li>
                        <?php endif; ?>
                    </ul>
                </article>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
