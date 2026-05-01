<?php
// admin/galerie/upload.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = isset($_GET['success']) ? "Image téléchargée avec succès !" : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = '../../public/uploads/' . $newFileName;
            $db_path = 'public/uploads/' . $newFileName;
            
            if (resizeImage($fileTmpPath, $dest_path, 1200, 1200)) {
                $pdo = getDBConnection();
                $stmt = $pdo->prepare("INSERT INTO galerie (titre_image, image_path) VALUES (:titre, :image_path)");
                if ($stmt->execute(['titre' => $titre ?: $fileName, 'image_path' => $db_path])) {
                    header("Location: upload.php?success=1");
                    exit;
                } else {
                    $error = "Erreur lors de l'enregistrement.";
                }
            } else {
                $error = "Erreur lors du traitement de l'image.";
            }
        } else {
            $error = "Extensions autorisées : " . implode(',', $allowedfileExtensions);
        }
    } else {
        $error = "Veuillez sélectionner une image.";
    }
}
?>

<h2><?= __('admin_add_new') ?> (<?= __('nav_gallery') ?>)</h2>

<?php if ($error): ?><div style="color: white; background: #e74c3c; padding: 10px; margin-bottom: 1rem; border-radius: 4px;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: white; background: #2ecc71; padding: 10px; margin-bottom: 1rem; border-radius: 4px;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?> (<?= __('form_cancel') ?>)</label>
        <input type="text" name="titre" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('admin_image') ?> *</label>
        <input type="file" name="image" required accept="image/*" style="width: 100%; padding: 8px;">
    </div>
    <button type="submit" class="btn btn-primary"><?= __('form_save') ?></button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);"><?= __('form_cancel') ?></a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
