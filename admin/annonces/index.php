<?php
// admin/annonces/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$annonces = $pdo->query("SELECT * FROM annonces ORDER BY created_at DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2><?= __('admin_manage_announcements') ?></h2>
    <a href="create.php" class="btn btn-primary">+ <?= __('admin_add_new') ?></a>
</div>

<div class="admin-table-container">
    <table>
        <thead>
            <tr>
                <th><?= __('form_label_title') ?></th>
                <th>Expire le</th>
                <th>Épinglé</th>
                <th style="text-align: right;"><?= __('admin_actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($annonces as $annonce): ?>
            <tr>
                <td style="font-weight: bold;"><?= h($annonce['titre']) ?></td>
                <td><?= formatDate($annonce['date_expiration']) ?></td>
                <td>
                    <?php if ($annonce['is_pinned']): ?>
                        <span style="background: #fffbeb; color: #92400e; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; border: 1px solid #fde68a;">📌 Épinglé</span>
                    <?php else: ?>
                        <span style="color: #94a3b8; font-size: 0.75rem;">-</span>
                    <?php endif; ?>
                </td>
                <td style="text-align: right;">
                    <a href="edit.php?id=<?= $annonce['id'] ?>" style="color: #6366f1; margin-right: 15px; font-weight: 600; text-decoration: none;"><?= __('admin_edit') ?></a>
                    <a href="delete.php?id=<?= $annonce['id'] ?>" style="color: #ef4444; font-weight: 600; text-decoration: none; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 4px;" onclick="return confirm('<?= __('admin_confirm_delete') ?>')"><?= __('admin_delete') ?></a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($annonces)): ?>
                <tr><td colspan="4" style="padding: 30px; text-align: center; color: #a0aec0;"><?= __('no_announcements') ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
