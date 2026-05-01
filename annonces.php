<?php
// annonces.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$pdo = getDBConnection();

// Pagination logic
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_stmt = $pdo->query("SELECT COUNT(*) FROM annonces WHERE date_expiration >= CURDATE()");
$total_items = $total_stmt->fetchColumn();
$total_pages = ceil($total_items / $limit);

$stmt = $pdo->prepare("SELECT * FROM annonces WHERE date_expiration >= CURDATE() ORDER BY is_pinned DESC, created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$annonces = $stmt->fetchAll();
?>

<section class="section">
    <div class="container">
        <h2 class="section-title">Annonces & Informations</h2>
        
        <div style="max-width: 800px; margin: 0 auto;">
            <?php foreach ($annonces as $annonce): ?>
            <div class="card" style="margin-bottom: 20px; border-left: 5px solid <?= $annonce['is_pinned'] ? 'var(--color-red-primary)' : 'var(--color-blue-accent)' ?>;">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <h3 class="card-title" style="margin-bottom: 5px;">
                            <?= $annonce['is_pinned'] ? '📌 ' : '' ?><?= h($annonce['titre']) ?>
                        </h3>
                        <span style="font-size: 0.8rem; color: var(--color-text-muted);">Expire le : <?= formatDate($annonce['date_expiration']) ?></span>
                    </div>
                    <p class="card-text" style="white-space: pre-wrap;"><?= h($annonce['contenu']) ?></p>
                    <?php if ($annonce['image_path']): ?>
                        <img src="<?= asset(h($annonce['image_path'])) ?>" style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 4px; margin-top: 15px;" loading="lazy">
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($total_pages > 1): ?>
        <div style="margin-top: 3rem; text-align: center; display: flex; justify-content: center; gap: 10px;">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn <?= $page === $i ? 'btn-primary' : '' ?>" style="padding: 8px 15px;"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($annonces)): ?>
            <p style="text-align: center; color: var(--color-text-muted);">Aucune annonce active.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
