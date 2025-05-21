<?php
session_start();
require_once 'config.php';

// 設置字符集
header('Content-Type: text/html; charset=utf-8');

// 檢查是否有系所名稱
if (!isset($_GET['name'])) {
    header('Location: index.php');
    exit;
}

$department_name = $_GET['name'];

// 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_department') {
    // 檢查是否為管理員
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: index.php');
        exit;
    }

    // 更新系所資料
    $stmt = $pdo->prepare("UPDATE departments SET 
        intro = ?,
        careers = ?,
        url = ?,
        updated_at = CURRENT_TIMESTAMP
        WHERE name = ?");
    
    $stmt->execute([
        $_POST['intro'],
        $_POST['careers'],
        $_POST['url'],
        $department_name
    ]);

    // 重新導向以避免重複提交
    header("Location: department_detail.php?name=" . urlencode($department_name));
    exit;
}

// 獲取系所信息
try {
    // 先獲取系所基本資訊
    $stmt = $pdo->prepare("SELECT * FROM DepartmentTransfer WHERE department_name = ?");
    $stmt->execute([$department_name]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$department) {
        header("Location: index.php");
        exit();
    }
    
    // 再獲取系所簡介
    $stmt = $pdo->prepare("SELECT * FROM departments WHERE name = ?");
    $stmt->execute([$department_name]);
    $department_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // 如果找不到簡介資料，設置預設值
    if (!$department_info) {
        $department_info = [
            'intro' => '',
            'careers' => '',
            'url' => ''
        ];
    }
    
} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
    exit();
}

$pageTitle = $department['department_name'] . ' - 詳細資訊';
include 'header.php';
?>

