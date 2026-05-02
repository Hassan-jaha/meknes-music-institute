<?php
// admin/dashboard.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Statistiques rapides sécurisées
$stats = ['actualites' => 0, 'annonces' => 0, 'galerie' => 0, 'messages' => 0];
try {
    $stats['actualites'] = $pdo->query("SELECT COUNT(*) FROM actualites")->fetchColumn();
    $stats['annonces'] = $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn();
    $stats['galerie'] = $pdo->query("SELECT COUNT(*) FROM galerie")->fetchColumn();
    $stats['messages'] = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
} catch (Exception $e) {
    // Si une table manque, on affiche 0 sans planter la page
}

?>
<h2 class="section-title"><?= __('admin_dashboard') ?></h2>

<div class="grid">
    <div class="card" style="text-align: center;">
        <div class="card-body">
            <h3 class="card-title"><?= __('nav_news') ?></h3>
            <p style="font-size: 2rem; color: var(--color-red-primary);"><?= $stats['actualites'] ?></p>
            <a href="<?= asset('admin/actualites/index.php') ?>" class="btn btn-primary" style="margin-top: 1rem;"><?= __('admin_manage_news') ?></a>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <div class="card-body">
            <h3 class="card-title"><?= __('nav_announcements') ?></h3>
            <p style="font-size: 2rem; color: var(--color-red-primary);"><?= $stats['annonces'] ?></p>
            <a href="<?= asset('admin/annonces/index.php') ?>" class="btn btn-primary" style="margin-top: 1rem;"><?= __('admin_manage_announcements') ?></a>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <div class="card-body">
            <h3 class="card-title"><?= __('nav_gallery') ?></h3>
            <p style="font-size: 2rem; color: var(--color-red-primary);"><?= $stats['galerie'] ?></p>
            <a href="<?= asset('admin/galerie/index.php') ?>" class="btn btn-primary" style="margin-top: 1rem;"><?= __('admin_manage_gallery') ?></a>
        </div>
    </div>
    
    <div class="card" style="text-align: center;">
        <div class="card-body">
            <h3 class="card-title"><?= __('admin_manage_messages') ?></h3>
            <p style="font-size: 2rem; color: var(--color-gold-primary);"><?= $stats['messages'] ?></p>
            <a href="<?= asset('admin/messages/index.php') ?>" class="btn btn-primary" style="margin-top: 1rem;"><?= __('admin_manage_messages') ?></a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
