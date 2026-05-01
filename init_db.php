<?php
// init_db.php
// Ce script initialise la base de données et les tables requises.
// À lancer une seule fois lors de l'installation.

$host = 'localhost';
$user = 'root'; // Modifier selon l'environnement
$pass = '';     // Modifier selon l'environnement

try {
    // Connexion sans spécifier de base de données pour pouvoir la créer
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Création de la base de données 'institut_musique'...<br>";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS institut_musique CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE institut_musique");

    echo "Création de la table 'administrateurs'...<br>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS administrateurs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "Création de la table 'actualites'...<br>";
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS actualites (
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
        CREATE TABLE IF NOT EXISTS annonces (
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
        CREATE TABLE IF NOT EXISTS galerie (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre_image VARCHAR(255) NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Insertion d'un administrateur par défaut
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM administrateurs WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
        $pdo->exec("INSERT INTO administrateurs (username, password_hash) VALUES ('admin', '$hash')");
        echo "Administrateur par défaut créé (admin / admin123).<br>";
    }

    // Insertion de fausses données (Lorem Ipsum) pour test
    // Actualités
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM actualites");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO actualites (titre, contenu, image_path, date_publication) VALUES 
            ('Concert d\'Hiver', 'L\'Institut a le plaisir de vous annoncer son grand concert d\'hiver...', 'public/images/placeholder.jpg', CURDATE()),
            ('Nouvelle Session d\'Inscriptions', 'Les inscriptions pour la session de printemps sont ouvertes...', 'public/images/placeholder.jpg', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
            ('Masterclass de Violon', 'Une masterclass exceptionnelle sera animée par...', 'public/images/placeholder.jpg', DATE_SUB(CURDATE(), INTERVAL 5 DAY))");
        echo "Actualités de test insérées.<br>";
    }

    // Annonces
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM annonces");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO annonces (titre, contenu, is_pinned, date_expiration) VALUES 
            ('Fermeture exceptionnelle', 'L\'institut sera fermé le 1er Mai.', TRUE, DATE_ADD(CURDATE(), INTERVAL 10 DAY)),
            ('Rappel Cours', 'N\'oubliez pas vos partitions pour les cours d\'ensemble.', FALSE, DATE_ADD(CURDATE(), INTERVAL 5 DAY))");
        echo "Annonces de test insérées.<br>";
    }

    echo "<strong>Initialisation terminée avec succès !</strong>";

} catch (PDOException $e) {
    die("Erreur lors de l'initialisation : " . $e->getMessage());
}
