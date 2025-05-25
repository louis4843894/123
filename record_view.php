<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    die('Not logged in');
}

// 獲取系所名稱
$department_name = $_POST['department_name'] ?? '';
if (empty($department_name)) {
    die('No department name provided');
}

try {
    // 插入新記錄
    $stmt = $pdo->prepare("
        INSERT INTO browse_history (user_id, page_type, page_id, page_title) 
        VALUES (:user_id, 'department', :department_name, :department_name)
    ");
    
    $stmt->execute([
        ':user_id' => $_SESSION['user_id'],
        ':department_name' => $department_name
    ]);

    // 返回成功
    echo 'success';
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
} 