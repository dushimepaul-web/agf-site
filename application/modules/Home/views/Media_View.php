<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title><?= $page_title ?? 'A.G.F' ?> - Media</title>
    <?php 
                            $site_logo = $this->Model->get_setting('site_logo');
                            if (!empty($site_logo)): 
                            ?>
    <link rel="icon" href="<?= base_url($site_logo) ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url($site_logo) ?>">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>">
    
   <script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,fr,rn,sw,ar,de,es,pt,it,zh-CN,ru,nl,pl,tr,ja,ko,hi,vi,th,el,he,sv,da,no,fi,cs,hu,ro,uk',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
        autoDisplay: false
    }, 'google_translate_element');
    
    // Forcer le cache de la langue depuis le cookie
    setTimeout(function() {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = cookies[i].trim();
            if (cookie.startsWith('googtrans=')) {
                var lang = cookie.split('=')[1];
                if (lang && lang !== '/en/') {
                    var selectElement = document.querySelector('.goog-te-combo');
                    if (selectElement) {
                        var langCode = lang.split('/')[2];
                        selectElement.value = langCode;
                        selectElement.dispatchEvent(new Event('change'));
                    }
                }
                break;
            }
        }
    }, 500);
}
</script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
    <style>
        /* Variables modernes — Premium Dark Theme */
        :root {
            --bg-primary: #09090b;
            --bg-secondary: #111113;
            --bg-tertiary: #18181b;
            --bg-card: #1c1c1f;
            --bg-hover: #27272a;
            --bg-active: #2a2d32;
            --text-primary: #fafafa;
            --text-secondary: #a1a1aa;
            --text-tertiary: #71717a;
            --accent-green: #10b981;
            --accent-green-glow: rgba(16, 185, 129, 0.25);
            --accent-blue: #3b82f6;
            --accent-blue-glow: rgba(59, 130, 246, 0.2);
            --accent-red: #ef4444;
            --accent-gradient: linear-gradient(135deg, #10b981, #059669);
            --accent-gradient-blue: linear-gradient(135deg, #3b82f6, #2563eb);
            --border-color: rgba(255, 255, 255, 0.06);
            --border-light: rgba(255, 255, 255, 0.1);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.4);
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 12px 40px rgba(0, 0, 0, 0.6);
            --shadow-glow-green: 0 0 30px rgba(16, 185, 129, 0.15);
            --shadow-glow-blue: 0 0 30px rgba(59, 130, 246, 0.12);
            --transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            --transition-fast: all 0.18s ease;
            --transition-bounce: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            --glass-bg: rgba(17, 17, 19, 0.88);
            --glass-border: rgba(255, 255, 255, 0.06);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-full: 9999px;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
        
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background: var(--bg-primary); 
            color: var(--text-primary);
            overflow-x: hidden;
            top: 0 !important;
            margin-top: 0 !important;
            padding-top: 0 !important;
            line-height: 1.5;
        }

        /* Cacher l'interface Google Translate */
        .goog-te-banner-frame.skiptranslate,
        .goog-te-banner-frame,
        .goog-te-banner,
        .skiptranslate {
            display: none !important;
            height: 0 !important;
            visibility: hidden !important;
            opacity: 0 !important;
            position: absolute !important;
            top: -9999px !important;
        }
        
        body {
            top: 0 !important;
            position: relative !important;
        }

        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--text-tertiary);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* ============================================ */
        /* NAVBAR GLASSMORPHISM */
        /* ============================================ */
        .navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            padding: 0.5rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            height: 64px;
        }
        
        .navbar .container-fluid {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            gap: 1rem;
            max-width: 1600px;
            margin: 0 auto;
        }
        
        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-shrink: 0;
        }
        
        .menu-icon {
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 50%;
            transition: var(--transition-fast);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .menu-icon:hover {
            background: var(--bg-hover);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
        }
        
        .brand-logo {
            height: 36px;
            width: auto;
        }
        
        .brand-name {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--text-primary);
            letter-spacing: -0.5px;
        }
        
        .brand-subname {
            font-size: 0.7rem;
            color: var(--accent-green);
            margin-left: 0.25rem;
            font-weight: 500;
        }

        /* Search Bar */
        .search-container {
            flex: 1;
            max-width: 600px;
            margin: 0 1rem;
        }
        
        .search-form {
            display: flex;
            width: 100%;
        }
        
        .search-input {
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 40px 0 0 40px;
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            width: 100%;
            font-size: 0.9rem;
            outline: none;
            height: 42px;
            transition: var(--transition-fast);
        }
        
        .search-input:focus {
            border-color: var(--accent-blue);
            background: var(--bg-secondary);
        }
        
        .search-btn {
            background: var(--bg-hover);
            border: 1px solid var(--border-color);
            border-left: none;
            border-radius: 0 40px 40px 0;
            color: var(--text-primary);
            padding: 0 1.25rem;
            cursor: pointer;
            transition: var(--transition-fast);
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .search-btn:hover {
            background: var(--bg-tertiary);
            border-color: var(--accent-blue);
        }

        /* Live Search Dropdown */
        .search-container { position: relative; }
        .search-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: var(--bg-card, #fff);
            border: 1px solid var(--border-color, #e2e8f0);
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,.15);
            max-height: 420px;
            overflow-y: auto;
            z-index: 1000;
            min-width: 380px;
        }
        .search-dropdown.active { display: block; }
        .search-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            text-decoration: none;
            color: inherit;
            transition: background .15s;
            border-bottom: 1px solid var(--border-color, #f0f0f0);
        }
        .search-item:last-child { border-bottom: none; }
        .search-item:hover { background: var(--bg-hover, #f8f9fa); }
        .search-item-thumb {
            position: relative;
            width: 72px;
            height: 48px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
            background: #111;
        }
        .search-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .search-duration {
            position: absolute;
            bottom: 2px;
            right: 2px;
            background: rgba(0,0,0,.8);
            color: #fff;
            font-size: 10px;
            padding: 1px 4px;
            border-radius: 3px;
        }
        .search-item-info { flex: 1; min-width: 0; }
        .search-item-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary, #1a1a2e);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .search-item-title i { margin-right: 4px; opacity: .5; }
        .search-item-meta {
            font-size: 11px;
            color: var(--text-muted, #94a3b8);
            margin-top: 2px;
        }
        .search-no-result {
            padding: 24px;
            text-align: center;
            color: var(--text-muted, #94a3b8);
        }
        .search-no-result i { font-size: 24px; display: block; margin-bottom: 8px; }
        .search-loading {
            padding: 16px;
            text-align: center;
            color: var(--text-muted, #94a3b8);
            font-size: 13px;
        }

        /* Mobile Search */
        .search-toggle, .mobile-search-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
        }
        
        .search-toggle:hover, .mobile-search-toggle:hover {
            background: var(--bg-hover);
        }

        .mobile-search-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--bg-primary);
            padding: 0.75rem 1rem;
            z-index: 1002;
            align-items: center;
            gap: 0.5rem;
            height: 64px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .mobile-search-overlay.active {
            display: flex;
        }
        
        .mobile-search-overlay .search-form {
            flex: 1;
        }
        
        .back-btn {
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1.5rem;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .back-btn:hover {
            background: var(--bg-hover);
        }

        /* Language Selector Desktop */
        .lang-selector-custom {
            position: relative;
            margin-left: 8px;
        }
        
        .custom-language-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 30px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            transition: var(--transition-fast);
        }
        
        .custom-language-btn:hover {
            border-color: var(--accent-blue);
            background: var(--bg-hover);
            transform: translateY(-1px);
        }
        
        .custom-language-btn img {
            width: 20px;
            height: 15px;
            border-radius: 3px;
        }
        
        .custom-language-btn i {
            font-size: 10px;
            transition: transform 0.2s ease;
        }
        
        .custom-language-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: var(--bg-tertiary);
            border-radius: 16px;
            box-shadow: var(--shadow);
            padding: 8px;
            min-width: 220px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: var(--transition);
            z-index: 1000;
            border: 1px solid var(--border-color);
            max-height: 400px;
            overflow-y: auto;
        }
        
        .custom-language-dropdown.active {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }
        
        .lang-option {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 10px 14px !important;
            border-radius: 10px !important;
            width: 100% !important;
            border: none !important;
            background: transparent !important;
            text-align: left !important;
            cursor: pointer !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            color: var(--text-primary) !important;
            transition: var(--transition-fast) !important;
        }
        
        .lang-option:hover {
            background: var(--bg-hover) !important;
            color: var(--accent-blue) !important;
            transform: translateX(4px) !important;
        }
        
        .lang-option img {
            width: 22px !important;
            height: 16px !important;
            border-radius: 3px !important;
        }

        /* Nav Icons */
        .nav-icons {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            flex-shrink: 0;
        }
        
        .nav-icon {
            background: transparent;
            border: none;
            color: var(--text-primary);
            font-size: 1.2rem;
            padding: 0.5rem;
            cursor: pointer;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition-fast);
            position: relative;
        }
        
        .nav-icon:hover {
            background: var(--bg-hover);
        }
        
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--accent-red);
            color: white;
            font-size: 10px;
            font-weight: 700;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ============================================ */
        /* SIDEBAR MODERNE */
        /* ============================================ */

        /* Theme Toggle Button */
        .theme-toggle {
            display: flex; align-items: center; justify-content: center;
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--bg-tertiary); border: 1px solid var(--border-color);
            color: var(--text-primary); cursor: pointer; transition: var(--transition-fast);
            font-size: 1rem; flex-shrink: 0;
        }
        .theme-toggle:hover { background: var(--bg-hover); transform: rotate(20deg); }

        /* Light Theme — doux, pas trop blanc */
        body.light-mode {
            --bg-primary: #eef0f2;
            --bg-secondary: #f5f6f8;
            --bg-tertiary: #eaecf0;
            --bg-card: #f9fafb;
            --bg-hover: #e2e5e9;
            --bg-active: #d5d9de;
            --text-primary: #1f2937;
            --text-secondary: #4b5563;
            --text-tertiary: #9ca3af;
            --accent-green: #059669;
            --accent-green-glow: rgba(5, 150, 105, 0.12);
            --accent-blue: #2563eb;
            --accent-blue-glow: rgba(37, 99, 235, 0.1);
            --accent-red: #dc2626;
            --border-color: rgba(0, 0, 0, 0.06);
            --border-light: rgba(0, 0, 0, 0.1);
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
            --shadow: 0 4px 16px rgba(0,0,0,0.06);
            --shadow-hover: 0 8px 30px rgba(0,0,0,0.08);
            --shadow-lg: 0 12px 40px rgba(0,0,0,0.08);
            --glass-bg: rgba(245, 246, 248, 0.88);
            --glass-border: rgba(0, 0, 0, 0.05);
        }
        body.light-mode .sidebar { background: var(--bg-secondary); border-right: 1px solid var(--border-color); }
        body.light-mode .media-card { border: 1px solid rgba(0,0,0,0.06); background: var(--bg-card); }
        body.light-mode .search-input { background: var(--bg-tertiary); border-color: var(--border-color); color: var(--text-primary); }
        body.light-mode .search-dropdown { background: var(--bg-card); border-color: var(--border-color); box-shadow: 0 12px 40px rgba(0,0,0,0.08); }
        body.light-mode .search-item { border-bottom-color: var(--border-color); }
        body.light-mode .search-item-title { color: var(--text-primary); }
        body.light-mode ::-webkit-scrollbar-thumb { background: #c4c8ce; }
        body.light-mode ::-webkit-scrollbar-track { background: #e8eaee; }
        body.light-mode .play-icon-wrap { box-shadow: 0 4px 20px rgba(5, 150, 105, 0.25); }
        body.light-mode .audio-card:hover { background: rgba(0,0,0,0.03); }
        body.light-mode .news-card { border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 4px 14px rgba(0,0,0,0.04); }

        .sidebar { 
            position: fixed; 
            left: 0; 
            top: 64px; 
            width: 260px; 
            height: calc(100vh - 64px); 
            background: var(--bg-secondary); 
            overflow-y: auto; 
            padding: 0.75rem 0; 
            z-index: 999; 
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid var(--border-color);
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar-item { 
            display: flex; 
            align-items: center; 
            gap: 1.25rem; 
            padding: 0.625rem 1.5rem; 
            color: var(--text-primary); 
            text-decoration: none; 
            cursor: pointer; 
            transition: var(--transition-fast);
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0;
        }
        
        .sidebar-item:hover { 
            background: var(--bg-hover); 
        }
        
        .sidebar-item.active { 
            background: var(--bg-tertiary);
            border-left: 3px solid var(--accent-green);
        }
        
        .sidebar-item i { 
            font-size: 1.25rem; 
            width: 24px; 
        }
        
        .sidebar-section { 
            padding: 0.5rem 0; 
            border-bottom: 1px solid var(--border-color); 
            margin-bottom: 0.5rem;
        }
        
        .sidebar-title { 
            padding: 0.5rem 1.5rem; 
            font-size: 0.75rem; 
            font-weight: 600; 
            color: var(--text-tertiary); 
            text-transform: uppercase; 
            letter-spacing: 0.5px;
        }

        /* Mobile Language Selector in Sidebar */
        .mobile-lang-selector {
            padding: 0 1rem;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .current-mobile-lang {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            background: var(--bg-tertiary);
            border-radius: 12px;
            cursor: pointer;
            transition: var(--transition-fast);
            border: 1px solid var(--border-color);
        }

        .current-mobile-lang:hover {
            background: var(--bg-hover);
            border-color: var(--accent-blue);
        }

        .current-mobile-lang img {
            width: 24px;
            height: 16px;
            border-radius: 3px;
        }

        .current-mobile-lang span {
            flex: 1;
            font-size: 14px;
            font-weight: 500;
        }

        .current-mobile-lang i {
            font-size: 12px;
            transition: transform 0.2s ease;
        }

        .current-mobile-lang.active i {
            transform: rotate(180deg);
        }

        .mobile-lang-dropdown {
            margin-top: 8px;
            background: var(--bg-tertiary);
            border-radius: 12px;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .mobile-lang-dropdown.active {
            max-height: 300px;
            opacity: 1;
            visibility: visible;
            overflow-y: auto;
        }

        .mobile-lang-option {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 12px 16px !important;
            width: 100% !important;
            background: transparent !important;
            border: none !important;
            color: var(--text-primary) !important;
            font-size: 14px !important;
            cursor: pointer !important;
            transition: var(--transition-fast) !important;
            text-align: left !important;
        }

        .mobile-lang-option:hover {
            background: var(--bg-hover) !important;
            padding-left: 24px !important;
        }

        .mobile-lang-option img {
            width: 24px;
            height: 16px;
            border-radius: 3px;
        }

        /* ============================================ */
        /* MAIN CONTENT PREMIUM */
        /* ============================================ */
        .main-content { 
            margin-left: 260px; 
            padding: 1.5rem; 
            min-height: calc(100vh - 64px); 
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            max-width: 1600px;
        }
        
        /* Search Header */
        .search-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .search-header h5 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        /* Media Grid Premium */
        .media-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); 
            gap: 1.5rem;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .media-card-wrapper {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Media Card Premium */
        .media-card { 
            cursor: pointer; 
            transition: var(--transition);
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
        }
        
        .media-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(16, 185, 129, 0.2);
        }
        
        .thumbnail-container { 
            position: relative; 
            border-radius: var(--radius-lg) var(--radius-lg) 0 0;
            overflow: hidden;
            background: var(--bg-tertiary);
            aspect-ratio: 16 / 9;
            background-size: cover;
            background-position: center;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .media-card:hover .thumbnail-container {
            transform: scale(1.06);
        }
        
        .play-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: var(--transition);
        }
        
        .media-card:hover .play-overlay {
            opacity: 1;
        }
        
        .play-icon-wrap {
            width: 64px;
            height: 64px;
            background: var(--accent-green);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px var(--accent-green-glow);
            transition: var(--transition-bounce);
            transform: scale(0.8);
        }
        
        .media-card:hover .play-icon-wrap {
            transform: scale(1);
        }
        
        .play-icon-wrap i {
            font-size: 1.75rem;
            color: white;
            margin-left: 3px;
        }
        
        /* Card badges */
        .card-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 4px 10px;
            border-radius: var(--radius-full);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 5;
            display: flex;
            align-items: center;
            gap: 4px;
            backdrop-filter: blur(8px);
        }
        .card-badge i { font-size: 0.65rem; }
        .card-badge-video { background: rgba(239, 68, 68, 0.85); color: #fff; }
        .card-badge-audio { background: rgba(139, 92, 246, 0.85); color: #fff; }
        .card-badge-image { background: rgba(59, 130, 246, 0.85); color: #fff; }
        .card-badge-doc { background: rgba(16, 185, 129, 0.85); color: #fff; }
        
        .duration-badge { 
            position: absolute; 
            bottom: 8px; 
            right: 8px; 
            background: rgba(0, 0, 0, 0.88); 
            color: var(--text-primary); 
            padding: 3px 8px; 
            border-radius: var(--radius-sm); 
            font-size: 0.72rem; 
            font-weight: 600; 
            z-index: 5;
            backdrop-filter: blur(4px);
            font-variant-numeric: tabular-nums;
        }
        
        .card-info { 
            padding: 0.85rem 1rem; 
            display: flex;
            gap: 0.75rem;
        }
        
        .card-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--accent-gradient);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px var(--accent-green-glow);
        }
        
        .card-avatar i {
            font-size: 0.95rem;
            color: white;
        }
        
        .card-details {
            flex: 1;
            min-width: 0;
        }
        
        .card-title { 
            font-size: 0.92rem; 
            font-weight: 600; 
            margin-bottom: 0.3rem; 
            line-height: 1.35; 
            display: -webkit-box; 
            -webkit-line-clamp: 2; 
            -webkit-box-orient: vertical; 
            overflow: hidden;
            color: var(--text-primary);
        }
        
        .card-meta { 
            color: var(--text-tertiary); 
            font-size: 0.75rem; 
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .card-channel { color: var(--text-secondary); }
        .card-sep { opacity: 0.4; }
        .card-views i { font-size: 0.68rem; margin-right: 1px; }

    /* ============================================ */
    /* AUDIO-SPECIFIC STYLES — SPOTIFY INSPIRED */
    /* ============================================ */
    
    /* Hero banner for audio section */
    .audio-hero {
        background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
        border-radius: 16px;
        padding: 2rem 2.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .audio-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(0, 208, 132, 0.15), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    
    .audio-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(62, 166, 255, 0.1), transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    
    .audio-hero-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #00d084, #00a86b);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }
    
    .audio-hero-icon i {
        font-size: 2.5rem;
        color: white;
    }
    
    .audio-hero-text {
        position: relative;
        z-index: 1;
    }
    
    .audio-hero-text h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        background: linear-gradient(135deg, #fff, #aaa);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .audio-hero-text p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 400;
    }
    
    .audio-hero-text p i {
        color: var(--accent-green);
        margin-right: 0.25rem;
    }
    
    /* Audio list container */
    .audio-list {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    /* Audio card — elegant list item */
    .audio-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.6rem 1rem;
        border-radius: 8px;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }
    
    .audio-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 0;
        background: var(--accent-green);
        border-radius: 0 3px 3px 0;
        transition: height 0.2s ease;
    }
    
    .audio-card:hover {
        background: rgba(255, 255, 255, 0.06);
    }
    
    .audio-card:hover::before {
        height: 60%;
    }
    
    .audio-card.playing {
        background: rgba(0, 208, 132, 0.08);
    }
    
    .audio-card.playing::before {
        height: 60%;
    }
    
    .audio-card.playing .audio-index {
        color: var(--accent-green);
    }
    
    .audio-card.playing .audio-title {
        color: var(--accent-green);
    }
    
    /* Audio index number */
    .audio-index {
        width: 24px;
        text-align: center;
        font-size: 0.85rem;
        color: var(--text-tertiary);
        font-weight: 500;
        font-variant-numeric: tabular-nums;
        flex-shrink: 0;
    }
    
    .audio-card:hover .audio-index {
        display: none;
    }
    
    .audio-card:hover .audio-play-indicator {
        display: flex;
    }
    
    .audio-play-indicator {
        display: none;
        width: 24px;
        height: 24px;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--text-primary);
        font-size: 0.85rem;
    }
    
    /* Thumbnail — circular album art */
    .audio-thumb-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
    }
    
    .audio-thumb-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .audio-thumb-wrapper .audio-play-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
    }
    
    .audio-card:hover .audio-thumb-wrapper .audio-play-overlay {
        opacity: 1;
    }
    
    .audio-play-overlay i {
        font-size: 1.25rem;
        color: white;
    }
    
    /* Audio info section */
    .audio-info {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .audio-title {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--text-primary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }
    
    .audio-artist {
        font-size: 0.8rem;
        color: var(--text-tertiary);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        transition: color 0.2s ease;
    }
    
    .audio-card:hover .audio-artist {
        color: var(--text-secondary);
    }
    
    /* Audio metadata (right side) */
    .audio-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-shrink: 0;
        font-size: 0.8rem;
        color: var(--text-tertiary);
    }
    
    .audio-duration {
        font-variant-numeric: tabular-nums;
        font-weight: 500;
        min-width: 40px;
        text-align: right;
    }
    
    .audio-views i {
        margin-right: 3px;
        font-size: 0.7rem;
    }
    
    /* Like button */
    .audio-like-btn {
        background: transparent;
        border: none;
        color: var(--text-tertiary);
        font-size: 1rem;
        padding: 0.25rem;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.2s ease;
        opacity: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .audio-card:hover .audio-like-btn {
        opacity: 1;
    }
    
    .audio-like-btn:hover {
        color: var(--accent-green);
        transform: scale(1.15);
    }
    
    .audio-like-btn.liked {
        color: var(--accent-green);
        opacity: 1;
    }
    
    /* Audio section divider */
    .audio-section-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem 0 0.75rem;
        margin-top: 0.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }
    
    .audio-section-divider:first-of-type {
        border-top: none;
        padding-top: 0;
        margin-top: 0;
    }
    
    .audio-section-divider h5 {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .audio-section-divider hr {
        flex: 1;
        border: none;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        margin: 0;
    }
    
    /* Pulse animation for playing */
    @keyframes audio-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(0, 208, 132, 0.4); }
        50% { box-shadow: 0 0 0 8px rgba(0, 208, 132, 0); }
    }
    
    .audio-card.playing .audio-thumb-wrapper {
        animation: audio-pulse 2s infinite;
    }
    
    /* Spin animation for playing vinyl */
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .audio-card.playing .audio-thumb-wrapper img {
        animation: spin-slow 8s linear infinite;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .audio-hero {
            padding: 1.25rem 1.5rem;
            gap: 1rem;
        }
        .audio-hero-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
        }
        .audio-hero-icon i {
            font-size: 1.75rem;
        }
        .audio-hero-text h2 {
            font-size: 1.25rem;
        }
        .audio-card {
            padding: 0.5rem 0.75rem;
        }
        .audio-thumb-wrapper {
            width: 40px;
            height: 40px;
        }
        .audio-meta {
            gap: 0.75rem;
        }
        .audio-views {
            display: none;
        }
        .audio-index {
            width: 20px;
            font-size: 0.75rem;
        }
    }

    /* Empty State */
    .empty-state { 
        text-align: center; 
        padding: 4rem 2rem; 
        color: var(--text-tertiary); 
    }
    
    .empty-state i { 
        font-size: 4rem; 
        margin-bottom: 1rem; 
        opacity: 0.5;
    }

    /* ============================================ */
    /* SECTION ACTUALITÉS / NEWS */
    /* ============================================ */
    .news-section h4 {
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .news-section h4 i {
        color: var(--accent-green);
        font-size: 1.5rem;
    }
    .news-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: var(--transition);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }
    .news-card:hover {
        transform: translateY(-5px);
        border-color: rgba(0, 208, 132, 0.4);
        box-shadow: var(--shadow-hover);
    }
    .news-thumb {
        position: relative;
        overflow: hidden;
    }
    .news-thumb img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }
    .news-thumb .news-fallback {
        width: 100%;
        height: 190px;
        background: linear-gradient(135deg, var(--bg-tertiary), var(--bg-hover));
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .news-thumb .news-fallback i {
        font-size: 3rem;
        color: var(--accent-green);
        opacity: 0.7;
    }
    .news-card:hover .news-thumb img {
        transform: scale(1.06);
    }
    .news-category {
        position: absolute;
        top: 12px;
        left: 12px;
        z-index: 2;
        background: var(--accent-green);
        color: #0a0a0a;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 4px 12px;
        border-radius: 20px;
    }
    .news-body {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .news-date {
        color: var(--text-tertiary);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .news-date i {
        color: var(--accent-green);
    }
    .news-title {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        line-height: 1.4;
        margin: 10px 0 8px;
    }
    .news-card:hover .news-title {
        color: var(--accent-green);
    }
    .news-excerpt {
        color: var(--text-secondary);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 16px;
        flex: 1;
    }
    .news-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        align-self: flex-start;
        background: rgba(0, 208, 132, 0.12);
        color: var(--accent-green);
        border: 1px solid rgba(0, 208, 132, 0.35);
        border-radius: 24px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition-fast);
    }
    .news-btn i {
        font-size: 0.8rem;
        transition: transform 0.2s ease;
    }
    .news-btn:hover {
        background: var(--accent-green);
        color: #0a0a0a;
        border-color: var(--accent-green);
        transform: translateX(3px);
    }
    .news-btn:hover i {
        transform: translateX(3px);
    }

        /* Toast Container */
        .toast-container { 
            position: fixed; 
            bottom: 80px; 
            right: 20px; 
            z-index: 9999; 
        }

        /* ============================================ */
        /* RESPONSIVE DESIGN — MOBILE FIRST */
        /* ============================================ */
        @media (max-width: 1200px) {
            .media-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 1.25rem;
            }
        }
        
        @media (max-width: 1023px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: none;
                transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 20px 0 60px rgba(0, 0, 0, 0.6);
            }
            .main-content {
                margin-left: 0;
            }
            .media-grid {
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                height: 56px;
                padding: 0 0.75rem;
            }
            .sidebar {
                top: 56px;
                height: calc(100vh - 56px);
                width: 280px;
                background: var(--bg-secondary);
                border-right: 1px solid var(--border-color);
            }
            .search-container {
                display: none;
            }
            .search-toggle {
                display: flex;
            }
            .nav-icon {
                width: 36px;
                height: 36px;
            }
            .brand-name {
                font-size: 1rem;
            }
            .brand-subname {
                display: none;
            }
            .brand-logo {
                height: 28px;
            }
            .media-grid {
                grid-template-columns: 1fr;
                gap: 0.875rem;
            }
            .main-content {
                padding: 1rem;
            }
            .lang-selector-custom {
                display: none !important;
            }
            /* Cards plus compacts sur mobile */
            .card-info {
                padding: 0.7rem 0.85rem;
                gap: 0.6rem;
            }
            .card-avatar {
                width: 32px;
                height: 32px;
            }
            .card-avatar i { font-size: 0.85rem; }
            .card-title {
                font-size: 0.88rem;
            }
            .play-icon-wrap {
                width: 52px;
                height: 52px;
            }
            .play-icon-wrap i {
                font-size: 1.4rem;
            }
            /* Sidebar overlay */
            .sidebar::before {
                content: '';
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: -1;
                opacity: 0;
                transition: opacity 0.3s;
            }
            .sidebar.open::before {
                opacity: 1;
            }
        }
        
        @media (min-width: 769px) {
            .lang-selector-custom {
                display: block;
            }
            .mobile-lang-selector {
                display: none;
            }
            .mobile-search-toggle {
                display: none;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 0.625rem;
            }
            .media-grid {
                gap: 0.75rem;
            }
            .card-title {
                font-size: 0.82rem;
                -webkit-line-clamp: 2;
            }
            .card-avatar {
                width: 30px;
                height: 30px;
            }
            .card-info {
                padding: 0.6rem 0.75rem;
            }
            .play-icon-wrap {
                width: 44px;
                height: 44px;
            }
            .play-icon-wrap i {
                font-size: 1.2rem;
            }
            .card-badge {
                font-size: 0.6rem;
                padding: 3px 7px;
            }
        }
        
        /* Animation pour le loader */
        @keyframes pulse {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.8; }
        }
        
        .skeleton {
            background: linear-gradient(90deg, var(--bg-tertiary) 25%, var(--bg-hover) 50%, var(--bg-tertiary) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="light-mode">

<!-- Google Translate Container (caché) -->
<div id="google_translate_element" style="display: none;"></div>

<!-- Mobile Search Overlay -->
<div class="mobile-search-overlay" id="mobileSearchOverlay">
    <button class="back-btn" onclick="closeMobileSearch()">
        <i class="bi bi-arrow-left"></i>
    </button>
    <form action="<?= base_url('media/apiSearch') ?>" method="GET" class="search-form" id="mobileSearchForm">
        <input type="text" name="q" class="search-input" placeholder="Rechercher..." id="mobileSearchInput" autocomplete="off">
        <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <div class="search-dropdown" id="mobileSearchDropdown" style="position:fixed;left:16px;right:16px;top:70px;"></div>
</div>

<!-- Navbar Premium -->
<nav class="navbar">
    <div class="container-fluid">
        <div class="logo-wrapper">
            <button class="menu-icon d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="<?= base_url('media') ?>">
                <?php 
                            $site_logo = $this->Model->get_setting('site_logo');
                            if (!empty($site_logo)): 
                            ?>
                    <img src="<?= base_url($site_logo) ?>" alt="<?= htmlspecialchars($this->Model->get_setting('site_name', 'A.G.F')) ?>" class="brand-logo">
                <?php endif; ?>
                <span class="brand-name"><?= htmlspecialchars($this->Model->get_setting('site_name', 'A.G.F')) ?></span>
                <span class="brand-subname">Media</span>
            </a>
        </div>
        
        <!-- Desktop Search -->
        <div class="search-container" id="desktopSearchContainer">
            <form action="<?= base_url('media/apiSearch') ?>" method="GET" class="search-form" id="desktopSearchForm">
                <input type="text" name="q" class="search-input" id="desktopSearchInput" placeholder="Rechercher..." value="<?= isset($search_query) ? htmlspecialchars($search_query) : '' ?>" autocomplete="off">
                <button class="search-btn" type="submit"><i class="bi bi-search"></i></button>
            </form>
            <div class="search-dropdown" id="desktopSearchDropdown"></div>
        </div>
        
        <div class="nav-icons">
            <button class="search-toggle d-md-none" onclick="openMobileSearch()">
                <i class="bi bi-search"></i>
            </button>
            
            <!-- Theme Toggle -->
            <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()" title="Changer de thème">
                <i class="bi bi-moon-fill" id="themeIcon"></i>
            </button>
            
            <!-- Language Selector DESKTOP -->
            <div class="lang-selector-custom">
                <button class="custom-language-btn" id="customLanguageBtn">
                    <img src="https://flagcdn.com/w20/us.png" alt="English" id="currentLangFlag">
                    <span id="currentLangLabel">English</span>
                    <i class="bi bi-chevron-down"></i>
                </button>
                <div class="custom-language-dropdown" id="customLanguageDropdown">
                    <button class="lang-option" data-lang="fr" data-flag="fr" data-label="Français">
                        <img src="https://flagcdn.com/w20/fr.png" alt="Français"> Français
                    </button>
                    <button class="lang-option" data-lang="en" data-flag="us" data-label="English">
                        <img src="https://flagcdn.com/w20/us.png" alt="English"> English
                    </button>
                    <button class="lang-option" data-lang="rn" data-flag="bi" data-label="Kirundi">
                        <img src="https://flagcdn.com/w20/bi.png" alt="Kirundi"> Kirundi
                    </button>
                    <button class="lang-option" data-lang="sw" data-flag="tz" data-label="Kiswahili">
                        <img src="https://flagcdn.com/w20/tz.png" alt="Kiswahili"> Kiswahili
                    </button>
                    <button class="lang-option" data-lang="ar" data-flag="sa" data-label="العربية">
                        <img src="https://flagcdn.com/w20/sa.png" alt="العربية"> العربية
                    </button>
                    <button class="lang-option" data-lang="de" data-flag="de" data-label="Deutsch">
                        <img src="https://flagcdn.com/w20/de.png" alt="Deutsch"> Deutsch
                    </button>
                    <button class="lang-option" data-lang="es" data-flag="es" data-label="Español">
                        <img src="https://flagcdn.com/w20/es.png" alt="Español"> Español
                    </button>
                    <button class="lang-option" data-lang="pt" data-flag="pt" data-label="Português">
                        <img src="https://flagcdn.com/w20/pt.png" alt="Português"> Português
                    </button>
                    <button class="lang-option" data-lang="it" data-flag="it" data-label="Italiano">
                        <img src="https://flagcdn.com/w20/it.png" alt="Italiano"> Italiano
                    </button>
                    <button class="lang-option" data-lang="zh-CN" data-flag="cn" data-label="中文">
                        <img src="https://flagcdn.com/w20/cn.png" alt="中文"> 中文
                    </button>
                    <button class="lang-option" data-lang="ru" data-flag="ru" data-label="Русский">
                        <img src="https://flagcdn.com/w20/ru.png" alt="Русский"> Русский
                    </button>
                    <button class="lang-option" data-lang="nl" data-flag="nl" data-label="Nederlands">
                        <img src="https://flagcdn.com/w20/nl.png" alt="Nederlands"> Nederlands
                    </button>
                    <button class="lang-option" data-lang="pl" data-flag="pl" data-label="Polski">
                        <img src="https://flagcdn.com/w20/pl.png" alt="Polski"> Polski
                    </button>
                    <button class="lang-option" data-lang="tr" data-flag="tr" data-label="Türkçe">
                        <img src="https://flagcdn.com/w20/tr.png" alt="Türkçe"> Türkçe
                    </button>
                    <button class="lang-option" data-lang="ja" data-flag="jp" data-label="日本語">
                        <img src="https://flagcdn.com/w20/jp.png" alt="日本語"> 日本語
                    </button>
                    <button class="lang-option" data-lang="ko" data-flag="kr" data-label="한국어">
                        <img src="https://flagcdn.com/w20/kr.png" alt="한국어"> 한국어
                    </button>
                    <button class="lang-option" data-lang="hi" data-flag="in" data-label="हिन्दी">
                        <img src="https://flagcdn.com/w20/in.png" alt="हिन्दी"> हिन्दी
                    </button>
                    <button class="lang-option" data-lang="vi" data-flag="vn" data-label="Tiếng Việt">
                        <img src="https://flagcdn.com/w20/vn.png" alt="Tiếng Việt"> Tiếng Việt
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Sidebar Premium -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-section">
        <a href="javascript:void(0)" class="sidebar-item <?= empty($current_type) && empty($search_query) ? 'active' : '' ?>" data-nav="home" onclick="spaNavigate('home', this)">
            <i class="bi bi-house-fill"></i><span>Home</span>
        </a>
        <a href="javascript:void(0)" class="sidebar-item <?= (!empty($current_type) && $current_type === 'video') ? 'active' : '' ?>" data-nav="video" onclick="spaNavigate('video', this)">
            <i class="bi bi-fire"></i><span>Tendances</span>
        </a>
        <a href="javascript:void(0)" class="sidebar-item" data-nav="temoignages" onclick="spaNavigate('temoignages', this)">
            <i class="bi bi-chat-quote-fill"></i><span>Témoignages</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-title">Catégories</div>
        <a href="javascript:void(0)" class="sidebar-item <?= (!empty($current_type) && $current_type === 'video') ? 'active' : '' ?>" data-nav="video" onclick="spaNavigate('video', this)">
            <i class="bi bi-camera-video-fill"></i><span>Vidéos</span>
        </a>
        <a href="javascript:void(0)" class="sidebar-item <?= (!empty($current_type) && $current_type === 'audio') ? 'active' : '' ?>" data-nav="audio" onclick="spaNavigate('audio', this)">
            <i class="bi bi-music-note-beamed"></i><span>Audio</span>
        </a>
        <a href="javascript:void(0)" class="sidebar-item <?= (!empty($current_type) && $current_type === 'image') ? 'active' : '' ?>" data-nav="image" onclick="spaNavigate('image', this)">
            <i class="bi bi-image-fill"></i><span>Images</span>
        </a>
        <a href="javascript:void(0)" class="sidebar-item <?= (!empty($current_type) && $current_type === 'book') ? 'active' : '' ?>" data-nav="book" onclick="spaNavigate('book', this)">
            <i class="bi bi-book-fill"></i><span>Livres</span>
        </a>
    </div>

    <!-- Language Selector MOBILE (dans le sidebar) -->
    <div class="sidebar-section">
        <div class="sidebar-title">
            <i class="bi bi-translate"></i> Langue
        </div>
        <div class="mobile-lang-selector" id="mobileLangSelector">
            <div class="current-mobile-lang" id="currentMobileLang" onclick="toggleMobileLangDropdown()">
                <img src="https://flagcdn.com/w20/us.png" alt="English" id="mobileCurrentLangFlag">
                <span id="mobileCurrentLangLabel">English</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="mobile-lang-dropdown" id="mobileLangDropdown">
                <button class="mobile-lang-option" data-lang="fr" data-flag="fr" data-label="Français">
                    <img src="https://flagcdn.com/w20/fr.png" alt="Français"> Français
                </button>
                <button class="mobile-lang-option" data-lang="en" data-flag="us" data-label="English">
                    <img src="https://flagcdn.com/w20/us.png" alt="English"> English
                </button>
                <button class="mobile-lang-option" data-lang="rn" data-flag="bi" data-label="Kirundi">
                    <img src="https://flagcdn.com/w20/bi.png" alt="Kirundi"> Kirundi
                </button>
                <button class="mobile-lang-option" data-lang="sw" data-flag="tz" data-label="Kiswahili">
                    <img src="https://flagcdn.com/w20/tz.png" alt="Kiswahili"> Kiswahili
                </button>
                <button class="mobile-lang-option" data-lang="ar" data-flag="sa" data-label="العربية">
                    <img src="https://flagcdn.com/w20/sa.png" alt="العربية"> العربية
                </button>
                <button class="mobile-lang-option" data-lang="de" data-flag="de" data-label="Deutsch">
                    <img src="https://flagcdn.com/w20/de.png" alt="Deutsch"> Deutsch
                </button>
                <button class="mobile-lang-option" data-lang="es" data-flag="es" data-label="Español">
                    <img src="https://flagcdn.com/w20/es.png" alt="Español"> Español
                </button>
                <button class="mobile-lang-option" data-lang="pt" data-flag="pt" data-label="Português">
                    <img src="https://flagcdn.com/w20/pt.png" alt="Português"> Português
                </button>
                <button class="mobile-lang-option" data-lang="it" data-flag="it" data-label="Italiano">
                    <img src="https://flagcdn.com/w20/it.png" alt="Italiano"> Italiano
                </button>
                <button class="mobile-lang-option" data-lang="zh-CN" data-flag="cn" data-label="中文">
                    <img src="https://flagcdn.com/w20/cn.png" alt="中文"> 中文
                </button>
                <button class="mobile-lang-option" data-lang="ru" data-flag="ru" data-label="Русский">
                    <img src="https://flagcdn.com/w20/ru.png" alt="Русский"> Русский
                </button>
            </div>
        </div>
    </div>

    <div class="sidebar-section">
        <a href="<?= base_url('') ?>" class="sidebar-item">
            <i class="bi bi-door-closed"></i><span>Quitter</span>
        </a>
    </div>
</aside>

<!-- Main Content -->
<main class="main-content">
    <?php if (!empty($search_query)): ?>
        <div class="search-header">
            <h5>Résultats pour "<?= htmlspecialchars($search_query) ?>"</h5>
            <small class="text-secondary"><?= (int)$results_count ?> résultat(s)</small>
        </div>
    <?php endif; ?>

    <?php if (!empty($show_temoignages)): ?>
        <div class="temoignages-section w-100">
            <h4 class="mb-3"><i class="bi bi-chat-quote-fill"></i> Témoignages</h4>
            <div class="row">
                <?php if (!empty($temoignages)): ?>
                    <?php foreach ($temoignages as $t): ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                            <div class="media-card" onclick="openTemoignageModal('<?= htmlspecialchars($t['titre'] ?? '') ?>', '<?= get_youtube_embed_url($t['video_url']) ?>')">
                                <div class="thumbnail-container" style="background-image: url('<?= !empty($t['miniature']) ? $t['miniature'] : base_url('assets/frontend/img/default-avatar.jpg') ?>')">
                                    <div class="play-overlay">
                                        <i class="bi bi-play-circle-fill"></i>
                                    </div>
                                </div>
                                <div class="card-info">
                                    <div class="channel-avatar">
                                        <i class="bi bi-chat-quote-fill"></i>
                                    </div>
                                    <div class="card-details">
                                        <div class="card-title"><?= htmlspecialchars($t['titre'] ?? '') ?></div>
                                        <div class="card-meta">
                                            <i class="bi bi-person"></i> A.G.F BURUNDI
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center text-muted py-4">Aucun témoignage disponible</div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="media-grid" id="mediaGrid">
        <?php if (!empty($medias)): ?>
            <?php foreach ($medias as $media): ?>
                <?= createMediaCard($media, $lang ?? 'fr') ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="empty-state" id="emptyState" style="display: <?= (empty($medias) && empty($show_temoignages)) ? 'flex' : 'none' ?>; flex-direction: column; align-items: center;">
        <i class="bi bi-play-circle"></i>
        <h5>Aucun média disponible</h5>
        <small class="text-secondary">Revenez plus tard pour découvrir du nouveau contenu.</small>
    </div>
</main>

<div class="toast-container" id="toastContainer"></div>

<script>
// ============================================
// THEME TOGGLE — DARK / LIGHT
// ============================================
function toggleTheme() {
    const body = document.body;
    const icon = document.getElementById('themeIcon');
    const isLight = body.classList.toggle('light-mode');
    localStorage.setItem('agf_theme', isLight ? 'light' : 'dark');
    icon.className = isLight ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
}

(function() {
    const saved = localStorage.getItem('agf_theme');
    const icon = document.getElementById('themeIcon');
    if (saved === 'dark') {
        document.body.classList.remove('light-mode');
        if (icon) icon.className = 'bi bi-moon-fill';
    } else {
        document.body.classList.add('light-mode');
        if (icon) icon.className = 'bi bi-sun-fill';
    }
})();

// ============================================
// SPA NAVIGATION — NO PAGE RELOAD
// ============================================
const SPA_API = '<?= base_url("media/apiGrid") ?>';
const BASE_URL = '<?= rtrim(base_url(), "/") ?>';
let spaLoading = false;

function spaNavigate(type, el) {
    if (spaLoading) return;
    
    // Fermer le sidebar mobile
    document.getElementById('sidebar').classList.remove('open');
    
    // Mettre à jour la sidebar active
    document.querySelectorAll('.sidebar-item').forEach(item => item.classList.remove('active'));
    if (el) el.classList.add('active');
    
    // Animation de sortie
    const grid = document.getElementById('mediaGrid');
    const emptyState = document.getElementById('emptyState');
    grid.style.opacity = '0';
    grid.style.transform = 'translateY(12px)';
    
    spaLoading = true;
    
    setTimeout(function() {
        if (type === 'home') {
            // Charger tout
            fetch(SPA_API + '?type=all')
                .then(r => r.json())
                .then(data => renderGrid(data, ''))
                .catch(() => { grid.style.opacity = '1'; grid.style.transform = 'none'; });
            history.pushState({type: 'home'}, '', BASE_URL + '/media');
        } else if (type === 'temoignages') {
            // Les témoignages restent en reload car complexe
            window.location.href = BASE_URL + '/media/temoignages';
            return;
        } else {
            // Charger par type
            fetch(SPA_API + '?type=' + encodeURIComponent(type))
                .then(r => r.json())
                .then(data => renderGrid(data, type))
                .catch(() => { grid.style.opacity = '1'; grid.style.transform = 'none'; });
            const slug = type === 'video' ? 'video' : type;
            history.pushState({type: type}, '', BASE_URL + '/media/type/' + slug);
        }
    }, 180);
}

function renderGrid(data, type) {
    const grid = document.getElementById('mediaGrid');
    const emptyState = document.getElementById('emptyState');
    
    if (!data.success || !data.medias || data.medias.length === 0) {
        grid.innerHTML = '';
        emptyState.style.display = 'flex';
        grid.style.opacity = '1';
        grid.style.transform = 'none';
        spaLoading = false;
        return;
    }
    
    emptyState.style.display = 'none';
    let html = '';
    data.medias.forEach(m => {
        if (m.card_html) html += '<div class="media-card-wrapper">' + m.card_html + '</div>';
    });
    grid.innerHTML = html;
    
    // Animation d'entrée
    requestAnimationFrame(() => {
        grid.style.opacity = '1';
        grid.style.transform = 'none';
        // Stagger animation
        grid.querySelectorAll('.media-card-wrapper').forEach((card, i) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            setTimeout(() => {
                card.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                card.style.opacity = '1';
                card.style.transform = 'none';
            }, i * 60);
        });
    });
    
    spaLoading = false;
}

function openMedia(mediaSlug) {
    window.location.href = BASE_URL + '/media/detail/' + mediaSlug;
}

// Gestion du bouton retour du navigateur
window.addEventListener('popstate', function(e) {
    if (e.state && e.state.type) {
        const sidebarItem = document.querySelector('[data-nav="' + e.state.type + '"]');
        spaNavigate(e.state.type, sidebarItem);
    } else {
        location.reload();
    }
});

// ============================================
// MOBILE SEARCH
// ============================================
function openMobileSearch() {
    document.getElementById('mobileSearchOverlay').classList.add('active');
    document.getElementById('mobileSearchInput').focus();
}

function closeMobileSearch() {
    document.getElementById('mobileSearchOverlay').classList.remove('active');
}

// ============================================
// LIVE SEARCH
// ============================================
(function() {
    const liveSearchUrl = '<?= base_url("media/liveSearch") ?>';
    let searchTimer = null;
    let reqSeq = 0;

    function setupLiveSearch(inputId, dropdownId, formId) {
        const input = document.getElementById(inputId);
        const dropdown = document.getElementById(dropdownId);
        const form = document.getElementById(formId);
        if (!input || !dropdown) return;

        input.addEventListener('input', function() {
            clearTimeout(searchTimer);
            const q = this.value.trim();
            if (q.length < 2) {
                dropdown.classList.remove('active');
                dropdown.innerHTML = '';
                return;
            }
            dropdown.innerHTML = '<div class="search-loading"><i class="bi bi-hourglass-split"></i> Recherche...</div>';
            dropdown.classList.add('active');
            const seq = ++reqSeq;
            searchTimer = setTimeout(function() {
                fetch(liveSearchUrl + '?q=' + encodeURIComponent(q))
                    .then(r => r.json())
                    .then(data => {
                        if (seq !== reqSeq) return;
                        if (data.success && data.html) {
                            dropdown.innerHTML = data.html;
                            dropdown.classList.add('active');
                        } else {
                            dropdown.classList.remove('active');
                        }
                    })
                    .catch(() => {
                        if (seq === reqSeq) dropdown.classList.remove('active');
                    });
            }, 250);
        });

        input.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && dropdown.innerHTML) {
                dropdown.classList.add('active');
            }
        });

        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && e.target !== input) {
                dropdown.classList.remove('active');
            }
        });

        form.addEventListener('submit', function() {
            dropdown.classList.remove('active');
        });
    }

    setupLiveSearch('desktopSearchInput', 'desktopSearchDropdown', 'desktopSearchForm');
    setupLiveSearch('mobileSearchInput', 'mobileSearchDropdown', 'mobileSearchForm');
})();

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeMobileSearch();
});

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
}

