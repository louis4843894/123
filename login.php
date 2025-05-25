<?php
session_start();
$pageTitle = '登入';
require_once 'config.php';

// 登入邏輯處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($student_id) || empty($password)) {
        $_SESSION['error'] = '請填寫學號和密碼';
    } else {
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
                $_SESSION['error'] = '學號或密碼錯誤';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = '系統錯誤，請稍後再試';
        }
    }
}

include 'header.php';
?>

<div class="container-fluid vh-100 d-flex align-items-center justify-content-center" style="margin-top: -60px;">
    <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body px-5 py-4">
                    <h2 class="card-title mb-4 text-center">登入</h2>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form method="POST" class="mb-3">
                        <input type="text" name="student_id" class="form-control mb-3" placeholder="學號" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="密碼" required>
                        <button type="submit" class="btn w-100 mb-3 text-white" style="background-color: rgb(148, 164, 189);">登入</button>
                    </form>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="register.php" class="btn" style="background-color: #E9ECEF; color: #333; border: 1px solid #DDD;">
                            還沒有帳號？註冊
                        </a>
                        <a href="forgot_password.php" class="btn" style="background-color: #E9ECEF; color: #333; border: 1px solid #DDD;">
                            忘記密碼？
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn:hover {
    background-color: #DDE0E3 !important;
    color: #333 !important;
}
</style>

<?php include 'footer.php'; ?>