<?php

function itstudio_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'gallery',
        'caption',
    ));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'itstudio'),
    ));

    load_theme_textdomain('itstudio', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'itstudio_theme_setup');

add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);
add_filter('comments_array', '__return_empty_array', 10, 2);

function itstudio_disable_comments_post_types() {
    $post_types = array('post', 'page', 'announcement', 'news', 'service');
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
        }
        if (post_type_supports($post_type, 'trackbacks')) {
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('init', 'itstudio_disable_comments_post_types', 100);

function itstudio_hide_comments_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'itstudio_hide_comments_menu', 999);

function itstudio_hide_admin_bar_comments() {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
}
add_action('init', 'itstudio_hide_admin_bar_comments');

function itstudio_redirect_comments_admin_pages() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php' || $pagenow === 'comment.php') {
        wp_safe_redirect(admin_url());
        exit;
    }
}
add_action('admin_init', 'itstudio_redirect_comments_admin_pages');

function itstudio_remove_comments_dashboard_widget() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'itstudio_remove_comments_dashboard_widget');

function itstudio_apply_site_identity() {
    $site_name = '爱特工作室';
    $site_tagline = '爱特工作室官方网站';

    if (get_option('blogname') !== $site_name) {
        update_option('blogname', $site_name);
    }

    if (get_option('blogdescription') !== $site_tagline) {
        update_option('blogdescription', $site_tagline);
    }
}
add_action('init', 'itstudio_apply_site_identity', 20);

function itstudio_output_favicon() {
    $favicon_url = get_template_directory_uri() . '/resources/it_logo_2024.svg';
    echo '<link rel="icon" href="' . esc_url($favicon_url) . '" type="image/svg+xml">' . "\n";
    echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '" type="image/svg+xml">' . "\n";
}

function itstudio_output_theme_bootstrap_script() {
    echo '<meta name="color-scheme" content="dark light">' . "\n";
    echo '<script>(function(){var d=document.documentElement;var t="light";try{var s=localStorage.getItem("theme");if(s==="dark"||s==="light"){t=s;}else if(window.matchMedia){t=window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";}}catch(e){if(window.matchMedia){t=window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light";}}d.setAttribute("data-theme",t);d.style.colorScheme=t;})();</script>' . "\n";
}

function itstudio_output_lang_bootstrap_script() {
    echo '<style id="itstudio-lang-boot-style">html.itstudio-lang-pending body{visibility:hidden;}</style>' . "\n";
    echo '<script>(function(){var d=document.documentElement;var lang="zh";d.classList.add("itstudio-lang-pending");try{var s=localStorage.getItem("language");if(s==="zh"||s==="en"){lang=s;}}catch(e){}d.setAttribute("lang",lang);window.__ITSTUDIO_LANG__=lang;var apply=function(root){var scope=root||document;var txt=scope.querySelectorAll("[data-cn][data-en]");for(var i=0;i<txt.length;i++){var el=txt[i];var next=lang==="en"?el.getAttribute("data-en"):el.getAttribute("data-cn");if(!next){continue;}if(el.tagName==="INPUT"||el.tagName==="TEXTAREA"){var tp=(el.getAttribute("type")||"").toLowerCase();if(tp==="submit"||tp==="button"||tp==="reset"){el.value=next;continue;}}el.textContent=next;}var ph=scope.querySelectorAll("[data-cn-placeholder][data-en-placeholder]");for(var j=0;j<ph.length;j++){var ep=ph[j];var pk=lang==="en"?"data-en-placeholder":"data-cn-placeholder";var pv=ep.getAttribute(pk);if(pv){ep.setAttribute("placeholder",pv);}}var ar=scope.querySelectorAll("[data-cn-aria-label][data-en-aria-label]");for(var k=0;k<ar.length;k++){var ea=ar[k];var ak=lang==="en"?"data-en-aria-label":"data-cn-aria-label";var av=ea.getAttribute(ak);if(av){ea.setAttribute("aria-label",av);}}};var finish=function(){if(document.body){document.body.setAttribute("lang",lang);}d.classList.remove("itstudio-lang-pending");var st=document.getElementById("itstudio-lang-boot-style");if(st&&st.parentNode){st.parentNode.removeChild(st);}};var run=function(){apply(document);finish();};if(document.readyState==="loading"){document.addEventListener("DOMContentLoaded",run,{once:true});}else{run();}})();</script>' . "\n";
}
function itstudio_disable_default_site_icon() {
    remove_action('wp_head', 'wp_site_icon', 99);
    remove_action('admin_head', 'wp_site_icon', 99);
    remove_action('login_head', 'wp_site_icon', 99);
}
add_action('init', 'itstudio_disable_default_site_icon');
add_action('wp_head', 'itstudio_output_theme_bootstrap_script', 0);
add_action('wp_head', 'itstudio_output_lang_bootstrap_script', 1);
add_action('wp_head', 'itstudio_output_favicon', 1);
add_action('admin_head', 'itstudio_output_favicon', 1);
add_action('login_head', 'itstudio_output_favicon', 1);

