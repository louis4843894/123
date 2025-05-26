<?php
require_once 'config.php';

try {
    // 檢查 users 表是否存在
    $check_table = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($check_table->rowCount() == 0) {
        echo "users 表不存在！";
        exit;
    }

    // 顯示 users 表的結構
    echo "<h3>users 表結構：</h3>";
    $columns = $pdo->query("SHOW CREATE TABLE users");
    $row = $columns->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . htmlspecialchars($row['Create Table']) . "</pre>";

    // 顯示 users 表的欄位信息
    echo "<h3>users 表欄位信息：</h3>";
    $columns = $pdo->query("SHOW COLUMNS FROM users");
    echo "<table border='1'>";
    echo "<tr><th>欄位名</th><th>類型</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $columns->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
        echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
}
?> 