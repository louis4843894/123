<?php
require_once 'config.php';

try {
    // 先刪除舊的 department_details 表格
    $pdo->exec("DROP TABLE IF EXISTS department_details");
    
    // 重新建立 department_details 表格，這次加上外鍵約束
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
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (`department_name`) REFERENCES `departments`(`name`) 
        ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    
    $pdo->exec($sql);
    echo "department_details 表格已重建，並加入外鍵約束<br>";
    
    // 從 departments 表格複製資料
    $stmt = $pdo->query("SELECT name FROM departments");
    $departments = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($departments as $dept_name) {
        $insert = $pdo->prepare("INSERT INTO department_details (department_name) VALUES (?)");
        $insert->execute([$dept_name]);
        echo "已為 {$dept_name} 建立關聯記錄<br>";
    }
    
    $count = $pdo->query("SELECT COUNT(*) FROM department_details")->fetchColumn();
    echo "<br>完成！department_details 表格現在有 {$count} 筆資料，且與 departments 表格建立了關聯<br>";
    echo "<a href='index.php'>回到首頁</a>";
    
} catch(PDOException $e) {
    echo "操作失敗: " . $e->getMessage();
}
?> 