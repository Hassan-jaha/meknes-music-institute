<?php
// admin/annonces/edit.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';
$pdo = getDBConnection();

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM annonces WHERE id = :id");
$stmt->execute(['id' => $id]);
$annonce = $stmt->fetch();

if (!$annonce) {
    echo "Annonce introuvable.";
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $is_pinned = isset($_POST['is_pinned']) ? 1 : 0;
    $date_expiration = trim($_POST['date_expiration'] ?? '');
    $image_path = $annonce['image_path'];

    if ($titre && $contenu && $date_expiration) {
        // Gestion de l'image (Optionnelle)
        if (isset($_FILES['image'])) {
            $uploadError = $_FILES['image']['error'];
            
            if ($uploadError === UPLOAD_ERR_OK) {
                $fileSize = $_FILES['image']['size'];
                $fileName = $_FILES['image']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png'];

                if (!in_array($fileExtension, $allowedExtensions)) {
                    $error = "Format non supporté (Uniquement JPG, JPEG, PNG).";
                } elseif ($fileSize > 5 * 1024 * 1024) {
                    $error = "L'image est trop lourde (Maximum 5 Mo).";
                } else {
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                    $dest_path = '../../public/uploads/' . $newFileName;
                    if (resizeImage($_FILES['image']['tmp_name'], $dest_path, 800, 600)) {
                        $image_path = 'public/uploads/' . $newFileName;
                    }
                }
            } elseif ($uploadError !== UPLOAD_ERR_NO_FILE) {
                $error = "Problème avec l'image : le fichier est probablement trop lourd pour le serveur.";
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("UPDATE annonces SET titre = :titre, contenu = :contenu, is_pinned = :is_pinned, date_expiration = :date_expiration, image_path = :image_path WHERE id = :id");
            if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'is_pinned' => $is_pinned, 'date_expiration' => $date_expiration, 'image_path' => $image_path, 'id' => $id])) {
                header("Location: index.php?success=edited");
                exit;
            } else {
                $error = "Erreur lors de la mise à jour.";
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<h2><?= __('admin_edit') ?> (<?= __('nav_announcements') ?>)</h2>

<?php if ($error): ?><div style="color: white; background: #e74c3c; padding: 10px; margin-bottom: 1rem; border-radius: 4px; max-width: 800px; margin-left: auto; margin-right: auto;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: white; background: #2ecc71; padding: 10px; margin-bottom: 1rem; border-radius: 4px; max-width: 800px; margin-left: auto; margin-right: auto;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Titre *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;" value="<?= h($annonce['titre']) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Date d'expiration *</label>
        <input type="date" name="date_expiration" required style="width: 100%; padding: 8px;" value="<?= h($annonce['date_expiration']) ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label>
            <input type="checkbox" name="is_pinned" <?= $annonce['is_pinned'] ? 'checked' : '' ?>> Épingler l'annonce
        </label>
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Image actuelle</label>
        <?php if ($annonce['image_path']): ?>
            <img src="<?= asset($annonce['image_path']) ?>" style="width: 100px; display: block; margin-bottom: 10px;">
        <?php else: ?>
            <p style="font-size: 0.8rem; color: gray;">Aucune image.</p>
        <?php endif; ?>
        <input type="file" name="image" accept="image/*" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Contenu *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"><?= h($annonce['contenu']) ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('form_save') ?></button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);"><?= __('form_cancel') ?></a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
