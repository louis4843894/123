<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// 生成 CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    // 獲取收藏的文章
    $stmt = $pdo->prepare("
        SELECT p.*, u.name as author_name, u.student_id as author_student_id,
               GROUP_CONCAT(DISTINCT t.name) as tags,
               (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
               (SELECT COUNT(*) FROM post_favorites WHERE post_id = p.id) as favorite_count,
               (SELECT COUNT(*) FROM discussion_replies WHERE post_id = p.id) as reply_count
        FROM post_favorites f
        JOIN discussion_posts p ON f.post_id = p.id
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN post_tags pt ON p.id = pt.post_id
        LEFT JOIN discussion_tags t ON pt.tag_id = t.id
        WHERE f.user_id = ?
        GROUP BY p.id
        ORDER BY f.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "資料庫錯誤：" . $e->getMessage();
    exit();
}

$pageTitle = '我的收藏';
include 'header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">我的收藏</h1>

    <?php if (empty($favorites)): ?>
        <div class="alert alert-info">
            您還沒有收藏任何文章。
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($favorites as $post): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="post_detail.php?id=<?= $post['id'] ?>" class="text-decoration-none">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h5>
                            
                            <div class="d-flex align-items-center mb-2">
                                <div class="me-3">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <div>
                                    <div class="fw-bold"><?= htmlspecialchars($post['author_name']) ?></div>
                                    <div class="text-muted small">
                                        <?= htmlspecialchars($post['author_student_id']) ?> | 
                                        <?= htmlspecialchars($post['department_name']) ?>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($post['tags'])): ?>
                                <div class="mb-2">
                                    <?php foreach (explode(',', $post['tags']) as $tag): ?>
                                        <span class="badge bg-secondary me-1"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="card-text mb-3">
                                <?= mb_substr(strip_tags($post['content']), 0, 100) ?>...
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="bi bi-heart"></i> <?= $post['like_count'] ?> |
                                    <i class="bi bi-star"></i> <?= $post['favorite_count'] ?> |
                                    <i class="bi bi-chat"></i> <?= $post['reply_count'] ?>
                                </div>
                                <div class="text-muted small">
                                    <?= date('Y-m-d H:i', strtotime($post['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?> 