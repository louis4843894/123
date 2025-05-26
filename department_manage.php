<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pageTitle = '系所管理';
include 'header.php';

// 獲取系所資料
$stmt = $pdo->query("
    SELECT 
        d.id,
        d.name,
        d.college_name,
        dt.year_2_enrollment,
        dt.year_3_enrollment,
        dt.year_4_enrollment,
        d.url,
        d.intro,
        d.careers,
        dt.exam_subjects,
        dt.data_review_ratio,
        dt.notes
    FROM departments d
    LEFT JOIN DepartmentTransfer dt ON d.name = dt.department_name
    ORDER BY d.name ASC
");
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .department-card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .enrollment-info {
        font-size: 0.9rem;
        color: #666;
    }

    .url-link {
        color: #0d6efd;
        text-decoration: none;
    }

    .url-link:hover {
        text-decoration: underline;
    }
</style>

<div class="container mt-2 pt-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">系所管理</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="bi bi-plus-lg"></i> 新增系所
            </button>
        </div>
    </div>

    <div class="department-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>系所名稱</th>
                        <th>所屬學院</th>
                        <th>招生名額</th>
                        <th>系所網站</th>
                        <th>轉系資訊</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $dept): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($dept['name']) ?>
                            <button type="button" class="btn btn-sm btn-link p-0 ms-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#introModal"
                                    data-dept-id="<?= $dept['id'] ?>"
                                    data-dept-name="<?= htmlspecialchars($dept['name']) ?>"
                                    data-dept-intro="<?= htmlspecialchars($dept['intro'] ?? '') ?>"
                                    data-dept-careers="<?= htmlspecialchars($dept['careers'] ?? '') ?>">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        </td>
                        <td><?= htmlspecialchars($dept['college_name'] ?? '未設定') ?></td>
                        <td>
                            <div class="enrollment-info">
                                二年級: <?= htmlspecialchars($dept['year_2_enrollment'] ?? '0') ?><br>
                                三年級: <?= htmlspecialchars($dept['year_3_enrollment'] ?? '0') ?><br>
                                四年級: <?= htmlspecialchars($dept['year_4_enrollment'] ?? '0') ?>
                            </div>
                        </td>
                        <td>
                            <?php if (!empty($dept['url'])): ?>
                                <a href="<?= htmlspecialchars($dept['url']) ?>" class="url-link" target="_blank">
                                    <i class="bi bi-box-arrow-up-right"></i> 開啟網站
                                </a>
                            <?php else: ?>
                                <span class="text-muted">未設定</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info text-white" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#transferRequirementsModal"
                                    data-dept-id="<?= $dept['id'] ?>"
                                    data-exam-subjects="<?= htmlspecialchars($dept['exam_subjects'] ?? '') ?>"
                                    data-data-review-ratio="<?= htmlspecialchars($dept['data_review_ratio'] ?? '') ?>">
                                查看要求
                            </button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editDepartmentModal"
                                    data-dept-id="<?= $dept['id'] ?>">
                                編輯
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteDepartmentModal"
                                    data-dept-id="<?= $dept['id'] ?>"
                                    data-dept-name="<?= htmlspecialchars($dept['name']) ?>">
                                刪除
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 新增系所 Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增系所</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_department.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">系所名稱</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">所屬學院</label>
                            <input type="text" name="college_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">二年級招生名額</label>
                            <input type="number" name="year_2_enrollment" class="form-control" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">三年級招生名額</label>
                            <input type="number" name="year_3_enrollment" class="form-control" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">四年級招生名額</label>
                            <input type="number" name="year_4_enrollment" class="form-control" required min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所網站</label>
                        <input type="url" name="url" class="form-control" placeholder="https://">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所簡介</label>
                        <textarea name="intro" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">未來發展</label>
                        <textarea name="careers" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">考試科目</label>
                        <textarea name="exam_subjects" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">資料準備</label>
                        <textarea name="data_review_ratio" class="form-control" rows="3"></textarea>
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

