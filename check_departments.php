<?php
require_once 'config.php';

try {
    // 檢查資料表結構
    $stmt = $pdo->query("DESCRIBE DepartmentTransfer");
    echo "<h3>資料表結構：</h3>";
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";

    // 檢查系所資料
    $stmt = $pdo->query("SELECT department_name, department_intro FROM DepartmentTransfer LIMIT 5");
    echo "<h3>前5筆系所資料：</h3>";
    echo "<pre>";
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "</pre>";
} catch (PDOException $e) {
    echo "錯誤：" . $e->getMessage();
}
?> 