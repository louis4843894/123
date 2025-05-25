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
    $dept_names = array_filter(explode(',', $_GET['departments']));
    
    if (count($dept_names) < 2) {
        echo '<div class="alert alert-warning mt-5">請至少選擇兩個系所進行比較</div>';
        echo '<div class="text-center mt-3"><a href="index.php" class="btn btn-primary">返回首頁</a></div>';
        include 'footer.php';
        exit;
    }
    
    try {
        // 先檢查系所是否存在
        $placeholders = str_repeat('?,', count($dept_names) - 1) . '?';
        $check_sql = "SELECT department_name FROM DepartmentTransfer WHERE department_name IN ($placeholders)";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute($dept_names);
        $existing_depts = $check_stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (count($existing_depts) < count($dept_names)) {
            $missing = array_diff($dept_names, $existing_depts);
            echo '<div class="alert alert-warning mt-5">以下系所不存在：' . implode(', ', $missing) . '</div>';
            echo '<div class="text-center mt-3"><a href="index.php" class="btn btn-primary">返回首頁</a></div>';
            include 'footer.php';
            exit;
        }
        
        // 獲取系所資料
        $sql = "
            SELECT 
                dt.department_name,
                dt.year_2_enrollment,
                dt.year_3_enrollment,
                dt.year_4_enrollment,
                dt.exam_subjects,
                dt.data_review_ratio,
                d.url,
                d.intro,
                d.careers
            FROM DepartmentTransfer dt
            LEFT JOIN departments d ON CAST(dt.department_name AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci = CAST(d.name AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci
            WHERE CAST(dt.department_name AS CHAR CHARACTER SET utf8mb4) COLLATE utf8mb4_unicode_ci IN ($placeholders)
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($dept_names);
        $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        error_log("Database error in compare.php: " . $e->getMessage());
        echo '<div class="alert alert-danger mt-5">';
        echo '<h4 class="alert-heading">系統發生錯誤</h4>';
        echo '<p>很抱歉，系統在處理您的請求時發生錯誤。請稍後再試。</p>';
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            echo '<hr>';
            echo '<p class="mb-0">錯誤詳情：' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        echo '</div>';
        echo '<div class="text-center mt-3"><a href="index.php" class="btn btn-primary">返回首頁</a></div>';
        include 'footer.php';
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>

<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>系所比較</h2>
        
    </div>
    
                <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-light">
                            <tr>
                    <th style="width: 200px;">比較項目</th>
                                <?php foreach ($departments as $dept): ?>
                        <th class="text-center">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-dark"><?= htmlspecialchars($dept['department_name']) ?></span>
                                <button class="btn btn-sm btn-outline-danger remove-dept ms-2" 
                                        data-dept="<?= htmlspecialchars($dept['department_name']) ?>"
                                        title="移除系所">
                                                <i class="bi bi-x"></i>
                                            </button>
                            </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                <!-- 系所簡介 -->
                <tr>
                    <td class="bg-light">系所簡介</td>
                    <?php foreach ($departments as $dept): ?>
                        <td><?= nl2br(htmlspecialchars($dept['intro'] ?? '暫無資料')) ?></td>
                    <?php endforeach; ?>
                </tr>
                
                <!-- 年級名額 -->
                <tr>
                    <td class="bg-light">年級名額</td>
                    <?php foreach ($departments as $dept): ?>
                        <td>
                            <div class="mb-2">
                                <strong>二年級：</strong>
                                <span class="badge bg-primary"><?= htmlspecialchars($dept['year_2_enrollment'] ?? '暫無資料') ?></span>
                            </div>
                            <div class="mb-2">
                                <strong>三年級：</strong>
                                <span class="badge bg-primary"><?= htmlspecialchars($dept['year_3_enrollment'] ?? '暫無資料') ?></span>
                            </div>
                            <div>
                                <strong>四年級：</strong>
                                <span class="badge bg-primary"><?= htmlspecialchars($dept['year_4_enrollment'] ?? '暫無資料') ?></span>
                            </div>
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

                <!-- 未來發展 -->
                <tr>
                    <td class="bg-light">未來發展</td>
                    <?php foreach ($departments as $dept): ?>
                        <td>
                            <?php
                            if (!empty($dept['careers'])) {
                                $careers = json_decode($dept['careers'], true);
                                if (is_array($careers)) {
                                    echo '<ul class="list-unstyled mb-0">';
                                    foreach ($careers as $career) {
                                        echo '<li><i class="bi bi-check2-circle text-success"></i> ' . htmlspecialchars($career) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo '暫無資料';
                                }
                            } else {
                                echo '暫無資料';
                            }
                            ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>

                <!-- 系所網站 -->
                <tr>
                    <td class="bg-light">系所網站</td>
                    <?php foreach ($departments as $dept): ?>
                        <td>
                            <?php if (!empty($dept['url'])): ?>
                                <a href="<?= htmlspecialchars($dept['url']) ?>" target="_blank" class="btn btn-outline-primary">
                                    <i class="bi bi-link-45deg"></i> 前往系所網站
                                            </a>
                                        <?php else: ?>
                                <span class="text-muted">暫無資料</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
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