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
    $post_types = array('post', 'page', 'announcement', 'news');
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
function itstudio_disable_default_site_icon() {
    remove_action('wp_head', 'wp_site_icon', 99);
    remove_action('admin_head', 'wp_site_icon', 99);
    remove_action('login_head', 'wp_site_icon', 99);
}
add_action('init', 'itstudio_disable_default_site_icon');
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
        wp_enqueue_script('itstudio-about-hero-waves', get_template_directory_uri() . '/assets/js/hero-waves.js', array(), '1.0.0', true);
        wp_enqueue_script('itstudio-about-hero', get_template_directory_uri() . '/assets/js/home-hero.js', array(), '1.0.0', true);
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
}
add_action('acf/init', 'itstudio_register_acf_fields');

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
