<?php
session_start();
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '請先登入']);
    exit();
}

if (!isset($_POST['post_id']) || !is_numeric($_POST['post_id'])) {
    echo json_encode(['success' => false, 'message' => '無效的貼文ID']);
    exit();
}

$user_id = $_SESSION['user_id'];
$post_id = (int)$_POST['post_id'];

try {
    // 檢查貼文是否存在
    $check_sql = "SELECT id FROM discussion_posts WHERE id = ?";
    $check_stmt = $pdo->prepare($check_sql);
    $check_stmt->execute([$post_id]);
    if (!$check_stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => '貼文不存在']);
        exit();
    }

    // 檢查是否已點讚
    $like_sql = "SELECT id FROM post_likes WHERE user_id = ? AND post_id = ?";
    $like_stmt = $pdo->prepare($like_sql);
    $like_stmt->execute([$user_id, $post_id]);
    $is_liked = $like_stmt->fetch() !== false;

    if ($is_liked) {
        // 取消點讚
        $delete_sql = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";
        $delete_stmt = $pdo->prepare($delete_sql);
        $delete_stmt->execute([$user_id, $post_id]);
        $is_liked = false;
    } else {
        // 添加點讚
        $insert_sql = "INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->execute([$user_id, $post_id]);
        $is_liked = true;
    }

    // 獲取更新後的點讚數
    $count_sql = "SELECT COUNT(*) FROM post_likes WHERE post_id = ?";
    $count_stmt = $pdo->prepare($count_sql);
    $count_stmt->execute([$post_id]);
    $like_count = $count_stmt->fetchColumn();

    echo json_encode([
        'success' => true,
        'is_liked' => $is_liked,
        'like_count' => $like_count
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '資料庫錯誤：' . $e->getMessage()]);
}
?> 