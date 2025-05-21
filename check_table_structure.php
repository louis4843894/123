<?php
require_once 'config.php';

try {
    // 檢查 departments 表格的結構
    $stmt = $pdo->query("DESCRIBE departments");
    echo "<h3>departments 表格結構：</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";

    // 檢查 DepartmentTransfer 表格的結構
    $stmt = $pdo->query("DESCRIBE DepartmentTransfer");
    echo "<h3>DepartmentTransfer 表格結構：</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";

} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
}
?> 