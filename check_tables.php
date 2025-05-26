<?php
require_once 'config.php';

try {
    // 獲取所有資料表
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>資料庫中的資料表：</h2>";
    echo "<pre>";
    foreach ($tables as $table) {
        echo "\n資料表：$table\n";
        echo "欄位結構：\n";
        $columns = $pdo->query("DESCRIBE `$table`")->fetchAll(PDO::FETCH_ASSOC);
        print_r($columns);
        echo "\n----------------------------------------\n";
    }
    echo "</pre>";

} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 