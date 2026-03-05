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

$is_registration_open = !empty($join_runtime['is_registration_open']);
$is_query_open = !empty($join_runtime['is_query_open']);
$is_notice_open = !empty($join_runtime['is_notice_open']);

$query_deadline_cn = isset($join_runtime['query_deadline_cn']) ? (string) $join_runtime['query_deadline_cn'] : '';
$query_deadline_en = isset($join_runtime['query_deadline_en']) ? (string) $join_runtime['query_deadline_en'] : '';

$current_label_cn = isset($current_stage['label_cn']) ? (string) $current_stage['label_cn'] : '当前未在招新时段';
$current_label_en = isset($current_stage['label_en']) ? (string) $current_stage['label_en'] : 'Recruitment is currently closed';
$current_range_cn = isset($current_stage['range_cn']) ? (string) $current_stage['range_cn'] : '请关注后续通知';
$current_range_en = isset($current_stage['range_en']) ? (string) $current_stage['range_en'] : 'Please check later updates';
$current_stage_photo_url = isset($join_runtime['current_stage_photo_url']) ? (string) $join_runtime['current_stage_photo_url'] : '';
if ($current_stage_photo_url === '') {
    $current_stage_photo_url = get_template_directory_uri() . '/resources/it_logo_2024.svg';
}

$signup_shortcode = trim((string) ($join_settings['signup_form_shortcode'] ?? ''));
$query_shortcode = trim((string) ($join_settings['query_form_shortcode'] ?? ''));
$notice_shortcode = trim((string) ($join_settings['notice_view_shortcode'] ?? ''));

$has_formidable = shortcode_exists('formidable') || class_exists('FrmFormsController');
?>

