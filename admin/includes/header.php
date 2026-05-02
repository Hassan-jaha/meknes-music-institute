<?php
// admin/includes/header.php
// ob_start() capte tout output et permet header() même après include
ob_start();

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    ob_end_clean();
    header("Location: " . asset('admin/login.php'));
    exit;
}

$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir  = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>" dir="<?= getLangDir() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('admin_login_header') ?> | Institut de Musique</title>
    <link rel="stylesheet" href="<?= asset('public/css/style.min.css?v=1.1') ?>">
    <style>
        :root { --admin-primary:#1e293b; --admin-secondary:#334155; --admin-accent:#c2a661; }

        *, *::before, *::after { box-sizing: border-box; }
        body {
            background: #f1f5f9 url('<?= asset("public/images/bg-pattern.png") ?>');
            background-blend-mode: overlay;
            font-family: 'Outfit', sans-serif;
            margin: 0;
        }

        /* ── NAV ── */
        .admin-nav {
            background: var(--admin-primary);
            border-bottom: 4px solid var(--admin-accent);
            position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .admin-nav .container {
            display: flex; justify-content: space-between; align-items: center;
            height: 64px; max-width: 1400px; margin: 0 auto; padding: 0 1.5rem;
        }
        .admin-header-title {
            display: flex; align-items: center; gap: 10px;
            font-size: 1rem; color: var(--admin-accent);
            font-weight: 900; text-transform: uppercase; letter-spacing: 2px;
            text-decoration: none;
        }
        .admin-header-title svg { flex-shrink: 0; }

        /* Desktop links */
        .admin-nav-links {
            display: flex; align-items: center; gap: 6px;
        }
        .admin-nav-links a {
            color: #94a3b8; font-weight: 600; text-decoration: none;
            font-size: 0.78rem; padding: 9px 12px; border-radius: 8px;
            display: flex; align-items: center; gap: 6px;
            transition: all 0.2s ease; white-space: nowrap;
        }
        .admin-nav-links a:hover { color: white; background: rgba(255,255,255,0.08); }
        .admin-nav-links a.active { color: var(--admin-accent); background: rgba(194,166,97,0.12); }

        .lang-switch {
            display: flex; gap: 3px; background: rgba(0,0,0,0.3);
            padding: 4px; border-radius: 8px; margin: 0 8px;
        }
        .lang-switch a { padding: 4px 9px; font-size: 0.68rem; color: #94a3b8; border-radius: 5px; }
        .lang-switch a:hover { color: white; background: rgba(255,255,255,0.1); }

        .btn-site-view {
            background: var(--admin-accent) !important;
            color: #1e293b !important; font-weight: 800 !important;
            border-radius: 8px !important;
        }
        .btn-logout { color: #ef4444 !important; border: 1px solid rgba(239,68,68,0.35) !important; }

        /* Hamburger (mobile) */
        .admin-hamburger {
            display: none; background: none; border: none;
            cursor: pointer; padding: 8px; border-radius: 6px;
            color: #94a3b8; transition: color 0.2s;
        }
        .admin-hamburger:hover { color: white; }

        /* Mobile drawer */
        .admin-drawer {
            display: none;
            flex-direction: column;
            background: var(--admin-secondary);
            border-top: 1px solid rgba(255,255,255,0.05);
            padding: 12px;
            gap: 4px;
            position: sticky; top: 64px; z-index: 999;
        }
        .admin-drawer.open { display: flex; }
        .admin-drawer a {
            color: #cbd5e1; text-decoration: none; font-weight: 600;
            font-size: 0.9rem; padding: 12px 16px; border-radius: 8px;
            display: flex; align-items: center; gap: 10px;
            transition: background 0.2s;
        }
        .admin-drawer a:hover, .admin-drawer a.active { background: rgba(255,255,255,0.08); color: white; }
        .admin-drawer a.active { color: var(--admin-accent); }
        .admin-drawer-lang { display: flex; gap: 6px; padding: 8px 16px; }
        .admin-drawer-lang a { color: #94a3b8; font-size: 0.8rem; padding: 4px 10px; border-radius: 6px; background: rgba(0,0,0,0.3); }

        /* Responsive breakpoint */
        @media (max-width: 900px) {
            .admin-nav-links { display: none !important; }
            .admin-hamburger { display: flex; align-items: center; justify-content: center; }
        }

        /* ── FLASH ── */
        .flash-message {
            padding: 14px 20px; border-radius: 10px; margin-bottom: 20px;
            font-weight: 600; display: flex; align-items: center; gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .flash-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .flash-error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

        /* ── TABLE ── */
        .admin-table-container {
            overflow-x: auto; background: white;
            border-radius: 14px; box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 480px; }
        th {
            background: #f8fafc; color: #64748b; padding: 14px 16px;
            text-align: left; font-size: 0.72rem; text-transform: uppercase;
            font-weight: 700; border-bottom: 1px solid #f1f5f9;
        }
        td { padding: 14px 16px; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 0.88rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f8fafc; }

        /* ── FORM CONTAINER ── */
        .admin-form-container {
            max-width: 800px; margin: 2rem auto;
            background: white; padding: 36px;
            border-radius: 16px; box-shadow: 0 8px 24px rgba(0,0,0,0.07);
            border: 1px solid #e2e8f0;
        }
        @media (max-width: 600px) {
            .admin-form-container { padding: 20px; margin: 1rem; }
            .admin-nav .container { padding: 0 1rem; }
        }

        /* ── DASHBOARD STATS ── */
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px; margin-top: 1.5rem;
        }
        .admin-stat-card {
            background: white; border-radius: 14px; padding: 28px 24px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06); border: 1px solid #f1f5f9;
            text-align: center; transition: transform 0.2s, box-shadow 0.2s;
        }
        .admin-stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,0.1); }
        .admin-stat-card .stat-icon { font-size: 2.5rem; margin-bottom: 12px; display: block; }
        .admin-stat-card .stat-number { font-size: 2.8rem; font-weight: 900; color: var(--admin-accent); line-height: 1; }
        .admin-stat-card .stat-label { color: #64748b; font-size: 0.85rem; margin: 8px 0 16px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .admin-stat-card .btn { display: inline-block; }
    </style>
</head>
<body>

<!-- ── DESKTOP NAV ── -->
<nav class="admin-nav">
    <div class="container">
        <a href="<?= asset('admin/dashboard.php') ?>" class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19 7-7 3 3-7 7-3-3z"/><path d="m18 13-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="m2 2 20 20"/></svg>
            <span>Admin</span>
        </a>

        <div class="admin-nav-links">
            <a href="<?= asset('admin/dashboard.php') ?>" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <?= __('admin_dashboard') ?>
            </a>
            <a href="<?= asset('admin/actualites/index.php') ?>" class="<?= $currentDir == 'actualites' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                <?= __('nav_news') ?>
            </a>
            <a href="<?= asset('admin/annonces/index.php') ?>" class="<?= $currentDir == 'annonces' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                <?= __('nav_announcements') ?>
            </a>
            <a href="<?= asset('admin/galerie/index.php') ?>" class="<?= $currentDir == 'galerie' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <?= __('nav_gallery') ?>
            </a>
            <a href="<?= asset('admin/messages/index.php') ?>" class="<?= $currentDir == 'messages' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <?= __('admin_manage_messages') ?>
            </a>

            <div class="lang-switch">
                <?php $cu = strtok($_SERVER["REQUEST_URI"], '?'); $gp = $_GET; ?>
                <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'fr'])) ?>">FR</a>
                <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'ar'])) ?>">AR</a>
                <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'en'])) ?>">EN</a>
                <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'zgh'])) ?>">ⵣ</a>
            </div>

            <a href="<?= asset('index.php') ?>" target="_blank" class="btn-site-view">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Site
            </a>
            <a href="<?= asset('admin/logout.php') ?>" class="btn-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <?= __('admin_logout') ?>
            </a>
        </div>

        <!-- Hamburger (mobile) -->
        <button class="admin-hamburger" id="adminHamburger" aria-label="Menu">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
        </button>
    </div>
