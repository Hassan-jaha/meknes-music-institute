<?php
require_once 'config/database.php';
require_once 'includes/functions.php';

$pdo = getDBConnection();
$tables = ['annonces', 'actualites', 'galerie'];

foreach ($tables as $table) {
    echo "<h2>Table: $table</h2>";
    $stmt = $pdo->query("SELECT * FROM $table");
    $rows = $stmt->fetchAll();
    
    if (empty($rows)) {
        echo "<p>Table is empty.</p>";
        continue;
    }

    foreach ($rows as $row) {
        $path = $row['image_path'] ?? ($row['chemin_image'] ?? 'NULL');
        echo "<p>ID: {$row['id']} - Path: $path</p>";
        if ($path && $path !== 'NULL') {
            $full_path = __DIR__ . '/' . $path;
            echo "File exists: " . (file_exists($full_path) ? 'YES' : 'NO') . " (Path: $full_path)<br>";
            echo "Asset URL: " . asset($path) . "<br>";
        }
    }
}
?>
