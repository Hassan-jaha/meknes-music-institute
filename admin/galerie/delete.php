<?php
// admin/galerie/delete.php
require_once __DIR__ . '/../../config/database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
if ($id) {
    $pdo = getDBConnection();
    
    // Récupérer le chemin du fichier pour le supprimer physiquement
    $stmt = $pdo->prepare("SELECT image_path FROM galerie WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $img = $stmt->fetch();
    
    if ($img) {
        $fullPath = '../../' . $img['image_path'];
        if (file_exists($fullPath) && is_file($fullPath)) {
            unlink($fullPath);
        }
        
        $stmt = $pdo->prepare("DELETE FROM galerie WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

header("Location: index.php");
exit;
