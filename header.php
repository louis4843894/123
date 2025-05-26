<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'check_maintenance.php';

// 檢查維護模式
checkMaintenanceMode($pdo);

$pageTitle = $pageTitle ?? '轉系系統'; 

// 記錄當前頁面作為下一個頁面的「上一個頁面」
$current_url = $_SERVER['REQUEST_URI'];
if (!isset($_SESSION['previous_page']) || $_SESSION['previous_page'] !== $current_url) {
    $_SESSION['last_page'] = $_SESSION['previous_page'] ?? 'index.php';
    $_SESSION['previous_page'] = $current_url;
}

// 判斷是否為管理員
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - 轉系資訊平台' : '轉系資訊平台'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .navbar {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            min-height: 60px;
        }

        .navbar .btn {
            padding: 0.375rem 1rem;
            font-size: 0.95rem;
        }

        .navbar-brand {
            font-size: 1.1rem;
            padding: 0;
        }

        .navbar-brand img {
            width: 28px;
            height: 28px;
        }
    </style>
</head>

<body>
    <!-- 導航欄 -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="d-flex align-items-center">
                <?php if (basename($_SERVER['PHP_SELF']) !== 'index.php'): ?>
                <a href="<?php echo $_SESSION['last_page']; ?>" class="btn btn-outline-light me-3">
                    <i class="bi bi-arrow-left"></i> 回上頁
                </a>
                <?php endif; ?>
                <a class="navbar-brand" href="<?php 
                    if ($is_admin) {
                        echo 'admin_dashboard.php';
                    } else {
                        echo 'index.php';
                    }
                ?>">
                <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/d/da/Fu_Jen_Catholic_University_logo.svg/1200px-Fu_Jen_Catholic_University_logo.svg.png"
                    alt="" width="30" height="30" class="d-inline-block align-text-top me-2">輔仁大學轉系系統
            </a>
            </div>
            <div class="d-flex align-items-center">
                <?php if ($is_admin): ?>
                    <a href="logout.php" class="btn btn-outline-light me-2">登出</a>
                <?php else: ?>
                    <?php 
                    $current_page = basename($_SERVER['PHP_SELF']);
                    if (isset($_SESSION['user_id'])): ?>
                        <a href="compare.php" class="btn btn-outline-light me-2" id="compareButton">
                            <i class="bi bi-arrow-left-right"></i> 比較系所
                            <span class="badge bg-light text-dark" id="compare-count">0</span>
                        </a>
                        <a href="account_settings.php" class="btn btn-outline-light me-2">帳號設定</a>
                        <a href="logout.php" class="btn btn-outline-light me-2">登出</a>
                    <?php else: ?>
                        <a href="#" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-arrow-left-right"></i> 比較系所
                    <span class="badge bg-light text-dark" id="compare-count">0</span>
                </a>
                    <a href="login.php" class="btn btn-outline-light me-2">登入</a>
                    <a href="register.php" class="btn btn-outline-light me-2">註冊</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <script>
        // 自定義的 alert 函數
        function showAlert(message) {
            setTimeout(function() {
                alert(message);
            }, 0);
        }

        // 更新比較數量
        function updateCompareCount() {
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            const countElements = document.querySelectorAll('#compare-count');
            countElements.forEach(element => {
                element.textContent = compareList.length;
            });
        }

        // 檢查比較列表並導向比較頁面
        function handleCompareClick(event) {
            event.preventDefault();
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            if (compareList.length < 2) {
                showAlert('請至少選擇兩個系所進行比較');
                return;
            }
            window.location.href = 'compare.php?departments=' + compareList.join(',');
        }

        // 頁面載入時更新比較數量和按鈕狀態
        document.addEventListener('DOMContentLoaded', function() {
            updateCompareCount();
            
            // 綁定比較按鈕點擊事件
            const compareButton = document.getElementById('compareButton');
            if (compareButton) {
                compareButton.addEventListener('click', handleCompareClick);
            }

            // 更新所有比較按鈕的狀態
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            document.querySelectorAll('.toggle-compare-btn').forEach(btn => {
                const deptName = btn.dataset.dept;
                if (compareList.includes(deptName)) {
                    btn.classList.remove("btn-success");
                    btn.classList.add("btn-danger");
                    btn.innerHTML = '<i class="bi bi-dash-circle"></i> 移除比較';
                    btn.dataset.action = "remove";
                }
            });
        });

        // 監聽 localStorage 變化
        window.addEventListener('storage', function(e) {
            if (e.key === 'compare_departments') {
                updateCompareCount();
            }
        });
    </script>
</body>

</html>

