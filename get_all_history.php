<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT DISTINCT page_id as department_name, viewed_at
        FROM browse_history
        WHERE user_id = :user_id
        AND page_type = 'department'
        ORDER BY viewed_at DESC
    ");
    
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 格式化日期時間
    foreach ($results as &$result) {
        if (isset($result['viewed_at'])) {
            $timestamp = strtotime($result['viewed_at']);
            $result['viewed_at'] = date('Y-m-d H:i:s', $timestamp);
        }
    }
    
    echo json_encode($results);
} catch (Exception $e) {
    error_log("Error in get_all_history.php: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
} 