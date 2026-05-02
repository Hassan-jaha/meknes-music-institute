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
                
                // On force le format WebP et le ratio 3:2 (1200x800)
                $final_dest = resizeImage($_FILES['image']['tmp_name'], $dest_path, 1200, 800, true);
                
                if ($final_dest) {
                    // On récupère le nom du fichier final (qui peut avoir changé d'extension en .webp)
                    $finalFileName = basename($final_dest);
                    $db_path = 'public/uploads/' . $finalFileName;

                    $pdo = getDBConnection();
                    $stmt = $pdo->prepare("INSERT INTO galerie (titre_image, image_path) VALUES (:titre, :image_path)");
                    $stmt->execute(['titre' => $titre ?: $fileName, 'image_path' => $db_path]);
                    
                    $_SESSION['flash_success'] = "Image ajoutée et optimisée en WebP !";
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

<div style="max-width: 800px; margin: 0 auto 1.5rem auto; background: #e3f2fd; padding: 20px; border-radius: 8px; border-left: 5px solid #2196f3; font-size: 0.9rem;">
    <h4 style="margin-top: 0; color: #1976d2;">💡 Recommandations pour le المعرض :</h4>
    <ul style="margin-bottom: 0;">
        <li><strong>Dimensions :</strong> 1200 × 800 px (Ratio 3:2) pour un affichage parfait.</li>
        <li><strong>Optimisation :</strong> Le système convertit automatiquement vos images en <strong>WebP</strong> pour une rapidité maximale.</li>
        <li><strong>Poids :</strong> Essayez d'utiliser des fichiers entre 200KB et 500KB avant envoi.</li>
    </ul>
</div>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?></label>
        <input type="text" name="titre" placeholder="Titre de l'image (ex: Concert de Printemps)" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
    </div>
    <div style="margin-bottom: 20px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('admin_image') ?> *</label>
        <input type="file" name="image" required accept="image/*" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
        <small style="color: #666;">Formats acceptés : JPG, PNG, WEBP (Max 5Mo)</small>
    </div>
    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: bold;"><?= __('form_save') ?></button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
