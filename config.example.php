<?php
// 資料庫配置
$host = 'localhost';
$dbname = 'university';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連接失敗: " . $e->getMessage());
}

// 郵件配置
define('MAILGUN_API_KEY', 'your-api-key-here');
define('MAILGUN_DOMAIN', 'your-domain-here');

// 應用配置
define('SITE_URL', 'http://localhost/SA');
define('SITE_NAME', '輔仁大學轉系系統'); 