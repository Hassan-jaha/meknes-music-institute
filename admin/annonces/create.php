<?php
// admin/annonces/create.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    $date_expiration = trim($_POST['date_expiration'] ?? '');

    if ($titre && $contenu && $date_expiration) {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO annonces (titre, contenu, is_pinned, date_expiration) VALUES (:titre, :contenu, :is_pinned, :date_expiration)");
        if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'is_pinned' => $is_pinned, 'date_expiration' => $date_expiration])) {
            $success = "Annonce ajoutée avec succès.";
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2>Ajouter une Annonce</h2>

<?php if ($error): ?><div style="color: red; margin-bottom: 1rem;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: green; margin-bottom: 1rem;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Titre *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Date d'expiration *</label>
        <input type="date" name="date_expiration" required style="width: 100%; padding: 8px;" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label>
            <input type="checkbox" name="is_pinned"> Épingler l'annonce (Bandeau d'accueil)
        </label>
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Contenu *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);">Annuler</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