function filterMedia(type) {
    const el = document.querySelector('[data-nav="' + type + '"]');
    spaNavigate(type, el);
}

// ============================================
// LANGUAGE MANAGEMENT
// ============================================
const langBtn = document.getElementById('customLanguageBtn');
const langDropdown = document.getElementById('customLanguageDropdown');
const currentLangFlag = document.getElementById('currentLangFlag');
const currentLangLabel = document.getElementById('currentLangLabel');

function clearGoogtransCookies() {
    document.cookie.split(';').forEach(cookie => {
        if (cookie.trim().startsWith('googtrans=')) {
            const name = cookie.trim().split('=')[0];
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + window.location.hostname + ';';
        }
    });
}

function setLanguageCookie(langCode) {
    clearGoogtransCookies();
    document.cookie = 'agf_lang=' + langCode + '; path=/; max-age=31536000';
    if (langCode !== 'en') {
        document.cookie = 'googtrans=/en/' + langCode + '; path=/; max-age=31536000';
    }
}

function buildLangUrl(lang) {
    var VALID_LANGS = ['en','fr','sw','rn','zh-CN','es','hi','ar','bn','pt','ru','ur','id','de','ja','ms','tr','ko','vi','it','fa','ta','th','pl','nl','uk','el','he','am','so','yo','ha','ig','zu','af','km','ne','mr','te','kn','gu','pa','da','no','fi','cs','hu','sv','ro'];
    var bp = (window.BASE_PATH || '').replace(/\/+$/, '');
    var path = window.location.pathname;
    var routePath = path.substring(bp.length) || '/';
    var seg = routePath.split('/')[1];
    if (seg && VALID_LANGS.indexOf(seg) !== -1) {
        routePath = '/' + routePath.split('/').slice(2).join('/');
    }
    if (routePath.charAt(0) !== '/') routePath = '/' + routePath;
    if (lang === 'en') return window.location.origin + bp + routePath;
    return window.location.origin + bp + '/' + lang + routePath;
}

