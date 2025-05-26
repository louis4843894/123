<?php
require_once 'config.php';
session_start();

// 檢查是否已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = '我的收藏系所';
include 'header.php';

// 獲取使用者的收藏系所
try {
    $stmt = $pdo->prepare("
        SELECT d.*, dd.intro_summary, dd.career_path, dd.url
        FROM compare_list cl
        JOIN departments d ON cl.department_id = d.id
        LEFT JOIN department_details dd ON d.id = dd.department_id
        WHERE cl.user_id = ?
        ORDER BY cl.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = '讀取收藏系所時發生錯誤：' . $e->getMessage();
}
?>

<div class="container mt-4">
    <h2>我的收藏系所</h2>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($favorites)): ?>
        <div class="alert alert-info">
            您還沒有收藏任何系所。
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($favorites as $dept): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="department_detail.php?name=<?php echo urlencode($dept['name']); ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted">
                                <small>
                                    學院：<?php echo htmlspecialchars($dept['college']); ?>
                                </small>
                            </p>
                            <p class="card-text">
                                <?php 
                                // 顯示系所簡介的前200個字符
                                $intro = $dept['intro_summary'] ?? $dept['intro_summary'] ?? '暫無簡介';
                                echo htmlspecialchars(mb_substr(strip_tags($intro), 0, 200)) . '...'; 
                                ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <?php if (!empty($dept['url'])): ?>
                                        <a href="<?php echo htmlspecialchars($dept['url']); ?>" target="_blank" class="btn btn-outline-secondary btn-sm me-2">
                                            <i class="bi bi-globe"></i> 系所網站
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <a href="department_detail.php?name=<?php echo urlencode($dept['name']); ?>" class="btn btn-outline-primary btn-sm me-2">
                                        查看詳情
                                    </a>
                                    <button class="btn btn-outline-danger btn-sm remove-favorite" data-department-id="<?php echo $dept['id']; ?>">
                                        <i class="bi bi-heart-fill"></i> 取消收藏
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 處理取消收藏
    document.querySelectorAll('.remove-favorite').forEach(button => {
        button.addEventListener('click', function() {
            const departmentId = this.dataset.departmentId;
            if (confirm('確定要取消收藏這個系所嗎？')) {
                fetch('remove_favorite.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'department_id=' + departmentId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // 移除對應的卡片
                        this.closest('.col-md-6').remove();
                        // 如果沒有更多收藏，顯示提示訊息
                        if (document.querySelectorAll('.col-md-6').length === 0) {
                            location.reload();
                        }
                    } else {
                        alert('取消收藏失敗：' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('發生錯誤，請稍後再試');
                });
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?> 