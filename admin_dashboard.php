<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pageTitle = '管理者儀表板';
include 'header.php';

// 獲取一些基本統計數據
$stats = [
    'total_users' => $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'")->fetchColumn(),
    'total_admins' => $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn(),
    'total_departments' => $pdo->query("SELECT COUNT(*) FROM departments")->fetchColumn(),
];

?>

<style>
    .dashboard-card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: transform 0.2s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: #2c3e50;
    }

    .stat-label {
        color: #7f8c8d;
        font-size: 1rem;
    }

    .quick-action-btn {
        width: 100%;
        padding: 1rem;
        margin-bottom: 1rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s;
        background-color: rgb(148, 164, 189) !important;
        color: white !important;
    }

    .quick-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.3rem 0.5rem rgba(0, 0, 0, 0.1);
        background-color: rgb(133, 148, 171) !important;
    }

    .modal .btn-primary {
        background-color: rgb(148, 164, 189) !important;
        border-color: rgb(148, 164, 189) !important;
    }

    .modal .btn-primary:hover {
        background-color: rgb(133, 148, 171) !important;
        border-color: rgb(133, 148, 171) !important;
    }
</style>

<div class="container mt-2 pt-2">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-4">管理者儀表板</h2>
        </div>
    </div>

    <!-- 統計卡片 -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="dashboard-card text-center">
                <div class="stat-number"><?= $stats['total_users'] ?></div>
                <div class="stat-label">一般使用者數</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card text-center">
                <div class="stat-number"><?= $stats['total_admins'] ?></div>
                <div class="stat-label">管理員數</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="dashboard-card text-center">
                <div class="stat-number"><?= $stats['total_departments'] ?></div>
                <div class="stat-label">系所總數</div>
            </div>
        </div>
    </div>

    <!-- 快速操作按鈕 -->
    <div class="row">
        <div class="col-md-6">
            <div class="dashboard-card">
                <h4 class="mb-4">使用者管理</h4>
                <a href="admin.php" class="btn quick-action-btn mb-3">
                    <i class="bi bi-people-fill"></i> 管理使用者
                </a>
                <button type="button" class="btn quick-action-btn" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                    <i class="bi bi-person-plus-fill"></i> 新增管理員
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-card">
                <h4 class="mb-4">系統管理</h4>
                <a href="department_manage.php" class="btn quick-action-btn mb-3">
                    <i class="bi bi-building"></i> 系所管理
                </a>
                <a href="system_settings.php" class="btn quick-action-btn mb-3">
                    <i class="bi bi-gear-fill"></i> 系統設定
                </a>
            </div>
        </div>
    </div>
</div>

<!-- 新增管理員 Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增管理員</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="admin.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_admin">
                    <div class="mb-3">
                        <label class="form-label">學號</label>
                        <input type="text" name="student_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">姓名</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">密碼</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">新增</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?> 