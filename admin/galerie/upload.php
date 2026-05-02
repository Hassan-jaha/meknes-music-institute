<?php
// admin/galerie/upload.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre      = trim($_POST['titre'] ?? '');
    $image_path = handleImageUpload('image');

    if ($image_path && !isset($_SESSION['flash_error'])) {
        try {
            $pdo = getDBConnection();
            $pdo->prepare("INSERT INTO galerie (titre_image, image_path) VALUES (?, ?)")
                ->execute([$titre ?: 'Image', $image_path]);
            $_SESSION['flash_success'] = "Image ajoutée et optimisée avec succès !";
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur : " . $e->getMessage();
        }
    } elseif (!isset($_SESSION['flash_error'])) {
        $_SESSION['flash_error'] = "Veuillez sélectionner une image (JPG, PNG ou WEBP).";
    }
    header("Location: " . asset('admin/galerie/upload.php'));
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width:800px; margin:0 auto 2rem; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
    <h2 style="margin:0;"><?= __('admin_add_new') ?> — <?= __('nav_gallery') ?></h2>
    <a href="<?= asset('admin/galerie/index.php') ?>" class="btn">← <?= __('form_cancel') ?></a>
</div>

<div class="admin-form-container">
    <div style="background:#f0f9ff; padding:18px 20px; border-radius:10px; border-left:5px solid #0ea5e9; margin-bottom:25px;">
        <h4 style="margin:0 0 8px; color:#0369a1;">💡 Recommandations</h4>
        <ul style="margin:0; color:#0c4a6e; padding-left:20px; font-size:0.9rem;">
            <li>Dimensions idéales : <strong>1200 × 800 px (Ratio 3:2)</strong></li>
            <li>Conversion automatique en <strong>WebP</strong> pour une vitesse optimale</li>
            <li>Taille max : <strong>5 Mo</strong></li>
        </ul>
    </div>

    <form method="POST" action="<?= asset('admin/galerie/upload.php') ?>" enctype="multipart/form-data">
        <div style="margin-bottom:20px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;"><?= __('form_label_title') ?> <small style="color:#64748b;">(Optionnel)</small></label>
            <input type="text" name="titre" placeholder="ex: Concert de Printemps 2026" style="width:100%; padding:12px; border:1px solid #e2e8f0; border-radius:8px; font-size:1rem;">
        </div>
        <div style="margin-bottom:24px;">
            <label style="display:block; margin-bottom:8px; font-weight:600;"><?= __('admin_image') ?> *</label>
            <label style="display:block; border:2px dashed #c2a661; border-radius:10px; padding:32px; text-align:center; cursor:pointer; background:#fffbf0; transition:background 0.2s;" onmouseover="this.style.background='#fff7e6'" onmouseout="this.style.background='#fffbf0'">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#c2a661" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block; margin:0 auto 12px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                <span style="font-weight:700; color:#92400e; display:block; margin-bottom:4px;">Cliquer pour choisir une image</span>
                <span style="font-size:0.8rem; color:#92400e; opacity:0.7;">JPG, PNG, WEBP — Max 5 Mo</span>
                <input type="file" name="image" required accept="image/png,image/jpeg,image/webp" style="display:none;" onchange="this.parentElement.querySelector('span').textContent = this.files[0]?.name || 'Fichier sélectionné'">
            </label>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%; padding:15px; font-size:1rem; font-weight:700;">
            📤 <?= __('form_save') ?>
        </button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
