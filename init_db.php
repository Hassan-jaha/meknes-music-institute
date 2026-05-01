<?php
// init_db.php
// Ce script réinitialise complètement la base de données.
// Utile pour appliquer des changements de structure (colonnes).

$host = 'localhost';
$user = 'root'; 
$pass = '';     

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Initialisation de la base de données...<br>";
    
    // On recrée la base pour être sûr de repartir de zéro
    $pdo->exec("DROP DATABASE IF EXISTS institut_musique");
    $pdo->exec("CREATE DATABASE institut_musique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE institut_musique");

    echo "Création de la table 'administrateurs'...<br>";
    $pdo->exec("
        CREATE TABLE administrateurs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "Création de la table 'actualites'...<br>";
    $pdo->exec("
        CREATE TABLE actualites (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(255) NOT NULL,
            contenu TEXT NOT NULL,
            image_path VARCHAR(255),
            date_publication DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "Création de la table 'annonces'...<br>";
    $pdo->exec("
        CREATE TABLE annonces (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(255) NOT NULL,
            contenu TEXT NOT NULL,
            is_pinned BOOLEAN DEFAULT FALSE,
            date_expiration DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "Création de la table 'galerie'...<br>";
    $pdo->exec("
        CREATE TABLE galerie (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre_image VARCHAR(255) NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "Création de la table 'messages'...<br>";
    $pdo->exec("
        CREATE TABLE messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Admin par défaut
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
    $pdo->exec("INSERT INTO administrateurs (username, password_hash) VALUES ('admin', '$hash')");

    // Données de test
    $pdo->exec("INSERT INTO actualites (titre, contenu, image_path, date_publication) VALUES 
        ('Nouveau Site Web', 'Bienvenue sur le nouveau portail digital de l\'institut.', 'public/images/placeholder.jpg', CURDATE()),
        ('Inscriptions Ouvertes', 'Venez vous inscrire pour la nouvelle saison musicale.', 'public/images/placeholder.jpg', CURDATE())");

    echo "<br><strong>Base de données réinitialisée avec succès !</strong><br>";
    echo "Vous pouvez maintenant retourner sur le <a href='index.php'>Site</a>.";

} catch (PDOException $e) {
    die("Erreur fatale : " . $e->getMessage());
}