function itstudio_enqueue_scripts() {
    // 基础样式 (Style.css)
    wp_enqueue_style('itstudio-style', get_stylesheet_uri(), array(), '2.1.2');

    wp_enqueue_style('itstudio-header', get_template_directory_uri() . '/assets/css/header.css', array('itstudio-style'), '2.1.2');
    wp_enqueue_style('itstudio-footer', get_template_directory_uri() . '/assets/css/footer.css', array('itstudio-style'), '2.1.2');
    wp_enqueue_style('itstudio-content', get_template_directory_uri() . '/assets/css/content.css', array('itstudio-style'), '2.1.2');

    // 仅在首页加载首页样式
    if (is_front_page() || is_home()) {
        wp_enqueue_style('itstudio-front-page', get_template_directory_uri() . '/assets/css/front-page.css', array('itstudio-style'), '2.1.2');
        wp_enqueue_script('itstudio-landing-hero-canvas', get_template_directory_uri() . '/assets/js/landing-hero-canvas.js', array(), '1.0.0', true);
    }

    // 仅在工作室介绍页加载（包含 /about fallback）
    $is_about = is_page('about') || is_page_template('page-about.php');
    if (!$is_about && is_404()) {
        global $wp;
        $request = isset($wp->request) ? trim($wp->request, '/') : '';
        $is_about = ($request === 'about');
    }

    if ($is_about) {
        wp_enqueue_style('itstudio-about-hero', get_template_directory_uri() . '/assets/css/about-hero.css', array('itstudio-content'), '2.1.2');
        wp_enqueue_script('itstudio-animejs', get_template_directory_uri() . '/assets/js/vendor/anime.min.js', array(), '3.2.2', true);
        wp_enqueue_script('itstudio-about-hero-waves', get_template_directory_uri() . '/assets/js/hero-waves.js', array('itstudio-animejs'), '1.0.0', true);
        wp_enqueue_script('itstudio-about-hero', get_template_directory_uri() . '/assets/js/home-hero.js', array('itstudio-animejs'), '1.0.0', true);
    }

    // 仅在便民服务页加载（包含 /services fallback）
    $is_services = is_page('services') || is_page_template('page-services.php');
    if (!$is_services && is_404()) {
        global $wp;
        $request = isset($wp->request) ? trim($wp->request, '/') : '';
        $is_services = ($request === 'services');
    }

    if ($is_services) {
        wp_enqueue_style('itstudio-services-page', get_template_directory_uri() . '/assets/css/services-page.css', array('itstudio-content'), '1.0.0');
    }

    // Scripts
    wp_enqueue_script('itstudio-theme-toggle', get_template_directory_uri() . '/assets/js/theme-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('itstudio-lang-toggle', get_template_directory_uri() . '/assets/js/lang-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('itstudio-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);

    if (is_singular(array('post', 'announcement', 'news'))) {
        wp_enqueue_script('itstudio-single-title-fit', get_template_directory_uri() . '/assets/js/single-title-fit.js', array(), '1.0.0', true);
    }
}
add_action('wp_enqueue_scripts', 'itstudio_enqueue_scripts');

function itstudio_expand_news_notice_date_precision($the_date, $format, $post) {
    $post_obj = $post instanceof WP_Post ? $post : get_post($post);
    if (!($post_obj instanceof WP_Post)) {
        return $the_date;
    }

    if (!in_array((string) $post_obj->post_type, array('announcement', 'news'), true)) {
        return $the_date;
    }

    $format = (string) $format;
    if ($format === '') {
        $format = (string) get_option('date_format');
    }

    $lower = strtolower($format);
    if (in_array($lower, array('c', 'r', 'u'), true)) {
        return $the_date;
    }

    // Already has time precision.
    if (preg_match('/[HhGgis]/', $format)) {
        return $the_date;
    }

    $timestamp = function_exists('get_post_timestamp')
        ? get_post_timestamp($post_obj, 'date')
        : false;
    if (!$timestamp) {
        return $the_date;
    }

    $format_with_time = rtrim($format) . ' H:i';
    return wp_date($format_with_time, (int) $timestamp, wp_timezone());
}
add_filter('get_the_date', 'itstudio_expand_news_notice_date_precision', 10, 3);

function itstudio_register_sidebars() {
    register_sidebar(array(
        'name' => __('Footer - Column 1', 'itstudio'),
        'id' => 'footer-1',
        'description' => __('Footer widget area 1', 'itstudio'),
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer - Column 2', 'itstudio'),
        'id' => 'footer-2',
        'description' => __('Footer widget area 2', 'itstudio'),
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'itstudio_register_sidebars');

function itstudio_intro_body_class($classes) {
    $is_about = is_page('about') || is_page_template('page-about.php');
    if (!$is_about && is_404()) {
        global $wp;
        $request = isset($wp->request) ? trim($wp->request, '/') : '';
        $is_about = ($request === 'about');
    }

    if ($is_about) {
        $classes[] = 'intro-about';
    }

    return $classes;
}
add_filter('body_class', 'itstudio_intro_body_class');

function itstudio_custom_post_types() {
    register_post_type('announcement', array(
        'labels' => array(
            'name' => __('Announcements', 'itstudio'),
            'singular_name' => __('Announcement', 'itstudio'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies' => array('post_tag'),
        'menu_icon' => 'dashicons-megaphone',
        'show_in_rest' => true,
    ));

    register_post_type('news', array(
        'labels' => array(
            'name' => __('News', 'itstudio'),
            'singular_name' => __('News', 'itstudio'),
        ),
        'public' => true,
        'has_archive' => 'news',
        'rewrite' => array(
            'slug' => 'news',
            'with_front' => false,
        ),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'taxonomies' => array('post_tag'),
        'menu_icon' => 'dashicons-media-document',
        'show_in_rest' => true,
    ));

    register_taxonomy('service_category', array('service'), array(
        'labels' => array(
            'name' => __('服务分类', 'itstudio'),
            'singular_name' => __('服务分类', 'itstudio'),
        ),
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => false,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'service-category',
            'with_front' => false,
        ),
    ));

    register_post_type('service', array(
        'labels' => array(
            'name' => __('便民服务', 'itstudio'),
            'singular_name' => __('便民服务', 'itstudio'),
            'menu_name' => __('便民服务', 'itstudio'),
            'add_new_item' => __('新增便民服务', 'itstudio'),
            'edit_item' => __('编辑便民服务', 'itstudio'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => false,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'has_archive' => false,
        'rewrite' => array(
            'slug' => 'service',
            'with_front' => false,
        ),
        'supports' => array('title', 'excerpt', 'thumbnail', 'page-attributes'),
        'taxonomies' => array('service_category'),
        'menu_icon' => 'dashicons-admin-tools',
        'show_in_rest' => true,
    ));

    register_taxonomy_for_object_type('post_tag', 'announcement');
    register_taxonomy_for_object_type('post_tag', 'news');
}
add_action('init', 'itstudio_custom_post_types');

function itstudio_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    acf_add_local_field_group(array(
        'key' => 'group_itstudio_content_priority',
        'title' => '内容权重',
        'fields' => array(
            array(
                'key' => 'field_itstudio_weight',
                'label' => '权重',
                'name' => 'itstudio_weight',
                'type' => 'number',
                'instructions' => '数值越大，文章在侧栏排序越靠前。',
                'required' => 0,
                'default_value' => 0,
                'min' => 0,
                'step' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'announcement',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news',
                ),
            ),
        ),
        'position' => 'side',
        'style' => 'default',
        'active' => true,
        'show_in_rest' => 1,
    ));

    acf_add_local_field_group(array(
        'key' => 'group_itstudio_recruitment_article',
        'title' => '招新文章标记',
        'fields' => array(
            array(
                'key' => 'field_itstudio_is_recruitment_article',
                'label' => '是否为招新文章',
                'name' => 'itstudio_is_recruitment_article',
                'type' => 'true_false',
                'instructions' => '勾选后，该文章会在“加入我们”页面顶部新闻条中显示（按发布时间排序）。',
                'required' => 0,
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '是',
                'ui_off_text' => '否',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'announcement',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'news',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'position' => 'side',
        'style' => 'default',
        'active' => true,
        'show_in_rest' => 1,
    ));
}
add_action('acf/init', 'itstudio_register_acf_fields');

function itstudio_get_recruitment_article_meta_key() {
    return 'itstudio_is_recruitment_article';
}

function itstudio_normalize_recruitment_flag($value) {
    if (is_bool($value)) {
        return $value;
    }

    if (is_numeric($value)) {
        return ((int) $value) > 0;
    }

    $value = strtolower(trim((string) $value));
    return in_array($value, array('1', 'true', 'yes', 'on', 'y'), true);
}

function itstudio_is_recruitment_article($post_id) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return false;
    }

    $meta_key = itstudio_get_recruitment_article_meta_key();
    $meta_keys = array(
        $meta_key,
        '_itstudio_is_recruitment_article',
        'itstudio_join_article',
        'join_recruitment_article',
        'is_recruitment_article',
    );

    foreach ($meta_keys as $key) {
        $raw = get_post_meta($post_id, $key, true);
        if ($raw !== '' && $raw !== null && itstudio_normalize_recruitment_flag($raw)) {
            return true;
        }
    }

    if (function_exists('get_field')) {
        $acf_keys = array(
            'itstudio_is_recruitment_article',
            'is_recruitment_article',
            'itstudio_join_article',
        );
        foreach ($acf_keys as $acf_key) {
            $raw = get_field($acf_key, $post_id, false);
            if ($raw !== '' && $raw !== null && itstudio_normalize_recruitment_flag($raw)) {
                return true;
            }
        }
    }

    return false;
}

function itstudio_add_recruitment_meta_boxes() {
    $screens = array('announcement', 'news', 'post');
    foreach ($screens as $screen) {
        add_meta_box(
            'itstudio_recruitment_flag',
            __('招新文章', 'itstudio'),
            'itstudio_render_recruitment_meta_box',
            $screen,
            'side',
            'high'
        );
    }
}
add_action('add_meta_boxes', 'itstudio_add_recruitment_meta_boxes');

function itstudio_render_recruitment_meta_box($post) {
    $meta_key = itstudio_get_recruitment_article_meta_key();
    $checked = itstudio_is_recruitment_article((int) $post->ID);
    wp_nonce_field('itstudio_save_recruitment_meta', 'itstudio_recruitment_meta_nonce');
    ?>
    <p>
        <label for="itstudio_is_recruitment_article">
            <input
                type="checkbox"
                id="itstudio_is_recruitment_article"
                name="itstudio_is_recruitment_article"
                value="1"
                <?php checked($checked); ?>
            >
            <?php esc_html_e('在加入我们页面显示为招新新闻', 'itstudio'); ?>
        </label>
    </p>
    <p style="color:#666;line-height:1.5;margin:0;">
        <?php esc_html_e('仅显示当年且已发布的招新文章，最多展示 5 篇。', 'itstudio'); ?>
    </p>
    <input type="hidden" name="itstudio_recruitment_meta_key" value="<?php echo esc_attr($meta_key); ?>">
    <?php
}

function itstudio_save_recruitment_meta_box($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) {
        return;
    }
    if (!isset($_POST['itstudio_recruitment_meta_nonce'])) {
        return;
    }

    $nonce = sanitize_text_field(wp_unslash($_POST['itstudio_recruitment_meta_nonce']));
    if (!wp_verify_nonce($nonce, 'itstudio_save_recruitment_meta')) {
        return;
    }

    $post_type = get_post_type($post_id);
    if (!in_array($post_type, array('announcement', 'news', 'post'), true)) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $meta_key = itstudio_get_recruitment_article_meta_key();
    $checked = isset($_POST['itstudio_is_recruitment_article'])
        ? sanitize_text_field(wp_unslash($_POST['itstudio_is_recruitment_article']))
        : '';
    if (itstudio_normalize_recruitment_flag($checked)) {
        update_post_meta($post_id, $meta_key, '1');
    } else {
        delete_post_meta($post_id, $meta_key);
    }
}
add_action('save_post', 'itstudio_save_recruitment_meta_box');

function itstudio_join_get_recruitment_feed_items($join_runtime = array(), $limit = 5) {
    $limit = max(1, min(10, (int) $limit));
    $join_runtime = is_array($join_runtime) ? $join_runtime : array();

    $now = new DateTimeImmutable('now', wp_timezone());
    $now_year = (int) $now->format('Y');
    $display_year = isset($join_runtime['recruitment_year']) && is_numeric($join_runtime['recruitment_year'])
        ? (int) $join_runtime['recruitment_year']
        : $now_year;

    $season_start_ts = null;
    $season_end_ts = null;
    $stages = isset($join_runtime['stages']) && is_array($join_runtime['stages']) ? $join_runtime['stages'] : array();
    foreach ($stages as $stage) {
        if (!is_array($stage)) {
            continue;
        }
        $start_ts = isset($stage['start_ts']) && is_numeric($stage['start_ts']) ? (int) $stage['start_ts'] : null;
        $end_ts = isset($stage['end_ts']) && is_numeric($stage['end_ts']) ? (int) $stage['end_ts'] : null;
        if ($start_ts !== null && ($season_start_ts === null || $start_ts < $season_start_ts)) {
            $season_start_ts = $start_ts;
        }
        if ($end_ts !== null && ($season_end_ts === null || $end_ts > $season_end_ts)) {
            $season_end_ts = $end_ts;
        }
    }

    $now_ts = (int) (isset($join_runtime['now_ts']) && is_numeric($join_runtime['now_ts']) ? $join_runtime['now_ts'] : ((int) $now->format('U') * 1000));
    if ($season_start_ts !== null && $now_ts < $season_start_ts && $display_year > $now_year) {
        // 下年招新未开始：继续展示本年招新资讯
        $display_year = $now_year;
    }

    $query = new WP_Query(array(
        'post_type' => array('announcement', 'news', 'post'),
        'post_status' => 'publish',
        'posts_per_page' => 80,
        'orderby' => 'date',
        'order' => 'DESC',
        'no_found_rows' => true,
        'ignore_sticky_posts' => true,
        'date_query' => array(
            array(
                'year' => $display_year,
            ),
        ),
    ));

    $items = array();
    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            if (count($items) >= $limit) {
                break;
            }

            $post_id = (int) $post->ID;
            if (!itstudio_is_recruitment_article($post_id)) {
                continue;
            }

            $title = trim((string) get_the_title($post_id));
            $url = (string) get_permalink($post_id);
            if ($title === '' || $url === '') {
                continue;
            }

            $excerpt = function_exists('itstudio_get_post_excerpt_chars')
                ? itstudio_get_post_excerpt_chars($post_id, 72)
                : wp_html_excerpt(trim(wp_strip_all_tags((string) get_post_field('post_excerpt', $post_id))), 72, '...');

            if ($excerpt === '') {
                $excerpt = '...';
            }

            $author_id = (int) get_post_field('post_author', $post_id);
            $author = trim((string) get_the_author_meta('display_name', $author_id));
            if ($author === '') {
                $author = 'Unknown';
            }

            $items[] = array(
                'id' => $post_id,
                'title' => $title,
                'excerpt' => $excerpt,
                'url' => $url,
                'date' => get_the_date('Y-m-d H:i', $post_id),
                'date_iso' => get_the_date('c', $post_id),
                'author' => $author,
                'type' => get_post_type($post_id),
            );
        }
    }
    wp_reset_postdata();

    return array(
        'display_year' => $display_year,
        'items' => $items,
    );
}

function itstudio_get_service_url_meta_key() {
    return '_itstudio_service_url';
}

function itstudio_get_service_title_cn_meta_key() {
    return '_itstudio_service_title_cn';
}

function itstudio_get_service_title_en_meta_key() {
    return '_itstudio_service_title_en';
}

function itstudio_get_service_excerpt_cn_meta_key() {
    return '_itstudio_service_excerpt_cn';
}

function itstudio_get_service_excerpt_en_meta_key() {
    return '_itstudio_service_excerpt_en';
}

function itstudio_get_service_campus_only_meta_key() {
    return '_itstudio_service_campus_only';
}

function itstudio_get_service_category_name_cn_meta_key() {
    return 'itstudio_service_category_name_cn';
}

function itstudio_get_service_category_name_en_meta_key() {
    return 'itstudio_service_category_name_en';
}

function itstudio_get_service_category_i18n_labels($term) {
    if (!$term || is_wp_error($term)) {
        return array(
            'cn' => '未分类',
            'en' => 'Uncategorized',
        );
    }

    $term_id = (int) $term->term_id;
    $name_cn = trim((string) get_term_meta($term_id, itstudio_get_service_category_name_cn_meta_key(), true));
    $name_en = trim((string) get_term_meta($term_id, itstudio_get_service_category_name_en_meta_key(), true));

    if ($name_cn === '') {
        $name_cn = (string) $term->name;
    }
    if ($name_en === '') {
        $name_en = $name_cn;
    }

    return array(
        'cn' => $name_cn,
        'en' => $name_en,
    );
}

function itstudio_get_service_i18n_content($service_id, $excerpt_limit = 90) {
    $service_id = (int) $service_id;
    if ($service_id <= 0) {
        return array(
            'title_cn' => '',
            'title_en' => '',
            'excerpt_cn' => '',
            'excerpt_en' => '',
        );
    }

    $title_cn = trim((string) get_post_meta($service_id, itstudio_get_service_title_cn_meta_key(), true));
    $title_en = trim((string) get_post_meta($service_id, itstudio_get_service_title_en_meta_key(), true));
    $excerpt_cn = trim((string) get_post_meta($service_id, itstudio_get_service_excerpt_cn_meta_key(), true));
    $excerpt_en = trim((string) get_post_meta($service_id, itstudio_get_service_excerpt_en_meta_key(), true));

    $fallback_title = trim((string) get_the_title($service_id));
    if ($fallback_title === '') {
        $fallback_title = '未命名服务';
    }

    $fallback_excerpt = function_exists('itstudio_get_post_excerpt_chars')
        ? itstudio_get_post_excerpt_chars($service_id, $excerpt_limit)
        : wp_html_excerpt(wp_strip_all_tags((string) get_post_field('post_excerpt', $service_id)), $excerpt_limit, '...');
    $fallback_excerpt = trim((string) $fallback_excerpt);
    if ($fallback_excerpt === '') {
        $fallback_excerpt = '暂无简介';
    }

    if ($title_cn === '') {
        $title_cn = $fallback_title;
    }
    if ($title_en === '') {
        $title_en = $title_cn;
    }
    if ($excerpt_cn === '') {
        $excerpt_cn = $fallback_excerpt;
    }
    if ($excerpt_en === '') {
        $excerpt_en = $excerpt_cn;
    }

    return array(
        'title_cn' => $title_cn,
        'title_en' => $title_en,
        'excerpt_cn' => $excerpt_cn,
        'excerpt_en' => $excerpt_en,
    );
}

function itstudio_add_service_meta_boxes() {
    add_meta_box(
        'itstudio_service_link',
        __('服务双语与链接', 'itstudio'),
        'itstudio_render_service_link_meta_box',
        'service',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'itstudio_add_service_meta_boxes');

function itstudio_render_service_link_meta_box($post) {
    $service_url = (string) get_post_meta($post->ID, itstudio_get_service_url_meta_key(), true);
    $title_cn = (string) get_post_meta($post->ID, itstudio_get_service_title_cn_meta_key(), true);
    $title_en = (string) get_post_meta($post->ID, itstudio_get_service_title_en_meta_key(), true);
    $excerpt_cn = (string) get_post_meta($post->ID, itstudio_get_service_excerpt_cn_meta_key(), true);
    $excerpt_en = (string) get_post_meta($post->ID, itstudio_get_service_excerpt_en_meta_key(), true);
    $is_campus_only = itstudio_is_service_campus_only($post->ID);
    wp_nonce_field('itstudio_save_service_meta', 'itstudio_service_meta_nonce');
    ?>
    <p>
        <label for="itstudio_service_title_cn"><strong><?php esc_html_e('中文名称', 'itstudio'); ?></strong></label>
    </p>
    <p>
        <input
            type="text"
            id="itstudio_service_title_cn"
            name="itstudio_service_title_cn"
            class="widefat"
            placeholder="<?php echo esc_attr((string) get_the_title($post)); ?>"
            value="<?php echo esc_attr($title_cn); ?>"
        >
    </p>
    <p>
        <label for="itstudio_service_title_en"><strong><?php esc_html_e('英文名称', 'itstudio'); ?></strong></label>
    </p>
    <p>
        <input
            type="text"
            id="itstudio_service_title_en"
            name="itstudio_service_title_en"
            class="widefat"
            placeholder="Service Name"
            value="<?php echo esc_attr($title_en); ?>"
        >
    </p>
    <p>
        <label for="itstudio_service_excerpt_cn"><strong><?php esc_html_e('中文简介', 'itstudio'); ?></strong></label>
    </p>
    <p>
        <textarea
            id="itstudio_service_excerpt_cn"
            name="itstudio_service_excerpt_cn"
            class="widefat"
            rows="3"
            placeholder="<?php echo esc_attr((string) get_the_excerpt($post)); ?>"
        ><?php echo esc_textarea($excerpt_cn); ?></textarea>
    </p>
    <p>
        <label for="itstudio_service_excerpt_en"><strong><?php esc_html_e('英文简介', 'itstudio'); ?></strong></label>
    </p>
    <p>
        <textarea
            id="itstudio_service_excerpt_en"
            name="itstudio_service_excerpt_en"
            class="widefat"
            rows="3"
            placeholder="Short English description"
        ><?php echo esc_textarea($excerpt_en); ?></textarea>
    </p>
    <p>
        <label for="itstudio_service_url"><strong><?php esc_html_e('目标链接', 'itstudio'); ?></strong></label>
    </p>
    <p>
        <input
            type="url"
            id="itstudio_service_url"
            name="itstudio_service_url"
            class="widefat"
            placeholder="https://example.com"
            value="<?php echo esc_attr($service_url); ?>"
        >
    </p>
    <p class="description">
        <?php esc_html_e('支持分别填写中英文名称与简介；未填写时会回退到标题/摘要。', 'itstudio'); ?>
    </p>
    <p>
        <label for="itstudio_service_campus_only">
            <input
                type="checkbox"
                id="itstudio_service_campus_only"
                name="itstudio_service_campus_only"
                value="1"
                <?php checked($is_campus_only); ?>
            >
            <?php esc_html_e('是否为校内服务（勾选后前台显示“仅校内访问”）', 'itstudio'); ?>
        </label>
    </p>
    <?php
}

function itstudio_save_service_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!isset($_POST['itstudio_service_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['itstudio_service_meta_nonce'])), 'itstudio_save_service_meta')) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (get_post_type($post_id) !== 'service') {
        return;
    }

    $title_cn = isset($_POST['itstudio_service_title_cn']) ? sanitize_text_field(wp_unslash($_POST['itstudio_service_title_cn'])) : '';
    $title_en = isset($_POST['itstudio_service_title_en']) ? sanitize_text_field(wp_unslash($_POST['itstudio_service_title_en'])) : '';
    $excerpt_cn = isset($_POST['itstudio_service_excerpt_cn']) ? sanitize_textarea_field(wp_unslash($_POST['itstudio_service_excerpt_cn'])) : '';
    $excerpt_en = isset($_POST['itstudio_service_excerpt_en']) ? sanitize_textarea_field(wp_unslash($_POST['itstudio_service_excerpt_en'])) : '';

    $meta_updates = array(
        itstudio_get_service_title_cn_meta_key() => trim($title_cn),
        itstudio_get_service_title_en_meta_key() => trim($title_en),
        itstudio_get_service_excerpt_cn_meta_key() => trim($excerpt_cn),
        itstudio_get_service_excerpt_en_meta_key() => trim($excerpt_en),
    );

    foreach ($meta_updates as $meta_key => $value) {
        if ($value === '') {
            delete_post_meta($post_id, $meta_key);
        } else {
            update_post_meta($post_id, $meta_key, $value);
        }
    }

    $campus_only_meta_key = itstudio_get_service_campus_only_meta_key();
    $is_campus_only = isset($_POST['itstudio_service_campus_only']) && (string) wp_unslash($_POST['itstudio_service_campus_only']) === '1';
    if ($is_campus_only) {
        update_post_meta($post_id, $campus_only_meta_key, '1');
    } else {
        delete_post_meta($post_id, $campus_only_meta_key);
    }

    $url_meta_key = itstudio_get_service_url_meta_key();
    $raw_url = isset($_POST['itstudio_service_url']) ? trim((string) wp_unslash($_POST['itstudio_service_url'])) : '';

    if ($raw_url === '') {
        delete_post_meta($post_id, $url_meta_key);
        return;
    }

    if (strpos($raw_url, '/') === 0) {
        $raw_url = home_url($raw_url);
    }

    $service_url = esc_url_raw($raw_url);
    if ($service_url === '') {
        delete_post_meta($post_id, $url_meta_key);
        return;
    }

    update_post_meta($post_id, $url_meta_key, $service_url);
}
add_action('save_post_service', 'itstudio_save_service_meta');

function itstudio_service_category_add_fields($taxonomy) {
    wp_nonce_field('itstudio_save_service_category_meta', 'itstudio_service_category_meta_nonce');
    ?>
    <div class="form-field term-itstudio-service-name-cn-wrap">
        <label for="itstudio_service_cat_name_cn"><?php esc_html_e('中文名称', 'itstudio'); ?></label>
        <input type="text" name="itstudio_service_cat_name_cn" id="itstudio_service_cat_name_cn" value="">
    </div>
    <div class="form-field term-itstudio-service-name-en-wrap">
        <label for="itstudio_service_cat_name_en"><?php esc_html_e('英文名称', 'itstudio'); ?></label>
        <input type="text" name="itstudio_service_cat_name_en" id="itstudio_service_cat_name_en" value="" placeholder="Category Name">
    </div>
    <?php
}
add_action('service_category_add_form_fields', 'itstudio_service_category_add_fields');

function itstudio_service_category_edit_fields($term) {
    $term_id = (int) $term->term_id;
    $name_cn = (string) get_term_meta($term_id, itstudio_get_service_category_name_cn_meta_key(), true);
    $name_en = (string) get_term_meta($term_id, itstudio_get_service_category_name_en_meta_key(), true);
    wp_nonce_field('itstudio_save_service_category_meta', 'itstudio_service_category_meta_nonce');
    ?>
    <tr class="form-field term-itstudio-service-name-cn-wrap">
        <th scope="row"><label for="itstudio_service_cat_name_cn"><?php esc_html_e('中文名称', 'itstudio'); ?></label></th>
        <td><input type="text" name="itstudio_service_cat_name_cn" id="itstudio_service_cat_name_cn" value="<?php echo esc_attr($name_cn); ?>"></td>
    </tr>
    <tr class="form-field term-itstudio-service-name-en-wrap">
        <th scope="row"><label for="itstudio_service_cat_name_en"><?php esc_html_e('英文名称', 'itstudio'); ?></label></th>
        <td><input type="text" name="itstudio_service_cat_name_en" id="itstudio_service_cat_name_en" value="<?php echo esc_attr($name_en); ?>" placeholder="Category Name"></td>
    </tr>
    <?php
}
add_action('service_category_edit_form_fields', 'itstudio_service_category_edit_fields');

function itstudio_save_service_category_meta($term_id) {
    if (!current_user_can('manage_categories')) {
        return;
    }

    if (!isset($_POST['itstudio_service_category_meta_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['itstudio_service_category_meta_nonce'])), 'itstudio_save_service_category_meta')) {
        return;
    }

    $name_cn = isset($_POST['itstudio_service_cat_name_cn']) ? sanitize_text_field(wp_unslash($_POST['itstudio_service_cat_name_cn'])) : '';
    $name_en = isset($_POST['itstudio_service_cat_name_en']) ? sanitize_text_field(wp_unslash($_POST['itstudio_service_cat_name_en'])) : '';

    $term_meta = array(
        itstudio_get_service_category_name_cn_meta_key() => trim($name_cn),
        itstudio_get_service_category_name_en_meta_key() => trim($name_en),
    );

    foreach ($term_meta as $meta_key => $value) {
        if ($value === '') {
            delete_term_meta($term_id, $meta_key);
        } else {
            update_term_meta($term_id, $meta_key, $value);
        }
    }
}
add_action('created_service_category', 'itstudio_save_service_category_meta');
add_action('edited_service_category', 'itstudio_save_service_category_meta');

function itstudio_get_service_target_url($service_id) {
    $service_id = (int) $service_id;
    if ($service_id <= 0) {
        return '';
    }

    $meta_key = itstudio_get_service_url_meta_key();
    $url = (string) get_post_meta($service_id, $meta_key, true);
    if ($url !== '') {
        return $url;
    }

    return (string) get_permalink($service_id);
}

function itstudio_is_service_campus_only($service_id) {
    $service_id = (int) $service_id;
    if ($service_id <= 0) {
        return false;
    }

    return (string) get_post_meta($service_id, itstudio_get_service_campus_only_meta_key(), true) === '1';
}

function itstudio_service_admin_columns($columns) {
    $columns['service_category'] = __('分类', 'itstudio');
    $columns['service_url'] = __('跳转链接', 'itstudio');
    return $columns;
}
add_filter('manage_service_posts_columns', 'itstudio_service_admin_columns');

function itstudio_service_admin_custom_column($column, $post_id) {
    if ($column === 'service_category') {
        $terms = get_the_terms($post_id, 'service_category');
        if (empty($terms) || is_wp_error($terms)) {
            echo '<span style="color:#888;">' . esc_html__('未分类', 'itstudio') . '</span>';
            return;
        }

        $labels = array();
        foreach ($terms as $term) {
            $i18n = itstudio_get_service_category_i18n_labels($term);
            $label = $i18n['cn'];
            if ($i18n['en'] !== $i18n['cn']) {
                $label .= ' / ' . $i18n['en'];
            }
            $labels[] = $label;
        }

        echo esc_html(implode(' | ', $labels));
        return;
    }

    if ($column === 'service_url') {
        $url = itstudio_get_service_target_url($post_id);
        if ($url === '') {
            echo '<span style="color:#888;">-</span>';
            return;
        }

        echo '<a href="' . esc_url($url) . '" target="_blank" rel="noopener noreferrer">' . esc_html($url) . '</a>';
    }
}
add_action('manage_service_posts_custom_column', 'itstudio_service_admin_custom_column', 10, 2);

function itstudio_archive_document_title($parts) {
    if (is_post_type_archive('announcement')) {
        $parts['title'] = '公告通知';
    } elseif (is_post_type_archive('news')) {
        $parts['title'] = '社团新闻';
    }

    return $parts;
}
add_filter('document_title_parts', 'itstudio_archive_document_title', 20);

// Fallback: render /about even if the page isn't created in WP admin.
function itstudio_about_fallback() {
    if (!is_404()) {
        return;
    }

    global $wp;
    $request = isset($wp->request) ? trim($wp->request, '/') : '';
    if ($request !== 'about') {
        return;
    }

    $template = locate_template('page-about.php');
    if ($template) {
        global $wp_query;
        if ($wp_query) {
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
            $virtual_post = new WP_Post((object) array(
                'ID' => 0,
                'post_type' => 'page',
                'post_parent' => 0,
                'post_title' => __('工作室介绍', 'itstudio'),
                'post_status' => 'publish',
                'post_name' => 'about',
                'post_content' => '',
            ));
            $wp_query->post = $virtual_post;
            $wp_query->posts = array($virtual_post);
            $wp_query->queried_object = $virtual_post;
            $wp_query->queried_object_id = 0;
            $wp_query->post_count = 1;
            $wp_query->found_posts = 1;
            $wp_query->max_num_pages = 1;
            global $post;
            $post = $virtual_post;
            setup_postdata($post);
        }
        add_filter('document_title_parts', function ($parts) {
            $parts['title'] = __('工作室介绍', 'itstudio');
            return $parts;
        });
        status_header(200);
        nocache_headers();
        include $template;
        exit;
    }
}
add_action('template_redirect', 'itstudio_about_fallback');

// Fallback: render /news via archive.php even if the page isn't created in WP admin.
function itstudio_news_fallback() {
    if (!is_404()) {
        return;
    }

    global $wp;
    $request = isset($wp->request) ? trim($wp->request, '/') : '';
    if ($request !== 'news') {
        return;
    }

    $template = locate_template('archive.php');
    if ($template) {
        global $wp_query;
        if ($wp_query) {
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
            $virtual_post = new WP_Post((object) array(
                'ID' => 0,
                'post_type' => 'page',
                'post_parent' => 0,
                'post_title' => __('社团新闻', 'itstudio'),
                'post_status' => 'publish',
                'post_name' => 'news',
                'post_content' => '',
            ));
            $wp_query->post = $virtual_post;
            $wp_query->posts = array($virtual_post);
            $wp_query->queried_object = $virtual_post;
            $wp_query->queried_object_id = 0;
            $wp_query->post_count = 1;
            $wp_query->found_posts = 1;
            $wp_query->max_num_pages = 1;
            global $post;
            $post = $virtual_post;
            setup_postdata($post);
        }
        add_filter('document_title_parts', function ($parts) {
            $parts['title'] = __('社团新闻', 'itstudio');
            return $parts;
        });
        $GLOBALS['itstudio_archive_mode'] = 'news';
        status_header(200);
        nocache_headers();
        include $template;
        unset($GLOBALS['itstudio_archive_mode']);
        exit;
    }
}
add_action('template_redirect', 'itstudio_news_fallback');

// Fallback: render /services even if the page isn't created in WP admin.
function itstudio_services_fallback() {
    if (!is_404()) {
        return;
    }

    global $wp;
    $request = isset($wp->request) ? trim($wp->request, '/') : '';
    if ($request !== 'services') {
        return;
    }

    $template = locate_template('page-services.php');
    if ($template) {
        global $wp_query;
        if ($wp_query) {
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
            $wp_query->is_singular = true;
            $virtual_post = new WP_Post((object) array(
                'ID' => 0,
                'post_type' => 'page',
                'post_parent' => 0,
                'post_title' => __('便民服务', 'itstudio'),
                'post_status' => 'publish',
                'post_name' => 'services',
                'post_content' => '',
            ));
            $wp_query->post = $virtual_post;
            $wp_query->posts = array($virtual_post);
            $wp_query->queried_object = $virtual_post;
            $wp_query->queried_object_id = 0;
            $wp_query->post_count = 1;
            $wp_query->found_posts = 1;
            $wp_query->max_num_pages = 1;
            global $post;
            $post = $virtual_post;
            setup_postdata($post);
        }
        add_filter('document_title_parts', function ($parts) {
            $parts['title'] = __('便民服务', 'itstudio');
            return $parts;
        });
        status_header(200);
        nocache_headers();
        include $template;
        exit;
    }
}
add_action('template_redirect', 'itstudio_services_fallback');

function itstudio_get_post_views($post_id) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return 0;
    }

    $meta_keys = array(
        'post_views_count',
        'itstudio_views',
        'views',
        'post_views',
        'view_count',
        'views_count',
    );

    $max_views = 0;
    foreach ($meta_keys as $meta_key) {
        $raw = get_post_meta($post_id, $meta_key, true);
        if ($raw !== '' && is_numeric($raw)) {
            $max_views = max($max_views, (int) $raw);
        }
    }

    return max(0, $max_views);
}

function itstudio_get_post_weight($post_id, $field_name = 'itstudio_weight') {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return 0;
    }

    if (!function_exists('get_field')) {
        return 0;
    }

    $raw = get_field($field_name, $post_id, false);
    if ($raw === '' || $raw === null || !is_numeric($raw)) {
        return 0;
    }

    return (int) $raw;
}

function itstudio_get_post_char_count($post_id) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return 0;
    }

    $content = (string) get_post_field('post_content', $post_id);
    $plain_text = trim(wp_strip_all_tags($content));
    if ($plain_text === '') {
        return 0;
    }

    if (function_exists('mb_strlen')) {
        return (int) mb_strlen($plain_text, 'UTF-8');
    }

    return (int) strlen($plain_text);
}

