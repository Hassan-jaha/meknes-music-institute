<?php
// init_db.php (Version alignée sur le cahier des charges)
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Initialisation de la base de données...<br>";
    $pdo->exec("DROP DATABASE IF EXISTS institut_musique");
    $pdo->exec("CREATE DATABASE institut_musique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE institut_musique");

    // Table Administrateurs
    $pdo->exec("
        CREATE TABLE administrateurs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Table Actualités (Alignée Section 4.1)
    $pdo->exec("
        CREATE TABLE actualites (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(255) NOT NULL,
            contenu TEXT NOT NULL,
            date DATE NOT NULL,
            image VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Table Annonces (Alignée Section 4.2)
    $pdo->exec("
        CREATE TABLE annonces (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            date DATE NOT NULL,
            image VARCHAR(255),
            is_pinned BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Table Galerie (Alignée Section 4.3)
    $pdo->exec("
        CREATE TABLE galerie (
            id INT AUTO_INCREMENT PRIMARY KEY,
            image VARCHAR(255) NOT NULL,
            description TEXT,
            date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Table Messages (Prises de contact)
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
    $pdo->exec("INSERT INTO actualites (titre, contenu, date, image) VALUES ('Premier Concert', 'Description du concert...', CURDATE(), 'public/images/placeholder.jpg')");
    $pdo->exec("INSERT INTO annonces (titre, description, date, is_pinned) VALUES ('Inscriptions', 'Les inscriptions sont ouvertes.', CURDATE(), TRUE)");
    
    echo "<strong>Réinitialisation réussie !</strong><br>";
    echo "Identifiants Admin : admin / admin123";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
