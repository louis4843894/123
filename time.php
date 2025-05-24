<?php
require_once 'config.php';
require_once 'functions.php';
$pageTitle = '考試與面試時程表';

// 記錄瀏覽歷史
recordPageView('schedule', 'schedule', '考試與面試時程表');

include 'time_header.php';

// 檢查日期是否接近的函數
function isDateApproaching($date, $days = 7) {
    $eventDate = new DateTime($date);
    $today = new DateTime();
    $diff = $today->diff($eventDate);
    return $diff->days <= $days && $diff->invert == 0;
}

try {
    $stmt = $pdo->prepare("
        SELECT date, event, time, location 
        FROM exam_schedule 
        ORDER BY date ASC, time ASC
    ");
    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // 如果資料表不存在，使用預設資料
    $schedules = [
        ['date' => '2025-06-01', 'event' => '考試', 'time' => '09:00 - 11:00', 'location' => '教室 A'],
        ['date' => '2025-06-02', 'event' => '面試', 'time' => '13:00 - 15:00', 'location' => '教室 B'],
        ['date' => '2025-06-03', 'event' => '考試', 'time' => '10:00 - 12:00', 'location' => '教室 C'],
    ];
}

// 獲取即將到來的活動
$upcomingEvents = array_filter($schedules, function($schedule) {
    return isDateApproaching($schedule['date']);
});
?>

<div class="schedule-container">
    <h1 class="text-center mb-4">考試與面試時程表</h1>

    <?php if (!empty($upcomingEvents)): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <h4 class="alert-heading"><i class="bi bi-bell-fill"></i> 重要提醒！</h4>
        <p>以下活動即將到來：</p>
        <ul>
            <?php foreach ($upcomingEvents as $event): ?>
                <li>
                    <strong><?= htmlspecialchars($event['event']) ?></strong> 
                    將於 <?= htmlspecialchars($event['date']) ?> 
                    <?= htmlspecialchars($event['time']) ?> 
                    在 <?= htmlspecialchars($event['location']) ?> 舉行
                </li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-secondary">
                <tr>
                    <th class="text-center">日期</th>
                    <th class="text-center">活動</th>
                    <th class="text-center">時間</th>
                    <th class="text-center">地點</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                <tr class="<?= isDateApproaching($schedule['date']) ? 'table-warning' : '' ?>">
                    <td class="text-center">
                        <?php if (isDateApproaching($schedule['date'])): ?>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock"></i>
                            </span>
                        <?php endif; ?>
                        <?= htmlspecialchars($schedule['date']) ?>
                    </td>
                    <td class="text-center"><?= htmlspecialchars($schedule['event']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($schedule['time']) ?></td>
                    <td class="text-center"><?= htmlspecialchars($schedule['location']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary">返回首頁</a>
    </div>
</div>

<style>
.schedule-container {
    max-width: 800px;
    margin: 0 auto;
    background: white;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 0 12px rgba(0,0,0,0.1);
}

.table {
    margin-top: 2rem;
}

.table th {
    background-color: #6c757d;
    color: white;
}

.btn-primary {
    background-color: #5b78bd;
    border-color: #5b78bd;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
}

.btn-primary:hover {
    background-color: #4a67ac;
    border-color: #4a67ac;
}

.alert {
    margin-bottom: 2rem;
}

.alert ul {
    margin-bottom: 0;
}

.badge {
    margin-right: 0.5rem;
}

.table-warning {
    background-color: #fff3cd !important;
}
</style>

<?php include 'footer.php'; ?>