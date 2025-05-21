<?php
require_once 'config.php';

// 設置字符集
header('Content-Type: text/html; charset=utf-8');

// 獲取系所ID
$department_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($department_id <= 0) {
    header("Location: index.php");
    exit();
}

// 獲取系所信息
try {
    // 獲取系所基本資訊
    $stmt = $pdo->prepare("
        SELECT d.*, 
               GROUP_CONCAT(DISTINCT et.exam_type_name) as exam_types,
               GROUP_CONCAT(DISTINCT dr.remark_text ORDER BY dr.remark_order) as remarks
        FROM department d
        LEFT JOIN departmentexamtype det ON d.department_id = det.department_id
        LEFT JOIN examtype et ON det.exam_type_id = et.exam_type_id
        LEFT JOIN departmentremark dr ON d.department_id = dr.department_id
        WHERE d.department_id = ?
        GROUP BY d.department_id
    ");
    $stmt->execute([$department_id]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$department) {
        header("Location: index.php");
        exit();
    }
    
} catch(PDOException $e) {
    echo "查詢失敗: " . $e->getMessage();
    exit();
}

// 處理添加到比較列表
if (isset($_POST['add_to_compare'])) {
    // 從 cookie 中獲取已選擇的系所
    $selected_departments = isset($_COOKIE['compare_departments']) ? json_decode($_COOKIE['compare_departments'], true) : [];
    
    // 如果系所不在列表中，則添加
    if (!in_array($department_id, $selected_departments)) {
        $selected_departments[] = $department_id;
        // 保存到 cookie，有效期 7 天
        setcookie('compare_departments', json_encode($selected_departments), time() + (86400 * 7), "/");
    }
    
    // 重定向回當前頁面
    header("Location: department_detail.php?id=" . $department_id);
    exit();
}

// 處理從比較列表中刪除
if (isset($_POST['remove_from_compare'])) {
    // 從 cookie 中獲取已選擇的系所
    $selected_departments = isset($_COOKIE['compare_departments']) ? json_decode($_COOKIE['compare_departments'], true) : [];
    
    // 從列表中移除當前系所
    $selected_departments = array_diff($selected_departments, [$department_id]);
    
    // 更新 cookie
    setcookie('compare_departments', json_encode($selected_departments), time() + (86400 * 7), "/");
    
    // 重定向回當前頁面
    header("Location: department_detail.php?id=" . $department_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($department['department_name']); ?> - 轉系系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
        }
        .card {
            margin-bottom: 1.5rem;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .info-item {
            margin-bottom: 1rem;
        }
        .info-label {
            font-weight: bold;
            color: #495057;
        }
        .compare-badge {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }
        .btn-add, .btn-remove {
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: 500;
        }
        .btn-add {
            background-color: #28a745;
            color: white;
        }
        .btn-remove {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <!-- 基本資訊卡片 -->
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><?php echo htmlspecialchars($department['department_name']); ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">考試類型：</span>
                                    <?php echo htmlspecialchars($department['exam_types'] ?? '無'); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <span class="info-label">備註：</span>
                                    <?php 
                                    $remarks = explode(',', $department['remarks'] ?? '');
                                    if (!empty($remarks[0])) {
                                        echo htmlspecialchars($remarks[0]);
                                    } else {
                                        echo '無';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 比較按鈕 -->
    <div class="compare-badge">
        <?php
        // 檢查當前系所是否在比較列表中
        $selected_departments = isset($_COOKIE['compare_departments']) ? json_decode($_COOKIE['compare_departments'], true) : [];
        $is_in_compare = in_array($department_id, $selected_departments);
        
        if ($is_in_compare):
        ?>
            <form method="POST" class="d-inline">
                <button type="submit" name="remove_from_compare" class="btn-remove btn-lg rounded-pill shadow">
                    <i class="bi bi-dash-circle"></i> 移除比較
                </button>
            </form>
        <?php else: ?>
            <form method="POST" class="d-inline">
                <button type="submit" name="add_to_compare" class="btn-add btn-lg rounded-pill shadow">
                    <i class="bi bi-plus-circle"></i> 加入比較
                </button>
            </form>
        <?php endif; ?>
        
        <?php if (count($selected_departments) >= 2): ?>
            <a href="compare.php?ids=<?php echo urlencode(implode(',', $selected_departments)); ?>" class="btn btn-success btn-lg rounded-pill shadow ms-2">
                <i class="bi bi-arrow-right-circle"></i> 開始比較
            </a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php include 'footer.php'; ?>
</body>
</html>