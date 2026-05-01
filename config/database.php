<?php
// محاولة جلب البيانات من Railway، وإذا لم توجد نستخدم بيانات الـ localhost
$host     = getenv('MYSQLHOST') ?: 'localhost';
$db_name  = getenv('MYSQLDATABASE') ?: 'institut_musique'; // تأكد من اسم قاعدتك في لوكال
$user     = getenv('MYSQLUSER') ?: 'root';
$password = getenv('MYSQLPASSWORD') ?: ''; // غالباً في اللوكال تكون فارغة
$port     = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("خطأ في الاتصال: " . $e->getMessage());
}