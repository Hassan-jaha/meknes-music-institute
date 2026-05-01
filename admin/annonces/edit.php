<?php
// admin/annonces/edit.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';
$pdo = getDBConnection();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = :id");
$stmt->execute(['id' => $id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    echo "Annonce introuvable.";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    $date_expiration = trim($_POST['date_expiration'] ?? '');

    if ($titre && $contenu && $date_expiration) {
        $stmt = $pdo->prepare("UPDATE annonces SET titre = :titre, contenu = :contenu, is_pinned = :is_pinned, date_expiration = :date_expiration WHERE id = :id");
        if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'is_pinned' => $is_pinned, 'date_expiration' => $date_expiration, 'id' => $id])) {
            $success = "Annonce mise à jour.";
            $annonce['titre'] = $titre;
            $annonce['contenu'] = $contenu;
            $annonce['is_pinned'] = $is_pinned;
            $annonce['date_expiration'] = $date_expiration;
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2>Modifier l'Annonce</h2>

<?php if ($error): ?><div style="color: red; margin-bottom: 1rem;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: green; margin-bottom: 1rem;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Titre *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;" value="<?= h($annonce['titre']) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Date d'expiration *</label>
        <input type="date" name="date_expiration" required style="width: 100%; padding: 8px;" value="<?= h($annonce['date_expiration']) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label>
            <input type="checkbox" name="is_pinned" <?= $annonce['is_pinned'] ? 'checked' : '' ?>> Épingler l'annonce (Bandeau d'accueil)
        </label>
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Contenu *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"><?= h($annonce['contenu']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);">Retour</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
