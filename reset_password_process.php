<?php
require 'config.php';
session_start();
$pageTitle = '密碼重設結果';
require 'header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $record = $stmt->fetch();

    echo '<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">';
    echo '<div class="card" style="max-width: 700px; width: 100%;">';
    echo '<div class="card-body text-center">';

    if ($record) {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$newPassword, $record['email']]);

        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$record['email']]);

        echo "<h4 class='text-success mb-3'>✅ 密碼已成功更新</h4>";
        echo "<p>請使用新密碼重新登入。</p>";
        echo "<div class='mt-4 text-center'>
                <a href='login.php' class='btn btn-outline-secondary btn-sm me-2'>前往登入</a>
                <a href='index.php' class='btn btn-outline-secondary btn-sm'>返回首頁</a>
              </div>";
    } else {
        echo "<h4 class='text-danger mb-3'>❌ 無效或過期的重設連結</h4>";
        echo "<p>請重新申請忘記密碼。</p>";
        echo "<div class='mt-4 text-center'>
                <a href='forgot_password.php' class='btn btn-outline-secondary btn-sm me-2'>重新申請</a>
                <a href='index.php' class='btn btn-outline-secondary btn-sm'>返回首頁</a>
              </div>";
    }

    echo '</div></div></div>';
}

require 'footer.php';
