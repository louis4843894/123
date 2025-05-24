<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle = $pageTitle ?? '轉系系統'; 
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .navbar {
            background: linear-gradient(90deg, rgb(168, 170, 173) 0%, rgb(114, 115, 116) 100%);
            padding: 10px 20px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
            text-decoration: none;
        }

        .navbar a:hover {
            color: rgb(212, 215, 217);
        }

        .navbar-brand img {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- 導航欄 -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/d/da/Fu_Jen_Catholic_University_logo.svg/1200px-Fu_Jen_Catholic_University_logo.svg.png"
                    alt="輔仁大學校徽" class="d-inline-block align-text-top">輔仁大學轉系系統
            </a>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <a href="admin.php" class="btn btn-outline-light me-2">
                        <i class="bi bi-gear"></i> 管理人員
                    </a>
                    <?php endif; ?>
                    <a href="account_settings.php" class="btn btn-outline-light me-2">帳號設定</a>
                    <a href="logout.php" class="btn btn-outline-light me-2">登出</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light me-2">登入</a>
                    <a href="register.php" class="btn btn-outline-light me-2">註冊</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- 主要內容容器 -->
    <div class="container mt-5 pt-4">

    <script>
        // 比較系所 script removed
    </script>
</body>
</html>