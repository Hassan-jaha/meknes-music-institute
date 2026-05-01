<?php
// جلب البيانات من بيئة Railway
// config/database.php

// Détecter si on est sur Railway (les variables d'environnement seront définies)
$is_railway = getenv('MYSQLHOST') !== false;
$is_local = !$is_railway;

if ($is_local) {
    // Activer l'affichage des erreurs uniquement en local
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Paramètres locaux
    $host = '127.0.0.1';
    $db_name = 'institut_musique';
    $user = 'root';
    $password = '';
    $port = '3306';
} else {
    // Désactiver les erreurs en production (Railway) - TEMPORAIREMENT ACTIVE POUR LE DEBUG
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    // Paramètres Railway
    $host     = getenv('MYSQLHOST') ?: 'localhost';
    $db_name  = getenv('MYSQLDATABASE') ?: 'railway';
    $user     = getenv('MYSQLUSER') ?: 'root';
    $password = getenv('MYSQLPASSWORD') ?: '';
    $port     = getenv('MYSQLPORT') ?: '3306';
}

function getDBConnection() {
    global $host, $port, $db_name, $user, $password;
    
    // Pattern Singleton basique
    static $pdo = null;
    
    if ($pdo === null) {
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 3 // Timeout court pour éviter que le site bloque
            ];
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", $user, $password, $options);
        } catch (PDOException $e) {
            die("خطأ في الاتصال: " . $e->getMessage());
        }
    }
    
    return $pdo;
}