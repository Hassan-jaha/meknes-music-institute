<?php
// admin/actualites/edit.php

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

$pdo = getDBConnection();
$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM actualites WHERE id = :id");
$stmt->execute(['id' => $id]);
$actualite = $stmt->fetch();

if (!$actualite) {
    $_SESSION['flash_error'] = "Actualité introuvable.";
    header("Location: " . asset('admin/actualites/index.php'));
    exit;
}

// === TRAITEMENT DU FORMULAIRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date_publication = trim($_POST['date_publication'] ?? '');

    if ($titre && $contenu && $date_publication) {
        try {
            $image_path = handleImageUpload('image', $actualite['image_path']);

            if (!isset($_SESSION['flash_error'])) {
                $stmt = $pdo->prepare("UPDATE actualites SET titre = :titre, contenu = :contenu, date_publication = :date_publication, image_path = :image_path WHERE id = :id");
                $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'date_publication' => $date_publication, 'image_path' => $image_path, 'id' => $id]);
                
                $_SESSION['flash_success'] = "✅ Actualité mise à jour avec succès !";
                header("Location: " . asset('admin/actualites/index.php'));
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur Base de données : " . $e->getMessage();
        }
    } else {
        $_SESSION['flash_error'] = "Veuillez remplir tous les champs obligatoires.";
    }
}

// === AFFICHAGE ===
require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 800px; margin: 0 auto 2rem auto; display: flex; justify-content: space-between; align-items: center;">
    <h2><?= __('admin_edit') ?> — <?= h($actualite['titre']) ?></h2>
    <a href="<?= asset('admin/actualites/index.php') ?>" class="btn">← <?= __('form_cancel') ?></a>
</div>

<div class="admin-form-container">
    <form method="POST" action="" enctype="multipart/form-data">
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_title') ?> *</label>
            <input type="text" name="titre" value="<?= h($actualite['titre']) ?>" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_date') ?> *</label>
            <input type="date" name="date_publication" value="<?= $actualite['date_publication'] ?>" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('admin_image') ?></label>
            <?php if ($actualite['image_path']): ?>
                <img src="<?= get_image_url($actualite['image_path']) ?>" style="width: 180px; border-radius: 10px; margin-bottom: 12px; display: block;">
            <?php endif; ?>
            <input type="file" name="image" accept="image/png, image/jpeg, image/webp" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
            <small style="color: #64748b;">Laisser vide pour conserver l'image actuelle.</small>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_content') ?> *</label>
            <textarea name="contenu" rows="8" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"><?= h($actualite['contenu']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1rem; font-weight: 700;"><?= __('form_save') ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
