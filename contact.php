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
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                $pdo = getDBConnection();
                $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (:nom, :email, :message)");
                if ($stmt->execute(['nom' => $nom, 'email' => $email, 'message' => $message])) {
                    $success = __('contact_success');
                } else {
                    $error = "Erreur technique lors de l'envoi.";
                }
            } catch (Exception $e) {
                $error = "La table 'messages' n'existe pas encore. Veuillez lancer init_db.php.";
            }
        } else {
            $error = "Email invalide.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?= __('contact_title') ?></h2>
        
        <div style="display: flex; flex-wrap: wrap; gap: 40px; margin-top: 2rem;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="color: var(--color-gold-primary);"><?= __('contact_info') ?></h3>
                <p style="margin-bottom: 20px;"><?= __('contact_desc') ?></p>
                
                <ul style="list-style: none;">
                    <li style="margin-bottom: 15px;">
                        <strong><?= __('contact_address_label') ?> :</strong><br>
                        <?= __('contact_address_value') ?>
                    </li>
                    <li style="margin-bottom: 15px;">
                        <strong><?= __('contact_email_label') ?> :</strong><br>
                        contact@institut-musique.ma
                    </li>
                    <li style="margin-bottom: 15px;">
                        <strong><?= __('contact_phone_label') ?> :</strong><br>
                        +212 5 35 XX XX XX
                    </li>
                </ul>
            </div>
            
            <div style="flex: 1; min-width: 300px; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px;"><?= __('contact_form_title') ?></h3>
                
                <?php if ($success): ?><div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px;"><?= h($success) ?></div><?php endif; ?>
                <?php if ($error): ?><div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px;"><?= h($error) ?></div><?php endif; ?>

                <form action="" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;"><?= __('contact_form_name') ?></label>
                        <input type="text" name="nom" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;"><?= __('contact_form_email') ?></label>
                        <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                    </div>
                    <div style="margin-bottom: 15px;">
                        <label style="display: block; margin-bottom: 5px;"><?= __('contact_form_message') ?></label>
                        <textarea name="message" rows="5" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= __('contact_form_send') ?></button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
