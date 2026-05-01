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
        // Détecter si on est sur Railway (les variables d'environnement seront définies)
        $is_railway = getenv('MYSQLHOST') !== false;
        $is_local = !$is_railway;

        if ($is_local) {
            $db_host = '127.0.0.1';
            $db_name = 'institut_musique';
            $db_user = 'root';
            $db_pass = '';
            $db_port = '3306';
        } else {
            $db_host = getenv('MYSQLHOST') ?: 'localhost';
            $db_name = getenv('MYSQLDATABASE') ?: 'railway';
            $db_user = getenv('MYSQLUSER') ?: 'root';
            $db_pass = getenv('MYSQLPASSWORD') ?: '';
            $db_port = getenv('MYSQLPORT') ?: '3306';
        }

        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3 // Timeout court pour éviter que le site bloque
            ];
            $pdo = new PDO("mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8", $db_user, $db_pass, $options);
        } catch (PDOException $e) {
            die("خطأ في الاتصال: " . $e->getMessage());
        }
    }
    
    return $pdo;
}