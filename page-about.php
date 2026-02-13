<?php
/* Template Name: IT Studio Introduction */
get_header();
?>

<main class="site-main intro-page">
    <section class="intro-hero intro-step is-active" id="intro">
        <div class="container intro-hero-grid">
            <div class="intro-copy">
                <h1 class="intro-title intro-animate" style="--delay: 0.05s" data-cn="与海同频的技术社团" data-en="A Tech Studio Tuned to the Ocean"></h1>
                <p class="intro-lead intro-animate stream-text" style="--delay: 0.1s" data-cn="中国海洋大学爱特工作室，自 2002 年以来聚焦人才培养与真实项目，连接设计、开发与创新实践。" data-en="Since 2002, IT Studio at OUC connects design, engineering, and real projects to grow talent."></p>
                <p class="intro-sub intro-animate" style="--delay: 0.15s" data-cn="在海洋与科技之间，探索更有温度的数字创造。" data-en="Where ocean spirit meets modern engineering."></p>
                <div class="intro-cta-group intro-animate" style="--delay: 0.2s">
                    <a class="intro-btn intro-btn--primary" href="#overview" data-cn="开始探索" data-en="Explore" aria-label="Explore"></a>
                    <a class="intro-btn intro-btn--ghost" href="<?php echo esc_url(home_url('/join')); ?>" data-cn="加入我们" data-en="Join Us" aria-label="Join Us"></a>
                </div>
                <div class="intro-stats intro-animate" style="--delay: 0.25s">
                    <div class="intro-stat">
                        <span class="stat-number">2002</span>
                        <span class="stat-label" data-cn="成立" data-en="Founded"></span>
                    </div>
                    <div class="intro-stat">
                        <span class="stat-number">6</span>
                        <span class="stat-label" data-cn="方向" data-en="Tracks"></span>
                    </div>
                    <div class="intro-stat">
                        <span class="stat-number">20+</span>
                        <span class="stat-label" data-cn="积累" data-en="Years"></span>
                    </div>
                </div>
            </div>
            <div class="intro-hero-media">
                <div class="media-card media-card--tall intro-animate" style="--delay: 0.15s" data-cn="视频占位：工作室剪影" data-en="Video Slot: Studio Reel"></div>
                <div class="media-card intro-animate" style="--delay: 0.2s" data-cn="图片占位：成员日常" data-en="Image Slot: Daily Life"></div>
                <div class="media-card intro-animate" style="--delay: 0.25s" data-cn="图片占位：赛事/项目" data-en="Image Slot: Projects & Competitions"></div>
            </div>
        </div>
    </section>

    <section class="intro-section intro-step" id="overview">
        <div class="container intro-grid">
            <div class="intro-copy">
                <h2 class="intro-heading intro-animate" style="--delay: 0s" data-cn="工作室概述" data-en="Overview"></h2>
                <p class="intro-text intro-animate" style="--delay: 0.05s" data-cn="依托信息科学与工程学部，爱特坚持项目驱动与教学并行，面向真实场景输出作品与人才。" data-en="Backed by the School of Information Science and Engineering, IT Studio blends project delivery with hands-on training."></p>
                <ul class="intro-points">
                    <li class="intro-animate" style="--delay: 0.1s"><span data-cn="项目驱动，贯通设计到开发" data-en="Project-driven pipeline from design to delivery"></span></li>
                    <li class="intro-animate" style="--delay: 0.15s"><span data-cn="课堂与实践并行，培养工程思维" data-en="Learning meets practice to build engineering mindset"></span></li>
                    <li class="intro-animate" style="--delay: 0.2s"><span data-cn="开放协作，跨方向互相赋能" data-en="Open collaboration across tracks"></span></li>
                </ul>
            </div>
            <div class="intro-media">
                <div class="media-card media-card--wide intro-animate" style="--delay: 0.1s" data-cn="视频占位：项目发布" data-en="Video Slot: Project Launch"></div>
                <div class="media-card intro-animate" style="--delay: 0.15s" data-cn="图片占位：工作室环境" data-en="Image Slot: Workspace"></div>
                <div class="media-card intro-animate" style="--delay: 0.2s" data-cn="图片占位：团队合影" data-en="Image Slot: Team Photo"></div>
            </div>
        </div>
    </section>

    <section class="intro-section intro-step" id="structure">
        <div class="container intro-grid reverse">
            <div class="intro-copy">
                <h2 class="intro-heading intro-animate" style="--delay: 0s" data-cn="组织架构" data-en="Organization"></h2>
                <p class="intro-text intro-animate" style="--delay: 0.05s" data-cn="矩阵式结构让每个方向深耕专业，同时保持跨团队协作与统一标准。" data-en="A matrix structure keeps each track focused while enabling cross-team collaboration."></p>
                <div class="intro-org-grid">
                    <div class="intro-card intro-animate" style="--delay: 0.1s">
                        <h3 data-cn="产品与设计" data-en="Product & Design"></h3>
                        <p data-cn="从需求到体验，定义产品路径" data-en="Define experience from insights to prototype"></p>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.12s">
                        <h3 data-cn="Web 开发" data-en="Web Development"></h3>
                        <p data-cn="构建稳定高效的应用与平台" data-en="Build reliable platforms and services"></p>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.14s">
                        <h3 data-cn="程序设计" data-en="Programming"></h3>
                        <p data-cn="算法与工程能力双线成长" data-en="Algorithmic rigor with engineering skill"></p>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.16s">
                        <h3 data-cn="移动开发" data-en="Mobile Development"></h3>
                        <p data-cn="打造便捷流畅的移动体验" data-en="Deliver polished mobile experiences"></p>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.18s">
                        <h3 data-cn="游戏与交互" data-en="Game & Interaction"></h3>
                        <p data-cn="创意玩法与交互体验探索" data-en="Experiment with interactive innovation"></p>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.2s">
                        <h3 data-cn="运营与传播" data-en="Operations & Media"></h3>
                        <p data-cn="记录成长与团队品牌传播" data-en="Tell stories and amplify impact"></p>
                    </div>
                </div>
            </div>
            <div class="intro-media">
                <div class="media-card media-card--wide intro-animate" style="--delay: 0.1s" data-cn="结构图占位：组织矩阵" data-en="Org Chart Slot"></div>
                <div class="media-card intro-animate" style="--delay: 0.15s" data-cn="图片占位：部门协作" data-en="Image Slot: Collaboration"></div>
                <div class="media-card intro-animate" style="--delay: 0.2s" data-cn="图片占位：技术分享" data-en="Image Slot: Tech Talk"></div>
            </div>
        </div>
    </section>

    <section class="intro-section intro-step" id="culture">
        <div class="container intro-grid">
            <div class="intro-copy">
                <h2 class="intro-heading intro-animate" style="--delay: 0s" data-cn="社团特色" data-en="Culture"></h2>
                <p class="intro-text intro-animate" style="--delay: 0.05s" data-cn="舒适环境与严谨技术并重，爱特强调分享、共创与持续成长。" data-en="We balance a comfortable space with rigorous craft, highlighting sharing and growth."></p>
                <div class="intro-feature-grid">
                    <div class="intro-card intro-animate" style="--delay: 0.1s">
                        <h3 data-cn="海洋元素空间" data-en="Ocean-inspired Space"></h3>
                        <p data-cn="清爽明快的海洋色调与科技感布局" data-en="Ocean palettes with a modern tech layout"></p>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.14s">
                        <h3 data-cn="导师与学长共创" data-en="Mentors & Alumni"></h3>
                        <p data-cn="前辈指导与同行交流并行" data-en="Guided mentorship and peer exchange"></p>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.18s">
                        <h3 data-cn="分享与教学氛围" data-en="Teaching Culture"></h3>
                        <p data-cn="内部课程、公开分享与成果展示" data-en="Workshops, open talks, and demos"></p>
                    </div>
                </div>
            </div>
            <div class="intro-media">
                <div class="media-card media-card--tall intro-animate" style="--delay: 0.12s" data-cn="视频占位：空间巡礼" data-en="Video Slot: Studio Tour"></div>
                <div class="media-card intro-animate" style="--delay: 0.16s" data-cn="图片占位：教学现场" data-en="Image Slot: Workshop"></div>
                <div class="media-card intro-animate" style="--delay: 0.2s" data-cn="图片占位：文化活动" data-en="Image Slot: Community"></div>
            </div>
        </div>
    </section>

    <section class="intro-section intro-step" id="advantages">
        <div class="container intro-grid reverse">
            <div class="intro-copy">
                <h2 class="intro-heading intro-animate" style="--delay: 0s" data-cn="独特优势" data-en="Advantages"></h2>
                <p class="intro-text intro-animate" style="--delay: 0.05s" data-cn="资源与支持覆盖从想法到落地的全链路，帮助成员更快成长。" data-en="Resources cover the full cycle from idea to launch, accelerating growth."></p>
                <div class="intro-adv-grid">
                    <div class="intro-card intro-animate" style="--delay: 0.1s">
                        <h3 data-cn="资源池" data-en="Resources"></h3>
                        <ul class="intro-list">
                            <li><span data-cn="高性能计算与私有服务器支持" data-en="High-performance compute and servers"></span></li>
                            <li><span data-cn="专属场地与硬件设备" data-en="Dedicated space and hardware"></span></li>
                            <li><span data-cn="校内导师与行业资源" data-en="Academic and industry mentors"></span></li>
                            <li><span data-cn="优秀学长学姐经验传承" data-en="Alumni knowledge sharing"></span></li>
                        </ul>
                    </div>
                    <div class="intro-card intro-animate" style="--delay: 0.14s">
                        <h3 data-cn="成员福利" data-en="Benefits"></h3>
                        <ul class="intro-list">
                            <li><span data-cn="主流 AI 工具与算力支持" data-en="Access to AI tools and compute"></span></li>
                            <li><span data-cn="赛事奖励与项目展示机会" data-en="Competitions and demo opportunities"></span></li>
                            <li><span data-cn="企业项目与实习推荐" data-en="Industry projects and internships"></span></li>
                            <li><span data-cn="礼品激励与成长认证" data-en="Rewards and growth recognition"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="intro-media">
                <div class="media-card media-card--wide intro-animate" style="--delay: 0.12s" data-cn="视频占位：算力与设备" data-en="Video Slot: Compute Lab"></div>
                <div class="media-card intro-animate" style="--delay: 0.16s" data-cn="图片占位：资源展示" data-en="Image Slot: Resources"></div>
                <div class="media-card intro-animate" style="--delay: 0.2s" data-cn="图片占位：奖励与成果" data-en="Image Slot: Achievements"></div>
            </div>
        </div>
    </section>

    <section class="intro-section intro-step intro-join" id="join">
        <div class="container intro-join-grid">
            <div class="intro-join-content">
                <h2 class="intro-heading intro-animate" style="--delay: 0s" data-cn="加入爱特工作室" data-en="Join IT Studio"></h2>
                <p class="intro-text intro-animate" style="--delay: 0.05s" data-cn="如果你热爱技术与创造，我们欢迎你。一起把想法变成作品。" data-en="If you love building with technology, you're welcome here. Let's turn ideas into impact."></p>
                <a class="intro-btn intro-btn--primary intro-animate" style="--delay: 0.1s" href="<?php echo esc_url(home_url('/join')); ?>" data-cn="立即加入" data-en="Apply Now" aria-label="Apply Now"></a>
            </div>
            <div class="intro-join-media intro-animate" style="--delay: 0.15s">
                <div class="media-card" data-cn="图片占位：招新现场" data-en="Image Slot: Recruitment"></div>
                <div class="media-card" data-cn="图片占位：成员合影" data-en="Image Slot: Team Moment"></div>
                <div class="media-card" data-cn="视频占位：未来愿景" data-en="Video Slot: Vision"></div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
