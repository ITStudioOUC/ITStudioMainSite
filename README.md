<div align="center">
    <img src="resources/it_logo_2024.svg" width=100/>
</div>
<div align="center">
    <h1>爱特工作室总站</h1>
</div>
<br>
<div align="center">
    <img src="resources/Organization-ITstudio-shield.svg"/><br>
    <img src="https://img.shields.io/badge/Language-php-purple">
    <img src="https://img.shields.io/badge/Language-javascript-yellow">
    <img src="https://img.shields.io/badge/Language-HTML-green">
    <img src="https://img.shields.io/badge/Language-CSS-blue">
    <img src="https://img.shields.io/badge/WordPress-Theme-21759b">
</div>
<br>

## 📖 项目简介

中国海洋大学爱特工作室官方网站 WordPress 主题。这是一个现代化、响应式的主题，支持暗色/亮色模式切换，融合海洋元素与 IT 风格。

## ✨ 主要特性

- 🎨 **现代简洁设计** - IT风格与海洋元素完美融合
- 🌓 **主题切换** - 支持暗色/亮色模式，可根据系统主题自动切换
- 📱 **响应式布局** - 完美适配 PC 端和移动端
- 🌐 **多语言支持** - 支持中文/英文切换
- ⚡ **性能优化** - 轻量级设计，加载快速
- 🎯 **自定义内容类型** - 支持公告通知、社团新闻、便民服务独立管理

## 🚀 快速开始

### 安装方法

1. 克隆本仓库到 WordPress 主题目录：
```bash
cd wp-content/themes
git clone https://github.com/itstudio-2002/ITStudioMainSite.git
```

2. 在 WordPress 后台激活主题：
   - 进入 **外观 > 主题**
   - 找到 "IT Studio Theme"
   - 点击 **激活**

### 主题配置

#### 1. 导航菜单设置

进入 **外观 > 菜单**，创建主导航菜单并分配到 "Primary Menu" 位置。

推荐菜单结构：
- 首页
- 公告通知
- 博客
- 便民服务
- 社团介绍
- 加入我们

#### 2. 发布公告

主题内置了"公告通知"自定义文章类型：
1. 进入 **Announcement > 新建 Announcement**
2. 填写标题和内容
3. 发布即可在首页显示

#### 3. 发布新闻

主题内置了"社团新闻"自定义文章类型：
1. 进入 **News > 新建 News**
2. 填写标题和内容
3. 发布即可在首页显示

#### 4. 新闻公告页权重字段（ACF）

新闻公告页右侧“高权重文章”读取 ACF 数值字段 `itstudio_weight`（数值越大，排序越靠前）。

适用范围：
- 默认文章（`post`）
- 公告（`announcement`）
- 新闻（`news`）

设置步骤：
1. 安装并启用插件 **Advanced Custom Fields (ACF)**。
2. 主题会自动注册字段组“内容权重”，字段名为 `itstudio_weight`（显示名“权重”）。
3. 编辑文章时在右侧填写“权重”数值（例如 `10`、`50`、`100`）。
4. 更新或发布文章。

说明：
- 当前仅读取 ACF 字段值，不再使用 Gutenberg 的“自定义字段”面板作为权重来源。
- 未设置 `itstudio_weight` 的文章按 `0` 处理。
- 当高权重文章不足 4 篇时，页面会自动用最新文章补足。

#### 5. 维护便民服务目录

主题内置了“便民服务（service）”内容类型，`/services` 页面会自动读取并展示。

每条服务信息来源：
- 图标：文章特色图（Featured Image）
- 名称：服务双语字段（中文名称 / 英文名称）
- 简介：服务双语字段（中文简介 / 英文简介）
- 类别：服务分类双语字段（中文名称 / 英文名称）
- 跳转链接：编辑页中的“服务跳转链接”字段
- 访问属性：编辑页中的“是否为校内服务”勾选项

后台新增步骤：
1. 进入 **便民服务 > 新增便民服务**。
2. 在“服务双语与链接”填写：
   - 中文名称 / 英文名称
   - 中文简介 / 英文简介
   - 服务跳转链接
   - 是否为校内服务（可选）
3. 设置特色图作为服务图标。
4. 在右侧选择或新建“服务分类”。
5. 发布后，该服务会自动出现在 `/services` 页面中。

分类双语设置：
1. 进入 **便民服务 > 服务分类**。
2. 新增或编辑分类时，可填写“中文名称”和“英文名称”。
3. 前台会根据语言切换自动显示对应分类名。

说明：
- 若未设置“服务跳转链接”，前端会回退到该服务文章自身链接。
- 若未设置特色图，前端会使用 `resources/it_logo_2024.svg` 作为默认图标。
- 勾选“是否为校内服务”后，服务卡片右上角会显示“仅校内访问”标签；不勾选则不显示该标签。
- 新增内容类型后若前台路由未生效，请进入 **设置 > 固定链接** 点击一次“保存更改”刷新重写规则。

## 🎨 设计规范

### 主题色彩

