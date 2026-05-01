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
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .admin-nav { background: var(--color-blue-deep); padding: 1rem 0; color: white; border-bottom: 3px solid var(--color-gold-primary); }
        .admin-nav .container { display: flex; justify-content: space-between; align-items: center; }
        .admin-nav a { color: white; margin-right: 15px; font-weight: 500; font-size: 0.9rem; }
        .admin-nav a:hover { color: var(--color-gold-primary); }
        .admin-header-title { font-family: var(--font-heading); font-size: 1.5rem; color: var(--color-gold-primary); }
    </style>
</head>
<body>
<nav class="admin-nav">
    <div class="container">
        <div class="admin-header-title"><?= __('admin_login_header') ?></div>
        <div>
            <a href="<?= $path_prefix ?>dashboard.php"><?= __('admin_dashboard') ?></a>
            <a href="<?= $path_prefix ?>actualites/index.php"><?= __('nav_news') ?></a>
            <a href="<?= $path_prefix ?>annonces/index.php"><?= __('nav_announcements') ?></a>
            <a href="<?= $path_prefix ?>galerie/index.php"><?= __('nav_gallery') ?></a>
            <a href="<?= $path_prefix ?>messages/index.php"><?= __('admin_manage_messages') ?></a>
            <a href="<?= $root_prefix ?>index.php" target="_blank">🌐 <?= __('admin_back_to_site') ?></a>
            <a href="<?= $path_prefix ?>logout.php" style="color: #ff7675;"><?= __('admin_logout') ?></a>
        </div>
    </div>
</nav>
<main class="container" style="margin-top: 2rem;">
