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
                    // Si ce n'est pas "Pas de fichier", alors c'est une vraie erreur (ex: trop lourd pour le serveur)
                    $error = "Problème avec l'image : le fichier est probablement trop lourd pour le serveur.";
                }
            }

            if (!$error) {
                $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, image_path, date_publication) VALUES (:titre, :contenu, :image_path, :date_publication)");
                if ($stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'image_path' => $image_path, 'date_publication' => $date_publication])) {
                    header("Location: index.php?success=added");
                    exit;
                } else {
                    $error = "Erreur lors de l'enregistrement.";
                }
            }
        } catch (PDOException $e) {
            $error = "Erreur Base de données : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<div style="max-width: 800px; margin: 0 auto 2rem auto; display: flex; justify-content: space-between; align-items: center;">
    <h2><?= __('admin_add_new') ?> (<?= __('nav_news') ?>)</h2>
    <a href="index.php" class="btn"><?= __('form_cancel') ?></a>
</div>

<?php if ($error): ?><div style="color: white; background: #e74c3c; padding: 10px; margin-bottom: 1rem; border-radius: 4px; max-width: 800px; margin-left: auto; margin-right: auto;"><?= h($error) ?></div><?php endif; ?>

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
