<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pageTitle = '系統設定';
include 'header.php';
?>

<style>
    .settings-card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .settings-section {
        margin-bottom: 2rem;
    }

    .settings-title {
        color: #2c3e50;
        margin-bottom: 1.5rem;
    }
</style>

<div class="container mt-2 pt-2">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">系統設定</h2>
            
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- 系統基本設定 -->
            <div class="settings-card settings-section">
                <h4 class="settings-title">基本設定</h4>
                <form method="POST" action="update_settings.php">
                    <div class="mb-3">
                        <label class="form-label">系統名稱</label>
                        <input type="text" class="form-control" name="system_name" value="輔仁大學轉系系統">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系統描述</label>
                        <textarea class="form-control" name="system_description" rows="3">輔仁大學轉系資訊整合平台</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">儲存設定</button>
                </form>
            </div>

            <!-- 系統維護設定 -->
            <div class="settings-card settings-section">
                <h4 class="settings-title">系統維護</h4>
                <form method="POST" action="maintenance_mode.php">
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="maintenanceMode" name="maintenance_mode">
                            <label class="form-check-label" for="maintenanceMode">啟用維護模式</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">維護說明訊息</label>
                        <textarea class="form-control" name="maintenance_message" rows="3">系統正在進行維護，請稍後再試。</textarea>
                    </div>
                    <button type="submit" class="btn btn-warning">更新維護狀態</button>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <!-- 系統資訊 -->
            <div class="settings-card">
                <h4 class="settings-title">系統資訊</h4>
                <div class="mb-3">
                    <label class="form-label fw-bold">PHP 版本</label>
                    <p><?= PHP_VERSION ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">MySQL 版本</label>
                    <p><?= $pdo->query('select version()')->fetchColumn() ?></p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">伺服器資訊</label>
                    <p><?= $_SERVER['SERVER_SOFTWARE'] ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 