<?php
session_start();
require_once 'config.php';

// 檢查是否為管理員
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$pageTitle = '轉系時程表管理';
include 'header.php';

// 撈取轉系時程表所有資料（按日期、時間排序）
try {
    $transferStmt = $pdo->query("
        SELECT id, `date`, `event`, `time`, `location`
        FROM transfer_schedule
        ORDER BY `date` ASC, `time` ASC
    ");
    $transfers = $transferStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>載入時程失敗：{$e->getMessage()}</div>";
    $transfers = [];
}
?>

<style>
    /* 讓編輯 Modal 的 body 超過高度時可以捲動 */
    #editTransferModal .modal-body {
        max-height: 60vh;       /* 最多佔螢幕高度的 60%，超出則顯示捲軸 */
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

    .btn-add-schedule {
        background-color: rgb(40, 167, 69);
        color: white;
    }
    .btn-add-schedule:hover {
        background-color: rgb(60, 180, 90);
    }

    .btn-edit-schedule {
        background-color: rgb(23, 162, 184);
        color: white;
    }
    .btn-edit-schedule:hover {
        background-color: rgb(24, 190, 200);
    }

    .btn-delete-schedule {
        background-color: rgb(220, 53, 69);
        color: white;
    }
    .btn-delete-schedule:hover {
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
            <h2 class="mb-0">轉系時程表管理</h2>
            <button type="button" class="btn btn-add-schedule" data-bs-toggle="modal" data-bs-target="#addTransferModal">
                <i class="bi bi-plus-lg"></i> 新增時程
            </button>
        </div>
    </div>

    <!-- 列表 -->
    <div class="schedule-card">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>日期</th>
                        <th>活動</th>
                        <th>時間</th>
                        <th>地點</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transfers)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-3">目前沒有任何轉系時程</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transfers as $ts): ?>
                        <tr>
                            <td><?= htmlspecialchars($ts['date']) ?></td>
                            <td><?= htmlspecialchars($ts['event']) ?></td>
                            <td><?= htmlspecialchars($ts['time']) ?></td>
                            <td><?= htmlspecialchars($ts['location']) ?></td>
                            <td>
                                <button
                                    type="button"
                                    class="btn action-btn btn-edit-schedule"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTransferModal"
                                    data-id="<?= $ts['id'] ?>"
                                >
                                    編輯
                                </button>
                                <button
                                    type="button"
                                    class="btn action-btn btn-delete-schedule"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteTransferModal"
                                    data-id="<?= $ts['id'] ?>"
                                    data-event="<?= htmlspecialchars($ts['event']) ?>"
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
<div class="modal fade" id="addTransferModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增轉系時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_transfer.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text" name="date" class="form-control" placeholder="YYYY-MM-DD" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">活動</label>
                        <input type="text" name="event" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text" name="time" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text" name="location" class="form-control" required>
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
<div class="modal fade" id="editTransferModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">編輯轉系時程</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_transfer.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" id="editTransferId">
                    <div class="mb-3">
                        <label class="form-label">日期</label>
                        <input type="text" name="date" class="form-control" id="editTransferDate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">活動</label>
                        <input type="text" name="event" class="form-control" id="editTransferEvent" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">時間</label>
                        <input type="text" name="time" class="form-control" id="editTransferTime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">地點</label>
                        <input type="text" name="location" class="form-control" id="editTransferLocation" required>
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
<div class="modal fade" id="deleteTransferModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">確認刪除</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="process_transfer.php">
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" id="deleteTransferId">
                    <p>確定要刪除「<span id="deleteTransferEvent"></span>」這筆時程嗎？此操作無法復原。</p>
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
// 當「編輯」按鈕被點擊時，透過 AJAX 取得該筆資料並填入 Modal
document.querySelectorAll('[data-bs-target="#editTransferModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        document.getElementById('editTransferId').value = id;

        fetch(`process_transfer.php?action=get&id=${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP 錯誤：' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const ts = data.data;
                    document.getElementById('editTransferDate').value     = ts.date;
                    document.getElementById('editTransferEvent').value    = ts.event;
                    document.getElementById('editTransferTime').value     = ts.time;
                    document.getElementById('editTransferLocation').value = ts.location;
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

// 當「刪除」按鈕被點擊時，填入隱藏欄位並顯示活動名稱
document.querySelectorAll('[data-bs-target="#deleteTransferModal"]').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        const eventName = this.dataset.event;
        document.getElementById('deleteTransferId').value = id;
        document.getElementById('deleteTransferEvent').textContent = eventName;
    });
});

// 若有成功／錯誤訊息，跳出 alert
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
