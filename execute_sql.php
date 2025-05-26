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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sql'])) {
    try {
        $sql = trim($_POST['sql']);
        
        // 檢查是否為 SELECT 查詢
        if (stripos($sql, 'SELECT') === 0) {
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $success_message = '查詢執行成功';
        } else {
            // 執行非 SELECT 查詢
            $affected = $pdo->exec($sql);
            $success_message = "查詢執行成功，影響 {$affected} 行";
        }
    } catch (PDOException $e) {
        $error_message = '執行SQL時發生錯誤：' . $e->getMessage();
    }
}

$pageTitle = '執行SQL';
include 'header.php';
?>

<div class="container mt-4">
    <h2>執行SQL查詢</h2>
    
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
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="sql" class="form-label">SQL查詢</label>
                    <textarea class="form-control" id="sql" name="sql" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">執行查詢</button>
            </form>
        </div>
    </div>
    
    <?php if (isset($results) && !empty($results)): ?>
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">查詢結果</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <?php foreach (array_keys($results[0]) as $column): ?>
                                    <th><?php echo htmlspecialchars($column); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <?php foreach ($row as $value): ?>
                                        <td><?php echo htmlspecialchars($value); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?> 