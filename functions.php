<?php
require_once 'config.php';

function recordPageView($page_type, $page_id, $page_title) {
    global $pdo;
    
    // 檢查用戶是否已登入
    if (!isset($_SESSION['user_id'])) {
        return;
    }
    
    try {
        // 檢查是否已存在相同的記錄
        $stmt = $pdo->prepare("
            SELECT id FROM browse_history 
            WHERE user_id = :user_id 
            AND page_type = :page_type 
            AND page_id = :page_id
        ");
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'page_type' => $page_type,
            'page_id' => $page_id
        ]);
        
        if ($stmt->rowCount() > 0) {
            // 更新現有記錄的時間戳
            $stmt = $pdo->prepare("
                UPDATE browse_history 
                SET viewed_at = CURRENT_TIMESTAMP 
                WHERE user_id = :user_id 
                AND page_type = :page_type 
                AND page_id = :page_id
            ");
        } else {
            // 插入新記錄
            $stmt = $pdo->prepare("
                INSERT INTO browse_history (user_id, page_type, page_id, page_title) 
                VALUES (:user_id, :page_type, :page_id, :page_title)
            ");
        }
        
        $stmt->execute([
            'user_id' => $_SESSION['user_id'],
            'page_type' => $page_type,
            'page_id' => $page_id,
            'page_title' => $page_title
        ]);
        
        // 保持每個用戶最多10條記錄
        $stmt = $pdo->prepare("
            DELETE FROM browse_history 
            WHERE user_id = :user_id 
            AND id NOT IN (
                SELECT id FROM (
                    SELECT id FROM browse_history 
                    WHERE user_id = :user_id 
                    ORDER BY viewed_at DESC 
                    LIMIT 10
                ) temp
            )
        ");
        $stmt->execute(['user_id' => $_SESSION['user_id']]);
        
    } catch(PDOException $e) {
        // 記錄錯誤但不中斷程序
        error_log("Error recording page view: " . $e->getMessage());
    }
}
?> 