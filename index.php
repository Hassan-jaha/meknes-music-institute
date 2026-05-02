<?php
// index.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Récupérer les annonces épinglées
$stmt_pinned = $pdo->query("SELECT * FROM annonces WHERE is_pinned = 1 AND date_expiration >= CURDATE() ORDER BY created_at DESC LIMIT 1");
$pinned_annonce = $stmt_pinned->fetch();

// Récupérer les 3 dernières actualités
$stmt = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 3");
$latest_news = $stmt->fetchAll();
?>


<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <h1><?= __('hero_title') ?></h1>
        <p><?= __('hero_subtitle') ?></p>
        <a href="galerie.php" class="btn btn-primary"><?= __('nav_gallery') ?></a>
    </div>
</section>

<!-- Section Vidéo Ambiance (Invisible ou intégrée) -->
<div style="display:none;"><div id="ambiance-player"></div></div>

<script src="https://www.youtube.com/iframe_api"></script>
<script>
    var player;
    function onYouTubeIframeAPIReady() {
        // Player principal (visuel)
        player = new YT.Player('main-player', {
            videoId: '5TsEXMj-QyE',
            playerVars: {
                'autoplay': 1,
                'loop': 1,
                'playlist': '5TsEXMj-QyE',
                'controls': 0,
                'showinfo': 0,
                'modestbranding': 1,
                'rel': 0,
                'mute': 1
            },
            events: {
                'onReady': onPlayerReady
            }
        });
    }

    function onPlayerReady(event) {
        event.target.playVideo();
        // Optionnel: Unmute automatique à 15% (certains navigateurs peuvent bloquer)
        setTimeout(function() {
            event.target.unMute();
            event.target.setVolume(15);
        }, 1000);
    }
</script>

<!-- Pinned Announcement (If exists) -->
<?php if ($pinned_annonce): ?>
<section style="background: var(--color-gold-primary); color: var(--admin-primary); padding: 15px 0; text-align: center; font-weight: bold; position: relative; z-index: 5;">
    <div class="container">
        <span style="background: white; color: var(--color-red-primary); padding: 2px 10px; border-radius: 20px; font-size: 0.8rem; margin-right: 10px; text-transform: uppercase;">📌 <?= __('admin_pinned') ?></span>
        <?= h($pinned_annonce['titre']) ?> 
        <a href="annonces.php" style="color: inherit; text-decoration: underline; margin-left: 15px;"><?= __('read_more') ?></a>
    </div>
</section>
<?php endif; ?>

<!-- Latest News Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title"><?= __('section_latest_news') ?></h2>
        <div class="grid">
            <?php foreach ($latest_news as $news): ?>
            <article class="card">
                <img src="<?= asset(h($news['image_path'])) ?>" class="card-img" alt="<?= h($news['titre']) ?>">
                <div class="card-body">
                    <span class="card-date"><?= formatDate($news['date_publication']) ?></span>
                    <h3 class="card-title"><?= h($news['titre']) ?></h3>
                    <p class="card-text"><?= nl2br(h(truncateText($news['contenu'], 300))) ?></p>
                    <a href="actualites.php" style="display: inline-block; margin-top: 1rem; font-weight: 600;"><?= __('read_more') ?> &rarr;</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="actualites.php" class="btn btn-primary"><?= __('page_news_title') ?></a>
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
                <li style="margin-bottom: 12px; display:flex; align-items:center; font-weight: 500;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:12px;"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    <?= __('excellence_list_1') ?>
                </li>
                <li style="margin-bottom: 12px; display:flex; align-items:center; font-weight: 500;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:12px;"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    <?= __('excellence_list_2') ?>
                </li>
                <li style="margin-bottom: 12px; display:flex; align-items:center; font-weight: 500;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right:12px;"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                    <?= __('excellence_list_3') ?>
                </li>
            </ul>
        </div>
        <div style="flex: 1; min-width: 300px; border-radius: 12px; overflow: hidden; box-shadow: 0 15px 35px rgba(0,0,0,0.2); position: relative; padding-bottom: 56.25%; height: 0; background: #000;">
             <div id="main-player" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
