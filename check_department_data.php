<?php
require_once 'config.php';

try {
    // 檢查 departments 表格中的資料
    $stmt = $pdo->prepare("SELECT * FROM departments WHERE name = ?");
    $stmt->execute(['兒童與家庭學系']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>兒童與家庭學系的資料：</h3>";
    echo "<pre>";
    if ($row) {
        print_r($row);
    } else {
        echo "找不到兒童與家庭學系的資料";
    }
    echo "</pre>";

    // 檢查 DepartmentTransfer 表格中的資料
    $stmt = $pdo->prepare("SELECT * FROM DepartmentTransfer WHERE department_name = ?");
    $stmt->execute(['兒童與家庭學系']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "<h3>DepartmentTransfer 表格中的資料：</h3>";
    echo "<pre>";
    if ($row) {
        print_r($row);
    } else {
        echo "找不到兒童與家庭學系的資料";
    }
    echo "</pre>";

} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
}
?> 