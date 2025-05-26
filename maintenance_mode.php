<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 獲取維護模式狀態和訊息
        $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
        $maintenance_message = $_POST['maintenance_message'] ?? '系統正在進行維護，請稍後再試。';

        // 更新系統設定
        $stmt = $pdo->prepare("UPDATE system_settings SET 
            maintenance_mode = :maintenance_mode,
            maintenance_message = :maintenance_message,
            updated_at = NOW()
            WHERE id = 1");
        
        $stmt->execute([
            ':maintenance_mode' => $maintenance_mode,
            ':maintenance_message' => $maintenance_message
        ]);

        // 如果沒有記錄，則插入新記錄
        if ($stmt->rowCount() === 0) {
            $stmt = $pdo->prepare("INSERT INTO system_settings 
                (maintenance_mode, maintenance_message, created_at, updated_at) 
                VALUES (:maintenance_mode, :maintenance_message, NOW(), NOW())");
            
            $stmt->execute([
                ':maintenance_mode' => $maintenance_mode,
                ':maintenance_message' => $maintenance_message
            ]);
        }

        $_SESSION['success_message'] = '維護模式設定已更新';
    } catch (PDOException $e) {
        $_SESSION['error_message'] = '更新維護模式設定時發生錯誤：' . $e->getMessage();
    }

    // 重定向回系統設定頁面
    header('Location: system_settings.php');
    exit;
}
?> 