<?php get_header(); ?>

<?php
$itstudio_archive_mode = isset($GLOBALS['itstudio_archive_mode']) ? sanitize_key((string) $GLOBALS['itstudio_archive_mode']) : '';
$is_itstudio_news_page = is_post_type_archive('news') || ($itstudio_archive_mode === 'news');
$is_itstudio_notice_page = is_post_type_archive('announcement') || ($itstudio_archive_mode === 'announcement');
$is_itstudio_content_page = $is_itstudio_news_page || $is_itstudio_notice_page;
?>

<?php if ($is_itstudio_content_page) : ?>
<?php
$keyword = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$active_post_type = $is_itstudio_news_page ? 'news' : 'announcement';
$archive_url = $is_itstudio_news_page ? get_post_type_archive_link('news') : get_post_type_archive_link('announcement');
if (!$archive_url) {
    $archive_url = $is_itstudio_news_page ? home_url('/news') : home_url('/announcements');
}

$default_cover_url = get_template_directory_uri() . '/resources/it_logo_2024.svg';
$weight_meta_key = 'itstudio_weight';
$title_cn = $is_itstudio_news_page ? '社团新闻' : '公告通知';
$title_en = $is_itstudio_news_page ? 'Club News' : 'Announcements';
$empty_cn = $is_itstudio_news_page ? '暂无新闻' : '暂无公告';
$empty_en = $is_itstudio_news_page ? 'No news found.' : 'No announcements found.';
$side_title_cn = $is_itstudio_news_page ? '要闻' : '重要公告';
$side_title_en = $is_itstudio_news_page ? 'Top Stories' : 'Important Announcements';

$list_query = new WP_Query(array(
    'post_type' => $active_post_type,
    'post_status' => 'publish',
    'posts_per_page' => 10,
    'paged' => $paged,
    's' => $keyword,
    'ignore_sticky_posts' => true,
    'orderby' => 'date',
    'order' => 'DESC',
));

$featured_query = new WP_Query(array(
    'post_type' => $active_post_type,
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'ignore_sticky_posts' => true,
    'meta_key' => $weight_meta_key,
    'orderby' => array(
        'meta_value_num' => 'DESC',
        'date' => 'DESC',
    ),
    'order' => 'DESC',
    'no_found_rows' => true,
));
$featured_ids = array_map('intval', wp_list_pluck($featured_query->posts, 'ID'));
wp_reset_postdata();

