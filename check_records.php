<?php
session_start();
require_once 'config.php';

echo "<pre>";
echo "=== Database Check ===\n";

try {
    // 1. 檢查用戶登入狀態
    echo "Current user ID: " . ($_SESSION['user_id'] ?? 'Not logged in') . "\n\n";

    // 2. 檢查表是否存在
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables in database:\n";
    print_r($tables);
    echo "\n";

    // 3. 如果 browse_history 表存在，檢查其結構
    if (in_array('browse_history', $tables)) {
        echo "Browse History table structure:\n";
        $columns = $pdo->query("DESCRIBE browse_history")->fetchAll(PDO::FETCH_ASSOC);
        print_r($columns);
        echo "\n";

        // 4. 檢查記錄數量
        $stmt = $pdo->query("SELECT COUNT(*) FROM browse_history");
        echo "Total records in browse_history: " . $stmt->fetchColumn() . "\n\n";

        // 5. 如果用戶已登入，顯示其記錄
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("
                SELECT * FROM browse_history 
                WHERE user_id = ? 
                ORDER BY viewed_at DESC
            ");
            $stmt->execute([$_SESSION['user_id']]);
            echo "Records for current user:\n";
            print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    } else {
        echo "browse_history table does not exist!\n";
        
        // 6. 嘗試創建表
        echo "\nTrying to create table...\n";
        $sql = file_get_contents('create_browse_history.sql');
        if ($sql) {
            $pdo->exec($sql);
            echo "Table created successfully!\n";
        } else {
            echo "Could not read create_browse_history.sql\n";
        }
    }

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}

echo "</pre>"; 