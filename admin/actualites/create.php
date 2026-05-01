<?php
// admin/actualites/create.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date_publication = trim($_POST['date_publication'] ?? '');
    $image_path = 'public/images/placeholder.jpg'; 

    if ($titre && $contenu && $date_publication) {
        // Gestion de l'image (si fournie)
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = '../../public/uploads/' . $newFileName;
            
            if (resizeImage($fileTmpPath, $dest_path, 800, 600)) {
                $image_path = 'public/uploads/' . $newFileName;
            }
        }

        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, image_path, date_publication) VALUES (:titre, :contenu, :image_path, :date_publication)");
        if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'image_path' => $image_path, 'date_publication' => $date_publication])) {
            $success = "Actualité ajoutée avec succès.";
        } else {
            $error = "Erreur lors de l'ajout en base de données.";
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2>Ajouter une Actualité</h2>

<?php if ($error): ?><div style="color: red; margin-bottom: 1rem;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: green; margin-bottom: 1rem;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Titre *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Date de publication *</label>
        <input type="date" name="date_publication" required style="width: 100%; padding: 8px;" value="<?= date('Y-m-d') ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Image (Optionnelle)</label>
        <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Contenu *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);">Annuler</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