function changeLanguage(langCode, flagCode, label) {
    if (currentLangFlag && currentLangLabel) {
        currentLangFlag.src = 'https://flagcdn.com/w20/' + flagCode + '.png';
        currentLangLabel.textContent = label;
    }
    localStorage.setItem('preferred_language', langCode);
    localStorage.setItem('preferred_flag', flagCode);
    localStorage.setItem('preferred_label', label);
    setLanguageCookie(langCode);
    window.location.href = buildLangUrl(langCode);
}

if (langBtn) {
    langBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        langDropdown.classList.toggle('active');
    });
}

document.addEventListener('click', function(e) {
    if (langBtn && langDropdown && !langBtn.contains(e.target) && !langDropdown.contains(e.target)) {
        langDropdown.classList.remove('active');
    }
});

document.querySelectorAll('.lang-option').forEach(option => {
    option.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        changeLanguage(this.dataset.lang, this.dataset.flag, this.dataset.label);
        langDropdown.classList.remove('active');
    });
});

// Mobile Language
function toggleMobileLangDropdown() {
    document.getElementById('currentMobileLang').classList.toggle('active');
    document.getElementById('mobileLangDropdown').classList.toggle('active');
}

function changeLanguageMobile(langCode, flagCode, label) {
    const mf = document.getElementById('mobileCurrentLangFlag');
    const ml = document.getElementById('mobileCurrentLangLabel');
    if (mf) mf.src = 'https://flagcdn.com/w20/' + flagCode + '.png';
    if (ml) ml.textContent = label;
    if (currentLangFlag) currentLangFlag.src = 'https://flagcdn.com/w20/' + flagCode + '.png';
    if (currentLangLabel) currentLangLabel.textContent = label;
    localStorage.setItem('preferred_language', langCode);
    localStorage.setItem('preferred_flag', flagCode);
    localStorage.setItem('preferred_label', label);
    setLanguageCookie(langCode);
    document.getElementById('currentMobileLang').classList.remove('active');
    document.getElementById('mobileLangDropdown').classList.remove('active');
    window.location.href = buildLangUrl(langCode);
}

