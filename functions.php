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
    wp_enqueue_style('itstudio-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_style('itstudio-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');

    wp_enqueue_script('itstudio-theme-toggle', get_template_directory_uri() . '/assets/js/theme-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('itstudio-lang-toggle', get_template_directory_uri() . '/assets/js/lang-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('itstudio-footer-slide', get_template_directory_uri() . '/assets/js/footer-slide.js', array(), '1.0.0', true);
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
