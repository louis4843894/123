<?php
require_once 'config.php';

try {
    // 讀取 SQL 檔案內容
    $sql = file_get_contents('insert_sample_data.sql');
    
    // 執行 SQL 命令
    $pdo->exec($sql);
    
    echo "測試資料新增成功！";
} catch(PDOException $e) {
    die("測試資料新增失敗: " . $e->getMessage());
}
?> 