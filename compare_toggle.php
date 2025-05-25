<?php
header('Content-Type: application/json');

// 檢查是否為 POST 請求
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

// 獲取 POST 數據
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['department_name']) || !isset($data['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    exit;
}

session_start();

// 初始化比較列表
if (!isset($_SESSION['compare_list'])) {
    $_SESSION['compare_list'] = [];
}

$department_name = $data['department_name'];
$action = $data['action'];

if ($action === 'add') {
    // 檢查是否已達到最大比較數量
    if (count($_SESSION['compare_list']) >= 3 && !in_array($department_name, $_SESSION['compare_list'])) {
        echo json_encode(['status' => 'error', 'message' => '最多只能比較三個系所']);
        exit;
    }
    
    // 添加到比較列表
    if (!in_array($department_name, $_SESSION['compare_list'])) {
        $_SESSION['compare_list'][] = $department_name;
    }
} else if ($action === 'remove') {
    // 從比較列表中移除
    $key = array_search($department_name, $_SESSION['compare_list']);
    if ($key !== false) {
        unset($_SESSION['compare_list'][$key]);
        $_SESSION['compare_list'] = array_values($_SESSION['compare_list']); // 重新索引數組
    }
}

echo json_encode([
    'status' => 'success',
    'compare_list' => $_SESSION['compare_list']
]); 