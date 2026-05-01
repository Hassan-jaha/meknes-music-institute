<?php
// index.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Récupérer les 3 dernières actualités
$stmt = $pdo->query("SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 3");
$latest_news = $stmt->fetchAll();
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container hero-content">
        <h1>L'Héritage Musical au Cœur du Digital</h1>
        <p>Découvrez l'Institut de Musique, un lieu où la tradition marocaine rencontre l'innovation moderne pour former les talents de demain.</p>
        <a href="galerie.php" class="btn btn-primary">Explorer l'Institut</a>
    </div>
</section>

<!-- Latest News Section -->
<section class="section">
    <div class="container">
        <h2 class="section-title">Dernières Actualités</h2>
        <div class="grid">
            <?php foreach ($latest_news as $news): ?>
            <article class="card">
                <img src="<?= h($news['image_path']) ?>" class="card-img" alt="<?= h($news['titre']) ?>">
                <div class="card-body">
                    <span class="card-date"><?= formatDateFR($news['date_publication']) ?></span>
                    <h3 class="card-title"><?= h($news['titre']) ?></h3>
                    <p class="card-text"><?= h(truncateText($news['contenu'], 120)) ?></p>
                    <a href="actualites.php" style="display: inline-block; margin-top: 1rem; font-weight: 600;">Lire la suite &rarr;</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; margin-top: 3rem;">
            <a href="actualites.php" class="btn btn-primary">Toutes les actualités</a>
        </div>
    </div>
</section>

<!-- Promotion / Zellige Accent -->
<section class="section" style="background-color: var(--color-bg-light); border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
    <div class="container" style="display: flex; flex-wrap: wrap; align-items: center; gap: 40px;">
        <div style="flex: 1; min-width: 300px;">
            <h2 style="font-size: 2.5rem;">Un Enseignement d'Excellence</h2>
            <p>Notre institut propose des cursus complets en musique classique et andalouse, encadrés par des maîtres renommés. Nous allions les méthodes ancestrales aux outils numériques pour une expérience d'apprentissage unique.</p>
            <ul style="margin-top: 1.5rem; list-style: none;">
                <li style="margin-bottom: 10px;">✨ Instruments Classiques & Traditionnels</li>
                <li style="margin-bottom: 10px;">✨ Théorie Musicale & Composition</li>
                <li style="margin-bottom: 10px;">✨ Ateliers de Production Digitale</li>
            </ul>
        </div>
        <div style="flex: 1; min-width: 300px; background: var(--color-blue-primary); height: 300px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background-image: url('public/images/patterns/zellige-bg.svg'); background-size: 80px;">
            <div style="background: white; padding: 20px; border-radius: 50%; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                 <svg width="60" height="60" viewBox="0 0 24 24" fill="var(--color-red-primary)"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
