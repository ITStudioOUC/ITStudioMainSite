<?php get_header(); ?>

<?php
$default_icon_url = get_template_directory_uri() . '/resources/it_logo_2024.svg';
$services_query = new WP_Query(array(
    'post_type' => 'service',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => array(
        'menu_order' => 'ASC',
        'date' => 'DESC',
    ),
));

$grouped_services = array();
$uncategorized_items = array();

if ($services_query->have_posts()) {
    while ($services_query->have_posts()) {
        $services_query->the_post();
        $service_id = get_the_ID();
        $service_terms = get_the_terms($service_id, 'service_category');
        $service_url = function_exists('itstudio_get_service_target_url') ? itstudio_get_service_target_url($service_id) : get_permalink($service_id);
        $service_url = is_string($service_url) ? trim($service_url) : '';
        if ($service_url === '') {
            $service_url = '#';
        }

        $i18n_content = function_exists('itstudio_get_service_i18n_content')
            ? itstudio_get_service_i18n_content($service_id, 90)
            : array(
                'title_cn' => get_the_title($service_id),
                'title_en' => get_the_title($service_id),
                'excerpt_cn' => function_exists('itstudio_get_post_excerpt_chars')
                    ? itstudio_get_post_excerpt_chars($service_id, 90)
                    : wp_html_excerpt(wp_strip_all_tags(get_the_excerpt() ?: get_the_content()), 90, '...'),
                'excerpt_en' => function_exists('itstudio_get_post_excerpt_chars')
                    ? itstudio_get_post_excerpt_chars($service_id, 90)
                    : wp_html_excerpt(wp_strip_all_tags(get_the_excerpt() ?: get_the_content()), 90, '...'),
            );

        $item = array(
            'id' => $service_id,
            'title_cn' => $i18n_content['title_cn'],
            'title_en' => $i18n_content['title_en'],
            'url' => $service_url,
            'excerpt_cn' => $i18n_content['excerpt_cn'],
            'excerpt_en' => $i18n_content['excerpt_en'],
            'has_thumb' => has_post_thumbnail($service_id),
            'is_campus_only' => function_exists('itstudio_is_service_campus_only') ? itstudio_is_service_campus_only($service_id) : false,
            'term_name_cn' => '',
            'term_name_en' => '',
        );

        if (!empty($service_terms) && !is_wp_error($service_terms)) {
            $primary_term = $service_terms[0];
            $term_id = (int) $primary_term->term_id;
            $term_labels = function_exists('itstudio_get_service_category_i18n_labels')
                ? itstudio_get_service_category_i18n_labels($primary_term)
                : array('cn' => $primary_term->name, 'en' => $primary_term->name);
            $item['term_name_cn'] = $term_labels['cn'];
            $item['term_name_en'] = $term_labels['en'];

            if (!isset($grouped_services[$term_id])) {
                $grouped_services[$term_id] = array(
                    'term' => $primary_term,
                    'items' => array(),
                );
            }
            $grouped_services[$term_id]['items'][] = $item;
        } else {
            $item['term_name_cn'] = '未分类';
            $item['term_name_en'] = 'Uncategorized';
            $uncategorized_items[] = $item;
        }
    }
    wp_reset_postdata();
}

$sections = array();
if (!empty($grouped_services)) {
    $ordered_terms = get_terms(array(
        'taxonomy' => 'service_category',
        'hide_empty' => true,
    ));

    if (!is_wp_error($ordered_terms) && !empty($ordered_terms)) {
        foreach ($ordered_terms as $term) {
            $term_id = (int) $term->term_id;
            if (!isset($grouped_services[$term_id])) {
                continue;
            }
            $term_labels = function_exists('itstudio_get_service_category_i18n_labels')
                ? itstudio_get_service_category_i18n_labels($term)
                : array('cn' => $term->name, 'en' => $term->name);
            $sections[] = array(
                'slug' => sanitize_title($term->slug),
                'name_cn' => $term_labels['cn'],
                'name_en' => $term_labels['en'],
                'items' => $grouped_services[$term_id]['items'],
            );
        }
    } else {
        foreach ($grouped_services as $group) {
            $term_labels = function_exists('itstudio_get_service_category_i18n_labels')
                ? itstudio_get_service_category_i18n_labels($group['term'])
                : array('cn' => $group['term']->name, 'en' => $group['term']->name);
            $sections[] = array(
                'slug' => sanitize_title($group['term']->slug),
                'name_cn' => $term_labels['cn'],
                'name_en' => $term_labels['en'],
                'items' => $group['items'],
            );
        }
    }
}

