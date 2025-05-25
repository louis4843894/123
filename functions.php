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

function getRecentViewedDepartments($limit = 5) {
    global $pdo;
    
    if (!isset($_SESSION['user_id'])) {
        return [];
    }

    try {
        $stmt = $pdo->prepare("
            SELECT DISTINCT page_id as department_name, viewed_at 
            FROM browse_history 
            WHERE user_id = :user_id 
            AND page_type = 'department' 
            ORDER BY viewed_at DESC 
            LIMIT :limit
        ");
        
        $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getRecentViewedDepartments: " . $e->getMessage());
        return [];
    }
}

function recordDepartmentView($deptName) {
    global $pdo;
    
    if (!isset($_SESSION['user_id']) || empty($deptName)) {
        return false;
    }

    try {
        // 插入新記錄
        $stmt = $pdo->prepare("
            INSERT INTO browse_history (user_id, page_type, page_id, page_title) 
            VALUES (:user_id, 'department', :page_id, :page_title)
        ");
        
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':page_id' => $deptName,
            ':page_title' => $deptName
        ]);

        // 只保留最新的5條記錄
        $stmt = $pdo->prepare("
            DELETE FROM browse_history 
            WHERE user_id = :user_id 
            AND id NOT IN (
                SELECT id FROM (
                    SELECT id 
                    FROM browse_history 
                    WHERE user_id = :user_id 
                    AND page_type = 'department'
                    ORDER BY viewed_at DESC 
                    LIMIT 5
                ) tmp
            )
        ");
        
        $stmt->execute([':user_id' => $_SESSION['user_id']]);
        
        return true;
    } catch (PDOException $e) {
        error_log("Error in recordDepartmentView: " . $e->getMessage());
        return false;
    }
}
?> 