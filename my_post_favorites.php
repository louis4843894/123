<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 分頁設定
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

try {
    // 計算總筆數
    $count_sql = "SELECT COUNT(*) 
                  FROM post_favorites f 
                  JOIN discussion_posts p ON f.post_id = p.id 
                  WHERE f.user_id = ?";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute([$_SESSION['user_id']]);
    $total_posts = $count_stmt->fetchColumn();
    $total_pages = ceil($total_posts / $limit);

    // 撈取收藏的文章列表
    $sql = "SELECT p.*, u.name as username, 
                   GROUP_CONCAT(DISTINCT t.name) as tags,
                   (SELECT COUNT(*) FROM discussion_replies WHERE post_id = p.id) as reply_count,
                   f.created_at as favorited_at
            FROM post_favorites f 
            JOIN discussion_posts p ON f.post_id = p.id
            LEFT JOIN users u ON p.author_id = u.id
            LEFT JOIN post_tags pt ON p.id = pt.post_id 
            LEFT JOIN discussion_tags t ON pt.tag_id = t.id 
            WHERE f.user_id = ?
            GROUP BY p.id
            ORDER BY f.created_at DESC 
            LIMIT ?, ?";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(2, $offset, PDO::PARAM_INT);
    $stmt->bindValue(3, $limit, PDO::PARAM_INT);
    $stmt->execute();
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "資料庫錯誤：" . $e->getMessage();
    exit();
}

$pageTitle = '我的收藏';
include 'header.php';
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>我的收藏</h1>
        <div>
            <a href="discussion.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> 返回討論區
            </a>
        </div>
    </div>

    <?php if (empty($posts)): ?>
        <div class="alert alert-info">
            您還沒有收藏任何文章。
        </div>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="post_detail.php?id=<?= $post['id'] ?>" class="text-decoration-none">
                            <?= htmlspecialchars($post['title'] ?? '') ?>
                        </a>
                    </h5>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">
                            作者：<?= htmlspecialchars($post['username'] ?? '') ?> | 
                            系所：<?= htmlspecialchars($post['department_name'] ?? '') ?> | 
                            發表時間：<?= date('Y-m-d H:i:s', strtotime($post['created_at'])) ?> |
                            收藏時間：<?= date('Y-m-d H:i:s', strtotime($post['favorited_at'])) ?>
                        </small>
                        <span class="badge bg-primary">
                            <i class="bi bi-chat-dots"></i> <?= $post['reply_count'] ?> 回覆
                        </span>
                    </div>
                    <p class="card-text">
                        <?= nl2br(htmlspecialchars(substr($post['content'] ?? '', 0, 200))) ?>
                        <?= strlen($post['content'] ?? '') > 200 ? '...' : '' ?>
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
                            <a class="page-link" href="?page=<?= $page - 1 ?>">上一頁</a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">下一頁</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?> 