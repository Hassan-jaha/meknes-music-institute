<?php
// جلب البيانات من بيئة Railway
$host     = getenv('MYSQLHOST');
$db_name  = getenv('MYSQLDATABASE');
$user     = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');
$port     = getenv('MYSQLPORT') ?: '3306';

// إذا لم يجد المتغيرات (هذا يعني أننا في Local) نستخدم بيانات اللوكال
if (!$host) {
    $host     = '127.0.0.1'; // استخدم 127.0.0.1 لتفادي مشاكل الـ Socket
    $db_name  = 'institut_musique';
    $user     = 'root';
    $password = ''; 
}

try {
    // الاتصال مع تحديد المنفذ والمضيف بدقة
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // إظهار رسالة خطأ واضحة في حالة الفشل
    die("خطأ في الاتصال: " . $e->getMessage());
}