function itstudio_get_post_excerpt_chars($post_id, $limit = 200) {
    $post_id = (int) $post_id;
    $limit = max(1, (int) $limit);
    if ($post_id <= 0) {
        return '';
    }

    $excerpt = (string) get_post_field('post_excerpt', $post_id);
    if (trim($excerpt) === '') {
        $excerpt = (string) get_post_field('post_content', $post_id);
    }

    $excerpt = trim(wp_strip_all_tags($excerpt));
    if ($excerpt === '') {
        return '';
    }

    return wp_html_excerpt($excerpt, $limit, '...');
}

function itstudio_get_archive_url_by_post_type($post_type) {
    $post_type = sanitize_key((string) $post_type);
    if ($post_type === '') {
        return home_url('/');
    }

    $archive_url = get_post_type_archive_link($post_type);
    if ($archive_url) {
        return $archive_url;
    }

    if ($post_type === 'news') {
        return home_url('/news/');
    }

    if ($post_type === 'announcement') {
        return home_url('/announcement/');
    }

    return home_url('/');
}

function itstudio_get_archive_tag_filter_url($term, $post_type) {
    $term_obj = null;
    if ($term instanceof WP_Term) {
        $term_obj = $term;
    } else {
        $term_obj = get_term($term, 'post_tag');
    }

    if (!($term_obj instanceof WP_Term) || is_wp_error($term_obj)) {
        return '';
    }

    $post_type = sanitize_key((string) $post_type);
    if (!in_array($post_type, array('news', 'announcement'), true)) {
        return '';
    }

    $archive_url = itstudio_get_archive_url_by_post_type($post_type);
    return add_query_arg(
        array(
            'tag' => $term_obj->slug,
        ),
        $archive_url
    );
}

function itstudio_archive_extended_search_enabled($query) {
    if (!($query instanceof WP_Query)) {
        return false;
    }

    if (!$query->get('itstudio_archive_extend_search')) {
        return false;
    }

    $keyword = trim((string) $query->get('itstudio_archive_keyword'));
    return $keyword !== '';
}

function itstudio_archive_extended_search_join($join, $query) {
    if (!itstudio_archive_extended_search_enabled($query)) {
        return $join;
    }

    global $wpdb;
    if (strpos($join, $wpdb->users) !== false) {
        return $join;
    }

    return $join . " LEFT JOIN {$wpdb->users} ON ({$wpdb->posts}.post_author = {$wpdb->users}.ID) ";
}
add_filter('posts_join', 'itstudio_archive_extended_search_join', 10, 2);

function itstudio_archive_extended_search_where($where, $query) {
    if (!itstudio_archive_extended_search_enabled($query)) {
        return $where;
    }

    global $wpdb;
    $keyword = trim((string) $query->get('itstudio_archive_keyword'));
    $like = '%' . $wpdb->esc_like($keyword) . '%';

    $search_sql = $wpdb->prepare(
        " AND (
            {$wpdb->posts}.post_title LIKE %s
            OR {$wpdb->posts}.post_excerpt LIKE %s
            OR {$wpdb->posts}.post_content LIKE %s
            OR {$wpdb->users}.display_name LIKE %s
            OR {$wpdb->users}.user_login LIKE %s
            OR {$wpdb->users}.user_nicename LIKE %s
        )",
        $like,
        $like,
        $like,
        $like,
        $like,
        $like
    );

    return $where . $search_sql;
}
add_filter('posts_where', 'itstudio_archive_extended_search_where', 10, 2);

function itstudio_is_probably_bot_request() {
    $user_agent = strtolower(trim((string) ($_SERVER['HTTP_USER_AGENT'] ?? '')));
    if ($user_agent === '') {
        return true;
    }

    $bot_signatures = array(
        'bot',
        'spider',
        'crawl',
        'slurp',
        'headless',
        'preview',
        'facebookexternalhit',
        'discordbot',
        'telegrambot',
        'linkedinbot',
        'applebot',
        'googlebot',
        'bingbot',
        'wget',
        'curl',
        'python-requests',
        'postmanruntime',
    );

    foreach ($bot_signatures as $signature) {
        if (strpos($user_agent, $signature) !== false) {
            return true;
        }
    }

    $purpose = strtolower((string) ($_SERVER['HTTP_PURPOSE'] ?? ''));
    $sec_purpose = strtolower((string) ($_SERVER['HTTP_SEC_PURPOSE'] ?? ''));
    $x_moz = strtolower((string) ($_SERVER['HTTP_X_MOZ'] ?? ''));
    if (strpos($purpose, 'prefetch') !== false || strpos($sec_purpose, 'prefetch') !== false || $x_moz === 'prefetch') {
        return true;
    }

    return false;
}

function itstudio_get_view_cookie_name() {
    return 'itstudio_viewed_posts';
}

function itstudio_read_view_cookie_map() {
    $cookie_name = itstudio_get_view_cookie_name();
    $raw_cookie = isset($_COOKIE[$cookie_name]) ? wp_unslash((string) $_COOKIE[$cookie_name]) : '';
    if ($raw_cookie === '') {
        return array();
    }

    $decoded = json_decode($raw_cookie, true);
    if (!is_array($decoded)) {
        return array();
    }

    $clean = array();
    foreach ($decoded as $post_id => $timestamp) {
        $post_id = (int) $post_id;
        $timestamp = (int) $timestamp;
        if ($post_id > 0 && $timestamp > 0) {
            $clean[(string) $post_id] = $timestamp;
        }
    }

    return $clean;
}

function itstudio_write_view_cookie_map($map, $window_seconds) {
    if (!headers_sent()) {
        $cookie_name = itstudio_get_view_cookie_name();
        $expire_at = time() + max(DAY_IN_SECONDS, (int) $window_seconds * 2);
        $path = defined('COOKIEPATH') && COOKIEPATH ? COOKIEPATH : '/';
        $domain = defined('COOKIE_DOMAIN') ? COOKIE_DOMAIN : '';
        $secure = is_ssl();
        $http_only = true;
        $encoded = wp_json_encode($map);
        if (!is_string($encoded)) {
            return;
        }

        if (PHP_VERSION_ID >= 70300) {
            setcookie($cookie_name, $encoded, array(
                'expires' => $expire_at,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $http_only,
                'samesite' => 'Lax',
            ));
        } else {
            setcookie($cookie_name, $encoded, $expire_at, $path, $domain, $secure, $http_only);
        }

        $_COOKIE[$cookie_name] = $encoded;
    }
}

function itstudio_should_count_post_view($post_id) {
    $post_id = (int) $post_id;
    if ($post_id <= 0) {
        return false;
    }

    if (itstudio_is_probably_bot_request()) {
        return false;
    }

    $window_seconds = (int) apply_filters('itstudio_post_view_window_seconds', 12 * HOUR_IN_SECONDS);
    $window_seconds = max(60, $window_seconds);
    $max_entries = (int) apply_filters('itstudio_post_view_cookie_max_entries', 120);
    $max_entries = max(10, $max_entries);

    $now = time();
    $key = (string) $post_id;
    $map = itstudio_read_view_cookie_map();

    foreach ($map as $id => $timestamp) {
        if (($now - (int) $timestamp) > $window_seconds) {
            unset($map[$id]);
        }
    }

    if (isset($map[$key]) && ($now - (int) $map[$key]) < $window_seconds) {
        return false;
    }

    $map[$key] = $now;
    if (count($map) > $max_entries) {
        asort($map, SORT_NUMERIC);
        $map = array_slice($map, -$max_entries, null, true);
    }

    itstudio_write_view_cookie_map($map, $window_seconds);

    return true;
}

function itstudio_track_post_views() {
    if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
        return;
    }

    if (!is_singular(array('post', 'announcement', 'news')) || is_preview() || is_feed() || is_trackback() || is_embed()) {
        return;
    }

    if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper((string) $_SERVER['REQUEST_METHOD']) !== 'GET') {
        return;
    }

    $post_id = (int) get_queried_object_id();
    if ($post_id <= 0) {
        return;
    }

    if (!itstudio_should_count_post_view($post_id)) {
        return;
    }

    $views = itstudio_get_post_views($post_id) + 1;
    update_post_meta($post_id, 'post_views_count', $views);
    update_post_meta($post_id, 'itstudio_views', $views);
}
add_action('template_redirect', 'itstudio_track_post_views', 20);

function itstudio_is_join_page_context() {
    $is_join = is_page('join') || is_page_template('page-join.php');
    if (!$is_join && is_404()) {
        global $wp;
        $request = isset($wp->request) ? trim((string) $wp->request, '/') : '';
        $is_join = ($request === 'join');
    }

    return $is_join;
}

function itstudio_join_get_default_settings() {
    return array(
        'registration_start' => '',
        'registration_end' => '',
        'first_interview_date' => '',
        'first_interview_end' => '',
        'first_interview_location_cn' => '',
        'first_interview_location_en' => '',
        'second_interview_date' => '',
        'second_interview_end' => '',
        'second_interview_location_cn' => '',
        'second_interview_location_en' => '',
        'assessment_start_date' => '',
        'assessment_end_date' => '',
        'notice_start_date' => '',
        'result_data_source' => 'file',
        'result_formidable_form_id' => '',
        'result_formidable_name_field' => '',
        'result_formidable_qq_field' => '',
        'result_formidable_email_field' => '',
        'result_formidable_student_id_field' => '',
        'result_formidable_registration_field' => '',
        'result_formidable_first_interview_field' => '',
        'result_formidable_assessment_field' => '',
        'result_formidable_second_interview_field' => '',
        'result_formidable_admission_field' => '',
        'result_first_interview_file' => 0,
        'result_assessment_file' => 0,
        'result_second_interview_file' => 0,
        'result_admission_file' => 0,
        'result_registration_records' => '',
        'result_first_interview_records' => '',
        'result_assessment_records' => '',
        'result_second_interview_records' => '',
        'result_admission_records' => '',
        'photo_registration' => 0,
        'photo_first_interview' => 0,
        'photo_assessment' => 0,
        'photo_second_interview' => 0,
        'photo_public_notice' => 0,
        'photo_inactive' => 0,
        'signup_form_shortcode' => '',
    );
}

function itstudio_join_get_photo_field_map() {
    return array(
        'registration' => 'photo_registration',
        'first_interview' => 'photo_first_interview',
        'assessment' => 'photo_assessment',
        'second_interview' => 'photo_second_interview',
        'public_notice' => 'photo_public_notice',
        'inactive' => 'photo_inactive',
    );
}

