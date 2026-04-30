<?php
// admin/actualites/edit.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';
$pdo = getDBConnection();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM actualites WHERE id = :id");
$stmt->execute(['id' => $id]);
$actualite = $stmt->fetch();

if (!$actualite) {
    echo "Actualité introuvable.";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $image_path = $actualite['image'];

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = '../../public/uploads/' . $newFileName;
            if (resizeImage($fileTmpPath, $dest_path, 1200, 1200)) {
                $image_path = 'public/uploads/' . $newFileName;
            }
        }
    }
    
    if ($titre && $contenu && $date) {
        $stmt = $pdo->prepare("UPDATE actualites SET titre = :titre, contenu = :contenu, date = :date, image = :image WHERE id = :id");
        if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'date' => $date, 'image' => $image_path, 'id' => $id])) {
            $success = "Actualité mise à jour.";
            $actualite['titre'] = $titre;
            $actualite['contenu'] = $contenu;
            $actualite['date'] = $date;
            $actualite['image'] = $image_path;
        } else {
            $error = "Erreur lors de la mise à jour.";
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2><?= __('admin_edit') ?></h2>

<?php if ($error): ?><div style="color: red; margin-bottom: 1rem;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: green; margin-bottom: 1rem;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?> *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;" value="<?= h($actualite['titre']) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_date') ?> *</label>
        <input type="date" name="date" required style="width: 100%; padding: 8px;" value="<?= h($actualite['date']) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Image actuelle</label>
        <img src="../../<?= h($actualite['image']) ?>" style="height: 100px; display: block; margin-bottom: 10px;">
        <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_content') ?> *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"><?= h($actualite['contenu']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('form_save') ?></button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);"><?= __('form_cancel') ?></a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
