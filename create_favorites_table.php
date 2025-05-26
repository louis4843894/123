<?php
require_once 'config.php';

try {
    // 先刪除已存在的表（如果有的話）
    $pdo->exec("DROP TABLE IF EXISTS favorites");
    
    // 建立 favorites 資料表
    $sql = "CREATE TABLE favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        department_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_favorite (user_id, department_name),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    $pdo->exec($sql);
    echo "favorites 資料表建立成功！";

    // 顯示建立後的表結構
    echo "<h3>favorites 表結構：</h3>";
    $columns = $pdo->query("SHOW CREATE TABLE favorites");
    $row = $columns->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . htmlspecialchars($row['Create Table']) . "</pre>";

} catch(PDOException $e) {
    echo "建立資料表失敗: " . $e->getMessage();
    echo "<br>錯誤代碼: " . $e->getCode();
    echo "<br>錯誤信息: " . $e->getMessage();
}
?> 