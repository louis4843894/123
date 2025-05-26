<?php
session_start();
require_once 'config.php';
require_once 'functions.php';
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

// 生成 CSRF token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// 獲取文章 ID
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// 記錄瀏覽歷史
if ($post_id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT title FROM discussion_posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($post) {
            recordPageView('discussion', $post_id, $post['title']);
        }
    } catch(PDOException $e) {
        error_log("Error getting post title: " . $e->getMessage());
    }
}

try {
    // 獲取文章詳情
    $stmt = $pdo->prepare("
        SELECT p.*, u.name as author_name, u.student_id as author_student_id,
               GROUP_CONCAT(DISTINCT t.name) as tags,
               (SELECT COUNT(*) FROM post_likes WHERE post_id = p.id) as like_count,
               (SELECT COUNT(*) FROM post_favorites WHERE post_id = p.id) as favorite_count,
               EXISTS(SELECT 1 FROM post_likes WHERE post_id = p.id AND user_id = ?) as is_liked,
               EXISTS(SELECT 1 FROM post_favorites WHERE post_id = p.id AND user_id = ?) as is_favorited
        FROM discussion_posts p
        LEFT JOIN users u ON p.author_id = u.id
        LEFT JOIN post_tags pt ON p.id = pt.post_id
        LEFT JOIN discussion_tags t ON pt.tag_id = t.id
        WHERE p.id = ?
        GROUP BY p.id
    ");
    $stmt->execute([$_SESSION['user_id'], $_SESSION['user_id'], $post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        header('Location: discussion.php');
        exit();
    }

    // 處理點讚和收藏操作
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $_SESSION['error_message'] = '無效的表單提交';
            header("Location: post_detail.php?id=$post_id");
            exit();
        }

        // 處理點讚
        if (isset($_POST['like_post'])) {
            try {
                if ($post['is_liked']) {
                    $stmt = $pdo->prepare("DELETE FROM post_likes WHERE post_id = ? AND user_id = ?");
                } else {
                    $stmt = $pdo->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (?, ?)");
                }
                $stmt->execute([$post_id, $_SESSION['user_id']]);
                header("Location: post_detail.php?id=$post_id");
                exit();
            } catch (Exception $e) {
                $_SESSION['error_message'] = '操作失敗：' . $e->getMessage();
            }
        }

        // 處理收藏
        if (isset($_POST['favorite_post'])) {
            try {
                if ($post['is_favorited']) {
                    $stmt = $pdo->prepare("DELETE FROM post_favorites WHERE post_id = ? AND user_id = ?");
                } else {
                    $stmt = $pdo->prepare("INSERT INTO post_favorites (post_id, user_id) VALUES (?, ?)");
                }
                $stmt->execute([$post_id, $_SESSION['user_id']]);
                header("Location: post_detail.php?id=$post_id");
                exit();
            } catch (Exception $e) {
                $_SESSION['error_message'] = '操作失敗：' . $e->getMessage();
            }
        }

        // 處理刪除留言
        if (isset($_POST['delete_reply'])) {
            $reply_id = (int)$_POST['delete_reply'];
            try {
                // 檢查是否有權限刪除
                $stmt = $pdo->prepare("
                    SELECT author_id 
                    FROM discussion_replies 
                    WHERE id = ? AND post_id = ?
                ");
                $stmt->execute([$reply_id, $post_id]);
                $reply = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($reply) {
                    // 檢查是否為作者或管理員
                    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user_role = $stmt->fetchColumn();

                    if ($reply['author_id'] === $_SESSION['user_id'] || $user_role === 'admin') {
                        // 刪除留言及其所有回覆
                        $stmt = $pdo->prepare("DELETE FROM discussion_replies WHERE id = ? OR parent_id = ?");
                        $stmt->execute([$reply_id, $reply_id]);
                        
                        $_SESSION['success_message'] = '留言已刪除';
                    } else {
                        $_SESSION['error_message'] = '您沒有權限刪除此留言';
                    }
                }
            } catch (Exception $e) {
                $_SESSION['error_message'] = '刪除留言失敗：' . $e->getMessage();
            }
            header("Location: post_detail.php?id=$post_id");
            exit();
        }

        // 處理新留言提交
        if (isset($_POST['content'])) {
            $content = trim($_POST['content']);
            $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
            
            if (!empty($content)) {
                try {
                    // 如果是回覆，先確認父留言是否存在且屬於同一篇文章
                    if ($parent_id !== null) {
                        $stmt = $pdo->prepare("SELECT id FROM discussion_replies WHERE id = ? AND post_id = ?");
                        $stmt->execute([$parent_id, $post_id]);
                        if (!$stmt->fetch()) {
                            throw new Exception('無效的回覆目標');
                        }
                    }

                    // 獲取用戶類型
                    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $user_role = $stmt->fetchColumn();
                    $author_type = ($user_role === 'admin') ? 'admin' : 'student';

                    // 使用參數化查詢來防止 SQL 注入
                    $stmt = $pdo->prepare("
                        INSERT INTO discussion_replies 
                        (post_id, author_id, content, parent_id, author_type, level) 
                        VALUES 
                        (:post_id, :author_id, :content, :parent_id, :author_type, :level)
                    ");

                    $level = $parent_id === null ? 0 : 1;

                    $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
                    $stmt->bindParam(':author_id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
                    $stmt->bindParam(':parent_id', $parent_id, PDO::PARAM_INT);
                    $stmt->bindParam(':author_type', $author_type, PDO::PARAM_STR);
                    $stmt->bindParam(':level', $level, PDO::PARAM_INT);

                    $stmt->execute();
                    
                    // 重新生成 CSRF token
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                    
                    // 設置成功訊息
                    $_SESSION['success_message'] = '留言發布成功！';
                    header("Location: post_detail.php?id=$post_id");
                    exit();
                } catch (Exception $e) {
                    $_SESSION['error_message'] = '留言發布失敗：' . $e->getMessage();
                }
            } else {
                $_SESSION['error_message'] = '留言內容不能為空！';
            }
        }
    }

    // 獲取留言（包含回覆）
    $stmt = $pdo->prepare("
        SELECT DISTINCT r.*, u.name as author_name, u.student_id as author_student_id
        FROM discussion_replies r
        LEFT JOIN users u ON r.author_id = u.id
        WHERE r.post_id = ?
        ORDER BY r.parent_id IS NULL DESC, r.created_at ASC, r.id ASC
    ");
    $stmt->execute([$post_id]);
    $all_replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 正確組織留言結構（使用引用）
    $replies_by_id = [];
    foreach ($all_replies as $k => &$reply) {
        $reply['replies'] = [];
        $replies_by_id[$reply['id']] = &$reply;
    }
    unset($reply); // 解除引用

    $replies = [];
    foreach ($all_replies as &$reply) {
        if ($reply['parent_id'] === null) {
            $replies[] = &$reply;
        } else {
            if (isset($replies_by_id[$reply['parent_id']])) {
                $replies_by_id[$reply['parent_id']]['replies'][] = &$reply;
            }
        }
    }
    unset($reply);

    // 修改第 333 行附近的代碼
    $post['title'] = htmlspecialchars($post['title'] ?? '');
    $post['content'] = htmlspecialchars($post['content'] ?? '');
    $post['author_name'] = htmlspecialchars($post['author_name'] ?? '');
    $post['author_student_id'] = htmlspecialchars($post['author_student_id'] ?? '');
    $post['tags'] = htmlspecialchars($post['tags'] ?? '');

    // 檢查是否已收藏
    $is_favorited = false;
    if (isset($_SESSION['user_id'])) {
        $favorite_sql = "SELECT id FROM post_favorites WHERE user_id = ? AND post_id = ?";
        $favorite_stmt = $pdo->prepare($favorite_sql);
        $favorite_stmt->execute([$_SESSION['user_id'], $post_id]);
        $is_favorited = $favorite_stmt->fetch() !== false;
    }

} catch (PDOException $e) {
    echo "資料庫錯誤：" . $e->getMessage();
    exit();
}

// 取得目前登入者的角色
$stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user_role = $stmt->fetchColumn();

// ====== 新增：遞迴顯示留言 function ======
function renderReplies($replies, $user_role, $session_user_id, $level = 0) {
    foreach ($replies as $reply) {
        $reply['content'] = htmlspecialchars($reply['content'] ?? '');
        $reply['author_name'] = htmlspecialchars($reply['author_name'] ?? '');
        $reply['author_student_id'] = htmlspecialchars($reply['author_student_id'] ?? '');
        ?>
        <div class="ms-<?= 5 * $level ?> mb-3">
            <div class="d-flex align-items-center mb-2">
                <div class="me-3">
                    <i class="bi bi-person-circle fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold"><?= $reply['author_name'] ?></div>
                    <div class="text-muted small"><?= $reply['author_student_id'] ?></div>
                </div>
            </div>
            <div class="ms-5">
                <p class="mb-2"><?= nl2br($reply['content']) ?></p>
                <div class="text-muted small">
                    <?= date('Y/m/d H:i', strtotime($reply['created_at'])) ?>
                <?php if ($reply['author_id'] === $session_user_id || $user_role === 'admin'): ?>
                        <form method="POST" class="d-inline" onsubmit="return confirm('確定要刪除這則留言嗎？');">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" name="delete_reply" value="<?= $reply['id'] ?>" class="btn btn-link btn-sm text-danger p-0 ms-2">刪除</button>
                    </form>
                <?php endif; ?>
            </div>
            </div>
            </div>
            <?php
            if (!empty($reply['replies'])) {
                renderReplies($reply['replies'], $user_role, $session_user_id, $level + 1);
            }
    }
}
// ====== 遞迴 function 結束 ======

$pageTitle = htmlspecialchars($post['title']);
include 'header.php';
?>

<div class="container mt-5">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['error_message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- 文章內容 -->
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="card-title mb-3"><?= htmlspecialchars($post['title']) ?></h1>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="bi bi-person-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="fw-bold"><?= htmlspecialchars($post['author_name']) ?></div>
                        <div class="text-muted small">
                            <?= htmlspecialchars($post['author_student_id']) ?> | 
                            <?= htmlspecialchars($post['department_name']) ?>
                        </div>
                    </div>
                </div>
                <div class="text-muted small">
                    發表於 <?= date('Y-m-d H:i:s', strtotime($post['created_at'])) ?>
                </div>
            </div>

            <?php if (!empty($post['tags'])): ?>
                <div class="mb-3">
                    <?php foreach (explode(',', $post['tags']) as $tag): ?>
                        <span class="badge bg-secondary me-1"><?= htmlspecialchars($tag) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="card-text mb-4">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <!-- 點讚和收藏按鈕 -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button class="btn btn-outline-primary like-btn" data-post-id="<?= $post['id'] ?>">
                        <i class="bi bi-heart<?= $post['is_liked'] ? '-fill' : '' ?>"></i>
                        <span class="like-count"><?= $post['like_count'] ?? 0 ?></span>
                    </button>
                    <button class="btn btn-outline-warning favorite-btn" data-post-id="<?= $post['id'] ?>">
                        <i class="bi bi-bookmark<?= $is_favorited ? '-fill' : '' ?>"></i>
                        <span class="favorite-count"><?= $post['favorite_count'] ?? 0 ?></span>
                    </button>
                </div>
                <div>
                    <a href="discussion.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> 返回討論區
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 留言區 -->
    <div class="card">
        <div class="card-body">
            <h3 class="card-title mb-4">留言區</h3>

            <!-- 發表留言表單 -->
            <form method="POST" class="mb-4" id="mainReplyForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="mb-3">
                    <textarea class="form-control" name="content" rows="3" placeholder="發表你的想法..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="submitBtn">發表留言</button>
            </form>

            <!-- 留言列表 -->
            <?php if (empty($replies)): ?>
                <div class="text-center text-muted py-4">
                    還沒有留言，來發表第一則留言吧！
                </div>
            <?php else: ?>
                <?php renderReplies($replies, $user_role, $_SESSION['user_id']); ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // 點讚功能
    $('.like-btn').click(function() {
        const postId = $(this).data('post-id');
        const $btn = $(this);
        const $icon = $btn.find('i');
        const $count = $btn.find('.like-count');
        
        $.post('toggle_like.php', {
            post_id: postId
        }, function(response) {
            if (response.success) {
                if (response.is_liked) {
                    $icon.removeClass('bi-heart').addClass('bi-heart-fill');
                } else {
                    $icon.removeClass('bi-heart-fill').addClass('bi-heart');
                }
                $count.text(response.like_count);
            } else {
                alert(response.message || '操作失敗');
            }
        });
    });

    // 收藏功能
    $('.favorite-btn').click(function() {
        const postId = $(this).data('post-id');
        const $btn = $(this);
        const $icon = $btn.find('i');
        const $count = $btn.find('.favorite-count');
        
        $.post('toggle_favorite.php', {
            post_id: postId
        }, function(response) {
            if (response.success) {
                if (response.is_favorited) {
                    $icon.removeClass('bi-bookmark').addClass('bi-bookmark-fill');
                } else {
                    $icon.removeClass('bi-bookmark-fill').addClass('bi-bookmark');
                }
                $count.text(response.favorite_count);
            } else {
                alert(response.message || '操作失敗');
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?> 