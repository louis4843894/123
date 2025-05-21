<?php
require_once 'config.php';

try {
    // 檢查 DepartmentTransfer 表格的內容
    $stmt = $pdo->query("SELECT department_name FROM DepartmentTransfer");
    echo "<h3>DepartmentTransfer 表格中的系所：</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo htmlspecialchars($row['department_name']) . "\n";
    }
    echo "</pre>";

    // 檢查 departments 表格的內容
    $stmt = $pdo->query("SELECT name, introduction, features, career, requirements FROM departments");
    echo "<h3>departments 表格中的系所：</h3>";
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "系所名稱：" . htmlspecialchars($row['name']) . "\n";
        echo "簡介：" . htmlspecialchars($row['introduction']) . "\n";
        echo "特色：" . htmlspecialchars($row['features']) . "\n";
        echo "未來發展：" . htmlspecialchars($row['career']) . "\n";
        echo "要求：" . htmlspecialchars($row['requirements']) . "\n";
        echo "------------------------\n";
    }
    echo "</pre>";

} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
}
?> 