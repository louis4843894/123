<?php
require_once 'config.php';

try {
    // 刪除不需要的資料表
    $tables_to_delete = [
        'favorites',
        'exam_schedule'
    ];

    foreach ($tables_to_delete as $table) {
        $pdo->exec("DROP TABLE IF EXISTS `$table`");
        echo "已刪除資料表: $table<br>";
    }

    echo "清理完成！";

} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 