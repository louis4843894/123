<?php
require_once 'config.php';

try {
    // 1. 先刪除舊表（如果存在）
    $pdo->exec("DROP TABLE IF EXISTS discussion_replies");
    $pdo->exec("DROP TABLE IF EXISTS discussion_posts");
    echo "已刪除舊表<br>";

    // 2. 創建 discussion_posts 表
    $pdo->exec("CREATE TABLE discussion_posts (
        id INT(11) NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        department_name VARCHAR(255) NOT NULL,
        author_id INT(11) NULL,
        author_type ENUM('student','admin') NOT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        status ENUM('active','closed') DEFAULT 'active',
        PRIMARY KEY (id)
    )");
    echo "已創建 discussion_posts 表<br>";

    // 3. 創建 discussion_replies 表
    $pdo->exec("CREATE TABLE discussion_replies (
        id INT(11) NOT NULL AUTO_INCREMENT,
        post_id INT(11) NOT NULL,
        content TEXT NOT NULL,
        author_id INT(11) NULL,
        author_type ENUM('student','admin') NOT NULL,
        parent_id INT(11) NULL,
        level INT(11) DEFAULT 0,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (post_id) REFERENCES discussion_posts(id) ON DELETE CASCADE
    )");
    echo "已創建 discussion_replies 表<br>";

    // 4. 添加外鍵約束（在表創建後添加，避免循環依賴）
    $pdo->exec("ALTER TABLE discussion_posts ADD CONSTRAINT fk_posts_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL");
    $pdo->exec("ALTER TABLE discussion_replies ADD CONSTRAINT fk_replies_author FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL");
    echo "已添加外鍵約束<br>";

    echo "修復完成！資料表已重置為初始狀態。";

} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 