<?php
// admin/annonces/delete.php
require_once __DIR__ . '/../../config/database.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
if ($id) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("DELETE FROM annonces WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: index.php");
exit;
