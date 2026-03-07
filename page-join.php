<?php
/* Template Name: Join Us */
get_header();

$join_runtime = function_exists('itstudio_join_get_runtime_data')
    ? itstudio_join_get_runtime_data()
    : array();

$join_settings = isset($join_runtime['settings']) && is_array($join_runtime['settings'])
    ? $join_runtime['settings']
    : array();
$join_stages = isset($join_runtime['stages']) && is_array($join_runtime['stages'])
    ? $join_runtime['stages']
    : array();
$current_stage = isset($join_runtime['current_stage']) && is_array($join_runtime['current_stage'])
    ? $join_runtime['current_stage']
    : array();
$current_stage_mode = isset($join_runtime['current_stage_mode']) ? (string) $join_runtime['current_stage_mode'] : 'inactive';

$is_registration_open = !empty($join_runtime['is_registration_open']);
$is_notice_open = !empty($join_runtime['is_notice_open']);
$is_notice_finished = !empty($join_runtime['is_notice_finished']);
$show_progress_visual = !empty($join_runtime['show_progress_visual']);
$is_progress_query_open = !empty($join_runtime['is_query_open']);

$current_label_cn = isset($current_stage['label_cn']) ? (string) $current_stage['label_cn'] : '当前未在招新时段';
$current_label_en = isset($current_stage['label_en']) ? (string) $current_stage['label_en'] : 'Recruitment is currently closed';
$current_range_cn = isset($current_stage['range_cn']) ? (string) $current_stage['range_cn'] : '请关注后续通知';
$current_range_en = isset($current_stage['range_en']) ? (string) $current_stage['range_en'] : 'Please check later updates';
$current_location_cn = trim((string) ($current_stage['location_cn'] ?? ''));
$current_location_en = trim((string) ($current_stage['location_en'] ?? ''));
if ($current_location_en === '') {
    $current_location_en = $current_location_cn;
}
if ($current_location_cn === '') {
    $current_location_cn = $current_location_en;
}
$current_stage_heading_cn = $current_stage_mode === 'next' ? '下一招新阶段' : '当前招新阶段';
$current_stage_heading_en = $current_stage_mode === 'next' ? 'Next Recruitment Stage' : 'Current Recruitment Stage';
$current_stage_photo_url = isset($join_runtime['current_stage_photo_url']) ? (string) $join_runtime['current_stage_photo_url'] : '';
if ($current_stage_photo_url === '') {
    $current_stage_photo_url = get_template_directory_uri() . '/resources/it_logo_2024.svg';
}

$signup_shortcode = trim((string) ($join_settings['signup_form_shortcode'] ?? ''));

$has_formidable = shortcode_exists('formidable') || class_exists('FrmFormsController');
$progress_lookup = function_exists('itstudio_join_resolve_progress_lookup')
    ? itstudio_join_resolve_progress_lookup($join_runtime, $_GET)
    : array(
        'submitted' => false,
        'has_query' => false,
        'name' => '',
        'qq' => '',
        'email' => '',
        'student_id' => '',
        'message_cn' => '',
        'message_en' => '',
        'tone' => 'info',
    );
$join_feed_data = function_exists('itstudio_join_get_recruitment_feed_items')
    ? itstudio_join_get_recruitment_feed_items($join_runtime, 5)
    : array('display_year' => (int) wp_date('Y'), 'items' => array());
$join_feed_items = isset($join_feed_data['items']) && is_array($join_feed_data['items'])
    ? $join_feed_data['items']
    : array();
$join_feed_items = array_values(array_filter($join_feed_items, static function ($item) {
    if (!is_array($item)) {
        return false;
    }
    $title = trim((string) ($item['title'] ?? ''));
    $url = trim((string) ($item['url'] ?? ''));
    return ($title !== '' && $url !== '');
}));

$join_form_status = '';
if (isset($_GET['join_form_status']) && is_string($_GET['join_form_status'])) {
    $join_form_status = strtolower(trim((string) wp_unslash($_GET['join_form_status'])));
}
if ($join_form_status !== 'submitted' && $join_form_status !== 'draft') {
    $join_form_status = (isset($_GET['join_form_submitted']) && (string) $_GET['join_form_submitted'] === '1') ? 'submitted' : '';
}

