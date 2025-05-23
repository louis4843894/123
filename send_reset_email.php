<?php
session_start();
$pageTitle = '寄送重設密碼連結';
require 'config.php';
require 'header.php';
require 'vendor/autoload.php';

use Mailgun\Mailgun;

date_default_timezone_set('Asia/Taipei');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    echo '<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">';
    echo '<div class="card" style="max-width: 600px; width: 100%;"><div class="card-body text-center">';

    if ($user) {
        $token = bin2hex(random_bytes(16));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expires]);

        $apiKey = 'e9daaaed858a6e9ce336e5c0e349acfe-e71583bb-5ba11f50';
        $domain = 'sandbox41e78a6cf3654933bf7b803506273890.mailgun.org';
        $mg = Mailgun::create($apiKey);

        $resetLink = "http://localhost/123/reset_password.php?token=" . urlencode($token);

        $mg->messages()->send($domain, [
            'from'    => '轉系系統 <postmaster@' . $domain . '>',
            'to'      => $email,
            'subject' => '🔐 重設您的密碼',
            'text'    => "您好，請點擊以下連結重設密碼：\n$resetLink\n此連結一小時內有效。",
            'html'    => "
                <p>您好，</p>
                <p>您剛剛提出了 <strong>忘記密碼</strong> 的請求。</p>
                <p>請點擊下方按鈕重設密碼：</p>
                <p style='text-align: center; margin: 30px 0;'>
                    <a href='$resetLink' style='
                        background-color: #007bff;
                        color: white;
                        padding: 12px 24px;
                        text-decoration: none;
                        border-radius: 6px;
                        display: inline-block;
                        font-weight: bold;'>🔐 點我重設密碼</a>
                </p>
                <p style='color: gray;'>若您沒有提出請求，請忽略此信。</p>
                <p style='font-size: 12px; color: #888;'>此連結將於 1 小時後失效。</p>
            "
        ]);

        echo "<h4 class='text-success mb-3'>✅ 重設密碼連結已寄出</h4>";
        echo "<p>請至 <strong>$email</strong> 信箱查收，並依照信中指示重設密碼。</p>";
        echo "<p class='text-muted'>如未收到請檢查垃圾郵件。</p>";
    } else {
        echo "<h5 class='text-danger'>❌ 查無此 Email，請確認輸入是否正確。</h5>";
    }

    echo '<div class="mt-4 text-center">';
    echo '<a href="forgot_password.php" class="btn btn-outline-secondary btn-sm me-2">重新輸入 Email</a>';
    echo '<a href="login.php" class="btn btn-outline-secondary btn-sm me-2">返回登入</a>';
    echo '<a href="index.php" class="btn btn-outline-secondary btn-sm">返回首頁</a>';
    echo '</div>';

    echo '</div></div></div>';
}

require 'footer.php';
