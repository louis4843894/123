<?php
require_once 'config.php';

try {
    // 檢查 departments 表格的結構
    $stmt = $pdo->query("SHOW COLUMNS FROM departments");
    echo "<h3>departments 表格結構：</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";

    // 檢查 departments 表格的內容
    $stmt = $pdo->query("SELECT * FROM departments LIMIT 1");
    echo "<h3>departments 表格內容範例：</h3>";
    echo "<pre>";
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($row);
    echo "</pre>";

    // 檢查特定系所的資料
    $stmt = $pdo->prepare("SELECT * FROM departments WHERE name = ?");
    $stmt->execute(['中國文學系']);
    echo "<h3>中國文學系的資料：</h3>";
    echo "<pre>";
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    print_r($row);
    echo "</pre>";

} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
}
?> 