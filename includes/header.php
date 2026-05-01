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
    <link rel="stylesheet" href="public/css/style.css">
    <!-- Préchargement du fond pour un affichage instantané -->
    <link rel="preload" href="public/images/bg-pattern.png" as="image">
</head>
<body>

<?php
// Optionnel: Afficher une annonce épinglée
require_once __DIR__ . '/../config/database.php';
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT titre FROM annonces WHERE is_pinned = 1 AND date_expiration >= CURDATE() ORDER BY created_at DESC LIMIT 1");
    $stmt->execute();
    if ($annonce = $stmt->fetch()) {
        echo '<div class="annonces-banner"><div class="container">📌 ' . htmlspecialchars($annonce['titre']) . ' <a href="annonces.php">' . __('view_all_announcements') . '</a></div></div>';
    }
} catch (Exception $e) {
    // Silently ignore DB errors in header for banner
}
?>

<header class="site-header">
    <div class="container">
        <a href="index.php" class="logo">
            <!-- Icone SVG simple pour le logo -->
            <svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-red-primary)"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
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
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
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
