<?php
// admin/actualites/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$actualites = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2><?= __('admin_manage_news') ?></h2>
    <a href="create.php" class="btn btn-primary">+ <?= __('admin_add_new') ?></a>
</div>

<div class="admin-table-container">
    <table>
        <thead>
            <tr>
                <th><?= __('form_label_title') ?></th>
                <th><?= __('form_label_date') ?></th>
                <th style="text-align: right;"><?= __('admin_actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actualites as $news): ?>
            <tr>
                <td style="font-weight: bold;"><?= h($news['titre']) ?></td>
                <td style="color: #64748b;"><?= formatDate($news['date_publication']) ?></td>
                <td style="text-align: right;">
                    <a href="edit.php?id=<?= $news['id'] ?>" style="color: #6366f1; margin-right: 15px; font-weight: 600; text-decoration: none;"><?= __('admin_edit') ?></a>
                    <a href="delete.php?id=<?= $news['id'] ?>" style="color: #ef4444; font-weight: 600; text-decoration: none; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 4px;" onclick="return confirm('<?= __('admin_confirm_delete') ?>')"><?= __('admin_delete') ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($actualites)): ?>
                <tr><td colspan="3" style="padding: 30px; text-align: center; color: #a0aec0;"><?= __('no_news') ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