function itstudio_join_sanitize_datetime_local_value($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    $value = preg_replace('/\s+/', 'T', $value);

    // 兼容旧数据：仅日期
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return $value;
    }

    // 兼容输入：YYYY-MM-DDTHH:MM 或 YYYY-MM-DDTHH:MM:SS
    if (preg_match('/^(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2})(:\d{2})?$/', $value, $matches)) {
        return $matches[1] . 'T' . $matches[2];
    }

    return '';
}

function itstudio_join_sanitize_date_value($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return '';
    }

    return $value;
}

function itstudio_join_sanitize_shortcode_value($value) {
    if (!is_string($value)) {
        return '';
    }

    return trim(sanitize_text_field(wp_unslash($value)));
}

function itstudio_join_sanitize_records_value($value) {
    if (!is_string($value)) {
        return '';
    }

    $value = sanitize_textarea_field(wp_unslash($value));
    $value = str_replace(array("\r\n", "\r"), "\n", $value);
    $lines = explode("\n", $value);
    $clean_lines = array();
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') {
            continue;
        }
        $clean_lines[] = $line;
    }

    return implode("\n", $clean_lines);
}

function itstudio_join_sanitize_settings($input) {
    $defaults = itstudio_join_get_default_settings();
    $input = is_array($input) ? $input : array();

    $sanitized = array(
        'registration_start' => itstudio_join_sanitize_datetime_local_value($input['registration_start'] ?? ''),
        'registration_end' => itstudio_join_sanitize_datetime_local_value($input['registration_end'] ?? ''),
        'first_interview_date' => itstudio_join_sanitize_datetime_local_value($input['first_interview_date'] ?? ''),
        'first_interview_end' => itstudio_join_sanitize_datetime_local_value($input['first_interview_end'] ?? ''),
        'first_interview_location_cn' => itstudio_join_sanitize_shortcode_value($input['first_interview_location_cn'] ?? ''),
        'first_interview_location_en' => itstudio_join_sanitize_shortcode_value($input['first_interview_location_en'] ?? ''),
        'second_interview_date' => itstudio_join_sanitize_datetime_local_value($input['second_interview_date'] ?? ''),
        'second_interview_end' => itstudio_join_sanitize_datetime_local_value($input['second_interview_end'] ?? ''),
        'second_interview_location_cn' => itstudio_join_sanitize_shortcode_value($input['second_interview_location_cn'] ?? ''),
        'second_interview_location_en' => itstudio_join_sanitize_shortcode_value($input['second_interview_location_en'] ?? ''),
        'assessment_start_date' => itstudio_join_sanitize_date_value($input['assessment_start_date'] ?? ''),
        'assessment_end_date' => itstudio_join_sanitize_date_value($input['assessment_end_date'] ?? ''),
        'notice_start_date' => itstudio_join_sanitize_date_value($input['notice_start_date'] ?? ''),
        // 固定使用文件结果模式，避免后台再配置字段映射。
        'result_data_source' => 'file',
        'result_formidable_form_id' => itstudio_join_sanitize_shortcode_value($input['result_formidable_form_id'] ?? ''),
        'result_formidable_name_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_name_field'] ?? ''),
        'result_formidable_qq_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_qq_field'] ?? ''),
        'result_formidable_email_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_email_field'] ?? ''),
        'result_formidable_student_id_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_student_id_field'] ?? ''),
        'result_formidable_registration_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_registration_field'] ?? ''),
        'result_formidable_first_interview_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_first_interview_field'] ?? ''),
        'result_formidable_assessment_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_assessment_field'] ?? ''),
        'result_formidable_second_interview_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_second_interview_field'] ?? ''),
        'result_formidable_admission_field' => itstudio_join_sanitize_shortcode_value($input['result_formidable_admission_field'] ?? ''),
        'result_first_interview_file' => isset($input['result_first_interview_file']) ? absint($input['result_first_interview_file']) : 0,
        'result_assessment_file' => isset($input['result_assessment_file']) ? absint($input['result_assessment_file']) : 0,
        'result_second_interview_file' => isset($input['result_second_interview_file']) ? absint($input['result_second_interview_file']) : 0,
        'result_admission_file' => isset($input['result_admission_file']) ? absint($input['result_admission_file']) : 0,
        'result_registration_records' => itstudio_join_sanitize_records_value($input['result_registration_records'] ?? ''),
        'result_first_interview_records' => itstudio_join_sanitize_records_value($input['result_first_interview_records'] ?? ''),
        'result_assessment_records' => itstudio_join_sanitize_records_value($input['result_assessment_records'] ?? ''),
        'result_second_interview_records' => itstudio_join_sanitize_records_value($input['result_second_interview_records'] ?? ''),
        'result_admission_records' => itstudio_join_sanitize_records_value($input['result_admission_records'] ?? ''),
        'signup_form_shortcode' => itstudio_join_sanitize_shortcode_value($input['signup_form_shortcode'] ?? ''),
    );

    foreach (itstudio_join_get_photo_field_map() as $field_key) {
        $sanitized[$field_key] = isset($input[$field_key]) ? absint($input[$field_key]) : 0;
    }

    return array_merge($defaults, $sanitized);
}

function itstudio_join_get_settings() {
    $defaults = itstudio_join_get_default_settings();
    $stored = get_option('itstudio_join_settings', array());
    if (!is_array($stored)) {
        return $defaults;
    }

    return array_merge($defaults, itstudio_join_sanitize_settings($stored));
}

function itstudio_join_parse_datetime_local($value, $date_only_as_end_of_day = false) {
    $value = itstudio_join_sanitize_datetime_local_value($value);
    if ($value === '') {
        return null;
    }

    $timezone = wp_timezone();
    $format = 'Y-m-d\TH:i';
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        $format = 'Y-m-d';
    }

    $parsed = DateTimeImmutable::createFromFormat('!' . $format, $value, $timezone);
    $errors = DateTimeImmutable::getLastErrors();
    if (!($parsed instanceof DateTimeImmutable)) {
        return null;
    }

    if (is_array($errors) && (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0)) {
        return null;
    }

    if ($format === 'Y-m-d') {
        return $date_only_as_end_of_day
            ? $parsed->setTime(23, 59, 59)
            : $parsed->setTime(0, 0, 0);
    }

    return $parsed;
}

function itstudio_join_parse_date($value) {
    $value = itstudio_join_sanitize_date_value($value);
    if ($value === '') {
        return null;
    }

    $timezone = wp_timezone();
    $parsed = DateTimeImmutable::createFromFormat('!Y-m-d', $value, $timezone);
    $errors = DateTimeImmutable::getLastErrors();
    if (!($parsed instanceof DateTimeImmutable)) {
        return null;
    }

    if (is_array($errors) && (($errors['warning_count'] ?? 0) > 0 || ($errors['error_count'] ?? 0) > 0)) {
        return null;
    }

    return $parsed;
}

function itstudio_join_to_datetime_local_input_value($value, $date_only_as_end_of_day = false) {
    $parsed = itstudio_join_parse_datetime_local($value, $date_only_as_end_of_day);
    return $parsed instanceof DateTimeImmutable ? $parsed->format('Y-m-d\TH:i') : '';
}

function itstudio_join_to_date_input_value($value) {
    $parsed = itstudio_join_parse_date($value);
    if (!($parsed instanceof DateTimeImmutable)) {
        $parsed = itstudio_join_parse_datetime_local($value, false);
    }
    return $parsed instanceof DateTimeImmutable ? $parsed->format('Y-m-d') : '';
}

function itstudio_join_get_result_field_map() {
    return array(
        'registration' => 'result_registration_records',
        'first_interview' => 'result_first_interview_records',
        'assessment' => 'result_assessment_records',
        'second_interview' => 'result_second_interview_records',
        'public_notice' => 'result_admission_records',
    );
}

function itstudio_join_get_result_file_field_map() {
    return array(
        'first_interview' => 'result_first_interview_file',
        'assessment' => 'result_assessment_file',
        'second_interview' => 'result_second_interview_file',
        'public_notice' => 'result_admission_file',
    );
}

function itstudio_join_is_file_result_mode($settings) {
    $settings = is_array($settings) ? $settings : array();
    return (($settings['result_data_source'] ?? 'manual') === 'file');
}

function itstudio_join_get_result_file_attachment_id($settings, $stage_key) {
    $settings = is_array($settings) ? $settings : array();
    $map = itstudio_join_get_result_file_field_map();
    $setting_key = isset($map[$stage_key]) ? $map[$stage_key] : '';
    if ($setting_key === '') {
        return 0;
    }
    return absint((string) ($settings[$setting_key] ?? ''));
}

function itstudio_join_csv_delimiter_for_line($line) {
    $line = (string) $line;
    $candidates = array(',', ';', "\t", '|');
    $best = ',';
    $best_count = -1;
    foreach ($candidates as $delimiter) {
        $count = substr_count($line, $delimiter);
        if ($count > $best_count) {
            $best_count = $count;
            $best = $delimiter;
        }
    }
    return $best;
}

function itstudio_join_read_csv_rows($path) {
    if (!is_string($path) || $path === '' || !is_readable($path)) {
        return array();
    }

    $rows = array();
    $handle = fopen($path, 'rb');
    if ($handle === false) {
        return array();
    }

    $delimiter = ',';
    $first_line = fgets($handle);
    if ($first_line !== false) {
        $first_line = preg_replace('/^\xEF\xBB\xBF/', '', (string) $first_line);
        $delimiter = itstudio_join_csv_delimiter_for_line($first_line);
        rewind($handle);
    }

    while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
        if (!is_array($data)) {
            continue;
        }
        $row = array();
        foreach ($data as $cell) {
            $row[] = trim((string) $cell);
        }
        if (!empty(array_filter($row, static function ($value) {
            return $value !== '';
        }))) {
            $rows[] = $row;
        }
    }

    fclose($handle);
    return $rows;
}

function itstudio_join_xlsx_column_to_index($column_ref) {
    $column_ref = strtoupper((string) $column_ref);
    $length = strlen($column_ref);
    $index = 0;
    for ($i = 0; $i < $length; $i++) {
        $ch = ord($column_ref[$i]);
        if ($ch < 65 || $ch > 90) {
            continue;
        }
        $index = ($index * 26) + ($ch - 64);
    }
    return max(0, $index - 1);
}

function itstudio_join_xlsx_shared_strings($zip) {
    $shared = array();
    $xml = $zip->getFromName('xl/sharedStrings.xml');
    if (!is_string($xml) || trim($xml) === '') {
        return $shared;
    }

    $sx = simplexml_load_string($xml);
    if (!($sx instanceof SimpleXMLElement)) {
        return $shared;
    }

    foreach ($sx->si as $si) {
        if (isset($si->t)) {
            $shared[] = (string) $si->t;
            continue;
        }
        $chunks = array();
        if (isset($si->r)) {
            foreach ($si->r as $run) {
                $chunks[] = (string) $run->t;
            }
        }
        $shared[] = implode('', $chunks);
    }

    return $shared;
}

function itstudio_join_read_xlsx_rows($path) {
    if (!class_exists('ZipArchive') || !class_exists('SimpleXMLElement')) {
        return array();
    }
    if (!is_string($path) || $path === '' || !is_readable($path)) {
        return array();
    }

    $zip = new ZipArchive();
    if ($zip->open($path) !== true) {
        return array();
    }

    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    if (!is_string($sheetXml) || trim($sheetXml) === '') {
        // fallback: first worksheet found
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (is_string($name) && preg_match('#^xl/worksheets/sheet\d+\.xml$#', $name)) {
                $sheetXml = $zip->getFromName($name);
                break;
            }
        }
    }

    if (!is_string($sheetXml) || trim($sheetXml) === '') {
        $zip->close();
        return array();
    }

    $sharedStrings = itstudio_join_xlsx_shared_strings($zip);
    $zip->close();

    $sheet = simplexml_load_string($sheetXml);
    if (!($sheet instanceof SimpleXMLElement) || !isset($sheet->sheetData)) {
        return array();
    }

    $rows = array();
    foreach ($sheet->sheetData->row as $rowNode) {
        $row = array();
        foreach ($rowNode->c as $cell) {
            $ref = (string) ($cell['r'] ?? '');
            $type = (string) ($cell['t'] ?? '');
            preg_match('/^[A-Z]+/i', $ref, $matches);
            $colRef = isset($matches[0]) ? $matches[0] : '';
            $colIndex = itstudio_join_xlsx_column_to_index($colRef);

            $value = '';
            if ($type === 's') {
                $sharedIndex = isset($cell->v) ? (int) $cell->v : -1;
                $value = ($sharedIndex >= 0 && isset($sharedStrings[$sharedIndex])) ? (string) $sharedStrings[$sharedIndex] : '';
            } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                $value = (string) $cell->is->t;
            } else {
                $value = isset($cell->v) ? (string) $cell->v : '';
            }

            $row[$colIndex] = trim($value);
        }

        if (!empty($row)) {
            ksort($row);
            $normalized = array_values($row);
            if (!empty(array_filter($normalized, static function ($value) {
                return $value !== '';
            }))) {
                $rows[] = $normalized;
            }
        }
    }

    return $rows;
}

function itstudio_join_read_result_rows_from_attachment($attachment_id) {
    static $cache = array();
    $attachment_id = absint($attachment_id);
    if ($attachment_id <= 0) {
        return array();
    }
    if (isset($cache[$attachment_id])) {
        return $cache[$attachment_id];
    }

    $path = get_attached_file($attachment_id);
    if (!is_string($path) || $path === '' || !is_readable($path)) {
        $cache[$attachment_id] = array();
        return array();
    }

    $ext = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));
    $rows = array();
    if ($ext === 'csv') {
        $rows = itstudio_join_read_csv_rows($path);
    } elseif ($ext === 'xlsx') {
        $rows = itstudio_join_read_xlsx_rows($path);
    } else {
        // try csv as fallback
        $rows = itstudio_join_read_csv_rows($path);
    }

    $cache[$attachment_id] = is_array($rows) ? $rows : array();
    return $cache[$attachment_id];
}

function itstudio_join_parse_result_rows_to_records($rows) {
    $rows = is_array($rows) ? $rows : array();
    $records = array();

    $is_header_row = static function ($name, $qq, $email, $student_id, $phone, $passed_raw) {
        $joined = implode(',', array($name, $qq, $email, $student_id, $phone, $passed_raw));
        $joined = preg_replace('/^\xEF\xBB\xBF/', '', (string) $joined);
        $joined = preg_replace('/\s+/u', '', (string) $joined);
        $joined_lower = function_exists('mb_strtolower') ? mb_strtolower($joined, 'UTF-8') : strtolower($joined);

        $is_cn_header = (
            strpos($joined_lower, '姓名') !== false
            || strpos($joined_lower, 'qq') !== false
            || strpos($joined_lower, '邮箱') !== false
            || strpos($joined_lower, '学号') !== false
            || strpos($joined_lower, '手机') !== false
            || strpos($joined_lower, '是否通过') !== false
        );

        $is_en_header = (
            strpos($joined_lower, 'name') !== false
            || strpos($joined_lower, 'qq') !== false
            || strpos($joined_lower, 'email') !== false
            || strpos($joined_lower, 'student') !== false
            || strpos($joined_lower, 'phone') !== false
            || strpos($joined_lower, 'pass') !== false
        );

        return $is_cn_header || $is_en_header;
    };

    foreach ($rows as $idx => $row) {
        if (!is_array($row)) {
            continue;
        }

        $name = trim((string) ($row[0] ?? ''));
        $qq = trim((string) ($row[1] ?? ''));
        $email = trim((string) ($row[2] ?? ''));
        $student_id = trim((string) ($row[3] ?? ''));
        $phone = trim((string) ($row[4] ?? ''));
        $passed_raw = trim((string) ($row[5] ?? ''));

        // 过滤标题行（支持中英文标题）
        if ($idx === 0 && $is_header_row($name, $qq, $email, $student_id, $phone, $passed_raw)) {
            continue;
        }
        // 容错：有些表格会在中间重复表头
        if ($is_header_row($name, $qq, $email, $student_id, $phone, $passed_raw)) {
            continue;
        }

        $record = array(
            'name' => itstudio_join_normalize_lookup_value('name', $name),
            'qq' => itstudio_join_normalize_lookup_value('qq', $qq),
            'email' => itstudio_join_normalize_lookup_value('email', $email),
            'student_id' => itstudio_join_normalize_lookup_value('student_id', $student_id),
            'phone' => preg_replace('/\D+/', '', $phone),
            'passed' => ($passed_raw === '1'),
        );

        if ($record['name'] === '' && $record['qq'] === '' && $record['email'] === '' && $record['student_id'] === '' && $record['phone'] === '') {
            continue;
        }

        $records[] = $record;
    }

    return $records;
}

function itstudio_join_is_formidable_result_mode($settings) {
    $settings = is_array($settings) ? $settings : array();
    return (($settings['result_data_source'] ?? 'manual') === 'formidable');
}

function itstudio_join_get_formidable_form_id($settings) {
    $settings = is_array($settings) ? $settings : array();
    return absint((string) ($settings['result_formidable_form_id'] ?? ''));
}

