<?php
// galerie.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();
$images = $pdo->query("SELECT * FROM galerie ORDER BY upload_date DESC")->fetchAll();
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Galerie de l'Institut</h2>
        <p style="text-align: center; max-width: 700px; margin: -2rem auto 3rem; color: var(--color-text-muted);">Explorez en images la vie de notre établissement, nos concerts et nos ateliers.</p>

        <div class="grid">
            <?php foreach ($images as $img): ?>
            <a href="<?= h($img['image_path']) ?>" class="card gallery-item" style="cursor: pointer;">
                <img src="<?= h($img['image_path']) ?>" class="card-img" alt="<?= h($img['titre_image']) ?>">
                <div class="card-body" style="text-align: center; padding: 10px;">
                    <h4 style="margin: 0; font-size: 1rem; color: var(--color-blue-primary);"><?= h($img['titre_image']) ?></h4>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($images)): ?>
            <p style="text-align: center; color: var(--color-text-muted);">La galerie est actuellement vide.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
