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

<div class="card" style="padding: 0; overflow: hidden; border: none;">
    <table style="width: 100%; border-collapse: collapse; background: white;">
        <thead>
            <tr style="background: var(--admin-primary); color: white;">
                <th style="padding: 15px; text-align: left;"><?= __('admin_table_title') ?></th>
                <th style="padding: 15px; text-align: left;"><?= __('expires_on') ?></th>
                <th style="padding: 15px; text-align: center;"><?= __('admin_pinned') ?></th>
                <th style="padding: 15px; text-align: right;"><?= __('admin_table_actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($annonces as $annonce): ?>
            <tr style="border-bottom: 1px solid #edf2f7; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                <td style="padding: 15px; font-weight: 500;"><?= h($annonce['titre']) ?></td>
                <td style="padding: 15px; color: #718096;"><?= formatDate($annonce['date_expiration']) ?></td>
                <td style="padding: 15px; text-align: center;">
                    <?php if ($annonce['is_pinned']): ?>
                        <span style="background: #fff3cd; color: #856404; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem;">📌 <?= __('admin_pinned') ?></span>
                    <?php endif; ?>
                </td>
                <td style="padding: 15px; text-align: right;">
                    <a href="edit.php?id=<?= $annonce['id'] ?>" class="btn btn-secondary" style="padding: 4px 10px; font-size: 0.8rem; margin-right: 5px;"><?= __('admin_edit') ?></a>
                    <a href="delete.php?id=<?= $annonce['id'] ?>" class="btn" style="padding: 4px 10px; font-size: 0.8rem; color: #e74c3c; border: 1px solid #e74c3c;"><?= __('admin_delete') ?></a>
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
