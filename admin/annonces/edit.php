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
    $image_path = $annonce['image_path'];

    if ($titre && $contenu && $date_expiration) {
        // Gestion de l'image (si nouvelle image fournie)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = '../../public/uploads/' . $newFileName;
            
            if (resizeImage($fileTmpPath, $dest_path, 800, 600)) {
                $image_path = 'public/uploads/' . $newFileName;
            }
        }

        $stmt = $pdo->prepare("UPDATE annonces SET titre = :titre, contenu = :contenu, is_pinned = :is_pinned, date_expiration = :date_expiration, image_path = :image_path WHERE id = :id");
        if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'is_pinned' => $is_pinned, 'date_expiration' => $date_expiration, 'image_path' => $image_path, 'id' => $id])) {
            $success = "Annonce mise à jour avec succès.";
            $annonce['titre'] = $titre;
            $annonce['contenu'] = $contenu;
            $annonce['is_pinned'] = $is_pinned;
            $annonce['date_expiration'] = $date_expiration;
            $annonce['image_path'] = $image_path;
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

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
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
            <input type="checkbox" name="is_pinned" <?= $annonce['is_pinned'] ? 'checked' : '' ?>> Épingler l'annonce
        </label>
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Image actuelle</label>
        <?php if ($annonce['image_path']): ?>
            <img src="<?= asset($annonce['image_path']) ?>" style="width: 100px; display: block; margin-bottom: 10px;">
        <?php else: ?>
            <p style="font-size: 0.8rem; color: gray;">Aucune image.</p>
        <?php endif; ?>
        <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Contenu *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"><?= h($annonce['contenu']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);">Retour</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
