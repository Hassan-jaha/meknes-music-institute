<?php
// admin/actualites/delete.php
require_once __DIR__ . '/../../config/database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
if ($id) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("DELETE FROM actualites WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['flash_success'] = "Actualité supprimée avec succès !";
    } catch (PDOException $e) {
        $_SESSION['flash_error'] = "Erreur lors de la suppression : " . $e->getMessage();
    }
}

header("Location: index.php");
exit;
