<?php
// optimize_db.php
require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDBConnection();
    
    // Add index to actualites.date_publication
    $pdo->exec("ALTER TABLE actualites ADD INDEX idx_date_pub (date_publication)");
    echo "Index added on actualites.date_publication<br>";
    
    // Add indexes to annonces
    $pdo->exec("ALTER TABLE annonces ADD INDEX idx_is_pinned (is_pinned)");
    echo "Index added on annonces.is_pinned<br>";
    
    $pdo->exec("ALTER TABLE annonces ADD INDEX idx_date_exp (date_expiration)");
    echo "Index added on annonces.date_expiration<br>";
    
    // Add index to galerie
    $pdo->exec("ALTER TABLE galerie ADD INDEX idx_uploaded_at (uploaded_at)");
    echo "Index added on galerie.uploaded_at<br>";
    
    echo "<h2>Optimisation de la base de données terminée avec succès !</h2>";
    echo "<p>Vous pouvez supprimer ce fichier pour des raisons de sécurité.</p>";
    
} catch (PDOException $e) {
    // Ignore duplicate key errors if indexes already exist
    if ($e->getCode() == '42000' && strpos($e->getMessage(), 'Duplicate key name') !== false) {
        echo "Les index existent déjà.<br>";
    } else {
        echo "Erreur PDO : " . $e->getMessage() . "<br>";
    }
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "<br>";
}
?>
