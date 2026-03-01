<?php get_header(); ?>

<main class="site-main home-landing">
    <?php
    $announcement_archive_url = get_post_type_archive_link('announcement');
    if (!$announcement_archive_url) {
        $announcement_archive_url = home_url('/announcements');
    }
    $posts_page_id = (int) get_option('page_for_posts');
    $blog_archive_url = $posts_page_id ? get_permalink($posts_page_id) : '';
    if (!$blog_archive_url) {
        $blog_archive_url = home_url('/blog');
    }
    ?>

    <section class="landing-hero">
        <canvas class="landing-hero-canvas" aria-hidden="true"></canvas>
        <div class="container">
            <div class="landing-hero-content">
                <h1 class="landing-hero-title" data-cn="爱特工作室" data-en="IT STUDIO"></h1>
                <p class="landing-hero-subtitle" data-cn="中国海洋大学信息技术与工程实践团队" data-en="Technology and Engineering Practice Team at OUC"></p>
                <a class="landing-hero-btn" href="<?php echo esc_url(home_url('/about')); ?>" data-cn="了解更多" data-en="Learn More"></a>
            </div>
        </div>
    </section>

    <section class="services-section">
        <div class="container">
            <div class="services-provided">
                <h2 data-cn="@ 服务提供" data-en="@ Services"></h2>
                <div class="services-grid-box">
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10 14L24 14L28 20H54C56.2091 20 58 21.7909 58 24V50C58 52.2091 56.2091 54 54 54H10C7.79086 54 6 52.2091 6 50V18C6 15.7909 7.79086 14 10 14Z" stroke="#ccd6f6"/>
                                <path d="M22 34H42" stroke="#64ffda"/>
                                <path d="M22 42H34" stroke="#64ffda"/>
                                <circle cx="48" cy="42" r="2" fill="#64ffda" stroke="none"/>
                            </svg>
                        </div>
                        <span data-cn="资源站" data-en="Resources"></span>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="32" cy="32" r="24" stroke="#ccd6f6"/>
                                <circle cx="32" cy="32" r="8" stroke="#ccd6f6"/>
                                <path d="M32 24V8" stroke="#64ffda"/>
                                <path d="M32 40V56" stroke="#233554"/>
                                <path d="M49 32H56" stroke="#64ffda"/>
                                <path d="M8 32H15" stroke="#233554"/>
                                <path d="M44 14C48 18 50 24 50 32" stroke="#64ffda" stroke-dasharray="4 4"/>
                            </svg>
                        </div>
                        <span data-cn="校内镜像站" data-en="Mirror Site"></span>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="16" y1="12" x2="16" y2="52" stroke="#ccd6f6"/>
                                <circle cx="16" cy="12" r="4" stroke="#ccd6f6"/>
                                <circle cx="16" cy="32" r="4" stroke="#ccd6f6"/>
                                <circle cx="16" cy="52" r="4" stroke="#ccd6f6"/>
                                <path d="M16 32C26 32 36 36 36 44V48" stroke="#64ffda"/>
                                <circle cx="36" cy="48" r="4" stroke="#64ffda"/>
                            </svg>
                        </div>
                        <span data-cn="代码托管" data-en="Git Hosting"></span>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M32 8L54 20V44L32 56L10 44V20L32 8Z" stroke="#ccd6f6"/>
                                <path d="M10 20L32 32L54 20" stroke="#ccd6f6"/>
                                <path d="M32 56V32" stroke="#ccd6f6"/>
                                <path d="M26 16L32 20L38 16" stroke="#64ffda"/>
                                <path d="M46 29L46 38" stroke="#64ffda"/>
                            </svg>
                        </div>
                        <span data-cn="Minecraft服务器" data-en="Minecraft Server"></span>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 16H42V40H20L12 48V16Z" stroke="#ccd6f6"/>
                                <path d="M26 10H56V34H44L38 40V34H26V10Z" stroke="#64ffda"/>
                                <line x1="32" y1="20" x2="50" y2="20" stroke="#64ffda"/>
                                <line x1="32" y1="26" x2="44" y2="26" stroke="#64ffda"/>
                            </svg>
                        </div>
                        <span data-cn="OUC论坛" data-en="OUC Forum"></span>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M46 14L50 18L26 42L22 38L46 14Z" stroke="#ccd6f6"/>
                                <path d="M22 38L14 46L18 50L26 42" stroke="#ccd6f6"/>
                                <path d="M14 46L10 54" stroke="#ccd6f6"/>
                                <path d="M45 28L36 19" stroke="#64ffda"/>
                                <path d="M20 44L14 50" stroke="#233554"/>
                                <path d="M50 15C53 12 58 12 60 14C62 16 62 21 59 24L34 49C32 51 29 51 27 49L25 47C23 45 23 42 25 40L50 15Z" stroke="#64ffda"/>
                            </svg>
                        </div>
                        <span data-cn="电脑维修" data-en="PC Repair"></span>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="10" y="12" width="44" height="40" rx="4" stroke="#ccd6f6"/>
                                <path d="M10 24H54" stroke="#ccd6f6"/>
                                <path d="M20 6V16" stroke="#ccd6f6"/>
                                <path d="M44 6V16" stroke="#ccd6f6"/>
                                <circle cx="32" cy="38" r="6" stroke="#64ffda"/>
                                <path d="M32 38L36 34" stroke="#64ffda"/>
                                <circle cx="32" cy="38" r="2" fill="#64ffda" stroke="none"/>
                            </svg>
                        </div>
                        <span data-cn="五八工坊预约" data-en="Workshop Booking"></span>
                    </div>
                    <div class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M32 56C32 56 56 44 56 26C56 16 46 10 38 16C35 18 32 22 32 22C32 22 29 18 26 16C18 10 8 16 8 26C8 44 32 56 32 56Z" stroke="#ccd6f6"/>
                                <path d="M32 22V36" stroke="#64ffda"/>
                                <circle cx="32" cy="40" r="3" stroke="#64ffda"/>
                                <path d="M18 28H24L26 32" stroke="#233554"/>
                                <path d="M46 28H40L38 32" stroke="#64ffda"/>
                            </svg>
                        </div>
                        <span data-cn="OUC便民服务" data-en="OUC Services"></span>
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
                        <h2 data-cn="@ 公告通知" data-en="@ Announcements"></h2>
                        <a href="<?php echo esc_url($announcement_archive_url); ?>" data-cn="更多" data-en="More"></a>
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
                            <li class="landing-feed-empty" data-cn="暂无公告" data-en="No announcements found."></li>
                        <?php endif; ?>
                    </ul>
                </article>

                <article class="landing-feed-box">
                    <header class="landing-feed-head">
                        <h2 data-cn="@ 技术博客" data-en="@ Blog"></h2>
                        <a href="<?php echo esc_url($blog_archive_url); ?>" data-cn="更多" data-en="More"></a>
                    </header>
                    <ul class="landing-feed">
                        <?php
                        $blogs = new WP_Query(array(
                            'post_type' => 'post',
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
                            <li class="landing-feed-empty" data-cn="暂无博客文章" data-en="No blog posts found."></li>
                        <?php endif; ?>
                    </ul>
                </article>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
