<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// 處理密碼修改
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'change_password' && isset($_POST['user_id']) && isset($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$new_password, $_POST['user_id']]);
    }
    // 處理角色變更
    else if ($_POST['action'] === 'change_role' && isset($_POST['user_id']) && isset($_POST['new_role'])) {
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$_POST['new_role'], $_POST['user_id']]);
    }
    // 處理新增管理員
    else if ($_POST['action'] === 'add_admin' && isset($_POST['student_id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (student_id, name, email, password, role) VALUES (?, ?, ?, ?, 'admin')");
        $stmt->execute([$_POST['student_id'], $_POST['name'], $_POST['email'], $password]);
    }
    // 處理刪除使用者
    else if ($_POST['action'] === 'delete_user' && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        
        // 確保使用者存在且不是管理員
        $check_stmt = $pdo->prepare("SELECT id, role FROM users WHERE id = ? LIMIT 1");
        $check_stmt->execute([$user_id]);
        $user = $check_stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $user['role'] !== 'admin') {
            $delete_stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $delete_stmt->execute([$user_id]);
        }
    }
    
    // 重新導向以避免重複提交
    header('Location: admin.php');
    exit;
}

// 撈取使用者資料
$stmt = $pdo->prepare("SELECT id, student_id, name, email, role FROM users ORDER BY role DESC, student_id ASC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
        color: white;
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
        color: white;
        background-color: rgb(115, 149, 179);
    }

    .btn-delete-user {
        padding: 0.5rem 1rem;
        background-color: rgb(255, 200, 200);
        color: rgb(220, 53, 69);
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }

    .btn-delete-user:hover {
        color: white;
        background-color: rgb(220, 53, 69);
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
</style>

<div class="container mt-2 pt-2">
    <!-- 管理員列表 -->
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

    <!-- 一般使用者列表 -->
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
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// 修改密碼 Modal
document.querySelectorAll('.btn-change-password').forEach(button => {
    button.addEventListener('click', function() {
        const userId = this.dataset.userId;
        const userName = this.dataset.userName;
        document.getElementById('passwordUserId').value = userId;
        document.getElementById('passwordUserName').textContent = userName;
    });
});

// 變更角色 Modal
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

// 刪除使用者 Modal
document.addEventListener('DOMContentLoaded', function() {
    const deleteUserModal = document.getElementById('deleteUserModal');
    const deleteUserForm = document.getElementById('deleteUserForm');
    
    if (deleteUserModal) {
        deleteUserModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');
            
            document.getElementById('deleteUserId').value = userId;
            document.getElementById('deleteUserName').textContent = userName;
        });
    }

    if (deleteUserForm) {
        deleteUserForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const userId = document.getElementById('deleteUserId').value;
            if (userId) {
                this.submit();
            }
        });
    }
});
</script>

<?php include 'footer.php'; ?> 