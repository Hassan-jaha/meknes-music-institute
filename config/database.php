<?php
// جلب البيانات من بيئة Railway
// config/database.php

// Activer l'affichage des erreurs en local, désactiver en production
if (getenv('MYSQLHOST') === false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    // Désactivé en production
    ini_set('display_errors', 0);
    error_reporting(0);
}

function getDBConnection() {
    // Pattern Singleton basique
    static $pdo = null;
    
    if ($pdo === null) {
        // Priorité aux variables d'environnement (Railway)
        $db_host = getenv('MYSQLHOST') ?: '127.0.0.1';
        $db_name = getenv('MYSQLDATABASE') ?: 'institut_musique';
        $db_user = getenv('MYSQLUSER') ?: 'root';
        $db_pass = getenv('MYSQLPASSWORD') ?: '';
        $db_port = getenv('MYSQLPORT') ?: '3306';

        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3 // Timeout court pour éviter que le site bloque
            ];
            $dsn = "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8";
            $pdo = new PDO($dsn, $db_user, $db_pass, $options);
            
            // --- AUTOMATIC SCHEMA FIX (One-time check) ---
            
            // Correction table annonces (image_path)
            $check = $pdo->query("SHOW COLUMNS FROM annonces LIKE 'image_path'");
            if (!$check->fetch()) {
                $pdo->exec("ALTER TABLE annonces ADD COLUMN image_path VARCHAR(255) DEFAULT NULL AFTER date_expiration");
            }

            // Correction table actualites (image_path)
            $check = $pdo->query("SHOW COLUMNS FROM actualites LIKE 'image_path'");
            if (!$check->fetch()) {
                $pdo->exec("ALTER TABLE actualites ADD COLUMN image_path VARCHAR(255) DEFAULT NULL AFTER contenu");
            }

            // Création table messages si elle n'existe pas
            $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nom VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                message TEXT NOT NULL,
                date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
            )");
            
        } catch (PDOException $e) {
            die("خطأ في الاتصال: " . $e->getMessage());
        }
    }
    
    return $pdo;
}