function itstudio_join_get_formidable_identity_field_refs($settings) {
    $settings = is_array($settings) ? $settings : array();
    return array(
        'name' => trim((string) ($settings['result_formidable_name_field'] ?? '')),
        'qq' => trim((string) ($settings['result_formidable_qq_field'] ?? '')),
        'email' => trim((string) ($settings['result_formidable_email_field'] ?? '')),
        'student_id' => trim((string) ($settings['result_formidable_student_id_field'] ?? '')),
    );
}

function itstudio_join_get_formidable_stage_field_ref($settings, $stage_key) {
    $settings = is_array($settings) ? $settings : array();
    $map = array(
        'registration' => 'result_formidable_registration_field',
        'first_interview' => 'result_formidable_first_interview_field',
        'assessment' => 'result_formidable_assessment_field',
        'second_interview' => 'result_formidable_second_interview_field',
        'public_notice' => 'result_formidable_admission_field',
    );
    $setting_key = isset($map[$stage_key]) ? $map[$stage_key] : '';
    if ($setting_key === '') {
        return '';
    }
    return trim((string) ($settings[$setting_key] ?? ''));
}

function itstudio_join_resolve_formidable_field_id($form_id, $field_ref) {
    static $cache = array();
    $form_id = absint($form_id);
    $field_ref = trim((string) $field_ref);
    if ($field_ref === '') {
        return 0;
    }

    $cache_key = $form_id . '|' . $field_ref;
    if (isset($cache[$cache_key])) {
        return (int) $cache[$cache_key];
    }

    if (ctype_digit($field_ref)) {
        $cache[$cache_key] = (int) $field_ref;
        return (int) $cache[$cache_key];
    }

    $field_id = 0;
    if (class_exists('FrmField') && method_exists('FrmField', 'get_id_by_key')) {
        $field_id = (int) FrmField::get_id_by_key($field_ref);
    }

    if ($field_id <= 0) {
        global $wpdb;
        $table = $wpdb->prefix . 'frm_fields';
        if ($form_id > 0) {
            $field_id = (int) $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table} WHERE field_key = %s AND form_id = %d LIMIT 1", $field_ref, $form_id));
        } else {
            $field_id = (int) $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table} WHERE field_key = %s LIMIT 1", $field_ref));
        }
    }

    $cache[$cache_key] = $field_id > 0 ? $field_id : 0;
    return (int) $cache[$cache_key];
}

function itstudio_join_get_formidable_entry_meta_value($entry_id, $field_id) {
    static $cache = array();
    $entry_id = absint($entry_id);
    $field_id = absint($field_id);
    if ($entry_id <= 0 || $field_id <= 0) {
        return '';
    }

    $cache_key = $entry_id . '|' . $field_id;
    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }

    $value = '';
    if (class_exists('FrmEntryMeta') && method_exists('FrmEntryMeta', 'get_entry_meta_by_field')) {
        $value = FrmEntryMeta::get_entry_meta_by_field($entry_id, $field_id, true);
    } else {
        global $wpdb;
        $table = $wpdb->prefix . 'frm_item_metas';
        $value = $wpdb->get_var($wpdb->prepare("SELECT meta_value FROM {$table} WHERE item_id = %d AND field_id = %d LIMIT 1", $entry_id, $field_id));
    }

    $cache[$cache_key] = $value;
    return $value;
}

function itstudio_join_is_truthy_result_value($value) {
    if (is_array($value)) {
        foreach ($value as $item) {
            if (itstudio_join_is_truthy_result_value($item)) {
                return true;
            }
        }
        return false;
    }

    if (is_bool($value)) {
        return $value;
    }

    if (is_numeric($value)) {
        return ((float) $value) > 0;
    }

    $value = trim((string) $value);
    if ($value === '') {
        return false;
    }

    if (is_serialized($value)) {
        $decoded = maybe_unserialize($value);
        if ($decoded !== $value) {
            return itstudio_join_is_truthy_result_value($decoded);
        }
    }

    $normalized = function_exists('mb_strtolower') ? mb_strtolower($value, 'UTF-8') : strtolower($value);
    return in_array($normalized, array(
        '1', 'true', 'yes', 'on', 'y',
        'pass', 'passed', 'admit', 'admitted',
        '是', '通过', '已通过', '录取', '已录取', '完成', '成功',
    ), true);
}

function itstudio_join_find_formidable_entry_id_by_query($settings, $query) {
    $settings = is_array($settings) ? $settings : array();
    $query = is_array($query) ? $query : array();
    if (!class_exists('FrmEntry') || !class_exists('FrmEntryMeta')) {
        return 0;
    }

    $form_id = itstudio_join_get_formidable_form_id($settings);
    if ($form_id <= 0) {
        return 0;
    }

    $identity_refs = itstudio_join_get_formidable_identity_field_refs($settings);
    $active_filters = array();
    foreach (array('name', 'qq', 'email', 'student_id') as $field) {
        $query_value = trim((string) ($query[$field] ?? ''));
        if ($query_value === '') {
            continue;
        }
        $field_ref = trim((string) ($identity_refs[$field] ?? ''));
        if ($field_ref === '') {
            continue;
        }
        $field_id = itstudio_join_resolve_formidable_field_id($form_id, $field_ref);
        if ($field_id <= 0) {
            continue;
        }
        $active_filters[$field] = $field_id;
    }

    if (empty($active_filters)) {
        return 0;
    }

    $entries = FrmEntry::getAll(
        array(
            'it.form_id' => $form_id,
            'is_draft' => 0,
        ),
        ' ORDER BY it.id DESC'
    );

    if (empty($entries)) {
        return 0;
    }

    foreach ($entries as $entry) {
        $entry_id = absint(is_object($entry) ? ($entry->id ?? 0) : (is_array($entry) ? ($entry['id'] ?? 0) : 0));
        if ($entry_id <= 0) {
            continue;
        }

        $matched = true;
        foreach ($active_filters as $field => $field_id) {
            $raw_value = itstudio_join_get_formidable_entry_meta_value($entry_id, $field_id);
            $normalized_entry_value = itstudio_join_normalize_lookup_value($field, (string) $raw_value);
            $normalized_query_value = itstudio_join_normalize_lookup_value($field, (string) ($query[$field] ?? ''));
            if ($normalized_query_value === '' || $normalized_entry_value === '' || $normalized_entry_value !== $normalized_query_value) {
                $matched = false;
                break;
            }
        }

        if ($matched) {
            return $entry_id;
        }
    }

    return 0;
}

function itstudio_join_formidable_entry_matches_stage($settings, $entry_id, $stage_key) {
    $settings = is_array($settings) ? $settings : array();
    $entry_id = absint($entry_id);
    $stage_key = (string) $stage_key;
    if ($entry_id <= 0) {
        return false;
    }

    $form_id = itstudio_join_get_formidable_form_id($settings);
    if ($form_id <= 0) {
        return false;
    }

    $field_ref = itstudio_join_get_formidable_stage_field_ref($settings, $stage_key);
    if ($field_ref === '') {
        return $stage_key === 'registration';
    }

    $field_id = itstudio_join_resolve_formidable_field_id($form_id, $field_ref);
    if ($field_id <= 0) {
        return false;
    }

    $raw_value = itstudio_join_get_formidable_entry_meta_value($entry_id, $field_id);
    return itstudio_join_is_truthy_result_value($raw_value);
}

function itstudio_join_formidable_stage_has_data($settings, $stage_key) {
    $settings = is_array($settings) ? $settings : array();
    if (!class_exists('FrmEntry') || !class_exists('FrmEntryMeta')) {
        return false;
    }

    $form_id = itstudio_join_get_formidable_form_id($settings);
    if ($form_id <= 0) {
        return false;
    }

    global $wpdb;
    $items_table = $wpdb->prefix . 'frm_items';
    $metas_table = $wpdb->prefix . 'frm_item_metas';

    $entry_count = (int) $wpdb->get_var($wpdb->prepare("SELECT COUNT(1) FROM {$items_table} WHERE form_id = %d AND is_draft = 0", $form_id));
    if ($entry_count <= 0) {
        return false;
    }

    if ($stage_key === 'registration') {
        return true;
    }

    $field_ref = itstudio_join_get_formidable_stage_field_ref($settings, $stage_key);
    $field_id = itstudio_join_resolve_formidable_field_id($form_id, $field_ref);
    if ($field_id <= 0) {
        return false;
    }

    $hit = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(1)
         FROM {$metas_table} AS m
         INNER JOIN {$items_table} AS i ON i.id = m.item_id
         WHERE i.form_id = %d
           AND i.is_draft = 0
           AND m.field_id = %d
           AND COALESCE(m.meta_value, '') <> ''",
        $form_id,
        $field_id
    ));

    return $hit > 0 || $entry_count > 0;
}

function itstudio_join_formidable_has_queryable_identity_field($settings, $query) {
    $settings = is_array($settings) ? $settings : array();
    $query = is_array($query) ? $query : array();
    $form_id = itstudio_join_get_formidable_form_id($settings);
    if ($form_id <= 0) {
        return false;
    }

    $identity_refs = itstudio_join_get_formidable_identity_field_refs($settings);
    foreach (array('name', 'qq', 'email', 'student_id') as $field) {
        $query_value = trim((string) ($query[$field] ?? ''));
        if ($query_value === '') {
            continue;
        }
        $field_ref = trim((string) ($identity_refs[$field] ?? ''));
        if ($field_ref === '') {
            continue;
        }
        $field_id = itstudio_join_resolve_formidable_field_id($form_id, $field_ref);
        if ($field_id > 0) {
            return true;
        }
    }

    return false;
}

function itstudio_join_has_uploaded_result_for_stage($settings, $stage_key) {
    $settings = is_array($settings) ? $settings : array();
    if (itstudio_join_is_file_result_mode($settings)) {
        $attachment_id = itstudio_join_get_result_file_attachment_id($settings, $stage_key);
        if ($attachment_id <= 0) {
            return false;
        }
        $rows = itstudio_join_read_result_rows_from_attachment($attachment_id);
        $records = itstudio_join_parse_result_rows_to_records($rows);
        return !empty($records);
    }

    if (itstudio_join_is_formidable_result_mode($settings)) {
        return itstudio_join_formidable_stage_has_data($settings, $stage_key);
    }

    $field_map = itstudio_join_get_result_field_map();
    $field_key = isset($field_map[$stage_key]) ? $field_map[$stage_key] : '';
    if ($field_key === '') {
        return false;
    }

    return trim((string) ($settings[$field_key] ?? '')) !== '';
}

function itstudio_join_normalize_lookup_value($field, $value) {
    $field = trim((string) $field);
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    if ($field === 'qq') {
        return preg_replace('/\D+/', '', $value);
    }

    if ($field === 'email') {
        return strtolower($value);
    }

    if ($field === 'student_id') {
        return strtoupper(preg_replace('/\s+/u', '', $value));
    }

    $value = preg_replace('/\s+/u', '', $value);
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($value, 'UTF-8');
    }

    return strtolower($value);
}

function itstudio_join_detect_progress_query_identity($raw_identity) {
    $raw_identity = trim((string) $raw_identity);
    $compacted = preg_replace('/\s+/u', '', $raw_identity);

    $detected = array(
        'raw' => $raw_identity,
        'field' => '',
        'value' => '',
    );

    if ($compacted === '') {
        return $detected;
    }

    $email = sanitize_email($compacted);
    if ($email !== '' && is_email($email)) {
        $detected['field'] = 'email';
        $detected['value'] = itstudio_join_normalize_lookup_value('email', $email);
        return $detected;
    }

    if (preg_match('/^\d+$/', $compacted)) {
        $digits = $compacted;
        $len = strlen($digits);

        if ($len >= 10 && $len <= 12) {
            $detected['field'] = 'student_id';
            $detected['value'] = itstudio_join_normalize_lookup_value('student_id', $digits);
            return $detected;
        }

        if ($len >= 5 && $len <= 12) {
            $detected['field'] = 'qq';
            $detected['value'] = itstudio_join_normalize_lookup_value('qq', $digits);
            return $detected;
        }

        return $detected;
    }

    if (preg_match('/^[\x{4e00}-\x{9fff}·]{2,20}$/u', $compacted)) {
        $detected['field'] = 'name';
        $detected['value'] = itstudio_join_normalize_lookup_value('name', $compacted);
        return $detected;
    }

    return $detected;
}

function itstudio_join_parse_result_records($raw) {
    $raw = str_replace(array("\r\n", "\r"), "\n", (string) $raw);
    if ($raw === '') {
        return array();
    }

    $records = array();
    $lines = explode("\n", $raw);
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        $header_probe = preg_replace('/\s+/u', '', $line);
        $header_probe = function_exists('mb_strtolower') ? mb_strtolower($header_probe, 'UTF-8') : strtolower($header_probe);
        $is_cn_header = (strpos($header_probe, '姓名') !== false) && (strpos($header_probe, 'qq') !== false || strpos($header_probe, '邮箱') !== false || strpos($header_probe, '学号') !== false);
        $is_en_header = (strpos($header_probe, 'name') !== false) && (strpos($header_probe, 'qq') !== false || strpos($header_probe, 'email') !== false || strpos($header_probe, 'student') !== false);
        if ($is_cn_header || $is_en_header) {
            continue;
        }

        $parts = preg_split('/[,\|，\t]+/u', $line);
        $parts = is_array($parts) ? array_values(array_filter(array_map('trim', $parts), static function ($item) {
            return $item !== '';
        })) : array();
        if (empty($parts)) {
            continue;
        }

        $name = '';
        $qq = '';
        $email = '';
        $student_id = '';

        if (count($parts) === 1) {
            $single = (string) $parts[0];
            if (strpos($single, '@') !== false) {
                $email = $single;
            } elseif (preg_match('/^\d{5,}$/', $single)) {
                $qq = $single;
            } else {
                $name = $single;
            }
        } else {
            $name = (string) ($parts[0] ?? '');
            $qq = (string) ($parts[1] ?? '');
            $email = (string) ($parts[2] ?? '');
            $student_id = (string) ($parts[3] ?? '');
        }

        $record = array(
            'name' => itstudio_join_normalize_lookup_value('name', $name),
            'qq' => itstudio_join_normalize_lookup_value('qq', $qq),
            'email' => itstudio_join_normalize_lookup_value('email', $email),
            'student_id' => itstudio_join_normalize_lookup_value('student_id', $student_id),
        );

        if ($record['name'] === '' && $record['qq'] === '' && $record['email'] === '' && $record['student_id'] === '') {
            continue;
        }

        $records[] = $record;
    }

    return $records;
}

function itstudio_join_record_matches_query($record, $query) {
    $record = is_array($record) ? $record : array();
    $query = is_array($query) ? $query : array();
    $has_any_query = false;

    foreach (array('name', 'qq', 'email', 'student_id') as $field) {
        $q = trim((string) ($query[$field] ?? ''));
        if ($q === '') {
            continue;
        }
        $has_any_query = true;
        $r = trim((string) ($record[$field] ?? ''));
        if ($r === '' || $r !== $q) {
            return false;
        }
    }

    return $has_any_query;
}

function itstudio_join_find_record_in_stage_results($settings, $stage_key, $query) {
    $settings = is_array($settings) ? $settings : array();
    if (itstudio_join_is_file_result_mode($settings)) {
        $attachment_id = itstudio_join_get_result_file_attachment_id($settings, $stage_key);
        if ($attachment_id <= 0) {
            return false;
        }
        $rows = itstudio_join_read_result_rows_from_attachment($attachment_id);
        $records = itstudio_join_parse_result_rows_to_records($rows);
        if (empty($records)) {
            return false;
        }
        foreach ($records as $record) {
            if (itstudio_join_record_matches_query($record, $query) && !empty($record['passed'])) {
                return true;
            }
        }
        return false;
    }

    if (itstudio_join_is_formidable_result_mode($settings)) {
        static $entry_cache = array();
        $cache_key = md5(wp_json_encode(array(
            'form_id' => itstudio_join_get_formidable_form_id($settings),
            'query' => $query,
        )));
        if (!isset($entry_cache[$cache_key])) {
            $entry_cache[$cache_key] = itstudio_join_find_formidable_entry_id_by_query($settings, $query);
        }
        $entry_id = (int) $entry_cache[$cache_key];
        if ($entry_id <= 0) {
            return false;
        }
        return itstudio_join_formidable_entry_matches_stage($settings, $entry_id, $stage_key);
    }

    $field_map = itstudio_join_get_result_field_map();
    $field_key = isset($field_map[$stage_key]) ? $field_map[$stage_key] : '';
    if ($field_key === '') {
        return false;
    }

    $raw = (string) ($settings[$field_key] ?? '');
    if (trim($raw) === '') {
        return false;
    }

    $records = itstudio_join_parse_result_records($raw);
    if (empty($records)) {
        return false;
    }

    foreach ($records as $record) {
        if (itstudio_join_record_matches_query($record, $query)) {
            return true;
        }
    }

    return false;
}

function itstudio_join_get_stage_status_by_key($runtime, $stage_key) {
    $runtime = is_array($runtime) ? $runtime : array();
    $stages = isset($runtime['stages']) && is_array($runtime['stages']) ? $runtime['stages'] : array();
    foreach ($stages as $stage) {
        if (!is_array($stage)) {
            continue;
        }
        if ((string) ($stage['key'] ?? '') === (string) $stage_key) {
            return (string) ($stage['status'] ?? 'pending');
        }
    }

    return 'pending';
}

