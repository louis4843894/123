<?php
require_once 'config.php';

try {
    echo "<h3>資料庫現有資料檢查</h3>";
    
    // 檢查每個表格中的資料數量
    $tables = ['departments', 'DepartmentTransfer', 'department_details'];
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "<br>$table 表格中有 $count 筆資料";
    }
    
    // 顯示所有系所名稱
    echo "<h4>系所列表：</h4>";
    $departments = $pdo->query("SELECT name FROM departments")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($departments as $dept) {
        echo "- $dept<br>";
    }
    
} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
}
?> 