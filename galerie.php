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
        <h2 class="section-title"><?= __('page_gallery_title') ?></h2>
        <p style="text-align: center; max-width: 700px; margin: -2rem auto 3rem; color: var(--color-text-muted);"><?= __('page_gallery_subtitle') ?></p>

        <div class="grid">
            <?php foreach ($images as $img): ?>
            <div class="card gallery-item">
                <img src="<?= get_image_url($img['image_path']) ?>" class="card-img" alt="<?= h($img['titre_image']) ?>">
                <div class="card-body" style="text-align: center; padding: 10px;">
                    <h4 style="margin: 0; font-size: 1rem; color: var(--color-blue-primary);"><?= h($img['titre_image']) ?></h4>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($images)): ?>
            <p style="text-align: center; color: var(--color-text-muted);"><?= __('no_images') ?></p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
