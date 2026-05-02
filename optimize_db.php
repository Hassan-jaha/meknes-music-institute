<?php
// optimize_db.php
require_once __DIR__ . '/config/database.php';

echo "<h1>Optimisation de la Base de Données</h1>";

try {
    $pdo = getDBConnection();
    
    echo "<ul>";
    
    // Index pour les annonces (recherches fréquentes sur is_pinned et date_expiration)
    $pdo->exec("ALTER TABLE annonces ADD INDEX IF NOT EXISTS idx_pinned_date (is_pinned, date_expiration)");
    echo "<li>✅ Index ajouté sur 'annonces' (is_pinned, date_expiration)</li>";
    
    // Index pour les actualités (recherche par date)
    $pdo->exec("ALTER TABLE actualites ADD INDEX IF NOT EXISTS idx_date_pub (date_publication)");
    echo "<li>✅ Index ajouté sur 'actualites' (date_publication)</li>";
    
    // Index pour les messages (recherche par date)
    $pdo->exec("ALTER TABLE messages ADD INDEX IF NOT EXISTS idx_created (created_at)");
    echo "<li>✅ Index ajouté sur 'messages' (created_at)</li>";
    
    echo "</ul>";
    echo "<p style='color:green;'><b>Base de données optimisée pour des requêtes instantanées !</b></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erreur SQL : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
