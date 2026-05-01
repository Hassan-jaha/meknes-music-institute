<?php
// init_admin.php
require_once __DIR__ . '/config/database.php';

echo "<h1>Initialisation de l'Administrateur</h1>";

try {
    $pdo = getDBConnection();
    
    $username = 'admin';
    $password = 'admin123';
    
    // Hashage du mot de passe
    $hash = password_hash($password, PASSWORD_DEFAULT);
    
    // Vérifier si l'utilisateur existe déjà
    $stmt = $pdo->prepare("SELECT id FROM administrateurs WHERE username = :username");
    $stmt->execute(['username' => $username]);
    
    if ($stmt->rowCount() > 0) {
        // Mettre à jour le mot de passe s'il existe déjà
        $update = $pdo->prepare("UPDATE administrateurs SET password_hash = :hash WHERE username = :username");
        $update->execute(['hash' => $hash, 'username' => $username]);
        echo "<p style='color:green;'>✅ Le mot de passe de l'utilisateur '$username' a été mis à jour avec le hash sécurisé !</p>";
    } else {
        // Créer l'utilisateur s'il n'existe pas
        $insert = $pdo->prepare("INSERT INTO administrateurs (username, password_hash) VALUES (:username, :hash)");
        $insert->execute(['username' => $username, 'hash' => $hash]);
        echo "<p style='color:green;'>✅ L'utilisateur '$username' a été créé avec succès avec le hash sécurisé !</p>";
    }
    
    echo "<p><a href='admin/login.php'>Aller à la page de connexion</a></p>";
    
    // IMPORTANT : Après succès, on supprime ce fichier pour la sécurité !
    // unlink(__FILE__);
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Erreur SQL : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