</nav>

<!-- ── MOBILE DRAWER ── -->
<div class="admin-drawer" id="adminDrawer">
    <a href="<?= asset('admin/dashboard.php') ?>" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
        <?= __('admin_dashboard') ?>
    </a>
    <a href="<?= asset('admin/actualites/index.php') ?>" class="<?= $currentDir == 'actualites' ? 'active' : '' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/></svg>
        <?= __('nav_news') ?>
    </a>
    <a href="<?= asset('admin/annonces/index.php') ?>" class="<?= $currentDir == 'annonces' ? 'active' : '' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/></svg>
        <?= __('nav_announcements') ?>
    </a>
    <a href="<?= asset('admin/galerie/index.php') ?>" class="<?= $currentDir == 'galerie' ? 'active' : '' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/></svg>
        <?= __('nav_gallery') ?>
    </a>
    <a href="<?= asset('admin/messages/index.php') ?>" class="<?= $currentDir == 'messages' ? 'active' : '' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
        <?= __('admin_manage_messages') ?>
    </a>
    <div class="admin-drawer-lang">
        <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'fr'])) ?>">FR</a>
        <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'ar'])) ?>">AR</a>
        <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'en'])) ?>">EN</a>
        <a href="<?= $cu.'?'.http_build_query(array_merge($gp,['lang'=>'zgh'])) ?>">ⵣ</a>
    </div>
    <a href="<?= asset('index.php') ?>" target="_blank" style="color: var(--admin-accent) !important;">↗ <?= __('admin_back_to_site') ?></a>
    <a href="<?= asset('admin/logout.php') ?>" style="color: #ef4444 !important;">⬅ <?= __('admin_logout') ?></a>
</div>

<script>
document.getElementById('adminHamburger').addEventListener('click', function() {
    document.getElementById('adminDrawer').classList.toggle('open');
});
</script>

<main class="container" style="margin-top: 2rem; min-height: 70vh; padding-bottom: 3rem;">
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="flash-message flash-success">✅ <?= h($_SESSION['flash_success']) ?><?php unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="flash-message flash-error">❌ <?= h($_SESSION['flash_error']) ?><?php unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>
