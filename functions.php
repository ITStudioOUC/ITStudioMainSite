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
}
add_action('acf/init', 'itstudio_register_acf_fields');

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
        'second_interview_date' => '',
        'notice_start_date' => '',
        'photo_registration' => 0,
        'photo_first_interview' => 0,
        'photo_assessment' => 0,
        'photo_second_interview' => 0,
        'photo_public_notice' => 0,
        'photo_inactive' => 0,
        'signup_form_shortcode' => '',
        'query_form_shortcode' => '',
        'notice_view_shortcode' => '',
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

function itstudio_join_sanitize_settings($input) {
    $defaults = itstudio_join_get_default_settings();
    $input = is_array($input) ? $input : array();

    $sanitized = array(
        'registration_start' => itstudio_join_sanitize_datetime_local_value($input['registration_start'] ?? ''),
        'registration_end' => itstudio_join_sanitize_datetime_local_value($input['registration_end'] ?? ''),
        'first_interview_date' => itstudio_join_sanitize_date_value($input['first_interview_date'] ?? ''),
        'second_interview_date' => itstudio_join_sanitize_date_value($input['second_interview_date'] ?? ''),
        'notice_start_date' => itstudio_join_sanitize_date_value($input['notice_start_date'] ?? ''),
        'signup_form_shortcode' => itstudio_join_sanitize_shortcode_value($input['signup_form_shortcode'] ?? ''),
        'query_form_shortcode' => itstudio_join_sanitize_shortcode_value($input['query_form_shortcode'] ?? ''),
        'notice_view_shortcode' => itstudio_join_sanitize_shortcode_value($input['notice_view_shortcode'] ?? ''),
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

    $first_interview_day = itstudio_join_parse_date($settings['first_interview_date']);
    $first_interview_start = $first_interview_day instanceof DateTimeImmutable ? $first_interview_day->setTime(0, 0, 0) : null;
    $first_interview_end = $first_interview_day instanceof DateTimeImmutable ? $first_interview_day->setTime(23, 59, 59) : null;

    $second_interview_day = itstudio_join_parse_date($settings['second_interview_date']);
    $second_interview_start = $second_interview_day instanceof DateTimeImmutable ? $second_interview_day->setTime(0, 0, 0) : null;
    $second_interview_end = $second_interview_day instanceof DateTimeImmutable ? $second_interview_day->setTime(23, 59, 59) : null;

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

    $assessment_start = DateTimeImmutable::createFromFormat('!Y-m-d', sprintf('%04d-10-01', $recruitment_year), $timezone);
    $assessment_end_base = DateTimeImmutable::createFromFormat('!Y-m-d', sprintf('%04d-10-07', $recruitment_year), $timezone);
    $assessment_end = $assessment_end_base instanceof DateTimeImmutable ? $assessment_end_base->setTime(23, 59, 59) : null;

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
        ),
        array(
            'key' => 'first_interview',
            'label_cn' => '第一次面试',
            'label_en' => 'Interview I',
            'short_cn' => '一面',
            'short_en' => 'I-1',
            'start' => $first_interview_start,
            'end' => $first_interview_end,
            'all_day' => true,
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
        ),
        array(
            'key' => 'second_interview',
            'label_cn' => '第二次面试',
            'label_en' => 'Interview II',
            'short_cn' => '二面',
            'short_en' => 'I-2',
            'start' => $second_interview_start,
            'end' => $second_interview_end,
            'all_day' => true,
        ),
        array(
            'key' => 'public_notice',
            'label_cn' => '录取结果公示',
            'label_en' => 'Public Notice',
            'short_cn' => '公示',
            'short_en' => 'Notice',
            'start' => $notice_start,
            'end' => $notice_end,
            'all_day' => true,
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

    $current_stage = ($current_stage_index >= 0 && isset($stages[$current_stage_index]))
        ? $stages[$current_stage_index]
        : array(
            'key' => 'inactive',
            'label_cn' => '当前未在招新时段',
            'label_en' => 'Recruitment is currently closed',
            'short_cn' => '',
            'short_en' => '',
            'status' => 'inactive',
            'range_cn' => '请关注后续通知',
            'range_en' => 'Please check later updates',
            'start_ts' => null,
            'end_ts' => null,
        );

    $is_registration_open = itstudio_join_is_in_window($now, $registration_start, $registration_end);

    $is_query_open = false;
    if ($registration_start instanceof DateTimeImmutable) {
        if ($notice_end instanceof DateTimeImmutable) {
            $is_query_open = itstudio_join_is_in_window($now, $registration_start, $notice_end);
        } else {
            $is_query_open = ($now >= $registration_start);
        }
    }

    $is_notice_open = itstudio_join_is_in_window($now, $notice_start, $notice_end);
    $current_stage_photo_url = itstudio_join_get_stage_photo_url($settings, (string) ($current_stage['key'] ?? ''));
    if ($current_stage_photo_url === '') {
        $current_stage_photo_url = get_template_directory_uri() . '/resources/it_logo_2024.svg';
    }

    $cached = array(
        'settings' => $settings,
        'timezone' => $timezone->getName(),
        'recruitment_year' => $recruitment_year,
        'now_ts' => (int) $now->format('U') * 1000,
        'stages' => $stages,
        'current_stage_index' => $current_stage_index,
        'current_stage' => $current_stage,
        'is_registration_open' => $is_registration_open,
        'is_query_open' => $is_query_open,
        'is_notice_open' => $is_notice_open,
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
                'startTs' => isset($stage['start_ts']) ? $stage['start_ts'] : null,
                'endTs' => isset($stage['end_ts']) ? $stage['end_ts'] : null,
            );
        }, (array) ($runtime['stages'] ?? array()))),
    );
}

