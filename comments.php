<?php
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <div class="comments-header">
            <h3 class="comments-title">
                <?php
                $comment_count = get_comments_number();
                if ($comment_count === 1) {
                    printf(esc_html__('1个回复', 'itstudio'));
                } else {
                    printf(
                        /* translators: 1: comment count number */
                        esc_html(_n('%1$s个回复', '%1$s个回复', $comment_count, 'itstudio')),
                        number_format_i18n($comment_count)
                    );
                }
                ?>
            </h3>
        </div>

        <ol class="comment-list">
            <?php
            wp_list_comments(array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 48,
                'callback'    => 'itstudio_comment_callback' // We will need to define this in functions.php or handle logic here if simple
            ));
            ?>
        </ol>

        <?php
        the_comments_navigation();

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
        ?>
            <p class="no-comments"><?php esc_html_e('评论已关闭。', 'itstudio'); ?></p>
        <?php
        endif;

    endif; // Check for have_comments().
    ?>

    <div class="comment-form-wrapper">
        <?php
        comment_form(array(
            'class_form'           => 'gh-comment-form',
            'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title" data-cn="发表回复" data-en="Leave a comment">',
            'title_reply_after'    => '</h3>',
            'title_reply'          => '', // Handled by data-cn/en in before tag
            'label_submit'         => __('发表评论', 'itstudio'), // This submits button text, harder to change via attr, usually needs JS or just leave as is if acceptable
            'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="" data-cn="发表评论" data-en="Comment" />',
            'comment_notes_before' => '',
            'comment_field'        => '<div class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></div>',
        ));
        ?>
    </div>

</div><!-- #comments -->
