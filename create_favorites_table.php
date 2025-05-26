<?php
require_once 'config.php';

try {
    // 創建 favorites 表
    $pdo->exec("CREATE TABLE favorites (
        id INT(11) NOT NULL AUTO_INCREMENT,
        user_id INT(11) NOT NULL,
        department_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY unique_favorite (user_id, department_name),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    echo "已成功創建 favorites 表！";

} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 