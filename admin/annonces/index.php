<?php
// admin/annonces/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();
$annonces = $pdo->query("SELECT * FROM annonces ORDER BY created_at DESC")->fetchAll();
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2>Gestion des Annonces</h2>
    <a href="create.php" class="btn btn-primary">+ Ajouter une annonce</a>
</div>

<table style="width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <thead>
        <tr style="background: var(--color-blue-primary); color: white;">
            <th style="padding: 10px; text-align: left;">Titre</th>
            <th style="padding: 10px; text-align: center;">Épinglée</th>
            <th style="padding: 10px; text-align: left;">Expiration</th>
            <th style="padding: 10px; text-align: right;">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($annonces as $annonce): ?>
        <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 10px;"><?= h($annonce['titre']) ?></td>
            <td style="padding: 10px; text-align: center;"><?= $annonce['is_pinned'] ? '✅' : '❌' ?></td>
            <td style="padding: 10px;"><?= formatDateFR($annonce['date_expiration']) ?></td>
            <td style="padding: 10px; text-align: right;">
                <a href="edit.php?id=<?= $annonce['id'] ?>" style="color: var(--color-blue-accent); margin-right: 10px;">Modifier</a>
                <a href="delete.php?id=<?= $annonce['id'] ?>" style="color: var(--color-red-accent);" onclick="return confirm('Êtes-vous sûr ?');">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
