<?php get_header(); ?>

<main class="site-main">
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 data-cn="爱特工作室" data-en="IT STUDIO"></h1>
                <p class="hero-description" data-cn="爱特工作室成立于2002年，致力于发现人才、培养人才、输送人才。现已拥有UI设计、Web开发、程序设计、Android开发、游戏设计、iOS开发六大技术方向，是海大网络技术的中坚力量！" data-en="Founded in 2002, IT Studio is dedicated to discovering, cultivating, and delivering talent. With six major technical directions including UI Design, Web Development, Programming, Android Development, Game Design, and iOS Development, we are the backbone of OUC's network technology!"></p>
            </div>

            <!-- 服务提供模块 -->
            <div class="services-provided">
                <h2 data-cn="@服务提供" data-en="@Our Services"></h2>
                <div class="services-grid-box">
                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad-res" x1="0" y1="64" x2="64" y2="0" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <path d="M4,16 L24,16 L30,22 L60,22 L60,54 L4,54 Z" fill="#bab5ec" opacity="0.5"/>
                                <rect x="10" y="10" width="40" height="40" fill="#f8d1d9" rx="2" />
                                <rect x="16" y="18" width="20" height="2" fill="#bab5ec" />
                                <rect x="16" y="24" width="28" height="2" fill="#bab5ec" />
                                <path d="M4,28 L60,28 L60,54 A4,4 0 0,1 56,58 L8,58 A4,4 0 0,1 4,54 Z" fill="url(#grad-res)"/>
                            </svg>
                        </div>
                        <span data-cn="资源站" data-en="Resources"></span>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="mirror-gradient" x1="10" y1="60" x2="54" y2="4" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <path d="M32,4 A28,28 0 1,1 4,32 A28,28 0 0,1 32,4 M32,22 A10,10 0 1,0 42,32 A10,10 0 0,0 32,22 Z" fill="url(#mirror-gradient)" stroke="#bab5ec" stroke-width="1" opacity="0.95"/>
                                <path d="M32,4 A28,28 0 0,1 60,32 L42,32 A10,10 0 0,0 32,22 Z" fill="#f8d1d9" opacity="0.8"/>
                                <circle cx="32" cy="32" r="7" fill="none" stroke="#f8d1d9" stroke-width="2" opacity="0.6" />
                                <rect x="14" y="42" width="4" height="4" rx="1" fill="#bab5ec" transform="rotate(45 16 44)" />
                                <rect x="50" y="14" width="3" height="3" rx="0.5" fill="#f8d1d9" />
                                <path d="M16,46 A22,22 0 0,0 48,46" fill="none" stroke="#f8d1d9" stroke-width="2" stroke-linecap="round" stroke-dasharray="4 4" opacity="0.5" />
                            </svg>
                        </div>
                        <span data-cn="校内镜像站" data-en="Mirror Site"></span>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad-code" x1="10" y1="60" x2="50" y2="10" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <rect x="8" y="8" width="48" height="48" rx="3" fill="none" stroke="#bab5ec" stroke-width="2"/>
                                <rect x="12" y="14" width="40" height="10" rx="1" fill="url(#grad-code)"/>
                                <circle cx="46" cy="19" r="2" fill="#f8d1d9"/>
                                <circle cx="40" cy="19" r="2" fill="#fff" opacity="0.5"/>
                                <rect x="12" y="27" width="40" height="10" rx="1" fill="url(#grad-code)"/>
                                <circle cx="46" cy="32" r="2" fill="#f8d1d9"/>
                                <rect x="12" y="40" width="40" height="10" rx="1" fill="url(#grad-code)"/>
                                <circle cx="46" cy="45" r="2" fill="#f8d1d9"/>
                                <circle cx="40" cy="45" r="2" fill="#fff" opacity="0.5"/>
                            </svg>
                        </div>
                        <span data-cn="代码托管" data-en="Git Hosting"></span>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad-mc" x1="0" y1="64" x2="64" y2="0" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <path d="M32,6 L58,21 L32,36 L6,21 Z" fill="#f8d1d9"/>
                                <path d="M58,21 L58,51 L32,66 L32,36 Z" fill="#bab5ec"/>
                                <path d="M6,21 L32,36 L32,66 L6,51 Z" fill="url(#grad-mc)"/>
                                <rect x="28" y="30" width="8" height="8" transform="rotate(30 32 34) skewX(-30)" fill="#bab5ec" opacity="0.3"/>
                            </svg>
                        </div>
                        <span data-cn="Minecraft服务器" data-en="Minecraft Server"></span>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad-chat" x1="0" y1="50" x2="50" y2="0" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <path d="M28,10 L54,10 C56.2,10 58,11.8 58,14 L58,36 C58,38.2 56.2,40 54,40 L44,40 L44,46 L36,40 L28,40 C25.8,40 24,38.2 24,36 L24,14 C24,11.8 25.8,10 28,10 Z" fill="#bab5ec" opacity="0.4"/>
                                <path d="M8,20 L42,20 C44.2,20 46,21.8 46,24 L46,48 C46,50.2 44.2,52 42,52 L18,52 L10,58 L10,52 L8,52 C5.8,52 4,50.2 4,48 L4,24 C4,21.8 5.8,20 8,20 Z" fill="url(#grad-chat)"/>
                                <rect x="14" y="30" width="22" height="3" rx="1.5" fill="#f8d1d9"/>
                                <rect x="14" y="38" width="14" height="3" rx="1.5" fill="#f8d1d9"/>
                            </svg>
                        </div>
                        <span data-cn="OUC论坛" data-en="OUC Forum"></span>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad-fix" x1="10" y1="50" x2="50" y2="10" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <circle cx="40" cy="24" r="14" fill="none" stroke="#bab5ec" stroke-width="4" stroke-dasharray="6 3"/>
                                <circle cx="40" cy="24" r="5" fill="#bab5ec" opacity="0.5"/>
                                <g transform="translate(-5, 5)">
                                    <rect x="16" y="34" width="30" height="10" rx="2" transform="rotate(-45 31 39)" fill="url(#grad-fix)"/>
                                    <path d="M12,12 C16,8 22,8 26,12 L22,16 C20,14 18,14 16,16 L12,12 Z" fill="url(#grad-fix)" transform="rotate(-45 19 14)"/>
                                    <circle cx="14" cy="48" r="3" fill="#f8d1d9"/>
                                </g>
                            </svg>
                        </div>
                        <span data-cn="电脑维修" data-en="PC Repair"></span>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad-58" x1="0" y1="60" x2="60" y2="0" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <path d="M8,12 L30,12 L28,20 L16,20 L14,28 C16,26 20,26 24,28 C28,30 28,36 24,40 C20,44 12,42 10,38" fill="none" stroke="url(#grad-58)" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M44,12 C38,12 36,20 44,24 C52,28 52,38 44,42 C36,38 36,28 44,24 C52,20 50,12 44,12 Z" fill="none" stroke="#bab5ec" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="54" cy="12" r="3" fill="#f8d1d9"/>
                                <rect x="4" y="46" width="6" height="6" transform="rotate(20)" fill="#bab5ec" opacity="0.6"/>
                            </svg>
                        </div>
                        <span data-cn="五八工坊预约" data-en="Workshop Booking"></span>
                    </a>

                    <a href="#" class="service-item">
                        <div class="service-icon">
                            <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="grad-heart" x1="10" y1="50" x2="54" y2="10" gradientUnits="userSpaceOnUse">
                                        <stop offset="0" stop-color="#bab5ec"/>
                                        <stop offset="1" stop-color="#f1b7bf"/>
                                    </linearGradient>
                                </defs>
                                <path d="M32,54 L12,34 C6,28 6,18 16,14 C22,12 28,16 32,22" fill="url(#grad-heart)"/>
                                <path d="M32,54 L52,34 C58,28 58,18 48,14 C42,12 36,16 32,22" fill="#bab5ec" opacity="0.8"/>
                                <path d="M32,8 L32,14" stroke="#f8d1d9" stroke-width="3" stroke-linecap="round"/>
                                <path d="M12,10 L16,14" stroke="#f8d1d9" stroke-width="2" stroke-linecap="round"/>
                                <path d="M52,10 L48,14" stroke="#f8d1d9" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <span data-cn="OUC便民服务" data-en="OUC Services"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="announcements-column">
                    <h2 data-cn="@公告通知" data-en="@Announcements"></h2>
                    <div class="post-list">
                        <?php
                        $announcements = new WP_Query(array(
                            'post_type' => 'announcement',
                            'posts_per_page' => 5,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));

                        if ($announcements->have_posts()) :
                            while ($announcements->have_posts()) : $announcements->the_post();
                        ?>
                            <article class="post-item">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                            </article>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <p><?php _e('暂无公告', 'itstudio'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="blog-column">
                    <h2 data-cn="@技术博客" data-en="@Blog"></h2>
                    <div class="post-list">
                        <?php
                        $blogs = new WP_Query(array(
                            'post_type' => 'post',
                            'posts_per_page' => 5,
                            'orderby' => 'date',
                            'order' => 'DESC'
                        ));

                        if ($blogs->have_posts()) :
                            while ($blogs->have_posts()) : $blogs->the_post();
                        ?>
                            <article class="post-item">
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                            </article>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <p><?php _e('暂无博客文章', 'itstudio'); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
