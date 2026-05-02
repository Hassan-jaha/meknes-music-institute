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
            --admin-accent: var(--color-gold-primary);
        }
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
        .admin-nav { background: var(--admin-primary); padding: 0.5rem 0; color: white; border-bottom: 3px solid var(--color-gold-primary); position: sticky; top: 0; z-index: 1000; }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav-links { display: flex; align-items: center; gap: 15px; }
        .admin-nav a { color: #cbd5e1; font-weight: 500; text-decoration: none; transition: all 0.2s; font-size: 0.9rem; padding: 8px 12px; border-radius: 6px; }
        .admin-nav a:hover, .admin-nav a.active { color: white; background: rgba(255,255,255,0.1); }
        .admin-header-title { font-size: 1.2rem; color: var(--color-gold-primary); font-weight: 800; text-transform: uppercase; letter-spacing: 1px; }
        .flash-message { padding: 15px 20px; border-radius: 8px; margin-bottom: 25px; font-weight: 600; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .flash-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .flash-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .lang-switch { display: flex; gap: 8px; background: rgba(255,255,255,0.05); padding: 4px; border-radius: 8px; }
        .lang-switch a { padding: 4px 8px; font-size: 0.75rem; border-radius: 4px; }
    </style>
</head>
<body>
<nav class="admin-nav">
    <div class="container">
        <div class="admin-header-title">
            <a href="<?= asset('admin/dashboard.php') ?>" style="color: inherit; text-decoration: none;">ADMIN</a>
        </div>
        <div class="admin-nav-links">
            <a href="<?= asset('admin/dashboard.php') ?>"><?= __('admin_dashboard') ?></a>
            <a href="<?= asset('admin/actualites/index.php') ?>"><?= __('nav_news') ?></a>
            <a href="<?= asset('admin/annonces/index.php') ?>"><?= __('nav_announcements') ?></a>
            <a href="<?= asset('admin/galerie/index.php') ?>"><?= __('nav_gallery') ?></a>
            <a href="<?= asset('admin/messages/index.php') ?>"><?= __('admin_manage_messages') ?></a>
            
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

            <a href="<?= asset('index.php') ?>" target="_blank" style="background: var(--color-gold-primary); color: #000; font-weight: bold;">
                👁️ <?= __('admin_back_to_site') ?>
            </a>

            <a href="<?= asset('admin/logout.php') ?>" style="color: #ef4444; border: 1px solid #ef4444;"><?= __('admin_logout') ?></a>
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
