<?php
require 'config.php';
session_start();
$pageTitle = '密碼重設結果';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 驗證密碼
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "密碼不一致";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "密碼長度至少需要6個字符";
        header("Location: reset_password.php?token=" . urlencode($token));
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $record = $stmt->fetch();

    if ($record) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // 更新用戶密碼
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed_password, $record['email']]);

        // 刪除已使用的重設請求
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$record['email']]);

        $_SESSION['success'] = "密碼已成功重設，請使用新密碼登入";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "無效或過期的重設連結";
        header("Location: forgot_password.php");
        exit();
    }
}

// 如果不是 POST 請求，重定向到登入頁面
header("Location: login.php");
exit();

require 'footer.php';
