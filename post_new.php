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

// 獲取所有系所列表
$departments_stmt = $pdo->query("SELECT DISTINCT department_name FROM DepartmentTransfer ORDER BY department_name");
$departments = $departments_stmt->fetchAll(PDO::FETCH_COLUMN);

// 獲取所有標籤
$tags_stmt = $pdo->query("SELECT id, name FROM discussion_tags ORDER BY name");
$tags = $tags_stmt->fetchAll(PDO::FETCH_ASSOC);

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $department_name = trim($_POST['department_name']);
    $selected_tags = isset($_POST['tags']) ? $_POST['tags'] : [];
    
    $errors = [];
    
    // 驗證
    if (empty($title)) {
        $errors[] = '標題不能為空';
    }
    if (empty($content)) {
        $errors[] = '內容不能為空';
    }
    if (empty($department_name)) {
        $errors[] = '請選擇系所';
    }
    
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            
            // 插入文章
            $stmt = $pdo->prepare("INSERT INTO discussion_posts (title, content, department_name, author_id, author_type) VALUES (?, ?, ?, ?, 'student')");
            $stmt->execute([$title, $content, $department_name, $_SESSION['user_id']]);
            $post_id = $pdo->lastInsertId();
            
            // 處理標籤
            if (!empty($selected_tags)) {
                $stmt = $pdo->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
                foreach ($selected_tags as $tag_id) {
                    $stmt->execute([$post_id, $tag_id]);
                }
            }
            
            $pdo->commit();
            
            // 設置成功訊息到 session
            $_SESSION['success_message'] = '文章發布成功！';
            header('Location: discussion.php');
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = '發文失敗：' . $e->getMessage();
        }
    }
}

$pageTitle = '發佈新文章';
include 'header.php';
?>

<div class="container mt-2 pt-2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">發佈新文章</h2>
                    
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" id="postForm">
                        <div class="mb-3">
                            <label for="title" class="form-label">標題</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= isset($_POST['title']) ? htmlspecialchars($_POST['title']) : '' ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="department_name" class="form-label">系所</label>
                            <select class="form-select" id="department_name" name="department_name" required>
                                <option value="">請選擇系所</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?= htmlspecialchars($dept) ?>" <?= isset($_POST['department_name']) && $_POST['department_name'] === $dept ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dept) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">內容</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required><?= isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '' ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">標籤</label>
                            <div class="row">
                                <?php foreach ($tags as $tag): ?>
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" id="tag_<?= $tag['id'] ?>" <?= isset($_POST['tags']) && in_array($tag['id'], $_POST['tags']) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="tag_<?= $tag['id'] ?>">
                                                <?= htmlspecialchars($tag['name']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">發佈文章</button>
                            <a href="discussion.php" class="btn btn-secondary">取消</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 