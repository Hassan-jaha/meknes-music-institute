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
        try {
            // Gestion de l'image (Optionnelle)
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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
                    if (resizeImage($_FILES['image']['tmp_name'], $dest_path, 1200, 800)) {
                        $image_path = 'public/uploads/' . $newFileName;
                    }
                }
            }

            if (!isset($_SESSION['flash_error'])) {
                $stmt = $pdo->prepare("UPDATE annonces SET titre = :titre, contenu = :contenu, is_pinned = :is_pinned, date_expiration = :date_expiration, image_path = :image_path WHERE id = :id");
                $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'is_pinned' => $is_pinned, 'date_expiration' => $date_expiration, 'image_path' => $image_path, 'id' => $id]);
                
                $_SESSION['flash_success'] = "Annonce mise à jour !";
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

<h2><?= __('admin_edit') ?> (<?= __('nav_announcements') ?>)</h2>

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
