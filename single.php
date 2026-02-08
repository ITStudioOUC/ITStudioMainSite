<?php get_header(); ?>

<main class="site-main single-post-gh-pro">
    <div class="container">
        <?php
        while (have_posts()) :
            the_post();
        ?>
            <!-- 1. 文章头部：极简极客风，去掉 Issue 编号等干扰 -->
            <header class="gh-pro-header">
                <div class="gh-pro-meta-top">
                    <?php
                    $categories = get_the_category();
                    if ($categories) :
                        $cat = $categories[0];
                        echo '<a href="' . get_category_link($cat->term_id) . '" class="gh-label-category">' . $cat->name . '</a>';
                    endif;
                    ?>
                </div>

                <h1 class="entry-title"><?php the_title(); ?></h1>

                <div class="gh-pro-meta-bar">
                    <div class="gh-author-lockup">
                        <?php echo get_avatar(get_the_author_meta('ID'), 32); ?>
                        <span class="author-name"><?php the_author(); ?></span>
                        <span class="meta-divider">/</span>
                        <span class="publish-date"><?php echo get_the_date('Y.m.d'); ?></span>
                    </div>

                    <div class="gh-stats">
                        <span class="stat-item" title="<?php esc_attr_e('Comments', 'itstudio'); ?>">
                            <svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16" data-view-component="true" class="octicon octicon-comment"><path d="M1 2.75C1 1.784 1.784 1 2.75 1h10.5c.966 0 1.75.784 1.75 1.75v7.5A1.75 1.75 0 0 1 13.25 12H9.06l-2.573 2.573A1.458 1.458 0 0 1 4 13.543V12H2.75A1.75 1.75 0 0 1 1 10.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h2a.75.75 0 0 1 .75.75v2.19l2.72-2.72a.749.749 0 0 1 .53-.22h4.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"></path></svg>
                            <?php comments_number('0', '1', '%'); ?>
                        </span>
                    </div>
                </div>
            </header>

            <!-- 2. 正文容器：类似 GitHub File/Readme 的框体风格 -->
            <div class="gh-pro-body-container">
                <div class="gh-file-box">
                    <div class="gh-file-header">
                        <div class="file-info">
                            <svg aria-hidden="true" height="16" viewBox="0 0 16 16" version="1.1" width="16" data-view-component="true" class="octicon octicon-book"><path d="M0 6.75C0 5.784.784 5 1.75 5h1.5a.75.75 0 0 1 0 1.5h-1.5a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-1.5a.75.75 0 0 1 1.5 0v1.5A1.75 1.75 0 0 1 9.25 16h-7.5A1.75 1.75 0 0 1 0 14.25Z"></path><path d="M5 1.75C5 .784 5.784 0 6.75 0h7.5C15.216 0 16 .784 16 1.75v7.5A1.75 1.75 0 0 1 14.25 11h-7.5A1.75 1.75 0 0 1 5 9.25Zm1.75-.25a.25.25 0 0 0-.25.25v7.5c0 .138.112.25.25.25h7.5a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25Z"></path></svg>
                            <strong>readme.md</strong>
                            <span class="file-divider"></span>
                            <span class="file-size">
                                <?php
                                    $read_time = round(str_word_count(strip_tags(get_the_content())) / 300, 1);
                                    printf(
                                        '<span data-cn="%s 分钟阅读" data-en="%s mins read"></span>',
                                        $read_time,
                                        $read_time
                                    );
                                ?>
                            </span>
                        </div>
                        <div class="file-actions">
                             <a href="#comments" class="btn-sm" data-cn="发表评论" data-en="Post Comment"></a>
                        </div>
                    </div>

                    <div class="gh-file-content markdown-body">
                         <?php if (has_post_thumbnail()) : ?>
                            <div class="entry-thumbnail-pro">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>

                        <?php the_content(); ?>

                        <div class="gh-file-footer">
                             <?php
                                $tags = get_the_tags();
                                if ($tags) :
                                    foreach ($tags as $tag) {
                                        echo '<span class="gh-tag tag-tag">#' . $tag->name . '</span> ';
                                    }
                                endif;
                             ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. 评论区：保持 GitHub Timeline 风格 -->
            <div class="gh-pro-comments">
                <div class="timeline-header">
                    <h3 data-cn="文章讨论" data-en="Discussion"></h3>
                </div>

                <div class="gh-timeline">
                    <!-- Vertical Line -->
                    <div class="timeline-line"></div>

                    <?php
                    // 直接加载评论模板
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
