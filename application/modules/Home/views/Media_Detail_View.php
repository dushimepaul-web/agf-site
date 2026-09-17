<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= htmlspecialchars($media['titre'] ?? 'Media') ?> - <?= $this->Model->get_setting('site_name', 'A.G.F') ?></title>
    <meta property="og:title" content="<?= htmlspecialchars($media['titre'] ?? '') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($media['description'] ?? $media['credits'] ?? '') ?>">
    <meta property="og:image" content="<?= $media['thumbnail_url'] ?? base_url('assets/images/default-share.jpg') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="video.other">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#09090b">
    <link rel="icon" href="<?= base_url($this->Model->get_setting('favicon_ico', 'assets/fro.png')) ?>" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>">

    <style>
        :root {
            --bg-primary: #09090b;
            --bg-secondary: #111113;
            --bg-tertiary: #18181b;
            --bg-card: #1c1c1f;
            --bg-hover: #27272a;
            --bg-glass: rgba(17, 17, 19, 0.88);
            --text-primary: #fafafa;
            --text-secondary: #a1a1aa;
            --text-tertiary: #71717a;
            --accent-green: #10b981;
            --accent-green-glow: rgba(16, 185, 129, 0.25);
            --accent-blue: #3b82f6;
            --accent-blue-glow: rgba(59, 130, 246, 0.2);
            --accent-red: #ef4444;
            --accent-gradient: linear-gradient(135deg, #10b981, #059669);
            --border-color: rgba(255, 255, 255, 0.06);
            --border-light: rgba(255, 255, 255, 0.1);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.4);
            --shadow: 0 4px 20px rgba(0,0,0,0.5);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.6);
            --shadow-glow: 0 0 30px rgba(16, 185, 129, 0.12);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.18s ease;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            line-height: 1.5;
            top: 0 !important;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-tertiary); }
        ::-webkit-scrollbar-thumb { background: var(--text-tertiary); border-radius: 10px; }

        .goog-te-banner-frame, .goog-te-banner, .skiptranslate {
            display: none !important; height: 0 !important; visibility: hidden !important;
            position: absolute !important; top: -9999px !important;
        }

        /* NAVBAR */
        .navbar {
            background: var(--bg-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            position: sticky; top: 0; z-index: 1000; height: 60px; padding: 0 1.25rem;
        }
        .navbar .container-fluid {
            display: flex; align-items: center; justify-content: space-between;
            height: 100%; max-width: 1600px; margin: 0 auto; gap: 1rem;
        }
        .navbar-brand { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .navbar-brand img { height: 32px; width: auto; }
        .brand-name { font-weight: 700; font-size: 1.15rem; color: var(--text-primary); letter-spacing: -0.5px; }
        .brand-badge {
            background: var(--accent-gradient); padding: 2px 10px; border-radius: 20px;
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;
        }
        .nav-right-group { display: flex; align-items: center; gap: 8px; }
        .nav-home-btn {
            display: flex; align-items: center; gap: 6px; padding: 7px 14px;
            background: var(--bg-tertiary); border: 1px solid var(--border-color);
            border-radius: 30px; color: var(--text-primary); text-decoration: none;
            font-size: 12px; font-weight: 500; transition: var(--transition-fast);
        }
        .nav-home-btn:hover { background: var(--accent-blue); border-color: var(--accent-blue); color: #fff; transform: translateY(-1px); }
        .nav-home-btn i { font-size: 14px; }

        /* Theme Toggle */
        .theme-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--bg-tertiary); border: 1px solid var(--border-color);
            color: var(--text-primary); cursor: pointer; transition: var(--transition-fast);
            font-size: 0.95rem;
        }
        .theme-toggle:hover { background: var(--bg-hover); transform: rotate(20deg); }

        /* Light Theme */
        body.light-mode {
            --bg-primary: #f8f9fa;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f1f3f5;
            --bg-card: #ffffff;
            --bg-hover: #e9ecef;
            --bg-glass: rgba(255, 255, 255, 0.88);
            --text-primary: #1a1a1a;
            --text-secondary: #495057;
            --text-tertiary: #868e96;
            --accent-green: #059669;
            --accent-green-glow: rgba(5, 150, 105, 0.15);
            --accent-blue: #2563eb;
            --accent-blue-glow: rgba(37, 99, 235, 0.12);
            --border-color: rgba(0, 0, 0, 0.08);
            --border-light: rgba(0, 0, 0, 0.12);
            --shadow: 0 4px 16px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.1);
        }
        body.light-mode .description-box { background: #fff; border-color: rgba(0,0,0,0.08); }
        body.light-mode .description-text { color: #495057; }
        body.light-mode .related-title-sm { color: #1a1a1a; }
        body.light-mode .related-meta { color: #868e96; }
        body.light-mode .video-wrapper { box-shadow: 0 12px 40px rgba(0,0,0,0.1); }
        body.light-mode .image-viewer { box-shadow: 0 12px 40px rgba(0,0,0,0.1); }
        body.light-mode .not-found-box { background: #fff; border-color: rgba(0,0,0,0.08); }
        body.light-mode .action-btn { background: #f1f3f5; border-color: rgba(0,0,0,0.08); color: #495057; }
        body.light-mode .action-btn:hover { background: #e9ecef; color: #1a1a1a; }

        /* Language */
        .lang-selector-custom { position: relative; }
        .custom-language-btn {
            display: flex; align-items: center; gap: 6px; padding: 7px 10px;
            background: var(--bg-tertiary); border: 1px solid var(--border-color);
            border-radius: 30px; cursor: pointer; font-size: 12px; font-weight: 500;
            color: var(--text-primary); transition: var(--transition-fast);
        }
        .custom-language-btn:hover { border-color: var(--accent-blue); background: var(--bg-hover); }
        .custom-language-btn img { width: 18px; height: 14px; border-radius: 3px; }
        .custom-language-dropdown {
            position: absolute; top: 100%; right: 0; margin-top: 8px;
            background: var(--bg-tertiary); border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg); padding: 6px; min-width: 200px;
            opacity: 0; visibility: hidden; transform: translateY(-8px);
            transition: var(--transition); z-index: 1000; border: 1px solid var(--border-color);
            max-height: 380px; overflow-y: auto;
        }
        .custom-language-dropdown.active { opacity: 1; visibility: visible; transform: translateY(0); }
        .lang-option {
            display: flex !important; align-items: center !important; gap: 10px;
            padding: 8px 12px; border-radius: 8px; width: 100%; border: none;
            background: transparent; cursor: pointer; font-size: 12px; font-weight: 500;
            color: var(--text-primary); transition: var(--transition-fast);
        }
        .lang-option:hover { background: var(--bg-hover); color: var(--accent-blue); }
        .lang-option img { width: 20px; height: 15px; border-radius: 3px; }

        /* MAIN */
        .main-content { max-width: 1600px; margin: 0 auto; padding: 1.5rem; }
        .watch-layout { display: flex; flex-direction: column; gap: 1.5rem; }
        @media (min-width: 1024px) {
            .watch-layout { flex-direction: row; }
            .video-column { flex: 2.5; min-width: 0; }
            .suggestions-column { flex: 1.2; min-width: 0; }
        }

        /* VIDEO WRAPPER */
        .video-wrapper {
            position: relative; background: #000; border-radius: var(--radius-xl);
            overflow: hidden; box-shadow: var(--shadow-lg); aspect-ratio: 16 / 9;
        }
        .video-wrapper iframe, .video-wrapper video {
            width: 100%; height: 100%; border: none; object-fit: contain;
        }
        .download-floating {
            position: absolute; bottom: 1rem; right: 1rem; z-index: 10;
            background: rgba(0,0,0,0.7); backdrop-filter: blur(8px);
            border: none; border-radius: 50%; width: 42px; height: 42px;
            display: flex; align-items: center; justify-content: center;
            color: white; cursor: pointer; transition: var(--transition-fast);
            opacity: 0;
        }
        .video-wrapper:hover .download-floating { opacity: 1; }
        .download-floating:hover { background: var(--accent-green); transform: scale(1.08); }

        /* IMAGE VIEWER */
        .image-viewer {
            background: #000; border-radius: var(--radius-xl); overflow: hidden;
            box-shadow: var(--shadow-lg); position: relative; display: flex;
            align-items: center; justify-content: center; min-height: 400px;
            max-height: 70vh;
        }
        .image-viewer img {
            max-width: 100%; max-height: 70vh; object-fit: contain;
            transition: transform 0.4s ease; cursor: zoom-in;
        }
        .image-viewer:hover img { transform: scale(1.02); }
        .image-toolbar {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.85));
            padding: 1.25rem; display: flex; align-items: center; justify-content: space-between;
            opacity: 0; transition: var(--transition);
        }
        .image-viewer:hover .image-toolbar { opacity: 1; }
        .image-toolbar-actions { display: flex; gap: 8px; }
        .image-toolbar-btn {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px 16px; background: rgba(255,255,255,0.12); backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.15); border-radius: 30px;
            color: white; font-size: 13px; font-weight: 500; cursor: pointer;
            transition: var(--transition-fast); text-decoration: none;
        }
        .image-toolbar-btn:hover { background: var(--accent-green); border-color: var(--accent-green); color: #fff; }
        .image-toolbar-btn i { font-size: 14px; }

        /* VIDEO INFO */
        .video-title {
            font-size: 1.4rem; font-weight: 700; margin: 1.25rem 0 0.5rem;
            line-height: 1.35; letter-spacing: -0.3px;
        }
        .video-meta-bar {
            display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center;
            gap: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);
            margin-bottom: 1rem;
        }
        .video-stats {
            display: flex; gap: 1rem; color: var(--text-secondary); font-size: 0.85rem;
        }
        .video-stats i { margin-right: 0.25rem; font-size: 0.8rem; }
        .action-buttons { display: flex; gap: 6px; flex-wrap: wrap; }
        .action-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; background: var(--bg-tertiary);
            border: 1px solid var(--border-color); border-radius: 30px;
            color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;
            cursor: pointer; transition: var(--transition-fast);
        }
        .action-btn:hover { background: var(--bg-hover); color: var(--text-primary); border-color: var(--border-light); }
        .action-btn i { font-size: 14px; }

        /* DESCRIPTION */
        .description-box {
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: var(--radius-md); padding: 1rem; margin: 1rem 0;
            cursor: pointer; transition: var(--transition-fast);
        }
        .description-box:hover { background: var(--bg-hover); border-color: var(--border-light); }
        .description-text {
            color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6;
        }
        .description-text:not(.expanded) {
            display: -webkit-box; -webkit-line-clamp: 3;
            -webkit-box-orient: vertical; overflow: hidden;
        }
        .description-text.expanded { white-space: normal; }
        .description-toggle {
            display: inline-flex; align-items: center; gap: 4px;
            margin-top: 0.5rem; color: var(--text-tertiary); font-size: 0.8rem; font-weight: 600;
            transition: var(--transition-fast);
        }
        .description-toggle:hover { color: var(--accent-blue); }

        /* AUDIO PLAYER */
        .audio-player {
            background: linear-gradient(135deg, #1a1a2e, #16213e);
            border-radius: var(--radius-xl); padding: 2.5rem 2rem; text-align: center;
            box-shadow: var(--shadow-lg); position: relative; overflow: hidden;
        }
        .audio-player::before {
            content: ''; position: absolute; top: -40%; right: -20%;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.12), transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .audio-cover {
            width: 180px; height: 180px; border-radius: var(--radius-lg);
            margin: 0 auto 1.25rem; background-size: cover; background-position: center;
            box-shadow: 0 8px 30px rgba(0,0,0,0.4); position: relative; z-index: 1;
        }
        .audio-title { font-size: 1.2rem; font-weight: 700; margin-bottom: 0.25rem; position: relative; z-index: 1; }
        .audio-artist { color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem; position: relative; z-index: 1; }
        .audio-controls { display: flex; align-items: center; justify-content: center; gap: 1.5rem; margin: 1.5rem 0; position: relative; z-index: 1; }
        .audio-btn {
            width: 48px; height: 48px; border-radius: 50%;
            background: rgba(255,255,255,0.08); border: none; color: white;
            cursor: pointer; transition: var(--transition-fast); font-size: 1.2rem;
            display: flex; align-items: center; justify-content: center;
        }
        .audio-btn:hover { transform: scale(1.1); background: rgba(255,255,255,0.15); }
        .audio-btn.play-pause { width: 56px; height: 56px; background: var(--accent-green); font-size: 1.5rem; }
        .audio-btn.play-pause:hover { background: #059669; box-shadow: 0 0 25px var(--accent-green-glow); }
        .progress-container { position: relative; z-index: 1; max-width: 400px; margin: 0 auto; }
        .progress-bar-track {
            width: 100%; height: 4px; background: rgba(255,255,255,0.15);
            border-radius: 2px; cursor: pointer; overflow: hidden;
        }
        .progress-bar-fill {
            background: var(--accent-green); height: 100%; width: 0%;
            transition: width 0.1s linear; border-radius: 2px;
        }
        .progress-time { display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-tertiary); margin-top: 6px; font-variant-numeric: tabular-nums; }

        /* SUGGESTIONS */
        .suggestions-title {
            font-size: 0.95rem; font-weight: 700; margin-bottom: 1rem;
            display: flex; align-items: center; gap: 8px; color: var(--text-primary);
        }
        .suggestions-title i { color: var(--accent-green); }
        .related-item {
            display: flex; gap: 0.75rem; margin-bottom: 0.75rem; cursor: pointer;
            transition: var(--transition-fast); padding: 8px; border-radius: var(--radius-md);
        }
        .related-item:hover { background: var(--bg-hover); transform: translateX(4px); }
        .related-thumb {
            width: 168px; height: 94px; border-radius: var(--radius-sm);
            background-size: cover; background-position: center; flex-shrink: 0;
        }
        .related-info { flex: 1; min-width: 0; display: flex; flex-direction: column; justify-content: center; }
        .related-title-sm {
            font-size: 0.85rem; font-weight: 600; margin-bottom: 4px; line-height: 1.3;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .related-meta { font-size: 0.72rem; color: var(--text-tertiary); display: flex; align-items: center; gap: 4px; }
        .related-meta i { font-size: 0.65rem; }

        /* TOAST */
        .toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 9999; }
        .toast-custom {
            background: var(--bg-card); border: 1px solid var(--border-color);
            color: white; padding: 10px 16px; border-radius: var(--radius-sm);
            margin-top: 8px; animation: slideIn 0.3s ease; display: flex;
            align-items: center; gap: 8px; font-size: 13px; font-weight: 500;
            box-shadow: var(--shadow);
        }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

        /* NOT FOUND */
        .not-found-box {
            background: var(--bg-card); border: 1px solid var(--border-color);
            border-radius: var(--radius-xl); padding: 4rem 2rem; text-align: center;
            max-width: 480px; margin: 3rem auto;
        }
        .not-found-box i { font-size: 4rem; color: var(--text-tertiary); opacity: 0.5; }
        .not-found-box h3 { margin-top: 1rem; font-weight: 700; }
        .not-found-box p { color: var(--text-secondary); margin-top: 0.5rem; font-size: 0.9rem; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 1.5rem; padding: 10px 24px;
            background: var(--accent-gradient); color: #fff; border: none;
            border-radius: 30px; font-size: 0.875rem; font-weight: 600;
            text-decoration: none; transition: var(--transition-fast);
        }
        .btn-back:hover { transform: translateY(-2px); box-shadow: var(--shadow-glow); color: #fff; }

        /* RESPONSIVE */
        @media (max-width: 1023px) {
            .main-content { padding: 1rem; }
            .video-title { font-size: 1.2rem; }
            .related-item { flex-direction: column; }
            .related-thumb { width: 100%; aspect-ratio: 16/9; height: auto; }
        }
        @media (max-width: 768px) {
            .navbar { padding: 0 1rem; height: 56px; }
            .main-content { padding: 0.75rem; }
            .video-title { font-size: 1.05rem; margin-top: 0.75rem; }
            .action-btn span { display: none; }
            .action-btn { padding: 8px 12px; }
            .video-wrapper { border-radius: var(--radius-lg); }
            .image-viewer { border-radius: var(--radius-lg); min-height: 280px; }
            .audio-player { padding: 1.5rem 1rem; }
            .audio-cover { width: 140px; height: 140px; }
            .brand-badge { display: none; }
            .navbar-brand img { height: 26px; }
            .brand-name { font-size: 1rem; }
            .nav-home-btn span { display: none; }
            .nav-home-btn { padding: 7px 10px; }
            .suggestions-title { font-size: 0.85rem; }
            .related-title-sm { font-size: 0.8rem; }
            .video-meta-bar { gap: 0.75rem; }
            .video-stats { font-size: 0.8rem; gap: 0.75rem; }
        }
        @media (max-width: 480px) {
            .main-content { padding: 0.5rem; }
            .video-title { font-size: 0.95rem; }
            .audio-cover { width: 120px; height: 120px; }
            .audio-controls { gap: 1rem; }
            .action-btn { padding: 6px 10px; font-size: 0.8rem; }
        }

        /* ANIMATIONS */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .watch-layout > * { animation: fadeInUp 0.5s ease forwards; }
        .suggestions-column { animation-delay: 0.15s; }
    </style>

    <script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,fr,rn,sw,ar,de,es,pt,it,zh-CN,ru,nl,pl,tr,ja,ko,hi,vi',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>
<body>

<script>
(function() {
    var CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';
    var CSRF_HASH = '<?= $this->security->get_csrf_hash() ?>';
    if (!CSRF_HASH || typeof window.fetch !== 'function' || window.__csrfFetchPatched) return;
    window.__csrfFetchPatched = true;
    var origFetch = window.fetch;
    window.fetch = function(url, options) {
        options = options || {};
        if ((options.method || 'GET').toUpperCase() === 'POST') {
            if (!options.headers) options.headers = {};
            if (typeof options.headers.set === 'function' && !options.headers.has('X-CSRF-TOKEN')) {
                options.headers.set('X-CSRF-TOKEN', CSRF_HASH);
            } else if (typeof options.headers === 'object') {
                options.headers['X-CSRF-TOKEN'] = CSRF_HASH;
            }
        }
        return origFetch.call(this, url, options);
    };
})();
</script>

<div id="google_translate_element" style="display: none;"></div>

<!-- Navbar -->
<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('media') ?>">
            <?php $site_logo = $this->Model->get_setting('site_logo'); if (!empty($site_logo)): ?>
                <img src="<?= base_url($site_logo) ?>" alt="<?= htmlspecialchars($this->Model->get_setting('site_name', 'A.G.F')) ?>">
            <?php endif; ?>
            <span class="brand-name"><?= htmlspecialchars($this->Model->get_setting('site_name', 'A.G.F')) ?></span>
            <span class="brand-badge">MEDIA</span>
        </a>

        <div class="nav-right-group">
            <a href="<?= base_url('media') ?>" class="nav-home-btn">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>

            <div class="lang-selector-custom">
                <button class="custom-language-btn" id="customLanguageBtn">
                    <img src="https://flagcdn.com/w20/us.png" alt="EN" id="currentLangFlag">
                    <span class="d-none d-sm-inline" id="currentLangLabel">EN</span>
                    <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                </button>
                <div class="custom-language-dropdown" id="customLanguageDropdown">
                    <button class="lang-option" data-lang="fr" data-flag="fr" data-label="Français"><img src="https://flagcdn.com/w20/fr.png"> Français</button>
                    <button class="lang-option" data-lang="en" data-flag="us" data-label="English"><img src="https://flagcdn.com/w20/us.png"> English</button>
                    <button class="lang-option" data-lang="rn" data-flag="bi" data-label="Kirundi"><img src="https://flagcdn.com/w20/bi.png"> Kirundi</button>
                    <button class="lang-option" data-lang="sw" data-flag="tz" data-label="Kiswahili"><img src="https://flagcdn.com/w20/tz.png"> Kiswahili</button>
                    <button class="lang-option" data-lang="ar" data-flag="sa" data-label="العربية"><img src="https://flagcdn.com/w20/sa.png"> العربية</button>
                    <button class="lang-option" data-lang="de" data-flag="de" data-label="Deutsch"><img src="https://flagcdn.com/w20/de.png"> Deutsch</button>
                    <button class="lang-option" data-lang="es" data-flag="es" data-label="Español"><img src="https://flagcdn.com/w20/es.png"> Español</button>
                    <button class="lang-option" data-lang="pt" data-flag="pt" data-label="Português"><img src="https://flagcdn.com/w20/pt.png"> Português</button>
                    <button class="lang-option" data-lang="it" data-flag="it" data-label="Italiano"><img src="https://flagcdn.com/w20/it.png"> Italiano</button>
                    <button class="lang-option" data-lang="zh-CN" data-flag="cn" data-label="中文"><img src="https://flagcdn.com/w20/cn.png"> 中文</button>
                    <button class="lang-option" data-lang="ru" data-flag="ru" data-label="Русский"><img src="https://flagcdn.com/w20/ru.png"> Русский</button>
                </div>
            </div>
        </div>
    </div>
</nav>

<main class="main-content">
    <?php
    function formatFileSize($bytes) {
        if (!$bytes) return '';
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 1) . ' Go';
        if ($bytes >= 1048576) return number_format($bytes / 1048576, 1) . ' Mo';
        if ($bytes >= 1024) return number_format($bytes / 1024, 1) . ' Ko';
        return $bytes . ' octets';
    }

    if ($media):
        $mediaSlug = !empty($media['slug']) ? $media['slug'] : $media['id_media'];
        $type = $media['type'] ?? 'autre';
        $fichier = $media['fichier_url'] ?? '';
        $youtube_id = $media['youtube_id'] ?? '';
        $lien = $media['lien'] ?? '';

        if (!empty($fichier) && !preg_match('/^https?:\/\//', $fichier)) {
            $fichier = base_url($fichier);
        }

        if (empty($youtube_id) && !empty($lien)) {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $lien, $matches);
            $youtube_id = $matches[1] ?? '';
        }

        if (empty($type) || $type === 'autre') {
            $mime = strtolower((string)($media['mime_type'] ?? ''));
            $ext = strtolower(pathinfo(parse_url($fichier, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
            if (($media['sous_type'] ?? '') === 'book' || strpos($mime, 'pdf') !== false || in_array($ext, ['pdf','doc','docx','rtf','ppt','pptx','xls','xlsx','epub'])) {
                $type = 'document';
            } elseif (strpos($mime, 'image') !== false || in_array($ext, ['jpg','jpeg','png','gif','webp','svg','bmp'])) {
                $type = 'image';
            } elseif (strpos($mime, 'audio') !== false || in_array($ext, ['mp3','wav','ogg','m4a','aac','flac'])) {
                $type = 'audio';
            } elseif (strpos($mime, 'video') !== false || in_array($ext, ['mp4','webm','avi','mov','m4v'])) {
                $type = 'video';
            }
        }

        $is_youtube_link = !empty($youtube_id);
        $is_downloadable = in_array($type, ['video','audio','image','document']) && !empty($media['fichier']);
    ?>
        <div class="watch-layout">
            <div class="video-column">
                <?php if ($is_youtube_link): ?>
                    <div class="video-wrapper">
                        <iframe src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($youtube_id) ?>?autoplay=1&rel=0&modestbranding=1&controls=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                    </div>

                <?php elseif ($type === 'video' && !empty($fichier)): ?>
                    <div class="video-wrapper">
                        <video controls autoplay playsinline>
                            <source src="<?= htmlspecialchars($fichier) ?>" type="video/mp4">
                        </video>
                        <button class="download-floating" onclick="downloadMedia('<?= htmlspecialchars($mediaSlug) ?>')">
                            <i class="bi bi-download"></i>
                        </button>
                    </div>

                <?php elseif ($type === 'audio' && !empty($fichier)): ?>
                    <div class="audio-player">
                        <div class="audio-cover" style="background-image: url('<?= htmlspecialchars($media['cover_url'] ?? base_url('assets/backend/images/defaut-mignature-audio.jpeg')) ?>')"></div>
                        <div class="audio-title"><?= htmlspecialchars($media['titre']) ?></div>
                        <div class="audio-artist"><?= htmlspecialchars($media['artist'] ?? $media['credits'] ?? 'Artiste') ?></div>
                        <audio id="audioElement" src="<?= htmlspecialchars($fichier) ?>" preload="auto"></audio>
                        <div class="audio-controls">
                            <button class="audio-btn" onclick="previousTrack()"><i class="bi bi-skip-backward-fill"></i></button>
                            <button class="audio-btn play-pause" id="playPauseBtn" onclick="togglePlay()"><i class="bi bi-play-fill"></i></button>
                            <button class="audio-btn" onclick="nextTrack()"><i class="bi bi-skip-forward-fill"></i></button>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar-track" onclick="seekAudio(event)">
                                <div class="progress-bar-fill" id="progressFill"></div>
                            </div>
                            <div class="progress-time">
                                <span id="currentTime">0:00</span>
                                <span id="totalTime">0:00</span>
                            </div>
                        </div>
                    </div>

                <?php elseif ($type === 'image' && !empty($fichier)): ?>
                    <div class="image-viewer">
                        <img src="<?= htmlspecialchars($fichier) ?>" alt="<?= htmlspecialchars($media['titre']) ?>" id="detailImage">
                        <div class="image-toolbar">
                            <div style="color:rgba(255,255,255,0.8);font-size:13px;">
                                <i class="bi bi-image"></i> <?= htmlspecialchars($media['titre']) ?>
                            </div>
                            <div class="image-toolbar-actions">
                                <a href="<?= htmlspecialchars($fichier) ?>" download class="image-toolbar-btn">
                                    <i class="bi bi-download"></i> Télécharger
                                </a>
                                <button class="image-toolbar-btn" onclick="shareMedia()">
                                    <i class="bi bi-share"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                <?php elseif ($type === 'document' && !empty($fichier)): ?>
                    <div class="document-viewer" id="documentViewer" style="background:var(--bg-card);border-radius:var(--radius-xl);overflow:hidden;box-shadow:var(--shadow-lg);">
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;background:var(--bg-tertiary);border-bottom:1px solid var(--border-color);flex-wrap:wrap;gap:8px;">
                            <div style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:14px;">
                                <i class="bi bi-file-earmark-text" style="color:var(--accent-green);"></i>
                                <span style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($media['titre']) ?></span>
                            </div>
                            <div style="display:flex;gap:6px;">
                                <button onclick="toggleDocFullscreen()" title="Plein écran" style="width:32px;height:32px;border-radius:8px;background:var(--bg-hover);border:1px solid var(--border-color);color:var(--text-primary);cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="bi bi-arrows-fullscreen"></i></button>
                                <button onclick="downloadMedia('<?= htmlspecialchars($mediaSlug) ?>')" title="Télécharger" style="width:32px;height:32px;border-radius:8px;background:var(--bg-hover);border:1px solid var(--border-color);color:var(--text-primary);cursor:pointer;display:flex;align-items:center;justify-content:center;"><i class="bi bi-download"></i></button>
                                <a href="<?= htmlspecialchars($fichier) ?>" target="_blank" title="Ouvrir" style="width:32px;height:32px;border-radius:8px;background:var(--bg-hover);border:1px solid var(--border-color);color:var(--text-primary);display:flex;align-items:center;justify-content:center;text-decoration:none;"><i class="bi bi-box-arrow-up-right"></i></a>
                            </div>
                        </div>
                        <iframe id="docFrame" src="<?= htmlspecialchars($fichier) ?>#toolbar=1&navpanes=1&view=FitH" style="width:100%;height:75vh;min-height:400px;border:none;background:#fff;" loading="eager"></iframe>
                    </div>
                    <script>
                    function toggleDocFullscreen() {
                        var el = document.getElementById('documentViewer');
                        if (!el) return;
                        if (!document.fullscreenElement) {
                            (el.requestFullscreen || el.webkitRequestFullscreen).call(el);
                        } else {
                            (document.exitFullscreen || document.webkitExitFullscreen).call(document);
                        }
                    }
                    </script>
                <?php endif; ?>

                <!-- Title + Meta -->
                <h1 class="video-title"><?= htmlspecialchars($media['titre']) ?></h1>
                <div class="video-meta-bar">
                    <div class="video-stats">
                        <span><i class="bi bi-eye"></i> <?= number_format($media['views_count'] ?? 0) ?> vues</span>
                        <?php if (!empty($media['date_media'])): ?>
                            <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($media['date_media'])) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn" onclick="shareMedia()">
                            <i class="bi bi-share"></i> <span>Partager</span>
                        </button>
                        <?php if ($is_downloadable): ?>
                            <button class="action-btn" onclick="downloadMedia('<?= htmlspecialchars($mediaSlug) ?>')">
                                <i class="bi bi-download"></i> <span>Télécharger</span>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Description -->
                <div class="description-box" onclick="toggleDescription()">
                    <div class="description-text" id="descriptionText">
                        <?= nl2br(htmlspecialchars($media['description'] ?? 'Aucune description')) ?>
                    </div>
                    <div class="description-toggle" id="descriptionToggle">
                        <span>Afficher plus</span>
                        <i class="bi bi-chevron-down" style="font-size:10px;"></i>
                    </div>
                </div>
            </div>

            <!-- Suggestions -->
            <div class="suggestions-column">
                <div class="suggestions-title"><i class="bi bi-collection-play"></i> À regarder ensuite</div>
                <?php if (!empty($recommended)): ?>
                    <?php foreach($recommended as $related):
                        $relatedSlug = !empty($related['slug']) ? $related['slug'] : $related['id_media'];
                    ?>
                        <div class="related-item" onclick="window.location.href='<?= base_url('media/detail/'.$relatedSlug) ?>'">
                            <div class="related-thumb" style="background-image: url('<?= htmlspecialchars($related['thumbnail_url'] ?? base_url('assets/backend/images/default-avatar.jpg')) ?>')"></div>
                            <div class="related-info">
                                <p class="related-title-sm"><?= htmlspecialchars($related['titre']) ?></p>
                                <div class="related-meta">
                                    <i class="bi bi-person"></i> <?= htmlspecialchars($related['credits'] ?? 'A.G.F') ?>
                                    &bull;
                                    <i class="bi bi-eye"></i> <?= number_format($related['views_count'] ?? 0) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align:center;padding:3rem 1rem;color:var(--text-tertiary);">
                        <i class="bi bi-collection-play" style="font-size:2.5rem;opacity:0.4;display:block;margin-bottom:0.75rem;"></i>
                        <p style="font-size:0.85rem;">Aucune suggestion</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>
        <div class="not-found-box">
            <i class="bi bi-exclamation-triangle"></i>
            <h3>Média non trouvé</h3>
            <p>Le média que vous recherchez n'existe pas ou a été supprimé.</p>
            <a href="<?= base_url('media') ?>" class="btn-back">
                <i class="bi bi-house"></i> Retour à l'accueil
            </a>
        </div>
    <?php endif; ?>
</main>

<div class="toast-container" id="toastContainer"></div>

<script>
const mediaId = <?= (int)($media['id_media'] ?? 0) ?>;
const mediaSlug = '<?= htmlspecialchars($mediaSlug ?? '') ?>';
const baseUrl = '<?= rtrim(base_url(), '/') ?>';

const playlist = <?= json_encode($playlist ?? [], JSON_UNESCAPED_UNICODE) ?>;
let currentTrackIndex = -1;
playlist.forEach((item, i) => {
    if (String(item.slug || item.id_media) === String(mediaSlug)) currentTrackIndex = i;
});

let audioElement = document.getElementById('audioElement');
let isPlaying = false;

if (audioElement) {
    audioElement.addEventListener('timeupdate', updateProgress);
    audioElement.addEventListener('ended', () => { isPlaying = false; updatePlayButton(); nextTrack(); });
    audioElement.addEventListener('loadedmetadata', () => {
        const t = document.getElementById('totalTime');
        if (t) t.textContent = formatTime(audioElement.duration);
    });
    audioElement.addEventListener('canplay', function onCanPlay() {
        audioElement.removeEventListener('canplay', onCanPlay);
        audioElement.play().then(() => { isPlaying = true; updatePlayButton(); }).catch(() => {
            const unlock = () => { audioElement.play().then(() => { isPlaying = true; updatePlayButton(); }).catch(() =>{}); document.removeEventListener('click', unlock); document.removeEventListener('touchstart', unlock); };
            document.addEventListener('click', unlock);
            document.addEventListener('touchstart', unlock);
        });
    });
}

function togglePlay() {
    if (!audioElement) return;
    if (isPlaying) audioElement.pause(); else audioElement.play();
    isPlaying = !isPlaying;
    updatePlayButton();
}
function updatePlayButton() {
    const btn = document.getElementById('playPauseBtn');
    if (btn) btn.innerHTML = isPlaying ? '<i class="bi bi-pause-fill"></i>' : '<i class="bi bi-play-fill"></i>';
}
function updateProgress() {
    if (!audioElement) return;
    const pct = (audioElement.currentTime / audioElement.duration) * 100;
    const fill = document.getElementById('progressFill');
    if (fill) fill.style.width = pct + '%';
    const ct = document.getElementById('currentTime');
    if (ct) ct.textContent = formatTime(audioElement.currentTime);
}
function formatTime(s) {
    if (isNaN(s) || !isFinite(s)) return '0:00';
    const m = Math.floor(s / 60), sec = Math.floor(s % 60);
    return m + ':' + sec.toString().padStart(2, '0');
}
function seekAudio(e) {
    if (!audioElement) return;
    const r = e.currentTarget.getBoundingClientRect();
    audioElement.currentTime = ((e.clientX - r.left) / r.width) * audioElement.duration;
}
function previousTrack() {
    if (!playlist.length) return;
    const i = currentTrackIndex > 0 ? currentTrackIndex - 1 : playlist.length - 1;
    const item = playlist[i];
    if (item) window.location.href = baseUrl + '/media/detail/' + (item.slug || item.id_media);
}
function nextTrack() {
    if (!playlist.length) return;
    const i = currentTrackIndex < playlist.length - 1 ? currentTrackIndex + 1 : 0;
    const item = playlist[i];
    if (item) window.location.href = baseUrl + '/media/detail/' + (item.slug || item.id_media);
}

function downloadMedia(identifier) {
    const isNum = !isNaN(identifier) && !isNaN(parseFloat(identifier));
    const p = isNum ? 'id' : 'slug';
    const a = document.createElement('a');
    a.href = baseUrl + '/media/downloader?' + p + '=' + encodeURIComponent(identifier);
    a.setAttribute('download', '');
    a.click();
    showToast('Téléchargement démarré', 'success');
}

function shareMedia() {
    if (navigator.share) {
        navigator.share({ title: '<?= htmlspecialchars($media["titre"] ?? "") ?>', url: window.location.href }).catch(() => copyToClipboard());
    } else { copyToClipboard(); }
}
function copyToClipboard() {
    navigator.clipboard.writeText(window.location.href);
    showToast('Lien copié !', 'success');
}

let descExpanded = false;
function toggleDescription() {
    const d = document.getElementById('descriptionText');
    const t = document.getElementById('descriptionToggle');
    if (!d) return;
    descExpanded = !descExpanded;
    d.classList.toggle('expanded', descExpanded);
    t.querySelector('span').textContent = descExpanded ? 'Afficher moins' : 'Afficher plus';
    t.querySelector('i').style.transform = descExpanded ? 'rotate(180deg)' : 'none';
}

function showToast(msg, type) {
    const c = document.getElementById('toastContainer');
    const t = document.createElement('div');
    t.className = 'toast-custom';
    const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-info-circle-fill';
    const bg = type === 'success' ? '#059669' : 'var(--bg-card)';
    t.style.background = bg;
    t.innerHTML = '<i class="bi ' + icon + '"></i><span>' + msg + '</span>';
    c.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

if (mediaId) {
    fetch(baseUrl + '/media/apiTrackView', { method: 'POST', headers: {'Content-Type': 'application/x-www-form-urlencoded'}, body: 'id_media=' + mediaId }).catch(() => {});
}

// Language
const langBtn = document.getElementById('customLanguageBtn');
const langDropdown = document.getElementById('customLanguageDropdown');

function clearGoogtransCookies() {
    document.cookie.split(';').forEach(c => {
        if (c.trim().startsWith('googtrans=')) {
            const n = c.trim().split('=')[0];
            document.cookie = n + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
    });
}
function setLanguageCookie(lang) {
    clearGoogtransCookies();
    if (lang !== 'en') document.cookie = 'googtrans=/en/' + lang + '; path=/; max-age=31536000';
}
function changeLanguage(langCode, flagCode, label) {
    document.getElementById('currentLangFlag').src = 'https://flagcdn.com/w20/' + flagCode + '.png';
    document.getElementById('currentLangLabel').textContent = label;
    localStorage.setItem('preferred_language', langCode);
    localStorage.setItem('preferred_flag', flagCode);
    localStorage.setItem('preferred_label', label);
    setLanguageCookie(langCode);
    langDropdown.classList.remove('active');
    setTimeout(() => location.reload(), 150);
}

if (langBtn) langBtn.addEventListener('click', e => { e.stopPropagation(); langDropdown.classList.toggle('active'); });
document.addEventListener('click', e => { if (langBtn && langDropdown && !langBtn.contains(e.target) && !langDropdown.contains(e.target)) langDropdown.classList.remove('active'); });
document.querySelectorAll('.lang-option').forEach(o => {
    o.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); changeLanguage(this.dataset.lang, this.dataset.flag, this.dataset.label); });
});

(function() {
    const sl = localStorage.getItem('preferred_language');
    const sf = localStorage.getItem('preferred_flag');
    const slabel = localStorage.getItem('preferred_label');
    if (sl && sf && slabel && sl !== 'en') {
        if (document.cookie.indexOf('googtrans=/en/' + sl) === -1) { setLanguageCookie(sl); setTimeout(() => location.reload(), 50); return; }
        document.getElementById('currentLangFlag').src = 'https://flagcdn.com/w20/' + sf + '.png';
        document.getElementById('currentLangLabel').textContent = slabel;
    }
})();

function removeGoogleTranslateBar() {
    document.querySelector('.goog-te-banner-frame')?.remove();
    document.body.style.marginTop = '0';
    document.body.style.top = '0';
    document.getElementById('google_translate_element').style.display = 'none';
}
removeGoogleTranslateBar();
setInterval(removeGoogleTranslateBar, 200);
</script>
</body>
</html>
