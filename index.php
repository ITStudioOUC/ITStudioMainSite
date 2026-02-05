<?php get_header(); ?>

<main class="site-main">
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1><?php _e('IT Studio', 'itstudio'); ?></h1>
                <p class="hero-description">
                    <?php _e('爱特工作室成立于2002年，致力于发现人才、培养人才、输送人才。现已拥有UI设计、Web开发、程序设计、Android开发、游戏设计、iOS开发六大技术方向，是海大网络技术的中坚力量！', 'itstudio'); ?>
                </p>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container">
            <div class="content-grid">
                <div class="announcements-column">
                    <h2><?php _e('公告通知', 'itstudio'); ?> <span class="subtitle">Announcements</span></h2>
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
                    <h2><?php _e('技术博客', 'itstudio'); ?> <span class="subtitle">Tech Blog</span></h2>
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
