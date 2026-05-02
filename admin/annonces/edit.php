<?php
// admin/annonces/edit.php

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

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = :id");
$stmt->execute(['id' => $id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    $_SESSION['flash_error'] = "Annonce introuvable.";
    header("Location: " . asset('admin/annonces/index.php'));
    exit;
}

// === TRAITEMENT DU FORMULAIRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    $date_expiration = trim($_POST['date_expiration'] ?? '');

    if ($titre && $contenu && $date_expiration) {
        try {
            $image_path = handleImageUpload('image', $annonce['image_path']);

            if (!isset($_SESSION['flash_error'])) {
                $stmt = $pdo->prepare("UPDATE annonces SET titre = :titre, contenu = :contenu, is_pinned = :is_pinned, date_expiration = :date_expiration, image_path = :image_path WHERE id = :id");
                $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'is_pinned' => $is_pinned, 'date_expiration' => $date_expiration, 'image_path' => $image_path, 'id' => $id]);
                
                $_SESSION['flash_success'] = "✅ Annonce mise à jour avec succès !";
                header("Location: edit.php?id=" . $id);
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
    <h2><?= __('admin_edit') ?> — <?= h($annonce['titre']) ?></h2>
    <a href="<?= asset('admin/annonces/index.php') ?>" class="btn">← <?= __('form_cancel') ?></a>
</div>

<div class="admin-form-container">
    <form method="POST" action="" enctype="multipart/form-data">
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_title') ?> *</label>
            <input type="text" name="titre" value="<?= h($annonce['titre']) ?>" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Date d'expiration *</label>
            <input type="date" name="date_expiration" value="<?= h($annonce['date_expiration']) ?>" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
        </div>
        <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
            <input type="checkbox" name="is_pinned" id="is_pinned" <?= $annonce['is_pinned'] ? 'checked' : '' ?> style="width: 18px; height: 18px;">
            <label for="is_pinned" style="font-weight: 600; cursor: pointer;">📌 Épingler cette annonce</label>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('admin_image') ?></label>
            <?php if ($annonce['image_path']): ?>
                <img src="<?= get_image_url($annonce['image_path']) ?>" style="width: 180px; border-radius: 10px; margin-bottom: 12px; display: block;">
            <?php endif; ?>
            <input type="file" name="image" accept="image/png, image/jpeg, image/webp" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
            <small style="color: #64748b;">Laisser vide pour conserver l'image actuelle.</small>
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_content') ?> *</label>
            <textarea name="contenu" rows="8" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"><?= h($annonce['contenu']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1rem; font-weight: 700;"><?= __('form_save') ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