<!-- 編輯系所 Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">編輯系所</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_department.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editDeptId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">系所名稱</label>
                            <input type="text" name="name" id="editDeptName" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">所屬學院</label>
                            <input type="text" name="college_name" id="editDeptCollege" class="form-control" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">二年級招生名額</label>
                            <input type="number" name="year_2_enrollment" id="editDeptYear2" class="form-control" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">三年級招生名額</label>
                            <input type="number" name="year_3_enrollment" id="editDeptYear3" class="form-control" required min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">四年級招生名額</label>
                            <input type="number" name="year_4_enrollment" id="editDeptYear4" class="form-control" required min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所網站</label>
                        <input type="url" name="url" id="editDeptUrl" class="form-control" placeholder="https://">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所簡介</label>
                        <textarea name="intro" id="editDeptIntro" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">未來發展</label>
                        <textarea name="careers" id="editDeptCareers" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">考試科目</label>
                        <textarea name="exam_subjects" id="editDeptExamSubjects" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">資料審查比例</label>
                        <textarea name="data_review_ratio" id="editDeptDataReviewRatio" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">備註</label>
                        <textarea name="notes" id="editDeptNotes" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">儲存變更</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 系所簡介 Modal -->
<div class="modal fade" id="introModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">系所資訊</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">系所簡介</h6>
                <div id="deptIntroContent" class="mb-4"></div>
                <h6 class="mb-3">未來發展</h6>
                <div id="deptCareersContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- 轉系要求 Modal -->
<div class="modal fade" id="transferRequirementsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">轉系要求</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6 class="mb-3">考試科目</h6>
                <div id="examSubjectsContent" class="mb-4"></div>
                <h6 class="mb-3">資料準備</h6>
                <div id="dataReviewRatioContent"></div>
            </div>
        </div>
    </div>
</div>

<!-- 刪除確認 Modal -->
<div class="modal fade" id="deleteDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">確認刪除</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_department.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteDeptId">
                    <p>確定要刪除 <span id="deleteDeptName" class="fw-bold"></span> 嗎？此操作無法復原。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">確認刪除</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// 編輯系所
document.querySelectorAll('[data-bs-target="#editDepartmentModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const deptId = this.dataset.deptId;
        document.getElementById('editDeptId').value = deptId;
        
        // 使用 AJAX 獲取系所資料
        fetch(`process_department.php?action=get_department&id=${deptId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const dept = data.data;
                    document.getElementById('editDeptName').value = dept.name;
                    document.getElementById('editDeptCollege').value = dept.college_name;
                    document.getElementById('editDeptYear2').value = dept.year_2_enrollment;
                    document.getElementById('editDeptYear3').value = dept.year_3_enrollment;
                    document.getElementById('editDeptYear4').value = dept.year_4_enrollment;
                    document.getElementById('editDeptUrl').value = dept.url;
                    document.getElementById('editDeptIntro').value = dept.intro;
                    document.getElementById('editDeptCareers').value = dept.careers;
                    document.getElementById('editDeptExamSubjects').value = dept.exam_subjects;
                    document.getElementById('editDeptDataReviewRatio').value = dept.data_review_ratio;
                    document.getElementById('editDeptNotes').value = dept.notes || '';
                } else {
                    alert('獲取系所資料失敗：' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('獲取系所資料時發生錯誤');
            });
    });
});

// 查看系所簡介
document.querySelectorAll('[data-bs-target="#introModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const intro = this.dataset.deptIntro;
        const careers = this.dataset.deptCareers;
        document.getElementById('deptIntroContent').innerHTML = intro ? `<p>${intro}</p>` : '<p class="text-muted">暫無簡介</p>';
        document.getElementById('deptCareersContent').innerHTML = careers ? `<p>${careers}</p>` : '<p class="text-muted">暫無資料</p>';
    });
});

// 查看轉系要求
document.querySelectorAll('[data-bs-target="#transferRequirementsModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const examSubjects = this.dataset.examSubjects;
        const dataReviewRatio = this.dataset.dataReviewRatio;
        document.getElementById('examSubjectsContent').innerHTML = examSubjects ? `<p>${examSubjects}</p>` : '<p class="text-muted">暫無資料</p>';
        document.getElementById('dataReviewRatioContent').innerHTML = dataReviewRatio ? `<p>${dataReviewRatio}</p>` : '<p class="text-muted">暫無資料</p>';
    });
});

// 刪除系所
document.querySelectorAll('[data-bs-target="#deleteDepartmentModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const deptId = this.dataset.deptId;
        const deptName = this.dataset.deptName;
        document.getElementById('deleteDeptId').value = deptId;
        document.getElementById('deleteDeptName').textContent = deptName;
    });
});

// 顯示成功或錯誤訊息
<?php if (isset($_SESSION['success_message'])): ?>
    alert('<?= htmlspecialchars($_SESSION['success_message']) ?>');
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    alert('<?= htmlspecialchars($_SESSION['error_message']) ?>');
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>
</script>

<?php include 'footer.php'; ?> 