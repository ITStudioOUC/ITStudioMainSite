<?php get_header(); ?>

<main class="site-main error-404">
    <div class="container">
        <div class="error-content">
            <h1 class="error-title">404</h1>
            <h2><?php _e('页面未找到', 'itstudio'); ?></h2>
            <p><?php _e('抱歉，您访问的页面不存在。', 'itstudio'); ?></p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home">
                <?php _e('返回首页', 'itstudio'); ?>
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
