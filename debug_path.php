<?php
echo "<h1>Diagnostic des Chemins</h1>";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Current File: " . __FILE__ . "<br>";
echo "Base Directory: " . realpath(__DIR__ . '/../') . "<br>";

$upload_dir = realpath(__DIR__ . '/public/uploads');
echo "Upload Directory: " . ($upload_dir ? $upload_dir : "NON TROUVÉ") . "<br>";

if ($upload_dir) {
    echo "Is Writable: " . (is_writable($upload_dir) ? "OUI" : "NON") . "<br>";
    echo "Files in uploads: <pre>";
    print_r(scandir($upload_dir));
    echo "</pre>";
}

echo "<h2>Test de création de fichier</h2>";
$test_file = __DIR__ . '/public/uploads/test_write.txt';
if (@file_put_contents($test_file, "Test Railway Persistence " . date('Y-m-d H:i:s'))) {
    echo "Écriture RÉUSSIE dans public/uploads/test_write.txt<br>";
} else {
    echo "Écriture ÉCHOUÉE (Erreur de permission probable)<br>";
}

echo "<h2>Données de la Base (Table annonces)</h2>";
try {
    require_once 'config/database.php';
    $pdo = getDBConnection();
    $stmt = $pdo->query("SELECT id, titre, image_path FROM annonces ORDER BY id DESC LIMIT 5");
    echo "<ul>";
    while ($row = $stmt->fetch()) {
        echo "<li>ID: {$row['id']} - Title: {$row['titre']} - Image Path: <b>" . ($row['image_path'] ? $row['image_path'] : "NULL") . "</b></li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "Erreur DB: " . $e->getMessage();
}
?>
