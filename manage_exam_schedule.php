<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pageTitle = '面試／筆試時程管理';
include 'header.php';

// 取得所有時程，並按「先把字串 '由系辦聯繫告知' 排最前」再按合法日期、時間排序
try {
    $stmt = $pdo->query("
        SELECT 
            id,
            `date`,
            `event`,       -- 這裡的欄位名稱為 event（代表「系所名稱」）
            `type`,
            `time`,
            `location`
        FROM exam_schedule
        ORDER BY 
            FIELD(`date`, '由系辦聯繫告知') ASC,
            /* 如果 date 格式是 YYYY-MM-DD，才會轉成合法日期比較 */
            STR_TO_DATE(`date`, '%Y-%m-%d') ASC,
            `time` ASC
    ");
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>載入時程失敗：{$e->getMessage()}</div>";
    $schedules = [];
}
?>

<style>
    /* 讓編輯 Modal 的 body 超過高度時可以垂直捲動 */
    #editExamModal .modal-body {
        max-height: 60vh;   /* 最多佔螢幕高度的 60%，超出則出現捲軸 */
        overflow-y: auto;
    }

    .schedule-card {
        background-color: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .action-btn {
        padding: 0.4rem 0.8rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }

    .btn-add-exam {
        background-color: rgb(40, 167, 69);
        color: white;
    }
    .btn-add-exam:hover {
        background-color: rgb(60, 180, 90);
    }

    .btn-edit-exam {
        background-color: rgb(23, 162, 184);
        color: white;
    }
    .btn-edit-exam:hover {
        background-color: rgb(24, 190, 200);
    }

    .btn-delete-exam {
        background-color: rgb(220, 53, 69);
        color: white;
    }
    .btn-delete-exam:hover {
        background-color: rgb(200, 35, 55);
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
</style>

<div class="container mt-2 pt-2">
    <!-- 標題 + 新增按鈕 -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0">面試／筆試時程管理</h2>
            <button type="button" class="btn btn-add-exam" data-bs-toggle="modal" data-bs-target="#addExamModal">
                <i class="bi bi-plus-lg"></i> 新增時程
            </button>
        </div>
    </div>

    <!-- 時程表列表 -->
    <div class="schedule-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>日期</th>
                        <th>系所</th>
                        <th>類型</th>
                        <th>時間</th>
                        <th>地點</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($schedules)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3">目前沒有任何面試／筆試時程</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($schedules as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['date']) ?></td>
                            <td><?= htmlspecialchars($item['event']) ?></td>
                            <td><?= htmlspecialchars($item['type']) ?></td>
                            <td><?= htmlspecialchars($item['time']) ?></td>
                            <td><?= htmlspecialchars($item['location']) ?></td>
                            <td>
                                <!-- 編輯按鈕：帶入 data-id -->
                                <button
                                    type="button"
                                    class="btn action-btn btn-edit-exam"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editExamModal"
                                    data-id="<?= $item['id'] ?>"
                                >
                                    編輯
                                </button>
                                <!-- 刪除按鈕：帶入 data-id、data-event 與 data-type，供彈出視窗顯示使用 -->
                                <button
                                    type="button"
                                    class="btn action-btn btn-delete-exam"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteExamModal"
                                    data-id="<?= $item['id'] ?>"
                                    data-event="<?= htmlspecialchars($item['event']) ?>"
                                    data-type="<?= htmlspecialchars($item['type']) ?>"
                                >
                                    刪除
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- 新增時程 Modal -->
<div class="modal fade" id="addExamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增面試／筆試時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_exam.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text"
                               name="date"
                               class="form-control"
                               placeholder="YYYY-MM-DD 或 由系辦聯繫告知"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所名稱</label>
                        <!-- 這裡的 name="event" 對應到資料庫的 event 欄位 -->
                        <input type="text"
                               name="event"
                               class="form-control"
                               placeholder="例如：中文系"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">類型</label>
                        <select name="type" class="form-select" required>
                            <option value="面試">面試</option>
                            <option value="筆試">筆試</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text"
                               name="time"
                               class="form-control"
                               placeholder="例如：10:00 或 由系辦聯繫告知"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text"
                               name="location"
                               class="form-control"
                               placeholder="例如：文開樓LE507 或 由系辦聯繫告知"
                               required>
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

<!-- 編輯時程 Modal -->
<div class="modal fade" id="editExamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">編輯面試／筆試時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_exam.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editExamId">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text"
                               name="date"
                               class="form-control"
                               id="editExamDate"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">系所名稱</label>
                        <!-- 這裡一樣要用 name="event" -->
                        <input type="text"
                               name="event"
                               class="form-control"
                               id="editExamDept"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">類型</label>
                        <select name="type" class="form-select" id="editExamType" required>
                            <option value="面試">面試</option>
                            <option value="筆試">筆試</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text"
                               name="time"
                               class="form-control"
                               id="editExamTime"
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text"
                               name="location"
                               class="form-control"
                               id="editExamLocation"
                               required>
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

<!-- 刪除時程 Modal -->
<div class="modal fade" id="deleteExamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">確認刪除</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_exam.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteExamId">
                    <p>確定要刪除「<span id="deleteExamInfo"></span>」這筆時程嗎？此操作無法復原。</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger">刪除</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- 載入 Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// 「編輯」按鈕：AJAX 取得單筆資料並填入 Modal
document.querySelectorAll('[data-bs-target="#editExamModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('editExamId').value = id;

        fetch(`process_exam.php?action=get&id=${id}`)
            .then(response => {
                if (!response.ok) throw new Error('HTTP 錯誤：' + response.status);
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const rec = data.data;
                    document.getElementById('editExamDate').value     = rec.date;
                    document.getElementById('editExamDept').value     = rec.event;          // 改為 event
                    document.getElementById('editExamType').value     = rec.type;
                    document.getElementById('editExamTime').value     = rec.time;
                    document.getElementById('editExamLocation').value = rec.location;
                } else {
                    alert('無法取得該筆資料：' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('讀取資料時發生錯誤');
            });
    });
});

// 「刪除」按鈕：填入隱藏欄位並顯示系所＋類型
document.querySelectorAll('[data-bs-target="#deleteExamModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const id    = this.dataset.id;
        const ev    = this.dataset.event;
        const type  = this.dataset.type;
        document.getElementById('deleteExamId').value = id;
        document.getElementById('deleteExamInfo').textContent = `${ev} (${type})`;
    });
});

// 顯示新增／編輯／刪除後的成功或錯誤訊息
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
