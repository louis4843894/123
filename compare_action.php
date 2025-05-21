<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        http_response_code(403);
        echo "未登入";
        exit();
    } else {
        header("Location: login.php");
        exit();
    }
}

$department_name = $_POST['department_name'] ?? '';
$selected_departments = isset($_COOKIE['compare_departments']) ? json_decode($_COOKIE['compare_departments'], true) : [];
if (!is_array($selected_departments)) $selected_departments = [];

if (isset($_POST['add_to_compare'])) {
    if (!in_array($department_name, $selected_departments)) {
        $selected_departments[] = $department_name;
    }
    setcookie('compare_departments', json_encode($selected_departments), time() + (86400 * 7), "/");

} elseif (isset($_POST['remove_from_compare'])) {
    $selected_departments = array_diff($selected_departments, [$department_name]);
    setcookie('compare_departments', json_encode(array_values($selected_departments)), time() + (86400 * 7), "/");
}

// 若為 AJAX 請求就不跳轉
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    echo "OK";
} else {
    // ✅ 重導回 compare.php（不是首頁 index.php）
    header("Location: compare.php");
    exit();
}
