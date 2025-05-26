<?php
session_start();
require_once 'config.php';

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => '請先登入']);
    exit;
}

// 檢查請求方法
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => '無效的請求方法']);
    exit;
}

// 獲取系所名稱
$department_name = isset($_POST['department_name']) ? $_POST['department_name'] : '';
if (empty($department_name)) {
    echo json_encode(['status' => 'error', 'message' => '系所名稱不能為空']);
    exit;
}

// 獲取操作類型（添加或移除收藏）
$action = isset($_POST['action']) ? $_POST['action'] : '';

try {
    if ($action === 'add') {
        // 添加收藏
        $stmt = $pdo->prepare("INSERT INTO favorites (user_id, department_name) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $department_name]);
        echo json_encode(['status' => 'success', 'message' => '收藏成功']);
    } elseif ($action === 'remove') {
        // 移除收藏
        $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = ? AND department_name = ?");
        $stmt->execute([$_SESSION['user_id'], $department_name]);
        echo json_encode(['status' => 'success', 'message' => '已取消收藏']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '無效的操作']);
    }
} catch (PDOException $e) {
    // 如果是重複收藏，返回特殊訊息
    if ($e->getCode() == 23000) {
        echo json_encode(['status' => 'error', 'message' => '已經收藏過此系所']);
    } else {
        echo json_encode(['status' => 'error', 'message' => '操作失敗：' . $e->getMessage()]);
    }
}
?> 