$is_post_request = isset($_SERVER['REQUEST_METHOD']) && strtoupper((string) $_SERVER['REQUEST_METHOD']) === 'POST';
$post_form_status = 'submitted';
if ($is_post_request && function_exists('itstudio_join_detect_form_submission_status')) {
    $post_form_status = itstudio_join_detect_form_submission_status($_POST);
}
?>

<main class="site-main join-page">
    <div class="container">
        <header class="join-head">
            <h1 class="join-title" data-cn="加入我们" data-en="Join Us">加入我们</h1>
        </header>

        <?php if ($join_form_status === 'submitted' || $join_form_status === 'draft') : ?>
            <?php
            $notice_cn = $join_form_status === 'draft' ? '草稿已保存' : '你的报名表单已提交，感谢报名';
            $notice_en = $join_form_status === 'draft' ? 'Draft saved.' : 'Your registration form has been submitted. Thank you for applying.';
            ?>
            <p class="join-submit-notice" data-cn="<?php echo esc_attr($notice_cn); ?>" data-en="<?php echo esc_attr($notice_en); ?>"><?php echo esc_html($notice_cn); ?></p>
            <script>
                (function () {
                    if (!window.history || !window.history.replaceState) {
                        return;
                    }
                    var url = new URL(window.location.href);
                    if (!url.searchParams.has('join_form_status') && !url.searchParams.has('join_form_submitted')) {
                        return;
                    }
                    url.searchParams.delete('join_form_status');
                    url.searchParams.delete('join_form_submitted');
                    var nextUrl = url.pathname + (url.search ? url.search : '') + url.hash;
                    window.history.replaceState({}, document.title, nextUrl);
                })();
            </script>
        <?php endif; ?>

        <section class="join-hero">
            <div class="join-canvas-shell">
                <div class="join-stage-photo-frame">
                    <img
                        class="join-stage-photo"
                        src="<?php echo esc_url($current_stage_photo_url); ?>"
                        alt="<?php echo esc_attr($current_label_cn); ?>"
                        loading="lazy"
                    >
                    <div class="join-canvas-overlay">
                        <p class="join-current-label" data-cn="<?php echo esc_attr($current_stage_heading_cn); ?>" data-en="<?php echo esc_attr($current_stage_heading_en); ?>"><?php echo esc_html($current_stage_heading_cn); ?></p>
                        <h2
                            id="joinCurrentStage"
                            class="join-current-stage"
                            data-cn="<?php echo esc_attr($current_label_cn); ?>"
                            data-en="<?php echo esc_attr($current_label_en); ?>"
                        >
                            <?php echo esc_html($current_label_cn); ?>
                        </h2>
                        <p
                            id="joinCurrentRange"
                            class="join-current-range"
                            data-cn="<?php echo esc_attr($current_range_cn); ?>"
                            data-en="<?php echo esc_attr($current_range_en); ?>"
                        >
                            <?php echo esc_html($current_range_cn); ?>
                        </p>
                        <?php if ($current_location_cn !== '' || $current_location_en !== '') : ?>
                            <?php
                            $current_location_line_cn = '地点：' . $current_location_cn;
                            $current_location_line_en = 'Location: ' . $current_location_en;
                            ?>
                            <p
                                id="joinCurrentLocation"
                                class="join-current-location"
                                data-cn="<?php echo esc_attr($current_location_line_cn); ?>"
                                data-en="<?php echo esc_attr($current_location_line_en); ?>"
                            >
                                <?php echo esc_html($current_location_line_cn); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="join-wave-layer">
                    <canvas id="joinProgressCanvas" class="join-progress-canvas" aria-hidden="true"></canvas>
                    <?php if ($show_progress_visual) : ?>
                        <div class="join-wave-progress" aria-hidden="true">
                            <div id="joinWaveTrack" class="join-wave-track">
                                <span id="joinWaveFill" class="join-wave-fill"></span>
                                <span id="joinWaveMarks" class="join-wave-marks"></span>
                                <span id="joinWaveBoat" class="join-wave-boat">
                                    <span class="join-wave-boat-icon">
                                        <svg viewBox="0 0 120 64" role="img" aria-hidden="true" focusable="false">
                                            <path class="boat-sail-main" d="M54 12L54 41L74 41L54 12Z"></path>
                                            <path class="boat-sail-side" d="M54 14L40 39L54 39L54 14Z"></path>
                                            <path class="boat-mast" d="M53 10H56V44H53Z"></path>
                                            <path class="boat-hull" d="M16 42H104C99 53 88 59 72 60H48C32 59 21 53 16 42Z"></path>
                                            <circle class="boat-porthole" cx="50" cy="50" r="2.2"></circle>
                                            <circle class="boat-porthole" cx="61" cy="51" r="2.2"></circle>
                                            <circle class="boat-porthole" cx="72" cy="50" r="2.2"></circle>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <ol class="join-stage-list">
                <?php foreach ($join_stages as $stage) : ?>
                    <?php
                    $stage_status = isset($stage['status']) ? (string) $stage['status'] : 'pending';
                    if ($is_notice_finished) {
                        // 录取结果公布结束后，所有阶段统一显示为“已结束”。
                        $stage_status = 'completed';
                    }
                    $status_cn = '待设置';
                    $status_en = 'Pending';
                    if ($stage_status === 'completed') {
                        $status_cn = '已结束';
                        $status_en = 'Ended';
                    } elseif ($stage_status === 'active') {
                        $status_cn = '进行中';
                        $status_en = 'In Progress';
                    } elseif ($stage_status === 'upcoming') {
                        $status_cn = '未开始';
                        $status_en = 'Upcoming';
                    }
                    $stage_key = (string) ($stage['key'] ?? '');
                    $stage_result_uploaded = !empty($stage['result_uploaded']);
                    $stage_location_cn = trim((string) ($stage['location_cn'] ?? ''));
                    $stage_location_en = trim((string) ($stage['location_en'] ?? ''));
                    if ($stage_location_en === '') {
                        $stage_location_en = $stage_location_cn;
                    }
                    if ($stage_location_cn === '') {
                        $stage_location_cn = $stage_location_en;
                    }
                    $is_current_stage = !empty($current_stage['key']) && !empty($stage['key']) && ((string) $current_stage['key'] === (string) $stage['key']);
                    $is_public_notice_stage = ($stage_key === 'public_notice');
                    $is_mid_stage_query_ready = in_array($stage_key, array('first_interview', 'assessment', 'second_interview'), true)
                        && $stage_status === 'completed'
                        && $stage_result_uploaded;
                    $is_query_ready_status = false;
                    $is_waiting_notice_status = false;
                    if (!$is_notice_finished && $is_mid_stage_query_ready) {
                        $status_cn = '可查询结果';
                        $status_en = 'Query Available';
                        $is_query_ready_status = true;
                    }
                    if (!$is_notice_finished && $is_public_notice_stage && in_array($stage_status, array('active', 'completed'), true)) {
                        if ($stage_result_uploaded) {
                            $status_cn = '可查询结果';
                            $status_en = 'Query Available';
                            $is_query_ready_status = true;
                        } else {
                            $status_cn = '请耐心等待通知';
                            $status_en = 'Please wait for notice';
                            $is_query_ready_status = false;
                            $is_waiting_notice_status = true;
                        }
                    }
                    ?>
                    <li class="join-stage-item is-<?php echo esc_attr($stage_status); ?><?php echo $is_current_stage ? ' is-current' : ''; ?><?php echo $is_waiting_notice_status ? ' is-waiting-notice' : ''; ?>">
                        <div class="join-stage-title-row">
                            <h3
                                class="join-stage-name"
                                data-cn="<?php echo esc_attr((string) ($stage['label_cn'] ?? '')); ?>"
                                data-en="<?php echo esc_attr((string) ($stage['label_en'] ?? '')); ?>"
                            >
                                <?php echo esc_html((string) ($stage['label_cn'] ?? '')); ?>
                            </h3>
                            <span
                                class="join-stage-status<?php echo $is_query_ready_status ? ' is-query-ready' : ''; ?><?php echo $is_waiting_notice_status ? ' is-waiting-notice' : ''; ?>"
                                data-cn="<?php echo esc_attr($status_cn); ?>"
                                data-en="<?php echo esc_attr($status_en); ?>"
                            >
                                <?php echo esc_html($status_cn); ?>
                            </span>
                        </div>
                        <p
                            class="join-stage-time"
                            data-cn="<?php echo esc_attr((string) ($stage['range_cn'] ?? '')); ?>"
                            data-en="<?php echo esc_attr((string) ($stage['range_en'] ?? '')); ?>"
                        >
                            <?php echo esc_html((string) ($stage['range_cn'] ?? '')); ?>
                        </p>
                        <?php if ($stage_location_cn !== '' || $stage_location_en !== '') : ?>
                            <?php
                            $stage_location_line_cn = '地点：' . $stage_location_cn;
                            $stage_location_line_en = 'Location: ' . $stage_location_en;
                            ?>
                            <p
                                class="join-stage-location"
                                data-cn="<?php echo esc_attr($stage_location_line_cn); ?>"
                                data-en="<?php echo esc_attr($stage_location_line_en); ?>"
                            >
                                <?php echo esc_html($stage_location_line_cn); ?>
                            </p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </section>

