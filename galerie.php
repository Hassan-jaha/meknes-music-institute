<?php
// galerie.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();
$images = $pdo->query("SELECT * FROM galerie ORDER BY date DESC")->fetchAll();
?>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?= __('page_gallery_title') ?></h2>
        <p style="text-align: center; max-width: 700px; margin: -2rem auto 3rem; color: var(--color-text-muted);"><?= __('page_gallery_subtitle') ?></p>

        <div class="grid">
            <?php foreach ($images as $img): ?>
            <a href="<?= asset(h($img['image'])) ?>" class="card gallery-item" style="cursor: pointer;">
                <img src="<?= asset(h($img['image'])) ?>" class="card-img" alt="<?= h($img['description']) ?>" loading="lazy">
                <div class="card-body" style="text-align: center; padding: 10px;">
                    <h4 style="margin: 0; font-size: 1rem; color: var(--color-blue-primary);"><?= h($img['description']) ?></h4>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($images)): ?>
            <p style="text-align: center; color: var(--color-text-muted);"><?= __('no_images') ?></p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
