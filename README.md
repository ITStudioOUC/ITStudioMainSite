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
- 🎯 **自定义文章类型** - 支持公告通知独立管理

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
- 技术博客
- 便民服务
- 工作室介绍
- 加入我们

#### 2. 发布公告

主题内置了"公告通知"自定义文章类型：
1. 进入 **公告 > 新建公告**
2. 填写标题和内容
3. 发布即可在首页显示

#### 3. 技术博客

使用 WordPress 默认的文章功能发布技术博客：
1. 进入 **文章 > 写文章**
2. 撰写博客内容
3. 发布后会在首页博客栏显示

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
│   │   └── main.css          # 主样式文件
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
- GitHub: [github.com/itstudio-2002](https://github.com/itstudio-2002)
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