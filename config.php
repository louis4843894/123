<?php
$host = 'localhost';
$dbname = 'fju';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");

    // 新增 department_intro 欄位
    try {
        $sql = "ALTER TABLE DepartmentTransfer ADD COLUMN department_intro TEXT DEFAULT '暫無簡介' AFTER department_name";
        $pdo->exec($sql);
    } catch(PDOException $columnError) {
        // 如果錯誤是因為欄位已存在（錯誤代碼 42S21），則忽略這個錯誤
        if($columnError->getCode() !== '42S21') {
            throw $columnError;
        }
    }
} catch(PDOException $e) {
    // 只有在真正的連接錯誤時才顯示錯誤信息
    die("資料庫連接失敗: " . $e->getMessage());
}
?> 