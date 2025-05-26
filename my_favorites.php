<?php
session_start();
require_once 'config.php';

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 獲取用戶的收藏系所
$stmt = $pdo->prepare("
    SELECT f.department_name, d.intro_summary as department_intro 
    FROM favorites f 
    LEFT JOIN departments d ON f.department_name = d.name 
    WHERE f.user_id = ? 
    ORDER BY f.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = '我的收藏';
include 'header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- 左側邊欄 -->
        <aside class="col-md-2 bg-light border-end vh-100 pt-1 mt-1">
            <div class="mt-0">
                <h5 class="px-3">其他功能</h5>
                <ul class="nav flex-column px-3">
                    <li class="nav-item mb-2">
                        <a href="discussion.php" class="nav-link btn btn-secondary text-white fw-bold mb-2">
                            <i class="bi bi-chat-dots"></i> 討論區
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="transfer_qa.php" class="nav-link btn btn-secondary text-white fw-bold mb-2">
                            <i class="bi bi-question-circle"></i> 轉系 Q&A
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="my_favorites.php" class="nav-link btn btn-secondary text-white fw-bold mb-2 active">
                            <i class="bi bi-heart-fill"></i> 我的收藏
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- 中間主內容 -->
        <main class="col-md-10 pt-0 px-5">
            <div class="container mt-0 pt-1">
                <h2 class="mb-4">我的收藏系所</h2>
                
                <?php if (empty($favorites)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 您還沒有收藏任何系所
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="25%" class="text-start">系所名稱</th>
                                    <th width="50%">系所簡介</th>
                                    <th width="25%" class="ps-5">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($favorites as $dept): ?>
                                    <tr>
                                        <td class="text-dark">
                                            <?= htmlspecialchars($dept['department_name']) ?>
                                        </td>
                                        <td>
                                            <p class="mb-0 text-muted">
                                                <?php
                                                $intro = isset($dept['department_intro']) ? $dept['department_intro'] : '暫無簡介';
                                                echo nl2br(htmlspecialchars(mb_strimwidth($intro, 0, 150, '...'))); 
                                                ?>
                                            </p>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-start ps-5">
                                                <a href="department_detail.php?name=<?= urlencode($dept['department_name']) ?>" 
                                                   class="btn-more w-40 text-center py-2">
                                                    點我詳情
                                                </a>
                                                <button class="btn-remove w-40 text-center py-2 remove-favorite" 
                                                        data-dept="<?= htmlspecialchars($dept['department_name']) ?>">
                                                    <i class="bi bi-heart-fill"></i> 取消收藏
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 處理取消收藏按鈕點擊
    document.querySelectorAll('.remove-favorite').forEach(btn => {
        btn.addEventListener('click', function() {
            const deptName = this.dataset.dept;
            
            // 發送請求到服務器
            fetch('check_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    department_name: deptName,
                    action: 'remove'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // 移除該行
                    this.closest('tr').remove();
                    
                    // 如果沒有更多收藏，顯示提示訊息
                    if (document.querySelectorAll('tbody tr').length === 0) {
                        location.reload();
                    }
                } else {
                    alert(data.message || '操作失敗，請稍後再試');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('操作失敗，請稍後再試');
            });
        });
    });
});
</script>

<style>
.btn-more, .btn-remove {
    letter-spacing: 0.5em;
    text-indent: 0.5em;
    white-space: nowrap;
    width: 120px !important;
    display: flex;
    align-items: center;
    justify-content: center;
}
.w-40 {
    width: 40% !important;
}
.bi-heart-fill {
    color: #dc3545;
    margin-right: 5px;
}
</style>

<?php include 'footer.php'; ?> 