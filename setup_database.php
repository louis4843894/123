<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'root';
$password = '';

try {
    // 建立不帶資料庫名的連線
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 建立資料庫（如果不存在）
    $pdo->exec("CREATE DATABASE IF NOT EXISTS fju CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "資料庫建立成功！<br>";
    
    // 選擇資料庫
    $pdo->exec("USE fju");
    
    // 檢查表格是否存在
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "現有的表格：" . implode(", ", $tables) . "<br>";
    
    // 關閉外鍵檢查
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    // 刪除舊表格（如果存在）
    $pdo->exec("DROP TABLE IF EXISTS department_details");
    $pdo->exec("DROP TABLE IF EXISTS DepartmentTransfer");
    $pdo->exec("DROP TABLE IF EXISTS departments");
    echo "舊表格已刪除<br>";
    
    // 讀取建立表格的 SQL
    $sql = file_get_contents('create_tables.sql');
    $statements = explode(';', $sql);
    
    // 逐一執行每個 SQL 語句
    foreach($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "執行成功: " . substr($statement, 0, 50) . "...<br>";
            } catch (PDOException $e) {
                echo "執行失敗: " . substr($statement, 0, 50) . "...<br>";
                echo "錯誤信息: " . $e->getMessage() . "<br>";
                throw $e;
            }
        }
    }
    
    // 檢查表格是否已建立
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "建立後的表格：" . implode(", ", $tables) . "<br>";
    
    // 插入測試資料
    $sql = file_get_contents('insert_sample_data.sql');
    $statements = explode(';', $sql);
    
    foreach($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "資料插入成功: " . substr($statement, 0, 50) . "...<br>";
            } catch (PDOException $e) {
                echo "資料插入失敗: " . substr($statement, 0, 50) . "...<br>";
                echo "錯誤信息: " . $e->getMessage() . "<br>";
                throw $e;
            }
        }
    }
    
    // 開啟外鍵檢查
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // 檢查每個表格中的資料數量
    $tables = ['departments', 'DepartmentTransfer', 'department_details'];
    foreach ($tables as $table) {
        $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "<br>$table 表格中有 $count 筆資料";
    }
    
    // 顯示所有系所名稱
    echo "<br><br>系所列表：<br>";
    $departments = $pdo->query("SELECT name FROM departments")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($departments as $dept) {
        echo "- $dept<br>";
    }
    
    echo "<br>全部設定完成！您現在可以 <a href='index.php'>回到首頁</a> 查看結果。";
    
} catch(PDOException $e) {
    die("設定失敗: " . $e->getMessage());
}
?> 