<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Changement de langue géré par functions.php

// Mise en cache navigateur pour les pages publiques (1 heure)
if (!isset($_SESSION['admin_id'])) {
    header("Cache-Control: public, max-age=3600");
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'fr' ?>" dir="<?= getLangDir() ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= __('site_title') ?></title>
    <meta name="description" content="<?= __('site_description') ?>">
    <link rel="stylesheet" href="public/css/style.min.css">
    <!-- Fallback if minified file fails (optional) -->
    <!-- <link rel="stylesheet" href="public/css/style.css"> -->
    <!-- Préchargement du fond pour un affichage instantané -->
    <link rel="preload" href="public/images/bg-pattern.png" as="image">
</head>
<body>



<header class="site-header">
    <div class="container">
        <a href="index.php" class="logo">
            <!-- Icone SVG Musique Professionnelle -->
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-music-2"><circle cx="8" cy="18" r="4"/><path d="M12 18V2l7 4"/></svg>
            <?= __('site_name') ?>
        </a>
        <nav class="nav">
            <ul class="nav-links">
                <li><a href="index.php"><?= __('nav_home') ?></a></li>
                <li><a href="actualites.php"><?= __('nav_news') ?></a></li>
                <li><a href="annonces.php"><?= __('nav_announcements') ?></a></li>
                <li><a href="galerie.php"><?= __('nav_gallery') ?></a></li>
                <li><a href="contact.php"><?= __('nav_contact') ?></a></li>
                
                <!-- Sélecteur de Langue Premium -->
                <li class="lang-selector">
                    <button class="lang-btn">
                        <span><?= strtoupper($_SESSION['lang'] ?? 'FR') ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
                    </button>
                    <div class="lang-dropdown">
                        <div class="lang-dropdown-content">
                            <a href="?lang=fr"><span>🇫🇷</span> Français</a>
                            <a href="?lang=ar"><span>🇲🇦</span> العربية</a>
                            <a href="?lang=en"><span>🇬🇧</span> English</a>
                            <a href="?lang=zgh"><span>ⵣ</span> ⵜⴰⵎⴰⵣⵉⵖⵜ</a>
                        </div>
                    </div>
                </li>

                <?php if(isset($_SESSION['admin_id'])): ?>
                    <li><a href="admin/dashboard.php" class="btn btn-primary" style="margin-left: 10px;"><?= __('nav_admin') ?></a></li>
                <?php else: ?>
                    <li><a href="admin/login.php" style="margin-left: 10px; color: var(--color-blue-deep); font-weight: 600;"><?= __('nav_admin') ?></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
<main>
