<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// ------------------------------------------------------------
// 1. 處理「使用者管理」相關 POST （原本就有的）
// ------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // 修改使用者密碼、變更角色、新增管理員、刪除使用者（原本程式）
    if ($_POST['action'] === 'change_password' && isset($_POST['user_id'], $_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_password, $_POST['user_id']]);
    }
    else if ($_POST['action'] === 'change_role' && isset($_POST['user_id'], $_POST['new_role'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$_POST['new_role'], $_POST['user_id']]);
    }
    else if ($_POST['action'] === 'add_admin' && isset($_POST['student_id'], $_POST['name'], $_POST['email'], $_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (student_id, name, email, password, role) VALUES (?, ?, ?, ?, 'admin')");
        $stmt->execute([$_POST['student_id'], $_POST['name'], $_POST['email'], $password]);
    }
    else if ($_POST['action'] === 'delete_user' && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        $check_stmt = $pdo->prepare("SELECT id, role FROM users WHERE id = ? LIMIT 1");
        $check_stmt->execute([$user_id]);
        $user = $check_stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && $user['role'] !== 'admin') {
            $delete_stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $delete_stmt->execute([$user_id]);
        }
    }

    // ------------------------------------------------------------
    // 2. 處理「轉系時程表」的 POST: add / edit / delete
    // ------------------------------------------------------------
    else if ($_POST['action'] === 'add_transfer' 
             && isset($_POST['date'], $_POST['event'], $_POST['time'], $_POST['location'])) {
        $stmt = $pdo->prepare("INSERT INTO transfer_schedule (`date`,`event`,`time`,`location`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['date'], $_POST['event'], $_POST['time'], $_POST['location']]);
    }
    else if ($_POST['action'] === 'edit_transfer' 
             && isset($_POST['id'], $_POST['date'], $_POST['event'], $_POST['time'], $_POST['location'])) {
        $stmt = $pdo->prepare(
            "UPDATE transfer_schedule 
             SET `date` = ?, `event` = ?, `time` = ?, `location` = ? 
             WHERE `id` = ?"
        );
        $stmt->execute([$_POST['date'], $_POST['event'], $_POST['time'], $_POST['location'], $_POST['id']]);
    }
    else if ($_POST['action'] === 'delete_transfer' && isset($_POST['id'])) {
        $delId = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM transfer_schedule WHERE `id` = ?");
        $stmt->execute([$delId]);
    }

    // ------------------------------------------------------------
    // 3. 處理「面筆試時程表」的 POST: add / edit / delete
    // ------------------------------------------------------------
    else if ($_POST['action'] === 'add_exam' 
             && isset($_POST['date'], $_POST['event'], $_POST['type'], $_POST['time'], $_POST['location'])) {
        $stmt = $pdo->prepare(
            "INSERT INTO time_table (`date`,`event`,`type`,`time`,`location`) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$_POST['date'], $_POST['event'], $_POST['type'], $_POST['time'], $_POST['location']]);
    }
    else if ($_POST['action'] === 'edit_exam' 
             && isset($_POST['id'], $_POST['date'], $_POST['event'], $_POST['type'], $_POST['time'], $_POST['location'])) {
        $stmt = $pdo->prepare(
            "UPDATE time_table 
             SET `date` = ?, `event` = ?, `type` = ?, `time` = ?, `location` = ? 
             WHERE `id` = ?"
        );
        $stmt->execute([
            $_POST['date'], $_POST['event'], $_POST['type'], $_POST['time'], $_POST['location'], $_POST['id']
        ]);
    }
    else if ($_POST['action'] === 'delete_exam' && isset($_POST['id'])) {
        $delId = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM time_table WHERE `id` = ?");
        $stmt->execute([$delId]);
    }

    // 重導回避免重複提交
    header('Location: admin.php');
    exit;
}

// ------------------------------------------------------------
// 撈取「使用者資料」並排序（原本程式）
$stmt = $pdo->prepare("SELECT id, student_id, name, email, role FROM users ORDER BY role DESC, student_id ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ------------------------------------------------------------
// 撈取「轉系時程表」所有資料（按日期排序）
$transferStmt = $pdo->query("SELECT * FROM transfer_schedule ORDER BY `date` ASC, `time` ASC");
$transfers = $transferStmt->fetchAll(PDO::FETCH_ASSOC);

// 撈取「面筆試時程表」所有資料（按日期 + event 排序）
$examStmt = $pdo->query("SELECT * FROM time_table ORDER BY `date` ASC, `event` ASC");
$exams = $examStmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = '管理人員介面';
include 'header.php';
?>

<style>
    .admin-section {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    .btn-change-password {
        padding: 0.5rem 1rem;
        background-color: rgb(87, 148, 100);
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }
    .btn-change-password:hover {
        background-color: rgb(100, 170, 115);
    }
    .btn-change-role {
        padding: 0.5rem 1rem;
        background-color: rgb(87, 112, 136);
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }
    .btn-change-role:hover {
        background-color: rgb(115, 149, 179);
    }
    .btn-delete-user, .btn-delete-schedule {
        padding: 0.5rem 1rem;
        background-color: rgb(255, 200, 200);
        color: rgb(220, 53, 69);
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }
    .btn-delete-user:hover, .btn-delete-schedule:hover {
        background-color: rgb(220, 53, 69);
        color: white;
    }
    .modal-content {
        border-radius: 0.5rem;
    }
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
    .btn-schedule {
        padding: 0.4rem 0.8rem;
        border-radius: 0.3rem;
        border: none;
        color: white;
    }
    .btn-add-schedule { background-color: rgb(40, 167, 69); }
    .btn-add-schedule:hover { background-color: rgb(60, 180, 90); }
    .btn-edit-schedule { background-color: rgb(23, 162, 184); }
    .btn-edit-schedule:hover { background-color: rgb(24, 190, 200); }
</style>

<div class="container mt-2 pt-2">
    <!-- 管理員列表（原本） -->
    <div class="admin-section">
        <h3 class="mb-4">管理員帳號</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>學號</th>
                        <th>姓名</th>
                        <th>Email</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php if ($user['role'] === 'admin'): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['student_id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <button type="button" class="btn btn-change-password" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['name']) ?>">
                                    修改密碼
                                </button>
                                <button type="button" class="btn btn-change-role" data-bs-toggle="modal" data-bs-target="#changeRoleModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['name']) ?>" data-current-role="admin">
                                    設為一般帳號
                                </button>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 一般使用者列表（原本） -->
    <div class="admin-section">
        <h3 class="mb-4">一般使用者帳號</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>學號</th>
                        <th>姓名</th>
                        <th>Email</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <?php if ($user['role'] !== 'admin'): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['student_id']) ?></td>
                            <td><?= htmlspecialchars($user['name']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td>
                                <button type="button" class="btn btn-change-password" data-bs-toggle="modal" data-bs-target="#changePasswordModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['name']) ?>">
                                    修改密碼
                                </button>
                                <button type="button" class="btn btn-change-role" data-bs-toggle="modal" data-bs-target="#changeRoleModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['name']) ?>" data-current-role="user">
                                    設為管理員
                                </button>
                                <button type="button" class="btn btn-delete-user" data-bs-toggle="modal" data-bs-target="#deleteUserModal" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['name']) ?>">
                                    刪除帳號
                                </button>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========= 轉系時程表管理 ========= -->
    <div class="admin-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>轉系時程表管理</h3>
            <button type="button" class="btn btn-schedule btn-add-schedule" data-bs-toggle="modal" data-bs-target="#addTransferModal">
                新增轉系時程
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>日期</th>
                        <th>活動</th>
                        <th>時間</th>
                        <th>地點</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transfers)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3">目前沒有任何轉系時程</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transfers as $ts): ?>
                        <tr>
                            <td><?= htmlspecialchars($ts['date']) ?></td>
                            <td><?= htmlspecialchars($ts['event']) ?></td>
                            <td><?= htmlspecialchars($ts['time']) ?></td>
                            <td><?= htmlspecialchars($ts['location']) ?></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-schedule btn-edit-schedule"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTransferModal"
                                    data-id="<?= $ts['id'] ?>"
                                    data-date="<?= htmlspecialchars($ts['date']) ?>"
                                    data-event="<?= htmlspecialchars($ts['event']) ?>"
                                    data-time="<?= htmlspecialchars($ts['time']) ?>"
                                    data-location="<?= htmlspecialchars($ts['location']) ?>"
                                >
                                    編輯
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-delete-schedule"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteTransferModal"
                                    data-id="<?= $ts['id'] ?>"
                                    data-event="<?= htmlspecialchars($ts['event']) ?>"
                                >
                                    刪除
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ========= 面筆試時程表管理 ========= -->
    <div class="admin-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>面筆試時程表管理</h3>
            <button type="button" class="btn btn-schedule btn-add-schedule" data-bs-toggle="modal" data-bs-target="#addExamModal">
                新增面筆試時程
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th>日期</th>
                        <th>系所</th>
                        <th>種類</th>
                        <th>時間</th>
                        <th>地點</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($exams)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3">目前沒有任何面筆試時程</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($exams as $ex): ?>
                        <tr>
                            <td><?= htmlspecialchars($ex['date']) ?></td>
                            <td><?= htmlspecialchars($ex['event']) ?></td>
                            <td><?= htmlspecialchars($ex['type']) ?></td>
                            <td><?= htmlspecialchars($ex['time']) ?></td>
                            <td><?= htmlspecialchars($ex['location']) ?></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-schedule btn-edit-schedule"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editExamModal"
                                    data-id="<?= $ex['id'] ?>"
                                    data-date="<?= htmlspecialchars($ex['date']) ?>"
                                    data-event="<?= htmlspecialchars($ex['event']) ?>"
                                    data-type="<?= htmlspecialchars($ex['type']) ?>"
                                    data-time="<?= htmlspecialchars($ex['time']) ?>"
                                    data-location="<?= htmlspecialchars($ex['location']) ?>"
                                >
                                    編輯
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-delete-schedule"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteExamModal"
                                    data-id="<?= $ex['id'] ?>"
                                    data-event="<?= htmlspecialchars($ex['event']) ?>"
                                >
                                    刪除
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========== 使用者管理相關 Modal（原本就有的） ========== -->
<!-- 修改密碼 Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">修改密碼</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="change_password">
                    <input type="hidden" name="user_id" id="passwordUserId">
                    <p>為 <span id="passwordUserName"></span> 設定新密碼：</p>
                    <div class="mb-3">
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">確認修改</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 變更角色 Modal -->
<div class="modal fade" id="changeRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">變更角色</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="change_role">
                    <input type="hidden" name="user_id" id="roleUserId">
                    <input type="hidden" name="new_role" id="newRole">
                    <p>確定要將 <span id="roleUserName"></span> 的角色變更為 <span id="roleChangeText"></span> 嗎？</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">確認變更</button>
                </div>
            </form>
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
            <form method="POST">
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

<!-- 刪除使用者 Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">刪除使用者</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="deleteUserForm" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete_user">
                    <input type="hidden" name="user_id" id="deleteUserId">
                    <p>確定要刪除 <span id="deleteUserName"></span> 的帳號嗎？此操作無法復原。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">確認刪除</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========= 轉系時程表相關 Modal ========= -->
<!-- 新增轉系時程 Modal -->
<div class="modal fade" id="addTransferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增轉系時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_transfer">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text" name="date" class="form-control" placeholder="YYYY-MM-DD" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">活動</label>
                        <input type="text" name="event" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text" name="time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text" name="location" class="form-control" required>
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

<!-- 編輯轉系時程 Modal -->
<div class="modal fade" id="editTransferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">編輯轉系時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_transfer">
                    <input type="hidden" name="id" id="editTransferId">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text" name="date" class="form-control" id="editTransferDate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">活動</label>
                        <input type="text" name="event" class="form-control" id="editTransferEvent" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text" name="time" class="form-control" id="editTransferTime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text" name="location" class="form-control" id="editTransferLocation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 刪除轉系時程 Modal -->
<div class="modal fade" id="deleteTransferModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">刪除轉系時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="deleteTransferForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete_transfer">
                    <input type="hidden" name="id" id="deleteTransferId">
                    <p>確定要刪除「<span id="deleteTransferEvent"></span>」這筆時程嗎？此操作無法復原。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">刪除</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ========== 面筆試時程表相關 Modal ========== -->
<!-- 新增面筆試時程 Modal -->
<div class="modal fade" id="addExamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增面筆試時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add_exam">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text" name="date" class="form-control" placeholder="YYYY-MM-DD" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所</label>
                        <input type="text" name="event" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">種類</label>
                        <select name="type" class="form-select" required>
                            <option value="筆試">筆試</option>
                            <option value="面試">面試</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text" name="time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text" name="location" class="form-control" required>
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

<!-- 編輯面筆試時程 Modal -->
<div class="modal fade" id="editExamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">編輯面筆試時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit_exam">
                    <input type="hidden" name="id" id="editExamId">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text" name="date" class="form-control" id="editExamDate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所</label>
                        <input type="text" name="event" class="form-control" id="editExamEvent" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">種類</label>
                        <select name="type" class="form-select" id="editExamType" required>
                            <option value="筆試">筆試</option>
                            <option value="面試">面試</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text" name="time" class="form-control" id="editExamTime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text" name="location" class="form-control" id="editExamLocation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">更新</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 刪除面筆試時程 Modal -->
<div class="modal fade" id="deleteExamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">刪除面筆試時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="deleteExamForm">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete_exam">
                    <input type="hidden" name="id" id="deleteExamId">
                    <p>確定要刪除「<span id="deleteExamEvent"></span>」這筆時程嗎？此操作無法復原。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">刪除</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// --------------------------
// 使用者管理 Modal 初始
// --------------------------
document.querySelectorAll('.btn-change-password').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.dataset.userId;
        const userName = this.dataset.userName;
        document.getElementById('passwordUserId').value = userId;
        document.getElementById('passwordUserName').textContent = userName;
    });
});
document.querySelectorAll('.btn-change-role').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.dataset.userId;
        const userName = this.dataset.userName;
        const currentRole = this.dataset.currentRole;
        const newRole = currentRole === 'admin' ? 'user' : 'admin';
        const roleText = newRole === 'admin' ? '管理員' : '一般使用者';
        document.getElementById('roleUserId').value = userId;
        document.getElementById('newRole').value = newRole;
        document.getElementById('roleUserName').textContent = userName;
        document.getElementById('roleChangeText').textContent = roleText;
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const deleteUserModal = document.getElementById('deleteUserModal');
    const deleteUserForm  = document.getElementById('deleteUserForm');
    if (deleteUserModal) {
        deleteUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            document.getElementById('deleteUserId').value     = userId;
            document.getElementById('deleteUserName').textContent = userName;
        });
    }
    if (deleteUserForm) {
        deleteUserForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const userId = document.getElementById('deleteUserId').value;
            if (userId) this.submit();
        });
    }
});

