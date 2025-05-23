<?php
session_start();
require_once 'config.php';

// 檢查用戶是否登入
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pageTitle = '系所比較';
include 'header.php';

// 獲取要比較的系所
$departments = [];
if (isset($_GET['departments'])) {
    $dept_names = explode(',', $_GET['departments']);
    if (empty($dept_names) || $dept_names[0] === '') {
        header('Location: index.php');
        exit;
    }
    
    $placeholders = str_repeat('?,', count($dept_names) - 1) . '?';
    
    // 使用正確的欄位名稱
    $stmt = $pdo->prepare("
        SELECT 
            dt.department_name,
            drs.remark1,
            drs.remark2,
            drs.remark3,
            dt.year_2_enrollment,
            dt.year_3_enrollment,
            dt.year_4_enrollment,
            dt.exam_subjects,
            dt.data_review_ratio,
            d.url
        FROM departmenttransfer dt
        LEFT JOIN departmentremarkssplit drs ON dt.department_name = drs.department_name
        LEFT JOIN departments d ON dt.department_name = d.name
        WHERE dt.department_name IN ($placeholders)
    ");
    
    $stmt->execute($dept_names);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // 如果沒有找到任何系所，跳回首頁
    if (empty($departments)) {
        header('Location: index.php');
        exit;
    }
} else {
    // 如果沒有 departments 參數，跳回首頁
    header('Location: index.php');
    exit;
}
?>

<div class="container mt-5 pt-5">
    <h2 class="mb-4">系所比較</h2>
    
    <div class="table-responsive">
        <?php if (count($departments) >= 2): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>比較項目</th>
                    <?php foreach ($departments as $dept): ?>
                        <th class="text-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-dark"><?= htmlspecialchars($dept['department_name']) ?></span>
                                <button class="btn btn-sm btn-danger remove-dept ms-2" 
                                        data-dept="<?= htmlspecialchars($dept['department_name']) ?>">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <!-- 轉系標準 -->
                <tr>
                    <td class="bg-light">轉系標準</td>
                    <?php foreach ($departments as $dept): ?>
                        <td>
                            <?php if (!empty($dept['remark1']) || !empty($dept['remark2']) || !empty($dept['remark3'])): ?>
                                <?php if (!empty($dept['remark1'])): ?>
                                    <p><?= nl2br(htmlspecialchars($dept['remark1'])) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($dept['remark2'])): ?>
                                    <p><?= nl2br(htmlspecialchars($dept['remark2'])) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($dept['remark3'])): ?>
                                    <p><?= nl2br(htmlspecialchars($dept['remark3'])) ?></p>
                                <?php endif; ?>
                            <?php else: ?>
                                暫無資料
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                
                <!-- 年級名額 -->
                <tr>
                    <td class="bg-light">年級名額</td>
                    <?php foreach ($departments as $dept): ?>
                        <td>
                            <p><strong>二年級：</strong><?= htmlspecialchars($dept['year_2_enrollment'] ?? '暫無資料') ?></p>
                            <p><strong>三年級：</strong><?= htmlspecialchars($dept['year_3_enrollment'] ?? '暫無資料') ?></p>
                            <p><strong>四年級：</strong><?= htmlspecialchars($dept['year_4_enrollment'] ?? '暫無資料') ?></p>
                        </td>
                    <?php endforeach; ?>
                </tr>
                
                <!-- 檢測內容 -->
                <tr>
                    <td class="bg-light">檢測內容</td>
                    <?php foreach ($departments as $dept): ?>
                        <td><?= nl2br(htmlspecialchars($dept['exam_subjects'] ?? '暫無資料')) ?></td>
                    <?php endforeach; ?>
                </tr>
                
                <!-- 準備資料 -->
                <tr>
                    <td class="bg-light">準備資料</td>
                    <?php foreach ($departments as $dept): ?>
                        <td><?= nl2br(htmlspecialchars($dept['data_review_ratio'] ?? '暫無資料')) ?></td>
                    <?php endforeach; ?>
                </tr>

                <!-- 系所網站 -->
                <tr>
                    <td class="bg-light">系所網站</td>
                    <?php foreach ($departments as $dept): ?>
                        <td>
                            <?php if (!empty($dept['url'])): ?>
                                <a href="<?= htmlspecialchars($dept['url']) ?>" target="_blank" class="btn btn-info">
                                    <i class="bi bi-link"></i> 前往系所網站
                                </a>
                            <?php else: ?>
                                暫無資料
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 從 localStorage 獲取已選擇的系所
    const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
    
    // 如果 URL 中沒有系所參數，則使用 localStorage 中的系所列表
    if (!window.location.search.includes('departments=')) {
        if (compareList.length >= 2) {
            window.location.href = 'compare.php?departments=' + compareList.join(',');
        } else {
            // 如果選擇的系所少於2個，顯示提示並返回首頁
            alert('請至少選擇兩個系所進行比較！');
            window.location.href = 'index.php';
        }
    }
    
    // 移除系所的功能
    document.querySelectorAll('.remove-dept').forEach(button => {
        button.addEventListener('click', function() {
            const deptName = this.dataset.dept;
            const newCompareList = compareList.filter(dept => dept !== deptName);
            localStorage.setItem('compare_departments', JSON.stringify(newCompareList));
            
            // 找到要移除的系所列
            const columnToRemove = this.closest('th');
            const columnIndex = Array.from(columnToRemove.parentElement.children).indexOf(columnToRemove);
            
            // 移除表頭的系所
            columnToRemove.remove();
            
            // 移除每一行對應的資料欄
            document.querySelectorAll('tbody tr').forEach(row => {
                row.children[columnIndex].remove();
            });

            // 檢查剩餘的系所數量
            const remainingColumns = document.querySelectorAll('thead th').length - 1; // 減去"比較項目"那一列
            if (remainingColumns === 0) {
                // 如果沒有剩餘系所，清空 localStorage 並返回首頁
                localStorage.setItem('compare_departments', '[]');
                window.location.href = 'index.php';
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>