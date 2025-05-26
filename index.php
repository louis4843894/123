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
    $count_stmt = $pdo->prepare("SELECT COUNT(*) FROM departments WHERE name LIKE :search");
    $count_stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    $count_stmt->execute();
} else {
    $count_stmt = $pdo->query("SELECT COUNT(*) FROM departments");
}
$total_departments = $count_stmt->fetchColumn();
$total_pages = ceil($total_departments / $limit);

// 撈取當頁資料
try {
if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT name as department_name, intro_summary as department_intro FROM departments WHERE name LIKE :search ORDER BY name ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
} else {
        $stmt = $pdo->prepare("SELECT name as department_name, intro_summary as department_intro FROM departments ORDER BY name ASC LIMIT :limit OFFSET :offset");
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // 如果發生錯誤，使用原始查詢
    if (!empty($search)) {
        $stmt = $pdo->prepare("SELECT department_name, '' as department_intro FROM DepartmentTransfer WHERE department_name LIKE :search ORDER BY department_name ASC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
    } else {
        $stmt = $pdo->prepare("SELECT department_name, '' as department_intro FROM DepartmentTransfer ORDER BY department_name ASC LIMIT :limit OFFSET :offset");
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$pageTitle = '首頁';
include 'header.php';
?>

<div class="container-fluid">
        <!-- 主內容：左側邊欄 + 中間內容 -->
        <div class="row">
            <!-- 左側邊欄 -->
        <aside class="col-md-2 bg-light border-end vh-100 pt-1 mt-1">
            <div class="mt-0">
                <h5 class="px-3">其他功能</h5>
                <ul class="nav flex-column px-3">
                    <li class="nav-item mb-2">
                        <a href="discussion.php" class="nav-link btn btn-secondary text-white fw-bold mb-2">
                            <i class="bi bi-chat-dots"></i> 討論區
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="transfer_qa.php" class="nav-link btn btn-secondary text-white fw-bold mb-2">
                            <i class="bi bi-question-circle"></i> 轉系 Q&A
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item mb-2">
                        <a href="my_favorites.php" class="nav-link btn btn-secondary text-white fw-bold mb-2">
                            <i class="bi bi-heart"></i> 我的收藏
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>

                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="mt-4">
                    <h6 class="px-3 mb-3"><i class="bi bi-clock-history"></i> 最近瀏覽的系所</h6>
                    <ul class="nav flex-column px-3" id="recentDeptsList">
                        <?php
                        require_once 'functions.php';
                        $recentDepts = getRecentViewedDepartments(5);
                        
                        if (empty($recentDepts)): ?>
                            <li class="nav-item">
                                <span class="nav-link text-muted py-1">暫無瀏覽記錄</span>
                            </li>
                        <?php else:
                            foreach ($recentDepts as $dept): ?>
                                <li class="nav-item">
                                    <a href="department_detail.php?name=<?= urlencode($dept['department_name']) ?>" 
                                       class="nav-link text-dark py-1"
                                       onclick="return handleDeptClick('<?= htmlspecialchars($dept['department_name'], ENT_QUOTES) ?>', this)">
                                        ▸ <?= htmlspecialchars($dept['department_name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li class="nav-item mt-2">
                                <a href="#" class="nav-link" id="showAllHistory" style="color: #6c757d;">
                                    <small><i class="bi bi-chevron-down">更多瀏覽記錄 </i></small>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <!-- 瀏覽記錄展開 Modal -->
                <div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="historyModalLabel">瀏覽記錄</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
                            </div>
                            <div class="modal-body">
                                <div id="fullHistoryList">
                                    <!-- 這裡會由 JavaScript 填充完整的瀏覽記錄 -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </aside>

            <!-- 中間主內容 -->
        <main class="col-md-10 pt-0 px-5">
            <!-- 主要內容 -->
            <div class="container mt-0 pt-1">
                <!-- 搜尋框 -->
                <div class="search-box mb-3">
                    <form method="GET" class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center flex-grow-1 me-3">
                            <input type="text" name="search" class="form-control form-control-lg" placeholder="搜尋系所..." value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-search btn-lg ms-2 px-4">
                                <span>搜尋</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 學院篩選 -->
                <div class="college-filter-wrapper text-center mb-4">
                    <?php
                    $colleges = [
                        '文學院',
                        '藝術學院',
                        '傳播學院',
                        '教育與運動學院',
                        '醫學院',
                        '理工學院',
                        '外國語文學院',
                        '民生學院',
                        '法律學院',
                        '社會科學院',
                        '管理學院'
                    ];
                    foreach ($colleges as $college):
                    ?>
                        <button class="btn btn-Faculty college-filter" data-college="<?php echo htmlspecialchars($college); ?>">
                            <?php echo htmlspecialchars($college); ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div id="departmentTableSection">
                        <div class="table-responsive">
                        <table class="table">
                                <thead>
                                    <tr>
                                    <th width="25%" class="text-start">系所名稱</th>
                                    <th width="50%">系所簡介</th>
                                    <th width="25%" class="ps-5">操作</th>
                                    </tr>
                                </thead>
                            <tbody id="department-table-body">
                                    <?php if (empty($departments)): ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <i class="bi bi-info-circle text-muted"></i> 沒有找到符合的系所
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($departments as $dept): ?>
                                            <tr>
                                        <td class="text-dark">
                                            <?= htmlspecialchars($dept['department_name']) ?>
                                                </td>
                                                <td>
                                            <p class="mb-0 text-muted">
                                                    <?php
                                                $intro = isset($dept['department_intro']) ? $dept['department_intro'] : '暫無簡介';
                                                echo nl2br(htmlspecialchars(mb_strimwidth($intro, 0, 150, '...'))); 
                                                    ?>
                                            </p>
                                                </td>
                                                <td>
                                            <div class="d-flex gap-2 justify-content-start ps-5">
                                                <a href="department_detail.php?name=<?= urlencode($dept['department_name']) ?>" 
                                                   class="btn-more w-40 text-center py-2"
                                                   onclick="recordDeptView('<?= htmlspecialchars($dept['department_name'], ENT_QUOTES) ?>', this); return false;">
                                                    點我詳情
                                                </a>
                                                <button class="btn-add w-40 text-center py-2 toggle-compare-btn" 
                                                        data-dept="<?= htmlspecialchars($dept['department_name']) ?>"
                                                        data-action="add">
                                                    加入比較
                                                                    </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <nav aria-label="Page navigation" id="pagination-wrapper">
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
            </main>
        </div>
    </div>

<!-- 加入比較登入提示 Modal -->
<div class="modal fade" id="loginModalAddCompare" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
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

<!-- 系所比較登入提示 Modal -->
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
    // 處理系所點擊
    function handleDeptClick(deptName, element) {
        if (!deptName) return true;
        
        // 發送瀏覽記錄
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'record_view.php', false); // 使用同步請求
        xhr.setRequestHeader('Content-Type', 'application/json');
        
        try {
            xhr.send(JSON.stringify({
                page_type: 'department',
                page_id: deptName,
                page_title: deptName
            }));
            
            if (xhr.status === 200) {
                // 立即更新瀏覽記錄
                updateRecentViews();
            }
        } catch (error) {
            console.error('Error recording view:', error);
        }
        
        return true; // 允許正常的頁面跳轉
    }

    // 更新瀏覽記錄
    function updateRecentViews() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_recent_views.php', false); // 使用同步請求
        
        try {
            xhr.send();
            if (xhr.status === 200) {
                var recentList = document.getElementById('recentDeptsList');
                if (recentList) {
                    recentList.innerHTML = xhr.responseText;
                }
            }
        } catch (error) {
            console.error('Error updating views:', error);
        }
    }

    // 處理查看更多按鈕
    document.addEventListener('DOMContentLoaded', function() {
        var showAllBtn = document.getElementById('showAllHistory');
        if (showAllBtn) {
            showAllBtn.onclick = function(e) {
                e.preventDefault();
                
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_all_history.php', true);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var data = JSON.parse(xhr.responseText);
                            var historyList = document.getElementById('fullHistoryList');
                            if (!historyList) return;
                            
                            historyList.innerHTML = '';
                            
                            if (!data || data.length === 0) {
                                historyList.innerHTML = '<p class="text-muted">暫無瀏覽記錄</p>';
                            } else {
                                var ul = document.createElement('ul');
                                ul.className = 'list-unstyled';
                                
                                // 添加標題和說明
                                var header = document.createElement('div');
                                header.className = 'mb-4';
                                header.innerHTML = `
                                    <h6 class="mb-3">所有瀏覽記錄</h6>
                                    <p class="text-muted small">顯示您的所有系所瀏覽記錄，按時間順序排列。</p>
                                `;
                                historyList.appendChild(header);
                                
                                // 按日期分組顯示記錄
                                var currentDate = '';
                                data.forEach(function(dept) {
                                    var recordDate = dept.viewed_at.split(' ')[0];
                                    
                                    if (recordDate !== currentDate) {
                                        currentDate = recordDate;
                                        var dateHeader = document.createElement('div');
                                        dateHeader.className = 'mt-4 mb-2 text-muted';
                                        dateHeader.innerHTML = formatDate(recordDate);
                                        ul.appendChild(dateHeader);
                                    }
                                    
                                    var li = document.createElement('li');
                                    li.className = 'mb-2 ps-3';
                                    li.innerHTML = `
                                        <a href="department_detail.php?name=${encodeURIComponent(dept.department_name)}" 
                                           onclick="recordDeptView('${dept.department_name}', this); return false;"
                                           class="text-decoration-none text-dark">
                                            ▸ ${dept.department_name}
                                        </a>
                                        <br>
                                        <small class="text-muted">
                                            ${dept.viewed_at.split(' ')[1]}
                                        </small>
                                    `;
                                    ul.appendChild(li);
                                });
                                
                                historyList.appendChild(ul);
                            }
                            
                            var historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
                            historyModal.show();
                        } catch (error) {
                            console.error('Error parsing history:', error);
                        }
                    }
                };
                
                xhr.send();
            };
        }
        
        // 每30秒更新一次瀏覽記錄
        setInterval(updateRecentViews, 30000);
    });

    // 處理比較按鈕點擊
        document.addEventListener("DOMContentLoaded", function () {
        // 初始化所有按鈕的狀態
        function initializeCompareButtons() {
            const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
            document.querySelectorAll('.toggle-compare-btn').forEach(btn => {
                const deptName = btn.dataset.dept;
                if (compareList.includes(deptName)) {
                    btn.classList.remove("btn-add");
                    btn.classList.add("btn-remove");
                    btn.textContent = '移出比較';
                    btn.dataset.action = "remove";
                } else {
                    btn.classList.remove("btn-remove");
                    btn.classList.add("btn-add");
                    btn.textContent = '加入比較';
                    btn.dataset.action = "add";
                }
            });

            // 更新計數器
            const countElements = document.querySelectorAll('#compare-count');
            countElements.forEach(element => {
                element.textContent = compareList.length;
            });
        }

        // ✅ 搜尋清空 → 回首頁
            const searchInput = document.querySelector("input[name='search']");
        if (searchInput) {
            searchInput.addEventListener("input", function () {
                if (searchInput.value.trim() === "") {
                    setTimeout(() => {
                        window.location.href = "index.php";
                    }, 500);
                }
            });
        }

        // ✅ 學院篩選（點同一顆再點 → 還原全部）＋ 隱藏分頁
        let currentCollege = null;
        document.querySelectorAll('.college-filter').forEach(button => {
            button.addEventListener('click', () => {
                const selectedCollege = button.getAttribute('data-college');

                // 移除所有按鈕的 active 類別
                document.querySelectorAll('.college-filter').forEach(btn => {
                    btn.classList.remove('active');
                });

                // 如果點擊的是當前選中的學院，則返回全部系所
                if (currentCollege === selectedCollege) {
                    window.location.href = 'index.php';
                    currentCollege = null;
                } else {
                    // 添加 active 類別到選中的按鈕
                    button.classList.add('active');
                    fetch('load_departments.php?college=' + encodeURIComponent(selectedCollege))
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('department-table-body').innerHTML = html;
                            document.getElementById('pagination-wrapper').style.display = 'none';
                            currentCollege = selectedCollege;
                            // 重新初始化按鈕狀態
                            initializeCompareButtons();
                        });
                }
            });
        });

        // ✅ 即時加入／移除比較按鈕（不刷新頁面）
        document.addEventListener("click", function (e) {
            const btn = e.target.closest(".toggle-compare-btn");
            if (!btn) return;

            <?php if (!isset($_SESSION['user_id'])): ?>
                var loginModalAddCompare = new bootstrap.Modal(document.getElementById('loginModalAddCompare'));
                loginModalAddCompare.show();
                return;
            <?php else: ?>
                e.preventDefault();
                const deptName = btn.dataset.dept;
                const action = btn.dataset.action;

                // 檢查是否達到最大比較數量
                if (action === "add") {
                    const compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
                    if (compareList.length >= 3 && !compareList.includes(deptName)) {
                        alert('最多只能同時比較3個系所，請先移除其中一個系所後再進行添加');
                        return;
                    }
                }

                // 更新按鈕狀態
                if (action === "add") {
                    btn.classList.remove("btn-add");
                    btn.classList.add("btn-remove");
                    btn.textContent = '移出比較';
                    btn.dataset.action = "remove";
                } else {
                    btn.classList.remove("btn-remove");
                    btn.classList.add("btn-add");
                    btn.textContent = '加入比較';
                    btn.dataset.action = "add";
                }

                // 更新 localStorage
                let compareList = JSON.parse(localStorage.getItem('compare_departments') || '[]');
                if (action === "add") {
                    if (!compareList.includes(deptName)) {
                        compareList.push(deptName);
                    }
                } else {
                    compareList = compareList.filter(dept => dept !== deptName);
                }
                localStorage.setItem('compare_departments', JSON.stringify(compareList));
                
                // 更新計數器
                const countElements = document.querySelectorAll('#compare-count');
                countElements.forEach(element => {
                    element.textContent = compareList.length;
                });

                // 更新後端
                fetch('compare_toggle.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        department_name: deptName,
                        action: action
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        alert(data.message);
                        // 如果發生錯誤，恢復按鈕狀態
                        if (action === 'add') {
                            btn.classList.remove('btn-remove');
                            btn.classList.add('btn-add');
                            btn.textContent = '加入比較';
                            btn.dataset.action = 'add';
                            // 恢復 localStorage 和計數器
                            compareList = compareList.filter(dept => dept !== deptName);
                        } else {
                            btn.classList.remove('btn-add');
                            btn.classList.add('btn-remove');
                            btn.textContent = '移出比較';
                            btn.dataset.action = 'remove';
                            // 恢復 localStorage 和計數器
                            if (!compareList.includes(deptName)) {
                                compareList.push(deptName);
                            }
                        }
                        localStorage.setItem('compare_departments', JSON.stringify(compareList));
                        // 更新計數器
                        countElements.forEach(element => {
                            element.textContent = compareList.length;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('操作失敗，請稍後再試');
                });
            <?php endif; ?>
        });

        // 初始化頁面時的按鈕狀態
        initializeCompareButtons();
    });

    function recordDeptView(deptName, link) {
        // 建立 XMLHttpRequest 對象
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'record_view.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        // 準備要發送的數據
        var data = 'department_name=' + encodeURIComponent(deptName);
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                // 無論成功與否都更新側邊欄並跳轉
                var sidebarXhr = new XMLHttpRequest();
                sidebarXhr.open('GET', 'get_recent_views.php', true);
                
                sidebarXhr.onreadystatechange = function() {
                    if (sidebarXhr.readyState === 4) {
                        var recentList = document.getElementById('recentDeptsList');
                        if (recentList) {
                            recentList.innerHTML = sidebarXhr.responseText;
                        }
                        // 跳轉到目標頁面
                        window.location.href = link.href;
                    }
                };
                
                sidebarXhr.send();
            }
        };
        
        // 發送請求
        xhr.send(data);
    }

    // 定期更新側邊欄
    setInterval(function() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_recent_views.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var recentList = document.getElementById('recentDeptsList');
                if (recentList) {
                    recentList.innerHTML = xhr.responseText;
                }
            }
        };
        xhr.send();
    }, 30000);

    // 添加日期格式化函數
    function formatDate(dateStr) {
        var date = new Date(dateStr);
        var today = new Date();
        var yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        
        if (dateStr === today.toISOString().split('T')[0]) {
            return '今天';
        } else if (dateStr === yesterday.toISOString().split('T')[0]) {
            return '昨天';
        } else {
            return dateStr;
        }
    }
