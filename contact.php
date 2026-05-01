<?php
// contact.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($nom && $email && $message) {
        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (:nom, :email, :message)");
            if ($stmt->execute(['nom' => $nom, 'email' => $email, 'message' => $message])) {
                $success = __('contact_success');
            } else {
                $error = "Erreur lors de l'envoi.";
            }
        } catch (Exception $e) {
            $error = "Erreur technique : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<section class="section">
    <div class="container" style="max-width: 900px;">
        <h2 class="section-title"><?= __('contact_title') ?></h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 3rem;">
            <!-- Infos de contact -->
            <div>
                <h3><?= __('contact_info') ?></h3>
                <p style="margin-bottom: 2rem; color: var(--color-text-muted);"><?= __('contact_desc') ?></p>
                
                <div style="margin-bottom: 15px;">
                    <strong><?= __('contact_address_label') ?> :</strong><br>
                    <?= __('contact_address_value') ?>
                </div>
                <div style="margin-bottom: 15px;">
                    <strong><?= __('contact_email_label') ?> :</strong><br>
                    contact@institut-musique-meknes.ma
                </div>
                <div style="margin-bottom: 15px;">
                    <strong><?= __('contact_phone_label') ?> :</strong><br>
                    +212 5 35 XX XX XX
                </div>
            </div>

            <!-- Formulaire -->
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <h3 style="margin-top: 0; margin-bottom: 20px;"><?= __('contact_form_title') ?></h3>

                <?php if ($success): ?>
                    <div style="background: #27ae60; color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                        <?= h($success) ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div style="background: #e74c3c; color: white; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                        <?= h($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;"><?= __('contact_form_name') ?></label>
                        <input type="text" name="nom" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;"><?= __('contact_form_email') ?></label>
                        <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; margin-bottom: 5px;"><?= __('contact_form_message') ?></label>
                        <textarea name="message" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;"><?= __('contact_form_send') ?></button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
