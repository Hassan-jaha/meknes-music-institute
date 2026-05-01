<?php
// admin/actualites/create.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date_publication = trim($_POST['date_publication'] ?? '');
    $image_path = 'public/images/placeholder.jpg'; 

    if ($titre && $contenu && $date_publication) {
        try {
            $pdo = getDBConnection();
            
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
                $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, image_path, date_publication) VALUES (:titre, :contenu, :image_path, :date_publication)");
                $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'image_path' => $image_path, 'date_publication' => $date_publication]);
                
                $_SESSION['flash_success'] = "Actualité ajoutée avec succès !";
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

<div style="max-width: 800px; margin: 0 auto 2rem auto; display: flex; justify-content: space-between; align-items: center;">
    <h2><?= __('admin_add_new') ?> (<?= __('nav_news') ?>)</h2>
    <a href="index.php" class="btn"><?= __('form_cancel') ?></a>
</div>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_title') ?> *</label>
        <input type="text" name="titre" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_date') ?> *</label>
        <input type="date" name="date_publication" required style="width: 100%; padding: 8px;" value="<?= date('Y-m-d') ?>">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('admin_image') ?> (Optionnel - Max 5Mo)</label>
        <input type="file" name="image" accept="image/png, image/jpeg" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;"><?= __('form_label_content') ?> *</label>
        <textarea name="contenu" rows="8" required style="width: 100%; padding: 8px;"></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?= __('form_save') ?></button>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
