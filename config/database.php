<?php
// تفعيل عرض الأخطاء مؤقتاً لتشخيص المشكلة (White Page)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// جلب الإعدادات من نظام Railway تلقائياً
$host     = getenv('MYSQLHOST') ?: 'localhost';
$db_name  = getenv('MYSQLDATABASE') ?: 'institut_musique';
$user     = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: '';
$port     = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("خطأ في الاتصال: " . $e->getMessage());
}