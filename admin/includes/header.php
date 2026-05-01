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
    <link rel="stylesheet" href="<?= asset('public/css/style.css') ?>">
    <style>
        .admin-nav { background: var(--color-blue-deep); padding: 1rem 0; color: white; border-bottom: 3px solid var(--color-gold-primary); }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .admin-nav-links { display: flex; align-items: center; flex-wrap: wrap; gap: 15px; }
        .admin-nav a { color: white; font-weight: 500; font-size: 0.9rem; text-decoration: none; }
        .admin-nav a:hover { color: var(--color-gold-primary); }
        .admin-header-title { font-family: var(--font-heading); font-size: 1.5rem; color: var(--color-gold-primary); white-space: nowrap; }
    </style>
</head>
<body>
<nav class="admin-nav">
    <div class="container">
        <div class="admin-header-title"><?= __('admin_login_header') ?></div>
        <div class="admin-nav-links">
            <a href="<?= asset('admin/dashboard.php') ?>"><?= __('admin_dashboard') ?></a>
            <a href="<?= asset('admin/actualites/index.php') ?>"><?= __('nav_news') ?></a>
            <a href="<?= asset('admin/annonces/index.php') ?>"><?= __('nav_announcements') ?></a>
            <a href="<?= asset('admin/galerie/index.php') ?>"><?= __('nav_gallery') ?></a>
            <a href="<?= asset('admin/messages/index.php') ?>"><?= __('admin_manage_messages') ?></a>
            
            <!-- Lang Selector Admin -->
            <span style="border-left: 1px solid rgba(255,255,255,0.2); padding-left: 15px; display: flex; gap: 8px;">
                <a href="?lang=fr">FR</a>
                <a href="?lang=ar">AR</a>
                <a href="?lang=en">EN</a>
                <a href="?lang=zgh">ⵣ</a>
            </span>

            <a href="<?= asset('index.php') ?>" target="_blank" style="margin-left: 10px; display:flex; align-items:center; gap:5px;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zm6.93 6h-2.95c-.32-1.25-.78-2.45-1.38-3.56 1.84.63 3.37 1.91 4.33 3.56zM12 4.04c.83 1.2 1.48 2.53 1.91 3.96h-3.82c.43-1.43 1.08-2.76 1.91-3.96zM4.26 14C4.08 13.36 4 12.69 4 12s.08-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2s.06 1.34.14 2H4.26zm.82 2h2.95c.32 1.25.78 2.45 1.38 3.56-1.84-.63-3.37-1.9-4.33-3.56zm2.95-8H5.08c.96-1.66 2.49-2.93 4.33-3.56C8.81 5.55 8.35 6.75 8.03 8zM12 19.96c-.83-1.2-1.48-2.53-1.91-3.96h3.82c-.43 1.43-1.08 2.76-1.91 3.96zM14.34 14H9.66c-.09-.66-.16-1.32-.16-2s.07-1.35.16-2h4.68c.09.65.16 1.32.16 2s-.07 1.34-.16 2zm.25 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95c-.96 1.65-2.49 2.93-4.33 3.56zM16.36 14c.08-.66.14-1.32.14-2s-.06-1.34-.14-2h3.38c.18.64.26 1.31.26 2s-.08 1.36-.26 2h-3.38z"/></svg>
                <?= __('admin_back_to_site') ?>
            </a>
            <a href="<?= asset('admin/logout.php') ?>" style="color: #ff7675;"><?= __('admin_logout') ?></a>
        </div>
    </div>
</nav>
<main class="container" style="margin-top: 2rem;">
