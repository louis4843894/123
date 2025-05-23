<?php
require_once 'config.php';

try {
    // 只建立 department_details 表格
    $sql = "
    CREATE TABLE IF NOT EXISTS `department_details` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `department_name` VARCHAR(255) NOT NULL,
        `course_features` TEXT,
        `future_development` TEXT,
        `faculty` TEXT,
        `transfer_requirements` TEXT,
        `phone` VARCHAR(50),
        `email` VARCHAR(255),
        `address` TEXT,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "department_details 表格建立成功！<br>";
    echo "<a href='index.php'>回到首頁</a>";
    
} catch(PDOException $e) {
    echo "建立表格失敗: " . $e->getMessage();
}
?> 