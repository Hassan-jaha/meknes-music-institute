<?php
// جلب الإعدادات من نظام Railway تلقائياً
$host     = getenv('MYSQLHOST');     // سيأخذ قيمة RAILWAY_PRIVATE_DOMAIN تلقائياً
$db_name  = getenv('MYSQLDATABASE'); // سيأخذ قيمة railway تلقائياً
$user     = getenv('MYSQLUSER');     // سيأخذ قيمة root تلقائياً
$password = getenv('MYSQLPASSWORD'); // سيأخذ كلمة السر nvHNkGR... تلقائياً
$port     = getenv('MYSQLPORT');     // سيأخذ 3306 تلقائياً

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("خطأ في الاتصال: " . $e->getMessage());
}