- **海洋浅蓝色** (#f0f8ff) - 亮色模式背景
- **海洋深蓝色** (#0a1929) - 暗色模式背景
- **强调色** (#8e88c7) - 链接、按钮等
- **中性色** - 灰度系列用于文本和边框

### 页面结构

- **Header** - 左侧 Logo，右侧导航栏、社交图标、主题切换按钮
- **首页** - 工作室介绍、公告列表、博客列表双栏布局
- **Footer** - 友情链接、联系方式、版权信息

## 📂 文件结构

```
ITStudioMainSite/
├── assets/
│   ├── css/
│   │   ├── content.css       # 内容页样式
│   │   ├── front-page.css    # 首页样式
│   │   └── services-page.css # 便民服务页样式
│   └── js/
│       ├── theme-toggle.js   # 主题切换功能
│       └── main.js           # 主要 JavaScript
├── resources/
│   ├── it_logo_2024.svg      # 工作室 Logo
│   ├── ouc-logo.svg          # 海大 Logo
│   └── ...
├── style.css                 # WordPress 主题样式表（必需）
├── functions.php             # 主题功能文件
├── index.php                 # 首页模板
├── header.php                # 头部模板
├── footer.php                # 底部模板
├── single.php                # 单篇文章模板
├── page.php                  # 页面模板
├── page-services.php         # 便民服务页面模板
├── archive.php               # 归档页面模板
└── 404.php                   # 404 错误页面
```

## 🛠️ 技术栈

- **WordPress** - 内容管理系统
- **PHP** - 后端开发语言
- **HTML5/CSS3** - 前端标记与样式
- **JavaScript** - 前端交互逻辑
- **SVG** - 矢量图标和 Logo

## 🔧 开发指南

### 本地开发环境

1. 安装 WordPress 本地开发环境（如 XAMPP、MAMP 等）
2. 克隆本仓库到 `wp-content/themes` 目录
3. 激活主题开始开发

### 自定义开发

- 修改 `assets/css/main.css` 自定义样式
- 编辑 `functions.php` 添加新功能
- 创建新的模板文件扩展功能

## 📝 使用许可

MIT License - 详见 LICENSE 文件

## 👥 关于我们

**爱特工作室**成立于 2002 年，是中国海洋大学信息科学与工程学部领导主持下的技术性团队。

🎯 **使命**: 发现人才，培养人才，输送人才

🔗 **链接**:
- GitHub: [https://github.com/ITStudioOUC](https://github.com/ITStudioOUC)
- Email: contact@itstudio.club

## 🤝 贡献

欢迎提交 Issue 和 Pull Request！

## 📄 更新日志

### v1.0.0 (2026-02-05)
- 🎉 初始版本发布
- ✅ 基础主题功能实现
- ✅ 响应式设计完成
- ✅ 主题切换功能
- ✅ 自定义文章类型（公告）

---

## 加入我们页面（/join）使用说明

主题已内置「加入我们」页面模板（`page-join.php`），并支持在没有创建 WordPress 页面时通过 `/join` 自动 fallback 渲染。

### 1. 需要安装的插件

1. **Formidable Forms**  
用于报名表单渲染（前台报名）。
2. **WP Mail SMTP**  
用于 Formidable 提交邮件发送（站点邮件走 SMTP）。

### 2. 后台时间节点设置

进入：**设置 > 招新设置**

可配置字段：
- 报名开始时间（datetime）
- 报名结束时间（datetime）
- 第一次面试开始/结束时间（datetime）
- 第一次面试地点（中/英）
- 第二次面试开始/结束时间（datetime）
- 第二次面试地点（中/英）
- 国庆能力摸底开始/结束日期（调试）
- 录取结果公布开始日期（date，系统自动延续 7 天）
- 报名表单 Shortcode

说明：
- 「国庆能力摸底阶段」固定为每年 **10/01 - 10/07**。
- 若填写“国庆能力摸底开始/结束日期（调试）”，将优先使用调试时间。

### 3. 阶段结果文件配置（重点）

在同一页面中，为以下阶段上传结果文件（CSV 或 XLSX）：
- 第一次面试结果文件
- 国庆能力摸底结果文件
- 第二次面试结果文件
- 录取结果文件

文件列顺序必须为：

`姓名,QQ,邮箱,学号,手机,是否通过`

其中：
- `是否通过 = 1` 表示通过
- 其他任意值（含 `0`、空）都按未通过处理

注意：
- 不需要配置任何“字段映射”或“阶段识别字段”。
- 系统会自动按上述固定列进行识别与查询。

### 4. 前台显示逻辑

- 报名表单：仅在报名阶段显示。
- 录取进度查询：报名阶段不显示；报名结束后按当前阶段显示对应结果（使用姓名/QQ/邮箱/学号查询）。
- Canvas 进度条：根据当前时间自动高亮阶段（已完成/进行中/未开始）。

### 5. WP Mail SMTP 配置建议

1. 安装并启用 **WP Mail SMTP**。
2. 在插件中填写 SMTP 服务信息（Host、Port、加密方式、账号密码）。
3. 设置发件人邮箱与发件人名称。
4. 使用插件自带发送测试邮件，确认可达后再开放报名。

## 加入我们页面快速说明

- 后台路径：`设置 -> 招新设置`。
- 先配置时间节点：报名开始/结束、一面开始/结束、二面开始/结束、录取结果公布开始日期。
- “国庆能力摸底”默认是每年 `10/01 - 10/07`（可用调试日期覆盖）。
- 报名表单短代码使用 Formidable 表单，邮件发送建议配合 WP Mail SMTP。
- 阶段结果统一使用 CSV/XLSX 上传，不再使用 Formidable Entries 做结果查询。
