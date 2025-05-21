<?php
require_once 'config.php';
session_start();

$pageTitle = '登入';
$message = '';

// 登入邏輯處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['student_id'] = $user['student_id'];
        header('Location: index.php');
        exit();
    } else {
        $message = "帳號或密碼錯誤";
    }
}

include 'header.php';
?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="mb-4 text-center">登入</h2>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="mb-3">
                    <input type="text" name="student_id" class="form-control mb-2" placeholder="學號" required>
                    <input type="password" name="password" class="form-control mb-2" placeholder="密碼" required>
                    <button type="submit" class="btn w-100" style="background-color:rgb(148, 164, 189);">登入</button>
                </form>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <div class="mt-3 text-center">
                    <a href="register.php" class="btn me-2"
                        style="background-color: lightgrey; padding: 6px 16px; border-radius: 6px; font-size: 14px;">
                        註冊新帳號
                    </a>
                    <a href="forgot_password.php" class="btn"
                        style="background-color: lightgrey; padding: 6px 16px; border-radius: 6px; font-size: 14px;">
                        忘記密碼
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>