<?php
require_once 'config.php';

function executeQuery($pdo, $query, $title) {
    echo "<h4>{$title}</h4>";
    try {
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        if (count($results) > 0) {
            print_r($results);
        } else {
            echo "查詢結果為空（沒有找到資料）\n";
        }
        echo "</pre>";
        return $results;
    } catch(PDOException $e) {
        echo "<div style='color: red;'>錯誤: " . $e->getMessage() . "</div>";
        echo "<div>執行的SQL查詢: " . $query . "</div>";
        return false;
    }
}

try {
    echo "<h3>資料表結構檢查</h3>";
    
    // 檢查 departmenttransfer 表格結構
    echo "<h4>departmenttransfer 表格結構：</h4>";
    $stmt = $pdo->query("SHOW COLUMNS FROM departmenttransfer");
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
    
    // 檢查 departmentremarkssplit 表格結構
    echo "<h4>departmentremarkssplit 表格結構：</h4>";
    $stmt = $pdo->query("SHOW COLUMNS FROM departmentremarkssplit");
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
    
    // 檢查 departmenttransfer 的實際資料
    echo "<h4>departmenttransfer 表格資料：</h4>";
    $stmt = $pdo->query("SELECT * FROM departmenttransfer LIMIT 1");
    echo "<pre>";
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
    echo "</pre>";
    
    // 檢查 departmentremarkssplit 的實際資料
    echo "<h4>departmentremarkssplit 表格資料：</h4>";
    $stmt = $pdo->query("SELECT * FROM departmentremarkssplit LIMIT 1");
    echo "<pre>";
    print_r($stmt->fetch(PDO::FETCH_ASSOC));
    echo "</pre>";

} catch(PDOException $e) {
    echo "錯誤: " . $e->getMessage();
}
?> 