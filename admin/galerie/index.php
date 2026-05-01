<?php
// admin/galerie/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$images = $pdo->query("SELECT * FROM galerie ORDER BY upload_date DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2><?= __('admin_manage_gallery') ?></h2>
    <a href="upload.php" class="btn btn-primary">+ <?= __('admin_add_new') ?></a>
</div>

<div class="grid">
    <?php foreach ($images as $img): ?>
    <div class="card">
        <img src="<?= asset($img['image_path']) ?>" class="card-img" alt="<?= h($img['titre_image']) ?>">
        <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.9rem;"><?= h($img['titre_image']) ?></span>
            <a href="delete.php?id=<?= $img['id'] ?>" style="color: var(--color-red-accent);" onclick="return confirm('<?= __('admin_confirm_delete') ?>');"><?= __('admin_delete') ?></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (empty($images)): ?>
    <p style="text-align: center; color: var(--color-text-muted);"><?= __('no_images') ?></p>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
