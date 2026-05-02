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
            max-width: 1300px;
            margin: 0 auto;
        }
        .admin-nav-links { 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .admin-nav a { 
            color: #94a3b8; 
            font-weight: 500; 
            text-decoration: none; 
            transition: all 0.2s ease; 
            font-size: 0.85rem; 
            padding: 8px 12px; 
            border-radius: 6px;
            white-space: nowrap;
        }
        .admin-nav a:hover, .admin-nav a.active { 
            color: white; 
            background: rgba(255,255,255,0.1); 
        }
        .admin-header-title { 
            font-size: 1.1rem; 
            color: var(--admin-accent); 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .flash-message { 
            padding: 16px 24px; 
            border-radius: 12px; 
            margin-bottom: 30px; 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .flash-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .flash-error { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        
        .lang-switch { display: flex; gap: 4px; background: rgba(0,0,0,0.2); padding: 4px; border-radius: 8px; margin: 0 15px; }
        .lang-switch a { padding: 4px 8px; font-size: 0.7rem; color: #cbd5e1; }
        .lang-switch a:hover { color: white; background: rgba(255,255,255,0.1); }
        
        /* Table Styles */
        table { width: 100%; border-collapse: separate; border-spacing: 0 8px; margin-top: 1rem; }
        th { background: var(--admin-primary); color: white; padding: 12px 15px; text-align: left; font-size: 0.8rem; text-transform: uppercase; }
        td { background: white; padding: 15px; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; }
        tr td:first-child { border-left: 1px solid #e2e8f0; border-radius: 8px 0 0 8px; }
        tr td:last-child { border-right: 1px solid #e2e8f0; border-radius: 0 8px 8px 0; }
        
        /* Form Container Fix */
        .admin-form-container { 
            max-width: 800px; 
            margin: 2rem auto; 
            background: white; 
            padding: 40px; 
            border-radius: 16px; 
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
<nav class="admin-nav">
    <div class="container">
        <div class="admin-header-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            <a href="<?= asset('admin/dashboard.php') ?>" style="color: inherit; text-decoration: none;">Administration</a>
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

            <a href="<?= asset('index.php') ?>" target="_blank" style="background: var(--admin-accent); color: #1e293b; font-weight: 800; border-radius: 8px;">
                👁️ <?= __('admin_back_to_site') ?>
            </a>

            <a href="<?= asset('admin/logout.php') ?>" style="color: #f87171; border: 1.5px solid #ef4444; margin-left: 10px;"><?= __('admin_logout') ?></a>
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
