<?php
function checkMaintenanceMode($pdo) {
    try {
        // 檢查系統是否處於維護模式
        $stmt = $pdo->query("SELECT maintenance_mode, maintenance_message FROM system_settings WHERE id = 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        // 如果沒有設定，預設為非維護模式
        if (!$settings) {
            return false;
        }

        // 維護模式開啟時，只有「已登入」且「不是管理員」的用戶才會被導向維護頁
        if (
            $settings['maintenance_mode'] &&
            isset($_SESSION['user_id']) && // 已登入
            (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') // 不是管理員
        ) {
            // 如果當前頁面不是維護訊息頁面，則重定向
            if (basename($_SERVER['PHP_SELF']) !== 'maintenance.php') {
                $_SESSION['maintenance_message'] = $settings['maintenance_message'];
                header('Location: maintenance.php');
                exit;
            }
            return true;
        }

        return false;
    } catch (PDOException $e) {
        // 如果發生錯誤，預設為非維護模式
        error_log('檢查維護模式時發生錯誤：' . $e->getMessage());
        return false;
    }
}
?> 