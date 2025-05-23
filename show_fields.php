<?php
require_once 'config.php';

try {
    // 直接顯示欄位名稱
    $stmt = $pdo->query("SELECT * FROM departmenttransfer LIMIT 0");
    for ($i = 0; $i < $stmt->columnCount(); $i++) {
        $col = $stmt->getColumnMeta($i);
        echo "欄位名稱: " . $col['name'] . "<br>";
    }
} catch(PDOException $e) {
    echo "錯誤: " . $e->getMessage();
}
?> 