<main class="site-main join-page">
    <div class="container">
        <header class="join-head">
            <h1 class="join-title" data-cn="加入我们" data-en="Join Us">加入我们</h1>
        </header>

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
                        <p class="join-current-label" data-cn="当前招新阶段" data-en="Current Recruitment Stage">当前招新阶段</p>
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
                    </div>
                </div>
                <div class="join-wave-layer">
                    <canvas id="joinProgressCanvas" class="join-progress-canvas" aria-hidden="true"></canvas>
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
                </div>
            </div>

            <ol class="join-stage-list">
                <?php foreach ($join_stages as $stage) : ?>
                    <?php
                    $stage_status = isset($stage['status']) ? (string) $stage['status'] : 'pending';
                    $status_cn = '待设置';
                    $status_en = 'Pending';
                    if ($stage_status === 'completed') {
                        $status_cn = '已完成';
                        $status_en = 'Completed';
                    } elseif ($stage_status === 'active') {
                        $status_cn = '进行中';
                        $status_en = 'In Progress';
                    } elseif ($stage_status === 'upcoming') {
                        $status_cn = '未开始';
                        $status_en = 'Upcoming';
                    }
                    $is_current_stage = !empty($current_stage['key']) && !empty($stage['key']) && ((string) $current_stage['key'] === (string) $stage['key']);
                    ?>
                    <li class="join-stage-item is-<?php echo esc_attr($stage_status); ?><?php echo $is_current_stage ? ' is-current' : ''; ?>">
                        <div class="join-stage-title-row">
                            <h3
                                class="join-stage-name"
                                data-cn="<?php echo esc_attr((string) ($stage['label_cn'] ?? '')); ?>"
                                data-en="<?php echo esc_attr((string) ($stage['label_en'] ?? '')); ?>"
                            >
                                <?php echo esc_html((string) ($stage['label_cn'] ?? '')); ?>
                            </h3>
                            <span
                                class="join-stage-status"
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
                    </li>
                <?php endforeach; ?>
            </ol>
        </section>

        <section class="join-forms-grid">
            <article class="join-form-card">
                <header class="join-form-head">
                    <h2 data-cn="报名表单" data-en="Registration Form">报名表单</h2>
                    <p data-cn="仅在报名阶段开放" data-en="Available during registration stage only.">仅在报名阶段开放</p>
                </header>
                <div class="join-form-content">
                    <?php if (!$is_registration_open) : ?>
                        <p class="join-form-tip" data-cn="当前不在报名时间段 请关注后续通知" data-en="Registration is currently closed.">当前不在报名时间段 请关注后续通知</p>
                    <?php elseif (!$has_formidable) : ?>
                        <p class="join-form-tip" data-cn="未检测到 Formidable Forms 插件 请先启用插件" data-en="Formidable Forms is not active.">未检测到 Formidable Forms 插件 请先启用插件</p>
                    <?php elseif ($signup_shortcode === '') : ?>
                        <p class="join-form-tip" data-cn="请在 设置 > 招新设置 中填写报名表单 Shortcode" data-en="Please configure the registration form shortcode in Settings > Recruitment Settings.">请在 设置 > 招新设置 中填写报名表单 Shortcode</p>
                    <?php else : ?>
                        <?php echo do_shortcode($signup_shortcode); ?>
                    <?php endif; ?>
                </div>
            </article>

            <article class="join-form-card">
                <header class="join-form-head">
                    <h2 data-cn="结果查询" data-en="Progress Lookup">结果查询</h2>
                    <p data-cn="报名开始后至公示结束前可查询进度" data-en="Available from registration start until the end of public notice.">报名开始后至公示结束前可查询进度</p>
                </header>
                <div class="join-form-content">
                    <?php if (!$is_query_open) : ?>
                        <p class="join-form-tip" data-cn="当前查询通道未开放或已关闭" data-en="Lookup is currently unavailable.">当前查询通道未开放或已关闭</p>
                    <?php elseif (!$has_formidable) : ?>
                        <p class="join-form-tip" data-cn="未检测到 Formidable Forms 插件 请先启用插件" data-en="Formidable Forms is not active.">未检测到 Formidable Forms 插件 请先启用插件</p>
                    <?php elseif ($query_shortcode === '') : ?>
                        <p class="join-form-tip" data-cn="请在 设置 > 招新设置 中填写查询表单 Shortcode" data-en="Please configure the lookup form shortcode in Settings > Recruitment Settings.">请在 设置 > 招新设置 中填写查询表单 Shortcode</p>
                    <?php else : ?>
                        <?php echo do_shortcode($query_shortcode); ?>
                    <?php endif; ?>
                </div>
                <?php if ($query_deadline_cn !== '' || $query_deadline_en !== '') : ?>
                    <footer
                        class="join-form-footnote"
                        data-cn="<?php echo esc_attr('查询截止时间 ' . $query_deadline_cn); ?>"
                        data-en="<?php echo esc_attr('Lookup closes at: ' . $query_deadline_en); ?>"
                    >
                        <?php echo esc_html('查询截止时间 ' . $query_deadline_cn); ?>
                    </footer>
                <?php endif; ?>
            </article>

            <article class="join-form-card join-form-card-full">
                <header class="join-form-head">
                    <h2 data-cn="录取结果公示" data-en="Admission Public Notice">录取结果公示</h2>
                    <p data-cn="公示阶段自动展示 公示期为 7 天" data-en="Published automatically during the 7-day notice window.">公示阶段自动展示 公示期为 7 天</p>
                </header>
                <div class="join-form-content">
                    <?php if (!$is_notice_open) : ?>
                        <p class="join-form-tip" data-cn="当前未进入公示阶段" data-en="Public notice is not active yet.">当前未进入公示阶段</p>
                    <?php elseif (!$has_formidable) : ?>
                        <p class="join-form-tip" data-cn="未检测到 Formidable Forms 插件 请先启用插件" data-en="Formidable Forms is not active.">未检测到 Formidable Forms 插件 请先启用插件</p>
                    <?php elseif ($notice_shortcode === '') : ?>
                        <p class="join-form-tip" data-cn="请在 设置 > 招新设置 中填写公示视图 Shortcode" data-en="Please configure the notice view shortcode in Settings > Recruitment Settings.">请在 设置 > 招新设置 中填写公示视图 Shortcode</p>
                    <?php else : ?>
                        <?php echo do_shortcode($notice_shortcode); ?>
                    <?php endif; ?>
                </div>
            </article>
        </section>
    </div>
</main>

<?php get_footer(); ?>
