<?php
session_start();
require_once 'config.php';

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = '最近瀏覽';
include 'header.php';

// 獲取用戶的最近瀏覽記錄
try {
    $stmt = $pdo->prepare("
        SELECT page_type, page_id, page_title, viewed_at 
        FROM browse_history 
        WHERE user_id = :user_id 
        ORDER BY viewed_at DESC 
        LIMIT 10
    ");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $recent_views = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $recent_views = [];
}
?>

<div class="container mt-5 pt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-clock-history"></i> 最近瀏覽</h4>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_views)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-muted" style="font-size: 2rem;"></i>
                            <p class="mt-3 text-muted">您還沒有瀏覽記錄</p>
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recent_views as $view): ?>
                                <a href="<?= getPageUrl($view['page_type'], $view['page_id']) ?>" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">
                                            <?php
                                            $icon = '';
                                            switch($view['page_type']) {
                                                case 'department':
                                                    $icon = '<i class="bi bi-building"></i>';
                                                    break;
                                                case 'discussion':
                                                    $icon = '<i class="bi bi-chat-dots"></i>';
                                                    break;
                                                case 'schedule':
                                                    $icon = '<i class="bi bi-calendar-event"></i>';
                                                    break;
                                            }
                                            echo $icon . ' ' . htmlspecialchars($view['page_title']);
                                            ?>
                                        </h5>
                                        <small class="text-muted">
                                            <?= date('Y/m/d H:i', strtotime($view['viewed_at'])) ?>
                                        </small>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// 獲取頁面URL的輔助函數
function getPageUrl($page_type, $page_id) {
    switch($page_type) {
        case 'department':
            return "department_detail.php?name=" . urlencode($page_id);
        case 'discussion':
            return "post_detail.php?id=" . urlencode($page_id);
        case 'schedule':
            return "time.php";
        default:
            return "#";
    }
}
?>

<style>
.card {
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    border-bottom: none;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 1rem;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}

.bi {
    margin-right: 0.5rem;
}
</style>

<?php include 'footer.php'; ?> 