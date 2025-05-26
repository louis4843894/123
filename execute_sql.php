<?php
require_once 'config.php';

try {
    // 檢查資料表是否已存在
    $stmt = $pdo->query("SHOW TABLES LIKE 'system_settings'");
    if ($stmt->rowCount() > 0) {
        echo "system_settings 資料表已存在，正在更新...\n";
        
        // 更新現有記錄
        $stmt = $pdo->prepare("UPDATE system_settings SET 
            maintenance_mode = 0,
            maintenance_message = '系統正在進行維護，請稍後再試。',
            updated_at = NOW()
            WHERE id = 1");
        $stmt->execute();
        
        if ($stmt->rowCount() === 0) {
            // 如果沒有記錄，則插入新記錄
            $stmt = $pdo->prepare("INSERT INTO system_settings 
                (maintenance_mode, maintenance_message, created_at, updated_at) 
                VALUES (0, '系統正在進行維護，請稍後再試。', NOW(), NOW())");
            $stmt->execute();
        }
    } else {
        echo "正在建立 system_settings 資料表...\n";
        
        // 建立資料表
        $pdo->exec("CREATE TABLE system_settings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            maintenance_mode TINYINT(1) DEFAULT 0,
            maintenance_message TEXT,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        )");
        
        // 插入預設設定
        $stmt = $pdo->prepare("INSERT INTO system_settings 
            (maintenance_mode, maintenance_message, created_at, updated_at) 
            VALUES (0, '系統正在進行維護，請稍後再試。', NOW(), NOW())");
        $stmt->execute();
    }
    
    echo "操作完成！\n";
    echo "system_settings 資料表已準備就緒。\n";
    
} catch (PDOException $e) {
    echo "發生錯誤：" . $e->getMessage() . "\n";
}
?> 