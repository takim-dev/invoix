<?php
    // Shared chrome for public-facing pages (landing, about, contact).
    // Page views extend this and provide their own styles + content sections.
    $appName     = $appName     ?? (new \App\Models\SettingModel())->getSetting('app_name', 'InvoiceApp');
    $appLogo     = $appLogo     ?? (new \App\Models\SettingModel())->getSetting('app_logo', '');
    $isLoggedIn  = $isLoggedIn  ?? (bool) session()->get('user_id');
    $userName    = $userName    ?? session()->get('user_name');

    // Brand mark: uploaded logo if set, otherwise the same SVG used on auth pages.
    $brandMark = function() use ($appLogo) {
        if (!empty($appLogo)) {
            return '<span class="brand-mark has-logo"><img src="' . esc($appLogo) . '" alt=""></span>';
        }
        return '<span class="brand-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true">'
            . '<path d="M7 3h8l4 4v14H7V3Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>'
            . '<path d="M15 3v5h4" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>'
            . '<path d="M10 12h6M10 16h6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>'
            . '</svg></span>';
    };
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> — <?= esc($appName) ?></title>

    <script>
        (function(){
            var t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #6571ff;
            --brand-dark: #4f5bd5;
            --brand-soft: rgba(101, 113, 255, 0.10);
            --ink: #0f172a;
            --muted: #64748b;
            --bg-soft: #f8fafc;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--ink);
            background: var(--bs-body-bg);
            margin: 0;
        }
        [data-bs-theme="dark"] body { background: #0b1020; }

        .landing-nav { padding: 1rem 0; background: transparent; }
        .brand {
            display: inline-flex; align-items: center; gap: 0.5rem;
            font-weight: 800; font-size: 1.25rem;
            color: var(--ink); text-decoration: none;
        }
        .brand-mark {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--brand) 0%, #8b5cf6 100%);
            border-radius: 9px;
            display: inline-flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1rem;
            box-shadow: 0 6px 16px rgba(101, 113, 255, 0.35);
            flex: 0 0 auto; overflow: hidden;
        }
        .brand-mark svg { width: 20px; height: 20px; }
        .brand-mark.has-logo {
            background: #fff; padding: 3px;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.10);
        }
        .brand-mark.has-logo img {
            width: 100%; height: 100%; object-fit: contain; border-radius: inherit;
        }

        .nav-cta { display: flex; gap: 0.5rem; align-items: center; }
        .nav-links { display: flex; gap: 1.4rem; align-items: center; justify-content: center; flex: 1; }
        .nav-links a {
            color: var(--muted); text-decoration: none; font-weight: 500; font-size: 0.95rem;
        }
        .nav-links a:hover { color: var(--brand); }

        .btn-brand {
            background: var(--brand); border-color: var(--brand); color: #fff;
            font-weight: 600; padding: 0.55rem 1.2rem; border-radius: 8px;
        }
        .btn-brand:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; transform: translateY(-1px); }
        .btn-outline-ink {
            border: 1px solid var(--bs-border-color); color: var(--ink);
            font-weight: 500; padding: 0.55rem 1.2rem; border-radius: 8px; background: transparent;
        }
        .btn-outline-ink:hover { background: var(--brand-soft); border-color: var(--brand); color: var(--brand); }

        .landing-footer {
            border-top: 1px solid var(--bs-border-color);
            padding: 2rem 0;
            color: var(--muted);
            font-size: 0.88rem;
        }
        .landing-footer .brand { font-size: 1.05rem; color: var(--ink); }
        .landing-footer a { color: var(--muted); text-decoration: none; }
        .landing-footer a:hover { color: var(--brand); }
        .lang-label { font-size: 0.85rem; color: var(--muted); white-space: nowrap; margin-right: 0.3rem; }
        .lang-dropdown { position: relative; }
        .lang-dropdown-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.4rem 0.7rem; border: 1px solid var(--bs-border-color); border-radius: 8px; background: transparent; color: var(--ink); font-size: 0.85rem; cursor: pointer; white-space: nowrap; }
        .lang-dropdown-btn:hover { border-color: var(--brand); }
        .lang-dropdown-menu { display: none; position: absolute; top: 100%; right: 0; z-index: 1050; margin-top: 0.3rem; min-width: 180px; background: var(--bs-body-bg); border: 1px solid var(--bs-border-color); border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); overflow: hidden; }
        .lang-dropdown.open .lang-dropdown-menu { display: block; }
        .lang-dropdown-item { display: flex; align-items: center; gap: 0.5rem; padding: 0.55rem 0.85rem; color: var(--ink); text-decoration: none; font-size: 0.9rem; transition: background 0.1s; }
        .lang-dropdown-item:hover { background: var(--brand-soft); }
        .lang-dropdown-item.active { background: var(--brand-soft); color: var(--brand); font-weight: 600; }
        .lang-flag { border-radius: 2px; flex: 0 0 auto; }

        <?= $this->renderSection('styles') ?>
    </style>
</head>
<body>

<nav class="landing-nav">
    <div class="container d-flex align-items-center">
        <a href="/" class="brand">
            <?= $brandMark() ?>
            <?= esc($appName) ?>
        </a>
        <div class="nav-links d-none d-md-flex">
            <a href="/about"><?= lang('Landing.nav_about') ?></a>
            <a href="/contact"><?= lang('Landing.nav_contact') ?></a>
        </div>
        <div class="nav-cta ms-auto">
            <?= view('partials/lang_switcher') ?>
            <?php if (!empty($isLoggedIn)): ?>
                <a href="/dashboard" class="btn btn-brand">
                    <i class="bi bi-grid-1x2-fill me-1"></i> <?= lang('Landing.cta_goto_dashboard') ?>
                </a>
            <?php else: ?>
                <a href="/login" class="btn btn-outline-ink"><?= lang('Landing.cta_login') ?></a>
                <a href="/register" class="btn btn-brand"><?= lang('Landing.cta_get_started') ?></a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?= $this->renderSection('content') ?>

<footer class="landing-footer">
    <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
        <a href="/" class="brand">
            <?= $brandMark() ?>
            <?= esc($appName) ?>
        </a>
        <div class="d-flex gap-3">
            <a href="/about"><?= lang('Landing.nav_about') ?></a>
            <a href="/contact"><?= lang('Landing.nav_contact') ?></a>
            <?php if (!empty($isLoggedIn)): ?>
                <a href="/dashboard"><?= lang('Nav.dashboard') ?></a>
                <a href="/account"><?= lang('Nav.account') ?></a>
                <a href="/logout"><?= lang('Nav.logout') ?></a>
            <?php else: ?>
                <a href="/login"><?= lang('Landing.cta_login') ?></a>
                <a href="/register"><?= lang('Landing.footer_signup') ?></a>
            <?php endif; ?>
        </div>
        <div>&copy; <?= date('Y') ?> <?= esc($appName) ?>. All rights reserved.</div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.lang-dropdown')) {
            document.querySelectorAll('.lang-dropdown.open').forEach(function(d) { d.classList.remove('open'); });
        }
    });
</script>
<?= $this->renderSection('scripts') ?>

</body>
</html>
