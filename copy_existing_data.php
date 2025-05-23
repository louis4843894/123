<?php
require_once 'config.php';

try {
    // 先從 departments 表格獲取現有的系所資料
    $stmt = $pdo->query("SELECT name FROM departments");
    $departments = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // 為每個系所建立一個基本的 department_details 記錄
    foreach ($departments as $dept_name) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM department_details WHERE department_name = ?");
        $check->execute([$dept_name]);
        
        // 只有當該系所在 department_details 中不存在時才插入
        if ($check->fetchColumn() == 0) {
            $insert = $pdo->prepare("INSERT INTO department_details (department_name) VALUES (?)");
            $insert->execute([$dept_name]);
            echo "已為 {$dept_name} 建立詳細資料記錄<br>";
        } else {
            echo "{$dept_name} 的詳細資料記錄已存在<br>";
        }
    }
    
    // 顯示結果
    $count = $pdo->query("SELECT COUNT(*) FROM department_details")->fetchColumn();
    echo "<br>完成！department_details 表格現在有 {$count} 筆資料<br>";
    echo "<a href='index.php'>回到首頁</a>";
    
} catch(PDOException $e) {
    echo "操作失敗: " . $e->getMessage();
}
?> 