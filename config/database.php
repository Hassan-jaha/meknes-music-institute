<?php
// جلب البيانات من Railway مع التأكد من القيم الافتراضية
$host     = getenv('MYSQLHOST') ?: '127.0.0.1'; // استخدمنا 127.0.0.1 بدل localhost لتجنب خطأ Socket
$db_name  = getenv('MYSQLDATABASE') ?: 'institut_musique'; 
$user     = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: ''; 
$port     = getenv('MYSQLPORT') ?: '3306';

try {
    // إضافة ;host=$host لضمان الاتصال عبر الشبكة
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("خطأ في الاتصال: " . $e->getMessage());
}