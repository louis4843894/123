<?php
session_start();
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

// 搜尋條件
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// 分頁設定
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 計算總筆數
if (!empty($search)) {
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM DepartmentTransfer WHERE department_name LIKE :search");
    $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $count_stmt->execute();
} else {
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM DepartmentTransfer");
}
$total_departments = $count_stmt->fetchColumn();
$total_pages = ceil($total_departments / $limit);

// 撈取當頁資料
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT department_name, year_2_enrollment, year_3_enrollment, year_4_enrollment, exam_subjects, data_review_ratio FROM DepartmentTransfer WHERE department_name LIKE :search ORDER BY department_name ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare("SELECT department_name, year_2_enrollment, year_3_enrollment, year_4_enrollment, exam_subjects, data_review_ratio FROM DepartmentTransfer ORDER BY department_name ASC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = '首頁';
include 'header.php';
?>

<style>
    .card {
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border-radius: 0.5rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .search-box {
        background-color: white;
        padding: 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        margin-bottom: 1.5rem;
    }

    .btn-more {
        padding: 0.5rem 1.5rem;
        background-color: rgb(104, 128, 151);
        text-decoration: none;
        color: white;
        border-radius: 0.5rem;
    }

    .btn-more:hover {
        color: white;
        background-color: rgb(115, 149, 179)
    }

    .btn-search {
        padding: 0.5rem 1.5rem;
        background-color: rgb(75, 100, 158);
        text-decoration: none;
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }

    .btn-search:hover {
        color: white;
        background-color: rgb(91, 120, 189);
    }

    .btn-add {
        padding: 0.5rem 1.5rem;
        background-color: rgb(87, 148, 100);
        text-decoration: none;
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }

    .btn-add:hover {
        color: white;
        background-color: rgb(100, 170, 115);
    }

    .btn-remove {
        padding: 0.5rem 1.5rem;
        background-color: rgb(203, 82, 66);
        text-decoration: none;
        color: white;
        border: none !important;
        outline: none !important;
        border-radius: 0.5rem;
    }

    .btn-remove:hover {
        color: white;
        background-color: rgb(207, 109, 96);
    }

    .btn-compare {
        padding: 0.5rem 1.5rem;
        color: white;
        outline: none;
        border-radius: 0.5rem;
        text-decoration: none;
        background-color: rgb(87, 148, 100);
    }

    .btn-compare:hover {
        background-color: rgb(124, 205, 151);
        color: black !important;
    }

    .btn-compare1 {
        padding: 0.5rem 1.5rem;
        color: white;
        outline: none;
        border-radius: 0.5rem;
        text-decoration: none;
    }

    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .department-link {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 500;
    }

    .department-link:hover {
        color: #0b5ed7;
        text-decoration: underline;
    }

    .dropdown-menu {
        background-color: lightgray;
        color: rgb(42, 38, 40);
        border-radius: 0.5rem;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #f5f5f5;
        color: rgb(42, 38, 40);
    }

    .page-item.active .page-link {
        background-color: rgb(85, 89, 87);
        border-color: rgb(65, 68, 67);
        color: #fff;
    }

    .page-link {
        color: rgb(85, 89, 87);
        border-color: rgb(65, 68, 67);
    }

    .page-link:hover {
        background-color: rgba(224, 232, 228, 0.5);
        color: #081c15;
    }

    .btn-Faculty {
        background-color: #e9ecef;
        color: #333;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 2rem;
        margin: 0.4rem;
        font-weight: 500;
        transition: 0.2s ease;
    }

    .btn-Faculty:hover {
        background-color: #d6d6d6;
        color: black;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container-fluid">
    <!-- 主內容：左側邊欄 + 中間內容 -->
    <div class="row">
        <!-- 左側邊欄 -->
        <aside class="col-md-2 bg-light border-end vh-100 pt-4">
            <h5 class="px-3">🔖 快捷功能</h5>
            <ul class="nav flex-column px-3">
                <li class="nav-item mb-2"><a href="#" class="nav-link text-dark">▸ 最近瀏覽（3-4 筆）</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link text-dark">▸ 設定提醒</a></li>
                <li class="nav-item mb-2"><a href="#" class="nav-link text-dark">▸ 轉系 Q&A</a></li>
            </ul>
        </aside>

        <!-- 中間主內容 -->
        <main class="col-md-10 pt-4 px-5">
            <!-- 主要內容 -->
            <div class="container mt-5 pt-5">
                <!-- 搜尋框 -->
                <div class="search-box">
                    <form method="GET" class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center flex-grow-1 me-3">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="搜尋系所..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-search ms-2">搜尋</button>
                        </div>
                    </form>
                </div>

                <!-- ✅ 學院按鈕列 -->
                <div class="d-flex flex-wrap justify-content-center mb-4">
                    <button class="btn-Faculty me-2 mb-2" data-college="文學院">文學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="藝術學院">藝術學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="傳播學院">傳播學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="教育與運動學院">教育與運動學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="醫學院">醫學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="理工學院">理工學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="外國語文學院">外國語文學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="民生學院">民生學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="法律學院">法律學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="社會科學院">社會科學院</button>
                    <button class="btn-Faculty me-2 mb-2" data-college="管理學院">管理學院</button>
                </div>

                <div id="departmentTableSection">
                    <div id="default-table">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>系所名稱</th>
                                        <th>二年級名額</th>
                                        <th>三年級名額</th>
                                        <th>四年級名額</th>
                                        <th>考試科目</th>
                                        <th>資料審查</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($departments)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="bi bi-info-circle text-muted"></i> 沒有找到符合的系所
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($departments as $dept): ?>
                                        <tr>
                                            <td>
                                                <a href="department_detail.php?name=<?= urlencode($dept['department_name']) ?>" class="department-link">
                                                    <?= htmlspecialchars($dept['department_name']) ?>
                                                </a>
                                            </td>
                                            <td><?= $dept['year_2_enrollment'] ? htmlspecialchars($dept['year_2_enrollment']) : '無' ?></td>
                                            <td><?= $dept['year_3_enrollment'] ? htmlspecialchars($dept['year_3_enrollment']) : '無' ?></td>
                                            <td><?= $dept['year_4_enrollment'] ? htmlspecialchars($dept['year_4_enrollment']) : '無' ?></td>
                                            <td><?= $dept['exam_subjects'] ? htmlspecialchars($dept['exam_subjects']) : '無' ?></td>
                                            <td><?= $dept['data_review_ratio'] ? htmlspecialchars($dept['data_review_ratio']) : '無' ?></td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="department_detail.php?name=<?= urlencode($dept['department_name']) ?>" class="btn btn-sm btn-outline-primary" style="white-space: nowrap;">
                                                        <i class="bi bi-info-circle"></i> 詳細資訊
                                                    </a>
                                                    <button onclick="toggleCompare('<?= htmlspecialchars($dept['department_name']) ?>')" class="btn btn-sm btn-outline-success" style="white-space: nowrap;">
                                                        <i class="bi bi-plus-circle"></i> 加入比較
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mt-4">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page - 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">上一頁</a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?><?= $search ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $page + 1 ?><?= $search ? '&search=' . urlencode($search) : '' ?>">下一頁</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
function toggleCompare(departmentName) {
    let compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
    
    if (compareList.includes(departmentName)) {
        compareList = compareList.filter(name => name !== departmentName);
        alert('已從比較清單中移除');
    } else {
        if (compareList.length >= 3) {
            alert('最多只能比較三個系所！');
            return;
        }
        compareList.push(departmentName);
        alert('已加入比較清單');
    }
    
    localStorage.setItem('compare_departments', JSON.stringify(compareList));
    updateCompareCount();
    window.location.href = 'compare.php';
}
</script>

<?php include 'footer.php'; ?>