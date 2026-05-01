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

<div class="card" style="padding: 0; overflow: hidden; border: none;">
    <table style="width: 100%; border-collapse: collapse; background: white;">
        <thead>
            <tr style="background: var(--admin-primary); color: white;">
                <th style="padding: 15px; text-align: left;"><?= __('admin_table_title') ?></th>
                <th style="padding: 15px; text-align: left;"><?= __('admin_table_date') ?></th>
                <th style="padding: 15px; text-align: right;"><?= __('admin_table_actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($actualites as $actu): ?>
            <tr style="border-bottom: 1px solid #edf2f7; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                <td style="padding: 15px; font-weight: 500;"><?= h($actu['titre']) ?></td>
                <td style="padding: 15px; color: #718096;"><?= formatDate($actu['date_publication']) ?></td>
                <td style="padding: 15px; text-align: right;">
                    <a href="edit.php?id=<?= $actu['id'] ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.8rem; margin-right: 5px;"><?= __('admin_edit') ?></a>
                    <a href="delete.php?id=<?= $actu['id'] ?>" class="btn" style="padding: 4px 10px; font-size: 0.8rem; color: #e74c3c; border: 1px solid #e74c3c;"><?= __('admin_delete') ?></a>
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