</script>

<style>
.btn-search {
    min-width: 120px;
    padding-left: 2rem;
    padding-right: 2rem;
}
.btn-primary {
    background-color: #0d6efd;
    border: none;
}
.btn-success {
    background-color: #198754;
    border: none;
}
.btn-primary:hover {
    background-color: #0b5ed7;
}
.btn-success:hover {
    background-color: #157347;
}
.btn-more, .btn-add {
    letter-spacing: 0.5em;
    text-indent: 0.5em;
    white-space: nowrap;
    width: 120px !important;
    display: flex;
    align-items: center;
    justify-content: center;
}
.w-40 {
    width: 40% !important;
}

/* 查看更多瀏覽記錄按鈕樣式 */
.show-all-history {
    color: #6c757d !important;  /* 使用 Bootstrap 的次要文字顏色 */
    text-decoration: none !important;
    font-size: 0.9rem;
    padding: 0.25rem 0;
    display: inline-flex;
    align-items: center;
    transition: color 0.15s ease-in-out;
}

.show-all-history:hover {
    color: #495057 !important;  /* 滑鼠懸停時的顏色 */
}

.show-all-history .bi-chevron-down {
    font-size: 0.8rem;
    margin-right: 0.25rem;
}
</style>

<?php include 'footer.php'; ?>