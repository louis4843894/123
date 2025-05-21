<?php
session_start();
require_once 'config.php';

// ✅ 未登入轉跳登入頁
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ✅ 取得 cookie 中的系所清單
$selected_departments = isset($_COOKIE['compare_departments']) ? json_decode($_COOKIE['compare_departments'], true) : [];
if (!is_array($selected_departments))
    $selected_departments = [];

// ✅ 若從按鈕移除系所
if (isset($_POST['remove_department'])) {
    $dept_to_remove = (int)$_POST['remove_department'];
    $selected_departments = array_diff($selected_departments, [$dept_to_remove]);
    setcookie('compare_departments', json_encode(array_values($selected_departments)), time() + 86400 * 7, "/");
    header("Location: compare.php");
    exit();
}

// ✅ 撈系所詳細資料
if (empty($selected_departments)) {
    $departments = [];
} else {
    $placeholders = implode(',', array_fill(0, count($selected_departments), '?'));
    $sql = "SELECT d.*, 
            GROUP_CONCAT(DISTINCT et.exam_type_name) as exam_types,
            GROUP_CONCAT(DISTINCT dr.remark_text ORDER BY dr.remark_order) as remarks
            FROM department d
            LEFT JOIN departmentexamtype det ON d.department_id = det.department_id
            LEFT JOIN examtype et ON det.exam_type_id = et.exam_type_id
            LEFT JOIN departmentremark dr ON d.department_id = dr.department_id
            WHERE d.department_id IN ($placeholders)
            GROUP BY d.department_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($selected_departments);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
include 'header1.php';
?>


<div class="container">
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">系所比較</h4>
        </div>
        <div class="card-body">
            <?php if (empty($departments)): ?>
                <div class="empty-state">
                    <i class="bi bi-info-circle" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">目前尚未加入任何系所</h5>
                    <p class="text-muted">請從系所列表中加入要比較的項目</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>比較項目</th>
                                <?php foreach ($departments as $dept): ?>
                                    <th class="department-header">
                                        <?php echo htmlspecialchars($dept['department_name']); ?>
                                        <form method="POST" class="d-inline remove-btn">
                                            <button type="submit" name="remove_department"
                                                value="<?php echo $dept['department_id']; ?>"
                                                class="btn btn-danger btn-sm">
                                                <i class="bi bi-x"></i>
                                            </button>
                                        </form>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>考試類型</td>
                                <?php foreach ($departments as $dept): ?>
                                    <td><?php echo htmlspecialchars($dept['exam_types'] ?? '無'); ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td>備註</td>
                                <?php foreach ($departments as $dept): ?>
                                    <td>
                                        <?php 
                                        $remarks = explode(',', $dept['remarks'] ?? '');
                                        if (!empty($remarks[0])) {
                                            echo htmlspecialchars($remarks[0]);
                                        } else {
                                            echo '無';
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'footer.php'; ?>