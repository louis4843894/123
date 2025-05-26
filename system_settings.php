<?php
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
    $maintenance_message = $_POST['maintenance_message'] ?? '系統維護中，請稍後再試。';
    
    try {
        // 更新或插入系統設定
        $stmt = $pdo->prepare("INSERT INTO system_settings (id, maintenance_mode, maintenance_message) 
                              VALUES (1, ?, ?) 
                              ON DUPLICATE KEY UPDATE 
                              maintenance_mode = VALUES(maintenance_mode),
                              maintenance_message = VALUES(maintenance_message)");
        $stmt->execute([$maintenance_mode, $maintenance_message]);
        
        $_SESSION['success_message'] = '系統設定已更新';
        header('Location: system_settings.php');
        exit;
    } catch (PDOException $e) {
        $error_message = '更新設定時發生錯誤：' . $e->getMessage();
    }
}

// 獲取當前設定
try {
    $stmt = $pdo->query("SELECT maintenance_mode, maintenance_message FROM system_settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $settings = [
        'maintenance_mode' => 0,
        'maintenance_message' => '系統維護中，請稍後再試。'
    ];
}

$pageTitle = '系統設定';
include 'header.php';
?>

<div class="container mt-4">
    <h2>系統設定</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success">
            <?php 
            echo $_SESSION['success_message'];
            unset($_SESSION['success_message']);
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="">
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                               <?php echo ($settings['maintenance_mode'] ?? 0) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="maintenance_mode">維護模式</label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="maintenance_message" class="form-label">維護訊息</label>
                    <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3"><?php 
                        echo htmlspecialchars($settings['maintenance_message'] ?? '系統維護中，請稍後再試。'); 
                    ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">儲存設定</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 