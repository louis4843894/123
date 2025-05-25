<?php
session_start();
require_once 'config.php';

echo "<pre>";
echo "=== Login Check ===\n";

// 檢查 session
echo "Session ID: " . session_id() . "\n";
echo "Session Data:\n";
print_r($_SESSION);

// 如果用戶已登入，檢查資料庫中的用戶資訊
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "\nUser Data from Database:\n";
        print_r($user);
    } catch (PDOException $e) {
        echo "\nDatabase Error: " . $e->getMessage() . "\n";
    }
}

// 檢查瀏覽記錄表
try {
    $tables = $pdo->query("SHOW TABLES LIKE 'browse_history'")->fetchAll();
    echo "\nBrowse History Table exists: " . (count($tables) > 0 ? 'Yes' : 'No') . "\n";
    
    if (count($tables) > 0) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM browse_history");
        echo "Total records in browse_history: " . $stmt->fetchColumn() . "\n";
        
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT * FROM browse_history WHERE user_id = ? ORDER BY viewed_at DESC");
            $stmt->execute([$_SESSION['user_id']]);
            echo "\nUser's Browse History:\n";
            print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }
} catch (PDOException $e) {
    echo "\nDatabase Error: " . $e->getMessage() . "\n";
}

echo "</pre>"; 