function itstudio_join_resolve_progress_lookup($runtime = array(), $request_source = null) {
    $runtime = is_array($runtime) ? $runtime : array();
    $settings = isset($runtime['settings']) && is_array($runtime['settings']) ? $runtime['settings'] : itstudio_join_get_settings();
    $request_source = is_array($request_source) ? $request_source : $_GET;

    $raw_identity = isset($request_source['join_query_identity']) ? sanitize_text_field(wp_unslash((string) $request_source['join_query_identity'])) : '';
    $raw_name = isset($request_source['join_query_name']) ? sanitize_text_field(wp_unslash((string) $request_source['join_query_name'])) : '';
    $raw_qq = isset($request_source['join_query_qq']) ? sanitize_text_field(wp_unslash((string) $request_source['join_query_qq'])) : '';
    $raw_email = isset($request_source['join_query_email']) ? sanitize_text_field(wp_unslash((string) $request_source['join_query_email'])) : '';
    $raw_student_id = isset($request_source['join_query_student_id']) ? sanitize_text_field(wp_unslash((string) $request_source['join_query_student_id'])) : '';

    if (trim($raw_identity) === '') {
        foreach (array($raw_name, $raw_qq, $raw_email, $raw_student_id) as $legacy_value) {
            $legacy_value = trim((string) $legacy_value);
            if ($legacy_value !== '') {
                $raw_identity = $legacy_value;
                break;
            }
        }
    }

    $detected_identity = itstudio_join_detect_progress_query_identity($raw_identity);

    $query = array(
        'name' => '',
        'qq' => '',
        'email' => '',
        'student_id' => '',
    );
    if ($detected_identity['field'] !== '') {
        $query[$detected_identity['field']] = (string) $detected_identity['value'];
    }

    $has_input_value = trim((string) $raw_identity) !== '';
    $has_query_value = $detected_identity['field'] !== '';

    $submitted = isset($request_source['join_progress_lookup']) || $has_input_value;
    $response = array(
        'submitted' => $submitted,
        'has_query' => $has_query_value,
        'identity' => $raw_identity,
        'identity_field' => (string) ($detected_identity['field'] ?? ''),
        'name' => $raw_name,
        'qq' => $raw_qq,
        'email' => $raw_email,
        'student_id' => $raw_student_id,
        'message_cn' => '',
        'message_en' => '',
        'tone' => 'info',
    );

    if (!$submitted) {
        return $response;
    }

    if (!$has_input_value) {
        $response['message_cn'] = '请输入姓名（中文）/ QQ / 邮箱 / 学号。';
        $response['message_en'] = 'Please enter Name (Chinese) / QQ / Email / Student ID.';
        $response['tone'] = 'warning';
        return $response;
    }

    if (!$has_query_value) {
        $response['message_cn'] = '无法识别输入内容，请输入姓名（中文）/ QQ / 邮箱 / 学号。';
        $response['message_en'] = 'Input cannot be recognized. Please enter Name (Chinese) / QQ / Email / Student ID.';
        $response['tone'] = 'warning';
        return $response;
    }

    $status_registration = itstudio_join_get_stage_status_by_key($runtime, 'registration');
    $status_first = itstudio_join_get_stage_status_by_key($runtime, 'first_interview');
    $status_assessment = itstudio_join_get_stage_status_by_key($runtime, 'assessment');
    $status_second = itstudio_join_get_stage_status_by_key($runtime, 'second_interview');
    $status_notice = itstudio_join_get_stage_status_by_key($runtime, 'public_notice');

    $uploaded_first = itstudio_join_has_uploaded_result_for_stage($settings, 'first_interview');
    $uploaded_assessment = itstudio_join_has_uploaded_result_for_stage($settings, 'assessment');
    $uploaded_second = itstudio_join_has_uploaded_result_for_stage($settings, 'second_interview');
    $uploaded_notice = itstudio_join_has_uploaded_result_for_stage($settings, 'public_notice');

    if ($status_registration === 'active' || $status_registration === 'upcoming') {
        $response['message_cn'] = '报名阶段暂不开放进度查询。';
        $response['message_en'] = 'Progress lookup is not available during registration stage.';
        $response['tone'] = 'success';
        return $response;
    }
    if ($status_first === 'upcoming' || $status_first === 'pending' || $status_first === 'active') {
        $response['message_cn'] = '当前暂不可查询，请等待第一次面试结束后查看结果。';
        $response['message_en'] = 'Lookup is not available yet. Please wait until Interview I results are published.';
        $response['tone'] = 'warning';
        return $response;
    }

    if ($status_first === 'completed') {
        if (!$uploaded_first) {
            $response['message_cn'] = '第一次面试已结束，结果尚未上传。';
            $response['message_en'] = 'Interview I has ended, but results are not uploaded yet.';
            $response['tone'] = 'warning';
            return $response;
        }

        $passed_first = itstudio_join_find_record_in_stage_results($settings, 'first_interview', $query);
        if (!$passed_first) {
            $response['message_cn'] = '未通过第一次面试，期待来年再次报名。';
            $response['message_en'] = 'You did not pass Interview I. Welcome to apply again next year.';
            $response['tone'] = 'error';
            return $response;
        }

        if ($status_assessment === 'upcoming' || $status_assessment === 'pending' || $status_assessment === 'active') {
            $response['message_cn'] = '恭喜，您已通过第一次面试。';
            $response['message_en'] = 'Congratulations! You have passed Interview I.';
            $response['tone'] = 'success';
            return $response;
        }
    }

    if ($status_assessment === 'completed') {
        if (!$uploaded_assessment) {
            $response['message_cn'] = '国庆能力摸底已结束，结果尚未上传。';
            $response['message_en'] = 'Assessment stage has ended, but results are not uploaded yet.';
            $response['tone'] = 'warning';
            return $response;
        }

        $passed_assessment = itstudio_join_find_record_in_stage_results($settings, 'assessment', $query);
        if (!$passed_assessment) {
            $response['message_cn'] = '未通过国庆能力摸底，期待来年再次报名。';
            $response['message_en'] = 'You did not pass the assessment stage. Welcome to apply again next year.';
            $response['tone'] = 'error';
            return $response;
        }

        if ($status_second === 'upcoming' || $status_second === 'pending' || $status_second === 'active') {
            $response['message_cn'] = '恭喜，您已通过国庆能力摸底。';
            $response['message_en'] = 'Congratulations! You have passed the assessment stage.';
            $response['tone'] = 'success';
            return $response;
        }
    }

    if ($status_second === 'completed') {
        if (!$uploaded_second) {
            $response['message_cn'] = '第二次面试已结束，结果尚未上传。';
            $response['message_en'] = 'Interview II has ended, but results are not uploaded yet.';
            $response['tone'] = 'warning';
            return $response;
        }

        $passed_second = itstudio_join_find_record_in_stage_results($settings, 'second_interview', $query);
        if (!$passed_second) {
            $response['message_cn'] = '未通过第二次面试，期待来年再次报名。';
            $response['message_en'] = 'You did not pass Interview II. Welcome to apply again next year.';
            $response['tone'] = 'error';
            return $response;
        }

        if ($status_notice === 'pending' || $status_notice === 'upcoming' || $status_notice === 'active') {
            if ($status_notice === 'active' && $uploaded_notice) {
                $admitted = itstudio_join_find_record_in_stage_results($settings, 'public_notice', $query);
                if ($admitted) {
                    $response['message_cn'] = '恭喜，您已被录取。';
                    $response['message_en'] = 'Congratulations! You have been admitted.';
                    $response['tone'] = 'success';
                    return $response;
                }

                $response['message_cn'] = '很遗憾，您未被录取，期待来年再次报名。';
                $response['message_en'] = 'Sorry, you were not admitted. Welcome to apply again next year.';
                $response['tone'] = 'error';
                return $response;
            }

            $response['message_cn'] = '恭喜，您已通过第二次面试，请等待录取结果公布。';
            $response['message_en'] = 'Congratulations! You have passed Interview II. Please wait for the final result.';
            $response['tone'] = 'success';
            return $response;
        }
    }

    if (($status_notice === 'active' || $status_notice === 'completed') && $uploaded_notice) {
        $admitted = itstudio_join_find_record_in_stage_results($settings, 'public_notice', $query);
        if ($admitted) {
            $response['message_cn'] = '恭喜，您已被录取。';
            $response['message_en'] = 'Congratulations! You have been admitted.';
            $response['tone'] = 'success';
        } else {
            $response['message_cn'] = '很遗憾，您未被录取，期待来年再次报名。';
            $response['message_en'] = 'Sorry, you were not admitted. Welcome to apply again next year.';
            $response['tone'] = 'error';
        }
        return $response;
    }

    $response['message_cn'] = '已报名。';
    $response['message_en'] = 'Registered.';
    $response['tone'] = 'success';
    return $response;
}

function itstudio_join_datetime_to_ms($date) {
    if (!($date instanceof DateTimeImmutable)) {
        return null;
    }

    return (int) $date->format('U') * 1000;
}

function itstudio_join_resolve_stage_status($now, $start, $end) {
    if (!($now instanceof DateTimeImmutable)) {
        return 'pending';
    }

    if (!($start instanceof DateTimeImmutable) && !($end instanceof DateTimeImmutable)) {
        return 'pending';
    }

    if ($start instanceof DateTimeImmutable && $now < $start) {
        return 'upcoming';
    }

    if ($start instanceof DateTimeImmutable && $end instanceof DateTimeImmutable) {
        if ($now >= $start && $now <= $end) {
            return 'active';
        }
        if ($now > $end) {
            return 'completed';
        }
    }

    if ($start instanceof DateTimeImmutable && !($end instanceof DateTimeImmutable)) {
        if ($now >= $start) {
            return 'active';
        }
    }

    if (!($start instanceof DateTimeImmutable) && $end instanceof DateTimeImmutable) {
        return $now <= $end ? 'active' : 'completed';
    }

    return 'upcoming';
}

function itstudio_join_is_in_window($now, $start, $end) {
    if (!($now instanceof DateTimeImmutable) || !($start instanceof DateTimeImmutable)) {
        return false;
    }

    if ($now < $start) {
        return false;
    }

    if ($end instanceof DateTimeImmutable && $now > $end) {
        return false;
    }

    return true;
}

function itstudio_join_format_stage_range($start, $end, $all_day = false) {
    if (!($start instanceof DateTimeImmutable) && !($end instanceof DateTimeImmutable)) {
        return array(
            'cn' => '时间待定',
            'en' => 'Schedule TBA',
        );
    }

    $format_cn_day = 'Y.m.d';
    $format_cn_full = 'Y.m.d H:i';
    $format_en_day = 'M j, Y';
    $format_en_full = 'M j, Y H:i';

    if ($start instanceof DateTimeImmutable && !($end instanceof DateTimeImmutable)) {
        return array(
            'cn' => $all_day ? $start->format($format_cn_day) : $start->format($format_cn_full),
            'en' => $all_day ? $start->format($format_en_day) : $start->format($format_en_full),
        );
    }

    if (!($start instanceof DateTimeImmutable) && $end instanceof DateTimeImmutable) {
        return array(
            'cn' => $all_day ? $end->format($format_cn_day) : $end->format($format_cn_full),
            'en' => $all_day ? $end->format($format_en_day) : $end->format($format_en_full),
        );
    }

    if (!($start instanceof DateTimeImmutable) || !($end instanceof DateTimeImmutable)) {
        return array(
            'cn' => '时间待定',
            'en' => 'Schedule TBA',
        );
    }

    $start_cn = $all_day ? $start->format($format_cn_day) : $start->format($format_cn_full);
    $end_cn = $all_day ? $end->format($format_cn_day) : $end->format($format_cn_full);
    $start_en = $all_day ? $start->format($format_en_day) : $start->format($format_en_full);
    $end_en = $all_day ? $end->format($format_en_day) : $end->format($format_en_full);

    if ($start_cn === $end_cn) {
        return array(
            'cn' => $start_cn,
            'en' => $start_en,
        );
    }

    return array(
        'cn' => $start_cn . ' - ' . $end_cn,
        'en' => $start_en . ' - ' . $end_en,
    );
}

function itstudio_join_get_stage_photo_url($settings, $stage_key) {
    $settings = is_array($settings) ? $settings : array();
    $field_map = itstudio_join_get_photo_field_map();
    $field_key = isset($field_map[$stage_key]) ? $field_map[$stage_key] : '';

    $attachment_id = 0;
    if ($field_key !== '' && isset($settings[$field_key])) {
        $attachment_id = absint($settings[$field_key]);
    }

    if ($attachment_id > 0) {
        $photo_url = wp_get_attachment_image_url($attachment_id, 'full');
        if (is_string($photo_url) && $photo_url !== '') {
            return $photo_url;
        }
    }

    return '';
}

