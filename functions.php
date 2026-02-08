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

function itstudio_enqueue_scripts() {
    // 基础样式 (Style.css)
    wp_enqueue_style('itstudio-style', get_stylesheet_uri(), array(), '2.1.2');

    wp_enqueue_style('itstudio-header', get_template_directory_uri() . '/assets/css/header.css', array('itstudio-style'), '2.1.2');
    wp_enqueue_style('itstudio-footer', get_template_directory_uri() . '/assets/css/footer.css', array('itstudio-style'), '2.1.2');
    wp_enqueue_style('itstudio-content', get_template_directory_uri() . '/assets/css/content.css', array('itstudio-style'), '2.1.2');

    // 仅在首页加载 Hero 样式
    if (is_front_page() || is_home()) {
        wp_enqueue_style('itstudio-front-page', get_template_directory_uri() . '/assets/css/front-page.css', array('itstudio-style'), '2.1.2');
    }

    // Scripts
    wp_enqueue_script('itstudio-theme-toggle', get_template_directory_uri() . '/assets/js/theme-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('itstudio-lang-toggle', get_template_directory_uri() . '/assets/js/lang-toggle.js', array(), '1.0.0', true);
    // 注册并加载打字机效果脚本 - 仅在首页
    if (is_front_page() || is_home()) {
        wp_enqueue_script('itstudio-stream', get_template_directory_uri() . '/assets/js/stream.js', array(), '1.0.0', true);
    }
    wp_enqueue_script('itstudio-main', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0.0', true);
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

function itstudio_custom_post_types() {
    register_post_type('announcement', array(
        'labels' => array(
            'name' => __('Announcements', 'itstudio'),
            'singular_name' => __('Announcement', 'itstudio'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-megaphone',
        'show_in_rest' => true,
    ));
}
add_action('init', 'itstudio_custom_post_types');

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
