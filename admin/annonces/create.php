<?php
// admin/annonces/create.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    $date_expiration = trim($_POST['date_expiration'] ?? '');
    $image_path = null;

    if ($titre && $contenu && $date_expiration) {
        try {
            $pdo = getDBConnection();
            
            // Gestion de l'image (si fournie)
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES['image']['tmp_name'];
                $fileName = $_FILES['image']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $dest_path = '../../public/uploads/' . $newFileName;
                
                if (resizeImage($fileTmpPath, $dest_path, 800, 600)) {
                    $image_path = 'public/uploads/' . $newFileName;
                }
            }

            $stmt = $pdo->prepare("INSERT INTO annonces (titre, contenu, is_pinned, date_expiration, image_path) VALUES (:titre, :contenu, :is_pinned, :date_expiration, :image_path)");
            if ($stmt->execute([
                'titre' => $titre, 
                'contenu' => $contenu, 
                'is_pinned' => $is_pinned, 
                'date_expiration' => $date_expiration, 
                'image_path' => $image_path
            ])) {
                $success = "Annonce ajoutée avec succès !";
            } else {
                $error = "Erreur lors de l'enregistrement.";
            }
        } catch (PDOException $e) {
            $error = "Erreur Base de données : " . $e->getMessage() . " (Avez-vous ajouté la colonne image_path ?)";
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2><?= __('admin_add_new') ?> (<?= __('nav_announcements') ?>)</h2>

<?php if ($error): ?><div style="color: white; background: #e74c3c; padding: 10px; margin-bottom: 1rem; border-radius: 4px;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: white; background: #2ecc71; padding: 10px; margin-bottom: 1rem; border-radius: 4px;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?> *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('expires_on') ?> *</label>
        <input type="date" name="date_expiration" required style="width: 100%; padding: 8px;" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label>
            <input type="checkbox" name="is_pinned"> <?= __('admin_pinned') ?>
        </label>
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('admin_image') ?> (<?= __('form_cancel') ?>)</label>
        <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_content') ?> *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('form_save') ?></button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);"><?= __('form_cancel') ?></a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
