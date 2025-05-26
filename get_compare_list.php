<?php
session_start();
require_once 'config.php';

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

try {
    // 從數據庫獲取用戶的比較列表
    $stmt = $pdo->prepare("SELECT department_name FROM compare_list WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $departments = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo json_encode($departments);
} catch(PDOException $e) {
    echo json_encode([]);
}
?> 