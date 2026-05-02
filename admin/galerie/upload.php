<?php
// admin/galerie/upload.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    
    $image_path = handleImageUpload('image');
    
    if ($image_path && !isset($_SESSION['flash_error'])) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO galerie (titre_image, image_path) VALUES (:titre, :image_path)");
            $stmt->execute(['titre' => $titre ?: $_FILES['image']['name'], 'image_path' => $image_path]);
            
            $_SESSION['flash_success'] = "Image ajoutée et optimisée en WebP !";
            header("Location: upload.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur Base de données : " . $e->getMessage();
        }
    } elseif (!isset($_SESSION['flash_error'])) {
        $_SESSION['flash_error'] = "Veuillez sélectionner une image valide.";
    }
}
?>

<div style="max-width: 800px; margin: 0 auto 2rem auto; display: flex; justify-content: space-between; align-items: center;">
    <h2><?= __('admin_add_new') ?> (<?= __('nav_gallery') ?>)</h2>
    <a href="index.php" class="btn"><?= __('form_cancel') ?></a>
</div>

<div class="admin-form-container">
    <div style="background: #f0f9ff; padding: 20px; border-radius: 8px; border-left: 5px solid #0ea5e9; font-size: 0.85rem; margin-bottom: 25px;">
        <h4 style="margin-top: 0; color: #0369a1;">💡 Recommandations pour le المعرض :</h4>
        <ul style="margin-bottom: 0; color: #0c4a6e;">
            <li><strong>Dimensions :</strong> 1200 × 800 px (Ratio 3:2) pour un affichage parfait.</li>
            <li><strong>Optimisation :</strong> Le système convertit automatiquement vos images en <strong>WebP</strong>.</li>
        </ul>
    </div>

    <form method="POST" action="" enctype="multipart/form-data">
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
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