function itstudio_join_get_runtime_data() {
    static $cached = null;
    if (is_array($cached)) {
        return $cached;
    }

    $settings = itstudio_join_get_settings();
    $timezone = wp_timezone();
    $now = new DateTimeImmutable('now', $timezone);

    $registration_start = itstudio_join_parse_datetime_local($settings['registration_start'], false);
    $registration_end = itstudio_join_parse_datetime_local($settings['registration_end'], true);
    if ($registration_start instanceof DateTimeImmutable) {
        $registration_start = $registration_start->setTime(0, 0, 0);
    }
    if ($registration_end instanceof DateTimeImmutable) {
        $registration_end = $registration_end->setTime(23, 59, 59);
    }
    if ($registration_start instanceof DateTimeImmutable && $registration_end instanceof DateTimeImmutable && $registration_end < $registration_start) {
        $registration_end = $registration_start;
    }

    $first_interview_start_raw = isset($settings['first_interview_date']) ? (string) $settings['first_interview_date'] : '';
    $first_interview_end_raw = isset($settings['first_interview_end']) ? (string) $settings['first_interview_end'] : '';
    $first_interview_start = itstudio_join_parse_datetime_local($first_interview_start_raw, false);
    $first_interview_end = itstudio_join_parse_datetime_local($first_interview_end_raw, true);
    if ($first_interview_start instanceof DateTimeImmutable && !($first_interview_end instanceof DateTimeImmutable)) {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($first_interview_start_raw))) {
            $first_interview_end = $first_interview_start->setTime(23, 59, 59);
        } else {
            $first_interview_end = $first_interview_start;
        }
    } elseif (!($first_interview_start instanceof DateTimeImmutable) && $first_interview_end instanceof DateTimeImmutable) {
        $first_interview_start = $first_interview_end;
    } elseif ($first_interview_start instanceof DateTimeImmutable && $first_interview_end instanceof DateTimeImmutable && $first_interview_end < $first_interview_start) {
        $first_interview_end = $first_interview_start;
    }

    $second_interview_start_raw = isset($settings['second_interview_date']) ? (string) $settings['second_interview_date'] : '';
    $second_interview_end_raw = isset($settings['second_interview_end']) ? (string) $settings['second_interview_end'] : '';
    $second_interview_start = itstudio_join_parse_datetime_local($second_interview_start_raw, false);
    $second_interview_end = itstudio_join_parse_datetime_local($second_interview_end_raw, true);
    if ($second_interview_start instanceof DateTimeImmutable && !($second_interview_end instanceof DateTimeImmutable)) {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($second_interview_start_raw))) {
            $second_interview_end = $second_interview_start->setTime(23, 59, 59);
        } else {
            $second_interview_end = $second_interview_start;
        }
    } elseif (!($second_interview_start instanceof DateTimeImmutable) && $second_interview_end instanceof DateTimeImmutable) {
        $second_interview_start = $second_interview_end;
    } elseif ($second_interview_start instanceof DateTimeImmutable && $second_interview_end instanceof DateTimeImmutable && $second_interview_end < $second_interview_start) {
        $second_interview_end = $second_interview_start;
    }

    $first_interview_location_cn = trim((string) ($settings['first_interview_location_cn'] ?? ''));
    $first_interview_location_en = trim((string) ($settings['first_interview_location_en'] ?? ''));
    if ($first_interview_location_en === '') {
        $first_interview_location_en = $first_interview_location_cn;
    }

    $second_interview_location_cn = trim((string) ($settings['second_interview_location_cn'] ?? ''));
    $second_interview_location_en = trim((string) ($settings['second_interview_location_en'] ?? ''));
    if ($second_interview_location_en === '') {
        $second_interview_location_en = $second_interview_location_cn;
    }

    $notice_start_day = itstudio_join_parse_date($settings['notice_start_date']);
    $notice_start = $notice_start_day instanceof DateTimeImmutable ? $notice_start_day->setTime(0, 0, 0) : null;
    $notice_end = $notice_start instanceof DateTimeImmutable ? $notice_start->modify('+6 days')->setTime(23, 59, 59) : null;

    $recruitment_year = null;
    if ($registration_start instanceof DateTimeImmutable) {
        $recruitment_year = (int) $registration_start->format('Y');
    } elseif ($notice_start instanceof DateTimeImmutable) {
        $recruitment_year = (int) $notice_start->format('Y');
    } else {
        $recruitment_year = (int) $now->format('Y');
    }

    $default_assessment_start = DateTimeImmutable::createFromFormat('!Y-m-d', sprintf('%04d-10-01', $recruitment_year), $timezone);
    $default_assessment_end_base = DateTimeImmutable::createFromFormat('!Y-m-d', sprintf('%04d-10-07', $recruitment_year), $timezone);
    $default_assessment_end = $default_assessment_end_base instanceof DateTimeImmutable ? $default_assessment_end_base->setTime(23, 59, 59) : null;

    $assessment_start_day = itstudio_join_parse_date($settings['assessment_start_date']);
    $assessment_end_day = itstudio_join_parse_date($settings['assessment_end_date']);
    $assessment_start = $assessment_start_day instanceof DateTimeImmutable
        ? $assessment_start_day->setTime(0, 0, 0)
        : $default_assessment_start;
    $assessment_end = $assessment_end_day instanceof DateTimeImmutable
        ? $assessment_end_day->setTime(23, 59, 59)
        : $default_assessment_end;

    if ($assessment_start_day instanceof DateTimeImmutable && !($assessment_end_day instanceof DateTimeImmutable)) {
        $assessment_end = $assessment_start->setTime(23, 59, 59);
    } elseif (!($assessment_start_day instanceof DateTimeImmutable) && $assessment_end_day instanceof DateTimeImmutable) {
        $assessment_start = $assessment_end_day->setTime(0, 0, 0);
    }
    if ($assessment_start instanceof DateTimeImmutable && $assessment_end instanceof DateTimeImmutable && $assessment_end < $assessment_start) {
        $assessment_end = $assessment_start->setTime(23, 59, 59);
    }

    $stage_seed = array(
        array(
            'key' => 'registration',
            'label_cn' => '报名阶段',
            'label_en' => 'Registration',
            'short_cn' => '报名',
            'short_en' => 'Reg',
            'start' => $registration_start,
            'end' => $registration_end,
            'all_day' => true,
            'location_cn' => '',
            'location_en' => '',
            'result_uploaded' => false,
        ),
        array(
            'key' => 'first_interview',
            'label_cn' => '第一次面试',
            'label_en' => 'Interview I',
            'short_cn' => '一面',
            'short_en' => 'I-1',
            'start' => $first_interview_start,
            'end' => $first_interview_end,
            'all_day' => false,
            'location_cn' => $first_interview_location_cn,
            'location_en' => $first_interview_location_en,
            'result_uploaded' => itstudio_join_has_uploaded_result_for_stage($settings, 'first_interview'),
        ),
        array(
            'key' => 'assessment',
            'label_cn' => '国庆能力摸底',
            'label_en' => 'Assessment',
            'short_cn' => '摸底',
            'short_en' => 'Assess',
            'start' => $assessment_start,
            'end' => $assessment_end,
            'all_day' => true,
            'location_cn' => '',
            'location_en' => '',
            'result_uploaded' => itstudio_join_has_uploaded_result_for_stage($settings, 'assessment'),
        ),
        array(
            'key' => 'second_interview',
            'label_cn' => '第二次面试',
            'label_en' => 'Interview II',
            'short_cn' => '二面',
            'short_en' => 'I-2',
            'start' => $second_interview_start,
            'end' => $second_interview_end,
            'all_day' => false,
            'location_cn' => $second_interview_location_cn,
            'location_en' => $second_interview_location_en,
            'result_uploaded' => itstudio_join_has_uploaded_result_for_stage($settings, 'second_interview'),
        ),
        array(
            'key' => 'public_notice',
            'label_cn' => '录取结果公布',
            'label_en' => 'Public Notice',
            'short_cn' => '公布',
            'short_en' => 'Notice',
            'start' => $notice_start,
            'end' => $notice_end,
            'all_day' => true,
            'location_cn' => '',
            'location_en' => '',
            'result_uploaded' => itstudio_join_has_uploaded_result_for_stage($settings, 'public_notice'),
        ),
    );

    $stages = array();
    foreach ($stage_seed as $stage) {
        $range = itstudio_join_format_stage_range($stage['start'], $stage['end'], !empty($stage['all_day']));
        $status = itstudio_join_resolve_stage_status($now, $stage['start'], $stage['end']);
        $stages[] = array(
            'key' => $stage['key'],
            'label_cn' => $stage['label_cn'],
            'label_en' => $stage['label_en'],
            'short_cn' => $stage['short_cn'],
            'short_en' => $stage['short_en'],
            'status' => $status,
            'range_cn' => $range['cn'],
            'range_en' => $range['en'],
            'location_cn' => isset($stage['location_cn']) ? (string) $stage['location_cn'] : '',
            'location_en' => isset($stage['location_en']) ? (string) $stage['location_en'] : '',
            'result_uploaded' => !empty($stage['result_uploaded']),
            'start_ts' => itstudio_join_datetime_to_ms($stage['start']),
            'end_ts' => itstudio_join_datetime_to_ms($stage['end']),
        );
    }

    $current_stage_index = -1;
    foreach ($stages as $index => $stage) {
        if ($stage['status'] === 'active') {
            $current_stage_index = (int) $index;
            break;
        }
    }

    $next_stage_index = -1;
    if ($current_stage_index < 0) {
        foreach ($stages as $index => $stage) {
            if (($stage['status'] ?? '') === 'upcoming') {
                $next_stage_index = (int) $index;
                break;
            }
        }
    }

    $current_stage_mode = 'inactive';
    $display_stage_index = -1;
    if ($current_stage_index >= 0 && isset($stages[$current_stage_index])) {
        $display_stage_index = $current_stage_index;
        $current_stage_mode = 'active';
    } elseif ($next_stage_index >= 0 && isset($stages[$next_stage_index])) {
        $display_stage_index = $next_stage_index;
        $current_stage_mode = 'next';
    }

    $current_stage = ($display_stage_index >= 0 && isset($stages[$display_stage_index]))
        ? $stages[$display_stage_index]
        : array(
            'key' => 'inactive',
            'label_cn' => '当前未在招新时段',
            'label_en' => 'Recruitment is currently closed',
            'short_cn' => '',
            'short_en' => '',
            'status' => 'inactive',
            'range_cn' => '请关注后续通知',
            'range_en' => 'Please check later updates',
            'location_cn' => '',
            'location_en' => '',
            'result_uploaded' => false,
            'start_ts' => null,
            'end_ts' => null,
        );

    $is_registration_open = itstudio_join_is_in_window($now, $registration_start, $registration_end);

    $is_query_open = false;
    $query_start = null;
    if ($registration_end instanceof DateTimeImmutable) {
        $query_start = $registration_end->modify('+1 second');
    } elseif ($first_interview_start instanceof DateTimeImmutable) {
        $query_start = $first_interview_start;
    }
    if ($query_start instanceof DateTimeImmutable) {
        if ($notice_end instanceof DateTimeImmutable) {
            $is_query_open = itstudio_join_is_in_window($now, $query_start, $notice_end);
        } else {
            $is_query_open = ($now >= $query_start);
        }
    }

    $is_notice_open = itstudio_join_is_in_window($now, $notice_start, $notice_end);
    $is_notice_finished = ($notice_end instanceof DateTimeImmutable) && ($now > $notice_end);
    $current_stage_photo_url = itstudio_join_get_stage_photo_url($settings, (string) ($current_stage['key'] ?? ''));
    if ($current_stage_photo_url === '') {
        $current_stage_photo_url = get_template_directory_uri() . '/resources/it_logo_2024.svg';
    }

    $season_start = null;
    $season_end = null;
    foreach ($stage_seed as $stage_window) {
        $stage_start = $stage_window['start'] instanceof DateTimeImmutable ? $stage_window['start'] : null;
        $stage_end = $stage_window['end'] instanceof DateTimeImmutable ? $stage_window['end'] : null;
        if (!($stage_start instanceof DateTimeImmutable) && !($stage_end instanceof DateTimeImmutable)) {
            continue;
        }

        $window_start = $stage_start instanceof DateTimeImmutable ? $stage_start : $stage_end;
        $window_end = $stage_end instanceof DateTimeImmutable ? $stage_end : $stage_start;
        if (!($window_start instanceof DateTimeImmutable) || !($window_end instanceof DateTimeImmutable)) {
            continue;
        }

        if (!($season_start instanceof DateTimeImmutable) || $window_start < $season_start) {
            $season_start = $window_start;
        }
        if (!($season_end instanceof DateTimeImmutable) || $window_end > $season_end) {
            $season_end = $window_end;
        }
    }

    $show_progress_visual = false;
    if ($season_start instanceof DateTimeImmutable && $season_end instanceof DateTimeImmutable) {
        $show_progress_visual = ($now >= $season_start && $now <= $season_end);
    } elseif ($current_stage_index >= 0) {
        $show_progress_visual = true;
    }

    $cached = array(
        'settings' => $settings,
        'timezone' => $timezone->getName(),
        'recruitment_year' => $recruitment_year,
        'now_ts' => (int) $now->format('U') * 1000,
        'stages' => $stages,
        'current_stage_index' => $current_stage_index,
        'current_stage_mode' => $current_stage_mode,
        'current_stage' => $current_stage,
        'is_registration_open' => $is_registration_open,
        'is_query_open' => $is_query_open,
        'is_notice_open' => $is_notice_open,
        'is_notice_finished' => $is_notice_finished,
        'show_progress_visual' => $show_progress_visual,
        'current_stage_photo_url' => $current_stage_photo_url,
        'query_deadline_cn' => $notice_end instanceof DateTimeImmutable ? $notice_end->format('Y-m-d H:i') : '',
        'query_deadline_en' => $notice_end instanceof DateTimeImmutable ? $notice_end->format('M j, Y H:i') : '',
    );

    return $cached;
}

function itstudio_join_get_frontend_payload() {
    $runtime = itstudio_join_get_runtime_data();
    return array(
        'nowTs' => (int) ($runtime['now_ts'] ?? 0),
        'currentStageIndex' => (int) ($runtime['current_stage_index'] ?? -1),
        'stages' => array_values(array_map(static function ($stage) {
            return array(
                'key' => (string) ($stage['key'] ?? ''),
                'labelCn' => (string) ($stage['label_cn'] ?? ''),
                'labelEn' => (string) ($stage['label_en'] ?? ''),
                'shortCn' => (string) ($stage['short_cn'] ?? ''),
                'shortEn' => (string) ($stage['short_en'] ?? ''),
                'status' => (string) ($stage['status'] ?? 'pending'),
                'rangeCn' => (string) ($stage['range_cn'] ?? ''),
                'rangeEn' => (string) ($stage['range_en'] ?? ''),
                'locationCn' => (string) ($stage['location_cn'] ?? ''),
                'locationEn' => (string) ($stage['location_en'] ?? ''),
                'resultUploaded' => !empty($stage['result_uploaded']),
                'startTs' => isset($stage['start_ts']) ? $stage['start_ts'] : null,
                'endTs' => isset($stage['end_ts']) ? $stage['end_ts'] : null,
            );
        }, (array) ($runtime['stages'] ?? array()))),
        'progressLookup' => array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'action' => 'itstudio_join_progress_lookup',
            'nonce' => wp_create_nonce('itstudio_join_progress_lookup'),
        ),
    );
}

function itstudio_join_ajax_progress_lookup() {
    if (!check_ajax_referer('itstudio_join_progress_lookup', 'nonce', false)) {
        wp_send_json_error(
            array(
                'tone' => 'error',
                'message_cn' => '请求已过期，请刷新页面后重试。',
                'message_en' => 'Request expired. Please refresh the page and try again.',
            ),
            403
        );
    }

    $runtime = itstudio_join_get_runtime_data();
    $request_source = $_POST;
    if (!isset($request_source['join_progress_lookup'])) {
        $request_source['join_progress_lookup'] = '1';
    }

    $lookup = itstudio_join_resolve_progress_lookup($runtime, $request_source);
    $tone = trim((string) ($lookup['tone'] ?? 'info'));
    if (!in_array($tone, array('success', 'warning', 'error', 'info'), true)) {
        $tone = 'info';
    }

    wp_send_json_success(
        array(
            'submitted' => !empty($lookup['submitted']),
            'has_query' => !empty($lookup['has_query']),
            'identity' => (string) ($lookup['identity'] ?? ''),
            'identity_field' => (string) ($lookup['identity_field'] ?? ''),
            'tone' => $tone,
            'message_cn' => (string) ($lookup['message_cn'] ?? ''),
            'message_en' => (string) ($lookup['message_en'] ?? ''),
        )
    );
}
add_action('wp_ajax_itstudio_join_progress_lookup', 'itstudio_join_ajax_progress_lookup');
add_action('wp_ajax_nopriv_itstudio_join_progress_lookup', 'itstudio_join_ajax_progress_lookup');

function itstudio_join_force_formidable_ajax_submit($form) {
    if (!itstudio_is_join_page_context() || !is_object($form)) {
        return $form;
    }

    if (!isset($form->options) || !is_array($form->options)) {
        $form->options = array();
    }

    $form->options['ajax_submit'] = 1;

    return $form;
}
add_filter('frm_pre_display_form', 'itstudio_join_force_formidable_ajax_submit', 20);

function itstudio_join_enqueue_assets() {
    if (!itstudio_is_join_page_context()) {
        return;
    }

    wp_enqueue_style(
        'itstudio-join-page',
        get_template_directory_uri() . '/assets/css/join-page.css',
        array('itstudio-content'),
        '1.1.1'
    );

    wp_enqueue_script(
        'itstudio-animejs',
        get_template_directory_uri() . '/assets/js/vendor/anime.min.js',
        array(),
        '3.2.2',
        true
    );

    wp_enqueue_script(
        'itstudio-join-canvas',
        get_template_directory_uri() . '/assets/js/join-canvas.js',
        array('itstudio-animejs'),
        '1.1.1',
        true
    );

    wp_localize_script('itstudio-join-canvas', 'itstudioJoinData', itstudio_join_get_frontend_payload());
}
add_action('wp_enqueue_scripts', 'itstudio_join_enqueue_assets', 30);

function itstudio_join_register_settings() {
    register_setting(
        'itstudio_join_settings_group',
        'itstudio_join_settings',
        array(
            'type' => 'array',
            'sanitize_callback' => 'itstudio_join_sanitize_settings',
            'default' => itstudio_join_get_default_settings(),
        )
    );
}
add_action('admin_init', 'itstudio_join_register_settings');

function itstudio_join_allow_result_file_mimes($mimes) {
    $mimes = is_array($mimes) ? $mimes : array();
    // 允许在媒体库上传招新结果文件。
    $mimes['csv'] = 'text/csv';
    $mimes['xlsx'] = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    return $mimes;
}
add_filter('upload_mimes', 'itstudio_join_allow_result_file_mimes');

function itstudio_join_register_settings_page() {
    add_options_page(
        '招新设置',
        '招新设置',
        'manage_options',
        'itstudio-join-settings',
        'itstudio_join_render_settings_page'
    );
}
add_action('admin_menu', 'itstudio_join_register_settings_page');

function itstudio_join_render_photo_field_row($field_key, $label, $settings) {
    $attachment_id = isset($settings[$field_key]) ? absint($settings[$field_key]) : 0;
    $preview_url = $attachment_id > 0 ? wp_get_attachment_image_url($attachment_id, 'medium_large') : '';
    ?>
    <tr>
        <th scope="row"><label><?php echo esc_html($label); ?></label></th>
        <td>
            <input type="hidden" class="itstudio-join-photo-id" name="itstudio_join_settings[<?php echo esc_attr($field_key); ?>]" value="<?php echo esc_attr($attachment_id); ?>">
            <div class="itstudio-join-photo-preview-wrap" style="margin-bottom:10px;">
                <img class="itstudio-join-photo-preview" src="<?php echo esc_url($preview_url); ?>" alt="" style="max-width:300px;height:auto;border:1px solid #dcdcde;border-radius:8px;display:<?php echo $preview_url !== '' ? 'block' : 'none'; ?>;">
            </div>
            <button type="button" class="button itstudio-join-photo-upload"><?php esc_html_e('上传 / 选择图片', 'itstudio'); ?></button>
            <button type="button" class="button-link-delete itstudio-join-photo-clear" style="margin-left:8px;<?php echo $preview_url !== '' ? '' : 'display:none;'; ?>"><?php esc_html_e('移除', 'itstudio'); ?></button>
        </td>
    </tr>
    <?php
}

function itstudio_join_render_result_records_row($field_key, $label, $settings, $description = '') {
    $value = isset($settings[$field_key]) ? (string) $settings[$field_key] : '';
    ?>
    <tr>
        <th scope="row"><label for="<?php echo esc_attr('itstudio_join_' . $field_key); ?>"><?php echo esc_html($label); ?></label></th>
        <td>
            <textarea
                id="<?php echo esc_attr('itstudio_join_' . $field_key); ?>"
                name="itstudio_join_settings[<?php echo esc_attr($field_key); ?>]"
                rows="6"
                class="large-text code"
                placeholder="姓名,QQ,邮箱,学号"
            ><?php echo esc_textarea($value); ?></textarea>
            <?php if ($description !== '') : ?>
                <p class="description"><?php echo esc_html($description); ?></p>
            <?php else : ?>
                <p class="description">每行一条记录，格式：姓名,QQ,邮箱,学号。可用逗号、中文逗号、竖线或 Tab 分隔。</p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}

