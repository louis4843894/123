<?php
session_start();
require_once 'config.php';

// 檢查是否已提交表單
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($student_id) || empty($password)) {
        $_SESSION['error'] = '請填寫學號和密碼';
        header('Location: login.php');
        exit;
    }

    try {
        // 查詢用戶
        $stmt = $pdo->prepare("SELECT * FROM users WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // 登入成功
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['student_id'] = $user['student_id'];

            // 根據角色重定向到不同頁面
            if ($user['role'] === 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            // 登入失敗
            $_SESSION['error'] = '學號或密碼錯誤';
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = '系統錯誤，請稍後再試';
        header('Location: login.php');
        exit;
    }
} else {
    // 如果不是 POST 請求，重定向到登入頁面
    header('Location: login.php');
    exit;
} 