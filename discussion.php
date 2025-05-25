<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

// 顯示錯誤訊息
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 分頁設定
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 搜尋條件
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$department = isset($_GET['department']) ? trim($_GET['department']) : '';
$tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

// 構建查詢
$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(p.title LIKE ? OR p.content LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($department)) {
    $where_conditions[] = "p.department_name = ?";
    $params[] = $department;
}

if (!empty($tag)) {
    $where_conditions[] = "t.name = ?";
    $params[] = $tag;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

try {
    // 計算總筆數
    $count_sql = "SELECT COUNT(DISTINCT p.id) 
                  FROM discussion_posts p 
                  LEFT JOIN post_tags pt ON p.id = pt.post_id 
                  LEFT JOIN discussion_tags t ON pt.tag_id = t.id 
                  $where_clause";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute($params);
    $total_posts = $count_stmt->fetchColumn();
    $total_pages = ceil($total_posts / $limit);

    // 撈取文章列表
    $sql = "SELECT p.*, u.name as username, 
                   GROUP_CONCAT(DISTINCT t.name) as tags,
                   (SELECT COUNT(*) FROM discussion_replies WHERE post_id = p.id) as reply_count
            FROM discussion_posts p 
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN post_tags pt ON p.id = pt.post_id 
            LEFT JOIN discussion_tags t ON pt.tag_id = t.id 
            $where_clause
            GROUP BY p.id
            ORDER BY p.created_at DESC 
            LIMIT $limit OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 獲取所有系所列表
    $departments_stmt = $pdo->query("SELECT DISTINCT department_name FROM DepartmentTransfer ORDER BY department_name");
    $departments = $departments_stmt->fetchAll(PDO::FETCH_COLUMN);

    // 獲取所有標籤
    $tags_stmt = $pdo->query("SELECT name FROM discussion_tags ORDER BY name");
    $tags = $tags_stmt->fetchAll(PDO::FETCH_COLUMN);

} catch (PDOException $e) {
    echo "資料庫錯誤：" . $e->getMessage();
    exit();
}

$pageTitle = '討論區';
include 'header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>討論區</h1>
        <div>
            <a href="my_favorites.php" class="btn btn-outline-warning me-2">
                <i class="bi bi-star"></i> 我的收藏
            </a>
            <a href="post_new.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> 發表文章
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    
    <div class="row">
        <!-- 左側邊欄 -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">篩選條件</h5>
                    <form method="GET" class="mb-3">
                        <div class="mb-3">
                            <label for="search" class="form-label">搜尋</label>
                            <input type="text" class="form-control" id="search" name="search" value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">系所</label>
                            <select class="form-select" id="department" name="department">
                                <option value="">全部系所</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= htmlspecialchars($dept) ?>" <?= $department === $dept ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="tag" class="form-label">標籤</label>
                            <select class="form-select" id="tag" name="tag">
                                <option value="">全部標籤</option>
                                <?php foreach ($tags as $t): ?>
                                    <option value="<?= htmlspecialchars($t) ?>" <?= $tag === $t ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($t) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">套用篩選</button>
                    </form>
                    <a href="post_new.php" class="btn btn-success w-100">發佈新文章</a>
                </div>
            </div>
        </div>

        <!-- 主要內容區 -->
        <div class="col-md-9">
            <?php if (empty($posts)): ?>
                <div class="alert alert-info">
                    目前沒有符合條件的文章。
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="post_detail.php?id=<?= $post['id'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h5>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    作者：<?= htmlspecialchars($post['username']) ?> | 
                                    系所：<?= htmlspecialchars($post['department_name']) ?> | 
                                    發表時間：<?= date('Y-m-d H:i:s', strtotime($post['created_at'])) ?>
                                </small>
                                <span class="badge bg-primary">
                                    <i class="bi bi-chat-dots"></i> <?= $post['reply_count'] ?> 回覆
                                </span>
                            </div>
                            <p class="card-text">
                                <?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>
                                <?= strlen($post['content']) > 200 ? '...' : '' ?>
                            </p>
                            <?php if (!empty($post['tags'])): ?>
                                <div class="mt-2">
                                    <?php foreach (explode(',', $post['tags']) as $tag): ?>
                                        <span class="badge bg-secondary me-1"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- 分頁 -->
                <?php if ($total_pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $department ? '&department=' . urlencode($department) : '' ?><?= $tag ? '&tag=' . urlencode($tag) : '' ?>">上一頁</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $department ? '&department=' . urlencode($department) : '' ?><?= $tag ? '&tag=' . urlencode($tag) : '' ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?><?= $department ? '&department=' . urlencode($department) : '' ?><?= $tag ? '&tag=' . urlencode($tag) : '' ?>">下一頁</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 