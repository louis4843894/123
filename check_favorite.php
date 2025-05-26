<?php
session_start();
require_once 'config.php';

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['is_favorite' => false]);
    exit;
}

// 獲取系所名稱
$department_name = isset($_GET['department_name']) ? $_GET['department_name'] : '';
if (empty($department_name)) {
    echo json_encode(['is_favorite' => false]);
    exit;
}

try {
    // 檢查是否已收藏
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = ? AND department_name = ?");
    $stmt->execute([$_SESSION['user_id'], $department_name]);
    $is_favorite = $stmt->fetchColumn() > 0;
    
    echo json_encode(['is_favorite' => $is_favorite]);
} catch (PDOException $e) {
    echo json_encode(['is_favorite' => false, 'error' => $e->getMessage()]);
}
?> 