function itstudio_join_render_text_setting_row($field_key, $label, $settings, $description = '', $placeholder = '') {
    $value = isset($settings[$field_key]) ? (string) $settings[$field_key] : '';
    ?>
    <tr>
        <th scope="row"><label for="<?php echo esc_attr('itstudio_join_' . $field_key); ?>"><?php echo esc_html($label); ?></label></th>
        <td>
            <input
                type="text"
                id="<?php echo esc_attr('itstudio_join_' . $field_key); ?>"
                name="itstudio_join_settings[<?php echo esc_attr($field_key); ?>]"
                value="<?php echo esc_attr($value); ?>"
                class="regular-text code"
                <?php if ($placeholder !== '') : ?>placeholder="<?php echo esc_attr($placeholder); ?>"<?php endif; ?>
            >
            <?php if ($description !== '') : ?>
                <p class="description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}

function itstudio_join_render_result_file_row($field_key, $label, $settings, $description = '') {
    $attachment_id = isset($settings[$field_key]) ? absint($settings[$field_key]) : 0;
    $file_url = $attachment_id > 0 ? wp_get_attachment_url($attachment_id) : '';
    $filename = $attachment_id > 0 ? basename((string) get_attached_file($attachment_id)) : '';
    ?>
    <tr>
        <th scope="row"><label><?php echo esc_html($label); ?></label></th>
        <td>
            <input type="hidden" class="itstudio-join-result-file-id" name="itstudio_join_settings[<?php echo esc_attr($field_key); ?>]" value="<?php echo esc_attr($attachment_id); ?>">
            <div class="itstudio-join-result-file-preview" style="margin-bottom:10px;<?php echo $file_url !== '' ? '' : 'display:none;'; ?>">
                <a class="itstudio-join-result-file-link" href="<?php echo esc_url($file_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($filename !== '' ? $filename : $file_url); ?></a>
            </div>
            <button type="button" class="button itstudio-join-result-file-upload"><?php esc_html_e('上传 / 选择结果文件', 'itstudio'); ?></button>
            <button type="button" class="button-link-delete itstudio-join-result-file-clear" style="margin-left:8px;<?php echo $file_url !== '' ? '' : 'display:none;'; ?>"><?php esc_html_e('移除', 'itstudio'); ?></button>
            <?php if ($description !== '') : ?>
                <p class="description"><?php echo esc_html($description); ?></p>
            <?php else : ?>
                <p class="description">支持 CSV / XLSX。列结构：姓名,QQ,邮箱,学号,手机,是否通过（1=通过，其他=未通过）。</p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}

function itstudio_join_render_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    wp_enqueue_media();

    $settings = itstudio_join_get_settings();
    $runtime = itstudio_join_get_runtime_data();
    $formidable_active = shortcode_exists('formidable') || class_exists('FrmFormsController');
    $smtp_active = class_exists('\WPMailSMTP\WP') || defined('WPMS_PLUGIN_VER');
    ?>
    <div class="wrap">
        <h1>爱特工作室招新设置</h1>
        <p>用于配置「加入我们」页面的时间节点、阶段结果和表单。</p>

        <?php if (!$formidable_active) : ?>
            <div class="notice notice-warning inline">
                <p><strong>提示：</strong>未检测到 Formidable Forms 插件。报名表单将无法在前台渲染。</p>
            </div>
        <?php endif; ?>

        <?php if (!$smtp_active) : ?>
            <div class="notice notice-warning inline">
                <p><strong>提示：</strong>未检测到 WP Mail SMTP 插件。建议启用后再开放报名邮件通知。</p>
            </div>
        <?php endif; ?>

        <form method="post" action="options.php">
            <?php settings_fields('itstudio_join_settings_group'); ?>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row"><label for="itstudio_join_registration_start">报名开始时间</label></th>
                    <td>
                        <input type="datetime-local" id="itstudio_join_registration_start" name="itstudio_join_settings[registration_start]" value="<?php echo esc_attr(itstudio_join_to_datetime_local_input_value((string) $settings['registration_start'], false)); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_registration_end">报名结束时间</label></th>
                    <td>
                        <input type="datetime-local" id="itstudio_join_registration_end" name="itstudio_join_settings[registration_end]" value="<?php echo esc_attr(itstudio_join_to_datetime_local_input_value((string) $settings['registration_end'], true)); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_first_interview_date">第一次面试开始时间</label></th>
                    <td>
                        <input type="datetime-local" id="itstudio_join_first_interview_date" name="itstudio_join_settings[first_interview_date]" value="<?php echo esc_attr(itstudio_join_to_datetime_local_input_value((string) $settings['first_interview_date'], false)); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_first_interview_end">第一次面试结束时间</label></th>
                    <td>
                        <input type="datetime-local" id="itstudio_join_first_interview_end" name="itstudio_join_settings[first_interview_end]" value="<?php echo esc_attr(itstudio_join_to_datetime_local_input_value((string) $settings['first_interview_end'], true)); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_first_interview_location_cn">第一次面试地点（中文）</label></th>
                    <td>
                        <input type="text" id="itstudio_join_first_interview_location_cn" name="itstudio_join_settings[first_interview_location_cn]" value="<?php echo esc_attr((string) ($settings['first_interview_location_cn'] ?? '')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_first_interview_location_en">第一次面试地点（英文）</label></th>
                    <td>
                        <input type="text" id="itstudio_join_first_interview_location_en" name="itstudio_join_settings[first_interview_location_en]" value="<?php echo esc_attr((string) ($settings['first_interview_location_en'] ?? '')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_second_interview_date">第二次面试开始时间</label></th>
                    <td>
                        <input type="datetime-local" id="itstudio_join_second_interview_date" name="itstudio_join_settings[second_interview_date]" value="<?php echo esc_attr(itstudio_join_to_datetime_local_input_value((string) $settings['second_interview_date'], false)); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_second_interview_end">第二次面试结束时间</label></th>
                    <td>
                        <input type="datetime-local" id="itstudio_join_second_interview_end" name="itstudio_join_settings[second_interview_end]" value="<?php echo esc_attr(itstudio_join_to_datetime_local_input_value((string) $settings['second_interview_end'], true)); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_second_interview_location_cn">第二次面试地点（中文）</label></th>
                    <td>
                        <input type="text" id="itstudio_join_second_interview_location_cn" name="itstudio_join_settings[second_interview_location_cn]" value="<?php echo esc_attr((string) ($settings['second_interview_location_cn'] ?? '')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_second_interview_location_en">第二次面试地点（英文）</label></th>
                    <td>
                        <input type="text" id="itstudio_join_second_interview_location_en" name="itstudio_join_settings[second_interview_location_en]" value="<?php echo esc_attr((string) ($settings['second_interview_location_en'] ?? '')); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_assessment_start_date">国庆能力摸底开始日期（调试）</label></th>
                    <td>
                        <input type="date" id="itstudio_join_assessment_start_date" name="itstudio_join_settings[assessment_start_date]" value="<?php echo esc_attr(itstudio_join_to_date_input_value((string) ($settings['assessment_start_date'] ?? ''))); ?>" class="regular-text">
                        <p class="description">留空时默认使用每年 10 月 1 日。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_assessment_end_date">国庆能力摸底结束日期（调试）</label></th>
                    <td>
                        <input type="date" id="itstudio_join_assessment_end_date" name="itstudio_join_settings[assessment_end_date]" value="<?php echo esc_attr(itstudio_join_to_date_input_value((string) ($settings['assessment_end_date'] ?? ''))); ?>" class="regular-text">
                        <p class="description">留空时默认使用每年 10 月 7 日。</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_notice_start_date">录取结果公布开始日期</label></th>
                    <td>
                        <input type="date" id="itstudio_join_notice_start_date" name="itstudio_join_settings[notice_start_date]" value="<?php echo esc_attr(itstudio_join_to_date_input_value((string) $settings['notice_start_date'])); ?>" class="regular-text">
                        <p class="description">公布会自动持续 7 天（开始日 + 后续 6 天）。</p>
                    </td>
                </tr>
                <?php itstudio_join_render_photo_field_row('photo_registration', '报名阶段图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_first_interview', '第一次面试图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_assessment', '国庆能力摸底图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_second_interview', '第二次面试图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_public_notice', '录取结果公布阶段图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_inactive', '非招新时段图片', $settings); ?>
                <tr>
                    <th scope="row"><label>结果数据来源</label></th>
                    <td>
                        <input type="hidden" name="itstudio_join_settings[result_data_source]" value="file">
                        <strong>CSV / XLSX 文件上传</strong>
                        <p class="description">上传第一次面试、国庆能力摸底、第二次面试、录取结果四个阶段文件，系统按“姓名,QQ,邮箱,学号,手机,是否通过”进行查询。最后一列为 1 视为通过，否则未通过。</p>
                    </td>
                </tr>
                <?php itstudio_join_render_result_file_row('result_first_interview_file', '第一次面试结果文件', $settings); ?>
                <?php itstudio_join_render_result_file_row('result_assessment_file', '国庆能力摸底结果文件', $settings); ?>
                <?php itstudio_join_render_result_file_row('result_second_interview_file', '第二次面试结果文件', $settings); ?>
                <?php itstudio_join_render_result_file_row('result_admission_file', '录取结果文件', $settings); ?>
                <tr>
                    <th scope="row"><label for="itstudio_join_signup_shortcode">报名表单 Shortcode</label></th>
                    <td>
                        <input type="text" id="itstudio_join_signup_shortcode" name="itstudio_join_settings[signup_form_shortcode]" value="<?php echo esc_attr((string) $settings['signup_form_shortcode']); ?>" class="regular-text code">
                        <p class="description">示例：<code>[formidable id="12"]</code></p>
                    </td>
                </tr>
            </table>
            <?php submit_button('保存设置'); ?>
        </form>

        <div class="notice notice-info" style="padding:12px 14px; margin: 16px 0;">
            <h2 style="margin:0 0 8px;">阶段结果文件使用说明</h2>
            <ol style="margin:0 0 0 18px;">
                <li>上传四个阶段结果文件：第一次面试、国庆能力摸底、第二次面试、录取结果（报名阶段无需结果文件）。</li>
                <li>文件列顺序固定为：<code>姓名,QQ,邮箱,学号,手机,是否通过</code>。</li>
                <li><strong>是否通过</strong> 字段：<code>1</code> 表示通过；其他任意值（含 <code>0</code>、空）都按未通过处理。</li>
                <li>前台用户可使用姓名、QQ、邮箱、学号进行查询，系统会按当前阶段自动返回对应进度。</li>
            </ol>
        </div>
        <hr>
        <h2>阶段预览</h2>
        <p>国庆能力摸底默认固定为每年 10 月 1 日至 10 月 7 日；如填写上方“摸底开始/结束日期（调试）”则优先使用调试时间，留空则恢复默认固定窗口。</p>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>阶段</th>
                    <th>时间</th>
                    <th>状态</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ((array) ($runtime['stages'] ?? array()) as $stage) : ?>
                    <?php
                    $status = (string) ($stage['status'] ?? 'pending');
                    $status_label = '待设置';
                    if ($status === 'completed') {
                        $status_label = '已完成';
                    } elseif ($status === 'active') {
                        $status_label = '进行中';
                    } elseif ($status === 'upcoming') {
                        $status_label = '未开始';
                    }
                    ?>
                    <tr>
                        <td><?php echo esc_html((string) ($stage['label_cn'] ?? '')); ?></td>
                        <td><?php echo esc_html((string) ($stage['range_cn'] ?? '')); ?></td>
                        <td><?php echo esc_html($status_label); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <script>
            (function () {
                const uploadButtons = document.querySelectorAll('.itstudio-join-photo-upload');
                if (!uploadButtons.length) {
                    return;
                }

                uploadButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        if (!window.wp || !wp.media) {
                            return;
                        }

                        const row = button.closest('td');
                        if (!row) {
                            return;
                        }

                        const input = row.querySelector('.itstudio-join-photo-id');
                        const preview = row.querySelector('.itstudio-join-photo-preview');
                        const clearBtn = row.querySelector('.itstudio-join-photo-clear');
                        if (!input || !preview) {
                            return;
                        }

                        const frame = wp.media({
                            title: '选择阶段图片',
                            button: { text: '使用此图片' },
                            multiple: false,
                            library: { type: 'image' },
                        });

                        frame.on('select', () => {
                            const selection = frame.state().get('selection').first();
                            if (!selection) {
                                return;
                            }
                            const data = selection.toJSON();
                            const imageUrl = (data.sizes && data.sizes.medium_large && data.sizes.medium_large.url)
                                ? data.sizes.medium_large.url
                                : data.url;
                            input.value = data.id || '';
                            preview.src = imageUrl || '';
                            preview.style.display = imageUrl ? 'block' : 'none';
                            if (clearBtn) {
                                clearBtn.style.display = imageUrl ? 'inline' : 'none';
                            }
                        });

                        frame.open();
                    });
                });

                document.querySelectorAll('.itstudio-join-photo-clear').forEach((button) => {
                    button.addEventListener('click', () => {
                        const row = button.closest('td');
                        if (!row) {
                            return;
                        }
                        const input = row.querySelector('.itstudio-join-photo-id');
                        const preview = row.querySelector('.itstudio-join-photo-preview');
                        if (input) {
                            input.value = '';
                        }
                        if (preview) {
                            preview.src = '';
                            preview.style.display = 'none';
                        }
                        button.style.display = 'none';
                    });
                });

                const resultFileButtons = document.querySelectorAll('.itstudio-join-result-file-upload');
                resultFileButtons.forEach((button) => {
                    button.addEventListener('click', () => {
                        if (!window.wp || !wp.media) {
                            return;
                        }

                        const row = button.closest('td');
                        if (!row) {
                            return;
                        }

                        const input = row.querySelector('.itstudio-join-result-file-id');
                        const preview = row.querySelector('.itstudio-join-result-file-preview');
                        const link = row.querySelector('.itstudio-join-result-file-link');
                        const clearBtn = row.querySelector('.itstudio-join-result-file-clear');
                        if (!input || !preview || !link) {
                            return;
                        }

                        const frame = wp.media({
                            title: '选择结果文件',
                            button: { text: '使用此文件' },
                            multiple: false,
                        });

                        frame.on('select', () => {
                            const selection = frame.state().get('selection').first();
                            if (!selection) {
                                return;
                            }
                            const data = selection.toJSON();
                            const fileUrl = data.url || '';
                            const filename = data.filename || (fileUrl ? fileUrl.split('/').pop() : '');
                            const ext = filename.includes('.') ? filename.split('.').pop().toLowerCase() : '';
                            if (!['csv', 'xlsx'].includes(ext)) {
                                window.alert('请上传 CSV 或 XLSX 文件。');
                                return;
                            }

                            input.value = data.id || '';
                            link.href = fileUrl || '#';
                            link.textContent = filename || fileUrl || '';
                            preview.style.display = fileUrl ? 'block' : 'none';
                            if (clearBtn) {
                                clearBtn.style.display = fileUrl ? 'inline' : 'none';
                            }
                        });

                        frame.open();
                    });
                });

                document.querySelectorAll('.itstudio-join-result-file-clear').forEach((button) => {
                    button.addEventListener('click', () => {
                        const row = button.closest('td');
                        if (!row) {
                            return;
                        }
                        const input = row.querySelector('.itstudio-join-result-file-id');
                        const preview = row.querySelector('.itstudio-join-result-file-preview');
                        const link = row.querySelector('.itstudio-join-result-file-link');
                        if (input) {
                            input.value = '';
                        }
                        if (link) {
                            link.href = '#';
                            link.textContent = '';
                        }
                        if (preview) {
                            preview.style.display = 'none';
                        }
                        button.style.display = 'none';
                    });
                });
            })();
        </script>
    </div>
    <?php
}

function itstudio_join_fallback() {
    if (!is_404()) {
        return;
    }

    global $wp;
    $request = isset($wp->request) ? trim((string) $wp->request, '/') : '';
    if ($request !== 'join') {
        return;
    }

    $template = locate_template('page-join.php');
    if (!$template) {
        return;
    }

    global $wp_query;
    if ($wp_query) {
        $wp_query->is_404 = false;
        $wp_query->is_page = true;
        $wp_query->is_singular = true;
        $virtual_post = new WP_Post((object) array(
            'ID' => 0,
            'post_type' => 'page',
            'post_parent' => 0,
            'post_title' => '加入我们',
            'post_status' => 'publish',
            'post_name' => 'join',
            'post_content' => '',
        ));
        $wp_query->post = $virtual_post;
        $wp_query->posts = array($virtual_post);
        $wp_query->queried_object = $virtual_post;
        $wp_query->queried_object_id = 0;
        $wp_query->post_count = 1;
        $wp_query->found_posts = 1;
        $wp_query->max_num_pages = 1;
        global $post;
        $post = $virtual_post;
        setup_postdata($post);
    }

    add_filter('document_title_parts', static function ($parts) {
        $parts['title'] = '加入我们';
        return $parts;
    });

    status_header(200);
    nocache_headers();
    include $template;
    exit;
}
add_action('template_redirect', 'itstudio_join_fallback', 9);
