<?php
// admin/messages/index.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../includes/header.php';

$pdo = getDBConnection();

// Suppression d'un message
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// Récupération des messages sécurisée
$messages = [];
try {
    $messages = $pdo->query("SELECT * FROM messages ORDER BY date_envoi DESC")->fetchAll();
} catch (Exception $e) {
    // Table non encore créée
}
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <h2 class="section-title" style="margin: 0;"><?= __('admin_manage_messages') ?></h2>
</div>

<?php if (empty($messages)): ?>
    <div class="card" style="padding: 2rem; text-align: center;">
        <p><?= __('no_images') ?> (Aucun message)</p>
    </div>
<?php else: ?>
    <div style="display: flex; flex-direction: column; gap: 15px;">
        <?php foreach ($messages as $msg): ?>
            <div class="card" style="padding: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <div>
                        <strong style="color: var(--color-blue-deep); font-size: 1.1rem;"><?= h($msg['nom']) ?></strong>
                        <br>
                        <small style="color: var(--color-text-muted);"><?= h($msg['email']) ?></small>
                    </div>
                    <div style="text-align: right;">
                        <span style="font-size: 0.8rem; color: var(--color-text-muted);"><?= $msg['date_envoi'] ?></span>
                        <br>
                        <a href="?delete=<?= $msg['id'] ?>" class="btn" style="background: #ff4d4d; color: white; padding: 5px 10px; font-size: 0.8rem; margin-top: 5px;" onclick="return confirm('<?= __('admin_confirm_delete') ?>')">
                            <?= __('admin_delete') ?>
                        </a>
                    </div>
                </div>
                <div style="background: var(--color-bg-light); padding: 15px; border-radius: 4px; border-left: 4px solid var(--color-gold-primary);">
                    <p style="white-space: pre-wrap; font-style: italic;"><?= h($msg['message']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
