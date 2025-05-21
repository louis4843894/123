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
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM department WHERE department_name LIKE :search");
    $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $count_stmt->execute();
} else {
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM department");
}
$total_departments = $count_stmt->fetchColumn();
$total_pages = ceil($total_departments / $limit);

// 撈取當頁資料
if (!empty($search)) {
    $stmt = $pdo->prepare("
        SELECT d.department_id, d.department_name, 
               GROUP_CONCAT(DISTINCT et.exam_type_name) as exam_types,
               GROUP_CONCAT(DISTINCT dr.remark_text ORDER BY dr.remark_order) as remarks
        FROM department d
        LEFT JOIN departmentexamtype det ON d.department_id = det.department_id
        LEFT JOIN examtype et ON det.exam_type_id = et.exam_type_id
        LEFT JOIN departmentremark dr ON d.department_id = dr.department_id
        WHERE d.department_name LIKE :search
        GROUP BY d.department_id
        ORDER BY d.department_id ASC
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare("
        SELECT d.department_id, d.department_name, 
               GROUP_CONCAT(DISTINCT et.exam_type_name) as exam_types,
               GROUP_CONCAT(DISTINCT dr.remark_text ORDER BY dr.remark_order) as remarks
        FROM department d
        LEFT JOIN departmentexamtype det ON d.department_id = det.department_id
        LEFT JOIN examtype et ON det.exam_type_id = et.exam_type_id
        LEFT JOIN departmentremark dr ON d.department_id = dr.department_id
        GROUP BY d.department_id
        ORDER BY d.department_id ASC
        LIMIT :limit OFFSET :offset
    ");
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>轉系系統</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            ;
            font-family: 'Microsoft JhengHei', Arial, sans-serif;
        }

        .navbar {
            background: linear-gradient(90deg, rgb(168, 170, 173) 0%, rgb(114, 115, 116) 100%);
            padding: 10px 20px;
            font-weight: bold;
        }

        .navbar a {
            color: white;
        }

        .navbar a:hover {
            color: rgb(212, 215, 217);
        }

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
</head>

<body>

    <div class="container-fluid">
        <!-- 導覽列 -->
        <nav class="navbar navbar-expand-lg fixed-top mb-4">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="https://upload.wikimedia.org/wikipedia/zh/thumb/d/da/Fu_Jen_Catholic_University_logo.svg/1200px-Fu_Jen_Catholic_University_logo.svg.png"
                        alt="" width="30" height="30" class="d-inline-block align-text-top me-2">輔仁大學轉系系統
                </a>
                <?php
                $selected_departments = isset($_COOKIE['compare_departments']) ? json_decode($_COOKIE['compare_departments'], true) : [];
                $compare_count = count($selected_departments);
                ?>

                <div class="d-flex align-items-center">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <button type="button" class="btn btn-outline-light me-2" onclick="showLoginPrompt()">
                            <i class="bi bi-arrow-left-right"></i> 系所比較
                        </button>
                    <?php else: ?>
                        <?php if ($compare_count >= 2): ?>
                            <a href="compare.php?names=<?php echo urlencode(implode(',', $selected_departments)); ?>"
                                class="btn btn-outline-light me-2">
                                <i class="bi bi-arrow-left-right"></i> 系所比較
                            </a>
                        <?php else: ?>
                            <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal"
                                data-bs-target="#noCompareModal">
                                <i class="bi bi-arrow-left-right"></i> 系所比較
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light dropdown-toggle" type="button" id="accountDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                帳號管理
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                <li><a class="dropdown-item" href="account_settings.php">修改資料</a></li>
                                <li><a class="dropdown-item" href="logout.php">登出</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-light me-2">登入</a>
                        <a href="register.php" class="btn btn-outline-light">註冊</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
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

                <!-- 搜尋列 -->
                <div class="search-box mb-4 mt-5" style="margin-top: 4rem !important;">
                    <form method="GET" class="mb-0">
                        <div class="input-group input-group-lg">
                            <input type="text" name="search" class="form-control" placeholder="搜尋系所..."
                                value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn-search">
                                <i class="bi bi-search"></i> 搜尋
                            </button>
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
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>系所編號</th>
                                        <th>系所名稱</th>
                                        <th>考試類型</th>
                                        <th>備註</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($departments)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <i class="bi bi-info-circle text-muted"></i> 沒有找到符合的系所
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($departments as $dept): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($dept['department_id']); ?></td>
                                                <td>
                                                    <a href="department_detail.php?id=<?php echo $dept['department_id']; ?>" class="department-link">
                                                        <?php echo htmlspecialchars($dept['department_name']); ?>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($dept['exam_types'] ?? '無'); ?></td>
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
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="department_detail.php?id=<?php echo $dept['department_id']; ?>" class="btn btn-more">詳細資訊</a>
                                                        <button type="button" class="btn btn-more dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <span class="visually-hidden">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="department_detail.php?id=<?php echo $dept['department_id']; ?>">查看詳情</a></li>
                                                            <li><a class="dropdown-item" href="compare.php?id=<?php echo $dept['department_id']; ?>">比較</a></li>
                                                        </ul>
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
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                    <di id="department-content" style="display: none;">
                </div>
            </main>
        </div>
    </div>

    <!-- 沒有比較系所的提示 Modal -->
    <div class="modal fade" id="noCompareModal" tabindex="-1" aria-labelledby="noCompareModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noCompareModalLabel">提示</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="bi bi-info-circle-fill  mb-3" style="font-size: 2rem;color:rgb(172, 192, 221)"></i>
                    <p class="mb-3">還沒有比較的系所喔，去主頁探索吧！</p>
                    <a href="index.php" class="btn" style="background-color:rgb(172, 192, 221);">
                        <i class="bi bi-house-door"></i> 返回主頁
                    </a>
                </div>
            </div>
        </div>
    </div>


    <!-- 登入提示 Modal -->
    <div class="modal fade" id="loginModalAddCompare" tabindex="-1" aria-labelledby="loginModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">尚未登入</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body">
                    您必須先登入才能使用「加入比較」功能。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <a href="login.php" class="btn" style="background-color:rgb(172, 192, 221);">前往登入</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">尚未登入</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                </div>
                <div class="modal-body">
                    您必須先登入才能使用「系所比較」功能。
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <a href="login.php" class="btn" style="background-color:rgb(172, 192, 221);">前往登入</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoginPrompt() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }
    </script>
    <script>
        function showLoginPrompt() {
            var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            loginModal.show();
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.querySelector("input[name='search']");

            searchInput.addEventListener("input", function () {
                if (searchInput.value.trim() === "") {
                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 500);
                }
            });
        });
    </script>




    <footer class="bg-light py-3 mt-5">
        <div class="container text-center text-muted">
            <small>&copy; <?php echo date('Y'); ?> 輔仁大學轉系系統</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>