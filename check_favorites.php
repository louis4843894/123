<?php
session_start();
require_once 'config.php';

// 檢查用戶是否已登入
if (!isset($_SESSION['user_id'])) {
    echo "請先登入";
    exit;
}

try {
    // 檢查 favorites 表是否存在
    $check_table = $pdo->query("SHOW TABLES LIKE 'favorites'");
    if ($check_table->rowCount() == 0) {
        echo "favorites 表不存在！";
        exit;
    }

    // 顯示用戶的收藏記錄
    echo "<h3>收藏記錄：</h3>";
    $stmt = $pdo->prepare("SELECT * FROM favorites WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($favorites)) {
        echo "沒有收藏記錄";
    } else {
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>系所名稱</th><th>收藏時間</th></tr>";
        foreach ($favorites as $favorite) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($favorite['id']) . "</td>";
            echo "<td>" . htmlspecialchars($favorite['department_name']) . "</td>";
            echo "<td>" . htmlspecialchars($favorite['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // 顯示 favorites 表結構
    echo "<h3>favorites 表結構：</h3>";
    $columns = $pdo->query("SHOW CREATE TABLE favorites");
    $row = $columns->fetch(PDO::FETCH_ASSOC);
    echo "<pre>" . htmlspecialchars($row['Create Table']) . "</pre>";

} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
    echo "<br>錯誤代碼: " . $e->getCode();
    echo "<br>錯誤信息: " . $e->getMessage();
}
?> 