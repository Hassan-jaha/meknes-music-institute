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

<?php if ($pinned_annonce): ?>
<div class="annonces-banner">
    <div class="container">
        📢 <strong><?= h($pinned_annonce['titre']) ?></strong> : <?= h(truncateText($pinned_annonce['contenu'], 100)) ?> 
        <a href="annonces.php"><?= __('view_all_announcements') ?> &rarr;</a>
    </div>
</div>
<?php endif; ?>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <h1><?= __('hero_title') ?></h1>
        <p><?= __('hero_subtitle') ?></p>
        <a href="galerie.php" class="btn btn-primary"><?= __('nav_gallery') ?></a>
    </div>
</section>

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
                <li style="margin-bottom: 10px;">✨ <?= __('excellence_list_1') ?></li>
                <li style="margin-bottom: 10px;">✨ <?= __('excellence_list_2') ?></li>
                <li style="margin-bottom: 10px;">✨ <?= __('excellence_list_3') ?></li>
            </ul>
        </div>
        <div style="flex: 1; min-width: 300px; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; padding-bottom: 28%; height: 0;">
            <div id="main-player" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
        </div>
    </div>
</section>

<!-- API YouTube -->
<script src="https://www.youtube.com/iframe_api"></script>
<script>
    var player;
    function onYouTubeIframeAPIReady() {
        player = new YT.Player('main-player', {
            videoId: '5TsEXMj-QyE',
            playerVars: {
                'autoplay': 1,
                'mute': 0,
                'controls': 1,
                'loop': 1,
                'playlist': '5TsEXMj-QyE',
                'rel': 0,
                'modestbranding': 1
            },
            events: {
                'onReady': function(event) {
                    event.target.setVolume(50); // Volume moyen (50%)
                    event.target.playVideo();
                }
            }
        });
    }
</script>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
