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
        <span style="background: white; color: var(--color-red-primary); padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; margin-right: 10px; text-transform: uppercase; display: inline-flex; align-items: center; gap: 6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            <?= __('admin_pinned') ?>
        </span>
        <?= h($pinned_annonce['titre']) ?> 
        <a href="annonces.php" style="color: inherit; text-decoration: underline; margin-left: 15px;"><?= __('read_more') ?></a>
    </div>
</section>
<?php endif; ?>

<!-- News Ticker Banner -->
<?php if (!empty($latest_news)): ?>
<section style="background: linear-gradient(135deg, var(--color-blue-deep) 0%, #1a3a5c 100%); color: white; padding: 0; overflow: hidden; border-bottom: 3px solid var(--color-gold-primary);">
    <div class="container" style="display: flex; align-items: stretch; min-height: 52px;">
        <!-- Label -->
        <div style="background: var(--color-gold-primary); color: #1e293b; padding: 0 24px; display: flex; align-items: center; gap: 8px; font-weight: 800; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; white-space: nowrap; flex-shrink: 0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/></svg>
            <?= __('nav_news') ?>
        </div>
        <!-- Ticker -->
        <div style="flex: 1; overflow: hidden; display: flex; align-items: center; padding: 0 20px; position: relative;">
            <div style="display: flex; gap: 60px; animation: ticker 20s linear infinite; white-space: nowrap; align-items: center;">
                <?php foreach ($latest_news as $news): ?>
                    <a href="<?= asset('actualites.php') ?>" style="color: rgba(255,255,255,0.9); text-decoration: none; font-size: 0.9rem; transition: color 0.2s; flex-shrink: 0;">
                        <span style="color: var(--color-gold-primary); margin-right: 8px;">•</span>
                        <?= h($news['titre']) ?>
                        <span style="color: rgba(255,255,255,0.4); font-size: 0.75rem; margin-left: 8px;"><?= formatDate($news['date_publication']) ?></span>
                    </a>
                <?php endforeach; ?>
                <!-- Repeat for seamless loop -->
                <?php foreach ($latest_news as $news): ?>
                    <a href="<?= asset('actualites.php') ?>" style="color: rgba(255,255,255,0.9); text-decoration: none; font-size: 0.9rem; flex-shrink: 0;">
                        <span style="color: var(--color-gold-primary); margin-right: 8px;">•</span>
                        <?= h($news['titre']) ?>
                        <span style="color: rgba(255,255,255,0.4); font-size: 0.75rem; margin-left: 8px;"><?= formatDate($news['date_publication']) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- CTA -->
        <a href="<?= asset('actualites.php') ?>" style="background: rgba(255,255,255,0.1); color: white; padding: 0 20px; display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 0.8rem; text-decoration: none; border-left: 1px solid rgba(255,255,255,0.1); transition: background 0.2s; white-space: nowrap; flex-shrink: 0;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
            <?= __('page_news_title') ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    </div>
</section>
<style>
@keyframes ticker {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
</style>
<?php endif; ?>

<!-- Latest News Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title"><?= __('section_latest_news') ?></h2>
        <div class="grid">
            <?php foreach ($latest_news as $news): ?>
            <article class="card">
                <img src="<?= get_image_url($news['image_path']) ?>" class="card-img" alt="<?= h($news['titre']) ?>">
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
