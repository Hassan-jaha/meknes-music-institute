<?php
// admin/galerie/upload.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        try {
            $fileSize = $_FILES['image']['size'];
            $fileName = $_FILES['image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                $_SESSION['flash_error'] = "Format non supporté (JPG, PNG, WEBP).";
            } elseif ($fileSize > 5 * 1024 * 1024) {
                $_SESSION['flash_error'] = "L'image est trop lourde (Max 5 Mo).";
            } else {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = '../../public/uploads/' . $newFileName;
                $db_path = 'public/uploads/' . $newFileName;
                
                if (resizeImage($_FILES['image']['tmp_name'], $dest_path, 1200, 1200)) {
                    $pdo = getDBConnection();
                    $stmt = $pdo->prepare("INSERT INTO galerie (titre_image, image_path) VALUES (:titre, :image_path)");
                    $stmt->execute(['titre' => $titre ?: $fileName, 'image_path' => $db_path]);
                    
                    $_SESSION['flash_success'] = "Image ajoutée à la galerie !";
                    header("Location: index.php");
                    exit;
                } else {
                    $_SESSION['flash_error'] = "Erreur lors du traitement de l'image.";
                }
            }
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur Base de données : " . $e->getMessage();
        }
    } else {
        $_SESSION['flash_error'] = "Veuillez sélectionner une image valide.";
    }
}
?>

<div style="max-width: 800px; margin: 0 auto 2rem auto; display: flex; justify-content: space-between; align-items: center;">
    <h2><?= __('admin_add_new') ?> (<?= __('nav_gallery') ?>)</h2>
    <a href="index.php" class="btn"><?= __('form_cancel') ?></a>
</div>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?> (<?= __('form_cancel') ?>)</label>
        <input type="text" name="titre" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('admin_image') ?> * (Max 5Mo, JPG/PNG)</label>
        <input type="file" name="image" required accept="image/png, image/jpeg" style="width: 100%; padding: 8px;">
    </div>
    <button type="submit" class="btn btn-primary"><?= __('form_save') ?></button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
