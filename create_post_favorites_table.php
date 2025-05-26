<?php
require_once 'config.php';

try {
    // 創建 post_favorites 表
    $sql = "CREATE TABLE IF NOT EXISTS post_favorites (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        post_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (post_id) REFERENCES discussion_posts(id) ON DELETE CASCADE,
        UNIQUE KEY unique_favorite (user_id, post_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

    $pdo->exec($sql);
    echo "post_favorites 表創建成功！";

    // 顯示表結構
    $result = $pdo->query("DESCRIBE post_favorites");
    echo "<br><br>表結構：<br>";
    echo "<pre>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";

} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 