<?php
require_once 'config.php';
session_start();

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$pageTitle = '資料檢查';
include 'header.php';

// 檢查資料表
$tables = [
    'departments' => '系所資料',
    'department_details' => '系所詳細資料',
    'DepartmentTransfer' => '轉系資料',
    'users' => '使用者資料',
    'password_resets' => '密碼重設資料',
    'discussion_posts' => '討論區文章',
    'discussion_replies' => '討論區回覆',
    'post_likes' => '文章按讚',
    'post_favorites' => '文章收藏',
    'exam_schedule' => '考試時程',
    'compare_list' => '比較清單'
];

$results = [];
foreach ($tables as $table => $description) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        $results[$table] = [
            'status' => 'success',
            'count' => $count,
            'message' => "成功讀取 $description，共有 $count 筆資料"
        ];
    } catch (PDOException $e) {
        $results[$table] = [
            'status' => 'error',
            'message' => "讀取 $description 時發生錯誤：" . $e->getMessage()
        ];
    }
}
?>

<div class="container mt-4">
    <h2>資料檢查</h2>
    
    <div class="row mt-4">
        <?php foreach ($results as $table => $result): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $tables[$table]; ?></h5>
                        <p class="card-text">
                            <?php if ($result['status'] === 'success'): ?>
                                <span class="text-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <?php echo $result['message']; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-danger">
                                    <i class="bi bi-exclamation-circle-fill"></i>
                                    <?php echo $result['message']; ?>
                                </span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?> 