<?php
        $show_registration_form = $is_registration_open;
        $show_progress_query_form = $is_progress_query_open;
        $visible_form_count = ($show_registration_form ? 1 : 0) + ($show_progress_query_form ? 1 : 0);
        ?>
        <section class="join-info-layout">
                <div class="join-info-main<?php echo $visible_form_count === 0 ? ' is-empty' : ''; ?>">
                    <?php if ($show_registration_form) : ?>
                        <h2 class="join-panel-title" data-cn="报名表单" data-en="Registration Form">报名表单</h2>
                        <article class="join-form-card">
                            <div class="join-form-content">
                                <?php if (!$has_formidable) : ?>
                                    <p class="join-form-tip" data-cn="未检测到 Formidable Forms 插件，请先启用插件" data-en="Formidable Forms is not active.">未检测到 Formidable Forms 插件，请先启用插件</p>
                                <?php elseif ($signup_shortcode === '') : ?>
                                    <p class="join-form-tip" data-cn="请在 设置 > 招新设置 中填写报名表单 Shortcode" data-en="Please configure the registration form shortcode in Settings > Recruitment Settings.">请在 设置 > 招新设置 中填写报名表单 Shortcode</p>
                                <?php else : ?>
                                    <?php echo do_shortcode($signup_shortcode); ?>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endif; ?>

                    <?php if ($show_progress_query_form) : ?>
                        <h2 class="join-panel-title" data-cn="录取进度查询" data-en="Admission Progress Lookup">录取进度查询</h2>
                        <article class="join-form-card">
                            <div class="join-form-content">
                                <form method="get" class="join-progress-query-form">
                                    <div class="join-progress-query-grid">
                                        <label class="join-progress-query-field">
                                            <span data-cn="姓名 / QQ / 邮箱 / 学号" data-en="Name / QQ / Email / Student ID">姓名 / QQ / 邮箱 / 学号</span>
                                            <input
                                                type="text"
                                                name="join_query_identity"
                                                value="<?php echo esc_attr((string) ($progress_lookup['identity'] ?? '')); ?>"
                                                data-cn-placeholder="请输入中文姓名 / QQ / 邮箱 / 10~12位学号"
                                                data-en-placeholder="Enter Name / QQ / Email / Student ID (10-12 digits)"
                                                placeholder="请输入中文姓名 / QQ / 邮箱 / 10~12位学号"
                                            >
                                        </label>
                                    </div>
                                    <div class="join-progress-query-actions">
                                        <button type="submit" name="join_progress_lookup" value="1" class="join-progress-query-submit" data-cn="查询录取进度" data-en="Check Progress">查询录取进度</button>
                                    </div>
                                </form>
                                <?php if (!empty($progress_lookup['submitted'])) : ?>
                                    <?php
                                    $lookup_tone = trim((string) ($progress_lookup['tone'] ?? 'info'));
                                    if (!in_array($lookup_tone, array('success', 'warning', 'error', 'info'), true)) {
                                        $lookup_tone = 'info';
                                    }
                                    ?>
                                    <p
                                        class="join-progress-query-feedback is-<?php echo esc_attr($lookup_tone); ?>"
                                        data-cn="<?php echo esc_attr((string) ($progress_lookup['message_cn'] ?? '')); ?>"
                                        data-en="<?php echo esc_attr((string) ($progress_lookup['message_en'] ?? '')); ?>"
                                    >
                                        <?php echo esc_html((string) ($progress_lookup['message_cn'] ?? '')); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endif; ?>

                    <?php if ($visible_form_count === 0) : ?>
                        <article class="join-form-card join-form-card-placeholder">
                            <div class="join-form-content">
                                <p class="join-form-tip" data-cn="当前阶段暂无可操作表单" data-en="No available form in the current stage.">当前阶段暂无可操作表单</p>
                            </div>
                        </article>
                    <?php endif; ?>
                </div>

                <aside class="join-info-side">
                    <h2 class="join-panel-title" data-cn="招新资讯" data-en="Related News">招新资讯</h2>
                    <section class="join-news-strip" aria-label="Related News">
                        <?php if (!empty($join_feed_items)) : ?>
                            <div class="join-news-strip-track">
                                <?php foreach ($join_feed_items as $feed_item) : ?>
                                    <?php
                                    $feed_title = isset($feed_item['title']) ? (string) $feed_item['title'] : '';
                                    $feed_excerpt = isset($feed_item['excerpt']) ? (string) $feed_item['excerpt'] : '';
                                    $feed_url = isset($feed_item['url']) ? (string) $feed_item['url'] : '';
                                    $feed_author = isset($feed_item['author']) ? (string) $feed_item['author'] : '';
                                    $feed_date = isset($feed_item['date']) ? (string) $feed_item['date'] : '';
                                    $feed_date_iso = isset($feed_item['date_iso']) ? (string) $feed_item['date_iso'] : '';
                                    ?>
                                    <article class="join-news-item">
                                        <h3 class="join-news-item-title">
                                            <a href="<?php echo esc_url($feed_url); ?>"><?php echo esc_html($feed_title); ?></a>
                                        </h3>
                                        <div class="join-news-item-meta">
                                            <?php if ($feed_author !== '') : ?>
                                                <span class="join-news-item-meta-author">
                                                    <span data-cn="发布者" data-en="Author">发布者</span>
                                                    <?php echo esc_html($feed_author); ?>
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($feed_date !== '') : ?>
                                                <time class="join-news-item-meta-time" datetime="<?php echo esc_attr($feed_date_iso !== '' ? $feed_date_iso : $feed_date); ?>">
                                                    <span data-cn="时间" data-en="Time">时间</span>
                                                    <?php echo esc_html($feed_date); ?>
                                                </time>
                                            <?php endif; ?>
                                        </div>
                                        <p class="join-news-item-excerpt"><?php echo esc_html($feed_excerpt); ?></p>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p class="join-news-strip-empty" data-cn="暂无可展示的招新新闻通告" data-en="No recruitment updates available yet.">暂无可展示的招新新闻通告</p>
                        <?php endif; ?>
                    </section>
                </aside>
            </section>

        <?php if ($is_post_request) : ?>
            <script>
                (function () {
                    var hasSuccessMessage = document.querySelector('.frm_message, .frm_success_style, .frm_success');
                    if (!hasSuccessMessage) {
                        return;
                    }
                    try {
                        var url = new URL(window.location.href);
                        ['join_form_status', 'join_form_submitted', 'frm_action', 'frm_state', 'frm_data', 'frm_page_order', 'frm_test', 'frm_nonce'].forEach(function (key) {
                            url.searchParams.delete(key);
                        });
                        var status = <?php echo wp_json_encode($post_form_status); ?>;
                        if (status !== 'draft' && status !== 'submitted') {
                            status = 'submitted';
                        }
                        var successText = (hasSuccessMessage.textContent || '').toLowerCase();
                        if (successText.indexOf('draft') !== -1 || successText.indexOf('草稿') !== -1) {
                            status = 'draft';
                        }
                        url.searchParams.set('join_form_status', status);
                        var nextUrl = url.pathname + (url.search ? url.search : '') + url.hash;
                        window.location.replace(nextUrl);
                    } catch (error) {
                        // keep current page if URL parsing fails
                    }
                })();
            </script>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
