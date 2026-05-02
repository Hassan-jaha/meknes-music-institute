<?php
// admin/includes/header.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$path_prefix = (basename(dirname($_SERVER['PHP_SELF'])) === 'admin') ? '' : '../';
$root_prefix = (basename(dirname($_SERVER['PHP_SELF'])) === 'admin') ? '../' : '../../';

if (!isset($_SESSION['admin_id'])) {
    header("Location: {$path_prefix}login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>" dir="<?= getLangDir() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('admin_login_header') ?> | Institut de Musique</title>
    <link rel="stylesheet" href="<?= asset('public/css/style.min.css?v=1.1') ?>">
    <style>
        :root {
            --admin-primary: #1e293b;
            --admin-secondary: #334155;
            --admin-accent: #c2a661;
        }
        body { 
            background: #f1f5f9 url('<?= asset("public/images/bg-pattern.png") ?>'); 
            background-blend-mode: overlay;
            font-family: 'Outfit', sans-serif; 
            margin: 0;
        }
        .admin-nav { 
            background: var(--admin-primary); 
            padding: 0 1rem; 
            color: white; 
            border-bottom: 4px solid var(--admin-accent); 
            position: sticky; 
            top: 0; 
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .admin-nav .container { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            height: 70px;
            max-width: 1400px;
            margin: 0 auto;
            width: 95%;
        }
        .admin-nav-links { 
            display: flex; 
            align-items: center; 
            gap: 8px; 
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .admin-nav a { 
            color: #94a3b8; 
            font-weight: 600; 
            text-decoration: none; 
            transition: all 0.2s ease; 
            font-size: 0.8rem; 
            padding: 10px 14px; 
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }
        .admin-nav a:hover { 
            color: white; 
            background: rgba(255,255,255,0.08); 
        }
        .admin-nav a.active {
            color: var(--admin-accent);
            background: rgba(194, 166, 97, 0.1);
        }
        .admin-header-title { 
            font-size: 1.1rem; 
            color: var(--admin-accent); 
            font-weight: 900; 
            text-transform: uppercase; 
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        /* Style spécial bouton retour site */
        .btn-site-view {
            background: var(--admin-accent) !important;
            color: #1e293b !important;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
        .btn-site-view:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        
        .lang-switch { display: flex; gap: 4px; background: rgba(0,0,0,0.3); padding: 4px; border-radius: 10px; margin: 0 10px; }
        .lang-switch a { padding: 4px 10px; font-size: 0.7rem; color: #94a3b8; border-radius: 6px; }
        .lang-switch a:hover { color: white; background: rgba(255,255,255,0.1); }
        
        /* Table Styles */
        .admin-table-container { overflow-x: auto; background: white; border-radius: 16px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); padding: 5px; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        th { background: #f8fafc; color: #64748b; padding: 16px; text-align: left; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #f1f5f9; }
        td { padding: 16px; border-bottom: 1px solid #f1f5f9; color: #1e293b; font-size: 0.9rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background-color: #f8fafc; }
    </style>
</head>
<body>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir = basename(dirname($_SERVER['PHP_SELF']));
?>
<nav class="admin-nav">
    <div class="container">
        <div class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19 7-7 3 3-7 7-3-3z"/><path d="m18 13-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="m2 2 20 20"/></svg>
            <span style="display: none; sm: block;">Administration</span>
        </div>
        <div class="admin-nav-links">
            <a href="<?= asset('admin/dashboard.php') ?>" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                <?= __('admin_dashboard') ?>
            </a>
            <a href="<?= asset('admin/actualites/index.php') ?>" class="<?= $currentDir == 'actualites' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
                <?= __('nav_news') ?>
            </a>
            <a href="<?= asset('admin/annonces/index.php') ?>" class="<?= $currentDir == 'annonces' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                <?= __('nav_announcements') ?>
            </a>
            <a href="<?= asset('admin/galerie/index.php') ?>" class="<?= $currentDir == 'galerie' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                <?= __('nav_gallery') ?>
            </a>
            <a href="<?= asset('admin/messages/index.php') ?>" class="<?= $currentDir == 'messages' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                <?= __('admin_manage_messages') ?>
            </a>
            
            <div class="lang-switch">
                <?php
                $current_url = strtok($_SERVER["REQUEST_URI"], '?');
                $params = $_GET;
                ?>
                <a href="<?= $current_url . '?' . http_build_query(array_merge($params, ['lang' => 'fr'])) ?>">FR</a>
                <a href="<?= $current_url . '?' . http_build_query(array_merge($params, ['lang' => 'ar'])) ?>">AR</a>
                <a href="<?= $current_url . '?' . http_build_query(array_merge($params, ['lang' => 'en'])) ?>">EN</a>
                <a href="<?= $current_url . '?' . http_build_query(array_merge($params, ['lang' => 'zgh'])) ?>">ⵣ</a>
            </div>

            <a href="<?= asset('index.php') ?>" target="_blank" class="btn-site-view">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                <?= __('admin_back_to_site') ?>
            </a>

            <a href="<?= asset('admin/logout.php') ?>" style="color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3);">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <?= __('admin_logout') ?>
            </a>
        </div>
    </div>
</nav>

<main class="container" style="margin-top: 2rem; min-height: 70vh;">
    <!-- Affichage des messages flash -->
    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="flash-message flash-success">✅ <?= h($_SESSION['flash_success']) ?><?php unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="flash-message flash-error">❌ <?= h($_SESSION['flash_error']) ?><?php unset($_SESSION['flash_error']); ?></div>
    <?php endif; ?>
