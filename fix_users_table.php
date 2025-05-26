<?php
require_once 'config.php';

try {
    // 先顯示修改前的表結構
    echo "<h3>修改前的表結構：</h3>";
    $columns = $pdo->query("SHOW CREATE TABLE users");
    $row = $columns->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . htmlspecialchars($row['Create Table']) . "</pre>";

    // 修改 users 表的 id 欄位
    $sql = "ALTER TABLE users 
            MODIFY COLUMN id int(11) NOT NULL AUTO_INCREMENT,
            ADD PRIMARY KEY (id)";
    
    $pdo->exec($sql);
    echo "<h3>users 表修改成功！</h3>";

    // 顯示修改後的表結構
    echo "<h3>修改後的表結構：</h3>";
    $columns = $pdo->query("SHOW CREATE TABLE users");
    $row = $columns->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . htmlspecialchars($row['Create Table']) . "</pre>";

} catch(PDOException $e) {
    echo "修改失敗: " . $e->getMessage();
    echo "<br>錯誤代碼: " . $e->getCode();
    echo "<br>錯誤信息: " . $e->getMessage();
}
?>
 