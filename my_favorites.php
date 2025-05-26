<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 顯示調試信息
echo "<!-- 會話信息：\n";
echo "user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '未設置') . "\n";
echo "name: " . (isset($_SESSION['name']) ? $_SESSION['name'] : '未設置') . "\n";
echo "student_id: " . (isset($_SESSION['student_id']) ? $_SESSION['student_id'] : '未設置') . "\n";
echo "role: " . (isset($_SESSION['role']) ? $_SESSION['role'] : '未設置') . "\n";
echo " -->\n";

// 生成 CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 獲取用戶的收藏列表
try {
    // 先檢查 favorites 表是否存在
    $check_table = $pdo->query("SHOW TABLES LIKE 'favorites'");
    if ($check_table->rowCount() == 0) {
        throw new Exception("favorites 表不存在！");
    }

    // 顯示調試信息
    echo "<!-- 用戶ID: " . $_SESSION['user_id'] . " -->\n";
    
    $stmt = $pdo->prepare("
        SELECT f.*, d.intro_summary 
        FROM favorites f 
        LEFT JOIN departments d ON f.department_name = d.name 
        WHERE f.user_id = ? 
        ORDER BY f.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 顯示調試信息
    echo "<!-- 查詢到的收藏數量: " . count($favorites) . " -->\n";
    
} catch (Exception $e) {
    $error = "獲取收藏列表失敗：" . $e->getMessage();
}

$pageTitle = '我的收藏';
include 'header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">我的收藏</h2>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (empty($favorites)): ?>
        <div class="alert alert-info">
            您還沒有收藏任何系所。瀏覽系所時，點擊「收藏」按鈕即可將系所加入收藏。
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>系所名稱</th>
                        <th>簡介</th>
                        <th>收藏時間</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($favorites as $favorite): ?>
                        <tr>
                            <td>
                                <a href="department_detail.php?name=<?php echo urlencode($favorite['department_name']); ?>">
                                    <?php echo htmlspecialchars($favorite['department_name']); ?>
                                </a>
                            </td>
                            <td>
                                <?php 
                                $intro = isset($favorite['intro_summary']) ? $favorite['intro_summary'] : '暫無簡介';
                                echo nl2br(htmlspecialchars(mb_strimwidth($intro, 0, 150, '...'))); 
                                ?>
                            </td>
                            <td><?php echo date('Y-m-d H:i', strtotime($favorite['created_at'])); ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-favorite" 
                                        data-department="<?php echo htmlspecialchars($favorite['department_name']); ?>">
                                    取消收藏
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 處理取消收藏
    document.querySelectorAll('.remove-favorite').forEach(button => {
        button.addEventListener('click', function() {
            const department = this.dataset.department;
            if (confirm('確定要取消收藏此系所嗎？')) {
                fetch('favorite.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `department_name=${encodeURIComponent(department)}&action=remove`
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
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('操作失敗，請稍後再試');
                });
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?> 