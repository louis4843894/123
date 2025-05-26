<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'fju';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("set names utf8mb4");

    // 新增 department_intro 欄位
    try {
        $sql = "ALTER TABLE DepartmentTransfer ADD COLUMN department_intro TEXT DEFAULT '暫無簡介' AFTER department_name";
        $pdo->exec($sql);
    } catch(PDOException $columnError) {
        // 如果錯誤是因為欄位已存在（錯誤代碼 42S21），則忽略這個錯誤
        if($columnError->getCode() !== '42S21') {
            throw $columnError;
        }
    }

    // 檢查系統維護模式
    function isMaintenanceMode() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT maintenance_mode FROM system_settings WHERE id = 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['maintenance_mode'] == 1;
        } catch (PDOException $e) {
            return false;
        }
    }

    // 檢查當前頁面是否為維護頁面或登入頁面
    $current_page = basename($_SERVER['PHP_SELF']);
    $allowed_pages = ['maintenance.php', 'login.php', 'create_system_settings.php', 'system_settings.php'];
    
    if (isMaintenanceMode() && !in_array($current_page, $allowed_pages)) {
        // 檢查用戶是否已登入且是管理員
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: maintenance.php');
            exit;
        }
    }

} catch(PDOException $e) {
    // 只有在真正的連接錯誤時才顯示錯誤信息
    die("資料庫連接失敗: " . $e->getMessage());
}
?> 