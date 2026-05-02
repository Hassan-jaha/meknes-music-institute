<?php
// admin/galerie/index.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . asset('admin/login.php'));
    exit;
}

setLanguage($_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr'));

// Suppression
if (isset($_GET['delete'])) {
    $del_id = (int)$_GET['delete'];
    $pdo = getDBConnection();
    // Récupérer le chemin pour supprimer le fichier aussi
    $row = $pdo->prepare("SELECT image_path FROM galerie WHERE id = ?");
    $row->execute([$del_id]);
    $img = $row->fetch();
    if ($img && $img['image_path']) {
        $file = dirname(__DIR__, 2) . '/' . ltrim($img['image_path'], '/');
        if (file_exists($file)) @unlink($file);
    }
    $pdo->prepare("DELETE FROM galerie WHERE id = ?")->execute([$del_id]);
    $_SESSION['flash_success'] = "Image supprimée.";
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$images = $pdo->query("SELECT * FROM galerie ORDER BY upload_date DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2><?= __('admin_manage_gallery') ?> (<?= count($images) ?>)</h2>
    <a href="<?= asset('admin/galerie/upload.php') ?>" class="btn btn-primary">
        + <?= __('admin_add_new') ?>
    </a>
</div>

<?php if (empty($images)): ?>
    <div style="text-align: center; padding: 60px; background: white; border-radius: 16px; color: #94a3b8;">
        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" style="margin-bottom: 20px; opacity: 0.4;"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
        <p style="font-size: 1.1rem;"><?= __('no_images') ?></p>
        <a href="<?= asset('admin/galerie/upload.php') ?>" class="btn btn-primary" style="margin-top: 15px;">📤 Ajouter une première image</a>
    </div>
<?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
        <?php foreach ($images as $img): ?>
        <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); border: 1px solid #f1f5f9;">
            <div style="position: relative; aspect-ratio: 3/2; overflow: hidden; background: #f8fafc;">
                <img 
                    src="<?= get_image_url($img['image_path']) ?>" 
                    alt="<?= h($img['titre_image']) ?>" 
                    style="width: 100%; height: 100%; object-fit: cover; display: block;"
                    onerror="this.src='<?= asset('public/images/bg-pattern.png') ?>'"
                >
            </div>
            <div style="padding: 12px 15px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 600; font-size: 0.85rem; color: #1e293b; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 160px;">
                    <?= h($img['titre_image']) ?: 'Sans titre' ?>
                </span>
                <a href="?delete=<?= $img['id'] ?>" 
                   style="color: #ef4444; font-size: 0.8rem; font-weight: 600; text-decoration: none; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 6px; white-space: nowrap;"
                   onclick="return confirm('Supprimer cette image définitivement ?')">
                    🗑 Supprimer
                </a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
