<?php
require_once 'config.php';

echo "<pre>";
echo "=== Users Table Check ===\n";

try {
    // 檢查 users 表結構
    $columns = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_ASSOC);
    echo "Users table structure:\n";
    print_r($columns);
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
}

echo "</pre>"; 