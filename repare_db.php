<?php
// repare_db.php
require_once __DIR__ . '/config/database.php';

try {
    $pdo = getDBConnection();
    
    // Ajout de la colonne image_path à la table annonces
    $pdo->exec("ALTER TABLE annonces ADD COLUMN IF NOT EXISTS image_path VARCHAR(255) DEFAULT NULL AFTER contenu");
    
    echo "<h2 style='color: green;'>✅ Réparation réussie !</h2>";
    echo "<p>La colonne <b>image_path</b> a été ajoutée à la table <b>annonces</b>.</p>";
    echo "<a href='admin/annonces/create.php'>Retourner à l'ajout d'annonce</a>";
    
} catch (PDOException $e) {
    if (strpos($e->getMessage(), "Duplicate column name") !== false) {
        echo "<h2 style='color: blue;'>ℹ️ Déjà réparé</h2>";
        echo "<p>La colonne existe déjà.</p>";
        echo "<a href='admin/annonces/create.php'>Retourner à l'ajout d'annonce</a>";
    } else {
        echo "<h2 style='color: red;'>❌ Erreur lors de la réparation :</h2>";
        echo "<pre>" . $e->getMessage() . "</pre>";
    }
}
?>
