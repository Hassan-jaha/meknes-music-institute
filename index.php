<?php
// index.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Récupérer les 3 dernières actualités
$stmt = $pdo->query("SELECT * FROM actualites ORDER BY date DESC LIMIT 3");
$latest_news = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1><?= __('hero_title') ?></h1>
        <p><?= __('hero_subtitle') ?></p>
        <div style="display: flex; gap: 20px; justify-content: center;">
            <a href="actualites.php" class="btn btn-primary"><?= __('btn_news') ?></a>
            <a href="contact.php" class="btn" style="border: 2px solid var(--color-gold-primary); color: var(--color-white);"><?= __('btn_contact') ?></a>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title"><?= __('section_latest_news') ?></h2>
        <div class="grid">
            <?php foreach ($latest_news as $news): ?>
            <article class="card">
                <img src="<?= h($news['image']) ?>" class="card-img" alt="<?= h($news['titre']) ?>" loading="lazy">
                <div class="card-body">
                    <span class="card-date"><?= formatDate($news['date']) ?></span>
                    <h3 class="card-title"><?= h($news['titre']) ?></h3>
                    <p class="card-text"><?= h(truncateText($news['contenu'], 120)) ?></p>
                    <a href="actualites.php" style="display: inline-block; margin-top: 1rem; font-weight: 600;"><?= __('read_more') ?> &rarr;</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="actualites.php" class="btn btn-primary"><?= __('btn_news') ?></a>
        </div>
    </div>
</section>

<!-- Promotion / Zellige Accent -->
<section class="section" style="background-color: var(--color-bg-light); border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
    <div class="container" style="display: flex; flex-wrap: wrap; align-items: center; gap: 40px;">
        <div style="flex: 1; min-width: 300px;">
            <h2 style="font-size: 2.5rem;"><?= __('section_excellence') ?></h2>
            <p><?= __('excellence_text') ?></p>
            <ul style="margin-top: 1.5rem; list-style: none;">
                <li style="margin-bottom: 10px;"><?= __('excellence_list_1') ?></li>
                <li style="margin-bottom: 10px;"><?= __('excellence_list_2') ?></li>
                <li style="margin-bottom: 10px;"><?= __('excellence_list_3') ?></li>
            </ul>
        </div>
        <div style="flex: 1; min-width: 300px; position: relative; padding-bottom: 28%; height: 0; overflow: hidden; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.2); border: 4px solid var(--color-gold-primary);">
            <iframe 
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                src="https://www.youtube.com/embed/QipIufCH3rw?autoplay=1&mute=1&loop=1&playlist=QipIufCH3rw&controls=1&rel=0&modestbranding=1" 
                title="<?= __('section_excellence') ?>" 
                allow="autoplay; encrypted-media" 
                loading="lazy"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
