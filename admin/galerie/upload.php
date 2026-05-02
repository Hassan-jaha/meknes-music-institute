<?php
// admin/galerie/upload.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . asset('admin/login.php'));
    exit;
}

setLanguage($_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr'));

// === TRAITEMENT DU FORMULAIRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');

    $image_path = handleImageUpload('image');

    if ($image_path && !isset($_SESSION['flash_error'])) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO galerie (titre_image, image_path) VALUES (:titre, :image_path)");
            $stmt->execute(['titre' => $titre ?: 'Image', 'image_path' => $image_path]);
            
            $_SESSION['flash_success'] = "✅ Image ajoutée avec succès et optimisée en WebP !";
            header("Location: upload.php");
            exit;
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur Base de données : " . $e->getMessage();
        }
    } elseif (!isset($_SESSION['flash_error'])) {
        $_SESSION['flash_error'] = "Veuillez sélectionner une image (JPG, PNG ou WEBP).";
    }
}

// === AFFICHAGE ===
require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 800px; margin: 0 auto 2rem auto; display: flex; justify-content: space-between; align-items: center;">
    <h2><?= __('admin_add_new') ?> (<?= __('nav_gallery') ?>)</h2>
    <a href="<?= asset('admin/galerie/index.php') ?>" class="btn">← <?= __('form_cancel') ?></a>
</div>

<div class="admin-form-container">
    <div style="background: #f0f9ff; padding: 20px; border-radius: 10px; border-left: 5px solid #0ea5e9; margin-bottom: 25px;">
        <h4 style="margin: 0 0 10px 0; color: #0369a1;">💡 Recommandations :</h4>
        <ul style="margin: 0; color: #0c4a6e; padding-left: 20px;">
            <li><strong>Dimensions idéales :</strong> 1200 × 800 px (Ratio 3:2)</li>
            <li><strong>Optimisation automatique :</strong> Vos images sont converties en <strong>WebP</strong> pour une vitesse maximale</li>
        </ul>
    </div>

    <form method="POST" action="" enctype="multipart/form-data">
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_title') ?> <small style="color:#64748b;">(Optionnel)</small></label>
            <input type="text" name="titre" placeholder="ex: Concert de Printemps 2026" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('admin_image') ?> * <small style="color:#64748b;">(Max 5Mo — JPG, PNG, WEBP)</small></label>
            <input type="file" name="image" required accept="image/png, image/jpeg, image/webp" style="width: 100%; padding: 10px; border: 2px dashed #c2a661; border-radius: 8px; background: #fffbf0; cursor: pointer;">
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1rem; font-weight: 700;">📤 <?= __('form_save') ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
