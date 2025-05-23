<?php
require_once 'config.php';

try {
    echo "<h3>檢查 departmenttransfer 表格</h3>";
    
    // 方法1：使用 DESCRIBE
    echo "<h4>方法1 - DESCRIBE：</h4>";
    $stmt = $pdo->query("DESCRIBE departmenttransfer");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // 方法2：使用 SHOW COLUMNS
    echo "<h4>方法2 - SHOW COLUMNS：</h4>";
    $stmt = $pdo->query("SHOW COLUMNS FROM departmenttransfer");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($columns);
    echo "</pre>";
    
    // 方法3：查詢一筆資料看欄位名稱
    echo "<h4>方法3 - 實際資料欄位：</h4>";
    $stmt = $pdo->query("SELECT * FROM departmenttransfer LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        echo "欄位名稱：<br>";
        echo "<pre>";
        print_r(array_keys($row));
        echo "</pre>";
        
        echo "資料範例：<br>";
        echo "<pre>";
        print_r($row);
        echo "</pre>";
    } else {
        echo "表格中沒有資料<br>";
    }
    
} catch(PDOException $e) {
    echo "<div style='color: red;'>錯誤：" . $e->getMessage() . "</div>";
    
    // 如果表格不存在，顯示建議的建表語句
    if (strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "<h4>建議：需要先建立表格，可以使用以下SQL：</h4>";
        echo "<pre>";
        echo "CREATE TABLE departmenttransfer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(255),
    second_grade_quota INT,
    third_grade_quota INT,
    fourth_grade_quota INT,
    exam_subjects TEXT,
    data_review_ratio TEXT
);";
        echo "</pre>";
    }
}
?> 