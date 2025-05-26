<?php
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
        // 添加收藏
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, department_name) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $department_name]);
        echo json_encode(['status' => 'success', 'message' => '已加入收藏']);
    } 
    else if ($action === 'remove') {
        // 移除收藏
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND department_name = ?");
        $stmt->execute([$_SESSION['user_id'], $department_name]);
        echo json_encode(['status' => 'success', 'message' => '已取消收藏']);
    }
    else {
        echo json_encode(['status' => 'error', 'message' => '無效的操作']);
    }
} catch (PDOException $e) {
    // 如果是重複收藏，返回成功
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'success', 'message' => '已加入收藏']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '操作失敗，請稍後再試']);
    }
}
?> 