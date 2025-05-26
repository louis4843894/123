<?php
require_once 'config.php';
session_start();

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$success_message = '';
$error_message = '';

try {
    // 建立系所詳細資料表格
    $pdo->exec("CREATE TABLE IF NOT EXISTS department_details (
        id INT PRIMARY KEY AUTO_INCREMENT,
        department_id INT NOT NULL,
        introduction TEXT,
        career_path TEXT,
        url VARCHAR(255),
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL,
        FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    $success_message = '系所詳細資料表格建立完成';
} catch (PDOException $e) {
    $error_message = '建立系所詳細資料表格時發生錯誤：' . $e->getMessage();
}

$pageTitle = '建立系所詳細資料表格';
include 'header.php';
?>

<div class="container mt-4">
    <h2>建立系所詳細資料表格</h2>
    
    <?php if ($success_message): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error_message): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">表格狀態</h5>
            <p class="card-text">
                <?php
                try {
                    $stmt = $pdo->query("SHOW TABLES LIKE 'department_details'");
                    echo "系所詳細資料表格：" . ($stmt->rowCount() > 0 ? "已存在" : "不存在");
                } catch (PDOException $e) {
                    echo "檢查表格狀態時發生錯誤：" . $e->getMessage();
                }
                ?>
            </p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 