<?php
// admin/actualites/create.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $image_path = 'public/images/placeholder.jpg'; // Par défaut

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = '../../public/uploads/' . $newFileName;
            if (resizeImage($fileTmpPath, $dest_path, 1200, 1200)) {
                $image_path = 'public/uploads/' . $newFileName;
            } else {
                $error = "Impossible de redimensionner l'image. Vérifiez l'extension GD.";
            }
        } else {
            $error = "Format d'image non supporté.";
        }
    } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $error = "Erreur d'upload (Code: " . $_FILES['image']['error'] . ")";
    }

    if ($titre && $contenu && $date) {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, image, date) VALUES (:titre, :contenu, :image, :date)");
        if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'image' => $image_path, 'date' => $date])) {
            $success = __('admin_error_id') === 'Identifiants incorrects.' ? "Actualité ajoutée !" : __('admin_dashboard'); // Utilisation de clés existantes ou nouvelles
            $success = "OK"; // Je vais plutôt ajouter de nouvelles clés spécifiques
        } else {
            $error = "Error";
        }
    } else {
        $error = __('admin_error_fields');
    }
}
?>

<h2><?= __('admin_add_new') ?></h2>

<?php if ($error): ?><div style="color: red; margin-bottom: 1rem;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: green; margin-bottom: 1rem;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?> *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_date') ?> *</label>
        <input type="date" name="date" required style="width: 100%; padding: 8px;" value="<?= date('Y-m-d') ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('admin_image') ?></label>
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
