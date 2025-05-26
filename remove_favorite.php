<?php
require_once 'config.php';
session_start();

// 檢查是否已登入
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '請先登入']);
    exit;
}

// 檢查是否有提供系所ID
if (!isset($_POST['department_id'])) {
    echo json_encode(['success' => false, 'message' => '缺少系所ID']);
    exit;
}

$department_id = $_POST['department_id'];
$user_id = $_SESSION['user_id'];

try {
    // 從收藏清單中移除
    $stmt = $pdo->prepare("DELETE FROM compare_list WHERE user_id = ? AND department_id = ?");
    $stmt->execute([$user_id, $department_id]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => '找不到收藏記錄']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '取消收藏時發生錯誤']);
} 