document.querySelectorAll('.mobile-lang-option').forEach(option => {
    option.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        changeLanguageMobile(this.dataset.lang, this.dataset.flag, this.dataset.label);
    });
});

document.addEventListener('click', function(e) {
    const sel = document.getElementById('mobileLangSelector');
    if (sel && !sel.contains(e.target)) {
        document.getElementById('currentMobileLang').classList.remove('active');
        document.getElementById('mobileLangDropdown').classList.remove('active');
    }
});

// ============================================
// GOOGLE TRANSLATE CLEANUP
// ============================================
function removeGoogleTranslateBar() {
    document.querySelector('.goog-te-banner-frame')?.remove();
    document.querySelectorAll('iframe').forEach(f => { if (f.src && f.src.includes('translate')) f.remove(); });
    document.body.style.marginTop = '0';
    document.body.style.top = '0';
    document.body.style.position = 'relative';
    document.getElementById('google_translate_element').style.display = 'none';
}
removeGoogleTranslateBar();
setInterval(removeGoogleTranslateBar, 200);

// Applied saved language
(function() {
    const sl = localStorage.getItem('preferred_language');
    const sf = localStorage.getItem('preferred_flag');
    const slabel = localStorage.getItem('preferred_label');
    if (sl && sf && slabel && sl !== 'en') {
        if (currentLangFlag) { currentLangFlag.src = 'https://flagcdn.com/w20/' + sf + '.png'; }
        if (currentLangLabel) { currentLangLabel.textContent = slabel; }
        const mff = document.getElementById('mobileCurrentLangFlag');
        const mll = document.getElementById('mobileCurrentLangLabel');
        if (mff) mff.src = 'https://flagcdn.com/w20/' + sf + '.png';
        if (mll) mll.textContent = slabel;
        if (document.cookie.indexOf('googtrans=/en/' + sl) === -1) {
            setLanguageCookie(sl);
            setTimeout(() => location.reload(), 50);
        }
    }
})();
</script>

