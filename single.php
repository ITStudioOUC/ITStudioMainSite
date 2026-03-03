<?php get_header(); ?>

<main class="site-main single-article-page">
    <div class="container single-article-shell">
        <?php while (have_posts()) : the_post(); ?>
            <?php
            $post_id = get_the_ID();
            $post_type = get_post_type($post_id);
            $post_type_object = get_post_type_object($post_type);
            $post_type_archive_url = get_post_type_archive_link($post_type);
            $post_type_label_en = $post_type_object ? $post_type_object->labels->name : '';
            $post_type_label_cn = $post_type_label_en;
            if ($post_type === 'announcement') {
                $post_type_label_cn = '公告通知';
                $post_type_label_en = 'Announcements';
            } elseif ($post_type === 'news') {
                $post_type_label_cn = '社团新闻';
                $post_type_label_en = 'News';
            } elseif ($post_type === 'post') {
                $post_type_label_cn = '技术博客';
                $post_type_label_en = 'Blog';
            }
            $views = function_exists('itstudio_get_post_views') ? (int) itstudio_get_post_views($post_id) : 0;

            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>
                <header class="single-article-header">
                    <div class="single-article-kicker">
                        <?php if (!empty($post_type_archive_url) && !empty($post_type_label_en)) : ?>
                            <a class="single-article-kicker-link" href="<?php echo esc_url($post_type_archive_url); ?>" data-cn="<?php echo esc_attr($post_type_label_cn); ?>" data-en="<?php echo esc_attr($post_type_label_en); ?>"><?php echo esc_html($post_type_label_en); ?></a>
                        <?php endif; ?>
                    </div>

                    <h1 class="single-article-title"><?php the_title(); ?></h1>

                    <div class="single-article-meta">
                        <span class="single-article-meta-item">
                            <span class="single-article-meta-label" data-cn="发布日期：" data-en="Published: ">Published: </span>
                            <time class="single-article-meta-value" datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y年m月d日')); ?></time>
                        </span>
                        <span class="single-article-meta-item">
                            <span class="single-article-meta-label" data-cn="作者：" data-en="Author: ">Author: </span>
                            <span class="single-article-meta-value"><?php echo esc_html(get_the_author()); ?></span>
                        </span>
                        <span class="single-article-meta-item">
                            <span class="single-article-meta-label" data-cn="浏览量：" data-en="Views: ">Views: </span>
                            <span class="single-article-meta-value"><?php echo esc_html(number_format_i18n($views)); ?></span>
                        </span>
                    </div>
                </header>

                <div class="single-article-body">
                    <?php the_content(); ?>
                </div>

                <?php
                $tags = get_the_terms($post_id, 'post_tag');
                if (!empty($tags) && !is_wp_error($tags)) :
                ?>
                    <footer class="single-article-tags">
                        <?php foreach (array_slice($tags, 0, 8) as $tag) : ?>
                            <?php $tag_link = get_term_link($tag); ?>
                            <?php if (!is_wp_error($tag_link)) : ?>
                                <a class="single-article-tag" href="<?php echo esc_url($tag_link); ?>">#<?php echo esc_html($tag->name); ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </footer>
                <?php endif; ?>
            </article>

            <section class="single-related">
                <div class="single-divider" aria-hidden="true"></div>
                <h2 class="single-related-title" data-cn="相关阅读" data-en="Related Content">Related Content</h2>

                <?php
                $related_args = array(
                    'post_type' => $post_type,
                    'post_status' => 'publish',
                    'posts_per_page' => 3,
                    'post__not_in' => array($post_id),
                    'ignore_sticky_posts' => true,
                    'orderby' => 'date',
                    'order' => 'DESC',
                );
                $tag_ids = wp_get_post_terms($post_id, 'post_tag', array('fields' => 'ids'));
                if (!empty($tag_ids) && !is_wp_error($tag_ids)) {
                    $related_args['tax_query'] = array(
                        array(
                            'taxonomy' => 'post_tag',
                            'field' => 'term_id',
                            'terms' => $tag_ids,
                        ),
                    );
                }

                $related_query = new WP_Query($related_args);
                if (!$related_query->have_posts() && isset($related_args['tax_query'])) {
                    unset($related_args['tax_query']);
                    $related_query = new WP_Query($related_args);
                }
                ?>

                <div class="single-related-grid">
                    <?php if ($related_query->have_posts()) : ?>
                        <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                            <?php
                            $related_excerpt = function_exists('itstudio_get_post_excerpt_chars')
                                ? itstudio_get_post_excerpt_chars(get_the_ID(), 96)
                                : wp_html_excerpt(wp_strip_all_tags(get_the_excerpt() ?: get_the_content()), 96, '...');
                            ?>
                            <article class="single-related-card">
                                <h3 class="single-related-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p class="single-related-card-excerpt"><?php echo esc_html($related_excerpt); ?></p>
                                <a class="single-related-card-link" href="<?php the_permalink(); ?>" data-cn="阅读更多 ->" data-en="Read more ->">Read more -></a>
                            </article>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <p class="single-related-empty" data-cn="暂无相关文章" data-en="No related content">No related content</p>
                    <?php endif; ?>
                </div>
            </section>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
