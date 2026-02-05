<footer class="site-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h3><?php _e('友情链接', 'itstudio'); ?></h3>
                <ul class="footer-links">
                    <li><a href="https://www.ouc.edu.cn" target="_blank" rel="noopener"><?php _e('中国海洋大学', 'itstudio'); ?></a></li>
                    <li><a href="https://cst.ouc.edu.cn" target="_blank" rel="noopener"><?php _e('信息科学与工程学部', 'itstudio'); ?></a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3><?php _e('加入我们', 'itstudio'); ?></h3>
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/join')); ?>"><?php _e('招新信息', 'itstudio'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/about')); ?>"><?php _e('了解工作室', 'itstudio'); ?></a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3><?php _e('联系我们', 'itstudio'); ?></h3>
                <ul class="footer-links">
                    <li>Email: contact@itstudio.club</li>
                    <li><a href="https://github.com/itstudio-2002" target="_blank" rel="noopener">GitHub</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3><?php _e('关于', 'itstudio'); ?></h3>
                <p class="footer-slogan"><?php _e('发现人才，培养人才，输送人才', 'itstudio'); ?></p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php _e('中国海洋大学爱特工作室', 'itstudio'); ?>. <?php _e('保留所有权利', 'itstudio'); ?>.</p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
