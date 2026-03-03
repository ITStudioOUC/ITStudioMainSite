<?php

function itstudio_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('automatic-feed-links');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'itstudio'),
    ));

    load_theme_textdomain('itstudio', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'itstudio_theme_setup');

function itstudio_apply_site_identity() {
    $site_name = base64_decode('54ix54m55bel5L2c5a6k');
    $site_tagline = base64_decode('54ix54m55bel5L2c5a6k5a6Y5pa5572R56uZ');

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
        $parts['title'] = html_entity_decode('&#20844;&#21578;&#36890;&#30693;', ENT_QUOTES, 'UTF-8');
    } elseif (is_post_type_archive('news')) {
        $parts['title'] = html_entity_decode('&#31038;&#22242;&#26032;&#38395;', ENT_QUOTES, 'UTF-8');
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

/**
 * GitHub 风格评论
 */
function itstudio_comment_callback($comment, $args, $depth) {
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class('gh-comment-item'); ?>>
        <div class="gh-comment-avatar">
            <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['avatar_size']); ?>
        </div>
        <div class="gh-comment-box">
            <div class="gh-comment-header">
                <div class="gh-comment-meta">
                    <span class="gh-author-name"><?php echo get_comment_author_link(); ?></span>
                    <!-- 双语支持: commented on / 评论于 -->
                    <span class="gh-action-text" data-cn="评论于" data-en="commented on"></span>
                    <a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                                // 处理时间双语
                                $time_diff = human_time_diff(get_comment_time('U'), current_time('timestamp'));
                                // 简单的替换逻辑，或者直接输出两个 span
                                printf(
                                    '<span data-cn="%s前" data-en="%s ago"></span>',
                                    $time_diff, // 中文环境通常也是数字+单位(如 5 分钟)，这里简化处理，假设 time_diff 本身已本地化或接受英文
                                    $time_diff
                                );
                                // 注意：WP的 human_time_diff 返回的是翻译后的字符串（如果后台是中文），
                                // 要实现完美的前端双语切换，需要一种不依赖后台语言设置的方式，或者接受后台返回当前语言的时间。
                                // 鉴于切换是纯前端的，最佳方式是让 PHP 输出特定格式，前端解析，但这里为了简单，
                                // 我们假设 time_diff 主要是数字+单位。
                                // 更稳妥的方式是直接显示标准日期格式，或者接受当前状态。
                                // 这里先简单处理结构。
                            ?>
                        </time>
                    </a>
                    <?php
                    // Author Badge
                    $post = get_post();
                    if ($comment->user_id === $post->post_author) {
                        // 双语支持: Author / 作者
                        echo '<span class="gh-badge author" data-cn="作者" data-en="Author"></span>';
                    }
                    ?>
                </div>
                <div class="gh-header-actions">
                    <?php edit_comment_link('', '', '', null, 'gh-edit-link'); /* 获取链接URL逻辑较复杂，这里直接用 edit_comment_link 输出，但内容需要自定义 */ ?>
                    <span class="edit-link-wrapper">
                        <?php
                        // 为了支持双语，我们不仅需要 URL，还需要在这里手动构造 A 标签，或者利用 PHP 里的 filter
                        // 但 edit_comment_link 直接输出 HTML。
                        // 简单方案：输出时内容留空，用 CSS 伪元素填充。
                        // edit_comment_link( $text, $before, $after )
                        // 我们可以把 $text 设为空字符串，并给 wrapper 加 data 属性？ 不行，A标签内部是空的。
                        // 我们可以让 A 标签带上 data 属性吗？ edit_comment_link 没有直接参数加属性到 A 标签。
                        // 替代方案：手动构建
                        if (current_user_can('edit_comment', $comment->comment_ID)) {
                            $edit_url = get_edit_comment_link($comment->comment_ID);
                            echo '<a class="comment-edit-link" href="' . esc_url($edit_url) . '" data-cn="编辑" data-en="Edit"></a>';
                        }
                        ?>
                    </span>
                </div>
            </div>
            <div class="gh-comment-body">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em class="comment-awaiting-moderation" data-cn="您的评论正在等待审核。" data-en="Your comment is awaiting moderation."></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
        </div>
    </li>
    <?php
}

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
