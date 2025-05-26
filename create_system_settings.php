<?php
require_once 'config.php';

try {
    // 創建 system_settings 表
    $pdo->exec("CREATE TABLE IF NOT EXISTS system_settings (
        id INT(11) NOT NULL AUTO_INCREMENT,
        maintenance_mode TINYINT(1) NOT NULL DEFAULT 0,
        maintenance_message TEXT NOT NULL DEFAULT '系統維護中，請稍後再試。',
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    )");
    
    // 插入預設的系統維護設定
    $stmt = $pdo->prepare("INSERT IGNORE INTO system_settings (id, maintenance_mode, maintenance_message) 
                          VALUES (1, 0, '系統維護中，請稍後再試。')");
    $stmt->execute();
    
    echo "系統設定表創建成功！";

} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 