if (!empty($uncategorized_items)) {
    $sections[] = array(
        'slug' => 'uncategorized',
        'name_cn' => '未分类',
        'name_en' => 'Uncategorized',
        'items' => $uncategorized_items,
    );
}
?>

<main class="site-main services-directory-page">
    <div class="container">
        <header class="services-directory-head">
            <h1 class="services-directory-title" data-cn="便民服务" data-en="Service">便民服务</h1>
        </header>

        <?php if (!empty($sections)) : ?>
            <div class="services-directory-layout">
                <aside class="services-directory-sidebar">
                    <div class="services-directory-nav-box">
                        <h2 class="services-directory-nav-title" data-cn="分类" data-en="Categories">分类</h2>
                        <ul class="services-directory-nav-list">
                            <?php foreach ($sections as $section) : ?>
                                <?php $count = count($section['items']); ?>
                                <li>
                                    <a href="#service-section-<?php echo esc_attr($section['slug']); ?>">
                                        <span class="services-directory-nav-name" data-cn="<?php echo esc_attr($section['name_cn']); ?>" data-en="<?php echo esc_attr($section['name_en']); ?>"><?php echo esc_html($section['name_cn']); ?></span>
                                        <span class="services-directory-nav-count"><?php echo esc_html($count); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </aside>

                <div class="services-directory-content">
                    <?php foreach ($sections as $section) : ?>
                        <section class="services-directory-group" id="service-section-<?php echo esc_attr($section['slug']); ?>">
                            <header class="services-directory-group-head">
                                <h2 data-cn="<?php echo esc_attr($section['name_cn']); ?>" data-en="<?php echo esc_attr($section['name_en']); ?>"><?php echo esc_html($section['name_cn']); ?></h2>
                            </header>

                            <div class="services-directory-grid">
                                <?php foreach ($section['items'] as $item) : ?>
                                    <?php $card_classes = 'services-directory-card' . (!empty($item['is_campus_only']) ? ' is-campus-only' : ''); ?>
                                    <a class="<?php echo esc_attr($card_classes); ?>" href="<?php echo esc_url($item['url']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php if (!empty($item['is_campus_only'])) : ?>
                                            <span class="services-directory-access services-directory-access-campus" data-cn="仅校内访问" data-en="Campus-only">仅校内访问</span>
                                        <?php endif; ?>
                                        <div class="services-directory-card-head">
                                            <div class="services-directory-icon-wrap">
                                                <?php if ($item['has_thumb']) : ?>
                                                    <?php echo get_the_post_thumbnail($item['id'], 'medium', array('class' => 'services-directory-icon')); ?>
                                                <?php else : ?>
                                                    <img class="services-directory-icon services-directory-icon-fallback" src="<?php echo esc_url($default_icon_url); ?>" alt="<?php echo esc_attr($item['title_cn']); ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div class="services-directory-meta">
                                                <h3 class="services-directory-name" data-cn="<?php echo esc_attr($item['title_cn']); ?>" data-en="<?php echo esc_attr($item['title_en']); ?>"><?php echo esc_html($item['title_cn']); ?></h3>
                                                <span class="services-directory-category" data-cn="<?php echo esc_attr($item['term_name_cn']); ?>" data-en="<?php echo esc_attr($item['term_name_en']); ?>"><?php echo esc_html($item['term_name_cn']); ?></span>
                                            </div>
                                        </div>
                                        <p class="services-directory-desc" data-cn="<?php echo esc_attr($item['excerpt_cn']); ?>" data-en="<?php echo esc_attr($item['excerpt_en']); ?>"><?php echo esc_html($item['excerpt_cn']); ?></p>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else : ?>
            <section class="services-directory-empty">
                <h2 data-cn="暂无便民服务" data-en="No services yet">暂无便民服务</h2>
                <p data-cn="请先在后台“便民服务”中添加服务条目。" data-en="Please add services in wp-admin first.">请先在后台“便民服务”中添加服务条目。</p>
                <?php if (current_user_can('edit_posts')) : ?>
                    <a class="services-directory-empty-btn" href="<?php echo esc_url(admin_url('post-new.php?post_type=service')); ?>" target="_blank" rel="noopener noreferrer" data-cn="前往新增服务" data-en="Create a service">前往新增服务</a>
                <?php endif; ?>
            </section>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
