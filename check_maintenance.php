<?php
require_once 'config.php';

function checkMaintenanceMode() {
    global $pdo;
    
    try {
        $stmt = $pdo->query("SELECT maintenance_mode, maintenance_message FROM system_settings WHERE id = 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($settings && $settings['maintenance_mode']) {
            return [
                'enabled' => true,
                'message' => $settings['maintenance_message'] ?? '系統維護中，請稍後再試。'
            ];
        }
    } catch (PDOException $e) {
        // 如果發生錯誤，預設不啟用維護模式
        error_log('檢查維護模式時發生錯誤：' . $e->getMessage());
    }
    
    return ['enabled' => false];
}
?> 