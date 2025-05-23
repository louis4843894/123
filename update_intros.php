<?php
require_once 'config.php';

try {
    // 讀取 SQL 文件內容
    $sql = file_get_contents('update_department_intros.sql');
    
    // 執行 SQL
    $stmt = $pdo->exec($sql);
    
    echo "成功更新 " . $stmt . " 個系所的簡介。";
    
    // 顯示更新後的資料
    $check_stmt = $pdo->query("SELECT department_name, department_intro FROM DepartmentTransfer WHERE department_intro != '暫無簡介' LIMIT 5");
    echo "<h3>更新後的前5筆資料：</h3>";
    echo "<pre>";
    while ($row = $check_stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['department_name'] . ":\n";
        echo $row['department_intro'] . "\n\n";
    }
    echo "</pre>";
    
} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 