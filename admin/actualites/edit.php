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
    $date_publication = trim($_POST['date_publication'] ?? '');
    $image_path = $actualite['image_path'];

    if ($titre && $contenu && $date_publication) {
        try {
            // Gestion de l'image via helper
            $image_path = handleImageUpload('image', $actualite['image_path']);

            if (!isset($_SESSION['flash_error'])) {
                $stmt = $pdo->prepare("UPDATE actualites SET titre = :titre, contenu = :contenu, date_publication = :date_publication, image_path = :image_path WHERE id = :id");
                $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'date_publication' => $date_publication, 'image_path' => $image_path, 'id' => $id]);
                
                $_SESSION['flash_success'] = "Actualité mise à jour !";
                header("Location: index.php");
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur Base de données : " . $e->getMessage();
        }
    } else {
        $_SESSION['flash_error'] = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2><?= __('admin_edit') ?> (<?= __('nav_news') ?>)</h2>

<div class="admin-form-container">
    <form method="POST" action="" enctype="multipart/form-data">
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?> *</label>
            <input type="text" name="titre" value="<?= h($actualite['titre']) ?>" required style="width: 100%; padding: 8px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;"><?= __('form_label_date') ?> *</label>
            <input type="date" name="date_publication" value="<?= $actualite['date_publication'] ?>" required style="width: 100%; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">Image actuelle</label>
            <?php if ($actualite['image_path']): ?>
                <img src="<?= get_image_url($actualite['image_path']) ?>" style="width: 150px; display: block; margin-bottom: 10px; border-radius: 8px;">
            <?php endif; ?>
            <input type="file" name="image" accept="image/png, image/jpeg" style="width: 100%; padding: 8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;"><?= __('form_label_content') ?> *</label>
            <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"><?= h($actualite['contenu']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px;"><?= __('form_save') ?></button>
    </form>
</div>
<a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);"><?= __('form_cancel') ?></a>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
