<?php
// admin/actualites/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$actualites = [];
try {
    $actualites = $pdo->query("SELECT * FROM actualites ORDER BY date DESC")->fetchAll();
} catch (Exception $e) {}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 class="section-title"><?= __('admin_manage_news') ?></h2>
    <a href="create.php" class="btn btn-primary" style="margin-bottom: 2rem;"><?= __('admin_add_new') ?></a>
</div>

<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <thead>
        <tr style="background: var(--color-blue-deep); color: white;">
            <th style="padding: 10px; text-align: left;"><?= __('admin_table_title') ?></th>
            <th style="padding: 10px; text-align: left;"><?= __('admin_table_date') ?></th>
            <th style="padding: 10px; text-align: right;"><?= __('admin_table_actions') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($actualites as $actu): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 10px;"><?= h($actu['titre']) ?></td>
            <td style="padding: 10px;"><?= formatDate($actu['date']) ?></td>
            <td style="padding: 10px; text-align: right;">
                <a href="edit.php?id=<?= $actu['id'] ?>" style="color: var(--color-blue-accent); margin-right: 10px;"><?= __('admin_edit') ?></a>
                <a href="delete.php?id=<?= $actu['id'] ?>" style="color: var(--color-red-accent);" onclick="return confirm('<?= __('admin_confirm_delete') ?>');"><?= __('admin_delete') ?></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($actualites)): ?>
            <tr><td colspan="3" style="padding: 20px; text-align: center; color: var(--color-text-muted);"><?= __('no_news') ?></td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
