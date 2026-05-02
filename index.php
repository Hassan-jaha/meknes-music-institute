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
                <li style="margin-bottom: 10px; display:flex; align-items:center;">
                    <svg width="18" height="18" fill="var(--color-gold-primary)" viewBox="0 0 24 24" style="margin-right:8px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <?= __('excellence_list_1') ?>
                </li>
                <li style="margin-bottom: 10px; display:flex; align-items:center;">
                    <svg width="18" height="18" fill="var(--color-gold-primary)" viewBox="0 0 24 24" style="margin-right:8px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <?= __('excellence_list_2') ?>
                </li>
                <li style="margin-bottom: 10px; display:flex; align-items:center;">
                    <svg width="18" height="18" fill="var(--color-gold-primary)" viewBox="0 0 24 24" style="margin-right:8px;"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                    <?= __('excellence_list_3') ?>
                </li>
            </ul>
        </div>
        <div style="flex: 1; min-width: 300px; border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); position: relative; padding-bottom: 56.25%; height: 0;">
            <div id="main-player" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></div>
            
            <!-- Unmute Button Overlay -->
            <div id="video-unmute-btn" onclick="toggleVideoSound()" style="display:none; position:absolute; bottom:20px; right:20px; z-index:10; background:rgba(0,0,0,0.6); color:white; padding:10px; border-radius:50%; cursor:pointer; width:40px; height:40px; align-items:center; justify-content:center; border: 2px solid var(--color-gold-primary); transition: background 0.3s;" onmouseover="this.style.background='rgba(0,0,0,0.8)'" onmouseout="this.style.background='rgba(0,0,0,0.6)'">
                <svg id="unmute-icon" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.53.41-1.13.73-1.78.96v2.06c1.18-.27 2.26-.78 3.19-1.46l2.07 2.07 1.27-1.27L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                </svg>
            </div>
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
                'mute': 1, // Muted is required for autoplay to work in Chrome/Safari
                'controls': 0, // Hidden controls for cleaner look
                'loop': 1,
                'playlist': '5TsEXMj-QyE',
                'rel': 0,
                'modestbranding': 1,
                'showinfo': 0
            },
            events: {
                'onReady': onPlayerReady
            }
        });
    }

    function onPlayerReady(event) {
        event.target.setPlaybackQuality('hd1080');
        event.target.setVolume(25); // Volume set to 25%
        event.target.playVideo();
        
        // Show unmute button
        document.getElementById('video-unmute-btn').style.display = 'flex';
    }

    function toggleVideoSound() {
        if (player.isMuted()) {
            player.unMute();
            document.getElementById('unmute-icon').innerHTML = '<path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>';
        } else {
            player.mute();
            document.getElementById('unmute-icon').innerHTML = '<path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.53.41-1.13.73-1.78.96v2.06c1.18-.27 2.26-.78 3.19-1.46l2.07 2.07 1.27-1.27L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>';
        }
    }
</script>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
