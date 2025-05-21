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
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
            padding-top: 60px;
        }

        .navbar {
            background: linear-gradient(90deg, rgb(168, 170, 173) 0%, rgb(114, 115, 116) 100%);
            padding: 10px 20px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
        }

        .navbar a:hover {
            color: rgb(212, 215, 217);
        }
    </style>
</head>

<body>
    <!-- 導航欄 -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/d/da/Fu_Jen_Catholic_University_logo.svg/1200px-Fu_Jen_Catholic_University_logo.svg.png"
                    alt="" width="30" height="30" class="d-inline-block align-text-top me-2">輔仁大學轉系系統
            </a>
            <div class="d-flex align-items-center">
                <a href="compare.php" class="btn btn-outline-light me-2" onclick="return checkCompareList(event)">
                    <i class="bi bi-arrow-left-right"></i> 比較系所
                    <span class="badge bg-light text-dark" id="compare-count">0</span>
                </a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="btn btn-outline-light me-2">登入</a>
                    <a href="register.php" class="btn btn-outline-light me-2">註冊</a>
                <?php else: ?>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="admin.php" class="btn btn-outline-light me-2">
                        <i class="bi bi-gear"></i> 管理人員
                    </a>
                    <?php endif; ?>
                    <a href="account_settings.php" class="btn btn-outline-light me-2">帳號設定</a>
                    <a href="logout.php" class="btn btn-outline-light me-2">登出</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        // 更新比較數量
        function updateCompareCount() {
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            const countElements = document.querySelectorAll('#compare-count');
            countElements.forEach(element => {
                element.textContent = compareList.length;
            });
        }

        // 檢查比較列表
        function checkCompareList(event) {
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            if (compareList.length < 2) {
                event.preventDefault();
                alert('請至少選擇兩個系所進行比較！');
                return false;
            }
            return true;
        }

        // 頁面載入時更新比較數量
        document.addEventListener('DOMContentLoaded', function() {
            updateCompareCount();
        });
    </script>
</body>

</html>

