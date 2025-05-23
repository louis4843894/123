<?php
require 'config.php';
session_start();
$pageTitle = '重設密碼';

// ✅ 設定時區為台灣，避免 token 過期錯誤
date_default_timezone_set('Asia/Taipei');

// ✅ 取得網址中的 token
$token = $_GET['token'] ?? '';

// ✅ 紀錄 debug log（可刪除）
file_put_contents('reset_debug.log', date('Y-m-d H:i:s') . " token = $token\n", FILE_APPEND);

// ✅ 查詢資料庫是否有有效 token
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
$stmt->execute([$token]);
$record = $stmt->fetch();

include 'header.php';
?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">

<?php if ($record): ?>
      <h4 class="card-title mb-4">設定新密碼</h4>
      <form method="POST" action="reset_password_process.php">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <div class="mb-3 text-start">
          <label class="form-label">新密碼</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">提交</button>
      </form>
<?php else: ?>
      <h4 class="text-danger mb-3">
        <i class="bi bi-exclamation-triangle-fill"></i> 此連結無效或已過期
      </h4>
      <p>請重新申請忘記密碼流程。</p>
      <div class="mt-3 text-center">
        <a href="forgot_password.php" class="btn btn-outline-primary btn-sm me-2">重新申請</a>
        <a href="login.php" class="btn btn-outline-secondary btn-sm me-2">返回登入</a>
        <a href="index.php" class="btn btn-outline-dark btn-sm">返回首頁</a>
      </div>
<?php endif; ?>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
