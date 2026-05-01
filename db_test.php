<?php
// db_test.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Diagnostic de Connexion à la Base de Données</h1>";

echo "<h2>Variables d'Environnement (Railway) :</h2>";
$host = getenv('MYSQLHOST');
$db = getenv('MYSQLDATABASE');
$user = getenv('MYSQLUSER');
$port = getenv('MYSQLPORT');

echo "<ul>";
echo "<li><b>MYSQLHOST</b> : " . ($host ? htmlspecialchars($host) : "<i>(Non défini)</i>") . "</li>";
echo "<li><b>MYSQLDATABASE</b> : " . ($db ? htmlspecialchars($db) : "<i>(Non défini)</i>") . "</li>";
echo "<li><b>MYSQLUSER</b> : " . ($user ? htmlspecialchars($user) : "<i>(Non défini)</i>") . "</li>";
echo "<li><b>MYSQLPORT</b> : " . ($port ? htmlspecialchars($port) : "<i>(Non défini)</i>") . "</li>";
echo "</ul>";

echo "<h2>Test de Connexion PDO (Timeout fixé à 3s) :</h2>";

$host = $host ?: 'localhost';
$db = $db ?: 'railway';
$user = $user ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port = $port ?: '3306';

try {
    // On met un timeout très court (3 secondes) pour ne pas bloquer le serveur
    $options = [
        PDO::ATTR_TIMEOUT => 3,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
    $start = microtime(true);
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $password, $options);
    $time = round(microtime(true) - $start, 3);
    
    echo "<p style='color:green;'>✅ Connexion réussie en {$time} secondes !</p>";
} catch (PDOException $e) {
    $time = round(microtime(true) - $start, 3);
    echo "<p style='color:red;'>❌ Échec de la connexion après {$time} secondes : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
