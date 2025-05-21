<?php
session_start();
$pageTitle = '登入';
require_once 'config.php';

// 登入邏輯處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $error = "學號或密碼錯誤";
        }
    } catch (PDOException $e) {
        $error = "登入時發生錯誤，請稍後再試";
    }
}

include 'header.php';
?>

<div class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4 text-center">登入</h2>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" class="mb-3">
                    <input type="text" name="student_id" class="form-control mb-2" placeholder="學號" required>
                    <input type="password" name="password" class="form-control mb-2" placeholder="密碼" required>
                    <button type="submit" class="btn w-100" style="background-color:rgb(148, 164, 189);">登入</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="register.php" class="btn"
                        style="background-color: lightgrey; padding: 6px 16px; border-radius: 6px; font-size: 14px;">
                        還沒有帳號？註冊
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>