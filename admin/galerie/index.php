<?php
// admin/galerie/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$images = [];
try {
    $images = $pdo->query("SELECT * FROM galerie ORDER BY date DESC")->fetchAll();
} catch (Exception $e) {}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 class="section-title"><?= __('admin_manage_gallery') ?></h2>
    <a href="upload.php" class="btn btn-primary" style="margin-bottom: 2rem;"><?= __('admin_add_new') ?></a>
</div>

<div class="grid">
    <?php foreach ($images as $img): ?>
    <div class="card">
        <img src="../../<?= h($img['image']) ?>" class="card-img" alt="<?= h($img['description']) ?>">
        <tr style="background: var(--color-blue-deep); color: white;">
            <th style="padding: 10px; text-align: left;"><?= __('admin_image') ?></th>
            <th style="padding: 10px; text-align: left;"><?= __('contact_form_message') ?></th>
            <th style="padding: 10px; text-align: right;"><?= __('admin_table_actions') ?></th>
        </tr>
        <div class="card-body" style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.9rem;"><?= h($img['description']) ?></span>
            <a href="delete.php?id=<?= $img['id'] ?>" style="color: var(--color-red-accent);" onclick="return confirm('<?= __('admin_confirm_delete') ?>');"><?= __('admin_delete') ?></a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if (empty($images)): ?>
    <p style="text-align: center; color: var(--color-text-muted);">Aucune image dans la galerie.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