if (count($featured_ids) < 4) {
    $fill_query = new WP_Query(array(
        'post_type' => $active_post_type,
        'post_status' => 'publish',
        'posts_per_page' => 4 - count($featured_ids),
        'ignore_sticky_posts' => true,
        'post__not_in' => $featured_ids,
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
    ));
    $featured_ids = array_merge($featured_ids, array_map('intval', wp_list_pluck($fill_query->posts, 'ID')));
    wp_reset_postdata();
}
$featured_ids = array_slice(array_values(array_unique($featured_ids)), 0, 4);
?>
<main class="site-main news-archive-page">
    <div class="container">
        <header class="news-archive-head">
            <h1 class="news-archive-title" data-cn="<?php echo esc_attr($title_cn); ?>" data-en="<?php echo esc_attr($title_en); ?>"><?php echo esc_html($title_en); ?></h1>
        </header>

        <div class="news-archive-tools news-archive-tools-single">
            <form class="news-archive-search" method="get" action="<?php echo esc_url($archive_url); ?>">
                <input
                    type="search"
                    name="q"
                    value="<?php echo esc_attr($keyword); ?>"
                    placeholder="<?php esc_attr_e('Search posts...', 'itstudio'); ?>"
                    aria-label="<?php esc_attr_e('Search posts', 'itstudio'); ?>"
                    data-cn-placeholder="搜索文章..."
                    data-en-placeholder="Search posts..."
                    data-cn-aria-label="搜索文章"
                    data-en-aria-label="Search posts"
                >
                <button type="submit" data-cn="搜索" data-en="Search">Search</button>
            </form>
        </div>

        <div class="news-archive-layout">
            <section class="news-main-column">
                <?php if ($list_query->have_posts()) : ?>
                    <div class="news-stream">
                        <?php while ($list_query->have_posts()) : $list_query->the_post(); ?>
                            <?php
                            $post_id = get_the_ID();
                            $excerpt = function_exists('itstudio_get_post_excerpt_chars')
                                ? itstudio_get_post_excerpt_chars($post_id, 200)
                                : wp_html_excerpt(wp_strip_all_tags(get_the_excerpt() ?: get_the_content()), 200, '...');
                            $views = function_exists('itstudio_get_post_views') ? (int) itstudio_get_post_views($post_id) : 0;
                            $char_count = function_exists('itstudio_get_post_char_count') ? (int) itstudio_get_post_char_count($post_id) : (int) strlen(wp_strip_all_tags(get_the_content()));
                            $tags = get_the_terms($post_id, 'post_tag');
                            ?>
                            <article class="news-story">
                                <div class="news-story-body">
                                    <h2 class="news-story-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

                                    <div class="news-story-meta">
                                        <span class="news-story-meta-item">
                                            <span data-cn="浏览量" data-en="Views">Views</span>
                                            <strong><?php echo esc_html(number_format_i18n($views)); ?></strong>
                                        </span>
                                        <span class="news-story-meta-dot" aria-hidden="true">/</span>
                                        <time class="news-story-meta-item" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <span data-cn="发表于" data-en="Published">Published</span>
                                            <?php echo esc_html(get_the_date('Y-m-d')); ?>
                                        </time>
                                        <span class="news-story-meta-dot" aria-hidden="true">/</span>
                                        <span class="news-story-meta-item">
                                            <span data-cn="发布者" data-en="Author">Author</span>
                                            <?php echo esc_html(get_the_author()); ?>
                                        </span>
                                        <span class="news-story-meta-dot" aria-hidden="true">/</span>
                                        <span class="news-story-meta-item">
                                            <span data-cn="字数" data-en="Words">Words</span>
                                            <strong><?php echo esc_html(number_format_i18n($char_count)); ?></strong>
                                        </span>
                                    </div>

                                    <p class="news-story-excerpt"><?php echo esc_html($excerpt); ?></p>

                                    <div class="news-story-tags">
                                        <span class="news-story-tag-label" data-cn="标签" data-en="Tags">Tags</span>
                                        <?php if (!empty($tags) && !is_wp_error($tags)) : ?>
                                            <?php foreach (array_slice($tags, 0, 5) as $tag) : ?>
                                                <?php $tag_link = get_term_link($tag); ?>
                                                <?php if (!is_wp_error($tag_link)) : ?>
                                                    <a class="news-story-tag" href="<?php echo esc_url($tag_link); ?>">#<?php echo esc_html($tag->name); ?></a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <span class="news-story-tag-empty" data-cn="无标签" data-en="No tags">No tags</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <a class="news-story-cover" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
                                    <?php else : ?>
                                        <img class="news-story-cover-fallback" src="<?php echo esc_url($default_cover_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy">
                                    <?php endif; ?>
                                </a>
                            </article>
                        <?php endwhile; ?>
                    </div>

                    <nav class="news-archive-pagination" aria-label="Pagination">
                        <?php
                        $pagination_base = esc_url_raw(add_query_arg('paged', '%#%', $archive_url));
                        echo wp_kses_post(
                            paginate_links(array(
                                'base' => $pagination_base,
                                'format' => '',
                                'current' => $paged,
                                'total' => max(1, (int) $list_query->max_num_pages),
                                'mid_size' => 2,
                                'prev_text' => '<span data-cn="上一页" data-en="Previous">Previous</span>',
                                'next_text' => '<span data-cn="下一页" data-en="Next">Next</span>',
                                'add_args' => array_filter(
                                    array(
                                        'q' => $keyword,
                                    ),
                                    static function ($value) {
                                        return $value !== '';
                                    }
                                ),
                            ))
                        );
                        ?>
                    </nav>
                <?php else : ?>
                    <p class="news-archive-empty" data-cn="<?php echo esc_attr($empty_cn); ?>" data-en="<?php echo esc_attr($empty_en); ?>"><?php echo esc_html($empty_en); ?></p>
                <?php endif; ?>
                <?php wp_reset_postdata(); ?>
            </section>

            <aside class="news-side-column">
                <section class="news-side-block">
                    <h2 class="news-side-title" data-cn="<?php echo esc_attr($side_title_cn); ?>" data-en="<?php echo esc_attr($side_title_en); ?>"><?php echo esc_html($side_title_en); ?></h2>
                    <ul class="news-side-list">
                        <?php if (!empty($featured_ids)) : ?>
                            <?php foreach ($featured_ids as $featured_id) : ?>
                                <?php
                                $featured_title = get_the_title($featured_id);
                                $featured_link = get_permalink($featured_id);
                                $featured_views = function_exists('itstudio_get_post_views') ? (int) itstudio_get_post_views($featured_id) : 0;
                                $featured_cover = get_the_post_thumbnail_url($featured_id, 'thumbnail');
                                ?>
                                <li class="news-side-item">
                                    <a class="news-side-cover" href="<?php echo esc_url($featured_link); ?>" aria-label="<?php echo esc_attr($featured_title); ?>">
                                        <?php if (!empty($featured_cover)) : ?>
                                            <img src="<?php echo esc_url($featured_cover); ?>" alt="<?php echo esc_attr($featured_title); ?>" loading="lazy">
                                        <?php else : ?>
                                            <img class="news-side-cover-fallback" src="<?php echo esc_url($default_cover_url); ?>" alt="<?php echo esc_attr($featured_title); ?>" loading="lazy">
                                        <?php endif; ?>
                                    </a>
                                    <div class="news-side-body">
                                        <h3 class="news-side-item-title">
                                            <a href="<?php echo esc_url($featured_link); ?>"><?php echo esc_html($featured_title); ?></a>
                                        </h3>
                                        <div class="news-side-meta">
                                            <time datetime="<?php echo esc_attr(get_the_date('c', $featured_id)); ?>"><?php echo esc_html(get_the_date('Y-m-d', $featured_id)); ?></time>
                                            <span class="news-side-meta-dot" aria-hidden="true">/</span>
                                            <span>
                                                <span data-cn="浏览" data-en="Views">Views</span>
                                                <?php echo esc_html(number_format_i18n($featured_views)); ?>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li class="news-side-empty" data-cn="暂无文章" data-en="No posts found.">No posts found.</li>
                        <?php endif; ?>
                    </ul>
                </section>
            </aside>
        </div>
    </div>
</main>
<?php else : ?>
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
                                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                    <?php echo esc_html(get_the_date()); ?>
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
                                <?php esc_html_e('Read More', 'itstudio'); ?> ->
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('Previous', 'itstudio'),
                    'next_text' => __('Next', 'itstudio'),
                ));
                ?>
            </div>
        <?php else : ?>
            <p><?php esc_html_e('No posts found.', 'itstudio'); ?></p>
        <?php endif; ?>
    </div>
</main>
<?php endif; ?>

<?php get_footer(); ?>
