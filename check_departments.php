<?php
require_once 'config.php';

try {
    // 檢查 departments 表格的結構
    $stmt = $pdo->query("DESCRIBE departments");
    echo "<h3>表格結構：</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";

    // 檢查 departments 表格的內容
    $stmt = $pdo->query("SELECT * FROM departments");
    echo "<h3>表格內容：</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";

} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
}
?> 