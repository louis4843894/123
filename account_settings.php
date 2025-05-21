<?php
require_once 'config.php';
require_once 'auth_check.php';
session_start();

$pageTitle = '帳號設定';
require 'header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?");
        $stmt->execute([$name, $email, $hashed_password, $user_id]);
        $message = "✅ 資料與密碼更新成功";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $user_id]);
        $message = "✅ 資料更新成功（密碼未變更）";
    }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="row w-100">
        <div class="col-md-6 mx-auto">
            <div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title mb-4 text-center">帳號設定</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">姓名</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">電子郵件</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">新密碼</label>
                            <input type="password" name="password" class="form-control" placeholder="新密碼">
                        </div>
                        <button type="submit" class="btn w-100 text-white" style="background-color:rgb(148, 164, 189);">更新資料</button>
                    </form>

                    <?php if ($message): ?>
                        <div class="alert alert-success mt-3 text-center" role="alert">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="index.php" class="btn btn-secondary btn-sm">返回首頁</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>
