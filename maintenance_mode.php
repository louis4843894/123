<?php
require_once 'config.php';
require_once 'check_maintenance.php';

// 檢查維護模式
$maintenance = checkMaintenanceMode();

// 如果不是維護模式，重定向到首頁
if (!$maintenance['enabled']) {
    header('Location: index.php');
    exit;
}

$pageTitle = '系統維護中';
include 'header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h1 class="display-4 mb-4">系統維護中</h1>
                    <div class="alert alert-warning">
                        <?php echo htmlspecialchars($maintenance['message']); ?>
                    </div>
                    <p class="text-muted">我們正在進行系統維護，請稍後再試。</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 