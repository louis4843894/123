<?php
session_start();
$pageTitle = '註冊';
require_once 'config.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 驗證輸入
        $student_id = trim($_POST['student_id'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // 基本驗證
        if (empty($student_id) || empty($name) || empty($email) || empty($password)) {
            throw new Exception('所有欄位都必須填寫');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('請輸入有效的 Email 地址');
        }

        if (strlen($password) < 6) {
            throw new Exception('密碼長度必須至少為 6 個字元');
        }

        // 檢查 Email 是否已存在
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            throw new Exception('此 Email 已經註冊過了');
        }

        // 檢查學號是否已存在
        $stmt = $pdo->prepare("SELECT id FROM users WHERE student_id = ?");
        $stmt->execute([$student_id]);
        if ($stmt->fetch()) {
            throw new Exception('此學號已經註冊過了');
        }

        // 密碼加密
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 插入新用戶
        $stmt = $pdo->prepare("INSERT INTO users (student_id, name, email, password, role) VALUES (?, ?, ?, ?, 'user')");
        if ($stmt->execute([$student_id, $name, $email, $hashed_password])) {
            $message = "註冊成功！請登入";
            // 清空表單
            $student_id = $name = $email = '';
        } else {
            throw new Exception('註冊失敗，請稍後再試');
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

include 'header.php';
?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4 text-center">註冊帳號</h2>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($message): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST" class="mb-3">
                    <div class="mb-3">
                        <input type="text" name="student_id" class="form-control" placeholder="學號" required 
                               value="<?= htmlspecialchars($student_id ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="name" class="form-control" placeholder="姓名" required
                               value="<?= htmlspecialchars($name ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required
                               value="<?= htmlspecialchars($email ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="密碼" required
                               minlength="6">
                        <small class="form-text text-muted">密碼長度至少 6 個字元</small>
                    </div>
                    <button type="submit" class="btn w-100" style="background-color:rgb(148, 164, 189);">註冊</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="login.php" class="btn"
                        style="background-color: lightgrey; padding: 6px 16px; border-radius: 6px; font-size: 14px;">
                        已有帳號？登入
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>