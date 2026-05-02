<?php
// fix_annonces_table.php
require_once __DIR__ . '/config/database.php';

echo "<h1>Correction de la table Annonces</h1>";

try {
    $pdo = getDBConnection();
    
    // Vérifier si la colonne image_path existe
    $stmt = $pdo->query("SHOW COLUMNS FROM annonces LIKE 'image_path'");
    $columnExists = $stmt->fetch();
    
    if (!$columnExists) {
        // Ajouter la colonne si elle n'existe pas
        $pdo->exec("ALTER TABLE annonces ADD COLUMN image_path VARCHAR(255) DEFAULT NULL AFTER date_expiration");
        echo "<p style='color:green;'>✅ La colonne 'image_path' a été ajoutée avec succès à la table 'annonces' !</p>";
    } else {
        echo "<p style='color:blue;'>ℹ️ La colonne 'image_path' existe déjà.</p>";
    }
    
    // Vérifier également pour les actualites si jamais
    $stmt = $pdo->query("SHOW COLUMNS FROM actualites LIKE 'image_path'");
    $columnExists = $stmt->fetch();
    if (!$columnExists) {
        $pdo->exec("ALTER TABLE actualites ADD COLUMN image_path VARCHAR(255) DEFAULT NULL AFTER contenu");
        echo "<p style='color:green;'>✅ La colonne 'image_path' a été ajoutée à 'actualites' !</p>";
    }

    echo "<p><a href='admin/annonces/index.php'>Retour aux annonces</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erreur SQL : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
