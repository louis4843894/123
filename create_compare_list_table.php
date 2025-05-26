<?php
require_once 'config.php';

try {
    // 創建比較列表表
    $sql = "CREATE TABLE IF NOT EXISTS compare_list (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        department_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        UNIQUE KEY unique_user_department (user_id, department_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($sql);
    echo "比較列表表創建成功！";
} catch(PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 