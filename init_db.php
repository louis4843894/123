<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // 先建立不指定資料庫的連接
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 創建資料庫
    $pdo->exec("CREATE DATABASE IF NOT EXISTS university");
    $pdo->exec("USE university");
    
    // 創建 department 資料表
    $sql = "CREATE TABLE IF NOT EXISTS department (
        department_id INT AUTO_INCREMENT PRIMARY KEY,
        department_name VARCHAR(255) NOT NULL UNIQUE
    )";
    $pdo->exec($sql);
    
    // 創建 examtype 資料表
    $sql = "CREATE TABLE IF NOT EXISTS examtype (
        exam_type_id INT AUTO_INCREMENT PRIMARY KEY,
        exam_type_name VARCHAR(50) NOT NULL UNIQUE
    )";
    $pdo->exec($sql);
    
    // 創建 departmentexamtype 資料表
    $sql = "CREATE TABLE IF NOT EXISTS departmentexamtype (
        department_id INT NOT NULL,
        exam_type_id INT NOT NULL,
        PRIMARY KEY (department_id, exam_type_id),
        FOREIGN KEY (department_id) REFERENCES department(department_id) ON DELETE CASCADE,
        FOREIGN KEY (exam_type_id) REFERENCES examtype(exam_type_id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    
    // 創建 departmentremark 資料表
    $sql = "CREATE TABLE IF NOT EXISTS departmentremark (
        remark_id INT AUTO_INCREMENT PRIMARY KEY,
        department_id INT NOT NULL,
        remark_order TINYINT NOT NULL,
        remark_text TEXT NOT NULL,
        FOREIGN KEY (department_id) REFERENCES department(department_id) ON DELETE CASCADE
    )";
    $pdo->exec($sql);
    
    // 創建 users 資料表
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id VARCHAR(50) NOT NULL,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    // 創建 password_resets 資料表
    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(64) NOT NULL,
        expires_at DATETIME NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    
    echo "資料庫初始化成功！";
} catch(PDOException $e) {
    echo "資料庫初始化失敗: " . $e->getMessage();
}
?> 