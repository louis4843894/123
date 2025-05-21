<?php
require_once 'config.php';

// 設置字符集
header('Content-Type: text/html; charset=utf-8');

// 從 URL 參數獲取比較列表
$compare_departments = isset($_GET['departments']) ? explode(',', $_GET['departments']) : [];

// 如果 URL 中沒有參數，則從 localStorage 獲取
if (empty($compare_departments)) {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            if (compareList.length > 0) {
                const url = new URL(window.location.href);
                url.searchParams.set('departments', compareList.join(','));
                window.location.href = url.toString();
            }
        });
    </script>";
}

// 獲取系所資訊
$departments = [];
if (!empty($compare_departments)) {
    $placeholders = str_repeat('?,', count($compare_departments) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM DepartmentTransfer WHERE department_name IN ($placeholders)");
    $stmt->execute($compare_departments);
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系所比較 - 轉系資訊系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
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

        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }

        .table th:first-child {
            background-color: #e9ecef;
        }

        .table td:first-child {
            background-color: #e9ecef;
            font-weight: bold;
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

        .btn-detail {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .btn-detail:hover {
            background-color: #5a6268;
            color: white;
        }

        .btn-remove {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            font-size: 0.9rem;
            margin-left: 5px;
        }

        .btn-remove:hover {
            background-color: #c82333;
            color: white;
        }

        .department-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .department-name {
            font-weight: bold;
            margin-right: 10px;
        }

        .department-actions {
            display: flex;
            gap: 5px;
        }
    </style>
</head>

<body>
    <!-- 導航欄 -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">轉系資訊系統</a>
            <div class="d-flex align-items-center">
                <a href="compare.php" class="btn btn-compare me-2" onclick="return checkCompareList(event)">
                    <i class="bi bi-arrow-left-right"></i> 比較系所
                    <span class="badge bg-light text-dark" id="compare-count">0</span>
                </a>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php" class="btn btn-outline-light me-2">登入</a>
                    <a href="register.php" class="btn btn-outline-light me-2">註冊</a>
                <?php else: ?>
                    <a href="account_settings.php" class="btn btn-outline-light me-2">帳號設定</a>
                    <a href="logout.php" class="btn btn-outline-light me-2">登出</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- 主要內容 -->
    <div class="container mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">系所比較</h2>
            <a href="index.php" class="btn btn-back">
                <i class="bi bi-arrow-left"></i> 返回列表
            </a>
        </div>

        <?php if (empty($departments)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 請先選擇要比較的系所
            </div>
        <?php else: ?>
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>比較項目</th>
                                <?php foreach ($departments as $dept): ?>
                                    <th>
                                        <div class="department-header">
                                            <span class="department-name"><?php echo htmlspecialchars($dept['department_name']); ?></span>
                                            <div class="department-actions">
                                                <a href="department_detail.php?name=<?php echo urlencode($dept['department_name']); ?>" class="btn btn-detail">
                                                    <i class="bi bi-eye"></i> 查看詳情
                                                </a>
                                                <button class="btn btn-remove" onclick="removeDepartment('<?php echo htmlspecialchars($dept['department_name']); ?>')">
                                                    <i class="bi bi-x"></i> 移除
                                                </button>
                                            </div>
                                        </div>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>二年級名額</td>
                                <?php foreach ($departments as $dept): ?>
                                    <td><?php echo $dept['year_2_enrollment'] ? htmlspecialchars($dept['year_2_enrollment']) : '無'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td>三年級名額</td>
                                <?php foreach ($departments as $dept): ?>
                                    <td><?php echo $dept['year_3_enrollment'] ? htmlspecialchars($dept['year_3_enrollment']) : '無'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td>四年級名額</td>
                                <?php foreach ($departments as $dept): ?>
                                    <td><?php echo $dept['year_4_enrollment'] ? htmlspecialchars($dept['year_4_enrollment']) : '無'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td>考試科目</td>
                                <?php foreach ($departments as $dept): ?>
                                    <td><?php echo $dept['exam_subjects'] ? htmlspecialchars($dept['exam_subjects']) : '無'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td>資料審查比例</td>
                                <?php foreach ($departments as $dept): ?>
                                    <td><?php echo $dept['data_review_ratio'] ? htmlspecialchars($dept['data_review_ratio']) : '無'; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
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

        // 移除系所
        function removeDepartment(departmentName) {
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            const index = compareList.indexOf(departmentName);
            if (index > -1) {
                compareList.splice(index, 1);
                localStorage.setItem('compare_departments', JSON.stringify(compareList));
                
                // 如果移除後只剩一個系所，返回首頁
                if (compareList.length < 2) {
                    window.location.href = 'index.php';
                } else {
                    // 更新 URL 並重新載入頁面
                    const url = new URL(window.location.href);
                    url.searchParams.set('departments', compareList.join(','));
                    window.location.href = url.toString();
                }
            }
        }

        // 頁面載入時更新比較數量
        document.addEventListener('DOMContentLoaded', function() {
            updateCompareCount();
        });
    </script>
</body>

</html>