<?php
function get_youtube_embed_url($url) {
    if (empty($url)) return '';
    $shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
    $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';
    if (preg_match($longUrlRegex, $url, $matches)) {
        if (!empty($matches[3])) {
            return 'https://www.youtube.com/embed/' . $matches[3];
        }
    }
    if (preg_match($shortUrlRegex, $url, $matches)) {
        if (!empty($matches[1])) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }
    }
    return $url;
}
?>

<!-- Modal Lecture Témoignage -->
<div class="modal fade" id="temoignageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-white border-0 shadow">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="temoignageModalTitle">Témoignage</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="ratio ratio-16x9">
                    <iframe id="temoignageIframe" src="" title="YouTube video" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openTemoignageModal(title, embedUrl) {
    document.getElementById('temoignageModalTitle').innerText = title;
    const separator = embedUrl.includes('?') ? '&' : '?';
    document.getElementById('temoignageIframe').src = embedUrl + separator + 'autoplay=1&rel=0&modestbranding=1';
    var myModal = new bootstrap.Modal(document.getElementById('temoignageModal'));
    myModal.show();
}

// Nettoyer l'iframe à la fermeture du modal pour stopper la vidéo
document.getElementById('temoignageModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('temoignageIframe').src = '';
});
</script>

<!-- Inclure Bootstrap JS pour les toasts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
function createMediaCard($media, $lang) {
    $type = $media['type'];
    $identifier = !empty($media['slug']) ? $media['slug'] : $media['id_media'];
    
    // Extraction YouTube ID
    $youtubeId = $media['youtube_id'] ?? '';
    if (empty($youtubeId) && !empty($media['lien'])) {
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $media['lien'], $matches);
        $youtubeId = $matches[1] ?? '';
    }
    
    // Miniature logic
    $thumbUrl = '';
    if (!empty($youtubeId)) {
        $thumbUrl = "https://img.youtube.com/vi/{$youtubeId}/mqdefault.jpg";
    } elseif (!empty($media['miniature']) && file_exists(FCPATH . $media['miniature'])) {
        $thumbUrl = base_url($media['miniature']);
    } elseif ($type === 'video' && !empty($media['fichier'])) {
        $thumb_path = FCPATH . 'attachments/Video/Thumbnails/' . pathinfo($media['fichier'], PATHINFO_FILENAME) . '_thumb.jpg';
        $thumbUrl = file_exists($thumb_path) 
            ? base_url('attachments/Video/Thumbnails/' . pathinfo($media['fichier'], PATHINFO_FILENAME) . '_thumb.jpg')
            : base_url('assets/backend/images/defaut-mignature-video.jpeg');
    } elseif ($type === 'audio' && !empty($media['fichier'])) {
        $base_name = pathinfo($media['fichier'], PATHINFO_FILENAME);
        $cover_path = FCPATH . 'attachments/Audio/Thumbnails/' . $base_name . '_cover.jpg';
        $wave_path  = FCPATH . 'attachments/Audio/Thumbnails/' . $base_name . '_waveform.jpg';
        if (file_exists($cover_path)) {
            $thumbUrl = base_url('attachments/Audio/Thumbnails/' . $base_name . '_cover.jpg');
        } elseif (file_exists($wave_path)) {
            $thumbUrl = base_url('attachments/Audio/Thumbnails/' . $base_name . '_waveform.jpg');
        } else {
            $thumbUrl = base_url('assets/backend/images/defaut-mignature-audio.jpeg');
        }
    } elseif ($type === 'image' && !empty($media['fichier_url'])) {
        $thumbUrl = $media['fichier_url'];
    } else {
        $defaults = [
            'video' => base_url('assets/backend/images/defaut-mignature-video.jpeg'),
            'audio' => base_url('assets/backend/images/defaut-mignature-audio.jpeg'),
            'image' => base_url('assets/backend/images/default-avatar.jpg'),
            'document' => base_url('assets/backend/images/default-avatar-pdf-mignature.png'),
            'link' => base_url('assets/backend/images/default-avatar.jpg'),
        ];
        $thumbUrl = $defaults[$type] ?? base_url('assets/backend/images/default-avatar.jpg');
    }
    
    $duration = $media['duration_formatted'] ?? '0:00';
    $title = htmlspecialchars($media['titre'] ?? 'Sans titre');
    $channel = htmlspecialchars($media['credits'] ?? $media['categorie'] ?? 'A.G.F BURUNDI');
    $views = number_format($media['views_count'] ?? 0);
    
    return '
    <div class="media-card" onclick="openMedia(\'' . addslashes($identifier) . '\')">
        <div class="thumbnail-container" style="background-image: url(\'' . $thumbUrl . '\')">
            <div class="play-overlay">
                <i class="bi bi-play-circle-fill"></i>
            </div>
            <span class="duration-badge">' . $duration . '</span>
        </div>
        <div class="card-info">
            <div class="channel-avatar">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="card-details">
                <div class="card-title">' . $title . '</div>
                <div class="card-meta">
                    <i class="bi bi-person"></i> ' . $channel . ' • 
                    <i class="bi bi-eye"></i> ' . $views . ' vues
                </div>
            </div>
        </div>
    </div>';
}
?>