// --------------------------
// 轉系時程表管理 Modal 初始
// --------------------------
document.querySelectorAll('#editTransferModal').forEach(modalEl => {
    modalEl.addEventListener('show.bs.modal', function(event) {
        const button     = event.relatedTarget;
        document.getElementById('editTransferId').value       = button.getAttribute('data-id');
        document.getElementById('editTransferDate').value     = button.getAttribute('data-date');
        document.getElementById('editTransferEvent').value    = button.getAttribute('data-event');
        document.getElementById('editTransferTime').value     = button.getAttribute('data-time');
        document.getElementById('editTransferLocation').value = button.getAttribute('data-location');
    });
});
document.querySelectorAll('#deleteTransferModal').forEach(modalEl => {
    modalEl.addEventListener('show.bs.modal', function(event) {
        const button        = event.relatedTarget;
        document.getElementById('deleteTransferId').value    = button.getAttribute('data-id');
        document.getElementById('deleteTransferEvent').textContent = button.getAttribute('data-event');
    });
});

// --------------------------
// 面筆試時程表管理 Modal 初始
// --------------------------
document.querySelectorAll('#editExamModal').forEach(modalEl => {
    modalEl.addEventListener('show.bs.modal', function(event) {
        const button     = event.relatedTarget;
        document.getElementById('editExamId').value       = button.getAttribute('data-id');
        document.getElementById('editExamDate').value     = button.getAttribute('data-date');
        document.getElementById('editExamEvent').value    = button.getAttribute('data-event');
        document.getElementById('editExamType').value     = button.getAttribute('data-type');
        document.getElementById('editExamTime').value     = button.getAttribute('data-time');
        document.getElementById('editExamLocation').value = button.getAttribute('data-location');
    });
});
document.querySelectorAll('#deleteExamModal').forEach(modalEl => {
    modalEl.addEventListener('show.bs.modal', function(event) {
        const button        = event.relatedTarget;
        document.getElementById('deleteExamId').value    = button.getAttribute('data-id');
        document.getElementById('deleteExamEvent').textContent = button.getAttribute('data-event');
    });
});
</script>

<?php include 'footer.php'; ?>
