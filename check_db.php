<?php
require_once 'config.php';

try {
    // 檢查 departments 資料表
    $stmt = $pdo->query("SELECT * FROM departments");
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "資料庫連線成功！\n\n";
    echo "departments 資料表內容：\n";
    echo "------------------------\n";
    
    if (count($departments) > 0) {
        foreach ($departments as $dept) {
            echo "系所名稱: " . $dept['name'] . "\n";
            echo "名額: " . $dept['quota'] . "\n";
            echo "年級: " . $dept['grade'] . "\n";
            echo "要求: " . $dept['requirements'] . "\n";
            echo "額外資訊: " . $dept['additional_info'] . "\n";
            echo "------------------------\n";
        }
    } else {
        echo "departments 資料表中沒有資料\n";
    }
    
} catch(PDOException $e) {
    echo "錯誤: " . $e->getMessage();
}
?> 