function itstudio_join_enqueue_assets() {
    if (!itstudio_is_join_page_context()) {
        return;
    }

    wp_enqueue_style(
        'itstudio-join-page',
        get_template_directory_uri() . '/assets/css/join-page.css',
        array('itstudio-content'),
        '1.1.0'
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
        '1.1.0',
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
        <p>用于配置「加入我们」页面的时间节点、表单和公示视图。</p>

        <?php if (!$formidable_active) : ?>
            <div class="notice notice-warning inline">
                <p><strong>提示：</strong>未检测到 Formidable Forms 插件。报名表单、查询表单、公示视图将无法在前台渲染。</p>
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
                        <input type="datetime-local" id="itstudio_join_registration_start" name="itstudio_join_settings[registration_start]" value="<?php echo esc_attr((string) $settings['registration_start']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_registration_end">报名结束时间</label></th>
                    <td>
                        <input type="datetime-local" id="itstudio_join_registration_end" name="itstudio_join_settings[registration_end]" value="<?php echo esc_attr((string) $settings['registration_end']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_first_interview_date">第一次面试日期</label></th>
                    <td>
                        <input type="date" id="itstudio_join_first_interview_date" name="itstudio_join_settings[first_interview_date]" value="<?php echo esc_attr((string) $settings['first_interview_date']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_second_interview_date">第二次面试日期</label></th>
                    <td>
                        <input type="date" id="itstudio_join_second_interview_date" name="itstudio_join_settings[second_interview_date]" value="<?php echo esc_attr((string) $settings['second_interview_date']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_notice_start_date">录取公示开始日期</label></th>
                    <td>
                        <input type="date" id="itstudio_join_notice_start_date" name="itstudio_join_settings[notice_start_date]" value="<?php echo esc_attr((string) $settings['notice_start_date']); ?>" class="regular-text">
                        <p class="description">公示会自动持续 7 天（开始日 + 后续 6 天）。</p>
                    </td>
                </tr>
                <?php itstudio_join_render_photo_field_row('photo_registration', '报名阶段图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_first_interview', '第一次面试图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_assessment', '国庆能力摸底图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_second_interview', '第二次面试图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_public_notice', '录取公示阶段图片', $settings); ?>
                <?php itstudio_join_render_photo_field_row('photo_inactive', '非招新时段图片', $settings); ?>
                <tr>
                    <th scope="row"><label for="itstudio_join_signup_shortcode">报名表单 Shortcode</label></th>
                    <td>
                        <input type="text" id="itstudio_join_signup_shortcode" name="itstudio_join_settings[signup_form_shortcode]" value="<?php echo esc_attr((string) $settings['signup_form_shortcode']); ?>" class="regular-text code">
                        <p class="description">示例：<code>[formidable id="12"]</code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_query_shortcode">结果查询 Shortcode</label></th>
                    <td>
                        <input type="text" id="itstudio_join_query_shortcode" name="itstudio_join_settings[query_form_shortcode]" value="<?php echo esc_attr((string) $settings['query_form_shortcode']); ?>" class="regular-text code">
                        <p class="description">示例：<code>[formidable id="13"]</code></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="itstudio_join_notice_shortcode">公示视图 Shortcode</label></th>
                    <td>
                        <input type="text" id="itstudio_join_notice_shortcode" name="itstudio_join_settings[notice_view_shortcode]" value="<?php echo esc_attr((string) $settings['notice_view_shortcode']); ?>" class="regular-text code">
                        <p class="description">示例：<code>[display-frm-data id="5"]</code></p>
                    </td>
                </tr>
            </table>
            <?php submit_button('保存设置'); ?>
        </form>

        <hr>
        <h2>阶段预览</h2>
        <p>国庆能力摸底阶段固定为每年 10 月 1 日至 10 月 7 日，年份自动取报名开始年份（未配置则取公示开始年份，再否则取当前年份）。</p>
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
