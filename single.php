<?php get_header(); ?>

<main class="site-main single-post">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
        ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="entry-meta">
                        <time datetime="<?php echo get_the_date('c'); ?>">
                            <?php echo get_the_date(); ?>
                        </time>
                        <?php if (get_post_type() === 'post') : ?>
                            <span class="separator">|</span>
                            <span class="author"><?php the_author(); ?></span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="entry-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <?php if (get_post_type() === 'post') : ?>
                    <footer class="entry-footer">
                        <?php
                        $categories = get_the_category();
                        if ($categories) :
                        ?>
                            <div class="entry-categories">
                                <strong><?php _e('分类:', 'itstudio'); ?></strong>
                                <?php
                                foreach ($categories as $category) {
                                    echo '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a> ';
                                }
                                ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        $tags = get_the_tags();
                        if ($tags) :
                        ?>
                            <div class="entry-tags">
                                <strong><?php _e('标签:', 'itstudio'); ?></strong>
                                <?php
                                foreach ($tags as $tag) {
                                    echo '<a href="' . get_tag_link($tag->term_id) . '">' . $tag->name . '</a> ';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </footer>
                <?php endif; ?>
            </article>

            <?php
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
