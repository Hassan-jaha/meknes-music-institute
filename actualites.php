<?php
// actualites.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Pagination logic
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_stmt = $pdo->query("SELECT COUNT(*) FROM actualites");
$total_items = $total_stmt->fetchColumn();
$total_pages = ceil($total_items / $limit);

$stmt = $pdo->prepare("SELECT * FROM actualites ORDER BY date DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$actualites = $stmt->fetchAll();
?>

<section class="section">
    <div class="container">
        <h2 class="section-title"><?= __('page_news_title') ?></h2>
        
        <div class="grid">
            <?php foreach ($actualites as $news): ?>
            <article class="card">
                <img src="<?= h($news['image']) ?>" class="card-img" alt="<?= h($news['titre']) ?>" loading="lazy">
                <div class="card-body">
                    <span class="card-date"><?= formatDate($news['date']) ?></span>
                    <h3 class="card-title"><?= h($news['titre']) ?></h3>
                    <p class="card-text"><?= h($news['contenu']) ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div style="margin-top: 3rem; text-align: center; display: flex; justify-content: center; gap: 10px;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn <?= $page === $i ? 'btn-primary' : '' ?>" style="padding: 8px 15px;"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($actualites)): ?>
            <p style="text-align: center; color: var(--color-text-muted);"><?= __('no_news') ?></p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
