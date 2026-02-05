<?php get_header(); ?>

<main class="site-main archive-page">
    <div class="container">
        <header class="archive-header">
            <?php
            the_archive_title('<h1 class="archive-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="posts-grid">
                <?php
                while (have_posts()) :
                    the_post();
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('archive-item'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="archive-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="archive-content">
                            <h2 class="archive-item-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <div class="archive-meta">
                                <time datetime="<?php echo get_the_date('c'); ?>">
                                    <?php echo get_the_date(); ?>
                                </time>
                                <?php if (get_post_type() === 'post') : ?>
                                    <span class="separator">|</span>
                                    <span class="author"><?php the_author(); ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if (has_excerpt()) : ?>
                                <div class="archive-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>

                            <a href="<?php the_permalink(); ?>" class="read-more">
                                <?php _e('阅读更多', 'itstudio'); ?> →
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('← 上一页', 'itstudio'),
                    'next_text' => __('下一页 →', 'itstudio'),
                ));
                ?>
            </div>
        <?php else : ?>
            <p><?php _e('暂无内容', 'itstudio'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
