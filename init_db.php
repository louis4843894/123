<?php
require_once 'config.php';

$host = 'localhost';
$username = 'root';
$password = '';

try {
    // 創建資料庫連接
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 創建資料庫
    $pdo->exec("CREATE DATABASE IF NOT EXISTS fju CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE fju");
    
    // 創建 DepartmentTransfer 表
    $sql = "CREATE TABLE IF NOT EXISTS DepartmentTransfer (
        id INT AUTO_INCREMENT PRIMARY KEY,
        department_name VARCHAR(255) NOT NULL,
        year_2_enrollment VARCHAR(50),
        year_3_enrollment VARCHAR(50),
        year_4_enrollment VARCHAR(50),
        exam_subjects TEXT,
        data_review_ratio VARCHAR(50)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 創建 DepartmentRemarksSplit 表
    $sql = "CREATE TABLE IF NOT EXISTS DepartmentRemarksSplit (
        id INT AUTO_INCREMENT PRIMARY KEY,
        department_name VARCHAR(255) NOT NULL,
        remark TEXT
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 創建 departments 表
    $sql = "CREATE TABLE IF NOT EXISTS departments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        intro TEXT,
        careers JSON,
        url VARCHAR(255)
    ) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);
    
    // 建立密碼重設表
    $pdo->exec("CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(64) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (email),
        INDEX (token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    
    // 建立用戶表
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(20) NOT NULL UNIQUE,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX (email),
        INDEX (student_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    
    // 建立預設管理員帳號
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT, ['cost' => 10]);
    $stmt = $pdo->prepare("INSERT IGNORE INTO users (student_id, name, email, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin001', '系統管理員', 'admin@fju.edu.tw', $admin_password, 'admin']);
    
    echo "資料庫初始化成功！";
    
} catch(PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 