<?php
// admin/actualites/create.php
// ⚠️ IMPORTANT: Toute la logique PHP DOIT être exécutée AVANT d'inclure header.php
// car header.php envoie du HTML, et on ne peut plus faire de header("Location:...") après ça.

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

// Démarrer la session AVANT de l'utiliser
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification connexion admin AVANT l'affichage
if (!isset($_SESSION['admin_id'])) {
    header("Location: " . asset('admin/login.php'));
    exit;
}

// Initialisation de la langue
setLanguage($_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr'));

// === TRAITEMENT DU FORMULAIRE ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $contenu = trim($_POST['contenu'] ?? '');
    $date_publication = trim($_POST['date_publication'] ?? '');

    if ($titre && $contenu && $date_publication) {
        try {
            $pdo = getDBConnection();
            
            // Gestion de l'image via helper centralisé
            $image_path = handleImageUpload('image', 'public/images/placeholder.jpg');

            if (!isset($_SESSION['flash_error'])) {
                $stmt = $pdo->prepare("INSERT INTO actualites (titre, contenu, image_path, date_publication) VALUES (:titre, :contenu, :image_path, :date_publication)");
                $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'image_path' => $image_path, 'date_publication' => $date_publication]);
                
                $_SESSION['flash_success'] = "✅ Actualité ajoutée avec succès !";
                // Redirection AVANT tout output HTML
                header("Location: create.php");
                exit;
            }
        } catch (PDOException $e) {
            $_SESSION['flash_error'] = "Erreur Base de données : " . $e->getMessage();
        }
    } else {
        $_SESSION['flash_error'] = "Veuillez remplir tous les champs obligatoires.";
    }
}

// === AFFICHAGE (seulement après toute la logique PHP) ===
require_once __DIR__ . '/../includes/header.php';
?>

<div style="max-width: 800px; margin: 0 auto 2rem auto; display: flex; justify-content: space-between; align-items: center;">
    <h2><?= __('admin_add_new') ?> (<?= __('nav_news') ?>)</h2>
    <a href="<?= asset('admin/actualites/index.php') ?>" class="btn">← <?= __('form_cancel') ?></a>
</div>

<div class="admin-form-container">
    <form method="POST" action="" enctype="multipart/form-data">
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_title') ?> *</label>
            <input type="text" name="titre" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_date') ?> *</label>
            <input type="date" name="date_publication" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;" value="<?= date('Y-m-d') ?>">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('admin_image') ?> <small style="color:#64748b;">(Optionnel - Max 5Mo, JPG/PNG/WEBP)</small></label>
            <input type="file" name="image" accept="image/png, image/jpeg, image/webp" style="width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; background: #f8fafc;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;"><?= __('form_label_content') ?> *</label>
            <textarea name="contenu" rows="8" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 1rem; font-weight: 700;"><?= __('form_save') ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
