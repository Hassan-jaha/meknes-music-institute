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
        :root {
            --admin-primary: #2c3e50;
            --admin-secondary: #34495e;
            --admin-accent: #e67e22;
        }
        body { background-color: #f4f7f6; }
        .admin-nav { background: var(--admin-primary); padding: 0.8rem 0; color: white; border-bottom: 4px solid var(--color-gold-primary); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav-links { display: flex; align-items: center; gap: 20px; }
        .admin-nav a { color: rgba(255,255,255,0.8); font-weight: 500; text-decoration: none; transition: color 0.3s; font-size: 0.95rem; }
        .admin-nav a:hover, .admin-nav a.active { color: white; }
        .admin-header-title { font-family: var(--font-heading); font-size: 1.4rem; color: var(--color-gold-primary); font-weight: bold; }
        .flash-message { padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; animation: slideDown 0.4s ease-out; }
        .flash-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .flash-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .lang-switch { display: flex; gap: 10px; background: rgba(0,0,0,0.2); padding: 5px 12px; border-radius: 20px; }
        .lang-switch a { font-size: 0.8rem; }
    </style>
</head>
<body>
<nav class="admin-nav">
    <div class="container">
        <div class="admin-header-title">
            <a href="<?= asset('admin/dashboard.php') ?>" style="color: inherit;"><?= __('admin_login_header') ?></a>
        </div>
        <div class="admin-nav-links">
            <a href="<?= asset('admin/dashboard.php') ?>"><?= __('admin_dashboard') ?></a>
            <a href="<?= asset('admin/actualites/index.php') ?>"><?= __('nav_news') ?></a>
            <a href="<?= asset('admin/annonces/index.php') ?>"><?= __('nav_announcements') ?></a>
            <a href="<?= asset('admin/galerie/index.php') ?>"><?= __('nav_gallery') ?></a>
            <a href="<?= asset('admin/messages/index.php') ?>"><?= __('admin_manage_messages') ?></a>
            
            <div class="lang-switch">
                <a href="?lang=fr" title="Français">FR</a>
                <a href="?lang=ar" title="العربية">AR</a>
                <a href="?lang=en" title="English">EN</a>
                <a href="?lang=zgh" title="Tamazight">ⵣ</a>
            </div>

            <!-- Bouton Retour au Site -->
            <a href="<?= asset('index.php') ?>" target="_blank" class="btn-view-site" style="background: var(--color-gold-primary); color: var(--admin-primary); padding: 5px 12px; border-radius: 4px; font-weight: bold; font-size: 0.8rem; text-decoration: none;">
                👁️ <?= __('admin_back_to_site') ?>
            </a>

            <a href="<?= asset('admin/logout.php') ?>" style="color: #ff7675; border: 1px solid #ff7675; padding: 4px 12px; border-radius: 4px;"><?= __('admin_logout') ?></a>
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
