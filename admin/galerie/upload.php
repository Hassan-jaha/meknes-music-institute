<?php
// admin/galerie/upload.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Sécurité supplémentaire : vérifier le type MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $fileTmpPath);
            finfo_close($finfo);
            
            if (strpos($mime, 'image/') === 0) {
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../../public/uploads/';
                $dest_path = $uploadFileDir . $newFileName;
                $db_path = 'public/uploads/' . $newFileName;
                
                // Redimensionnement automatique (max 1200px de large)
                if (resizeImage($fileTmpPath, $dest_path, 1200, 1200)) {
                    $pdo = getDBConnection();
                    $stmt = $pdo->prepare("INSERT INTO galerie (titre_image, image_path) VALUES (:titre, :image_path)");
                    if ($stmt->execute(['titre' => $titre ?: $fileName, 'image_path' => $db_path])) {
                        $success = "Image téléchargée et optimisée avec succès.";
                    } else {
                        $error = "Erreur lors de l'enregistrement en base de données.";
                    }
                } else {
                    $error = "Erreur lors du traitement de l'image.";
                }
            } else {
                $error = "Le fichier n'est pas une image valide.";
            }
        } else {
            $error = "Extensions autorisées : " . implode(',', $allowedfileExtensions);
        }
    } else {
        $error = "Veuillez sélectionner une image.";
    }
}
?>

<h2>Ajouter une Photo</h2>

<?php if ($error): ?><div style="color: red; margin-bottom: 1rem;"><?= h($error) ?></div><?php endif; ?>
<?php if ($success): ?><div style="color: green; margin-bottom: 1rem;"><?= h($success) ?></div><?php endif; ?>

<form method="POST" action="" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Titre de l'image (optionnel)</label>
        <input type="text" name="titre" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label style="display: block; margin-bottom: 5px;">Sélectionner l'image *</label>
        <input type="file" name="image" required accept="image/*" style="width: 100%; padding: 8px;">
        <small style="color: var(--color-text-muted);">Formats acceptés : JPG, PNG, WEBP. Redimensionnement automatique.</small>
    </div>
    <button type="submit" class="btn btn-primary">Télécharger</button>
    <a href="index.php" style="margin-left: 15px; color: var(--color-blue-primary);">Retour</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