<style>
    body {
        background-color: #f8f9fa;
    }

    .navbar {
        background-color: #6c757d !important;
    }

    .navbar-brand {
        color: #fff !important;
        font-weight: bold;
    }

    .btn-outline-light:hover {
        background-color: #fff;
        color: #6c757d;
    }

    .card {
        border: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #6c757d;
        color: white;
        font-weight: bold;
        padding: 15px 20px;
    }

    .info-item {
        margin-bottom: 15px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .info-label {
        font-weight: bold;
        color: #495057;
        margin-right: 10px;
    }

    .btn-back {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-back:hover {
        background-color: #5a6268;
        color: white;
    }

    .btn-compare {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-compare:hover {
        background-color: #5a6268;
        color: white;
    }

    .btn-add {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-add:hover {
        background-color: #5a6268;
        color: white;
    }

    .btn-remove {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    .btn-remove:hover {
        background-color: #c82333;
        color: white;
    }

    .btn-go {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-go:hover {
        background-color: #5a6268;
        color: white;
    }

    .department-section {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .edit-btn {
        padding: 0.5rem 1rem;
        background-color: rgb(75, 100, 158);
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .edit-btn:hover {
        color: white;
        background-color: rgb(91, 120, 189);
    }

    .save-btn {
        padding: 0.5rem 1rem;
        background-color: rgb(87, 148, 100);
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }

    .save-btn:hover {
        color: white;
        background-color: rgb(100, 170, 115);
    }

    .cancel-btn {
        padding: 0.5rem 1rem;
        background-color: rgb(104, 128, 151);
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }

    .cancel-btn:hover {
        color: white;
        background-color: rgb(115, 149, 179);
    }

    .edit-mode textarea {
        width: 100%;
        min-height: 200px;
        margin-bottom: 1rem;
        padding: 0.5rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .view-mode {
        white-space: pre-line;
    }
</style>

<div class="container mt-5 pt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><?php echo htmlspecialchars($department['department_name']); ?></h2>
        <a href="index.php" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> 返回列表
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">系所資訊</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="info-label">二年級名額：</span>
                        <?php echo $department['year_2_enrollment'] ? htmlspecialchars($department['year_2_enrollment']) : '無'; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">三年級名額：</span>
                        <?php echo $department['year_3_enrollment'] ? htmlspecialchars($department['year_3_enrollment']) : '無'; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">四年級名額：</span>
                        <?php echo $department['year_4_enrollment'] ? htmlspecialchars($department['year_4_enrollment']) : '無'; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-item">
                        <span class="info-label">考試科目：</span>
                        <?php echo $department['exam_subjects'] ? htmlspecialchars($department['exam_subjects']) : '無'; ?>
                    </div>
                    <div class="info-item">
                        <span class="info-label">資料審查比例：</span>
                        <?php echo $department['data_review_ratio'] ? htmlspecialchars($department['data_review_ratio']) : '無'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 系所簡介卡片 -->
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">系所簡介</h4>
        </div>
        <div class="card-body">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <button type="button" class="edit-btn" onclick="toggleEditMode()">
                <i class="bi bi-pencil"></i> 編輯內容
            </button>
            <?php endif; ?>

            <form method="POST" id="editForm" style="display: none;">
                <input type="hidden" name="action" value="update_department">
                
                <div class="mb-4">
                    <h4>系所簡介</h4>
                    <textarea name="intro" class="form-control"><?= htmlspecialchars($department_info['intro']) ?></textarea>
                </div>

                <div class="mb-4">
                    <h4>系所特色</h4>
                    <textarea name="careers" class="form-control"><?= htmlspecialchars($department_info['careers']) ?></textarea>
                </div>

                <div class="mb-4">
                    <h4>系所網站</h4>
                    <textarea name="url" class="form-control"><?= htmlspecialchars($department_info['url']) ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="save-btn">儲存修改</button>
                    <button type="button" class="cancel-btn" onclick="toggleEditMode()">取消</button>
                </div>
            </form>

            <div id="viewMode">
                <div class="mb-4">
                    <h4>系所簡介</h4>
                    <div class="view-mode">
                        <?php 
                        if (isset($department_info['intro'])) {
                            echo nl2br(htmlspecialchars($department_info['intro']));
                        }
                        ?>
                    </div>
                </div>

                <div class="mb-4">
                    <h4>未來發展</h4>
                    <div class="view-mode">
                        <?php 
                        if (isset($department_info['careers'])) {
                            $careers = json_decode($department_info['careers'], true);
                            if (is_array($careers)) {
                                echo "<ul>";
                                foreach ($careers as $career) {
                                    echo "<li>" . htmlspecialchars($career) . "</li>";
                                }
                                echo "</ul>";
                            }
                        }
                        ?>
                    </div>
                </div>

                <?php if (isset($department_info['url']) && !empty($department_info['url'])): ?>
                <div class="mb-4">
                    <h4>系所網站</h4>
                    <div class="view-mode">
                        <a href="<?= htmlspecialchars($department_info['url']) ?>" target="_blank" class="btn btn-primary">
                            前往系所網站
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- 比較按鈕 -->
<div class="compare-badge">
    <button onclick="toggleCompare('<?php echo htmlspecialchars($department['department_name']); ?>')" class="btn-add btn-lg rounded-pill shadow">
        <i class="bi bi-plus-circle"></i> 加入比較
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // 更新比較數量
    function updateCompareCount() {
        const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
        const countElement = document.getElementById('compare-count');
        countElement.textContent = compareList.length;
    }

    // 檢查比較列表
    function checkCompareList(event) {
        const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
        if (compareList.length < 2) {
            event.preventDefault();
            alert('請至少選擇兩個系所進行比較！');
            return false;
        }
        return true;
    }

    // 頁面載入時更新比較數量
    document.addEventListener('DOMContentLoaded', updateCompareCount);

    function toggleCompare(departmentName) {
        let compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
        if (compareList.length >= 3) {
            alert('最多只能比較三個系所！');
            return;
        }
        if (!compareList.includes(departmentName)) {
            compareList.push(departmentName);
            localStorage.setItem('compare_departments', JSON.stringify(compareList));
            updateCompareCount();
            window.location.href = 'compare.php';
        } else {
            alert('該系所已在比較清單中！');
        }
    }

    function toggleEditMode() {
        const editForm = document.getElementById('editForm');
        const viewMode = document.getElementById('viewMode');
        const editBtn = document.querySelector('.edit-btn');
        
        if (editForm.style.display === 'none') {
            editForm.style.display = 'block';
            viewMode.style.display = 'none';
            editBtn.style.display = 'none';
        } else {
            editForm.style.display = 'none';
            viewMode.style.display = 'block';
            editBtn.style.display = 'block';
        }
    }
</script>

<?php include 'footer.php'; ?>