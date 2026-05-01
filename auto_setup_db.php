<?php
// auto_setup_db.php
require_once __DIR__ . '/config/database.php';

echo "<h1>Configuration de la Base de Données</h1>";

try {
    $pdo = getDBConnection();
    echo "<p style='color:green;'>✅ Connexion réussie à la base de données.</p>";

    $sql_file = __DIR__ . '/database.sql';
    if (!file_exists($sql_file)) {
        die("<p style='color:red;'>❌ Fichier database.sql introuvable.</p>");
    }

    $sql = file_get_contents($sql_file);
    
    // Désactiver la vérification des clés étrangères pendant l'import
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
    
    // Exécuter le SQL
    $pdo->exec($sql);
    
    // Réactiver la vérification des clés étrangères
    $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');

    echo "<p style='color:green;'>✅ Base de données importée avec succès ! Les tables sont créées.</p>";
    echo "<a href='index.php'>Aller à l'accueil</a>";

} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erreur SQL : " . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Exception $e) {
    echo "<p style='color:red;'>❌ Erreur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
