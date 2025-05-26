<?php
header('Content-Type: application/json');

session_start();
require_once 'config.php';

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '請先登入']);
    exit;
}

// 獲取 POST 數據
$data = json_decode(file_get_contents('php://input'), true);
$department_name = $data['department_name'] ?? '';
$action = $data['action'] ?? '';

if (empty($department_name) || empty($action)) {
    echo json_encode(['status' => 'error', 'message' => '參數錯誤']);
    exit;
}

try {
    if ($action === 'add') {
        // 檢查是否已達到最大比較數量
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM compare_list WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $count = $stmt->fetchColumn();
        
        if ($count >= 3) {
            echo json_encode(['status' => 'error', 'message' => '最多只能同時比較3個系所，請先移除其中一個系所後再進行添加']);
            exit;
        }
        
        // 檢查是否已經存在
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM compare_list WHERE user_id = ? AND department_name = ?");
        $stmt->execute([$_SESSION['user_id'], $department_name]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => '該系所已在比較列表中']);
            exit;
        }
        
        // 添加到比較列表
        $stmt = $pdo->prepare("INSERT INTO compare_list (user_id, department_name) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $department_name]);
    } else {
        // 從比較列表中移除
        $stmt = $pdo->prepare("DELETE FROM compare_list WHERE user_id = ? AND department_name = ?");
        $stmt->execute([$_SESSION['user_id'], $department_name]);
    }
    
    // 獲取更新後的數量
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM compare_list WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $count = $stmt->fetchColumn();
    
    echo json_encode(['status' => 'success', 'count' => $count]);
} catch(PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => '操作失敗，請稍後再試']);
}
?> 